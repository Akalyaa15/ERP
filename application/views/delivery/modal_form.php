<?php echo form_open(get_uri("Delivery/save"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?> 
<?php if($model_info->proformainvoice_no) {?>
<script type="text/javascript">
    $(document).ready(function () {
                $("#id").hide()
                $("#pd").show()
$("#import_from").select2("destroy").val("pi");
});
</script>

<?php }else if($model_info->invoice_no) { ?>
<script type="text/javascript">
    $(document).ready(function () {
                $("#id").show()
                $("#pd").hide()
                 $("#import_from").select2("destroy").val("inv");
});
</script>

<?php } ?>
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
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <?php if($model_info->id) { ?>
     <div class="form-group">
        <label for="voucher_no" class=" col-md-3"><?php echo lang('dc_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "dc_no",
                "name" => "dc_no",
                "value" => $model_info->dc_no?$model_info->dc_no:get_delivery_id($model_info->id),
                "class" => "form-control",
                "placeholder" => lang('dc_no'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>
    <div class="form-group">
        <label for="estimate_date" class=" col-md-3"><?php echo lang('requested_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_date",
                "name" => "estimate_date",
                "value" => $model_info->estimate_date,
                "class" => "form-control",
                "placeholder" => lang('requested_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="valid_until" class=" col-md-3"><?php echo lang('valid_until'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "valid_until",
                "name" => "valid_until",
                "value" => $model_info->valid_until,
                "class" => "form-control",
                "placeholder" => lang('valid_until'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#estimate_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
            <label for="dc_type_id" class=" col-md-3"><?php echo lang('dc_type'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("dc_type_id", $dc_types_dropdown, array($model_info->dc_type_id), "class='select2 validate-hidden' id='dc_type_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
    <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('member'); ?></label>
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
            <div class="form-group">
                <label for="address" class=" col-md-3"><?php echo lang('mailing_address'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                         "value" => $model_info->address,
                        "class" => "form-control",
                        "placeholder" => lang('mailing_address')
                    ));
                    ?>
                </div>
            </div>
              <div class="form-group">
                <label for="state" class=" col-md-3"><?php echo lang('state'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "state",
                        "name" => "state",
                         "value" => $model_info->state,
                        "class" => "form-control",
                        "placeholder" => lang('state')
                    ));
                    ?>
                </div>
            </div>
              <div class="form-group">
                <label for="country" class=" col-md-3"><?php echo lang('country'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "country",
                        "name" => "country",
                         "value" => $model_info->country,
                        "class" => "form-control",
                        "placeholder" => lang('country')
                    ));
                    ?>
                </div>
            </div>
             <div class="form-group">
                <label for="zip" class=" col-md-3"><?php echo lang('zip'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "zip",
                        "name" => "zip",
                         "value" => $model_info->zip,
                        "class" => "form-control",
                        "placeholder" => lang('zip')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                         "value" => $model_info->phone,
                        "class" => "form-control",
                        "placeholder" => lang('phone')
                    ));
                    ?>
                </div>
            </div>
            </div>
    <?php if ($client_id) { ?>
        <input type="hidden" name="estimate_client_id" value="<?php echo $client_id; ?>" />
    <?php } else { ?>
        <div class="form-group" id="team" style="display: none">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("estimate_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='estimate_client_id' ");
                ?>
            </div>
        </div>
        
    <?php } ?>
<div class="form-group" id="outsource" style="display: none">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('outsource_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("estimate_client_idss", $rm_dropdown, $model_info->client_id, "class='select2 validate-hidden' id='rm_member'");
                ?>
            </div>
        </div>
 <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('import_from'); ?></label>
            <div class="col-md-9">
                  <select class='select2 validate-hidden' id='import_from' name='import_from'>
                    <option value="-">-</option>

  <option value="inv">Taxable Invoice</option>
  <option value="pi">Quotation</option>
</select>
            </div>
        </div>
 <div class="form-group" id="id" style="display: none;">
            <label for="invoice_dropdown" class=" col-md-3"><?php echo lang('invoice'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_no", $voucher_dropdown, $model_info->invoice_no, "class='select2 validate-hidden' id='invoice_no'");
                ?>
            </div>
        </div>
  <div class="form-group" id="pd"  style="display: none;">
            <label for="invoice_dropdown" class=" col-md-3"><?php echo lang('estimate'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("proformainvoice_no", $vouchers_dropdown, $model_info->proformainvoice_no, "class='select2 validate-hidden' id='proformainvoice_no'");
                ?>
            </div>
        </div>  
        <div class="form-group">
        <label for="invoice_no" class=" col-md-3"><?php echo lang('invoice_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_for_dc",
                "name" => "invoice_for_dc",
                 "value" => $model_info->invoice_for_dc,
                "class" => "form-control",
                "placeholder" => lang('invoice_no')
            ));
            ?>
        </div>
    </div>
     <div class="form-group">
        <label for="invoice_date" class=" col-md-3"><?php echo lang('invoice_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_date",
                "name" => "invoice_date",
               "value" => $model_info->invoice_date ? $model_info->invoice_date: "",
                "class" => "form-control",
                "placeholder" => lang('invoice_date')
            ));
            ?>
        </div>
    </div>
        <div class="form-group">
            <label for="invoice_client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("invoice_client_id", $org_clients_dropdown, array($model_info->invoice_client_id), "class='select2 validate-hidden' id='invoice_client_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
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
     <div class="form-group">
        <label for="buyers_order_date" class=" col-md-3"><?php echo lang('buyers_order_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "buyers_order_date",
                "name" => "buyers_order_date",
                "value" => $model_info->buyers_order_date ? $model_info->buyers_order_date: "",
                "class" => "form-control",
                "placeholder" => lang('buyers_order_date')
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
     <div class="form-group">
        <label for="lc_date" class=" col-md-3"><?php echo lang('lc_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "lc_date",
                "name" => "lc_date",
                "value" => $model_info->lc_date ? $model_info->lc_date: "",
                "class" => "form-control",
                "placeholder" => lang('lc_date')
            ));
            ?>
        </div>
    </div>
<div class="form-group">
        <label for="invoice_payment_method_id" class=" col-md-3"><?php echo lang('dispatched_through'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("dispatched_through", $dispatched_through_dropdown, array($model_info->dispatched_through), "class='select2 validate-hidden' id='dispatched_through' data-rule-required='true' data-msg-required='" . lang('field_required') . "'");
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
        <label for="dispatch_date" class=" col-md-3"><?php echo lang('dispatch_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "dispatch_date",
                "name" => "dispatch_date",
                "value" => $model_info->dispatch_date ? $model_info->dispatch_date: "",
                "class" => "form-control",
                "placeholder" => lang('dispatch_date')
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
        <label for="demo_period" class=" col-md-3"><?php echo lang('demo_period'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "demo_period",
                "name" => "demo_period",
                "value" => $model_info->demo_period ? $model_info->demo_period : "",
                "class" => "form-control",
                "placeholder" => lang('demo_period')
            ));
            ?>
        </div>
    </div>   
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
</div></div>
    <div class="form-group">
        <label for="estimate_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_note",
                "name" => "estimate_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note')
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
                    window.location = "<?php echo site_url('delivery/view'); ?>/" + result.id;
                }
            }
        });
        $("#estimate-form .tax-select2").select2();
        $("#estimate_client_id").select2();
        $("#import_from").select2();
        $("#invoice_no").select2();
        $("#invoice_client_id").select2();
        $("#dispatched_through").select2();
        $("#proformainvoice_no").select2();
$("#member_type").select2();
        $("#rm_member").select2();
        setDatePicker("#estimate_date, #valid_until, #lc_date,#buyers_order_date,#dispatch_date,#invoice_date");
        $("#dc_type_id").select2();


    });
</script>
<script>
$( "#import_from").change(function() {
    if($("#import_from").val()=="inv"){
        $("#proformainvoice_no").select2("destroy").val("");
        $("#proformainvoice_no").select2()
                        $("#pd").hide()
                        $("#id").show()

    }else if($("#import_from").val()=="pi"){
                $("#invoice_no").select2("destroy").val("");
$("#invoice_no").select2()
                $("#id").hide()
                        $("#pd").show()

    }else{
        $("#id").hide()
                $("#pd").hide()
                $("#invoice_no").select2("destroy").val("");
$("#invoice_no").select2()
 $("#proformainvoice_no").select2("destroy").val("");
        $("#proformainvoice_no").select2()
    }
    });
$(document).ready(function () {
                //$("#id").hide()
                //$("#pd").hide()



    });
</script>
<script>
$( "#member_type").change(function(e) {
    if($("#member_type").val()=="others"){
       
                        $("#otherss").show()
                        $("#team").hide()
$("#outsource").hide()
    }else if($("#member_type").val()=="tm"){
                
                $("#otherss").hide()
                        $("#team").show()
$("#outsource").hide()

    }else if($("#member_type").val()=="om"){
                
                $("#otherss").hide()
                $("#team").hide()
                $("#outsource").show()

    }
    });
</script>
<script type="text/javascript">
    $("#invoice_delivery_address").click(function () {
            if ($(this).is(":checked")) {
                $("#invoice_delivery_address_fields").removeClass("hide");
            } else {
                $("#invoice_delivery_address_fields").addClass("hide");
            }
        });
</script>