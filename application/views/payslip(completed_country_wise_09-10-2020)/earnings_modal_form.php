<?php echo form_open(get_uri("payslip/save_earnings"), array("id" => "payslip-earnings-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="payslip_id" value="<?php echo $payslip_id; ?>" />
   

    <div class="form-group">
            <label for="payslip_earnings_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("payslip_earnings_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='payslip_earnings_user_id'");
                ?>
            </div>
        </div> 

       <!-- <div class="form-group">
        <label for="payslip_earnings_title" class=" col-md-3"><?php echo lang('earnings'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "payslip_earnings_title",
                "name" => "payslip_earnings_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('earnings'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="payslip_earnings_rate" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_earnings_rate",
                "name" => "payslip_earnings_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
           */ ?>
        </div>
    </div> -->

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>



<script type="text/javascript">
    $(document).ready(function () {
       $("#payslip-earnings-form").appForm({
            onSuccess: function (result) {
                $("#payslip-earnings-table").appTable({newData: result.data, dataId: result.id});
                $("#earnings-total-section").html(result.earnings_total_view);
                
            }
        });
        
        

        $("#payslip-earnings-form .select2").select2();

    });
</script>
