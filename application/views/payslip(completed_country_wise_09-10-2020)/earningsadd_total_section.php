<table id="payslip-earningsadd-table" class="table display dataTable text-right strong table-responsive">     
    <!-- <tr>
        <td><?php /* echo lang("total_earnings"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($earningsadd_total_summary->earningsadd_subtotal, $earningsadd_total_summary->currency_symbol); */ ?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <tr>
        <td><?php echo lang("total_earnings"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($payslip_user_total_duration->earningsadd_subtotal+$payslip_user_total_duration->monthly_total_earnings, $earningsadd_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"></td>
    </tr>
    <tr>
        <td><?php echo lang("over_time_amount"); ?></td>
        <td style="width: 120px;"><?php echo 
        to_currency($payslip_user_total_duration->over_time_amount_admin,$earningsadd_total_summary->currency_symbol);?></td>
        <td style="width: 100px;"> </td>
    </tr>
    <!-- <tr>
        <td><?php /* echo lang("total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($earningsadd_total_summary->earningsadd_total, $earningsadd_total_summary->currency_symbol); */ ?></td>
        <td style="width: 100px;"> </td>
    </tr> -->
    <tr>
        <td><?php echo lang("total"); ?></td>
        <td style="width: 120px;"><?php echo to_currency($payslip_user_total_duration->earningsadd_total_admin+$payslip_user_total_duration->monthly_total_earnings, $earningsadd_total_summary->currency_symbol); ?></td>
        <td style="width: 100px;"> </td>
    </tr>
</table>