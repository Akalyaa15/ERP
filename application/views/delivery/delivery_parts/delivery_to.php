<style type="text/css">
  .invoice-preview * {
    line-height: 23px !important;
}
</style>

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
<table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:152px;">
<div><b><?php echo lang("buyer"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>

<strong style="font-weight: bold;color:black;"><?php echo $client_infos->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_infos->address || (isset($client_infos->custom_fields) && $client_infos->custom_fields)) { ?>
        <div><?php echo nl2br($client_infos->address); ?>
            <?php if ($client_infos->city) { ?>
                <br /> <?php echo $client_infos->city."-"; ?>
            <?php } ?>
            
            <?php if ($client_infos->zip) { ?>
                <?php echo $client_infos->zip.","; ?>
            <?php } ?>
            <?php if ($client_infos->country) { ?>
                <?php echo $client_country_name->countryName; ?>
            <?php } ?>
            <?php if ($client_infos->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $client_infos->gst_number; ?>
            <?php } ?>
            <?php if ($client_infos->state) { ?>
                <br /><?php echo lang("state") . ": " .  $client_state->title; echo",";echo lang("code") . ": " . $client_infos->gstin_number_first_two_digits;  ?>
            <?php } ?>
            <?php
            if (isset($client_info->custom_fields) && $client_info->custom_fields) {
                foreach ($client_info->custom_fields as $field) {
                    if ($field->value) {
                        echo "<br />" . $field->custom_field_title . ": " . $this->load->view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value), true);
                    }
                }
            }
            ?></div>
<?php } ?>
</span></td></tr>

<tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px"><?php echo lang("dc_type").":" ;?><?php if ($estimate_info->dc_type_id) { ?>
        <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->dc_type_title; ?></div>
            <?php } ?></td>
            <?php if($estimate_info->member_type=='tm'||$estimate_info->member_type=='om') { ?>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px"><?php echo lang("dispatch_by").":";?>
               <div style="font-weight: bold;color:#232323;"><?php echo $client_info->first_name." ".$client_info->last_name; ?></div>
           </td>
             <?php }elseif ($estimate_info->member_type=='others') { ?>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px"><?php echo lang("dispatch_by").":";?><?php if ($estimate_info->f_name) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->f_name." ".$estimate_info->l_name; ?></div>
            <?php } ?></td>
    <?php } ?>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px"><?php echo lang("mod").":" ;?><?php if ($estimate_info->dispatched_through) { ?>
        <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->mode_of_dispatch; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px"><?php echo lang("dispatch_date").":";?><?php if ($estimate_info->dispatch_docket) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo format_to_date($estimate_info->dispatch_date,false); ?></div>
            <?php } ?></td>
    
    
    
  </tr>
 
    <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px;"><?php echo lang("lc_no").":";?><?php if ($estimate_info->lc_no) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->lc_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px;"><?php echo lang("lc_date").":";?> <?php if ($estimate_info->lc_no) { ?><div style="font-weight: bold;color:#232323;"><?php echo format_to_date($estimate_info->lc_date,false); ?></div><?php } ?></td>
    
    
  </tr> 
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px;"><?php echo lang("dispatch_docket").":";?><?php if ($estimate_info->dispatch_docket) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->dispatch_docket; ?></div>
            <?php } ?></td>
   <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px;"><?php echo lang("dispatch_name").":";?><?php if ($estimate_info->dispatch_name) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->dispatch_name; ?></div>
            <?php } ?></td>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px;"><?php echo lang("waybill_no").":";?><?php if ($estimate_info->waybill_no) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->waybill_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 38px;"><?php echo lang("demo_period").":";?> <?php if ($estimate_info->demo_period) { ?><div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->demo_period; ?></div><?php } ?></td>
    
    
  </tr>  
</table>
<?php /*
<table> 
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:145px;">
<strong style="font-weight: bold;color:black;"><?php echo lang("deliver_to"); ?></strong><br>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<!--div style="line-height: 3px;"> </div-->
<!--strong style="font-weight: bold;color:black;"><?php echo $client_info->first_name." ".$client_info->last_name; ?> </strong-->
<!--div style="line-height: 3px;"> </div-->
<?php if($estimate_info->member_type=='tm'||$estimate_info->member_type=='om') { ?>
<strong><?php echo $client_info->first_name." ".$client_info->last_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->job_title) { ?>
        <div><?php echo nl2br($client_info->job_title); ?>
            
        </div>
    <?php } ?>
</span>    <?php }elseif ($estimate_info->member_type=='others') {
 ?><strong><?php echo $estimate_info->f_name." ".$estimate_info->l_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
        <div><?php echo nl2br($estimate_info->address); ?>
            
        </div>
        <div><?php echo  nl2br('Contact No:'.$estimate_info->phone); ?>
            
        </div>
</span> 
    <?php } ?>

</td></tr>

<tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("dc_type").":" ;?><?php if ($estimate_info->dc_type_id) { ?>
        <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->dc_type_title; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("dispatched_through").":";?><?php if ($estimate_info->invoice_for_dc) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->invoice_for_dc; ?></div>
            <?php } ?></td>
    
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("mod").":" ;?><?php if ($estimate_info->dispatched_through) { ?>
        <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->mode_of_dispatch; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("dispatch_date").":";?><?php if ($estimate_info->dispatch_date) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->dispatch_date; ?></div>
            <?php } ?></td>
    
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("order_no").":";?><?php if ($estimate_info->buyers_order_no) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->buyers_order_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("order_date").":";?> <?php if ($estimate_info->buyers_order_date) { ?><div style="font-weight: bold;color:#232323;"><?php echo format_to_date($estimate_info->buyers_order_date, false); ?></div><?php } ?></td>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("invoice_no").":";?><?php if ($estimate_info->invoice_for_dc) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->invoice_for_dc; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("invoice_date").":";?> <?php if ($estimate_info->estimate_date&&$estimate_info->invoice_for_dc) { ?><div style="font-weight: bold;color:#232323;"><?php echo format_to_date($estimate_info->estimate_date, false); ?></div><?php } ?></td>
    
    
  </tr> 
</table> */ ?>