<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
             <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
   <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("loan"); ?></h4></li>
            <li><a id="monthly-loan-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-loan"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("loan/yearly/"); ?>" data-target="#yearly-loan"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("loan/custom/"); ?>" data-target="#custom-loan"><?php echo lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("loan/yearly_chart/"); ?>" data-target="#yearly-chart"><?php echo lang('chart'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("loan/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_loan'), array("class" => "btn btn-default mb0", "title" => lang('add_loan'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-loan">
                <div class="table-responsive">
                    <table id="monthly-loan-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-loan"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-loan"></div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-chart"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadLoanTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

        $(selector).appTable({
            source: '<?php echo_uri("loan/list_data") ?>',
            dateRangeType: dateRange,
            filterDropdown: [
                {name: "category_id", class: "w160", options: <?php echo $categories_dropdown; ?>},
                 {name: "user_ids", class: "w160", options: <?php echo $rm_members_dropdown; ?>},
                {name: "user_id", class: "w160", options: <?php echo $members_dropdown; ?>},
                {name: "client_id", class: "w160", options: <?php echo $clients_dropdown; ?>},
                {name: "vendor_id", class: "w160", options: <?php echo $vendors_dropdown; ?>},
               
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>},
                {name: "status", class: "w150", options: <?php $this->load->view("loan/loan_statuses_dropdown"); ?>}
            ],
            order: [[0, "asc"]],
            rangeDatepicker: customDatePicker,
            columns: [
                {title: '<?php echo lang("voucher_no") ?>', "class": "w250"},
                {visible: false, searchable: false},
                {title: '<?php echo lang("loan_date") ?>', "iDataSort": 0},
                {title: '<?php echo lang("due_date") ?>'},
                {title: '<?php echo lang("category") ?>',"class": "w10p"},
                {title: '<?php echo lang("title") ?>',"class": "w10p"},
                {title: '<?php echo lang("description") ?>',"class": "w25p"},
                
                {title: '<?php echo lang("amount") ?>', "class": "text-right w10p"},
               
                {title: '<?php echo lang("interest") ?>', "class": "text-center w10p"},
                 {title: '<?php echo lang("interest_amount") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("total") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("paid_amount") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("due") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("files") ?>'},
                {title: '<?php echo lang("status") ?>'},
                 {title: '<?php echo lang("voucher_status") ?>', "class": "w5p"},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"}
                 
<?php echo $custom_field_headers; ?>,
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            xlsColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            summation: [{column: 7, dataType: 'currency'},  {column: 9, dataType: 'currency'}, {column: 10, dataType: 'currency'},{column: 11, dataType: 'currency'},{column: 12, dataType: 'currency'}]
        });
    };

    $(document).ready(function () {
        $("#monthly-loan-button").trigger("click");
        loadLoanTable("#monthly-loan-table", "monthly");
    });
</script>
