<?php
$settings_menu = array(
    "app_settings" => array(
        array("name" => "general", "url" => "settings/general"),
        array("name" => "email", "url" => "settings/email"),
        array("name" => "email_templates", "url" => "email_templates"),
        array("name" => "terms_conditions_templates", "url" => "terms_conditions_templates"),
        array("name" => "modules", "url" => "settings/modules"),
        array("name" => "cron_job", "url" => "settings/cron_job"),
        array("name" => "notifications", "url" => "settings/notifications"),
        array("name" => "integration", "url" => "settings/integration"),
        array("name" => "updates", "url" => "updates"),
        
    ),
    /*"company_setup" => array(
        array("name" => "branch", "url" => "branches"),
        array("name" => "department", "url" => "department"),
        array("name" => "designation", "url" => "designation"),
         array("name" => "countries", "url" => "countries")
    ), */
   /* "product_master_data" => array(
        array("name" => "part_no_generation", "url" => "part_no_generation"),
        array("name" => "product_id_generation", "url" => "product_id_generation")
        
    ), */
   /* "assets" => array(
        array("name" => "tools", "url" => "tools")
        
    ),*/
    "access_permission" => array(
        array("name" => "roles", "url" => "roles"),
        array("name" => "team", "url" => "team"),
    ),
    "client" => array(
        array("name" => "client_permissions", "url" => "settings/client_permissions"),
        array("name" => "client_groups", "url" => "client_groups"),
         array("name" => "client_left_menu", "url" => "left_menus/index/client_default"),
        
    ),
    "partner" => array(
        
        array("name" => "partner_groups", "url" => "partner_groups")
    ),
    "vendor" => array(
        array("name" => "vendor_permissions", "url" => "settings/vendor_permissions"),
        array("name" => "vendor_groups", "url" => "vendor_groups"),
        array("name" => "vendor_left_menu", "url" => "left_menus/index/vendor_default"),
        
    ),
    "company" => array(
        array("name" => "company_permissions", "url" => "settings/company_permissions"),
        array("name" => "company_groups", "url" => "company_groups")
        
    ),
    "student_desk_setup" => array(
       
        array("name" => "vap_category", "url" => "vap_category"),
        array("name" => "student_desk_permissions", "url" => "settings/student_desk_permissions")

        
    ),
    "gst_setup" => array(
        array("name" => "hsn_sac_code", "url" => "hsn_sac_code"),
        array("name" => "gst_state_code", "url" => "gst_state_code"),
        
    ),

    "pricing_setup" => array(
        array("name" => "buyer_types", "url" => "buyer_types"),
        
        
    ),

    "payslip_setup" => array(
        
),
   
    "setup" => array(
        array("name" => "custom_fields", "url" => "custom_fields"),
        array("name" => "tasks", "url" => "task_status"),
        array("name" => "vendors_invoice_status", "url" => "vendors_invoice_status"),
        array("name" => "unit_type", "url" => "unit_type"),
    )
);

//restricted settings
if (get_setting("module_attendance") == "1") {
    $settings_menu["access_permission"][] = array("name" => "ip_restriction", "url" => "settings/ip_restriction");
}

if (get_setting("module_leave") == "1") {
    $settings_menu["setup"][] = array("name" => "leave_types", "url" => "leave_types");
}

if (get_setting("module_ticket") == "1") {
    $settings_menu["setup"][] = array("name" => "tickets", "url" => "ticket_types");
}

if (get_setting("module_expense") == "1") {
    $settings_menu["setup"][] = array("name" => "expense_categories", "url" => "expense_categories");
}
//$settings_menu["setup"][] = array("name" => "product_categories", "url" => "product_categories");
 $settings_menu["setup"][] = array("name" => "cheque_categories", "url" => "cheque_categories");
 $settings_menu["setup"][] = array("name" => "cheque_status", "url" => "cheque_status");
if (get_setting("module_voucher") == "1") {
    $settings_menu["setup"][] = array("name" => "voucher_types", "url" => "voucher_types");
}
if (get_setting("module_delivery") == "1") {
    $settings_menu["setup"][] = array("name" => "dc_types", "url" => "dc_types");
}
if (get_setting("module_invoice") == "1") {
    $settings_menu["setup"][] = array("name" => "invoices", "url" => "settings/invoices");
}
if (get_setting("module_purchase_order") == "1") {
    $settings_menu["setup"][] = array("name" => "purchase_orders", "url" => "settings/purchase_orders");
}

if (get_setting("module_work_order") == "1") {
    $settings_menu["setup"][] = array("name" => "work_orders", "url" => "settings/work_orders");
}
//$settings_menu["setup"][] = array("name" => "tools", "url" => "tools");
$settings_menu["setup"][] = array("name" => "payment_methods", "url" => "payment_methods");
$settings_menu["setup"][] = array("name" => "payment_status", "url" => "payment_status");
$settings_menu["setup"][] = array("name" => "company", "url" => "settings/company");
//$settings_menu["setup"][] = array("name" => "taxes", "url" => "taxes");
//$settings_menu["setup"][] = array("name" => "hsn_sac_code", "url" => "hsn_sac_code");
//$settings_menu["setup"][] = array("name" => "gst_state_code", "url" => "gst_state_code");
$settings_menu["payslip_setup"][] = array("name" => "earnings", "url" => "earnings");
$settings_menu["payslip_setup"][] = array("name" => "deductions", "url" => "deductions");
//$settings_menu["setup"][] = array("name" => "bank_name", "url" => "bank_name");
$settings_menu["setup"][] = array("name" => "mode_of_dispatch", "url" => "mode_of_dispatch");
$settings_menu["gst_setup"][] = array("name" => "lut_number", "url" => "lut_number");
if (get_setting("module_payslip") == "1") {
    $settings_menu["payslip_setup"][] = array("name" => "payslip", "url" => "settings/payslip");
}
if (get_setting("module_delivery") == "1") {
    $settings_menu["setup"][] = array("name" => "delivery", "url" => "settings/delivery");
}
if (get_setting("module_voucher") == "1") {
    $settings_menu["setup"][] = array("name" => "voucher", "url" => "settings/voucher");
}
$settings_menu["setup"][] = array("name" => "left_menu", "url" => "left_menus");

?>

<ul class="nav nav-tabs vertical settings" role="tablist">
    <?php
    foreach ($settings_menu as $key => $value) {

        //collapse the selected settings tab panel
        $collapse_in = "";
        $collapsed_class = "collapsed";
        if (in_array($active_tab, array_column($value, "name"))) {
            $collapse_in = "in";
            $collapsed_class = "";
        }
        ?>

        <div class="clearfix settings-anchor <?php echo $collapsed_class; ?>" data-toggle="collapse" data-target="#settings-tab-<?php echo $key; ?>">
            <?php echo lang($key); ?>
            <span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
        </div>

        <?php
        echo "<div id='settings-tab-$key' class='collapse $collapse_in'>";
        echo "<ul class='list-group help-catagory'>";

        foreach ($value as $sub_setting) {
            $active_class = "";
            $setting_name = get_array_value($sub_setting, "name");
            $setting_url = get_array_value($sub_setting, "url");

            if ($active_tab == $setting_name) {
                $active_class = "active";
            }

            echo "<a href='" . get_uri($setting_url) . "' class='list-group-item $active_class'>" . lang($setting_name) . "</a>";
        }

        echo "</ul>";
        echo "</div>";
    }
    ?>
</ul>