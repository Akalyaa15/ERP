<div class="tab-content">
    <?php
    $user_id = $this->login_user->id;
    echo form_open(get_uri("team_members/save_my_preferences/"), array("id" => "my-preferences-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('my_preferences'); ?></h4>
        </div>
        <div class="panel-body">
            <!--            <div class="form-group">
                            <label for="show_push_notification" class=" col-md-2"><?php echo lang('show_push_notification'); ?></label>
                            <div class=" col-md-10">
            <?php
            /*
              $push_notification = get_setting('user_' . $user_id . '_show_push_notification');

              if(!$push_notification){
              $push_notification='no';
              }

              echo form_dropdown(
              "show_push_notification", array(
              "yes" => lang("yes"),
              "no" => lang("no")
              ), $push_notification, "class='select2 mini'"
              ); */
            ?>
                            </div>
                        </div>-->
            <div class="form-group">
                <label for="notification_sound_volume" class=" col-md-2"><?php echo lang('notification_sound_volume'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_dropdown(
                            "notification_sound_volume", array(
                        "0" => "-",
                        "1" => "|",
                        "2" => "||",
                        "3" => "|||",
                        "4" => "||||",
                        "5" => "|||||",
                        "6" => "||||||",
                        "7" => "|||||||",
                        "8" => "||||||||",
                        "9" => "|||||||||",
                            ), get_setting('user_' . $user_id . '_notification_sound_volume'), "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="enable_web_notification" class=" col-md-2"><?php echo lang('enable_web_notification'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_dropdown(
                            "enable_web_notification", array(
                        "1" => lang("yes"),
                        "0" => lang("no")
                            ), $user_info->enable_web_notification, "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="enable_email_notification" class=" col-md-2"><?php echo lang('enable_email_notification'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_dropdown(
                            "enable_email_notification", array(
                        "1" => lang("yes"),
                        "0" => lang("no")
                            ), $user_info->enable_email_notification, "class='select2 mini'"
                    );
                    ?>
                </div>
            </div>

            <div class="form-group">
                        <label for="timezone" class=" col-md-2"><?php echo lang('timezone'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "user_timezone", $timezone_dropdown, $user_info->user_timezone, "class='select2 mini'  id='user_timezone'"
                            );
                            ?>
                        </div>
                    </div>

            <?php if (count($language_dropdown) && !get_setting("disable_language_selector_for_team_members")) { ?>
                <div class="form-group">
                    <label for="personal_language" class=" col-md-2"><?php echo lang('language'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_dropdown(
                                "personal_language", $language_dropdown, get_setting('user_' . $user_info->id . '_personal_language') ? get_setting('user_' . $user_info->id . '_personal_language') : get_setting("language"), "class='select2 mini'"
                        );
                        ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <label for="hidden_topbar_menus" class=" col-md-2"><?php echo lang('hide_menus_from_topbar'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "hidden_topbar_menus",
                        "name" => "hidden_topbar_menus",
                        "value" => get_setting('user_' . $user_id . '_hidden_topbar_menus'),
                        "class" => "form-control",
                        "placeholder" => lang('hidden_topbar_menus')
                    ));
                    ?>
                </div>
            </div>
                        <div class="form-group">
                <label for="disable_keyboard_shortcuts" class=" col-md-2"><?php echo lang('disable_keyboard_shortcuts'); ?></label>
                <div class=" col-md-3">
                    <?php
                    $disable_keyboard_shortcuts = get_setting('user_' . $user_id . '_disable_keyboard_shortcuts');
                    $disable_keyboard_shortcuts = $disable_keyboard_shortcuts ? $disable_keyboard_shortcuts : "0";

                    echo form_dropdown(
                            "disable_keyboard_shortcuts", array(
                        "1" => lang("yes"),
                        "0" => lang("no")
                            ), $disable_keyboard_shortcuts, "class='select2 mini'"
                    );

                    echo modal_anchor(get_uri("team_members/keyboard_shortcut_modal_form/"), "<i class='fa fa-info'></i>", array("class" => "btn btn-default keyboard-shortcut-info-icon ml10", "title" => lang('keyboard_shortcuts_info'), "data-post-user_id" => $this->login_user->id));
                    ?>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button id="save_btn" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#my-preferences-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#my-preferences-form .select2").select2();

        $("#hidden_topbar_menus").select2({
            multiple: true,
            data: <?php echo ($hidden_topbar_menus_dropdown); ?>
        });


        //time alert
 $("#user_timezone").change(function () {
           
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
    /*var t =  $("#timezone_result").val(time_result);
    var loginuser_timezone = $("#loginuser_timezone").val(); 
    var country_name = data.geo.country_name+' ('+data.geo.country_code3+')';
    $("#country_name").html(country_name);
    var country_datetime = data.timezone+' ('+data.date_time_wti+')';
    $("#country_timezone").html(country_datetime);
    alert(country_name);*/
    if(time_result == $("#user_timezone").val()){
    
     $("#save_btn").prop("disabled", false).prop('title','');
    
    }else{
     alert("Your Current timezone and the timezone that you choose are different. Kindly, choose this " + a + "as your timezone");
      $("#save_btn").prop("disabled", true).prop('title','Please choose your current timezone');
    }
    
  }
});

        });

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
    /*var t =  $("#timezone_result").val(time_result);
    var loginuser_timezone = $("#loginuser_timezone").val(); 
    var country_name = data.geo.country_name+' ('+data.geo.country_code3+')';
    $("#country_name").html(country_name);
    var country_datetime = data.timezone+' ('+data.date_time_wti+')';
    $("#country_timezone").html(country_datetime);
    alert(country_name);*/
    if(time_result == $("#user_timezone").val()){
    
     $("#save_btn").prop("disabled", false).prop('title','');
    
    }else{
     alert("Your Current timezone and the timezone that you choose are different. Kindly, choose this " + a + "as your timezone");
     $("#save_btn").prop("disabled", true).prop('title','Please choose your current timezone');
    }
    
  }
});

    });
</script>    