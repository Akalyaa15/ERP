
<div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
 <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("bank_statement"); ?></h4></li>
            <li><a id="monthly-monthly-bankstatement-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-bankstatement"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("excel_import/yearly/"); ?>" data-target="#yearly-bankstatement"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("excel_import/custom/"); ?>" data-target="#custom-bankstatement"><?php echo lang('custom'); ?></a></li>
            
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                   <div class="title-button-group">
                <?php  echo modal_anchor(get_uri("excel_import/excel_import_form"), "<i class='fa fa-upload' aria-hidden='true'></i> " . lang('add_bank_statement'), array("class" => "btn btn-default", "title" => lang('add_bank_statement')));  ?>
            </div> 
                
                </div>
            </div>
        </ul>
        
       <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-bankstatement">
        <div class="table-responsive">
            <table id="import_excel-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="yearly-bankstatement"></div>
    <div role="tabpanel" class="tab-pane fade" id="custom-bankstatement"></div>
</div>

<script type="text/javascript">
    loadImportExcelTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        } 

       $(selector).appTable({
            source: '<?php echo_uri("Excel_import/list_data") ?>',
           dateRangeType: dateRange,
           filterDropdown: [
                {name: "BankName", class: "w200", options: <?php echo $bank_dropdown; ?>}
                 
            ],
            order: [[0, 'asc']],
            rangeDatepicker: customDatePicker,
            displayLength: 100,
            columns: [
            {title: "<?php echo lang('Bank_name') ?> ", "class": "w15p"},
             {title: "<?php echo lang('account_number') ?> ", "class": "w15p"},
            {title: "<?php echo lang('value_date') ?> ", "class": "w15p"},
            {title: "<?php echo lang('post_date') ?>", "class": "w15p"},
                {title: "<?php echo lang('remitter_branch') ?> ","class": "w15p"},
             {title: "<?php echo lang('description') ?> ", "class": "w40p"},
             {title: "<?php echo lang('cheque_number') ?> ", "class": "w15p"},
             {title: "<?php echo lang('transaction_id') ?> ", "class": "w10p"},
             
             {title: "<?php echo lang('debit_amount') ?>", "class": "w15p"},
                {title: "<?php echo lang('credit_amount') ?>", "class": "w100"},
                {title: "<?php echo lang('account_balance') ?>", "class": "w15p"},
                {title: "<?php echo lang('remark') ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8,9,10],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8,9,10],

            summation: [{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 9, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 10, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
         
        });
    };
 /*$("#select_bank").select2();
 $("#bank_id.select2").select2();*/
    $(document).ready(function () {
   $("#monthly-bankstatement-button").trigger("click");
        loadImportExcelTable("#import_excel-table","monthly");
    });
</script>
