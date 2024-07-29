<?php if (isset($page_type) && $page_type === "full") { ?>
    <div id="page-content" class="m20 clearfix">
    <?php } ?>

    <div class="panel">
        <?php if (isset($page_type) && $page_type === "full") { ?>
            <div class="page-title clearfix">
                <h1><?php echo lang('clients_wo_list'); ?></h1>
                <div class="title-button-group">
                    <?php

                 
                    echo modal_anchor(get_uri("clients_wo_list/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_client_wo'), array("class" => "btn btn-default mb0","data-post-client_id" => $client_id, "title" => lang('add_client_wo'))); ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="tab-title clearfix">
                <h4><?php echo lang('clients_wo_list'); ?></h4>

                <div class="title-button-group">
                    <?php

                 
                    echo modal_anchor(get_uri("clients_wo_list/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_client_wo'), array("class" => "btn btn-default mb0","data-post-client_id" => $client_id, "title" => lang('add_client_wo'))); ?>
                </div>
           
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table id="monthly-clients_wo_list-table" class="display" width="100%">
            </table>
        </div>
    </div>
    <?php if (isset($page_type) && $page_type === "full") { ?>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $client_info->currency_symbol; ?>";
        $("#monthly-clients_wo_list-table").appTable({
            source: '<?php echo_uri("clients_wo_list/vendors_invoice_list_data_of_vendor/".$client_id) ?>',
            order: [[0, 'desc']],
         /* filterDropdown: [
                
                {name: "status_id", class: "w200", options: <?php echo $status_dropdown; ?>}
                
            ], */
            filterDropdown: [{name: "status_id", class: "w150", options: <?php $this->load->view("clients_wo_list/vendors_invoice_list_dropdown"); ?>}], 
            columns: [
                
                //{title: '<?php echo lang("created_date"); ?>', "class": "w200"},
                //{title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("wo_no") ?>',"class": "w10p"},
                {title: '<?php echo lang("wo_date"); ?>', "class": "w10p"},
                {title: '<?php echo lang("clients"); ?>',"class": "w10p"},
                {title: '<?php echo lang("proforma_no"); ?>'},
                {title: '<?php echo lang("amount") ?>',  "class": "text-right w250p"},
                {title: '<?php echo lang("igst_tax") ?>',  "class": "text-right w250p"},
                
                {title: '<?php echo lang("total") ?>',  "class": "text-right w250p"},
                 {title: '<?php echo lang("paid_amount") ?>',  "class": "text-right w250p"},
                 {title: '<?php echo lang("due") ?>',  "class": "text-right w250p"},
                {title: '<?php echo lang("files") ?>', "class": "w20"},
                {title: '<?php echo lang("status") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4,5,6,7,8],
            xlsColumns: [0, 1, 2, 3, 4,5,6,7,8],
           summation: [{column: 4, dataType: 'currency', currencySymbol: currencySymbol},{column: 5, dataType: 'currency', currencySymbol: currencySymbol},{column: 6, dataType: 'currency', currencySymbol: currencySymbol},{column: 7, dataType: 'currency', currencySymbol: currencySymbol},{column: 8, dataType: 'currency', currencySymbol: currencySymbol}],
        });
    });
</script>