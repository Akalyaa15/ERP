<?php 
$job_info = $this->Users_model->get_job_info($payslip_info->user_id);
$user_kyc_options = array("user_id"=> $payslip_info->user_id);
$has_kyc_info = $this->Kyc_info_model->get_details($user_kyc_options)->row();
$user_table = $this->Users_model->get_one($payslip_info->user_id);
$user_branch_code = $user_table->branch;
if($user_branch_code){ 
    $user_branch_options = array("branch_code"=> $user_branch_code);
    $get_user_branch_info = $this->Branches_model->get_details($user_branch_options)->row();
}

$month_days = $payslip_user_total_duration->num_of_days? $payslip_user_total_duration->num_of_days:"0";
$payable_days = $payslip_user_total_duration->num_of_days? $payslip_user_total_duration->num_of_days:"0";
$paid_holidays = round($payslip_user_total_duration->no_of_paidleave_ofiicial_sundays_holidays?$payslip_user_total_duration->no_of_paidleave_ofiicial_sundays_holidays:"-",2) ? round($payslip_user_total_duration->no_of_paidleave_ofiicial_sundays_holidays?$payslip_user_total_duration->no_of_paidleave_ofiicial_sundays_holidays:"-",2) : "0";

$week_off =$payslip_user_total_duration->num_of_sundays  ? $payslip_user_total_duration->num_of_sundays  : "0";
$present_days = $payslip_user_total_duration->total_days_excepted_sundays ? $payslip_user_total_duration->total_days_excepted_sundays : "0";
$lop_days = $payslip_user_total_duration->monthly_lop_days ? $payslip_user_total_duration->monthly_lop_days : "0";
$annual_leave_eligible = $payslip_info->payslip_casual_leave?$payslip_info->payslip_casual_leave:0;
$annual_leave_taken = $payslip_user_total_duration->total_annual_paid_leave+$payslip_user_total_duration->employee_paid_leave;


//remain elible leve 
$annaul_leave_available = 0;
if($annual_leave_eligible>=$annual_leave_taken){
     $annaul_leave_available = $annual_leave_eligible-$annual_leave_taken;
}

?>
<table style="color: #444; width: 100%;">
      <!-- <tr><td><?php  echo "Month Days" . ": " .$month_days ;?>
            
             
                <br /><?php echo "Payable Days" . ": " . $payable_days; ?>
          
                <br /><?php echo "Paid Holidays" . ": " .$paid_holidays; ?>
            
            
                <br /><?php echo "Weekly Off" . ": " .$week_off; ?>
            
          
                <br /><?php echo "Present" . ": " .$present_days; ?>
            
           
                <br /><?php echo lang("lop") . ": " .$lop_days; ?>
           
                <br /><?php echo "Annual Leave Eligible" . ": " . $annual_leave_eligible; ?>
                <br /><?php echo "Annual Leave Taken" . ": " .$annual_leave_taken; ?>
                <br /><?php echo "Annual Leave Available" . ": " .$annaul_leave_available; ?>
            
            </td>
    </tr> -->
    <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Month Days"; ?> </td> <td style="width: 30%;"><?php echo ": " . $month_days?></td></tr>
     <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Payable Days"; ?> </td> <td style="width: 30%;"><?php echo ": " .$payable_days;?></td></tr>
    <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Paid Holidays"; ?> </td> <td style="width: 30%;"><?php echo ": " . $paid_holidays;?></td></tr>
    <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Weekly Off"; ?> </td> <td style="width: 30%;"><?php echo ": " .$week_off;?></td></tr>
   <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Present"; ?> </td> <td style="width: 30%;"><?php echo ": " . ($present_days);?></td></tr>
   <tr> <td style="width: 70%; font-weight: bold;color:black;"><?php  echo lang("lop"); ?> </td> <td style="width: 30%;"><?php echo ": " . ($lop_days);?></td></tr>
    <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Annual Leave Eligible"; ?> </td> <td style="width: 30%;"><?php echo ": " . ($annual_leave_eligible);?></td></tr>
    <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Annual Leave Taken"; ?> </td> <td style="width: 30%;"><?php echo ": " . ($annual_leave_taken);?></td></tr>
     <tr><td style="width: 70%; font-weight: bold;color:black;"><?php  echo "Annual Leave Available"; ?> </td> <td style="width: 30%;"><?php echo ": " . ($annaul_leave_available);?></td></tr>
    
</table>