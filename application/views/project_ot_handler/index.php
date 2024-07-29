<?php  /*
 $ip = get_real_ip(); 
;
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses"); print_r($ip); */ 

/* $options = array(
            //"start_date" =>format_to_date($data->start_date, false),
            "user_id" => "5"      
        ); 
           $list_data = $this->Timesheets_model->project_ot_handler_get_details($options)->result();

$task_array = array();
$project_array = array();
foreach ($list_data as $group) {
                
        $task_array[] = $group->task_id;
        $project_array[] = $group->project_id;
                    
                    
                }
        $array_unique = array_unique($task_array);
        $project_unique = array_unique($project_array);
$task_id_list = "";
        foreach ($array_unique as  $value) {
            # code...
$task_id_list .= $value.",";

        }
        $project_id_list = "";
        foreach ($project_unique as  $value) {
            # code...
$project_id_list .= $value.",";

        }
$tasklist_arry_con = explode(",",$task_id_list);
$project_arry_con = explode(",",$project_id_list);
        echo print_r($array_unique)."</br>";
        echo $task_id_list."</br>";
        echo print_r($project_array)."</br>";
        echo $project_id_list."</br>";
        echo print_r($tasklist_arry_con)."</br>";
        echo print_r($project_arry_con)."</br>";
        echo print_r($project_unique)."</br>";

*/
?> <div id="page-content" class="p20 clearfix">

    <div class="panel panel-default">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("t_ot_handler"); ?></h4></li>

            <li><a role="presentation" class="active" href="javascript:;" data-target="#daily-attendance"><?php echo lang("daily"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/monthly_ot_handler/"); ?>" data-target="#monthly-attendance"><?php echo lang('monthly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/yearly_ot_handler/"); ?>" data-target="#yearly-attendance"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/summary_ot_handler_details/"); ?>" data-target="#summary-attendance-details"><?php echo lang('summary_details'); ?></a></li>
           

            <div class="tab-title clearfix no-border">
                <!-- <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("attendance/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_attendance'), array("class" => "btn btn-default", "title" => lang('add_attendance'))); ?>
                </div> -->
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
        $("#attendance-table").appTable({
            source: '<?php echo_uri("projects/daily_details_list_ot_handler_data/"); ?>',
            order: [[0, "asc"]],
             filterDropdown: [{name: "user_id", class: "w100", options: <?php echo $team_members_dropdown; ?>},{name: "userr_id", class: "w100", options: <?php echo $team_members_dropdowns; ?>}],
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

<!-- <script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-table").appTable({
            source: '<?php echo_uri("projects/daily_details_list_ot_handler_data/"); ?>',
            order: [[0, "asc"]],
             filterDropdown: [{name: "user_id", class: "w100", options: <?php echo $team_members_dropdown; ?>},{name: "userr_id", class: "w100", options: <?php echo $team_members_dropdowns; ?>}],
            dateRangeType: "daily",
            columns: [
                //{visible: false, searchable: false},
                {title: "<?php echo lang("team_member"); ?>", "iDataSort": 0, "class": "w20p"},
                {title: "<?php echo lang("date"); ?>", "bSortable": false, "class": "w10p"},
                {title: "<?php echo lang("duration"); ?>", "bSortable": false, "class": "w5p text-right"},
                {title: "<?php echo lang("hours"); ?>", "bSortable": false, "class": "w5p text-right"},
                {title: "<?php echo lang("clockin_loc"); ?>",  "bSortable": false,"class": "w15p text-right"},
                {title: "<?php echo lang("clockout_loc"); ?>",  "bSortable": false,"class": "w15p text-right"},
                {title: "<?php echo lang("todo_list"); ?>",  "bSortable": false,"class": "w30p text-center"}
            ],
            printColumns: [ 1, 2, 3, 4],
            xlsColumns: [ 1, 2, 3, 4],
            summation: [{column: 2, dataType: 'time'}, {column: 3, dataType: 'number'}]
        });
    });
</script>  
<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-table").appTable({
            source: '<?php echo_uri("projects/daily_details_list_ot_handler_data/") ?>',
            filterDropdown: [
                {name: "user_id", class: "w100", options: <?php echo isset($members_dropdown) ? $members_dropdown : '[]' ?>},
                {name: "user_id", class: "w100", options: <?php echo isset($rm_members_dropdown) ? $rm_members_dropdown : '[]' ?>},
                {name: "project_id", class: "w200", options: <?php echo isset($projects_dropdown) ? $projects_dropdown : '[]' ?>, dependency: ["client_id"], dataSource: '<?php echo_uri("projects/get_projects_of_selected_client_for_filter") ?>', selfDependency: true},
                <?php if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "client")) { ?>
                {name: "client_id", class: "w200", options: <?php echo isset($clients_dropdown) ? $clients_dropdown : '[]' ?>, dependent: ["project_id"]},
<?php } ?>
            ],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}],
            columns: [
                {title: "<?php echo lang('member') ?>"},
                {title: "<?php echo lang('project') ?>"},
                {title: "<?php echo lang('task') ?>"},
                {title: "<?php echo lang('duration') ?>", "class": "text-right"},
                {title: "<?php echo lang('total') ?>"},
                {title: "<?php echo lang('note') ?>"}
            ],
            printColumns: [0, 1, 2, 4, 6, 7],
            xlsColumns: [0, 1, 2, 4, 6, 7],
            summation: [{column: 3, dataType: 'time'}, {column: 4, dataType: 'number'}]
        });
    });
</script>
