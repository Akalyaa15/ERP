
<!--div style=" margin: auto;"-->
    <?php
    $color = get_setting("payslip_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $style = get_setting("payslip_style");
    ?>
    <?php
    $data = array(
        
        "color" => $color,
        "payslip_info" => $payslip_info
    );
    if ($style === "style_2") {
        $this->load->view('payslip/payslip_parts/header_style_2.php', $data);
    } else {
        $this->load->view('payslip/payslip_parts/header_style_1.php', $data);
    }
    ?>
    <h3 style="text-align: center">PAYSLIP FOR THE MONTH OF <?php $currentMonth =$payslip_info->payslip_date."first day of previous month";$last=Date('F', strtotime($currentMonth ));echo strtoupper($last); ?> <?php $currentMonth =$payslip_info->payslip_date."last month";$lastyear=Date('Y', strtotime($currentMonth ));echo strtoupper($lastyear); ?></h3>
<!--h3 style="text-align: center">PAYSLIP FOR THE MONTH OF <?php /* $currentMonth =$payslip_info->payslip_date."last month";$last=Date('F', strtotime($currentMonth ));echo strtoupper($last); ?> <?php $currentMonth =$payslip_info->payslip_date."last month";$lastyear=Date('Y', strtotime($currentMonth ));echo strtoupper($lastyear); */?></h3-->
    <!--/div-->
<table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 50%; border-right: 1px solid #eee;"> <?php echo lang("earnings"); ?> </th>
       <th style="text-align: right;  width: 50%; border-right: 1px solid #eee;"> <?php echo lang("amount"); ?></th>
        
    </tr>
    
   <!-- <tr>
        <td colspan="1" style="text-align: left; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php echo lang("basic_salary"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($earnings_total_summary->earnings_subtotal, $earnings_total_summary->currency_symbol); ?>
        </td>
    </tr> -->

   
    <?php
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('earnings');
 $DB2->where('earnings.status','active');
 $DB2->where('earnings.deleted','0');
 $DB2 ->where('earnings.title','Basic Salary');
 $DB2 ->or_where('earnings.title','BasicSalary');
$query=$DB2->get();
$query->result();

$DB1 = $this->load->database('default', TRUE);
$DB1->select ("title,percentage");
 $DB1->from('earnings');
 $DB1->where('earnings.status','active');
 $DB1->where('earnings.deleted','0');
 $DB1 ->where('earnings.title !=','Basic Salary');
 $DB1 ->where('earnings.title !=','BasicSalary');
$query1=$DB1->get();
$query1->result();

 $salary=$earnings_total_summary->earnings_subtotal/100;
 
  
   foreach ($query->result() as $rows):?>

     <tr>
        <td colspan="1" style="text-align: left; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php echo $rows->title; ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php
         $b=$salary * $rows->percentage ;echo to_currency($b) ;
         ?></td>
        

    </tr>
<?php endforeach;?> 


<?php 
$c = $b/100; 
$total=0;
foreach ($query1->result() as $rows1):?>
     <tr>
        <td colspan="1" style="text-align: left; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php echo $rows1->title; ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php
         $a=$c * $rows1->percentage ;echo to_currency($a);$total+=$a; 
      ?></td>
        

    </tr>
<?php endforeach;?> 
<?php
    foreach ($payslip_earningsadd as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 50%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
    
                
            </td>
            
            <td style="text-align: right; width: 50%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            
        </tr>
    <?php } ?>
<!--<tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("total_earnings"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $f= $b+$total+$earningsadd_total_summary->earningsadd_subtotal;echo to_currency($f); ?></td>
        
    </tr>-->
    <tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("total_earnings"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $f= $earnings_total_summary->earnings_subtotal+$earningsadd_total_summary->earningsadd_subtotal;echo to_currency($f); ?></td>
        
    </tr>

   
    
</table>
<?php /* if ($payslip_attendance){?>
<h4>Attendance Details:</h4>
<table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 30%; border-right: 1px solid #eee;"> <?php echo lang("title"); ?> </th>
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("start_date"); ?> </th>
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("end_date"); ?> </th>
       <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("no_of_days"); ?></th>
       
    </tr>
    <?php } ?>
    <?php
    foreach ($payslip_attendance as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
        <td style="width: 30%; border: 1px solid #fff; padding: 10px;"><?php echo $item->leave_type_id_name; ?>
    </td>
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $item->leave_start_user_name; ?>
    
                
            </td>
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $item->leave_end_user_name; ?>
    
                
            </td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo($item->attendance_user_name); ?></td>
            
        </tr>
    <?php } ?>
    <?php if ($attendance_total_summary->attendance_subtotal1) { ?>
     <tr>
        <td colspan="3" style="text-align: right;"><?php echo lang("total_leave"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo ($attendance_total_summary->attendance_subtotal1); ?>
        </td>
    </tr>
    <?php }  */?>
<!--/table-->
<h4>Deductions Amount:</h4>
<table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 50%; border-right: 1px solid #eee;"> <?php echo lang("deductions"); ?> </th>
       <th style="text-align: right;  width: 50%; border-right: 1px solid #eee;"> <?php echo lang("amount"); ?></th>
       
    </tr>
    <?php
    foreach ($payslip_deductions as $item) {
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 50%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
    
                
            </td>
            
            <td style="text-align: right; width: 50%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            
        </tr>
    <?php } ?>
    <?php 



 $DB1 = $this->load->database('default', TRUE);
 $DB1->select ("title,percentage");
 $DB1->from('earnings');
 $DB1->where('earnings.status','active');
 $DB1->where('earnings.deleted','0');
 $DB1 ->where('earnings.title','Basic Salary ');
 $DB1 ->or_where('earnings.title ','BasicSalary ');
 $query1=$DB1->get();
 $query1->result();   
   
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('deductions');
 $DB2->where('deductions.status','active');
 $DB2->where('deductions.deleted','0');
 
$query=$DB2->get();
$query->result();

$salary=$earnings_total_summary->earnings_subtotal/100;
 
  
   foreach ($query1->result() as $rows):?>
    <?php
    $b=$salary * $rows->percentage ;
         ?>
<?php endforeach;?>

<?php 
$c = $b/100; 
$total=0;
foreach ($query->result() as $rows1):?>
     <tr>
        <td colspan="1" style="text-align: left; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php echo $rows1->title; ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php
         $a=$c * $rows1->percentage ;echo to_currency($a);$total+=$a; 
      ?></td>
        

    </tr>
<?php endforeach;?> 
<tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("total_deductions"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $k=$payslip_user_total_duration->one_day_leave_amount; $g=($total+$payslip_user_total_duration->deductions_amount+$deductions_total_summary->deductions_subtotal)-($k); echo to_currency($g, $deductions_total_summary->currency_symbol); ?></td>
        
    </tr>
    <?php if($payslip_user_total_duration->over_time_amount){ ?>
<tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("over_time_amount"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $j=$payslip_user_total_duration->over_time_amount; echo to_currency($j, $deductions_total_summary->currency_symbol);?></td>
        
    </tr>
     <?php } ?>
    <?php if($payslip_user_total_duration->one_day_leave_amount!=0){ ?>
    <tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("paid_leave_amount"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $k=$payslip_user_total_duration->one_day_leave_amount; echo to_currency($k, $deductions_total_summary->currency_symbol);?></td>
        
    </tr>
    <?php } ?>

    <tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("net.salary"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php 
        $h=$earnings_total_summary->earnings_subtotal-$g ;$i=($h+$earningsadd_total_summary->earningsadd_subtotal+$j); echo to_currency($i, $payslip_total_summary->currency_symbol); ?></td>
       
    </tr>
 <?php /*if ($attendance_total_summary->attendanceleaveamoumt_total) { ?>
    <tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("total_leave_amount"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency ($attendance_total_summary->attendanceleaveamoumt_total,$attendance_total_summary->currency_symbol);?>
        </td>
    </tr>
      <?php }  */?>


<!-- <tr> <?php /*
        <td colspan="1" style="text-align: right;"><?php echo lang("total_deductions"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $g=$total+$deductions_total_summary->attendanceleaveamoumt_total+$deductions_total_summary->deductions_subtotal; echo to_currency($g, $deductions_total_summary->currency_symbol); ?></td>
        
    </tr>


    <tr>
        <td colspan="1" style="text-align: right;"><?php echo lang("net.salary"); ?></td>
        <td style="text-align: right; width: 50%; border: 1px solid #fff; background-color: #f4f4f4;"><?php $h=$earnings_total_summary->earnings_subtotal-$g ;$i=$h+$earningsadd_total_summary->earningsadd_subtotal; echo to_currency($i, $payslip_total_summary->currency_symbol); ?></td>
       */ ?>
    </tr> --> 


    
<?php

 $DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $payslip_info->id);
 $DB4->update('payslip', array('total' => $i));
 
?>
    <?php

 $DB3 = $this->load->database('default', TRUE);

 
 $DB3->where('id', $payslip_info->id);
 $DB3->update('payslip', array('rate' => $g));
 
 
?>
<?php

 $DB3 = $this->load->database('default', TRUE);

 
 $DB3->where('id', $payslip_info->id);
 $DB3->update('payslip', array('over_time_amount' => $j));
 
 
?>
</table>
<br>
<br>

<?php
 
$number = $i;

function convertToIndianCurrencys($number) {

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
    $paise = ($decimal) ? "And  " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10]) ." Paise"  : '';
    return ($Rupees ? $company_currency ." " . $Rupees : '') . $paise . " Only";

}


echo "Net Salary(in words) : " . convertToIndianCurrencys($number);



?>


<!--div style="margin-top: 15px;">
    <?php echo get_setting("payslip_footer"); ?>
</div-->


<?php /*
<!--table style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 30%; border-right: 1px solid #eee;"> <?php echo lang("title"); ?> </th>
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("start_date"); ?> </th>
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("end_date"); ?> </th>
       <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("no_of_days"); ?></th>
       
    </tr>
<?php 
$DB14 = $this->load->database('default', TRUE);
$DB14->select ("*");
 $DB14->from('payslip_attendance');
 $DB14->where('payslip_attendance.payslip_id',$payslip_info->id);
 $DB14->join('leave_applications', 'leave_applications.applicant_id = payslip_attendance.user_id');
$DB14->where('leave_applications.deleted',0);
 
$query=$DB14->get();
$sy=$query->result(); 
foreach ($query->result() as $sy) { 
    ?>
    <tr style="background-color: #f4f4f4; ">
        <td style="width: 30%; border: 1px solid #fff; padding: 10px;"><?php echo $sy->payslip_id; ?>
    </td>
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $sy->start_date; ?>
    
                
            </td>
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $sy->end_date; ?>
    
                
            </td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo($sy->total_days); ?></td>
            
        </tr>
    <?php } */?>
    