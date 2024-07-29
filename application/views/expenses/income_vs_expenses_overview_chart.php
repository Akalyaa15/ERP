<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("chart"); ?>
                        <div class="pull-right">
                            <div id="overview-yearly-chart-date-range-selector">
                            </div>
                        </div>
                    </div>
    <div class="panel-body ">
        <div style="padding-left:35px;">
            <div id="income-vs-expenses-overview-chart" style="width:100%; height: 350px;"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
     var initIncomeExpenseOverviewChart = function (income, expense,purchase_order_payment,work_order_payment) {
        var dataset = [
            {
                data: income,
                color: "rgba(0, 179, 147, 1)",
                lines: {
                    show: true,
                    fill: 0.2
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                label: "<?php echo lang('income'); ?>",
                data: income,
                color: "rgba(0, 179, 147, 1)",
                lines: {
                    show: false
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#fff",
                    lineWidth: 1
                },
                shadowSize: 0,
                curvedLines: {
                    apply: false
                }
            },
            {
                data: expense,
                color: "#F06C71",
                lines: {
                    show: true,
                    fill: 0.2
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                label: "<?php echo lang('expense'); ?>",
                data: expense,
                color: "#F06C71",
                lines: {
                    show: false
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#fff",
                    lineWidth: 1
                },

                shadowSize: 0,
                curvedLines: {
                    apply: false
                }
            },
            {
                data: purchase_order_payment,
                color: "#f4a941",
                lines: {
                    show: true,
                    fill: 0.2
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                label: "<?php echo lang('purchase_order_payments'); ?>",
                data: purchase_order_payment,
                color: "#f4a941",
                lines: {
                    show: false
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#fff",
                    lineWidth: 1
                },
                
                shadowSize: 0,
                curvedLines: {
                    apply: false
                }
            },
            {
                data: work_order_payment,
                color: "#d43480",
                lines: {
                    show: true,
                    fill: 0.2
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                label: "<?php echo lang('work_order_payments'); ?>",
                data: work_order_payment,
                color: "#d43480",
                lines: {
                    show: false
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#fff",
                    lineWidth: 1
                },
                
                shadowSize: 0,
                curvedLines: {
                    apply: false
                }
            }


        ];
        $.plot("#income-vs-expenses-overview-chart", dataset, {
            series: {
                curvedLines: {
                    apply: true,
                    active: true,
                    monotonicFit: true
                }
            },
            legend: {
                show: true
            },
            yaxis: {
                min: 0
            },
            xaxis: {
                ticks: [[1, "<?php echo lang('short_january'); ?>"], [2, "<?php echo lang('short_february'); ?>"], [3, "<?php echo lang('short_march'); ?>"], [4, "<?php echo lang('short_april'); ?>"], [5, "<?php echo lang('short_may'); ?>"], [6, "<?php echo lang('short_june'); ?>"], [7, "<?php echo lang('short_july'); ?>"], [8, "<?php echo lang('short_august'); ?>"], [9, "<?php echo lang('short_september'); ?>"], [10, "<?php echo lang('short_october'); ?>"], [11, "<?php echo lang('short_november'); ?>"], [12, "<?php echo lang('short_december'); ?>"]]
            },
            grid: {
                color: "#bbb",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: '#FFF'
            },
            tooltip: {
                show: true,
                content: function (x, y, z) {
                    if (x) {
                        return "%s: " + toCurrency(z);
                    } else {
                        return false;
                    }
                },
                defaultTheme: false
            }
        });
    };
    var prepareExpensesOverviewFlotChart = function (data) {
        appLoader.show();
        $.ajax({
            url: "<?php echo_uri("expenses/income_vs_expenses_overview_chart_data") ?>",
            data: data,
            cache: false,
            type: 'POST',
            dataType: "json",
            success: function (response) {
                appLoader.hide();
                initIncomeExpenseOverviewChart(response.income, response.expenses,response.purchase_order_payment,response.work_order_payment);
            }
        });
    };
    $(document).ready(function () {
        

        $("#overview-yearly-chart-date-range-selector").appDateRange({
            dateRangeType: "yearly",
            onChange: function (dateRange) {
                prepareExpensesOverviewFlotChart(dateRange);
            },
            onInit: function (dateRange) {
                prepareExpensesOverviewFlotChart(dateRange);
            }
        });
    });
</script>