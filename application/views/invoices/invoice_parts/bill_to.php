<?php 
$options = array(
            "id" => $client_info->state,
                   );
        $client_state = $this->States_model->get_details($options)->row();
        ?>

        <?php 

$optionss = array(
            "id" =>$invoice_info->dispatched_through,
                   );
        $dispatched_through_data = $this->Mode_of_dispatch_model->get_details($optionss)->row();
        ?>

        <?php 
$options_country = array(
            "id" => $invoice_info->country,
                   );
      $client_country = $this->Countries_model->get_details($options_country)->row();
        ?>

<table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:152px;">
<div><b><?php echo lang("buyer"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>

<strong style="font-weight: bold;color:black;"><?php echo $client_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->address || (isset($client_info->custom_fields) && $client_info->custom_fields)) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
                <br /> <?php echo $client_info->city."-"; ?>
            <?php } ?>
            
            <?php if ($client_info->zip) { ?>
                <?php echo $client_info->zip.","; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
                <?php echo $client_country->countryName; ?>
            <?php } ?>
            <?php if ($client_info->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $client_info->gst_number; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                <br /><?php echo lang("state") . ": " .  $client_state->title; echo",";echo lang("code") . ": " . $client_info->gstin_number_first_two_digits;  ?>
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
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("buyers_order_no").":";?><?php if ($invoice_info->buyers_order_no) { ?>
            <div style="font-weight: bold;color:#232323;"><?php echo $invoice_info->buyers_order_no; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px"><?php echo lang("buyers_order_date") . ": " ;?><?php if ($invoice_info->buyers_order_date) { ?><div style="font-weight: bold;color:#232323;"><?php if($invoice_info->buyers_order_no){echo format_to_date($invoice_info->buyers_order_date, false); } ?><?php } ?></div></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("delivery_note").":";?><?php if ($invoice_info->note) { ?>
                <div style="font-weight: bold;color:#232323;"><?php echo $invoice_info->note; ?></div>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 30px;"><?php echo lang("delivery_note_date").":";?> <?php if ($invoice_info->delivery_note_date) { ?><div style="font-weight: bold;color:#232323;"><?php if($invoice_info->note) { echo format_to_date($invoice_info->delivery_note_date, false);  } ?></div><?php } ?></td>
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 40px"><?php echo lang("dispatch_docket").":" ;?><?php if ($invoice_info->dispatch_docket) { ?>
        <div style="font-weight: bold;color:#232323;"><?php echo $invoice_info->dispatch_docket; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 40px"><?php echo lang("mode_of_dispatch").":";?><?php if ($invoice_info->dispatched_through) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $dispatched_through_data->title; ?></div>
            <?php } ?></td>
    
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
  <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 40px"><?php echo lang("lc_no").":" ;?><?php if ($invoice_info->lc_no) { ?>
        <div style="font-weight: bold;color:#232323;"><?php echo $invoice_info->lc_no; ?></div>
            <?php } ?></td>
            <td   style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height: 40px"><?php echo lang("lc_date").":";?><?php if ($invoice_info->lc_no) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $invoice_info->lc_date; ?></div>
            <?php } ?></td>
    
    
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;"><?php echo lang("destination").":";?></td>
    <td style="border: 1px solid #dddddd;color: #232323;font-size:14px;
  text-align: left;padding: 5px;height: 20px;font-weight: bold;"><?php if ($invoice_info->destination) { ?>
               <b><?php echo($invoice_info->destination); ?></b>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    
    <td  colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:14px;
  text-align: left;padding: 5px;height:60px;"><?php echo lang("terms_of_delivery").":";?><?php if ($invoice_info->terms_of_delivery) { ?>
               <div style="font-weight: bold;color:#232323;"><?php echo $invoice_info->terms_of_delivery; ?></div>
            <?php } ?></td>
    
  </tr>
  
 
</table>


<!--<div><b><?php echo lang("bill_to"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong><?php echo $client_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->address || (isset($client_info->custom_fields) && $client_info->custom_fields)) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
                <br /><?php echo $client_info->city; ?>
            <?php } ?>
            
            <?php if ($client_info->zip) { ?>
                <br /><?php echo $client_info->zip; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
                <br /><?php echo $client_info->country; ?>
            <?php } ?>
            <?php if ($client_info->gst_number) { ?>
                <br /><?php echo lang("gst_number") . ": " . $client_info->gst_number; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                <br /><?php echo lang("state") . ": " . $client_info->state; echo",";echo lang("code") . ": " . $client_info->gstin_number_first_two_digits;  ?>
            <?php } ?>
            <?php
            if (isset($client_info->custom_fields) && $client_info->custom_fields) {
                foreach ($client_info->custom_fields as $field) {
                    if ($field->value) {
                        echo "<br />" . $field->custom_field_title . ": " . $this->load->view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value), true);
                    }
                }
            }
            ?>


        </div>
<?php } ?>

<?php if ($invoice_info->buyers_order_no) { ?>
                <br /><?php echo lang("buyers_order_no").":". $invoice_info->buyers_order_no; ?>
            <?php } ?>
            <br>
<span><?php echo lang("buyers_order_date") . ": " . format_to_date($invoice_info->buyers_order_date, false); ?></span>
<?php if ($invoice_info->dispatch_document_no) { ?>
                <br /><?php echo lang("dispatch_document_no").":" .$invoice_info->dispatch_document_no; ?>
            <?php } ?>
            <br>
<span><?php echo lang("delivery_note_date") . ": " . format_to_date($invoice_info->delivery_note_date, false); ?></span>
<?php if ($invoice_info->dispatched_through) { ?>
                <br /><?php echo lang("dispatched_through").":".$invoice_info->dispatched_through; ?>
            <?php } ?>
      <?php if ($invoice_info->destination) { ?>
                <br /><?php echo lang("destination").":".$invoice_info->destination; ?>
            <?php } ?>
            <?php if ($invoice_info->terms_of_delivery) { ?>
                <br /><?php echo lang("terms_of_delivery").":".$invoice_info->terms_of_delivery; ?>
            <?php } ?>
            
</span>
<!--<table>
  <tr style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"> <?php if ($invoice_info->buyers_order_no) { ?>
                <br /><?php echo lang("buyers_order_no").":". $invoice_info->buyers_order_no; ?>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"><?php echo lang("buyers_order_date") . ": " . format_to_date($invoice_info->buyers_order_date, false); ?></td>
    
  </tr>
  
    

  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"><?php if ($invoice_info->dispatch_document_no) { ?>
                <br /><?php echo lang("dispatch_document_no").":" .$invoice_info->dispatch_document_no; ?>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"><?php if ($invoice_info->dispatch_document_no) { ?>
                <br /><?php echo lang("dispatch_document_no").":" .$invoice_info->dispatch_document_no; ?>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"> <?php echo lang("delivery_note_date") . ": " . format_to_date($invoice_info->delivery_note_date, false); ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"><?php if ($invoice_info->dispatched_through) { ?>
                <br /><?php echo lang("dispatched_through").":".$invoice_info->dispatched_through; ?>
            <?php } ?></td>
    
  </tr>
  <tr style="border: 1px solid #dddddd;
  text-align: left;padding: 5px;">
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"><?php echo lang("destination"); ?><?php if ($invoice_info->destination) { ?>
                <br /><?php echo($invoice_info->destination); ?>
            <?php } ?></td>
    <td style="border: 1px solid #dddddd;color: #666;font-size:13px;
  text-align: left;padding: 5px;"><?php if ($invoice_info->terms_of_delivery) { ?>
                <br /><?php echo lang("terms_of_delivery").":".$invoice_info->terms_of_delivery; ?>
            <?php } ?></td>
    
  </tr>
 
</table>-->
