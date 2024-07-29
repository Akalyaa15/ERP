<table id="payslip-deductions-table" class="table display dataTable text-right strong table-responsive"> 
<?php 



/* country info details*/
$user_table = $this->Users_model->get_one($payslip_info->user_id);
//$user_country = $user_table->country;
$user_country =$payslip_info->branch;
if($user_country){
    $user_country_options = array("buid"=> $user_country);
    $get_user_country_info = $this->Branches_model->get_details($user_country_options)->row();
  }

if($get_user_country_info->id){
 $DB1 = $this->load->database('default', TRUE);
 $DB1->select ("title,percentage");
 $DB1->from('country_earnings');
 $DB1->where('country_earnings.status','active');
 $DB1->where('country_earnings.deleted','0');
 $DB1->where('country_earnings.country_id',$get_user_country_info->id);
 $DB1->where('country_earnings.key_name','basic_salary');
 $query1=$DB1->get();
 $query1->result();   
   
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('country_deductions');
 $DB2->where('country_deductions.status','active');
$DB2->where('country_deductions.country_id',$get_user_country_info->id);
 $DB2->where('country_deductions.deleted','0');
 
$query=$DB2->get();
$query->result();

}else{
 $DB1 = $this->load->database('default', TRUE);
 $DB1->select ("title,percentage");
 $DB1->from('earnings');
 $DB1->where('earnings.status','active');
 $DB1->where('earnings.deleted','0');
 $DB1->where('earnings.key_name','basic_salary');
 $query1=$DB1->get();
 $query1->result();   
   
$DB2 = $this->load->database('default', TRUE);
$DB2->select ("title,percentage");
 $DB2->from('deductions');
 $DB2->where('deductions.status','active');
 $DB2->where('deductions.deleted','0');
 
$query=$DB2->get();
$query->result();
}

 


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
         $a=$c * $rows1->percentage ;echo to_currency($a,$deductions_total_summary->currency_symbol);$total+=$a; 
      ?></td>
        <td style="width: 100px;"> </td>

    </tr>
<?php endforeach;?>  

<!-- lop amount -->  
    <tr>
        <td><?php echo lang("lop"); ?></td>
        <td style="width: 120px;"><?php $lop=
          $payslip_user_total_duration->deductions_amount;
if($lop<0){
  $lop = 0;
}
           echo to_currency($lop, $deductions_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>

<!-- deduction amount -->     
    <tr>
        <td><?php  echo lang("total_deductions"); ?></td>
        <td style="width: 120px;"><?php  $g=($total+$lop+$deductions_total_summary->deductions_subtotal);
if($g<0){
  $g = 0;
}
         echo to_currency($g, $deductions_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>

<!-- netsalary amount -->  
    <tr>
        <td><?php  echo lang("total"); ?></td>
        <td style="width: 120px;"><?php $h=$payslip_total_summary->payslip_total-$g+($payslip_user_total_duration->earningsadd_total_admin); 
if($h<0){
  $h = 0;
  }        
      echo to_currency($h, $payslip_total_summary->currency_symbol);  ?></td>
        <td style="width: 100px;"></td>
    </tr>

    <tr>
        <td><?php  echo lang("round_off"); ?></td>
        <td style="width: 120px;"><?php $c= to_currency($h); $d=substr($c,-2); if($d>=50){
            $e=(100-$d);
            echo "(+)0.".$e;
            }elseif($d<50){
                echo "(-)0.".$d;

                } ?></td>
        <td style="width: 100px;"></td>
    </tr>
    <tr>
        <td><?php  echo lang("net_salary"); ?></td>
        <td style="width: 120px;"><?php 
            
                echo to_currency(number_format(round($h), 2, ".", ""),$payslip_total_summary->currency_symbol);
                
                 ?></td>
        <td style="width: 100px;"></td>
    </tr>
      <tr>
        <td><?php  echo lang("paid"); ?></td>
        <td style="width: 120px;"><?php 
            
                echo to_currency(number_format(round($payslip_user_total_duration->payslip_total_paid), 2, ".", ""),$payslip_total_summary->currency_symbol);
                
                 ?></td>
        <td style="width: 100px;"></td>
    </tr>
    <tr>
        <td><?php  echo lang("due"); ?></td>
        <td style="width: 120px;"><?php 
            
                echo to_currency(number_format(round($h-$payslip_user_total_duration->payslip_total_paid), 2, ".", ""),$payslip_total_summary->currency_symbol);
                
                 ?></td>
        <td style="width: 100px;"></td>
    </tr>

<!-- OT AMOUNT -->
<?php 

 $i= $payslip_user_total_duration->over_time_amount; 
 //$user_net_salary=$payslip_total_summary->payslip_total-$g+($payslip_user_total_duration->earningsadd_total);
 $user_net_salary=number_format(round($payslip_total_summary->payslip_total-$g+($payslip_user_total_duration->earningsadd_total)), 2, ".", "");
 if($user_net_salary<0){
     $user_net_salary=0;
 }
?> 
    
    <?php

 $DB3 = $this->load->database('default', TRUE);

 
 $DB3->where('id', $payslip_info->id);
 $DB3->update('payslip', array('rate' => $g));
 
 
?>

<?php

 $DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $payslip_info->id);
 $DB4->update('payslip', array('total' => $user_net_salary));
 
?>
<?php

 $DB4 = $this->load->database('default', TRUE);

 $DB4->where('id', $payslip_info->id);
 $DB4->update('payslip', array('over_time_amount' => $i));
 
?>
    
 
</table>

