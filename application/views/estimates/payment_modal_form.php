
<?php $max_paid_amount =$estimate_total_summary->balance_due+$model_info->amount; ?>
<?php echo form_open(get_uri("estimate_payments/save_payment"), array("id" => "estimate-payment-form", "class" => "general-form", "role" => "form")); ?>
<div id="estimate_payment-dropzone" class="post-dropzone">
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_id" value="<?php echo $estimate_id; ?>" />
    <div class="form-group">
        <label for="estimate_payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("estimate_payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2' id='estimate_payment_method_id'");
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
        <label for="estimate_payment_date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_payment_date",
                "name" => "estimate_payment_date",
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
        <label for="estimate_payment_amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "estimate_payment_amount",
                "name" => "estimate_payment_amount",
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
        <label for="estimate_payment_note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_payment_note",
                "name" => "estimate_payment_note",
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
        $("#estimate-payment-form").appForm({
            onSuccess: function(result) {
                $("#estimate-payment-table").appTable({newData: result.data, dataId: result.id});
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                     updateInvoiceStatusBar(result.estimate_id);
                }
            }
        });
        $("#estimate-payment-form .select2").select2();

        var uploadUrl = "<?php echo get_uri("estimate_payments/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("estimate_payments/validate_estimate_file"); ?>";

        var dropzone = attachDropzoneWithForm("#estimate_payment-dropzone", uploadUrl, validationUrl);
        
        setDatePicker("#estimate_payment_date");


        $('#estimate_payment_method_id').on('change', function() {
      var data = $("#estimate_payment_method_id option:selected").text();
     if(data.toLowerCase().includes('cash')){
         $("#ref_name").html("Reference No");
        
      $('#reference_number').attr('placeholder', 
                "Reference No"); 
     
  }else{

   $("#ref_name").html(data+"No");
       $('#reference_number').attr('placeholder', 
                data+"No");
                } 
     // alert(data);
    })

      var data = $("#estimate_payment_method_id option:selected").text();
      if(data.toLowerCase().includes('cash')){
         $("#ref_name").html("Reference No");
        
      $('#reference_number').attr('placeholder', 
                "Reference No"); 
     
  }else{
      $("#ref_name").html(data+"No");
      $('#reference_number').attr('placeholder', 
                data+"No"); 
  }
     
    });
</script>