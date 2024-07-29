<?php if($model_info->member_type=='tm') {?>
<script type="text/javascript">
    $(document).ready(function () {
                $("#otherss").hide()
                $("#outsource").hide()
                 $("#team").show()
$("#member_type").select2("destroy").val("tm");
});
</script>

<?php }else if($model_info->member_type=='om') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#otherss").hide()
                $("#outsource").show()
                 $("#team").hide()
                 $("#member_type").select2("destroy").val("om");
});
</script>

<?php }else if($model_info->member_type=='others') { ?>
<script type="text/javascript">
    $(document).ready(function () {
                 $("#otherss").show()
                $("#outsource").hide()
                 $("#team").hide()
                 $("#member_type").select2("destroy").val("others");
});
</script>

<?php } ?>


<?php if($model_info->lut_number) {

 ?>
<style>
      #luts_number{
        display:block;
      }

       
        
</style>
<?php } ?>
<?php if(!$model_info->lut_number) {

 ?>
<style>
      #luts_number{
        display:none;
      }

       
        
</style>
<?php } ?>

<?php echo form_open(get_uri("invoices/save"), array("id" => "invoice-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
     <input type="hidden" id="current_date" value="<?php echo get_my_local_time("Y-m-d"); ?>" />
    <input type="hidden" name="warranty_expiry_date" id="warranty_expiry_date" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" id="company_country" value="<?php echo get_setting("company_country") ; ?>" />
    <?php if($model_info->id) { ?>
     <div class="form-group">
        <label for="invoice_no" class=" col-md-3"><?php echo lang('invoice_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_no",
                "name" => "invoice_no",
                "value" => $model_info->invoice_no?$model_info->invoice_no:get_invoice_id($model_info->id),
                "class" => "form-control",
                "placeholder" => lang('invoice_no'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>
    <div class="form-group">
        <label for="invoice_bill_date" class=" col-md-3"><?php echo lang('bill_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_bill_date",
                "name" => "invoice_bill_date",
                "value" => $model_info->bill_date,
                "class" => "form-control recurring_element",
                "placeholder" => lang('bill_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_due_date" class=" col-md-3"><?php echo lang('due_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_due_date",
                "name" => "invoice_due_date",
                "value" => $model_info->due_date,
                "class" => "form-control",
                "placeholder" => lang('due_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#invoice_bill_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
     </div>
     <div class="form-group">
            <label for="warranty" class=" col-md-3"><?php echo lang('warranty'); ?></label>
            <div class="col-md-4">
                <?php
                echo form_input(array(
                    "id" => "warranty",
                    "name" => "warranty",
                    "type" => "number",
                    "value" => $model_info->warranty ? $model_info->warranty : 0,
                    "min" => 0,
                    //"max" => 50,             
                    "class" => "form-control recurring_element",
                    "placeholder" => lang('repeat_every'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required")
                ));
                ?>
            </div>
            <div class="col-md-5">
                <?php
                echo form_dropdown(
                        "warranty_type", array(
                     "days" => lang("interval_days"),
                     "weeks" => lang("interval_weeks"),
                    "months" => lang("interval_months"),
                    "years" => lang("interval_years"),
                        ), $model_info->warranty_type ? $model_info->warranty_type : "days", "class='select2 warranty_type' id='warranty_type'"
                );
                ?>
            </div>
        </div> 
     <div class="form-group">
        <label for="invoice_note" class=" col-md-3"><?php echo lang('delivery_note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "invoice_note",
                "name" => "invoice_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('delivery_note')
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="invoice_delivery_note_date_show" style="display: none">
        <label for="invoice_delivery_note_date" class=" col-md-3"><?php echo lang('delivery_note_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_delivery_note_date",
                "name" => "delivery_note_date",
                "value" => $model_info->delivery_note_date ? $model_info->delivery_note_date: get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('delivery_note_date')
            ));
            ?>
        </div>
    </div>
    <!--div class="form-group">
        <label for="terms_of_payment" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
        <div class=" col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "terms_of_payment",
                "name" => "terms_of_payment",
                "value" => $model_info->terms_of_payment,
                "class" => "form-control",
                "placeholder" => lang('terms_of_payment')
            ));
            */?>
        </div>
    </div-->

    <div class="form-group">
        <label for="invoice_payment_method_id" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("invoice_payment_method_id", $payment_methods_dropdown, array($model_info->terms_of_payment), "class='select2'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="supplier_ref" class=" col-md-3"><?php echo lang('supplier_ref'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "supplier_ref",
                "name" => "supplier_ref",
                "value" => $model_info->supplier_ref,
                "class" => "form-control",
                "placeholder" => lang('supplier_ref')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="other_references" class=" col-md-3"><?php echo lang('other_references'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "other_references",
                "name" => "other_references",
                "value" => $model_info->other_references,
                "class" => "form-control",
                "placeholder" => lang('other_references')
            ));
            ?>
        </div>
    </div>
    <?php if ($client_id && !$project_id) { ?>
        <input type="hidden" name="invoice_client_id" value="<?php echo $client_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="invoice_client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='invoice_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <?php } ?>
    <div class="form-group" id="luts_number">
        <label for="lut_number" class=" col-md-3"><?php echo lang('lut_number'); ?></label>
        <div class=" col-md-9">
        <?php 
        echo form_input(array(
            "id" => "lut_number",
            "name" => "lut_number",
            "value" => $model_info->lut_number,
            "class" => "form-control",
            "placeholder" => lang('lut_number'),
            

        ));
        ?>
    </div>
</div>
    <div class="form-group">
        <label for="buyers_order_no" class=" col-md-3"><?php echo lang('buyers_order_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "buyers_order_no",
                "name" => "buyers_order_no",
                "value" => $model_info->buyers_order_no,
                "class" => "form-control",
                "placeholder" => lang('buyers_order_no')
            ));
            ?>
        </div>
    </div>
     <div class="form-group" id="buyers_order_date_show" style="display: none">
        <label for="buyers_order_date" class=" col-md-3"><?php echo lang('buyers_order_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "buyers_order_date",
                "name" => "buyers_order_date",
                "value" => $model_info->buyers_order_date ? $model_info->buyers_order_date: get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('buyers_order_date'),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>
     <div class="form-group">
        <label for="lc_no" class=" col-md-3"><?php echo lang('lc_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "lc_no",
                "name" => "lc_no",
                "value" => $model_info->lc_no,
                "class" => "form-control",
                "placeholder" => lang('lc_no')
            ));
            ?>
        </div>
    </div>
     <div class="form-group" id="lc_date_show" style="display: none">
        <label for="lc_date" class=" col-md-3"><?php echo lang('lc_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "lc_date",
                "name" => "lc_date",
                "value" => $model_info->lc_date ? $model_info->lc_date: get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('lc_date'),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>
    <!--div class="form-group">
        <label for="dispatch_document_no" class=" col-md-3"><?php echo lang('dispatch_document_no'); ?></label>
        <div class=" col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "dispatch_document_no",
                "name" => "dispatch_document_no",
                "value" => $model_info->dispatch_document_no,
                "class" => "form-control",
                "placeholder" => lang('dispatch_document_no')
            ));
           */ ?>
        </div>
    </div-->
     
    <!--div class="form-group">
        <label for="dispatched_through" class=" col-md-3"><?php echo lang('dispatched_through'); ?></label>
        <div class=" col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "dispatched_through",
                "name" => "dispatched_through",
                "value" => $model_info->dispatched_through,
                "class" => "form-control",
                "placeholder" => lang('dispatched_through'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            */ ?>
        </div>
    </div-->

    <div class="form-group">
        <label for="invoice_payment_method_id" class=" col-md-3"><?php echo lang('dispatched_through'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("dispatched_through", $dispatched_through_dropdown, array($model_info->dispatched_through), "class='select2 validate-hidden' data-rule-required='true' data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>

    <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('dispatch_by'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='member_type' name='member_type' required>
                    <option value="">-</option>

  <option value="tm">Team members </option>
  <option value="om">Outsource members </option>
  <option value="others">Others </option>

</select>
            </div>
        </div>
   <div class="tab-content mt15">
        <div role="tabpanel" class="tab-pane active" id="general-info-tab">
        <div id="otherss" style="display: none">
            <div class="form-group">
                <label for="name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "first_name",
                        "name" => "first_name",
                         "value" => $model_info->f_name,
                        "class" => "form-control",
                        "placeholder" => lang('first_name'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class=" col-md-3"><?php echo lang('last_name'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "last_name",
                        "name" => "last_name",
                         "value" => $model_info->l_name,
                        "class" => "form-control",
                        "placeholder" => lang('last_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <!--div class="form-group">
                <label for="address" class=" col-md-3"><?php echo lang('mailing_address'); ?></label>
                <div class=" col-md-9">
                    <?php /*
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                         "value" => $model_info->address,
                        "class" => "form-control",
                        "placeholder" => lang('mailing_address'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    */?>
                </div>
            </div-->
            <div class="form-group">
                <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                         "value" => $model_info->phone,
                        "class" => "form-control",
                        "placeholder" => lang('phone'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            </div>
<div class="form-group" id="team" style="display: none">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_dispatch_team_member_id", $members_dropdown, array($model_info->dispatch_user_id), "class='select2 validate-hidden' id='invoice_team_members_ids' ");
                ?>
            </div>
        </div>
<div class="form-group" id="outsource" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('outsource_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_dispatch_outsource_id", $rm_members_dropdown, $model_info->dispatch_user_id, " class='select2 validate-hidden' id='rm_member'");
                ?>
            </div>
        </div>


    <div class="form-group">
        <label for="dispatch_docket" class=" col-md-3"><?php echo lang('dispatch_docket'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "dispatch_docket",
                "name" => "dispatch_docket",
                "value" => $model_info->dispatch_docket,
                "class" => "form-control",
                "placeholder" => lang('dispatch_docket')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="dispatch_name" class=" col-md-3"><?php echo lang('dispatch_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "dispatch_name",
                "name" => "dispatch_name",
                "value" => $model_info->dispatch_name,
                "class" => "form-control",
                "placeholder" => lang('dispatch_name')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="waybill_no" class=" col-md-3"><?php echo lang('waybill_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "waybill_no",
                "name" => "waybill_no",
                "value" => $model_info->waybill_no,
                "class" => "form-control",
                "placeholder" => lang('waybill_no')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="destination" class=" col-md-3"><?php echo lang('destination'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "destination",
                "name" => "destination",
                "value" => $model_info->destination,
                "class" => "form-control",
                "placeholder" => lang('destination')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="terms_of_delivery" class=" col-md-3"><?php echo lang('terms_of_delivery'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "terms_of_delivery",
                "name" => "terms_of_delivery",
                "value" => $model_info->terms_of_delivery,
                "class" => "form-control",
                "placeholder" => lang('terms_of_delivery')
            ));
            ?>
        </div>
    </div>
     
    
    <!--<div class="form-group">
        <label for="delivery_address" class=" col-md-3"><?php echo lang('buyer_other_consignee'); ?></label>
        <div class=" col-md-9">
            <?php /*
            echo form_textarea(array(
                "id" => "delivery_address",
                "name" => "delivery_address",
                "value" => $model_info->delivery_address ? $model_info->delivery_address : "",
                "class" => "form-control",
                "placeholder" => lang('delivery_address')
            ));
            */?>
        </div>
    </div>-->
    <?php if ($project_id) { ?>
        <input type="hidden" name="invoice_project_id" value="<?php echo $project_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="invoice_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class="col-md-9" id="invoice-porject-dropdown-section">
                <?php
                echo form_input(array(
                    "id" => "invoice_project_id",
                    "name" => "invoice_project_id",
                    "value" => $model_info->project_id,
                    "class" => "form-control",
                    "placeholder" => lang('project')
                ));
                ?>
            </div>
        </div>
    <?php } ?>

<div class="form-group">
        <label for="invoice_delivery_address" class=" col-md-3"><?php echo lang('delivery_address_other'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('delivery_address_other'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-9">
            <?php
            echo form_checkbox("invoice_delivery_address", "1", $model_info->invoice_delivery_address ? true : false, "id='invoice_delivery_address'");
            ?>                       
        </div>
    </div>
    <div id="invoice_delivery_address_fields" class="<?php if (!$model_info->invoice_delivery_address) echo "hide"; ?>"> 
        <div class="form-group">
            <label for="consignee" class=" col-md-3"><?php echo lang('company_name_person'); ?></label>
            <div class=" col-md-9">
    <?php
        echo form_input(array(
            "id" => "delivery_address_company_name",
            "name" => "delivery_address_company_name",
            "value" => $model_info->delivery_address_company_name,
            "class" => "form-control",
            "placeholder" => lang('company_name_person'),
            //"autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>  
    <div class="form-group">
            <label for="no_of_cycles" class=" col-md-3"><?php echo lang('address'); ?></label>
            <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "delivery_address",
                "name" => "delivery_address",
                "value" => $model_info->delivery_address ? $model_info->delivery_address : "",
                "class" => "form-control",
                "placeholder" => lang('address')
            ));
            ?>
        </div>
    </div>
    

<div class="form-group">
            <label for="delivery_address_city" class=" col-md-3"><?php echo lang('city'); ?></label>
<div class=" col-md-9">
        <?php
        echo form_input(array(
            "id" => "delivery_address_city",
            "name" => "delivery_address_city",
            "value" => $model_info->delivery_address_city,
            "class" => "form-control",
            "placeholder" => lang('city')
        ));
        ?>
    </div>
</div>
<div class="form-group">
            <label for="delivery_address_state" class=" col-md-3"><?php echo lang('state'); ?></label>
            <div class=" col-md-9">
   <?php
        echo form_input(array(
            "id" => "delivery_address_state",
            "name" => "delivery_address_state",
            "value" => $model_info->delivery_address_state,
            "class" => "form-control",
            "placeholder" => lang('state')
        ));
        ?>
    </div>
</div>
<div class="form-group">
            <label for="delivery_address_zip" class=" col-md-3"><?php echo lang('pincode'); ?></label>
<div class=" col-md-9">    <?php
        echo form_input(array(
            "id" => "delivery_address_zip",
            "name" => "delivery_address_zip",
            "value" => $model_info->delivery_address_zip,
            "class" => "form-control",
            "placeholder" => lang('pincode')
        ));
        ?>
    </div>
</div>
<div class="form-group">
            <label for="delivery_address_country" class=" col-md-3"><?php echo lang('country'); ?></label>
<div class=" col-md-9">
    <?php
        echo form_input(array(
            "id" => "delivery_address_country",
            "name" => "delivery_address_country",
            "value" => $model_info->delivery_address_country,
            "class" => "form-control",
            "placeholder" => lang('country')
        ));
        ?>
    </div>
</div>
<div class="form-group">
                <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "delivery_address_phone",
                        "name" => "delivery_address_phone",
                         "value" => $model_info->delivery_address_phone,
                        "class" => "form-control",
                        "placeholder" => lang('phone'),
                         "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
</div>
             
    <!--<div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2 tax-select2'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('second_tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2'");
            ?>
        </div>
    </div>-->
    <div class="form-group">
        <label for="invoice_recurring" class=" col-md-3"><?php echo lang('recurring'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-9">
            <?php
            echo form_checkbox("recurring", "1", $model_info->recurring ? true : false, "id='invoice_recurring'");
            ?>                       
        </div>
    </div>    
    <div id="recurring_fields" class="<?php if (!$model_info->recurring) echo "hide"; ?>"> 
        <div class="form-group">
            <label for="repeat_every" class=" col-md-3"><?php echo lang('repeat_every'); ?></label>
            <div class="col-md-4">
                <?php
                echo form_input(array(
                    "id" => "repeat_every",
                    "name" => "repeat_every",
                    "type" => "number",
                    "value" => $model_info->repeat_every ? $model_info->repeat_every : 1,
                    "min" => 1,
                    "class" => "form-control recurring_element",
                    "placeholder" => lang('repeat_every'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required")
                ));
                ?>
            </div>
            <div class="col-md-5">
                <?php
                echo form_dropdown(
                        "repeat_type", array(
                    "days" => lang("interval_days"),
                    "weeks" => lang("interval_weeks"),
                    "months" => lang("interval_months"),
                    "years" => lang("interval_years"),
                        ), $model_info->repeat_type ? $model_info->repeat_type : "months", "class='select2 recurring_element' id='repeat_type'"
                );
                ?>
            </div>
        </div>    

        <div class="form-group">
            <label for="no_of_cycles" class=" col-md-3"><?php echo lang('cycles'); ?></label>
            <div class="col-md-4">
                <?php
                echo form_input(array(
                    "id" => "no_of_cycles",
                    "name" => "no_of_cycles",
                    "type" => "number",
                    "min" => 1,
                    "value" => $model_info->no_of_cycles ? $model_info->no_of_cycles : "",
                    "class" => "form-control",
                    "placeholder" => lang('cycles')
                ));
                ?>
            </div>
            <div class="col-md-5 mt5">
                <span class="help" data-toggle="tooltip" title="<?php echo lang('recurring_cycle_instructions'); ?>"><i class="fa fa-question-circle"></i></span>
            </div>
        </div>  



        <div class = "form-group hide" id = "next_recurring_date_container" >
            <label for = "next_recurring_date" class = " col-md-3"><?php echo lang('next_recurring_date'); ?>  </label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "next_recurring_date",
                    "name" => "next_recurring_date",
                    "class" => "form-control",
                    "placeholder" => lang('next_recurring_date'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

    </div>  
   
    


    <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 


    <?php if ($estimate_id) { ?>
        <div class="form-group">
            <label for="estimate_id_checkbox" class=" col-md-12">
                <input type="hidden" name="copy_items_from_estimate" value="<?php echo $estimate_id; ?>" />
                <?php
                echo form_checkbox("estimate_id_checkbox", $estimate_id, true, " class='pull-left' disabled='disabled'");
                ?>    
                <span class="pull-left ml15"> <?php echo lang('include_all_items_of_this_estimate'); ?> </span>
            </label>
        </div>
<div style = "display:none">
        <div class="form-group" >
        <label for="freight_amount" class="col-md-3"><?php echo lang('freight'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount ? to_decimal_format($model_info->amount) : "",
                "class" => "form-control",
                //"autofocus" => "true",
                "placeholder" => lang('freight_amount'),
                //"data-rule-required" => true,
                //"data-msg-required" => lang("field_required"),
                
            ));
            ?>
        </div>
        </div>
        <div class="form-group">
                <label for="invoice_recurring" class=" col-md-3"><?php echo lang('gst_applicable'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('gst_applicable'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "with_gst",
                        "name" => "with_gst",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->with_gst === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "without_gst",
                        "name" => "with_gst",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->with_gst === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>


    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group" id="s">
        <label for="hsn_code" class=" col-md-3"><?php echo lang('hsn_sac_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "hsn_code",
                "name" => "hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_item'),
              // "data-rule-required" => true,
               // "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close_hsn_code">×</span></a>
        </div>
    </div>
    <div class="form-group" id="y">
        <label for="gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "gst",
                "name" => "gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "placeholder" => lang('gst'),
                "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="z">
        <label for="hsn_description" class=" col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "hsn_description",
                "name" => "hsn_description",
                "value" => $model_info->hsn_description,
                "class" => "form-control",
                "placeholder" => lang('hsn_description'),
               "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>

    <div class="form-group" id="a">
                <label for="expense_recurring" class=" col-md-3"><?php echo lang('inclusive_tax'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('gst_applicable'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "with_inclusive_tax",
                        "name" => "with_inclusive_tax",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->with_inclusive_tax === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "without_inclusive_tax",
                        "name" => "with_inclusive_tax",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->with_inclusive_tax === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>

            <div class="form-group" style="display:none">
        <label for="freight_amount" class="col-md-3"><?php echo lang('freight'); ?></label>
        <div class="col-md-9">
            <?php 
            echo form_input(array(
                "id" => "freight_amount",
                "name" => "freight_amount",
                "value" => $model_info->freight_amount ? $model_info->freight_amount : "",
                "class" => "form-control",
                //"autofocus" => "true",
                "placeholder" => lang('freight_amount'),
                //"data-rule-required" => true,
                //"data-msg-required" => lang("field_required"),
                
            ));
              ?>
        </div>
        </div>
        <div class="form-group" >
        <label for="freight_amount" class="col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php 
            echo form_input(array(
                "id" => "freight_tax_amount",
                "name" => "freight_tax_amount",
                "value" => $model_info->freight_tax_amount ? $model_info->freight_tax_amount : "",
                "class" => "form-control",
                //"autofocus" => "true",
                "placeholder" => lang('freight_amount'),
                //"data-rule-required" => true,
                //"data-msg-required" => lang("field_required"),
                
            ));
              ?>
        </div>
        </div>
        </div>
    <?php } ?>
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
        if ("<?php echo $estimate_id; ?>") {
            RELOAD_VIEW_AFTER_UPDATE = false; //go to invoice page
        }

        $("#invoice-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('invoices/view'); ?>/" + result.id;
                }
            },
            onAjaxSuccess: function (result) {
                if (!result.success && result.next_recurring_date_error) {
                    $("#next_recurring_date").val(result.next_recurring_date_value);
                    $("#next_recurring_date_container").removeClass("hide");

                    $("#invoice-form").data("validator").showErrors({
                        "next_recurring_date": result.next_recurring_date_error
                    });
                }
            }
        });
        $("#invoice-form .tax-select2").select2();
         $("#invoice-form .select2").select2();
        $("#repeat_type").select2();
        $("#warranty_type").select2();

        setDatePicker("#invoice_bill_date, #invoice_due_date,#invoice_delivery_note_date,#buyers_order_date,#lc_date");

        //load all projects of selected client
        $("#invoice_client_id").select2().on("change", function () {
            var client_id = $(this).val();
            if ($(this).val()) {
                $('#invoice_project_id').select2("destroy");
                $("#invoice_project_id").hide();
                appLoader.show({container: "#invoice-porject-dropdown-section"});
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_project_suggestion") ?>" + "/" + client_id,
                    dataType: "json",
                    success: function (result) {
                        $("#invoice_project_id").show().val("");
                        $('#invoice_project_id').select2({data: result});
                        appLoader.hide();
                    }
                });
            }
        });

        $("#lut_number").select2({
                multiple: false,
                data: <?php echo json_encode($lut_dropdown); ?>
            }); 


        $('#invoice_project_id').select2({data: <?php echo json_encode($projects_suggestion); ?>});

        if ("<?php echo $project_id; ?>") {
            $("#invoice_client_id").select2("readonly", true);
        }

        //show/hide recurring fields
        $("#invoice_recurring").click(function () {
            if ($(this).is(":checked")) {
                $("#recurring_fields").removeClass("hide");
            } else {
                $("#recurring_fields").addClass("hide");
            }
        });

        //show/hide recurring fields
        $("#invoice_delivery_address").click(function () {
            if ($(this).is(":checked")) {
                $("#invoice_delivery_address_fields").removeClass("hide");
            } else {
                $("#invoice_delivery_address_fields").addClass("hide");
            }
        });


        setDatePicker("#next_recurring_date", {
            startDate: moment().add(1, 'days').format("YYYY-MM-DD") //set min date = tomorrow
        });


        $('[data-toggle="tooltip"]').tooltip();

    });
</script>
<script>
$("#invoice_client_id").change(function () {
         var client_id = $("#invoice_client_id").val();
                
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_client_country_item_info_suggestion"); ?>",
                    data: {item_name:client_id},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                           /* if (!$("#other_references").val()) {
                                $("#other_references").val(response.item_info.country);
                            } */
                           
                            
var company_country = $("#company_country").val(); 
     var client_country = response.item_info.country ;  

     if(company_country!=client_country){
        $("#luts_number").show();
        
        //alert(response.item_info.country)
     }else{
        $("#luts_number").hide();
     }                   
                            
                            
                        }
                    }
                });
            
})
        
    


</script>
<script type="text/javascript">
    $("#invoice_client_id").on("change", function() {
        //$('#invoice_item_title').attr('readonly', false);
        
        $("#lut_number").val("")
});
    
     $("#buyers_order_no").on("keyup", function() {
        //$('#invoice_item_title').attr('readonly', false);
        var curr = $("#current_date").val();
       var purdate = $("#buyers_order_no").val();
       if(purdate) {
        $("#buyers_order_date_show").show();
        $("#buyers_order_date").attr("required", "true");
        
    }else if(!purdate){
        $("#buyers_order_date_show").hide();
        $("#buyers_order_date").val(curr).attr("required", "false");

    }
});
 $("#lc_no").on("keyup", function() {
        //$('#invoice_item_title').attr('readonly', false);
        var curr = $("#current_date").val();
       var delivdate = $("#lc_no").val();
       if(delivdate) {
        $("#lc_date_show").show();
        $("#lc_date").attr("required", "true");
    }else if(!delivdate){
        $("#lc_date_show").hide();
        $("#lc_date").val(curr).attr("required", "false");
    }
});

 $("#invoice_note").on("keyup", function() {
        //$('#invoice_item_title').attr('readonly', false);
        var curr = $("#current_date").val();
       var delivdate = $("#invoice_note").val();
       if(delivdate) {
        $("#invoice_delivery_note_date_show").show();
        $("#invoice_delivery_note_date").attr("required", "true");
    }else if(!delivdate){
        $("#invoice_delivery_note_date_show").hide();
        $("#invoice_delivery_note_date").val(curr).attr("required", "false");
    }
});
<?php if($model_info->buyers_order_no){?>
      $("#buyers_order_date_show").show();
<?php } ?>
<?php if($model_info->lc_no){?>
      $("#lc_date_show").show();
<?php } ?>
<?php if($model_info->note){?>
      $("#invoice_delivery_note_date_show").show();
<?php } ?>

</script>
<script>
$( "#member_type").change(function(e) {
    if($("#member_type").val()=="others"){

                        $("#otherss").show()
                        $("#team").hide()
$("#outsource").hide()
//$("#invoice_team_members_ids").select2("val","")
$("#invoice_team_members_ids").removeAttr("required");
$("#rm_member").removeAttr("required");
    }else if($("#member_type").val()=="tm"){
                $("#rm_member").removeAttr('required');
 $("#invoice_team_members_ids").prop("required", "true");

                $("#otherss").hide()
                $("#team").show()
                $("#outsource").hide()
               // $("#first_name").val("")
                //$("#last_name").val("")
                //$("#phone").val("")

    }else if($("#member_type").val()=="om"){
                $("#rm_member").prop("required", "true");
 $("#invoice_team_members_ids").removeAttr('required');

                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").show()
                //$("#first_name").val("")
                //$("#last_name").val("")
                //$("#phone").val("")

    }
    });
$(document).ready(function () { 
                //$("#id").hide()
                //$("#pd").hide()



    });
</script>
 <script>
function convert(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [date.getFullYear(), mnth, day].join("-");
}
    $( "#warranty_type,#warranty,#invoice_bill_date").change(function(e) {
var b_date=$( "#invoice_bill_date").val();
  var warranty_type=$( "#warranty_type").val();
   var warranty = $( "#warranty").val();
   if(warranty!=0){
    if(warranty_type=='months'){
        var exp = 1*warranty; //or whatever offset  
        var CurrentDate = new Date(b_date);
CurrentDate.setMonth(CurrentDate.getMonth() + exp);  
    }else if(warranty_type=='years'){
        var exp = 12*warranty; 
        var CurrentDate = new Date(b_date);
       CurrentDate.setMonth(CurrentDate.getMonth() + exp);
    }else if(warranty_type=='days'){
        var exp = 1*warranty; 
        var CurrentDate = new Date(b_date);
       CurrentDate.setDate(CurrentDate.getDate() + exp);
    }else if(warranty_type=='weeks'){
        var exp = 7*warranty; 
        var CurrentDate = new Date(b_date);
       CurrentDate.setDate(CurrentDate.getDate() + exp);
    }
    $( "#warranty_expiry_date").val(convert(CurrentDate))
}else{
    $( "#warranty_expiry_date").val("");
}


//alert($( "#warranty_expiry_date").val());
    });
    $(document).ready(function () { 
       var b_date=$( "#invoice_bill_date").val();
        var warranty_type=$( "#warranty_type").val();
         var warranty = $( "#warranty").val();
         if(warranty!=0){
        if(warranty_type=='months'){
        var exp = 1*warranty; //or whatever offset 
        var CurrentDate = new Date(b_date);
        CurrentDate.setMonth(CurrentDate.getMonth() + exp);   
         }else if(warranty_type=='years'){
        var exp = 12*warranty; 
        var CurrentDate = new Date(b_date);
         CurrentDate.setMonth(CurrentDate.getMonth() + exp);
            }else if(warranty_type=='days'){
        var exp = 1*warranty; 
        var CurrentDate = new Date(b_date);
       CurrentDate.setDate(CurrentDate.getDate() + exp);
    }else if(warranty_type=='weeks'){
        var exp = 7*warranty; 
        var CurrentDate = new Date(b_date);
       CurrentDate.setDate(CurrentDate.getDate() + exp);
    }
    $( "#warranty_expiry_date").val(convert(CurrentDate))
}else{
    $( "#warranty_expiry_date").val("");
}
/*var CurrentDate = new Date(b_date);
CurrentDate.setMonth(CurrentDate.getMonth() + exp);*/

//alert($( "#warranty_expiry_date").val());
    });
</script>
<!-- <script>
function convert(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [date.getFullYear(), mnth, day].join("-");
}
    $( "#warranty_type,#warranty,#invoice_bill_date").change(function(e) {
var b_date=$( "#invoice_bill_date").val();
  var warranty_type=$( "#warranty_type").val();
   var warranty = $( "#warranty").val();
    if(warranty_type=='months'){
        var exp = 1*warranty; //or whatever offset    
    }else if(warranty_type=='years'){
        var exp = 12*warranty; 
    }
var CurrentDate = new Date(b_date);
CurrentDate.setMonth(CurrentDate.getMonth() + exp);
$( "#warranty_expiry_date").val(convert(CurrentDate))
    });
    $(document).ready(function () { 
       var b_date=$( "#invoice_bill_date").val();
        var warranty_type=$( "#warranty_type").val();
         var warranty = $( "#warranty").val();
        if(warranty_type=='months'){
        var exp = 1*warranty; //or whatever offset    
         }else if(warranty_type=='years'){
        var exp = 12*warranty; 
            }
var CurrentDate = new Date(b_date);
CurrentDate.setMonth(CurrentDate.getMonth() + exp);
$( "#warranty_expiry_date").val(convert(CurrentDate))
    });
</script> -->

 