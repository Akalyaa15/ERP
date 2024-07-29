<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <?php
        if ($this->login_user->user_type === "client" && $estimate_info->status == "new") {
            ?>

            <div class = "panel panel-default  p15 no-border clearfix">

                <div class="mr15 strong pull-left">
                    <?php echo ajax_anchor(get_uri("estimates/update_estimate_status/$estimate_info->id/accepted"), "<i class='fa fa fa-check-circle'></i> " . lang('mark_as_accepted'), array("class" => "btn btn-success mr15", "title" => lang('mark_as_accepted'), "data-reload-on-success" => "1")); ?>
                    <?php echo ajax_anchor(get_uri("estimates/update_estimate_status/$estimate_info->id/declined"), "<i class='fa fa-times-circle-o'></i> " . lang('mark_as_rejected'), array("class" => "btn btn-danger mr15", "title" => lang('mark_as_rejected'), "data-reload-on-success" => "1")); ?>
                </div>
                <div class="pull-right">
                    <?php
                    echo "<div class='text-center'>" . anchor("estimates/download_pdf/" . $estimate_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
                    ?>
                </div>

            </div>

            <?php
         } else if ($this->login_user->user_type === "client") {

$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('estimate_items');
 $DB10->where('estimate_items.estimate_id',$estimate_info->id);
 $DB10->where('estimate_items.gst!=','0');
 $DB10->where('estimate_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);

  if($hsn_size>0||$estimate_total_summary->freight_tax_amount||
    $estimate_total_summary->installation_tax) {
            echo "<div class='text-center'>" . anchor("estimates/download_pdf/" . $estimate_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        } else {
            echo "<div class='text-center'>" . anchor("estimates/download_estimate_without_gst_pdf/" . $estimate_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        }
    }
        if ($show_close_preview)
            echo "<div class='text-center'>" . anchor("estimates/view/" . $estimate_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
            ?>

        <div class="bg-white mt15 p30">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $estimate_status_label; ?></div>
            </div>

            <?php
             echo $estimate_preview;
            ?>
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
 $DB10->from('estimate_items');
 $DB10->where('estimate_items.estimate_id',$estimate_info->id);
  $DB10->where('estimate_items.gst!=','0');
 $DB10->where('estimate_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
if($hsn_size>0 || $estimate_total_summary->freight_tax_amount||$estimate_total_summary->installation_tax) {?>
<h2> Tax Calculation </h2>

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
 $DB2->from('estimate_items');
 $DB2->where('estimate_items.estimate_id',$estimate_info->id);
 $DB2->where('estimate_items.gst!=','0');
 $DB2->where('estimate_items.deleted','0');
 
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


<?php if($estimate_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('estimate_items');
 $DBinstallation5->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation5->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation5->where('estimate_items.installation_gst!=','0');
 $DBinstallation5->where('estimate_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;

$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('estimate_items');
 $DBinstallation99->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation99->where('estimate_items.installation_gst!=','0');
 $DBinstallation99->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation99->where('estimate_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row= $installation_querys->row();
  
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$installation_rows->installation_gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$installation_rows->installation_gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   

<?php } ?>

            <?php if($estimate_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('estimates');
 $DB5->where('estimates.id',$estimate_info->id);
 $DB5->where('estimates.gst!=','0');
 $DB5->where('estimates.deleted','0');
 
$query=$DB5->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$rows->gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$rows->gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
   
<?php } ?>



      <tr>
        <td colspan="0" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal+$estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
$DB99 = $this->load->database('default', TRUE);
$DB99->select_sum("total");
 $DB99->from('estimate_items');
 $DB99->where('estimate_items.estimate_id',$estimate_info->id);
 $DB99->where('estimate_items.gst!=','0');
 $DB99->where('estimate_items.deleted','0');
 
$querys=$DB99->get();
$querys->result();

//INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('estimate_items');
 $DBinstallation99->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation99->where('estimate_items.installation_gst!=','0');
 $DBinstallation99->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation99->where('estimate_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row =
$installation_querys->row();



$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('estimates');
 $DB988->where('estimates.id',$estimate_info->id);
 $DB988->where('estimates.gst!=','0');
 $DB988->where('estimates.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();

 foreach ($querys->result() as $rowss) { 
    echo to_currency($installation_total_row->installation_total+$rowss->total+$freight_total->amount, $estimate_total_summary->currency_symbol); } ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->installation_tax/2+$estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->installation_tax/2+$estimate_total_summary->freight_tax3/2, $_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3; echo to_currency($h, $estimate_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_state = get_setting("company_state");
if($company_state ==$client_info->state) {?>
<?php 
$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('estimate_items');
 $DB10->where('estimate_items.estimate_id',$estimate_info->id);
  $DB10->where('estimate_items.gst!=','0');
 $DB10->where('estimate_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
if($hsn_size>0 || $estimate_total_summary->freight_tax_amount||$estimate_total_summary->installation_tax) {?>
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
 $DB2->from('estimate_items');
 $DB2->where('estimate_items.estimate_id',$estimate_info->id);
 $DB2->where('estimate_items.gst!=','0');
 $DB2->where('estimate_items.deleted','0');
 
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

<?php if($estimate_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('estimate_items');
 $DBinstallation5->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation5->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation5->where('estimate_items.installation_gst!=','0');
 $DBinstallation5->where('estimate_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;
  
  //INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('estimate_items');
 $DBinstallation99->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation99->where('estimate_items.installation_gst!=','0');
 $DBinstallation99->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation99->where('estimate_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row=$installation_querys->row();
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$installation_rows->installation_gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$installation_rows->installation_gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   

<?php } ?>



            <?php if($estimate_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('estimates');
 $DB5->where('estimates.id',$estimate_info->id);
 $DB5->where('estimates.gst!=','0');
 $DB5->where('estimates.deleted','0');
 
$query=$DB5->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$rows->gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$rows->gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
   
<?php } ?>



      <tr>
        <td colspan="0" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal+$estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
$DB99 = $this->load->database('default', TRUE);
$DB99->select_sum("total");
 $DB99->from('estimate_items');
 $DB99->where('estimate_items.estimate_id',$estimate_info->id);
 $DB99->where('estimate_items.gst!=','0');
 $DB99->where('estimate_items.deleted','0');
 
$querys=$DB99->get();
$querys->result();


$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('estimates');
 $DB988->where('estimates.id',$estimate_info->id);
 $DB988->where('estimates.gst!=','0');
 $DB988->where('estimates.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();


 foreach ($querys->result() as $rowss) { 
    echo to_currency($installation_total_row->installation_total+$rowss->total+$freight_total->amount, $estimate_total_summary->currency_symbol); } ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->installation_tax/2+$estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->installation_tax/2+$estimate_total_summary->freight_tax3/2, $_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3; echo to_currency($h, $estimate_total_summary->currency_symbol); ?>
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
 $DB11->from('estimate_items');
 $DB11->where('estimate_items.estimate_id',$estimate_info->id);
  $DB11->where('estimate_items.gst!=','0');
 $DB11->where('estimate_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $estimate_total_summary->freight_tax_amount||$estimate_total_summary->installation_tax)  { ?>
<h2> Tax Calculation </h2>
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
 $DB3->from('estimate_items');
 $DB3->where('estimate_items.estimate_id',$estimate_info->id);
 $DB3->where('estimate_items.gst!=','0');
 $DB3->where('estimate_items.deleted','0');
 
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

<?php if($estimate_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('estimate_items');
 $DBinstallation5->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation5->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation5->where('estimate_items.installation_gst!=','0');
 $DBinstallation5->where('estimate_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;
  
  //INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('estimate_items');
 $DBinstallation99->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation99->where('estimate_items.installation_gst!=','0');
 $DBinstallation99->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation99->where('estimate_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row=$installation_querys->row();
    ?>
    <tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $estimate_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($installation_rows->installation_gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>


<?php if($estimate_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('estimates');
 $DB6->where('estimates.id',$estimate_info->id);
 $DB6->where('estimates.gst!=','0');
 $DB6->where('estimates.deleted','0');
 
$query=$DB6->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3, $estimate_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>




      <tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal+$estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php $DB98 = $this->load->database('default', TRUE);
$DB98->select_sum("total");
 $DB98->from('estimate_items');
 $DB98->where('estimate_items.estimate_id',$estimate_info->id);
 $DB98->where('estimate_items.gst!=','0');
 $DB98->where('estimate_items.deleted','0');
 
$querys_same_taxvalue=$DB98->get();
$querys_same_taxvalue->result();


$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('estimates');
 $DB988->where('estimates.id',$estimate_info->id);
 $DB988->where('estimates.gst!=','0');
 $DB988->where('estimates.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();
//$totalss=0;
 foreach ($querys_same_taxvalue->result() as $rows_taxsame) { 
    echo to_currency($installation_total_row->installation_total+$rows_taxsame->total+$freight_total->amount, $estimate_total_summary->currency_symbol); }  ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3; echo to_currency($h, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $i= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+
          $estimate_total_summary->freight_tax3; echo to_currency($i, $estimate_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<br>
<br>
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
 $DB11->from('estimate_items');
 $DB11->where('estimate_items.estimate_id',$estimate_info->id);
  $DB11->where('estimate_items.gst!=','0');
 $DB11->where('estimate_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $estimate_total_summary->freight_tax_amount||$estimate_total_summary->installation_tax)  { ?>
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
 $DB3->from('estimate_items');
 $DB3->where('estimate_items.estimate_id',$estimate_info->id);
 $DB3->where('estimate_items.gst!=','0');
 $DB3->where('estimate_items.deleted','0');
 
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
<?php if($estimate_total_summary->installation_tax) { ?>
<?php 
$DBinstallation5 = $this->load->database('default', TRUE);
$DBinstallation5->select ("with_installation_gst,installation_hsn_code,installation_gst,installation_total");
 $DBinstallation5->from('estimate_items');
 $DBinstallation5->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation5->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation5->where('estimate_items.installation_gst!=','0');
 $DBinstallation5->where('estimate_items.deleted','0');
 
$installation_query=$DBinstallation5->get();
$installation_rows=$installation_query->row();
//$ret = $query->row();
//return $ret->campaign_id;
  
  //INSTALLATION TOTAL 
$DBinstallation99 = $this->load->database('default', TRUE);
$DBinstallation99->select_sum("installation_total");
 $DBinstallation99->from('estimate_items');
 $DBinstallation99->where('estimate_items.estimate_id',$estimate_info->id);
 $DBinstallation99->where('estimate_items.installation_gst!=','0');
 $DBinstallation99->where('estimate_items.with_installation_gst!=',"no");
 $DBinstallation99->where('estimate_items.deleted','0');
 
$installation_querys=$DBinstallation99->get();
$installation_total_row=$installation_querys->row();
    ?>
    <tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $installation_rows->installation_hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($installation_total_row->installation_total, $estimate_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($installation_rows->installation_gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php if($estimate_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('estimates');
 $DB6->where('estimates.id',$estimate_info->id);
 $DB6->where('estimates.gst!=','0');
 $DB6->where('estimates.deleted','0');
 
$query=$DB6->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3, $estimate_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax3, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>




      <tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal+$estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td-->
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php $DB98 = $this->load->database('default', TRUE);
$DB98->select_sum("total");
 $DB98->from('estimate_items');
 $DB98->where('estimate_items.estimate_id',$estimate_info->id);
 $DB98->where('estimate_items.gst!=','0');
 $DB98->where('estimate_items.deleted','0');
 
$querys_same_taxvalue=$DB98->get();
$querys_same_taxvalue->result();


$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('estimates');
 $DB988->where('estimates.id',$estimate_info->id);
 $DB988->where('estimates.gst!=','0');
 $DB988->where('estimates.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();
//$totalss=0;
 foreach ($querys_same_taxvalue->result() as $rows_taxsame) { 
    echo to_currency($installation_total_row->installation_total+$rows_taxsame->total+$freight_total->amount, $estimate_total_summary->currency_symbol); }  ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3; echo to_currency($h, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $i= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3; echo to_currency($i, $estimate_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<br>
<br>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>



<?php $taxamt=$estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3; 
if($taxamt>0){ ?>
<table style="width: 99%; color: #444;line-height: 15px">
    <tr style="font-weight: bold; background-color:white; color: black;  ">
        <th style="width: 100%; border-right: 1px solid #eee;font-weight: bold;font-size: 14px;"><?php
 

$number = $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->installation_tax+$estimate_total_summary->freight_tax3;

function convertToIndianCurrencysTaxss($number) {

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


 echo "Tax Amount(in words) : " . convertToIndianCurrencysTaxss($number);
?></th>
</tr>
</table> 
<?php } ?>
<span style="color:#444; line-height: 14px;">
           <?php echo get_setting("estimate_footer"); ?>
         </span>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payment-amount").change(function () {
            var value = $(this).val();
            $(".payment-amount-field").each(function () {
                $(this).val(value);
            });
        });
    });



</script>
