<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
             <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
   <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("income"); ?></h4></li>
            <li><a id="monthly-income-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-income"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("income/yearly/"); ?>" data-target="#yearly-income"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("income/custom/"); ?>" data-target="#custom-income"><?php echo lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("income/yearly_chart/"); ?>" data-target="#yearly-chart"><?php echo lang('chart'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("income/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_income'), array("class" => "btn btn-default mb0", "title" => lang('add_income'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-income">
                <div class="table-responsive">
                    <table id="monthly-income-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-income"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-income"></div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-chart"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadIncomeTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

        $(selector).appTable({
            source: '<?php echo_uri("income/list_data") ?>',
            dateRangeType: dateRange,
            filterDropdown: [
                {name: "category_id", class: "w160", options: <?php echo $categories_dropdown; ?>},
                 {name: "user_ids", class: "w160", options: <?php echo $rm_members_dropdown; ?>},
                {name: "user_id", class: "w200", options: <?php echo $members_dropdown; ?>},
               {name: "client_id", class: "w160", options: <?php echo $clients_dropdown; ?>},
                {name: "vendor_id", class: "w160", options: <?php echo $vendors_dropdown; ?>},
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>}
            ],
            order: [[0, "asc"]],
            rangeDatepicker: customDatePicker,
            columns: [
                {title: '<?php echo lang("voucher_no") ?>', "class": "w250"},
                {visible: false, searchable: false},
                {title: '<?php echo lang("date") ?>', "iDataSort": 0},
                {title: '<?php echo lang("category") ?>'},
                {title: '<?php echo lang("title") ?>'},
                {title: '<?php echo lang("description") ?>'},
                {title: '<?php echo lang("files") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right"},
               /* {title: '<?php echo lang("tax") ?>', "class": "text-right"},
                {title: '<?php echo lang("second_tax") ?>', "class": "text-right"}, */
                {title: '<?php echo lang("cgst_tax") ?>', "class": "text-right"},
                {title: '<?php echo lang("sgst_tax") ?>', "class": "text-right"},
                {title: '<?php echo lang("igst_tax") ?>', "class": "text-right"},
                {title: '<?php echo lang("total") ?>', "class": "text-right"},
                {title: '<?php echo lang("status") ?>', "class": "w5p"},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"}
<?php echo $custom_field_headers; ?>,
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            xlsColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            summation: [{column: 7, dataType: 'currency'}, {column: 8, dataType: 'currency'}, {column: 9, dataType: 'currency'}, {column: 10, dataType: 'currency'},{column: 11, dataType: 'currency'}]
        });
    };

    $(document).ready(function () {
        $("#monthly-income-button").trigger("click");
        loadIncomeTable("#monthly-income-table", "monthly");
    });
</script>
