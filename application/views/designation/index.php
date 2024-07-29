<div id="page-content" class="p20 clearfix">
    <!--div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php /*
            $tab_view['active_tab'] = "designation";
            $this->load->view("settings/tabs", $tab_view);
            */?>
        </div>

        <div class="col-sm-9 col-lg-10"-->
            <div class="panel panel-default">
                    <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <div class="page-title clearfix">
                    <h4> <?php echo lang('designation'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("designation/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_designation'), array("class" => "btn btn-default", "title" => lang('add_designation'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="taxes-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#taxes-table").appTable({
            source: '<?php echo_uri("designation/list_data") ?>',
            
            columns: [
                {title: '<?php echo lang("designation_name"); ?>'},
                {title: '<?php echo lang("designation_code"); ?>'},
                {title: '<?php echo lang("department_name"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
             {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5,6],
            xlsColumns: [0, 1, 2, 3,4,5,6],
        });
    });
</script>