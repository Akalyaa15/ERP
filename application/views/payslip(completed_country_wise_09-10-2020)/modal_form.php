<?php  
$payslip_user_id=$model_info->user_id;
$options = array(
            "id" => $model_info->user_id,
                   );
        $list_data = $this->Users_model->get_details($options)->row();
        $pays=$list_data->first_name." ".$list_data->last_name."(".$list_data->employee_id.")" ;

if($payslip_user_id)
{?>
<html>
<input type="hidden" name="pay" id="pay" value="<?php echo $pays; ?>" />
</html>
<script type="text/javascript" >

$( document ).ready(function() {
var payslip =$("#pay").val(); 

});
</script>
<?php } ?>

<?php echo form_open(get_uri("payslip/save"), array("id" => "payslip-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
         <input type="hidden"   id="payslip_working_hourss" name="payslip_working_hours" value="<?php echo $model_info->payslip_working_hours; ?>" />
          <input type="hidden"  id="payslip_casual_leaves"  name="payslip_casual_leave" value="<?php echo $model_info->payslip_casual_leave; ?>" />
       
    <?php if($model_info->id) { ?>
     <div class="form-group">
        <label for="payslip_no" class=" col-md-3"><?php echo lang('payslip_no'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_no",
                "name" => "payslip_no",
                "value" => $model_info->payslip_no?$model_info->payslip_no:get_payslip_id($model_info->id),
                "class" => "form-control",
                "placeholder" => lang('payslip_no'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
<?php } ?>
         <div class=" form-group">
            <label for="payslip_date" class=" col-md-3"><?php echo lang('date_of_payslip'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "payslip_date",
                    "name" => "payslip_date",
                    "value" => $model_info->payslip_date? $model_info->payslip_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ) );
                ?>
            </div>
        </div>

        <?php if(!$model_info->user_id) { ?>
        <div class="form-group" id= "aa" >
        <label for="title" class=" col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_user_id",
                "name" => "payslip_user_id",
                "value" =>$model_info->user_id,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_employee_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="payslip_user_id_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="close">×</span></a>
        </div>
    </div>
  <?php } else { ?>
  <div class="form-group" id= "aa" style="display:none";>
        <label for="title" class=" col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_user_id",
                "name" => "payslip_user_id",
                "value" =>$model_info->user_id,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_employee_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="payslip_user_id_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="close">×</span></a>
        </div>
    </div>
   <?php } ?>


<?php if($model_info->user_id) { ?>
   
        <div class="form-group" id= "bb">
        <label for="title" class=" col-md-3"><?php echo lang('team_member'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "payslip_user_ids",
                "name" => "payslip_user_ids",
                //"value" =>$model_info->user_id,
                "value"=> "$pays", 
                "class" => "form-control validate-hidden",
                "placeholder" => lang('select_employee_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "readonly"=> true,

            ));
            ?>
            <a id="payslip_user_id_title_dropdwon_icons" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id ="closes">×</span></a>
        </div>
    </div>

<?php } ?>


<div class="form-group" id="country_app" >
        <label for="country" class=" col-md-3"><?php echo lang('country'); ?></label>
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
                 "readonly"=> true,
            ));
            ?>
           <span id="country_message"></span> 
        </div>
    </div>
    <div class="form-group" id="branch_app" >
                        <label for="branch" class=" col-md-3"><?php echo lang('branch'); ?></label>
                        <div class=" col-md-9">
                            <?php
                            echo form_input(array(
                                "id" => "branch",
                                "name" => "branch",
                                "value" =>$model_info->branch,
                                //"readonly"=> "true",
                                "class" => "form-control validate-hidden",
                                "placeholder" => lang('branch'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                                 "readonly"=> true,
                            ));
                            ?>
                            <span id='message'></span>
                        </div>
                    </div>


               <div class="form-group">
                        <label for="company working hours for one day" class=" col-md-3"><?php echo lang('company_working_hours_for_one_day'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown(
                                    "payslip_working_hours", array(
                                        ""=>"-",
                                "1" => "1",
                                "2" => "2",
                                "3" => "3",
                                "4" => "4","5" => "5","6" => "6","7" => "7","8" => "8","9" => "9","10" => "10","11" => "11","12" => "12","13" => "13","14" => "14","15" => "15","16" => "16","17" => "17","18" => "18","19" => "19","20" => "20","21" => "21","22" => "22","23" => "23","24" => "24",
                                    ), $model_info->payslip_working_hours, "class='select2 mini' disabled id='payslip_working_hours'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="annual_leave" class=" col-md-3"><?php echo lang('annual_leave'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown(
                                    "payslip_casual_leave",$annual_leave_dropdown, $model_info->payslip_casual_leave, "class='select2 mini' disabled  id='payslip_casual_leave' "
                            );
                            ?>
                        </div>
                    </div>
     <div class="form-group">
        <label for="status" class=" col-md-3"><?php  echo lang('payslip'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_radio(array(
                "id" => "payslip_status_attendance",
                "name" => "payslip_created_status",
                "data-msg-required" => lang("field_required"),
                    ), "create_attendance", ($model_info->payslip_created_status === "create_attendance") ? true : ($model_info->payslip_created_status !== "create_timesheets") ? true : false);
            ?>
            <label for="status_active" class="mr15"><?php echo lang('attendance'); ?></label>
            <?php
            echo form_radio(array(
                "id" => "payslip_status_timesheets",
                "name" => "payslip_created_status",
                "data-msg-required" => lang("field_required"),
                    ), "create_timesheets", ($model_info->payslip_created_status === "create_timesheets") ? true : false);
            ?>
            <label for="status_inactive" class=""><?php echo lang('timesheets'); ?></label>
        </div>
    </div> 

    <div class="form-group">
        <label for="status" class=" col-md-3"><?php echo lang('ot_amount'); ?></label>
        <div class=" col-md-9">
         <a>
                   <!--  <h5><?php echo lang("can_access_payslip"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5> --> 
                   
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ot_permission_no",
                            "name" => "payslip_ot_permission",
                            "value" => "no",
                            "class" => "ot_permission toggle_specific",
                                ),$model_info->payslip_ot_permission,  ($model_info->payslip_ot_permission === "no") ? true : false);
                        ?>
                        <label for="payslip_permission_no"><?php echo lang("disable"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ot_permission_all",
                            "name" => "payslip_ot_permission",
                            "value" => "all",
                            "class" => "ot_permission toggle_specific",
                                ), $model_info->payslip_ot_permission, ($model_info->payslip_ot_permission === "all") ? true : false);
                        ?>
                        <label for="ot_permission_all"><?php echo lang("enable"); ?></label>
                    </div>
                    <div class="form-group" style="display: none;">
                        <?php
                        echo form_radio(array(
                            "id" => "ot_permission_specific",
                            "name" => "payslip_ot_permission",
                            "value" => "specific",
                            "class" => "ot_permission toggle_specific",
                                ),$model_info->payslip_ot_permission,  ($model_info->payslip_ot_permission === "specific") ? true : false);
                        ?>
                        <label for="ot_permission_specific"><?php echo lang("enable_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $model_info->payslip_ot_permission_specific; ?>" name="payslip_ot_permission_specific" id="ot_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
                    </a>
                </div>
            </div>


      
        <!--div class="form-group">
            <label for="payslip_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php /*
                echo form_dropdown("payslip_user_id", $members_dropdown, $model_info->user_id, "class='select2 validate-hidden' id='payslip_user_id'");
                */?>
            </div>
        </div-->

        <div class="modal-footer">
            <div class="row">
                
                <button id="submit_payslip" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
       $("#payslip-form").appForm({
            onSuccess: function (result) {
                $("#monthly-payslip-table").appTable({newData: result.data, dataId: result.id});
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                   
                    //window.location = 'Payslip/pays';
                    window.location = "<?php echo site_url('payslip/view'); ?>/" + result.id;
                }
            }
        });

       $("#country").select2({
            multiple: false,
            data: <?php echo ($company_setup_country_dropdown); ?>
        });

        $("#branch").select2({
            multiple: false,
            data: <?php echo ($company_branch_dropdown); ?>
        });

       var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }
/*if (isUpdate) {
    var payslip =$("#pay").val(); 

         $("#payslip_user_id").val(payslip)
        } */

       

        $("#payslip_user_id_title_dropdwon_icon").click(function () {
             applySelect2OnItemTitle();
        })

        $("#payslip_user_id_title_dropdwon_icons").click(function () {
         $("#payslip_user_ids").hide();
         $("#payslip_user_id").show();
         $("#aa").show();
         $("#bb").hide();
             applySelect2OnItemTitle();
        })
        
        setDatePicker("#payslip_date");

        $("#payslip-form .select2").select2();
      
    });

    function applySelect2OnItemTitle() {
    //var datepayslip =$("#payslip_date").val();
        $("#payslip_user_id").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("payslip/get_payslip_user_id_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page,datepayslip) {
                    return {
                        q: term,payslip_date:$("#payslip_date").val() // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
               
            } else if (e.val) {

                
                $.ajax({ 
                    url: "<?php echo get_uri("payslip/get_country_payslip_user_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
                            //alert(response.item_info.id);
                           
                                //$("#payslip_created_status").val(response.item_info.payslip_created_status);
                         
                            $("input[name=payslip_created_status][value=" + response.item_info.payslip_created_status + "]").prop('checked', true);
                            //$("input[name=payslip_ot_permission][value=" + response.item_info.ot_permission + "]").prop('checked', true);
                              var  result_ot_permission = response.item_info.ot_permission;
                             // alert(result_ot_permission)
                          if(result_ot_permission == "all"){
                             $("#ot_permission_all").click()
                             $(".specific_dropdown").hide();
                              $("#ot_specific_dropdown").select2( "val","");
                            // $("input[name=payslip_ot_permission][value=" + response.item_info.ot_permission + "]").prop('checked', true);
                         }else if(result_ot_permission == "no"){
                            $("#ot_permission_no").click();
                             $(".specific_dropdown").hide();
                              $("#ot_specific_dropdown").select2( "val","");
                             //$("input[name=payslip_ot_permission][value=" + response.item_info.ot_permission + "]").prop('checked', true);
                         }else if(result_ot_permission == "specific"){
                           // $("input[name=payslip_ot_permission][value=" + response.item_info.ot_permission + "]").prop('checked', true);
                            //$("ot_permission_specific").click();
                           // $(".toggle_specific").click();
                            //$("#ot_specific_dropdown").select2( "val",[response.item_info.ot_permission_specific]);
                            //$(".specific_dropdown").show();
              //end specific  OT user check
                             $.ajax({ 
                    url: "<?php echo get_uri("payslip/get_country_payslip_user_ot_specific_info_suggestion"); ?>",
                    data: {ot_value:response.item_info.ot_permission_specific,user_id:$("#payslip_user_id").val(),ot_permission:result_ot_permission},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            var ot_result = response.ot_result;
                            if(ot_result == "yes")
                            {
                                $("#ot_permission_all").click();
                                $(".specific_dropdown").hide();
                               $("#ot_specific_dropdown").select2( "val",""); 
                            }else  if (ot_result == "no"){
                                $("#ot_permission_no").click();
                                $(".specific_dropdown").hide();
                                $("#ot_specific_dropdown").select2( "val","");

                            }

                        }}
                    });
                    //end specific  OT user check
                         }
                         
                         
                          
                               // $("#payslip_ot_permission").val(response.item_info.ot_permission);
                                //$("#payslip_ot_permission_specific").val(response.item_info.ot_permission_specific);
                                //$("#ot_specific_dropdown").select2("val",response.item_info.ot_permission_specific);
                                //$("#payslip_ot_permission_specific").click()

                             var work_hours = response.item_info.company_working_hours_for_one_day;
                             var cas_leave=  response.item_info.user_annual_leave;
                             var country = response.item_info.user_country;
                             var branch =  response.item_info.user_branch;
                            if(work_hours ==null){
                              $("#submit_payslip").prop('disabled', true).prop('title','Please fill in all the fields ');

                            }else if(country == null){
                                $("#submit_payslip").prop('disabled', true).prop('title','Please fill in all the fields');
                            }else if(branch == null){
                                $("#submit_payslip").prop('disabled', true).prop('title','Please fill in all the fields');
                            }else if(cas_leave == null){
                             $("#submit_payslip").prop('disabled', true).prop('title','Please fill in  all the fields');
                            }else{
                                $("#submit_payslip").prop('disabled', false);
                            }
                               
                                $("#payslip_working_hours").select2("val",response.item_info.company_working_hours_for_one_day);
                                //$("#payslip_working_hours").val(response.item_info.company_working_hours_for_one_day);
                          
                                //$("#payslip_casual_leave").select2("val",response.item_info.maximum_no_of_casual_leave_per_month);
                                $("#payslip_casual_leave").select2("val",response.item_info.user_annual_leave);
                                //$("#payslip_casual_leave").val(response.item_info.maximum_no_of_casual_leave_per_month);

                                
                                $("#payslip_working_hourss").val(response.item_info.company_working_hours_for_one_day);
                                $("#payslip_casual_leaves").val(response.item_info.user_annual_leave);
                           
                            $("#country").select2("val",response.item_info.user_country);
                             $("#branch").select2("val",response.item_info.user_branch);


                            

                           
                            
                            
                            
                            
                        }else {
                            alert("This user not assign country ");
                            $("#payslip_working_hours").select2("val","");
                            $("#payslip_casual_leave").select2("val","");
                            $("#ot_specific_dropdown").select2( "val","");
                            $("#ot_permission_no").click();
                            $("ot_permission_specific").val("");
                            $("#ot_permission_all").val("");
                            //$("#submit_payslip").prop('disabled', true);
                            //$('input[name="payslip_ot_permission"]').val("");
                            $("#country").select2("val","0");
                            $("#branch").select2( "val","");
                            $("#payslip_working_hourss").val("");
                            $("#payslip_casual_leaves").val("");
                            $("#submit_payslip").prop('disabled', true).prop('title','Please fill in  all the fields')

                           
                        }
                    }
                });
            }

        });
    }
    //ot amount spefic 
$("#ot_specific_dropdown").select2({
            multiple: true,
            formatResult: teamAndMemberSelect2Format,
            formatSelection: teamAndMemberSelect2Format,
            data: <?php echo ($members_and_teams_dropdown); ?>
        });

$(".toggle_specific").click(function () {
            toggle_specific_dropdown();
        });
 $('[data-toggle="tooltip"]').tooltip();
toggle_specific_dropdown();
        function toggle_specific_dropdown() {
            var selectors = [".ot_permission"];
            $.each(selectors, function (index, element) {
                var $element = $(element + ":checked");
                if ($element.val() === "specific") {
                    $element.closest("a").find(".specific_dropdown").show().find("input").addClass("validate-hidden");
                } else {
                    //console.log($element.closest("li").find(".specific_dropdown"));
                    $(element).closest("a").find(".specific_dropdown").hide().find("input").removeClass("validate-hidden");
                }
            });

        }
/*function applySelectPayslipUserId() {
    var datepayslip =$("#payslip_date").val();
    var emp_userid =$("#payslip_user_id").val();
    $.ajax({
                     url: "<?php echo get_uri("payslip/get_emp_monthly_payslip_info"); ?>",
                    data: {payslip_date:datepayslip ,user_id: emp_userid },
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {


if (response && response.success) {
 
                            if (response.item_info>0) 
                            {
                                $("#submit_payslip").prop('disabled', true);
                            }

                            
                        }
                        //auto fill the description, unit type and rate fields.

 /*var no_user_id=$("#payslip_dates").val();  
                    if  (no_user_id>0)
                    {


                        $("#submit-payslip").prop('disabled', true);
                        $('#message').html('Create the Employee Payslip Monthly Once').css('color', 'red');
                        //$("#submit-payslip").hide();
                        //alert('create the employee payslip monthly once ');
                    }else {
                        //alert('success');
                        //$("#submit-payslip").show();
                        $("#submit-payslip").prop('disabled', false)
                        $('#message').html('Discount Percentage should less than profit percentage').css('color', 'white');
                    } 
         
                    } 


                });

} */
</script>
<script type="text/javascript">
    
    $("#country").change(function () {
    $("#branch").val("").attr('readonly', false)
                    var country_name =$("#branch").val();

          $("#branch").select2({
            multiple: false,
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("events/get_branches_suggestion"); ?>",
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
                //$('#branch').select2({multiple: true, data: results.data});
                }
            }
        })


    });
</script>
 



