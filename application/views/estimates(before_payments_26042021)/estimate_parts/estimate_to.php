<?php 
$options = array(
            "id" => $client_info->state,
                   );
        $client_state = $this->States_model->get_details($options)->row();
        ?>

        <?php 

$optionss = array(
            "id" =>$estimate_info->dispatched_through,
                   );
        $dispatched_through_data = $this->Mode_of_dispatch_model->get_details($optionss)->row();
        ?>
        <?php 
$options_country = array(
            "id" => $estimate_info->country,
                   );
      $client_country = $this->Countries_model->get_details($options_country)->row();
        ?>

<table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:150px;">
<div><b><?php echo lang("consignee"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>

<strong style="font-weight: bold;color:black;"><?php echo $client_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->address || (isset($client_info->custom_fields) && $client_info->custom_fields)) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
                <?php echo $client_info->city."-"; ?>
            <?php } ?>
            
            <?php if ($client_info->zip) { ?>
                <?php echo $client_info->zip.","; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
               <?php echo $client_country->countryName; ?>
            <?php } ?>
            <?php if ($client_info->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $client_info->gst_number.","; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                <?php echo lang("state") . ": " . $client_state->title; echo",";echo lang("code") . ": " . $client_info->gstin_number_first_two_digits;  ?>
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

  <tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("buyers_order_no").":";?><?php if ($estimate_info->buyers_order_no) { ?>
            <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->buyers_order_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("buyers_order_date") . ": " ;?><?php if ($estimate_info->buyers_order_date) { ?><div style="font-weight: bold;color:#232323;"><?php if($estimate_info->buyers_order_no) {echo format_to_date($estimate_info->buyers_order_date, false); } ?><?php } ?></div></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("delivery_note").":";?><?php if ($estimate_info->note) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->note; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("delivery_note_date").":";?> <?php if ($estimate_info->delivery_note_date) { ?><div style="font-weight: bold;color:#232323;"><?php if($estimate_info->note) { echo format_to_date($estimate_info->delivery_note_date, false); }  ?></div><?php } ?></td>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
<td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 40px"><?php echo lang("dispatch_docket").":" ;?><?php if ($estimate_info->dispatch_document_no) { ?><div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->dispatch_document_no; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height:40px;"><?php echo lang("dispatched_through").":";?><?php if ($estimate_info->dispatched_through) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $dispatched_through_data->title; ?></div>
            <?php } ?></td>
</tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 20px"><?php echo lang("destination").":";?></td>
    <td style="border: 1px solid #dddddd;color: #232323;font-size:14px;
  text-align: left;padding: 5px;height: 20px;font-weight: bold;"><?php if ($estimate_info->destination) { ?>
               <b><?php echo($estimate_info->destination); ?></b>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    
    <td  colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:60px;"><?php echo lang("terms_of_delivery").":";?><?php if ($estimate_info->terms_of_delivery) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $estimate_info->terms_of_delivery; ?></div>
            <?php } ?></td>
    
  </tr>
  
 
</table>
