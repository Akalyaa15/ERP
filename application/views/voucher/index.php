
<!-- <?php /*
            $Designation = $this->Designation_model->get_details()->result();
            foreach ($Designation as $Desig) {
$role_data = array(
        "title" => $Desig->title.'-'.$Desig->department_title);
        $save_role_data = $this->Roles_model->save($role_data);
              } */ ?> -->
<div id="page-content" class="p20 clearfix">
    <div class="panel clearfix">
            <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('voucher'); ?></h4></li>
            <li><a id="monthly-estimate-button" class="active" role="presentation" href="javascript:;" data-target="#monthly-estimates"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("estimates/yearly/"); ?>" data-target="#yearly-estimates"><?php echo lang('yearly'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("voucher/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_voucher'), array("class" => "btn btn-default", "title" => lang('add_voucher'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-estimates">
                <div class="table-responsive">
                    <table id="monthly-estimate-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-estimates"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadEstimatesTable = function (selector, dateRange) {
        $(selector).appTable({
            source: '<?php echo_uri("voucher/list_data") ?>',
            order: [[0, "desc"]],
            dateRangeType: dateRange,
            radioButtons: [{text: '<?php echo lang("my_voucher") ?>', name: "voucher", value:'created_user_id', isChecked: true}, {text: '<?php echo lang("voucher") ?>', name: "voucher", value: 'line_manager', isChecked: false}, <?php if($this->login_user->is_admin=="1"){ ?> {text: '<?php echo lang("all") ?>', name: "voucher", value: "all", isChecked: false}
            <?php } ?> 
             ], 
                        filterDropdown: [{name: "status", class: "w150", options: <?php $this->load->view("voucher/voucher_statuses_dropdown"); ?>},{name: "team_member", class: "w150", options: <?php echo $members_dropdown; ?>}, {name: "line_manager_dropdown", class: "w150", options: <?php echo $line_manager_dropdown; ?>}],
            columns: [
                {title: "<?php echo lang("voucher") ?> ", "class": "w15p"},
                //{title: "<?php echo lang("team_member") ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("requested_date") ?>", "iDataSort": 2, "class": "w10p"},
                {title: "<?php echo lang("due_date") ?>", "iDataSort": 2, "class": "w10p"},
                {title: "<?php echo lang("voucher_note") ?>", "iDataSort": 2, "class": "w20p"},
                {title: "<?php echo lang("voucher_type") ?>", "iDataSort": 2, "class": "w20p"},
                {title: "<?php echo lang("terms_of_payment") ?>", "iDataSort": 2, "class": "w20p"},
                {title: "<?php echo lang("status") ?>", "class": "text-center"}
<?php echo $custom_field_headers; ?>,
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
           
        });
    };

    $(document).ready(function () {
        $("#monthly-estimate-button").trigger("click");
        loadEstimatesTable("#monthly-estimate-table", "monthly");
    });

</script>