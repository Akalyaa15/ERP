<table id="invoice-item-table" class="table display dataTable text-right strong table-responsive" style="width: 100px;">
    <tr>
        <td style="width: 85px;"><?php echo lang("sub_total"); ?></td>
        <td style="width: 96px;"><?php echo to_currency($invoice_total_summary->invoice_quantity_subtotal ?? 0, $invoice_total_summary->currency_symbol); ?></td>
        <td style="width: 80px;"><?php echo to_currency($invoice_total_summary->invoice_tax_subtotal ?? 0, $invoice_total_summary->currency_symbol); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->invoice_subtotal ?? 0, $invoice_total_summary->currency_symbol); ?></td>
        <td style="width: 30px;"></td>
    </tr>
<?php
    // Check if 'discount_total' exists in the object and is not null
    $discount_total = isset($invoice_total_summary->discount_total) ? $invoice_total_summary->discount_total : 0;

    // Generate the discount row if 'discount_total' is set and has a value
    if ($invoice_total_summary->invoice_subtotal && (!$discount_total || ($discount_total !== 0 && $invoice_total_summary->discount_type == "before_tax"))) {
        $discount_row = "<tr>
                            <td style='padding-top:13px;'>" . lang("discount") . "</td>
                            <td style='padding-top:13px;'>" . to_currency($discount_total, $invoice_total_summary->currency_symbol) . "</td>
                            <td class='text-center option w100'>" . modal_anchor(get_uri("invoices/discount_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-invoice_id" => $invoice_id, "title" => lang('edit_discount'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                        </tr>";

        echo $discount_row;
    }
    ?>
    <?php
    $freight_row = "<tr>
                        <td></td>
                        <td></td>
                        <td style='padding-top:13px;'>" . lang("freight") . "</td>
                        <td style='padding-top:13px;'>" . to_currency($invoice_total_summary->freight_rate_amount, $invoice_total_summary->currency_symbol) . "</td>
                        <td class='text-center option w10p'>" . modal_anchor(get_uri("invoices/freight_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-invoice_id" => $invoice_id, "title" => lang('edit_freight'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>";

    // Output the freight row
    echo $freight_row;
    ?>
    <!-- Optionally include IGST and other totals if needed -->
    <?php if($invoice_total_summary->installation_total) { ?>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_total"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_total, $invoice_total_summary->currency_symbol); ?></td>
        <td style="width: 36px;"></td>
    </tr>
    <?php } ?>

    <?php 
    // Check if $client_info is defined and not null
    if (isset($client_info) && !is_null($client_info)) {
        $company_setup_country = get_setting("company_setup_country");

        // Ensure that $client_info has the property 'country'
        if ($company_setup_country == $client_info->country) { 
            // Check if $invoice_total_summary and $invoice_info are defined
            if (isset($invoice_total_summary->igst_total) || (isset($invoice_info->gst) && $invoice_info->gst !== "0")) { 
                // Ensure that $client_info has the property 'gstin_number_first_two_digits'
                if (!empty($client_info->gstin_number_first_two_digits)) { 
                }
    ?>
<?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$client_info->gstin_number_first_two_digits) {?>
<tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->igst_total+$invoice_total_summary->freight_tax_amount, $invoice_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <?php } ?>

<?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>
<?php 
$company_state = get_setting("company_state");
if($company_state !==$client_info->state) {?>
<tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->igst_total+$invoice_total_summary->freight_tax_amount, $invoice_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_tax, $invoice_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <?php } ?>
    <?php } ?>
    <?php } ?>

<?php if($invoice_total_summary->invoice_tax_subtotal||$invoice_info->gst!=="0") { ?>
<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>
    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$client_info->gstin_number_first_two_digits) {?>
<tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax_amount/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax_amount/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>
    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
<?php } ?>
<?php } else if (empty($client_info->gstin_number_first_two_digits)) { ?>
    <?php 
$company_state = get_setting("company_state");
if($company_state ==$client_info->state) {?>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax_amount/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->invoice_tax_subtotal/2+$invoice_total_summary->freight_tax_amount/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>
    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($invoice_total_summary->installation_tax/2, $invoice_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
  <?php } ?>  
  <?php } ?>
  <?php } ?>

<?php } ?>
<tr>
            <td></td>
            <td></td>
            <td><?php echo lang("total"); ?></td>
            <td><?php echo to_currency($invoice_total_summary->invoice_net_subtotal_default, $invoice_total_summary->currency_symbol); ?></td>
             <td></td>
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td><?php echo lang("round_off"); ?></td>
        <td><?php $c= to_currency($invoice_total_summary->invoice_net_subtotal_default); $d=substr($c,-2); if($d>=50){
            $e=(100-$d);
            echo "(+)0.".$e;
            }elseif($d<50){
                echo "(-)0.".$d;
                } ?></td>
        <td></td> 
    </tr>
   

<!--<?php if (isset($invoice_total_summary->igst_before_tax_total) && $invoice_total_summary->igst_before_tax_total) { ?>
    <tr>
        <td style="width: 85px;"><?php echo lang("igst_output"); ?></td>
        <td style="width: 96px;"><?php echo to_currency($invoice_total_summary->igst_before_tax_total, $invoice_total_summary->currency_symbol); ?></td>
        <td style="width: 80px;"> </td>
        <td style="width: 36px;"></td>
    </tr>
<?php }?>
    <?php if ($invoice_total_summary->tax) { ?>
        <tr>
            <td><?php echo $invoice_total_summary->tax_name; ?></td>
            <td><?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td><?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <?php
    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "after_tax") {
        //when there is discount and type is after tax
        echo $discount_row;
    }
    ?>
    <?php if ($invoice_total_summary->balance_due>=1) {
        $balance_due=$invoice_total_summary->balance_due;
    }else{
         $balance_due=0;
    }
    ?>-->
    <?php if ($invoice_total_summary->total_paid) { ?>
        <tr>
            <td></td>
            <td></td>
            <td><?php echo lang("paid"); ?></td>
            <td><?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?></td>
            <td></td>
        </tr>
    <?php } ?>
    <!--<tr>
        <td><?php echo lang("total"); ?></td>
        <td><?php echo to_currency($balance_due, $invoice_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>-->
    <tr>
       <td></td>
       <td></td>
       <td><?php echo lang("balance_due"); ?></td>
    <td><?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
    <!--<tr>
        <td><?php echo lang("balance_due"); ?></td>
        <td><?php echo to_currency($invoice_total_summary->balances_due, $invoice_total_summary->currency_symbol); ?></td>
        <td></td>-->
    </tr>
</table>