<?php if($model_info->with_gst=="no") { ?>
<style>
      #s,#y,#z,#a{
        display:none;
      }
</style>
<?php } ?>
<?php /* echo $country; */ ?>
<div class="form-group">
        <label for="discount" class="col-md-3"></label>
        <div class="col-md-9">
            <span id='foreign_message'></span>
        </div>
        </div>
<?php  echo form_open(get_uri("estimates/save_freight"), array("id" => "freight-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="estimate_id" value="<?php echo 
    $model_info->id; ?>" /><div class="form-group">
        <label for="freight_amount" class="col-md-3"><?php echo lang('freight'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount ?$model_info->amount : "",
                "class" => "form-control",
                "min"=>0,
                "autofocus" => "true",
                "placeholder" => lang('freight_amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                
            ));
            ?>
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
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
                "autofocus" => "true",
                "placeholder" => lang('freight_amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                
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
        $("#freight-form").appForm({
            onSuccess: function (result) {
                location.reload(true)
                if (result.success && result.estimate_total_view) {
                    $("#estimate-total-section").html(result.estimate_total_view);
                } else {
                    appAlert.error(result.message);
                }
            }
        });
        var isUpdate = "<?php echo $model_info->hsn_code; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

<?php if($model_info->hsn_code) { ?>
$('#hsn_code').attr('readonly', true);
<?php } ?>
        //$("#discount-form .select2").select2();
    });
    function applySelect2OnItemTitle() {
        $("#hsn_code").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("estimates/get_invoice_freight_suggestion"); ?>",
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
               $("#hsn_code").select2("destroy").val("").focus().attr('readonly', false);
               $("#gst").val("").attr('readonly', false);
               $("#hsn_description").val("").attr('readonly', false);


                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("estimates/get_invoice_freight_info_suggestion"); ?>",
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
    $("#hsn_code").on("change", function() {
   
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

</script>
<script type="text/javascript">
    $("#close_hsn_code").on("click", function() {
       $("#hsn_code").val("").attr('readonly', false)
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

</script>
<script type="text/javascript">
    $("#without_gst").on("click", function() {
   
        $("#hsn_code").attr('readonly', true)
        $("#s").hide()
        $("#s").hide()
        $("#y").hide()
        $("#z").hide()
        $("#a").hide()
        $("#without_inclusive_tax").click()
        $("#hsn_code_description").hide()
        $("#gst").hide()
});
</script>
<script type="text/javascript">
    $("#with_gst").on("click", function() {
   
        $("#hsn_code").attr('readonly', false)
        $("#s").show()
        $("#y").show()
        $("#z").show()
       $("#a").show()
        $("#hsn_code_description").show()
        $("#gst").show()
});
</script>
<?php 

if($model_info->with_inclusive_tax=="yes")
{?>
<script type="text/javascript" >
$( document ).ready(function() {
var freight_amount = $("#freight_amount").val();
$("#amount").val(freight_amount);

});
</script>
<?php } ?>
<?php 
$company_country=get_setting("company_country");

if($company_country!=$country)
{?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#without_gst").click() 
$("#gstapp").hide() 
$('#foreign_message').html('GST is not applicable for this foreign client ').css('color', 'red');

});
</script>
<?php } ?>




<?php /* echo form_open(get_uri("estimates/save_freight"), array("id" => "freight-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="estimate_id" value="<?php echo 
    $model_info->id; ?>" />
<div class="form-group">
        <label for="freight_amount" class="col-md-3"><?php echo lang('freight'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "freight_amount",
                "name" => "freight_amount",
                "value" => $model_info->freight_amount ? $model_info->freight_amount : "",
                "class" => "form-control",
                "autofocus" => "true",
                "placeholder" => lang('freight_amount'),
                
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
                "placeholder" => lang('select_or_create_new_item'),
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="hsn_code_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close_hsn_code">×</span></a>
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
               "readonly" => "true",

               
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
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#freight-form").appForm({
            onSuccess: function (result) {
                location.reload(true)
                if (result.success && result.estimate_total_view) {
                    $("#estimate-total-section").html(result.estimate_total_view);
                } else {
                    appAlert.error(result.message);
                }
            }
        });
        var isUpdate = "<?php echo $model_info->hsn_code; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#hsn_code_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

$('#hsn_code').attr('readonly', true);
        //$("#discount-form .select2").select2();
    });
    function applySelect2OnItemTitle() {
        $("#hsn_code").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("estimates/get_invoice_freight_suggestion"); ?>",
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
               $("#hsn_code").select2("destroy").val("").focus().attr('readonly', false);
               $("#gst").val("").attr('readonly', false);
               $("#hsn_description").val("").attr('readonly', false);


                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("estimates/get_invoice_freight_info_suggestion"); ?>",
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
    $("#hsn_code").on("change", function() {
   
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

</script>
<script type="text/javascript">
    $("#close_hsn_code").on("click", function() {
       $("#hsn_code").val("").attr('readonly', false)
        $("#gst").val("")
        $("#hsn_description").val("")
    
    
});

</script>