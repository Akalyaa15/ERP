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
                <div class="page-title clearfix">
                    <h4> <?php echo lang('tools'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("tools/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_tool'))); ?>
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
            source: '<?php echo_uri("tools/list_data") ?>',
            columns: [
                {title: '<?php echo lang("product_id"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("location"); ?>'},
                {title: '<?php echo lang("quantity"); ?>'},
                {title: "<?php echo lang('category') ?> "},
             {title: "<?php echo lang('make') ?> "},
             {title: "<?php echo lang('unit_type') ?>", "class": "w100"},
                {title: "<?php echo lang('rate') ?>", "class": "text-right w100"},
                 {title: "<?php echo lang('stock_value') ?>", "class": "text-right w100"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5,6,7,8],
            xlsColumns: [0, 1, 2, 3,4,5,6,7,8],
            summation: [{column: 7, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol},{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    });
</script>