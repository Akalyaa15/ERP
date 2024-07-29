<?php echo form_open(get_uri("designation/save"), array("id" => "tax-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
            <label for="department" class=" col-md-3"><?php echo lang('department'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("department_code", $department_dropdown, array($model_info->department_code), "class='select2 validate-hidden' id='department_code' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('designation_name'); ?></label>
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
        <label for="designation_code" class=" col-md-3"><?php echo lang('designation_code'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "designation_code",
                "name" => "designation_code",
                "value" => $model_info->designation_code ,
                "class" => "form-control",
                "placeholder" => lang('designation_code'),
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
        $("#tax-form").appForm({
            onSuccess: function(result) {
                $("#taxes-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
                $("#department_code").select2();

    });
</script>    
<?php if($model_info->id){ ?>
<script type="text/javascript">
$(document).ready(function() {
            $("#department_code").select2("readonly", true);
            $("#designation_code").attr('readonly', true)

});
</script>
<?php } ?>