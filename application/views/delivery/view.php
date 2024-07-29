<?php 
$options = array("estimate_id" => $estimate_info->id);
$list_data = $this->Delivery_items_model->get_details($options)->result();
$sold_details = $this->Delivery_items_model->get_sold_details($options)->result();
$ret_sold_details = $this->Delivery_items_model->get_ret_sold_details($options)->result();
?> 
<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo /*get_delivery_id($estimate_info->id)*/$estimate_info->dc_no?$estimate_info->dc_no:get_delivery_id($estimate_info->id); ?></h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button> 
                 
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><?php echo anchor(get_uri("delivery/download_pdf/" . $estimate_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("delivery/preview/" . $estimate_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('delivery_preview'), array("title" => lang('delivery_preview')), array("target" => "_blank")); ?> </li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><?php echo modal_anchor(get_uri("delivery/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_delivery'), array("title" => lang('edit_delivery'), "data-post-id" => $estimate_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
<?php  
    if ($this->login_user->is_admin ||in_array($this->login_user->id,$delivery_access)||$delivery_access_all=="all" ) { ?>
                        <?php
                        if ($list_data&&($estimate_status == "draft"||$estimate_status == "modified")) {
                            ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/given"), "<i class='fa fa-hand-lizard-o'></i> " . lang('mark_as_given'), array("data-reload-on-success" => "1")); ?> </li>
                            <!--li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/received"), "<i class='fa fa-send'></i> " . lang('mark_as_received'), array("data-reload-on-success" => "1")); ?> </li-->
                        <?php } else if ($estimate_status == "ret_sold"&&$ret_sold_details) { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/approve_ret_sold"), "<i class='fa fa-check-circle'></i> " . lang('save_ret_sold'), array("data-reload-on-success" => "1")); ?> </li>
                            <?php } else if ($estimate_status == "approve_ret_sold"&&$sold_details&&!$estimate_info->invoice_for_dc) { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("invoices/invoice_modal_form/" . $estimate_info->id), "<i class='fa fa-refresh'></i> " . lang('create_invoice'), array("data-reload-on-success" => "1")); ?> </li>
                            <?php } else if (($estimate_status == "invoice_created"||$estimate_status == "approve_ret_sold")&&$sold_details&&$estimate_info->invoice_for_dc) { ?>
                            <li role="presentation"><?php echo anchor(get_uri("invoices/view/" . $estimate_info->invoice_for_dc), "<i class='fa fa-eye'></i> " . lang('view_inv'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } else if ($estimate_status == "given") { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/received"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_received'), array("data-reload-on-success" => "1")); ?> </li>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/modified"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_modified'), array("data-reload-on-success" => "1")); ?> </li>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/sold"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_sold'), array("data-reload-on-success" => "1")); ?> </li>
                          <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/ret_sold"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_ret_sold'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } else if ($estimate_status == "received"||$estimate_status == "sold") { ?>
                            <!--li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/given"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_given'), array("data-reload-on-success" => "1")); ?> </li-->
                        <?php } ?>
 <?php } ?>
                     
                    </ul>
                </span>
                <?php echo modal_anchor(get_uri("delivery/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'),"id"=>"add_item", "data-post-estimate_id" => $estimate_info->id)); ?>
            </div>
        </div>
        <div id="estimate-status-bar">
            <?php $this->load->view("delivery/delivery_status_bar"); ?>
        </div>
        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("delivery_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $style = get_setting("delivery_style");
                    ?>
                    <?php
                    $data = array(
                        "client_info" => $client_info,
                        "color" => $color,
                        "estimate_info" => $estimate_info
                    );
                    if ($style === "style_2") {
                        $this->load->view('delivery/delivery_parts/header_style_2.php', $data);
                    } else {
                        $this->load->view('delivery/delivery_parts/header_style_1.php', $data);
                    }
                    ?>

                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="estimate-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="pull-right pr15" id="estimate-total-section" style="width: 420px;">
                        
                    </div>
                </div>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($estimate_info->note); ?></p>

            </div>
        </div>

    </div>
</div>



<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#estimate-item-table").appTable({
            source: '<?php echo_uri("delivery/item_list_data/" . $estimate_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            <?php if ($estimate_status == "draft") { ?>
            columns: [
                {title: "<?php echo lang("model") ?> ", "class": "text-center w25p"},
                {title: "<?php echo lang("category") ?>", "class": "text-center w15p"},
                {title: "<?php echo lang("make") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("quantity") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("rate") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("total") ?>", "class": "text-center w15p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            <?php }else if ($estimate_status == "ret_sold") { ?>
            columns: [ 
                {title: "<?php echo lang("model") ?> ", "class": "text-center w25p"},
                {title: "<?php echo lang("category") ?>", "class": "text-center w15p"},
                {title: "<?php echo lang("make") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("quantity") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("rate") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("total") ?>", "class": "text-center w45p"},
                 {title: "<?php echo lang("sold") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("returned") ?>", "class": "text-center w45p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            <?php }else if ($estimate_status == "approve_ret_sold") { ?>
            columns: [
                {title: "<?php echo lang("model") ?> ", "class": "text-center w25p"},
                {title: "<?php echo lang("category") ?>", "class": "text-center w15p"},
                {title: "<?php echo lang("make") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("quantity") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("rate") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("total") ?>", "class": "text-center w45p"},
               {title: "<?php echo lang("sold") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("returned") ?>", "class": "text-center w45p"},
            ],
            <?php }else if ($estimate_status == "modified") { ?>
            columns: [
                {title: "<?php echo lang("model") ?> ", "class": "text-center w25p"},
                {title: "<?php echo lang("category") ?>", "class": "text-center w15p"},
                {title: "<?php echo lang("make") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("quantity") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("rate") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("total") ?>", "class": "text-center w15p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            <?php }else{ ?>
            columns: [
                {title: "<?php echo lang("model") ?> ", "class": "text-center w25p"},
                {title: "<?php echo lang("category") ?>", "class": "text-center w15p"},
                {title: "<?php echo lang("make") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("quantity") ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("rate") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("total") ?>", "class": "text-center w45p"}
                
            ],
            <?php } ?>
            onDeleteSuccess: function (result) {
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }
                                location.reload();

            },
            onUndoSuccess: function (result) {
                $("#estimate-total-section").html(result.estimate_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.estimate_id);
                }

            }
        });
    });

    updateInvoiceStatusBar = function (estimateId) {
        $.ajax({
            url: "<?php echo get_uri("delivery/get_delivery_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
                                                location.reload();

            }
        });
    };

</script>

<script type="text/javascript">
  $( document ).ready(function() {
   event.preventDefault();
  <?php if ($estimate_status == "received"||$estimate_status == "given"||$estimate_status == "sold"||$estimate_status == "ret_sold"||$estimate_status == "approve_ret_sold"||$estimate_status == "invoice_created") { ?>
   $('#add_item').prop("disabled", true); // Element(s) are now enabled.
   $('#add_item').attr("disabled", true); 
   $("#add_item").attr('title', 'Already Delivery challan has been created');
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
