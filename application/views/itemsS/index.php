<?php /*
<div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('items_product'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="item-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-table").appTable({
            source: '<?php echo_uri("items/list_data") ?>',
            
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('product_id') ?> ", "class": "w15p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('category') ?> "},
             {title: "<?php echo lang('make') ?> "},
             {title: "<?php echo lang('hsn_code') ?> "},
             {title: "<?php echo lang('stock') ?>" },
             
             {title: "<?php echo lang('unit_type') ?>", "class": "w15"},

                {title: "<?php echo lang('rate') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('gst') ?>", "class": "text-right w5p"},
                 {title: "<?php echo lang('stock_total') ?>", "class": "text-right w10p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5,6,7,8,9,10],
            xlsColumns: [0, 1, 2, 3,4,5,6,7,8,9,10],
            summation: [{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    });
</script>
*/?>
<div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('items_product'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="item-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-table").appTable({
            source: '<?php echo_uri("items/list_data") ?>',
            
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('product_id') ?> ", "class": "w15p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('category') ?> "},
             {title: "<?php echo lang('make') ?> "},
             {title: "<?php echo lang('hsn_code') ?> "},
             {title: "<?php echo lang('stock') ?>" },
             
             {title: "<?php echo lang('unit_type') ?>", "class": "w15"},

                {title: "<?php echo lang('rate') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('profit') ?>", "class": "text-right w5p"},
                {title: "<?php echo lang('actual_value') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('gst') ?>", "class": "text-right w5p"},

                 {title: "<?php echo lang('mrp') ?>", "class": "text-right w10p"},
                  {title: "<?php echo lang('stock_total') ?>", "class": "text-right w10p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5,6,7,8,9,10],
            xlsColumns: [0, 1, 2, 3,4,5,6,7,8,9,10],
            summation: [{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 11, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 12, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    });
</script>