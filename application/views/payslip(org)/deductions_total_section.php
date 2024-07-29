<table id="payslip-deductions-table" class="table display dataTable text-right strong table-responsive"> 
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
        <td><?php echo $rows1->title; ?></td>
        <td style="width: 120px;"><?php
         $a=$c * $rows1->percentage ;echo to_currency($a);$total+=$a; 
      ?></td>
        <td style="width: 100px;"> </td>

    </tr>
<?php endforeach;?>  
<?php /*
<!-- <tr>
        <td><?php echo lang("total_deductions"); ?></td>
        <td style="width: 120px;"><?php $g=$total+$deductions_total_summary->attendanceleaveamoumt_total+$deductions_total_summary->deductions_subtotal; echo to_currency($g, $deductions_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>

  <tr>
        <td><?php echo lang("total"); ?></td>
        <td style="width: 120px;"><?php $h=$payslip_total_summary->payslip_total-$g+$earningsadd_total_summary->earningsadd_subtotal; echo to_currency($h, $payslip_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr> --> */ ?>
    <tr>
        <td><?php echo lang("lop"); ?></td>
        <td style="width: 120px;"><?php $lop=
          $payslip_user_total_duration->deductions_amount-$payslip_user_total_duration->one_day_leave_amount; echo to_currency($lop, $deductions_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <!-- <tr>
        <td><?php /* echo lang("total_deductions"); ?></td>
        <td style="width: 120px;"><?php $j=$payslip_user_total_duration->one_day_leave_amount; $g=($total+$payslip_user_total_duration->deductions_amount+$deductions_total_summary->deductions_subtotal)-($j); echo to_currency($g, $deductions_total_summary->currency_symbol); */ ?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <tr>
        <td><?php  echo lang("total_deductions"); ?></td>
        <td style="width: 120px;"><?php  $g=($total+$lop+$deductions_total_summary->deductions_subtotal); echo to_currency($g, $deductions_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
<!-- <tr>
        <td><?php /* echo lang("over_time_amount"); ?></td>
        <td style="width: 120px;"><?php $i=$payslip_user_total_duration->over_time_amount; echo to_currency($i, $deductions_total_summary->currency_symbol); */ ?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <!-- <tr>
        <td><?php /* echo lang("one_day_leave_amount"); ?></td>
        <td style="width: 120px;"><?php $j=$payslip_user_total_duration->one_day_leave_amount; echo to_currency($j, $deductions_total_summary->currency_symbol); */ ?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <!-- <tr>
        <td><?php /* echo lang("total"); ?></td>
        <td style="width: 120px;"><?php $h=$payslip_total_summary->payslip_total-$g+($earningsadd_total_summary->earningsadd_subtotal+$i); echo to_currency($h, $payslip_total_summary->currency_symbol); */ ?></td>
        <td style="width: 100px;"></td>
    </tr> -->
    <tr>
        <td><?php  echo lang("total"); ?></td>
        <td style="width: 120px;"><?php $h=$payslip_total_summary->payslip_total-$g+($payslip_user_total_duration->earningsadd_total); echo to_currency($h, $payslip_total_summary->currency_symbol);  ?></td>
        <td style="width: 100px;"></td>
    </tr>

<!-- OT AMOUNT -->
<?php 

 $i= $payslip_user_total_duration->over_time_amount; 

?> 
    
    <?php

 $DB3 = $this->load->database('default', TRUE);

 
 $DB3->where('id', $payslip_info->id);
 $DB3->update('payslip', array('rate' => $g));
 
 
?>

<?php

 $DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $payslip_info->id);
 $DB4->update('payslip', array('total' => $h));
 
?>
<?php

 $DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $payslip_info->id);
 $DB4->update('payslip', array('over_time_amount' => $i));
 
?>
    
 
</table>

