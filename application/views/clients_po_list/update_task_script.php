<?php
// Sample data for $task_statuses
$task_statuses = array(
    (object) array('id' => 1, 'key_name' => 'status_1', 'title' => 'Status 1'),
    (object) array('id' => 2, 'key_name' => 'status_2', 'title' => 'Status 2'),
    (object) array('id' => 3, 'key_name' => 'status_3', 'title' => 'Status 3')
);

$status_dropdown = array();
foreach ($task_statuses as $status) {
    $status_dropdown[] = array("value" => $status->id, "text" => $status->key_name ? lang($status->key_name) : $status->title);
};
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
