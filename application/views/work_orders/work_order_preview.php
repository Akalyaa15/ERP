<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>
    <div class="invoice-preview">
        <?php if (
            isset($this->login_user->user_type) && 
            $this->login_user->user_type === "staff" && 
            isset($work_order_total_summary->balance_due) && 
            $work_order_total_summary->balance_due >= 1 && 
            isset($payment_methods) && 
            count($payment_methods) && 
            isset($vendor_info->disable_online_payment) && 
            !$vendor_info->disable_online_payment
        ) { ?>
            <div class="panel panel-default p15 no-border clearfix">
                <div class="inline-block strong pull-left pt5 pr15">
                    <?php echo lang("pay_work_order"); ?>:
                </div>
                <div class="mr15 strong pull-left general-form pull-left" style="width: 145px;">
                    <?php if (get_setting("allow_partial_invoices_payment_from_clients")) { ?>
                        <span style="background-color: #f6f8f9; display: inline-block; padding: 7px 2px 7px 10px;"><?php echo $work_order_total_summary->currency; ?></span>
                        <input type="text" id="payment-amount" value="<?php echo to_decimal_format($work_order_total_summary->balance_due); ?>" class="form-control inline-block" style="padding-left: 3px; width: 100px" />
                    <?php } else { ?>
                        <span class="pt5 inline-block">
                            <?php echo to_currency($work_order_total_summary->balance_due, $work_order_total_summary->currency . " "); ?>
                        </span>
                    <?php } ?>
                </div>

                <?php
if (isset($payment_methods) && is_array($payment_methods)) {
    foreach ($payment_methods as $payment_method) {
        // Debugging information
        // Uncomment the following line if you need to debug the payment_method array
        // echo '<pre>' . print_r($payment_method, true) . '</pre>';

        $method_type = get_array_value($payment_method, "type");

        // Debugging information
        // Uncomment the following line if you need to debug the method_type
        // echo 'Method Type: ' . htmlspecialchars($method_type) . '<br>';

        $pass_variables = array(
            "payment_method" => $payment_method,
            "balance_due" => isset($work_order_total_summary->balance_due) ? $work_order_total_summary->balance_due : 0,
            "currency" => isset($work_order_total_summary->currency) ? $work_order_total_summary->currency : '',
            "work_order_info" => isset($work_order_info) ? $work_order_info : array(),
            "work_order_id" => isset($work_order_id) ? $work_order_id : '',
            "paypal_url" => isset($paypal_url) ? $paypal_url : ''
        );

        $minimum_payment_amount = get_array_value($payment_method, "minimum_payment_amount", 0);

        // Debugging information
        // Uncomment the following line if you need to debug the minimum_payment_amount
        // echo 'Minimum Payment Amount: ' . htmlspecialchars($minimum_payment_amount) . '<br>';

        if (isset($work_order_total_summary->balance_due) && $work_order_total_summary->balance_due >= $minimum_payment_amount) {
            if ($method_type === "net_banking") {
                $this->load->view("work_orders/_net_banking_form", $pass_variables);
            }
        }
    }
} else {
    // Debugging informations
    echo 'Payment methods are not set or not an array.';
}
                    echo "<div class='text-center'>" . anchor("work_orders/view/" . $work_order_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
                    ?>
                </div>
            </div>
        <?php } elseif (isset($this->login_user->user_type) && $this->login_user->user_type === "vendor") {

            $DB11 = $this->load->database('default', TRUE);
            $DB11->select("hsn_code, hsn_description, gst");
            $DB11->from('work_order_items');
            $DB11->where('work_order_items.work_order_id', $work_order_info->id);
            $DB11->where('work_order_items.gst !=', '0');
            $DB11->where('work_order_items.deleted', '0');

            $queryhsns = $DB11->get();
            $hsngsts = $queryhsns->result();
            $hsn_sizes = sizeof($hsngsts);

            if ($hsn_sizes > 0 || $work_order_total_summary->freight_tax) {
                echo "<div class='text-center'>" . anchor("work_orders/download_pdf/" . $work_order_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
            } else {
                echo "<div class='text-center'>" . anchor("work_orders/download_work_order_without_gst_pdf/" . $work_order_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
            }
        } elseif (isset($show_close_preview) && $show_close_preview) {
            echo "<div class='text-center'>" . anchor("work_orders/view/" . $work_order_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>
        <div class="bg-white mt15 p30">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $work_order_status_label; ?></div>
            </div>

            <?php
            echo $work_order_preview;
            ?>
        </div>
    </div>
</div>
    <!--div class="invoice-preview"-->
        <?php /*
        if ($this->login_user->user_type === "vendor" && $work_order_info->status == "new") {
            ?>

            <div class = "panel panel-default  p15 no-border clearfix">

                <div class="mr15 strong pull-left">
                    <?php echo ajax_anchor(get_uri("work_orders/update_work_order_status/$work_order_info->id/accepted"), "<i class='fa fa fa-check-circle'></i> " . lang('mark_as_accepted'), array("class" => "btn btn-success mr15", "title" => lang('mark_as_accepted'), "data-reload-on-success" => "1")); ?>
                    <?php echo ajax_anchor(get_uri("work_orders/update_work_order_status/$work_order_info->id/declined"), "<i class='fa fa-times-circle-o'></i> " . lang('mark_as_rejected'), array("class" => "btn btn-danger mr15", "title" => lang('mark_as_rejected'), "data-reload-on-success" => "1")); ?>
                </div>
                <div class="pull-right">
                    <?php
                    echo "<div class='text-center'>" . anchor("work_orders/download_pdf/" . $work_order_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
                    ?>
                </div>

            </div>

            <?php
        } else if ($this->login_user->user_type === "vendor") {

            echo "<div class='text-center'>" . anchor("work_orders/download_pdf/" . $work_order_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        }
        if ($show_close_preview)
            echo "<div class='text-center'>" . anchor("work_orders/view/" . $work_order_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>"
            ?>

        <div class="bg-white mt15 p30">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $work_order_status_label; ?></div>
            </div>

            <?php
             echo $work_order_preview;
           */ ?>
 <?php 
$company_setup_country = get_setting("company_setup_country");
if($company_setup_country ==$vendor_info->country) {?>           
<?php if (!empty($vendor_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$vendor_info->gstin_number_first_two_digits) {?>

<?php 
$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('work_order_items');
 $DB10->where('work_order_items.work_order_id',$work_order_info->id);
  $DB10->where('work_order_items.gst!=','0');
 $DB10->where('work_order_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
if($hsn_size>0 || $work_order_total_summary->freight_tax_amount) {?>
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
 $DB2->from('work_order_items');
 $DB2->where('work_order_items.work_order_id',$work_order_info->id);
 $DB2->where('work_order_items.gst!=','0');
 $DB2->where('work_order_items.deleted','0');
 
$query=$DB2->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $b=$rows->total ;echo to_currency($b,$work_order_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php  $p=$rows->gst;$q=$p/2;echo($q."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $c= $q/100*$rows->total ;echo to_currency($c,$work_order_total_summary->currency_symbol);
         ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php echo ($q."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $d= $q/100*$rows->total ;echo to_currency($d,$work_order_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $e= $c+$d;echo to_currency($e,$work_order_total_summary->currency_symbol);
         ?></td>
            </tr>
            <?php } ?>
            <?php if($work_order_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('work_orders');
 $DB5->where('work_orders.id',$work_order_info->id);
 $DB5->where('work_orders.gst!=','0');
 $DB5->where('work_orders.deleted','0');
 
$query=$DB5->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax2, $work_order_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$rows->gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$rows->gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3, $work_order_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
   
<?php } ?>



      <tr>
        <td colspan="0" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($purchase_order_total_summary->estimate_subtotal+$purchase_order_total_summary->freight_tax2, $purchase_order_total_summary->currency_symbol); ?>
        </td-->
 
 <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
            $DB98 = $this->load->database('default', TRUE);
$DB98->select_sum("total");
 $DB98->from('work_order_items');
 $DB98->where('work_order_items.work_order_id',$work_order_info->id);
 $DB98->where('work_order_items.gst!=','0');
 $DB98->where('work_order_items.deleted','0');
 
$querys_same_taxvalue=$DB98->get();
$querys_same_taxvalue->result();

$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('work_orders');
 $DB988->where('work_orders.id',$work_order_info->id);
 $DB988->where('work_orders.gst!=','0');
 $DB988->where('work_orders.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();
//$totalss=0;
 foreach ($querys_same_taxvalue->result() as $rows_taxsame) {

            echo to_currency($rows_taxsame->total+$freight_total->amount, $work_order_total_summary->currency_symbol);
            } ?>
        </td>        
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($work_order_total_summary->estimate_tax_subtotal/2+$work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($work_order_total_summary->estimate_tax_subtotal/2+
            $work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $work_order_total_summary->estimate_tax_subtotal+$work_order_total_summary->freight_tax3; echo to_currency($h, $work_order_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } else if (empty($vendor_info->gstin_number_first_two_digits)) { ?>


<?php /* if (empty($client_info->gstin_number_first_two_digits)) { */ ?>
<?php 
$company_state = get_setting("company_state");
if($company_state ==$vendor_info->state) {?>

<?php 
$DB10 = $this->load->database('default', TRUE);
$DB10->select ("hsn_code,hsn_description,gst");
 $DB10->from('work_order_items');
 $DB10->where('work_order_items.work_order_id',$work_order_info->id);
  $DB10->where('work_order_items.gst!=','0');
 $DB10->where('work_order_items.deleted','0');
 
$queryhsn=$DB10->get();
$hsngst=$queryhsn->result();
$hsn_size= sizeof($hsngst);
if($hsn_size>0 || $work_order_total_summary->freight_tax_amount) {?>
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
 $DB2->from('work_order_items');
 $DB2->where('work_order_items.work_order_id',$work_order_info->id);
 $DB2->where('work_order_items.gst!=','0');
 $DB2->where('work_order_items.deleted','0');
 
$query=$DB2->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $b=$rows->total ;echo to_currency($b,$work_order_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php  $p=$rows->gst;$q=$p/2;echo($q."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $c= $q/100*$rows->total ;echo to_currency($c,$work_order_total_summary->currency_symbol);
         ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php echo ($q."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php
         $d= $q/100*$rows->total ;echo to_currency($d,$work_order_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $e= $c+$d;echo to_currency($e,$work_order_total_summary->currency_symbol);
         ?></td>
            </tr>
            <?php } ?>
            <?php if($work_order_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('work_orders');
 $DB5->where('work_orders.id',$work_order_info->id);
 $DB5->where('work_orders.gst!=','0');
 $DB5->where('work_orders.deleted','0');
 
$query=$DB5->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>

      <tr style="background-color: #f4f4f4; ">
            <td style="width: 16%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax2, $work_order_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"> <?php $y=$rows->gst/2; echo($y."%"); ?>
            </td>
            <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php $z=$rows->gst/2; echo($z."%"); ?></td>
         <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol); ?>
             </td>
        <td style="text-align: right; width: 14%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3, $work_order_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
   
<?php } ?>



      <tr>
        <td colspan="0" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($purchase_order_total_summary->estimate_subtotal+$purchase_order_total_summary->freight_tax2, $purchase_order_total_summary->currency_symbol); ?>
        </td-->
 
 <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
            $DB98 = $this->load->database('default', TRUE);
$DB98->select_sum("total");
 $DB98->from('work_order_items');
 $DB98->where('work_order_items.work_order_id',$work_order_info->id);
 $DB98->where('work_order_items.gst!=','0');
 $DB98->where('work_order_items.deleted','0');
 
$querys_same_taxvalue=$DB98->get();
$querys_same_taxvalue->result();

$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('work_orders');
 $DB988->where('work_orders.id',$work_order_info->id);
 $DB988->where('work_orders.gst!=','0');
 $DB988->where('work_orders.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();
//$totalss=0;
 foreach ($querys_same_taxvalue->result() as $rows_taxsame) {

            echo to_currency($rows_taxsame->total+$freight_total->amount, $work_order_total_summary->currency_symbol);
            } ?>
        </td>        
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($work_order_total_summary->estimate_tax_subtotal/2+$work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($work_order_total_summary->estimate_tax_subtotal/2+$work_order_total_summary->freight_tax3/2, $work_order_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $work_order_total_summary->estimate_tax_subtotal+$work_order_total_summary->freight_tax3; echo to_currency($h, $work_order_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<?php } ?>
<?php } ?>




<?php if (!empty($vendor_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_gstin_number_first_two_digits = get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$vendor_info->gstin_number_first_two_digits) {?>

<?php 
$DB11 = $this->load->database('default', TRUE);
$DB11->select ("hsn_code,hsn_description,gst");
 $DB11->from('work_order_items');
 $DB11->where('work_order_items.work_order_id',$work_order_info->id);
  $DB11->where('work_order_items.gst!=','0');
 $DB11->where('work_order_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $work_order_total_summary->freight_tax_amount) {?>
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
 $DB3->from('work_order_items');
 $DB3->where('work_order_items.work_order_id',$work_order_info->id);
 $DB3->where('work_order_items.gst!=','0');
 $DB3->where('work_order_items.deleted','0');
 
$query=$DB3->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->total,$work_order_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->tax_amount,$work_order_total_summary->currency_symbol);?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php $e=$rows->tax_amount ;echo to_currency($e,$work_order_total_summary->currency_symbol);
         ?></td>
            </tr>
   
<?php } ?>
<?php if($work_order_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('work_orders');
 $DB6->where('work_orders.id',$work_order_info->id);
 $DB6->where('work_orders.gst!=','0');
 $DB6->where('work_orders.deleted','0');
 
$query=$DB6->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax2, $work_order_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3, $work_order_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3, $work_order_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>




      <tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <!--td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
            echo to_currency($purchase_order_total_summary->estimate_subtotal+$purchase_order_total_summary->freight_tax2, $purchase_order_total_summary->currency_symbol); ?>
        </td-->
         <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">

  
            <?php 
            $DB99 = $this->load->database('default', TRUE);
$DB99->select_sum("total");
 $DB99->from('work_order_items');
 $DB99->where('work_order_items.work_order_id',$work_order_info->id);
 $DB99->where('work_order_items.gst!=','0');
 $DB99->where('work_order_items.deleted','0');
 
$querys=$DB99->get();
$querys->result();

$DB988 = $this->load->database('default', TRUE);
$DB988->select("amount");
 $DB988->from('work_orders');
 $DB988->where('work_orders.id',$work_order_info->id);
 $DB988->where('work_orders.gst!=','0');
 $DB988->where('work_orders.deleted','0');
 
$querys_same_taxvalues=$DB988->get();
$freight_total=$querys_same_taxvalues->row();


//$totalss=0;
 foreach ($querys->result() as $rowss) { 
            echo to_currency($rowss->total+$freight_total->amount, $work_order_total_summary->currency_symbol); 
            }
            ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $work_order_total_summary->estimate_tax_subtotal+$work_order_total_summary->freight_tax3; echo to_currency($h, $work_order_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $i= $work_order_total_summary->estimate_tax_subtotal+$work_order_total_summary->freight_tax3; echo to_currency($i, $work_order_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<br>
<br>
<?php } ?>
<?php } ?>
<?php } else if (empty($vendor_info->gstin_number_first_two_digits)) { ?>


<?php /* if (empty($client_info->gstin_number_first_two_digits)) { */ ?>
<?php 
$company_state = get_setting("company_state");
if($company_state !==$vendor_info->state) {?>

<?php 
$DB11 = $this->load->database('default', TRUE);
$DB11->select ("hsn_code,hsn_description,gst");
 $DB11->from('work_order_items');
 $DB11->where('work_order_items.work_order_id',$work_order_info->id);
  $DB11->where('work_order_items.gst!=','0');
 $DB11->where('work_order_items.deleted','0');
 
$queryhsns=$DB11->get();
$hsngsts=$queryhsns->result();
$hsn_sizes= sizeof($hsngsts);
if($hsn_sizes>0 || $work_order_total_summary->freight_tax_amount) {?>
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
 $DB3->from('work_order_items');
 $DB3->where('work_order_items.work_order_id',$work_order_info->id);
 $DB3->where('work_order_items.gst!=','0');
 $DB3->where('work_order_items.deleted','0');
 
$query=$DB3->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->total,$work_order_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($rows->tax_amount,$work_order_total_summary->currency_symbol);?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php $e=$rows->tax_amount ;echo to_currency($e,$work_order_total_summary->currency_symbol);
         ?></td>
            </tr>
   
<?php } ?>
<?php if($work_order_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('work_orders');
 $DB6->where('work_orders.id',$work_order_info->id);
 $DB6->where('work_orders.gst!=','0');
 $DB6->where('work_orders.deleted','0');
 
$query=$DB6->get();
$query->result();
 foreach ($query->result() as $rows) { 
    ?>
<tr style="background-color: #f4f4f4; ">
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $rows->hsn_code; ?>
            </td>
    <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax2, $work_order_total_summary->currency_symbol); ?>
        </td>
    <td style="text-align: right; width: 15%; border: 1px solid #fff;"> <?php  echo($rows->gst."%"); ?>
            </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3, $work_order_total_summary->currency_symbol); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($work_order_total_summary->freight_tax3, $work_order_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>
<tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
            if (isset($work_order_total_summary->currency_symbol)) {
                echo to_currency($total_with_freight, $work_order_total_summary->currency_symbol);
            } else {
                echo "Currency symbol not available";
            }
            ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
            if (isset($work_order_total_summary->currency_symbol)) {
                $h = $work_order_total_summary->estimate_tax_subtotal + $work_order_total_summary->freight_tax3;
                echo to_currency($h, $work_order_total_summary->currency_symbol);
            } else {
                echo "Currency symbol not available";
            }
            ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php 
            if (isset($work_order_total_summary->currency_symbol)) {
                $i = $work_order_total_summary->estimate_tax_subtotal + $work_order_total_summary->freight_tax3;
                echo to_currency($i, $work_order_total_summary->currency_symbol);
            } else {
                echo "Currency symbol not available";
            }
            ?>
        </td>
    </tr>

</table>
<br>
<br>
<?php } ?>
<?php } ?>

<?php } ?>
<?php 
$taxamt = $work_order_total_summary->estimate_tax_subtotal+$work_order_total_summary->freight_tax3;

if($taxamt>0) { ?>
<table style="width: 99%; color: #444;line-height: 15px">
    <tr style="font-weight: bold; background-color:white; color: black;  ">
        <th style="width: 100%; border-right: 1px solid #eee;font-weight: bold;font-size: 14px;"><?php
 

$number = $work_order_total_summary->estimate_tax_subtotal+$work_order_total_summary->freight_tax3;

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
<?php } ?>
<span style="color:#444; line-height: 14px;">
           <?php echo get_setting("purchase_order_footer"); ?>
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
