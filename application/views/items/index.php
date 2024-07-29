<div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
             <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
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
                       filterDropdown: [{name: "quantity", class: "w150", options: <?php $this->load->view("items/quantity_dropdown"); ?>}],

            order: [[0, 'desc']],
            columns: [
                //{title: "<?php echo lang('product_id') ?> ", "class": "w15p"},
                {title: "<?php echo lang('description_of_goods') ?> ","class": "w200"},
                /*{title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('category') ?> "},
             {title: "<?php echo lang('make') ?> "},*/
             
             {title: "<?php echo lang('stock') ?>" },
             
             {title: "<?php echo lang('unit_type') ?>", "class": "w15"},

                {title: "<?php echo lang('supply_rate') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('supply_stock_total') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('supply_profit') ?>", "class": "text-right w5p"},
                 {title: "<?php echo lang('supply_profit_value_per_quantity') ?>", "class": "text-right w5p"},
                {title: "<?php echo lang('supply_profit_value') ?>", "class": "text-right w5p"},
                {title: "<?php echo lang('supply_actual_value_per_quantity') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('supply_actual_value') ?>", "class": "text-right w10p"},
                {title: "<?php echo lang('hsn_code') ?> "},
               {title: "<?php echo lang('gst') ?>", "class": "text-right w5p"},
                {title: "<?php echo lang('supply_mrp') ?>", "class": "text-right w10p"},
                 // {title: "<?php echo lang('supply_stock_total') ?>", "class": "text-right w10p"},
                  {title: "<?php echo lang('installation_hsn_code') ?> "},
                {title: "<?php echo lang('installation_gst') ?>", "class": "text-right w5p"},
                  {title: "<?php echo lang('installation_rate') ?>", "class": "text-right w10p"},
                  {title: "<?php echo lang('installation_profit') ?>", "class": "text-right w5p"},
                {title: "<?php echo lang('installation_profit_value') ?>", "class": "text-right w5p"},
                {title: "<?php echo lang('installation_actual_value') ?>", "class": "text-right w10p"},
                 {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                 {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
                
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18],
            xlsColumns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18],
            summation: [{column: 3, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 4, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 6, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 7, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 12,dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 15, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 17, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 18, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    });
</script>