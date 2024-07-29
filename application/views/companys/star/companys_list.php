<div class="list-group">
    <?php
    if (count($companys)) {
        foreach ($companys as $company) {

            $icon = "fa fa-building";

            $title = "<i class='fa $icon mr10'></i> " . $company->company_name;
            echo anchor(get_uri("companys/view/" . $company->cr_id), $title, array("class" => "list-group-item"));
        }
    } else {
        ?>
        <div class='list-group-item'>
            <?php echo lang("empty_starred_companys"); ?>              
        </div>
    <?php } ?>
</div>