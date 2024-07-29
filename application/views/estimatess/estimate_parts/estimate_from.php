<table>

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
        <?php echo lang("state"). ": ". $company_state;echo",";echo lang("code") . ": " . $company_gstin_number_first_two_digits;  ?> 
    <?php } ?>
    
     </span></td></tr>

  <!--tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height: 22px"><?php echo lang("delivery_note").":";?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 8px;font-weight: bold;color:#232323;"><?php if ($invoice_info->note) { ?>
                <?php echo($invoice_info->note); ?>
            <?php } ?></td>
            
  </tr-->
  <tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 22px"><?php echo lang("terms_of_payment").":";?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 8px;font-weight: bold;color:#232323;"><?php if ($estimate_info->terms_of_payment) { ?>
          <?php echo($estimate_info->terms_of_payment); ?>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 22px"><?php echo lang("supplier_ref").":";?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 8px;font-weight: bold;color:#232323;"><?php if ($estimate_info->supplier_ref) { ?>
              <?php echo($estimate_info->supplier_ref); ?>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 22px"><?php echo lang("other_references").":";?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 8px;font-weight: bold;color:#232323;"><?php if ($estimate_info->other_references) { ?>
             <?php echo($estimate_info->other_references); ?>
            <?php } ?></td>
    
  </tr>
<!--tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:15px;max-width: 20px;word-wrap: break-word;
  text-align: left;padding: 5px;height: 70px;"><?php echo lang("buyer_other_consignee").":";?><?php if ($invoice_info->delivery_address) { ?>
             <br/><?php echo($invoice_info->delivery_address); ?>
            <?php } ?></td>
        
  </tr-->
<?php if($estimate_info->estimate_delivery_address==1) { ?>
  <tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:125px;">
<div><b><?php echo lang("buyer_other_consignee"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>

<strong style="font-weight: bold;color:black;"><?php echo $estimate_info->delivery_address_company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">

    <?php if ($estimate_info->delivery_address ) { ?>
        <div><?php echo nl2br($estimate_info->delivery_address); ?>
            <?php if ($estimate_info->delivery_address_city) { ?>
                <br /><?php echo $estimate_info->delivery_address_city."-"; ?>
            <?php } ?>
            
            <?php if ($estimate_info->delivery_address_zip) { ?>
               <?php echo $estimate_info->delivery_address_zip; ?>
            <?php } ?>
            <?php if ($estimate_info->delivery_address_country) { ?>
                <br /><?php echo $estimate_info->delivery_address_country; ?>
            <?php } ?>
            
            
            </div>
<?php } ?>

</span>

</td></tr>
<?php } ?>
<?php if($estimate_info->estimate_delivery_address==0) { ?>
  <tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:125px;">
<div><b><?php echo lang("buyer_other_consignee"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>

<strong style="font-weight: bold;color:black;"><?php echo $client_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">

    <?php if ($client_info->address ) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
               <?php echo $client_info->city."-"; ?>
            <?php } ?>
            
            <?php if ($client_info->zip) { ?>
                <?php echo $client_info->zip.","; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
                <?php echo $client_info->country; ?>
            <?php } ?>
            <!--<?php if ($client_info->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $client_info->gst_number; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                <br /><?php echo lang("state") . ": " . $client_info->state; echo",";echo lang("code") . ": " . $client_info->gstin_number_first_two_digits;  ?>
            <?php } ?>-->
            
            
            </div>
<?php } ?>

</span>

</td></tr>
<?php } ?>
</table>

