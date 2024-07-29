<?php

$user_theme =str_replace(".css","",$this->login_user->theme_color);

 ?>
<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo "Timezone update"; ?></h1>
            
        </div>
        <div class="panel-body">
            <div id="ticket-title-section">
               <!--  <?php $this->load->view("tickets/ticket_sub_title"); ?> -->
              <!--  <img class="dashboard-image" src="<?php echo get_logo_url(); ?>" /> -->
            </div>

<!-- <h4><strong><?php echo "Confirm your location"; ?> </strong></h4>
<div style="line-height: 3px;"> </div> -->

<!-- <span >
   <div>
 <p style="font-size: 16px"><?php echo 'As a measure to keep your data up to date , please confirm your location details before proceeding.' ; ?></p>


        </div>

</span> -->
<div style="line-height: 3px;"> </div>
<div class ="row">
<div class="col-md-8">
     <img class="dashboard-image" src="<?php echo get_logo_url(); ?>" />
    <h4><strong><?php echo "Confirm your location"; ?> </strong></h4>
<div style="line-height: 3px;"> </div>
    <span >
   <div>
 <p style="font-size: 16px"><?php echo 'As a measure to keep your data up to date , please confirm your location details before proceeding.' ; ?></p>


        </div>

</span>
             <blockquote class="font-14 text-justify" style="<?php echo "border-color:" .'#'.$user_theme; ?>" >
                <div class="pb10 pt10 ">
                <i class="fa fa-globe"></i>
                <span id="country_name"></span> 
            </div>
            <div class="pb10 pt10 ">
                <i class="fa fa-clock-o"></i>
                <span id="country_timezone"></span> 
            </div>
        </blockquote>
        </div>

        <div class="col-md-4">
    <img src="<?php echo get_file_uri(get_setting("system_file_path") . 'timezone.jpeg')?>"  style="  height:250px;opacity: 0.6;
"> 
           
       
        </div>

    </div>

            

            <div id="comment-form-container" >
                <?php echo form_open(get_uri("timezone_update/update_user_timezone"), array("id" => "comment-form", "class" => "general-form", "role" => "form")); ?>
                <div class="p15 box">
                    <div class="box-content avatar avatar-md pr15">
                        <img src="<?php echo get_avatar($this->login_user->image); ?>" alt="..." />
                    </div>

                    <div>
                        
                        <input type="hidden" name="timezone_result" id="timezone_result" >
<input type="hidden" name="loginuser_timezone" id="loginuser_timezone" value="<?php echo $this->login_user->user_timezone; ?>" />
<input type="hidden" name="loginuser_id" id="loginuser_id" value="<?php echo $this->login_user->id; ?>" />
                        
                       <!--  <footer class="panel-footer b-a clearfix ">
                            
                            <button class="btn btn-primary pull-left btn-sm " type="submit"><i class='fa fa-paper-plane'></i> <?php echo lang("save"); ?></button>
                             <button class="btn btn-primary pull-left btn-sm " type="submit"><i class='fa fa-paper-plane'></i> <?php echo lang("save"); ?></button>
                        </footer> -->
                        <div class="modal-footer">
    <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button> -->
     <a id="go_back" style="display: none;" class="btn btn-primary pull-left" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <button id="confirm_btn" type="submit" class="btn btn-primary pull-left"><span class="fa fa-check-circle"></span> <?php echo lang('confirm'); ?></button>
</div>
                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>

           


        </div>
    </div>
</div>


 <script type="text/javascript">
    $(document).ready(function () {
        
        $.ajax({
  url: 'https://api.ipgeolocation.io/timezone',
  type: 'GET',
  dataType: 'json',
  jsonp: 'jsoncallback',
  data: {
    //lat: $("#lat").val(),
    //long: $("#long").val(),
    apiKey: 'a7d92fa6b4944eae924a980892a6e6a4'
      },
   success: function (data) {
    var a=JSON.stringify(data.timezone);
    var time_result = data.timezone;
    var t =  $("#timezone_result").val(time_result);
    var loginuser_timezone = $("#loginuser_timezone").val(); 
    var country_name = data.geo.country_name+' ('+data.geo.country_code3+')';
    $("#country_name").html(country_name);
    var country_datetime = data.timezone+' ('+data.date_time_wti+')';
    $("#country_timezone").html(country_datetime);
    //alert(country_name);
    if(time_result == loginuser_timezone){
    /*alert(a);
    alert(time_result);*/
    $("#go_back").show(); 
    $("#confirm_btn").hide(); 
    }else{
    $("#go_back").hide(); 
    $("#confirm_btn").show(); 
    }
    
  }
});

         $("#comment-form").appForm({
            isModal: false,
            onSuccess: function (result) {
               // $("#description").val("");

               

                appAlert.success(result.message, {duration: 10000});
                 $("#go_back").show(); 
                $("#confirm_btn").hide();
                
            }
        });

    });
</script>
 