<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "work_orders";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_work_order_settings"), array("id" => "work_order-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("work_order_settings"); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="logo" class=" col-md-2"><?php echo lang('work_order_logo'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="work_order-logo-preview" src="<?php echo get_file_uri(get_setting("system_file_path") . get_setting("work_order_logo")); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="work_order_logo_file" class="cropbox-upload upload" name="work_order_logo_file" type="file" data-height="100" data-width="300" data-preview-container="#work_order-logo-preview" data-input-field="#work_order_logo" />
                            </div>
                            <input type="hidden" id="work_order_logo" name="work_order_logo" value=""  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="work_order_prefix" class=" col-md-2"><?php echo lang('work_order_prefix'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "work_order_prefix",
                                "name" => "work_order_prefix",
                                "value" => get_setting("work_order_prefix"),
                                "class" => "form-control",
                                "placeholder" => strtoupper(lang("work_order")) . " #"
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="work_order_color" class=" col-md-2"><?php echo lang('work_order_color'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "work_order_color",
                                "name" => "work_order_color",
                                "value" => get_setting("work_order_color"),
                                "class" => "form-control",
                                "placeholder" => "Ex. #e2e2e2"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="work_order_footer" class=" col-md-2"><?php echo lang('work_order_footer'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_textarea(array(
                                "id" => "work_order_footer",
                                "name" => "work_order_footer",
                                "value" => get_setting("work_order_footer"),
                                "class" => "form-control"
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="work_order_style" class=" col-md-2"><?php echo lang('work_order_style'); ?></label>
                        <div class="col-md-10">
                            <?php
                            $work_order_style = get_setting("work_order_style") ? get_setting("work_order_style") : "style_1";
                            ?>
                            <input type="hidden" id="work_order_style" name="work_order_style" value="<?php echo $work_order_style; ?>" />

                            <div class="clearfix invoice-styles">
                                <div data-value="style_1" class="item <?php echo $work_order_style != 'style_2' ? ' active ' : ''; ?>" >
                                    <img src="<?php echo get_file_uri("assets/images/invoice_style_1.png") ?>" alt="style_1" />
                                </div>
                                <div data-value="style_2" class="item <?php echo $work_order_style === 'style_2' ? ' active ' : ''; ?>" >
                                    <img src="<?php echo get_file_uri("assets/images/invoice_style_2.png") ?>" alt="style_2" />
                                </div>

                            </div>    
                        </div>
                    </div>
                   <!-- <div class="form-group">
                        <label for="send_bcc_to" class=" col-md-2"><?php echo lang('send_bcc_to'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "send_bcc_to",
                                "name" => "send_bcc_to",
                                "value" => get_setting("send_bcc_to"),
                                "class" => "form-control",
                                "placeholder" => lang("email")
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allow_partial_invoice_payment_from_clients" class=" col-md-2"><?php echo lang('allow_partial_invoice_payment_from_clients'); ?></label>

                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "allow_partial_invoice_payment_from_clients", array("1" => lang("yes"), "0" => lang("no")), get_setting('allow_partial_invoice_payment_from_clients'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_invoice_due_pre_reminder" class=" col-md-2"><?php echo lang('send_due_invoice_reminder_notification_before'); ?> <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>

                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "send_invoice_due_pre_reminder", array(
                                "" => " - ",
                                "1" => "1 " . lang("day"),
                                "2" => "2 " . lang("days"),
                                "3" => "3 " . lang("days"),
                                "5" => "5 " . lang("days"),
                                "7" => "7 " . lang("days"),
                                "10" => "10 " . lang("days"),
                                "14" => "14 " . lang("days"),
                                "15" => "15 " . lang("days"),
                                "20" => "20 " . lang("days"),
                                "30" => "30 " . lang("days"),
                                    ), get_setting('send_invoice_due_pre_reminder'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_invoice_due_after_reminder" class=" col-md-2"><?php echo lang('send_invoice_overdue_reminder_after'); ?> <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>

                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "send_invoice_due_after_reminder", array(
                                "" => " - ",
                                "1" => "1 " . lang("day"),
                                "2" => "2 " . lang("days"),
                                "3" => "3 " . lang("days"),
                                "5" => "5 " . lang("days"),
                                "7" => "7 " . lang("days"),
                                "10" => "10 " . lang("days"),
                                "14" => "14 " . lang("days"),
                                "15" => "15 " . lang("days"),
                                "20" => "20 " . lang("days"),
                                "30" => "30 " . lang("days"),
                                    ), get_setting('send_invoice_due_after_reminder'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="send_recurring_invoice_reminder_before_creation" class=" col-md-2"><?php echo lang('send_recurring_invoice_reminder_before_creation'); ?> <span class="help" data-toggle="tooltip" title="<?php echo lang('cron_job_required'); ?>"><i class="fa fa-question-circle"></i></span></label>

                        <div class="col-md-3">
                            <?php
                            echo form_dropdown(
                                    "send_recurring_invoice_reminder_before_creation", array(
                                "" => " - ",
                                "1" => "1 " . lang("day"),
                                "2" => "2 " . lang("days"),
                                "3" => "3 " . lang("days"),
                                "5" => "5 " . lang("days"),
                                "7" => "7 " . lang("days"),
                                "10" => "10 " . lang("days"),
                                "14" => "14 " . lang("days"),
                                "15" => "15 " . lang("days"),
                                "20" => "20 " . lang("days"),
                                "30" => "30 " . lang("days"),
                                    ), get_setting('send_recurring_invoice_reminder_before_creation'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div> -->
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php $this->load->view("includes/cropbox"); ?>

<?php
load_css(array(
    "assets/js/summernote/summernote.css",
    "assets/js/summernote/summernote-bs3.css"
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
    "assets/js/bootstrap-confirmation/bootstrap-confirmation.js",
));
?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#work_order-settings-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "work_order_footer") {
                        data[index]["value"] = encodeAjaxPostData(getWYSIWYGEditorHTML("#work_order_footer"));
                    }
                    if (obj.name === "work_order_logo") {
                        var image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = image;
                    }
                });
            },
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
                if ($("#work_order_logo").val()) {
                    location.reload();
                }
            }
        });
        $("#work_order-settings-form .select2").select2();

        initWYSIWYGEditor("#work_order_footer", {height: 100});

        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });

        $(".invoice-styles .item").click(function () {
            $(".invoice-styles .item").removeClass("active");
            $(this).addClass("active");
            $("#work_order_style").val($(this).attr("data-value"));
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>