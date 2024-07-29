<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "company";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_company_settings"), array("id" => "company-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("company_settings"); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="company_name" class=" col-md-2"><?php echo lang('company_name'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_name",
                                "name" => "company_name",
                                "value" => get_setting("company_name"),
                                "class" => "form-control",
                                "placeholder" => lang('company_name'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="company_address" class=" col-md-2"><?php echo lang('address'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_textarea(array(
                                "id" => "company_address",
                                "name" => "company_address",
                                "value" => get_setting("company_address"),
                                "class" => "form-control",
                                "placeholder" => lang('address'),
                            ));
                            ?>
                        </div>
                    </div>
<div class="form-group">
        <label for="company_country" class=" col-md-2"><?php echo lang('country'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "company_setup_country",
                "name" => "company_setup_country",
                "value" => get_setting('company_setup_country'),
                "class" => "form-control validate-hidden",
                "placeholder" => lang('company_country'),
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
           <span id="country_message"></span> 
        </div>
    </div>

                    <div class="form-group">
                        <label for="company_state" class=" col-md-2"><?php echo lang('state'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_state",
                                "name" => "company_state",
                                "value" => get_setting("company_state"),
                                "readonly"=> "true",
                                "class" => "form-control",
                                "placeholder" => lang('state')
                            ));
                            ?>
                            <span id='message'></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_city" class=" col-md-2"><?php echo lang('city'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_city",
                                "name" => "company_city",
                                "value" => get_setting("company_city"),
                                "class" => "form-control",
                                "placeholder" => lang('city'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_pincode" class=" col-md-2"><?php echo lang('pincode'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_pincode",
                                "name" => "company_pincode",
                                "value" => get_setting("company_pincode"),
                                "class" => "form-control",
                                "placeholder" => lang('pincode'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_phone" class=" col-md-2"><?php echo lang('phone'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_phone",
                                "name" => "company_phone",
                                "value" => get_setting("company_phone"),
                                "class" => "form-control",
                                "placeholder" => lang('phone')
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_email" class=" col-md-2"><?php echo lang('email'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_email",
                                "name" => "company_email",
                                "value" => get_setting("company_email"),
                                "class" => "form-control",
                                "placeholder" => lang('email')
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_website" class=" col-md-2"><?php echo lang('website'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_website",
                                "name" => "company_website",
                                "value" => get_setting("company_website"),
                                "class" => "form-control",
                                "placeholder" => lang('website')
                            ));
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="company_gst_number" class=" col-md-2"><?php echo lang('gst_number'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_gst_number",
                                "name" => "company_gst_number",
                                "value" => get_setting("company_gst_number"),
                                "class" => "form-control",
                                "placeholder" => lang('gst_number')
                            ));
                            ?>
                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="company_gstin_number_first_two_digits" class=" col-md-2"><?php echo lang('company_gstin_number_first_two_digits'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "company_gstin_number_first_two_digits",
                                "name" => "company_gstin_number_first_two_digits",
                                "value" => get_setting("company_gstin_number_first_two_digits"),
                                "class" => "form-control",
                                "readonly" => "true",
                                "placeholder" => lang('company_gstin_number_first_two_digits')
                            ));
                            ?>
                            
                        </div>
                    </div>

                    <div class="form-group">
        <label for="discount_cutoff_margin" class=" col-md-2"><?php echo lang('discount_cutoff_margin'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_inputnumber(array(
                "id" => "discount_cutoff_margin",
                "name" => "discount_cutoff_margin",
                "value" => get_setting("discount_cutoff_margin")?get_setting("discount_cutoff_margin"):"0",
                "class" => "form-control",
                "min" =>"0",
                "max"=>"50",
                "placeholder" => lang('discount_cutoff_margin'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>


                </div>
                <div class="panel-footer">
                    <button  id="savebutton" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#company-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#company_gstin_number_first_two_digits").select2({
            multiple: false,
            data: <?php echo ($company_gst_state_code_dropdown); ?>
        });

        $("#company_setup_country").select2({
            multiple: false,
            data: <?php echo ($company_setup_country_dropdown); ?>
        });

         $("#company_state").select2({
            multiple: false,
            data: <?php echo ($company_state_dropdown); ?>
        });

    });

    $("#company_setup_country").change(function () {
    $("#company_state").val("").attr('readonly', false)
                    var country_name =$("#company_state").val();

          $("#company_state").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("clients/get_state_suggestion"); ?>",
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
        })


    $("#company_gst_number").on('keyup',function () {
        //$("#company_gstin_number_first_two_digits").val("").attr('readonly', false)
                    var gst_number =$("#company_gst_number").val().substr(0,2);

          //$("#gstin_number_first_two_digits").select2().("val",'33')
          $("#company_gstin_number_first_two_digits").select2("val", gst_number);
        })
</script>
<!-- <script type="text/javascript">
    $("#company_state").on("change", function() {
     var state_name = $('#company_state').val() 
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
 
$('#savebutton').click(function () {
    var state =$("#company_state").val();
    if(state)
    {
    $('#message').html('Select the State Name').css('color', 'white').hide()
    //$('#country_message').html('Select the Country Name').css('color', 'white').hide()
    return true;
    }
    else
    {
    $('#message').html('Select the State Name').css('color', 'red').show()
    //$('#country_message').html('Select the Country Name').css('color', 'white').show()
    return false;
    }
});
    </script> -->
    <script type="text/javascript">
    $("#company_setup_country").on("change", function() {
     var company_setup_country_name = $('#company_setup_country').val() 
    // var gst_number = $('#gst_number').val().substr(0,2)

if(company_setup_country_name) {

    $("#country_message").hide()
}else
{
    $("#country_message").show()
}
   
       
});
</script>
<script>
 
$('#savebutton').click(function () {
    var company_setup_country =$("#company_setup_country").val();
    if(company_setup_country)
    {
    $('#country_message').html('Select the Country Name').css('color', 'white').hide()
    //$('#country_message').html('Select the Country Name').css('color', 'white').hide()
    return true;
    }
    else
    {
    $('#country_message').html('Select the Country Name').css('color', 'red').show()
    //$('#country_message').html('Select the Country Name').css('color', 'white').show()
    return false;
    }
});
    </script>
<!--script>
//var s=$('#gst_number').val();
//var t=$('#gstin_number_first_two_digits').val();
  $('#company_gst_number').on('keyup', function () {
   if ($('#company_gst_number').val()!=="" && $('#company_gstin_number_first_two_digits').val()!==""){ 
  if ($('#company_gst_number').val().substr(0,2) == $('#company_gstin_number_first_two_digits').val()) {
    $('#message').html('').css('color', 'green');
    $("#savebutton").show();
  } else {
  $("#savebutton").hide();
    $('#message').html('GST Number and GSTIN Register Number does Not Match').css('color', 'red');
    }
}
        
});

$(' #company_gstin_number_first_two_digits').on('click', function () {
   if ($('#company_gst_number').val()!=="" && $('#company_gstin_number_first_two_digits').val()!==""){ 
  if ($('#company_gst_number').val().substr(0,2) == $('#company_gstin_number_first_two_digits').val()) {
    $('#message').html('').css('color', 'green');
$("#savebutton").show();

  } else {
  $("#savebutton").hide();
    $('#message').html('GST Number and GSTIN Register  Number does Not Match').css('color', 'red');
    } 
}
});   
</script-->