<?php
$this->load->view("includes/summernote");
?>
<?php
 $share_with_explode = explode(":", $model_info->share_with);
            $model_info->share_with_specific = $share_with_explode[0];
        ?>
<div id="page-content" class="clearfix p20">
    <div class="panel view-container">
        <div id="announcement-dropzone" class="post-dropzone">
            <?php echo form_open(get_uri("announcements/save"), array("id" => "announcement-form", "class" => "general-form", "role" => "form")); ?>

            <div class="panel-default">

                <div class="page-title clearfix">
                    <?php if ($model_info->id) { ?>
                        <h1><?php echo lang('edit_announcement'); ?></h1>
                        <div class="title-button-group">
                            <?php echo anchor(get_uri("announcements/view/" . $model_info->id), "<i class='fa fa-external-link-square'></i> " . lang('view'), array("class" => "btn btn-default", "title" => lang('view'))); ?>
                        </div>
                    <?php } else { ?>
                        <h1><?php echo lang('add_announcement'); ?></h1>
                    <?php } ?>
                </div>

                <div class="panel-body">
                    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
                    <div class="form-group">
                        <label for="title" class="col-md-12"><?php echo lang('title'); ?></label>
                        <div class=" col-md-12">
                            <?php
                            echo form_input(array(
                                "id" => "title",
                                "name" => "title",
                                "value" => $model_info->title,
                                "class" => "form-control",
                                "placeholder" => lang('title'),
                                "autofocus" => true,
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">

                        <div class=" col-md-12">
                            <?php
                            echo form_textarea(array(
                                "id" => "description",
                                "name" => "description",
                                "value" => $model_info->description,
                                "placeholder" => lang('description'),
                                "class" => "form-control"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="clearfix">
                        <label for="start_date" class="col-md-2"><?php echo lang('start_date'); ?></label>
                        <div class="form-group col-md-4">
                            <?php
                            echo form_input(array(
                                "id" => "start_date",
                                "name" => "start_date",
                                "value" => $model_info->start_date,
                                "class" => "form-control",
                                "placeholder" => "YYYY-MM-DD",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>

                        <label for="end_date" class="col-md-2"><?php echo lang('end_date'); ?></label>
                        <div class="form-group col-md-4">
                            <?php
                            echo form_input(array(
                                "id" => "end_date",
                                "name" => "end_date",
                                "value" => $model_info->end_date,
                                "class" => "form-control",
                                "placeholder" => "YYYY-MM-DD",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                                "data-rule-greaterThanOrEqual" => "#start_date",
                                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
                            ));
                            ?>
                        </div>
                    </div>

                    <?php if (isset($client_id) && $client_id) { ?>
    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
    <?php } else if (isset($vendor_id) && $vendor_id) { ?>
    <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>" />
<?php } else if (count($clients_dropdown)) { ?>
    <div class="form-group">
        <label for="client_id" class="col-md-2"><?php echo lang('client'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_dropdown("client_id", $clients_dropdown, isset($model_info->client_id) ? $model_info->client_id : "", "class='form-control'");
            ?>
        </div>
    </div>
    <?php if (isset($partner_id)) { ?>
        <input type="hidden" name="partner_id" value="<?php echo $partner_id; ?>" />
    <?php } else if (isset($client_id)) { ?>
        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
    <?php } else if (count($partners_dropdown)) { ?>
        <div class="form-group">
            <label for="partner_id" class="col-md-2"><?php echo lang('partner'); ?></label>
            <div class="col-md-10">
                <?php
                echo form_input(array(
                    "id" => "partners_dropdown",
                    "name" => "partner_id",
                    "value" => isset($model_info->partner_id) ? $model_info->partner_id : "",
                    "class" => "form-control"
                ));
                ?>
            </div>
        </div>
    <?php } ?>

<?php } ?>

        
        <div class="form-group">
                        <label for="share_with" class=" col-md-2"><?php echo lang('share_with'); ?></label>
                        <div class="col-md-10">
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_all",
                            "name" => "share_with",
                            "value" => "all_members",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with === "all_members") ? true : false);
                        ?>
                        <label for="share_with_all"><?php echo lang("all_team_members"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_clients",
                            "name" => "share_with",
                            "value" => "all_clients",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with === "all_clients") ? true : false);
                        ?>
                        <label for="share_with_clients"><?php echo lang("all_team_clients"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_vendors",
                            "name" => "share_with",
                            "value" => "all_vendors",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with === "all_vendors") ? true : false);
                        ?>
                        <label for="share_with_vendors"><?php echo lang("all_vendors"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_resource",
                            "name" => "share_with",
                            "value" => "all_resource",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with === "all_resource") ? true : false);
                        ?>
                        <label for="share_with_resource"><?php echo lang("all_outsource_members"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_partners",
                            "name" => "share_with",
                            "value" => "all_partners",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with === "all_partners") ? true : false);
                        ?>
                        <label for="share_with_partners"><?php echo lang("all_partners"); ?></label>
                    </div>
                    <div class="form-group mb0">
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_specific_radio_button",
                            "name" => "share_with",
                            "value" => "specific",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with&& $model_info->share_with != "all_members" && $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors"&&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners" && $model_info->share_with_specific != "outsource_member" && $model_info->share_with_specific != "vendor_contact" && $model_info->share_with_specific != "partner_contact"&& $model_info->share_with_specific != "contact") ? true : false);
                        ?>
                        <label for="share_with_specific_radio_button"><?php echo lang("specific_members_and_teams"); ?>:</label>
                        <div class="specific_dropdown" style="display: none;">
                            <input type="text" value="<?php echo ($model_info->share_with && $model_info->share_with != "all_members"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors" &&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners" && $model_info->share_with_specific != "outsource_member"&& $model_info->share_with_specific != "vendor_contact"&& $model_info->share_with_specific != "partner_contact" && $model_info->share_with_specific != "contact") ? $model_info->share_with : ""; ?>" name="share_with_specific" id="share_with_specific" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
                    <div class="form-group mb0">
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_resource_specific_radio_button",
                            "name" => "share_with",
                            "value" => "resource_specific",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with&& $model_info->share_with != "all_members" && $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors"&&$model_info->share_with != "all_resource" &&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "member" && $model_info->share_with_specific != "partner_contact"&& $model_info->share_with_specific != "vendor_contact" && $model_info->share_with_specific != "contact") ? true : false);
                        ?>
                        <label for="share_with_resource_specific_radio_button"><?php echo lang("specfic_outsource_members_and_teams"); ?>:</label>
                        <div class="specific_dropdown" style="display: none;">
                            <input type="text" value="<?php echo ($model_info->share_with && $model_info->share_with != "all_members"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors" &&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners" && $model_info->share_with_specific != "member"&& $model_info->share_with_specific != "vendor_contact"&& $model_info->share_with_specific != "partner_contact" && $model_info->share_with_specific != "contact") ? $model_info->share_with : ""; ?>" name="share_with_resource_specific" id="share_with_resource_specific" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_outsource_members_and_teams'); ?>"  />
                        </div>
                    </div>
                    <div id="share-with-vendor-contact" class="form-group mb0 hide">
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_vendor_contact_radio_button",
                            "name" => "share_with",
                            "value" => "specific_vendor_contacts",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with && $model_info->share_with != "all_members"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors" &&$model_info->share_with != "all_resource" &&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "outsource_member"&& $model_info->share_with_specific != "member" && $model_info->share_with_specific != "partner_contact"&& $model_info->share_with_specific != "contact" && $model_info->share_with_specific != "team") ? true : false);
                        ?>
                        <label for="share_with_vendor_contact_radio_button"><?php echo lang("specific_vendor_contacts"); ?>:</label>
                        <div class="specific_dropdown" style="display: none;">
                            <input type="text" value="<?php echo ($model_info->share_with && $model_info->share_with != "all_members"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors"&&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "contact" && $model_info->share_with_specific != "outsource_member" && $model_info->share_with_specific != "partner_contact"&& $model_info->share_with_specific != "member") ? $model_info->share_with : ""; ?>" name="share_with_specific_vendor_contact" id="share_with_specific_vendor_contact" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_vendor_contacts'); ?>"  />
                        </div>
                    </div>

                  <div id="share-with-client-contact" class="form-group mb0 hide">
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_client_contact_radio_button",
                            "name" => "share_with",
                            "value" => "specific_client_contacts",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with && $model_info->share_with != "all_members" && $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors"&&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "vendor_contact" && $model_info->share_with_specific != "outsource_member" && $model_info->share_with_specific != "partner_contact"&& $model_info->share_with_specific != "member" && $model_info->share_with_specific != "team") ? true : false);
                        ?>
                        <label for="share_with_client_contact_radio_button"><?php echo lang("specific_client_contacts"); ?>:</label>
                        <div class="specific_dropdown" style="display: none;">
                            <input type="text" value="<?php echo ($model_info->share_with && $model_info->share_with != "all_members"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors" &&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "vendor_contact" && $model_info->share_with_specific != "outsource_member"&& $model_info->share_with_specific != "partner_contact"&& $model_info->share_with_specific != "member") ? $model_info->share_with : ""; ?>" name="share_with_specific_client_contact" id="share_with_specific_client_contact" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_client_contacts'); ?>"  />
                        </div>
                    </div>


                    <div id="share-with-partner-contact" class="form-group mb0 hide">
                        <?php
                        echo form_radio(array(
                            "id" => "share_with_partner_contact_radio_button",
                            "name" => "share_with",
                            "value" => "specific_partner_contacts",
                            "class" => "toggle_specific",
                                ), $model_info->share_with, ($model_info->share_with && $model_info->share_with != "all_members" && $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors"&&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "vendor_contact" && $model_info->share_with_specific != "outsource_member"&& $model_info->share_with_specific != "contact"&& $model_info->share_with_specific != "member" && $model_info->share_with_specific != "team") ? true : false);
                        ?>
                        <label for="share_with_partner_contact_radio_button"><?php echo lang("specific_partner_contacts"); ?>:</label>
                        <div class="specific_dropdown" style="display: none;">
                            <input type="text" value="<?php echo ($model_info->share_with && $model_info->share_with != "all_members"&& $model_info->share_with != "all_clients"&& $model_info->share_with != "all_vendors" &&$model_info->share_with != "all_resource"&&$model_info->share_with != "all_partners"&& $model_info->share_with_specific != "vendor_contact"&& $model_info->share_with_specific != "contact" && $model_info->share_with_specific != "outsource_member"&& $model_info->share_with_specific != "member") ? $model_info->share_with : ""; ?>" name="share_with_specific_partner_contact" id="share_with_specific_partner_contact" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_partner_contacts'); ?>"  />
                        </div>
                    </div>

                    
                    </div>
                </div>
                   
                    <div class="form-group">
                        <label class=" col-md-2"></label>
                        <div class="col-md-10">
                            <?php
                            $this->load->view("includes/file_list", array("files" => $model_info->files));
                            ?>
                        </div>
                    </div>
                </div>


                <?php $this->load->view("includes/dropzone_preview"); ?>    

                <div class="panel-footer clearfix">
                    <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                    <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div> 
    </div> 
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#announcement-form").appForm({
            ajaxSubmit: false
        });
        $("#title").focus();

        initWYSIWYGEditor("#description", {
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['hr', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']]
            ],
            onImageUpload: function (files, editor, welEditable) {
                //insert image url
            },
            lang: "<?php echo lang('language_locale_long'); ?>"
        });

        setDatePicker("#start_date, #end_date");


        var uploadUrl = "<?php echo get_uri("announcements/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("announcements/validate_announcement_file"); ?>";

        var dropzone = attachDropzoneWithForm("#announcement-dropzone", uploadUrl, validationUrl);
          get_specific_dropdown($("#share_with_specific"), <?php echo ($members_and_teams_dropdown); ?>);
          get_resource_specific_dropdown($("#share_with_resource_specific"), <?php echo ($outsource_members_and_teams_dropdown); ?>);
var partnerId = "<?php echo $model_info->partner_id; ?>";

        if (partnerId && partnerId != "0") {
            prepareShareWithPartnerContactsDropdown(partnerId);
        }

        var clientId = "<?php echo $model_info->client_id; ?>";

        if (clientId && clientId != "0") {
            prepareShareWithClientContactsDropdown(clientId);
        }

        var vendorId = "<?php echo $model_info->vendor_id; ?>";

        if (vendorId && vendorId != "0") {
            prepareShareWithVendorContactsDropdown(vendorId);
        }
$('#partners_dropdown').select2({data: <?php echo json_encode($partners_dropdown); ?>}).on("change", function () {
            prepareShareWithPartnerContactsDropdown($(this).val());
        });
        //show the specific client contacts readio button after select any client
        $('#clients_dropdown').select2({data: <?php echo json_encode($clients_dropdown); ?>}).on("change", function () {
            prepareShareWithClientContactsDropdown($(this).val());
        });

        $('#vendors_dropdown').select2({data: <?php echo json_encode($vendors_dropdown); ?>}).on("change", function () {
            prepareShareWithVendorContactsDropdown($(this).val());

    });
    function prepareShareWithPartnerContactsDropdown(partnerId) {
            if (partnerId) {
                $("#share-with-partner-contact").removeClass("hide");
                $("#share_with_partner_contact_radio_button").click();
                $("#share_with_partner_contact_radio_button").click();
                $.ajax({
                    url: "<?php echo get_uri("announcements/get_all_contacts_of_partner") ?>" + "/" + partnerId,
                    dataType: "json",
                    success: function (result) {

                        if (result.length) {
                            get_specific_dropdown($("#share_with_specific_partner_contact"), result);
                        } else {
                            //if no client contact exists, then don't show the share with client contacts option
                            $("#share-with-partner-contact").addClass("hide");
                            prepareShareWithPartnerContactsDropdown();
                        }

                    }
                });
            } else {
                $("#share-with-partner-contact").addClass("hide");
                var $element = $(".toggle_specific:checked");
                if ($element.val() === "specific_partner_contacts") {
                    //unselect the specific_client_contacts
                    $("#all_members").trigger("click");
                    toggle_specific_dropdown();
                }
            }
        }
        function prepareShareWithClientContactsDropdown(clientId) {
            if (clientId) {
                $("#share-with-client-contact").removeClass("hide");
                $("#share_with_client_contact_radio_button").click();
                $("#share_with_client_contact_radio_button").click();
                $.ajax({
                    url: "<?php echo get_uri("announcements/get_all_contacts_of_client") ?>" + "/" + clientId,
                    dataType: "json",
                    success: function (result) {

                        if (result.length) {
                            get_specific_dropdown($("#share_with_specific_client_contact"), result);
                            
                        } else {
                            //if no client contact exists, then don't show the share with client contacts option
                            $("#share-with-client-contact").addClass("hide");
                            prepareShareWithClientContactsDropdown();
                        }

                    }
                });
            } else {
                $("#share-with-client-contact").addClass("hide");
                var $element = $(".toggle_specific:checked");
                if ($element.val() === "specific_client_contacts") {
                    //unselect the specific_client_contacts
                    $("#all_members").trigger("click");
                    toggle_specific_dropdown();
                }
            }
        }

        function prepareShareWithVendorContactsDropdown(vendorId) {
            if (vendorId) {
                $("#share-with-vendor-contact").removeClass("hide");
                $("#share_with_vendor_contact_radio_button").click();
                 $("#share_with_vendor_contact_radio_button").click();
                $.ajax({
                    url: "<?php echo get_uri("announcements/get_all_contacts_of_vendor") ?>" + "/" + vendorId,
                    dataType: "json",
                    success: function (result) {

                        if (result.length) {
                            get_specific_dropdown($("#share_with_specific_vendor_contact"), result);
                        } else {
                            //if no client contact exists, then don't show the share with client contacts option
                            $("#share-with-vendor-contact").addClass("hide");
                            prepareShareWithVendorContactsDropdown();
                        }

                    }
                });
            } else {
                $("#share-with-vendor-contact").addClass("hide");
                var $element = $(".toggle_specific:checked");
                if ($element.val() === "specific_vendor_contacts") {
                    //unselect the specific_client_contacts
                    $("#all_members").trigger("click");
                    toggle_specific_dropdown();
                }
            }
        }

        function get_specific_dropdown(container, data) {
            setTimeout(function () {
                container.select2({
                    multiple: true,
                    formatResult: teamAndMemberSelect2Format,
                    formatSelection: teamAndMemberSelect2Format,
                    data: data
                });
            }, 100);
        }
        function get_resource_specific_dropdown(container, data) {
            setTimeout(function () {
                container.select2({
                    multiple: true,
                    formatResult: teamAndMemberSelect2Format,
                    formatSelection: teamAndMemberSelect2Format,
                    data: data
                });
            }, 100);
        }

        $(".toggle_specific").click(function () {
            toggle_specific_dropdown();
        });

        toggle_specific_dropdown();

        function toggle_specific_dropdown() {
            $(".specific_dropdown").hide().find("input").removeClass("validate-hidden");

            var $element = $(".toggle_specific:checked");
            if ($element.val() === "specific" || $element.val() === "specific_client_contacts") {
                var $dropdown = $element.closest("div").find("div.specific_dropdown");
                $dropdown.show().find("input").addClass("validate-hidden");
            }
            if ($element.val() === "specific" || $element.val() === "specific_vendor_contacts") {
                var $dropdown = $element.closest("div").find("div.specific_dropdown");
                $dropdown.show().find("input").addClass("validate-hidden");
            }
            if ($element.val() === "specific" || $element.val() === "resource_specific") {
                var $dropdown = $element.closest("div").find("div.specific_dropdown");
                $dropdown.show().find("input").addClass("validate-hidden");
            }
            if ($element.val() === "specific" || $element.val() === "specific_partner_contacts") {
                var $dropdown = $element.closest("div").find("div.specific_dropdown");
                $dropdown.show().find("input").addClass("validate-hidden");
            }
        }

        

        $("#announcement-form .select2").select2();

        //show/hide recurring fields
        

        $('[data-toggle="tooltip"]').tooltip();

    });
</script>    <script type="text/javascript">

$('#partners_dropdown').change(function () {
   $("#share-with-partner-contact").show();
    $("#clients_dropdown").select2("val", "");
    $("#share-with-client-contact").hide();
$("#vendors_dropdown").select2("val", "");
 $("#share-with-vendor-contact").hide();
});
$('#clients_dropdown').change(function () {
  
   $("#share-with-client-contact").show();
    $("#partners_dropdown").select2("val", "");
    $("#share-with-partner-contact").hide();
$("#vendors_dropdown").select2("val", "");
 $("#share-with-vendor-contact").hide();
});
$('#vendors_dropdown').change(function () {
  
   $("#share-with-vendor-contact").show();
    $("#partners_dropdown").select2("val", "");
    $("#share-with-partner-contact").hide();
$("#clients_dropdown").select2("val", "");
 $("#share-with-client-contact").hide();
});
</script>
<script type="text/javascript">

$('#share_with_all,#share_with_clients,#share_with_vendors,#share_with_partners,#share_with_resource,#share_with_specific_radio_button,#share_with_resource_specific_radio_button').click(function () {
   $('#vendors_dropdown').val(null).trigger("change");
    $('#clients_dropdown').val(null).trigger("change");
     $('#partners_dropdown').val(null).trigger("change");
});


</script>


<?php 
if(!$model_info->share_with)
{?>
<script type="text/javascript" >
$( document ).ready(function() {
$("#share_with_all").click() 
});
</script>
<?php } ?>
