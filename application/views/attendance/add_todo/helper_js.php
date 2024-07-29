<script type="text/javascript">
    $(document).ready(function () {
        $("#todo-inline-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                $("#todo-title").val("");
                 $("#task_user_id").select2("val","");
                $("#attendance-todo-table").appTable({newData: result.data, dataId: result.id});
                appAlert.success(result.message, {duration: 5000});
                if (result.clock_widget) {
                   $("#timecard-clock-out").closest("#js-clock-in-out").html(result.clock_widget);
                }
            }
        });

        $('body').on('click', '[data-act=update-todo-status-checkbox]', function () {
            $(this).find("span").addClass("inline-loader");
            $.ajax({
                url: '<?php echo_uri("attendance/todo_save_status") ?>',
                type: 'POST',
                dataType: 'json',
                data: {id: $(this).attr('data-id'), status: $(this).attr('data-value')},
                success: function (response) {
                    if (response.success) {
                        $("#attendance-todo-table").appTable({newData: response.data, dataId: response.id});
                    }
                }
            });
        });
    });
</script>