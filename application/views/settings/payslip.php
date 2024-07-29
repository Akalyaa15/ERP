<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "payslip";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_payslip_settings"), array("id" => "payslip-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("payslip_settings"); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="logo" class=" col-md-2"><?php echo lang('payslip_logo'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="payslip-logo-preview" src="<?php echo get_file_uri(get_setting("system_file_path") . get_setting("payslip_logo")); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="payslip_logo_file" class="cropbox-upload upload" name="payslip_logo_file" type="file" data-height="100" data-width="300" data-preview-container="#payslip-logo-preview" data-input-field="#payslip_logo" />
                            </div>
                            <input type="hidden" id="payslip_logo" name="payslip_logo" value=""  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payslip_prefix" class=" col-md-2"><?php echo lang('payslip_prefix'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "payslip_prefix",
                                "name" => "payslip_prefix",
                                "value" => get_setting("payslip_prefix"),
                                "class" => "form-control",
                                "placeholder" => strtoupper(lang("payslip")) . " #"
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payslip_color" class=" col-md-2"><?php echo lang('payslip_color'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "payslip_color",
                                "name" => "payslip_color",
                                "value" => get_setting("payslip_color"),
                                "class" => "form-control",
                                "placeholder" => "Ex. #e2e2e2"
                            ));
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="payslip_footer" class=" col-md-2"><?php echo lang('payslip_footer'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_textarea(array(
                                "id" => "payslip_footer",
                                "name" => "payslip_footer",
                                "value" => get_setting("payslip_footer"),
                                "class" => "form-control"
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payslip_style" class=" col-md-2"><?php echo lang('payslip_style'); ?></label>
                        <div class="col-md-10">
                            <?php
                            $payslip_style = get_setting("payslip_style") ? get_setting("payslip_style") : "style_1";
                            ?>
                            <input type="hidden" id="payslip_style" name="payslip_style" value="<?php echo $payslip_style; ?>" />

                            <div class="clearfix invoice-styles">
                                <div data-value="style_1" class="item <?php echo $payslip_style != 'style_2' ? ' active ' : ''; ?>" >
                                    <img src="<?php echo get_file_uri("assets/images/payslip_style_1.png") ?>" alt="style_1" />
                                </div>
                                <div data-value="style_2" class="item <?php echo $payslip_style === 'style_2' ? ' active ' : ''; ?>" >
                                    <img src="<?php echo get_file_uri("assets/images/payslip_style_2.png") ?>" alt="style_2" />
                                </div>

                            </div>    
                        </div>
                        <div class="form-group">
                        <label for="payslip_generate_date" class=" col-md-2"><?php echo lang('payslip_generate_date'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "payslip_generate_date", array(
                                "01" => "01",
                                "02" => "02",
                                "03" => "03",
                                "04" => "04",
                                "05" => "05",
                                "06" => "06",
                                "07" => "07",
                                "08" => "08",
                                "09" => "09",
                                "10" => "10",
                                "11" => "11",
                                "12" => "12",
                                "13" => "13",
                                "14" => "14",
                                "15" => "15",
                                "16" => "16",
                                "17" => "17",
                                "18" => "18",
                                "19" => "19",
                                "20" => "20",
                                "21" => "21",
                                "22" => "22",
                                "23" => "23",
                                "24" => "24",
                                "25" => "25",
                                "26" => "26",
                                "27" => "27",
                                "28" => "28",
                                "29" => "29",
                                "30" => "30",
                                "31" => "31",), get_setting('payslip_generate_date'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company working hours for one day" class=" col-md-2"><?php echo lang('company_working_hours_for_one_day'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "company_working_hours_for_one_day", array(
                               "1" => "1",
                                "2" => "2",
                                "3" => "3",
                                "4" => "4","5" => "5","6" => "6","7" => "7","8" => "8","9" => "9","10" => "10","11" => "11","12" => "12","13" => "13","14" => "14","15" => "15","16" => "16","17" => "17","18" => "18","19" => "19","20" => "20","21" => "21","22" => "22","23" => "23","24" => "24",
                                    ), get_setting('company_working_hours_for_one_day'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="annual_leave" class=" col-md-2"><?php echo lang('annual_leave'); ?></label>
                        <div class="col-md-3">
                            <?php /*
                            echo form_dropdown(
                                    "maximum_no_of_casual_leave_per_month", array(
                                "00" => "00",
                                "01" => "01",
                                "02" => "02",
                                "03" => "03",
                                "04" => "04",
                                "05" => "05",
                                "06" => "06",
                                "07" => "07",
                                "08" => "08",
                                "09" => "09",
                                "10" => "10",
                                "11" => "11",
                                "12" => "12",
                                "13" => "13",
                                "14" => "14",
                                "15" => "15",
                                "16" => "16",
                                "17" => "17",
                                "18" => "18",
                                "19" => "19",
                                "20" => "20",
                                "21" => "21",
                                "22" => "22",
                                "23" => "23",
                                "24" => "24",
                                "25" => "25",
                                "26" => "26",
                                "27" => "27",
                                "28" => "28",
                                "29" => "29",
                                "30" => "30",
                                "31" => "31",
                                "32" => "32",
                                "33" => "33",
                                "34" => "34",
                                "35" => "35",
                                "36" => "36",
                                "37" => "37",
                                "38" => "38",
                                "39" => "39",
                                "40" => "40",
                                "41" => "41",
                                "42" => "42","43" => "43","44" => "44","45" => "45","46" => "46","47" => "47","48" => "48",
                                "49" => "49","50" => "50",), get_setting('maximum_no_of_casual_leave_per_month'), "class='select2 mini'"
                            );
                            */?>
                            <?php 
                            echo form_dropdown(
                                    "maximum_no_of_casual_leave_per_month", $annual_leave_dropdown, get_setting('maximum_no_of_casual_leave_per_month'), "class='select2 mini' id='maximum_no_of_casual_leave_per_month'"
                            );
                            ?>
                        </div>
                        <div class="col-md-6">
               
                <span id= "eligible_message"></span>
              
               
            </div>
                    </div>
                   <!-- <div class="form-group">
        <label for="status" class=" col-md-2"><?php  echo lang('ot_amount'); ?></label>
        <div class=" col-md-10">
            <?php /*
            echo form_radio(array(
                "id" => "status_active",
                "name" => "payslip_ot_status",
                "data-msg-required" => lang("field_required"),
                    ), "active", (get_setting("payslip_ot_status") === "active") ? true : (get_setting("payslip_ot_status") !== "inactive") ? true : false);
            ?>
            <label for="status_active" class="mr15"><?php echo lang('enable'); ?></label>
            <?php
            echo form_radio(array(
                "id" => "status_inactive",
                "name" => "payslip_ot_status",
                "data-msg-required" => lang("field_required"),
                    ), "inactive", (get_setting("payslip_ot_status") === "inactive") ? true : false);
            */?>
            <label for="status_inactive" class=""><?php echo lang('disable'); ?></label>
        </div>
    </div> -->

 <div class="form-group">
        <label for="status" class=" col-md-2"><?php  echo lang('payslip'); ?></label>
        <div class=" col-md-10">
            <?php 
            echo form_radio(array(
                "id" => "payslip_status_attendance",
                "name" => "payslip_created_status",
                "data-msg-required" => lang("field_required"),
                    ), "create_attendance", (get_setting("payslip_created_status") === "create_attendance") ? true : (get_setting("payslip_created_status") !== "create_timesheets") ? true : false);
            ?>
            <label for="status_active" class="mr15"><?php echo lang('attendance'); ?></label>
            <?php
            echo form_radio(array(
                "id" => "payslip_status_timesheets",
                "name" => "payslip_created_status",
                "data-msg-required" => lang("field_required"),
                    ), "create_timesheets", (get_setting("payslip_created_status") === "create_timesheets") ? true : false);
            ?>
            <label for="status_inactive" class=""><?php echo lang('timesheets'); ?></label>
        </div>
    </div> 
<!-- OT Amount specific team members -->
<div class="form-group">
        <label for="status" class=" col-md-2"><?php echo lang('ot_amount'); ?></label>
        <div class=" col-md-10">
         <a>
                   <!--  <h5><?php echo lang("can_access_payslip"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5> --> 
                   
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ot_permission_no",
                            "name" => "ot_permission",
                            "value" => "no",
                            "class" => "ot_permission toggle_specific",
                                ),get_setting("ot_permission"),  (get_setting("ot_permission") === "no") ? true : false);
                        ?>
                        <label for="payslip_permission_no"><?php echo lang("disable"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ot_permission_all",
                            "name" => "ot_permission",
                            "value" => "all",
                            "class" => "ot_permission toggle_specific",
                                ), get_setting("ot_permission"), (get_setting("ot_permission") === "all") ? true : false);
                        ?>
                        <label for="ot_permission_all"><?php echo lang("enable_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "ot_permission_specific",
                            "name" => "ot_permission",
                            "value" => "specific",
                            "class" => "ot_permission toggle_specific",
                                ),get_setting("ot_permission"),  (get_setting("ot_permission") === "specific") ? true : false);
                        ?>
                        <label for="ot_permission_specific"><?php echo lang("enable_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo get_setting("ot_permission_specific"); ?>" name="ot_permission_specific" id="ot_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
                    </a>
                </div>
            </div>




                    </div>
    <?php /*    
                   <!-- <div class="form-group">
                        <label for="send_bcc_to" class=" col-md-2"><?php echo lang('send_bcc_to'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "send_bcc_to",
                                "name" => "send_bcc_to",
                                "value" => get_setting("send_bcc_to"),
                                "class" => "form-control",
                                "placeholder" => lang("email")
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allow_partial_invoice_payment_from_clients" class=" col-md-2"><?php echo lang('allow_partial_invoice_payment_from_clients'); ?></label>

                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "allow_partial_invoice_payment_from_clients", array("1" => lang("yes"), "0" => lang("no")), get_setting('allow_partial_invoice_payment_from_clients'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_invoice_due_pre_reminder" class=" col-md-2"><?php echo lang('send_due_invoice_reminder_notification_before'); ?> <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>

                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "send_invoice_due_pre_reminder", array(
                                "" => " - ",
                                "1" => "1 " . lang("day"),
                                "2" => "2 " . lang("days"),
                                "3" => "3 " . lang("days"),
                                "5" => "5 " . lang("days"),
                                "7" => "7 " . lang("days"),
                                "10" => "10 " . lang("days"),
                                "14" => "14 " . lang("days"),
                                "15" => "15 " . lang("days"),
                                "20" => "20 " . lang("days"),
                                "30" => "30 " . lang("days"),
                                    ), get_setting('send_invoice_due_pre_reminder'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_invoice_due_after_reminder" class=" col-md-2"><?php echo lang('send_invoice_overdue_reminder_after'); ?> <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>

                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "send_invoice_due_after_reminder", array(
                                "" => " - ",
                                "1" => "1 " . lang("day"),
                                "2" => "2 " . lang("days"),
                                "3" => "3 " . lang("days"),
                                "5" => "5 " . lang("days"),
                                "7" => "7 " . lang("days"),
                                "10" => "10 " . lang("days"),
                                "14" => "14 " . lang("days"),
                                "15" => "15 " . lang("days"),
                                "20" => "20 " . lang("days"),
                                "30" => "30 " . lang("days"),
                                    ), get_setting('send_invoice_due_after_reminder'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_recurring_invoice_reminder_before_creation" class=" col-md-2"><?php echo lang('send_recurring_invoice_reminder_before_creation'); ?> <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>

                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "send_recurring_invoice_reminder_before_creation", array(
                                "" => " - ",
                                "1" => "1 " . lang("day"),
                                "2" => "2 " . lang("days"),
                                "3" => "3 " . lang("days"),
                                "5" => "5 " . lang("days"),
                                "7" => "7 " . lang("days"),
                                "10" => "10 " . lang("days"),
                                "14" => "14 " . lang("days"),
                                "15" => "15 " . lang("days"),
                                "20" => "20 " . lang("days"),
                                "30" => "30 " . lang("days"),
                                    ), get_setting('send_recurring_invoice_reminder_before_creation'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div> --> */ ?>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php $this->load->view("includes/cropbox"); ?>

<?php
load_css(array(
    "assets/js/summernote/summernote.css",
    "assets/js/summernote/summernote-bs3.css"
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
    "assets/js/bootstrap-confirmation/bootstrap-confirmation.js",
));
?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payslip-settings-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "payslip_footer") {
                        data[index]["value"] = encodeAjaxPostData(getWYSIWYGEditorHTML("#payslip_footer"));
                    }
                    if (obj.name === "payslip_logo") {
                        var image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = image;
                    }
                });
            },
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
                if ($("#payslip_logo").val()) {
                    location.reload();
                }
            }
        });
        $("#payslip-settings-form .select2").select2();

        initWYSIWYGEditor("#payslip_footer", {height: 100});

        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });

        $(".invoice-styles .item").click(function () {
            $(".invoice-styles .item").removeClass("active");
            $(this).addClass("active");
            $("#payslip_style").val($(this).attr("data-value"));
        });

        $('[data-toggle="tooltip"]').tooltip();

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




        
    });
</script>
<script type="text/javascript">
    
    $("#maximum_no_of_casual_leave_per_month").on("change", function() {
   
        var annual_cuff = $("#maximum_no_of_casual_leave_per_month").val();
        //Math.round(value,2)
        var year = 365/annual_cuff;
        var mon = 30/annual_cuff;

        var month = mon.toFixed(2);
        var year  = year.toFixed(2);
        var eligible_result = "Monthly => "+"30/"+annual_cuff+"="+month+" days"+"<br>"+"Yearly => "+"365/"+annual_cuff+"="+year+" days";
       
        $("#eligible_message").html(eligible_result);
        
});


</script>
<?php if(get_setting('maximum_no_of_casual_leave_per_month')) {?>
<script type="text/javascript">
    
     $(document).ready(function () {
   
        var annual_cuff = $("#maximum_no_of_casual_leave_per_month").val();
        //Math.round(value,2)
        var year = 365/annual_cuff;
        var mon = 30/annual_cuff;

        var month = mon.toFixed(2);
        var year  = year.toFixed(2);
        var eligible_result = "Monthly => "+"30/"+annual_cuff+"="+month+" days"+"<br>"+"Yearly => "+"365/"+annual_cuff+"="+year+" days";
       
        $("#eligible_message").html(eligible_result);
        
});


</script>
<?php } ?>
