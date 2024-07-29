<?php echo form_open(get_uri("terms_conditions_templates/save_title"), array("id" => "terms_conditions_templates-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "template_name",
                "name" => "template_name",
                "value" => $model_info->template_name,
                "class" => "form-control",
                "placeholder" => lang('template_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>

        </div>        </div>


   
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#terms_conditions_templates-form").appForm({
            onSuccess: function (result) {
                location.reload()
            }
        });
        $("#title").focus();

    });
</script>    