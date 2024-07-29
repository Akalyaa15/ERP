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

<?php echo form_open(get_uri("purchase_orders/save"), array("id" => "purchase_order-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
     <input type="hidden" id="current_date" value="<?php echo get_my_local_time("Y-m-d"); ?>" />
    <input type="hidden" id="company_country" value="<?php echo get_setting("company_country") ; ?>" />
    <?php if($model_info->id) { ?>
     <div class="form-group">
        <label for="purchase_order_no" class=" col-md-3"><?php echo lang('purchase_order_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_no",
                "name" => "purchase_no",
                "value" => $model_info->purchase_no?$model_info->purchase_no:get_purchase_order_id($model_info->id),
                "class" => "form-control",
                "placeholder" => lang('purchase_order_no'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>
    <div class="form-group">
        <label for="purchase_order_date" class=" col-md-3"><?php echo lang('generate_purchase_order_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_date",
                "name" => "purchase_order_date",
                "value" => $model_info->purchase_order_date,
                "class" => "form-control",
                "placeholder" => lang('generate_purchase_order_date'),
                "data-rule-required" => true,
               
                 "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
                "data-msg-required" => lang("field_required"),
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
                "data-rule-greaterThanOrEqual" => "#purchase_order_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <!--div class="form-group">
        <label for="terms_of_payment" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
        <div class=" col-md-9">
            <?php/*
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
        <label for="purchase_order_payment_method_id" class=" col-md-3"><?php echo lang('terms_of_payment'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("purchase_order_payment_method_id", $payment_methods_dropdown, array($model_info->terms_of_payment), "class='select2'");
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
    <?php if ($vendor_id) { ?>
        <input type="hidden" name="purchase_order_vendor_id" value="<?php echo $vendor_id; ?>" />
    <?php } else { ?>
        <div class="form-group">
            <label for="purchase_order_vendor_id" class=" col-md-3"><?php echo lang('vendor'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("purchase_order_vendor_id", $vendors_dropdown, array($model_info->vendor_id), "class='select2 validate-hidden' id='purchase_order_vendor_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
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
        <label for="purchase_order_no" class=" col-md-3"><?php echo lang('buyer_purchase_order_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_order_no",
                "name" => "purchase_order_no",
                "value" => $model_info->purchase_order_no,
                "class" => "form-control",
                "placeholder" => lang('buyer_purchase_order_no')
            ));
            ?>
        </div>
    </div>
     <div class="form-group"  id="purchase_order_date_show" style="display:none">
        <label for="purchase_date" class=" col-md-3"><?php echo lang('buyer_purchase_order_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_date",
                "name" => "purchase_date",
                "value" => $model_info->purchase_date?$model_info->purchase_date:get_my_local_time("Y-m-d"),
                //"value" => $model_info->purchase_date,
                "class" => "form-control",
                "placeholder" => lang('buyer_purchase_order_date'),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group" >
        <label for="purchase_order_note" class=" col-md-3"><?php echo lang('delivery_note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "purchase_order_note",
                "name" => "purchase_order_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note')
            ));
            ?>
        </div>
    </div>
     
    <div class="form-group" id="delivery_date_show" style="display:none">
        <label for="invoice_delivery_note_date" class=" col-md-3"><?php echo lang('delivery_note_date'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "delivery_note_date",
                "name" => "delivery_note_date",
                "value" => $model_info->delivery_note_date?$model_info->delivery_note_date:get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('delivery_note_date'),
                "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="dispatch_document_no" class=" col-md-3"><?php echo lang('dispatch_docket'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "dispatch_document_no",
                "name" => "dispatch_document_no",
                "value" => $model_info->dispatch_document_no,
                "class" => "form-control",
                "placeholder" => lang('dispatch_docket')
            ));
            ?>
        </div>
    </div>
     
    <!--div class="form-group">
        <label for="dispatched_through" class=" col-md-3"><?php echo lang('dispatched_through'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "dispatched_through",
                "name" => "dispatched_through",
                "value" => $model_info->dispatched_through,
                "class" => "form-control",
                "placeholder" => lang('dispatched_through')
            ));
            ?>
        </div>
    </div-->
    <div class="form-group">
        <label for="invoice_payment_method_id" class=" col-md-3"><?php echo lang('dispatched_through'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("dispatched_through", $dispatched_through_dropdown, array($model_info->dispatched_through), "class='select2'");
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
    <div class="form-group">
        <label for="estimate_delivery_address" class=" col-md-3"><?php echo lang('delivery_address_other'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('delivery_address_other'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-9">
            <?php
            echo form_checkbox("estimate_delivery_address", "1", $model_info->estimate_delivery_address ? true : false, "id='estimate_delivery_address'");
            ?>                       
        </div>
    </div>
    <div id="estimate_delivery_address_fields" class="<?php if (!$model_info->estimate_delivery_address) echo "hide"; ?>"> 
        <div class="form-group">
            <label for="consignee" class=" col-md-3"><?php echo lang('company_name'); ?></label>
            <div class=" col-md-9">
    <?php
        echo form_input(array(
            "id" => "delivery_address_company_name",
            "name" => "delivery_address_company_name",
            "value" => $model_info->delivery_address_company_name,
            "class" => "form-control",
            "placeholder" => lang('company_name'),
            //"autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>  
    <div class="form-group">
            <label for="no_of_cycles" class=" col-md-3"><?php echo lang('delivery_address'); ?></label>
            <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "delivery_address",
                "name" => "delivery_address",
                "value" => $model_info->delivery_address ? $model_info->delivery_address : "",
                "class" => "form-control",
                "placeholder" => lang('delivery_address')
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
            <label for="delivery_address_country" class=" col-md-3"><?php echo lang('phone'); ?></label>
<div class=" col-md-9">
    <?php
        echo form_input(array(
            "id" => "delivery_address_phone",
            "name" => "delivery_address_phone",
            "value" => $model_info->delivery_address_phone,
            "class" => "form-control",
            "placeholder" => lang('phone')
        ));
        ?>
    </div>
</div>
</div>
<!--div class="form-group">
        <label for="without_gst" class=" col-md-3"><?php echo lang('without_gst'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('without_gst'); ?>"><i class="fa fa-question-circle"></i></span></label>
        <div class=" col-md-9">
            <?php
            echo form_checkbox("without_gst", "1", $model_info->without_gst ? true : false, "id='without_gst'");
            ?>                       
        </div>
    </div-->
    


    <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase_order-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('purchase_orders/view'); ?>/" + result.id;
                }
            }
        });
        $("#lut_number").select2({
                multiple: false,
                data: <?php echo json_encode($lut_dropdown); ?>
            });
        $("#purchase_order-form .tax-select2").select2();
        $("#purchase_order-form .select2").select2();
        $("#purchase_order_vendor_id").select2();

        setDatePicker("#purchase_order_date, #valid_until,#delivery_note_date,#purchase_date");

         $("#estimate_delivery_address").click(function () {
            if ($(this).is(":checked")) {
                $("#estimate_delivery_address_fields").removeClass("hide");
            } else {
                $("#estimate_delivery_address_fields").addClass("hide");
            }
        });

$('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script>
$("#purchase_order_vendor_id").change(function () {
         var client_id = $("#purchase_order_vendor_id").val();
                
                $.ajax({
                    url: "<?php echo get_uri("purchase_orders/get_vendor_country_item_info_suggestion"); ?>",
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
    $("#purchase_order_vendor_id").on("change", function() {
        //$('#invoice_item_title').attr('readonly', false);
        
        $("#lut_number").val("")
});
    
     $("#purchase_order_no").on("keyup", function() {
        //$('#invoice_item_title').attr('readonly', false);
        var curr = $("#current_date").val();
       var purdate = $("#purchase_order_no").val();
       if(purdate) {
        $("#purchase_order_date_show").show();
        $("#purchase_date").attr("required", "true");
        
    }else if(!purdate){
        $("#purchase_order_date_show").hide();
        $("#purchase_date").val(curr).attr("required", "false");

    }
});
 $("#purchase_order_note").on("keyup", function() {
        //$('#invoice_item_title').attr('readonly', false);
        var curr = $("#current_date").val();
       var delivdate = $("#purchase_order_note").val();
       if(delivdate) {
        $("#delivery_date_show").show();
        $("#delivery_note_date").attr("required", "true");
    }else if(!delivdate){
        $("#delivery_date_show").hide();
        $("#delivery_note_date").val(curr).attr("required", "false");
    }
});

<?php if($model_info->purchase_order_no){?>
      $("#purchase_order_date_show").show();
<?php } ?>
<?php if($model_info->note){?>
      $("#delivery_date_show").show();
<?php } ?>
</script>
