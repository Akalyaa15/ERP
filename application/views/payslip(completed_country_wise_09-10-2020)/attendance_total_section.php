<table id="payslip-attendance-table" class="table display dataTable text-right strong table-responsive"> 
<tr>
        <td><?php echo lang("total_leave"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->employee_total_leave?$payslip_user_total_duration->employee_total_leave:"-";?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <!-- <?php /* if($payslip_user_total_duration->employee_total_leave>$payslip_user_total_duration->total_user_lop_days)  {?>
    <tr>
        <td><?php echo lang("reduce_the_leave"); ?></td>
        <td style="width: 120px;color:red"><?php echo " Applied Leave Greater Than Taken Leave There is No Casual Leave"
        ;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <?php } */ ?> -->
<?php /* if($payslip_user_total_duration->employee_total_leave<=$payslip_user_total_duration->total_user_lop_days)  { */?>  
     <?php 
    $freight_row = "<tr>
                       
                        
                        <td style='padding-top:13px;'>" . lang("paid_leave") . "</td>
                        <td style='padding-top:13px;'>" . round($payslip_user_total_duration->employee_paid_leave,2) . "</td>
                        <td class='text-center option w10p'>" . modal_anchor(get_uri("payslip/paid_leave_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "data-post-payslip_id" => $payslip_info->id, "title" => lang('edit_paid_leave'))) . "<span class='p20'>&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>";

   
        //when there is discount and type is before tax or no discount
        echo $freight_row;
        ?> 
       <!--  <?php /*  }  */ ?> -->
        <!-- <tr>
        <td><?php /* echo lang("leave"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_user_lop_days; */ ?></td>
        <td style="width: 100px;"> </td>
    </tr>  -->
    <tr>
        <td><?php echo lang("total_number_of_paid_leave_for_this_month"); ?></td>
        <td style="width: 120px;"><?php echo 
        round($payslip_user_total_duration->no_of_paidleave_ofiicial_sundays_holidays?$payslip_user_total_duration->no_of_paidleave_ofiicial_sundays_holidays:"-",2);?></td>
        <td style="width: 100px;"> </td>
    </tr>
     <tr>
        <td><?php echo lang("number_of_days_lop"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->monthly_lop_days?$payslip_user_total_duration->monthly_lop_days:"-";?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <!-- <tr>
        <td><?php /* echo lang("number_of_days_lop"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->employee_number_of_days_lop?$payslip_user_total_duration->employee_number_of_days_lop:"-"; */?></td>
        <td style="width: 100px;"> </td>
    </tr> -->

    <tr>
        <td><?php echo lang("num_of_days"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->num_of_days?$payslip_user_total_duration->num_of_days:"-";?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("total_num_of_days_hours"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_month_days_company_hours;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("num_of_sundays"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->num_of_sundays;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("official_holidays"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->official_leave?$payslip_user_total_duration->official_leave:"-";?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("total_official_holidays"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_official_sunday_holidays?$payslip_user_total_duration->total_official_sunday_holidays:"-";?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <!-- <tr>
        <td><?php /* echo lang("official"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->event_date; */ ?></td>
        <td style="width: 100px;"> </td>
    </tr> -->  
    <tr>
        <td><?php echo lang("company_working_days"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_days;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("company_working_hours"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->company_hours;?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <tr>
        <td><?php echo lang("employee_work_hours"); ?></td>
        <td style="width: 120px;"><?php $s= convert_seconds_to_time_format(abs(
        $payslip_user_total_duration->total_duration)); echo to_decimal_format(convert_time_string_to_decimal($s) );?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <tr>
        <td><?php echo lang("employee_work_excepted_official_holidays_hours"); ?></td>
        <td style="width: 120px;"><?php $yy= convert_seconds_to_time_format(abs(
        $payslip_user_total_duration->total_duration_excepted_sundays)); echo to_decimal_format(convert_time_string_to_decimal($yy) );?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr style="font-weight: bold; background-color:#4baae3; color: #4baae3;  ">
    <td><?php echo "Overall Contributed Hours with Standard Time Period"."(".$payslip_user_total_duration->payslip_info_working_hours_view." Hours)" ;?></td>
    <td style="width: 120px;"><?php echo $payslip_user_total_duration->total_onedaycompany_duration_user;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
    <td><?php echo "Overall Contributed Hours Except Official Holidays with Standard Time Period"."(".$payslip_user_total_duration->payslip_info_working_hours_view." Hours)"; ?></td>
    <td style="width: 120px;"><?php echo $payslip_user_total_duration->excepted_oneday_user_contribute;?></td>
        <td style="width: 100px;"> </td>
    </tr>

    <tr>
     <tr>
    <td><?php echo "Overall Contributed Hours in Official Holidays with Standard Time Period"."(".$payslip_user_total_duration->payslip_info_working_hours_view." Hours)"; ?></td>
    <td style="width: 120px;"><?php echo $payslip_user_total_duration->only_holidays_oneday_user_contribute;?></td>
        <td style="width: 100px;"> </td>
    </tr>

    <tr>

        <td><?php echo lang("employee_contributed_days"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_user_days;?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <tr>
        <td><?php echo lang("employee_contributed_excepted_days"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_days_excepted_sundays;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("employee_work_official_holidays"); ?></td>
        <td style="width: 120px;"><?php  echo $payslip_user_total_duration->total_days_work_sundays;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr style="font-weight: bold; background-color:#4baae3; color: #4baae3;  ">
        <td><?php echo lang("monthly_per_hour_salary"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->employee_per_hour_salary,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <tr style="font-weight: bold; background-color:#4baae3; color: #4baae3;  ">
        <td><?php echo lang("monthly_per_one_day_salary"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->employee_per_one_day_salary,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("over_time"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->over_time_admin;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("over_time_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->over_time_amount_admin,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("lop_deductions_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->deductions_amount,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("monthly_salary"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->monthly_working_salary,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("paid_leave_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->one_day_leave_amount,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <!-- <tr>
        <td><?php /* echo lang("monthly_earnings"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->total_leave_monthly_salary); */?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <tr style="font-weight: bold; background-color:#4baae3; color: #4baae3;  ">
        <td><?php echo lang("total_monthly_earnings"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->monthly_total_earnings,$payslip_user_total_duration->currency_symbol);?></td>
        <td style="width: 100px;"></td>
    </tr>
    <!--<tr>
        <td><?php /* echo lang("total earnings amount"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($attendance_total_summary->earningsattendance_total,$attendance_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>-->
    <!--<tr>
        <td><?php echo lang("total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($attendance_total_summary->payslip_total, $attendance_total_summary->currency_symbol); */?></td>
        <td style="width: 100px;"> </td>
    </tr>-->

</table>