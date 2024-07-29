<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------
  | AUTO-LOADER
  | -------------------------------------------------------------------
  | This file specifies which systems should be loaded by default.
  |
  | In order to keep the framework as light-weight as possible only the
  | absolute minimal resources are loaded by default. For example,
  | the database is not connected to automatically since no assumption
  | is made regarding whether you intend to use it.  This file lets
  | you globally define which systems you would like loaded with every
  | request.
  |
  | -------------------------------------------------------------------
  | Instructions
  | -------------------------------------------------------------------
  |
  | These are the things you can load automatically:
  |
  | 1. Packages
  | 2. Libraries
  | 3. Drivers
  | 4. Helper files
  | 5. Custom config files
  | 6. Language files
  | 7. Models
  |
 */

/*
  | -------------------------------------------------------------------
  |  Auto-load Packages
  | -------------------------------------------------------------------
  | Prototype:
  |
  |  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
  |
 */
$autoload['packages'] = array();

/*
  | -------------------------------------------------------------------
  |  Auto-load Libraries
  | -------------------------------------------------------------------
  | These are the classes located in system/libraries/ or your
  | application/libraries/ directory, with the addition of the
  | 'database' library, which is somewhat of a special case.
  |
  | Prototype:
  |
  |	$autoload['libraries'] = array('database', 'email', 'session');
  |
  | You can also supply an alternative library name to be assigned
  | in the controller:
  |
  |	$autoload['libraries'] = array('user_agent' => 'ua');
 */
$autoload['libraries'] = array('database', 'session', 'form_validation', 'encryption', 'template', 'finediff', 'parser');

/*
  | -------------------------------------------------------------------
  |  Auto-load Drivers
  | -------------------------------------------------------------------
  | These classes are located in system/libraries/ or in your
  | application/libraries/ directory, but are also placed inside their
  | own subdirectory and they extend the CI_Driver_Library class. They
  | offer multiple interchangeable driver options.
  |
  | Prototype:
  |
  |	$autoload['drivers'] = array('cache');
 */
$autoload['drivers'] = array();

/*
  | -------------------------------------------------------------------
  |  Auto-load Helper Files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['helper'] = array('url', 'file');
 */
$autoload['helper'] = array('url', 'file', 'form', 'language', 'general', 'date_time', 'app_files', 'widget', 'activity_logs', 'currency');

/*
  | -------------------------------------------------------------------
  |  Auto-load Config files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['config'] = array('config1', 'config2');
  |
  | NOTE: This item is intended for use ONLY if you have created custom
  | config files.  Otherwise, leave it blank.
  |
 */
$autoload['config'] = array('app');

/*
  | -------------------------------------------------------------------
  |  Auto-load Language files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['language'] = array('lang1', 'lang2');
  |
  | NOTE: Do not include the "_lang" part of your file.  For example
  | "codeigniter_lang.php" would be referenced as array('codeigniter');
  |
 */
$autoload['language'] = array('default', 'custom');

/*
  | -------------------------------------------------------------------
  |  Auto-load Models
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['model'] = array('first_model', 'second_model');
  |
  | You can also supply an alternative model name to be assigned
  | in the controller:
  |
  |	$autoload['model'] = array('first_model' => 'first');
 */
$autoload['model'] = array(
    'Crud_model',
    'Settings_model',
    'Users_model',
    'Team_model',
    'Attendance_model',
    'Leave_types_model',
    'Leave_applications_model',
    'Events_model',
    'Announcements_model',
    'Messages_model',
    'Clients_model',
    'Projects_model',
    'Milestones_model',
    'Task_status_model',
    'Tasks_model',
    'Project_comments_model',
    'Activity_logs_model',
    'Project_files_model',
    'Notes_model',
    'Project_members_model',
    'Ticket_types_model',
    'Tickets_model',
    'Ticket_comments_model',
    'Items_model',
    'Invoices_model',
    'Invoice_items_model',
    'Invoice_payments_model',
    'Payment_methods_model',
    'Email_templates_model',
    'Roles_model',
    'Posts_model',
    'Timesheets_model',
    'Expenses_model',
    'Expense_categories_model',
    'Taxes_model',
    'Social_links_model',
    'Notification_settings_model',
    'Notifications_model',
    'Custom_fields_model',
    'Estimate_forms_model',
    'Estimate_requests_model',
    'Custom_field_values_model',
    'Estimates_model',
    'Estimate_items_model',
    'General_files_model',
    'Todo_model',
    'Client_groups_model',
    'Gst_state_code_model',
    'Hsn_sac_code_model',
    'Payslip_model',
    'Payslip_earnings_model',
    'Payslip_earningsadd_model',
    'Payslip_deductions_model',
    'Payslip_attendance_model',
    'Earnings_model',
    'Earnings_model',
    'Deductions_model',
    'Dashboards_model',
    'Countries_model',
    'Branches_model',
     'Designation_model',
     'Department_model',
     'Delivery_model',
     'Vendors_model',
    'Vendor_groups_model',
    'Outsource_jobs_model',
    'Kyc_info_model',
    'Purchase_orders_model',
    'Purchase_order_items_model',
    'Purchase_order_payments_model',
    'Work_orders_model',
    'Work_order_items_model',
    'Work_order_payments_model',
'Delivery_items_model',
 'Tools_model','Partners_model','Partner_groups_model','Voucher_model','Voucher_expenses_model','Excel_import_model',
 'Bank_name_model','Part_no_generation_model','Product_id_generation_model','States_model','Voucher_types_model','Lut_number_model','Credentials_model','Personal_bank_statement_model','Buyer_types_model','Dc_types_model','Mode_of_dispatch_model','Vendors_invoice_list_model','Cheque_handler_model','Vendors_invoice_status_model','Cheque_categories_model','Cheque_status_model','Vendors_invoice_payments_list_model','Income_model','Loan_model','Loan_payments_list_model','Attendance_todo_model','Student_desk_model','Vap_category_model','Unit_type_model','Manufacturer_model','Clients_po_list_model','Clients_po_payments_list_model','Attendance_task_todo_model','Groups_model','Groups_comments_model','Voucher_comments_model','Payment_status_model','Country_earnings_model','Country_deductions_model','Vat_types_model','Company_groups_model','Companys_model','Clients_wo_list_model','Clients_wo_payments_list_model','Product_categories_model','Payslip_payments_model','Service_categories_model','Job_id_generation_model','Service_id_generation_model','Terms_conditions_templates_model','Estimate_payments_model'

);

