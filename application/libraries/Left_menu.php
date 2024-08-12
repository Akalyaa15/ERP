<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Left_menu {

    private $ci = null;

    public function __construct() {
        $this->ci = & get_instance();
    }

    function get_available_items($type = "default") {
        $items_array = $this->_prepare_sidebar_menu_items($type);

        //remove used items
        $default_left_menu_items = $this->_get_left_menu_from_setting($type);

        foreach ($default_left_menu_items as $default_item) {
            unset($items_array[get_array_value($default_item, "name")]);
        }

        $items = "";
        foreach ($items_array as $item) {
            $items .= $this->_get_item_data($item, true);
        }

        return $items ? $items : "<span class='text-off empty-area-text'>" . lang('no_more_items_available') . "</span>";
    }
private function _prepare_sidebar_menu_items($type = "") {
        $final_items_array = array();
        $items_array = $this->_get_sidebar_menu_items($type);

        foreach ($items_array as $item) {
            $main_menu_name = get_array_value($item, "name");

            if (isset($item["submenu"])) {
                //first add this menu removing the submenus
                if ($main_menu_name !== "finance" && $main_menu_name !== "help_and_support") {
                    $main_menu = $item;
                    unset($main_menu["submenu"]);
                    $final_items_array[$main_menu_name] = $main_menu;
                }

                $submenu = get_array_value($item, "submenu");
                foreach ($submenu as $key => $s_menu) {
                    //prepare help items differently
                    if ($main_menu_name == "help_and_support") {
                        $s_menu = $this->_make_customized_sub_menu_for_help_and_support($key, $s_menu);
                    }

                    if (get_array_value($s_menu, "class")) {
                        $final_items_array[get_array_value($s_menu, "name")] = $s_menu;
                    }
                }
            } else {
                $final_items_array[$main_menu_name] = $item;
            }
        }
        //add todo
        $final_items_array["todo"] = array("name" => "todo", "url" => "todo", "class" => "fa-check-square-o");
        return $final_items_array;
    }

    private function _make_customized_sub_menu_for_help_and_support($key, $s_menu) {
        if ($key == 1) {
            $s_menu["name"] = "help_articles";
        } else if ($key == 2) {
            $s_menu["name"] = "help_categories";
        } else if ($key == 4) {
            $s_menu["name"] = "knowledge_base_articles";
        } else if ($key == 5) {
            $s_menu["name"] = "knowledge_base_categories";
        }
         return $s_menu;
    }

    private function _get_left_menu_from_setting_for_rander($is_preview = false, $type = "default") {
        $user_left_menu = get_setting("user_" . $this->ci->login_user->id . "_left_menu");
        $default_left_menu = ($type == "client_default" || $this->ci->login_user->user_type == "client") ? get_setting("default_client_left_menu") : get_setting("default_left_menu");
        if($type == "vendor_default"|| $this->ci->login_user->user_type == "vendor"){
           $default_left_menu = ($type == "vendor_default" || $this->ci->login_user->user_type == "vendor") ? get_setting("default_vendor_left_menu") : get_setting("default_left_menu"); 
        }
        $custom_left_menu = "";

        //for preview, show the edit type preview
        if ($is_preview) {
            $custom_left_menu = $default_left_menu; //default preview
            if ($type == "user") {
                $custom_left_menu = $user_left_menu ? $user_left_menu : $default_left_menu; //user level preview
            }
        } else {
            $custom_left_menu = $user_left_menu ? $user_left_menu : $default_left_menu; //page rander
        }

        return $custom_left_menu ? json_decode(json_encode(@unserialize($custom_left_menu)), true) : array();
    }

    private function _get_left_menu_from_setting($type) {
        if ($type == "client_default") {
            $default_left_menu = get_setting("default_client_left_menu");
        }else if ($type == "vendor_default") {
            $default_left_menu = get_setting("default_vendor_left_menu");
        } else if ($type == "user") {
            $default_left_menu = get_setting("user_" . $this->ci->login_user->id . "_left_menu");
        } else {
            $default_left_menu = get_setting("default_left_menu");
        }

        return $default_left_menu ? json_decode(json_encode(@unserialize($default_left_menu)), true) : array();
    }

    public function _get_item_data($item, $is_default_item = false) {
        $name = get_array_value($item, "name");
        $url = get_array_value($item, "url");
        $is_sub_menu = get_array_value($item, "is_sub_menu");
        $open_in_new_tab = get_array_value($item, "open_in_new_tab");
        $icon = get_array_value($item, "icon");

        if ($name) {
            $sub_menu_class = "";
            if ($is_sub_menu) {
                $sub_menu_class = "ml20";
            }

            $extra_attr = "";
            $edit_button = "";
            $name_lang = "";
            if ($is_default_item || !$url) {
                $name_lang = lang($name);
            } else {
                //custom menu item
                $extra_attr = "data-url='$url' data-icon='$icon' data-custom_menu_item_id='" . rand(2000, 400000000) . "' data-open_in_new_tab='$open_in_new_tab'";
                $name_lang = $name;
                $edit_button = modal_anchor(get_uri("left_menus/add_menu_item_modal_form"), "<i class='fa fa-pencil'></i> ", array("title" => lang('edit'), "class" => "custom-menu-edit-button", "data-post-title" => $name, "data-post-url" => $url, "data-post-is_sub_menu" => $is_sub_menu, "data-post-icon" => $icon, "data-post-open_in_new_tab" => $open_in_new_tab));
            }

            return "<div data-value='" . $name . "' $extra_attr class='left-menu-item mb5 widget clearfix p10 bg-white $sub_menu_class'>
                        <span class='pull-left text-left'><i class='fa fa-arrows text-off pr5'></i> " . $name_lang . "</span>
                        <span class='pull-right invisible'>
                            <i class='fa fa-level-down clickable make-sub-menu font-14' title='" . lang("make_previous_items_sub_menu") . "'></i>
                            $edit_button
                            <i class='fa fa-times text-danger clickable delete-left-menu-item font-14' title=" . lang("delete") . "></i>
                        </span>
                    </div>";
        }
    }

    function get_sortable_items($type = "default") {
        $items = "<div id='menu-item-list-2' class='js-left-menu-scrollbar add-column-drop text-center p15 menu-item-list sortable-items-container'>";

        $default_left_menu_items = $this->_get_left_menu_from_setting($type);
        if (count($default_left_menu_items)) {
            foreach ($default_left_menu_items as $item) {
                $items .= $this->_get_item_data($item);
            }
        } else {
            $items .= "<span class='text-off empty-area-text'>" . lang('drag_and_drop_items_here') . "</span>";
        }

        $items .= "</div>";

        return $items;
    }

    function rander_left_menu($is_preview = false, $type = "default") {
        $final_left_menu_items = array();
        $custom_left_menu_items = $this->_get_left_menu_from_setting_for_rander($is_preview, $type);

        if ($custom_left_menu_items) {
            $left_menu_items = $this->_prepare_sidebar_menu_items($type);

            $last_menu_item = ""; //store last menu item to the get the data on creating submenu
            $last_final_menu_item = ""; //store the last menu item of final left menu to add submenu to this item 
            $parent_item_added_as_submenu = false;

            foreach ($custom_left_menu_items as $key => $value) {
                $item_value_array = $this->_get_item_array_value($value, $left_menu_items);

                $is_sub_menu = get_array_value($value, "is_sub_menu");
                if ($is_sub_menu) {
                    //this is a sub menu, move it to it's parent item                        
                    //since the parent item is also a standalone menu item, make a submenu of that too
                    //but if any other menu item which haven't any submenu, added as a submenu of a menu item which have submenu, that won't be added

                    $parent_item_array = $this->_get_item_array_value(get_array_value($custom_left_menu_items, $last_menu_item), $left_menu_items);
                    if (!$parent_item_added_as_submenu && !isset($parent_item_array["submenu"])) {
                        $final_left_menu_items[$last_final_menu_item]["submenu"][] = $parent_item_array;
                        $parent_item_added_as_submenu = true;
                    }

                    //add this item
                    array_push($final_left_menu_items[$last_final_menu_item]["submenu"], $item_value_array);
                } else {
                    $final_left_menu_items[] = $item_value_array;
                    $last_menu_item = $key;
                    $last_final_menu_item = end($final_left_menu_items);
                    $last_final_menu_item = key($final_left_menu_items);
                    $parent_item_added_as_submenu = false;
                }
            }
        }

        $view_data["show_devider"] = true;

        if (count($final_left_menu_items)) {
            $view_data["sidebar_menu"] = $final_left_menu_items;
            $view_data["show_devider"] = false;
        } else {
            $view_data["sidebar_menu"] = $this->_get_sidebar_menu_items($type);
        }

        $view_data["is_preview"] = $is_preview;
        return $this->ci->load->view("includes/left_menu", $view_data, true);
    }


    function rander_left_menu_preview($is_preview = false, $type = "default") {
        $final_left_menu_items = array();
        $custom_left_menu_items = $this->_get_left_menu_from_setting_for_rander($is_preview, $type);

        if ($custom_left_menu_items) {
            $left_menu_items = $this->_prepare_sidebar_menu_items($type);

            $last_menu_item = ""; //store last menu item to the get the data on creating submenu
            $last_final_menu_item = ""; //store the last menu item of final left menu to add submenu to this item 
            $parent_item_added_as_submenu = false;

            foreach ($custom_left_menu_items as $key => $value) {
                $item_value_array = $this->_get_item_array_value($value, $left_menu_items);

                $is_sub_menu = get_array_value($value, "is_sub_menu");
                if ($is_sub_menu) {
                    //this is a sub menu, move it to it's parent item                        
                    //since the parent item is also a standalone menu item, make a submenu of that too
                    //but if any other menu item which haven't any submenu, added as a submenu of a menu item which have submenu, that won't be added

                    $parent_item_array = $this->_get_item_array_value(get_array_value($custom_left_menu_items, $last_menu_item), $left_menu_items);
                    if (!$parent_item_added_as_submenu && !isset($parent_item_array["submenu"])) {
                        $final_left_menu_items[$last_final_menu_item]["submenu"][] = $parent_item_array;
                        $parent_item_added_as_submenu = true;
                    }

                    //add this item
                    array_push($final_left_menu_items[$last_final_menu_item]["submenu"], $item_value_array);
                } else {
                    $final_left_menu_items[] = $item_value_array;
                    $last_menu_item = $key;
                    $last_final_menu_item = end($final_left_menu_items);
                    $last_final_menu_item = key($final_left_menu_items);
                    $parent_item_added_as_submenu = false;
                }
            }
        }

        $view_data["show_devider"] = true;

        if (count($final_left_menu_items)) {
            $view_data["sidebar_menu"] = $final_left_menu_items;
            $view_data["show_devider"] = false;
        } else {
            $view_data["sidebar_menu"] = $this->_get_sidebar_menu_items($type);
        }

        $view_data["is_preview"] = $is_preview;
        return $this->ci->load->view("includes/left_menu_preview", $view_data, true);
    }

    private function _get_item_array_value($data_array, $left_menu_items) {
        $name = get_array_value($data_array, "name");
        $url = get_array_value($data_array, "url");
        $icon = get_array_value($data_array, "icon");
        $open_in_new_tab = get_array_value($data_array, "open_in_new_tab");
        $item_value_array = array();

        if ($url) { //custom menu item
            $item_value_array = array("name" => $name, "url" => $url, "is_custom_menu_item" => true, "class" => "fa-$icon", "open_in_new_tab" => $open_in_new_tab);
        } else if (array_key_exists($name, $left_menu_items)) { //default menu items
            $item_value_array = get_array_value($left_menu_items, $name);
        }
     return $item_value_array;
    }


    private function _get_sidebar_menu_items($type = "") {
        $dashboard_menu = array("name" => "dashboard", "url" => "dashboard", "class" => "fa-desktop dashboard-menu");

        $selected_dashboard_id = get_setting("user_" . $this->ci->login_user->id . "_dashboard");
        if ($selected_dashboard_id) {
            $dashboard_menu = array("name" => "dashboard", "url" => "dashboard/view/" . $selected_dashboard_id, "class" => "fa-desktop dashboard-menu");
        }

        if (($this->ci->login_user->user_type == "staff"||$this->ci->login_user->user_type == "resource") && $type !== "client_default" && $type !== "vendor_default") {
            
// start
            $sidebar_menu = array("dashboard" => $dashboard_menu);
            $permissions = $this->ci->login_user->permissions;

                $access_expense = get_array_value($permissions, "expense");
                $access_invoice = get_array_value($permissions, "invoice");
                $access_ticket = get_array_value($permissions, "ticket");
                $access_client = get_array_value($permissions, "client");
                 $access_vendor = get_array_value($permissions, "vendor");
                  $access_purchase_order = get_array_value($permissions, "purchase_order");
                $access_timecard = get_array_value($permissions, "attendance");
                $access_leave = get_array_value($permissions, "leave");
                
                $access_estimate = get_array_value($permissions, "estimate");
                $access_items = ($this->ci->login_user->is_admin || $access_invoice || $access_estimate );
                $access_payslip = get_array_value($permissions, "payslip");
                $access_work_order = get_array_value($permissions, "work_order");
                 $access_outsource_jobs = ($this->ci->login_user->is_admin || $access_work_order );

                 $access_master_data = get_array_value($permissions, "master_data");
                $access_production_data = get_array_value($permissions, "production_data");
                $access_assets_data = get_array_value($permissions, "assets_data");
                 $access_voucher = get_array_value($permissions, "voucher");
                $access_outsource_members = get_array_value($permissions, "outsource_members");
$access_delivery = get_array_value($permissions, "delivery");
$access_tools = get_array_value($permissions, "tools");
$access_cheque_handler = get_array_value($permissions, "cheque_handler");
$access_company_bank_statement = get_array_value($permissions, "company_bank_statement");
$access_student_desk = get_array_value($permissions, "student_desk");
$access_register = get_array_value($permissions, "register");

                $manage_help_and_knowledge_base = ($this->ci->login_user->is_admin || get_array_value($permissions, "help_and_knowledge_base"));
                $manage_timesheets = ($this->ci->login_user->is_admin || get_array_value($permissions, "timesheet_manage_permission"));
                $access_loan = get_array_value($permissions, "loan");
                $access_income = get_array_value($permissions, "income");
                $access_company = get_array_value($permissions, "company");
                $access_country = get_array_value($permissions, "country");
                $access_state = get_array_value($permissions, "state");
                $access_branch = get_array_value($permissions, "branch");
                $access_department = get_array_value($permissions, "department");
                $access_designation = get_array_value($permissions, "designation");
                $access_inventory = get_array_value($permissions, "inventory");



                if (get_setting("module_timeline") == "1") {
                    $sidebar_menu[] = array("name" => "timeline", "url" => "timeline", "class" => " fa-comments font-18");
                }
                
  

                if (get_setting("module_event") == "1") {
                    $sidebar_menu[] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
                }
                if (get_setting("module_note") == "1") {
                    $sidebar_menu[] = array("name" => "notes", "url" => "notes", "class" => "fa-book font-16");
                }
                 if ($this->ci->login_user->user_type == "staff"){
                if (get_setting("module_message") == "1") {
                    $sidebar_menu[] = array("name" => "messages", "url" => "messages", "class" => "fa-envelope", "devider" => true, "badge" => count_unread_message(), "badge_class" => "badge-secondary");
                }
}
                if (get_setting("module_voucher") == "1" ) {
                    $sidebar_menu[] = array("name" => "voucher", "url" => "voucher", "class" => "fa-fax");
                   }
                
                
                if ((get_setting("module_master_data") == "1" ||get_setting("module_company") == "1"||
                    get_setting("module_country") == "1" ||get_setting("module_branch") == "1" ||get_setting("module_state") == "1" ||get_setting("module_department") == "1" ||get_setting("module_designation") == "1") && ($access_vendor||$access_client||$access_designation ||$access_department ||$access_branch ||$access_state ||
                    $access_company ||$access_country ||$access_master_data || $this->ci->login_user->is_admin) ){

                                       $master_data_submenu = array();

                     if ($this->ci->login_user->is_admin || $access_client) {
        
                     $master_data_submenu[] =array("name" => "clients", "url" => "clients", "class" => "fa-briefcase","classs"=>"clientss");
                }

                     if ($this->ci->login_user->is_admin || $access_vendor) {
                     $master_data_submenu[] = array("name" => "vendors", "url" => "vendors", "class" => "fa fa-industry");
   }             
                       if ($this->ci->login_user->is_admin || $access_client) {
                    $master_data_submenu[] = array("name" => "partners","url" => "partners","class" => "fa-handshake-o","classs"=>"sss" );               
}                         
if (get_setting("module_country") == "1" && ($this->ci->login_user->is_admin || $access_country)) {
                            $master_data_submenu[] = array("name" => "countries", "url" => "countries","class"=>"fa fa-globe");
                        }
if (get_setting("module_state") == "1" && ($this->ci->login_user->is_admin || $access_state)) {                        
                             $master_data_submenu[] = array("name" => "states", "url" => "states","class"=>"fa fa-circle-o");
                         }
if (get_setting("module_company") == "1" && ($this->ci->login_user->is_admin || $access_company)) {  
                            $master_data_submenu[] = array("name" => "companys", "url" => "companys","class"=>"fa fa-building-o");
                        }
if (get_setting("module_branch") == "1" && ($this->ci->login_user->is_admin || $access_branch)) {                         
                             $master_data_submenu[] = array("name" => "branches", "url" => "branches","class"=>"fa fa-building");
                         }
if (get_setting("module_department") == "1" && ($this->ci->login_user->is_admin || $access_department)) {  
                            $master_data_submenu[] = array("name" => "department", "url" => "department","class"=>"fa fa-building-o");
                        }
if (get_setting("module_designation") == "1" && ($this->ci->login_user->is_admin || $access_designation)) {  
                            $master_data_submenu[] = array("name" => "designation", "url" => "designation","class"=>"fa fa-user-circle-o");
                        }
if (get_setting("module_master_data") == "1" && ($this->ci->login_user->is_admin || $access_master_data)) {  
                             $master_data_submenu[] = array("name" => "bank_name", "url" => "bank_name","class"=>"fa fa-university");
                         }
                        
                 $sidebar_menu[] = array("name" => "master_data", "class" => "fa-id-card", "submenu" => $master_data_submenu);

                } 
                
            if ((get_setting("module_invoice") == "1" || get_setting("module_expense") == "1" || get_setting("module_cheque_handler") == "1" || get_setting("module_company_bank_statement") == "1" || get_setting("module_income") == "1" || get_setting("module_income") == "1") && ($this->ci->login_user->is_admin || $access_expense || $access_invoice||
                    $access_cheque_handler||$access_company_bank_statement||$access_work_order||$access_purchase_order||$access_loan||$access_income)) {
                    $finance_submenu = array();
                    $finance_url = "";
                    $show_payments_menu = false;
                    $show_expenses_menu = false;


                    if (get_setting("module_invoice") == "1" && ($this->ci->login_user->is_admin || $access_invoice)) {
                        $finance_submenu[] = array("name" => "invoice_payments", "url" => "invoice_payments","class"=>"fa fa-credit-card");
                        $finance_url = "invoice_payments";
                        $show_payments_menu = true;
                    }
                    if (get_setting("module_purchase_order") && ($this->ci->login_user->is_admin ||$access_purchase_order)) {
                    $finance_submenu[] = array("name" => "purchase_order_payments", "url" => "purchase_order_payments", "class" => "fa-credit-card");
                                            $show_payments_menu = true;

                }
if (get_setting("module_work_order") && ($this->ci->login_user->is_admin ||$access_work_order)) {
                    $finance_submenu[] = array("name" => "work_order_payments", "url" => "work_order_payments", "class" => "fa-credit-card");
                                            $show_payments_menu = true;

                }
                    if (get_setting("module_income") == "1" && ($this->ci->login_user->is_admin || $access_income)) {
                        $finance_submenu[] = array("name" => "income", "url" => "income","class" => "fa fa-info-circle");
                        $finance_url = "income";
                        $show_expenses_menu = true;
                        
                    }
                    if (get_setting("module_expense") == "1" && ($this->ci->login_user->is_admin || $access_expense)) {
                        $finance_submenu[] = array("name" => "expenses", "url" => "expenses","class" => "fa fa-minus-circle");
                        $finance_url = "expenses";
                    
                    
                       
                        $show_expenses_menu = true;
                         
                        //$show_expenses_menu = true;
                    }
                    if (get_setting("module_loan") == "1" && ($this->ci->login_user->is_admin || $access_loan)) {
                        $finance_submenu[] = array("name" => "loan", "url" => "loan","class"=>"fa fa-home");
                        $finance_url = "loan";
                        $show_expenses_menu = true;
                        
                    }
 if (get_setting("module_company_bank_statement") == "1"&& ($this->ci->login_user->is_admin || $access_company_bank_statement)){
                    $finance_submenu[] = array("name" => "excel_import", "url" => "excel_import","class"=>"fa fa-university");
                        $finance_url = "excel_import";
                            }

                    if (get_setting("module_cheque_handler") == "1"&& ($this->ci->login_user->is_admin || $access_cheque_handler)){

                         $finance_submenu[] = array("name" => "cheque_handler", "url" => "cheque_handler","class"=>"fa fa-check-square");
                          $finance_url = "cheque_handler";
                        }

                    if ($show_expenses_menu && $show_payments_menu) {
                        $finance_submenu[] = array("name" => "income_vs_expenses", "url" => "expenses/income_vs_expenses","class"=>"fa fa-line-chart");
                    }

                    $sidebar_menu[] = array("name" => "finance", "url" => $finance_url, "class" => "fa-money", "submenu" => $finance_submenu);
                }         
//Hr module 
                if ((get_setting("module_leave") == "1" || get_setting("module_attendance") == "1" || get_setting("module_outsource_members") == "1"||get_setting("module_payslip") == "1" ||(get_array_value($this->ci->login_user->permissions, "hide_team_members_list") != "1"&& $this->ci->login_user->user_type == "staff") ) && ($this->ci->login_user->is_admin)) {
                    $hrm_submenu = array();
                    $hrm_url = "";
                    
                     if (get_setting("module_attendance") == "1" && ($this->ci->login_user->is_admin || $access_timecard)) {
                    $hrm_submenu[] = array("name" => "attendance", "url" => "attendance", "class" => "fa-clock-o font-16");
                    $hrm_url = "attendance";
                }
                if (get_array_value($this->ci->login_user->permissions, "hide_team_members_list") != "1"&& $this->ci->login_user->user_type == "staff") {
                    $hrm_submenu[] = array("name" => "team_members", "url" => "team_members", "class" => "fa-user font-16");
                    $hrm_url = "team_members";
                }

                    if (get_setting("module_leave") == "1" && ($this->ci->login_user->is_admin || $access_leave)) {
                    $hrm_submenu[] = array("name" => "leaves", "url" => "leaves", "class" => "fa-sign-out font-16", "devider" => true);
                    $hrm_url = "leaves";

                }
                   
                 if (get_setting("module_attendance") == "1" && ($this->ci->login_user->is_admin || $access_timecard)) {
                    $hrm_submenu[] = array("name" => "a_ot_handler", "url" => "attendance/ot_handler", "class" => "fa-clock-o font-16");
                    $hrm_url = "attendance";
                }
                            if ($manage_timesheets && get_setting("module_project_timesheet")) {

                     $hrm_submenu[] = array("name" => "t_ot_handler", "url" => "t_ot_handler", "class" => "fa-clock-o font-16");
                }

                if (get_setting("module_payslip") == "1" && ($this->ci->login_user->is_admin || $access_payslip)) {
                        $hrm_submenu[] = array("name" => "payslip", "url" => "payslip","class"=>"fa fa-money");
                        $hrm_url = "payslip";
                    }
                if (get_setting("module_announcement") == "1") {
                    $hrm_submenu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
                }

                if ($this->ci->login_user->is_admin) {
                    $hrm_submenu[] = array("name" => "settings", "url" => "settings/general", "class" => "fa-wrench");
                }
                
                    
                    
 

                    $sidebar_menu[] = array("name" => "hrm", "url" => $hrm_url, "class" => "fa fa-black-tie", "submenu" => $hrm_submenu);
                }



                $project_submenu = array(
                    array("name" => "all_projects", "url" => "projects/all_projects","class" => "fa fa-th"),
                    array("name" => "tasks", "url" => "projects/all_tasks","class" => "fa fa-tasks"));

                if ($manage_timesheets && get_setting("module_project_timesheet")) {
                    $project_submenu[] = array("name" => "timesheets", "url" => "projects/all_timesheets","class" => "fa fa-clock-o");
                   
                }
                /*if ($manage_timesheets && get_setting("module_project_timesheet")&&!$this->login_user->is_admin) {
                    $project_submenu[] = array("name" => "ot_handler", "url" => "projects/ot_handler", "class" => "fa-clock-o font-16");
                }*/

                $sidebar_menu[] = array("name" => "projects", "url" => "projects", "class" => "fa-th-large",
                    "submenu" => $project_submenu
                );
                if (get_setting("module_production_data") == "1" && ($this->ci->login_user->is_admin || $access_production_data||$access_outsource_jobs||$access_purchase_order)){
                $project_submenu = array();

                        $project_submenu[] = array("name" => "product_categories", "url" => "product_categories","class" =>"fa fa-bars");

                             $project_submenu[] =  array("name" => "manufacturer", "url" => "manufacturer","class" =>"fa fa-industry");
                          $project_submenu[] =  array("name" => "part_no_generation", "url" => "part_no_generation","class" =>"fa fa-list");
                           
                          $project_submenu[] =  array("name" => "product_id_generation", "url" => "product_id_generation","class" =>"fa fa-th-list");
                        if ($this->ci->login_user->is_admin ||$access_outsource_jobs ) {                
                 $project_submenu[] =  array("name" => "outsource_jobs", "url" => "outsource_jobs", "class" => "fa-user-plus");
                }
                    
            $sidebar_menu[] = array("name" => "production", "class" => "fa-product-hunt ", "submenu" => $project_submenu);

                }  

                if (get_setting("module_production_data") == "1" && ($this->ci->login_user->is_admin || $access_production_data)){
                $services_submenu = array();

                        $services_submenu[] =  array("name" => "service_categories", "url" => "service_categories","class" =>"fa fa-bars");
                        $services_submenu[] =  array("name" => "job_id_generation", "url" => "job_id_generation","class" =>"fa fa-list");
                           
                          $services_submenu[] =  array("name" => "service_id_generation", "url" => "service_id_generation","class" =>"fa fa-th-list");
                        
                    
            $sidebar_menu[] = array("name" => "services", "class" => "fa fa-cogs", "submenu" => $services_submenu);

                }
                if  (($this->ci->login_user->is_admin || $access_delivery||$access_items||$access_inventory) && (get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" || get_setting("module_delivery") == "1" ) ) {

                                   $stock_submenu = array();
                if((get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" ) && ($this->ci->login_user->is_admin ||$access_items||$access_inventory)){
                    $stock_submenu[] =array("name" => "items", "url" => "items", "class" => "fa-list-ul");                           
                }
                                if(get_setting("module_delivery") == "1" &&($this->ci->login_user->is_admin ||$access_delivery)){

                   $stock_submenu[] = array("name" => "delivery", "url" => "delivery", "class" => "fa-clone");
                       }
               $sidebar_menu[] = array("name" => "stock_management", "class" => "fa-file ", "submenu" => $stock_submenu);

                }
               
  if (get_setting("module_estimate") && get_setting("module_estimate_request") && ($this->ci->login_user->is_admin || $access_estimate)) {

                    $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file",
                        "submenu" => array(
                            array("name" => "estimate_list", "url" => "estimates","class" => "fa-list-ol"),
                            array("name" => "estimate_requests", "url" => "estimate_requests","class" => "fa fa-paper-plane-o"),
                            array("name" => "estimate_forms", "url" => "estimate_requests/estimate_forms","class" => "fa fa-list-alt")
                        )
                    );
                } else if (get_setting("module_estimate") && ($this->ci->login_user->is_admin || $access_estimate)) {
                    $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file");
                }
  


               /* if (get_setting("module_purchase_order") && ($this->login_user->is_admin || $access_purchase_order)) {
                    $sidebar_menu[] = array("name" => "purchase_orders", "url" => "purchase_orders", "class" => "fa-file");
                } */
                if (get_setting("module_purchase_order") && get_setting("module_purchase_order") && ($this->ci->login_user->is_admin || $access_purchase_order)) {

                    $sidebar_menu[] = array("name" => "purchase_orders", "url" => "purchase_orders", "class" => "fa-file",
                        "submenu" => array(
                            array("name" => "purchase_order_list", "url" => "purchase_orders","class" => "fa-list-ol"),
                           
                            // array("name" => "purchase_order_payments", "url" => "purchase_order_payments","class" => "fa-credit-card"),
                             array("name" => "vendors_invoice_list", "url" => "vendors_invoice_list","class" => "fa-file-text"),
                             array("name" => "clients_po_list", "url" => "clients_po_list","class" => "fa-file-text"),
                              array("name" => "clients_wo_list", "url" => "clients_wo_list","class" => "fa-file-text")
                        )
                    );
                } 

               /* if (get_setting("module_work_order") && ($this->login_user->is_admin || $access_work_order)) {
                    $sidebar_menu[] = array("name" => "work_orders", "url" => "work_orders", "class" => "fa-file");
                } */
                if (get_setting("module_work_order") && get_setting("module_work_order") && ($this->ci->login_user->is_admin || $access_work_order)) {

                    $sidebar_menu[] = array("name" => "work_orders", "url" => "work_orders", "class" => "fa-file",
                        "submenu" => array(
                            array("name" => "work_order_list", "url" => "work_orders","class" => "fa-list-ol"),
                           
                            // array("name" => "work_order_payments", "url" => "work_order_payments","class" => "fa-credit-card")
                        )
                    );
                }               
                 if (get_setting("module_invoice") == "1" && ($this->ci->login_user->is_admin || $access_invoice)) {
                    $sidebar_menu[] = array("name" => "invoices", "url" => "invoices", "class" => "fa-file-text");
                }            // if (get_setting("module_delivery") == "1" && ($access_delivery || !$this->login_user->is_admin)) {
                    
             //        $sidebar_menu[] = array("name" => "delivery", "url" => "delivery", "class" => "fa-clone");
             //    }
/* if (get_setting("module_assets_data") == "1" && ($access_assets_data || $this->login_user->is_admin) ){

                    $sidebar_menu[] = array("name" => "assets",  "class" => "fa-adn",
                        "submenu" => array(
                            array("name" => "tools", "url" => "tools"),
                          array("name" => "credentials", "url" => "credentials")
                        )
                    );
                }  */


                if ((get_setting("module_assets_data") == "1" ) && ($this->ci->login_user->is_admin || $access_assets_data|| 
                    $access_tools)) {
                    $assets_submenu = array();
                    $assets_url = "";
                   // $show_payments_menu = false;
                    //$show_expenses_menu = false;

                   if (get_setting("module_assets_data") == "1" && ($this->ci->login_user->is_admin || $access_tools)) {
                        $assets_submenu[] = array("name" => "tools", "url" => "tools","class"=>"fa fa-wrench");
                        $assets_url = "tools";
                        }
                    if (get_setting("module_assets_data") == "1" && ($this->ci->login_user->is_admin || $access_assets_data)) {
                        $assets_submenu[] = array("name" => "credentials", "url" => "credentials","class"=>"fa fa-key");
                        $assets_url = "credentials";
                       // $show_payments_menu = true;
                    }


                    $sidebar_menu[] = array("name" => "assets", "url" => $assets_url, "class" => "fa-adn", "submenu" => $assets_submenu);
                }
                


                
/*if (get_setting("module_payslip") == "1" && ($this->login_user->is_admin || $access_payslip)) {
                        $sidebar_menu[] = array("name" => "payslip", "url" => "payslip","class"=>"fa fa-money");
                    }else if (get_setting("module_payslip") == "1"&& $this->login_user->user_type !== "resource") {
                    $sidebar_menu[] = array("name" => "payslip", "url" => "payslip/payslip_info", "class" => "fa fa-money");
                } */

                if (get_setting("module_payslip") == "1" && ($access_payslip && !$this->ci->login_user->is_admin)) {
                        $sidebar_menu[] = array("name" => "payslip", "url" => "payslip","class"=>"fa fa-money");
                    }else if (get_setting("module_payslip") == "1"&& ($this->ci->login_user->user_type !== "resource" && !$this->ci->login_user->is_admin)) {
                    $sidebar_menu[] = array("name" => "payslip", "url" => "payslip/payslip_info", "class" => "fa fa-money");
                }

                

               

                
/*if (get_array_value($this->login_user->permissions, "hide_team_members_list") != "1"&& $this->login_user->user_type == "staff") {
                    $sidebar_menu[] = array("name" => "team_members", "url" => "team_members", "class" => "fa-user font-16");
                }*/

                if (get_array_value($this->ci->login_user->permissions, "hide_team_members_list") != "1"&& $this->ci->login_user->user_type == "staff"&& !$this->ci->login_user->is_admin) {
                    $sidebar_menu[] = array("name" => "team_members", "url" => "team_members", "class" => "fa-user font-16");
                }

                

 /*if (get_setting("module_outsource_members") == "1"&& ($access_outsource_members||$this->login_user->is_admin||$this->login_user->user_type == "resource")&& (get_array_value($this->login_user->permissions, "hide_team_members_list") != "1")){
                    $sidebar_menu[] = array("name" => "rm_members", "url" => "rm_members", "class" => "fa-user-o");
                } */

                if ((get_setting("module_outsource_members") == "1")&& (
    $access_outsource_members ||$this->ci->login_user->user_type == "resource")&& (get_array_value($this->ci->login_user->permissions, "hide_team_members_list") != "1")){
                    $sidebar_menu[] = array("name" => "rm_members", "url" => "rm_members", "class" => "fa-user-o");
                } 


                if ($this->ci->login_user->is_admin || $access_register  || $access_student_desk) {
                    $reg_submenu = array();
                    $reg_url = "";
                   // $show_payments_menu = false;
                    //$show_expenses_menu = false;

                if (get_array_value($this->ci->login_user->permissions, "hide_team_members_list") != "1"&& $this->ci->login_user->user_type == "staff"&&$this->ci->login_user->is_admin) {
                    $reg_submenu[] = array("name" => "team_members", "url" => "team_members", "class" => "fa-user font-16");
                    $reg_url = "team_members";
                }

                if (get_setting("module_outsource_members") == "1"&& $this->ci->login_user->is_admin){
                    $reg_submenu[] = array("name" => "rm_members", "url" => "rm_members", "class" => "fa-user-o");
                    $reg_url = "rm_members";
                } 
                    if (get_setting("module_student_desk") == "1" && ($this->ci->login_user->is_admin || $access_student_desk)) {
                    $reg_submenu[] = array("name" => "student_desk", "url" => "student_desk", "class" => "fa fa-address-card-o");
               }    
               if($this->ci->login_user->is_admin || $access_register){


                    $reg_submenu[] = array("name" => "clients_register", "url" => "clients_register", "class" => "fa-briefcase clientssss");
                    $reg_submenu[] = array("name" => "vendors_register", "url" => "vendors_register", "class" => "fa-handshake-o");
                    $reg_submenu[] = array("name" => "partners_register", "url" => "partners_register", "class" => "fa fa fa-industry ssss");
 }

                    $sidebar_menu[] = array("name" => "registration", "url" => $reg_url, "class" => "fa-adn", "submenu" => $reg_submenu);
                }
                

           

                /*if (get_setting("module_attendance") == "1" && ($this->login_user->is_admin || $access_timecard)) {
                    $sidebar_menu[] = array("name" => "attendance", "url" => "attendance", "class" => "fa-clock-o font-16");
                } else if (get_setting("module_attendance") == "1") {
                    $sidebar_menu[] = array("name" => "attendance", "url" => "attendance/attendance_info", "class" => "fa-clock-o font-16");
                }
*/
if (get_setting("module_attendance") == "1" && ($access_timecard && !$this->ci->login_user->is_admin)) {
    $sidebar_menu[] = array("name" => "attendance", "url" => "attendance", "class" => "fa-clock-o font-16");
} else if (get_setting("module_attendance") == "1" && !$this->ci->login_user->is_admin) {
    $sidebar_menu[] = array("name" => "attendance", "url" => "attendance/attendance_info", "class" => "fa-clock-o font-16");
}


/*if (get_setting("module_leave") == "1" && ($this->login_user->is_admin || $access_leave)) {
    $sidebar_menu[] = array("name" => "leaves", "url" => "leaves", "class" => "fa-sign-out font-16", "devider" => true);
} else if (get_setting("module_leave") == "1") {
    $sidebar_menu[] = array("name" => "leaves", "url" => "leaves/leave_info", "class" => "fa-sign-out font-16", "devider" => true);
}*/
if (get_setting("module_attendance") == "1" && ($access_timecard && !$this->ci->login_user->is_admin)) {
    $sidebar_menu[] = array("name" => "attendance", "url" => "attendance", "class" => "fa-clock-o font-16");
} else if (get_setting("module_attendance") == "1" && !$this->ci->login_user->is_admin) {
    $sidebar_menu[] = array("name" => "attendance", "url" => "attendance/attendance_info", "class" => "fa-clock-o font-16");
}


/*if (get_setting("module_leave") == "1" && ($this->login_user->is_admin || $access_leave)) {
    $sidebar_menu[] = array("name" => "leaves", "url" => "leaves", "class" => "fa-sign-out font-16", "devider" => true);
} else if (get_setting("module_leave") == "1") {
    $sidebar_menu[] = array("name" => "leaves", "url" => "leaves/leave_info", "class" => "fa-sign-out font-16", "devider" => true);
}*/

if (get_setting("module_leave") == "1" && ($access_leave && !$this->ci->login_user->is_admin)) {
    $sidebar_menu[] = array("name" => "leaves", "url" => "leaves", "class" => "fa-sign-out font-16", "devider" => true);
} else if (get_setting("module_leave") == "1" && !$this->ci->login_user->is_admin) {
    $sidebar_menu[] = array("name" => "leaves", "url" => "leaves/leave_info", "class" => "fa-sign-out font-16", "devider" => true);
}

if (isset($hidden_menu) && get_setting("module_announcement") == "1" && !in_array("announcements", $hidden_menu) && !$this->ci->login_user->is_admin) {
    $sidebar_menu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
}

$module_help = get_setting("module_help") == "1" ? true : false;
$module_knowledge_base = get_setting("module_knowledge_base") == "1" ? true : false;

            /*    //prepere the help and suppor menues
                if ($module_help || $module_knowledge_base) {

                    $help_knowledge_base_menues = array();
                    $main_url = "help";

                    if ($module_help) {
                        $help_knowledge_base_menues[] = array("name" => "help", "url" => $main_url);
                    }

                    //push the help manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_help) {
                        $help_knowledge_base_menues[] = array("name" => "articles", "url" => "help/help_articles");
                        $help_knowledge_base_menues[] = array("name" => "categories", "url" => "help/help_categories");
                    }

                    if ($module_knowledge_base) {
                        $help_knowledge_base_menues[] = array("name" => "knowledge_base", "url" => "knowledge_base");
                    }

                    //push the knowledge_base manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_knowledge_base) {
                        $help_knowledge_base_menues[] = array("name" => "articles", "category" => "help", "url" => "help/knowledge_base_articles");
                        $help_knowledge_base_menues[] = array("name" => "categories", "category" => "help", "url" => "help/knowledge_base_categories");
                    }


                    if (!$module_help) {
                        $main_url = "knowledge_base";
                    }

                    $sidebar_menu[] = array("name" => "help_and_support", "url" => $main_url, "class" => "fa-question-circle",
                        "submenu" => $help_knowledge_base_menues
                    );
                } */
                if (get_setting("module_ticket") == "1" && ($this->ci->login_user->is_admin || $access_ticket)) {

                    $ticket_badge = 0;
                    if ($this->ci->login_user->is_admin || $access_ticket === "all") {
                        $ticket_badge = count_new_tickets();
                    } else if ($access_ticket === "specific") {
                        $specific_ticket_permission = get_array_value($permissions, "ticket_specific");
                        $ticket_badge = count_new_tickets($specific_ticket_permission);
                    }

                    // 

                    $sidebar_menu[] = array("name" => "tickets", "url" => "tickets", "class" => "fa-life-ring", "devider" => true, "badge" => $ticket_badge, "badge_class" => "badge-secondary");
                }
//prepere the help and suppor menues
                               if ($module_knowledge_base) {

                    $knowledge_base_menues = array();
                    $mains_url = "knowledge_base";

                    
    
                if ($module_help) {

                    $help_knowledge_base_menues = array();
                    $main_url = "help";

                    if ($module_help) {
                        $help_knowledge_base_menues[] = array("name" => "help", "url" => $main_url,"class"=>"fa fa-info");
                    }

                    //push the help manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_help) {
                        $help_knowledge_base_menues[] = array("name" => "articles", "url" => "help/help_articles","class"=>"fa fa-newspaper-o");
                        $help_knowledge_base_menues[] = array("name" => "categories", "url" => "help/help_categories","class"=>"fa fa-search-plus");
                    }

                  /*  if ($module_knowledge_base) {
                        $help_knowledge_base_menues[] = array("name" => "knowledge_base", "url" => "knowledge_base");
                    }

                    //push the knowledge_base manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_knowledge_base) {
                        $help_knowledge_base_menues[] = array("name" => "articles", "category" => "help", "url" => "help/knowledge_base_articles");
                        $help_knowledge_base_menues[] = array("name" => "categories", "category" => "help", "url" => "help/knowledge_base_categories");
                    }


                    if (!$module_help) {
                        $main_url = "knowledge_base";
                    } */

                    $sidebar_menu[] = array("name" => "help", "url" => $main_url, "class" => "fa fa-info-circle sssss",
                        "submenu" => $help_knowledge_base_menues,"classs"=>"helps"
                    );
                } 
if ($module_knowledge_base) {
                        $knowledge_base_menues[] = array("name" => "knowledge_base", "url" => "knowledge_base","class"=>"fa fa-question");
                    }

                    //push the knowledge_base manage menu if user has access
                    if ($manage_help_and_knowledge_base && $module_knowledge_base) {
                        $knowledge_base_menues[] = array("name" => "articles", "category" => "knowledge_base", "url" => "help/knowledge_base_articles","class"=>"fa fa-newspaper-o");
                        $knowledge_base_menues[] = array("name" => "categories", "category" => "knowledge_base", "url" => "help/knowledge_base_categories","class"=>"fa fa-search-plus");
                    }


                     

                    $sidebar_menu[] = array("name" => "knowledge_base", "url" => $mains_url, "class" => "fa-question-circle sssss","classs"=>"supports",
                        "submenu" => $knowledge_base_menues
                    );
                }  




                //prepere the help and suppor menues


 //if ($this->login_user->is_admin) {
 //                   $sidebar_menu[] = array("name" => "excel_import", "url" => "excel_import", "class" => "fa fa-upload");
   //             }



         // end side   
        } else if($this->ci->login_user->user_type == "client"||$type == "client_default"){
            //client menu
                //get the array of hidden menu
                $hidden_menu = explode(",", get_setting("hidden_client_menus"));

                $sidebar_menu[] = $dashboard_menu;

                if (get_setting("module_event") == "1" && !in_array("events", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
                }
                
                //check message access settings for clients
                if (get_setting("module_message") && get_setting("client_message_users")) {
                    $sidebar_menu[] = array("name" => "messages", "url" => "messages", "class" => "fa-envelope", "badge" => count_unread_message());
                }

                if (!in_array("projects", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "projects", "url" => "projects/all_projects", "class" => "fa fa-th-large");
                }


                if (get_setting("module_estimate") && !in_array("estimates", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "fa-file");
                }

                if (get_setting("module_invoice") == "1") {
                    if (!in_array("invoices", $hidden_menu)) {
                        $sidebar_menu[] = array("name" => "invoices", "url" => "invoices", "class" => "fa-file-text");
                    }
                    if (!in_array("payments", $hidden_menu)) {
                        $sidebar_menu[] = array("name" => "invoice_payments", "url" => "invoice_payments", "class" => "fa-money");
                    }
                }

                if (get_setting("module_ticket") == "1" && !in_array("tickets", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "tickets", "url" => "tickets", "class" => "fa-life-ring");
                }

                if (get_setting("module_announcement") == "1" && !in_array("announcements", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
                }

                $sidebar_menu[] = array("name" => "users", "url" => "clients/users", "class" => "fa-user");
                $sidebar_menu[] = array("name" => "my_profile", "url" => "clients/contact_profile/" . $this->ci->login_user->id, "class" => "fa-cog");

                if (get_setting("module_knowledge_base") == "1" && !in_array("knowledge_base", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "fa-question-circle");
                }
        }else if($this->ci->login_user->user_type == "vendor"||$type == "vendor_default"){
                  $hidden_menu = explode(",", get_setting("hidden_vendor_menus"));

                $sidebar_menu[] = $dashboard_menu;

               if (get_setting("module_event") == "1" && !in_array("events", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
                }
                
                
                if (get_setting("module_purchase_order") && !in_array("purchase_orders", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "purchase_orders", "url" => "purchase_orders", "class" => "fa-file");
                }


                
                

                if (get_setting("module_work_order") && !in_array("work_orders", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "work_orders", "url" => "work_orders", "class" => "fa-file");
                }
                

                if (get_setting("module_announcement") == "1" && !in_array("announcements", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
                }

                $sidebar_menu[] = array("name" => "users", "url" => "vendors/users", "class" => "fa-user");
                $sidebar_menu[] = array("name" => "my_profile", "url" => "vendors/contact_profile/" . $this->ci->login_user->id, "class" => "fa-cog");

                if (get_setting("module_knowledge_base") == "1" && !in_array("knowledge_base", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "fa-question-circle");
                }
            }
        

        return $sidebar_menu;
    }

}
