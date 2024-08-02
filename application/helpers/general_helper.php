<?php

/**
 * use this to print link location
 *
 * @param string $uri
 * @return print url
 */
if (!function_exists('echo_uri')) {

    function echo_uri($uri = "") {
        echo get_uri($uri);
    }

}

/**
 * prepare uri
 * 
 * @param string $uri
 * @return full url 
 */
if (!function_exists('get_uri')) {

    function get_uri($uri = "") {
        $ci = get_instance();
        $index_page = $ci->config->item('index_page');
        return base_url($index_page . '/' . $uri);
    }

}

/**
 * use this to print file path
 * 
 * @param string $uri
 * @return full url of the given file path
 */
if (!function_exists('get_file_uri')) {

    function get_file_uri($uri = "") {
        return base_url($uri);
    }

}

/**
 * get the url of user avatar
 * 
 * @param string $image_name
 * @return url of the avatar of given image reference
 */
if (!function_exists('get_avatar')) {

    function get_avatar($image_name = "") {
        if ($image_name === "system_bot") {
            return base_url("assets/images/avatar-bot.jpg");
        } else if ($image_name) {
            return base_url(get_setting("profile_image_path")) . "/" . $image_name;
        } else {
            return base_url("assets/images/avatar.jpg");
        }
    }

}
if (!function_exists('get_flag')) {
    function get_flag($image_name = "") {
        return base_url("assets/images/flags") . "/" . $image_name . ".svg";
    }
}
/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('load_css')) {

    function load_css(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url($uri) . "?v=$version' />";
        }
    }

}


/**
 * link the javascript files 
 * 
 * @param array $array
 * @return print js links
 */
if (!function_exists('load_js')) {

    function load_js(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<script type='text/javascript'  src='" . base_url($uri) . "?v=$version'></script>";
        }
    }

}

/**
 * check the array key and return the value 
 * 
 * @param array $array
 * @return extract array value safely
 */
if (!function_exists('get_array_value')) {

    function get_array_value(array $array, $key) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
    }

}

/**
 * prepare a anchor tag for any js request
 * 
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('js_anchor')) {

    function js_anchor($title = '', $attributes = '') {
        $title = (string) $title;
        $html_attributes = "";

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        return '<a href="#"' . $html_attributes . '>' . $title . '</a>';
    }

}


/**
 * prepare a anchor tag for modal 
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('modal_anchor')) {

    function modal_anchor($url, $title = '', $attributes = '') {
        $attributes["data-act"] = "ajax-modal";
        if (get_array_value($attributes, "data-modal-title")) {
            $attributes["data-title"] = get_array_value($attributes, "data-modal-title");
        } else {
            $attributes["data-title"] = get_array_value($attributes, "title");
        }
        $attributes["data-action-url"] = $url;

        return js_anchor($title, $attributes);
    }

}

/**
 * prepare a anchor tag for ajax request
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('ajax_anchor')) {

    function ajax_anchor($url, $title = '', $attributes = '') {
        $attributes["data-act"] = "ajax-request";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }

}

/**
 * get the selected menu 
 * 
 * @param string $url
 * @param array $submenu
 * @return string "active" indecating the active page
 */
/*if (!function_exists('active_menu')) {

    function active_menu($menu = "", $submenu = array()) {
        $ci = & get_instance();
        $controller_name = strtolower(get_class($ci));

        //compare with controller name. if not found, check in submenu values
        if ($menu === $controller_name) {
            return "active";
        } else if ($submenu && count($submenu)) {
            foreach ($submenu as $sub_menu) {
                if (get_array_value($sub_menu, "name") === $controller_name) {
                    return "active";
                } else if (get_array_value($sub_menu, "category") === $controller_name) {
                    return "active";
                }
            }
        }
    }

}*/

/**
 * get the selected menu 
 * 
 * @param array $sidebar_menu
 * @return the array containing an active class key
 */
if (!function_exists('active_menu')) {

    function get_active_menu($sidebar_menu = array()) {
        $ci = & get_instance();
        $controller_name = strtolower(get_class($ci));
        $uri_string = uri_string();
        $current_url = get_uri($uri_string);

        $found_url_active_key = null;
        $found_controller_active_key = null;
        $found_special_active_key = null;

        foreach ($sidebar_menu as $key => $menu) {
            if (isset($menu["name"]) && isset($menu["url"])) {
                $menu_name = $menu["name"];
                $menu_url = $menu["url"];

                //compare with current url
                if ($menu_url === $current_url || get_uri($menu_url) === $current_url) {
                    $found_url_active_key = $key;
                }

                //compare with controller name
                if ($menu_name === $controller_name) {
                    $found_controller_active_key = $key;
                }

                //compare with some special links
                if ($uri_string == "projects/all_tasks_kanban" && $menu_url == "projects/all_tasks") {
                    $found_special_active_key = $key;
                }

                //check in submenu values
                $submenu = get_array_value($menu, "submenu");
                if ($submenu && count($submenu)) {
                    foreach ($submenu as $sub_menu) {
                        if (isset($sub_menu['name'])) {
                            $sub_menu_url = $sub_menu["url"];

                            //compare with current url
                            if ($sub_menu_url === $current_url || get_uri($sub_menu_url) === $current_url) {
                                $found_url_active_key = $key;
                            }

                            //compare with controller name
                            if (get_array_value($sub_menu, "name") === $controller_name) {
                                $found_controller_active_key = $key;
                            } else if (get_array_value($sub_menu, "category") === $controller_name) {
                                $found_controller_active_key = $key;
                            }

                            //compare with some special links
                            if ($uri_string == "projects/all_tasks_kanban" && $sub_menu_url == "projects/all_tasks") {
                                $found_special_active_key = $key;
                            }
                        }
                    }
                }
            }
        }

        if (!is_null($found_url_active_key)) {
            $sidebar_menu[$found_url_active_key]["is_active_menu"] = 1;
        } else if (!is_null($found_special_active_key)) {
            $sidebar_menu[$found_special_active_key]["is_active_menu"] = 1;
        } else if (!is_null($found_controller_active_key)) {
            $sidebar_menu[$found_controller_active_key]["is_active_menu"] = 1;
        }

        return $sidebar_menu;
    }

}

/**
 * get the selected submenu
 * 
 * @param string $submenu
 * @param boolean $is_controller
 * @return string "active" indecating the active sub page
 */
if (!function_exists('active_submenu')) {

    function active_submenu($submenu = "", $is_controller = false) {
        $ci = & get_instance();
        //if submenu is a controller then compare with controller name, otherwise compare with method name
        if ($is_controller && $submenu === strtolower(get_class($ci))) {
            return "active";
        } else if ($submenu === strtolower($ci->router->method)) {
            return "active";
        }
    }

}

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */
if (!function_exists('get_setting')) {

    function get_setting($key = "") {
        $ci = get_instance();
        return $ci->config->item($key);
    }

}



/**
 * check if a string starts with a specified sting
 * 
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('starts_with')) {

    function starts_with($string, $needle) {
        $string = $string;
        return $needle === "" || strrpos($string, $needle, -strlen($string)) !== false;
    }

}

/**
 * check if a string ends with a specified sting
 * 
 * @param string $string
 * @param string $needle
 * @return true/false
 */
if (!function_exists('ends_with')) {

    function ends_with($string, $needle) {
        return $needle === "" || (($temp = strlen($string) - strlen($string)) >= 0 && strpos($string, $needle, $temp) !== false);
    }

}

/**
 * create a encoded id for sequrity pupose 
 * 
 * @param string $id
 * @param string $salt
 * @return endoded value
 */
if (!function_exists('encode_id')) {

    function encode_id($id, $salt) {
        $ci = get_instance();
        $id = $ci->encryption->encrypt($id . $salt);
        $id = str_replace("=", "~", $id);
        $id = str_replace("+", "_", $id);
        $id = str_replace("/", "-", $id);
        return $id;
    }

}


/**
 * decode the id which made by encode_id()
 * 
 * @param string $id
 * @param string $salt
 * @return decoded value
 */
if (!function_exists('decode_id')) {

    function decode_id($id, $salt) {
        $ci = get_instance();
        $id = str_replace("_", "+", $id);
        $id = str_replace("~", "=", $id);
        $id = str_replace("-", "/", $id);
        $id = $ci->encryption->decrypt($id);

        if ($id && strpos($id, $salt) != false) {
            return str_replace($salt, "", $id);
        } else {
            return "";
        }
    }

}

/**
 * decode html data which submited using a encode method of encodeAjaxPostData() function
 * 
 * @param string $html
 * @return htmle
 */
if (!function_exists('decode_ajax_post_data')) {

    function decode_ajax_post_data($html) {
        $html = str_replace("~", "=", $html);
        $html = str_replace("^", "&", $html);
        return $html;
    }

}

/**
 * check if fields has any value or not. and generate a error message for null value
 * 
 * @param array $fields
 * @return throw error for bad value
 */
if (!function_exists('check_required_hidden_fields')) {

    function check_required_hidden_fields($fields = array()) {
        $has_error = false;
        foreach ($fields as $field) {
            if (!$field) {
                $has_error = true;
            }
        }
        if ($has_error) {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')));
            exit();
        }
    }

}

/**
 * convert simple link text to clickable link
 * @param string $text
 * @return html link
 */
if (!function_exists('link_it')) {

    function link_it($text) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s]?[^\s]+)?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
    }

}

/**
 * convert mentions to link or link text
 * @param string $text containing text with mentioned brace
 * @param string $return_type indicates what to return (link or text)
 * @return text with link or link text
 */
if (!function_exists('convert_mentions')) {

    function convert_mentions($text, $convert_links = true) {

        preg_match_all('#\@\[(.*?)\]#', $text, $matches);

        $members = array();

        $mentions = get_array_value($matches, 1);
        if ($mentions && count($mentions)) {
            foreach ($mentions as $mention) {
                $user = explode(":", $mention);
                if ($convert_links) {
                    $user_id = get_array_value($user, 1);
                    $members[] = get_team_member_profile_link($user_id, trim($user[0]));
                } else {
                    $members[] = $user[0];
                }
            }
        }

        if ($convert_links) {
            $text = nl2br(link_it($text));
        } else {
            $text = nl2br($text);
        }

        $text = preg_replace_callback('/\[[^]]+\]/', function ($matches) use (&$members) {
            return array_shift($members);
        }, $text);

        return $text;
    }

}

/**
 * get all the use_ids from comment mentions
 * @param string $text
 * @return array of user_ids
 */
if (!function_exists('get_members_from_mention')) {

    function get_members_from_mention($text) {

        preg_match_all('#\@\[(.*?)\]#', $text, $matchs);

        //find the user ids.
        $user_ids = array();
        $mentions = get_array_value($matchs, 1);

        if ($mentions && count($mentions)) {
            foreach ($mentions as $mention) {
                $user = explode(":", $mention);
                $user_id = get_array_value($user, 1);
                if ($user_id) {
                    array_push($user_ids, $user_id);
                }
            }
        }

        return $user_ids;
    }

}

/**
 * send mail
 * 
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param array $optoins
 * @return true/false
 */
if (!function_exists('send_app_mail')) {

    function send_app_mail($to, $subject, $message, $optoins = array()) {
        $email_config = Array(
            'charset' => 'utf-8',
            'mailtype' => 'html'
        );

        //check mail sending method from settings
        if (get_setting("email_protocol") === "smtp") {
            $email_config["protocol"] = "smtp";
            $email_config["smtp_host"] = get_setting("email_smtp_host");
            $email_config["smtp_port"] = get_setting("email_smtp_port");
            $email_config["smtp_user"] = get_setting("email_smtp_user");
            $email_config["smtp_pass"] = get_setting("email_smtp_pass");
            $email_config["smtp_crypto"] = get_setting("email_smtp_security_type");

            if (!$email_config["smtp_crypto"]) {
                $email_config["smtp_crypto"] = "tls"; //for old clients, we have to set this by defaultsssssssss
            }

            if ($email_config["smtp_crypto"] === "none") {
                $email_config["smtp_crypto"] = "";
            }
        }

        $ci = get_instance();
        $ci->load->library('email', $email_config);
        $ci->email->clear(true); //clear previous message and attachment
        $ci->email->set_newline("\r\n");
        $ci->email->from(get_setting("email_smtp_user"), get_setting("email_sent_from_name"));
        $ci->email->to($to);
        $ci->email->subject($subject);
        $ci->email->message($message);

        //add attachment
        $attachments = get_array_value($optoins, "attachments");
        if (is_array($attachments)) {
            foreach ($attachments as $value) {
                $file_path = get_array_value($value, "file_path");
                $file_name = get_array_value($value, "file_name");
                $ci->email->attach(trim($file_path), "attachment", $file_name);
            }
        }

        //check cc
        $cc = get_array_value($optoins, "cc");
        if ($cc) {
            $ci->email->cc($cc);
        }

        //check bcc
        $bcc = get_array_value($optoins, "bcc");
        if ($bcc) {
            $ci->email->bcc($bcc);
        }

        //send email
        if ($ci->email->send()) {
            return true;
        } else {
            //show error message in none production version
            if (ENVIRONMENT !== 'production') {
                show_error($ci->email->print_debugger());
            }
            return false;
        }
    }

}


/**
 * get users ip address
 * 
 * @return ip
 */
if (!function_exists('get_real_ip')) {

    function get_real_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}

/**
 * check if it's localhost
 * 
 * @return boolean
 */
if (!function_exists('is_localhost')) {

    function is_localhost() {
        $known_localhost_ip = array(
            '127.0.0.1',
            '::1'
        );
        if (in_array(get_real_ip(), $known_localhost_ip)) {
            return true;
        }
    }

}


/**
 * convert string to url
 * 
 * @param string $address
 * @return url
 */
if (!function_exists('to_url')) {

    function to_url($address = "") {
        if (strpos($address, 'http://') === false && strpos($address, 'https://') === false) {
            $address = "http://" . $address;
        }
        return $address;
    }

}

/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_submitted_data')) {

    function validate_submitted_data($fields = array()) {
        $ci = get_instance();
        foreach ($fields as $field_name => $requirement) {
            $ci->form_validation->set_rules($field_name, $field_name, $requirement);
        }

        if ($ci->form_validation->run() == FALSE) {
            if (ENVIRONMENT === 'production') {
                $message = lang('something_went_wrong');
            } else {
                $message = validation_errors();
            }
            echo json_encode(array("success" => false, 'message' => $message));
            exit();
        }
    }

}


/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_numeric_value')) {

    function validate_numeric_value($value=0) {
        if($value && !is_numeric($value)){
            die("Invalid value");
        }
    }

}

/**
 * team members profile anchor. only clickable to team members
 * client's will see a none clickable link
 * 
 * @param string $id
 * @param string $name
 * @param array $attributes
 * @return html link
 */
if (!function_exists('get_team_member_profile_link')) {

    function get_team_member_profile_link($id = 0, $name = "", $attributes = array()) {
        $ci = get_instance();
        if ($ci->login_user->user_type === "staff"||$ci->login_user->user_type === "resource") {
            return anchor("team_members/view/" . $id, $name, $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }

}
if (!function_exists('get_rm_member_profile_link')) {

    function get_rm_member_profile_link($id = 0, $name = "", $attributes = array()) {
        $ci = get_instance();
        if ($ci->login_user->user_type === "staff"||$ci->login_user->user_type === "resource") {
            return anchor("rm_members/view/" . $id, $name, $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }

}
if (!function_exists('get_rm_member_profile_link')) {

    function get_rm_member_profile_link($id = 0, $name = "", $attributes = array()) {
        $ci = get_instance();
        if ($ci->login_user->user_type === "resource"||$ci->login_user->user_type === "staff") {
            return anchor("rm_members/view/" . $id, $name, $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }

}


/**
 * team members profile anchor. only clickable to team members
 * client's will see a none clickable link
 * 
 * @param string $id
 * @param string $name
 * @param array $attributes
 * @return html link
 */
if (!function_exists('get_client_contact_profile_link')) {

    function get_client_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("clients/contact_profile/" . $id, $name, $attributes);
    }

}

if (!function_exists('get_vendor_contact_profile_link')) {

    function get_vendor_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("vendors/contact_profile/" . $id, $name, $attributes);
    }

}
if (!function_exists('get_clientr_contact_profile_link')) {

    function get_clientr_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("clients_register/contact_profile/" . $id, $name, $attributes);
    }

}

if (!function_exists('get_vendorr_contact_profile_link')) {

    function get_vendorr_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("vendors_register/contact_profile/" . $id, $name, $attributes);
    }

}

if (!function_exists('get_company_contact_profile_link')) {

    function get_company_contact_profile_link($id = 0, $name = "", $attributes = array()) {
        return anchor("companys/contact_profile/" . $id, $name, $attributes);
    }

}

/**
 * return a colorful label accroding to invoice status
 * 
 * @param Object $invoice_info
 * @return html
 */
if (!function_exists('get_invoice_status_label')) {

    function get_invoice_status_label($invoice_info, $return_html = true) {
        $invoice_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $invoice_info->invoice_value = floor($invoice_info->invoice_value * 100) / 100;

        if ($invoice_info->status != "draft" && $invoice_info->due_date < $now && $invoice_info->payment_received < $invoice_info->invoice_value) {
            $invoice_status_class = "label-danger";
            $status = "overdue";
        } else if ($invoice_info->status !== "draft" && $invoice_info->payment_received <= 0) {
            $invoice_status_class = "label-warning";
            $status = "not_paid";
        } else if ($invoice_info->payment_received * 1 && $invoice_info->payment_received >= $invoice_info->invoice_value) {
            $invoice_status_class = "label-success";
            $status = "fully_paid";
        } else if ($invoice_info->payment_received > 0 && $invoice_info->payment_received < $invoice_info->invoice_value) {
            $invoice_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($invoice_info->status === "draft") {
            $invoice_status_class = "label-default";
            $status = "draft";
        }

        $invoice_status = "<span class='label $invoice_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $invoice_status;
        } else {
            return $status;
        }
    }

}

if (!function_exists('get_purchase_order_status_label')) {

    function get_purchase_order_status_label($purchase_order_info, $return_html = true) {
        $purchase_order_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $purchase_order_info->purchase_order_value = floor($purchase_order_info->purchase_order_value * 100) / 100;

        if ($purchase_order_info->status != "draft" && $purchase_order_info->valid_until < $now && $purchase_order_info->payment_received < $purchase_order_info->purchase_order_value) {
            $purchase_order_status_class = "label-danger";
            $status = "overdue";
        } else if ($purchase_order_info->status !== "draft" && $purchase_order_info->payment_received <= 0) {
            $purchase_order_status_class = "label-warning";
            $status = "not_paid";
        } else if ($purchase_order_info->payment_received * 1 && $purchase_order_info->payment_received >= $purchase_order_info->purchase_order_value) {
            $purchase_order_status_class = "label-success";
            $status = "fully_paid";
        } else if ($purchase_order_info->payment_received > 0 && $purchase_order_info->payment_received < $purchase_order_info->purchase_order_value) {
            $purchase_order_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($purchase_order_info->status === "draft") {
            $purchase_order_status_class = "label-default";
            $status = "draft";
        }

        $purchase_order_status = "<span class='label $purchase_order_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $purchase_order_status;
        } else {
            return $status;
        }
    }
}


//vendors invoice status
if (!function_exists('get_vendor_invoice_status_label')) {

    function get_vendor_invoice_status_label($loan_info, $return_html = true) {
        $loan_info_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $loan_info->total = floor($loan_info->total * 100) / 100;

       /* if ($loan_info->status != "draft" &&  $loan_info->due_date < $now && $loan_info->paid_amount < $loan_info->total) {
            $loan_info_status_class = "label-danger";
            $status = "overdue";
        } else */if ($loan_info->status !== "draft" && $loan_info->paid_amount <= 0) {
            $loan_info_status_class = "label-warning";
            $status = "not_paid";
        } else if ($loan_info->paid_amount * 1 && $loan_info->paid_amount >= $loan_info->total) {
            $loan_info_status_class = "label-success";
            $status = "fully_paid";
        } else if ($loan_info->paid_amount > 0 && $loan_info->paid_amount < $loan_info->total) {
            $loan_info_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($loan_info->status === "draft") {
            $loan_info_status_class = "label-default";
            $status = "draft";
        }

        $loan_info_status = "<span class='label $loan_info_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $loan_info_status;
        } else {
            return $status;
        }
    }
}



//loan status 
if (!function_exists('get_loan_status_label')) {

    function get_loan_status_label($loan_info, $return_html = true) {
        $loan_info_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $loan_info->total = floor($loan_info->total * 100) / 100;

        if ($loan_info->status != "draft" &&  $loan_info->due_date < $now && $loan_info->paid_amount < $loan_info->total) {
            $loan_info_status_class = "label-danger";
            $status = "overdue";
        } else if ($loan_info->status !== "draft" && $loan_info->paid_amount <= 0) {
            $loan_info_status_class = "label-warning";
            $status = "not_paid";
        } else if ($loan_info->paid_amount * 1 && $loan_info->paid_amount >= $loan_info->total) {
            $loan_info_status_class = "label-success";
            $status = "fully_paid";
        } else if ($loan_info->paid_amount > 0 && $loan_info->paid_amount < $loan_info->total) {
            $loan_info_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($loan_info->status === "draft") {
            $loan_info_status_class = "label-default";
            $status = "draft";
        }

        $loan_info_status = "<span class='label $loan_info_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $loan_info_status;
        } else {
            return $status;
        }
    }
}

if (!function_exists('get_work_order_status_label')) {

    function get_work_order_status_label($work_order_info, $return_html = true) {
        $work_order_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $work_order_info->work_order_value = floor($work_order_info->work_order_value * 100) / 100;

        if ($work_order_info->status != "draft" && $work_order_info->valid_until < $now && $work_order_info->payment_received < $work_order_info->work_order_value) {
            $work_order_status_class = "label-danger";
            $status = "overdue";
        } else if ($work_order_info->status !== "draft" && $work_order_info->payment_received <= 0) {
            $work_order_status_class = "label-warning";
            $status = "not_paid";
        } else if ($work_order_info->payment_received * 1 && $work_order_info->payment_received >= $work_order_info->work_order_value) {
            $work_order_status_class = "label-success";
            $status = "fully_paid";
        } else if ($work_order_info->payment_received > 0 && $work_order_info->payment_received < $work_order_info->work_order_value) {
            $work_order_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($work_order_info->status === "draft") {
            $work_order_status_class = "label-default";
            $status = "draft";
        }

        $work_order_status = "<span class='label $work_order_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $work_order_status;
        } else {
            return $status;
        }
    }
}



/**
 * get all data to make an invoice
 * 
 * @param Int $invoice_id
 * @return array
 */
/*if (!function_exists('get_invoice_making_data')) {

    function get_invoice_making_data($invoice_id) {
        $ci = get_instance();
        $invoice_info = $ci->Invoices_model->get_details(array("id" => $invoice_id))->row();
        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['invoice_items'] = $ci->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data["invoice_total_summary"] = $ci->Invoices_model->get_invoice_total_summary($invoice_id);

            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->result();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->result();
            return $data;
        }
    }

} */

if (!function_exists('get_student_making_data')) {

    function get_student_making_data($student_desk_id) {
        $ci = get_instance();
        $student_desk_info = $ci->Student_desk_model->get_details(array("id" => $student_desk_id))->row();
        if ($student_desk_info) {
            $data['student_desk_info'] = $student_desk_info;
            
           
            return $data;
        }
    }

}


if (!function_exists('prepare_student_desk_pdf')) {

    function prepare_student_desk_pdf($student_desk_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);

        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);
       //$ci->pdf->SetAutoPageBreak(true,0);


       if ($student_desk_data) {
        $student_desk_data["mode"] = $mode;
    
        $html = $ci->load->view("student_desk/student_desk_pdf", $student_desk_data, true);
    
        // Commented out section
        /* if ($mode != "html") {
            $ci->pdf->writeHTML($html, true, false, true, false, '');
        } */
        
        // Set font
        $footer_sign = $ci->load->view("student_desk/footer_sign", $student_desk_data, true);
        $footer = $ci->load->view("payslip/payslip_footer", $student_desk_data, true);
    
        if ($mode != "html") {
            $ci->pdf->writeHTML($html, true, false, true, false, '');
            $ci->pdf->SetAutoPageBreak(true, 0);
            $ci->pdf->SetY(238);
            $ci->pdf->writeHTML($footer_sign, true, false, true, false, '');
            $ci->pdf->SetAutoPageBreak(true, 0);
            $ci->pdf->SetY(252);
            $ci->pdf->writeHTML($footer, true, false, true, false, '');
        }
    
        // Ensure student_desk_info is set correctly
        $student_desk_info = isset($student_desk_data['student_desk_info']) ? $student_desk_data['student_desk_info'] : null;
    
        if ($student_desk_info) {
            $pdf_file_name = lang("student_desk") . "-" . $student_desk_info->id . ".pdf";
    
            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
    }
}


if (!function_exists('get_invoice_making_data')) {

    function get_invoice_making_data($invoice_id) {
        $ci = get_instance();
        $invoice_info = $ci->Invoices_model->get_details(array("id" => $invoice_id))->row();
        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['invoice_items'] = $ci->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->result();
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data["invoice_total_summary"] = $ci->Invoices_model->get_invoice_total_summary($invoice_id);

            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->result();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->result();
            return $data;
        }
    }}

if (!function_exists('get_payslip_making_data')) {

    function get_payslip_making_data($payslip_id) {
        $ci = get_instance();
        $payslip_info = $ci->Payslip_model->get_details(array("id" => $payslip_id))->row();
        if ($payslip_info) {
            $data['payslip_info'] = $payslip_info;
            $data['user_info'] = $ci->Users_model->get_one($data['payslip_info']->user_id);

            $data['payslip_earnings'] = $ci->Payslip_earningsadd_model->get_details(array("payslip_id" => $payslip_id))->result();
            $data['payslip_deductions'] = $ci->Payslip_deductions_model->get_details(array("payslip_id" => $payslip_id))->result();
            $data['payslip_attendance'] = $ci->Payslip_attendance_model->get_details(array("payslip_id" => $payslip_id))->result();
            $data['payslip_earningsadd'] = $ci->Payslip_earningsadd_model->get_details(array("payslip_id" => $payslip_id))->result();
            $data["earnings_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
            $data["hra_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
            $data["conveyance_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
            $data["medical_allowance_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
             $data["payslip_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
            $data["deductions_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
            $data["attendance_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
           $data["earningsadd_total_summary"] = $ci->Payslip_model->get_deductions_total_summary($payslip_id);
           $data["payslip_user_total_duration"] = $ci->Payslip_model->get_payslip_user_per_month_total_duration($payslip_id);
           
            return $data;
        }
    }}
/**
 * get all data to make an invoice
 * 
 * @param Invoice making data $invoice_data
 * @return array
 */
/*if (!function_exists('prepare_invoice_pdf')) {

    function prepare_invoice_pdf($invoice_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(10);

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);

            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $pdf_file_name = lang("invoice") . "-" . $invoice_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

} */

if (!function_exists('prepare_invoice_pdf')) {

    function prepare_invoice_pdf($invoice_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->setPrintFooters(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(8.5);
        //$ci->pdf->Rect(5,5,200,287,'D');

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);
             $htmlss = $ci->load->view("invoices/invoice_pdfs", $invoice_data, true);
           $htmls = $ci->load->view("invoices/footer", $invoice_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
            if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            }
if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            $invoice_info = get_array_value($invoice_data, "invoice_info");
            //$pdf_file_name = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            $pdf_file_name = $invoice_info->company_name."-".get_setting("company_name")."-".lang("invoice") . "-" . $invoice_info->id . ".pdf";
           /* $pdf_file_namess = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_namess;
                $ci->pdf->Output($temp_download_path, "F");
                  } 
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                
           } */
            if ($mode === "download") {
                ob_end_clean();
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                 ob_end_clean();
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }

    }

}

if (!function_exists('prepare_invoice_without_gst_pdf')) {

    function prepare_invoice_without_gst_pdf($invoice_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->setPrintFooters(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(8.5);
        //$ci->pdf->Rect(5,5,200,287,'D');

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);
             $htmlss = $ci->load->view("invoices/invoice_pdfs", $invoice_data, true);
           $htmls = $ci->load->view("invoices/footer", $invoice_data, true);
          // $footer = $ci->load->view("invoices/continued_pdf", $invoice_data, true);
            if ($mode != "html") {
                $ci->pdf->SetAutoPageBreak(true,2);
                            $ci->pdf->SetY(5);
                $ci->pdf->writeHTML($html, true, false, true, false, '');
                $ci->pdf->SetAutoPageBreak(true,0);
                            $ci->pdf->SetY(-15);

                 $ci->pdf->writeHTML($footer, true, false, true, false, '');

            }
            
if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $pdf_file_name = $invoice_info->company_name."-".get_setting("company_name")."-".lang("invoice") . "-" . $invoice_info->id . ".pdf";
           /* $pdf_file_namess = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_namess;
                $ci->pdf->Output($temp_download_path, "F");
                
           
                
                
            } 
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                
           } */

            if ($mode === "download") {
                ob_end_clean();
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                ob_end_clean();
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                ob_end_clean();
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                ob_end_clean();
                return $html;
            }
        }

    }

}

if (!function_exists('prepare_invoice_print_pdf')) {

    function prepare_invoice_print_pdf($invoice_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->setPrintFooters(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(8.5);
        //$ci->pdf->Rect(5,5,200,287,'D');

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);
            $htmlss = $ci->load->view("invoices/invoice_pdfs", $invoice_data, true);
            $htmls = $ci->load->view("invoices/footer", $invoice_data, true);
            $html2 = $ci->load->view("invoices/invoice_client_pdf", $invoice_data, true);
            
            $html3 = $ci->load->view("invoices/invoice_company_pdf", $invoice_data, true);
            
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
             if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            }
if ($mode != "html") {
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
                
               
                $ci->pdf->writeHTML($html2, true, false, true, false, '');
                $ci->pdf->writeHTML($html, true, false, true, false, '');
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
                
               
                $ci->pdf->writeHTML($html3, true, false, true, false, '');
                $ci->pdf->writeHTML($html, true, false, true, false, '');
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            $invoice_info = get_array_value($invoice_data, "invoice_info");
            //$pdf_file_name = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            $pdf_file_name = $invoice_info->company_name."-".get_setting("company_name")."-".lang("invoice") . "-" . $invoice_info->id . ".pdf";
           /* $pdf_file_namess = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_namess;
                $ci->pdf->Output($temp_download_path, "F");
                
           
                
                
            } 
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                
           } */

            if ($mode === "download") {
                ob_end_clean();
                $ci->pdf->Output($pdf_file_name, "I");
            } 
        }

    }

}

if (!function_exists('prepare_print_invoice_without_gst_pdf')) {

    function prepare_print_invoice_without_gst_pdf($invoice_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->setPrintFooters(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(8.5);
        //$ci->pdf->Rect(5,5,200,287,'D');

        if ($invoice_data) {

            $invoice_data["mode"] = $mode;

            $html = $ci->load->view("invoices/invoice_pdf", $invoice_data, true);
            $htmlss = $ci->load->view("invoices/invoice_pdfs", $invoice_data, true);
            $htmls = $ci->load->view("invoices/footer", $invoice_data, true);
            $html2 = $ci->load->view("invoices/invoice_client_pdf", $invoice_data, true);
            
            $html3 = $ci->load->view("invoices/invoice_company_pdf", $invoice_data, true);
            $footer = $ci->load->view("invoices/continued_pdf", $invoice_data, true);
            
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
            /*if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            } */
if ($mode != "html") {
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
                
               
                $ci->pdf->writeHTML($html2, true, false, true, false, '');
                $ci->pdf->writeHTML($html, true, false, true, false, '');
               // $ci->pdf->AddPage();
               // $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
                
               
                $ci->pdf->writeHTML($html3, true, false, true, false, '');
                $ci->pdf->writeHTML($html, true, false, true, false, '');
               // $ci->pdf->AddPage();
                //$ci->pdf->writeHTML($htmlss, true, false, true, false, '');
                $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            $invoice_info = get_array_value($invoice_data, "invoice_info");
            //$pdf_file_name = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            $pdf_file_name = $invoice_info->company_name."-".get_setting("company_name")."-".lang("invoice") . "-" . $invoice_info->id . ".pdf";
           /* $pdf_file_namess = lang("invoice") . "-" . $invoice_info->id . ".pdf";
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_namess;
                $ci->pdf->Output($temp_download_path, "F");
                
           
                
                
            } 
            if ($mode === "download") {
                $temp_download_path =  "/var/www/html/"  . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                
           } */

            if ($mode === "download") {
                ob_end_clean();
                $ci->pdf->Output($pdf_file_name, "I");
            } 
        }

    }

}


if (!function_exists('prepare_delivery_pdf')) {

    function prepare_delivery_pdf($estimate_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(9);

        if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("delivery/delivery_pdf", $estimate_data, true);
            $footer = $ci->load->view("delivery/delivery_footer", $estimate_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
                $ci->pdf->SetAutoPageBreak(true,0);
                            $ci->pdf->SetY(200);

                 $ci->pdf->writeHTML($footer, true, false, true, false, '');
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $pdf_file_name = lang("delivery") . "-$estimate_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}
if (!function_exists('prepare_voucher_pdf')) {

    function prepare_voucher_pdf($estimate_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->setPrintFooters(false);
        $ci->pdf->setVoucherFooter(true);
        $ci->pdf->SetCellPadding(0.0);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->SetFontSize(11.00);
        $ci->pdf->AddPage();

       if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("voucher/voucher_pdf", $estimate_data, true);
        // Set font
        $footer = $ci->load->view("voucher/voucher_footer", $estimate_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
                            $ci->pdf->SetY(242);

                 $ci->pdf->writeHTML($footer, true, false, true, false, '');
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $estimate_items = get_array_value($estimate_data, "estimate_items");
            $pdf_file_name = get_setting("company_name")."-".lang("voucher") . "-$estimate_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}
/**
 * get all data to make an estimate
 * 
 * @param emtimate making data $estimate_data
 * @return array
 */
/*if (!function_exists('prepare_estimate_pdf')) {

    function prepare_estimate_pdf($estimate_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();

        if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("estimates/estimate_pdf", $estimate_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $pdf_file_name = lang("estimate") . "-$estimate_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

} */
if (!function_exists('prepare_estimate_pdf')) {

    function prepare_estimate_pdf($estimate_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
       $ci->pdf->SetFontSize(8.5);
        if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("estimates/estimate_pdf", $estimate_data, true);
            $htmls = $ci->load->view("estimates/footer", $estimate_data, true);
            $htmlss = $ci->load->view("estimates/estimate_pdfs", $estimate_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
            if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            }

if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            
            
            

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            //$pdf_file_name = lang("estimate") . "-$estimate_info->id.pdf";
            $pdf_file_name = $estimate_info->company_name."-".get_setting("company_name")."-".lang("estimate") . "-" . $estimate_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

if (!function_exists('prepare_estimate_without_gst_pdf')) {

    function prepare_estimate_without_gst_pdf($estimate_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
       $ci->pdf->SetFontSize(8.5);
        if ($estimate_data) {

            $estimate_data["mode"] = $mode;

            $html = $ci->load->view("estimates/estimate_pdf", $estimate_data, true);
            $htmls = $ci->load->view("estimates/footer", $estimate_data, true);
            $htmlss = $ci->load->view("estimates/estimate_pdfs", $estimate_data, true);
            if ($mode != "html") {
                 $ci->pdf->SetAutoPageBreak(true,4);
                            $ci->pdf->SetY(5);
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
           /* if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            } */

if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            
            
            

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            //$pdf_file_name = lang("estimate") . "-$estimate_info->id.pdf";
            $pdf_file_name = $estimate_info->company_name."-".get_setting("company_name")."-".lang("estimate") . "-" . $estimate_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

/**
 * 
 * get invoice number
 * @param Int $invoice_id
 * @return string
 */
if (!function_exists('get_invoice_id')) {

    function get_invoice_id($invoice_id) {
        $prefix = get_setting("invoice_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("invoice")) . " #";
        return $prefix . $invoice_id;
    }

}

if (!function_exists('get_payslip_id')) {

    function get_payslip_id($payslip_id) {
        //get user country details 
        $ci = get_instance();
        $payslip_table = $ci->Payslip_model->get_one($payslip_id);
        $user_table = $ci->Users_model->get_one($payslip_table->user_id);
        //$user_country = $user_table->country;
         $user_country = $payslip_table->branch;

if($user_country){ 
    $user_country_options = array("buid"=> $user_country);
    $get_user_country_info = $ci->Branches_model->get_details($user_country_options)->row();
    $get_country_prefix = $get_user_country_info->payslip_prefix;
}
        $settings_prefix = get_setting("payslip_prefix");
        $settings_prefix = $settings_prefix ? $settings_prefix : strtoupper(lang("payslip")) . " #";
        $prefix = $get_country_prefix ? $get_country_prefix : $settings_prefix;
        /*$prefix = get_setting("payslip_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("payslip")) . " #";*/
        return $prefix . $payslip_id;
    }

}



if (!function_exists('prepare_payslip_pdf')) {

    function prepare_payslip_pdf($payslip_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(false);
        $ci->pdf->setPrintPayslip_Footers(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
        $ci->pdf->SetFontSize(9.3);
       //$ci->pdf->SetAutoPageBreak(true,0);


        if ($payslip_data) {

            $payslip_data["mode"] = $mode;

            $html = $ci->load->view("payslip/payslip_pdf", $payslip_data, true);

           /* if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            } */
            // Set font
       // $footer = $ci->load->view("payslip/payslip_footer", $payslip_data, true);

            //country footer
            $payslip_info = get_array_value($payslip_data, "payslip_info");
            $payslip_table = $ci->Payslip_model->get_one($payslip_info->id);
        $user_table = $ci->Users_model->get_one($payslip_table->user_id);
        $user_country = $payslip_table->branch;
if($user_country){ 
    $user_country_options = array("buid"=> $user_country);
    $get_user_country_info = $ci->Branches_model->get_details($user_country_options)->row();
    $get_country_prefix = $get_user_country_info->payslip_footer;
}
        //$footer = $ci->load->view("payslip/payslip_footer", $payslip_data, true);
           $footer = '<div style="text-align:center">'.$get_country_prefix .'<div>';

            
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
                $ci->pdf->SetAutoPageBreak(true,0);
                            $ci->pdf->SetY(250);

                 $ci->pdf->writeHTML($footer, true, false, true, false, '');
            }

            $payslip_info = get_array_value($payslip_data, "payslip_info");
            $pdf_file_name = lang("payslip") . "-" . $payslip_info->id . ".pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

/**
 * 
 * get estimate number
 * @param Int $estimate_id
 * @return string
 */
if (!function_exists('get_estimate_id')) {

    function get_estimate_id($estimate_id) {
        $prefix = get_setting("estimate_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("estimate")) . " #";
        return $prefix . $estimate_id;
    }

}
if (!function_exists('get_delivery_id')) {

    function get_delivery_id($delivery_id) {
        $prefix = get_setting("delivery_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("delivery")) . " #";
        return $prefix . $delivery_id;
    }

}

if (!function_exists('get_purchase_order_id')) {

    function get_purchase_order_id($purchase_order_id) {
        $prefix = get_setting("purchase_order_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("purchase_order")) . " #";
        return $prefix . $purchase_order_id;
    }

}

if (!function_exists('get_work_order_id')) {

    function get_work_order_id($work_order_id) {
        $prefix = get_setting("work_order_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("work_order")) . " #";
        return $prefix . $work_order_id;
    }

}
if (!function_exists('get_voucher_id')) {

    function get_voucher_id($voucher_id) {
        $prefix = get_setting("voucher_prefix");
        $prefix = $prefix ? $prefix : strtoupper(lang("voucher")) . " #";
        return $prefix . $voucher_id;
    }

}
/**
 * 
 * get ticket number
 * @param Int $ticket_id
 * @return string
 */
if (!function_exists('get_ticket_id')) {

    function get_ticket_id($ticket_id) {
        $prefix = get_setting("ticket_prefix");
        $prefix = $prefix ? $prefix : lang("ticket") . " #";
        return $prefix . $ticket_id;
    }

}


/**
 * get all data to make an estimate
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('get_estimate_making_data')) {

    function get_estimate_making_data($estimate_id) {
        $ci = get_instance();
        $estimate_info = $ci->Estimates_model->get_details(array("id" => $estimate_id))->row();
        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['estimate_info']->client_id);
            $data['estimate_items'] = $ci->Estimate_items_model->get_details(array("estimate_id" => $estimate_id))->result();
            $data["estimate_total_summary"] = $ci->Estimates_model->get_estimate_total_summary($estimate_id);

            $data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();
            return $data;
        }
    }

}
if (!function_exists('get_delivery_making_data')) {

    function get_delivery_making_data($estimate_id) {
        $ci = get_instance();
        $estimate_info = $ci->Delivery_model->get_details(array("id" => $estimate_id))->row();
        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['client_info'] = $ci->Users_model->get_one($data['estimate_info']->client_id);
            $data['estimate_items'] = $ci->Delivery_items_model->get_details(array("estimate_id" => $estimate_id))->result();
            //$data["estimate_total_summary"] = $ci->Estimates_model->get_estimate_total_summary($estimate_id);

            $data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();
            return $data;
        }
    }

}

if (!function_exists('get_purchase_order_making_data')) {

    function get_purchase_order_making_data($purchase_order_id) {
        $ci = get_instance();
        $purchase_order_info = $ci->Purchase_orders_model->get_details(array("id" => $purchase_order_id))->row();
        if ($purchase_order_info) {
            $data['purchase_order_info'] = $purchase_order_info;
            $data['vendor_info'] = $ci->Vendors_model->get_one($data['purchase_order_info']->vendor_id);
            $data['purchase_order_items'] = $ci->Purchase_order_items_model->get_details(array("purchase_order_id" => $purchase_order_id))->result();
            $data["purchase_order_total_summary"] = $ci->Purchase_orders_model->get_purchase_order_total_summary($purchase_order_id);

            // Assuming you intended to get custom fields related to the purchase order
            $data['purchase_order_info']->custom_fields = $ci->Custom_field_values_model->get_details(array(
                "related_to_type" => "purchase_orders", // Adjusted from "estimates" to "purchase_orders"
                "show_in_estimate" => true,
                "related_to_id" => $purchase_order_id // Using $purchase_order_id instead of undefined $estimate_id
            ))->result();

            return $data;
        }
    }

}

if (!function_exists('prepare_purchase_order_pdf')) {

    function prepare_purchase_order_pdf($purchase_order_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
       $ci->pdf->SetFontSize(8.5);
        if ($purchase_order_data) {

            $purchase_order_data["mode"] = $mode;

            $html = $ci->load->view("purchase_orders/purchase_order_pdf", $purchase_order_data, true);
            $htmls = $ci->load->view("purchase_orders/footer", $purchase_order_data, true);
            $htmlss = $ci->load->view("purchase_orders/purchase_order_pdfs", $purchase_order_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
            if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            }

if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            
            
            

            $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
            //$pdf_file_name = lang("purchase_orders") . "-$purchase_order_info->id.pdf";
            $pdf_file_name = $purchase_order_info->company_name ."-".get_setting("company_name")."-".lang("purchase_orders") . "-$purchase_order_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

if (!function_exists(' prepare_purchase_order_without_gst_pdf')) {

    function  prepare_purchase_order_without_gst_pdf($purchase_order_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
       $ci->pdf->SetFontSize(8.5);
        if ($purchase_order_data) {

            $purchase_order_data["mode"] = $mode;

            $html = $ci->load->view("purchase_orders/purchase_order_pdf", $purchase_order_data, true);
            $htmls = $ci->load->view("purchase_orders/footer", $purchase_order_data, true);
            //$htmlss = $ci->load->view("purchase_orders/purchase_order_pdfs", $purchase_order_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
          /*  if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            } */

if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            
            
            

            $purchase_order_info = get_array_value($purchase_order_data, "purchase_order_info");
            //$pdf_file_name = lang("purchase_orders") . "-$purchase_order_info->id.pdf";
            $pdf_file_name = $purchase_order_info->company_name ."-".get_setting("company_name")."-".lang("purchase_orders") . "-$purchase_order_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}


if (!function_exists('get_work_order_making_data')) {

    function get_work_order_making_data($work_order_id) {
        $ci = get_instance();
        $work_order_info = $ci->Work_orders_model->get_details(array("id" => $work_order_id))->row();
        if ($work_order_info) {
            $data['work_order_info'] = $work_order_info;
            $data['vendor_info'] = $ci->Vendors_model->get_one($data['work_order_info']->vendor_id);
            $data['work_order_items'] = $ci->Work_order_items_model->get_details(array("work_order_id" => $work_order_id))->result();
            $data["work_order_total_summary"] = $ci->Work_orders_model->get_work_order_total_summary($work_order_id);
    
            // Ensure that estimate_id is defined before using it
            if (isset($work_order_info->estimate_id)) {
                $estimate_id = $work_order_info->estimate_id;
                $data['work_order_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();
            } else {
                $data['work_order_info']->custom_fields = array(); // Default to an empty array if no estimate_id
            }
            
            return $data;
        }
    
        return null; // Return null if work order info is not found
    }
}
    
if (!function_exists('get_voucher_making_data')) {

    function get_voucher_making_data($estimate_id) {
        $ci = get_instance();
        $estimate_info = $ci->Voucher_model->get_details(array("id" => $estimate_id))->row();
        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['estimate_items'] = $ci->Voucher_expenses_model->get_details(array("estimate_id" => $estimate_id))->result();
            //$data["estimate_total_summary"] = $ci->Estimates_model->get_estimate_total_summary($estimate_id);

            //$data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->result();
            return $data;
        }
    }

}
if (!function_exists('prepare_work_order_pdf')) {

    function prepare_work_order_pdf($work_order_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
       $ci->pdf->SetFontSize(8.5);
        if ($work_order_data) {

            $work_order_data["mode"] = $mode;

            $html = $ci->load->view("work_orders/work_order_pdf", $work_order_data, true);
            $htmls = $ci->load->view("work_orders/footer", $work_order_data, true);
            $htmlss = $ci->load->view("work_orders/work_order_pdfs", $work_order_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
            if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            }

if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            
            
            

            $work_order_info = get_array_value($work_order_data, "work_order_info");
            //$pdf_file_name = lang("work_orders") . "-$work_order_info->id.pdf";
             $pdf_file_name = $work_order_info->company_name ."-".get_setting("company_name")."-".lang("work_orders") . "-$work_order_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}

if (!function_exists('prepare_work_order_without_gst_pdf')) {

function prepare_work_order_without_gst_pdf($work_order_data, $mode = "download") {
        $ci = get_instance();
        $ci->load->library('pdf');
        $ci->pdf->setPrintHeader(false);
        $ci->pdf->setPrintFooter(true);
        $ci->pdf->SetCellPadding(1.5);
        $ci->pdf->setImageScale(1.42);
        $ci->pdf->AddPage();
       $ci->pdf->SetFontSize(8.5);
        if ($work_order_data) {

            $work_order_data["mode"] = $mode;

            $html = $ci->load->view("work_orders/work_order_pdf", $work_order_data, true);
            $htmls = $ci->load->view("work_orders/footer", $work_order_data, true);
           // $htmlss = $ci->load->view("work_orders/work_order_pdfs", $work_order_data, true);
            if ($mode != "html") {
                $ci->pdf->writeHTML($html, true, false, true, false, '');
            }
          /*  if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmlss, true, false, true, false, '');
            } */

if ($mode != "html") {
       $ci->pdf->AddPage();
                $ci->pdf->writeHTML($htmls, true, false, true, false, '');
            }
            
            
            

            $work_order_info = get_array_value($work_order_data, "work_order_info");
            //$pdf_file_name = lang("work_orders") . "-$work_order_info->id.pdf";
             $pdf_file_name = $work_order_info->company_name ."-".get_setting("company_name")."-".lang("work_orders") . "-$work_order_info->id.pdf";

            if ($mode === "download") {
                $ci->pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $ci->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $ci->pdf->Output($pdf_file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

}



/**
 * get team members and teams select2 dropdown data list
 * 
 * @return array
 */
if (!function_exists('get_team_members_and_teams_select2_data_list')) {

    function get_team_members_and_teams_select2_data_list() {
        $ci = get_instance();

        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff","status"=>"active"))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $members_and_teams_dropdown;
    }

}

if (!function_exists('get_roles_select2_data_list')) {

    function get_roles_select2_data_list($role_id="") {
        $ci = get_instance();

        // team members
        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff","role_id"=>$role_id,"status"=>"active"))->result();
        
        //ousource members 
        $rm_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource","role_id"=>$role_id,"status"=>"active"))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        foreach ($rm_members as $rm_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $rm_member->id, "text" => lang("outsource_member").":".$rm_member->first_name . " " . $rm_member->last_name);
        }
        // team
        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $members_and_teams_dropdown;
    }

}

if (!function_exists('get_payslip_user_country_select2_data_list')) {

    function get_payslip_user_country_select2_data_list($country_id="") {
        $ci = get_instance();

        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff","country"=>$country_id,"status"=>"active"))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $members_and_teams_dropdown;
    }

}


/*if (!function_exists('get_team_members_and_teams_select2_data_list')) {

    function get_team_members_and_teams_select2_data_list() {
        $ci = get_instance();

        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $members_and_teams_dropdown;
    }

}*/

if (!function_exists('get_outsource_members_and_teams_select2_data_list')) {

    function get_outsource_members_and_teams_select2_data_list() {
        $ci = get_instance();

        $outsource_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "resource"))->result();
        $outsource_members_and_teams_dropdown = array();

        foreach ($outsource_members as $team_member) {
            $outsource_members_and_teams_dropdown[] = array("type" => "outsource_member", "id" => "outsource_member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $outsource_members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $outsource_members_and_teams_dropdown;
    }

}



/**
 * submit data for notification
 * 
 * @return array
 */
if (!function_exists('log_notification')) {

    function log_notification($event, $options = array(), $user_id = 0) {

        $ci = get_instance();

        $url = get_uri("notification_processor/create_notification");

        $req = "event=" . encode_id($event, "notification");

        if ($user_id) {
            $req .= "&user_id=" . $user_id;
        } else if ($user_id === "0") {
            $req .= "&user_id=" . $user_id; //if user id is 0 (string) we'll assume that it's system bot 
        } else if (isset($ci->login_user)) {
            $req .= "&user_id=" . $ci->login_user->id;
        }


        foreach ($options as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);


        if (get_setting("add_useragent_to_curl")) {
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:19.0) Gecko/20100101 Firefox/19.0");
        }

        curl_exec($ch);
        curl_close($ch);
    }

}


/**
 * save custom fields for any context
 * 
 * @param Int $estimate_id
 * @return array
 */
if (!function_exists('save_custom_fields')) {

    function save_custom_fields($related_to_type, $related_to_id, $is_admin = 0, $user_type = "", $activity_log_id = 0) {
        $ci = get_instance();

        $custom_fields = $ci->Custom_fields_model->get_combined_details($related_to_type, $related_to_id, $is_admin, $user_type)->result();

        // we have to update the activity logs table according to the changes of custom fields
        $changes = array();

        //save custom fields
        foreach ($custom_fields as $field) {
            $field_name = "custom_field_" . $field->id;
            //save only submitted fields
            if (array_key_exists($field_name, $_POST)) {
                $value = $ci->input->post($field_name);

                if ($value) {
                    $field_value_data = array(
                        "related_to_type" => $related_to_type,
                        "related_to_id" => $related_to_id,
                        "custom_field_id" => $field->id,
                        "value" => $value
                    );

                    $field_value_data = clean_data($field_value_data);
                    $save_data = $ci->Custom_field_values_model->upsert($field_value_data);

                    if ($save_data) {
                        $changed_values = get_array_value($save_data, "changes");
                        $field_title = get_array_value($changed_values, "title");
                        $field_type = get_array_value($changed_values, "field_type");

                        //add changes of custom fields
                        if (get_array_value($save_data, "operation") == "update") {
                            //update
                            $changes[$field_title . "[:" . $field->id . "," . $field_type . ":]"] = array("from" => get_array_value($changed_values, "from"), "to" => get_array_value($changed_values, "to"));
                        } else if (get_array_value($save_data, "operation") == "insert") {
                            //insert
                            $changes[$field_title . "[:" . $field->id . "," . $field_type . ":]"] = array("from" => "", "to" => $value);
                        }
                    }
                }
            }
        }

        //finally save the changes to activity logs table
        return update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id);
    }

}

/**
 * update custom fields changes to activity logs table
 */
if (!function_exists('update_custom_fields_changes')) {

    function update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id = 0) {
        if ($changes && count($changes)) {
            $ci = get_instance();

            $related_to_data = new stdClass();

            $log_type = "";
            $log_for = "";
            $log_type_title = "";
            $log_for_id = "";

            if ($related_to_type == "tasks") {
                $related_to_data = $ci->Tasks_model->get_one($related_to_id);
                $log_type = "task";
                $log_for = "project";
                $log_type_title = $related_to_data->title;
                $log_for_id = $related_to_data->project_id;
            }

            $log_data = array(
                "action" => "updated",
                "log_type" => $log_type,
                "log_type_title" => $log_type_title,
                "log_type_id" => $related_to_id,
                "log_for" => $log_for,
                "log_for_id" => $log_for_id
            );


            if ($activity_log_id) {
                $before_changes = array();

                //we have to combine with the existing changes of activity logs
                $activity_log = $ci->Activity_logs_model->get_one($activity_log_id);
                $activity_logs_changes = unserialize($activity_log->changes);
                if (is_array($activity_logs_changes)) {
                    foreach ($activity_logs_changes as $key => $value) {
                        $before_changes[$key] = array("from" => get_array_value($value, "from"), "to" => get_array_value($value, "to"));
                    }
                }

                $log_data["changes"] = serialize(array_merge($before_changes, $changes));

                if ($activity_log->action != "created") {
                    $ci->Activity_logs_model->update_where($log_data, array("id" => $activity_log_id));
                }
            } else {
                $log_data["changes"] = serialize($changes);
                return $ci->Activity_logs_model->save($log_data);
            }
        }
    }

}
/**
 * use this to clean xss and html elements
 * the best practice is to use this before rendering 
 * but you can use this before saving for suitable cases
 *
 * @param string or array $data
 * @return clean $data
 */
if (!function_exists("clean_data")) {

    function clean_data($data) {
        $ci = get_instance();

        $data = $ci->security->xss_clean($data);
        $disable_html_input = get_setting("disable_html_input");

        if ($disable_html_input == "1") {
            $data = html_escape($data);
        }

        return $data;
    }

}


//return site logo
if (!function_exists("get_logo_url")) {

    function get_logo_url() {
        return get_file_uri(get_setting("system_file_path") . get_setting("site_logo"));
    }

}


//get branches users
if (!function_exists('get_payslip_user_branch_select2_data_list')) {

    function get_payslip_user_branch_select2_data_list($branch_id="",$company_id="") {
        $ci = get_instance();

        $team_members = $ci->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff","branch"=>$branch_id,"status"=>"active","company_id"=>$company_id))->result();
        $members_and_teams_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_and_teams_dropdown[] = array("type" => "member", "id" => "member:" . $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $team = $ci->Team_model->get_all_where(array("deleted" => 0))->result();
        foreach ($team as $team) {
            $members_and_teams_dropdown[] = array("type" => "team", "id" => "team:" . $team->id, "text" => $team->title);
        }

        return $members_and_teams_dropdown;
    }

}

//get logo from setting
if (!function_exists("get_file_from_setting")) {

    function get_file_from_setting($setting_name = "", $only_file_path_with_slash = false) {

        if ($setting_name) {
            $setting_value = get_setting($setting_name);
            if ($setting_value) {
                $file = @unserialize($setting_value);
                if (is_array($file)) {

                    //show full size thumbnail for signin page background
                    $show_full_size_thumbnail = false;
                    if ($setting_name == "signin_page_background") {
                        $show_full_size_thumbnail = true;
                    }

                    return get_source_url_of_file($file, get_setting("system_file_path"), "thumbnail", $only_file_path_with_slash, $only_file_path_with_slash, $show_full_size_thumbnail);
                } else {
                    if ($only_file_path_with_slash) {
                        return "/" . (get_setting("system_file_path") . $setting_value);
                    } else {
                        return get_file_uri(get_setting("system_file_path") . $setting_value);
                    }
                }
            }
        }
    }

}

//get site favicon
if (!function_exists("get_favicon_url")) {

    function get_favicon_url() {
        $favicon_from_setting = get_file_from_setting('favicon');
        return $favicon_from_setting ? $favicon_from_setting : get_file_uri("assets/images/favicon.png");
    }

}

/**
 * delete files
 * @param String $directory_path
 * @param Array $files
 */
if (!function_exists("delete_app_files")) {

    function delete_app_files($directory_path = "", $files = array()) {
        $ci = get_instance();

        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_array($file)) {
                    $file_name = get_array_value($file, "file_name");
                    $file_id = get_array_value($file, "file_id");
                    $service_type = get_array_value($file, "service_type");

                    if ($service_type == "google") {
                        //google drive file
                        $ci->load->library("google");
                        $ci->google->delete_file($file_id);
                    } else {
                        $source_path = $directory_path . $file_name;
                        delete_file_from_directory($source_path);
                    }
                } else {
                    delete_file_from_directory($directory_path . $file); //single file
                }
            }
        } else {
            delete_file_from_directory($directory_path . $files); //system files won't be array at first time
        }
    }

}

/**
 * Get system files setting value
 * @param string $setting_name
 * @return array/string setting value
 */
if (!function_exists("get_system_files_setting_value")) {

    function get_system_files_setting_value($setting_name = "") {
        $setting_value = get_setting($setting_name);
        if ($setting_value) {
            $setting_as_array = @unserialize($setting_value);
            if (is_array($setting_as_array)) {
                return array($setting_as_array);
            } else {
                return $setting_value;
            }
        }
    }

}
if (!function_exists('get_payslip_status_label')) {

    function get_payslip_status_label($payslip_info, $return_html = true) {
        $payslip_status_class = "label-default";
        $status = "not_paid";
        $now = get_my_local_time("Y-m-d");

        //ignore the hidden value. check only 2 decimal place.
        $payslip_info->netsalary = floor($payslip_info->netsalary * 100) / 100;

        /*if ($payslip_info->status != "draft" && $payslip_info->due_date < $now && $payslip_info->payment_received < $payslip_info->netsalary) {
            $payslip_status_class = "label-danger";
            $status = "overdue";
        } else*/ if ($payslip_info->status !== "draft" && $payslip_info->payment_received <= 0) {
            $payslip_status_class = "label-warning";
            $status = "not_paid";
        } else if ($payslip_info->payment_received * 1 && $payslip_info->payment_received >= $payslip_info->netsalary) {
            $payslip_status_class = "label-success";
            $status = "fully_paid";
        } else if ($payslip_info->payment_received > 0 && $payslip_info->payment_received < $payslip_info->netsalary) {
            $payslip_status_class = "label-primary";
            $status = "partially_paid";
        } else if ($payslip_info->status === "draft") {
            $payslip_status_class = "label-default";
            $status = "draft";
        }

        $payslip_status = "<span class='label $payslip_status_class large'>" . lang($status) . "</span>";
        if ($return_html) {
            return $payslip_status;
        } else {
            return $status;
        }
    }

}

