<!DOCTYPE html>
<html lang="en">
    <head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
        <?php $this->load->view('includes/head'); ?>
    </head>
    <body>
        <?php
        if (get_setting("show_background_image_in_signin_page") === "yes") {
            $background_url = get_file_uri('files/system/sigin-background-image.jpg');
            ?>
            <style type="text/css">
                body {background-image: url('<?php echo $background_url; ?>'); background-size:cover}
            </style>
        <?php } ?>
        <div id="page-content" class="clearfix">
            <div class="scrollable-page">
                <div class="signin-box">
                    <div class="panel panel-default clearfix">
                        <div class="panel-heading text-center">
                            <h2 class="form-signin-heading"><?php echo lang('registration_form'); ?></h2>
                            <p><?php echo $signup_message; ?></p>
                        </div>
                        <div class="panel-body">
<?php echo form_open("student_desk_signup/create_account", array("id" => "signup-form", "class" => "general-form", "role" => "form")); ?>
                            <input type="hidden" id="country_code_id" value="" />
                            <div class="form-group">
                                <label for="name" class=" col-md-12"><?php echo lang('name'); ?></label>
                                <div class="col-md-12">
                                    <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                //"value" => $model_info->name,
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
                                <label for="name" class=" col-md-12"><?php echo lang('name'); ?></label>
                                <div class="col-md-12">
                                    <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                //"value" => $model_info->last_name,
                "class" => "form-control",
                "placeholder" => lang('last_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                                </div>
                            </div>


                            <input type="hidden" name="signup_key"  value="<?php echo isset($signup_key) ? $signup_key : ''; ?>" />
                            <div class="form-group">
                                <label for="date" class=" col-md-12"><?php echo lang('registration_date'); ?></label>
                                <div class=" col-md-12">
                                    <?php 
            echo form_input(array(
                "id" => "date",
                "name" => "date",
                "value" => $model_info->date?$model_info->date:get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "placeholder" => lang('registration_date'),
                 "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                                </div>
                            </div>

<div class="form-group">
        <label for="college_name" class="col-md-12"><?php echo lang('college_name'); ?></label>
        <div class=" col-md-12">
            <?php 
            echo form_input(array(
                "id" => "college_name",
                "name" => "college_name",
                //"value" => $model_info->college_name,
                "class" => "form-control",
                "placeholder" => lang('college_name'),
                 "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<div class="form-group">
                                    <label for="department" class=" col-md-12"><?php echo lang('department'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "department",
                                            "name" => "department",
                                            "class" => "form-control",
                                             "placeholder" => lang('department'),
                                             /* "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                                        ));
                                        ?>
                                    </div>
                                </div>
                      
<div class="form-group">
                                    <label for="year" class=" col-md-12"><?php echo lang('year_of_passed_out'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "year",
                                            "name" => "year",
                                            "class" => "form-control",
                                           /* "data-rule-required" => true,
                                            "data-msg-required" => lang("field_required"),*/
                                            "placeholder" => lang('year_of_passed_out'),
                                             /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="name" class=" col-md-12"><?php echo lang('name'); ?></label>
                                <div class="col-md-12">
                                    <?php 
            echo form_input(array(
                "id" => "parent_name",
                "name" => "parent_name",
                //"value" => $model_info->parent_name,
                "class" => "form-control",
                "placeholder" => lang('parent_name'),
                 /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label for="communication_address" class=" col-md-12"><?php echo lang('communication_address'); ?></label>
                                   
                <div class=" col-md-12">
                    <?php
                    echo form_textarea(array(
                        "id" => "communication_address",
                        "name" => "communication_address",
                        "value" => $model_info->communication_address,
                        "class" => "form-control",
                        "placeholder" => lang('communication_address'),
                         /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                    ));
                    ?>
                                    </div>
                                </div>

<div class="form-group">
                                <label for="gender" class=" col-md-12"><?php echo lang('communication_address_same_as_permanent_address'); ?></label>
                                <div class=" col-md-12">
                                    <?php
                                    echo form_radio(array(
                                        "id" => "same",
                                        "name" => "same_address",
                                            ), "yes", false);
                                    ?>
                                    <label for="same" class="mr15"><?php echo lang('yes'); ?></label> <?php
                                    echo form_radio(array(
                                        "id" => "different",
                                        "name" => "same_address",
                                            ), "no", false);
                                    ?>
                                    <label for="others" class=""><?php echo lang('no'); ?></label>
                                    
                                </div>
                            </div>


                                <div class="form-group">
                                    <label for="mailing_address" class=" col-md-12"><?php echo lang('mailing_address'); ?></label>
                                   
                <div class=" col-md-12">
                    <?php
                    echo form_textarea(array(
                        "id" => "permanent_address",
                        "name" => "permanent_address",
                        "value" => $model_info->permanent_address,
                        "class" => "form-control",
                        "placeholder" => lang('permanent_address')/*,
                         "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                    ));
                    ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pincode" class=" col-md-12"><?php echo lang('pincode'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "pincode",
                                            "name" => "pincode",
                                            "class" => "form-control",
                                            "maxlength"=>6,
                                            /*"data-rule-required" => true,
                                            "data-msg-required" => lang("field_required"),*/
                                            "placeholder" => lang('pincode')
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
        <label for="country" class="col-md-12"><?php echo lang('country'); ?></label>
        <div class="col-md-12">
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
            <a id="country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div>
    <div class="form-group" id= "state_mandatory_app" style="display:none;">
                <label for="invoice_recurring" class="col-md-12"><?php echo lang('state_mandatory'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('state_mandatory'); ?>"><i class="fa fa-question-circle"></i></span></label>
                <div class="col-md-12">
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
                                    <label for="state" class=" col-md-12"><?php echo lang('state'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "state",
                                            "name" => "state",
                                            "class" => "form-control",
                                            "data-rule-required" => true,
                                            "data-msg-required" => lang("field_required"),
                                            "readonly"=>true,
                                            "placeholder" => lang('state')
                                        ));
                                        ?>
                                       <span id ="message"></span> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="district" class=" col-md-12"><?php echo lang('district'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "district",
                                            "name" => "district",
                                            "class" => "form-control",
                                            /*"data-rule-required" => true,
                                            "data-msg-required" => lang("field_required"),*/
                                            "placeholder" => lang('district')
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class=" col-md-12"><?php echo lang('email'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "email",
                                            "name" => "email",
                                            "class" => "form-control",
                                            "autofocus" => true,
                                            "data-rule-email" => true,
                                            "data-msg-email" => lang("enter_valid_email"),
                                            "data-rule-required" => true,
                                            "data-msg-required" => lang("field_required"),
                                            "placeholder" => lang('email')
                                        ));
                                        ?>
                                    </div>
                                </div>

<div class="form-group">
                                    <label for="year" class=" col-md-12"><?php echo lang('phone'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "phone",
                                            "name" => "phone",
                                            "class" => "form-control",
                                            "maxlength"=>10,
                                            "data-rule-required" => true,

                                            "data-msg-required" => lang("field_required"),
                                            "placeholder" => lang('phone')
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="year" class=" col-md-12"><?php echo lang('alternative_phone'); ?></label>
                                    <div class=" col-md-12">
                                        <?php
                                        echo form_input(array(
                                            "id" => "alternative_phone",
                                            "name" => "alternative_phone",

                                           "class" => "form-control",
                                           "maxlength"=>10,
                                            "placeholder" => lang('alternative_phone')
                                        ));
                                        ?>
                                    </div>
                                </div>
                            <div class="form-group">
                <label for="dob" class=" col-md-12"><?php echo lang('date_of_birth'); ?></label>
                <div class=" col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "dob",
                        "name" => "dob",
                        
                        "class" => "form-control",
                        "placeholder" => lang('date_of_birth')
                    ));
                    ?>
                </div>
            </div>
                        <div class="form-group">
                                <label for="gender" class=" col-md-12"><?php echo lang('gender'); ?></label>
                                <div class=" col-md-12">
                                    <?php
                                    echo form_radio(array(
                                        "id" => "gender_male",
                                        "name" => "gender",
                                            ), "male", true);
                                    ?>
                                    <label for="gender_male" class="mr15"><?php echo lang('male'); ?></label> <?php
                                    echo form_radio(array(
                                        "id" => "gender_female",
                                        "name" => "gender",
                                            ), "female", false);
                                    ?>
                                    <label for="gender_female" class="mr15"><?php echo lang('female'); ?></label>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "gender_other",
                                        "name" => "gender",
                                            ), "others", false);
                                    ?>
                                    <label for="gender_female" class=""><?php echo lang('others'); ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                <label for="vap_category" class=" col-md-12"><?php echo lang('vap_category'); ?></label>
                <div class="col-md-12">
                <?php
                echo form_dropdown("vap_category", 
                    $vap_category_dropdown, $model_info->vap_category, "class='select2 validate-hidden' id='vap_category'");
                ?>
                </div>
            </div>
<div class="form-group">
                <label for="type" class=" col-md-12"><?php echo lang('program_title'); ?></label>
                <div class=" col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "program_title",
                        "name" => "program_title",
                       // "value" => $model_info->program_title,
                        "class" => "form-control",
                        "placeholder" => lang('program_title'),
                      /*  "data-msg-required" => lang("field_required"),
                        "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/

                    ));
                    ?>
                </div>
            </div>

<div class="clearfix">
       
         <label for="start_date" class=" col-md-3 col-sm-3"><?php echo lang('duration_of_course'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "start_date",
                "name" => "start_date",
                //"value" => $model_info->start_date,
                "class" => "form-control",
                "placeholder" => lang('start_date'),
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
        </div>
        <!--label for="start_time" class=" col-md-2 col-sm-2"><?php echo lang('start_time'); ?></label-->
        <div class=" col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "end_date",
                "name" => "end_date",
                //"value" => $model_info->end_date,
                "class" => "form-control",
                "placeholder" => lang('end_date'),
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                "data-rule-greaterThanOrEqual" => "#start_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>

    <div class="clearfix">
       
         <label for="in_time" class=" col-md-3 col-sm-3"><?php echo lang('timing'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            $start_time = is_date_exists($model_info->start_time) ? $model_info->start_time : "";

            if ($time_format_24_hours) {
                $start_time = $start_time ? date("H:i", strtotime($start_time)) : "";
            } else {
                $start_time = $start_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($start_time))) : "";
            }
            echo form_input(array(
                "id" => "start_time",
                "name" => "start_time",
                //"value" => $start_time,
                "class" => "form-control",
                "placeholder" => lang('start_time'),
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
        </div>
        <!--label for="start_time" class=" col-md-2 col-sm-2"><?php echo lang('start_time'); ?></label-->
        <div class=" col-md-4 col-sm-4 form-group">
            <?php
            $end_time = is_date_exists($model_info->end_time) ? $model_info->end_time : "";

            if ($time_format_24_hours) {
                $end_time = $end_time ? date("H:i", strtotime($end_time)) : "";
            } else {
                $end_time = $end_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($end_time))) : "";
            }
            echo form_input(array(
                "id" => "end_time",
                "name" => "end_time",
                //"value" => $end_time,
                "class" => "form-control",
                "placeholder" => lang('end_time'),
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
                <label for="aadhar_no" class=" col-md-12"><?php echo lang('aadhar_no'); ?></label>
                <div class=" col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "aadhar_no",
                        "name" => "aadhar_no",
                        //"value" => $model_info->aadhar_no,
                        "class" => "form-control",

                        "maxlength"=>12,
                        "placeholder" => lang('aadhar_no'),
                         /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                    ));
                    ?>
                </div>
            </div>


                            <div class="col-md-12">
                                  <?php $this->load->view("signin/re_captcha"); ?>
                            </div>
                                                      
                            <div class="form-group">
                                <div class=" col-md-12">
                                    <button  id="savebutton" class="btn btn-lg btn-primary btn-block" type="submit"><?php echo lang('register'); ?></button>
                                </div>
                            </div>
                        </div>
<?php echo form_close(); ?>
                    </div>
                    <div id="signin_link"><?php echo lang("already_have_an_account") . " " . anchor("signin", lang("signin")); ?></div>
                </div>
            </div>
        </div> <!-- /container -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#signup-form").appForm({
                    isModal: false,
                    onSubmit: function () {
                        appLoader.show();
                    },
                    onSuccess: function (result) {
                        appLoader.hide();
                        appAlert.success(result.message, {container: '.panel-body', animate: false});
                        $("#signup-form").remove();
                        $("#signin_link").remove();
                    },
                    onError: function (result) {
                        appLoader.hide();
                        appAlert.error(result.message, {container: '.panel-body', animate: false});
                        return false;
                    }
                });
                //setTimePicker("#start_time, #end_time");
                setDatePicker("#start_date, #end_date,#date,#dob");
 $( "#start_time" ).timepicker();
 $( "#end_time" ).timepicker();
           
        $("#signup-form .select2").select2();


//get the country and state dropdown
$("#same").click(function () {

           var address_checkbox= $("#same").val();
           if(address_checkbox=='yes'){
var comm_address=$("#communication_address").val();
$("#permanent_address").val(comm_address);
           }
        })
$("#different").click(function () {

           var address_different= $("#different").val();
           if(address_different=='no'){

$("#permanent_address").val("");
           }
        })



var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }
$("#country").attr('readonly', true);
        //re-initialize item suggestion dropdown on request
        $("#country_dropdwon_icon").click(function () {

            applySelect2OnItemTitle();
        })
$("#country").change(function () {
    $("#state").val("").attr('readonly', false)
                    var country_name =$("#state").val();

                    var country_name_id =$("#country").val();
                    $.ajax({

                    url: "<?php echo get_uri("student_desk_signup/get_country_code_suggestion"); ?>",
                    data: {item_name:country_name_id},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
//var country_code = response.item_info.numberCode;
             $('#country_code_id').val(response.item_info.numberCode)
            var country_code= response.item_info.numberCode;
            //alert(country_code);
              if(country_code=="356"){
               $('#state_mandatory_app').hide(); 
               $('#state_mandatory').click();
              }else{
                $('#state_mandatory_app').show(); 
               $('#state_mandatory').click();
              }

                

                        }
                    }
                });


          $("#state").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("student_desk_signup/get_state_suggestion"); ?>",
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




        function applySelect2OnItemTitle() {
        $("#country").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("student_desk_signup/get_country_item_suggestion"); ?>",
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
                
                
               
                
                $("#country").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                var country_name =$("#country").val();
                $.ajax({

                    url: "<?php echo get_uri("student_desk_signup/get_country_item_info_suggestion"); ?>",
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
                            if (!$("#state").val()) {
                               // $("#state").select2("destroy").val(response.item_infos.country_code);

                                
                        //$('#state').select2("destroy").val(response.item_infos.title);
                            } 



                            
                       

                        }
                    }
                });
            }

        });
    }








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
   $.ajax({

                    url: "<?php echo get_uri("student_desk_signup/get_country_code_suggestion"); ?>",
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
                });
   //var country_code = $('#country_code_id').val();
var country_code = $('#country_code_id').val();
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

<!-- <script>
 
$('#savebutton').click(function () {
    var state =$("#state").val();
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
});
    </script> -->
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
    </body>
</html>