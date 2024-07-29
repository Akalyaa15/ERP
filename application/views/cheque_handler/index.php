<div id="page-content" class="p20 clearfix">
   <div class="panel clearfix">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('cheque_handler'); ?></h4></li>
            <li><a id="monthly-cheque-button" class="active" role="presentation" href="javascript:;" data-target="#monthly-cheque"><?php echo lang("monthly"); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("cheque_handler/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_cheque'), array("class" => "btn btn-default", "title" => lang('add_cheque'))); ?>
                </div>
            </div>
        </ul> <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-cheque">
                <div class="table-responsive">
                    <table id="monthly-cheque-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div> </div>
        </div>
    </div>
</div>
<script type="text/javascript">
 loadChequeTable = function (selector, dateRange) {
         $(selector).appTable({
            source: '<?php echo_uri("cheque_handler/list_data") ?>',
             dateRangeType: dateRange,
            filterDropdown: [
                
                {name: "status_id", class: "w200", options: <?php echo $status_dropdown; ?>},
                {name: "user_ids", class: "w160", options: <?php echo $rm_members_dropdown; ?>},
                {name: "user_id", class: "w200", options: <?php echo $members_dropdown; ?>},
                {name: "client_id", class: "w160", options: <?php echo $clients_dropdown; ?>},
                {name: "vendor_id", class: "w160", options: <?php echo $vendors_dropdown; ?>},
                {name: "other_id", class: "w160", options: <?php 
                     echo $others_dropdown; ?>},
                
            ],
            columns: [
                {title: '<?php echo lang("id"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("member"); ?>'},
                {title: '<?php echo lang("bank_name"); ?>'},
                {title: '<?php echo lang("account_number"); ?>'},
                {title: "<?php echo lang('cheque_no') ?> "},
              {title: "<?php echo lang('cheque_category') ?> "},
              {title: "<?php echo lang('amount') ?> "},
            {visible: false, searchable: false},
             {title: "<?php echo lang('issue_date') ?>", "class": "w100"},
                {title: "<?php echo lang('drawn_on') ?>", "class": "text-right w100"},
 {title: "<?php echo lang('valid_upto') ?>", "class": "text-right w100"},
                 {title: "<?php echo lang('status') ?>", "class": "text-right w50"},
                 {title: '<?php echo lang("files") ?>', "class": "w40"},
                 {title: "<?php echo lang("last_activity_user") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_activity") ?>", "class": "w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
            xlsColumns: [1, 2, 3, 4, 6, 7, 8, 9,10],
        });
    };
     $(document).ready(function () {
        $("#monthly-cheque-button").trigger("click");
        loadChequeTable("#monthly-cheque-table", "monthly");
    });
</script>
<?php $this->load->view("cheque_handler/update_cheque_script"); ?>
