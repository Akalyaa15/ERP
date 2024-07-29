<?php echo form_open(get_uri("lut_number/save"), array("id" => "lut_number-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="lut_year" class=" col-md-3"><?php echo lang('lut_year'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "lut_year",
                "name" => "lut_year",
                "value" => $model_info->lut_year,
                "class" => "form-control",
                "placeholder" => lang('lut_year'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    
    
    <div class="form-group">
        <label for="lut_number" class=" col-md-3"><?php echo lang('lut_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "lut_number",
                "name" => "lut_number",
                "value" => $model_info->lut_number,
                "class" => "form-control",
                "placeholder" => lang('lut_number'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description,
                "class" => "form-control",
                "placeholder" => lang('description'),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class=" col-md-3"><?php echo lang('status'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_radio(array(
                "id" => "status_active",
                "name" => "status",
                "data-msg-required" => lang("field_required"),
                    ), "active", ($model_info->status === "active") ? true : ($model_info->status !== "inactive") ? true : false);
            ?>
            <label for="status_active" class="mr15"><?php echo lang('active'); ?></label>
            <?php
            echo form_radio(array(
                "id" => "status_inactive",
                "name" => "status",
                "data-msg-required" => lang("field_required"),
                    ), "inactive", ($model_info->status === "inactive") ? true : false);
            ?>
            <label for="status_inactive" class=""><?php echo lang('inactive'); ?></label>
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
        $("#lut_number-form").appForm({
            onSuccess: function(result) {
                $("#lut_number-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#lut_year").focus();
    });
</script>    