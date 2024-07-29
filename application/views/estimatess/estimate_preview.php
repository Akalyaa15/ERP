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

            echo "<div class='text-center'>" . anchor("estimates/download_pdf/" . $estimate_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
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
$company_gstin_number_first_two_digits = get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits !==$client_info->gstin_number_first_two_digits) {?>
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
<?php if($estimate_total_summary->freight_tax) { ?>
<?php 

$DB6 = $this->load->database('default', TRUE);
$DB6->select ("hsn_code,gst,freight_amount");
 $DB6->from('estimates');
 $DB6->where('estimates.id',$estimate_info->id);
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
        <td style="text-align: right; width: 20%; border: 1px solid #fff;"><?php echo to_currency($estimate_total_summary->freight_tax, $estimate_total_summary->currency_symbol); ?></td>
            </tr>
   
<?php } ?>
<?php } ?>




      <tr>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal+$estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->freight_tax3; echo to_currency($h, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $i= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->freight_tax3; echo to_currency($i, $estimate_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php  } ?>
<?php 
$company_gstin_number_first_two_digits= get_setting("company_gstin_number_first_two_digits");
if($company_gstin_number_first_two_digits ==$client_info->gstin_number_first_two_digits) {?>

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
        <td style="text-align: right; width: 15%; border: 1px solid #fff;"><?php $e= $c+$d;echo to_currency($e);
         ?></td>
            </tr>
            <?php } ?>
            <?php if($estimate_total_summary->freight_tax) { ?>
<?php 
$DB5 = $this->load->database('default', TRUE);
$DB5->select ("hsn_code,gst,freight_amount");
 $DB5->from('estimates');
 $DB5->where('estimates.id',$estimate_info->id);
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
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal+$estimate_total_summary->freight_tax2, $estimate_total_summary->currency_symbol); ?>
        </td>
        <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->freight_tax3/2, $estimate_total_summary->currency_symbol ); ?>
        </td>
    <td colspan="1" style="text-align: right;color:#181919;"><?php echo lang("total"); ?></td>
     <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
            <?php echo to_currency($estimate_total_summary->estimate_tax_subtotal/2+$estimate_total_summary->freight_tax3/2, $_total_summary->currency_symbol ); ?>
        </td>
        
        <td style="text-align: right; width: 14%; border: 1px solid #fff; background-color: #f4f4f4;color:#181919;">
        <?php $h= $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->freight_tax3; echo to_currency($h, $estimate_total_summary->currency_symbol); ?>
        </td>

    </tr>

</table>
<?php } ?>
<table style="width: 99%; color: #444;line-height: 15px">
    <tr style="font-weight: bold; background-color:white; color: black;  ">
        <th style="width: 100%; border-right: 1px solid #eee;font-weight: bold;font-size: 14px;"><?php
 

$number = $estimate_total_summary->estimate_tax_subtotal+$estimate_total_summary->freight_tax3;

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
