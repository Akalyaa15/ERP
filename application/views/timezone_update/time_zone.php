<!DOCTYPE html>
<html lang="en">
<head>
  <title>Timezone Update </title>
  <?php $this->load->view('includes/head'); ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style type="text/css">
	@media only screen and (min-width: 768px) {
#mobile {
    display: block;
  }
</style>
</head>
<body>
  <div class="row">
    <div class="col" style="padding-right: 5%;padding-left: 10%;">
<div class="container p-3 my-3 " >
<!-- 	<img src="http://localhost/ddd.png" style="height: 60px;"> -->
  <img class="dashboard-image" src="<?php echo get_logo_url(); ?>" />

  <h4>Confirm your location</h4>
  <p>As a measure to keep your data upto date, please confirm your location details before proceeding.</p>
</div>

<div class="container p-3 my-3 border" style="background-color: #cedcbba8">
  <p><i class="fa fa-globe" aria-hidden="true"></i> <span id="country_name"></span> 
</p>
  <p><i class="fa fa-clock-o" aria-hidden="true"></i> <span id="country_timezone"></span> 
</p>
</div>
<div class="container p-3 my-3 ">
  <a id="go_back"  class="btn btn-primary " href="javascript:window.history.go(-1);">❮ Go Back</a>
   <button id="timezone-button" type="button" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('confirm'); ?></button>
 <!--  <button type="button" class="btn">CHANGE</button> -->
</div>    </div>
    <div class="col p-3 my-3" id="mobile">
<img src="<?php echo get_file_uri(get_setting("system_file_path") . 'timezone.jpeg')?>"  style="  height:400px;opacity: 0.6;
">    </div>
  </div>

</body>
</html>
<input type="hidden" name="timezone_result" id="timezone_result" >
<input type="hidden" name="loginuser_timezone" id="loginuser_timezone" value="<?php echo $this->login_user->user_timezone; ?>" />
<input type="hidden" name="loginuser_id" id="loginuser_id" value="<?php echo $this->login_user->id; ?>" />
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
    if(time_result == loginuser_timezone){
    /*alert(a);
    alert(time_result);*/
    $("#timezone-button").hide();
    }else{
        $("#timezone-button").show();
       //window.location = "<?php echo site_url('timezone_update'); ?>"; 
         //$("#check_status").removeAttr("disabled");

    }

    
  }
});
  });
</script>
<script>
$("#timezone-button").click(function(){
    
        //$("#delete-button").attr("href", "query.php?ACTION=delete&ID='1'");
      var loginuser_timezone = $("#timezone_result").val();
      var loginuser_id = $("#loginuser_id").val();
$.ajax({
                    url: "<?php echo get_uri('attendance/update_user_timezone') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {login_user_id: loginuser_id, login_user_timezone: loginuser_timezone},
                    success: function (result) {
                        if (result.success) {
                           // $("#event-calendar").fullCalendar('refetchEvents');
                           appAlert.warning(result.message, {duration: 10000});
                          
                           $("#timezone-button").hide();
                        } else {
                            appAlert.error(result.message);
                        }
                    }
                });
    
    
});
</script>
