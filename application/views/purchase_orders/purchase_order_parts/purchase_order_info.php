<span style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo /*get_purchase_order_id($purchase_order_info->id)*/ $purchase_order_info->purchase_no?$purchase_order_info->purchase_no:get_purchase_order_id($purchase_order_info->id); ?>&nbsp;</span>
<div style="line-height: 10px;"></div><?php
if (isset($purchase_order_info->custom_fields) && $purchase_order_info->custom_fields) {
    foreach ($purchase_order_info->custom_fields as $field) {
        if ($field->value) {
            echo "<span>" . $field->custom_field_title . ": " . $this->load->view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value), true) . "</span><br />";
        }
    }
}
?>
<span><?php echo lang("generate_purchase_order_date") . ": " . format_to_date($purchase_order_info->purchase_order_date, false); ?></span><br />
<span><?php echo lang("valid_until") . ": " . format_to_date($purchase_order_info->valid_until, false); ?></span><br>
<span><?php if($purchase_order_info->lut_number){echo lang("lut_number") . ": " .$purchase_order_info->lut_number; }?></span>