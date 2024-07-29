
<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo /*get_payslip_id($payslip_info->id)*/ $payslip_info->payslip_no?$payslip_info->payslip_no:get_payslip_id($payslip_info->id); ?></h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><?php echo anchor(get_uri("payslip/download_pdf/" . $payslip_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("payslip/preview/" . $payslip_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('payslip_preview'), array("title" => lang('payslip_preview')), array("target" => "_blank")); ?> </li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><?php echo modal_anchor(get_uri("payslip/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_payslip'), array("title" => lang('edit_payslip'), "data-post-id" => $payslip_info->id, "role" => "menuitem", "tabindex" => "-1")); ?></li>

                        
                       
                    </ul>
                </span>
                <?php echo modal_anchor(get_uri("payslip/earningsadd_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_earnings_amount'), array("class" => "btn btn-default", "title" => lang('add_earnings_amount'), "data-post-payslip_id" => $payslip_info->id)); ?>
                  <?php echo modal_anchor(get_uri("payslip/deductions_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_deductions_amount'), array("class" => "btn btn-default", "title" => lang('add_deductions_amount'), "data-post-payslip_id" => $payslip_info->id)); ?>
                 <!-- <?php echo modal_anchor(get_uri("payslip/attendance_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_attendance'), array("class" => "btn btn-default", "title" => lang('add_attendance'), "data-post-payslip_id" => $payslip_info->id)); ?> -->
                        <?php echo modal_anchor(get_uri("payslip_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'), "data-post-payslip_id" => $payslip_info->id)); ?>
        </div>
        </div>
                <div id="invoice-status-bar">
            <?php $this->load->view("payslip/payslip_status_bar"); ?>
        </div>
        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                     <h3 style="text-align: center;  font-weight: bold" ><?php if($payslip_info->payslip_created_status==="create_timesheets"){
                        echo "Payslip Based on Timesheets";
                     }else if ($payslip_info->payslip_created_status==="create_attendance"){
                         echo "Payslip Based on Attendance";
                     }; ?></h3>
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $user_table = $this->Users_model->get_one($payslip_info->user_id);
                   $user_country = $user_table->country;
                 if($user_country){
                 $user_country_options = array("numberCode"=> $user_country);
                 $get_user_country_info = $this->Countries_model->get_details($user_country_options)->row();
                 $get_user_country_color = $get_user_country_info->payslip_color;
 
    

}                   
                   // $color =  get_setting("payslip_color");
                   $color = $get_user_country_color? $get_user_country_color :get_setting("payslip_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $style = get_setting("payslip_style");
                    ?>
                    <?php
                    $data = array(
                       
                        "color" => $color,
                        "payslip_info" => $payslip_info
                    );
                    if ($style === "style_2") {
                        $this->load->view('payslip/payslip_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('payslip/payslip_parts/header_style_1.php', $data);
                    }
                    ?>

                </div>



<h5 style="margin-left:2%";>Earnings Details:</h5>
                <div class="table-responsive mt15 pl15 pr15">
                    <table id="payslip-earnings-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="pull-right pr15" id="earnings-total-section" style="width: 420px;">
                        <?php $this->load->view("payslip/earnings_total_section"); ?>
                    </div>
                </div>
<h5 style="margin-left:2%";>Attendance Details:</h5>
                <div class="table-responsive mt15 pl15 pr15">
                    <table id="payslip-attendance-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="pull-right pr15" id="attendance-total-section" style="width: 420px;">
                        <?php $this->load->view("payslip/attendance_total_section"); ?>
                    </div>
                </div>

                
                <h5 style="margin-left:2%";>Addtional Earnings(if any):</h5>
                <div class="table-responsive mt15 pl15 pr15">
                    <table id="payslip-earningsadd-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                <div class="pull-right pr15" id="earningsadd-total-section" style="width: 420px;">
                        <?php $this->load->view("payslip/earningsadd_total_section"); ?> 
                    </div>
                </div>

                



                <h5 style="margin-left:2%";>Deductions Details:</h5>
                <div class="table-responsive mt15 pl15 pr15">
                    <table id="payslip-deductions-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="pull-right pr15" id="deductions-total-section" style="width: 420px;">
                        <?php $this->load->view("payslip/deductions_total_section"); ?>
                    </div>
                </div>


                
                

            </div>
        </div>
        <!-- payslip payments table -->
        <div class="panel panel-default">
                <div class="tab-title clearfix">
                    <h4> <?php echo lang('payslip_payment_list'); ?></h4>
                </div>
                <div class="table-responsive">
                    <table id="payslip-payment-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <!-- payslip payments table end -->
    </div>
</div>



<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#payslip-earnings-table").appTable({
            source: '<?php echo_uri("payslip/earnings_list_data/" . $payslip_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [
               {title: "<?php echo lang("title") ?> "},

                //{title: "<?php echo lang("basic_salary") ?>", "class": "text-right w20p"},
                {title: "<?php echo lang("amount") ?>", "class": "text-center w30p"},
                
                //{title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {
                $("#earnings-total-section").html(result.earnings_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#earnings-total-section").html(result.earnings_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            }
        });
    });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("estimates/get_estimate_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
    };

</script>
<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#payslip-earningsadd-table").appTable({
            source: '<?php echo_uri("payslip/earningsadd_list_data/" . $payslip_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [
               {title: "<?php echo lang("title") ?> "},

                //{title: "<?php echo lang("basic_salary") ?>", "class": "text-right w20p"},
                {title: "<?php echo lang("amount") ?>", "class": "text-right w700p"},
                
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {
                location.reload(true)
                $("#earningsadd-total-section").html(result.earningsadd_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#earningsadd-total-section").html(result.earningsadd_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            }
        });
    });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("estimates/get_estimate_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
    };

</script>
<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#payslip-deductions-table").appTable({
            source: '<?php echo_uri("payslip/deductions_list_data/" . $payslip_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [
                {title: "<?php echo lang("title") ?> "},
                {title: "<?php echo lang("amount") ?>", "class": "text-right w15p"},
                //{title: "<?php echo lang("total") ?>", "class": "text-right w15p"},
                
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {
                location.reload(true)
                $("#deductions-total-section").html(result.deductions_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#deductions-total-section").html(result.deductions_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            }
        });
    });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("estimates/get_estimate_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
    };

</script>
<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#payslip-attendance-table").appTable({
            source: '<?php echo_uri("payslip/attendance_list_data/" . $payslip_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [
                {title: "<?php echo lang("title") ?> "},
               // {title: "<?php echo lang("days") ?>", "class": "text-right w15p"},
               {title: "<?php echo lang("start_date") ?>", "class": "text-left w30p"},
                {title: "<?php echo lang("end_date") ?>", "class": "text-left w35p"},
                {title: "<?php echo lang("no_of_days") ?>", "class": "text-left w20p"},
                
                
               // {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {
                $("#attendance-total-section").html(result.attendance_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#attendance-total-section").html(result.attendance_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
            }
        });
    });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("estimates/get_estimate_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
    };

</script>
<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        //payslip payment table
         $("#payslip-payment-table").appTable({
            source: '<?php echo_uri("payslip_payments/payment_list_data/" . $payslip_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {title: "<?php echo lang("users") ?>", "class": "w15p"},
                //{title: "<?php echo lang("project") ?>", "class": "w15p"},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                //{title: '<?php echo lang("note") ?>'},
                 {title: '<?php echo lang("reference_number") ?>', "class": "w15p"},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                 {title: '<?php echo lang("files") ?>', "class": "w10p"},
                {title: '<?php echo lang("description") ?>', "class": "text-center w25p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {

                updateInvoiceStatusBar();
                $("#payslip-total-section").html(result.payslip_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
                 location.reload(true)
            },
            onUndoSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#payslip-total-section").html(result.payslip_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payslip_id);
                }
                 location.reload(true)
            }
        });
    });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("estimates/get_estimate_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
    };

</script>
<?php
//required to send email 

load_css(array(
    "assets/js/summernote/summernote.css",
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
));
?>
