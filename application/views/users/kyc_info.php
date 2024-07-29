<div class="tab-content">
    <?php

    
    $reload_url = get_uri("team_members/view/" . $user_id . "/kyc");
    $save_url = get_uri("team_members/save_kyc_info/" . $user_id);
    if (isset($user_type) && $user_type === "resource") {
        $reload_url = get_uri("rm_members/view/" . $user_id . "/kyc");
        $save_url = get_uri("rm_members/save_kyc_info/" . $user_id);
    }

    if (isset($user_type) && $user_type === "client") {
        $reload_url = "";
        $save_url = get_uri("clients/save_kyc_info/" . $user_id);
    }
    if (isset($user_type) && $user_type === "vendor") {
        $reload_url = "";
        $save_url = get_uri("vendors/save_kyc_info/" . $user_id);
    }
    if (isset($user_type) && $user_type === "company") {
        $reload_url = "";
        $save_url = get_uri("companys/save_kyc_info/" . $user_id);
    }
    

    
    echo form_open($save_url, array("id" => "kyc-info-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('kyc_info'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="aadhar_no" class=" col-md-2"><?php echo lang('aadhar_no'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "aadhar_no",
                        "name" => "aadhar_no",
                        "value" => $model_info->aadhar_no,
                        "class" => "form-control",
                        "placeholder" => lang('aadhar_no')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="panno" class=" col-md-2"><?php echo lang('panno'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "panno",
                        "name" => "panno",
                        "value" => $model_info->panno,
                        "class" => "form-control",
                        "placeholder" =>  lang('panno')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="epf_no" class=" col-md-2"><?php echo lang('epf_no'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "epf_no",
                        "name" => "epf_no",
                        "value" => $model_info->epf_no,
                        "class" => "form-control",
                        "placeholder" =>  lang('epf_no')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="uan_no" class=" col-md-2"><?php echo lang('uan_no'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "uan_no",
                        "name" => "uan_no",
                        "value" => $model_info->uan_no,
                        "class" => "form-control",
                        "placeholder" =>  lang('uan_no')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="drivinglicenseno" class=" col-md-2"><?php echo lang('drivinglicenseno'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "drivinglicenseno",
                        "name" => "drivinglicenseno",
                        "value" => $model_info->drivinglicenseno,
                        "class" => "form-control",
                        "placeholder" => lang('drivinglicenseno')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="voterid" class=" col-md-2"><?php echo lang('voterid'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "voterid",
                        "name" => "voterid",
                        "value" => $model_info->voterid,
                        "class" => "form-control",
                        "placeholder" => lang('voterid')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="passportno" class=" col-md-2"><?php echo lang('passportno'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "passportno",
                        "name" => "passportno",
                        "value" => $model_info->passportno,
                        "class" => "form-control",
                        "placeholder" =>  lang('passportno')
                    ));
                    ?>
                </div>
            </div></div>    <div class="panel">

            <div class="panel-default panel-heading" >
            <h4> <?php echo lang('bankaccountdetails'); ?></h4>
        </div>
        <div class ="panel-body">
            <div class="form-group">
                <label for="name" class=" col-md-2"><?php echo lang('beneficiaryname'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "name",
                        "name" => "name",
                        "value" => $model_info->name,
                        "class" => "form-control",
                        "placeholder" =>  lang('name')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="accountnumber" class=" col-md-2"><?php echo lang('accountnumber'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "accountnumber",
                        "name" => "accountnumber",
                        "value" => $model_info->accountnumber,
                        "class" => "form-control",
                        "placeholder" =>  lang ('accountnumber')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="bankname" class=" col-md-2"><?php echo lang('bankname'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "bankname",
                        "name" => "bankname",
                        "value" => $model_info->bankname,
                        "class" => "form-control",
                        "placeholder" =>  lang('bankname')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="branch" class=" col-md-2"><?php echo lang('branch'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "branch",
                        "name" => "branch",
                        "value" => $model_info->branch,
                        "class" => "form-control",
                    "placeholder" =>  lang('branch')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="ifsc" class=" col-md-2"><?php echo lang('ifsc'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "ifsc",
                        "name" => "ifsc",
                        "value" => $model_info->ifsc,
                        "class" => "form-control",
                        "placeholder" =>  lang('ifsc')
                    ));
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="micr" class=" col-md-2"><?php echo lang('micr'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "micr",
                        "name" => "micr",
                        "value" => $model_info->micr,
                        "class" => "form-control",
                        "placeholder" =>  lang('micr')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="swift_code" class=" col-md-2"><?php echo lang('swift_code'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "swift_code",
                        "name" => "swift_code",
                        "value" => $model_info->swift_code,
                        "class" => "form-control",
                        "placeholder" =>  lang('swift_code')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="iban_code" class=" col-md-2"><?php echo lang('iban_code'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "iban_code",
                        "name" => "iban_code",
                        "value" => $model_info->iban_code,
                        "class" => "form-control",
                        "placeholder" =>  lang('iban_code')
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#kyc-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});

                var reloadUrl = "<?php echo $reload_url; ?>";
                if (reloadUrl) {
                    setTimeout(function () {
                        window.location.href = reloadUrl;
                    }, 500);
                }

            }
        });
    });
</script>    