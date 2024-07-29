<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "earnings";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
        <div class="page-title clearfix">
                    <h4> <?php echo lang('earnings'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("earnings/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_earnings'), array("class" => "btn btn-default", "title" => lang('add_earnings'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="earnings-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#earnings-table").appTable({
            source: '<?php echo_uri("earnings/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("percentage"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>