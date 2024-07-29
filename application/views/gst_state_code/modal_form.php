<?php echo form_open(get_uri("gst_state_code/save"), array("id" => "gst-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('state'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="gstin_number_first_two_digits" class=" col-md-3"><?php echo lang('gstinnumber_firsttwodigits'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "gstin_number_first_two_digits",
                "name" => "gstin_number_first_two_digits",
                "value" => $model_info->gstin_number_first_two_digits,
                "class" => "form-control",
                "placeholder" => lang('gstinnumber_firsttwodigits'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="state_code" class=" col-md-3"><?php echo lang('state_code'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "state_code",
                "name" => "state_code",
                "value" => $model_info->state_code,
                "class" => "form-control",
                "placeholder" => lang('name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#gst-form").appForm({
            onSuccess: function(result) {
                $("#gst_state_code-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
    });
</script>    