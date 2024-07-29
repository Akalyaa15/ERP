<div class="tab-content">
    <?php
    $url = "team_members";
    if ($user_info->user_type === "client") {
        $url = "clients_register";
    }
    if ($user_info->user_type === "resource") {
        $url = "rm_members";
    }
    if ($user_info->user_type === "vendor") {
        $url = "vendors_register";
    }
    echo form_open(get_uri($url . "/save_account_settings/" . $user_info->id), array("id" => "account-info-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('account_settings'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="email" class=" col-md-2"><?php echo lang('email'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "value" => $user_info->email,
                        "class" => "form-control",
                        "placeholder" => lang('email'),
                        "autocomplete" => "off",
                        "data-rule-email" => true,
                        "data-msg-email" => lang("enter_valid_email"),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class=" col-md-2"><?php echo lang('password'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_password(array(
                        "id" => "password",
                        "name" => "password",
                        "class" => "form-control",
                        "placeholder" => lang('password'),
                        "autocomplete" => "off",
                        "data-rule-minlength" => 6,
                        "data-msg-minlength" => lang("enter_minimum_6_characters"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="retype_password" class=" col-md-2"><?php echo lang('retype_password'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_password(array(
                        "id" => "retype_password",
                        "name" => "retype_password",
                        "class" => "form-control",
                        "placeholder" => lang('retype_password'),
                        "autocomplete" => "off",
                        "data-rule-equalTo" => "#password",
                        "data-msg-equalTo" => lang("enter_same_value")
                    ));
                    ?>
                </div>
            </div>
<?php if ($this->login_user->is_admin && $user_info->id !== $this->login_user->id) { ?>
            <div class="form-group">
                <label for="work_mode" class=" col-md-2"><?php echo lang('work_mode'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_radio(array(
                        "id" => "indoor",
                        "name" => "work_mode",
                        "data-msg-required" => lang("field_required"),
                            ), "0", ($user_info->work_mode === "1") ? false : true);
                    ?>
                    <label for="indoor" class="mr15"><?php echo lang('indoor'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "outdoor",
                        "name" => "work_mode",
                        "data-msg-required" => lang("field_required"),
                            ), "1", ($user_info->work_mode === "1") ? true : false);
                    ?>
                    <label for="gender_female" class=""><?php echo lang('outdoor'); ?></label>
                </div>
            </div>
<?php } ?>
            <?php if ($user_info->user_type === "staff"||$user_info->user_type === "resource" && $this->login_user->is_admin) { ?>
                <div class="form-group">
                    <label for="role" class=" col-md-2"><?php echo lang('role'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        if ($this->login_user->id == $user_info->id) { ?>
<?php if(!$this->login_user->is_admin){ ?>     
<div class='ml15'><?php echo $this->login_user->role_title; ?></div>                     
<?php } ?>
<?php if($this->login_user->is_admin){ ?>     
<div class='ml15'><?php echo lang("admin") ;?></div>                     

<div id="user-role-help-block" class="help-block ml10 <?php echo $user_info->role_id === "admin" ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("admin_user_has_all_power"); ?></div>
<?php } ?>
                     <?php   } else {
                            echo form_dropdown("role", $role_dropdown, array($user_info->role_id), "class='select2' id='user-role'");
                            ?>
                            <div id="user-role-help-block" class="help-block ml10 <?php echo $user_info->role_id === "admin" ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("admin_user_has_all_power"); ?></div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($user_info->user_type === "staff"||$user_info->user_type === "resource" ) { ?>
                <div class="form-group">
                    <label for="role" class=" col-md-2"><?php echo lang('line_manager'); ?></label>
                    <div class=" col-md-10">
                         <?php
                        // if (($this->login_user->id == $user_info->id)&& $this->login_user->is_admin) {
                        //     echo "<div class='ml15'>" . lang("admin") . "</div>";
                        // }else if(($this->login_user->id != $user_info->id)&& $this->login_user->is_admin) {
                        if($this->login_user->is_admin) {
                    echo form_dropdown("line_manager", $line_manager, array($user_info->line_manager), "class='select2' id='user-posts' required");
                            ?>
                            <div id="user-role-help-block" class="help-block ml10 "><i class="fa fa-warning text-warning"></i> <?php echo lang("line_manager_has_all_power"); ?></div>
                            <?php
                        }else{
                            if($user_info->line_manager>0){
                            $options = array("id"=>$user_info->line_manager);
                             $line_manager = $this->Users_model->get_details($options)->row();
                              echo "<div class='ml15'>" . $line_manager->first_name." ". $line_manager->last_name . "</div>";}
                              else if($user_info->line_manager=="admin"){
                             echo "<div class='ml15'>Admin</div>";   
                              }else{
                            echo "<div class='ml15'>-</div>";   
   
                              } }
                        
                        ?>
                    </div>
                </div>
            <?php } ?>                        
            <?php if ($this->login_user->is_admin && $user_info->id !== $this->login_user->id) { ?>
                <div class="form-group">
                    <label for="disable_login" class="col-md-2"><?php echo lang('disable_login'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_checkbox("disable_login", "1", $user_info->disable_login ? true : false, "id='disable_login' class='ml15'");
                        ?>
                        <span id="disable-login-help-block" class="ml10 <?php echo $user_info->disable_login ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("disable_login_help_message"); ?></span>
                    </div>
                </div>

                <?php if ($user_info->user_type === "staff"||$user_info->user_type === "resource") { ?>
                    <div class="form-group">
                        <label for="user_status" class="col-md-2"><?php echo lang('mark_as_inactive'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("status", "inactive", $user_info->status === "inactive" ? true : false, "id='user_status' class='ml15'");
                            ?>
                            <span id="user-status-help-block" class="ml10 <?php echo $user_info->status === "inactive" ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("mark_as_inactive_help_message"); ?></span>
                        </div>
                    </div>
                <?php } ?>

            <?php } ?>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#account-info-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#account-info-form .select2").select2();


        //show/hide asmin permission help message
        $("#user-role").change(function() {
            if ($(this).val() === "admin") {
                $("#user-role-help-block").removeClass("hide");
            } else {
                $("#user-role-help-block").addClass("hide");
            }
        });

        //show/hide disable login help message
        $("#disable_login").click(function() {
            if ($(this).is(":checked")) {
                $("#disable-login-help-block").removeClass("hide");
            } else {
                $("#disable-login-help-block").addClass("hide");
            }
        });

        //show/hide user status help message
        $("#user_status").click(function() {
            if ($(this).is(":checked")) {
                $("#user-status-help-block").removeClass("hide");
            } else {
                $("#user-status-help-block").addClass("hide");
            }
        });
    });
</script>    