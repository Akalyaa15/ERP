<!-- <img src="<?php /* echo get_file_uri(get_setting("system_file_path") . get_setting("payslip_logo")); */?>"  />  -->
<?php //echo $payslip_info->id;
//echo $payslip_info->user_id;
$user_table = $this->Users_model->get_one($payslip_info->user_id);
$user_country = $user_table->country;
if($user_country){ 
    $user_country_options = array("numberCode"=> $user_country);
    $get_user_country_info = $this->Countries_model->get_details($user_country_options)->row();
}
 ?>

<?php if($user_country){ ?>
    <img src="<?php echo get_file_uri(get_general_file_path("country", $get_user_country_info->id) . $get_user_country_info->payslip_logo); ?>" />
 <?php } else { ?>
<img src="<?php  echo get_file_uri(get_setting("system_file_path") . get_setting("payslip_logo")); ?>"  />
 <?php } ?>





