<table id="estimate-item-table" class="table display dataTable text-right strong table-responsive"; style="width: 100px;">
    <tr>
        <td style="width: 85px;"><?php echo lang("sub_total"); ?></td>
        <!--td style="width: 96px;"><?php echo to_currency($estimate_total_summary->estimate_subtotal, $estimate_total_summary->currency_symbol); ?></td-->
        <td style="width: 96px;"><?php echo to_currency($estimate_total_summary->estimate_quantity_subtotal, $estimate_total_summary->currency_symbol); ?></td>
        <td style="width: 80px;"><?php echo to_currency($estimate_total_summary->estimate_tax_subtotal, $estimate_total_summary->currency_symbol); ?> </td>
        <!--td style="width: 36px;"><?php echo to_currency($estimate_total_summary->estimate_net_subtotal, $estimate_total_summary->currency_symbol); ?></td-->
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->estimate_subtotal, $estimate_total_summary->currency_symbol); ?></td>
        <td style="width: 30px;"></td>

    </tr>

   
   <?php
    $freight_row = "<tr>
                        <td></td>
                        <td></td>
                        <td style='padding-top:13px;'>" . lang("freight") . "</td>
                        <td style='padding-top:13px;'>" . to_currency($estimate_total_summary->freight_rate_amount, $estimate_total_summary->currency_symbol) . "</td>
                        <td class='text-center option w10p'>" . modal_anchor(get_uri("estimates/freight_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-estimate_id" => $estimate_info->id, "title" => lang('edit_freight'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>";

   
        //when there is discount and type is before tax or no discount
        echo $freight_row;
    
    ?> 

   <!-- <tr>
        <td style="width: 85px;"><?php echo lang("igst_output"); ?></td>
        <td style="width: 96px;"><?php echo to_currency($invoice_total_summary->freight_tax, $invoice_total_summary->currency_symbol); ?></td>-->
   
     <!--tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->igst_total, $estimate_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr-->

<?php if($estimate_total_summary->installation_total) { ?>
<tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_total"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_total, $estimate_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
<?php } ?>


    <?php 
$company_setup_country = get_setting("company_setup_country");
if($company_setup_country ==$client_info->country) {?>    
    <?php if($estimate_total_summary->igst_total ||$estimate_info->gst!=="0") { ?>
<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>

    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$client_info->gstin_number_first_two_digits) {?>
<tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->igst_total+$estimate_total_summary->freight_tax_amount, $estimate_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
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
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->igst_total+$estimate_total_summary->freight_tax_amount, $estimate_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_igst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_tax, $estimate_total_summary->currency_symbol); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <?php } ?>
    <?php } ?>
    <?php } ?>

<?php if($estimate_total_summary->estimate_tax_subtotal||$estimate_info->gst!=="0") { ?>
<?php if (!empty($client_info->gstin_number_first_two_digits)) { ?>
    <?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$client_info->gstin_number_first_two_digits) {?>
<tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->freight_tax_amount/2, $estimate_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->freight_tax_amount/2, $estimate_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol ); ?></td>
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
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->freight_tax_amount/2, $estimate_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->freight_tax_amount/2, $estimate_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_cgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol ); ?></td>
         <td style="width: 36px;"></td>

    </tr>
    <tr>
        <td style="width: 85px;"></td>
        <td style="width: 96px;"></td>
        <td style="width: 80px;"><?php echo lang("installation_sgst_output"); ?> </td>
        <td style="width: 36px;"><?php echo to_currency($estimate_total_summary->installation_tax/2, $estimate_total_summary->currency_symbol ); ?></td>
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
            <td><?php echo to_currency($estimate_total_summary->estimate_net_subtotal_default, $estimate_total_summary->currency_symbol); ?></td>
             <td></td>
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td><?php echo lang("round_off"); ?></td>
        <td><?php $c= to_currency($estimate_total_summary->estimate_net_subtotal_default); $d=substr($c,-2); if($d>=50){
            $e=(100-$d);
            echo "(+)0.".$e;
            }elseif($d<50){
                echo "(-)0.".$d;
                } ?></td>
        <td></td> 
    </tr>
   <tr>
       <td></td>
       <td></td>
       <td><?php echo lang("net_total"); ?></td>
    <td><?php echo to_currency($estimate_total_summary->estimate_total, $estimate_total_summary->currency_symbol); ?></td>
        <td></td>
    </tr>
    
    </tr>
</table>