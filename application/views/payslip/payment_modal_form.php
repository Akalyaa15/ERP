
<!-- <?php 
$net_sal = $payslip_info->total;
$total_paid = $payslip_total_summary->payslip_total_paid;
$balance_due = $net_sal-$total_paid;
$max_paid_amount =$balance_due+$model_info->amount;
echo $max_paid_amount;
?> -->

<?php echo form_open(get_uri("payslip_payments/save_payment"), array("id" => "payslip-payment-form", "class" => "general-form", "role" => "form")); ?>
<div id="payslip_payment-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="payslip_id" value="<?php echo $payslip_id; ?>" />
    <div class="form-group">
        <label for="payslip_payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("payslip_payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2' id='payslip_payment_method_id'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="reference_number" class=" col-md-3"><span id="ref_name"></span><!-- <?php echo lang('reference_number'); ?> --></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "reference_number",
                "name" => "reference_number",
                "value" => $model_info->reference_number,
                "class" => "form-control",
                "placeholder" => lang('reference_number'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="payslip_payment_date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_payment_date",
                "name" => "payslip_payment_date",
                "value" => $model_info->payment_date,
                "class" => "form-control",
                "placeholder" => lang('payment_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="payslip_payment_amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "payslip_payment_amount",
                "name" => "payslip_payment_amount",
               // "value" => $model_info->amount ? to_decimal_format($model_info->amount) : "",
                "value" => $model_info->amount,
                "class" => "form-control",
                "min"=>0,
                "max"=>$max_paid_amount,
                "placeholder" => lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="payslip_payment_note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "payslip_payment_note",
                "name" => "payslip_payment_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
            <label class=" col-md-3"></label>
            <div class="col-md-9">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>
 <?php $this->load->view("includes/dropzone_preview"); ?>

 </div>


<div class="modal-footer">
     <button  class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
 </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#payslip-payment-form").appForm({
            onSuccess: function(result) {
                location.reload(true)
                $("#payslip-payment-table").appTable({newData: result.data, dataId: result.id});
                $("#payslip-total-section").html(result.payslip_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                     updateInvoiceStatusBar(result.payslip_id);
                }
            }
        });
        $("#payslip-payment-form .select2").select2();

        var uploadUrl = "<?php echo get_uri("payslip_payments/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("payslip_payments/validate_invoice_file"); ?>";

        var dropzone = attachDropzoneWithForm("#payslip_payment-dropzone", uploadUrl, validationUrl);
        
        setDatePicker("#payslip_payment_date");


        $('#payslip_payment_method_id').on('change', function() {
      var data = $("#payslip_payment_method_id option:selected").text();
      $("#ref_name").html(data+"No");
       $('#reference_number').attr('placeholder', 
                data+"No"); 
     // alert(data);
    })

      var data = $("#payslip_payment_method_id option:selected").text();
      $("#ref_name").html(data+"No");
      $('#reference_number').attr('placeholder', 
                data+"No"); 
     
    });
</script>