<?php echo form_open(get_uri("branches/save"), array("id" => "tax-form", "class" => "general-form", "role" => "form")); ?> 
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('branch_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description,
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
        <div class="form-group" >
            <label for="estimate_client_id" class=" col-md-3"><?php echo lang('company_name'); ?></label>
            <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "company_name",
                "name" => "company_name",
                "value" => $model_info->company_name,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('company_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        </div>
                    <div class="form-group">
                        <label for="company_address" class=" col-md-3"><?php echo lang('address'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_textarea(array(
                                "id" => "company_address",
                                "name" => "company_address",
                                "value" => $model_info->company_address,
                                "class" => "form-control",
                                "placeholder" => lang('address'),
                            ));
                            ?>
                        </div>
                    </div>
<div class="form-group">
        <label for="company_country" class=" col-md-3"><?php echo lang('country'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "company_setup_country",
                "name" => "company_setup_country",
                "value" => $model_info->company_setup_country,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
           <span id="country_message"></span> 
        </div>
    </div>

    <div class="form-group" id= "state_mandatory_app" style="display:none;">
                <label for="invoice_recurring" class="col-md-3"><?php echo lang('state_mandatory'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('state_mandatory'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class="col-md-9">
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
                        <label for="company_state" class=" col-md-3"><?php echo lang('state'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "company_state",
                                "name" => "company_state",
                                "value" =>$model_info->company_state,
                                //"readonly"=> "true",
                                "class" => "form-control validate-hidden",
                                "placeholder" => lang('state'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                            <span id='message'></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_city" class=" col-md-3"><?php echo lang('city'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "company_city",
                                "name" => "company_city",
                                "value" => $model_info->company_city,
                                "class" => "form-control",
                                "placeholder" => lang('city'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_pincode" class=" col-md-3"><?php echo lang('pincode'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_inputnumber(array(
                                "id" => "company_pincode",
                                "name" => "company_pincode",
                                "value" => $model_info->company_pincode,
                                "class" => "form-control",
                                "min"=> 0,
                                "placeholder" => lang('pincode'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_inputnumber(array(
                                "id" => "company_phone",
                                "name" => "company_phone",
                                "value" => $model_info->company_phone,
                                "class" => "form-control",
                                "placeholder" => lang('phone')
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_email" class=" col-md-3"><?php echo lang('email'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "company_email",
                                "name" => "company_email",
                                "value" => $model_info->company_email,
                                "class" => "form-control",
                                "placeholder" => lang('email'),
                                "data-rule-email" => true,
                                "data-msg-email" => lang("enter_valid_email"),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_website" class=" col-md-3"><?php echo lang('website'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "company_website",
                                "name" => "company_website",
                                "value" => $model_info->company_website,
                                "class" => "form-control",
                                "placeholder" => lang('website')
                            ));
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
      <label for="gst_number" class="col-md-3">
        <span id="vat"><?php echo (!$model_info->company_setup_country) ? "VAT" : ""; ?> <?php echo isset($vat_name) ? $vat_name : ''; ?></span> Number
    </label>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "company_gst_number",
            "name" => "company_gst_number",
            "value" => isset($model_info->company_gst_number) ? $model_info->company_gst_number : '',
            "class" => "form-control",
            "placeholder" => lang('gst_number')
        ));
        ?>
                </div>
                  </div>

                  <div class="form-group">
    <label for="gstin_number_first_two_digits" class="col-md-3">
        <span id="vats"><?php echo (!$model_info->company_setup_country) ? "VAT" : ""; ?> <?php echo isset($vat_name) ? $vat_name : ''; ?></span> <?php echo lang('gstinnumber_firsttwodigits'); ?>
    </label>
    <div class="col-md-9">
        <?php
        echo form_input(array(
            "id" => "company_gstin_number_first_two_digits",
            "name" => "company_gstin_number_first_two_digits",
            "value" => isset($model_info->company_gstin_number_first_two_digits) ? $model_info->company_gstin_number_first_two_digits : '',
            "class" => "form-control",
            "readonly" => "true",
            "placeholder" => lang('company_gstin_number_first_two_digits')
        ));
        ?>
    </div>
</div>

                    <div class="form-group">
                <label for="first_day_of_week" class=" col-md-3"><?php echo lang('holiday_day_of_week'); ?></label>
                <div class=" col-md-9">
                   <?php
                            /*echo form_dropdown(
                                    "first_day_of_week", array(
                                "0" => "Sunday",
                                "1" => "Monday",
                                "2" => "Tuesday",
                                "3" => "Wednesday",
                                "4" => "Thursday",
                                "5" => "Friday",
                                "6" => "Saturday"
                                    ), $model_info->holiday_of_week, "class='select2 mini' id='holiday_of_week' "
                            );*/
                            echo form_input(array(
                                "id" => "holiday_of_week",
                                "name" => "holiday_of_week",
                                "value" => $model_info->holiday_of_week,
                                "class" => "form-control",
                                "placeholder" => lang('holiday_day_of_week')
                            ));
                            ?>
                </div>
            </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button  id="savebutton" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#tax-form").appForm({
            onSuccess: function(result) {
                $("#taxes-table").appTable({newData: result.data, dataId: result.id});
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                   
                    //window.location = 'Payslip/pays';
                    window.location = "<?php echo site_url('branches/view'); ?>/" + result.id;
                }
            }
        });
        $("#title").focus();
        $("#holiday_of_week").select2({
            multiple: true,
            data: <?php echo ($holiday_of_week_dropdown); ?>
        });
        
        $("#company_gstin_number_first_two_digits").select2({
            multiple: false,
            data: <?php echo ($company_gst_state_code_dropdown); ?>
        });

        $("#company_setup_country").select2({
            multiple: false,
            data: <?php echo ($company_setup_country_dropdown); ?>
        });
        $("#company_name").select2({
            multiple: false,
            data: <?php echo ($company_name_dropdown); ?>
        });

         $("#company_state").select2({
            multiple: false,
            data: <?php echo ($company_state_dropdown); ?>
        });

    });
    $("#company_setup_country").change(function () {
    $("#company_state").val("").attr('readonly', false)
                    var country_name =$("#company_state").val();
        var country =  $("#company_setup_country").val();           

          $("#company_state").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("branches/get_state_suggestion"); ?>",
                dataType: 'json',
               data: function (country_name, page) {
                    return {
                        q: country_name,
                        ss:$("#company_setup_country").val()// search term
                    };
                },
                    cache: false,
                    type: 'POST',
                results: function (data, page) {
                    return {results: data};
                }
            }
        })

          if(country=="356"){
               $('#state_mandatory_app').hide(); 
               $('#state_mandatory').click();
              }else{
                $('#state_mandatory_app').show(); 
               $('#state_mandatory').click();
              }

               //get country based gst/vat name 
                $.ajax({

                    url: "<?php echo get_uri("companys/get_country_item_info_suggestion"); ?>",
                    data: {item_name: country},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            
                            
                             
                             $('#vat,#vats').html(response.item_info.vat_name)
                         if(!response.item_info.vat_name){
                          $('#vat,#vats').html("VAT")
               
                          }
                      }
                  }
              });
                // end get country based gst/vat name



    });
    $("#company_gst_number").on('keyup',function () {
        //$("#company_gstin_number_first_two_digits").val("").attr('readonly', false)
                    var gst_number =$("#company_gst_number").val().substr(0,2);

          //$("#gstin_number_first_two_digits").select2().("val",'33')
          $("#company_gstin_number_first_two_digits").select2("val", gst_number);
        })
</script>   
<?php if($model_info->id){ ?>
<script type="text/javascript">
$(document).ready(function() {
            //$("#branch_code").attr('readonly', true)
});
</script>
<?php } ?>

<script>
        $('#state_mandatory_no').click(function () {
           $("#company_state").removeClass("validate-hidden").removeAttr('required');
           $("#company_state-error").hide();
        });

        $('#state_mandatory').click(function () {
           $("#company_state").addClass("validate-hidden");
           $("#company_state-error").show();
        });
 
$('#savebutton').click(function () {
    
    var state =$("#company_state").val();
    var country =  $("#company_setup_country").val();
    var state_mandory_name =$("input[name='state_mandatory']:checked").val();
    if(state_mandory_name == "yes"){
       $("#company_state").addClass("validate-hidden");
        $("#company_state-error").show();
    }else if(state_mandory_name == "no"){
        $("#company_state").removeClass("validate-hidden");
           
    }

});
    </script>
<script type="text/javascript">
 <?php if($model_info->company_setup_country){ ?> 
    <?php  if($model_info->company_setup_country!="356"){ ?>

    $('#state_mandatory_app').show(); 
   //$('#state_mandatory').click();
   //$('#state_mandatory_no').click();
<?php } ?>
<?php } ?>
<?php if($model_info->id){ ?> 
   

   $("#company_name").attr("readonly",true)
   //$('#state_mandatory').click();
   //$('#state_mandatory_no').click();

<?php } ?>
</script>