<div style=" margin: auto;">
    <?php
    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $style = get_setting("invoice_style");
    ?>
    <?php
    $data = array(
        "estimate_items" => $estimate_items,
        "color" => $color,
        "estimate_info" => $estimate_info
    );
    if ($style === "style_2") {
        $this->load->view('voucher/voucher_parts/header_style_2.php', $data);
    } else {
        $this->load->view('voucher/voucher_parts/header_style_1.php', $data);
    }
    ?>
</div>
<?php
    foreach ($estimate_items as $item) {
        ?>
<h1 style="text-align: center; text-decoration: underline;color: #4baae3">VOUCHER</h1>
<br>
<!--span style="font-size: 18px;">VOUCHER NUMBER : <?php echo $item->id; ?></span-->  <br>
<span style="font-size: 18px;">DATE OF EXPENSES  : <?php echo $item->expense_date; ?></span> <br><br><br>
<!--div style="line-height: 160%;font-size: 20px;">&nbsp &nbsp &nbsp &nbsp Paid to <b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->paid_to; ?>&nbsp&nbsp</b> BY Cash / Cheque Number<b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->cheque_no; ?>&nbsp&nbsp</b> Drawn On<b style="text-decoration: underline;"> &nbsp<?php echo $item->drawn_on; ?>&nbsp</b >a sum of Rs. <b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->amount; ?>&nbsp&nbsp</b> only towards Salary/Truck Exp/Unloading Chargers/Advance/.........</div-->
<span style="font-size: 21px;line-height: 23px;color:#3c3e42">Paid to <strong style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 23px ">  <?php echo $item->paid_to ; ?> </strong> for  project <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 23px ">  <?php echo $item->project_title; ?> </b ><br><br>BY Cash / Cheque Number <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 23px "> <?php if ($item->cheque_no) {
    echo $item->cheque_no;
} else{echo 'CASH';} ?> </b> Drawn On <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 23px ">  <?php echo $item->drawn_on; ?> </b ><br><br>a sum of Rupees <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 23px "> <?php function convertToIndianCurrency($number) {
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
    $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
    return ($Rupees ?   $Rupees : '') . $paise . " ";
}
 
echo  convertToIndianCurrency($item->amount);  ?> </b>only<br><br> towards <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 23px "> <?php echo $item->category_title; ?> </b > .</span>
<br><br>
<h4>Amount :<b> <?php echo to_currency($item->amount); ?></b></h4>
<h4>Concerned :<b> <?php echo $item->linked_user_name; ?></b><br>
person</h4>  

<table style="width:104%">
  <tr>
    <th style="font-size: 18px">Signature:</th>
   <th style="font-size: 18px;color:white">Signature:</th><th style="font-size: 18px;color:white">Signature:</th>
    <th style="font-size: 18px;color:">Authority signature</th>
  </tr>
 
</table><h4>GEMICATES TECHNOLOGIES</h4>
 <?php } ?>

<!--table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 70%; border-right: 1px solid #eee;"> <?php echo lang("item"); ?> </th>
        <th style="text-align: center;  width: 30%; border-right: 1px solid #eee;"> <?php echo lang("quantity"); ?></th>
        
    </tr>
    <?php
    foreach ($estimate_items as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 70%; border: 1px solid #fff; padding: 10px;"><?php echo $item->id; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
            </td>
            <td style="text-align: center; width: 30%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
           
        </tr>
    <?php } ?>
    
    
    
</table-->


<!--div style="border-top: 2px solid #f2f2f2; color:#444;">
    <div><?php echo nl2br($estimate_info->note); ?></div>
</div-->

<!--div style="margin-top: 15px;">
    <?php echo get_setting("estimate_footer"); ?>
</div-->

