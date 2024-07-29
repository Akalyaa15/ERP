<?php if($client_info->partner_id){ ?>
<script type="text/javascript">
     $(document).ready(function () {
    $(".clientss").removeClass("active");        
    $(".sss").addClass("active");        
    });
</script>
<?php } ?>
<?php if($client_info->partner_id){ ?>
<style>
#group_id {
    display: none;
}

</style>
<?php } ?>
<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php 
if($client_info->partner_id){
echo lang('partner_details') . " - " . $client_info->company_name ;
}else{
    echo lang('client_details') . " - " . $client_info->company_name ;

    } ?>
</h1>
        <span id="star-mark">
            <?php
            if ($is_starred) {
                $this->load->view('clients/star/starred', array("client_id" => $client_info->id));
            } else {
                $this->load->view('clients/star/not_starred', array("client_id" => $client_info->id));
            }
            ?>
        </span>    
    </h1>
</div>
        <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>

<div id="page-content" class="clearfix">
    <div class="mt15">
        <?php $this->load->view("clients/info_widgets/index"); ?>
    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("clients/contacts/" . $client_info->id); ?>" data-target="#client-contacts"> <?php echo lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("clients/company_info_tab/" . $client_info->id); ?>" data-target="#client-info"> <?php 
if($client_info->partner_id){
echo lang('partner_info');
}else{
    echo lang('client_info');

    } ?></a></li>
    <li><a  role="presentation" href="<?php echo_uri("clients/bank_info_tab/" . $client_info->id); ?>" data-target="#client-bank_info"><?php 
if($client_info->partner_id){
echo lang('partner_kyc');
}else{
    echo lang('client_kyc');

    } ?></a></li>
           <?php if ($show_estimate_request_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/estimate_requests/" . $client_info->id); ?>" data-target="#client-estimate-requests"> <?php echo lang('estimate_requests'); ?></a></li>
        <?php } ?>
                 <?php if ($show_estimate_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/estimates/" . $client_info->id); ?>" data-target="#client-estimates"> <?php echo lang('estimates'); ?></a></li>
        <?php } ?>

                <?php if ($show_estimate_info) { ?>
        <li><a  role="presentation" href="<?php echo_uri("clients/clients_po_list/" . $client_info->id); ?>" data-target="#client-po_list"><?php 
if($client_info->partner_id){
echo lang('partner_po_list');
}else{
    echo lang('client_po_list');

    } ?></a></li>
        <?php  }  ?>
                <?php if ($show_estimate_info) { ?>
        <li><a  role="presentation" href="<?php echo_uri("clients/clients_wo_list/" . $client_info->id); ?>" data-target="#client-wo_list"> <?php 
if($client_info->partner_id){
echo lang('partner_wo_list');
}else{
    echo lang('client_wo_list');

    } ?></a></li>
        <?php  }  ?>    
                <li><a  role="presentation" href="<?php echo_uri("clients/projects/" . $client_info->id); ?>" data-target="#client-projects"><?php echo lang('projects'); ?></a></li>
      
        <?php if ($show_invoice_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/invoices/" . $client_info->id); ?>" data-target="#client-invoices"> <?php echo lang('invoices'); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("clients/payments/" . $client_info->id); ?>" data-target="#client-payments"> <?php echo lang('payments'); ?></a></li>
        <?php } ?>

        <?php if ($show_ticket_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/tickets/" . $client_info->id); ?>" data-target="#client-tickets"> <?php echo lang('tickets'); ?></a></li>
        <?php } ?>
        <?php if ($show_note_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/notes/" . $client_info->id); ?>" data-target="#client-notes"> <?php echo lang('notes'); ?></a></li>
        <?php } ?>
       <li><a  role="presentation" href="<?php echo_uri("clients/files/" . $client_info->id); ?>" data-target="#client-files"><?php echo lang('files'); ?></a></li>
       
        <?php if ($show_event_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/events/" . $client_info->id); ?>" data-target="#client-events"> <?php echo lang('events'); ?></a></li>
        <?php } ?>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="client-projects"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-bank_info"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-contacts"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-invoices"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-payments"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-estimates"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-estimate-requests"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-tickets"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-notes"></div>
         <div role="tabpanel" class="tab-pane fade" id="client-po_list"></div>
         <div role="tabpanel" class="tab-pane fade" id="client-wo_list"></div>
        <div role="tabpanel" class="tab-pane" id="client-events" style="min-height: 300px"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var tab = "<?php echo $tab; ?>";
        if (tab === "info") {
            $("[data-target=#client-info]").trigger("click");
        }

    });
</script>
