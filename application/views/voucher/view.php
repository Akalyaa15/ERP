<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo /*get_voucher_id($estimate_info->id)*/$estimate_info->voucher_no?$estimate_info->voucher_no:get_voucher_id($estimate_info->id); ?></h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button> 
                  <?php 
                  $options = array("estimate_id" => $estimate_info->id);
                  $list_data = $this->Voucher_expenses_model->get_details($options)->result();
                      ?>
                    <ul class="dropdown-menu" role="menu">
                    <?php if($estimate_status=="accepted"||$estimate_status=="paid"||$estimate_status=="approved_by_accounts"||$estimate_status=="payment_in_progress"||$estimate_status=="payment_hold"||$estimate_status=="payment_done"||$estimate_status=="payment_received"||$estimate_status=="closed") {?>
                        <li role="presentation"><?php echo anchor(get_uri("voucher/download_pdf/" . $estimate_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li> <?php } ?>
                        <li role="presentation"><?php echo anchor(get_uri("voucher/preview/" . $estimate_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('voucher_preview'), array("title" => lang('voucher_preview')), array("target" => "_blank")); ?> </li>
                        <li role="presentation" class="divider"></li>
                        <?php if($estimate_status=="modified"||$estimate_status=="draft") {?>
                        <li role="presentation"><?php echo modal_anchor(get_uri("voucher/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_voucher'), array("title" => lang('edit_voucher'), "data-post-id" => $estimate_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?><?php if($estimate_status=="accepted") {?>
                        <li role="presentation"><?php echo ajax_anchor(get_uri("voucher/update_voucher_status/" . $estimate_info->id . "/modified"), "<i class='fa fa-send'></i> " . lang('mark_as_modified'), array("data-reload-on-success" => "1")); ?> </li> <?php } ?>
                       <?php
                        if ($estimate_status == "draft") {
                            ?>
                            <!--li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/given"), "<i class='fa fa-hand-lizard-o'></i> " . lang('mark_as_given'), array("data-reload-on-success" => "1")); ?> </li!-->
                            <!--li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/received"), "<i class='fa fa-send'></i> " . lang('mark_as_received'), array("data-reload-on-success" => "1")); ?> </li!-->
                        <?php } else if ($estimate_status == "sent") { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/accepted"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_accepted'), array("data-reload-on-success" => "1")); ?> </li>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("delivery/update_delivery_status/" . $estimate_info->id . "/declined"), "<i class='fa fa-times-circle-o'></i> " . lang('mark_as_declined'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } else if ($list_data&&($estimate_status == "applied"||$estimate_status == "resubmitted")&&($estimate_info->line_manager ==$this->login_user->id||($estimate_info->line_manager =='admin'&&$this->login_user->is_admin))) { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("voucher/update_voucher_status/" . $estimate_info->id . "/verified_by_manager"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_verified_by_manager'), array("data-reload-on-success" => "1")); ?> </li>
                            <li role="presentation"><?php echo modal_anchor(get_uri("voucher/remarks/" . $estimate_info->id . "/rejected_by_manager"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_rejected_by_manager'), array("data-reload-on-success" => "1")); ?> </li>                      
                            <?php } else if ($list_data&&$estimate_status == "verified_by_manager"&&($this->login_user->department ==="09")&&($estimate_info->created_user_id!=$this->login_user->id)) { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("voucher/update_voucher_status/" . $estimate_info->id . "/approved_by_accounts"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_verified_by_accounts'), array("data-reload-on-success" => "1")); ?> </li>
                            <li role="presentation"><?php echo modal_anchor(get_uri("voucher/remarks/" . $estimate_info->id . "/rejected_by_accounts"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_rejected_by_accounts'), array("data-reload-on-success" => "1")); ?> </li>                       
                          <?php }else if ($list_data&&$estimate_status == "approved_by_accounts"&&($this->login_user->department ==="09")&&($estimate_info->created_user_id!=$this->login_user->id)) { ?>
                           <!--  <li role="presentation"><?php echo ajax_anchor(get_uri("voucher/update_voucher_status/" . $estimate_info->id . "/paid"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_paid'), array("data-reload-on-success" => "1")); ?> </li> -->
                          <?php }else if ($list_data&&($estimate_status == "rejected_by_accounts"||$estimate_status == "rejected_by_manager")&&($estimate_info->created_user_id==$this->login_user->id)) { ?>
                            <li role="presentation"><?php echo ajax_anchor(get_uri("voucher/update_voucher_status/" . $estimate_info->id . "/resubmitted"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_resubmit'), array("data-reload-on-success" => "1")); ?> </li>                                       <?php } else if ($list_data&&$estimate_status == "modified") { ?>
                             <li role="presentation"><?php echo ajax_anchor(get_uri("voucher/update_voucher_status/" . $estimate_info->id . "/accepted"), "<i class='fa fa-check-circle'></i> " . lang('mark_as_accepted'), array("data-reload-on-success" => "1")); ?> </li>
                        <?php } ?>
                
                    </ul>
                </span>
                 <?php echo modal_anchor(get_uri("voucher/item_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_amount'), array("class" => "btn btn-default", "title" => lang('add_vd'),"id"=>"add_item", "data-post-estimate_id" => $estimate_info->id)); ?>
                  </div>
               </div>
        <div id="estimate-status-bar">
            <?php $this->load->view("voucher/voucher_status_bar"); ?>
        </div>
        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("voucher_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $style = get_setting("voucher_style");
                    ?>
                 <?php 
if (!empty($client_info)) {
    $data = array(
        "client_info" => $client_info,
        "color" => isset($color) ? $color : '', 
        "estimate_info" => isset($estimate_info) ? $estimate_info : '',
    );
    
    if (isset($style) && $style === "style_2") {
        $this->load->view('voucher/voucher_parts/header_style_2.php', $data);
    } else {
        $this->load->view('voucher/voucher_parts/header_style_1.php', $data);
    }
} else {
    echo "Client information is missing.";
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

                <p class="b-t b-info pt10 m15"><b>Description : </b><?php echo nl2br($estimate_info->note); ?></p>
                        <div class="page-title clearfix mt15">
             <h1 style="color:orange"><?php echo lang('remark'); ?></h1>
                           
                        </div>

            <?php
            //for decending mode, show the comment box at the bottom
            if ($sort_as_decending) {
                foreach ($comments as $comment) {
                    $this->load->view("tickets/comment_row", array("comment" => $comment));
                }
            }
            ?>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
    RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#estimate-item-table").appTable({
            source: '<?php echo_uri("voucher/item_list_data/" . $estimate_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
             <?php if (($estimate_status == "modified"||$estimate_status == "rejected_by_manager"||$estimate_status == "rejected_by_accounts")&&($this->login_user->id==$estimate_info->created_user_id)) { ?>
            columns: [
            {title: "<?php echo lang("voucher_no") ?> ", "class": "text-center w10p"},
                {title: "<?php echo lang("category") ?> ", "class": "text-center w55p"},
                {title: "<?php echo lang("amount") ?>", "class": "text-center w45p"},
                 {title: "<?php echo lang("convert_amount") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("issuer") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("received_by") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("date") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("project") ?> ", "class": "text-center w45p"},
                {title: "<?php echo lang("files") ?> ", "class": "text-center w45p"},
            {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ], 
                        <?php }else { ?>
           columns: [
            {title: "<?php echo lang("voucher_no") ?> ", "class": "text-center w10p"},
                {title: "<?php echo lang("category") ?> ", "class": "text-center w55p"},
                {title: "<?php echo lang("amount") ?>", "class": "text-center w45p"},
                 {title: "<?php echo lang("convert_amount") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("issuer") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("received_by") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("date") ?>", "class": "text-center w45p"},
                {title: "<?php echo lang("project") ?> ", "class": "text-center w45p"},
                {title: "<?php echo lang("files") ?> ", "class": "text-center w45p"},
             
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
            url: "<?php echo get_uri("voucher/get_delivery_status_bar"); ?>/" + estimateId,
            success: function (result) {
                if (result) {
                    $("#estimate-status-bar").html(result);
                }
            }
        });
    };

</script>

<script type="text/javascript">
    $( document ).ready(function() {
   event.preventDefault();
  <?php if ($list_data&&($estimate_status == "accepted"||$estimate_status == "applied"||$estimate_status == "sold"||$estimate_status == "verified_by_manager"||$estimate_status == "rejected_by_manager"||$estimate_status == "approved_by_accounts"||$estimate_status == "rejected_by_accounts"||$estimate_status == "paid"||$estimate_status == "resubmitted"||$estimate_status=="payment_in_progress"||$estimate_status=="payment_hold"||$estimate_status=="payment_done"||$estimate_status=="payment_received"||$estimate_status=="closed")) { ?>
   $('#add_item').prop("disabled", true);
   $('#add_item').attr("disabled", true); 
   $("#add_item").attr('title', 'Already voucher has been created');
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
