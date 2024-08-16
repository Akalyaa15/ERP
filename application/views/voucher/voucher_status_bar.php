<div class="panel panel-default p15 no-border m0" style="line-height: 25px">
    <span><?php echo lang("status") . ": " . $estimate_status_label; ?></span>
    <?php
    // show assign to field to team members only
    $options = array("id" => $estimate_info->created_user_id);
    $created_user = $this->Users_model->get_details($options)->row();
    $image_url = isset($created_user->assigned_to_avatar) ? get_avatar($created_user->assigned_to_avatar) : base_url('path/to/default/avatar.png'); // Replace with the actual path to your default avatar
    ?>
    <span class="text-off ml15 mr10"><?php echo lang("created_by") . ": "; ?></span>
    <?php
    echo get_team_member_profile_link($created_user->id, $created_user->first_name . " " . $created_user->last_name);
    ?>
    <?php if (isset($estimate_info->line_manager) && $estimate_info->line_manager != "admin") { ?>
        <?php
        // show assign to field to team members only
        $options = array("id" => $estimate_info->line_manager);
        $line_manager = $this->Users_model->get_details($options)->row();
        $image_url = isset($line_manager->assigned_to_avatar) ? get_avatar($line_manager->assigned_to_avatar) : base_url('path/to/default/avatar.png'); // Replace with the actual path to your default avatar
        ?>
        <?php if ($estimate_info->line_manager) { ?>
            <span class="text-off ml15 mr10">
                <?php
                if (isset($voucher_expense) && $voucher_expense->r_member_type == 'clients') {
                    echo lang("project_manager") . ": ";
                } else if (isset($voucher_expense) && $voucher_expense->r_member_type == 'vendors') {
                    echo lang("purchase_manager") . ": ";
                } else {
                    echo lang("line_manager") . ": ";
                }
                ?>
            </span>
            <?php
            echo get_team_member_profile_link($line_manager->id, $line_manager->first_name . " " . $line_manager->last_name);
            ?>
        <?php } ?>
    <?php } ?>

    <?php
    // show assign to field to team members only
    $options = array("id" => $estimate_info->accounts_handler);
    $accounts_handler = $this->Users_model->get_details($options)->row();
    $image_url = isset($accounts_handler->assigned_to_avatar) ? get_avatar($accounts_handler->assigned_to_avatar) : base_url('path/to/default/avatar.png'); // Replace with the actual path to your default avatar
    ?>
    <span class="text-off ml15 mr10"><?php echo lang("accounts_handler") . ": "; ?></span>
    <?php if ($estimate_info->accounts_handler) {
        echo get_team_member_profile_link($accounts_handler->id, $accounts_handler->first_name . " " . $accounts_handler->last_name);
    } ?>

    <?php
    // show assign to field to team members only
    $options = array("id" => $estimate_info->payments_handler);
    $payments_handler = $this->Users_model->get_details($options)->row();
    $image_url = isset($payments_handler->assigned_to_avatar) ? get_avatar($payments_handler->assigned_to_avatar) : base_url('path/to/default/avatar.png'); // Replace with the actual path to your default avatar
    ?>
    <span class="text-off ml15 mr10"><?php echo lang("payments_handler") . ": "; ?></span>
    <?php if ($estimate_info->payments_handler) {
        echo get_team_member_profile_link($payments_handler->id, $payments_handler->first_name . " " . $payments_handler->last_name);
    } ?>

    <span class="ml15">
        <?php
        if (isset($estimate_info->project_id)) {
            echo lang("project") . ": ";
            echo anchor(get_uri("projects/view/" . $estimate_info->project_id), $estimate_info->project_title);
        }
        ?>
    </span>
</div>
