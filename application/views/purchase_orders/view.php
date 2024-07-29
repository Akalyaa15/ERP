<?php 
$company_state = get_setting("company_state");
$options = array("id" => $company_state);
$company_state_data = $this->States_model->get_details($options)->row();
?>

<table>
    <tr style="border: 1px solid #666; text-align: left;padding: 5px;">
        <td colspan="2" style="border: 1px solid #dddddd;color: #666;font-size:14px;text-align: left;padding: 5px;height:140px;">
            <?php
            $company_address = nl2br(get_setting("company_address"));
            $company_phone = get_setting("company_phone");
            $company_website = get_setting("company_website");
            $company_gst_number = get_setting("company_gst_number");
            $company_gstin_number_first_two_digits = get_setting("company_gstin_number_first_two_digits");
            ?>
            <div style="font-weight: bold;color:black;"><strong><?php echo get_setting("company_name"); ?></strong></div>
            <div style="line-height: 3px;"> </div>
            <span class="invoice-meta" style="font-size: 90%; color: #666;">
                <?php
                if ($company_address) {
                    echo $company_address;
                }
                ?>
                <?php if ($company_phone) { ?>
                    <br /><?php echo lang("phone") . ": " . $company_phone; ?>
                <?php } ?>
                <?php if ($company_website) { ?>
                    <br /><?php echo lang("website"); ?>: <a style="color:#666; text-decoration: none;" href="<?php echo $company_website; ?>"><?php echo $company_website; ?></a>
                <?php } ?>
                <?php if ($company_gst_number) { ?>
                    <br /><?php echo lang("gst_number"). ": ". $company_gst_number.","; ?>
                <?php } ?>

                <?php if (isset($invoice_info) && $company_state) { ?>
                    <?php echo lang("state"). ": ". $company_state_data->title . "," . lang("code") . ": " . $company_gstin_number_first_two_digits; ?>
                <?php } ?>
            </span>
        </td>
    </tr>
</table>

<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo $purchase_order_info->purchase_no ? $purchase_order_info->purchase_no : get_purchase_order_id($purchase_order_info->id); ?></h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation">
                            <?php 
                            $purchase_options = array("purchase_order_id" => $purchase_order_info->id);
                            $purchase_list_data = $this->Purchase_order_items_model->get_details($purchase_options)->result();

                            $options = array("purchase_order_id" => $purchase_order_info->id);
                            $list_data = $this->Vendors_invoice_list_model->get_details($options)->result();

                            $DB10 = $this->load->database('default', TRUE);
                            $DB10->select("hsn_code, hsn_description, gst");
                            $DB10->from('purchase_order_items');
                            $DB10->where('purchase_order_items.purchase_order_id', $purchase_order_info->id);
                            $DB10->where('purchase_order_items.gst !=', '0');
                            $DB10->where('purchase_order_items.deleted', '0');

                            $queryhsn = $DB10->get();
                            $hsngst = $queryhsn->result();
                            $hsn_size = sizeof($hsngst);
                            if ($hsn_size > 0 || $purchase_order_total_summary->freight_tax_amount) { ?>
                                <?php echo anchor(get_uri("purchase_orders/download_pdf/" . $purchase_order_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> 
                            <?php } else { ?> 
                                <?php echo anchor(get_uri("purchase_orders/download_purchase_order_without_gst_pdf/" . $purchase_order_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?>
                            <?php } ?> 
                        </li>
                        <li role="presentation"><?php echo anchor(get_uri("purchase_orders/preview/" . $purchase_order_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('purchase_order_preview'), array("title" => lang('purchase_order_preview'), "target" => "_blank")); ?> </li>
                        <li role="presentation" class="divider"></li>
                        <?php
                        $optionss = array("id" => $purchase_order_info->id);
                        $modifed_data = $this->Purchase_orders_model->get_details($optionss)->row();
                        if ($modifed_data->modified == '1') { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_purchase_order'), array("title" => lang('edit_purchase_order'), "data-post-id" => $purchase_order_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>
                        <?php 
                        if (!$list_data) { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_purchase_order'), array("title" => lang('edit_purchase_order'), "data-post-id" => $purchase_order_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                            <?php if ($purchase_list_data && $purchase_order_status == "draft") { ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("purchase_orders/set_purchase_order_status_to_not_paid/" . $purchase_order_info->id), "<i class='fa fa-check'></i> " . lang('mark_purchase_order_as_not_paid'), array("data-reload-on-success" => "1")); ?> </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($list_data && $modifed_data->modified == '0') { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("purchase_orders/set_purchase_order_status_to_modified/" . $purchase_order_info->id), "<i class='fa fa-check'></i> " . lang('purchase_order_modified'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } else if ($list_data && $modifed_data->modified == '1') { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("purchase_orders/set_purchase_order_status_to_not_modified/" . $purchase_order_info->id), "<i class='fa fa-check'></i> " . lang('purchase_order_not_modified'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } ?> 
                    </ul>
                </span>
                <?php echo modal_anchor(get_uri("purchase_orders/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'), "id" => "add_item", "data-post-purchase_order_id" => $purchase_order_info->id)); ?>
                <?php echo modal_anchor(get_uri("purchase_order_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'), "id" => "add_payment", "data-post-purchase_order_id" => $purchase_order_info->id)); ?>
            </div>
        </div>
        <div id="purchase_order-status-bar">
            <?php $this->load->view("purchase_orders/purchase_order_status_bar"); ?>
        </div>
        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("purchase_order_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
$style = get_setting("purchase_order_style");
?>
<?php
$data = array(
    "vendor_info" => $vendor_info,
    "color" => $color,
    "purchase_order_info" => $purchase_order_info
);
if ($style === "style_2") {
    $this->load->view('purchase_orders/purchase_order_parts/header_style_2.php', $data);
} else {
    $this->load->view('purchase_orders/purchase_order_parts/header_style_1.php', $data);
}
?>

</div>

<div class="table-responsive mt15 pl15 pr15">
<table id="purchase_order-item-table" class="display" width="100%">            
</table>
</div>

<div class="clearfix">
<div class="col-sm-8">

</div>
<div class="pull-right pr15" id="purchase_order-total-section" style="width: 420px;">
<?php $this->load->view("purchase_orders/purchase_order_total_section"); ?>
</div>
</div>
</div>
</div>
<div class="panel panel-default">
<div class="tab-title clearfix">
<h4> <?php echo lang('purchase_order_payment_list'); ?></h4>
</div>
<div class="table-responsive">
<table id="purchase_order-payment-table" class="display" cellspacing="0" width="100%">            
</table>
</div>
</div

</div>
</div>
<script type="text/javascript">
        RELOAD_VIEW_AFTER_UPDATE = true;
        $(document).ready(function () {
            $("#purchase_order-item-table").appTable({
                source: '<?php echo_uri("purchase_orders/item_list_data/" . $purchase_order_info->id . "/") ?>',
                order: [[0, "asc"]],
                hideTools: true,
                columns: [
    
                    {title: '<?php echo lang("model") ?> ', "bSortable": false},
                    {title: '<?php echo lang("category") ?>', "class": "text-right w15p", "bSortable": false},
                    {title: '<?php echo lang("make") ?>', "class": "text-right w15p", "bSortable": false},
                    {title: '<?php echo lang("hsn_code") ?>', "class": "text-right w10p", "bSortable": false},
                    {title: '<?php echo lang("quantity") ?>', "class": "text-right w10p", "bSortable": false},
                    {title: '<?php echo lang("rate") ?>', "class": "text-right w10p", "bSortable": false},
    
                    {title: '<?php echo lang("total") ?>', "class": "text-right w15p", "bSortable": false},
                    {title: '<?php echo lang("gst") ?>', "
                        class": "text-right w10p", "bSortable": false},
                    {title: '<?php echo lang("tax_amount") ?>', "class": "text-right w15p", "bSortable": false},
                    {title: '<?php echo lang("discount_percent") ?>', "class": "text-center w10p", "bSortable": false},
                    {title: '<?php echo lang("net_total") ?>', "class": "text-right w15p", "bSortable": false},
                    {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
    
                ],
                onDeleteSuccess: function (result) {
                    $("#purchase_order-total-section").html(result.purchase_order_total_view);
                    if (typeof updateInvoiceStatusBar == 'function') {
                        updateInvoiceStatusBar(result.purchase_order_id);
                    }
                },
                onUndoSuccess: function (result) {
                    $("#purchase_order-total-section").html(result.purchase_order_total_view);
                    if (typeof updateInvoiceStatusBar == 'function') {
                        updateInvoiceStatusBar(result.purchase_order_id);
                    }
                }
            });
        });
    
        $("#purchase_order-payment-table").appTable({
            source: '<?php echo_uri("purchase_order_payments/payment_list_data/" . $purchase_order_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang
                ("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo lang("reference_number") ?>', "class": "w15p"},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {title: '<?php echo lang("files") ?>', "class": "w10p"},
                {title: '<?php echo lang("description") ?>', "class": "text-center w25p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.purchase_order_id);
                }
            },
            onUndoSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#purchase_order-total-section").html(result.purchase_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.purchase_order_id);
                }
            }
        });
    
        updateInvoiceStatusBar = function (purchase_orderId) {
            $.ajax({
                url: "<?php echo get_uri("purchase_orders/get_purchase_order_status_bar"); ?>/" + purchase_orderId,
                success: function (result) {
                    if (result) {
                        $("#purchase_order-status-bar").html(result);
                    }
                }
            });
        };
    
    </script>
    <script type="text/javascript">
        $( document ).ready(function() {
            event.preventDefault();
            <?php if ($list_data && $modifed_data->modified == '0') { ?>
                $('#add_item').prop("disabled", true);
                $('#add_payment').prop("disabled", true);
                $('#add_item').attr("disabled", true); 
                $('#add_payment').attr("disabled", true); 
                $("#add_item").attr('title', 'Generated vendor invoice  has been created');
                $("#add_payment").attr('title', 'Generated vendor invoice  has been created');
                // Element(s) are now enabled.
            <?php } ?>
        });
    </script>
    
    <?php
    //required to send email 
    
    load_css(array(
        "assets/js/summernote/summernote.css",
    ));
    load_js(array(
        "assets/js/summernote/summernote.min.js",
    ));

    ?>
       