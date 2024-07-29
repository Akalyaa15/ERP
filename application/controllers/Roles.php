<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class roles extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    //load the role view
    function index() {
        $this->template->rander("roles/index");
    }

    //load the role add/edit modal
    function modal_form() { 

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Roles_model->get_one($this->input->post('id'));
        $view_data['roles_dropdown'] = array("" => "-") + $this->Roles_model->get_dropdown_list(array("title"), "id");
        $this->load->view('roles/modal_form', $view_data);
    }

    //get permisissions of a role
    function permissions($role_id) {
        if ($role_id) {
            $view_data['model_info'] = $this->Roles_model->get_one($role_id);

            //$view_data['members_and_teams_dropdown'] = json_encode(get_team_members_and_teams_select2_data_list());
            $view_data['members_and_teams_dropdown'] = json_encode(get_roles_select2_data_list($role_id));

            $ticket_types_dropdown = array();
            $ticket_types = $this->Ticket_types_model->get_all_where(array("deleted" => 0))->result();
            foreach ($ticket_types as $type) {
                $ticket_types_dropdown[] = array("id" => $type->id, "text" => $type->title);
            }
            $view_data['ticket_types_dropdown'] = json_encode($ticket_types_dropdown);

            $permissions = unserialize($view_data['model_info']->permissions);

            if (!$permissions) {
                $permissions = array();
            }

            $view_data['leave'] = get_array_value($permissions, "leave");
            $view_data['leave_specific'] = get_array_value($permissions, "leave_specific");
            $view_data['attendance_specific'] = get_array_value($permissions, "attendance_specific");

            $view_data['attendance'] = get_array_value($permissions, "attendance");
            $view_data['invoice'] = get_array_value($permissions, "invoice");
            $view_data['estimate'] = get_array_value($permissions, "estimate");
            $view_data['expense'] = get_array_value($permissions, "expense");
            $view_data['client'] = get_array_value($permissions, "client");
            $view_data['vendor'] = get_array_value($permissions, "vendor");
            $view_data['purchase_order'] = get_array_value($permissions, "purchase_order");
            $view_data['work_order'] = get_array_value($permissions, "work_order");
           
            

            $view_data['ticket'] = get_array_value($permissions, "ticket");
            $view_data['ticket_specific'] = get_array_value($permissions, "ticket_specific");

            $view_data['announcement'] = get_array_value($permissions, "announcement");
            $view_data['help_and_knowledge_base'] = get_array_value($permissions, "help_and_knowledge_base");

            $view_data['can_manage_all_projects'] = get_array_value($permissions, "can_manage_all_projects");
            $view_data['can_create_projects'] = get_array_value($permissions, "can_create_projects");
            $view_data['can_edit_projects'] = get_array_value($permissions, "can_edit_projects");
            $view_data['can_delete_projects'] = get_array_value($permissions, "can_delete_projects");

            $view_data['can_add_remove_project_members'] = get_array_value($permissions, "can_add_remove_project_members");

            $view_data['can_create_tasks'] = get_array_value($permissions, "can_create_tasks");
            $view_data['can_edit_tasks'] = get_array_value($permissions, "can_edit_tasks");
            $view_data['can_delete_tasks'] = get_array_value($permissions, "can_delete_tasks");
            $view_data['can_comment_on_tasks'] = get_array_value($permissions, "can_comment_on_tasks");

            $view_data['can_create_milestones'] = get_array_value($permissions, "can_create_milestones");
            $view_data['can_edit_milestones'] = get_array_value($permissions, "can_edit_milestones");
            $view_data['can_delete_milestones'] = get_array_value($permissions, "can_delete_milestones");

            $view_data['can_delete_files'] = get_array_value($permissions, "can_delete_files");

            $view_data['can_view_team_members_contact_info'] = get_array_value($permissions, "can_view_team_members_contact_info");
            $view_data['can_view_team_members_social_links'] = get_array_value($permissions, "can_view_team_members_social_links");
            $view_data['team_member_update_permission'] = get_array_value($permissions, "team_member_update_permission");
            $view_data['team_member_update_permission_specific'] = get_array_value($permissions, "team_member_update_permission_specific");

            $view_data['timesheet_manage_permission'] = get_array_value($permissions, "timesheet_manage_permission");
            $view_data['timesheet_manage_permission_specific'] = get_array_value($permissions, "timesheet_manage_permission_specific");

            $view_data['disable_event_sharing'] = get_array_value($permissions, "disable_event_sharing");

            $view_data['hide_team_members_list'] = get_array_value($permissions, "hide_team_members_list");

            $view_data['can_delete_leave_application'] = get_array_value($permissions, "can_delete_leave_application");
            
             $view_data['hagwaytower'] = get_array_value($permissions, "hagwaytower");
            $view_data['hagwaytower_specific'] = get_array_value($permissions, "hagwaytower_specific");
            $view_data['delivery'] = get_array_value($permissions, "delivery");
            $view_data['delivery_specific'] = get_array_value($permissions, "delivery_specific");
             $view_data['bank_statement'] = get_array_value($permissions, "bank_statement");
            $view_data['bank_statement_specific'] = get_array_value($permissions, "bank_statement_specific");
            $view_data['voucher'] = get_array_value($permissions, "voucher");
            $view_data['voucher_specific'] = get_array_value($permissions, "voucher_specific");
            $view_data['gemicates_tower'] = get_array_value($permissions, "gemicates_tower");
             $view_data['gemicates_tower_specific'] = get_array_value($permissions, "gemicates_tower_specific");
            $view_data['inframotetower'] = get_array_value($permissions, "inframotetower");
             $view_data['inframotetower_specific'] = get_array_value($permissions, "inframotetower_specific");
            $view_data['gem_lab_admin'] = get_array_value($permissions, "gem_lab_admin");
             $view_data['gem_lab_admin_specific'] = get_array_value($permissions, "gem_lab_admin_specific");
            $view_data['gemicates_seller_portal'] = get_array_value($permissions, "gemicates_seller_portal");
            $view_data['gemicates_seller_portal_specific'] = get_array_value($permissions, "gemicates_seller_portal_specific");
            $view_data['can_access_device_manager'] = get_array_value($permissions, "can_access_device_manager");
            $view_data['can_access_IR_library'] = get_array_value($permissions, "can_access_IR_library");
            $view_data['can_access_channel_library'] = get_array_value($permissions, "can_access_channel_library");
            $view_data['can_access_VMS_library'] = get_array_value($permissions, "can_access_VMS_library");
            $view_data['can_access_hagway_manager'] = get_array_value($permissions, "can_access_hagway_manager");
            $view_data['can_access_website_manager'] = get_array_value($permissions, "can_access_website_manager");
            $view_data['can_access_architecture'] = get_array_value($permissions, "can_access_architecture");
            $view_data['can_access_enquiry_manager'] = get_array_value($permissions, "can_access_enquiry_manager");
            $view_data['can_access_inframote_manager'] = get_array_value($permissions, "can_access_inframote_manager");

            $view_data['master_data'] = get_array_value($permissions, "master_data");
            $view_data['master_data_specific'] = get_array_value($permissions, "master_data_specific");

            $view_data['register'] = get_array_value($permissions, "register");
            $view_data['register_specific'] = get_array_value($permissions, "register_specific");

            $view_data['production_data'] = get_array_value($permissions, "production_data");
            $view_data['production_data_specific'] = get_array_value($permissions, "production_data_specific");
            $view_data['assets_data'] = get_array_value($permissions, "assets_data");
            $view_data['assets_data_specific'] = get_array_value($permissions, "assets_data_specific");
            $view_data['outsource_members'] = get_array_value($permissions, "outsource_members");
            $view_data['outsource_members_specific'] = get_array_value($permissions, "outsource_members_specific");
            $view_data['payslip'] = get_array_value($permissions, "payslip");
            $view_data['payslip_specific'] = get_array_value($permissions, "payslip_specific");

            $view_data['company_bank_statement'] = get_array_value($permissions, "company_bank_statement");
            $view_data['company_bank_statement_specific'] = get_array_value($permissions, "company_bank_statement_specific");
            $view_data['tools'] = get_array_value($permissions, "tools");
            $view_data['tools_specific'] = get_array_value($permissions, "tools_specific");
            $view_data['cheque_handler'] = get_array_value($permissions, "cheque_handler");
            $view_data['cheque_handler_specific'] = get_array_value($permissions, "cheque_handler_specific");

            $view_data['student_desk'] = get_array_value($permissions, "student_desk");
            $view_data['student_desk_specific'] = get_array_value($permissions, "student_desk_specific");

            //sub module permissions
            $view_data['loan'] = get_array_value($permissions, "loan");
            $view_data['loan_specific'] = get_array_value($permissions, "loan_specific");

            $view_data['income'] = get_array_value($permissions, "income");
            $view_data['income_specific'] = get_array_value($permissions, "income_specific");

            $view_data['company'] = get_array_value($permissions, "company");
            $view_data['company_specific'] = get_array_value($permissions, "company_specific");

            $view_data['country'] = get_array_value($permissions, "country");
            $view_data['country_specific'] = get_array_value($permissions, "country_specific");

            $view_data['state'] = get_array_value($permissions, "state");
            $view_data['state_specific'] = get_array_value($permissions, "state_specific");

            $view_data['branch'] = get_array_value($permissions, "branch");
            $view_data['branch_specific'] = get_array_value($permissions, "branch_specific");

            $view_data['department'] = get_array_value($permissions, "department");
            $view_data['department_specific'] = get_array_value($permissions, "department_specific");

            $view_data['designation'] = get_array_value($permissions, "designation");
            $view_data['designation_specific'] = get_array_value($permissions, "designation_specific");

            $view_data['inventory'] = get_array_value($permissions, "inventory");
            $view_data['inventory_specific'] = get_array_value($permissions, "inventory_specific");

            $this->load->view("roles/permissions", $view_data);
        }
    }

    //save a role
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        $copy_settings = $this->input->post('copy_settings');
        $data = array(
            "title" => $this->input->post('title'),
        );

        if ($copy_settings) {
            $role = $this->Roles_model->get_one($copy_settings);
            $data["permissions"] = $role->permissions;
        }

        $save_id = $this->Roles_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save permissions of a role
    function save_permissions() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        $leave = $this->input->post('leave_permission');
        $leave_specific = "";
        if ($leave === "specific") {
            $leave_specific = $this->input->post('leave_permission_specific');
        }

        $attendance = $this->input->post('attendance_permission');
        $attendance_specific = "";
        if ($attendance === "specific") {
            $attendance_specific = $this->input->post('attendance_permission_specific');
        }
        $hagwaytower = $this->input->post('hagwaytower_permission');
        $hagwaytower_specific = "";
        if ($hagwaytower === "specific") {
            $hagwaytower_specific = $this->input->post('hagwaytower_permission_specific');
        }
        $delivery = $this->input->post('delivery_permission');
        $delivery_specific = "";
        if ($delivery === "specific") {
            $delivery_specific = $this->input->post('delivery_permission_specific');
        }
        $bank_statement = $this->input->post('bank_statement_permission');
        $bank_statement_specific = "";
        if ($bank_statement === "specific") {
            $bank_statement_specific = $this->input->post('bank_statement_permission_specific');
        }
        $voucher = $this->input->post('voucher_permission');
        $voucher_specific = "";
        if ($voucher === "specific") {
            $voucher_specific = $this->input->post('voucher_permission_specific');
        }
        $gemicates_tower = $this->input->post('gemicates_tower_permission');
         $gemicates_tower_specific = "";
        if ($gemicates_tower === "specific") {
            $gemicates_tower_specific = $this->input->post('gemicates_tower_permission_specific');
        }
        $inframotetower = $this->input->post('inframotetower_permission');
        $inframotetower_specific = "";
        if ($inframotetower === "specific") {
            $inframotetower_specific = $this->input->post('inframotetower_permission_specific');
        }
        
        $gem_lab_admin = $this->input->post('gem_lab_admin_permission');
        $gem_lab_admin_specific = "";
        if ($gem_lab_admin === "specific") {
            $gem_lab_admin_specific = $this->input->post('gem_lab_admin_permission_specific');
        }
        $gemicates_seller_portal = $this->input->post('gemicates_seller_portal_permission');
        $gemicates_seller_portal_specific = "";
        if ($gemicates_seller_portal === "specific") {
            $gemicates_seller_portal_specific = $this->input->post('gemicates_seller_portal_permission_specific');
        }

         $master_data = $this->input->post('master_data_permission');
        $master_data_specific = "";
        if ($master_data === "specific") {
            $master_data_specific = $this->input->post('master_data_permission_specific');
        }

         $register = $this->input->post('register_permission');
        $register_specific = "";
        if ($register === "specific") {
            $register_specific = $this->input->post('register_permission_specific');
        }
                $production_data = $this->input->post('production_data_permission');
        $production_data_specific = "";
        if ($production_data === "specific") {
            $production_data_specific = $this->input->post('production_data_permission_specific');
        }
        
        $assets_data = $this->input->post('assets_data_permission');
        $assets_data_specific = "";
        if ($assets_data === "specific") {
            $assets_data_specific = $this->input->post('assets_data_permission_specific');
        }

        $outsource_members = $this->input->post('outsource_members_permission');
        $outsource_members_specific = "";
        if ($outsource_members === "specific") {
            $outsource_members_specific = $this->input->post('outsource_members_permission_specific');
        }

        $payslip = $this->input->post('payslip_permission');
        $payslip_specific = "";
        if ($payslip === "specific") {
            $payslip_specific = $this->input->post('payslip_permission_specific');
        }

        $company_bank_statement = $this->input->post('company_bank_statement_permission');
        $company_bank_statement_specific = "";
        if ($company_bank_statement === "specific") {
            $company_bank_statement_specific = $this->input->post('company_bank_statement_permission_specific');
        }

        $tools = $this->input->post('tools_permission');
        $tools_specific = "";
        if ($tools === "specific") {
            $tools_specific = $this->input->post('tools_permission_specific');
        }

        $cheque_handler = $this->input->post('cheque_handler_permission');
        $cheque_handler_specific = "";
        if ($cheque_handler === "specific") {
            $cheque_handler_specific = $this->input->post('cheque_handler_permission_specific');
        }

        // sub modules permissions
        $loan = $this->input->post('loan_permission');
        $loan_specific = "";
        if ($loan === "specific") {
            $loan_specific = $this->input->post('loan_permission_specific');
        }

        $income = $this->input->post('income_permission');
        $income_specific = "";
        if ($income === "specific") {
            $income_specific = $this->input->post('income_permission_specific');
        }


        $company = $this->input->post('company_permission');
        $company_specific = "";
        if ($company === "specific") {
            $company_specific = $this->input->post('company_permission_specific');
        }

        $country = $this->input->post('country_permission');
        $country_specific = "";
        if ($country === "specific") {
            $country_specific = $this->input->post('country_permission_specific');
        }

        $state = $this->input->post('state_permission');
        $state_specific = "";
        if ($state === "specific") {
            $state_specific = $this->input->post('state_permission_specific');
        }

        $branch = $this->input->post('branch_permission');
        $branch_specific = "";
        if ($branch === "specific") {
            $branch_specific = $this->input->post('branch_permission_specific');
        }

        $department = $this->input->post('department_permission');
        $department_specific = "";
        if ($department === "specific") {
            $department_specific = $this->input->post('department_permission_specific');
        }

        $designation = $this->input->post('designation_permission');
        $designation_specific = "";
        if ($designation === "specific") {
            $designation_specific = $this->input->post('designation_permission_specific');
        }

         $inventory = $this->input->post('inventory_permission');
        $inventory_specific = "";
        if ($inventory === "specific") {
            $inventory_specific = $this->input->post('inventory_permission_specific');
        }





        $can_access_device_manager = $this->input->post('can_access_device_manager');
       $can_access_IR_library = $this->input->post('can_access_IR_library');
       $can_access_channel_library = $this->input->post('can_access_channel_library');
        $can_access_VMS_library = $this->input->post('can_access_VMS_library');
        $can_access_hagway_manager = $this->input->post('can_access_hagway_manager');
        $can_access_website_manager = $this->input->post('can_access_website_manager');
        $can_access_architecture = $this->input->post('can_access_architecture');
        $can_access_enquiry_manager = $this->input->post('can_access_enquiry_manager');
        $can_access_inframote_manager = $this->input->post('can_access_inframote_manager');

        $invoice = $this->input->post('invoice_permission');
        $estimate = $this->input->post('estimate_permission');
        $expense = $this->input->post('expense_permission');
        $client = $this->input->post('client_permission');
        $vendor = $this->input->post('vendor_permission');
        $purchase_order = $this->input->post('purchase_order_permission');
        $work_order = $this->input->post('work_order_permission');


        $ticket = $this->input->post('ticket_permission');

        $ticket_specific = "";
        if ($ticket === "specific") {
            $ticket_specific = $this->input->post('ticket_permission_specific');
        }


        $can_manage_all_projects = $this->input->post('can_manage_all_projects');
        $can_create_projects = $this->input->post('can_create_projects');
        $can_edit_projects = $this->input->post('can_edit_projects');
        $can_delete_projects = $this->input->post('can_delete_projects');

        $can_add_remove_project_members = $this->input->post('can_add_remove_project_members');

        $can_create_tasks = $this->input->post('can_create_tasks');
        $can_edit_tasks = $this->input->post('can_edit_tasks');
        $can_delete_tasks = $this->input->post('can_delete_tasks');
        $can_comment_on_tasks = $this->input->post('can_comment_on_tasks');

        $can_create_milestones = $this->input->post('can_create_milestones');
        $can_edit_milestones = $this->input->post('can_edit_milestones');
        $can_delete_milestones = $this->input->post('can_delete_milestones');

        $can_delete_files = $this->input->post('can_delete_files');

        $announcement = $this->input->post('announcement_permission');
        $help_and_knowledge_base = $this->input->post('help_and_knowledge_base');

        $can_view_team_members_contact_info = $this->input->post('can_view_team_members_contact_info');
        $can_view_team_members_social_links = $this->input->post('can_view_team_members_social_links');
        $team_member_update_permission = $this->input->post('team_member_update_permission');
        $team_member_update_permission_specific = $this->input->post('team_member_update_permission_specific');

        $timesheet_manage_permission = $this->input->post('timesheet_manage_permission');
        $timesheet_manage_permission_specific = $this->input->post('timesheet_manage_permission_specific');

        $disable_event_sharing = $this->input->post('disable_event_sharing');

        $hide_team_members_list = $this->input->post('hide_team_members_list');

        $can_delete_leave_application = $this->input->post('can_delete_leave_application');

        $student_desk = $this->input->post('student_desk_permission');
        $student_desk_specific = "";
        if ($student_desk === "specific") {
            $student_desk_specific = $this->input->post('student_desk_permission_specific');
        }


        $permissions = array(
            "leave" => $leave,
            "leave_specific" => $leave_specific,
            "attendance" => $attendance,
            "attendance_specific" => $attendance_specific,
            "invoice" => $invoice,
            "estimate" => $estimate,
            "expense" => $expense,
            "client" => $client,
            "vendor" => $vendor,
            "purchase_order" => $purchase_order,
            "work_order" => $work_order,
            "ticket" => $ticket,
            "ticket_specific" => $ticket_specific,
            "announcement" => $announcement,
            "help_and_knowledge_base" => $help_and_knowledge_base,
            "can_manage_all_projects" => $can_manage_all_projects,
            "can_create_projects" => $can_create_projects,
            "can_edit_projects" => $can_edit_projects,
            "can_delete_projects" => $can_delete_projects,
            "can_add_remove_project_members" => $can_add_remove_project_members,
            "can_create_tasks" => $can_create_tasks,
            "can_edit_tasks" => $can_edit_tasks,
            "can_delete_tasks" => $can_delete_tasks,
            "can_comment_on_tasks" => $can_comment_on_tasks,
            "can_create_milestones" => $can_create_milestones,
            "can_edit_milestones" => $can_edit_milestones,
            "can_delete_milestones" => $can_delete_milestones,
            "can_delete_files" => $can_delete_files,
            "can_view_team_members_contact_info" => $can_view_team_members_contact_info,
            "can_view_team_members_social_links" => $can_view_team_members_social_links,
            "team_member_update_permission" => $team_member_update_permission,
            "team_member_update_permission_specific" => $team_member_update_permission_specific,
            "timesheet_manage_permission" => $timesheet_manage_permission,
            "timesheet_manage_permission_specific" => $timesheet_manage_permission_specific,
            "disable_event_sharing" => $disable_event_sharing,
            "hide_team_members_list" => $hide_team_members_list,
            "can_delete_leave_application" => $can_delete_leave_application,
            "hagwaytower" => $hagwaytower,
            "hagwaytower_specific" => $hagwaytower_specific,
             "delivery" => $delivery,
            "delivery_specific" => $delivery_specific,
            "voucher" => $voucher,
            "voucher_specific" => $voucher_specific,
            "inframotetower" => $inframotetower,
            "inframotetower_specific" => $inframotetower_specific,
            "gem_lab_admin" => $gem_lab_admin,
            "gem_lab_admin_specific" => $gem_lab_admin_specific,
            "gemicates_seller_portal" => $gemicates_seller_portal,
            "gemicates_seller_portal_specific" => $gemicates_seller_portal_specific,
            "can_access_device_manager" => $can_access_device_manager,
            "can_access_IR_library" => $can_access_IR_library,
            "can_access_channel_library" => $can_access_channel_library,
            "can_access_VMS_library" => $can_access_VMS_library,
            "can_access_hagway_manager" => $can_access_hagway_manager,
            "can_access_website_manager" => $can_access_website_manager,
            "can_access_architecture" => $can_access_architecture,
            "can_access_enquiry_manager" => $can_access_enquiry_manager,
            "can_access_inframote_manager" => $can_access_inframote_manager,
            "gemicates_tower" => $gemicates_tower,
            "gemicates_tower_specific" => $gemicates_tower_specific,
             "register" => $register,
            "register_specific" => $register_specific,
           "master_data" => $master_data,
            "master_data_specific" => $master_data_specific,
            "production_data" => $production_data,
            "production_data_specific" => $production_data_specific,
            "assets_data" => $assets_data,
            "assets_data_specific" => $assets_data_specific,
            "bank_statement" => $bank_statement,
            "bank_statement_specific" => $bank_statement_specific,
            "outsource_members" => $outsource_members,
            "outsource_members_specific" => $outsource_members_specific,
            "payslip" => $payslip,
            "payslip_specific" => $payslip_specific,
                        "company_bank_statement" => $company_bank_statement,
            "company_bank_statement_specific" => $company_bank_statement_specific,
            "tools" => $tools,
            "tools_specific" => $tools_specific,
            "cheque_handler" => $cheque_handler,
            "cheque_handler_specific" => $cheque_handler_specific,
            "student_desk" => $student_desk,
            "student_desk_specific" => $student_desk_specific,
            "loan" => $loan,
            "loan_specific" => $loan_specific,
            "income" => $income,
            "income_specific" => $income_specific,
            "company" => $company,
            "company_specific" => $company_specific,
            "country" => $country,
            "country_specific" => $country_specific,
            "state" => $state,
            "state_specific" => $state_specific,
            "branch" => $branch,
            "branch_specific" => $branch_specific,
            "department" => $department,
            "department_specific" => $department_specific,
            "designation" => $designation,
            "designation_specific" => $designation_specific,
            "inventory" => $inventory,
            "inventory_specific" => $inventory_specific,
        );

        $data = array(
            "permissions" => serialize($permissions),
        );

        $save_id = $this->Roles_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete or undo a role
    function delete() {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Roles_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Roles_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //get role list data
    function list_data() {
        $list_data = $this->Roles_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of role list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Roles_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //make a row of role list table
    private function _make_row($data) {
        return array("<a href='#' data-id='$data->id' class='role-row link'>" . $data->title . "</a>",
            "<a class='edit'><i class='fa fa-check' ></i></a>" . modal_anchor(get_uri("roles/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "", "title" => lang('edit_role'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_role'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("roles/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file roles.php */
/* Location: ./application/controllers/roles.php */
