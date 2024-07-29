<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "unit_type";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
        <div class="page-title clearfix">
                    <h4> <?php echo lang('unit_type'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("unit_type/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_unit_type'), array("class" => "btn btn-default", "title" => lang('add_unit_type'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="unit_type-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#unit_type-table").appTable({
            source: '<?php echo_uri("unit_type/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2]
        });
    });
</script>