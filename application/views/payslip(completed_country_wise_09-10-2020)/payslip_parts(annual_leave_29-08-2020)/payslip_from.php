

<?php //echo $payslip_info->id;
//echo $payslip_info->user_id;
$user_table = $this->Users_model->get_one($payslip_info->user_id);
$user_branch_code = $user_table->branch;
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
      $branch_country = $this->Countries_model->get_details($options_country)->row();
        ?>

<?php if($get_user_branch_info){ ?>
 <table>

<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td  colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height:140px;">
<?php
$company_address = nl2br($get_user_branch_info->company_address);
$company_phone = $get_user_branch_info->company_phone;
$company_website = $get_user_branch_info->company_website;
?><div style="font-weight: bold;color:black;"><strong><?php echo $get_user_branch_info->company_name; ?></strong></div>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;"><?php
    if ($company_address) {
        echo $company_address;
    }
    ?>
    <?php if ($get_user_branch_info->company_city) { ?>
                <?php echo $get_user_branch_info->company_city."-"; ?>
            <?php } ?>
            
            <?php if ($get_user_branch_info->company_pincode) { ?>
                <?php echo $get_user_branch_info->company_pincode.","; ?>
            <?php } ?>
            <?php if ($get_user_branch_info->company_setup_country) { ?>
                <?php echo  $branch_country->countryName; ?>
            <?php } ?>
             <?php if ($get_user_branch_info->company_state) { ?>
                <?php echo lang("state") . ": " . $company_state_data->title; echo",";echo lang("code") . ": " . 
                $get_user_branch_info->company_gstin_number_first_two_digits;  ?>
            <?php } ?>
    <?php if ($company_phone) { ?>
        <div style="line-height: 1px;"> </div>
        <br /><?php echo lang("phone") . ": " . $company_phone; ?>
    <?php } ?>
    <?php if ($company_website) { ?>
        <!--div style="line-height: 2px;"> </div-->
        <br /><?php echo lang("website"); ?>: <a style="color:#666; text-decoration: none;" href="<?php echo $company_website; ?>"><?php echo $company_website; ?></a>
    <?php } ?>
</span>
</td></tr>
</table>
<?php } else { ?>

<table>
<tr  style="border: 1px solid #666;
  text-align: left;padding: 5px;">
    <td  colspan="2"; style="border: 1px solid #dddddd;color: #666;font-size:15px;
  text-align: left;padding: 5px;height:140px;">
<?php
$company_address = nl2br(get_setting("company_address"));
$company_phone = get_setting("company_phone");
$company_website = get_setting("company_website");
?><div style="font-weight: bold;color:black;"><strong><?php echo get_setting("company_name"); ?></strong></div>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;"><?php
    if ($company_address) {
        echo $company_address;
    }
    ?>
    <?php if ($company_phone) { ?>
        <div style="line-height: 1px;"> </div>
        <br /><?php echo lang("phone") . ": " . $company_phone; ?>
    <?php } ?>
    <?php if ($company_website) { ?>
        <!--div style="line-height: 2px;"> </div-->
        <br /><?php echo lang("website"); ?>: <a style="color:#666; text-decoration: none;" href="<?php echo $company_website; ?>"><?php echo $company_website; ?></a>
    <?php } ?>
</span>
</td></tr>
</table>

<?php } ?>