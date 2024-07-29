<div class="clearfix">
    <?php if ($show_invoice_info) { ?>
        <?php if (!in_array("purchase_orders", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6 widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "total_purchase_orders")); ?>
            </div>
        <?php } ?>

        <?php if (!in_array("purchase_orders", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6  widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "purchase_order_value")); ?>
            </div>
        <?php } ?>

        <?php if (!in_array("purchase_orders", $hidden_menu) && !in_array("purchase_orders", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6  widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "purchase_order_payments")); ?>
            </div>
            <div class="col-md-3 col-sm-6  widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "purchase_order_due")); ?>
            </div>
        <?php } ?>

        <?php if ((in_array("purchase_orders", $hidden_menu)) && (in_array("purchase_order_payments", $hidden_menu))) { ?>
            <div class="col-sm-12 col-md-12" style="margin-top: 10%">
                <div class="text-center box">
                    <div class="box-content" style="vertical-align: middle; height: 100%">
                        <span class="fa fa-meh-o" style="font-size: 2000%; color:#CBCED0;"></span>
                    </div>
                </div>
            </div>
        <?php } ?>

    <?php } ?>
</div>

<div class="clearfix">
    <?php if ($show_estimate_info) { ?>
        <?php if (!in_array("work_orders", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6 widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "total_work_orders")); ?>
            </div>
        <?php } ?>

        <?php if (!in_array("work_orders", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6  widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "work_order_value")); ?>
            </div>
        <?php } ?>

        <?php if (!in_array("work_orders", $hidden_menu) && !in_array("work_order_payments", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6  widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "work_order_payments")); ?>
            </div>
            <div class="col-md-3 col-sm-6  widget-container">
                <?php $this->load->view("vendors/info_widgets/tab", array("tab" => "work_order_due")); ?>
            </div>
        <?php } ?>

        <?php if ((in_array("work_orders", $hidden_menu)) && (in_array("work_order_payments", $hidden_menu))) { ?>
            <div class="col-sm-12 col-md-12" style="margin-top: 10%">
                <div class="text-center box">
                    <div class="box-content" style="vertical-align: middle; height: 100%">
                        <span class="fa fa-meh-o" style="font-size: 2000%; color:#CBCED0;"></span>
                    </div>
                </div>
            </div>
        <?php } ?>

    <?php } ?>
</div>