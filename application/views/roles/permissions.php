<div class="tab-content">
    <?php echo form_open(get_uri("roles/save_permissions"), array("id" => "permissions-form", "class" => "general-form dashed-row", "role" => "form")); ?>
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('permissions') . ": " . $model_info->title; ?></h4>
        </div>
        <div class="panel-body">

            <ul class="permission-list">

                <li>
                    <h5><?php echo lang("set_event_permissions"); ?>:</h5>
                    <div>
                        <?php
                        echo form_checkbox("disable_event_sharing", "1", $disable_event_sharing ? true : false, "id='disable_event_sharing'");
                        ?>
                        <label for="disable_event_sharing"><?php echo lang("disable_event_sharing"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_team_members_access_voucher"); ?> <span class="help" data-toggle="tooltip" title="Access permissions on Voucher Module."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "voucher_permission_no",
                            "name" => "voucher_permission",
                            "value" => "",
                            "class" => "voucher_permission toggle_specific",
                                ), $voucher, ($voucher === "") ? true : false);
                        ?>
                        <label for="voucher_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "voucher_permission_all",
                            "name" => "voucher_permission",
                            "value" => "all",
                            "class" => "voucher_permission toggle_specific",
                                ), $voucher, ($voucher === "all") ? true : false);
                        ?>
                        <label for="voucher_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "voucher_permission_specific",
                            "name" => "voucher_permission",
                            "value" => "specific",
                            "class" => "voucher_permission toggle_specific",
                                ), $voucher, ($voucher === "specific") ? true : false);
                        ?>
                        <label for="voucher_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $voucher_specific; ?>" name="voucher_permission_specific" id="voucher_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>
                
<li>
                    <h5><?php echo lang("can_access_clients_information"); ?> <span class="help" data-toggle="tooltip" title="Hides all information of clients except company name."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "client_no",
                            "name" => "client_permission",
                            "value" => "",
                                ), $client, ($client === "") ? true : false);
                        ?>
                        <label for="client_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "client_yes",
                            "name" => "client_permission",
                            "value" => "all",
                                ), $client, ($client === "all") ? true : false);
                        ?>
                        <label for="client_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_access_vendors_information"); ?> <span class="help" data-toggle="tooltip" title="Hides all information of vendors except company name."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "vendor_no",
                            "name" => "vendor_permission",
                            "value" => "",
                                ), $vendor, ($vendor === "") ? true : false);
                        ?>
                        <label for="vendor_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "vendor_yes",
                            "name" => "vendor_permission",
                            "value" => "all",
                                ), $vendor, ($vendor === "all") ? true : false);
                        ?>
                        <label for="vendor_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>

<li>
                    <h5><?php echo lang("can_access_team_members_country"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for country."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "country_permission_no",
                            "name" => "country_permission",
                            "value" => "",
                            "class" => "country_permission toggle_specific",
                                ), $country, ($country === "") ? true : false);
                        ?>
                        <label for="country_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "country_permission_all",
                            "name" => "country_permission",
                            "value" => "all",
                            "class" => "country_permission toggle_specific",
                                ), $country, ($country === "all") ? true : false);
                        ?>
                        <label for="country_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "country_permission_specific",
                            "name" => "country_permission",
                            "value" => "specific",
                            "class" => "country_permission toggle_specific",
                                ), $country, ($country === "specific") ? true : false);
                        ?>
                        <label for="country_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $country_specific; ?>" name="country_permission_specific" id="country_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_state"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for state."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "state_permission_no",
                            "name" => "state_permission",
                            "value" => "",
                            "class" => "state_permission toggle_specific",
                                ), $state, ($state === "") ? true : false);
                        ?>
                        <label for="country_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "state_permission_all",
                            "name" => "state_permission",
                            "value" => "all",
                            "class" => "state_permission toggle_specific",
                                ), $state, ($state === "all") ? true : false);
                        ?>
                        <label for="state_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "state_permission_specific",
                            "name" => "state_permission",
                            "value" => "specific",
                            "class" => "state_permission toggle_specific",
                                ), $state, ($state === "specific") ? true : false);
                        ?>
                        <label for="state_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $state_specific; ?>" name="state_permission_specific" id="state_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_company"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for company."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "company_permission_no",
                            "name" => "company_permission",
                            "value" => "",
                            "class" => "company_permission toggle_specific",
                                ), $company, ($company === "") ? true : false);
                        ?>
                        <label for="income_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "company_permission_all",
                            "name" => "company_permission",
                            "value" => "all",
                            "class" => "company_permission toggle_specific",
                                ), $company, ($company === "all") ? true : false);
                        ?>
                        <label for="company_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "company_permission_specific",
                            "name" => "company_permission",
                            "value" => "specific",
                            "class" => "company_permission toggle_specific",
                                ), $company, ($company === "specific") ? true : false);
                        ?>
                        <label for="company_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $company_specific; ?>" name="company_permission_specific" id="company_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_branch"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for branch."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "branch_permission_no",
                            "name" => "branch_permission",
                            "value" => "",
                            "class" => "branch_permission toggle_specific",
                                ), $branch, ($branch === "") ? true : false);
                        ?>
                        <label for="country_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "branch_permission_all",
                            "name" => "branch_permission",
                            "value" => "all",
                            "class" => "branch_permission toggle_specific",
                                ), $branch, ($branch === "all") ? true : false);
                        ?>
                        <label for="branch_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "branch_permission_specific",
                            "name" => "branch_permission",
                            "value" => "specific",
                            "class" => "branch_permission toggle_specific",
                                ), $branch, ($branch === "specific") ? true : false);
                        ?>
                        <label for="branch_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $branch_specific; ?>" name="branch_permission_specific" id="branch_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>      

<li>
                    <h5><?php echo lang("can_access_team_members_department"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for department."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "department_permission_no",
                            "name" => "department_permission",
                            "value" => "",
                            "class" => "department_permission toggle_specific",
                                ), $department, ($department === "") ? true : false);
                        ?>
                        <label for="department_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "department_permission_all",
                            "name" => "department_permission",
                            "value" => "all",
                            "class" => "department_permission toggle_specific",
                                ), $department, ($department === "all") ? true : false);
                        ?>
                        <label for="department_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "department_permission_specific",
                            "name" => "department_permission",
                            "value" => "specific",
                            "class" => "department_permission toggle_specific",
                                ), $department, ($department === "specific") ? true : false);
                        ?>
                        <label for="department_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $department_specific; ?>" name="department_permission_specific" id="department_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>            

<li>
                    <h5><?php echo lang("can_access_team_members_designation"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for designation."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "designation_permission_no",
                            "name" => "designation_permission",
                            "value" => "",
                            "class" => "designation_permission toggle_specific",
                                ), $designation, ($designation === "") ? true : false);
                        ?>
                        <label for="department_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "designation_permission_all",
                            "name" => "designation_permission",
                            "value" => "all",
                            "class" => "designation_permission toggle_specific",
                                ), $designation, ($designation === "all") ? true : false);
                        ?>
                        <label for="designation_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "designation_permission_specific",
                            "name" => "designation_permission",
                            "value" => "specific",
                            "class" => "designation_permission toggle_specific",
                                ), $designation, ($designation === "specific") ? true : false);
                        ?>
                        <label for="designation_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $designation_specific; ?>" name="designation_permission_specific" id="designation_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_master_data"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for master data."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "master_data_permission_no",
                            "name" => "master_data_permission",
                            "value" => "",
                            "class" => "master_data_permission toggle_specific",
                                ), $master_data, ($master_data === "") ? true : false);
                        ?>
                        <label for="master_data_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "master_data_permission_all",
                            "name" => "master_data_permission",
                            "value" => "all",
                            "class" => "master_data_permission toggle_specific",
                                ), $master_data, ($master_data === "all") ? true : false);
                        ?>
                        <label for="master_data_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "master_data_permission_specific",
                            "name" => "master_data_permission",
                            "value" => "specific",
                            "class" => "master_data_permission toggle_specific",
                                ), $master_data, ($master_data === "specific") ? true : false);
                        ?>
                        <label for="master_data_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $master_data_specific; ?>" name="master_data_permission_specific" id="master_data_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_income"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for income."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "income_permission_no",
                            "name" => "income_permission",
                            "value" => "",
                            "class" => "income_permission toggle_specific",
                                ), $income, ($income === "") ? true : false);
                        ?>
                        <label for="income_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "income_permission_all",
                            "name" => "income_permission",
                            "value" => "all",
                            "class" => "income_permission toggle_specific",
                                ), $income, ($income === "all") ? true : false);
                        ?>
                        <label for="income_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "income_permission_specific",
                            "name" => "income_permission",
                            "value" => "specific",
                            "class" => "income_permission toggle_specific",
                                ), $income, ($income === "specific") ? true : false);
                        ?>
                        <label for="master_data_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $income_specific; ?>" name="income_permission_specific" id="income_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>            
<li>
                    <h5><?php echo lang("can_access_expenses"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "expense_no",
                            "name" => "expense_permission",
                            "value" => "",
                                ), $expense, ($expense === "") ? true : false);
                        ?>
                        <label for="expense_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "expense_yes",
                            "name" => "expense_permission",
                            "value" => "all",
                                ), $expense, ($expense === "all") ? true : false);
                        ?>
                        <label for="expense_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_access_team_members_loan"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for loan."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "loan_permission_no",
                            "name" => "loan_permission",
                            "value" => "",
                            "class" => "loan_permission toggle_specific",
                                ), $loan, ($loan === "") ? true : false);
                        ?>
                        <label for="income_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "loan_permission_all",
                            "name" => "loan_permission",
                            "value" => "all",
                            "class" => "loan_permission toggle_specific",
                                ), $loan, ($loan === "all") ? true : false);
                        ?>
                        <label for="income_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "loan_permission_specific",
                            "name" => "loan_permission",
                            "value" => "specific",
                            "class" => "loan_permission toggle_specific",
                                ), $loan, ($loan === "specific") ? true : false);
                        ?>
                        <label for="loan_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $loan_specific; ?>" name="loan_permission_specific" id="loan_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
                <li>
                    <h5><?php echo lang("can_team_members_access_company_bank_statement"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "company_bank_statement_permission_no",
                            "name" => "company_bank_statement_permission",
                            "value" => "",
                            "class" => "company_bank_statement_permission toggle_specific",
                                ), $company_bank_statement, ($company_bank_statement === "") ? true : false);
                        ?>
                        <label for="company_bank_statement_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "company_bank_statement_permission_all",
                            "name" => "company_bank_statement_permission",
                            "value" => "all",
                            "class" => "company_bank_statement_permission toggle_specific",
                                ), $company_bank_statement, ($company_bank_statement === "all") ? true : false);
                        ?>
                        <label for="company_bank_statement_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "company_bank_statement_permission_specific",
                            "name" => "company_bank_statement_permission",
                            "value" => "specific",
                            "class" => "company_bank_statement_permission toggle_specific",
                                ), $company_bank_statement, ($company_bank_statement === "specific") ? true : false);
                        ?>
                        <label for="company_bank_statement_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $company_bank_statement_specific; ?>" name="company_bank_statement_permission_specific" id="company_bank_statement_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_team_members_access_bank_statement"); ?> <span class="help" data-toggle="tooltip" title="Access permissions on Personal Bank Statement."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "bank_statement_permission_no",
                            "name" => "bank_statement_permission",
                            "value" => "",
                            "class" => "bank_statement_permission toggle_specific",
                                ), $bank_statement, ($bank_statement === "") ? true : false);
                        ?>
                        <label for="bank_statement_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "bank_statement_permission_all",
                            "name" => "bank_statement_permission",
                            "value" => "all",
                            "class" => "bank_statement_permission toggle_specific",
                                ), $bank_statement, ($bank_statement === "all") ? true : false);
                        ?>
                        <label for="bank_statement_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "bank_statement_permission_specific",
                            "name" => "bank_statement_permission",
                            "value" => "specific",
                            "class" => "bank_statement_permission toggle_specific",
                                ), $bank_statement, ($bank_statement === "specific") ? true : false);
                        ?>
                        <label for="bank_statement_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $bank_statement_specific; ?>" name="bank_statement_permission_specific" id="bank_statement_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li> 


                <li>
                    <h5><?php echo lang("can_team_members_access_cheque_handler"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "cheque_handler_permission_no",
                            "name" => "cheque_handler_permission",
                            "value" => "",
                            "class" => "cheque_handler_permission toggle_specific",
                                ), $cheque_handler, ($cheque_handler === "") ? true : false);
                        ?>
                        <label for="cheque_handler_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "cheque_handler_permission_all",
                            "name" => "cheque_handler_permission",
                            "value" => "all",
                            "class" => "cheque_handler_permission toggle_specific",
                                ), $cheque_handler, ($cheque_handler === "all") ? true : false);
                        ?>
                        <label for="cheque_handler_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                        "id" => "cheque_handler_permission_specific",
                            "name" => "cheque_handler_permission",
                            "value" => "specific",
                            "class" => "cheque_handler_permission toggle_specific",
                                ), $cheque_handler, ($cheque_handler === "specific") ? true : false);
                        ?>
                        <label for="cheque_handler_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $cheque_handler_specific; ?>" name="cheque_handler_permission_specific" id="cheque_handler_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>

<li>
                    <h5><?php echo lang("can_manage_team_members_timecards"); ?> <span class="help" data-toggle="tooltip" title="Add, edit and delete time cards"><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "attendance_permission_no",
                            "name" => "attendance_permission",
                            "value" => "",
                            "class" => "attendance_permission toggle_specific",
                                ), $attendance, ($attendance === "") ? true : false);
                        ?>
                        <label for="attendance_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "attendance_permission_all",
                            "name" => "attendance_permission",
                            "value" => "all",
                            "class" => "attendance_permission toggle_specific",
                                ), $attendance, ($attendance === "all") ? true : false);
                        ?>
                        <label for="attendance_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "attendance_permission_specific",
                            "name" => "attendance_permission",
                            "value" => "specific",
                            "class" => "attendance_permission toggle_specific",
                                ), $attendance, ($attendance === "specific") ? true : false);
                        ?>
                        <label for="attendance_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_time_cards") . ")"; ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $attendance_specific; ?>" name="attendance_permission_specific" id="attendance_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>
                <li>
                    <h5><?php echo lang("can_manage_team_members_leave"); ?> <span class="help" data-toggle="tooltip" title="Assign, approve or reject leave applications"><i class="fa fa-question-circle"></i></span> </h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "leave_permission_no",
                            "name" => "leave_permission",
                            "value" => "",
                            "class" => "leave_permission toggle_specific",
                                ), $leave, ($leave === "") ? true : false);
                        ?>
                        <label for="leave_permission_no"><?php echo lang("no"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "leave_permission_all",
                            "name" => "leave_permission",
                            "value" => "all",
                            "class" => "leave_permission toggle_specific",
                                ), $leave, ($leave === "all") ? true : false);
                        ?>
                        <label for="leave_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "leave_permission_specific",
                            "name" => "leave_permission",
                            "value" => "specific",
                            "class" => "leave_permission toggle_specific",
                                ), $leave, ($leave === "specific") ? true : false);
                        ?>
                        <label for="leave_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_leaves") . ")"; ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $leave_specific; ?>" name="leave_permission_specific" id="leave_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                        </div>

                    </div>
                    <div class="form-group">
                        <div>
                            <?php
                            echo form_checkbox("can_delete_leave_application", "1", $can_delete_leave_application ? true : false, "id='can_delete_leave_application'");
                            ?>
                            <label for="can_delete_leave_application"><?php echo lang("can_delete_leave_application"); ?> <span class="help" data-toggle="tooltip" title="Can delete based on his/her access permission"><i class="fa fa-question-circle"></i></span></label>
                        </div>
                    </div>
                </li>

                <li>
                    <h5><?php echo lang("can_access_payslip"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "payslip_permission_no",
                            "name" => "payslip_permission",
                            "value" => "",
                            "class" => "payslip_permission toggle_specific",
                                ), $payslip, ($payslip === "") ? true : false);
                        ?>
                        <label for="payslip_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "payslip_permission_all",
                            "name" => "payslip_permission",
                            "value" => "all",
                            "class" => "payslip_permission toggle_specific",
                                ), $payslip, ($payslip === "all") ? true : false);
                        ?>
                        <label for="payslip_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "payslip_permission_specific",
                            "name" => "payslip_permission",
                            "value" => "specific",
                            "class" => "payslip_permission toggle_specific",
                                ), $payslip, ($payslip === "specific") ? true : false);
                        ?>
                        <label for="payslip_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $payslip_specific; ?>" name="payslip_permission_specific" id="payslip_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_manage_announcements"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "announcement_no",
                            "name" => "announcement_permission",
                            "value" => "",
                                ), $announcement, ($announcement === "") ? true : false);
                        ?>
                        <label for="announcement_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "announcement_yes",
                            "name" => "announcement_permission",
                            "value" => "all",
                                ), $announcement, ($announcement === "all") ? true : false);
                        ?>
                        <label for="announcement_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>


                <li>
                    <h5><?php echo lang("set_project_permissions"); ?>:</h5>
                    <div>
                        <?php
                        echo form_checkbox("can_manage_all_projects", "1", $can_manage_all_projects ? true : false, "id='can_manage_all_projects'");
                        ?>
                        <label for="can_manage_all_projects"><?php echo lang("can_manage_all_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_create_projects", "1", $can_create_projects ? true : false, "id='can_create_projects'");
                        ?>
                        <label for="can_create_projects"><?php echo lang("can_create_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_edit_projects", "1", $can_edit_projects ? true : false, "id='can_edit_projects'");
                        ?>
                        <label for="can_edit_projects"><?php echo lang("can_edit_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_delete_projects", "1", $can_delete_projects ? true : false, "id='can_delete_projects'");
                        ?>
                        <label for="can_delete_projects"><?php echo lang("can_delete_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_add_remove_project_members", "1", $can_add_remove_project_members ? true : false, "id='can_add_remove_project_members'");
                        ?>
                        <label for="can_add_remove_project_members"><?php echo lang("can_add_remove_project_members"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_create_tasks", "1", $can_create_tasks ? true : false, "id='can_create_tasks'");
                        ?>
                        <label for="can_create_tasks"><?php echo lang("can_create_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_edit_tasks", "1", $can_edit_tasks ? true : false, "id='can_edit_tasks'");
                        ?>
                        <label for="can_edit_tasks"><?php echo lang("can_edit_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_delete_tasks", "1", $can_edit_tasks ? true : false, "id='can_delete_tasks'");
                        ?>
                        <label for="can_delete_tasks"><?php echo lang("can_delete_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_comment_on_tasks", "1", $can_comment_on_tasks ? true : false, "id='can_comment_on_tasks'");
                        ?>
                        <label for="can_comment_on_tasks"><?php echo lang("can_comment_on_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_create_milestones", "1", $can_create_milestones ? true : false, "id='can_create_milestones'");
                        ?>
                        <label for="can_create_milestones"><?php echo lang("can_create_milestones"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_edit_milestones", "1", $can_edit_milestones ? true : false, "id='can_edit_milestones'");
                        ?>
                        <label for="can_edit_milestones"><?php echo lang("can_edit_milestones"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_delete_milestones", "1", $can_delete_milestones ? true : false, "id='can_delete_milestones'");
                        ?>
                        <label for="can_delete_milestones"><?php echo lang("can_delete_milestones"); ?></label>
                    </div>

                    <div>
                        <?php
                        echo form_checkbox("can_delete_files", "1", $can_delete_files ? true : false, "id='can_delete_files'");
                        ?>
                        <label for="can_delete_files"><?php echo lang("can_delete_files"); ?></label>
                    </div>

                </li>

                <li>
                    <h5><?php echo lang("can_manage_team_members_project_timesheet"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "timesheet_manage_permission_no",
                            "name" => "timesheet_manage_permission",
                            "value" => "",
                            "class" => "timesheet_manage_permission toggle_specific",
                                ), $timesheet_manage_permission, ($timesheet_manage_permission === "") ? true : false);
                        ?>
                        <label for="timesheet_manage_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "timesheet_manage_permission_all",
                            "name" => "timesheet_manage_permission",
                            "value" => "all",
                            "class" => "timesheet_manage_permission toggle_specific",
                                ), $timesheet_manage_permission, ($timesheet_manage_permission === "all") ? true : false);
                        ?>
                        <label for="timesheet_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "timesheet_manage_permission_specific",
                            "name" => "timesheet_manage_permission",
                            "value" => "specific",
                            "class" => "timesheet_manage_permission toggle_specific",
                                ), $timesheet_manage_permission, ($timesheet_manage_permission === "specific") ? true : false);
                        ?>
                        <label for="timesheet_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $timesheet_manage_permission_specific; ?>" name="timesheet_manage_permission_specific" id="timesheet_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
                </li>

                <li>
                    <h5><?php echo lang("can_access_team_members_production_data"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "production_data_permission_no",
                            "name" => "production_data_permission",
                            "value" => "",
                            "class" => "production_data_permission toggle_specific",
                                ), $production_data, ($production_data === "") ? true : false);
                        ?>
                        <label for="production_data_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "production_data_permission_all",
                            "name" => "production_data_permission",
                            "value" => "all",
                            "class" => "production_data_permission toggle_specific",
                                ), $production_data, ($production_data === "all") ? true : false);
                        ?>
                        <label for="production_data_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "production_data_permission_specific",
                            "name" => "production_data_permission",
                            "value" => "specific",
                            "class" => "production_data_permission toggle_specific",
                                ), $production_data, ($production_data === "specific") ? true : false);
                        ?>
                        <label for="production_data_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $production_data_specific; ?>" name="production_data_permission_specific" id="production_data_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_inventory"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for inventory."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "inventory_permission_no",
                            "name" => "inventory_permission",
                            "value" => "",
                            "class" => "inventory_permission toggle_specific",
                                ), $inventory, ($inventory === "") ? true : false);
                        ?>
                        <label for="income_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "inventory_permission_all",
                            "name" => "inventory_permission",
                            "value" => "all",
                            "class" => "inventory_permission toggle_specific",
                                ), $inventory, ($inventory === "all") ? true : false);
                        ?>
                        <label for="income_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "inventory_permission_specific",
                            "name" => "inventory_permission",
                            "value" => "specific",
                            "class" => "inventory_permission toggle_specific",
                                ), $inventory, ($inventory === "specific") ? true : false);
                        ?>
                        <label for="loan_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $inventory_specific; ?>" name="inventory_permission_specific" id="inventory_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_team_members_access_delivery"); ?> <span class="help" data-toggle="tooltip" title="Access permissions on Delivery Module."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "delivery_permission_no",
                            "name" => "delivery_permission",
                            "value" => "",
                            "class" => "delivery_permission toggle_specific",
                                ), $delivery, ($delivery === "") ? true : false);
                        ?>
                        <label for="delivery_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "delivery_permission_all",
                            "name" => "delivery_permission",
                            "value" => "all",
                            "class" => "delivery_permission toggle_specific",
                                ), $delivery, ($delivery === "all") ? true : false);
                        ?>
                        <label for="delivery_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "delivery_permission_specific",
                            "name" => "delivery_permission",
                            "value" => "specific",
                            "class" => "delivery_permission toggle_specific",
                                ), $delivery, ($delivery === "specific") ? true : false);
                        ?>
                        <label for="delivery_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $delivery_specific; ?>" name="delivery_permission_specific" id="delivery_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>
                <li>
                    <h5><?php echo lang("can_access_estimates"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "estimate_no",
                            "name" => "estimate_permission",
                            "value" => "",
                                ), $estimate, ($estimate === "") ? true : false);
                        ?>
                        <label for="estimate_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "estimate_yes",
                            "name" => "estimate_permission",
                            "value" => "all",
                                ), $estimate, ($estimate === "all") ? true : false);
                        ?>
                        <label for="estimate_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_access_purchase_order"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "purchase_order_no",
                            "name" => "purchase_order_permission",
                            "value" => "",
                                ), $purchase_order, ($purchase_order === "") ? true : false);
                        ?>
                        <label for="purchase_order_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "purchase_order_yes",
                            "name" => "purchase_order_permission",
                            "value" => "all",
                                ), $purchase_order, ($purchase_order === "all") ? true : false);
                        ?>
                        <label for="purchase_order_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_access_work_order"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "work_order_no",
                            "name" => "work_order_permission",
                            "value" => "",
                                ), $work_order, ($work_order === "") ? true : false);
                        ?>
                        <label for="work_order_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "work_order_yes",
                            "name" => "work_order_permission",
                            "value" => "all",
                                ), $work_order, ($work_order === "all") ? true : false);
                        ?>
                        <label for="work_order_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_access_invoices"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "invoice_no",
                            "name" => "invoice_permission",
                            "value" => "",
                                ), $invoice, ($invoice === "") ? true : false);
                        ?>
                        <label for="invoice_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "invoice_yes",
                            "name" => "invoice_permission",
                            "value" => "all",
                                ), $invoice, ($invoice === "all") ? true : false);
                        ?>
                        <label for="invoice_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <h5><?php echo lang("can_team_members_access_tools_accessories"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "tools_permission_no",
                            "name" => "tools_permission",
                            "value" => "",
                            "class" => "tools_permission toggle_specific",
                                ), $tools, ($tools === "") ? true : false);
                        ?>
                        <label for="tools_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "tools_permission_all",
                            "name" => "tools_permission",
                            "value" => "all",
                            "class" => "tools_permission toggle_specific",
                                ), $tools, ($tools === "all") ? true : false);
                        ?>
                        <label for="tools_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                        "id" => "tools_permission_specific",
                            "name" => "tools_permission",
                            "value" => "specific",
                            "class" => "tools_permission toggle_specific",
                                ), $tools, ($tools === "specific") ? true : false);
                        ?>
                        <label for="tools_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $tools_specific; ?>" name="tools_permission_specific" id="tools_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_team_members_credentials"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for credentials."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "assets_data_permission_no",
                            "name" => "assets_data_permission",
                            "value" => "",
                            "class" => "assets_data_permission toggle_specific",
                                ), $assets_data, ($assets_data === "") ? true : false);
                        ?>
                        <label for="assets_data_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "assets_data_permission_all",
                            "name" => "assets_data_permission",
                            "value" => "all",
                            "class" => "assets_data_permission toggle_specific",
                                ), $assets_data, ($assets_data === "all") ? true : false);
                        ?>
                        <label for="assets_data_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "assets_data_permission_specific",
                            "name" => "assets_data_permission",
                            "value" => "specific",
                            "class" => "assets_data_permission toggle_specific",
                                ), $assets_data, ($assets_data === "specific") ? true : false);
                        ?>
                        <label for="assets_data_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $assets_data_specific; ?>" name="assets_data_permission_specific" id="assets_data_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>



                


                <li>
                    <h5><?php echo lang("set_team_members_permission"); ?>:</h5>


                    <div>
                        <?php
                        echo form_checkbox("hide_team_members_list", "1", $hide_team_members_list ? true : false, "id='hide_team_members_list'");
                        ?>
                        <label for="hide_team_members_list"><?php echo lang("hide_team_members_list"); ?></label>
                    </div>

                    <div>
                        <?php
                        echo form_checkbox("can_view_team_members_contact_info", "1", $can_view_team_members_contact_info ? true : false, "id='can_view_team_members_contact_info'");
                        ?>
                        <label for="can_view_team_members_contact_info"><?php echo lang("can_view_team_members_contact_info"); ?></label>
                    </div>

                    <div>
                        <?php
                        echo form_checkbox("can_view_team_members_social_links", "1", $can_view_team_members_social_links ? true : false, "id='can_view_team_members_social_links'");
                        ?>
                        <label for="can_view_team_members_social_links"><?php echo lang("can_view_team_members_social_links"); ?></label>
                    </div>

                    <div>
                        <label for="can_update_team_members_general_info_and_social_links"><?php echo lang("can_update_team_members_general_info_and_social_links"); ?></label>
                        <div class="ml15">
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "team_member_update_permission_no",
                                    "name" => "team_member_update_permission",
                                    "value" => "",
                                    "class" => "team_member_update_permission toggle_specific",
                                        ), $team_member_update_permission, ($team_member_update_permission === "") ? true : false);
                                ?>
                                <label for="team_member_update_permission_no"><?php echo lang("no"); ?></label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "team_member_update_permission_all",
                                    "name" => "team_member_update_permission",
                                    "value" => "all",
                                    "class" => "team_member_update_permission toggle_specific",
                                        ), $team_member_update_permission, ($team_member_update_permission === "all") ? true : false);
                                ?>
                                <label for="team_member_update_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                            <div class="form-group">
                                <?php
                                echo form_radio(array(
                                    "id" => "team_member_update_permission_specific",
                                    "name" => "team_member_update_permission",
                                    "value" => "specific",
                                    "class" => "team_member_update_permission toggle_specific",
                                        ), $team_member_update_permission, ($team_member_update_permission === "specific") ? true : false);
                                ?>
                                <label for="team_member_update_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $team_member_update_permission_specific; ?>" name="team_member_update_permission_specific" id="team_member_update_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                </div>
                            </div>
                        </div>
                    </div>

                </li>
                <li>
                    <h5><?php echo lang("can_access_outsource_members"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "outsource_members_permission_no",
                            "name" => "outsource_members_permission",
                            "value" => "",
                            "class" => "outsource_members_permission toggle_specific",
                                ), $outsource_members, ($outsource_members === "") ? true : false);
                        ?>
                        <label for="outsource_members_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "outsource_members_permission_all",
                            "name" => "outsource_members_permission",
                            "value" => "all",
                            "class" => "outsource_members_permission toggle_specific",
                                ), $outsource_members, ($outsource_members === "all") ? true : false);
                        ?>
                        <label for="outsource_members_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "outsource_members_permission_specific",
                            "name" => "outsource_members_permission",
                            "value" => "specific",
                            "class" => "outsource_members_permission toggle_specific",
                                ), $outsource_members, ($outsource_members === "specific") ? true : false);
                        ?>
                        <label for="outsource_members_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $outsource_members_specific; ?>" name="outsource_members_permission_specific" id="outsource_members_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>
<li>
                    <h5><?php echo lang("can_access_student_desk"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "student_desk_permission_no",
                            "name" => "student_desk_permission",
                            "value" => "",
                            "class" => "student_desk_permission toggle_specific",
                                ), $student_desk, ($student_desk === "") ? true : false);
                        ?>
                        <label for="student_desk_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "student_desk_permission_all",
                            "name" => "student_desk_permission",
                            "value" => "all",
                            "class" => "student_desk_permission toggle_specific",
                                ), $student_desk, ($student_desk === "all") ? true : false);
                        ?>
                        <label for="student_desk_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "student_desk_permission_specific",
                            "name" => "student_desk_permission",
                            "value" => "specific",
                            "class" => "student_desk_permission toggle_specific",
                                ), $student_desk, ($student_desk === "specific") ? true : false);
                        ?>
                        <label for="student_desk_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $student_desk_specific; ?>" name="student_desk_permission_specific" id="student_desk_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li> 
<li>
                    <h5><?php echo lang("can_access_team_members_register"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for Register."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "register_permission_no",
                            "name" => "register_permission",
                            "value" => "",
                            "class" => "register_permission toggle_specific",
                                ), $register, ($register === "") ? true : false);
                        ?>
                        <label for="register_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "register_permission_all",
                            "name" => "register_permission",
                            "value" => "all",
                            "class" => "register_permission toggle_specific",
                                ), $register, ($register === "all") ? true : false);
                        ?>
                        <label for="register_permission_all"><?php echo lang("yes"); ?></label>
                    </div>
                   <!--  <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "register_permission_specific",
                            "name" => "register_permission",
                            "value" => "specific",
                            "class" => "register_permission toggle_specific",
                                ), $register, ($register === "specific") ? true : false);
                        ?>
                        <label for="register_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $register_specific; ?>" name="register_permission_specific" id="register_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div> -->
</li>

                <li>
                    <h5><?php echo lang("can_access_tickets"); ?></h5>       
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ticket_permission_no",
                            "name" => "ticket_permission",
                            "value" => "",
                            "class" => "ticket_permission toggle_specific",
                                ), $ticket, ($ticket === "") ? true : false);
                        ?>
                        <label for="ticket_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ticket_permission_all",
                            "name" => "ticket_permission",
                            "value" => "all",
                            "class" => "ticket_permission toggle_specific",
                                ), $ticket, ($ticket === "all") ? true : false);
                        ?>
                        <label for="ticket_permission_all"><?php echo lang("yes_all_tickets"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "ticket_permission_specific",
                            "name" => "ticket_permission",
                            "value" => "specific",
                            "class" => "ticket_permission toggle_specific",
                                ), $ticket, ($ticket === "specific") ? true : false);
                        ?>
                        <label for="ticket_permission_specific"><?php echo lang("yes_specific_ticket_types"); ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $ticket_specific; ?>" name="ticket_permission_specific" id="ticket_types_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_ticket_types'); ?>"  />
                        </div>
                    </div>
                </li>
                
                <li>
                    <h5><?php echo lang("can_manage_help_and_knowledge_base"); ?></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "help_no",
                            "name" => "help_and_knowledge_base",
                            "value" => "",
                                ), $help_and_knowledge_base, ($help_and_knowledge_base === "") ? true : false);
                        ?>
                        <label for="help_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "help_yes",
                            "name" => "help_and_knowledge_base",
                            "value" => "all",
                                ), $help_and_knowledge_base, ($help_and_knowledge_base === "all") ? true : false);
                        ?>
                        <label for="help_yes"><?php echo lang("yes"); ?></label>
                    </div>
                </li>
                 <li>
                    <h5><?php echo lang("can_access_team_members_hagwaytowers"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "hagwaytower_permission_no",
                            "name" => "hagwaytower_permission",
                            "value" => "",
                            "class" => "hagwaytower_permission toggle_specific",
                                ), $hagwaytower, ($hagwaytower === "") ? true : false);
                        ?>
                        <label for="haywaytower_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "hagwaytower_permission_all",
                            "name" => "hagwaytower_permission",
                            "value" => "all",
                            "class" => "hagwaytower_permission toggle_specific",
                                ), $hagwaytower, ($hagwaytower === "all") ? true : false);
                        ?>
                        <label for="hagwaytower_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "hagwaytower_permission_specific",
                            "name" => "hagwaytower_permission",
                            "value" => "specific",
                            "class" => "hagwaytower_permission toggle_specific",
                                ), $hagwaytower, ($hagwaytower === "specific") ? true : false);
                        ?>
                        <label for="hagwaytower_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $hagwaytower_specific; ?>" name="hagwaytower_permission_specific" id="hagwaytower_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>

                                
               
                <li>
                    <h5><?php echo lang("set_gemicates_tower_permission"); ?>:</h5>
                     <div>
                        <?php
                        echo form_checkbox("can_access_device_manager", "1", $can_access_device_manager ? true : false, "id='can_access_device_manager'");
                        ?>
                        <label for="can_access_device_manager"><?php echo lang("can_access_device_manager"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_IR_library", "1", $can_access_IR_library ? true : false, "id='can_access_IR_Library'");
                        ?>
                        <label for="can_access_IR_library"><?php echo lang("can_access_IR_library"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_channel_library", "1", $can_access_channel_library ? true : false, "id='can_access_channel_library'");
                        ?>
                        <label for="can_access_IR_library"><?php echo lang("can_access_channel_library"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_VMS_library", "1", $can_access_VMS_library ? true : false, "id='can_access_VMS_library'");
                        ?>
                        <label for="can_access_IR_library"><?php echo lang("can_access_VMS_library"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_hagway_manager", "1", $can_access_hagway_manager ? true : false, "id='can_access_hagway_manager'");
                        ?>
                        <label for="can_access_hagway_manager"><?php echo lang("can_access_hagway_manager"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_website_manager", "1", $can_access_website_manager ? true : false, "id='can_access_website_manager'");
                        ?>
                        <label for="can_access_website_manager"><?php echo lang("can_access_website_manager"); ?></label>
                    </div>
                     <div>
                        <?php
                        echo form_checkbox("can_access_architecture", "1", $can_access_architecture ? true : false, "id='can_access_architecture'");
                        ?>
                        <label for="can_access_architecture"><?php echo lang("can_access_architecture"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_enquiry_manager", "1", $can_access_enquiry_manager ? true : false, "id='can_access_enquiry_manager'");
                        ?>
                        <label for="can_access_enquiry_manager"><?php echo lang("can_access_enquiry_manager"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_access_inframote_manager", "1", $can_access_inframote_manager ? true : false, "id='can_access_inframote_manager'");
                        ?>
                    <label for="can_access_inframote_manager"><?php echo lang("can_access_inframote_manager"); ?></label>
                    </div>

                   <div>
                        <label for="can_access_team_members_gemicates_tower"><?php echo lang("can_access_team_members_gemicates_tower"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></label>
                        <div class="ml15">
                        <div>
                        <?php
                        echo form_radio(array(
                            "id" => "gemicates_tower_permission_no",
                            "name" => "gemicates_tower_permission",
                            "value" => "",
                            "class" => "gemicates_tower_permission toggle_specific",
                                ), $gemicates_tower, ($gemicates_tower === "") ? true : false);
                        ?>
                        <label for="gemicates_tower_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "gemicates_tower_permission_all",
                            "name" => "gemicates_tower_permission",
                            "value" => "all",
                            "class" => "gemicates_tower_permission toggle_specific",
                                ), $gemicates_tower, ($gemicates_tower === "all") ? true : false);
                        ?>
                        <label for="gemicates_tower_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "gemicates_tower_permission_specific",
                            "name" => "gemicates_tower_permission",
                            "value" => "specific",
                            "class" => "gemicates_tower_permission toggle_specific",
                                ), $gemicates_tower, ($gemicates_tower === "specific") ? true : false);
                        ?>
                        <label for="gemicates_tower_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $gemicates_tower_specific; ?>" name="gemicates_tower_permission_specific" id="gemicates_tower_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
                </div>
            </div>

                </li>

                <li>
                    <h5><?php echo lang("can_access_team_members_inframotetower"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "inframotetower_permission_no",
                            "name" => "inframotetower_permission",
                            "value" => "",
                            "class" => "inframotetower_permission toggle_specific",
                                ), $inframotetower, ($inframotetower === "") ? true : false);
                        ?>
                        <label for="inframotetower_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "inframotetower_permission_all",
                            "name" => "inframotetower_permission",
                            "value" => "all",
                            "class" => "inframotetower_permission toggle_specific",
                                ), $inframotetower, ($inframotetower === "all") ? true : false);
                        ?>
                        <label for="inframotetower_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "inframotetower_permission_specific",
                            "name" => "inframotetower_permission",
                            "value" => "specific",
                            "class" => "inframotetower_permission toggle_specific",
                                ), $inframotetower, ($inframotetower === "specific") ? true : false);
                        ?>
                        <label for="inframotetower_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $inframotetower_specific; ?>" name="inframotetower_permission_specific" id="inframotetower_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>

                <li>
                    <h5><?php echo lang("can_access_team_members_gem_lab_admin"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members." ><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "gem_lab_admin_permission_no",
                            "name" => "gem_lab_admin_permission",
                            "value" => "",
                            "class" => "gem_lab_admin_permission toggle_specific",
                                ), $gem_lab_admin, ($gem_lab_admin === "") ? true : false);
                        ?>
                        <label for="gem_lab_admin_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "gem_lab_admin_permission_all",
                            "name" => "gem_lab_admin_permission",
                            "value" => "all",
                            "class" => "gem_lab_admin_permission toggle_specific",
                                ), $gem_lab_admin, ($gem_lab_admin === "all") ? true : false);
                        ?>
                        <label for="gem_lab_admin_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "gem_lab_admin_permission_specific",
                            "name" => "gem_lab_admin_permission",
                            "value" => "specific",
                            "class" => "gem_lab_admin_permission toggle_specific",
                                ), $gem_lab_admin, ($gem_lab_admin === "specific") ? true : false);
                        ?>
                        <label for="gem_lab_admin_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $gem_lab_admin_specific; ?>" name="gem_lab_admin_permission_specific" id="gem_lab_admin_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>

                <li>
                    <h5><?php echo lang("can_access_team_members_gemicates_seller_portal"); ?> <span class="help" data-toggle="tooltip" title="Access permissions for team members."><i class="fa fa-question-circle"></i></span></h5>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "gemicates_seller_portal_permission_no",
                            "name" => "gemicates_seller_portal_permission",
                            "value" => "",
                            "class" => "gemicates_seller_portal_permission toggle_specific",
                                ), $gemicates_seller_portal, ($gemicates_seller_portal === "") ? true : false);
                        ?>
                        <label for="gemicates_seller_portal_permission_no"><?php echo lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "gemicates_seller_portal_permission_all",
                            "name" => "gemicates_seller_portal_permission",
                            "value" => "all",
                            "class" => "gemicates_seller_portal_permission toggle_specific",
                                ), $gemicates_seller_portal, ($gemicates_seller_portal === "all") ? true : false);
                        ?>
                        <label for="gemicates_seller_portal_permission_all"><?php echo lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "gemicates_seller_portal_permission_specific",
                            "name" => "gemicates_seller_portal_permission",
                            "value" => "specific",
                            "class" => "gemicates_seller_portal_permission toggle_specific",
                                ), $gemicates_seller_portal, ($gemicates_seller_portal === "specific") ? true : false);
                        ?>
                        <label for="gemicates_seller_portal_permission_specific"><?php echo lang("yes_specific_members_or_teams") ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $gemicates_seller_portal_specific; ?>" name="gemicates_seller_portal_permission_specific" id="gemicates_seller_portal_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
</li>










      





                
















            </ul>

        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary mr10"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#permissions-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

    $("#leave_specific_dropdown, #attendance_specific_dropdown, #timesheet_manage_permission_specific_dropdown, #hagwaytower_specific_dropdown,#delivery_specific_dropdown,#voucher_specific_dropdown,#gemicates_tower_specific_dropdown,#inframotetower_specific_dropdown,#gem_lab_admin_specific_dropdown,  #gemicates_seller_portal_specific_dropdown,#team_member_update_permission_specific_dropdown,#master_data_specific_dropdown,#register_specific_dropdown,#production_data_specific_dropdown,#assets_data_specific_dropdown,#bank_statement_specific_dropdown,#outsource_members_specific_dropdown, #payslip_specific_dropdown,#company_bank_statement_specific_dropdown,#tools_specific_dropdown,#cheque_handler_specific_dropdown,#student_desk_specific_dropdown,#income_specific_dropdown,#loan_specific_dropdown,#company_specific_dropdown,#country_specific_dropdown,#state_specific_dropdown,#branch_specific_dropdown,#department_specific_dropdown,#designation_specific_dropdown,#inventory_specific_dropdown").select2({
            multiple: true,
            formatResult: teamAndMemberSelect2Format,
            formatSelection: teamAndMemberSelect2Format,
            data: <?php echo ($members_and_teams_dropdown); ?>
        });

        $("#ticket_types_specific_dropdown").select2({
            multiple: true,
            data: <?php echo ($ticket_types_dropdown); ?>
        });

        $('[data-toggle="tooltip"]').tooltip();

        $(".toggle_specific").click(function () {
            toggle_specific_dropdown();
        });

        toggle_specific_dropdown();
        function toggle_specific_dropdown() {
            var selectors = [".leave_permission", ".attendance_permission", ".timesheet_manage_permission",".hagwaytower_permission",".delivery_permission",".voucher_permission",".gemicates_tower_permission", ".inframotetower_permission",".gem_lab_admin_permission",".gemicates_seller_portal_permission",".team_member_update_permission", ".ticket_permission",".master_data_permission",".register_permission",".production_data_permission",".assets_data_permission",".bank_statement_permission",".outsource_members_permission",".payslip_permission",".company_bank_statement_permission",".tools_permission",".cheque_handler_permission",".student_desk_permission",".income_permission",".loan_permission",".company_permission",".country_permission",".state_permission",".branch_permission",".department_permission",".designation_permission",".inventory_permission"];
            $.each(selectors, function (index, element) {
                var $element = $(element + ":checked");
                if ($element.val() === "specific") {
                    $element.closest("li").find(".specific_dropdown").show().find("input").addClass("validate-hidden");
                } else {
                    //console.log($element.closest("li").find(".specific_dropdown"));
                    $(element).closest("li").find(".specific_dropdown").hide().find("input").removeClass("validate-hidden");
                }
            });

        }
    });
</script>    
