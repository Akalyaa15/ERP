<!-- <table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
     <td colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height:140px;">
<div><b><?php echo lang("payslip_to"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong style="font-weight: bold;color:black;">Employee Details</strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($user_info->first_name) { ?>
        <div><?php  echo lang("employee_name") . ": " . ($user_info->first_name)." ".$user_info->last_name;?>
            <?php if ($user_info->employee_id) { ?>
                <br /><?php echo lang("employee_id") . ": " .  $user_info->employee_id; ?>
            <?php } ?>
             <?php if ($user_info->job_title) { ?>
                <br /><?php echo lang("designation") . ": " .  $user_info->job_title; ?>
            <?php } ?>
            <?php if ($user_info->email) { ?>
                <br /><?php echo lang("email") . ": " . $user_info->email; ?>
            <?php } ?>
             
            <?php if ($user_info->date_of_hire) { ?>
                <br /><?php echo lang("date_of_joining") . ": " . $user_info->date_of_hire; ?>
            <?php }  ?>
        </div>
    <?php } ?>
</span>
</td></tr>
</table> -->
<?php 
$job_info = $this->Users_model->get_job_info($payslip_info->user_id);
$user_kyc_options = array("user_id"=> $payslip_info->user_id);
$has_kyc_info = $this->Kyc_info_model->get_details($user_kyc_options)->row();
$user_table = $this->Users_model->get_one($payslip_info->user_id);
//$user_branch_code = $user_table->branch;
$user_branch_code = $payslip_info->branch;
if($user_branch_code){ 
    $user_branch_options = array("buid"=> $user_branch_code);
    $get_user_branch_info = $this->Branches_model->get_details($user_branch_options)->row();
}
if($job_info->date_of_hire =="0000-00-00"){
    $data_of_joining = "-";

}else{
  $data_of_joining = $job_info->date_of_hire;   
}
$currency_paid_in = $payslip_total_summary->currency ? $payslip_total_summary->currency : "-";
$bank_name = $has_kyc_info->bankname? $has_kyc_info->bankname : "-";
$account_number = $has_kyc_info->accountnumber? $has_kyc_info->accountnumber : "-";
$ifsc = $has_kyc_info->ifsc ? $has_kyc_info->ifsc : "-";
$micr = $has_kyc_info->micr ? $has_kyc_info->micr : "-";
$swift = $has_kyc_info->swift_code ? $has_kyc_info->swift_code : "-";
$iban = $has_kyc_info->iban_code ?  $has_kyc_info->iban_code : "-";


//acount xxxx set 
$acc_no = str_repeat('X', strlen($account_number) - strlen(substr($account_number, -4))) . substr($account_number, -4);
$ifsc_code = str_repeat('X', strlen($ifsc) - strlen(substr($ifsc, -3))) . substr($ifsc, -3);
$micr_code = str_repeat('X', strlen($micr) - strlen(substr($micr, -3))) . substr($micr, -3);
$swift_code = str_repeat('X', strlen($swift) - strlen(substr($swift, -3))) . substr($swift, -3);
$iban_code = str_repeat('X', strlen($iban) - strlen(substr($iban, -3))) . substr($iban, -3);


?>
<table style="color: #444; width: 100%;">
     
    <tr><td style="width: 49%;font-weight: bold;color:black;"><?php  echo lang("date_of_joining"); ?> </td> <td style="width: 51%;"><?php echo ": " . $data_of_joining;?></td></tr>
     <tr><td style="width: 49%; font-weight: bold;color:black;"><?php  echo "Currency Paid In "; ?> </td> <td style="width: 51%;"><?php echo ": " .$currency_paid_in;?></td></tr>
    <tr><td style="width: 49%; font-weight: bold;color:black;"><?php  echo "Payment Mode" ; ?> </td> <td style="width: 51%;"><?php echo ": " . "Bank" ;?></td></tr>
    <tr><td style="width: 49%; font-weight: bold;color:black;"><?php  echo "Bank Name"; ?> </td> <td style="width: 51%;"><?php echo ": " .$bank_name;?></td></tr>
   <tr><td style="width: 49%; font-weight: bold;color:black;"><?php  echo "Account No"; ?> </td> <td style="width: 51%;"><?php echo ": " . ($acc_no);?></td></tr>
   <tr> <td style="width: 49%; font-weight: bold;color:black;"><?php  echo "IFSC Code"; ?> </td> <td style="width: 51%;"><?php echo ": " . ($ifsc_code);?></td></tr>
    <tr><td style="width: 49%; font-weight: bold;color:black;"><?php  echo "MICR Code"; ?> </td> <td style="width: 51%;"><?php echo ": " . ($micr_code);?></td></tr>
    <tr><td style="width: 49%;font-weight: bold;color:black;"><?php  echo lang("swift_code"); ?> </td> <td style="width: 51%;"><?php echo ": " . ($swift_code);?></td></tr>
     <tr><td style="width: 49%; font-weight: bold;color:black;"><?php  echo "IBAN Code"; ?> </td> <td style="width: 51%;"><?php echo ": " . ($iban_code);?></td></tr>
    
</table>