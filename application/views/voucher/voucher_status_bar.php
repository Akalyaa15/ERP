<?php
// Fetch the created user details
$options = array("id" => $estimate_info->created_user_id);
$created_user = $this->Users_model->get_details($options)->row();

// Check if the 'assigned_to_avatar' property exists before using it
$image_url = property_exists($created_user, 'assigned_to_avatar') && $created_user->assigned_to_avatar ? get_avatar($created_user->assigned_to_avatar) : get_avatar(null);

// Check if the 'assigned_to_user' property exists before using it
$assigned_to_user = property_exists($created_user, 'assigned_to_user') ? 
    "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $created_user->assigned_to_user" :
    "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> Unknown User";
?>

<span class="text-off ml15 mr10"><?php echo lang("created_by") . ": "; ?></span>
<?php echo get_team_member_profile_link($created_user->id, $created_user->first_name . " " . $created_user->last_name); ?>

<?php if ($estimate_info->line_manager != "admin") { 
    $options = array("id" => $estimate_info->line_manager);
    $line_manager = $this->Users_model->get_details($options)->row();
    $image_url = property_exists($line_manager, 'assigned_to_avatar') && $line_manager->assigned_to_avatar ? get_avatar($line_manager->assigned_to_avatar) : get_avatar(null);
    $assigned_to_user = property_exists($line_manager, 'assigned_to_user') ? 
        "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $line_manager->assigned_to_user" :
        "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> Unknown User";
?>
    <?php if ($estimate_info->line_manager) { ?>
        <span class="text-off ml15 mr10">
            <?php 
            echo lang("line_manager") . ": "; 
            echo get_team_member_profile_link($line_manager->id, $line_manager->first_name . " " . $line_manager->last_name);
            ?>
        </span>
    <?php } ?>
<?php } ?>

<?php 
$options = array("id" => $estimate_info->accounts_handler);
$accounts_handler = $this->Users_model->get_details($options)->row();
$image_url = property_exists($accounts_handler, 'assigned_to_avatar') && $accounts_handler->assigned_to_avatar ? get_avatar($accounts_handler->assigned_to_avatar) : get_avatar(null);
$assigned_to_user = property_exists($accounts_handler, 'assigned_to_user') ? 
    "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $accounts_handler->assigned_to_user" :
    "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> Unknown User";
?>
<span class="text-off ml15 mr10"><?php echo lang("accounts_handler") . ": "; ?></span>
<?php if ($estimate_info->accounts_handler) {
    echo get_team_member_profile_link($accounts_handler->id, $accounts_handler->first_name . " " . $accounts_handler->last_name);
} ?>

<?php 
$options = array("id" => $estimate_info->payments_handler);
$payments_handler = $this->Users_model->get_details($options)->row();
$image_url = property_exists($payments_handler, 'assigned_to_avatar') && $payments_handler->assigned_to_avatar ? get_avatar($payments_handler->assigned_to_avatar) : get_avatar(null);
$assigned_to_user = property_exists($payments_handler, 'assigned_to_user') ? 
    "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $payments_handler->assigned_to_user" :
    "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> Unknown User";
?>
<span class="text-off ml15 mr10"><?php echo lang("payments_handler") . ": "; ?></span>
<?php if ($estimate_info->payments_handler) {
    echo get_team_member_profile_link($payments_handler->id, $payments_handler->first_name . " " . $payments_handler->last_name);
} ?>
