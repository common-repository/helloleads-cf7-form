<?php
require_once HLOL_US_PLUGIN_DIR . '/inc/template/header.php';
?>

<?php 

$email     = get_option('hlolead_email');
$token     = get_option('hlolead_token');

global $current_user;

if(isset($lead_list['lists']) && $lead_list['lists'] != null){
  $lead_list = $lead_list['lists'];
}

?>

<?php if(isset($email) && !empty($email) && isset($token) && !empty($token)):?>
      
      <div class="container">
        <div class="row">

          <div class="col-md-12">
            <div class="align_center">
              <a target="_blank" href="https://www.helloleads.io/">
               <img src="<?php echo plugin_dir_url( __FILE__ ) .'../img/HLS_Logo.png'; ?>">
             </a>
            </div>
            <p class="font_size pb-20">Congratulations&nbsp;<b><?php echo esc_html($current_user->user_login); ?> !!!</b>&nbsp;Your Integration with HelloLeads is successfull.</p>
          </div>

          <div class="col-md-5">
            
            <div class="table_container">

             <form action="javascript:void(0);" id="save_config">
                  <div class="form-group">
                     <label for="email">Enter Email:</label>
                     <input type="email" name="email" class="form-control" disabled id="email" value="<?php echo esc_attr($email); ?>">
                  </div>
                  <div class="form-group">
                     <label for="key">Enter Key:</label>
                     <input type="text" name="token" class="form-control" value="<?php echo esc_attr($token); ?>" id="token" disabled>
                  </div>

                  <div class="form-group scroll_tbl">


                    <table class="table table-responsive table-striped mapping_list">
                      <thead class="thead-dark">
                        <tr>
                          <th width="50%">CF7 Name</th>
                          <th width="50%">List Mapped</th>
                        </tr>
                      </thead>
                      
                      <?php //echo"<pre>";print_r($cf7_id_array);?>
                   
                      <?php if(!empty($cf7_id_array)):?>
                             <?php foreach ($cf7_id_array as $cf): ?>

                              <?php $sel_id = ''; 
                                    $cf_svid = get_post_meta($cf['id'],'hlolead_list_key');
                                    if(isset($cf_svid) && !empty($cf_svid[0])){$sel_id = $cf_svid[0]; }
                                   ?>
                               <tr>
                                  <td><?php  echo esc_html($cf['name']);?></td>
                                  <td>
                                    <select class="form-control sel_box" name="hls_list_id<?php echo esc_attr( $cf['id'] );  ?>" id="hls_list_idhls_list_id<?php echo esc_attr($cf['id']);  ?>">
                                     <option value="" data-id="<?php echo esc_attr($cf['id']); ?>">--------Select List --------</option>
                                     <?php if(!empty($lead_list)):?>
                                       <?php foreach ($lead_list as $llist): ?>
                                        <?php if(null == $llist['list_key'] ){continue;}?>
                                        <option <?php if(null != $hls_ides_arr && null !=$llist['list_key'] ){if($cf['id'].$sel_id == $cf['id'].$llist['list_key']){echo esc_attr("selected"); }}?> value="<?php  echo esc_attr( $llist['list_key']); ?>"  data-id="<?php echo esc_attr( $cf['id']); ?>"><?php echo esc_attr( $llist['name']); ?></option>
                                       <?php endforeach;?>
                                     <?php endif; ?>
                                   </select>
                                  </td>
                               </tr>
                             <?php endforeach;?>
                             <?php else:?>
                              <tr><td colspan="2">No CF7 form found, Please create any form</td></tr>
                           <?php endif; ?>
                       
                       </table>

                  </div>

                 

                   <div class="form-group show_error hide" id="show_error">
                    <label id="msg_error" class="" for="msg error"></label>
                   </div>
                 <!--  <button type="submit" class="btn btn-primary" id="save_list">Save</button>
                   -->
                </form>

             </div>

          </div>
          <div class="col-md-1">
            
          </div>

          <div class="col-md-3">
              <div class="table_container">

                  <p style='font-size:16px;'><b>Field name used in contact form 7</b></p>

                  <span  class="view_pdf" target="_blank" href="javascript:void(0)" data-id="<?php echo plugin_dir_url( __FILE__ ) .'../img/field_list_cf7_V1.pdf' ?>"><i class="fa fa-file-pdf-o" aria-hidden="true" style='font-size:48px;color:red'></i></span>

                  <p class="dwn_btn"><span class="view_pdf view_pdf_btn" target="_blank" href="javascript:void(0)" data-id="<?php echo plugin_dir_url( __FILE__ ) .'../img/field_list_cf7_V1.pdf' ?>"><i class="fa fa-download pt-20" aria-hidden="true" style='font-size:48px;'></i></span></p>

                </div>
          </div>

          <div class="col-md-3">
             <div class="table_container">
                <p style='font-size:16px;'><b>Sample contact form 7 code</b></p>
                <span class="view_pdf" target="_blank" href="javascript:void(0)" data-id="<?php echo plugin_dir_url( __FILE__ ) .'../img/sample_code_cf7v2.pdf' ?>"><i class="fa fa-file-pdf-o" aria-hidden="true" style='font-size:48px;color:red'></i></span>
                <p class="dwn_btn"><span class="view_pdf view_pdf_btn" target="_blank" href="javascript:void(0)" data-id="<?php echo plugin_dir_url( __FILE__ ) .'../img/sample_code_cf7v2.pdf' ?>"><i class="fa fa-download pt-20" aria-hidden="true" style='font-size:48px;'></i></span></p>
              </div>
          </div>
          
        </div>



        
      </div> <!-- container -->

    

      <div id="loader">
      </div>




      <style type="text/css">
           #loader {display: none;position: fixed;top: 0;left: 0;right: 0;bottom: 0;width: 100%;background: rgba(0, 0, 0, 0.75) url("<?php echo plugin_dir_url( __FILE__ ) .'../img/loading.gif'; ?>") no-repeat center center;z-index: 10000;}.view_pdf{cursor: pointer;}span.view_pdf.view_pdf_btn {color: cornflowerblue;}

      </style>
    

<?php else:?>
   <meta http-equiv="Refresh" content="0; url=<?php echo  admin_url('admin.php?page=hellolead-config');?>">




<?php endif; ?>

<?php
require_once HLOL_US_PLUGIN_DIR . '/inc/template/footer.php';
?>
