<div class="tab-content">
    <?php echo form_open(get_uri("clients/save_bank_info/" . $model_info->id), array("id" => "bank_info-form", "class" => "general-form", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('bank_information'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="cin" class=" col-md-2"><?php echo lang('cin'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "cin",
                        "name" => "cin",
                        "value" => $model_info->cin,
                        "class" => "form-control",
                        "placeholder" => lang('corporate_identification_number')
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
                <label for="uam" class=" col-md-2"><?php echo lang('uam'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "uam",
                        "name" => "uam",
                        "value" => $model_info->uam,
                        "class" => "form-control",
                        "placeholder" => lang('udyog_aadhaar_memorandum')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="iec" class=" col-md-2"><?php echo lang('iec'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "iec",
                        "name" => "iec",
                        "value" => $model_info->iec,
                        "class" => "form-control",
                        "placeholder" => lang('importer_exporter_code')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="tan" class=" col-md-2"><?php echo lang('tan'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "tan",
                        "name" => "tan",
                        "value" => $model_info->tan,
                        "class" => "form-control",
                        "placeholder" =>  
                        lang('tax_deduction_number')
                    ));
                    ?>
                </div>
            </div>
            <div class="panel-default panel-heading" >
            <h4> <?php echo lang('bankaccountdetails'); ?></h4>
        </div>
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
"placeholder" =>lang('society_for_worldwide_interbank_financial_telecommunication')
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
        $("#bank_info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                

                
            }
        });
    });
</script>    