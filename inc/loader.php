<?php



class Hellolead {  

    
   /*---------------------------------------------------------
   |  Construct function for adding hook
   ----------------------------------------------------------*/

    public function __construct() {
      
      // ini_set('display_errors', 1);
      // ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
        
     add_action( 'admin_menu', array($this,'hlo_add_admin_page') );
     
     add_action( 'wp_ajax_nopriv_check_credentials', array($this,'check_credentials') );
     add_action( 'wp_ajax_check_credentials', array($this,'check_credentials' ) );

     add_action( 'wp_ajax_nopriv_save_list', array($this,'save_list') );
     add_action( 'wp_ajax_save_list', array($this,'save_list' ) );

     add_action( 'wp_ajax_nopriv_del_mapping', array($this,'del_mapping') );
     add_action( 'wp_ajax_del_mapping', array($this,'del_mapping' ) );

     add_action( 'wp_ajax_nopriv_reset_crm_config', array($this,'reset_crm_config') );
     add_action( 'wp_ajax_reset_crm_config', array($this,'reset_crm_config' ) );

     add_action("wpcf7_before_send_mail", array($this,"wpcf7_get_posted_data") );

     add_action( 'admin_enqueue_scripts', array($this,'hls_enqueue_admin_style' ) );
    


       
     
 

    }


    /*--------------------------------------------------------
    | add js/css admin
    ----------------------------------------------------------*/


    public  function hls_enqueue_admin_style() {

        wp_register_style( 'dtbl_css', plugin_dir_url( __FILE__ ) . 'template/css/jquery.dataTables.min.css', false, '1.0.0' );
        wp_register_style( 'btstp_css', plugin_dir_url( __FILE__ ). 'template/css/bootstrap.min.css', false, '1.0.0' );
        wp_register_style( 'tstr_css', plugin_dir_url( __FILE__ ) . 'template/css/toastr.css', false, '1.0.0' );
        wp_register_style( 'styl_css', plugin_dir_url( __FILE__ ) . 'template/css/style.css', false, '1.0.0' );
        wp_register_style( 'ftws_css', plugin_dir_url( __FILE__ ) . 'template/css/font-awesome.min.css', false, '1.0.0' );

        wp_register_script( 'js_btstrp', plugin_dir_url( __FILE__ ). 'template/js/bootstrap.min.js', false, '1.0.0' );
        wp_register_script( 'js_dttbl', plugin_dir_url( __FILE__ ) . 'template/js/jquery.dataTables.min.js', false, '1.0.0' );
        wp_register_script( 'js_dtbtn', plugin_dir_url( __FILE__ ) . 'template/js/dataTables.buttons.min.js', false, '1.0.0' );
        wp_register_script( 'js_jszip', plugin_dir_url( __FILE__ ) . 'template/js/jszip.min.js', false, '1.0.0' );
        wp_register_script( 'js_pdfmk', plugin_dir_url( __FILE__ ) . 'template/js/pdfmake.min.js', false, '1.0.0' );
        wp_register_script( 'js_vsfnt', plugin_dir_url( __FILE__ ) . 'template/js/vfs_fonts.js', false, '1.0.0' );
        wp_register_script( 'js_tstr', plugin_dir_url( __FILE__ ) . 'template/js/toastr.js', false, '1.0.0' );
        wp_register_script( 'js_vldt', plugin_dir_url( __FILE__ ) . 'template/js/jquery.validate.js', false, '1.0.0' );
        wp_register_script( 'js_cstm', plugin_dir_url( __FILE__ ) . 'template/js/custom.js', false, '1.0.0' );

        
        wp_enqueue_script( 'js_btstrp' );
        wp_enqueue_script( 'js_dttbl' );
        wp_enqueue_script( 'js_dtbtn' );
        wp_enqueue_script( 'js_jszip' );
        wp_enqueue_script( 'js_pdfmk' );
        wp_enqueue_script( 'js_vsfnt' );
        wp_enqueue_script( 'js_tstr' );
        wp_enqueue_script( 'js_vldt' );
        wp_enqueue_script( 'js_cstm' );
        
        wp_enqueue_style( 'dtbl_css' );
        wp_enqueue_style( 'btstp_css' );
        wp_enqueue_style( 'tstr_css' );
        wp_enqueue_style( 'styl_css' );
        wp_enqueue_style( 'ftws_css' );

      }



    /*---------------------------------------------------------
    |  Create  lead in HLS
    ----------------------------------------------------------*/

     public function wpcf7_get_posted_data($cf7) {

        $wpcf    = WPCF7_ContactForm::get_current();
        $form_id = $cf7->id;
        
        $submission  = WPCF7_Submission::get_instance(); 
        $posted_data = $submission->get_posted_data();

        if(isset($posted_data['interests']) && is_array($posted_data['interests'])){
          if(array_key_exists('interests', $posted_data)){
           $posted_data['interests'] = implode(',', $posted_data['interests']);
         }

        }
        
        if(isset($posted_data['tags']) && is_array($posted_data['tags'])){
           if(array_key_exists('tags', $posted_data)){
             $posted_data['tags'] = implode(',', $posted_data['tags']);
           }
        }

       if(isset($posted_data['category']) && is_array($posted_data['category'])){
         if(array_key_exists('category', $posted_data)){
           $posted_data['category'] = implode(',', $posted_data['category']);
         }
       }


        if(isset($posted_data) && null != $posted_data && null != $form_id){

            $email     = get_option('hlolead_email');
            $token     = get_option('hlolead_token');
            $listid    = get_post_meta($form_id,'hlolead_list_key');
            if(null != $listid){
              $listid = $listid[0];
            }

          
            $posted_data['list_key'] = $listid;
            $data                    = json_encode($posted_data);
            
            $result = $this->create_lead_in_crm($token,$email,$data);

            $response = json_decode($result,true);
            

            if($response['status'] == 'success'){
                 return $wpcf;
            }else{
                return $result;
            }
            
            

        }
       
    }



    /*---------------------------------------------------------
    |  Update List with CF7 config setting
    ----------------------------------------------------------*/

    public function save_list(){

        $data = [];

        if(null != sanitize_text_field($_POST['cf7_id']) && !empty(sanitize_text_field($_POST['cf7_id']) ) ){

            $post_id     = sanitize_text_field($_POST['cf7_id']);
            $list_key_id = sanitize_text_field($_POST['hls_list_id']);
            $list_found  = $this->get_list_name_by_id($list_key_id);

           

              if(metadata_exists('post', $post_id, 'hlolead_list_key')) {
                delete_post_meta($post_id,'hlolead_list_key');
              }
              global $wpdb;
              $tablename  = $wpdb->prefix.'postmeta';
              $list_exist = $wpdb->get_results("SELECT * FROM $tablename WHERE `meta_key` = 'hlolead_list_key'");

              if($list_exist){
                foreach ($list_exist as $key => $list_e) {
                  if($list_e->meta_value == $list_key_id){
                    delete_post_meta($list_e->post_id,'hlolead_list_key');
                    
                  }
                }
              }

              update_post_meta($post_id,'hlolead_list_key',$list_key_id);
           


              $data['status'] = true;
              $data['msg']    = " mapped to ";

        

            

        }else{
                $data['status'] = false;
                $data['msg']    = "Invalid parameters. Please try again.";
        }

        echo json_encode($data);exit;
    }


    /*---------------------------------------------------------
    |  Delete Mapping
    ----------------------------------------------------------*/

    public function del_mapping(){

        $data = [];

        if(null != sanitize_text_field($_POST['p_id'])  && !empty(sanitize_text_field($_POST['p_id']) ) ){

            $post_id = sanitize_text_field($_POST['p_id']);

            delete_post_meta($post_id,'hlolead_list_key');
            $data['status'] = true;
            $data['msg']    = "Mapping deleted successfully";

        }else{
                $data['status'] = false;
                $data['msg']    = "Something went wrong try again.";
        }

        echo json_encode($data);exit;


    }
    
    
    /*---------------------------------------------------------
    |  Reset config setting
    ----------------------------------------------------------*/

    public function reset_crm_config(){

        $data = [];

        if(null != sanitize_text_field($_POST['token'])  && !empty(sanitize_text_field($_POST['token']) ) && sanitize_text_field($_POST['token']) == 'reset'){

            
            $metas   = array( 'hlolead_email'         =>'',
                              'hlolead_token'         =>'', 
                            );

            foreach($metas as $key => $value) {
                delete_option($key);
            }

            $cf7_id_array =  $this->get_all_cf7_forms();

            if(null != $cf7_id_array){
              foreach ($cf7_id_array as $key => $value) {
                delete_post_meta($value['id'],'hlolead_list_key');
              }
            }

            $data['status'] = true;
            $data['msg']    = "Configration has been reset !";

        }else{
                $data['status'] = false;
                $data['msg']    = "Something went wrong try again.";
        }

        echo json_encode($data);exit;


    }



    /*---------------------------------------------------------
    |  Update config setting
    ----------------------------------------------------------*/

    public function check_credentials(){

        $data = [];

        if(null != sanitize_text_field($_POST['token'])  && !empty(sanitize_text_field($_POST['token']) ) && null != sanitize_email($_POST['email'])  && !empty(sanitize_email($_POST['email']) ) ){

            $email       = sanitize_email($_POST['email']);
            $token       = sanitize_text_field($_POST['token']);
            $lists       = $this->get_lead_listead_list($token,$email);
            

            if(array_key_exists('error', $lists)){

                $data['status'] = false;
                $data['msg']    = 'Invalid parameters. Please try again.'; //$lists["message"];

            }else{

                     $metas = array( 
                                      'hlolead_email'         => $email,
                                      'hlolead_token'         => $token, 
                                  );

                      foreach($metas as $key => $value) {
                          update_option($key, $value);
                      }

                      $data['status'] = true;
                      $data['msg']    = "Setting updated successfully.";
                      


            }

                     

            


        }else{

              $data['status'] = false;
              $data['msg']    = "Invalid parameters. Please try again.";
        }

        echo json_encode($data);exit;

    }



   /*---------------------------------------------------------
    |  Plugin add admin menu page
    ----------------------------------------------------------*/

    public function hlo_add_admin_page(){
        $icon_url = plugin_dir_url( __FILE__ ) .'template/img/icon.png';
        add_menu_page( 'HelloLeads CF7', 'HelloLeads CF7', 'manage_options', 'helloleads-config', array($this,'Hellolead_config'),$icon_url);

         $email     = get_option('hlolead_email');
         $token     = get_option('hlolead_token');

         if(null !=$email && !empty($email) && null != $token && !empty($token)):

          add_submenu_page( 'helloleads-config','HelloLeads CF7 List','HelloLeads CF7 List','manage_options','manage-hls-list',array($this,'Manage_hls_list'));
         endif;

    }

    /*---------------------------------------------------------
    |  Admin page callback
    ----------------------------------------------------------*/

    public function Hellolead_config(){

        ob_start();
        require_once HLOL_US_PLUGIN_DIR . '/inc/template/view/config.php';
        $email_content = ob_get_contents();
        ob_end_clean(); 
        echo  html_entity_decode(esc_html($email_content));

    }

    public function Manage_hls_list(){


        $email     = get_option('hlolead_email');
        $token     = get_option('hlolead_token');
        $lead_list = [];

        if(null != $email && null != $token){
          $lead_list = $this->get_lead_listead_list($token,$email);
        }

        $cf7_id_array = $this->get_all_cf7_forms();
       // echo"<pre>";print_r($cf7_id_array);die;

        $saved_list_array = $cf_ides_arr= $hls_ides_arr =[];


        if(isset($cf7_id_array) && null != $cf7_id_array){

          foreach ($cf7_id_array as $key => $value) {

            $list_key    = get_post_meta($value['id'],'hlolead_list_key');
            $list_key    = @isset($list_key)?($list_key !=null)?$list_key[0]:'':'';
            $list_name = $this->get_list_name_by_id($list_key);

            if(null != $list_name){
              $cf_ides_arr[]                       = $value['id'];
              $hls_ides_arr[]                      = $list_key;
              $saved_list_array[$key]['id']        = $value['id'];
              $saved_list_array[$key]['cf_name']   = $value['name'];
              $saved_list_array[$key]['list_name'] = $list_name;
            }else{
              continue;
            }
            


          }

        }

        ob_start();
        require_once HLOL_US_PLUGIN_DIR . '/inc/template/view/hls_list.php';
        $email_content = ob_get_contents();
        ob_end_clean(); 
        echo  html_entity_decode(esc_html($email_content));

    }



    /*--------------------------------------------------------------------------
    | Commom function for CRM
    -------------------------------------------------------------------------*/

    public function get_list_name_by_id($list_key){

         $email     = get_option('hlolead_email');
         $token     = get_option('hlolead_token');
         $data      = $this->get_lead_listead_list($token,$email);
         
         if(!empty($data) ){

            if(isset($data['lists']) && null != $data['lists']){
              foreach ($data['lists'] as $key => $val) {
              if($val['list_key'] == $list_key){
                return  $val['name'];exit;
              }
            }
           
           }
         }
         


    }

    public function get_all_cf7_forms(){
        
         
				 
	        
			

        $cf7_id_array = array();
        
        if (post_type_exists('wpcf7_contact_form')) {
            
            $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
		    $cf7Forms = get_posts( $args );
		    
		    if(null != $cf7Forms){
		        
		        $i = 0;
		        foreach($cf7Forms as $cf7){
		            
		            $cf7_id_array[$i]['id']   = $cf7->ID;
                    $cf7_id_array[$i]['name'] = $cf7->post_title;
                    $i++;
		            
		        }
		    }
		    
        }

        return $cf7_id_array;
    }


    public function get_lead_listead_list($token=null,$xemail=null){

        $res = [];
        $endpoint = HLOL_US_GETLEADLIST_URL;
        $options = [
            'body'        => '',
            'headers'     => [
                    "hls-key"       => "token=$token",
                    "xemail"        => "$xemail"
            ],
            // 'timeout'     => 60,
            // 'redirection' => 5,
            // 'blocking'    => true,
            // 'httpversion' => '1.0',
            // 'sslverify'   => false,
            // 'data_format' => 'body',
        ];
         
        $response = wp_remote_get( $endpoint, $options );

        if(!is_wp_error($response)){
          $res      = $response['body'];
        }
        
        return json_decode($res,true);

    }



    public function create_lead_in_crm($token=null,$xemail=null,$data=null){

        $endpoint = HLOL_US_CREATELEAD_URL;
        $options = [
            'body'        => $data,
            'headers'     => [
                    "cache-control" => "no-cache",
                    "content-type"  => "application/json",
                    "hls-key"       => "token=$token",
                    "xemail"        => "$xemail"
            ],
            // 'timeout'     => 60,
            // 'redirection' => 5,
            // 'blocking'    => true,
            // 'httpversion' => '1.0',
            // 'sslverify'   => false,
            // 'data_format' => 'body',
        ];
         
        $response = wp_remote_post( $endpoint, $options );
        $res      = $response['body'];
        return $res;



    }

    /*-------------------------------------------------*/




}

new Hellolead();
