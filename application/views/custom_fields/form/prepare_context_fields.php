<?php
$label_column = isset($label_column) ? $label_column : "col-md-3";
$field_column = isset($field_column) ? $field_column : "col-md-9";
$custom_fields = isset($custom_fields) ? $custom_fields : array();

foreach ($custom_fields as $field) {
    if (is_object($field) && isset($field->title) && isset($field->field_type)) {
        ?>
        <div class="form-group">
            <label class="<?php echo htmlspecialchars($label_column); ?>"><?php echo htmlspecialchars($field->title); ?></label>
            <div class="<?php echo htmlspecialchars($field_column); ?>">
                <?php
                $input_view = "custom_fields/input_" . htmlspecialchars($field->field_type);
                if (file_exists(APPPATH . "views/" . $input_view . ".php")) {
                    $this->load->view($input_view, array("field_info" => $field));
                } else {
                    echo "<p>Input type not supported: " . htmlspecialchars($field->field_type) . "</p>";
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        echo "<p>Invalid field structure</p>";
    }
}
?>
