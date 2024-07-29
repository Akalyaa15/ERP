<?php if (isset($page_type) && $page_type === "full") { ?>
    <div id="page-content" class="m20 clearfix">
    <?php } ?>

    <div class="panel">
        <?php if (isset($page_type) && $page_type === "full") { ?>
            <div class="page-title clearfix">
                <h1><?php echo lang('work_order_payments'); ?></h1>
            </div>
        <?php } else { ?>
            <div class="tab-title clearfix">
                <h4><?php echo lang('work_order_payments'); ?></h4>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table id="work_order-payment-table" class="display" width="100%">
            </table>
        </div>
    </div>
    <?php if (isset($page_type) && $page_type === "full") { ?>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        var currencySymbol = "<?php echo $vendor_info->currency_symbol; ?>";
        $("#work_order-payment-table").appTable({
            source: '<?php echo_uri("work_order_payments/payment_list_data_of_vendor/" . $vendor_id) ?>',
            order: [[1, "desc"]],
            columns: [
                {title: '<?php echo lang("work_order_no") ?> ', "class": "w10p"},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p",  "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo lang("reference_number") ?>', "class": "w15p"},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {title: '<?php echo lang("description") ?>',"class": "text-center w25p"},
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            summation: [{column: 5, dataType: 'currency', currencySymbol: currencySymbol}]
        });

    });
</script>