<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "student_desk_permissions";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('student_desk_permissions'); ?></h4>
                </div>

                <?php echo form_open(get_uri("settings/save_student_desk_permissions"), array("id" => "student_desk_permissions-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                <div class="panel-body"> 
                    <div class="form-group">
                        <label for="disable_student_desk_registration" class="col-md-2"><?php echo lang('disable_student_desk_registration'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("disable_student_desk_registration", "1", get_setting("disable_student_desk_registration") ? true : false, "id='disable_student_desk_registration' class='ml15'");
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
        $("#student_desk_permissions-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
       
    });
</script>