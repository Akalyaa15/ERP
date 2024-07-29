<table id="payslip-attendance-table" class="table display dataTable text-right strong table-responsive"> 
 <?php /* if ($attendance_total_summary->attendance_subtotal1) { ?>      
    <tr>
        <td><?php echo lang("total_leave"); ?></td>
        <td style="width: 120px;"><?php echo ($attendance_total_summary->attendance_subtotal1); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <?php } ?>
    
    <tr>
        <td><?php echo lang("earnings amount for one day"); ?></td>
        <td style="width: 120px;"><?php echo to_currency ($attendance_total_summary->earningscurrentdate_total,$attendance_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <?php if ($attendance_total_summary->attendanceleaveamoumt_total) { ?>    
    <tr>
        <td><?php echo lang("leave amount for month"); ?></td>
        <td style="width: 120px;"><?php echo to_currency ($attendance_total_summary->attendanceleaveamoumt_total,$attendance_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <?php }  */?>
    <tr>
        <td><?php echo lang("total_leave"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->attendance_subtotal;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("paid_leave"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->paid_leave;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("number_of_days_lop"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->number_of_days_lop;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("num_of_days"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->num_of_days;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("num_of_sundays"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->num_of_sundays;?></td>
        <td style="width: 100px;"> </td>
    </tr> 
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
        <td><?php echo lang("employee_working_days"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->total_user_days;?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <tr>
        <td><?php echo lang("employee_work_hours"); ?></td>
        <td style="width: 120px;"><?php $s= convert_seconds_to_time_format(abs(
        $payslip_user_total_duration->total_duration)); echo to_decimal_format(convert_time_string_to_decimal($s) );?></td>
        <td style="width: 100px;"> </td>
    </tr> 
    <tr>
        <td><?php echo lang("over_time"); ?></td>
        <td style="width: 120px;"><?php echo 
        $payslip_user_total_duration->over_time;?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("over_time_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->over_time_amount);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("lop_deductions_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->deductions_amount);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("monthly_salary"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->monthly_salary);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <tr>
        <td><?php echo lang("paid_leave_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->one_day_leave_amount);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <!-- <tr>
        <td><?php /* echo lang("monthly_earnings"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->total_leave_monthly_salary); */?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <tr>
        <td><?php echo lang("total_monthly_earnings"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->one_day_leave_amount+
        $payslip_user_total_duration->monthly_salary);?></td>
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