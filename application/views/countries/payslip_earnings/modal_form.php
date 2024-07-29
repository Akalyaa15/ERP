<?php echo form_open(get_uri("countries/save_earnings"), array("id" => "earnings-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="country_id" value="<?php echo $country_id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('name'); ?></label>
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
        <label for="percentage" class=" col-md-3"><?php echo lang('percentage'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "percentage",
                "name" => "percentage",
                "value" => $model_info->percentage ? $model_info->percentage : "",
                "class" => "form-control",
                "placeholder" => lang('percentage'),
                "min" => 0,
                "max" => 100,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
        $("#earnings-form").appForm({
            onSuccess: function(result) {
                //$("#payslip-eanings-table").appTable({newData: result.data, dataId: result.id});
                $("#payslip-eanings-table").appTable({reload: true});
            }
        });
        $("#title").focus();
    });
</script>    



<!-- <?php echo form_open(get_uri("team_members/save_file"), array("id" => "file-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
    <?php
    $this->load->view("includes/multi_file_uploader", array(
        "upload_url" =>get_uri("team_members/upload_file"),
        "validation_url" =>get_uri("team_members/validate_file"),
    ));
    ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-upload" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" disabled="disabled" class="btn btn-primary start-upload"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#file-form").appForm({
            onSuccess: function (result) {
                $("#team-member-file-table").appTable({reload: true});
            }
        });

    });

</script>    
 -->

