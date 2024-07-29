<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("clients_po_list"); ?></h4></li>
            <li><a id="monthly-clients_po_list-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-clients_po_list"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("clients_po_list/yearly/"); ?>" data-target="#yearly-clients_po_list"><?php echo lang('yearly'); ?></a></li>
            
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php

                 
                    echo modal_anchor(get_uri("clients_po_list/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_client_po'), array("class" => "btn btn-default mb0", "title" => lang('add_client_po'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-clients_po_list">
                <div class="table-responsive">
                    <table id="monthly-clients_po_list-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
                </div>
                 <div role="tabpanel" class="tab-pane fade" id="yearly-clients_po_list"></div>
            
            
        </div>
    </div>
</div>
<script type="text/javascript">
    loadClientsPoListTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

        $(selector).appTable({
            source: '<?php echo_uri("clients_po_list/list_data") ?>',
            dateRangeType: dateRange,
            order: [[0, "asc"]],
            rangeDatepicker: customDatePicker,
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo isset($members_dropdown) ? json_encode($members_dropdown) : '[]'; ?>},
                {name: "status_id", class: "w200", options: <?php echo isset($status_dropdown) ? json_encode($status_dropdown) : '[]'; ?>}
            ],
            columns: [
                {title: '<?php echo lang("po_no") ?>', "class": "w250"},
                {title: '<?php echo lang("po_date"); ?>', "class": "w250"},
                {title: '<?php echo lang("clients"); ?>'},
                {title: '<?php echo lang("proforma_no"); ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("tax") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("total") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("paid_amount") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("due") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("files") ?>', "class": "w250p"},
                {title: '<?php echo lang("status") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 5],
            xlsColumns: [1, 2, 3, 4, 5],
            // summation: [{column: 4, dataType: 'currency'}, ...]
        });
    };

    $(document).ready(function () {
        $("#monthly-clients_po_list-button").trigger("click");
        loadClientsPoListTable("#monthly-clients_po_list-table", "monthly");
    });
</script>

<?php $this->load->view("clients_po_list/update_task_script"); ?>
<?php $this->load->view("clients_po_list/update_task_read_comments_status_script"); ?>



