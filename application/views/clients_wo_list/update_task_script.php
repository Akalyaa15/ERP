<?php
// Check if $task_statuses is set and not null
if (isset($task_statuses) && is_array($task_statuses)) {
    // Initialize $status_dropdown as an empty array
    $status_dropdown = array();

    // Iterate over $task_statuses array
    foreach ($task_statuses as $status) {
        // Add status to $status_dropdown array
        $status_dropdown[] = array("value" => $status->id, "text" => $status->key_name ? lang($status->key_name) : $status->title);
    }
} else {
    // Handle the case where $task_statuses is null or not set
    // For example, display an error message or initialize $status_dropdown differently
    // Here, we'll initialize $status_dropdown as an empty array
    $status_dropdown = array();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '[data-act=update-task-status]', function () {
            $(this).editable({
                type: "select2",
                pk: 1,
                name: 'status',
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                value: $(this).attr('data-value'),
                url: '<?php echo_uri("clients_po_list/save_task_status") ?>/' + $(this).attr('data-id'),
                showbuttons: false,
                source: <?php echo json_encode($status_dropdown) ?>,
                success: function (response, newValue) {
                    if (response.success) {
                        $("#clients_po_list-table").appTable({newData: response.data, dataId: response.id});
                    }
                }
            });
            $(this).editable("show");
        });

        $('body').on('click', '[data-act=update-task-status-checkbox]', function () {
            $(this).find("span").addClass("inline-loader");
            $.ajax({
                url: '<?php echo_uri("clients_po_list/save_task_status") ?>/' + $(this).attr('data-id'),
                type: 'POST',
                dataType: 'json',
                data: {value: $(this).attr('data-value')},
                success: function (response) {
                    if (response.success) {
                        $("#clients_po_list-table").appTable({newData: response.data, dataId: response.id});
                    }
                }
            });
        });
    });
</script>