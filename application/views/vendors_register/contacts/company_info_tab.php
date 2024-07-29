<div class="tab-content">
    <?php echo form_open(get_uri("vendors_register/save/"), array("id" => "company-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('vendor_info'); ?></h4>
        </div>
        <div class="panel-body">
            <?php $this->load->view("vendors/vendor_form_fields"); ?>
            <div class="form-group">
                        <label for="logo" class=" col-md-2"><?php echo lang('company_logo'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="site-logo-preview" src="<?php echo get_file_uri(get_general_file_path("vendor", $model_info->id) . $model_info->vendor_logo); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="site_logo_file" class="cropbox-upload upload" name="site_logo_file" type="file" data-height="90" data-width="120" data-preview-container="#site-logo-preview" data-input-field="#site_logo" />
                            </div>
                            <input type="hidden" id="site_logo" name="site_logo" value=""  />
                        </div>
                    </div>
                    <?php if ($this->login_user->is_admin) { ?>
                    <div class="form-group">
        <label for="enable_vendor_logo" class="<?php echo $label_column; ?>"><?php echo lang('enable_vendor_logo'); ?>
            <span class="help" data-container="body" data-toggle="tooltip" title="<?php echo lang('enable_vendor_logo_description') ?>"><i class="fa fa-question-circle"></i></span>
        </label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_checkbox("enable_vendor_logo", "1", $model_info->enable_vendor_logo ? true : false, "id='enable_vendor_logo'");
            ?>                       
        </div>
    </div>
    <?php } ?>
        </div>
        <div class="panel-footer">
            <button id="savebutton" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php $this->load->view("includes/cropbox"); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#company-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });
    });
</script>