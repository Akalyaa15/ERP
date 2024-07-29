<?php //echo $payslip_info->id;
//echo $payslip_info->user_id;
$user_table = $this->Users_model->get_one($payslip_info->user_id);
//$user_branch_code = $user_table->branch;
$user_branch_code = $payslip_info->branch;
if($user_branch_code){ 
    $user_branch_options = array("branch_code"=> $user_branch_code);
    $get_user_branch_info = $this->Branches_model->get_details($user_branch_options)->row();
}
?>

<?php 

$options = array(
            "id" =>$get_user_branch_info->company_state,
                   );
        $company_state_data = $this->States_model->get_details($options)->row();
        ?>

        <?php 
$options_country = array(
            "numberCode" => $get_user_branch_info->company_setup_country,
                   );
      $branch_country_data = $this->Countries_model->get_details($options_country)->row();
        ?>
 <?php
$company_address = nl2br($get_user_branch_info->company_address);
$company_phone = $get_user_branch_info->company_phone;
$company_website = $get_user_branch_info->company_website;
$company_city = $get_user_branch_info->company_city;
$company_pincode = $get_user_branch_info->company_pincode;
$company_state = $company_state_data->title;
$company_country =  $branch_country_data->countryName;

?>

<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('payslip/payslip_parts/company_logo'); ?>
        </td>
        <!--td style="width: 20%;">
        </td-->
        <td style="width: 50%; vertical-align: top; text-align: right"><?php
            $data = array(
              // "job_info" => $job_info,
                "color" => $color,
                "payslip_info" => $payslip_info
            );
            $this->load->view('payslip/payslip_parts/payslip_info', $data);
            ?>
        </td>
    </tr>
    </table>
    <?php if($get_user_branch_info) { ?>
    <table style="color: #444; width: 100%;">
    <tr>
        <td><div style="font-weight: bold;color:black;"><strong><?php echo $get_user_branch_info->company_name; ?></strong></div>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;"><?php
    if ($company_address) {
        echo $company_address;
    }
    ?>
    <?php if ($company_city) { ?>
                <?php echo $company_city."-"; ?>
             <?php } ?>
            
            <?php if ($company_pincode) { ?>
                <?php echo $company_pincode.","; ?>
            <?php } ?>
             <?php if ($get_user_branch_info->company_state) { ?>
                <?php echo lang("state") . ": " . $company_state; echo",";  ?>
            <?php } ?>
            <?php if ($get_user_branch_info->company_setup_country) { ?>
        <!--<div style="line-height: 1px;"> </div>-->
         <?php echo lang("country"). ": ". $company_country;echo",";  ?> 
    <?php } ?>
    <?php if ($company_phone) { ?>
       <br /><?php echo lang("phone") . ": " . $company_phone; ?>
    <?php } ?>
    <?php if ($company_website) { ?>
         <br /><?php echo lang("website"); ?>: <a style="color:#666; text-decoration: none;" href="<?php echo $company_website; ?>"><?php echo $company_website; ?></a>
    <?php } ?>
</span>
        </td>
    </tr>
    </table>
<?php } ?>
 <h3 style="text-align: center">PAYSLIP FOR THE MONTH OF <?php $currentMonth =$payslip_info->payslip_date."first day of previous month";$last=Date('F', strtotime($currentMonth ));echo strtoupper($last); ?> <?php $currentMonth =$payslip_info->payslip_date."last month";$lastyear=Date('Y', strtotime($currentMonth ));echo strtoupper($lastyear); ?></h3>  

<table style="color: #444; width: 100%;font-size: 90%;border: 1px solid #666;">
    <tr>
        <td><?php
            $this->load->view('payslip/payslip_parts/payslip_from', $data);
            ?>
        </td>
         <td><?php
            $this->load->view('payslip/payslip_parts/payslip_leave', $data);
            ?>
        </td>
        
        <td><?php
            $this->load->view('payslip/payslip_parts/payslip_to', $data);
            ?>
        </td>
    </tr>
</table>

