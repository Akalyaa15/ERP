<div class="box">
    <div class="box-content widget-container b-r">
        <div class="panel-body ">
            <h1 class=""><?php echo $project_open; ?></h1>
            <!-- <span class="text-off uppercase"><?php echo lang("open_projects"); ?></span> -->
             <span class="text-off uppercase"><?php echo anchor(get_uri("projects"), lang("open_projects"),array("class" => "black-link")); ?></span>
        </div>
    </div>
    <div class="box-content widget-container ">
        <div class="panel-body ">
            <h1><?php echo $project_completed; ?></h1>
            <!-- <span class="text-off uppercase"><?php echo lang("projects_completed"); ?></span> -->
            <span class="text-off uppercase"><?php echo anchor(get_uri("projects/widget_all_completed_projects/completed"), lang("projects_completed"),array("class" => "black-link")) ; ?></span>
        </div>
    </div>
</div>




