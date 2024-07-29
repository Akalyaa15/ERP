<?php 
if($model_info->country){
 $country_no = is_numeric($model_info->country);
 if(!$country_no){
   $model_info->country = 0;
 }
 } 
$options = array(
            "id" => $model_info->country,
                   );
        $country_id_name = $this->Countries_model->get_details($options)->row();
        $country_dummy_name =$country_id_name->countryName;
        ?>

<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input type="hidden" id="country_code_id" value="" />
<div class="form-group">
        <label for="name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info->name,
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
        <label for="last_name" class=" col-md-3"><?php echo lang('last_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "value" => $model_info->last_name,
                "class" => "form-control",
                "placeholder" => lang('last_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="date" class="col-md-3"><?php echo lang('registration_date'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_input(array(
                "id" => "date",
                "name" => "date",
                "value" => $model_info->date?$model_info->date:get_my_local_time("Y-m-d"),
                "class" => "form-control",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "placeholder" => lang('registration_date')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="college_name" class="col-md-3"><?php echo lang('college_name'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_input(array(
                "id" => "college_name",
                "name" => "college_name",
                "value" => $model_info->college_name,
                "class" => "form-control",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "placeholder" => lang('college_name')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="department" class="col-md-3"><?php echo lang('department'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_input(array(
                "id" => "department",
                "name" => "department",
                "value" => $model_info->department,
                "class" => "form-control",

                "placeholder" => lang('department')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="year" class="col-md-3"><?php echo lang('year_of_passed_out'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_input(array(
                "id" => "year",
                "name" => "year",
                "value" => $model_info->year,
                "class" => "form-control",
                "placeholder" => lang('year_of_passed_out')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="parent_name" class="col-md-3"><?php echo lang('parent_name'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_input(array(
                "id" => "parent_name",
                "name" => "parent_name",
                "value" => $model_info->parent_name,
                "class" => "form-control",
               /* "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                "placeholder" => lang('parent_name')
            ));
            ?>
        </div>
    </div>
           <div class="form-group">
                <label for="communication_address" class=" col-md-3"><?php echo lang('communication_address'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "communication_address",
                        "name" => "communication_address",
                        "value" => $model_info->communication_address,
                        "class" => "form-control",
                       /* "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),*/
                        "placeholder" => lang('communication_address')
                    ));
                    ?>
                </div>
            </div>
           
             <div class="form-group">
                                <label for="gender" class=" col-md-3"><?php echo lang('communication_address_same_as_permanent_address'); ?></label>
                                <div class=" col-md-9">
                                    <?php
                                    echo form_radio(array(
                                        "id" => "same",
                                        "name" => "same_address",
                                            ), "yes", ($model_info->same_address === "yes") ? true : false);

                                    ?>
                                    <label for="same" class="mr15"><?php echo lang('yes'); ?></label> <?php
                                    echo form_radio(array(
                                        "id" => "different",
                                        "name" => "same_address",
                                            ), "no", ($model_info->same_address === "yes") ? false : true);
                                    ?>
                                    <label for="others" class=""><?php echo lang('no'); ?></label>
                                    
                                </div>
                            </div>
            <div class="form-group">
                <label for="mailing_address" class=" col-md-3"><?php echo lang('mailing_address'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "permanent_address",
                        "name" => "permanent_address",
                        "value" => $model_info->permanent_address,
                        "class" => "form-control",
                        /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                        "placeholder" => lang('mailing_address')
                    ));
                    ?>
                </div>
            </div>
<div class="form-group">
                <label for="pincode" class=" col-md-3"><?php echo lang('pincode'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "pincode",
                        "name" => "pincode",
                        "value" => $model_info->pincode,
                        "class" => "form-control",
                        "maxlength"=> 6,
                       /* "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),*/
                        "placeholder" => lang('pincode')
                       
                       
                    ));
                    ?>
                </div>
            </div>
            <!-- <div class="form-group">
        <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
        <div class="col-md-9">
            <?php /*
            echo form_input(array(
                "id" => "country",
                "name" => "country",
                "value" => $model_info->country,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('country'),

               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            */?>
            <span id ="country_message"></span>
            <a id="country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div> -->
    <?php if(!$model_info->country) { ?>

    <div class="form-group" id= "aa">
        <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
        <div class="col-md-9">
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
    <?php } else{ ?>
    
    <div class="form-group"  id= "aa" style="display:none;">
        <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
        <div class="col-md-9">
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
    <?php } ?>
    <?php if($model_info->country) { ?>
    <div class="form-group" id= "bb">
       <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "dummy_country",
                "name" => "dummy_country",
                "value" => $country_dummy_name,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('country'),
                "readonly" => "true",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <span id ="country_message"></span>
            <a id="dummy_country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="dummy_close">×</span></a>
        </div>
    </div>
    <?php } ?> <div class="form-group" id= "state_mandatory_app" style="display:none;">
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
                <label for="state" class=" col-md-3"><?php echo lang('state'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "state",
                        "name" => "state",
                        "value" => $model_info->state,
                        "class" => "form-control",
                        "placeholder" => lang('state'),
                        //"readonly"=> "true",
                    ));
                    ?>
                     <span id ="message"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="district" class=" col-md-3"><?php echo lang('district'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "district",
                        "name" => "district",
                        "value" => $model_info->district,
                        "class" => "form-control",
                       /* "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
                        "placeholder" => lang('district'),
                        
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class=" col-md-3"><?php echo lang('email'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "value" => $model_info->email,
                        "class" => "form-control",
                        
                        "placeholder" => lang('email'),
                        "data-rule-email" => true,
                        "data-msg-email" => lang("enter_valid_email"),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                        "value" => $model_info->phone,
                        "class" => "form-control",
                        "maxlength"=>15,
                        "placeholder" => lang('phone'),
                        "data-rule-required" => true,
                       "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_phone" class=" col-md-3"><?php echo lang('alternative_phone'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "alternative_phone",
                        "name" => "alternative_phone",
                        "value" => $model_info->alternative_phone,
                        "class" => "form-control",
                        "maxlength"=>15,
                        "placeholder" => lang('alternative_phone')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="dob" class=" col-md-3"><?php echo lang('date_of_birth'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "dob",
                        "name" => "dob",
                        "value" => $model_info->dob,
                        "class" => "form-control",
                        "placeholder" => lang('date_of_birth')
                    ));
                    ?>
                </div>
            </div>
             <div class="form-group">
                <label for="gender" class=" col-md-3"><?php echo lang('gender'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_radio(array(
                        "id" => "gender_male",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "male", ($model_info->gender === "male") ? $model_info->gender === "male" : true);
                    ?>
                    <label for="gender_male" class="mr15"><?php echo lang('male'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "gender_female",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "female", ($model_info->gender === "female") ? $model_info->gender === "female" : '');
                    ?>
                    <label for="gender_female" class="mr15"><?php echo lang('female'); ?></label>
                     <?php
                    echo form_radio(array(
                        "id" => "gender_others",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "others", ($model_info->gender === "others") ? $model_info->gender === "others" : '');
                    ?>
                    <label for="gender_female" class=""><?php echo lang('others'); ?></label>
                </div>
            </div>
             <div class="form-group">
                <label for="vap_category" class=" col-md-3"><?php echo lang('vap_category'); ?></label>
                <div class=" col-md-9">
                <?php
                echo form_dropdown("vap_category", 
                    $vap_category_dropdown, $model_info->vap_category, "class='select2 validate-hidden' id='vap_category'");
                ?>
               </div>
            </div>
            <div class="form-group">
                <label for="type" class=" col-md-3"><?php echo lang('program_title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "program_title",
                        "name" => "program_title",
                        "value" => $model_info->program_title,
                        "class" => "form-control",
                        /*"data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),*/
                        "placeholder" => lang('program_title')
                    ));
                    ?>
                </div>
            </div>
            
            <div class="form-group">
       
         <label for="start_date" class=" col-md-3 col-sm-3"><?php echo lang('duration_of_course'); ?></label>
        <div class="col-md-4 col-sm-4 ">
            <?php
            echo form_input(array(
                "id" => "start_date",
                "name" => "start_date",
                "value" => $model_info->start_date,
                "class" => "form-control",
                "placeholder" => lang('start_date'),
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
        </div>
        <div class=" col-md-4 col-sm-4">
            <?php
            echo form_input(array(
                "id" => "end_date",
                "name" => "end_date",
                "value" => $model_info->end_date,
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

     <div class="form-group">
       
         <label for="in_time" class=" col-md-3 col-sm-3"><?php echo lang('timing'); ?></label>
        <div class="col-md-4 col-sm-4 ">
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
                "value" => $start_time,
                "class" => "form-control",
                "placeholder" => lang('start_time'),
                /*"data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
        </div>
        <!--label for="start_time" class=" col-md-2 col-sm-2"><?php echo lang('start_time'); ?></label-->
        <div class=" col-md-4 col-sm-4 ">
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
                "value" => $end_time,
                "class" => "form-control",
                "placeholder" => lang('end_time'),
               /* "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),*/
            ));
            ?>
        </div>
    </div>
<div class="form-group">
                <label for="aadhar_no" class=" col-md-3"><?php echo lang('aadhar_no'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "aadhar_no",
                        "name" => "aadhar_no",
                        "value" => $model_info->aadhar_no,
                        "class" => "form-control",

                        "maxlength"=>12,
                        "placeholder" => lang('aadhar_no'),
                        /*"data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),*/
                    ));
                    ?>
                </div>
            </div>
           
    <script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();



setTimePicker("#start_time, #end_time");
<?php if (isset($state_dropdown)) { ?>
            $("#state").select2({
                multiple: false,
                data: <?php echo json_encode($state_dropdown); ?>
            });
<?php } ?>
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

<?php if($model_info->same_address=='yes') { ?>
    $("#communication_address").keyup(function(){
        var sa = $("#communication_address").val();
  $("#permanent_address").val(sa);
});
    <?php } ?>

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

                    url: "<?php echo get_uri("clients/get_country_code_suggestion"); ?>",
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
                url: "<?php echo get_uri("clients/get_state_suggestion"); ?>",
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
                url: "<?php echo get_uri("clients/get_country_item_suggestion"); ?>",
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

                    url: "<?php echo get_uri("clients/get_country_item_info_suggestion"); ?>",
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

    $("#dummy_country_dropdwon_icon").click(function () {
         $("#dummy_country").hide();
        // $("#country").show();
         $("#country").val("").attr('readonly', false).show();
         $("#aa").show();
         $("#bb").hide();
         $("#state").select2("destroy").val("")
        //$("#country").val("")
        //$("#country").val("").attr('readonly', false)
        
             applySelect2OnItemTitle();
        })


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

                    url: "<?php echo get_uri("clients/get_country_code_suggestion"); ?>",
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
<script type="text/javascript">
 <?php if($model_info->country){ ?> 
    <?php  if($country_id_name->numberCode!="356"){ ?>

    $('#state_mandatory_app').show(); 
   //$('#state_mandatory').click();
   //$('#state_mandatory_no').click();
<?php } ?>
<?php } ?>
</script>