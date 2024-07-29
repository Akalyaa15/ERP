<?php  /*
 $ip = get_real_ip(); 
;
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses"); print_r($ip); */ ?> <div id="page-content" class="p20 clearfix">

    <div class="panel panel-default">
         <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
       <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("a_ot_handler"); ?></h4></li>

            <li><a role="presentation" class="active" href="javascript:;" data-target="#daily-attendance"><?php echo lang("daily"); ?></a></li>
              <li><a role="presentation" href="<?php echo_uri("attendance/weekly_ot_handler/"); ?>" data-target="#weekly-attendance"><?php echo lang('weekly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/monthly_ot_handler/"); ?>" data-target="#monthly-attendance"><?php echo lang('monthly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/yearly_ot_handler/"); ?>" data-target="#yearly-attendance"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/summary_ot_handler_details/"); ?>" data-target="#summary-attendance-details"><?php echo lang('summary_details'); ?></a></li>
           

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
            <div role="tabpanel" class="tab-pane fade" id="weekly-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="monthly-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="summary-attendance-details"></div>
            
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-table").appTable({
            source: '<?php echo_uri("attendance/daily_details_list_ot_handler_data/"); ?>',
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
               // {title: "<?php echo lang("task_list"); ?>",  "bSortable": false,"class": "w30p text-center"},
                {title: "<?php echo lang("todo_list"); ?>",  "bSortable": false,"class": "w30p text-center"}
            ],
            printColumns: [ 1, 2, 3, 4],
            xlsColumns: [ 1, 2, 3, 4],
            summation: [{column: 2, dataType: 'time'}, {column: 3, dataType: 'number'}]
        });
    });
</script>    
