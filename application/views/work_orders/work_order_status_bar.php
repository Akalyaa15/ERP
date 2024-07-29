<div class="panel panel-default  p15 no-border m0">
    <span><?php echo lang("status") . ": " . $work_order_status_label; ?></span>
    <span class="ml15"><?php
        echo lang("vendor") . ": ";
        echo (anchor(get_uri("vendors/view/" . $work_order_info->vendor_id), $work_order_info->company_name));
    ?>
    </span>
    <span class="ml15"><?php
        if (isset($estimate_info) && isset($estimate_info->project_id)) {
            echo lang("project") . ": ";
            echo (anchor(get_uri("projects/view/" . $estimate_info->project_id), $estimate_info->project_title));
        }
    ?>
    </span>
</div>
