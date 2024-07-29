<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
        <div class="page-title clearfix">
            <h1><?php echo lang('clients'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("clients_register/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_client'), array("class" => "btn btn-default", "title" => lang('add_client'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="client-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var showInvoiceInfo = <?php echo isset($show_invoice_info) ? json_encode($show_invoice_info) : 'false'; ?>;
        var customFieldHeaders = <?php echo isset($custom_field_headers) ? json_encode($custom_field_headers) : '[]'; ?>;

        $("#client-table").appTable({
            source: '<?php echo_uri("clients_register/list_data") ?>',
            filterDropdown: [
                {name: "group_id", class: "w200", options: <?php echo $groups_dropdown; ?>}
            ],
            columns: [
                {title: "<?php echo lang("id") ?>", "class": "text-center w50"},
                {title: "<?php echo lang("client_name") ?>"},
                {title: "<?php echo lang("primary_contact") ?>"},
                {title: "<?php echo lang("client_groups") ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], customFieldHeaders),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], customFieldHeaders)
        });
    });
</script>

