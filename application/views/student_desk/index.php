<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
           <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
     <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("student_desk"); ?></h4></li>
            <li><a id="monthly-student_desk-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-student_desk"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("student_desk/yearly/"); ?>" data-target="#yearly-student_desk"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("student_desk/custom/"); ?>" data-target="#custom-student_desk"><?php echo lang('custom'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("student_desk/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_student_desk'), array("class" => "btn btn-default mb0", "title" => lang('add_student_desk'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-student_desk">
                <div class="table-responsive">
                    <table id="monthly-student_desk-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-student_desk"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-student_desk"></div>
            
        </div>
    </div>
</div>
<script type="text/javascript">
    loadStudentdeskTable = function (selector, dateRange) {
        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }

        $(selector).appTable({
            source: '<?php echo_uri("student_desk/list_data") ?>',
            dateRangeType: dateRange,
            order: [[0, "asc"]],
            rangeDatepicker: customDatePicker,
            columns: [
                //{visible: false, searchable: false},
                {title: '<?php echo lang("name") ?>'},
                {title: '<?php echo lang("registration_date") ?>'},
                {title: '<?php echo lang("college_name") ?>'},
                {title: '<?php echo lang("department") ?>'},
                {title: '<?php echo lang("vap_category") ?>'},
                {title: '<?php echo lang("program_title") ?>'},
                {title: '<?php echo lang("duration_of_course") ?>', "class": "text-center"},
               
                
                {title: '<?php echo lang("timing") ?>', "class": "text-center"},
                {title: '<?php echo lang("phone") ?>', "class": "text-right"},
                {title: '<?php echo lang("email") ?>', "class": "text-left"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            xlsColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            
        });
    };

    $(document).ready(function () {
        $("#monthly-student_desk-button").trigger("click");
         loadStudentdeskTable("#monthly-student_desk-table", "monthly");
    });
</script>


<!-- <div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('student_desk'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("student_desk/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_student_desk'), array("class" => "btn btn-default", "title" => lang('add_student_desk'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="student_desk-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#student_desk-table").appTable({
            source: '<?php echo_uri("student_desk/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("registration_date"); ?>'},
                {title: '<?php echo lang("college_name"); ?>'},
                 {title: '<?php echo lang("department"); ?>'},
                 {title: '<?php echo lang("vap_category"); ?>'},
                 {title: '<?php echo lang("program_title"); ?>'},
                  {title: '<?php echo lang("duration_of_course"); ?>'},
                   {title: '<?php echo lang("timing"); ?>'},
                 {title: '<?php echo lang("phone"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script> -->