<?php  
$options = array(
            "id" => get_setting('company_country'),
                   );
        $country_id_name = $this->Countries_model->get_details($options)->row();
        $country_dummy_name =$country_id_name->countryName;
        ?>
<div id="page-content" class="p20 clearfix">
    <div class="row">

        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "general";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_general_settings"), array("id" => "general-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                   <a class="btn btn-primary" href="javascript:window.history.go(-1);">❮ Go Back</a>
     <div class="panel-default panel-heading">
                    <h4><?php echo lang("general_settings"); ?></h4>
                </div>
                <div class="panel-body post-dropzone">
                    <div class="form-group">
                        <label for="logo" class=" col-md-2"><?php echo lang('site_logo'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="site-logo-preview" src="<?php echo get_file_uri(get_setting("system_file_path") . get_setting("site_logo")); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="site_logo_file" class="cropbox-upload upload" name="site_logo_file" type="file" data-height="40" data-width="175" data-preview-container="#site-logo-preview" data-input-field="#site_logo" />
                            </div>
                            <input type="hidden" id="site_logo" name="site_logo" value=""  />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="favicon" class="col-md-2"><?php echo lang('favicon'); ?></label>
                        <div class="col-lg-10">
                            <div class="pull-left mr15">
                                <img id="favicon-preview" src="<?php echo get_favicon_url(); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="favicon_file" class="cropbox-upload upload" name="favicon_file" type="file" data-height="32" data-width="32" data-preview-container="#favicon-preview" data-input-field="#favicon" />
                            </div>
                            <input type="hidden" id="favicon" name="favicon" value="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="show_logo_in_signin_page" class=" col-md-2"><?php echo lang('show_logo_in_signin_page'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "show_logo_in_signin_page", array(
                                "no" => lang("no"),
                                "yes" => lang("yes")
                                    ), get_setting('show_logo_in_signin_page'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="show_background_image_in_signin_page" class=" col-md-2"><?php echo lang('show_background_image_in_signin_page'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "show_background_image_in_signin_page", array(
                                "no" => lang("no"),
                                "yes" => lang("yes")
                                    ), get_setting('show_background_image_in_signin_page'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" col-md-2"><?php echo lang('signin_page_background'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="signin-background-preview" style="max-width: 100px; max-height: 80px;" src="<?php echo get_file_uri(get_setting("system_file_path") . "sigin-background-image.jpg"); ?>" alt="..." />
                            </div>
                            <div class="pull-left mr15">
                                <?php $this->load->view("includes/dropzone_preview"); ?>    
                            </div>
                            <div class="pull-left upload-file-button btn btn-default btn-xs">
                                <span>...</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="app_title" class=" col-md-2"><?php echo lang('app_title'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "app_title",
                                "name" => "app_title",
                                "value" => get_setting('app_title'),
                                "class" => "form-control",
                                "placeholder" => lang('app_title'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="language" class=" col-md-2"><?php echo lang('language'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "language", $language_dropdown, get_setting('language'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="timezone" class=" col-md-2"><?php echo lang('timezone'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "timezone", $timezone_dropdown, get_setting('timezone'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_format" class=" col-md-2"><?php echo lang('date_format'); ?></label>
                        <div class="col-md-10">
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
                                    ), get_setting('date_format'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="time_format" class=" col-md-2"><?php echo lang('time_format'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "time_format", array(
                                "capital" => "12 AM",
                                "small" => "12 am",
                                "24_hours" => "24 hours"
                                    ), get_setting('time_format'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="first_day_of_week" class=" col-md-2"><?php echo lang('first_day_of_week'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "first_day_of_week", array(
                                "0" => "Sunday",
                                "1" => "Monday",
                                "2" => "Tuesday",
                                "3" => "Wednesday",
                                "4" => "Thursday",
                                "5" => "Friday",
                                "6" => "Saturday"
                                    ), get_setting('first_day_of_week'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <!-- <div class="form-group">
        <label for="company_country" class=" col-md-2"><?php echo lang('country'); ?></label>
        <div class="col-md-10">
            <?php /*
            echo form_input(array(
                "id" => "company_country",
                "name" => "company_country",
                "value" => get_setting('company_country'),
                "class" => "form-control validate-hidden",
                "placeholder" => lang('company_country'),
               "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            */?>
            <a id="country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div> -->
    <?php if(!get_setting('company_country')){ ?>
                    <div class="form-group">
        <label for="company_country" class=" col-md-2"><?php echo lang('country'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "company_country",
                "name" => "company_country",
                "value" => get_setting('company_country'),
                
                "class" => "form-control validate-hidden",
                "placeholder" => lang('company_country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group" id="aa" style="display:none;">
        <label for="company_country" class=" col-md-2"><?php echo lang('country'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "company_country",
                "name" => "company_country",
                "value" => get_setting('company_country'),
                
                "class" => "form-control validate-hidden",
                "placeholder" => lang('company_country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div>
    <?php } ?>
    <?php if(get_setting('company_country')){ ?>

<div class="form-group" id="bb"  >
        <label for="company_country" class=" col-md-2"><?php echo lang('country'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "dummy_company_country",
                "name" => "dummy_company_country",
                //"value" => get_setting('company_country'),
                "value" => $country_dummy_name,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('company_country'),
                "readonly"=>true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),

            ));
            ?>
            <a id="dummy_country_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span id="close">×</span></a>
        </div>
    </div>

    <?php } ?>
                    <!--div class="form-group">
                        <label for="default_currency" class=" col-md-2"><?php echo lang('currency'); ?></label>
                        <div class="col-md-10">
                            <?php /*
                            echo form_dropdown(
                                    "default_currency", $currency_dropdown, get_setting('default_currency'), "class='select2 mini'"
                            );
                            */?>
                        </div>
                    </div-->
                    <div class="form-group">
                        <label for="default_currency" class=" col-md-2"><?php echo lang('currency'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "default_currency",
                                "name" => "default_currency",
                                "value" => get_setting('default_currency'),
                                "class" => "form-control",
                                "placeholder" => lang('currency'),
                                 "readonly"=>true,
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
                                "value" => get_setting('currency_symbol'),
                                "class" => "form-control",
                                "placeholder" => lang('currency_symbol'),
                                "data-rule-required" => true,
                                 "readonly"=>true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="currency_position" class=" col-md-2"><?php echo lang('currency_position'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "currency_position", array(
                                "left" => lang("left"),
                                "right" => lang("right")
                                    ), get_setting('currency_position'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="decimal_separator" class=" col-md-2"><?php echo lang('decimal_separator'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "decimal_separator", array("." => "Dot (.)", "," => "Comma (,)"), get_setting('decimal_separator'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_of_decimals" class=" col-md-2"><?php echo lang('no_of_decimals'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "no_of_decimals", array(
                                "0" => "0",
                                "2" => "2"
                                    ), get_setting('no_of_decimals') == "0" ? "0" : "2", "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
<div class="form-group">
                        
                        <label for="number_of_quantity" class=" col-md-2"><?php echo lang('number_of_quantity'); ?>  <span class="help" data-toggle="tooltip" title="<?php echo lang('number_of_quantity_invoice'); ?>"><i class="fa fa-question-circle"></i></span></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "number_of_quantity",
                                "name" => "number_of_quantity",
                                "value" => get_setting('number_of_quantity'),
                                "class" => "form-control",
                                "placeholder" => lang('number_of_quantity'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="accepted_file_formats" class=" col-md-2"><?php echo lang('accepted_file_format'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "accepted_file_formats",
                                "name" => "accepted_file_formats",
                                "value" => get_setting('accepted_file_formats'),
                                "class" => "form-control",
                                "placeholder" => lang('comma_separated'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rows_per_page" class=" col-md-2"><?php echo lang('rows_per_page'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "rows_per_page", array(
                                "10" => "10",
                                "25" => "25",
                                "50" => "50",
                                "100" => "100",
                                    ), get_setting('rows_per_page'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="scrollbar" class=" col-md-2"><?php echo lang('scrollbar'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "scrollbar", array(
                                "jquery" => "jQuery",
                                "native" => "Native"
                                    ), get_setting('scrollbar'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="item_purchase_code" class=" col-md-2"><?php echo lang('item_purchase_code'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "item_purchase_code",
                                "name" => "item_purchase_code",
                                "value" => get_setting('item_purchase_code') ? "******" : "",
                                "class" => "form-control",
                                //"placeholder" => "Envato Purchase Code",
                                "placeholder" => "Gems Office Manager",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>

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

<script type="text/javascript">
    $(document).ready(function () {
        $("#general-settings-form .select2").select2();

        $("#general-settings-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "invoice_logo" || obj.name === "site_logo"|| obj.name === "favicon") {
                        var image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = image;
                    }
                });
            },
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                if ($("#site_logo").val() || $("#invoice_logo").val()|| $("#favicon").val()) {
                    location.reload();
                }
            }
        });

        var uploadUrl = "<?php echo get_uri("settings/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("settings/validate_file"); ?>";

        var dropzone = attachDropzoneWithForm("#general-settings-form", uploadUrl, validationUrl, {maxFiles: 1});


        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });

         //re-initialize item suggestion dropdown on request
        $("#country_dropdwon_icon").click(function () {
            //$("#state").val("").attr('readonly', false)
            applySelect2OnItemTitle();
        })

        function applySelect2OnItemTitle() {
        $("#company_country").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("clients/get_country_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                
                
               
                
                $("#company_country").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                var country_name =$("#company_country").val();
                $.ajax({

                    url: "<?php echo get_uri("clients/get_country_item_info_suggestion"); ?>",
                    data: {item_name: e.val,country_name:country_name},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {

                            if (!$("#currency_symbol").val()) {
                                $("#currency_symbol").val(response.item_info.currency_symbol);
                            }

                            if (!$("#default_currency").val()) {
                                $("#default_currency").val(response.item_info.currency);
                            }
                            


                            
                       

                        }
                    }
                });
            }

        });
    }

    $("#dummy_country_dropdwon_icon").click(function () {
         $("#dummy_company_country").hide();
        // $("#country").show();
         $("#company_country").val("").attr('readonly', false).show();
         $("#aa").show();
         $("#bb").hide();
         
         $("#default_currency").select2("destroy").val("")
        
        $("#currency_symbol").val("")
             applySelect2OnItemTitle();
        })

    });
</script>
<script type="text/javascript">
    $("#company_country").on("change", function() {
   
        
        $("#default_currency").select2("destroy").val("")
        
        $("#currency_symbol").val("")
});
</script>
<script type="text/javascript">
    $("#close").on("click", function() {
         
        $("#company_country").val("")
        $("#default_currency").select2("destroy").val("")
        
        $("#currency_symbol").val("")
});
</script>