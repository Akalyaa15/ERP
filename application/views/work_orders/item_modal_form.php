<?php 
if($buyer_type){
    $options = array(
        "id" => $buyer_type,
    );
    $list_data = $this->Buyer_types_model->get_details($options)->row();
}
?>
<?php if($model_info->with_gst == "no") { ?>
<style>
    #s, #y, #z {
        display: none;
    }
</style>
<?php } ?>
<?php /* echo $buyer_type; */ ?>

<?php 
$vendor_buyer_type_name = "";
if (isset($list_data) && !empty($list_data)) {
    $vendor_buyer_type_name = $list_data->buyer_type;
}

// echo $vendor_buyer_type_name;
?>
<?php /* echo $country; */ ?>
<br>
<div class="form-group">
    <label for="discount" class="col-md-3"></label>
    <div class="col-md-9">
        <span id='foreign_message'></span>
    </div>
</div>
<?php echo form_open(get_uri("work_orders/save_item"), array("id" => "work_order-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" id="work_order_ids" name="work_order_id" value="<?php echo $work_order_id; ?>" />
    <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />
    <div class="form-group">
        <label for="work_order_item_title" class=" col-md-3"><?php echo lang('job_id'); ?></label>
         <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "work_order_item_title",
                "name" => "work_order_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_outsource_job'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="work_order_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="work_order_item_category" class=" col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "work_order_item_category",
                "name" => "work_order_item_category",
                "value" => $model_info->category,
                "class" => "form-control",
                "placeholder" => lang('category'),
                "readonly" =>"true",
                
            ));
            ?>
        </div>
    </div>
    <!--div class="form-group">
        <label for="work_order_item_make" class=" col-md-3"><?php echo lang('make'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "work_order_item_make",
                "name" => "work_order_item_make",
                "value" => $model_info->make,
                "class" => "form-control",
                "placeholder" => lang('make')
                
            ));
           */ ?>
        </div>
    </div-->
    
    <div class="form-group">
        <label for="work_order_item_description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "work_order_item_description",
                "name" => "work_order_item_description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "readonly" =>"true",
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="work_order_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "work_order_item_quantity",
                "name" => "work_order_item_quantity",
                "value" => $model_info->quantity ? $model_info->quantity : "",
                "class" => "form-control",
                "min"=>0,
                "maxlength"=> get_setting('number_of_quantity'),
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="work_order_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "work_order_unit_type",
                "name" => "work_order_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control validate-hidden",
                "readonly" =>"true",
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
                "value" => $vendor_buyer_type_name,
                "class" => "form-control",
                "placeholder" => lang('buyer_type'),
                
                "readonly"=>"true",
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="work_order_item_rate" class=" col-md-3"><?php echo lang('rate'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "work_order_item_rate",
                "name" => "work_order_item_rate",
                //"value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "value" => $model_info->rate ?  $model_info->rate:"",
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('rate'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "readonly" =>"true",
            ));
            ?>
        </div>
    </div>

<div class="form-group" id="gst_app">
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
                "id" => "work_order_item_hsn_code",
                "name" => "work_order_item_hsn_code",
                "value" => $model_info->hsn_code,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_or_create_new_hsn_code'),
                "readonly"=>true,
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close_hsn_code">×</span></a>
        </div>
    </div>
      </div>
     <div class="form-group" id="y" >
        <label for="work_order_item_gst" class=" col-md-3"><?php echo lang('gst'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "work_order_item_gst",
                "name" => "work_order_item_gst",
                "value" => $model_info->gst,
                "class" => "form-control",
                "min"=>0,
                "placeholder" => lang('gst'),
               "readonly" =>"true",
                
            ));
            ?>
        </div>
    </div>
    <div class="form-group" id="z">
        <label for="work_order_item_hsn_description" class="col-md-3"><?php echo lang('hsn_description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
             "id" => "work_order_item_hsn_code_description",
            "name" => "work_order_item_hsn_code_description",
             "value" => $model_info->hsn_description ? $model_info->hsn_description : "",
                "class" => "form-control",
                "placeholder" => lang('hsn_description'),
                "readonly" =>"true",
            ));
            ?>
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

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#work_order-item-form").appForm({
            onSuccess: function (result) {
                location.reload();
                $("#work_order-item-table").appTable({newData: result.data, dataId: result.id});
                $("#work_order-total-section").html(result.work_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.work_order_id);
                }
            }
        });

        <?php  if (isset($unit_type_dropdown)) { ?>
            $("#work_order_unit_type").select2({
                multiple: false,
                data: <?php echo json_encode($unit_type_dropdown); ?>
            });
<?php }  ?> 

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#work_order_item_title_dropdwon_icon").click(function () {
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
        <?php if($model_info->hsn_code) { ?>
$('#work_order_item_hsn_code').attr('readonly', true);
<?php } ?>
<?php if($model_info->hsn_code) { ?>
$("#work_order_item_title").attr('readonly', true);
<?php } ?> 
    });

    function applySelect2OnItemTitle() {
        $("#work_order_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("work_orders/get_estimate_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term ,s:$("#work_order_ids").val()// search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
               // $("#work_order_item_title").select2("destroy").val("").focus();
               $("#work_order_item_title").select2("destroy").val("").focus().attr('readonly', false);
                $("#work_order_item_description").val("").attr('readonly', false);
        $("#work_order_unit_type").val("").attr('readonly', false);
        $("#work_order_item_category").val("").attr('readonly', false);
        $("#work_order_item_rate").val("").attr('readonly', false);
         $("#work_order_item_hsn_code").select2("destroy").val("").attr('readonly', false);
        
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("work_orders/get_estimate_item_info_suggestion");?>",
                    data: {item_name: e.val,s:"<?php echo $work_order_id; ?>"},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#work_order_item_description").val()) {
                                $("#work_order_item_description").val(response.item_info.description);
                            }

                            if (!$("#work_order_unit_type").val()) {
                                /*$("#work_order_unit_type").val(response.item_info.unit_type);*/
                                $("#work_order_unit_type").select2('val', response.item_info.unit_type);
                            }

                            if (!$("#work_order_item_rate").val()) {
                                 a=response.item_infos;
                                 b=response.item_info.rate;
                                c=a*b;
                                if(a=="failed"||a == null){
                                    alert("Sorry,Currency conversion cannot be done");
                                   c=0; 
                                   $("#work_order_item_rate").val(c).attr('readonly', false);
                                }else if(a=="same_country"){
                                   //alert("Same Country");
                                   c=b; 
                                   $("#work_order_item_rate").val(c).attr('readonly', true);
                                }else{
                                $("#work_order_item_rate").val(c).attr('readonly', true);
                            }
                            }
                            if (!$("#work_order_item_category").val()) {
                                $("#work_order_item_category").val(response.item_info.category);
                            }
                            
                            if (!$("#work_order_item_make").val()) {
                                $("#work_order_item_make").val(response.item_info.make);
                            }
                            if (!$("#work_order_item_hsn_code").val()) {
                                $("#work_order_item_hsn_code").val(response.item_info.hsn_code);
                            }
                            if (!$("#work_order_item_hsn_code_description").val()) {
                                $("#work_order_item_hsn_code_description").val(response.item_info.hsn_description);
                            }
                            if (!$("#work_order_item_gst").val()) {
                                $("#work_order_item_gst").val(response.item_info.gst);
                            }
                        }
                    }
                });
            }

        });
    }

    function applySelect2OnHsnTitle() {
        $("#work_order_item_hsn_code").select2({
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
                //$("#work_order_item_hsn_code").select2("destroy").val("").focus();
                $("#work_order_item_hsn_code").select2("destroy").val("").focus().attr('readonly', false);
                $("#work_order_item_gst").val("").attr('readonly', false);
                $("#work_order_item_hsn_code_description").val("").attr('readonly', false);
                $("#add_new_item_to_librarys").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_librarys").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("items/get_invoice_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#work_order_item_gst").val()) {
                                $("#work_order_item_gst").val(response.item_info.gst);
                            }
                           if (!$("#work_order_item_hsn_code_description").val()) {
                                $("#work_order_item_hsn_code_description").val(response.item_info.hsn_description);
                            }

                            

                            
                            
                            
                            
                            
                        }
                    }
                });
            }

        });
    }




</script>
<script type="text/javascript">
    $("#work_order_item_title").on("change", function() {
   
        $("#work_order_item_gst").val("")
        $("#work_order_item_description").val("")
        //$("#work_order_unit_type").val("")
        $("#work_order_unit_type").select2('val',"")
        $("#work_order_item_category").val("")
        $("#work_order_item_rate").val("")
        $("#work_order_item_make").val("")
        $("#work_order_item_hsn_code").select2("destroy").val("")
        $("#work_order_item_hsn_code_description").val("")
});
</script>
<script type="text/javascript">
    $("#close").on("click", function() {
   $("#work_order_item_title").val("").attr('readonly', false)
        $("#work_order_item_gst").val("")
        $("#work_order_item_description").val("")
        //$("#work_order_unit_type").val("")
        $("#work_order_unit_type").select2('val',"")
        $("#work_order_item_category").val("")
        $("#work_order_item_rate").val("")
        $("#work_order_item_make").val("")
        $("#work_order_item_hsn_code").select2("destroy").val("")
        $("#work_order_item_hsn_code_description").val("")
});
</script>
<script type="text/javascript">
    $("#work_order_item_hsn_code").on("change", function() {
   
        $("#work_order_item_gst").val("")
       
        $("#work_order_item_hsn_code_description").val("")
});
</script>
<script type="text/javascript">
    $("#close_hsn_code").on("click", function() {
    $("#work_order_item_hsn_code").val("").attr('readonly', false)
        $("#work_order_item_gst").val("")
       
        $("#work_order_item_hsn_code_description").val("")
});
</script>
<script type="text/javascript">
    $("#without_gst").on("click", function() {
   
        $("#work_order_item_hsn_code").attr('readonly', true)
        $("#s").hide()
        $("#y").hide()
        $("#z").hide()
       
        $("#work_order_item_hsn_code_description").hide()
        $("#work_order_item_gst").hide()
});
</script>
<script type="text/javascript">
    $("#with_gst").on("click", function() {
   
        $("#work_order_item_hsn_code").attr('reqiured', true)
        $("#s").show()
        $("#y").show()
        $("#z").show()
       
        $("#work_order_item_hsn_code_description").show()
        $("#work_order_item_gst").show()


});
</script>
<?php 
$company_country=get_setting("company_country");

if($company_country!=$country)
{?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#without_gst").click() 
$("#gst_app").hide() 
$('#foreign_message').html('GST is not applicable for this foreign client ').css('color', 'red');

});
</script>
<?php } ?>
