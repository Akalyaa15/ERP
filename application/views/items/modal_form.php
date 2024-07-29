<?php 

$rate =$model_info->rate;
        $group_list = "";
        if ($rate) {
            $groups = explode(",", $rate);
            foreach ($groups as $group) {
                if ($group) {
                     $options = array("id" => $group);
                    $list_group = $this->Part_no_generation_model->get_details($options)->row(); 
                    $group_list += $list_group->rate;
                }
            }
        }?>
<?php echo form_open(get_uri("items/save"), array("id" => "item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="col-md-12 text-off"> <?php echo lang('item_edit_instruction'); ?></div>
        </div>
    <?php } ?>
    <input type="hidden" name="product_generation_id"  id="product_generation_id" value="<?php echo $model_info->product_generation_id; ?>"/>
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
            <a id="product_id_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="close">×</span></a>
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
                "placeholder" => lang('description'),
                 "readonly"=>"true",
            ));
            ?>
        </div>
    </div>
    <input type="hidden" name="add_new_category_to_library" value="" id="add_new_category_to_library" />
    <div class="form-group">
        <label for="category" class=" col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "category",
                "name" => "category",
                "value" => $model_info->category,
                "class" => "form-control",
                "placeholder" => lang('category'),
                 "readonly"=>"true",
                
            ));
            ?>
        </div>
    </div>
    <!-- <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "make",
                "name" => "make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make'),
                "readonly"=>"true",
                
            ));
            */?>
        </div>
    </div> -->
    <input type="hidden" name="add_new_make_to_library" value="" id="add_new_make_to_library" />
    <div class="form-group">
        <label for="make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "make",
                "name" => "make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make'),
                "readonly"=>"true",
                
            ));
            /*echo form_dropdown("make", $make_dropdown, array($model_info->make), "class='select2' id='make' readonly='true'");*/
            ?>
        </div>
    </div>
    

    <div class="form-group">
        <label for="item_stock" class=" col-md-3"><?php echo lang('stock'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "item_stock",
                "name" => "item_stock",
                "value" => $model_info->stock ? $model_info->stock : "",
                "class" => "form-control",
                "min" => 0,
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
                "class" => "form-control validate-hidden",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)',
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <input type="hidden" name="rate_id"  id="rate_id" value="<?php echo $model_info->rate; ?>" />
    <div class="form-group" id = 'orginal_rate'>
        <label for="item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "item_rate",
                "name" => "item_rate",
                "value" => $group_list ? $group_list : "",
                "class" => "form-control",
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
    <div class="form-group">
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
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="hsn_code_close">×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "gst",
                "name" => "gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('gst'),
                "readonly" => 'true',

               
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
                "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>

    <div class="form-group" id = 'install_rate'>
        <label for="item_rate" class=" col-md-3"><?php echo lang('installation_rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_rate",
                "name" => "installation_rate",
                "value" => $model_info->installation_rate ? $model_info->installation_rate : "",
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('installation_rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
               // "readonly"=>"true",
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="installation_profit_percentage" class="col-md-3"><?php echo lang('installation_profit_percentage'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_profit_percentage",
                "name" => "installation_profit_percentage",
                "value" => $model_info->installation_profit_percentage ? $model_info->installation_profit_percentage : "",
                "class" => "form-control",
                "min"=>0,
                "max"=>100,
                "placeholder" => lang('installation_profit_percentage'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                
            ));
            ?>
        </div>
        </div>
       <input type="hidden" name="add_new_installation_item_to_library" value="" id="add_new_installation_item_to_library" />
    <div class="form-group">
        <label for="installation_hsn_code" class=" col-md-3"><?php echo lang('hsn_sac_code'); ?></label>
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
            ));
            ?>
            <a id="installation_hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="install_hsn_code_close">×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="gst" class=" col-md-3"><?php echo lang('installation_gst'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "installation_gst",
                "name" => "installation_gst",
                "value" => $model_info->installation_gst,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('installation_gst'),
               "readonly" => 'true',

               
            ));
            ?>
        </div>
    </div>
  <div class="form-group">
        <label for="installation_hsn_description" class=" col-md-3"><?php echo lang('installation_hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "installation_hsn_description",
                "name" => "installation_hsn_description",
                "value" => $model_info->installation_hsn_description,
                "class" => "form-control",
                "placeholder" => lang('installation_hsn_description'),
               "readonly" => 'true',

               
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
       // $("#item-form .select2").select2();
$("#associated_with_part_no").select2({
            multiple: true,
            data: <?php echo ($part_no_dropdown); ?>
        });

$("#make").select2({
            multiple: false,
            data: <?php echo ($make_dropdown); ?>
        });

$("#category").select2({
            multiple: false,
            data: <?php echo ($product_categories_dropdown); ?>
        });

<?php if (isset($unit_type_dropdown)) { ?>
            $("#unit_type").select2({
                multiple: false,
                data: <?php echo json_encode($unit_type_dropdown); ?>
            });
<?php } ?>
        // $("#item-form .tax-select2").select2();
         var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        $("#product_id_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnHsnTitle();
        })
        var ishsnUpdate = "<?php echo $model_info->id; ?>";
        if (!ishsnUpdate) {
            applySelect2OnHsnTitle();
        }

        $("#installation_hsn_code_dropdwon_icon").click(function () {
            applySelect2OnInstallationHsnTitle();
        })
        var isinstallationhsnUpdate = "<?php echo $model_info->id; ?>";
        if (!isinstallationhsnUpdate) {
            applySelect2OnInstallationHsnTitle();
        }

        




    });
    <?php if($model_info->hsn_code){?>
$('#hsn_code').attr('readonly', true);
<?php } ?>
<?php if($model_info->installation_hsn_code){?>
$('#installation_hsn_code').attr('readonly', true);
<?php } ?>
<?php if($model_info->title){?>
$("#title").attr('readonly', true);
<?php } ?>


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
                $("#installation_hsn_code").select2("destroy").val("").focus();
                $("#installation_hsn_code").val("").attr('readonly', false);
                $("#installation_gst").val("").attr('readonly', false);
                $("#installation_hsn_description").val("").attr('readonly', false);
                $("#add_new_installation_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_installation_item_to_library").val(""); //reset the flag to add new item in library
                 $("#installation_gst").val("").attr('readonly', true);
                 $("#installation_hsn_description").val("").attr('readonly', true);
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                           
                                $("#installation_gst").val(response.item_info.gst);
                            
                           
                                $("#installation_hsn_description").val(response.item_info.hsn_description);
                            

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }

    function applySelect2OnHsnTitle() {
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
                $("#gst").val("").attr('readonly', false);
                $("#hsn_code").val("").attr('readonly', false);
                $("#hsn_description").val("").attr('readonly', false);
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                 $("#gst").val("").attr('readonly', true);
                $("#hsn_description").val("").attr('readonly', true);
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                                $("#gst").val(response.item_info.gst);
                            
                           
                                $("#hsn_description").val(response.item_info.hsn_description);
                            

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }

    function applySelect2OnItemTitle() {
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
                $("#rate_id").val("");
                $("#orginal_rate").remove().val();
                $("#item_rate").remove().val();
                //$("#category").val("").attr('readonly', false);
                $("#category").select("val","").attr('readonly', false);
                $("#title").val("").attr('readonly', false);
                $("#make").select2("val", "").attr('readonly', false);
                $("#description").val("").attr('readonly', false);
                $("#associated_with_part_no").select2("val","").attr('readonly', false);
                $("#title").select2("destroy").val("").focus();
                $("#add_new_product_id_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_product_id_to_library").val(""); //reset the flag to add new item in library
                $("#category").val("").attr('readonly', true);
                $("#make").val("").attr('readonly', true);
                $("#description").val("").attr('readonly', true);
                 $("#rate").hide();
                 $("#associated_with_part_no").val("").attr('readonly', true);
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
                          /* if (!$("#make").val()) {
                                $("#make").val(response.item_info.make);
                            }*/

                                $("#make").select2("val",response.item_info.make);
                            

                            if (!$("#category").val()) {
                                //$("#category").val(response.item_info.category);
                                $("#category").select2("val",response.item_info.category);
                            }
                            if (!$("#rate_id").val()) {
                                $("#rate_id").val(response.item_info.associated_with_part_no);
                            }
                            if (!$("#product_generation_id").val()) {
                                $("#product_generation_id").val(response.item_info.id);
                            }
                            // $("#associated_with_part_no").select2( "val",[response.item_info.associated_with_part_no]);
                            if (!$("#item_rate").val()) {
                               // $("#item_rate").val(response.item_info.total);
                               $.ajax({
                    url: "<?php echo get_uri("items/assoc_details"); ?>",
                    data: {item_name: response.item_info.associated_with_part_no},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            $("#item_rate").val(response.assoc_rate);
                        }}
                    });
                            }


                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }



</script>
<script type="text/javascript">
    $("#hsn_code_close").on("click", function() {
    $("#hsn_code").val("").attr('readonly', false)
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});
    $("#hsn_code").on("change", function() {
   
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

    $("#install_hsn_code_close").on("click", function() {
    $("#installation_hsn_code").val("").attr('readonly', false)
        $("#installation_gst").val("")
        $("#installation_hsn_description").val("")
    
    
});
    $("#installation_hsn_code").on("change", function() {
   
        $("#installation_hsn_gst").val("")
        $("#installation_hsn_description").val("")
    
    
});

</script>
<script type="text/javascript">
    $("#close").on("click", function() {
        $("#title").val("").attr('readonly', false)
        //$("#gst").val("")
        $("#description").val("")
        $("#rate_id").val("")
        //$("#invoice_unit_type").val("")
        //$("#category").val("")
        $("#category").select2("val", " ")
        $("#item_rate").val("")
        $("#make").select2("val", " ")
        $("#product_generation_id").val("") 

        //$("#hsn_code").select2("destroy").val("")
        //$("#hsn_description").val("")
});
</script>
<script type="text/javascript">
    $("#title").on("change", function() {
   
        //$("#gst").val("")
        $("#description").val("")
        $("#rate_id").val("")
        //$("#invoice_unit_type").val("")
        //$("#category").val("")
        $("#category").select2("val", " ")
        $("#item_rate").val("")
        $("#make").select2("val", " ")
        $("#product_generation_id").val("") 
       // $("#hsn_code").select2("destroy").val("")
       // $("#hsn_description").val("")
});

    $("#make").on("change",function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                
                $("#make").select2("destroy").val("").focus();
                $("#add_new_make_to_library").val(1); //set the flag to add new item in library
            }
        });
    $("#category").on("change",function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                
                $("#category").select2("destroy").val("").focus();
                $("#add_new_category_to_library").val(1); //set the flag to add new item in library
            }
        });
</script>