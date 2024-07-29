<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("vendors_invoice_list"); ?></h4></li>
            <li><a id="monthly-vendors_invoice_list-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-vendors_invoice_list"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vendors_invoice_list/yearly/"); ?>" data-target="#yearly-vendors_invoice_list"><?php echo lang('yearly'); ?></a></li>
            
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php

                 
                    echo modal_anchor(get_uri("vendors_invoice_list/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vendor_invoice'), array("class" => "btn btn-default mb0", "title" => lang('add_vendor_invoice'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-vendors_invoice_list">
                <div class="table-responsive">
                    <table id="monthly-vendors_invoice_list-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
                </div>
                 <div role="tabpanel" class="tab-pane fade" id="yearly-vendors_invoice_list"></div>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    loadVendorsInvoiceListTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

       $(selector).appTable({
            source: '<?php echo_uri("vendors_invoice_list/list_data") ?>',
            dateRangeType: dateRange,
           /* filterDropdown: [
                
                {name: "user_id", class: "w200", options: <?php echo $members_dropdown; ?>}
                
            ], */
            order: [[0, "asc"]],
            rangeDatepicker: customDatePicker,
            filterDropdown: [
                
                {name: "status_id", class: "w200", options: <?php echo $status_dropdown; ?>}
                
            ],
            columns: [
                
                //{title: '<?php echo lang("created_date"); ?>', "class": "w200"},
                //{title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("invoice_no") ?>', "class": "w250"},
                {title: '<?php echo lang("invoice_date"); ?>', "class": "w250"},
                {title: '<?php echo lang("vendors"); ?>'},
                {title: '<?php echo lang("amount") ?>',  "class": "w10p text-right"},
                {title: '<?php echo lang("igst_tax") ?>',  "class": "w10p text-right"},
                {title: '<?php echo lang("cgst_tax") ?>',  "class": "w10p text-right"},
                {title: '<?php echo lang("sgst_tax") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("total") ?>',  "class": "w10p text-right"},
                 {title: '<?php echo lang("paid_amount") ?>',  "class": "w10p text-right"},
                 {title: '<?php echo lang("due") ?>',  "class": "w10p text-right"},
                 {title: '<?php echo lang("files") ?>', "class": "w250"},
                 {title: '<?php echo lang("status") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 5],
            xlsColumns: [1, 2, 3, 4, 5],
           summation: [{column: 3, dataType: 'currency'},{column: 4, dataType: 'currency'},{column: 5, dataType: 'currency'},{column: 6, dataType: 'currency'},{column: 7, dataType: 'currency'},{column: 7, dataType: 'currency'},{column: 8, dataType: 'currency'},{column: 9, dataType: 'currency'}]
         
        });
    };

    $(document).ready(function () {
        $("#monthly-vendors_invoice_list-button").trigger("click");
        loadVendorsInvoiceListTable("#monthly-vendors_invoice_list-table", "monthly");
    });
</script>
<?php $this->load->view("vendors_invoice_list/update_task_script"); ?>
<?php $this->load->view("vendors_invoice_list/update_task_read_comments_status_script"); ?>


<!--div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('vendors_invoice_list') ; ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("vendors_invoice_list/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vendor_invoice'), array("class" => "btn btn-default", "title" => lang('add_vendor_invoice'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="vendors_invoice_list-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div-->
<?php /* 

    //prepare status dropdown list
    //select the non completed tasks for team members by default
    //show all tasks for client by default.

    $statuses = array(); 
    foreach($task_statuses as $status){
        $is_selected = false;
        if($this->login_user->user_type=="staff"){
            if($status->key_name !="fully_paid"){
                $is_selected = true;
            }
        }
        
        $statuses[] = array("text"=> ($status->key_name? lang($status->key_name): $status->title), "value"=>$status->id, "isChecked"=>$is_selected);
    }
   
*/?>
<!--script type="text/javascript">
    $(document).ready(function () {
        $("#vendors_invoice_list-table").appTable({
            source: '<?php echo_uri("vendors_invoice_list/list_data") ?>',
            order: [[0, 'desc']],
         /*  multiSelect: [
                    {
                        name: "status_idss",
                        text: "<?php echo lang('status');?>", 
                        options: <?php echo json_encode($statuses); ?>
                    }
                ],  */
                filterDropdown: [
                
                {name: "status_id", class: "w200", options: <?php echo $status_dropdown; ?>}
                
            ],
            columns: [
                
                //{title: '<?php echo lang("created_date"); ?>', "class": "w200"},
                //{title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("invoice_no") ?>', "class": "w250"},
                {title: '<?php echo lang("invoice_date"); ?>', "class": "w200"},
                {title: '<?php echo lang("vendors"); ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "w250"},
                {title: '<?php echo lang("igst_tax") ?>', "class": "w250"},
                {title: '<?php echo lang("cgst_tax") ?>', "class": "w250"},
                {title: '<?php echo lang("sgst_tax") ?>', "class": "w250"},
                {title: '<?php echo lang("total") ?>', "class": "w250"},
                 {title: '<?php echo lang("paid_amount") ?>', "class": "w250"},
                 {title: '<?php echo lang("due") ?>', "class": "w250"},
                 {title: '<?php echo lang("files") ?>', "class": "w250"},
                 {title: '<?php echo lang("status") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script-->

