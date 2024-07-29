<div id="page-content" class="p20 clearfix">
     <div class="panel panel-default">
         <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
       <div class="page-title clearfix">
            <h1> <?php echo lang('outsource_jobs'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("outsource_jobs/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_outsource_job'), array("class" => "btn btn-default", "title" => lang('add_outsource_job'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="outsource_job-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#outsource_job-table").appTable({
            source: '<?php echo_uri("outsource_jobs/list_data") ?>',
            
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('job_id') ?> ", "class": "w15p"},
                {title: "<?php echo lang('clients') ?> "},
                {title: "<?php echo lang('projects') ?> "},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('category') ?> "},
             //{title: "<?php echo lang('make') ?> "},
                {title: "<?php echo lang('hsn_code') ?> "},
             
                {title: "<?php echo lang('unit_type') ?>", "class": "w100"},
                {title: "<?php echo lang('gst') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('rate') ?>", "class": "text-right w100"},
                {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            summation: [{column: 8, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    });
</script>