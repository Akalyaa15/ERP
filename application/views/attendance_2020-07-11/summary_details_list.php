<div class="table-responsive">
    <table id="attendance-summary-details-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-summary-details-table").appTable({
            source: '<?php echo_uri("attendance/summary_details_list_data/"); ?>',
            order: [[0, "asc"]],
             filterDropdown: [{name: "user_id", class: "w100", options: <?php echo $team_members_dropdown; ?>},{name: "userr_id", class: "w100", options: <?php echo $team_members_dropdowns; ?>}],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang("team_member"); ?>", "iDataSort": 0, "class": "w10p"},
                {title: "<?php echo lang("date"); ?>", "bSortable": false, "class": "w10p"},
                {title: "<?php echo lang("duration"); ?>", "bSortable": false, "class": "w5p text-right"},
                {title: "<?php echo lang("hours"); ?>", "bSortable": false, "class": "w5p text-right"},
                {title: "<?php echo lang("clockin_loc"); ?>",  "bSortable": false,"class": "w15p text-right"},
                {title: "<?php echo lang("clockout_loc"); ?>",  "bSortable": false,"class": "w15p text-right"},
                 {title: "<?php echo lang("task_list"); ?>",  "bSortable": false,"class": "w30p text-center"},
                {title: "<?php echo lang("todo_list"); ?>",  "bSortable": false,"class": "w30p text-center"}
            ],
            printColumns: [ 1, 2, 3, 4],
            xlsColumns: [ 1, 2, 3, 4],
            summation: [{column: 3, dataType: 'time'}, {column: 4, dataType: 'number'}]
        });
    });
</script>