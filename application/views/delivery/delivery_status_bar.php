<div class="panel panel-default p15 no-border m0">
    <span><?php echo lang("status") . ": " . $estimate_status_label; ?></span>
    <span class="ml15"><?php
        echo lang("team_member") . ": ";
        echo (anchor(get_uri("team_members/view/" . $estimate_info->client_id), $estimate_info->first_name." ". $estimate_info->last_name));
        ?>
    </span>
    <span class="ml15"><?php
        if (isset($estimate_info->project_id) && $estimate_info->project_id) {
            echo lang("project") . ": ";
            echo (anchor(get_uri("projects/view/" . $estimate_info->project_id), $estimate_info->project_title));
        }
        ?>
    </span>
</div>
