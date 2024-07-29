<?php echo form_open(get_uri("voucher/save_remarks"), array("id" => "save_remarks-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $voucher_id; ?>" />
     <input type="hidden" name="status" value="<?php echo $status; ?>" />
             <div class="form-group">
                <label for="dealer" class=" col-md-3"><?php echo lang('remark'); ?></label>
                <div class="col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "class" => "form-control",
                "placeholder" => lang('remarks'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                </div>
            </div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#save_remarks-form").appForm({
            onSuccess: function(result) {
        location.reload()           
         }
        });
        $("#description").focus();
    });
</script>