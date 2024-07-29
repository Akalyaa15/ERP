<?php echo form_open(get_uri("payslip/save_deductions"), array("id" => "payslip-deductions-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="payslip_id" value="<?php echo $payslip_id; ?>" />
   
    <div class="form-group">
        <label for="payslip_deductions_title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_deductions_title",
                "name" => "payslip_deductions_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('title'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            
        </div>
    </div>

    <div class="form-group">
        <label for="payslip_deductions_rate" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "payslip_deductions_rate",
                "name" => "payslip_deductions_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "min"=> 0,
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
    $(document).ready(function () {
       $("#payslip-deductions-form").appForm({
            onSuccess: function (result) {
                location.reload(true)
                $("#payslip-deductions-table").appTable({newData: result.data, dataId: result.id});
                $("#deductions-total-section").html(result.deductions_total_view);
                
            }
        });
        
        

        $("#payslip-deductions-form .select2").select2();

    });
</script>
