<div class="list-group">
    <?php
    if (count($vendors)) {
        foreach ($vendors as $vendor) {

            $icon = "fa fa-industry";

            $title = "<i class='fa $icon mr10'></i> " . $vendor->company_name;
            echo anchor(get_uri("vendors/view/" . $vendor->id), $title, array("class" => "list-group-item"));
        }
    } else {
        ?>
        <div class='list-group-item'>
            <?php echo lang("empty_starred_vendors"); ?>              
        </div>
    <?php } ?>
</div>