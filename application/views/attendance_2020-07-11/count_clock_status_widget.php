<div class="box b-t bg-white">
    <div class="box-content widget-container b-r">
        <div class="panel-body ">
            <h1><?php echo $members_clocked_in; ?></h1>
            <!-- <span class="text-off uppercase"><?php echo lang("members_clocked_in"); ?></span> -->
 <span class="text-off uppercase"><?php echo anchor(get_uri("attendance"), lang("members_clocked_in"),array("class" => "black-link")); ?></span>
        </div>
    </div>
    <div class="box-content widget-container">
        <div class="panel-body ">
            <h1 class=""><?php echo $members_clocked_out; ?></h1>
            <!-- <span class="text-off uppercase"><?php echo lang("members_clocked_out"); ?></span> -->
            <span class="text-off uppercase"><?php echo anchor(get_uri("attendance"), lang("members_clocked_out"),array("class" => "black-link")); ?></span>
        </div>
    </div>
</div>