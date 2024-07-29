<?php $this->load->view("includes/cropbox"); ?>
<div id="page-content" class="clearfix">
    <div class="row bg-dark-success p20">
        <div class="col-md-6">
            <!-- start profile image -->
            <div class="box">
                <div class="box-content w200 text-center profile-image">
                    <?php
                    echo form_open(get_uri("branches/save_profile_image/" . $branch_info->id), array("id" => "profile-image-form", "class" => "general-form", "role" => "form"));
                    ?>
                    <div class="file-upload btn mt0 p0" style="vertical-align: top;  margin-left: -45px; ">
                        <span><i class="btn fa fa-camera"></i></span>
                        <input id="profile_image_file" class="upload" name="profile_image_file" type="file" data-height="200" data-width="200" data-preview-container="#profile-image-preview" data-input-field="#profile_image" />
                    </div>
                    <input type="hidden" id="profile_image" name="profile_image" value="" />
                    <span class="avatar avatar-lg">
                        <?php if ($branch_info->image) { ?>
                            <img id="profile-image-preview" src="<?php echo get_file_uri(get_general_file_path("branch_profile_image", $branch_info->id) . $branch_info->image) ?>" alt="...">
                        <?php } else { ?>
                            <img id="profile-image-preview" src="<?php echo get_avatar($branch_info->image); ?>" alt="...">
                        <?php } ?>
                    </span>
                    <h4 class=""><?php echo $branch_info->title; ?></h4>
                    <?php echo form_close(); ?>
                </div>
                <div class="box-content pl15">
                    <p class="p10 m0">
                        <?php if ($branch_info->branch_code) { ?>
                            <?php echo lang("branch_code") . ":"; ?> <label class="label label-info large"><strong><?php echo $branch_info->branch_code; ?></strong></label>
                        <?php } ?>
                    </p>
                    <p class="p10 m0">
                        <?php if ($branch_info->buid) { ?>
                            <?php echo lang("bu_id") . ":"; ?> <label class="label label-info large"><strong><?php echo $branch_info->buid; ?></strong></label>
                        <?php } ?>
                    </p>
                    <p class="p10 m0"><i class="fa fa-envelope-o"></i> <?php echo $branch_info->company_email ? $branch_info->company_email : "-"; ?></p>
                    <div class="p10 m0 clearfix">
                        <div class="pull-left"></div>
                    </div>
                </div>
            </div>
            <!-- end profile image -->
        </div>
        <div class="col-md-6">
            <p>
                <?php echo lang("company_id") . ": <b>" . $branch_info->company_name . "</b>"; ?>
            </p>
            <p>
                <?php echo lang("company_name") . ": <b>" . $branch_info->company . "</b>"; ?>
            </p>
            <?php if ($branch_info->company_address) { ?>
                <p>
                    <?php echo nl2br($branch_info->company_address); ?>
                    <?php if ($branch_info->company_city) { ?>
                        <br /><?php echo $branch_info->company_city; ?>
                    <?php } ?>
                    <?php if ($branch_info->company_state) { ?>
                        <br />
                        <?php
                        $state_no = is_numeric($branch_info->company_state);
                        if (!$state_no) {
                            $branch_info->company_state = 0;
                        }
                        $options = array(
                            "id" => $branch_info->company_state,
                        );
                        $state_id_name = $this->States_model->get_details($options)->row();
                        $state_dummy_name = $state_id_name ? $state_id_name->title : 'Unknown State';
                        echo $state_dummy_name;
                        ?>
                    <?php } ?>
                    <?php if ($branch_info->company_pincode) { ?>
                        <br /><?php echo $branch_info->company_pincode; ?>
                    <?php } ?>
                    <?php if ($branch_info->company_setup_country) { ?>
                        <br />
                        <?php
                        $country_no = is_numeric($branch_info->company_setup_country);
                        if (!$country_no) {
                            $branch_info->company_setup_country = 0;
                        }
                        $options = array(
                            "numberCode" => $branch_info->company_setup_country,
                        );
                        $country_id_name = $this->Countries_model->get_details($options)->row();
                        $country_dummy_name = $country_id_name ? $country_id_name->countryName : 'Unknown Country';
                        echo $country_dummy_name;
                        ?>
                    <?php } ?>
                </p>
                <p>
                    <?php if ($branch_info->company_website) {
                        $website = to_url($branch_info->company_website);
                        echo lang("website") . ": " . "<a target='_blank' href='" . $website . "' class='white-link'>$website</a>";
                    } ?>
                    <?php if ($branch_info->company_gst_number) { ?>
                        <br /><?php echo lang("gst_number") . ": " . $branch_info->company_gst_number; ?>
                    <?php } ?>
                    <?php if ($branch_info->company_phone) { ?>
                        <br /><?php echo lang("phone") . ": " . $branch_info->company_phone ?>
                    <?php } ?>
                </p>
            <?php } ?>
        </div>
    </div>

    <ul data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a role="presentation" href="<?php echo_uri("branches/branch_info/" . $branch_info->id); ?>" data-target="#branch-info"><?php echo lang('branch_info'); ?></a></li>
        <li><a role="presentation" href="<?php echo_uri("branches/payslip_info/" . $branch_info->id); ?>" data-target="#payslip-info"> <?php echo lang('payslip_settings'); ?></a></li>
        <li><a role="presentation" href="<?php echo_uri("branches/payslip_earnings_info/" . $branch_info->id); ?>" data-target="#payslip_earnings-info"><?php echo lang('earnings'); ?></a></li>
        <li><a role="presentation" href="<?php echo_uri("branches/payslip_deductions_info/" . $branch_info->id); ?>" data-target="#payslip_deductions-info"><?php echo lang('deductions'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="branch-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="payslip-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="payslip_earnings-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="payslip_deductions-info"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".upload").change(function () {
            if (typeof FileReader == 'function') {
                showCropBox(this);
            } else {
                $("#profile-image-form").submit();
            }
        });
        $("#profile_image").change(function () {
            $("#profile-image-form").submit();
        });

        $("#profile-image-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "profile_image") {
                        var profile_image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = profile_image;
                    }
                });
            },
            onSuccess: function (result) {
                if (typeof FileReader == 'function') {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    location.reload();
                }
            }
        });

        var tab = "<?php echo $tab; ?>";
        if (tab === "branch_info") {
            $("[data-target=#branch-info]").trigger("click");
        } else if (tab === "payslip_info") {
            $("[data-target=#payslip-info]").trigger("click");
        } else if (tab === "payslip_earnings_info") {
            $("[data-target=#payslip_earnings-info]").trigger("click");
        } else if (tab === "payslip_deductions_info") {
            $("[data-target=#payslip_deductions-info]").trigger("click");
        }
    });
</script>
<?php
$options = array("country_id" => $branch_info->id, "key_name" => "basic_salary");

$list_data = $this->Country_earnings_model->get_details($options)->result();
if (!$list_data) {
    $datas[] = array(
        "title" => "Basic Salary",
        "percentage" => 40,
        "status" => "active",
        "description" => "-",
        "country_id" => $branch_info->id,
        "key_name" => "basic_salary"
    );

    $this->Country_earnings_model->insert($datas);
}
?>
