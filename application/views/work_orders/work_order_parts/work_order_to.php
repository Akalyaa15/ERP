<?php 
$options = array(
            "id" => $vendor_info->state,
                   );
        $vendor_state = $this->States_model->get_details($options)->row();
        ?>

        <?php 

$optionss = array(
            "id" =>$work_order_info->dispatched_through,
                   );
        $dispatched_through_data = $this->Mode_of_dispatch_model->get_details($optionss)->row();
        ?>
        <?php 
$options_country = array(
            "id" => $vendor_info->country,
                   );
      $vendor_country = $this->Countries_model->get_details($options_country)->row();
        ?>
<table>
 <tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:152px;">
<div><b><?php echo lang("supplier_name&address"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>

<strong style="font-weight: bold;color:black;"><?php echo $vendor_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($vendor_info->address || (isset($vendor_info->custom_fields) && $vendor_info->custom_fields)) { ?>
        <div><?php echo nl2br($vendor_info->address); ?>
            <?php if ($vendor_info->city) { ?>
                <?php echo $vendor_info->city."-"; ?>
            <?php } ?>
            
            <?php if ($vendor_info->zip) { ?>
                <?php echo $vendor_info->zip.","; ?>
            <?php } ?>
            <?php if ($vendor_info->country) { ?>
               <?php echo $vendor_country->countryName; ?>
            <?php } ?>
            <?php if ($vendor_info->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $vendor_info->gst_number.","; ?>
            <?php } ?>
            <?php if ($vendor_info->state) { ?>
                <?php echo lang("state") . ": " . $vendor_state->title; echo",";echo lang("code") . ": " . $vendor_info->gstin_number_first_two_digits;  ?>
            <?php } ?>
            <?php
            if (isset($vendor_info->custom_fields) && $vendor_info->custom_fields) {
                foreach ($vendor_info->custom_fields as $field) {
                    if ($field->value) {
                        echo "<br />" . $field->custom_field_title . ": " . $this->load->view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value), true);
                    }
                }
            }
            ?></div>
<?php } ?>
</span></td></tr>

  <tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("po_no").":";?><?php if ($work_order_info->work_order_no) { ?>
            <div style="font-weight: bold;color:#232323;"><?php echo $work_order_info->work_order_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("po_date") . ": " ;?><?php if ($work_order_info->work_date) { ?><div style="font-weight: bold;color:#232323;"><?php if($work_order_info->work_order_no){ echo format_to_date($work_order_info->work_date, false); } ?><?php } ?></div></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("delivery_note").":";?><?php if ($work_order_info->note) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $work_order_info->note; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("delivery_note_date").":";?> <?php if ($work_order_info->delivery_note_date) { ?><div style="font-weight: bold;color:#232323;"><?php if($work_order_info->note){  echo format_to_date($work_order_info->delivery_note_date, false); } ?></div><?php } ?></td>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
<td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 40px"><?php echo lang("dispatch_docket").":" ;?><?php if ($work_order_info->dispatch_document_no) { ?><div style="font-weight: bold;color:#232323;"><?php echo $work_order_info->dispatch_document_no; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height:40px;"><?php echo lang("dispatched_through").":";?><?php if ($work_order_info->dispatched_through) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $dispatched_through_data->title; ?></div>
            <?php } ?></td>
</tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;"><?php echo lang("destination").":";?></td>
    <td style="border: 1px solid #dddddd;color: #232323;font-size:14px;
  text-align: left;padding: 5px;height: 20px;font-weight: bold;"><?php if ($work_order_info->destination) { ?>
               <b><?php echo($work_order_info->destination); ?></b>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    
    <td  colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:60px;"><?php echo lang("terms_of_delivery").":";?><?php if ($work_order_info->terms_of_delivery) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $work_order_info->terms_of_delivery; ?></div>
            <?php } ?></td>
    
  </tr>
  
 
</table>
