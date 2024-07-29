<!-- <?php if($client_info->partner_id){ ?>
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
<?php } ?> -->
<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php 

    echo lang('company_details') . " - " . $company_info->company_name ;

 ?>
</h1>
        <span id="star-mark">
            <?php
            if ($is_starred) {
                $this->load->view('companys/star/starred', array("company_id" => $company_info->id));
            } else {
                $this->load->view('companys/star/not_starred', array("company_id" => $company_info->id));
            }
            ?>
        </span>    
    </h1>
</div>
        <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>

<div id="page-content" class="clearfix">
    <div class="mt15">
        <?php $this->load->view("companys/info_widgets/index"); ?>
    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("companys/contacts/" . $company_info->cr_id); ?>" data-target="#company-contacts"> <?php echo lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("companys/company_info_tab/" . $company_info->cr_id); ?>" data-target="#company-info"> <?php 

    echo lang('company_info');

     ?></a></li>
    <li><a  role="presentation" href="<?php echo_uri("companys/bank_info_tab/" . $company_info->cr_id); ?>" data-target="#company-bank_info"> <?php echo lang('company_kyc'); ?></a></li>

            <li><a  role="presentation" href="#" data-target="#client-estimate-requests"> <?php echo lang('estimate_requests'); ?></a></li>
            <li><a  role="presentation" href="#" data-target="#client-estimates"> <?php echo lang('estimates'); ?></a></li>
             <li><a  role="presentation" href="<?php echo_uri("clients/projects/" . $client_info->id); ?>" data-target="#client-projects"><?php echo lang('projects'); ?></a></li>
      
        <?php if ($show_invoice_info) { ?>
            <li><a  role="presentation" href="#" data-target="#client-invoices"> <?php echo lang('invoices'); ?></a></li>
            <li><a  role="presentation" href="#" data-target="#client-payments"> <?php echo lang('payments'); ?></a></li>
        <?php } ?>

        <?php if ($show_ticket_info) { ?>
            <li><a  role="presentation" href="#" data-target="#client-tickets"> <?php echo lang('tickets'); ?></a></li>
        <?php } ?>
        <?php if ($show_note_info) { ?>
             <li><a  role="presentation" href="<?php echo_uri("companys/notes/".$company_info->cr_id); ?>" data-target="#company-notes"> <?php echo lang('notes'); ?></a></li>
        <?php } ?>
      <li><a  role="presentation" href="<?php echo_uri("companys/files/" . $company_info->cr_id); ?>"  data-target="#company-files"><?php echo lang('files'); ?></a></li>
       
        <?php if ($show_event_info) { ?>
            <li><a  role="presentation" href="#" data-target="#client-events"> <?php echo lang('events'); ?></a></li>
        <?php } ?>

       
       </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="company-projects"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-bank_info"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-contacts"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-invoices"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-payments"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-estimates"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-estimate-requests"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-tickets"></div>
        <div role="tabpanel" class="tab-pane fade" id="company-notes"></div>
         <div role="tabpanel" class="tab-pane fade" id="company-po_list"></div>
        <div role="tabpanel" class="tab-pane" id="company-events" style="min-height: 300px"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var tab = "<?php echo $tab; ?>";
        if (tab === "info") {
            $("[data-target=#company-info]").trigger("click");
        }

    });
</script>
