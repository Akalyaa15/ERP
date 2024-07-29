<?php //echo $payslip_info->id;
//echo $payslip_info->user_id;
$job_info = $this->Users_model->get_job_info($payslip_info->user_id);
$user_kyc_options = array("user_id"=> $payslip_info->user_id);
$has_kyc_info = $this->Kyc_info_model->get_details($user_kyc_options)->row();
$user_department_options = array("department_code"=> $user_info->department);
$has_department_info = $this->Department_model->get_details($user_department_options)->row();
$user_Designation_options = array("designation_code"=> $user_info->department);
$has_designation_info = $this->Designation_model->get_details($user_Designation_options)->row();

$user_table = $this->Users_model->get_one($payslip_info->user_id);
$user_branch_code = $user_table->branch;
if($user_branch_code){ 
    $user_branch_options = array("branch_code"=> $user_branch_code);
    $get_user_branch_info = $this->Branches_model->get_details($user_branch_options)->row();
}

$department = $has_department_info->title? $has_department_info->title:"-";
$designation  = $has_designation_info->title  ? $has_designation_info->title :"-";
$employee_id = $user_info->employee_id?$user_info->employee_id:"-";
$dob = $user_info->dob? $user_info->dob:"-";
$panno = $has_kyc_info->panno?$has_kyc_info->panno:"-";
$epf_no = $has_kyc_info->epf_no?$has_kyc_info->epf_no:"-";
$uan_no  = $has_kyc_info->uan_no ? $has_kyc_info->uan_no:"-";
$status = $user_info->status?$user_info->status:"-";
$emp_name = ($user_info->first_name)." ".$user_info->last_name;
?>
<table style="color: #444; width: 100%;">
     <!--  <tr><td><?php  echo lang("employee_name") . ": " . ($user_info->first_name)." ".$user_info->last_name;?>
            
             
            
                <br /><?php echo lang("department") . ": " .$department; ?>
            
           
                <br /><?php echo lang("designation") . ": " .  $designation; ?>
           
            
                <br /><?php echo lang("employee_id") . ": " . $employee_id ; ?>
            
            
                <br /><?php echo lang("date_of_birth") . ": " . $dob; ?>
          
                <br /><?php echo lang("panno") . ": " . $panno; ?>
         
                <br /><?php echo lang("epf_no") . ": " . $epf_no; ?>
                <br /><?php echo lang("uan_no") . ": " . $uan_no; ?>
                <br /><?php echo lang("status") . ": " . $status;  ?>
            
            
            </td>
    </tr> -->
    <tr><td style="width: 37%; font-weight: bold;color:black;"><?php  echo "Emp Name"; ?> </td> <td style="width: 63%;font-size: 95%"><?php echo ": " .strtoupper($emp_name) ?></td></tr>
     <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo lang("designation"); ?> </td> <td style="width: 63%;"><?php echo ": " .$designation;?></td></tr>
    <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo lang("department"); ?> </td> <td style="width: 63%;"><?php echo ": " . $department;?></td></tr>
    <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo "Emp ID"; ?> </td> <td style="width: 63%;"><?php echo ": " .$employee_id;?></td></tr>
   <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo "DOB"; ?> </td> <td style="width: 63%;"><?php echo ": " . ($dob);?></td></tr>
   <tr> <td style="width: 37%;font-weight: bold;color:black;"><?php  echo lang("panno"); ?> </td> <td style="width: 63%;"><?php echo ": " . ($panno);?></td></tr>
    <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo lang("epf_no"); ?> </td> <td style="width: 63%;"><?php echo ": " . ($epf_no);?></td></tr>
    <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo lang("uan_no"); ?> </td> <td style="width: 63%;"><?php echo ": " . ($uan_no);?></td></tr>
     <tr><td style="width: 37%;font-weight: bold;color:black;"><?php  echo lang("status"); ?> </td> <td style="width: 63%;font-size: 95%;"><?php echo ": " . strtoupper($status);?></td></tr>
   
</table>