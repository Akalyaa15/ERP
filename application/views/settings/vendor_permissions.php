<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "vendor_permissions";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('vendor_permissions'); ?></h4>
                </div>

                <?php echo form_open(get_uri("settings/save_vendor_settings"), array("id" => "vendor-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                <div class="panel-body"> 
                    <div class="form-group">
                        <label for="disable_vendor_signup" class="col-md-2"><?php echo lang('disable_vendor_signup'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("disable_vendor_signup", "1", get_setting("disable_vendor_signup") ? true : false, "id='disable_vendor_signup' class='ml15'");
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disable_vendor_login" class="col-md-2"><?php echo lang('disable_vendor_login'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("disable_vendor_login", "1", get_setting("disable_vendor_login") ? true : false, "id='disable_vendor_login' class='ml15'");
                            ?>
                        </div>
                    </div>

                    <!--div class="form-group">
                        <label for="vendor_message_users" class=" col-md-2"><?php echo lang('who_can_send_or_receive_message_to_or_from_clients'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "vendor_message_users",
                                "name" => "vendor_message_users",
                                "value" => get_setting("vendor_message_users"),
                                "class" => "form-control",
                                "placeholder" => lang('team_members')
                            ));
                            ?>
                        </div>
                    </div-->

                    <div class="form-group">
                        <label for="hidden_vendor_menus" class=" col-md-2"><?php echo lang('hide_menus_from_vendor_portal'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "hidden_vendor_menus",
                                "name" => "hidden_vendor_menus",
                                "value" => get_setting("hidden_vendor_menus"),
                                "class" => "form-control",
                                "placeholder" => lang('hidden_menus')
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disable_editing_left_menu_by_vendors" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('disable_editing_left_menu_by_vendors'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("disable_editing_left_menu_by_vendors", "1", get_setting("disable_editing_left_menu_by_vendors") ? true : false, "id='disable_editing_left_menu_by_vendors' class='ml15'");
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disable_topbar_menu_customization" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('disable_topbar_menu_customization'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("disable_topbar_menu_customization_vendors", "1", get_setting("disable_topbar_menu_customization_vendors") ? true : false, "id='disable_topbar_menu_customization_vendors' class='ml15'");
                            ?>
                        </div>
                    </div>

                    

                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vendor-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#vendor_message_users").select2({
            multiple: true,
            data: <?php echo ($members_dropdown); ?>
        });
        $("#hidden_vendor_menus").select2({
            multiple: true,
            data: <?php echo ($hidden_menu_dropdown); ?>
        });
    });
</script>