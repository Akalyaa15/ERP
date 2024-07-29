<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang('vendor_details') . " - " . $vendor_info->company_name ?>
        <span id="star-mark">
            <?php
            if ($is_starred) {
                $this->load->view('vendors/star/starred', array("vendor_id" => $vendor_info->id));
            } else {
                $this->load->view('vendors/star/not_starred', array("vendor_id" => $vendor_info->id));
            }
            ?>
        </span>    
    </h1>
</div>

<div id="page-content" class="clearfix">
    <div class="mt15">
        <?php $this->load->view("vendors/info_widgets/index"); ?>
    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("vendors/contacts/" . $vendor_info->id); ?>" data-target="#vendor-contacts"> <?php echo lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("vendors/company_info_tab/" . $vendor_info->id); ?>" data-target="#vendor-info"> <?php echo lang('vendor_info'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("vendors/bank_info_tab/" . $vendor_info->id); ?>" data-target="#vendor-bank_info"> <?php echo lang('vendor_kyc'); ?></a></li>
         <!-- <li><a  role="presentation" href="<?php /* echo_uri("vendors/estimate_requests/" . $client_info->id); ?>" data-target="#client-estimate-requests"> <?php echo lang('estimate_requests'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("vendors/estimates/" . $client_info->id); ?>" data-target="#client-estimates"> <?php echo lang('estimates'); */?></a></li> -->
        <?php if ($show_invoice_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("vendors/purchase_orders/" . $vendor_info->id); ?>" data-target="#vendor-purchase_orders"> <?php echo lang('v_purchase_order'); ?></a></li>
        <?php  }  ?>
         <?php if ($show_estimate_info) { ?>
        <li><a  role="presentation" href="<?php echo_uri("vendors/work_orders/" . $vendor_info->id); ?>" data-target="#vendor-work_orders"> <?php echo lang('v_work_order'); ?></a></li>
        <?php  }  ?>



         <!-- <li><a  role="presentation" href="<?php /* echo_uri("vendors/projects/" . $vendor_info->id); ?>" data-target="#vendor-projects"> <?php echo lang('projects'); */?></a></li> -->
       <?php if ($show_estimate_info) { ?>
        <li><a  role="presentation" href="<?php echo_uri("vendors/vendors_invoice_list/" . $vendor_info->id); ?>" data-target="#vendor-invoice_list"> <?php echo lang('vendor_invoice_list'); ?></a></li>
        <?php  }  ?>
         <?php if ($show_invoice_info) { ?>
        <li><a  role="presentation" href="<?php echo_uri("vendors/payments/" . $vendor_info->id); ?>" data-target="#vendor-payments"> <?php echo lang('purchase_order_payments'); ?></a></li>
        <?php  }  ?>

         <?php if ($show_estimate_info) { ?>
        <li><a  role="presentation" href="<?php echo_uri("vendors/wo_payments/" . $vendor_info->id); ?>" data-target="#vendor-wo_payments"> <?php echo lang('work_order_payments'); ?></a></li>
        <?php  }  ?>
                       <?php if ($show_note_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("vendors/notes/" . $vendor_info->id); ?>" data-target="#vendor-notes"> <?php echo lang('notes'); ?></a></li>
        <?php } ?>
              <li><a  role="presentation" href="<?php echo_uri("vendors/files/" . $vendor_info->id); ?>" data-target="#vendor-files"><?php echo lang('files'); ?></a></li>
       <?php if ($show_event_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("vendors/events/" . $vendor_info->id); ?>" data-target="#vendor-events"> <?php echo lang('events'); ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="vendor-contacts"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-info"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-bank_info"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-estimates"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-estimate-requests"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-purchase_orders"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-work_orders"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-projects"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-invoice_list"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-payments"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-wo_payments"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-notes"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-files"></div>
        <div role="tabpanel" class="tab-pane" id="vendor-events" style="min-height: 300px"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var tab = "<?php echo $tab; ?>";
        if (tab === "info") {
            $("[data-target=#vendor-info]").trigger("click");
        }

    });
</script>
