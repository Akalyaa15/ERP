 <div class="panel panel-default">
            
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("timesheets_ot"); ?></h4></li>

            <li><a id="timesheet_ot-details-button" role="presentation" class="active" href="javascript:;" data-target="#daily-attendance"><?php echo lang("daily"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/timesheets_monthly_ot_handler/". $project_id); ?>" data-target="#monthly-attendance"><?php echo lang('monthly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/timesheets_yearly_ot_handler/". $project_id); ?>" data-target="#yearly-attendance"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/timesheets_summary_ot_handler_details/" . $project_id); ?>" data-target="#summary-attendance-details"><?php echo lang('summary_details'); ?></a></li>
           

            <div class="tab-title clearfix no-border">
               
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="daily-attendance">
                <div class="table-responsive">
                    <table id="attendance-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="monthly-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="summary-attendance-details"></div>
            
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#timesheet_ot-details-button").trigger("click");
        $("#attendance-table").appTable({
            source: '<?php echo_uri("projects/daily_details_list_timesheets_ot_handler_data/"); ?>',
            filterParams: {project_id: "<?php echo $project_id; ?>"},
            order: [[0, "asc"]],
             //filterDropdown: [{name: "user_id", class: "w100", options: <?php echo $team_members_dropdown; ?>},{name: "userr_id", class: "w100", options: <?php echo $team_members_dropdowns; ?>}],
             filterDropdown: [{name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}],
            dateRangeType: "daily",
            columns: [
                //{visible: false, searchable: false},
                {title: "<?php echo lang("team_member"); ?>", "iDataSort": 0, "class": "w20p"},
                {title: "<?php echo lang("date"); ?>", "bSortable": false, "class": "w10p"},
                {title: "<?php echo lang("duration"); ?>", "bSortable": false, "class": "w5p text-right"},
                {title: "<?php echo lang("hours"); ?>", "bSortable": false, "class": "w5p text-right"},
                {title: "<?php echo lang("projects"); ?>",  "bSortable": false,"class": "w15p text-right"},
                {title: "<?php echo lang("task_list"); ?>",  "bSortable": false,"class": "w15p text-right"},
                {title: "<?php echo lang("job_done"); ?>",  "bSortable": false,"class": "w30p text-center"}
            ],
            printColumns: [ 1, 2, 3, 4],
            xlsColumns: [ 1, 2, 3, 4],
            summation: [{column: 2, dataType: 'time'}, {column: 3, dataType: 'number'}]
        });
    });
</script>  

