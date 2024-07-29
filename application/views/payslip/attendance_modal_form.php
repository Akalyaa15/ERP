<?php echo form_open(get_uri("payslip/save_attendance"), array("id" => "payslip-attendance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="payslip_id" value="<?php echo $payslip_id; ?>" />
   
    <div class="form-group">
            <label for="payslip_attendance_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("payslip_attendance_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='payslip_attendance_user_id'");
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
    $(document).ready(function () {
       $("#payslip-attendance-form").appForm({
            onSuccess: function (result) {
                $("#payslip-attendance-table").appTable({newData: result.data, dataId: result.id});
                $("#attendance-total-section").html(result.attendance_total_view);
                
            }
        });
        
        

        $("#payslip-attendance-form .select2").select2();

    });
</script>