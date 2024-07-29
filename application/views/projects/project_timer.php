<?php

if ($timer_status === "open") {
    echo modal_anchor(get_uri("projects/stop_timer_modal_form/". $project_info->id),  "<i class='fa fa fa-clock-o'></i> " . lang('stop_timer'), array("class" => "btn btn-danger", "title" => lang('stop_timer')));
} else {
    /*echo ajax_anchor(get_uri("projects/timer/" . $project_info->id . "/start"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "data-reload-on-success" => "1"));*/

          if (!$this->login_user->is_admin&&$this->login_user->work_mode=='0') {
  
            $ip = get_real_ip();
           
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
             
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) {
         echo modal_anchor(get_uri("attendance/forbid"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "id"=>"timecard-clock-out"));
                }else{
                      echo ajax_anchor(get_uri("projects/timer/" . $project_info->id . "/start"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "data-reload-on-success" => "1"));

                }
        
           }else{
                                            echo ajax_anchor(get_uri("projects/timer/" . $project_info->id . "/start"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "data-reload-on-success" => "1"));

           }     
         }else{
                                echo ajax_anchor(get_uri("projects/timer/" . $project_info->id . "/start"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "data-reload-on-success" => "1"));

         }
        
//     $options = array(
//             "user_id" => $this->login_user->id,
//             "status" => 'open',
            
//         );

//        $timer_info = $this->Timesheets_model->get_details($options)->row();
//        if($this->login_user->work_mode==1||$this->login_user->is_admin){


//        if(!$timer_info){ 
//     echo ajax_anchor(get_uri("projects/timer/" . $project_info->id . "/start"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "data-reload-on-success" => "1"));
// }
// }else{
//          echo modal_anchor(get_uri("attendance/forbid"), "<i class='fa fa fa-clock-o'></i> " . lang('start_timer'), array("class" => "btn btn-info", "title" => lang('start_timer'), "id"=>"timecard-clock-out"));

// }
}
?>
<?php if ($this->login_user->user_type === "staff" || $this->login_user->user_type === "resource") { ?>
<input type="hidden" name="timezone_result" id="timezone_result" >
<input type="hidden" name="loginuser_timezone" id="loginuser_timezone" value="<?php echo $this->login_user->user_timezone; ?>" />
<input type="hidden" name="loginuser_id" id="loginuser_id" value="<?php echo $this->login_user->id; ?>" />
<script type="text/javascript">
	 $(document).ready(function () {
	//uer timezone 
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
    if(time_result == loginuser_timezone){
    /*alert(a);
    alert(time_result);*/
    }else{
        
        /*if(confirm("Your current timezone differs from your previously saved timezone. Do you want to change your current timezone as default one?")){
        //$("#delete-button").attr("href", "query.php?ACTION=delete&ID='1'");
      var loginuser_timezone = $("#timezone_result").val();
      var loginuser_id = $("#loginuser_id").val();
$.ajax({
                    url: "<?php /* echo get_uri('attendance/update_user_timezone') */?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {login_user_id: loginuser_id, login_user_timezone: loginuser_timezone},
                    success: function (result) {
                        if (result.success) {
                           // $("#event-calendar").fullCalendar('refetchEvents');
                           appAlert.warning(result.message, {duration: 10000});
                          // $("#check_status").show();
                           //$("#timezone-button").hide();
                        } else {
                            appAlert.error(result.message);
                        }
                    }
                });
    }
    else{
         return false;
    }*/
    window.location = "<?php echo site_url('timezone_update'); ?>"; 
         
    }
    
  }
});
        });
	</script>
	<?php } ?>