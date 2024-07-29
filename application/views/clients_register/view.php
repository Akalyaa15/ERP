<?php if($client_info->partner_id){ ?>
<script type="text/javascript">
     $(document).ready(function () {
    $(".clientssss").removeClass("active");        
    $(".ssss").addClass("active");        
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
                $this->load->view('clients_register/star/starred', array("client_id" => $client_info->id));
            } else {
                $this->load->view('clients_register/star/not_starred', array("client_id" => $client_info->id));
            }
            ?>
        </span>    
    </h1>
</div>
        <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>

<div id="page-content" class="clearfix">
    <div class="mt15">
           <div class="row bg-dark-success p20">
 <div class="col-md-6">
            <p> 
                <?php
                

                echo lang("company_name") . ": <b>" . $client_info->company_name . "</b>";
                ?>

            </p>
            <?php if ($client_info->address) { ?>
                <p><?php echo nl2br($client_info->address); ?>
                    <?php if ($client_info->city) { ?>
                        <br /><?php echo $client_info->city; ?>
                    <?php } ?>
                    <?php if ($client_info->state) { ?>
                        <br /><!-- <?php echo $client_info->state; ?> -->
                      <?php   if($client_info->state){
$state_no = is_numeric($client_info->state);
 if(!$state_no){
   $client_info->state = 0;
 }
}
$options = array(
            "id" => $client_info->state,
                   );
        $state_id_name = $this->States_model->get_details($options)->row();
        $state_dummy_name =$state_id_name->title;
        echo $state_dummy_name;
        ?>
                    <?php } ?>
                    <?php if ($client_info->zip) { ?>
                        <br /><?php echo $client_info->zip; ?>
                    <?php } ?>
                    <?php if ($client_info->country) { ?>
                        <br /><!-- <?php echo $client_info->country; ?> -->
                        <?php  
if($client_info->country){
$country_no = is_numeric($client_info->country);
 if(!$country_no){
   $client_info->country = 0;
 }
}
$options = array(
            "id" => $client_info->country,
                   );
        $country_id_name = $this->Countries_model->get_details($options)->row();
        $country_dummy_name =$country_id_name->countryName;
        echo $country_dummy_name;
        ?>
                    <?php } ?>
                </p>
                <p>
                    <?php
                    if ($client_info->website) {
                        $website = to_url($client_info->website);
                        echo lang("website") . ": " . "<a target='_blank' href='" . $website . "' class='white-link'>$website</a>";
                        ?>
                    <?php } ?>
                    <?php if ($client_info->gst_number) { ?>
                        <br /><?php echo lang("gst_number") . ": " . $client_info->gst_number; ?>
                    <?php } ?>  
                    <?php if ($client_info->phone) { ?>
                        <br /><?php echo lang("phone") . ": " . $client_info->phone; ?>
                    <?php } ?> 
                </p>
            <?php } ?>
        </div>  </div>  </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("clients_register/contacts/" . $client_info->id); ?>" data-target="#client-contacts"> <?php echo lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("clients_register/company_info_tab/" . $client_info->id); ?>" data-target="#client-info"> <?php 
if($client_info->partner_id){
echo lang('partner_info');
}else{
    echo lang('client_info');

    } ?></a></li>
 <li><a  role="presentation" href="<?php echo_uri("clients_register/bank_info_tab/" . $client_info->id); ?>" data-target="#client-bank_info"> <?php echo lang('company_kyc'); ?></a></li>
        <li>
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
