 <?php 
$company_state = get_setting("company_state");
$options = array(
            "id" =>$company_state,
                   );
        $company_state_data = $this->States_model->get_details($options)->row();
        ?>

<table>
 <?php 

$optionss = array(
            "id" =>$estimate_info->invoice_client_id,
                   );
        $client_infos = $this->Clients_model->get_details($optionss)->row();
        ?>
 <?php 
$options = array(
            "id" => $client_infos->state,
                   );
        $client_state = $this->States_model->get_details($options)->row();
        ?>
        <?php 
$client_country = array(
            "id" => $client_infos->country,
                   );
        $client_country_name = $this->Countries_model->get_details($client_country)->row();
        ?>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td  colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:150px;">
<?php
$company_address = nl2br(get_setting("company_address"));
$company_phone = get_setting("company_phone");
$company_website = get_setting("company_website");
$company_gst_number = get_setting("company_gst_number");
$company_state = get_setting("company_state");
$company_gstin_number_first_two_digits = get_setting("company_gstin_number_first_two_digits");

?><div style="font-weight: bold;color:black;"><strong><?php echo get_setting("company_name"); ?></strong></div>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;"><?php
    if ($company_address) {
        echo $company_address;
    }
    ?>
    <?php if ($company_phone) { ?>
        <!--<div style="line-height: 1px;"> </div>-->
        <br /><?php echo lang("phone") . ": " . $company_phone; ?>
    <?php } ?>
    <?php if ($company_website) { ?>
        <!--<div style="line-height: 1px;"> </div>-->
        <br /><?php echo lang("website"); ?>: <a style="color:#666; text-decoration: none;" href="<?php echo $company_website; ?>"><?php echo $company_website; ?></a>
    <?php } ?>
    <?php if ($company_gst_number) { ?>
        <!--<div style="line-height: 1px;"> </div>-->
        <br /><?php echo lang("gst_number"). ": ". $company_gst_number.","; ?>
    <?php } ?>
    
    <?php if ($company_state) { ?>
        <!--<div style="line-height: 1px;"> </div>-->
         <br /><?php echo lang("state"). ": ". $company_state_data->title;echo",";echo lang("code") . ": " . $company_gstin_number_first_two_digits;  ?> 
    <?php } ?>
 
     </span></td></tr>

  
   <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("order_no").":";?><?php if ($estimate_info->buyers_order_no) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->buyers_order_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("order_date").":";?> <?php if ($estimate_info->buyers_order_no) { ?><div style="font-weight: bold;color:#232323;"><?php echo format_to_date($estimate_info->buyers_order_date, false); ?></div><?php } ?></td>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("invoice_no").":";?><?php if ($estimate_info->invoice_for_dc) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->invoice_for_dc; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("invoice_date").":";?> <?php if ($estimate_info->invoice_for_dc) { ?><div style="font-weight: bold;color:#232323;"><?php echo format_to_date($estimate_info->invoice_date, false); ?></div><?php } ?></td>
    
     
  </tr> 
  <tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:125px;">
<div><b><?php echo lang("buyer_other_consignee"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>


<span class="invoice-meta" style="font-size: 90%; color: #666;">

    <?php if ($estimate_info->invoice_delivery_address=='1' ) { ?><strong style="font-weight: bold;color:black;"><?php echo $estimate_info->delivery_address_company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
        <div><?php echo nl2br($estimate_info->delivery_address); ?>
            <?php if ($estimate_info->delivery_address_city) { ?>
                <?php echo $estimate_info->delivery_address_city."-"; ?>
            <?php } ?>
            
            <?php if ($estimate_info->delivery_address_zip) { ?>
                <?php echo $estimate_info->delivery_address_zip; ?>
            <?php } ?>
            <?php if ($estimate_info->delivery_address_country) { ?>
                <br /><?php echo $estimate_info->delivery_address_country; ?>
            <?php } ?>
            
            
            </div><?php }else {?>

<strong style="font-weight: bold;color:black;"><?php echo $client_infos->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
 <?php if ($client_infos->address || (isset($client_infos->custom_fields) && $client_infos->custom_fields)) { ?>
        <div><?php echo nl2br($client_infos->address); ?>
            <?php if ($client_infos->city) { ?>
                <br /> <?php echo $client_infos->city."-"; ?>
            <?php } ?>
            
            <?php if ($client_infos->zip) { ?>
                <?php echo $client_infos->zip.","; ?>
            <?php } ?>
            <?php if ($client_infos->country) { ?>
                <?php echo  $client_country_name->countryName; ?>
            <?php } ?>
            <?php if ($client_infos->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $client_infos->gst_number; ?>
            <?php } ?>
            <?php if ($client_infos->state) { ?>
                <br /><?php echo lang("state") . ": " .  $client_state->title; echo",";echo lang("code") . ": " . $client_infos->gstin_number_first_two_digits;  ?>
            <?php } ?>   </div>         <?php } ?>

<?php } ?>
</span>

</td></tr>




</table>
