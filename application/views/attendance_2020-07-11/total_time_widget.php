<div class="box b-t">
    <div class="box-content widget-container b-r">
        <div class="panel-body ">
            <h1><?php echo $total_hours_worked; ?></h1>
            <!-- <span class="text-off uppercase"><?php echo lang("total_hours_worked"); ?></span> -->
            <?php if ($this->login_user->user_type === "staff") { ?>
            <span class="text-off uppercase"><?php echo get_team_member_profile_link($this->login_user->id,lang("total_hours_worked"),array("class" => "black-link")) ?></span>
        <?php } else if ($this->login_user->user_type === "resource") { ?>
            <span class="text-off uppercase" style="color: black"><?php echo get_rm_member_profile_link($this->login_user->id,lang("total_hours_worked"),array("class" => "black-link")) ?></span>
        <?php } ?>
        </div>
    </div>
    <div class="box-content widget-container">
        <div class="panel-body ">
            <h1 class=""><?php echo $total_project_hours; ?></h1>
          <!--   <span class="text-off uppercase"><?php echo lang("total_project_hours"); ?></span> -->
             
        <span class="text-off uppercase"><?php echo anchor(get_uri("projects/all_timesheets"), lang("total_project_hours"),array("class" => "black-link")); ?></span>
        </div>
    </div>
</div>


