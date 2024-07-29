<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">

        <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10"></h4></li>
            <li><a role="presentation" class="active" href="javascript:;" data-target="#work_orders-tab"><?php echo lang("work_orders"); ?></a></li>
            <?php /*if (isset($can_request_estimate) && $can_request_estimate) { ?>
                <li><a role="presentation" href="<?php echo_uri("estimate_requests/estimate_requests_for_client/".$client_id); ?>" data-target="#esimate-requests-tab"><?php echo lang('estimate_requests'); ?></a></li>
                <div class="tab-title clearfix no-border">

                        <div class="title-button-group">
                            <?php echo modal_anchor(get_uri("estimate_requests/request_an_estimate_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('request_an_estimate'), array("class" => "btn btn-default", "title" => lang('request_an_estimate'))); ?>           
                        </div>

                </div>
             <?php } */ ?>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="work_orders-tab">
                <div class="table-responsive">
                    <table id="work_order-table" class="display" width="100%">
                    </table>
                </div>
            </div>
            <!--div role="tabpanel" class="tab-pane fade" id="esimate-requests-tab"></div-->
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $vendor_info->currency_symbol; ?>";
        $("#work_order-table").appTable({
            source: '<?php echo_uri("work_orders/work_order_list_data_of_vendor/" . $vendor_id) ?>',
            order: [[0, "desc"]],
            filterDropdown: [{name: "status", class: "w150", options: <?php $this->load->view("work_orders/work_order_statuses_dropdown"); ?>}],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang("work_order_no") ?>", "class": "w25p"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: "<?php echo lang("work_order_date") ?>", "iDataSort": 2},
                {visible: false, searchable: false},
                {title: "<?php echo lang("due_date") ?>", "class": "w10p", "iDataSort": 4},
                {title: "<?php echo lang("work_order_value") ?>", "class": "text-right"},
                {title: "<?php echo lang("payment_received") ?>", "class": "text-right"},
                {title: "<?php echo lang("due") ?>", "class": "text-right"},
                {title: "<?php echo lang("status") ?>", "class": "text-center"}
                <?php echo $custom_field_headers; ?>,
                {visible: false}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6,7, 8],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            summation: [{column: 7, dataType: 'currency', currencySymbol: currencySymbol},{column: 8, dataType: 'currency', currencySymbol: currencySymbol},{column: 9, dataType: 'currency', currencySymbol: currencySymbol}]
        });
    });
</script>