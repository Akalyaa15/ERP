<div class="panel panel-default">
    <div class='panel-heading'>
        <i class='fa fa-envelope-o mr10'></i><?php echo $model_info->template_name; ?>
         <?php if($model_info->is_default){ ?>
<span class='label label-success large'>Default</span>
<?php } ?>
    </div>       
<br>
 <?php if(!$model_info->is_default){ ?>
    <button id="to_default" style="margin-left: 20px;" data-toggle="popover" data-id="<?php echo $model_info->id; ?>" data-placement="top" type="button" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo "Set as Default"; ?></button>



<!--     <button id="default" class="btn btn-primary" style="margin-left: 20px;">Set as Default</button>
 --><?php } ?>

    <?php echo form_open(get_uri("terms_conditions_templates/save"), array("id" => "email-template-form", "class" => "general-form", "role" => "form")); ?>
    <div class="modal-body clearfix">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class='row'>
            <div class="form-group">
                <div class=" col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "template_name",
                        "name" => "template_name",
                        "value" => $model_info->template_name,
                        "class" => "form-control",
                        "placeholder" => lang('subject'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class=" col-md-12">
                    <?php
                    echo form_textarea(array(
                        "id" => "custom_message",
                        "name" => "custom_message",
                        "value" => $model_info->custom_message ? $model_info->custom_message : $model_info->default_message,
                        "class" => "form-control"
                    ));
                    ?>
                </div>
            </div>
        </div>

        <hr />
        <div class="form-group m0">
            <button type="submit" class="btn btn-primary mr15"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            <?php if(!$model_info->is_default){ ?>
            <button id="to_delete" data-toggle="popover" data-id="<?php echo $model_info->id; ?>" data-placement="top" type="button" class="btn btn-danger"><span class="fa fa-check-circle"></span> <?php echo lang('delete'); ?></button>
            <?php } ?>
<!--             <button id="restore_to_default" data-toggle="popover" data-id="<?php echo $model_info->id; ?>" data-placement="top" type="button" class="btn btn-danger"><span class="fa fa-refresh"></span> <?php echo lang('restore_to_default'); ?></button> -->
        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#email-template-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                var custom_message = encodeAjaxPostData(getWYSIWYGEditorHTML("#custom_message"));
                $.each(data, function (index, obj) {
                    if (obj.name === "custom_message") {
                        data[index]["value"] = custom_message;
                    }
                });
            },
            onSuccess: function (result) {
                if (result.success) {
                    if(result.success){
                        location.reload()
                    }

                } else {
                    appAlert.error(result.message);
                }
            }
        });

        initWYSIWYGEditor("#custom_message", {height: 480});


        $('#to_default').confirmation({
            btnOkLabel: "<?php echo lang('yes'); ?>",
            btnCancelLabel: "<?php echo lang('no'); ?>",
            onConfirm: function () {
                $.ajax({
                    url: "<?php echo get_uri('terms_conditions_templates/to_default') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {id: this.id},
                    success: function (result) {
                        if (result.success) {
                            $('#custom_message').summernote('code', result.data);
                            appAlert.success(result.message, {duration: 2000});
                            location.reload()
                        } else {
                            appAlert.error(result.message);
                        }
                    }
                });

            }
        });
                
                     $('#to_delete').confirmation({
            btnOkLabel: "<?php echo lang('yes'); ?>",
            btnCancelLabel: "<?php echo lang('no'); ?>",
            onConfirm: function () {
                $.ajax({
                    url: "<?php echo get_uri('terms_conditions_templates/delete') ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {id: this.id},
                    success: function (result) {
                        if (result.success) {
                            appAlert.success(result.message, {duration: 2000});
                            location.reload()
                        } else {
                            appAlert.error(result.message);
                        }
                    }
                });

            }
        });
    });
</script>    