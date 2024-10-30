<?php
require_once HLOL_US_PLUGIN_DIR . '/inc/template/header.php';

$email     = get_option('hlolead_email');
$token     = get_option('hlolead_token');

?>




<div class="container">
  <div class="row">

    <div class="col-md-12">
      <div class="align_center">
        <a target="_blank" href="https://www.helloleads.io/">
         <img src="<?php echo plugin_dir_url( __FILE__ ) .'../img/HLS_Logo.png'; ?>">
       </a>
      </div>
      <p class="font_size ">To integrate HelloLeads plugin with Contact Form 7, provide the below details from <a target="_blank" href="https://app.helloleads.io/index.php/app/account/login">HelloLeads web app</a> >> settings >> API integration</p>
     <br/><br/>
    </div>

    <div class="col-md-5">
      
      <div class="table_container">

       <form action="javascript:void(0);" id="update_config">
            <div class="form-group">
               <label for="email">Enter Email:</label>
               <input type="email" name="email" class="form-control" id="email" value="<?php if(isset($email) && !empty($email)){echo  esc_attr($email); }?>" <?php if(isset($email) && !empty($email)){echo esc_attr("disabled");}?>>
            </div>
            <div class="form-group">
               <label for="key">Enter Key:</label>
               <input type="text" name="token" class="form-control" id="token" value="<?php if(isset($token) && !empty($token)){echo  esc_attr($token);  }?>" <?php if(isset($token) && !empty($token)){echo esc_attr("disabled");}?>>
            </div>
             <div class="form-group show_error hide" id="show_error">
              <label id="msg_error" class="" for="msg error"></label>
             </div>
            <button type="submit" class="btn btn-primary" id="get_list" <?php if(isset($email) && !empty($email) && isset($token) && !empty($token) ){echo esc_attr("disabled");}?> >Connect</button>
            <button type="reset" class="btn btn-warning" id="reset_config">Reset / Reconfigure</button>
          </form>

       </div>

    </div>

    <div class="col-md-7">
<p class="font_size"><b>Steps to Follow:</b></p>




     <ol class="font_size">
       <li>Log into HelloLeads web app (does not have? <a target="_blank" href="https://app.helloleads.io/index.php/app/account/register">Create an account</a>)</li>
       <li>Provide the registered email id and API key from Settings >> API
Integration</li>
       <li>Choose the list where leads need to be populated</li>
       <li>Use the sample code to create contact form 7</li>
     </ol>
      
    </div>
    
  </div>
  
</div>



<div id="loader">
</div>




<style type="text/css">
  	 #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.75) url("<?php echo plugin_dir_url( __FILE__ ) .'../img/loading.gif'; ?>") no-repeat center center;
            z-index: 10000;
        }
</style>



<?php
require_once HLOL_US_PLUGIN_DIR . '/inc/template/footer.php';
?>
