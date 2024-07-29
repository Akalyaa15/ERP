<!-- max no paid leave -->
<?php 

if($payslip_user_total_duration->employee_total_leave <=$payslip_user_total_duration->total_user_lop_days)  {
$max_paid_leave = $payslip_user_total_duration->employee_total_leave;
 } else if($payslip_user_total_duration->employee_total_leave>$payslip_user_total_duration->total_user_lop_days) { 

/*$max_paid_leave = number_format($payslip_user_total_duration->total_user_lop_days,3,'.','');*/

$submax = number_format($payslip_user_total_duration->total_user_lop_days,3,'.','');
  $max_paid_leave =substr($submax,0,-1);
 
}

 ?>
<!-- end paid leave-->


<?php echo form_open(get_uri("payslip/save_paid_leave"), array("id" => "paid_leave-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="payslip_id" value="<?php echo $model_info->id; ?>" />
    
    <div class="form-group">
        <label for="freight_amount" class="col-md-3"><?php echo lang('no_of_days'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "no_of_paid_leave",
                "name" => "no_of_paid_leave",
                "value" => round($model_info->no_of_paid_leave?$model_info->no_of_paid_leave:$payslip_user_total_duration->employee_paid_leave,2),
                "max"=>$max_paid_leave,
                "min" =>0,
                "class" => "form-control",
                "autofocus" => "true",
                "placeholder" => lang('days')
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                
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
    $(document).ready(function () {
        $("#paid_leave-form").appForm({
            onSuccess: function (result) {
                location.reload(true)
                if (result.success && result.attendance_total_view) {
                    $("#attendance-total-section").html(result.attendance_total_view);
                } else {
                    appAlert.error(result.message);
                }
            }
        });
        



        //$("#discount-form .select2").select2();
    });



    

</script>


