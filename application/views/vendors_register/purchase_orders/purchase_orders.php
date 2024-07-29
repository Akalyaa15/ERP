<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('purchase_orders'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_purchase_order'), array("class" => "btn btn-default", "data-post-vendor_id" => $vendor_id, "title" => lang('add_purchase_order'))); ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="purchase_order-table" class="display" width="100%">
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $vendor_info->currency_symbol; ?>";
        $("#purchase_order-table").appTable({
            source: '<?php echo_uri("purchase_orders/purchase_order_list_data_of_vendor/" . $vendor_id) ?>',
            order: [[0, "desc"]],
            filterDropdown: [{name: "status", class: "w150", options: <?php $this->load->view("purchase_orders/purchase_order_statuses_dropdown"); ?>}],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang("purchase_order_no") ?>", "class": "w20p"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: "<?php echo lang("purchase_order_date") ?>", "iDataSort": 2, "class": "w10p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("due_date") ?>", "class": "w10p", "iDataSort": 5},
                {title: "<?php echo lang("purchase_order_value") ?>", "class": "text-right w20p"},
                {title: "<?php echo lang("payment_received") ?>", "class": "text-right w20p"},
                {title: "<?php echo lang("due") ?>", "class": "text-right w20p"},
                {title: "<?php echo lang("status") ?>", "class": "text-center w20p"}
                <?php echo $custom_field_headers; ?>,
                {visible: false}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
            summation: [{column: 7, dataType: 'currency', currencySymbol: currencySymbol},{column: 8, dataType: 'currency', currencySymbol: currencySymbol},{column: 9, dataType: 'currency', currencySymbol: currencySymbol}]
        });
    });
</script>