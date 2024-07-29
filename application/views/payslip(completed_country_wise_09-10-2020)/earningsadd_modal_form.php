<?php echo form_open(get_uri("payslip/save_earningsadd"), array("id" => "payslip-earningsadd-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="payslip_id" value="<?php echo $payslip_id; ?>" />
   

        <div class="form-group">
        <label for="payslip_earningsadd_title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_earningsadd_title",
                "name" => "payslip_earningsadd_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="payslip_earningsadd_rate" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "payslip_earningsadd_rate",
                "name" => "payslip_earningsadd_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "min"=> 0,
                "placeholder" => lang('amount'),
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
    $(document).ready(function () {
       $("#payslip-earningsadd-form").appForm({
            onSuccess: function (result) {
                location.reload(true)
                $("#payslip-earningsadd-table").appTable({newData: result.data, dataId: result.id});
                $("#earningsadd-total-section").html(result.earningsadd_total_view);
                
            }
        });
        
        

       
    });
</script>
