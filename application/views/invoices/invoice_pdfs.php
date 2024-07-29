<?php 
$company_setup_country = get_setting("company_setup_country");
if($company_setup_country ==$client_info->country) {?> 


<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$client_info->gstin_number_first_two_digits) {?>
<?php 
$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('invoice_items');
 $DB10->where('invoice_items.invoice_id',$invoice_info->id);
  $DB10->where('invoice_items.gst!=','0');
 $DB10->where('invoice_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
if($hsn_size>0 || $invoice_total_summary->freight_tax_amount||$invoice_total_summary->installation_tax) {?>
<h2> Tax Calculation : </h2>
<table style="width: 100%; color: #444;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 16%; border-right: 1px solid #eee;"> <?php echo lang("hsn_code"); ?> </th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("taxable_value"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("central_tax_rate"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("central_tax_amount"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("state_tax_rate"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("state_tax_amount"); ?></th>
        <th style="text-align: right;  width: 14%; "> <?php echo lang("total_tax_amount"); ?></th>
    </tr>

<?php 
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("total,hsn_code,gst");
 $DB2->from('invoice_items');
 $DB2->where('invoice_items.invoice_id',$invoice_info->id);
 $DB2->where('invoice_items.gst!=','0');
 $DB2->where('invoice_items.deleted','0');
 
$query=$DB2->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $b=$rows->total ;echo to_currency($b); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php  $p=$rows->gst;$q=$p/2;echo($q."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $c= $q/100*$rows->total ;echo to_currency($c);
         ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php echo ($q."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $d= $q/100*$rows->total ;echo to_currency($d); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $e= $c+$d;echo to_currency($e);
         ?></td>
            </tr>
   
<?php } ?>
<?php if($invoice_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('invoice_items');
 $DBinstallation5->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation5->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation5->where('invoice_items.installation_gst!=','0');
 $DBinstallation5->where('invoice_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;

$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('invoice_items');
 $DBinstallation99->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation99->where('invoice_items.installation_gst!=','0');
 $DBinstallation99->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation99->where('invoice_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row= $installation_querys->row();
  
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $invoice_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$installation_rows->installation_gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$installation_rows->installation_gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   

<?php } ?>
<?php if($invoice_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('invoices');
 $DB5->where('invoices.id',$invoice_info->id);
 $DB5->where('invoices.gst!=','0');
 $DB5->where('invoices.deleted','0');
 
$query=$DB5->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$rows->gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$rows->gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>


      <tr>
        <td colspan="0" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal+$invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
$DB99 = $this->load->database('default', TRUE);
$DB99->select_sum("total");
 $DB99->from('invoice_items');
 $DB99->where('invoice_items.invoice_id',$invoice_info->id);
 $DB99->where('invoice_items.gst!=','0');
 $DB99->where('invoice_items.deleted','0');
 
$querys=$DB99->get();
$querys->result();

//INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('invoice_items');
 $DBinstallation99->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation99->where('invoice_items.installation_gst!=','0');
 $DBinstallation99->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation99->where('invoice_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row =
$installation_querys->row();

$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('invoices');
 $DB988->where('invoices.id',$invoice_info->id);
 $DB988->where('invoices.gst!=','0');
 $DB988->where('invoices.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();

 foreach ($querys->result() as $rowss) { 
            echo to_currency($installation_total_row->installation_total+$rowss->total+$freight_total->amount, $invoice_total_summary->currency_symbol); }?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->installation_tax/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->installation_tax/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3; echo to_currency($h, $invoice_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>


<?php /* if (empty($client_info->gstin_number_first_two_digits)) { */ ?>
<?php 
$company_state = get_setting("company_state");
if($company_state ==$client_info->state) {?>
<?php 
$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('invoice_items');
 $DB10->where('invoice_items.invoice_id',$invoice_info->id);
  $DB10->where('invoice_items.gst!=','0');
 $DB10->where('invoice_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
if($hsn_size>0 || $invoice_total_summary->freight_tax_amount||$invoice_total_summary->installation_tax) {?>
<h2> Tax Calculation : </h2>
<table style="width: 100%; color: #444;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 16%; border-right: 1px solid #eee;"> <?php echo lang("hsn_code"); ?> </th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("taxable_value"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("central_tax_rate"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("central_tax_amount"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("state_tax_rate"); ?></th>
        <th style="text-align: right;  width: 14%; border-right: 1px solid #eee;"> <?php echo lang("state_tax_amount"); ?></th>
        <th style="text-align: right;  width: 14%; "> <?php echo lang("total_tax_amount"); ?></th>
    </tr>

<?php 
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("total,hsn_code,gst");
 $DB2->from('invoice_items');
 $DB2->where('invoice_items.invoice_id',$invoice_info->id);
 $DB2->where('invoice_items.gst!=','0');
 $DB2->where('invoice_items.deleted','0');
 
$query=$DB2->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $b=$rows->total ;echo to_currency($b); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php  $p=$rows->gst;$q=$p/2;echo($q."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $c= $q/100*$rows->total ;echo to_currency($c);
         ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php echo ($q."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $d= $q/100*$rows->total ;echo to_currency($d); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $e= $c+$d;echo to_currency($e);
         ?></td>
            </tr>
   
<?php } ?>
<?php if($invoice_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('invoice_items');
 $DBinstallation5->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation5->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation5->where('invoice_items.installation_gst!=','0');
 $DBinstallation5->where('invoice_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;
  
  //INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('invoice_items');
 $DBinstallation99->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation99->where('invoice_items.installation_gst!=','0');
 $DBinstallation99->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation99->where('invoice_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row=$installation_querys->row();
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $invoice_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$installation_rows->installation_gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$installation_rows->installation_gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   

<?php } ?>
<?php if($invoice_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('invoices');
 $DB5->where('invoices.id',$invoice_info->id);
 $DB5->where('invoices.gst!=','0');
 $DB5->where('invoices.deleted','0');
 
$query=$DB5->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$rows->gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$rows->gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>

      <tr>
        <td colspan="0" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal+$invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
$DB99 = $this->load->database('default', TRUE);
$DB99->select_sum("total");
 $DB99->from('invoice_items');
 $DB99->where('invoice_items.invoice_id',$invoice_info->id);
 $DB99->where('invoice_items.gst!=','0');
 $DB99->where('invoice_items.deleted','0');
 
$querys=$DB99->get();
$querys->result();

$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('invoices');
 $DB988->where('invoices.id',$invoice_info->id);
 $DB988->where('invoices.gst!=','0');
 $DB988->where('invoices.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();


 foreach ($querys->result() as $rowss) { 
            echo to_currency($installation_total_row->installation_total+$rowss->total+$freight_total->amount, $invoice_total_summary->currency_symbol); }?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->installation_tax/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->installation_tax/2+$invoice_total_summary->freight_tax3/2, $invoice_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3; echo to_currency($h, $invoice_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } ?>

<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_gstin_number_first_two_digits = get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$client_info->gstin_number_first_two_digits) {?>

<?php 
$DB11 = $this->load->database('default', TRUE);
$DB11->select ("hsn_code,hsn_description,gst");
 $DB11->from('invoice_items');
 $DB11->where('invoice_items.invoice_id',$invoice_info->id);
  $DB11->where('invoice_items.gst!=','0');
 $DB11->where('invoice_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $invoice_total_summary->freight_tax_amount||$invoice_total_summary->installation_tax) {?>
<h2> Tax Calculation : </h2>
<table style="width: 100%; color: #444;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("hsn_code"); ?> </th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("taxable_value"); ?></th>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("igst_rate"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("igst_amount"); ?></th>
       <th style="text-align: right;  width: 20%; "> <?php echo lang("total_tax_amount"); ?></th>
    </tr>

<?php 
$DB3 = $this->load->database('default', TRUE);
$DB3->select ("total,hsn_code,gst,tax_amount");
 $DB3->from('invoice_items');
 $DB3->where('invoice_items.invoice_id',$invoice_info->id);
 $DB3->where('invoice_items.gst!=','0');
 $DB3->where('invoice_items.deleted','0');
 
$query=$DB3->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->total); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->tax_amount);?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php $e=$rows->tax_amount ;echo to_currency($e);
         ?></td>
            </tr>
   
<?php } ?>
<?php if($invoice_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('invoice_items');
 $DBinstallation5->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation5->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation5->where('invoice_items.installation_gst!=','0');
 $DBinstallation5->where('invoice_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;
  
  //INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('invoice_items');
 $DBinstallation99->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation99->where('invoice_items.installation_gst!=','0');
 $DBinstallation99->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation99->where('invoice_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row=$installation_querys->row();
    ?>
    <tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $invoice_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($installation_rows->installation_gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>

<?php if($invoice_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('invoices');
 $DB6->where('invoices.id',$invoice_info->id);
 $DB6->where('invoices.gst!=','0');
 $DB6->where('invoices.deleted','0');
 
$query=$DB6->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>
      <tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal+$invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php  
            $DB98 = $this->load->database('default', TRUE);
$DB98->select_sum("total");
 $DB98->from('invoice_items');
 $DB98->where('invoice_items.invoice_id',$invoice_info->id);
 $DB98->where('invoice_items.gst!=','0');
 $DB98->where('invoice_items.deleted','0');
 
$querys_same_taxvalue=$DB98->get();
$querys_same_taxvalue->result();

$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('invoices');
 $DB988->where('invoices.id',$invoice_info->id);
 $DB988->where('invoices.gst!=','0');
 $DB988->where('invoices.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();
//$totalss=0;
 foreach ($querys_same_taxvalue->result() as $rows_taxsame) { 
            echo to_currency($installation_total_row->installation_total+$rows_taxsame->total+$freight_total->amount, $invoice_total_summary->currency_symbol); } ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3; echo to_currency($h, $invoice_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $i= $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3; echo to_currency($i, $invoice_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>

<?php /* if (empty($client_info->gstin_number_first_two_digits)) {  */?>
<?php 
$company_state = get_setting("company_state");
if($company_state !==$client_info->state) {?>

<?php 
$DB11 = $this->load->database('default', TRUE);
$DB11->select ("hsn_code,hsn_description,gst");
 $DB11->from('invoice_items');
 $DB11->where('invoice_items.invoice_id',$invoice_info->id);
  $DB11->where('invoice_items.gst!=','0');
 $DB11->where('invoice_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $invoice_total_summary->freight_tax_amount||$invoice_total_summary->installation_tax) {?>
<h2> Tax Calculation : </h2>
<table style="width: 100%; color: #444;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("hsn_code"); ?> </th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("taxable_value"); ?></th>
        <th style="text-align: right;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("igst_rate"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("igst_amount"); ?></th>
       <th style="text-align: right;  width: 20%; "> <?php echo lang("total_tax_amount"); ?></th>
    </tr>

<?php 
$DB3 = $this->load->database('default', TRUE);
$DB3->select ("total,hsn_code,gst,tax_amount");
 $DB3->from('invoice_items');
 $DB3->where('invoice_items.invoice_id',$invoice_info->id);
 $DB3->where('invoice_items.gst!=','0');
 $DB3->where('invoice_items.deleted','0');
 
$query=$DB3->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->total); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->tax_amount);?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php $e=$rows->tax_amount ;echo to_currency($e);
         ?></td>
            </tr>
   
<?php } ?>

<?php if($invoice_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('invoice_items');
 $DBinstallation5->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation5->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation5->where('invoice_items.installation_gst!=','0');
 $DBinstallation5->where('invoice_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;
  
  //INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('invoice_items');
 $DBinstallation99->where('invoice_items.invoice_id',$invoice_info->id);
 $DBinstallation99->where('invoice_items.installation_gst!=','0');
 $DBinstallation99->where('invoice_items.with_installation_gst!=',"no");
 $DBinstallation99->where('invoice_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row=$installation_querys->row();
    ?>
    <tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $invoice_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($installation_rows->installation_gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>

<?php if($invoice_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('invoices');
 $DB6->where('invoices.id',$invoice_info->id);
 $DB6->where('invoices.gst!=','0');
 $DB6->where('invoices.deleted','0');
 
$query=$DB6->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->freight_tax3, $invoice_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>
      <tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal+$invoice_total_summary->freight_tax2, $invoice_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php  
            $DB98 = $this->load->database('default', TRUE);
$DB98->select_sum("total");
 $DB98->from('invoice_items');
 $DB98->where('invoice_items.invoice_id',$invoice_info->id);
 $DB98->where('invoice_items.gst!=','0');
 $DB98->where('invoice_items.deleted','0');
 
$querys_same_taxvalue=$DB98->get();
$querys_same_taxvalue->result();


$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('invoices');
 $DB988->where('invoices.id',$invoice_info->id);
 $DB988->where('invoices.gst!=','0');
 $DB988->where('invoices.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();
//$totalss=0;
 foreach ($querys_same_taxvalue->result() as $rows_taxsame) { 
            echo to_currency($installation_total_row->installation_total+$rows_taxsame->total+$freight_total->amount, $invoice_total_summary->currency_symbol); } ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3; echo to_currency($h, $invoice_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $i= $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3; echo to_currency($i, $invoice_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } ?>
<br>
<br>
<?php 
$taxamt=$invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3;
if($taxamt>0) { ?>
<table style="width: 99%; color: #444;line-height: 15px">
    <tr style="font-weight: bold; background-color:white; color: black;  ">
        <th style="width: 100%; border-right: 1px solid #eee;font-weight: bold;font-size: 14px;"><?php
 

$number = $invoice_total_summary->invoice_tax_subtotal+$invoice_total_summary->installation_tax+$invoice_total_summary->freight_tax3;

function convertToIndianCurrencysTaxy($number) {

    $company_currency = get_setting("default_currency");
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
   if($decimal>19){
    $paise = ($decimal) ? "And  " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])." Paise"  : '';
    }else{
       $paise = ($decimal) ? "And  " . ($words[$decimal]) ." Paise"  : ''; 
    }
    return ($Rupees ? $company_currency ." ". $Rupees : '') . $paise . " Only";

}


 echo "Tax Amount(in words) : " . convertToIndianCurrencysTaxy($number);
?></th>
</tr>
</table>
<?php } ?>

<?php } ?>