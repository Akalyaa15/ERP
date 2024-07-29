<div style=" margin: auto;">
    <?php
    $color = get_setting("voucher_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $style = get_setting("voucher_style");
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

<?php $users=$this->Users_model->get_one($estimate_info->accounts_handler);

$manager=$this->Users_model->get_one($estimate_info->line_manager);
 ?>
<?php
    foreach ($estimate_items as $item) {
        ?>
<h1 style="text-align: center; text-decoration: underline;color: #4baae3"><b>VOUCHER</b></h1>
<br><br>

<!--span style="font-size: 18px;">VOUCHER NUMBER : <?php echo $item->id; ?></span-->  <br>
<span style="font-size: 16px;">DATE  : <?php echo $item->expense_date; ?></span> <br><br><br>
<!--div style="line-height: 160%;font-size: 20px;">&nbsp &nbsp &nbsp &nbsp Paid to <b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->paid_to; ?>&nbsp&nbsp</b> BY Cash / Cheque Number<b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->cheque_no; ?>&nbsp&nbsp</b> Drawn On<b style="text-decoration: underline;"> &nbsp<?php echo $item->drawn_on; ?>&nbsp</b >a sum of Rs. <b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->amount; ?>&nbsp&nbsp</b> only towards Salary/Truck Exp/Unloading Chargers/Advance/.........</div-->
<span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">This voucher denotes the  <strong style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px ">  <?php echo $estimate_info->type_title ; ?> </strong> from <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "> <?php if($item->member_type=='om'||$item->member_type=='tm') {if($item->linked_user_name){echo $item->linked_user_name;}else{
        echo '...........................';
        }}
          elseif($item->member_type=='clients') {echo $item->client_name;}elseif($item->member_type=='vendors') {echo $item->vendor_name;}else{
echo $item->f_name." ".$item->l_name;
    } ?> </b > 
           <?php if ($item->member_type=='clients'||$item->member_type=='vendors') { ?>
         represented by <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php if($item->i_rep){echo $item->i_rep;}else{
        echo '...........................';
        } }?> </b><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">for the purpose of</span> <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php echo $item->category_title; ?> </b ><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">which has been dated on </span><b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "> <?php if ($estimate_info->payment_method_id==7||$estimate_info->payment_method_id==8) {
    if($item->drawn_on){echo $item->drawn_on;}else{
        echo '...........................';
        }
} else{if($item->expense_date){echo $item->expense_date;}else{
        echo '...........................';
        }} ?> </b><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important"> <?php if ($estimate_info->type_title=='Expense'||$estimate_info->type_title=='Loan') {
    echo 'and Paid to';
} else if($estimate_info->type_title=='Income'||$estimate_info->type_title=='Payment in Advance'){ echo 'and Received by';} ?> </span><b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php if($item->r_member_type=='om'||$item->r_member_type=='tm') {if($item->receiver_name){echo $item->receiver_name;}else{
        echo '...........................';
        }}
          elseif($item->r_member_type=='clients') {echo $item->receiver_client_name;}elseif($item->r_member_type=='vendors') {echo $item->receiver_vendor_name;}else{
echo $item->r_f_name." ".$item->r_l_name;
    } ?> </b > 
           <?php if ($item->r_member_type=='clients'||$item->r_member_type=='vendors') { ?>
         <span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">and represented by</span> <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php if($item->r_rep){echo $item->r_rep;}else{
        echo '...........................';
        } }?></b><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important"> through</span> <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "> <?php echo $estimate_info->title; ?> </b ><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">,a sum of     <?php echo $item->currency; ?>
 </span><b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php function convertToIndianCurrency($number) {
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
if($item->amount)
    {echo  convertToIndianCurrency($item->amount);} else{
        echo '...................................';
    }
  ?> </b><?php if($item->category_id==11 && $estimate_info->type_title=='Loan'){
 echo '.';
    }else{ ?><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important"> on behalf of the project </span><b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php if($item->project_title){echo $item->project_title;}else{echo '...................................';}  ?></b></b></span>
<?php }?><br><br><br>
<span style="font-size: 17px;">Amount :<b> <?php if($item->amount){ echo to_currency($item->amount,$item->currency_symbol); } ?></b></span><br><br>
<span style="font-size: 17px;"><?php if ($estimate_info->payment_method_id==7) { 
         echo 'Cheque Number :';}else if ($estimate_info->payment_method_id==8) { 
         echo 'DD Number :';}else if($estimate_info->payment_method_id==4||$estimate_info->payment_method_id==5||$estimate_info->payment_method_id==6){ 
           echo 'UTR Number :'; }?><b> <?php echo $item->cheque_no; ?></b></span><br><br>
<table >
<tbody>
<!--  <tr><td style="width:50%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><span style="font-size: 17px;">Issuer:<b> <?php if($item->member_type=='om'||$item->member_type=='tm') {echo $item->linked_user_name;}elseif($item->member_type=='others'){
echo $item->f_name." ".$item->l_name;
    }elseif ($item->member_type=='clients'||$item->member_type=='vendors') {
        echo $item->i_rep;
        } ?></b></span><br>
  
<span style="font-size: 17px;">Signature :<?php if(($item->member_type=='om'||$item->member_type=='tm')&&$item->signature) {?><img style="width: 130px; height: 50px; background-color:white;" src="<?php echo get_file_uri(get_setting("profile_image_path") . "signature/".$item->signature); ?>" > <?php }elseif (($item->member_type=='clients'||$item->member_type=='vendors')&&$item->i_rep_signature) {?>
<img style="width: 130px; height: 50px; background-color:white;" src="<?php echo get_file_uri(get_setting("profile_image_path") . "signature/".$item->i_rep_signature); ?>" >       <?php  } ?></span></td>
<td style="width:50%;font-size:16px;text-align:right;padding-top:10px;"><span style="font-size: 17px;">Manager :<b><?php echo $manager->first_name." ".$manager->last_name; ?> </b></span><br>
<span style="font-size: 17px;">Signature :<img style="width: 130px; height: 50px; background-color:white;" src="<?php if($users->signature) { echo get_file_uri(get_setting("profile_image_path") . "signature/".$manager->signature);} ?>" alt=""></span></td></tr> 
<br><br> -->
 <tr><td style="width:50%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Issuer's Name:<b><?php if($item->member_type=='om'||$item->member_type=='tm') {echo $item->linked_user_name;}elseif($item->member_type=='others'){
echo $item->f_name." ".$item->l_name;
    }elseif ($item->member_type=='clients'||$item->member_type=='vendors') {
        echo $item->i_rep;
        } ?></b></p><p><?php if(($item->member_type=='om'||$item->member_type=='tm')&&$item->signature) {?><img style="width: 130px; height: 50px; background-color:white;" src="<?php echo get_file_uri(get_setting("profile_image_path") . "signature/".$item->signature); ?>" > <?php }elseif (($item->member_type=='clients'||$item->member_type=='vendors')&&$item->i_rep_signature) {?>
<img style="width: 130px; height: 50px; background-color:white;" src="<?php echo get_file_uri(get_setting("profile_image_path") . "signature/".$item->i_rep_signature); ?>" >       <?php  } ?></p></td>
<td style="width:50%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Manager :<b><?php echo $manager->first_name." ".$manager->last_name; ?> </b></p><p><img style="width: 130px; height: 50px; background-color:white;" src="<?php if($manager->signature) { echo get_file_uri(get_setting("profile_image_path") . "signature/".$manager->signature);} ?>" alt=""></p></td></tr>
<tr><td style="width:50%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Signature</p></td>

<td style="width:50%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Authorised Signature</p></td></tr> 
<tr><td style="width:50%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Receiver's Name:<b><?php if($item->r_member_type=='om'||$item->r_member_type=='tm') {echo $item->receiver_name;}
          elseif($item->r_member_type=='others'){
echo $item->r_f_name." ".$item->r_l_name;
    }elseif ($item->r_member_type=='clients'||$item->r_member_type=='vendors') {
        echo $item->r_rep;
        } ?></b></p><p><?php if(($item->r_member_type=='om'||$item->r_member_type=='tm')&&$item->r_signature) {?><img style="width: 130px; height: 50px; background-color:white;" src="<?php echo get_file_uri(get_setting("profile_image_path") . "signature/".$item->r_signature); ?>" > <?php }elseif (($item->r_member_type=='clients'||$item->r_member_type=='vendors')&&$item->r_signature) {?>
<img style="width: 130px; height: 50px; background-color:white;" src="<?php echo get_file_uri(get_setting("profile_image_path") . "signature/".$item->r_rep_signature); ?>" >       <?php  } ?></p></td>
<td style="width:50%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;For Gemicates Technologies Pvt Ltd</p><p><img style="width: 130px; height: 50px; background-color:white;" src="<?php if($users->signature) { echo get_file_uri(get_setting("profile_image_path") . "signature/".$users->signature);} ?>" alt=""></p></td></tr>
<tr><td style="width:50%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Signature</p></td>

<td style="width:50%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Authorised Signature</p></td></tr></tbody></table><!--h4>GEMICATES TECHNOLOGIES</h4-->
 <?php } ?><br><br>
<!--p style="text-align:center;color:#2371bd;font-size: 15px">Registered Office : Gemicates Technologies Private Limited<br>CIN:U29253TN2014PTC098558&nbsp;<br>11/6,Sundareshwarer Koil Street,Saidapet, Chennai-600015.<br>Phone No:044-48534375/044-48527269 | www.gemicates.com | info@gemicates.com</p-->
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

<?php /* <div style=" margin: auto;">
    <?php
    $color = get_setting("voucher_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $style = get_setting("voucher_style");
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
<br><br>

<!--span style="font-size: 18px;">VOUCHER NUMBER : <?php echo $item->id; ?></span-->  <br>
<span style="font-size: 16px;">DATE  : <?php echo $item->expense_date; ?></span> <br><br><br>
<!--div style="line-height: 160%;font-size: 20px;">&nbsp &nbsp &nbsp &nbsp Paid to <b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->paid_to; ?>&nbsp&nbsp</b> BY Cash / Cheque Number<b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->cheque_no; ?>&nbsp&nbsp</b> Drawn On<b style="text-decoration: underline;"> &nbsp<?php echo $item->drawn_on; ?>&nbsp</b >a sum of Rs. <b style="text-decoration: underline;"> &nbsp&nbsp<?php echo $item->amount; ?>&nbsp&nbsp</b> only towards Salary/Truck Exp/Unloading Chargers/Advance/.........</div-->
<span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">This voucher denotes the  <strong style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px ">  <?php echo $estimate_info->type_title ; ?> </strong> from <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "> <?php if($item->member_type=='om'||$item->member_type=='tm') {echo $item->linked_user_name;}
          elseif($item->member_type=='clients'||$item->member_type=='vendors') {echo $item->linked_user_name;}else{
echo $item->f_name." ".$item->l_name;
    } ?> </b > 
           <?php if ($item->member_type=='clients'||$item->member_type=='vendors') { ?>
         represented by <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php echo 'member'; }?> </b><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">for the purpose of</span> <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "><?php echo $item->category_title; ?> </b ><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">which has been dated on </span><b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "> <?php if ($estimate_info->payment_method_id==7||$estimate_info->payment_method_id==8) {
    echo $item->drawn_on;
} else{echo $item->expense_date;} ?> </b><span style="font-size: 18px;color:#3c3e42;line-height: 32px !important">only towards</span> <b style="border-bottom: 1px dashed #999;
          text-decoration: underline;color: #0e0e0f;font-size: 20px "> <?php echo $item->category_title; ?> </b > .</span>
<br><br><br>
<span style="font-size: 18px;">Amount :<b> <?php echo to_currency($item->amount); ?></b></span><br><br>
<span style="font-size: 18px;">Concerned :<b> <?php if($item->member_type=='om'||$item->member_type=='tm') {echo $item->linked_user_name;}else{
echo $item->f_name." ".$item->l_name;
    } ?></b><span><br>
person</h4>  


<table style="width:115%;padding-top: 250px !important;">
  <tr>
    <th style="font-size: 18px">Receiver's Signature</th>
   <!--th style="font-size: 18px;color:white">Signature:</th--><th style="font-size: 18px;color:white">Signature:</th>
    <th style="font-size: 18px;color:">Authorized signature</th>
  </tr>
 
</table><!--h4>GEMICATES TECHNOLOGIES</h4-->
 <?php } ?><br><br>
<!--p style="text-align:center;color:#2371bd;font-size: 15px">Registered Office : Gemicates Technologies Private Limited<br>CIN:U29253TN2014PTC098558&nbsp;<br>11/6,Sundareshwarer Koil Street,Saidapet, Chennai-600015.<br>Phone No:044-48534375/044-48527269 | www.gemicates.com | info@gemicates.com</p-->
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

 */ ?>