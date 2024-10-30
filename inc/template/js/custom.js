 /*-------------------------------------------------------------
  |  Intialize datatable
  -------------------------------------------------------------*/

jQuery(function($) {
         

      $('#loader').hide();
        
      /*-------------------------------------------------------------
      |  setting Form validation
      -------------------------------------------------------------*/

      $("#update_config").validate({
          // Specify validation rules
          rules: {
            email: "required",
            token: "required",
            
          },
          messages: {
            email: {
            required: "Please enter email",
           },
           token: {
            required: "Please enter token",
           },      

          },
        
        });

      /*-------------------------------------------------------------
      |  Connect CRM with List
      -------------------------------------------------------------*/

      $("#get_list").click(function(){

         
         var list_key  = '';
         var email     =  $('#email').val();
         var token     =  $('#token').val();
         list_key      =  $('#list_key').val();

        if(email && token){
                 
             $('#loader').show();

             $.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "check_credentials","token":token,"email":email},
                dataType:"json",
                success: function(res) {
                  $('#loader').hide();
                  
                  if(res.status == true){


                     window.location.href= admin_url_page;
                    
                    
                  }else{

                    $("#msg_error").removeAttr('style');
                    $("#msg_error").addClass('error');
                    $("#msg_error").text(res.msg);
                    $(".show_error").removeClass('hide');
                    
                  }
                }
            });

       }

      })


   


      /*-------------------------------------------------------------
      |  Save config cf7 and key List
      -------------------------------------------------------------*/

      $(".sel_box").change(function(){

         var cf7_id      =  $(this).find(":selected").attr('data-id');
         var hls_list_id =  $(this).val();
         var list_name   =  $(this).find(":selected").text();
         var cf_name     =  $(this).parent('td').prev('td').text();

        


        if(cf7_id ){
                 
             $('#loader').show();

             $.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "save_list","cf7_id":cf7_id,"hls_list_id":hls_list_id},
                dataType:"json",
                success: function(res) {
                  $('#loader').hide();
                  
                  if(res.status == true){                   
                    toastr.success(list_name+' '+res.msg+' '+cf_name+' successfully');
                   location.reload();                    
                  }else{

                    $("#msg_error").removeAttr('style');
                    $("#msg_error").addClass('error');
                    $("#msg_error").text(res.msg);
                    $(".show_error").removeClass('hide');
                    
                  }
                }
            });

       }

      })



      /*-------------------------------------------------------------
      |  Delete Mapping List
      -------------------------------------------------------------*/

      $(".del_mapping").click(function(){

        var id = $(this).attr('data-id');
        if(confirm("Are you sure you want to reset details ?")){

          $('#loader').show();

             $.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "del_mapping","p_id":id},
                dataType:"json",
                success: function(res) {
                  
                    $('#loader').hide();
                    if(res.status == true){
                      toastr.success(res.msg);
                      location.reload();
                    }else{
                      toastr.error(res.msg);
                    }
                    
                  
                }
            });
 

        }else{

          return false;
        }

      });


      /*-------------------------------------------------------------
      |  Reset CRM Config
      -------------------------------------------------------------*/

      $("#reset_config").click(function(){

        if(confirm("Are you sure you want to reset details ?")){

          $('#loader').show();

             $.ajax({
                type : "POST",
                url : admin_url,
                data : {action: "reset_crm_config","token":"reset"},
                dataType:"json",
                success: function(res) {
                  
                    $('#loader').hide();
                    toastr.success(res.msg);
                    location.reload();
                  
                }
            });
 

        }else{

          return false;
        }

      });

      $(".view_pdf").click(function(){
        var url = $(this).attr('data-id');
        window.open(url, '_blank');

      });

      



 });    