<?php /*
<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "countries";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('countries'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("countries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_country'), array("class" => "btn btn-default", "title" => lang('add_country'))); ?>
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
            source: '<?php echo_uri("countries/list_data") ?>',
            columns: [
                {title: '<?php echo lang("iso_code"); ?>'},
                {title: '<?php echo lang("country"); ?>'},      
                {title: '<?php echo lang("country_code"); ?>'},

                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>
*/ ?>
<div id="page-content" class="p20 clearfix">
    
            <div class="panel panel-default">
                <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
        <div class="page-title clearfix">
                    <h4> <?php echo lang('states'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("states/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_state'), array("class" => "btn btn-default", "title" => lang('add_state'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="states-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
 
<script type="text/javascript">
    $(document).ready(function () {
        $("#states-table").appTable({
            source: '<?php echo_uri("states/list_data") ?>',
            columns: [
                {title: '<?php echo lang("state"); ?>'},
                {title: '<?php echo lang("country"); ?>'},      
                {title: '<?php echo lang("state_code"); ?>'},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},

                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3,4,5],
            xlsColumns: [0, 1, 2, 3,4,5],
        });
    });
</script>

