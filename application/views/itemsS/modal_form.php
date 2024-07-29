<?php /* echo form_open(get_uri("items/save"), array("id" => "item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="col-md-12 text-off"> <?php echo lang('item_edit_instruction'); ?></div>
        </div>
    <?php } ?>
 
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('product_id'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('product_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
     <div class="form-group">
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="category" class=" col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "category",
                "name" => "category",
                "value" => $model_info->category,
                "class" => "form-control",
                "placeholder" => lang('category')
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "make",
                "name" => "make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make')
                
            ));
            ?>
        </div>
    </div>
    

    <div class="form-group">
        <label for="item_stock" class=" col-md-3"><?php echo lang('stock'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_stock",
                "name" => "item_stock",
                "value" => $model_info->stock ? $model_info->stock : "",
                "class" => "form-control",
                "placeholder" => lang('no_stock'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
   
    <div class="form-group">
        <label for="unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "unit_type",
                "name" => "unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_rate",
                "name" => "item_rate",
                "value" => $model_info->rate ? $model_info->rate : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="hsn_code" class=" col-md-3"><?php echo lang('hsn_sac_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "hsn_code",
                "name" => "hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_hsn_code'),
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "gst",
                "name" => "gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "placeholder" => lang('gst'),
               // "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>
  <div class="form-group">
        <label for="hsn_description" class=" col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "hsn_description",
                "name" => "hsn_description",
                "value" => $model_info->hsn_description,
                "class" => "form-control",
                "placeholder" => lang('hsn_description'),
               // "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>

    <!--div class="form-group">
        <label for="tax" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax", $taxes_dropdown, array($model_info->tax), "class='select2 tax-select2'");
            ?>
        </div>
    </div-->

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-form").appForm({
            onSuccess: function (result) {
                $("#item-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        // $("#item-form .tax-select2").select2();
         var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });
    function applySelect2OnItemTitle() {
        $("#hsn_code").select2({
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
                $("#hsn_code").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#gst").val()) {
                                $("#gst").val(response.item_info.gst);
                            }
                           if (!$("#hsn_description").val()) {
                                $("#hsn_description").val(response.item_info.hsn_description);
                            }

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }
</script>
<script type="text/javascript">
    $("#hsn_code").on("click", function() {
   
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

</script>
*/?>
<?php echo form_open(get_uri("items/save"), array("id" => "item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="col-md-12 text-off"> <?php echo lang('item_edit_instruction'); ?></div>
        </div>
    <?php } ?>
 <input type="hidden" name="add_new_product_id_to_library" value="" id="add_new_product_id_to_library" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('product_id'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('product_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="product_id_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
     <div class="form-group">
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="category" class=" col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "category",
                "name" => "category",
                "value" => $model_info->category,
                "class" => "form-control",
                "placeholder" => lang('category')
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "make",
                "name" => "make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make')
                
            ));
            ?>
        </div>
    </div>
    

    <div class="form-group">
        <label for="item_stock" class=" col-md-3"><?php echo lang('stock'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_stock",
                "name" => "item_stock",
                "value" => $model_info->stock ? $model_info->stock : "",
                "class" => "form-control",
                "placeholder" => lang('no_stock'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
   
    <div class="form-group">
        <label for="unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "unit_type",
                "name" => "unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id = 'orginal_rate'>
        <label for="item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_rate",
                "name" => "item_rate",
                "value" => $model_info->rate ? $model_info->rate : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
                                "class" => "form-control",
                                "placeholder" => lang('associated_with_part_no')
                            ));
                            ?>
                        </div>
                    </div>
                    </div>
    <div class="form-group">
        <label for="profit_percentage" class="col-md-3"><?php echo lang('profit_percentage'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "profit_percentage",
                "name" => "profit_percentage",
                "value" => $model_info->profit_percentage ? $model_info->profit_percentage : "",
                "class" => "form-control",
                "placeholder" => lang('profit_percentage'),
                
            ));
            ?>
        </div>
        </div>

    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="hsn_code" class=" col-md-3"><?php echo lang('hsn_sac_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "hsn_code",
                "name" => "hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_hsn_code'),
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "gst",
                "name" => "gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "placeholder" => lang('gst'),
               // "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>
  <div class="form-group">
        <label for="hsn_description" class=" col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "hsn_description",
                "name" => "hsn_description",
                "value" => $model_info->hsn_description,
                "class" => "form-control",
                "placeholder" => lang('hsn_description'),
               // "readonly" => 'true',

               
            ));
            ?>
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
        $("#item-form").appForm({
            onSuccess: function (result) {
                $("#item-table").appTable({newData: result.data, dataId: result.id});
            }

        });
$("#associated_with_part_no").select2({
            multiple: true,
            data: <?php echo ($part_no_dropdown); ?>
        });
        // $("#item-form .tax-select2").select2();
         var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })
        var ishsnUpdate = "<?php echo $model_info->id; ?>";
        if (!ishsnUpdate) {
            applySelect2OnHsnTitle();
        }

        $("#product_id_title_dropdwon_icon").click(function () {
            applySelect2OnHsnTitle();
        })


    });

    function applySelect2OnItemTitle() {
        $("#hsn_code").select2({
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
                $("#hsn_code").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#gst").val()) {
                                $("#gst").val(response.item_info.gst);
                            }
                           if (!$("#hsn_description").val()) {
                                $("#hsn_description").val(response.item_info.hsn_description);
                            }

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }

    function applySelect2OnHsnTitle() {
        $("#title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("items/get_inventory_product_id_suggestion"); ?>",
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
                $("#rate").show();
                $("#orginal_rate").remove().val();
                $("#item_rate").remove().val();
                $("#title").select2("destroy").val("").focus();
                $("#add_new_product_id_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_product_id_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("items/get_inventory_product_id_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#description").val()) {
                                $("#description").val(response.item_info.description);
                            }
                           if (!$("#make").val()) {
                                $("#make").val(response.item_info.make);
                            }
                            if (!$("#category").val()) {
                                $("#category").val(response.item_info.category);
                            }
                            if (!$("#item_rate").val()) {
                                $("#item_rate").val(response.item_info.total);
                            }


                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }



</script>
<script type="text/javascript">
    $("#hsn_code").on("click", function() {
   
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

</script>
<script type="text/javascript">
    $("#title").on("click", function() {
   
        $("#gst").val("")
        $("#description").val("")
        //$("#invoice_unit_type").val("")
        $("#category").val("")
        $("#item_rate").val("")
        $("#make").val("")
        $("#hsn_code").select2("destroy").val("")
        $("#hsn_description").val("")
});
</script>