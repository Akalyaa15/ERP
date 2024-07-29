<div class="bg-white p15 pt0 b-b">
    <?php
    $ticket_labels = "";
    if (isset($ticket_info->labels) && $this->login_user->user_type == "staff") {
        $labels = explode(",", $ticket_info->labels);
        foreach ($labels as $label) {
            $ticket_labels .= "<span class='label label-info'  title='$label'>" . htmlspecialchars($label) . "</span> ";
        }
    }
    echo "<span class='mr15'>" . $ticket_labels . " </span>";
    ?>

    <span class="text-off"><?php echo lang("status") . ": "; ?></span>

    <?php
    $ticket_status_class = "label-danger";
    if (isset($ticket_info->status)) {
        if ($ticket_info->status === "new") {
            $ticket_status_class = "label-warning";
        } else if ($ticket_info->status === "closed") {
            $ticket_status_class = "label-success";
        }

        if ($ticket_info->status === "client_replied" && $this->login_user->user_type === "client") {
            $ticket_info->status = "open"; // don't show client_replied status to client
        }

        $ticket_status = "<span class='label $ticket_status_class large'>" . lang($ticket_info->status) . "</span> ";
        echo $ticket_status;
    }
    ?>

    <?php if ($this->login_user->user_type === "staff" && isset($ticket_info->company_name)) { ?>
        <span class="text-off ml15"><?php echo lang("client") . ": "; ?></span>
        <?php echo $ticket_info->company_name ? anchor(get_uri("clients/view/" . $ticket_info->client_id), $ticket_info->company_name) : "-"; ?>
    <?php } ?>

    <?php if (isset($ticket_info->project_id) && $ticket_info->project_id != "0" && $show_project_reference) { ?>
        <span class="text-off ml15"><?php echo lang("project") . ": "; ?></span>
        <?php echo $ticket_info->project_title ? anchor(get_uri("projects/view/" . $ticket_info->project_id), $ticket_info->project_title) : "-"; ?>
    <?php } ?>

    <?php if (isset($ticket_info->created_at)) { ?>
        <span class="text-off ml15"><?php echo lang("created") . ": "; ?></span>
        <?php echo format_to_relative_time($ticket_info->created_at); ?> 
    <?php } ?>

    <?php if (isset($ticket_info->created_by)) { ?>
        <span class="text-off ml15"><?php echo lang("created_by") . ": "; ?></span>
        <?php 
           $created_options = array("id" => $ticket_info->created_by);
            $created_user_data = $this->Users_model->get_details($created_options)->row();
            $image_url = get_avatar($created_user_data->image);
            $created_user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $created_user_data->first_name $created_user_data->last_name"; 
            if ($created_user_data->user_type == "resource") {
                echo get_rm_member_profile_link($ticket_info->created_by, $created_user );   
            } else if ($created_user_data->user_type == "client") {
                echo get_client_contact_profile_link($ticket_info->created_by, $created_user);
            } else {
                echo get_team_member_profile_link($ticket_info->created_by, $created_user ); 
            }
        ?>
    <?php } ?>

    <?php if (isset($ticket_info->ticket_type)) { ?>
        <span class="text-off ml15"><?php echo lang("ticket_type") . ": "; ?></span>
        <?php echo $ticket_info->ticket_type; ?> 
    <?php } ?>

    <?php
    if (isset($ticket_info->assigned_to) && $this->login_user->user_type == "staff") {
        // Show assign to field to team members only
        $image_url = get_avatar($ticket_info->assigned_to_avatar);
        $assigned_to_user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $ticket_info->assigned_to_user";
        ?>
        <span class="text-off ml15 mr10"><?php echo lang("assigned_to") . ": "; ?></span>
        <?php
        echo get_team_member_profile_link($ticket_info->assigned_to, $assigned_to_user);
    }
    ?>
</div>

<?php
if (isset($custom_fields_list) && count($custom_fields_list)) {
    $fields = "";
    foreach ($custom_fields_list as $data) {
        if ($data->value) {
            $fields .= "<div class='p15 bg-white b-b '><i class='fa fa-check-square ml15'></i> <span class='text-off'> $data->title:</span> " . $this->load->view("custom_fields/output_" . $data->field_type, array("value" => $data->value), true) . "</div>";
        }
    }
    if ($fields) {
        echo $fields;
    }
}
?>
