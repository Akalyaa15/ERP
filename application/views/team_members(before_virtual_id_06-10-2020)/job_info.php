<div class="tab-content">
    <?php echo form_open(get_uri("team_members/save_job_info/"), array("id" => "job-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

    <input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('job_info'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="job_title" class=" col-md-2"><?php echo lang('job_title'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "job_title",
                        "name" => "job_title",
                        "value" => $job_info->job_title,
                        "class" => "form-control",
                        "placeholder" => lang('job_title')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="salary" class=" col-md-2"><?php echo lang('salary'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary",
                        "name" => "salary",
                        //"value" => $job_info->salary ? to_decimal_format($job_info->salary) : "",
                        "value" => $job_info->salary ? $job_info->salary : "",
                        "class" => "form-control",
                        "min"=> 0,
                        "placeholder" => lang('salary')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="salary_term" class=" col-md-2"><?php echo lang('salary_term'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary_term",
                        "name" => "salary_term",
                        "value" => $job_info->salary_term,
                        "class" => "form-control",
                        "placeholder" => lang('salary_term')
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
                        "placeholder" => lang('date_of_hire')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                        <label for="annual_leave" class=" col-md-2"><?php echo lang('annual_leave'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "annual_leave",$annual_leave_dropdown , $user_info->annual_leave, "class='select2 mini'  id='annual_leave' "
                            );
                            ?>
                        </div>
             </div>
            <?php if ($this->login_user->is_admin) { ?>
            
            <div class="form-group">
                <label for="country" class="col-md-2"><?php echo lang('country'); ?></label>
                <div class="col-md-10">
                    <?php
                     echo form_dropdown("country", $country_dropdown, $user_info->country, "class='select2 validate-hidden' id='user-country' data-rule-required='true' data-msg-required='" . lang('field_required') . "'");
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
        <?php } ?>
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

    $("#user-country").change(function () {
    $("#user-branch").val("").attr('readonly', false)
    
                    var country_name =$("#user-branch").val();

          $("#user-branch").select2({
           // multiple: true,
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("events/get_branches_suggestion"); ?>",
                dataType: 'json',
               data: function (country_name, page) {
                    return {
                        q: country_name,
                        ss:$("#user-country").val()// search term
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

           $.ajax({ 
                    url: "<?php echo get_uri("team_members/get_country_annual_leave_info_suggestion"); ?>",
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
                });


    });
 
    

</script>   