<?php 
if($model_info->country){
 $country_no = is_numeric($model_info->country);
 if(!$country_no){
   $model_info->country = 0;
 }
 $options = array(
            "numberCode" => $model_info->country,
                   );
        $country_id_name = $this->Countries_model->get_details($options)->row();
       
        $country_dummy_name =$country_id_name->countryName;
        if($country_id_name->vat_name){
            $vat_name= $country_id_name->vat_name;
        }else{
            $vat_name= 'VAT';

        }} 
?>
<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" id="country_code_id" value="" />
<input type="hidden" name="view" value="<?php echo isset($view) ? $view : ""; ?>" />
<div class="form-group">
    <label for="company_name" class="<?php echo $label_column; ?>"><?php echo lang('company_name'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "company_name",
            "name" => "company_name",
            "value" => $model_info->company_name,
            "class" => "form-control",
            "placeholder" => lang('company_name'),
            "autofocus" => true,
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label for="address" class="<?php echo $label_column; ?>"><?php echo lang('address'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_textarea(array(
            "id" => "address",
            "name" => "address",
            "value" => $model_info->address ? $model_info->address : "",
            "class" => "form-control",
            "placeholder" => lang('address')
        ));
        ?>

    </div>
</div>
<div class="form-group" id= "aa">
        <label for="country" class="<?php echo $label_column; ?>"><?php echo lang('country'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "country",
                "name" => "country",
                "value" => $model_info->country,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <span id ="country_message"></span>
            
        </div>
    </div>
    <div class="form-group" id= "state_mandatory_app" style="display:none;">
                <label for="invoice_recurring" class="<?php echo $label_column; ?>"><?php echo lang('state_mandatory'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('state_mandatory'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class="<?php echo $field_column; ?>">
                    <?php
                    echo form_radio(array(
                        "id" => "state_mandatory",
                        "name" => "state_mandatory",
                        "data-msg-required" => lang("field_required"),
                            ), "yes", ($model_info->state_mandatory === "no") ? false : true);
                    ?>
                     <label for="gender_male" class="mr15"><?php echo lang('with_gst'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "state_mandatory_no",
                        "name" => "state_mandatory",
                        "data-msg-required" => lang("field_required"),
                            ), 
                    "no", ($model_info->state_mandatory === "no") ? true : false);
                    ?>
                    <label for="without_gst" class=""><?php echo lang('without_gst'); ?></label>
                </div>
            </div>
<div class="form-group">
    <label for="state" class="<?php echo $label_column; ?>"><?php echo lang('state'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "state",
            "name" => "state",
            "value" => $model_info->state,
            "class" => "form-control",
            "placeholder" => lang('state'),
            "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            //"readonly"=> "true",
        ));
        ?>
        <span id='message'></span>
    </div>
</div>
<div class="form-group">
    <label for="city" class="<?php echo $label_column; ?>"><?php echo lang('city'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "city",
            "name" => "city",
            "value" => $model_info->city,
            "class" => "form-control",
            "placeholder" => lang('city')
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label for="zip" class="<?php echo $label_column; ?>"><?php echo lang('zip'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "zip",
            "name" => "zip",
            "value" => $model_info->zip,
            "class" => "form-control",
            "placeholder" => lang('pincode_placeholder')
        ));
        ?>
    </div>
</div>
<!--div class="form-group">
    <label for="country" class="<?php echo $label_column; ?>"><?php echo lang('country'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php /*
        echo form_input(array(
            "id" => "country",
            "name" => "country",
            "value" => $model_info->country,
            "class" => "form-control",
            "placeholder" => lang('country')
        ));
        */?>
    </div>
</div-->
<div class="form-group">
    <label for="phone" class="<?php echo $label_column; ?>"><?php echo lang('phone'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "phone",
            "name" => "phone",
            "value" => $model_info->phone,
            "class" => "form-control",
            "maxlength"=>15,
            "placeholder" => lang('phone')
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label for="website" class="<?php echo $label_column; ?>"><?php echo lang('website'); ?></label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "website",
            "name" => "website",
            "value" => $model_info->website,
            "class" => "form-control",
            "placeholder" => lang('website')
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label for="gst_number" class="<?php echo $label_column; ?>">
        <span id="vat">
            <?php 
            if(!$model_info->country){ 
                echo "VAT"; 
            }
            echo isset($vat_name) ? $vat_name : "GST"; 
            ?>
        </span> Number
    </label>
    <div class="<?php echo $field_column; ?>">
        <?php
        echo form_input(array(
            "id" => "gst_number",
            "name" => "gst_number",
            "value" => $model_info->gst_number,
            "class" => "form-control",
            "placeholder" => lang('gst_number')
        ));
        ?>
    </div>
</div>
<!--div class="form-group">
        <label for="gstin_number_first_two_digits" class="<?php echo $label_column; ?>"></label>
        <div class="<?php echo $field_column; ?>">
            <span id='message'></span>
        </div>
        </div-->
        <div class="form-group">
    <label for="gstin_number_first_two_digits" class="<?php echo $label_column; ?>">
        <span id="vats">
            <?php 
            if (isset($model_info) && !$model_info->country) { 
                echo "VAT"; 
            } else {
                echo isset($vat_name) ? $vat_name : "GST"; 
            }
            ?>
        </span> 
        <?php echo lang('gstinnumber_firsttwodigits'); ?>
    </label>
    <div class="<?php echo $field_column; ?>">
        <?php
        // Check if $model_info is set and not null
        $gstin_number_first_two_digits = isset($model_info) ? $model_info->gstin_number_first_two_digits : '';
        
        echo form_input(array(
            "id" => "gstin_number_first_two_digits",
            "name" => "gstin_number_first_two_digits",
            "value" => $gstin_number_first_two_digits,
            "class" => "form-control",
            "readonly" => "true",
            "placeholder" => lang('gstinnumber_firsttwodigits'),
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>


<?php if ($this->login_user->user_type === "staff") { ?>
    <div class="form-group" id="group_id">
        <label for="groups" class="<?php echo $label_column; ?>"><?php echo lang('company_groups'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "group_ids",
                "name" => "group_ids",
                "value" => $model_info->group_ids,
                "class" => "form-control",
                "placeholder" => lang('company_groups')
            ));
            ?>
        </div>
    </div>
<?php } ?>

<?php if ($this->login_user->user_type === "staff") { ?>
    <div class="form-group" id="group_id">
        <label for="groups" class="<?php echo $label_column; ?>"><?php echo lang('buyer_type'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "buyer_type",
                "name" => "buyer_type",
                "value" => $model_info->buyer_type,
                "class" => "form-control",
                "placeholder" => lang('buyer_type'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>

<div class="form-group">
        <label for="currency" class="<?php echo $label_column; ?>"><?php echo lang('currency'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "currency",
                "name" => "currency",
                "value" => $model_info->currency,
                "class" => "form-control",
                "readonly" => "true",
                "placeholder" => lang('currency')
            ));
            ?>
        </div>
    </div>    
    <div class="form-group">
        <label for="currency_symbol" class="<?php echo $label_column; ?>"><?php echo lang('currency_symbol'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "currency_symbol",
                "name" => "currency_symbol",
                "value" => $model_info->currency_symbol,
                "class" => "form-control",
                "readonly" => "true",
                //"placeholder" => lang('keep_it_blank_to_use_default') . " (" . get_setting("currency_symbol") . ")"
                "placeholder" => lang('currency_symbol') 
            ));
            ?>
        </div>
    </div>



<?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => $label_column, "field_column" => $field_column)); ?> 

<?php if ($this->login_user->is_admin && get_setting("module_invoice")) { ?>
    <div class="form-group">
        <label for="disable_online_payment" class="<?php echo $label_column; ?>"><?php echo lang('disable_online_payment'); ?>
            <span class="help" data-container="body" data-toggle="tooltip" title="<?php echo lang('disable_online_payment_description') ?>"><i class="fa fa-question-circle"></i></span>
        </label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_checkbox("disable_online_payment", "1", $model_info->disable_online_payment ? true : false, "id='disable_online_payment'");
            ?>                       
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

<?php /*if (isset($currency_dropdown)) { ?>
            if ($('#currency').length) {
                $('#currency').select2({data: <?php echo json_encode($currency_dropdown); ?>});
            }
<?php } */?>

<?php if (isset($buyer_types_dropdown)) { ?>
            $("#buyer_type").select2({
                multiple: false,
                data: <?php echo json_encode($buyer_types_dropdown); ?>
            });
<?php } ?>

<?php if (isset($groups_dropdown)) { ?>
            $("#group_ids").select2({
                multiple: true,
                data: <?php echo json_encode($groups_dropdown); ?>
            });
<?php } ?>
<?php if (isset($gst_code_dropdown)) { ?>
            $("#gstin_number_first_two_digits").select2({
                multiple: false,
                data: <?php echo json_encode($gst_code_dropdown); ?>
            });
<?php } ?>
<?php if (isset($state_dropdown)) { ?>
            $("#state").select2({
                multiple: false,
                data: <?php echo json_encode($state_dropdown); ?>
            });
<?php } ?>

 $("#country").select2({
            multiple: false,
            data: <?php echo ($company_setup_country_dropdown); ?>
        });




        
$("#country").change(function () {
    $("#state").val("").attr('readonly', false)
    var country_name =$("#state").val();
   $("#state").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("companys/get_state_suggestion"); ?>",
                dataType: 'json',
               data: function (country_name, page) {
                    return {
                        q: country_name,
                        ss:$("#country").val()// search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })
        })





        $("#gst_number").on('keyup',function () {
        $("#gstin_number_first_two_digits").val("").attr('readonly', false)
                    var gst_number =$("#gst_number").val().substr(0,2);

          //$("#gstin_number_first_two_digits").select2().("val",'33')
          $("#gstin_number_first_two_digits").select2("val", gst_number);
        })


        $("#country").change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                
                
               
                
                $("#country").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                var country_name =$("#country").val();
                $.ajax({

                    url: "<?php echo get_uri("companys/get_country_item_info_suggestion"); ?>",
                    data: {item_name: e.val,country_name:country_name},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#currency_symbol").val()) {
                                $("#currency_symbol").val(response.item_info.currency_symbol);
                            }

                            if (!$("#currency").val()) {
                                $("#currency").val(response.item_info.currency);
                            }
                            
                             
                             $('#vat,#vats').html(response.item_info.vat_name)
                         if(!response.item_info.vat_name){
                          $('#vat,#vats').html("VAT")
               
                          }
                     //var country_code = response.item_info.numberCode;
                   $('#country_code_id').val(response.item_info.numberCode)
                     var country_code= response.item_info.numberCode;
            //alert(country_code);
              if(country_name=="356"){
               $('#state_mandatory_app').hide(); 
               $('#state_mandatory').click();
              }else{
                $('#state_mandatory_app').show(); 
               $('#state_mandatory').click();
              }


                            
                       

                        }
                    }
                });
            }

        });
    

    


    });
</script>
<script type="text/javascript">
    $("#country").on("change", function() {
   
        $("#state").val("").attr('readonly', false)
        $("#currency").select2("destroy").val("")
        
        $("#currency_symbol").val("")
});
</script>
<script type="text/javascript">
    $("#close").on("click", function() {
        $("#state").select2("destroy").val("")
        //$("#country").val("")
        $("#country").val("").attr('readonly', false)
        $("#currency").select2("destroy").val("")
        
        $("#currency_symbol").val("")
});
</script>
<script type="text/javascript">
    $("#state").on("change", function() {
     var state_name = $('#state').val() 
    // var gst_number = $('#gst_number').val().substr(0,2)

if(state_name) {

    $("#message").hide()
}else
{
    $("#message").show()
}
   
       
});
</script>
<script>
        $('#state_mandatory_no').click(function () {
            $('#message').html('Select the State Name').css('color', 'white').hide()
        });
 
$('#savebutton').click(function () {
    //var state_mandatory = $("#state_mandatory").val();
    //var state_mandatory_no =$("#state_mandatory_no").val();
    //var state_mandory_name =$('input[name="state_mandatory"]').val();
    //var state_mandory_name =$("input[name='state_mandatory']:checked").val();
    //var state =$("#state").val();
   
   var country_name = $("#country").val();
   /*$.ajax({

                    url: "<?php /* echo get_uri("companys/get_country_code_suggestion"); */?>",
                    data: {item_name:country_name},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
//var country_code = response.item_info.numberCode;
              $('#country_code_id').val(response.item_info.numberCode);
//alert(country_code);

                        }
                    }
                });*/
   //var country_code = $('#country_code_id').val();
var country_code = $('#country').val();
var state =$("#state").val();
var state_mandory_name =$("input[name='state_mandatory']:checked").val();
if(country_code=="356"){
if(state_mandory_name=="yes"){
    if(state)
    {
    $('#message').html('Select the State Name').css('color', 'white').hide()
    $('#country_message').html('Select the Country Name').css('color', 'white').hide()
    return true;
    }
    else
    {
    $('#message').html('Select the State Name').css('color', 'red').show()
    $('#country_message').html('Select the Country Name').css('color', 'white').show()
    return false;
    }
}
 }else{
    
   if(state_mandory_name=="yes"){
    if(state)
    {
    $('#message').html('Select the State Name').css('color', 'white').hide()
    $('#country_message').html('Select the Country Name').css('color', 'white').hide()
    return true;
    }
    else
    {
    $('#message').html('Select the State Name').css('color', 'red').show()
    $('#country_message').html('Select the Country Name').css('color', 'white').show()
    return false;
    }
} 
 }   
});
    </script>


    <script>
 
$('#savebutton').click(function () {
    var country =$("#country").val();
    if(country)
    {
    //$('#message').html('Select the State Name').css('color', 'white').hide()
    $('#country_message').html('Select the Country Name').css('color', 'white').hide()
    return true;
    }
    else
    {
    //$('#message').html('Select the State Name').css('color', 'red').show()
    $('#country_message').html('Select the Country Name').css('color', 'red').show()
    return false;
    }
});
    </script>
    <script type="text/javascript">
    $("#country").on("change", function() {
     var country_name = $('#country').val() 
    // var gst_number = $('#gst_number').val().substr(0,2)

if(country_name) {

    $("#country_message").hide()
}else
{
    $("#country_message").show()
}
   
       
});
</script>
<script type="text/javascript">
 <?php if($model_info->country){ ?> 
    <?php  if($model_info->country!="356"){ ?>

    $('#state_mandatory_app').show(); 
   //$('#state_mandatory').click();
   //$('#state_mandatory_no').click();
<?php } ?>
<?php } ?>
</script>