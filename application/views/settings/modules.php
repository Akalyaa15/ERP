<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "modules";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_module_settings"), array("id" => "module-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("manage_modules"); ?></h4>
                    <div><?php echo lang("module_settings_instructions"); ?></div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="module_timeline" class="col-md-2"><?php echo lang('timeline'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_timeline", "1", get_setting("module_timeline") ? true : false, "id='module_timeline' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_event" class="col-md-2"><?php echo lang('event'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_event", "1", get_setting("module_event") ? true : false, "id='module_event' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_todo" class="col-md-2"><?php echo lang('todo'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_todo", "1", get_setting("module_todo") ? true : false, "id='module_todo' class='ml15'");
                            ?>                       
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_note" class="col-md-2"><?php echo lang('note'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_note", "1", get_setting("module_note") ? true : false, "id='module_note' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_message" class="col-md-2"><?php echo lang('message'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_message", "1", get_setting("module_message") ? true : false, "id='module_message' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_chat" class="col-md-2"><?php echo lang('chat'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_chat", "1", get_setting("module_chat") ? true : false, "id='module_chat' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_invoice" class="col-md-2"><?php echo lang('invoice'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_invoice", "1", get_setting("module_invoice") ? true : false, "id='module_invoice' class='ml15'");
                            ?>                       
                        </div>
                    </div>    
                    <div class="form-group">
                        <label for="module_expense" class="col-md-2"><?php echo lang('expense'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_expense", "1", get_setting("module_expense") ? true : false, "id='module_expense' class='ml15'");
                            ?>                       
                        </div>
                    </div>  
                    <div class="form-group">
                        <label for="module_attendance" class="col-md-2"><?php echo lang('attendance'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_attendance", "1", get_setting("module_attendance") ? true : false, "id='module_attendance' class='ml15'");
                            ?>                       
                        </div>
                    </div>  
                    <div class="form-group">
                        <label for="module_leave" class="col-md-2"><?php echo lang('leave'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_leave", "1", get_setting("module_leave") ? true : false, "id='module_leave' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_estimate" class="col-md-2"><?php echo lang('estimate'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_estimate", "1", get_setting("module_estimate") ? true : false, "id='module_estimate' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_estimate_request" class="col-md-2"><?php echo lang('estimate_request'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_estimate_request", "1", get_setting("module_estimate_request") ? true : false, "id='module_estimate_request' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_ticket" class="col-md-2"><?php echo lang('ticket'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_ticket", "1", get_setting("module_ticket") ? true : false, "id='module_ticket' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_announcement" class="col-md-2"><?php echo lang('announcement'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_announcement", "1", get_setting("module_announcement") ? true : false, "id='module_announcement' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_project_timesheet" class="col-md-2"><?php echo lang('project_timesheet'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_project_timesheet", "1", get_setting("module_project_timesheet") ? true : false, "id='module_project_timesheet' class='ml15'");
                            ?>                       
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_help" class="col-md-2"><?php echo lang('help') . " (" . lang("team_members") . ")"; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_help", "1", get_setting("module_help") ? true : false, "id='module_help' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_knowledge_base" class="col-md-2"><?php echo lang('knowledge_base') . " (" . lang("public") . ")"; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_knowledge_base", "1", get_setting("module_knowledge_base") ? true : false, "id='module_knowledge_base' class='ml15'");
                            ?>                       
                        </div>
                    </div>

                <div class="form-group">
                        <label for="module_knowledge_base" class="col-md-2"><?php echo lang('rm_members') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_outsource_members", "1", get_setting("module_outsource_members") ? true : false, "id='module_outsource_members' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_payslip" class="col-md-2"><?php echo lang('payslip'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_payslip", "1", get_setting("module_payslip") ? true : false, "id='payslip' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
<div class="form-group">
                        <label for="module_delivery" class="col-md-2"><?php echo lang('delivery') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_delivery", "1", get_setting("module_delivery") ? true : false, "id='module_delivery' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_purchase_order" class="col-md-2"><?php echo lang('purchase_order'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_purchase_order", "1", get_setting("module_purchase_order") ? true : false, "id='module_purchase_order' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="module_work_order" class="col-md-2"><?php echo lang('work_order'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_work_order", "1", get_setting("module_work_order") ? true : false, "id='module_work_order' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
<div class="form-group">
                        <label for="module_voucher" class="col-md-2"><?php echo lang('voucher') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_voucher", "1", get_setting("module_voucher") ? true : false, "id='module_voucher' class='ml15'");
                            ?>                       
                        </div>
                    </div>
<div class="form-group">
                        <label for="module_master_data" class="col-md-2"><?php echo lang('bank_list') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_master_data", "1", get_setting("module_master_data") ? true : false, "id='module_master_data' class='ml15'");
                            ?>                       
                        </div>
                    </div>   
<div class="form-group">
                        <label for="production_data" class="col-md-2"><?php echo lang('production_data') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_production_data", "1", get_setting("module_production_data") ? true : false, "id='module_production_data' class='ml15'");
                            ?>                       
                        </div>
                    </div>   
<div class="form-group">
                        <label for="assets_data" class="col-md-2"><?php echo lang('assets_data') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_assets_data", "1", get_setting("module_assets_data") ? true : false, "id='module_assets_data' class='ml15'");
                            ?>                       
                        </div>
                    </div>  
                    <div class="form-group">
                        <label for="company_bank_statement" class="col-md-2"><?php echo lang('company_bank_statement') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_company_bank_statement", "1", get_setting("module_company_bank_statement") ? true : false, "id='module_company_bank_statement' class='ml15'");
                            ?>                       
                        </div>
                    </div>  
<div class="form-group">
                        <label for="module_cheque_handler" class="col-md-2"><?php echo lang('cheque_handler') ; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_cheque_handler", "1", get_setting("module_cheque_handler") ? true : false, "id='module_cheque_handler' class='ml15'");
                            ?>                       
                        </div>
                    </div>
<div class="form-group">
                        <label for="module_student_desk" class="col-md-2"><?php echo lang('student_desk'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_student_desk", "1", get_setting("module_student_desk") ? true : false, "id='module_student_desk' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
<div class="form-group">
                        <label for="module_income" class="col-md-2"><?php echo lang('income'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_income", "1", get_setting("module_income") ? true : false, "id='module_income' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_loan" class="col-md-2"><?php echo lang('loan'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_loan", "1", get_setting("module_loan") ? true : false, "id='module_loan' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_state" class="col-md-2"><?php echo lang('states'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_state", "1", get_setting("module_state") ? true : false, "id='module_state' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_country" class="col-md-2"><?php echo lang('countries'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_country", "1", get_setting("module_country") ? true : false, "id='module_country' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_company" class="col-md-2"><?php echo lang('companys'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_company", "1", get_setting("module_company") ? true : false, "id='module_company' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="module_branch" class="col-md-2"><?php echo lang('branches'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_branch", "1", get_setting("module_branch") ? true : false, "id='module_branch' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
                     <div class="form-group">
                        <label for="module_department" class="col-md-2"><?php echo lang('department'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_department", "1", get_setting("module_department") ? true : false, "id='module_department' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
                     <div class="form-group">
                        <label for="module_designation" class="col-md-2"><?php echo lang('designation'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_checkbox("module_designation", "1", get_setting("module_designation") ? true : false, "id='module_designation' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="module_bank_list" class="col-md-2"><?php echo lang('bank_list'); ?></label>
                        <div class="col-md-10">
                            <?php /*
                            echo form_checkbox("module_bank_list", "1", get_setting("module_bank_list") ? true : false, "id='module_bank_list' class='ml15'");
                            */?>                       
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#module-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                location.reload();
            }
        });
    });
</script>