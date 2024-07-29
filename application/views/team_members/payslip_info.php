
<div class="panel clearfix <?php
if (isset($page_type) && $page_type === "full") {
    echo "m20";
}
?>">
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php
                if ($user_id === $this->login_user->id) {
                    echo lang("my_payslip");
                } else {
                    echo lang("payslip");
                }
                ?></h4></li>
        <li><a id="monthly-payslip-button"  role="presentation" class="active" href="javascript:;" data-target="#team_member-monthly-payslip"><?php echo lang("monthly"); ?></a></li>
         <li><a role="presentation" href="<?php echo_uri("team_members/yearly_payslip/"); ?>" data-target="#team_member-yearly-payslip"><?php echo lang('yearly'); ?></a></li>
        
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="team_member-monthly-payslip">
            <table id="monthly-payslip-table" class="display" cellspacing="0" width="100%">    
            </table>
            <script type="text/javascript">
                loadMembersPayslipTable = function (selector, type) {
                    var rangeDatepicker = [],
                            dateRangeType = "";

                    if (type === "custom_range") {
                        rangeDatepicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}];
                    } else {
                        dateRangeType = type;
                    }

                    $(selector).appTable({
                        source: '<?php echo_uri("payslip/list_data/"); ?>',
                        order: [[2, "desc"]],
                        dateRangeType: dateRangeType,
                        rangeDatepicker: rangeDatepicker,
                        filterParams: {user_id: "<?php echo $user_id; ?>"},
                        columns: [
                        {visible: false, searchable: false},
                        {title: '<?php echo lang("payslip_no") ?>'},
                {title: '<?php echo lang("payslip_date") ?>'},
              
                {title: '<?php echo lang("team_members") ?>'},
                {title: '<?php echo lang("earnings_amount") ?>', "class": "w10p text-right"},
               
                {title: '<?php echo lang("total_deductions") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("over_time_amount") ?>', "class": "w10p text-right"},
                 {title: '<?php echo lang("netsalary") ?>', "class": "w10p text-right"},
                 {visible: false, searchable: false},
                 {visible: false, searchable: false},
                 {visible: false, searchable: false},
              {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
                         ],
                        printColumns: [2, 3, 5, 6, 7],
                        xlsColumns: [2, 3, 5, 6, 7],
                        summation: [{column: 4, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}, {column: 5, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 6, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 7, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]

                    });
                };
                $(document).ready(function () {
                    $("#monthly-payslip-button").trigger("click");
                    loadMembersPayslipTable("#monthly-payslip-table", "monthly");
                });
            </script>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-yearly-payslip"></div>
    </div>
</div>