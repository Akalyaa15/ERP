<?php 
$uri = ''; 
echo "<link id='custom-theme-color' class='custom-theme-color' rel='stylesheet'  type='text/css' href='" . base_url($uri) .'assets/css/color/'.$this->login_user->theme_color."' />";
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="fa fa-chevron-down"></span>
        </button>
        <button id="sidebar-toggle" type="button" class="navbar-toggle"  data-target="#sidebar">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
        </button>

        <?php
        $user = $this->login_user->id;
        $dashboard_link = get_uri("dashboard");
        $user_dashboard = get_setting("user_" . $this->login_user->id . "_dashboard");
        if ($user_dashboard) {
            $dashboard_link = get_uri("dashboard/view/" . $user_dashboard);
        }
        ?>

        <a class="navbar-brand" href="<?php echo $dashboard_link; ?>"><img class="dashboard-image" src="<?php echo get_logo_url(); ?>" /></a>


    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
            <li class="hidden-xs pl15 pr15  b-l">
                <button class="hidden-xs" id="sidebar-toggle-md">
                    <span class="fa fa-dedent"></span>
                </button>
            </li>

            <?php /* $this->load->view("todo/topbar_icon"); ?>

            <?php $this->load->view("projects/star/topbar_icon"); ?>
            <?php $this->load->view("clients/star/topbar_icon"); ?>
              <?php $this->load->view("vendors/star/topbar_icon"); ?>
              <?php $this->load->view("companys/star/topbar_icon"); ?>
            <?php $this->load->view("dashboards/list/topbar_icon"); */?>

            <?php 
            //get the array of hidden topbar menus
            $hidden_topbar_menus = explode(",", get_setting("user_" . $user . "_hidden_topbar_menus"));
            if (!in_array("to_do", $hidden_topbar_menus)) {
                $this->load->view("todo/topbar_icon");
            }
            
            if (!in_array("favorite_projects", $hidden_topbar_menus) && !(get_setting("disable_access_favorite_project_option_for_clients") && $this->login_user->user_type == "client")) {
            $this->load->view("projects/star/topbar_icon");
            } 
            
            if (!in_array("favorite_clients", $hidden_topbar_menus)) {
             $this->load->view("clients/star/topbar_icon");
            } 
            
            if (!in_array("favorite_vendors", $hidden_topbar_menus)) {
               $this->load->view("vendors/star/topbar_icon");
            }          
            
            if (!in_array("favorite_companys", $hidden_topbar_menus)) {
               $this->load->view("companys/star/topbar_icon");
            } 
               
            if (!in_array("dashboard_customization", $hidden_topbar_menus) && (get_setting("disable_new_dashboard_icon") != 1)) {
             $this->load->view("dashboards/list/topbar_icon"); 
            }

            ?>

            <?php echo my_open_timers(true); ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">


<?php
            if ($this->login_user->user_type == "staff"||$this->login_user->user_type == "resource") {
                load_js(array("assets/js/awesomplete/awesomplete.min.js"));
                ?>
                <li class="hidden-xs" title="<?php echo lang('search') . ' (/)'; ?>" data-toggle="tooltip" data-placement="left">
                    <?php echo modal_anchor(get_uri("search/search_modal_form"), "<i class='fa fa-search'></i>", array("class" => "pull-left", "data-modal-title" => lang('search'), "data-post-hide-header" => true, "id" => "global-search-btn")); ?>
                </li>
            <?php } ?>
                        <?php
            if (!in_array("quick_add", $hidden_topbar_menus)) {
                $this->load->view("settings/topbar_parts/quick_add");
            }
            ?>
            <?php if (!in_array("language", $hidden_topbar_menus) && (($this->login_user->user_type == "staff" && !get_setting("disable_language_selector_for_team_members")) || ($this->login_user->user_type == "client" && !get_setting("disable_language_selector_for_clients"))|| ($this->login_user->user_type == "vendor" && !get_setting("disable_language_selector_for_clients")))) { ?>

                <li class="user-language-option">
                    <?php echo js_anchor("<i class='fa fa-globe'></i>", array("id" => "personal-language-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>

                    <ul class="dropdown-menu p0" style="height: 300px; overflow-y: scroll; min-width: 170px;">
                        <li>
                            <?php
                            $user_language = get_setting("user_" . $this->login_user->id . "_personal_language");
                            $system_language = get_setting("language");

                            foreach (get_language_list() as $language) {
                                $language_status = "";
                                $language_text = $language;

                                if ($user_language == strtolower($language) || (!$user_language && $system_language == strtolower($language))) {
                                    $language_status = "<span class='pull-right checkbox-checked m0'></span>";
                                    $language_text = "<strong class='pull-left'>" . $language . "</strong>";
                                }

                                if ($this->login_user->user_type == "staff") {
                                    echo ajax_anchor(get_uri("team_members/save_personal_language/$language"), $language_text . $language_status, array("class" => "clearfix", "data-reload-on-success" => "1"));
                                } else if($this->login_user->user_type == "client") {
                                    echo ajax_anchor(get_uri("clients/save_personal_language/$language"), $language_text . $language_status, array("class" => "clearfix", "data-reload-on-success" => "1"));
                                }
                                else if ($this->login_user->user_type == "vendor") {
                                    echo ajax_anchor(get_uri("vendors/save_personal_language/$language"), $language_text . $language_status, array("class" => "clearfix", "data-reload-on-success" => "1"));
                                }
                            }
                            ?>
                        </li>
                    </ul>
                </li>

            <?php } ?>

            <li class="">
                <?php echo js_anchor("<i class='fa fa-bell-o'></i>", array("id" => "web-notification-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>
                <div class="dropdown-menu aside-xl m0 p0 font-100p" style="min-width: 400px;" >
                    <div class="dropdown-details panel bg-white m0">
                        <div class="list-group">
                            <span class="list-group-item inline-loader p10"></span>                          
                        </div>
                    </div>
                    <div class="panel-footer text-sm text-center">
                        <?php echo anchor("notifications", lang('see_all')); ?>
                    </div>
                </div>
            </li>

            <?php if (get_setting("module_message")) { ?>
                <li class=" <?php echo ($this->login_user->user_type === "client" && !get_setting("client_message_users")) ? "hide" : ""; ?>">
                    <?php echo js_anchor("<i class='fa fa-envelope-o'></i>", array("id" => "message-notification-icon", "class" => "dropdown-toggle", "data-toggle" => "dropdown")); ?>
                    <div class="dropdown-menu aside-xl m0 p0 w300 font-100p">
                        <div class="dropdown-details panel bg-white m0">
                            <div class="list-group">
                                <span class="list-group-item inline-loader p10"></span>                          
                            </div>
                        </div>
                        <div class="panel-footer text-sm text-center">
                            <?php echo anchor("messages", lang('see_all')); ?>
                        </div>
                    </div>
                </li>
                <li class=" <?php echo ($this->login_user->user_type === "client" && !get_setting("client_message_users")) ? "hide" : ""; ?>">
                   
                    <a id="g_message-notification-icon" href="<?php echo_uri('messages/groups_items'); ?>"><i class='fa fa-users'></i></a>
                    <div class="dropdown-menu aside-xl m0 p0 w300 font-100p">
                        <div class="dropdown-details panel bg-white m0">
                            <div class="list-group">
                                <span class="list-group-item inline-loader p10"></span>                          
                            </div>
                        </div>
                        <div class="panel-footer text-sm text-center">
                            <?php echo anchor("messages", lang('see_all')); ?>
                        </div>
                    </div>
                </li>
                            <?php } ?>

            <li class="dropdown pr15 dropdown-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <span class="avatar-xs avatar pull-left mt-5 mr10" >
                        <img alt="..." src="<?php echo get_avatar($this->login_user->image); ?>">
                    </span><?php echo $this->login_user->first_name . " " . $this->login_user->last_name; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu p0" role="menu">
                    <?php if ($this->login_user->user_type == "client") { ?>
                        <li><?php echo get_client_contact_profile_link($this->login_user->id . '/general', "<i class='fa fa-user mr10'></i>" . lang('my_profile')); ?></li>
                        <li><?php echo get_client_contact_profile_link($this->login_user->id . '/account', "<i class='fa fa-key mr10'></i>" . lang('change_password')); ?></li>
                        <li><?php echo get_client_contact_profile_link($this->login_user->id . '/my_preferences', "<i class='fa fa-cog mr10'></i>" . lang('my_preferences')); ?></li>
                        <?php } else if($this->login_user->user_type == "resource"){ ?>
                        <li><?php echo get_rm_member_profile_link($this->login_user->id . '/general', "<i class='fa fa-user mr10'></i>" . lang('my_profile')); ?></li>
                        <li><?php echo get_rm_member_profile_link($this->login_user->id . '/account', "<i class='fa fa-key mr10'></i>" . lang('change_password')); ?></li>
                        <li><?php echo get_rm_member_profile_link($this->login_user->id . '/my_preferences', "<i class='fa fa-cog mr10'></i>" . lang('my_preferences')); ?></li>
                        <?php } else if ($this->login_user->user_type == "vendor") { ?>
                        <li><?php echo get_vendor_contact_profile_link($this->login_user->id . '/general', "<i class='fa fa-user mr10'></i>" . lang('my_profile')); ?></li>
                        <li><?php echo get_vendor_contact_profile_link($this->login_user->id . '/account', "<i class='fa fa-key mr10'></i>" . lang('change_password')); ?></li>
                        <li><?php echo get_vendor_contact_profile_link($this->login_user->id . '/my_preferences', "<i class='fa fa-cog mr10'></i>" . lang('my_preferences')); ?></li>
                                       <?php } else { ?>
                        <li><?php echo get_team_member_profile_link($this->login_user->id . '/general', "<i class='fa fa-user mr10'></i>" . lang('my_profile')); ?></li>
                        <li><?php echo get_team_member_profile_link($this->login_user->id . '/account', "<i class='fa fa-key mr10'></i>" . lang('change_password')); ?></li>
                        <li><?php echo get_team_member_profile_link($this->login_user->id . '/my_preferences', "<i class='fa fa-cog mr10'></i>" . lang('my_preferences')); ?></li>
                    <?php } ?>
                    
                    <li class="divider theme-changer-devider"></li>    
                    <li class="pl10 ml10  mt10 theme-changer">

                        <?php
//scan the css files for theme color and show a list
                        try {
                            $dir = getcwd() . '/assets/css/color/';
                            $files = scandir($dir);
                            if ($files && is_array($files)) {

                                echo "<span class='color-tag clickable mr15 change-theme' style='background:#2874a6'> </span>"; //default color #1d2632

                                foreach ($files as $file) {
                                    if ($file != "." && $file != ".." && $file != "index.html") {
                                        $color_colde = str_replace(".css", "", $file);
                                        echo "<span class='color-tag clickable mr15 change-theme' style='background:#$color_colde' data-color='$color_colde'> </span>";
                                    }
                                }
                            }
                        } catch (Exception $exc) {
                            
                        }
                        ?>

                    </li>

                    <li class="divider"></li>
                    <li><a href="<?php echo_uri('signin/sign_out'); ?>"><i class="fa fa-power-off mr10"></i> <?php echo lang('sign_out'); ?></a></li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
</nav>

<script type="text/javascript">
    $(document).ready(function () {

        //load notification sound
        $("<audio></audio>").attr({
            'src': '<?php echo get_file_uri(get_setting("system_file_path") . "notification.mp3"); ?>',
            'id': 'notificationPlayer',
            'type': 'audio/mpeg'
        }).appendTo("body");

        //load message notifications
        var messageOptions = {},
                notificationOptions = {},
                g_messageOptions = {},
                $messageIcon = $("#message-notification-icon"),
                $notificationIcon = $("#web-notification-icon");
                $g_messageIcon = $("#g_message-notification-icon"),

        //check message notifications
        messageOptions.notificationUrl = "<?php echo_uri('messages/get_notifications'); ?>";
        messageOptions.notificationStatusUpdateUrl = "<?php echo_uri('messages/update_notification_checking_status'); ?>";
        messageOptions.checkNotificationAfterEvery = "<?php echo get_setting('check_notification_after_every'); ?>";
        messageOptions.icon = "fa-envelope-o";
        messageOptions.notificationSelector = $messageIcon;
        messageOptions.isMessageNotification = true;
        checkNotifications(messageOptions);

        window.updateLastMessageCheckingStatus = function () {
            checkNotifications(messageOptions, true);
        }

        $messageIcon.click(function () {
            checkNotifications(messageOptions, true);
        });

        //check message notifications
        g_messageOptions.notificationUrl = "<?php echo_uri('messages/get_g_notifications'); ?>";
        g_messageOptions.notificationStatusUpdateUrl = "<?php echo_uri('messages/update_notification_checking_statuss'); ?>";
        g_messageOptions.checkNotificationAfterEvery = "<?php echo get_setting('check_notification_after_every'); ?>";
        g_messageOptions.icon = "fa fa-users";
        g_messageOptions.notificationSelector = $g_messageIcon;
        g_messageOptions.isMessageNotification = true;
     g_messageOptions.chatread = true;
     checkNotifications(g_messageOptions);

        window.updateLastMessageCheckingStatus = function () {
            checkNotifications(g_messageOptions, true);
        }

        $g_messageIcon.click(function () {
            checkNotifications(g_messageOptions, true);
        });


        //check web notifications
        notificationOptions.notificationUrl = "<?php echo_uri('notifications/count_notifications'); ?>";
        notificationOptions.notificationStatusUpdateUrl = "<?php echo_uri('notifications/update_notification_checking_status'); ?>";
        notificationOptions.checkNotificationAfterEvery = "<?php echo get_setting('check_notification_after_every'); ?>";
        notificationOptions.icon = "fa-bell-o";
        notificationOptions.notificationSelector = $notificationIcon;
        notificationOptions.notificationType = "web";

        checkNotifications(notificationOptions); //start checking notification after starting the message checking 


        $notificationIcon.click(function () {
            notificationOptions.notificationUrl = "<?php echo_uri('notifications/get_notifications'); ?>";
            checkNotifications(notificationOptions, true);
        });
    });
</script>
<script type="text/javascript">
        $(".change-theme").click(function () {  

       $.ajax({ 
        
                    url: "<?php echo get_uri("team_members/save_theme_color"); ?>",
                    data: {theme_color: $(this).attr("data-color") },
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) { 
                           location.reload()
                        }
                    }
                });

    });
</script>