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
 <div class="row bg-dark-success p20">
 <div class="col-md-6">
            <p> 
                <?php
                

                echo lang("company_name") . ": <b>" . $vendor_info->company_name . "</b>";
                ?>

            </p>
            <?php if ($vendor_info->address) { ?>
                <p><?php echo nl2br($vendor_info->address); ?>
                    <?php if ($vendor_info->city) { ?>
                        <br /><?php echo $vendor_info->city; ?>
                    <?php } ?>
                    <?php if ($vendor_info->state) { ?>
                        <br /><!-- <?php echo $vendor_info->state; ?> -->
                      <?php   if($vendor_info->state){
$state_no = is_numeric($vendor_info->state);
 if(!$state_no){
   $vendor_info->state = 0;
 }
}
$options = array(
            "id" => $vendor_info->state,
                   );
        $state_id_name = $this->States_model->get_details($options)->row();
        $state_dummy_name =$state_id_name->title;
        echo $state_dummy_name;
        ?>
                    <?php } ?>
                    <?php if ($vendor_info->zip) { ?>
                        <br /><?php echo $vendor_info->zip; ?>
                    <?php } ?>
                    <?php if ($vendor_info->country) { ?>
                        <br /><!-- <?php echo $vendor_info->country; ?> -->
                        <?php  
if($vendor_info->country){
$country_no = is_numeric($vendor_info->country);
 if(!$country_no){
   $vendor_info->country = 0;
 }
}
$options = array(
            "id" => $vendor_info->country,
                   );
        $country_id_name = $this->Countries_model->get_details($options)->row();
        $country_dummy_name =$country_id_name->countryName;
        echo $country_dummy_name;
        ?>
                    <?php } ?>
                </p>
                <p>
                    <?php
                    if ($vendor_info->website) {
                        $website = to_url($vendor_info->website);
                        echo lang("website") . ": " . "<a target='_blank' href='" . $website . "' class='white-link'>$website</a>";
                        ?>
                    <?php } ?>
                    <?php if ($vendor_info->gst_number) { ?>
                        <br /><?php echo lang("gst_number") . ": " . $vendor_info->gst_number; ?>
                    <?php } ?>  
                    <?php if ($vendor_info->phone) { ?>
                        <br /><?php echo lang("phone") . ": " . $vendor_info->phone; ?>
                    <?php } ?> 
                </p>
            <?php } ?>
        </div>  </div>    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("vendors_register/contacts/" . $vendor_info->id); ?>" data-target="#vendor-contacts"> <?php echo lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("vendors_register/company_info_tab/" . $vendor_info->id); ?>" data-target="#vendor-info"> <?php echo lang('vendor_info'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("vendors_register/bank_info_tab/" . $vendor_info->id); ?>" data-target="#vendor-bank_info"> <?php echo lang('company_kyc'); ?></a></li>
        
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="vendor-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="vendor-contacts"></div>
         <div role="tabpanel" class="tab-pane fade" id="vendor-bank_info"></div>
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
