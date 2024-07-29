<?php echo form_open(get_uri("countries/save"), array("id" => "tax-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('iso_code'); ?></label>
        <div class=" col-md-9">
            <?php 
            echo form_input(array(
                "id" => "iso_code",
                "name" => "iso_code",
                "value" => $model_info->iso,
                "class" => "form-control",
                "maxlength" => 2,
                "placeholder" => lang('iso_code'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="percentage" class=" col-md-3"><?php echo lang('country'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "country_name",
                "name" => "country_name",
                "value" => $model_info->countryName ,
                "class" => "form-control",
                "placeholder" => lang('country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('country_code'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_inputnumber(array(
                "id" => "number_code",
                "name" => "number_code",
                "value" => $model_info->numberCode,
                "class" => "form-control",
                "placeholder" => lang('country_code'),
                "min"=> 0,
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
     <div class="form-group">
        <label for="currency_name" class=" col-md-3"><?php echo lang('currency_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "currency_name",
                "name" => "currency_name",
                "value" => $model_info->currency_name,
                "class" => "form-control",
                "placeholder" => lang('currency_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="currency" class=" col-md-3"><?php echo lang('currency'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "currency",
                "name" => "currency",
                "value" => $model_info->currency,
                "class" => "form-control",
                "placeholder" => lang('currency'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="currency_symbol" class=" col-md-3"><?php echo lang('currency_symbol'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "currency_symbol",
                "name" => "currency_symbol",
                "value" => $model_info->currency_symbol,
                "class" => "form-control",
                "placeholder" => lang('currency_symbol'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
            <div class="form-group">
        <label for="vat_type" class=" col-md-3"><?php echo lang('vat_type'); ?></label>
        <div class="col-md-9">
           <?php 
                echo form_dropdown("vat_type", $vat_dropdown, array($model_info->vat_type), "class='select2 validate-hidden mini' id='vat_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
        </div>
    </div>
    <div class="form-group">
                        <label for="language" class=" col-md-3"><?php echo lang('language'); ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown(
                                    "language", $language_dropdown, $model_info->language, "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
    <label for="timezone" class="col-md-3"><?php echo lang('timezone'); ?></label>
    <div class="col-md-9">
        <?php
        echo form_dropdown(
            "timezone", 
            $timezone_dropdown, 
            isset($country_info->timezone) ? $country_info->timezone : '', 
            "class='select2 mini'"
        );
        ?>
    </div>
</div>
<div class="form-group">
    <label for="date_format" class="col-md-3"><?php echo lang('date_format'); ?></label>
    <div class="col-md-9">
        <?php
        echo form_dropdown(
            "date_format", 
            array(
                "d-m-Y" => "d-m-Y",
                "m-d-Y" => "m-d-Y",
                "Y-m-d" => "Y-m-d",
                "d/m/Y" => "d/m/Y",
                "m/d/Y" => "m/d/Y",
                "Y/m/d" => "Y/m/d",
                "d.m.Y" => "d.m.Y",
                "m.d.Y" => "m.d.Y",
                "Y.m.d" => "Y.m.d",
            ), 
            isset($country_info->date_format) ? $country_info->date_format : '', 
            "class='select2 mini'"
        );
        ?>
    </div>
</div>

            <div class="form-group">
                <label for="time_format" class=" col-md-3"><?php echo lang('time_format'); ?></label>
                <div class=" col-md-9">
                    <?php
                            echo form_dropdown(
                                    "time_format", array(
                                "capital" => "12 AM",
                                "small" => "12 am",
                                "24_hours" => "24 hours"
                                    ), $model_info->time_format, "class='select2 mini'"
                            );
                            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="first_day_of_week" class=" col-md-3"><?php echo lang('holiday_day_of_week'); ?></label>
                <div class=" col-md-9">
                   <?php
                            /*echo form_dropdown(
                                    "first_day_of_week", array(
                                "0" => "Sunday",
                                "1" => "Monday",
                                "2" => "Tuesday",
                                "3" => "Wednesday",
                                "4" => "Thursday",
                                "5" => "Friday",
                                "6" => "Saturday"
                                    ), $model_info->first_day_of_week, "class='select2 mini'"
                            );*/
                            echo form_input(array(
                                "id" => "first_day_of_week",
                                "name" => "first_day_of_week",
                                "value" => $model_info->first_day_of_week,
                                "class" => "form-control",
                                "placeholder" => lang('holiday_day_of_week')
                            ));
                            ?>
                </div>
            </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#tax-form").appForm({
            onSuccess: function(result) {
                $("#taxes-table").appTable({newData: result.data, dataId: result.id});
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                   
                    //window.location = 'Payslip/pays';
                    window.location = "<?php echo site_url('countries/view'); ?>/" + result.id;
                }
            }
        });
        $("#title").focus();
        $("#tax-form .select2").select2();

        $("#first_day_of_week").select2({
            multiple: true,
            data: <?php echo ($holiday_of_week_dropdown); ?>
        });
    });
</script>    