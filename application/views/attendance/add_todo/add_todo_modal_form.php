<?php echo form_open(get_uri("attendance/add_todo_modal_save"), array("id" => "todo-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
     <input type="hidden"  name="todo_id" value="<?php echo $todo_id; ?>" />
    <div class="form-group">
     <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control notepad-title",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
    <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class="col-md-9">
            <div class="notepad">
                <?php
                 echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description,
                    "class" => "form-control",
                    "placeholder" => lang('description') . "...",
                ));
                ?>
            </div>
        </div>
    </div>
    <!--div class="form-group">
        <div class="col-md-12">
            <div class="notepad">
                <?php
                echo form_input(array(
                    "id" => "todo_labels",
                    "name" => "labels",
                    "value" => $model_info->labels,
                    "class" => "form-control",
                    "placeholder" => lang('labels')
                ));
                ?>
            </div>
        </div>
    </div-->
    <div class="form-group">
    <label for="date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "start_date",
                "name" => "start_date",
                "value" => is_date_exists($model_info->start_date) ? $model_info->start_date : "",
                "class" => "form-control",
                "placeholder" => lang('date')
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
<div id="link-of-task-view" class="hide">
            <?php
           // echo modal_anchor(get_uri("attendance/todo_view"), "", array());
            echo modal_anchor(get_uri("attendance/todo_view/"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("class" => "btn btn-default", "data-post-id" => $todo_id, "title" => lang('add_todo')));
            ?>
        </div>
        <div class="row">
    <button type="button" class="btn btn-default" data-dismiss="modal">
    <span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button id="save-and-show-button" type="button" class="btn btn-primary" ><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
    <!--button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button-->
</div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
       /* $("#todo-form").appForm({
            onSuccess: function (result) {
                $("#attendance-todo-table").appTable({newData: result.data, dataId: result.id});
            }
        }); */
        window.showAddNewModal = false;

        $("#save-and-show-button").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");

        });

        var taskInfoText = "<?php echo lang('add_payment') ?>";

        window.taskForm = $("#todo-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#attendance-todo-table").appTable({newData: result.data, dataId: result.id});
                //$("#reload-kanban-button").trigger("click");

                $("#save_and_show_value").append(result.save_and_show_link);

                if (window.showAddNewModal) {
                    var $taskViewLink = $("#link-of-task-view").find("a");
                   // $taskViewLink.attr("data-title", taskInfoText + "#" + result.id);
                  //  $taskViewLink.attr("data-post-id", result.id);

                    $taskViewLink.trigger("click");
                } else {
                    window.taskForm.closeModal();
                }
            }
        });
        $("#title").focus();
      /*  $("#todo_labels").select2({
            tags: <?php echo json_encode($label_suggestions); ?>,
            'minimumInputLength': 0
        }); */

        setDatePicker("#start_date");
    });
</script>    