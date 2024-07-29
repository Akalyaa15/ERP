<div class="table-responsive">
    <table id="custom-attendance-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#custom-attendance-table").appTable({
            source: '<?php echo_uri("attendance/list_data/"); ?>',
            order: [[2, "desc"]],
            filterDropdown: [{name: "user_id", class: "w100", options: <?php echo $team_members_dropdown; ?>},{name: "userr_id", class: "w100", options: <?php echo $team_members_dropdowns; ?>}],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {title: "<?php echo lang("team_member"); ?>", "class": "w20p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("in_date"); ?>", "class": "w10p", iDataSort: 1},
                {title: "<?php echo lang("in_time"); ?>", "class": "w10p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("out_date"); ?>", "class": "w10p", iDataSort: 4},
                {title: "<?php echo lang("out_time"); ?>", "class": "w10p"},
                {title: "<?php echo lang("clockin_loc"); ?>", "class": "w15p"},
                {title: "<?php echo lang("clockout_loc"); ?>", "class": "w15p"},
                {title: "<?php echo lang("duration"); ?>", "class": "text-right"},
                {title: '<i class="fa fa-comment"></i>', "class": "text-center w50"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 2, 3, 5, 6, 7, 8, 9, 10],
            xlsColumns: [0, 2, 3, 5, 6, 7, 8, 9, 10],
            summation: [{column: 9, dataType: 'time'}]
        });
    });
</script>