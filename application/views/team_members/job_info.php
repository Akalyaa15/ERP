<div class="tab-content">
    <?php echo form_open(get_uri("team_members/save_job_info/"), array("id" => "job-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

    <input type="hidden" name="buid" id="buid" value="<?php echo isset($user_info->buid) ? $user_info->buid : ''; ?>" />
    <input name="user_id" type="hidden" value="<?php echo isset($user_id) ? $user_id : ''; ?>" />

    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('job_info'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="job_title" class="col-md-2"><?php echo lang('job_title'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "job_title",
                        "name" => "job_title",
                        "value" => isset($job_info->job_title) ? $job_info->job_title : "",
                        "class" => "form-control",
                        "placeholder" => lang('job_title')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="salary" class="col-md-2"><?php echo lang('salary'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary",
                        "name" => "salary",
                        "value" => isset($job_info->salary) ? $job_info->salary : "",
                        "class" => "form-control",
                        "min" => 0,
                        "placeholder" => lang('salary')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="ctc" class="col-md-2">CTC</label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "ctc",
                        "name" => "ctc",
                        "value" => isset($job_info->ctc) ? $job_info->ctc : "",
                        "class" => "form-control",
                        "min" => 0,
                        "placeholder" => lang('salary'),
                        "readonly" => 'true'
                    ));
                    ?>
                </div>
            </div>           <div class="form-group">
                <label for="salary_term" class=" col-md-2"><?php echo lang('salary_term'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary_term",
                        "name" => "salary_term",
                        "value" => $job_info->salary_term? $job_info->salary_term : "Monthly",
                        "class" => "form-control",
                        "placeholder" => lang('salary_term'),
                        "readonly" =>'true'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="currency" class=" col-md-2"><?php echo lang('currency'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "currency",
                        "name" => "currency",
                        "value" => $job_info->currency,
                        "class" => "form-control",
                        "placeholder" => lang('currency'),
                        "readonly" =>'true'
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="currency_symbol" class=" col-md-2"><?php echo lang('currency_symbol'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "currency_symbol",
                        "name" => "currency_symbol",
                        "value" => $job_info->currency_symbol,
                        "class" => "form-control",
                        "placeholder" => lang('currency_symbol'),
                        "readonly" =>'true'
                    ));
                    ?>
                </div>
            </div>
                        <div class="form-group">
                <label for="date_of_hire" class=" col-md-2"><?php echo lang('date_of_hire'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "date_of_hire",
                        "name" => "date_of_hire",
                        "value" => $job_info->date_of_hire,
                        "class" => "form-control",
                        "placeholder" => lang('date_of_hire'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                        "data-rule-lessThanOrEqual" =>get_my_local_time(get_setting('date_format')),
                "data-msg-lessThanOrEqual" => lang("generate_date_must_be_equal_or_less_than_current_date"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                        <label for="annual_leave" class=" col-md-2"><?php echo lang('annual_leave'); ?></label>
                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "annual_leave",$annual_leave_dropdown , $user_info->annual_leave, "class='select2 validate-hidden mini'  id='annual_leave' data-rule-required='true' data-msg-required='" . lang('field_required') . "' "
                            );
                            ?>
                        </div>
                        <div class="col-md-6">
               <span id= "eligible_message"></span>
              </div>
             </div>
           <!--  <?php /* if ($this->login_user->is_admin) { */?> -->
            <div class="form-group">
                <label for="country" class="col-md-2"><?php echo lang('employer'); ?></label>
                <div class="col-md-10">
                    <?php
                     echo form_dropdown("company_id", $company_dropdown, $user_info->company_id, "class='select2 validate-hidden' id='user-company' data-rule-required='true' data-msg-required='" . lang('field_required') . "'");
                    ?>
                   
                </div>
            </div>            
            <div class="form-group">
                <label for="branch" class="col-md-2"><?php echo lang('branch'); ?></label>
                <div class="col-md-10">
                    <?php /*
                    echo form_dropdown("branch", $branches_dropdown, array(), "class='select2' id='user-branch'");
                    */?>
                     <?php
                            echo form_input(array(
                                "id" => "user-branch",
                                "name" => "branch",
                                 "value" => $user_info->branch,
                                //"readonly"=> "true",
                                "class" => "form-control validate-hidden",
                                "placeholder" => lang('branch'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                   
                </div>
            </div>
                        <div class="form-group">
                <label for="country" class="col-md-2"><?php echo lang('country'); ?></label>
                <div class="col-md-10">
                    <?php
                     echo form_dropdown("country", $country_dropdown, $user_info->country, "class='select2 validate-hidden' id='user-country' data-rule-required='true' data-msg-required='" . lang('field_required') . "'");
                    ?>
                   
                </div>
            </div>

            <div class="form-group">
                <label for="department" class="col-md-2"><?php echo lang('department'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_dropdown("department", $department_dropdown, $user_info->department, "class='select2  validate-hidden' id='user-department' data-rule-required='true' data-msg-required='" . lang('field_required') . "'");
                    ?>
                   
                </div>
            </div>
            <div class="form-group">
                <label for="designation" class="col-md-2"><?php echo lang('designation'); ?></label>
                <div class="col-md-10">
                   <?php
        echo form_input(array(
            "id" => "user-designation",
            "name" => "designation",
           "value" =>$user_info->designation,
            "class" => "form-control validate-hidden",
            "placeholder" => lang('designation'),
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),            
//"readonly" => 'true',
        ));
        ?></div>
            </div>
        <!-- <?php /*} */?> -->
        </div>

        <?php if ($this->login_user->is_admin) { ?>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        <?php } ?>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#job-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                window.location.href = "<?php echo get_uri("team_members/view/" . $job_info->user_id); ?>" + "/job_info";
            }
        });
        $("#job-info-form .select2").select2();

        setDatePicker("#date_of_hire");

         $("#user-branch").select2({
                multiple: false,
                data: <?php echo $company_branch_dropdown; ?>
            });


       $("#user-designation").select2({
                multiple: false,
                data: <?php echo $designation_dropdown; ?>
            });

    });
</script> 
<script type="text/javascript">
     $("#user-department").change(function () {
        var dep_code =$("#user-department").val();
        $("#user-designation").val("").attr('readonly', false)

       $("#user-designation").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("designation/get_designation"); ?>",
                dataType: 'json',
               data: function (term, page) {
                    return {
                        dep_code: dep_code // search term
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

     /*$("#job-info-tab").change(function () { 
       $("#user-branch").addClass("validate-hidden");
       $("#user-designation").addClass("validate-hidden");
       $("#user-department").addClass("validate-hidden");

     });*/

    $("#user-company").change(function () {
    $("#user-branch").val("").select2('readonly', false)
     $("#annual_leave").select2("val","")
     $("#currency_symbol").val("")
     $("#currency").val("")
    
                    var company_name =$("#user-company").val();

          $("#user-branch").select2({
           // multiple: true,
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("team_members/get_branches_suggestion"); ?>",
                dataType: 'json',
               data: function (company_name, page) {
                    return {
                        q: $("#user-company").val(),
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

           /*$.ajax({ 
                    url: "<?php /*echo get_uri("team_members/get_country_annual_leave_info_suggestion"); */?>",
                    data: {item_name: $("#user-country").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
                            $("#annual_leave").select2("val",response.item_info.maximum_no_of_casual_leave_per_month);
                        }
                    }
                });*/


    });
 
    

</script>   
<script type="text/javascript">
        $("#user-company").change(function () {
    
                    var country_name =$("#user-branch").val();

           $.ajax({ 
                    url: "<?php echo get_uri("team_members/get_country_branch"); ?>",
                    data: {item_name: $("#user-company").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
    


                // $("#currency_symbol").val(response.item_info.currency_symbol)  
                 // $("#currency").val(response.item_info.currency)          
                    //$("#buid").val(response.item_info.buid)  
                          

                   }
                    }
                });
      });
       $("#user-branch").change(function () {

               $("#user-country").select2("val","")
  $.ajax({
                    url: "<?php echo get_uri("team_members/get_country"); ?>",
                    data: {item_name: $("#user-branch").val(),company_name: $("#user-company").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
 
                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
                     $("#user-country").select2("val",response.item_info.company_setup_country)
                    $("#user-country").select2("readonly","true")
                    $("#buid").val(response.item_info.buid) 
                     $("#annual_leave").select2("val",response.item_info.maximum_no_of_casual_leave_per_month);
                     // eligible cutt off 
     var annual_cuff = response.item_info.maximum_no_of_casual_leave_per_month;
        //Math.round(value,2)
        var year = 365/annual_cuff;
        var mon = 30/annual_cuff;

        var month = mon.toFixed(2);
        var year  = year.toFixed(2);
        var eligible_result = "Monthly => "+"30/"+annual_cuff+" ="+month+" days"+"<br>"+"Yearly => "+"365/"+annual_cuff+" = "+year+" days";
       
        $("#eligible_message").html(eligible_result);
        //end eligible cutofff 
                   // branch currency 
     $.ajax({
                    url: "<?php echo get_uri("team_members/get_branch_currency"); ?>",
                    data: {item_name: $("#user-country").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
                         if (response && response.success) {
                        $("#currency_symbol").val(response.item_info.currency_symbol)
                        $("#currency").val(response.item_info.currency)
                    }
                }
                })
                // end brnach currency
                      }
                    }
                });

})
     $("#salary").keyup(function () {
var kk= $("#salary").val()
var bv=kk*12;
$("#ctc").val(bv)
})
     $("#salary").change(function () {
var kk= $("#salary").val()
var bv=kk*12;
$("#ctc").val(bv)
})</script>
<?php if($job_info->salary){ ?>
<script type="text/javascript">
        $(document).ready(function () {
var kk= $("#salary").val()
var bv=kk*12;
$("#ctc").val(bv)
});
</script>
<?php } ?>
<?php if($user_info->company_id){ ?>
<script type="text/javascript">
    $(document).ready(function () {
                            $("#user-country").select2("readonly","true")

});    
</script>
<?php } ?>
<script type="text/javascript">
     $(document).ready(function () {
    $("#annual_leave").on("change", function() {
   
        var annual_cuff = $("#annual_leave").val();
        //Math.round(value,2)
        var year = 365/annual_cuff;
        var mon = 30/annual_cuff;

        var month = mon.toFixed(2);
        var year  = year.toFixed(2);
        var eligible_result = "Monthly => "+"30/"+annual_cuff+"="+month+" days"+"<br>"+"Yearly => "+"365/"+annual_cuff+"="+year+" days";
       
        $("#eligible_message").html(eligible_result);
      });  
});


</script>
<?php if($user_info->annual_leave) {?>
<script type="text/javascript">
    
     $(document).ready(function () {
   
        var annual_cuff = $("#annual_leave").val();
        //Math.round(value,2)
        var year = 365/annual_cuff;
        var mon = 30/annual_cuff;

        var month = mon.toFixed(2);
        var year  = year.toFixed(2);
        var eligible_result = "Monthly => "+"30/"+annual_cuff+" ="+month+" days"+"<br>"+"Yearly => "+"365/"+annual_cuff+" = "+year+" days";
       
        $("#eligible_message").html(eligible_result);
        
});


</script>
<?php } ?>

<?php if (!$this->login_user->is_admin) { ?>
    <script type="text/javascript">
    
     $(document).ready(function () {
   
       
       
         $("#user-country").select2("readonly","true")
          $("#user-branch").select2("readonly","true")
           $("#user-company").select2("readonly","true")
           $("#user-designation").select2("readonly","true")
            $("#user-department").select2("readonly","true")
          
        
});

/* remove the right click content */
$('.tab-content').bind('contextmenu', function(e){
    return false;
}); 

/* end remove the right click content */


</script>
    <?php } ?>
