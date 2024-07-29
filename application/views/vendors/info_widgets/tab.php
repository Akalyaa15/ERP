<?php
$panel = "";
$icon = "";
$value = "";

if ($tab == "total_purchase_orders") {
    $panel = "panel-sky";
    $icon = "fa-th-large";
    $value = to_decimal_format($vendor_info->total_purchase_orders);
} else if ($tab == "purchase_order_value") {
    $panel = "panel-primary";
    $icon = "fa-file-text";
    $value = to_currency($vendor_info->purchase_order_value, $vendor_info->currency_symbol);
} else if ($tab == "purchase_order_payments") {
    $panel = "panel-success";
    $icon = "fa-check-square";
    $value = to_currency($vendor_info->payment_received, $vendor_info->currency_symbol);
} else if ($tab == "purchase_order_due") {
    $panel = "panel-coral";
    $icon = "fa-money";
    $value = to_currency(ignor_minor_value($vendor_info->purchase_order_value - $vendor_info->payment_received), $vendor_info->currency_symbol);
}
if ($tab == "total_work_orders") {
    $panel = "panel-blue";
    $icon = "fa-th-large";
    $value = to_decimal_format($vendor_info->total_work_orders);
} else if ($tab == "work_order_value") {
    $panel = "dark-magenta";
    $icon = "fa-file-text";
    $value = to_currency($vendor_info->work_order_value, $vendor_info->currency_symbol);
} else if ($tab == "work_order_payments") {
    $panel = "medium-spring-green";
    $icon = "fa-check-square";
    $value = to_currency($vendor_info->work_order_payment_received, $vendor_info->currency_symbol);
} else if ($tab == "work_order_due") {
    $panel = "fire-brick";
    $icon = "fa-money";
    $value = to_currency(ignor_minor_value($vendor_info->work_order_value - $vendor_info->work_order_payment_received), $vendor_info->currency_symbol);
}
?>

<div class="panel <?php echo $panel ?>">
    <div class="panel-body ">
        <div class="widget-icon">
            <i class="fa <?php echo $icon; ?>"></i>
        </div>
        <div class="widget-details">
            <h1><?php echo $value; ?></h1>
            <?php echo lang($tab); ?>
        </div>
    </div>
</div>