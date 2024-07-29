<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('earnings'); ?></h4>
        <div class="title-button-group">
            <?php
            echo modal_anchor(get_uri("countries/earnings_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_earnings'), array("class" => "btn btn-default", "title" => lang('add_earnings'), "data-post-country_id" => $country_id));
            ?>
        </div>
    </div>

    <div class="table-responsive">
        <table id="payslip-eanings-table" class="display" width="100%">            
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {


        $("#payslip-eanings-table").appTable({
           //source: '<?php /* echo_uri("earnings/list_data")*/ ?>',
           source: '<?php echo_uri("countries/earnings_list_data/" . $country_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("percentage"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>