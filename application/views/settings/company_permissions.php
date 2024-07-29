<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "company_permissions";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('company_permissions'); ?></h4>
                </div>

                <?php echo form_open(get_uri("settings/save_companypermission_settings"), array("id" => "client-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                <div class="panel-body"> 
                    <div class="form-group">
                        <label for="disable_client_signup" class="col-md-2"><?php echo lang('disable_company_signup'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("disable_company_signup", "1", get_setting("disable_company_signup") ? true : false, "id='disable_company_signup' class='ml15'");
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
        $("#client-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        
    });
</script>