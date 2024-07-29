<div id="page-content" class="p20 clearfix">
    <!--div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php /*
            $tab_view['active_tab'] = "product_id_generation";
            $this->load->view("settings/tabs", $tab_view);
            */?>
        </div>

        <div class="col-sm-9 col-lg-10"-->
            <div class="panel panel-default">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
            <div class="page-title clearfix">
                    <h4> <?php echo lang('product_id_generation'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("product_id_generation/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_item'), array("class" => "btn btn-default", "title" => lang('add_item'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="product_id_generation-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#product_id_generation-table").appTable({
            source: '<?php echo_uri("product_id_generation/list_data") ?>',
            columns: [
                {title: '<?php echo lang("product_id"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                
                {title: "<?php echo lang('category') ?> "},
                {title: "<?php echo lang('make') ?> "},
                {title: "<?php echo lang('rate') ?> ", "class": "text-right w10p"},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
               {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5,6,7,8,9,10],
            xlsColumns: [0, 1, 2, 3,4,5,6,7,8,9,10],
            summation: [{column: 4, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    });
</script>