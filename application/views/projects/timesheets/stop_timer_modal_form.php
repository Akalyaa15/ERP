<?php echo form_open(get_uri("projects/timer/" . $project_id . "/stop"), array("id" => "stop-timer-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="note" class=" col-md-12"><?php echo lang('note'); ?></label>
        <div class=" col-md-12">
            <?php
            echo form_textarea(array(
                "id" => "note",
                "name" => "note",
                "class" => "form-control",
                "placeholder" => lang('note')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="task" class="col-md-12"><?php echo lang('task'); ?>        </label>
        <div class="col-md-12">
            <?php
            echo form_dropdown("task_id", $tasks_dropdown, "", "class='select2 validate-hidden'  data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>
<input type="hidden" name="timezone_result" id="timezone_result" >
<input type="hidden" name="loginuser_timezone" id="loginuser_timezone" value="<?php echo $this->login_user->user_timezone; ?>" />
<input type="hidden" name="loginuser_id" id="loginuser_id" value="<?php echo $this->login_user->id; ?>" />
<script type="text/javascript">
    $(document).ready(function () {
        $("#stop-timer-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        $("#stop-timer-form .select2").select2();
        $("#note").focus();
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
        //alert("different time zone")
        //$("#timezone-button").show();
        //$("#save-and-show-button").hide();
       /* if(confirm("Your current timezone differs from your previously saved timezone. Do you want to change your current timezone as default one?")){
        //$("#delete-button").attr("href", "query.php?ACTION=delete&ID='1'");
      var loginuser_timezone = $("#timezone_result").val();
      var loginuser_id = $("#loginuser_id").val();
$.ajax({
                    url: "<?php /*echo get_uri('attendance/update_user_timezone')*/ ?>",
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
