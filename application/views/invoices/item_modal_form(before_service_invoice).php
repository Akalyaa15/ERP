<?php 
if($buyer_type){
$options = array(
            "id" => $buyer_type,
                   );
        $list_data = $this->Buyer_types_model->get_details($options)->row();
    }
        ?>
<?php if($model_info->with_gst=="no") { ?>
<style>
      #s,#y,#z{
        display:none;
      }
</style>
<?php } ?>
<?php if($model_info->with_installation =="no") { ?>
<style>
      #installation_part{
        display:none;
      }
</style>
<?php } ?>
<?php if($model_info->with_installation_gst =="no") { ?>
<style>
      #ss,#yy,#zz{
        display:none;
      }
</style>
<?php } ?>
<?php /*  $buyer_type; */?>
<?php 

$client_buyer_type_profit = $list_data->profit_margin;
$client_buyer_type_name = $list_data->buyer_type;
//echo $client_buyer_type_name;
 ?>
<br>
<div class="form-group">

        <label for="discount" class="col-md-3"></label>
        <div class="col-md-9">
            <span id='foreign_message'></span>
        </div>
        </div>
<?php echo form_open(get_uri("invoices/save_item"), array("id" => "invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="invoice_id" id="invoice_ids" value="<?php echo $invoice_id; ?>" />
    <input type="hidden" id="discount_cutoff_margin" name="discount_cutoff_margin" value="<?php echo get_setting("discount_cutoff_margin"); ?>" />
    <input type="hidden" id="client_profit_margin" name="client_profit_margin" value="<?php echo (isset($client_buyer_type_profit)) ?  $client_buyer_type_profit : '' ?>"  />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
                <label for="invoice_type" class=" col-md-3"><?php echo lang('invoice_type'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('invoice_type'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "supply_type",
                        "name" => "invoice_type",
                        "data-msg-required" => lang("field_required"),
                            ), "0", ($model_info->invoice_type === "1") ? false : true);
                    ?>
                     <label for="supply" class="mr15"><?php echo lang('supply'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "service_type",
                        "name" => "invoice_type",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "1", ($model_info->invoice_type === "1") ? true : false);
                    ?>
                    <label for="service" class=""><?php echo lang('service'); ?></label>
                </div>
            </div>
    <div class="form-group">
        <label for="invoice_item_title" class=" col-md-3"><?php echo lang('model'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_title",
                "name" => "invoice_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_product'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_category" class=" col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_category",
                "name" => "invoice_item_category",
                "value" => $model_info->category,
                "class" => "form-control",
                "placeholder" => lang('category'),
                "readonly"=>"true",
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_make",
                "name" => "invoice_item_make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make'),
                "readonly"=>"true",
                
            ));
            ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="invoice_item_description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "invoice_item_description",
                "name" => "invoice_item_description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "readonly"=>"true",
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "invoice_item_quantity",
                "name" => "invoice_item_quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "min"=>0,
                 "maxlength"=> get_setting('number_of_quantity'),
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_unit_type",
                "name" => "invoice_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control validate-hidden",
                "readonly"=>"true",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)',
                 "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<div class="form-group" >
        <label for="buyer_type" class=" col-md-3"><?php echo lang('buyer_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "buyer_type_name",
                "name" => "buyer_type_name",
                "value" => $client_buyer_type_name,
                "class" => "form-control",
                "placeholder" => lang('buyer_type'),
                
                "readonly"=>"true",
            ));
            ?>
        </div>
    </div>   
    <input type="hidden" name="rate_id" id="rate_id" />  
    <div class="form-group" id = 'orginal_rate'>
        <label for="invoice_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "invoice_item_rate",
                "name" => "invoice_item_rate",
               // "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "value" => $model_info->rate ? $model_info->rate : "",
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "readonly"=>"true",
            ));
            ?>
        </div>
    </div>

    
    <div style='display:none' id='rate'>
    <div class="form-group">
                        <label for="associated_with_part_no" class=" col-md-3"><?php echo lang('associated_with_part_no'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "associated_with_part_no",
                                "name" => "associated_with_part_no",
                                "value" => $model_info->associated_with_part_no,
                                "class" => "form-control validate-hidden",
                                "placeholder" => lang('associated_with_part_no'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                                "readonly"=>"true",
                            ));
                            ?>
                        </div>
                    </div>
                    </div>
                    <div   style='display:none' id = 'profit'>
        <div  class="form-group" >
        <label for="profit_percentage" class="col-md-3"><?php echo lang('profit_percentage'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "profit_percentage",
                "name" => "profit_percentage",
                "value" => $model_info->profit_percentage ? $model_info->profit_percentage : "",
                "class" => "form-control",
                "min"=>0,
                "max"=>100,
                "placeholder" => lang('profit_percentage'),
                
            ));
            ?>
        </div>
        </div>
        </div>








    <div class="form-group" id="gstapp">
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
    




     

   <div id="s">
    <input type="hidden" name="add_new_item_to_librarys" value="" id="add_new_item_to_librarys" />
    <div class="form-group">
        <label for="hsn_code" class=" col-md-3"><?php echo lang('hsn_sac_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_hsn_code",
                "name" => "invoice_item_hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_hsn_code'),
               "data-rule-required" => true,
               "data-msg-required" => lang("field_required"),
                "readonly"=>"true",
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close_hsn_code">×</span></a>
        </div>
    </div>
    </div>
     <div class="form-group"  id ="y">
        <label for="invoice_item_gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "invoice_item_gst",
                "name" => "invoice_item_gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('gst'),
               "readonly"=>"true",
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="z">
        <label for="invoice_item_hsn_description" class="col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
             "id" => "invoice_item_hsn_code_description",
            "name" => "invoice_item_hsn_code_description",
             "value" => $model_info->hsn_description ? $model_info->hsn_description : "",
                "class" => "form-control",
                "placeholder" => lang('hsn_description'),
                "readonly"=>"true",
            ));
            ?> 
        </div>
    </div>


<div class="form-group" id="installation_app">
                <label for="invoice_recurring" class=" col-md-3"><?php echo lang('installation_applicable'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('installation_applicable'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "with_installation",
                        "name" => "with_installation",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->with_installation === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "without_installation",
                        "name" => "with_installation",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->with_installation === "no") ? true : false);
                    ?>
                    <label for="without_installation" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>
            <div id= "installation_part">
<div class="form-group"  id ="installation_rates" >
        <label for="installation_rate" class=" col-md-3"><?php echo lang('installation_rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_rate",
                "name" => "installation_rate",
                "value" => $model_info->installation_rate,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('installation_rate'),
                 "readonly"=>"true",
                  
            ));
            ?>
        </div>
    </div>
    <div class="form-group"  id ="installation_new_rates" style="display:none"; >
        <label for="installation_new_rate" class=" col-md-3"><?php echo lang('installation_rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_new_rate",
                "name" => "installation_new_rate",
                "value" => $model_info->installation_new_rate,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('installation_rate'),
                 
                  
            ));
            ?>
        </div>
    </div>
    <div class="form-group"  style="display:none"  id = 'installation_profit'>
        <label for="installation_profit_percentage" class=" col-md-3"><?php echo lang('installation_profit_percentage'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_profit_percentage",
                "name" => "installation_profit_percentage",
                "value" => $model_info->installation_profit_percentage,
                "class" => "form-control",
                "min"=>0,
                "max"=>100,
                "placeholder" => lang('installation_profit_percentage'),
                 "readonly"=>"true",
                  
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="installation_gstapp">
                <label for="invoice_recurring" class=" col-md-3"><?php echo lang('installation_gst_applicable'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('installation_gst_applicable'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "with_installation_gst",
                        "name" => "with_installation_gst",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->with_installation_gst === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "without_installation_gst",
                        "name" => "with_installation_gst",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->with_installation_gst === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>
<div id="ss">
    <input type="hidden" name="add_new_item_to_libraryss" value="" id="add_new_item_to_libraryss" />
    <div class="form-group">
        <label for="installation_hsn_code" class=" col-md-3"><?php echo lang('installation_hsn_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "installation_hsn_code",
                "name" => "installation_hsn_code",
                "value" => $model_info->installation_hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_hsn_code'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "readonly"=>"true",
            ));
            ?>
            <a id="installation_hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="installation_close_hsn_code">×</span></a>
        </div>
    </div>
    </div>
     <div class="form-group"  id ="yy">
        <label for="installation_gst" class=" col-md-3"><?php echo lang('installation_gst'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_gst",
                "name" => "installation_gst",
                "value" => $model_info->installation_gst,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('installation_gst'),
               "readonly"=>"true",
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="zz">
        <label for="installation_hsn_description" class="col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
             "id" => "installation_hsn_code_description",
            "name" => "installation_hsn_code_description",
             "value" => $model_info->installation_hsn_description ? $model_info->installation_hsn_description : "",
                "class" => "form-control",
                "placeholder" => lang('installation_hsn_description'),
                "readonly"=>"true",
            ));
            ?> 
        </div>
    </div>
    </div>


    
    
    <div class="form-group">
        <label for="discount" class="col-md-3"><?php echo lang('discount_percentage'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "discount_percentage",
                "name" => "discount_percentage",
                "value" => $model_info->discount_percentage ? $model_info->discount_percentage : "",
                "class" => "form-control",
                "min"=>0,
                "max"=>100,
                "placeholder" => lang('discount_percentage'),
                
                
            ));
            ?>
        </div>
        </div>
        <div class="form-group">
        <label for="discount" class="col-md-3"></label>
        <div class="col-md-9">
            <span id='message'></span>
        </div>
        </div>
        
        

        

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button  id= "savebutton" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#invoice-item-form").appForm({
            onSuccess: function (result) {
                location. reload(true);
                $("#invoice-item-table").appTable({newData: result.data, dataId: result.id});
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        $("#associated_with_part_no").select2({
            multiple: true,
            data: <?php echo ($part_no_dropdown); ?>
        });
        $("#invoice_item_make").select2({
            multiple: false,
            data: <?php echo ($make_dropdown); ?>
        });
        $("#invoice_item_category").select2({
            multiple: false,
            data: <?php echo ($product_categories_dropdown); ?>
        });
        <?php  if (isset($unit_type_dropdown)) { ?>
            $("#invoice_unit_type").select2({
                multiple: false,
                data: <?php echo json_encode($unit_type_dropdown); ?>
            });
<?php }  ?> 
       $("#invoice-item-form .tax-select2").select2();
        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

var ishsnUpdate = "<?php echo $model_info->id; ?>";
        if (!ishsnUpdate) {
            applySelect2OnHsnTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnHsnTitle();
        })


var isinstallationhsnUpdate = "<?php echo $model_info->id; ?>";
        if (!isinstallationhsnUpdate) {
            applySelect2OnInstallationHsnTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#installation_hsn_code_dropdwon_icon").click(function () {
            applySelect2OnInstallationHsnTitle();
        })


$('#invoice_item_hsn_code').attr('readonly', true);
$('#installation_hsn_code').attr('readonly', true);

<?php if($model_info->title){?>
$("#invoice_item_title").attr('readonly', true);
<?php } ?>

    });

    function applySelect2OnItemTitle() {
        $("#invoice_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("invoices/get_invoice_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,s:$("#invoice_ids").val() // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#installation_rates").hide();
                $("#installation_new_rates").show();
                //$("#installation_new_rate").show();
                $("#profit").show();
                $("#installation_profit").show();
                $("#rate").show();
                
                $("#orginal_rate").hide();
                $("#invoice_item_rate").hide().val("");
                //$("#orginal_rate").remove().val();
                //$("#invoice_item_rate").remove().val();
                //$("#invoice_item_title").select2("destroy").val("").focus();
                $("#invoice_item_title").select2("destroy").val("").focus().attr('readonly', false);
                $("#invoice_item_description").val("").attr('readonly', false);
        $("#invoice_unit_type").val("").attr('readonly', false);
        $("#invoice_unit_type").select2({
                multiple: false,
                data: <?php echo json_encode($unit_type_dropdown); ?>
            });
        $("#invoice_item_category").val("").attr('readonly', false);
        $("#invoice_item_rate").val("").attr('readonly', false);
        $("#invoice_item_make").select2("val","").attr('readonly', false);
        $("#associated_with_part_no").val("");
        $("#installation_rate").val("").attr('readonly', false);
        $("#installation_profit_percentage").val("").attr('readonly', false);
        //$("#installation_hsn_code").val("").attr('readonly', false);
        $("#rate_id").val("");
         $("#associated_with_part_no").select2("val","").attr('readonly', false);
        $("#invoice_item_hsn_code").select2("destroy").val("");
        $("#installation_hsn_code").select2("destroy").val("");
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                 $("#installation_rates").show();
                $("#installation_new_rates").hide().val("");
         $("#orginal_rate").show();
                //$("#installation_new_rate").show();
                $("#profit").hide();
                $("#installation_profit").hide().val("");
                $("#rate").hide();
                $("#invoice_unit_type").val("").attr('readonly', true);
        $("#invoice_item_category").val("").attr('readonly', true);
        $("#invoice_item_rate").show().attr('readonly', true);
        $("#invoice_item_make").val("").attr('readonly', true);
        $("#installation_rate").val("").attr('readonly', true);
        $("#installation_profit_percentage").val("").attr('readonly', true);
        $("#installation_hsn_code").val("").attr('readonly', true);
        $("#invoice_item_description").val("").attr('readonly', true);
        $("#associated_with_part_no").val("").attr('readonly', true);
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val,s:"<?php echo $invoice_id; ?>",client_type:"<?php echo $invoice_id; ?>"},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                         
                            
                                a=response.item_infos;
  $("#rate_id").val(response.item_info.rate);

  $.ajax({
                    url: "<?php echo get_uri("items/assoc_details"); ?>",
                    data: {item_name:$("#rate_id").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        

                            //$("#item_rate").val(response.assoc_rate);
                            /*$("#invoice_item_rate").val(response.assoc_rate)*/
                            $("#rate_id").val(response.assoc_rate);
                            $.ajax({
                    url: "<?php echo get_uri("invoices/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val,s:"<?php echo $invoice_id; ?>",client_type:"<?php echo $invoice_id; ?>"},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#invoice_item_description").val()) {
                                $("#invoice_item_description").val(response.item_info.description);
                            }
                            

                            if (!$("#invoice_unit_type").val()) {
                                /*$("#invoice_unit_type").val(response.item_info.unit_type);*/
                                $("#invoice_unit_type").select2('val', response.item_info.unit_type);
                            }

                            if (!$("#invoice_item_category").val()) {
                                //$("#invoice_item_category").val(response.item_info.category);
                                $("#invoice_item_category").select2("val",response.item_info.category);
                            }
                            if (!$("#invoice_item_rate").val()) {
                                a=response.item_infos;

                                /*b=response.item_info.actual_value;
                                v=response.item_infoss.profit_margin;
                                y=(b*v/100);
                                //yy=y+b;
                                zz=parseFloat(y)+parseFloat(b);*/
                                a=response.item_infos;
  var rr= $("#rate_id").val();
                               inventory_rate =rr; 
                         inventory_profit =inventory_rate*response.item_info.profit_percentage/100;
                              inventory_actual_value = parseFloat(inventory_rate)+parseFloat(inventory_profit);
                              

                                
                                v=response.item_infoss.profit_margin;
                                y=(inventory_actual_value*v/100);
                                //yy=y+b;
                                zz=parseFloat(y)+parseFloat(inventory_actual_value);

                                c=a*zz;
                                if(a=="failed"||a == null){
                                    alert("Sorry,Currency conversion cannot be done");
                                   c=0; 
                                }else if(a=="same_country"){
                                    //alert("Same Country");
                                   c=zz; 
                                }
                                $("#invoice_item_rate").val(c);
                            }
                            
                                $("#client_profit_margin").val(response.item_infoss.profit_margin);
                            
                           /* if (!$("#invoice_item_make").val()) {
                                $("#invoice_item_make").val(response.item_info.make);
                            }*/

                            $("#invoice_item_make").select2("val",response.item_info.make);
                            
                            if (!$("#invoice_item_hsn_code").val()) {
                                $("#invoice_item_hsn_code").val(response.item_info.hsn_code);
                            }
                            if (!$("#invoice_item_hsn_code_description").val()) {
                                $("#invoice_item_hsn_code_description").val(response.item_info.hsn_description);
                            }
                            if (!$("#invoice_item_gst").val()) {
                                $("#invoice_item_gst").val(response.item_info.gst);
                            }
                            if (!$("#profit_percentage").val()) {
                                $("#profit_percentage").val(response.item_info.profit_percentage);
                            }

                            if (!$("#client_buyer_type").val()) {

                                v=response.item_infoss.buyer_type;
                                $("#client_buyer_type").val(v);
                            }
//installation add

if (!$("#installation_gst").val()) {
                                $("#installation_gst").val(response.item_info.installation_gst);
                            }
                           if (!$("#installation_hsn_code_description").val()) {
                                $("#installation_hsn_code_description").val(response.item_info.installation_hsn_description);
                            }

                            if (!$("#installation_hsn_code").val()) {
                                $("#installation_hsn_code").val(response.item_info.installation_hsn_code);
                            }
                if (!$("#installation_rate").val()) {

                    /*d=response.item_infos;
                    e= response.item_info.installation_actual_value*/
                    d=response.item_infos;
                    var install_actual_values = response.item_info.installation_rate *response.item_info.installation_profit_percentage/100;
                    var install_actual_value =  
                    parseFloat(install_actual_values)+parseFloat(response.item_info.installation_rate);
                    e = install_actual_value;
                    f=d*e;
                    if(d=="failed"||d == null){
                                    alert("Sorry,Currency conversion cannot be done");
                                   f=0; 
                                }else if(d=="same_country"){
                                    //alert("same");
                                   f=e; 
                                }
                            $("#installation_rate").val(f);
                            }
                            if (!$("#installation_profit_percentage").val()) {

                    
                            $("#installation_profit_percentage").val(response.item_info.installation_profit_percentage);
                            }
    }

  } 

});

                

                        }
                        });
                






                        }
                    }
                });
            }

        });
    }

    function applySelect2OnHsnTitle() {
        $("#invoice_item_hsn_code").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("items/get_invoice_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                //$("#invoice_item_hsn_code").select2("destroy").val("").focus();
                $("#invoice_item_hsn_code").select2("destroy").val("").focus().attr('readonly', false);
                $("#invoice_item_gst").val("").attr('readonly', false);
                $("#invoice_item_hsn_code_description").val("").attr('readonly', false);
                $("#add_new_item_to_librarys").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_librarys").val(""); //reset the flag to add new item in library
                $("#invoice_item_gst").val("").attr('readonly', true);
                $("#invoice_item_hsn_code_description").val("").attr('readonly', true);
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val,},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#invoice_item_gst").val()) {
                                $("#invoice_item_gst").val(response.item_info.gst);
                            }
                           if (!$("#invoice_item_hsn_code_description").val()) {
                                $("#invoice_item_hsn_code_description").val(response.item_info.hsn_description);
                            }

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }

    function applySelect2OnInstallationHsnTitle() {
        $("#installation_hsn_code").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("items/get_invoice_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                //$("#invoice_item_hsn_code").select2("destroy").val("").focus();
                $("#installation_hsn_code").select2("destroy").val("").focus().attr('readonly', false);
                $("#installation_gst").val("").attr('readonly', false);
                $("#installation_hsn_code_description").val("").attr('readonly', false);
                $("#add_new_item_to_libraryss").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_libraryss").val(""); //reset the flag to add new item in library
                 $("#installation_gst").val("").attr('readonly', true);
                $("#installation_hsn_code_description").val("").attr('readonly', true);
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val,},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#installation_gst").val()) {
                                $("#installation_gst").val(response.item_info.gst);
                            }
                           if (!$("#installation_hsn_code_description").val()) {
                                $("#installation_hsn_code_description").val(response.item_info.hsn_description);
                            }

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }




</script>
<script type="text/javascript">
    $("#invoice_item_title").on("change", function() {
   
        $("#invoice_item_gst").val("")
        $("#invoice_item_description").val("")
        //$("#invoice_unit_type").val("")
        $("#invoice_unit_type").select2('val',"")
        $("#invoice_item_category").select2('val',"")
        $("#invoice_item_rate").val("")
        $("#invoice_item_make").select2("val"," ")
        $("#invoice_item_hsn_code").select2("destroy").val("")
        $("#associated_with_part_no").val("")
        $("#invoice_item_hsn_code_description").val("")
        $("#installation_rate").val("")
        $("#installation_hsn_code").select2("destroy").val("")
        $("#installation_hsn_code_description").val("")
        $("#installation_gst").val("")
        $("#profit_percentage").val("")
        $("#rate_id").val("")
});
</script>
<script type="text/javascript">
    $("#close").on("click", function() {
        $("#invoice_item_title").val("").attr('readonly', false)
        $("#invoice_item_gst").val("")
        $("#invoice_item_description").val("")
        //$("#invoice_unit_type").val("")
        $("#invoice_unit_type").select2('val',"")
        $("#invoice_item_category").select2('val',"")
        $("#invoice_item_rate").val("")
        $("#invoice_item_make").select2("val"," ")
        $("#invoice_item_hsn_code").select2("destroy").val("")
        $("#associated_with_part_no").val("")
        $("#invoice_item_hsn_code_description").val("")
        $("#installation_rate").val("")
        $("#installation_hsn_code").select2("destroy").val("")
        $("#installation_hsn_code_description").val("")
        $("#installation_gst").val("")
        $("#profit_percentage").val("")
        $("#rate_id").val("")
});
</script>
<script type="text/javascript">
    $("#invoice_item_hsn_code").on("change", function() {
   
        $("#invoice_item_gst").val("")
       
        $("#invoice_item_hsn_code_description").val("")
});
    $("#installation_hsn_code").on("change", function() {
   
        $("#installation_gst").val("")
       
        $("#installation_hsn_code_description").val("")
});
</script>
<script type="text/javascript">
    $("#close_hsn_code").on("click", function() {
   $("#invoice_item_hsn_code").val("").attr('readonly', false)
        $("#invoice_item_gst").val("")
       
        $("#invoice_item_hsn_code_description").val("")
});
$("#installation_close_hsn_code").on("click", function() {
   $("#installation_hsn_code").val("").attr('readonly', false)
        $("#installation_gst").val("")
       
        $("#installation_hsn_code_description").val("")
});

</script>
<script>

  $("#profit_percentage, #discount_percentage").on("keyup", function () {
    var profit_percentage = $("#profit_percentage").val();
    var discount_cutoff_margin = $("#discount_cutoff_margin").val();
    var b=$("#client_profit_margin").val();
    if(!b){
        var b=0;
    }
    var discount_percentage=$("#discount_percentage").val();
    //var installation_profit_percentage = $("#installation_profit_percentage").val();
    var p = ((parseInt(b)+parseInt(profit_percentage))/100)*discount_cutoff_margin;
//alert(p)

    if (discount_percentage>p) {
      //alert("Second value should less than first value");
      $('#message').html('Above Offer Price Not Applicable').css('color', 'red');
     //$("#savebutton").hide();
     $("#savebutton").prop('disabled', true)
    return true;
    }else  {
      //alert("Second value should less than first value");
      $('#message').html('Above Offer Price Not Applicable').css('color', 'white');
     //$("#savebutton").show();
     $("#savebutton").prop('disabled', false)
    return true;
    }
  })

  /*$("#profit_percentage, #discount_percentage").on("keyup", function () {
    var fst=$("#profit_percentage").val();
    var sec=$("#discount_percentage").val();
    if (sec-1<fst) {
      //alert("Second value should less than first value");
      $('#message').html('Discount Percentage should less than profit percentage').css('color', 'blue');

     $("#savebutton").show();
    return true;
    }
  })*/



</script>
<script type="text/javascript">
    $("#without_installation").on("click", function() {
      $("#without_installation_gst").click()
        
        $("#installation_part").hide()
});
</script>
<script type="text/javascript">
    $("#with_installation").on("click", function() {
   
        
        $("#installation_part").show()
});
</script>
<script type="text/javascript">
    $("#without_installation_gst").on("click", function() {
   
       $("#installation_hsn_code").attr('readonly', true)
        $("#ss").hide()
        $("#yy").hide()
        $("#zz").hide()
       
        $("#installation_hsn_code_description").hide()
        $("#installation_gst").hide()
});
</script>
<script type="text/javascript">
    $("#with_installation_gst").on("click", function() {
   
       // $("#installation_hsn_code").show()
        $("#ss").show()
        $("#yy").show()
        $("#zz").show()
       
        $("#installation_hsn_code_description").show()
        $("#installation_gst").show()
});
</script>

<script type="text/javascript">
    $("#without_gst").on("click", function() {
   
       $("#invoice_item_hsn_code").attr('readonly', true)
        $("#s").hide()
        $("#y").hide()
        $("#z").hide()
       
        $("#invoice_item_hsn_code_description").hide()
        $("#invoice_item_gst").hide()
});
</script>
<script type="text/javascript">
    $("#with_gst").on("click", function() {
   
        //$("#invoice_item_hsn_code").show()
        $("#s").show()
        $("#y").show()
        $("#z").show()
       
        $("#invoice_item_hsn_code_description").show()
        $("#invoice_item_gst").show()
});
</script>
<?php 
$company_country=get_setting("company_country");

if($company_country!=$country)
{?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#without_gst").click() 
$("#without_installation_gst").click() 
$("#gstapp").hide()
$("#installation_gstapp").hide() 
$('#foreign_message').html('GST is not applicable for this foreign client ').css('color', 'red');

});
</script>
<?php } ?>
<?php 


if($model_info->invoice_type == 1)
{?>
<script type="text/javascript" >
$( document ).ready(function() {
//$("#invoice_item_rate").click()
$("#invoice_item_rate").attr('readonly', false)
$("#installation_rate").attr('readonly', false)
$("#invoice_item_category").attr('readonly', false)
$("#invoice_item_make").attr('readonly', false)
$("#invoice_item_description").attr('readonly', false)
$("#invoice_unit_type").attr('readonly', false)


});
</script>
<?php } ?>

<script type="text/javascript" >
$( document ).ready(function() {
//$("#invoice_item_rate").click()
$("#service_type").on("click", function() {
$("#invoice_item_rate").attr('readonly', false)
$("#installation_rate").attr('readonly', false)
$("#invoice_item_category").attr('readonly', false)
$("#invoice_item_make").select2().attr('readonly', false)
$("#invoice_item_description").attr('readonly', false)
$("#invoice_unit_type").attr('readonly', false)

});
$("#supply_type").on("click", function() {
$("#invoice_item_rate").attr('readonly', true)
$("#installation_rate").attr('readonly', true)
$("#invoice_item_category").attr('readonly', true)
$("#invoice_item_make").select2().attr('readonly', true)
$("#invoice_item_description").attr('readonly', true)
$("#invoice_unit_type").attr('readonly', true)

});
});
</script>


