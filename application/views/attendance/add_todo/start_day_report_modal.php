<?php
$url = "attendance/save_sor";

if ($clock_in == "1") {
    $url = "attendance/log_sor_time";
}
echo form_open(get_uri($url), array("id" => "attendance-sor-form", "class" => "general-form", "role" => "form"));
?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="note" class=" col-md-12">Note (Enable location mode in your device to clock in)</label>
        <div class=" col-md-12">
            <?php
            echo form_textarea(array(
                "id" => "sor_note",
                "name" => "sor_note",
                "class" => "form-control",
                "placeholder" => lang('Enable Location in order to clock in'),
                "value" => $model_info->sor_note,
                "readonly"=>true
  ));
            ?>
        </div>
        <input name="clock_in" type="hidden" value="<?php echo $clock_in; ?>" />
    </div>
</div>
<div class="modal-footer">
<div id="link-of-task-view" class="hide">
            <?php
            echo modal_anchor(get_uri("attendance/todo_view"), "", array());
            ?>
        </div>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button id="save-and-show-button"  disabled type="button" class="btn btn-primary" title="Please enable location mode in your device to clock in" ><span class="fa fa-check-circle"></span> <?php echo lang('add_todo'); ?></button>
    <!--button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button-->
</div><input type="hidden" name="result" id="result">
<input type="hidden" name="timezone_result" id="timezone_result" >
<input type="hidden" name="loginuser_timezone" id="loginuser_timezone" value="<?php echo $this->login_user->user_timezone; ?>" />
<input type="hidden" name="loginuser_id" id="loginuser_id" value="<?php echo $this->login_user->id; ?>" />

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
      /* $("#attendance-sor-form").appForm({
            onSuccess: function (result) {
                if (result.clock_widget) {
                   $("#timecard-clock-in").closest("#js-clock-in-out").html(result.clock_widget);
                } else {
                    if (result.isUpdate) {
                        $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
                    } else {
                        $(".dataTable:visible").appTable({reload: true});
                    }
                }
            }
        });  */
window.showAddNewModal = false;
$("#save-and-show-button").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");

        });

        var taskInfoText = "<?php echo lang('add_todo') ?>";

window.taskForm = $("#attendance-sor-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
               //$("#monthly-loan-table").appTable({newData: result.data, dataId: result.id});
                //$("#reload-kanban-button").trigger("click");
if (result.clock_widget) {
                   $("#timecard-clock-in").closest("#js-clock-in-out").html(result.clock_widget);
                } else {
                    if (result.isUpdate) {
                        $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
                    } else {
                        $(".dataTable:visible").appTable({reload: true});
                    }
                }
                $("#save_and_show_value").append(result.save_and_show_link);

                if (window.showAddNewModal) {
                    var $taskViewLink = $("#link-of-task-view").find("a");
                    $taskViewLink.attr("data-title", taskInfoText + " #" + result.id);
                    $taskViewLink.attr("data-post-id", result.id);

                    $taskViewLink.trigger("click");
                } else {
                    window.taskForm.closeModal();
                }
            }
        }); 

    $("#note").focus();
    });
        
        
delay().then(function(response) {
 $.ajax({
  url: 'https://reverse.geocoder.api.here.com/6.2/reversegeocode.json',
  type: 'GET',
  dataType: 'json',
  jsonp: 'jsoncallback',
  data: {
    prox: $("#result").val(),
    mode: 'retrieveAddresses',
    maxresults: '1',
    gen: '9',
    app_id: 'luqHzxdDgQRXukjNkhmd',
    app_code: 'rJm0dYRyV5Cpchlv9SJWzQ'
  },
  success: function (data) {
    var a=JSON.stringify(data.Response.View[0].Result[0].Location.Address.Label);
    var b=$('#result').val()

    $('#result').val(a)
    $('#sor_note').append("\n"+"Clock in Location : "+a+"\n"+"Clock in - Lat, Long : "+b)
    $("#save-and-show-button").removeAttr("disabled").prop('title',"");

  }
}); 

//get the current user time zone 
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
function delay() {
  return new Promise(function(resolve,reject) {
         if ("geolocation" in navigator){ //check geolocation available 
        //try to get user current location using getCurrentPosition() method
        navigator.geolocation.getCurrentPosition(function(position){ 
                $("#result").val(position.coords.latitude+","+ position.coords.longitude);
resolve('success');            });
    }else{
        console.log("Browser doesn't support geolocation!");
    } 

  });
}
</script>
<script type="text/javascript">
    $(document).on("click","#save-and-show-button",function() {
    $("#save-and-show-button").attr('disabled', 'true');
});
</script>