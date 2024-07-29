<div id="page-content" class="p20 clearfix">
    <div class="panel clearfix">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('purchase_orders'); ?></h4></li>
            <li><a id="monthly-purchase_order-button" class="active" role="presentation" href="javascript:;" data-target="#monthly-purchase_orders"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("purchase_orders/yearly/"); ?>" data-target="#yearly-purchase_orders"><?php echo lang('yearly'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_purchase_order'), array("class" => "btn btn-default", "title" => lang('add_purchase_order'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-purchase_orders">
                <div class="table-responsive">
                    <table id="monthly-purchase_order-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-purchase_orders"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadPurchaseTable = function (selector, dateRange) {
        $(selector).appTable({
            source: '<?php echo_uri("purchase_orders/list_data") ?>',
            order: [[0, "desc"]],
            dateRangeType: dateRange,
            filterDropdown: [{name: "status", class: "w150", options: <?php $this->load->view("purchase_orders/purchase_order_statuses_dropdown"); ?>}
            
            ],
            columns: [
                {title: "<?php echo lang("id") ?> ", "class": "w10p"},
                {title: "<?php echo lang("purchase_order_no") ?> ", "class": "w15p"},
                {title: "<?php echo lang("vendor") ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("purchase_order_date") ?>", "iDataSort": 2, "class": "w10p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("due_date") ?>", "class": "w10p", "iDataSort": 5},
                {title: "<?php echo lang("purchase_order_value") ?>", "class": "w10p text-right"},
               {title: "<?php echo lang("paid_amount") ?>", "class": "w10p text-right"},
               {title: "<?php echo lang("due_amount") ?>", "class": "w10p text-right"},
                {title: "<?php echo lang("status") ?>", "class": "text-center"}
<?php echo $custom_field_headers; ?>,
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5, 6], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5, 6], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 7, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    };

    $(document).ready(function () {
        $("#monthly-purchase_order-button").trigger("click");
        loadPurchaseTable("#monthly-purchase_order-table", "monthly");
    });

</script>