<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
           <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
     <div class="page-title clearfix">
            <h1><?php echo lang('partners'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("partners_register/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_partner'), array("class" => "btn btn-default", "title" => lang('add_partner'))); ?>
            </div>
            
        </div>
        <div class="table-responsive">
            <table id="partners-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
       

        $("#partners-table").appTable({
            source: '<?php echo_uri("partners_register/list_data") ?>',
            filterDropdown: [
                {name: "group_id", class: "w200", options: <?php echo $groups_dropdown; ?>}
            ],
            columns: [
                {title: "<?php echo lang("id") ?>", "class": "text-center w50"},
                {title: "<?php echo lang("partner_name") ?>"},
                {title: "<?php echo lang("primary_contact") ?>"},
                {title: "<?php echo lang("partner_groups") ?>"},
                
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], '<?php echo $custom_field_headers; ?>')
        });
    });
</script>