<?php $options = array("id" => $estimate_id);
            $statuss = $this->Delivery_model->get_details($options)->row();  ?>
<?php echo form_open(get_uri("delivery/save_item"), array("id" => "estimate-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_id"  id="estimate_ids" value="<?php echo $estimate_id; ?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <input type="hidden" name="add_new_item_to_librarys" value="" id="add_new_item_to_librarys" />
<?php if(!$model_info->title){?>
  <div class="form-group">
        <label for="estimate_item_titles" class=" col-md-3"><?php echo lang('model'); ?></label>
        <div class="col-md-9">
            <?php

            echo form_input(array(
                "id" => "estimate_item_titles",
                "name" => "estimate_item_titles",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_delivery_product'),
                //"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="estimate_item_title_dropdwon_icons" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div><?php }else if($model_info->is_tool=="1"){?>
    <div class="form-group">
        <label for="estimate_item_titles" class=" col-md-3"><?php echo lang('model'); ?></label>
        <div class="col-md-9">
            <?php

            echo form_input(array(
                "id" => "estimate_item_titles",
                "name" => "estimate_item_titles",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_delivery_product'),
                //"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="estimate_item_title_dropdwon_icons" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div><?php } ?>
    <?php if(!$model_info->title){?>
  <div class="form-group">
        <label for="estimate_item_titles" class=" col-md-3"><?php echo lang('tool'); ?></label>
        <div class="col-md-9">
            <?php

            echo form_input(array(
                "id" => "estimate_item_title",
                "name" => "estimate_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_delivery_tool'),
                //"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="estimate_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div><?php }else if(!$model_info->is_tool=="1"){?>
    <div class="form-group">
        <label for="estimate_item_titles" class=" col-md-3"><?php echo lang('tool'); ?></label>
        <div class="col-md-9">
            <?php

            echo form_input(array(
                "id" => "estimate_item_title",
                "name" => "estimate_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_delivery_tool'),
                //"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="estimate_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div><?php } ?>
    <div class="form-group">
        <label for="estimate_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "estimate_item_quantity",
                "name" => "estimate_item_quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "min" =>"1",
                //"max"=>"5",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
   <div id="sasai">
  <div class="form-group">
        <label for="estimate_item_description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_item_description",
                "name" => "estimate_item_description",
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
        <label for="estimate_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_unit_type",
                "name" => "estimate_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
        <div class="form-group" id="gstapp">
                <label for="invoice_recurring" class=" col-md-3"><?php echo lang('price_visibility'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('price_visibility'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "price_yes",
                        "name" => "price_visibility",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->price_visibility === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('yes'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "price_no",
                        "name" => "price_visibility",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->price_visibility === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('no'); ?></label>
                </div>
            </div>
    <div class="form-group" id='rates'>
        <label for="estimate_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_item_rate",
                "name" => "estimate_item_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div></div>
        <?php  if($statuss->status=='ret_sold'){ ?>
    <div class="form-group">
        <label for="estimate_item_quantity" class=" col-md-3"><?php echo lang('status'); ?></label>
        <div class=" col-md-9">
                   <?php
                    echo form_radio(array(
                        "id" => "ret_sold_status",
                        "name" => "ret_sold_status",
                        "data-msg-required" => lang("field_required"),
                            ), "returned", ($model_info->ret_sold_status === "returned") ? true : false);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('received'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "ret_sold_statusret_sold_status",
                        "name" => "ret_sold_status",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "sold", ($model_info->ret_sold_status === "returned") ? false : true);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('sold'); ?></label>
    </div></div>
    <div class="form-group">
        <label for="estimate_item_quantity" class=" col-md-3"><?php echo lang('quantitys'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "ret_sold",
                "name" => "ret_sold",
                "value" => $model_info->ret_sold,
                "class" => "form-control",
                "min" =>"1",
                "max"=>$model_info->quantity,
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>
    </div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
       var product_chosen = $("#estimate_item_titles").val();
        if (product_chosen) {
            pq();
        }
        var tool_chosen = $("#estimate_item_title").val();
        if (tool_chosen) {
            tq();
        }
        $("#estimate-item-form").appForm({
            onSuccess: function (result) {
                $("#estimate-item-table").appTable({newData: result.data, dataId: result.id});
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }
            }
        });

<?php  if (isset($unit_type_dropdown)) { ?>
            $("#estimate_unit_type").select2({
                multiple: false,
                data: <?php echo json_encode($unit_type_dropdown); ?>
            });
<?php }  ?> 

$("#make").select2({
            multiple: false,
            data: <?php echo ($make_dropdown); ?>
        });
$("#category").select2({
            multiple: false,
            data: <?php echo ($product_categories_dropdown); ?>
        });
        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#estimate_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    function applySelect2OnItemTitle() {
        $("#estimate_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("delivery/get_delivery_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,s:$("#estimate_ids").val() // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#estimate_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("delivery/get_delivery_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                                $("#estimate_item_description").val(response.item_info.description).attr('readonly', true);
                            
                               // $("#make").val(response.item_info.make).attr('readonly', true);
                                  $("#make").select2("val",response.item_info.make).attr('readonly', true);

                           
                                //$("#category").val(response.item_info.category).attr('readonly', true);
                                $("#category").select2("val",response.item_info.category).attr('readonly', true);
                            
                           
                                /*$("#estimate_unit_type").val(response.item_info.unit_type).attr('readonly', true);*/
                           
$("#estimate_unit_type").select2('val', response.item_info.unit_type).attr('readonly', true);
                           
                                $("#estimate_item_rate").val(response.item_info.rate).attr('readonly', true);
                            
                    if (!$("#estimate_item_quantity").val()) {
                                $("#estimate_item_quantity").attr({
       "max" : response.item_info.quantity,        // substitute your own
                // values (or variables) here
    });
                            }
                        }
                    }
                });
            }

        });
    }
$(document).ready(function () {
        $("#estimate-item-form").appForm({
            onSuccess: function (result) {
                $("#estimate-item-table").appTable({newData: result.data, dataId: result.id});
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitles();
        }

        //re-initialize item suggestion dropdown on request
        $("#estimate_item_title_dropdwon_icons").click(function () {
            applySelect2OnItemTitles();
        })

    });

    function applySelect2OnItemTitles() {
        $("#estimate_item_titles").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("delivery/get_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,s:$("#estimate_ids").val() // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#estimate_item_titles").select2("destroy").val("").focus();
                $("#add_new_item_to_librarys").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_librarys").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("delivery/get_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                                $("#estimate_item_description").val(response.item_info.description).attr('readonly', true);
                            

                               // $("#make").val(response.item_info.make).attr('readonly', true);
                                $("#make").select2("val",response.item_info.make).attr('readonly', true);
                        

                         
                                //$("#category").val(response.item_info.category).attr('readonly', true);
                                $("#category").select2("val",response.item_info.category).attr('readonly', true);
                            
                            
                                /*$("#estimate_unit_type").val(response.item_info.unit_type).attr('readonly', true);*/
                            $("#estimate_unit_type").select2('val', response.item_info.unit_type).attr('readonly', true);

                            
                               // $("#estimate_item_rate").val(response.item_info.rate).attr('readonly', true);
                                $.ajax({
                    url: "<?php echo get_uri("delivery/assoc_details"); ?>",
                    data: {item_name: response.item_info.rate},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            $("#estimate_item_rate").val(response.assoc_rate);
                        }}
                    });
                            
                    if (!$("#estimate_item_quantity").val()) {
                                $("#estimate_item_quantity").attr({
       "max" : response.item_info.stock,        // substitute your own
                // values (or variables) here
    });
                            }
                        }
                    }
                });
            }

        });
    }
function pq() {
        $.ajax({
                    url: "<?php echo get_uri("delivery/get_item_info_suggestion"); ?>",
                    data: {item_name:$("#estimate_item_titles").val() },
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            
                    
                                $("#estimate_item_quantity").attr({
       "max" : response.item_info.stock,        // substitute your own
                // values (or variables) here
    });
                            
                        }
                    }
                });
    }
function tq() {
        $.ajax({
                    url: "<?php echo get_uri("delivery/get_delivery_item_info_suggestion"); ?>",
                    data: {item_name: $("#estimate_item_title").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                    
                                $("#estimate_item_quantity").attr({
       "max" : response.item_info.quantity,        // substitute your own
                // values (or variables) here
    });
                           
                        }
                    }
                });
    }


</script><script>
$( "#estimate_item_title" ).change(function() {
    $("#estimate_item_quantity").val("");
$("#estimate_item_titles").select2("destroy").val("");
 applySelect2OnItemTitles();
});
$( "#estimate_item_titles" ).change(function() {
$("#estimate_item_quantity").val("");
$("#estimate_item_title").select2("destroy").val("");
 applySelect2OnItemTitle();
});
</script>
<script>
$( "#price_no" ).click(function() {
    $("#rates").hide();
});
$( "#price_yes" ).click(function() {
    $("#rates").show();
});
</script>
<?php if($model_info->price_visibility=='no'){ ?>
<script type="text/javascript">
    $( document ).ready(function() {
   $("#rates").hide();
});
</script>
<?php } ?>
<?php if($statuss->status=='ret_sold'){ ?>
<script type="text/javascript">
    $( document ).ready(function() {
   $("#sasai").hide();
$("#estimate_item_title").attr('readonly', true);
$("#estimate_item_titles").attr('readonly', true);
$("#estimate_item_quantity").attr('readonly', true);

});
</script>
<?php } ?>