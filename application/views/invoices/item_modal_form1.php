<?php echo form_open(get_uri("invoices/save_item"), array("id" => "invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="invoice_item_title" class=" col-md-3"><?php echo lang('product_id'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_title",
                "name" => "invoice_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_product_id'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
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
                "placeholder" => lang('category')
                
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
                "placeholder" => lang('make')
                
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
                "placeholder" => lang('description')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_quantity",
                "name" => "invoice_item_quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
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
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_rate",
                "name" => "invoice_item_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_hsn_code" class=" col-md-3"><?php echo lang('hsn_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_hsn_code",
                "name" => "invoice_item_hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control",
                "placeholder" => lang('hsn_code'),
                //"readonly" => "true"
            ));
            ?>
        </div>
    </div>
     <div class="form-group">
        <label for="invoice_item_gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_gst",
                "name" => "invoice_item_gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "placeholder" => lang('gst'),
                //"readonly" => "true"
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_hsn_description" class="col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
             "id" => "invoice_item_hsn_code_description",
            "name" => "invoice_item_hsn_code_description",
             "value" => $model_info->hsn_description ? $model_info->hsn_description : "",
                "class" => "form-control",
                "placeholder" => lang('hsn_description')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="discount" class="col-md-3"><?php echo lang('discount_percentage'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "discount_percentage",
                "name" => "discount_percentage",
                "value" => $model_info->discount_percentage ? $model_info->discount_percentage : "",
                "class" => "form-control",
                "placeholder" => lang('discount_percentage'),
                
            ));
            ?>
        </div>
    <!--div class="form-group">
        <label for="invoice_item_tax" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("invoice_item_tax", $taxes_dropdown, array($model_info->tax), "class='select2 tax-select2'");
            ?>
        </div>
    </div-->

    <!--<div class="form-group">
        <label for="invoice_item_tax" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
       
            <select class="form-control" id="invoice_item_tax" name="invoice_item_tax" > <?php 
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("id,title");
 $DB2->from('taxes');
 $DB2->where('taxes.deleted','0');
 
$query=$DB2->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
  <option value="<?php echo $rows->id; ?>"><?php echo $rows->title; ?></option>
  <?php } ?>
 
</select>
        </div>
    </div>-->

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#invoice-item-form").appForm({
            onSuccess: function (result) {
                $("#invoice-item-table").appTable({newData: result.data, dataId: result.id});
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });
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
                $("#invoice_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("invoices/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
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
                                $("#invoice_unit_type").val(response.item_info.unit_type);
                            }

                            if (!$("#invoice_item_category").val()) {
                                $("#invoice_item_category").val(response.item_info.category);
                            }
                            if (!$("#invoice_item_rate").val()) {
                                $("#invoice_item_rate").val(response.item_info.rate);
                            }
                            if (!$("#invoice_item_make").val()) {
                                $("#invoice_item_make").val(response.item_info.make);
                            }
                            if (!$("#invoice_item_hsn_code").val()) {
                                $("#invoice_item_hsn_code").val(response.item_info.hsn_code);
                            }
                            if (!$("#invoice_item_hsn_code_description").val()) {
                                $("#invoice_item_hsn_code_description").val(response.item_info.hsn_description);
                            }
                            if (!$("#invoice_item_gst").val()) {
                                $("#invoice_item_gst").val(response.item_info.gst);
                            }
                        }
                    }
                });
            }

        });
    }




</script>
<script type="text/javascript">
    $("#invoice_item_title").on("click", function() {
   
        $("#invoice_item_gst").val("")
        $("#invoice_item_description").val("")
        $("#invoice_unit_type").val("")
        $("#invoice_item_category").val("")
        $("#invoice_item_rate").val("")
        $("#invoice_item_make").val("")
        $("#invoice_item_hsn_code").val("")
        $("#invoice_item_hsn_code_description").val("")
});
</script>