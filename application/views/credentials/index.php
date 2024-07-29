<div id="page-content" class="p20 clearfix">
    <!--div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php /*
            $tab_view['active_tab'] = "tools";
            $this->load->view("settings/tabs", $tab_view);
           */ ?>
        </div>

        <div class="col-sm-9 col-lg-10"-->
            <div class="panel panel-default">
                <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
        <div class="page-title clearfix">
                    <h4> <?php echo lang('credentials'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("credentials/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_credential'), array("class" => "btn btn-default", "title" => lang('add_credential'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tools-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#tools-table").appTable({
            source: '<?php echo_uri("credentials/list_data") ?>',
            columns: [
                {title: '<?php echo lang("created_date"); ?>'},
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("username"); ?>'},
                {title: "<?php echo lang('password') ?> "},
             {title: "<?php echo lang('description') ?> "},
             {title: "<?php echo lang('url') ?> "},
             {title: '<?php echo lang("files") ?>', "class": "w250"},
             {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
             {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
             {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>