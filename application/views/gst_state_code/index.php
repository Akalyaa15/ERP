<div id="page-content" class="p20 clearfix">
     <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "gst_state_code";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                  <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
      <div class="page-title clearfix">
                    <h4> <?php echo lang('gst_state_code'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("gst_state_code/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_gst_state_code'), array("class" => "btn btn-default", "title" => lang('add_gst_state_code'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="gst_state_code-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#gst_state_code-table").appTable({
            source: '<?php echo_uri("gst_state_code/list_data") ?>',
            columns: [
                {title: '<?php echo lang("state"); ?>'},
{title: '<?php echo lang("gstinnumber_firsttwodigits"); ?>'},
                {title: '<?php echo lang("state_code"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>