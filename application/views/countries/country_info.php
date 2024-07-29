<div class="tab-content">
    <?php echo form_open(get_uri("countries/save_country_info/" . $country_info->id), array("id" => "general-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('country_info'); ?></h4>
        </div>
        <div class="panel-body">
             
            <div class="form-group">
                <label for="country_name" class=" col-md-2"><?php echo lang('iso_code'); ?></label>
                <div class=" col-md-10">
                    <?php
            echo form_input(array(
                "id" => "iso_code",
                "name" => "iso_code",
                "value" => $country_info->iso,
                "class" => "form-control",
                "maxlength" => 2,
                "placeholder" => lang('iso_code'),
                //"autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="country_name" class=" col-md-2"><?php echo lang('country'); ?></label>
                <div class=" col-md-10">
                    <?php
                 echo form_input(array(
                "id" => "country_name",
                "name" => "country_name",
                "value" => $country_info->countryName,
                "class" => "form-control",
                "placeholder" => lang('country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                //"readonly" =>true,
            ));
            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="country_code" class=" col-md-2"><?php echo lang('country_code'); ?></label>
                <div class=" col-md-10">
                    <?php
            echo form_input(array(
                "id" => "number_code",
                "name" => "number_code",
                "value" => $country_info->numberCode,
                "min"=> 0,
                "class" => "form-control",
                "placeholder" => lang('country_code'),
               // "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                //"readonly" =>true,
            ));
            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="currency_name" class=" col-md-2"><?php echo lang('currency_name'); ?></label>
                <div class=" col-md-10">
                     <?php
            echo form_input(array(
                "id" => "currency_name",
                "name" => "currency_name",
                "value" => $country_info->currency_name,
                "class" => "form-control",
                "placeholder" => lang('currency_name'),
               // "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="currency" class=" col-md-2"><?php echo lang('currency'); ?></label>
                <div class=" col-md-10">
                    <?php
            echo form_input(array(
                "id" => "currency",
                "name" => "currency",
                "value" => $country_info->currency,
                "class" => "form-control",
                "placeholder" => lang('currency'),
                //"autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                </div>
            </div>
             <div class="form-group">
                <label for="currency_symbol" class=" col-md-2"><?php echo lang('currency_symbol'); ?></label>
                <div class=" col-md-10">
                    <?php
            echo form_input(array(
                "id" => "currency_symbol",
                "name" => "currency_symbol",
                "value" => $country_info->currency_symbol,
                "class" => "form-control",
                "placeholder" => lang('currency_symbol'),
                //"autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
                </div>
            </div>
            <div class="form-group">
        <label for="vat_type" class=" col-md-2"><?php echo lang('vat_type'); ?></label>
        <div class="col-md-10">
           <?php 
                echo form_dropdown("vat_type", $vat_dropdown, array($country_info->vat_type), "class='select2 validate-hidden mini' id='vat_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
        </div>
    </div>
            <div class="form-group">
                        <label for="language" class=" col-md-2"><?php echo lang('language'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "language", $language_dropdown,  $country_info->language, "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
           
                             <div class="form-group">
                <label for="timezone" class=" col-md-2"><?php echo lang('timezone'); ?></label>
                <div class=" col-md-10">
                   <?php
                            echo form_dropdown(
                                    "timezone", $timezone_dropdown, $country_info->timezone, "class='select2 mini'"
                            );
                            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="date_format" class=" col-md-2"><?php echo lang('date_format'); ?></label>
                <div class=" col-md-10">
                   <?php
                            echo form_dropdown(
                                    "date_format", array(
                                "d-m-Y" => "d-m-Y",
                                "m-d-Y" => "m-d-Y",
                                "Y-m-d" => "Y-m-d",
                                "d/m/Y" => "d/m/Y",
                                "m/d/Y" => "m/d/Y",
                                "Y/m/d" => "Y/m/d",
                                "d.m.Y" => "d.m.Y",
                                "m.d.Y" => "m.d.Y",
                                "Y.m.d" => "Y.m.d",
                                    ), $country_info->date_format, "class='select2 mini'"
                            );
                            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="time_format" class=" col-md-2"><?php echo lang('time_format'); ?></label>
                <div class=" col-md-10">
                    <?php
                            echo form_dropdown(
                                    "time_format", array(
                                "capital" => "12 AM",
                                "small" => "12 am",
                                "24_hours" => "24 hours"
                                    ), $country_info->time_format, "class='select2 mini'"
                            );
                            ?>
                </div>
            </div>
            <div class="form-group">
                <label for="first_day_of_week" class=" col-md-2"><?php echo lang('holiday_day_of_week'); ?></label>
                <div class=" col-md-10">
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
                                    ), $country_info->first_day_of_week, "class='select2 mini'"
                            );*/
                             echo form_input(array(
                                "id" => "first_day_of_week",
                                "name" => "first_day_of_week",
                                "value" => $country_info->first_day_of_week,
                                "class" => "form-control",
                                "placeholder" => lang('holiday_day_of_week')
                            ));
                            ?>
                </div>
            </div>
            


            <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-2", "field_column" => "col-md-10")); ?>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#general-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                setTimeout(function () {
                    window.location.href = "<?php echo get_uri("countries/view/" . $country_info->id); ?>" + "/country_info";
                }, 500);
            }
        });
        $("#general-info-form .select2").select2();

        $("#first_day_of_week").select2({
            multiple: true,
            data: <?php echo ($holiday_of_week_dropdown); ?>
        });

        setDatePicker("#dob");

    });
</script>    