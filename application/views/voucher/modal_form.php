<?php echo form_open(get_uri("Voucher/save"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="created_user_id" value="<?php echo $this->login_user->id; ?>" />
    <?php if($this->login_user->line_manager>0 || $this->login_user->line_manager=="admin"){ ?>
    <input type="hidden" name="line_manager" value="<?php echo $this->login_user->line_manager; ?>" />
<?php }else{ ?>     <input type="hidden" name="line_manager" value="0" />
<?php } ?>
    <?php if($model_info->id) { ?>
     <div class="form-group">
        <label for="voucher_no" class=" col-md-3"><?php echo lang('voucher_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "voucher_no",
                "name" => "voucher_no",
                "value" => $model_info->voucher_no?$model_info->voucher_no:get_voucher_id($model_info->id),
                "class" => "form-control",
                "placeholder" => lang('voucher_no'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>
    <div class="form-group">
        <label for="estimate_date" class=" col-md-3"><?php echo lang('voucher_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_date",
                "name" => "estimate_date",
                "value" => $model_info->estimate_date,
                "class" => "form-control",
                "placeholder" => lang('voucher_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group" style="display: none">
        <label for="valid_until" class=" col-md-3"><?php echo lang('due_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "valid_until",
                "name" => "valid_until",
                "value" => $model_info->valid_until,
                "class" => "form-control",
                "placeholder" => lang('due_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#estimate_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <?php if ($client_id) { ?>
        <input type="hidden" name="estimate_client_id" value="<?php echo $client_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("estimate_client_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2 validate-hidden' id='estimate_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
         <div class="form-group">
            <label for="voucher_type_id" class=" col-md-3"><?php echo lang('voucher_type'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("voucher_type_id", $voucher_types_dropdown, array($model_info->voucher_type_id), "class='select2 validate-hidden' id='voucher_type_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <?php } ?>

   
    <div class="form-group">
        <label for="estimate_note" class=" col-md-3"><?php echo lang('voucher_note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_note",
                "name" => "estimate_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('voucher_note')
            ));
            ?>
        </div>
    </div>

    <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('voucher/view'); ?>/" + result.id;
                }
            }
        });
        $("#estimate-form .tax-select2").select2();
        $("#estimate_client_id").select2();
        $("#voucher_type_id").select2();

        setDatePicker("#estimate_date");


    });
</script>
<script type="text/javascript">
$("#estimate_date").on("change", function(){
       var date = new Date($("#estimate_date").val()),
           days = 14;
        
        if(!isNaN(date.getTime())){
            date.setDate(date.getDate() + days);
            
            $("#valid_until").val(date.toInputFormat());
        } else {
            alert("Invalid Date");  
        }

    });
    
    
    //From: http://stackoverflow.com/questions/3066586/get-string-in-yyyymmdd-format-from-js-date-object
    Date.prototype.toInputFormat = function() {
       var yyyy = this.getFullYear().toString();
       var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
       var dd  = this.getDate().toString();
       return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
    };
    </script> 