<div id="sss" class="p20 clearfix"><div id="page-content" class="p20 clearfix">

<?php 

$optionss = array(
            "id" =>$model_info->id,
                   );
        $clock_in_data = $this->Attendance_model->get_details($optionss)->row();
        //echo format_to_time($clock_in_data->in_time);
       // echo $clock_in_data->id;
        //echo $clock_in_data->user_id;
$in_time = format_to_time($clock_in_data->in_time); if (isset($clock_in_data->id)&& ($in_time =='00:00')) {
        ?>
    <h5 style="color:red;">Add Atleast One Todo and One Task to Save the Clock in Time  </h5> 
    <?php } ?>
     <?php  $attendancetask_options = array(
            "todo_id" =>$model_info->id,
                   );
        $attendance_task_data = $this->Attendance_task_todo_model->get_details($attendancetask_options)->row();if($attendance_task_data) { ?>
    <?php echo form_open(get_uri("attendance/todo_save"), array("id" => "todo-inline-form", "class" => "", "role" => "form")); ?>
    <input type="hidden" name="id" id="todo_id" value="<?php echo $todo_id; ?>" />
    <div class="todo-input-box">

        <div class="input-group">
            <?php
            echo form_input(array(
                "id" => "todo-title",
                "name" => "title",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('add_a_todo'),
                "autocomplete" => "off",
                "autofocus" => true
            ));
            ?>
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </span>
        </div>

    </div>
    <?php echo form_close(); ?>
<?php } ?>
<div class="page-title clearfix">
            <h1> <?php echo lang('tasks'); ?></h1>
</div>
<!-- pending task list -->
<div class="checklist-items">

</div>
           
 <div class="Attendance_savetask-items">

</div>
<!-- end save pending task -->

    <div class="panel panel-default">
        <div class="page-title clearfix">
            <!--h1> <?php echo lang('todo') . " (" . lang('private') . ")"; ?></h1-->
            <h1> <?php echo lang('todo_list'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="attendance-todo-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('my_tasks'); ?></h4>
    </div>
    <div class="table-responsive">
        <table id="task-table" class="display" cellspacing="0" width="100%">
        </table>
    </div>
</div>

<?php $this->load->view("attendance/add_todo/helper_js"); ?>


<?php
$url = "attendance/save_note";

if ($clock_out == "1") {
    $url = "attendance/log_time";
}

echo form_open(get_uri($url), array("id" => "attendance-note-form", "class" => "general-form", "role" => "form"));
?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="note" class=" col-md-12">Note (Enable location mode in your device to clock out)</label>
        <div class=" col-md-12">
            <?php
            echo form_textarea(array(
                "id" => "note",
                "name" => "note",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "value" => $model_info->note,
                //"readonly"=>true
            ));
            ?>
        </div>
        <input name="clock_out" type="hidden" value="<?php echo $clock_out; ?>" />
    </div>
</div>
 

<div class="modal-footer">
     <div id="link-of-task-view" class="hide">
            <?php
           // echo modal_anchor(get_uri("attendance/todo_view"), "", array());
            echo modal_anchor(get_uri("attendance/todo_view/"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("class" => "btn btn-default", "data-post-id" => $todo_id, "title" => lang('add_todo')));
            ?>
        </div>
    <button type="button" class="btn btn-default" data-dismiss="modal" id='closetodo'><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <!--button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button-->
    <?php if (!$clock_out == "1") { ?>
     <button  id="note_save" disabled  type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
     <?php } ?>
     <?php if ($clock_out == "1") { ?>
    <?php 

$optionss = array(
            "id" =>$model_info->id,
                   );
        $clock_in_data = $this->Attendance_model->get_details($optionss)->row();
        //echo format_to_time($clock_in_data->in_time);
       // echo $clock_in_data->id;
        //echo $clock_in_data->user_id;
$in_time = format_to_time($clock_in_data->in_time); if (isset($clock_in_data->id)&& ($in_time !=='00:00')) {
        ?>
    <button id="note_save"  style="display:none" type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
    <button  id="check_status" disabled type="button" class="btn btn-primary" ><span class="fa check-circle"></span> <?php echo lang('save'); ?></button>
    <?php } ?>
    <?php } ?>
</div><input type="hidden" name="result" id="result" >

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
//show the items in checklist
        $(".checklist-items").html(<?php echo $checklist_items; ?>);
         $(".Attendance_savetask-items").html(<?php echo $Attendance_savetask_items; ?>);

       /* $(".checklist-items").click(function(){
  alert("The paragraph was clicked.");
});
*/


      // window.showAddNewModal = false;
  $(".checklist-items").click(function () {
           
                    var $taskViewLink = $("#link-of-task-view").find("a");
                     setTimeout(function(){ $taskViewLink.trigger("click"); }, 1000);
             
        });

});
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-note-form").appForm({
            onSuccess: function (result) {
                if (result.clock_widget) {
                   $("#timecard-clock-out").closest("#js-clock-in-out").html(result.clock_widget);
                } else {
                    if (result.isUpdate) {
                        $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
                    } else {
                        $(".dataTable:visible").appTable({reload: true});
                    }
                }
            }
        });

        $("#note").focus();
        <?php if ($clock_out == "1") { ?>  
         $("#check_status").click(function () {

    
    $.ajax({
                    url: "<?php echo get_uri("attendance/get_status_suggestion"); ?>",
                    data: {item_name: $("#todo_id").val()},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            
                         /*   if (!$("#item_rate").val()) {
                                $("#item_rate").val(response.item_info.total);
                            } */
                            
                            //alert("arun");
                         $("#check_status").show(); 
                         alert("Not Complete on Today Your Todo list"); 
//$("#note_save").show();
//$("#note_save").prop("readonly", true);


                       }else{
                            $("#note_save").click();
                            $("#note_save").show();
                            $("#check_status").hide();
                       }
                    }
                });
});
         <?php } ?>
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-todo-table").appTable({
            source: '<?php echo_uri("attendance/todo_list_data/". $model_info->id . "/") ?>',
            order: [[1, 'desc']],
            columns: [
               {visible: false, searchable: false},
                {title: '', "class": "w25"},
                {title: '<?php echo lang("title"); ?>'},
                {targets: [5], visible: false},
               {title: '<?php echo lang("date"); ?>', "class": "w200"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
        /*  checkBoxes: [
                {text: '<?php echo lang("to_do") ?>', name: "status", value: "to_do", isChecked: true},
                {text: '<?php echo lang("done") ?>', name: "status", value: "done", isChecked: false}
            ], */
            printColumns: [2, 4],
            xlsColumns: [2, 4],
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).addClass(aData[0]);
            }
        });
    });
</script>
<?php
//if we get any task parameter, we'll show the task details modal automatically
$preview_task_id = get_array_value($_GET, 'task');
if ($preview_task_id) {
    echo modal_anchor(get_uri("projects/task_view"), "", array("id" => "preview_task_link", "title" => lang('task_info') . " #$preview_task_id", "data-post-id" => $preview_task_id));
}

$statuses = array();
foreach ($task_statuses as $status) {
    $is_selected = false;
    if ($status->key_name != "done") {
        $is_selected = true;
    }

    $statuses[] = array("text" => ($status->key_name ? lang($status->key_name) : $status->title), "value" => $status->id, "isChecked" => $is_selected);
}
?>

<script type="text/javascript">
    $(document).ready(function () {

        $("#task-table").appTable({
            source: '<?php echo_uri("projects/my_tasks_list_data") ?>',
            order: [[1, "desc"]],
            filterDropdown: [
                {name: "specific_user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
                {name: "milestone_id", class: "w200", options: [{id: "", text: "- <?php echo lang('milestone'); ?> -"}], dependency: ["project_id"], dataSource: '<?php echo_uri("projects/get_milestones_for_filter") ?>'}, //milestone is dependent on project
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>, dependent: ["milestone_id"]}, //reset milestone on changing of project               
            ],
            singleDatepicker: [{name: "deadline", defaultText: "<?php echo lang('deadline') ?>",
                    options: [
                        {value: "expired", text: "<?php echo lang('expired') ?>"},
                        {value: moment().format("YYYY-MM-DD"), text: "<?php echo lang('today') ?>"},
                        {value: moment().add(1, 'days').format("YYYY-MM-DD"), text: "<?php echo lang('tomorrow') ?>"},
                        {value: moment().add(7, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(lang('in_number_of_days'), 7); ?>"},
                        {value: moment().add(15, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(lang('in_number_of_days'), 15); ?>"}
                    ]}],
            multiSelect: [
                {
                    name: "status_id",
                    text: "<?php echo lang('status'); ?>",
                    options: <?php echo json_encode($statuses); ?>
                }
            ],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("id") ?>'},
                {title: '<?php echo lang("title") ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo lang("start_date") ?>', "iDataSort": 3},
                {visible: false, searchable: false},
                {title: '<?php echo lang("deadline") ?>', "iDataSort": 5},
                {title: "<?php echo lang("client") ?>", "class": ""},
                {title: '<?php echo lang("project") ?>'},
                {title: '<?php echo lang("assigned_to") ?>', "class": "min-w150"},
                {title: '<?php echo lang("collaborators") ?>'},
                {title: '<?php echo lang("status") ?>'}
<?php echo $custom_field_headers; ?>,
                {visible: false, searchable: false}
            ],
            printColumns: combineCustomFieldsColumns([1, 2, 4, 6, 7, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([1, 2, 4, 6, 7, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).attr("style", "border-left:5px solid " + aData[0] + " !important;");
            }
        });


        //open task details modal automatically 

        if ($("#preview_task_link").length) {
            $("#preview_task_link").trigger("click");
        }


    });
    
</script>
<?php $this->load->view("projects/tasks/update_task_script"); ?>
<?php $this->load->view("projects/tasks/update_task_read_comments_status_script"); ?>

</div>
<script type="text/javascript">
function dd(){
                        setTimeout(function(){ 

             $("#closetodo ").click();
             $("#timecard-clock-out ").click();  }, 1000);
     
                     
 
}
</script>
<script type="text/javascript">
    delay().then(function(response) {
 $.ajax({
  url: 'https://reverse.geocoder.api.here.com/6.2/reversegeocode.json',
  type: 'GET',
  dataType: 'json',
  jsonp: 'jsoncallback',
  data: {
    prox: $("#result").val(),
    mode: 'retrieveAddresses',
    maxresults: '1',
    gen: '9',
    app_id: 'luqHzxdDgQRXukjNkhmd',
    app_code: 'rJm0dYRyV5Cpchlv9SJWzQ'
  },
  success: function (data) {
    var a=JSON.stringify(data.Response.View[0].Result[0].Location.Address.Label);
    $('#result').val(a)
    //$('#note').val(a)

    $("#check_status").removeAttr("disabled");
    $("#check_status").removeAttr("disabled");

  }
});    });

function delay() {
  return new Promise(function(resolve,reject) {
         if ("geolocation" in navigator){ //check geolocation available 
        //try to get user current location using getCurrentPosition() method
        navigator.geolocation.getCurrentPosition(function(position){ 
                $("#result").val(position.coords.latitude+","+ position.coords.longitude);
resolve('success');            });
    }else{
        console.log("Browser doesn't support geolocation!");
    } 

  });
}
</script>