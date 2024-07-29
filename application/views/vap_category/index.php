<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "vap_category";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
        <div class="page-title clearfix">
                    <h4> <?php echo lang('vap_category'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("vap_category/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vap_category'), array("class" => "btn btn-default", "title" => lang('add_vap_category'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="vap_category-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#vap_category-table").appTable({
            source: '<?php echo_uri("vap_category/list_data") ?>',
            columns: [
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2]
        });
    });
</script>