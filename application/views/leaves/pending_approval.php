<div class="table-responsive">
    <table id="pending-approval-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#pending-approval-table").appTable({
            source: '<?php echo_uri("leaves/pending_approval_list_data") ?>',
            filterDropdown: [
                {name: "leave_type_id", class: "w200", options: <?php echo $leave_types_dropdown; ?>},
                {name: "applicant_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
                {name: "line_managers", class: "w200", options: <?php echo $line_manager_dropdown; ?>}
            ],
            columns: [
                {title: '<?php echo lang("applicant") ?>', "class": "w20p"},
                {title: '<?php echo lang("leave_type") ?>'},
                {title: '<?php echo lang("date") ?>', "class": "w10p"},
                {title: '<?php echo lang("duration") ?>', "class": "w15"},
                {title: '<?php echo lang("status") ?>', "class": "w10"},
                {title: '<?php echo lang("apply_date") ?>', "class": "w20p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>

