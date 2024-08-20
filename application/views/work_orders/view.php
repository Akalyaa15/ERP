<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo /*get_work_order_id($work_order_info->id)*/$work_order_info->work_no?$work_order_info->work_no:get_work_order_id($work_order_info->id); ?></h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation">
<?php 
$work_options = array("work_order_id" => $work_order_info->id);
$work_list_data = $this->Work_order_items_model->get_details($work_options)->result();
?>
<?php 
$DB11 = $this->load->database('default', TRUE);
$DB11->select ("hsn_code,hsn_description,gst");
 $DB11->from('work_order_items');
 $DB11->where('work_order_items.work_order_id',$work_order_info->id);
  $DB11->where('work_order_items.gst!=','0');
 $DB11->where('work_order_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $work_order_total_summary->freight_tax) { ?>
                        <?php echo anchor(get_uri("work_orders/download_pdf/" . $work_order_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?>
<?php } else {  ?>

<?php echo anchor(get_uri("work_orders/download_work_order_without_gst_pdf/" . $work_order_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("download_pdf" => lang('print'))); ?>
<?php } ?>
                         </li>
                        <li role="presentation"><?php echo anchor(get_uri("work_orders/preview/" . $work_order_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('work_order_preview'), array("title" => lang('work_order_preview')), array("target" => "_blank")); ?> </li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><?php echo modal_anchor(get_uri("work_orders/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_work_order'), array("title" => lang('edit_work_order'), "data-post-id" => $work_order_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>

                        
                     <?php if ($work_list_data && $work_order_status == "draft") { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("work_orders/set_work_order_status_to_not_paid/" . $work_order_info->id), "<i class='fa fa-check'></i> " . lang('mark_work_order_as_not_paid'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } ?>
                    </ul>
                </span>
                <?php echo modal_anchor(get_uri("work_orders/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_outsource_job'), array("class" => "btn btn-default", "title" => lang('add_outsource_job'), "data-post-work_order_id" => $work_order_info->id)); ?>
                 <?php echo modal_anchor(get_uri("work_order_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'), "data-post-work_order_id" => $work_order_info->id)); ?>
            </div>
        </div>
        <div id="work_order-status-bar">
            <?php $this->load->view("work_orders/work_order_status_bar"); ?>
        </div>
        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("work_order_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $style = get_setting("work_order_style");
                    ?>
                    <?php
                    $data = array(
                        "vendor_info" => $vendor_info,
                        "color" => $color,
                        "work_order_info" => $work_order_info
                    );
                    if ($style === "style_2") {
                        $this->load->view('work_orders/work_order_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('work_orders/work_order_parts/header_style_1.php', $data);
                    }
                    ?>

                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="work_order-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="pull-right pr15" id="work_order-total-section" style="width: 420px;">
                        <?php $this->load->view("work_orders/work_order_total_section"); ?>
                    </div>
                </div>

 <!--p class="b-t b-info pt10 m15"><?php echo isset($estimate_info->note) ? nl2br($estimate_info->note) : ''; ?></p-->

            </div>
        </div>

         <div class="panel panel-default">
                <div class="tab-title clearfix">
                    <h4> <?php echo lang('work_order_payment_list'); ?></h4>
                </div>
                <div class="table-responsive">
                    <table id="work_order-payment-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>

    </div>
</div>
<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#work_order-item-table").appTable({
            source: '<?php echo_uri("work_orders/item_list_data/" . $work_order_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            columns: [

                {title: '<?php echo lang("job_id") ?> ', "bSortable": false},
                {title: '<?php echo lang("category") ?>', "class": "text-right w15p", "bSortable": false},
                //{title: '<?php echo lang("make") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("hsn_code") ?>', "class": "text-right w10p", "bSortable": false},
                 {title: '<?php echo lang("gst") ?>', "class": "text-right w10p", "bSortable": false},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w10p", "bSortable": false},
                {title: '<?php echo lang("rate") ?>', "class": "text-right w10p", "bSortable": false},
                 {title: '<?php echo lang("total") ?>', "class": "text-right w15p", "bSortable": false},
              //  {title: '<?php echo lang("total") ?>', "class": "text-right w15p", "bSortable": false},
               
               {title: '<?php echo lang("tax_amount") ?>', "class": "text-right w15p", "bSortable": false},
              {title: '<?php echo lang("discount_percent") ?>', "class": "text-center w10p", "bSortable": false},
               {title: '<?php echo lang("net_total") ?>', "class": "text-right w15p", "bSortable": false},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}

            ],
            onDeleteSuccess: function (result) {
                $("#work_order-total-section").html(result.work_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.work_order_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#work_order-total-section").html(result.work_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.work_order_id);
                }
            }
        });
    });
$("#work_order-payment-table").appTable({
            source: '<?php echo_uri("work_order_payments/payment_list_data/" . $work_order_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                //{title: '<?php echo lang("note") ?>'},
                {title: '<?php echo lang("reference_number") ?>', "class": "w15p"},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {title: '<?php echo lang("files") ?>', "class": "w10p"},
                {title: '<?php echo lang("description") ?>', "class": "text-center w25p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onDeleteSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#work_order-total-section").html(result.work_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.work_order_id);
                }
            },
            onUndoSuccess: function (result) {
                updateInvoiceStatusBar();
                $("#work_order-total-section").html(result.work_order_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.work_order_id);
                }
            }
        });
  updateInvoiceStatusBar = function (work_orderId) {
        $.ajax({
            url: "<?php echo get_uri("work_orders/get_work_order_status_bar"); ?>/" + work_orderId,
            success: function (result) {
                if (result) {
                    $("#work_order-status-bar").html(result);
                }
            }
        });
    };
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
