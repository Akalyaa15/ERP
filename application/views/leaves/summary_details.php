<div class="table-responsive">
    <table id="leave-summary-details-table" class="display" cellspacing="0"width="100%">
    </table>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $("#leave-summary-details-table").appTable({
            source: '<?php echo_uri("leaves/summary_details_list_data") ?>',
            filterDropdown: [
               
                {name: "applicant_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
                 {name: "leave_type_id", class: "w200", options: <?php echo $leave_types_dropdown; ?>}
            ],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {title: '<?php echo lang("applicant") ?>', "class": "w30p"},
                {title: '<?php echo lang("leave_type") ?>'},
                {title: '<?php echo lang("total_leave") ?>',"class": "w20p text-right"}
            ],
            printColumns: [0, 1],
            xlsColumns: [0, 1],
            summation: [{column: 2, dataType: 'number'}]
        });
    }
    );
</script>