<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendors_invoice_list extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->access_only_admin();
        $this->init_permission_checker("purchase_order");
        $this->access_only_allowed_members();
    }

    function index() {
        $this->check_module_availability("module_purchase_order");
        //$this->access_only_allowed_members();
        //$this->template->rander("countries/index");
        $view_data['vendors_dropdown'] = json_encode($this->_get_vendors_dropdown());
        if ($this->login_user->is_admin == "1")
        {
            // $view_data['task_statuses'] = $this->Vendors_invoice_status_model->get_details()->result();
           

            
           // $view_data['status_dropdown'] = $this->_get_vendors_invoice_status_dropdown();
            $this->template->rander("vendors_invoice_list/index",$view_data);
        }
        else if ($this->login_user->user_type == "staff")
         {
            //$this->access_only_allowed_members();
      if ($this->access_type!="all"&&!in_array($this->login_user->id, $this->allowed_members)) {
                   redirect("forbidden");
              }
              //$view_data['status_dropdown'] = $this->_get_vendors_invoice_status_dropdown();
              //$view_data['task_statuses'] = $this->Vendors_invoice_status_model->get_details()->result();
            $this->template->rander("vendors_invoice_list/index",$view_data);
        }else {
            //$view_data['status_dropdown'] = $this->_get_vendors_invoice_status_dropdown();
       
       //$view_data['task_statuses'] = $this->Vendors_invoice_status_model->get_details()->result();

        $this->template->rander("vendors_invoice_list/index",$view_data);
    } 
    }


    //get clients dropdown
    private function _get_vendors_dropdown() {
        $vendors_dropdown = array(array("id" => "", "text" => "- " . lang("vendor") . " -"));
        $vendors = $this->Vendors_model->get_dropdown_list(array("company_name"));
        foreach ($vendors as $key => $value) {
            $vendors_dropdown[] = array("id" => $key, "text" => $value);
        }
        return $vendors_dropdown;
    }


     //load the yearly view of estimate list
    function yearly() {
        $this->load->view("vendors_invoice_list/yearly_vendors_invoice_list");
    }

    function modal_form() {
//$this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Vendors_invoice_list_model->get_one($this->input->post('id'));
        $vendor_id = $this->input->post('vendor_id');
        $view_data["vendor_id"] = $vendor_id;
        $view_data['model_info'] =  $model_info;
        $view_data['vendors_dropdown'] = array("" => "-") + $this->Vendors_model->get_dropdown_list(array("company_name"));
         $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
          $view_data['gst_code_dropdown'] = $this->_get_gst_code_dropdown_select2_data();
         //$view_data['statuses'] = $this->Vendors_invoice_status_model->get_details()->result();
          //voucher id dropdown 
        $po_info = $this->Purchase_orders_model->get_one($model_info->purchase_order_id);
        $purchase_id_dropdown = array(array("id" => "", "text" => "-"));
        $purchase_id_dropdown[] = array("id" => $model_info->purchase_order_id, "text" => $po_info->purchase_no?$po_info->purchase_no:get_purchase_order_id($model_info->purchase_order_id));
        $view_data['purchase_id_dropdown'] = $purchase_id_dropdown;
        $this->load->view('vendors_invoice_list/modal_form', $view_data);
    }

 //gst state code
    private function _get_gst_code_dropdown_select2_data($show_header = false) {
        $gst_code = $this->Gst_state_code_model->get_all()->result();
        $gst_code_dropdown = array();

        

        foreach ($gst_code as $code) {
            $gst_code_dropdown[] = array("id" => $code->gstin_number_first_two_digits, "text" => $code->title);
        }
        return $gst_code_dropdown;
    }

 /*   //get team members dropdown
    private function _get_vendors_invoice_status_dropdown() {
          $statuses = $this->Vendors_invoice_status_model->get_details()->result();

             $status_dropdown = array(
                array("id" => "", "text" => "- " . lang("status") . " -")
            );

            foreach ($statuses as $status) {
                $status_dropdown[] = array("id" => $status->id, "text" => ( $status->key_name ? lang($status->key_name) : $status->title));
            }

        return json_encode($status_dropdown);
    } */



    function save() {

        validate_submitted_data(array(
            "id" => "numeric"
          
        ));

        $id = $this->input->post('id');
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "note");
        $new_files = unserialize($files_data);
        $data = array(
            "vendor_id" => $this->input->post('vendor_id'),
            "invoice_no" => $this->input->post('invoice_no'),
            "invoice_date" => $this->input->post('invoice_date'),
            "amount" => $this->input->post('amount'),
            "igst_tax" => $this->input->post('igst_tax'),
            "cgst_tax" => $this->input->post('cgst_tax'),
            "sgst_tax" => $this->input->post('sgst_tax'),
            "description" => $this->input->post('description'),
            "total" => $this->input->post('total'),
           // "due" => $this->input->post('due'),
            //"cheque_no" => $this->input->post('cheque_no'),
            //"utr_no" => $this->input->post('utr_no'),
            // "amount_paid" => $this->input->post('amount_paid'),
              "state_tax"=>$this->input->post('state_tax'),
             //"payment_method_id"=>$this->input->post('payment_method_id'),
             // "status_id"=>$this->input->post('status_id'),
              "gst_number"=>$this->input->post('gst_number'),
"gstin_number_first_two_digits"=>$this->input->post('gstin_number_first_two_digits'),
"purchase_order_id"=>$this->input->post('purchase_order_id'),




        );
        if ($id) {
            $note_info = $this->Vendors_invoice_list_model->get_one($id);
          $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $note_info->files, $new_files);
        }

        if (!$id) {
    // check the vendor invoice no     
        $data["invoice_no"] =$this->input->post('invoice_no');
        if ($this->Vendors_invoice_list_model->is_vendors_invoice_exists($data["invoice_no"])) {
                echo json_encode(array("success" => false, 'message' => lang('vendors_invoice_already')));
                exit();
            }

        }
        if ($id) {
    // check the vendor invoice no     
        $data["invoice_no"] =$this->input->post('invoice_no');
        $data["id"] =$this->input->post('id');
       if ($this->Vendors_invoice_list_model->is_vendors_invoice_exists($data["invoice_no"],$id)) {
                echo json_encode(array("success" => false, 'message' => lang('vendors_invoice_already')));
                exit();
            }

        }

        $data["files"] = serialize($new_files);
        if($data["files"]=='a:0:{}'){
    echo json_encode(array("success" => false, 'message' => '*Uploading files are required'));
    exit();
}
        $save_id = $this->Vendors_invoice_list_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        $this->access_only_allowed_members();
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Vendors_invoice_list_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Vendors_invoice_list_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        //$this->access_only_allowed_members();
       
        //$status = $this->input->post('status_id');
        $status   = $this->input->post("status");
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
         $vendor_id = $this->input->post('vendor_id');
        //$options = array("start_date" => $start_date ,"status_id" => $status,"end_date" => $end_date,"login_user_id" => $this->login_user->id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $options =  array("start_date" => $start_date ,"status" => $status,
            "end_date" => $end_date,"login_user_id" => $this->login_user->id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members,"vendor_id" =>$vendor_id);

       /* $options = array(
            
            "status_id" => $status,
           
        );  */
         $list_data = $this->Vendors_invoice_list_model->get_details( $options)->result();
         $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }



    /* list of invoice of a specific client, prepared for datatable  */

    function vendors_invoice_list_data_of_vendor($vendor_id) {
        $this->access_only_allowed_members_or_client_contact($vendor_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);
        $status = $this->input->post('status_id');
        $status = $this->input->post('status');
        $options = array(
            "vendor_id" => $vendor_id,
            "status" => $status
            
        );

        


        $list_data = $this->Vendors_invoice_list_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Vendors_invoice_list_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {

         $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("notes/file_preview/" . $file_name)));
                }
            }
        } 


        $files_links = "";
       
            $payment_files = $this->Vendors_invoice_payments_list_model->get_details(array("task_id" => $data->id))->result();
            foreach ($payment_files as $payment_file) {
                # code...
             if ($payment_file->files) {
            $files = unserialize($payment_file->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_links .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("notes/file_preview/" . $file_name)));
                }
            }
         }
        }

  // $check_status = js_anchor("<span class='$checkbox_class'></span>", array('title' => "", "class" => "", "data-id" => $data->id, "data-value" => $data->status_key_name === "done" ? "1" : "3", "data-act" => "update-task-status-checkbox")) . $data->id;
   //  $status = js_anchor($data->status_key_name ? lang($data->status_key_name) : $data->status_title, array('title' => "", "class" => "", "data-id" => $data->id, "data-value" => $data->status_id, "data-act" => "update-task-status"));
if($data->purchase_order_id){
    $purchase_info = $this->Purchase_orders_model->get_one($data->purchase_order_id);
     $purchase_order_url = anchor(get_uri("purchase_orders/view/" . $data->purchase_order_id), $purchase_info->purchase_no?$purchase_info->purchase_no:get_purchase_order_id($data->purchase_order_id));
 }else{
    $purchase_order_url = "-";
 }
       $due = 0;
        $due = 0;
        if ($data->total) {
            $due = ignor_minor_value($data->total - $data->paid_amount);
        }
        return array(//$data->id,
            $data->invoice_no,
            $data->invoice_date,
            //$data->vendor_name,
            anchor(get_uri("vendors/view/" . $data->vendor_id), $data->vendor_name),
            $purchase_order_url,
            to_currency($data->amount, $data->currency_symbol),
            to_currency($data->igst_tax,$data->currency_symbol),
            to_currency($data->cgst_tax,$data->currency_symbol),
            to_currency($data->sgst_tax,$data->currency_symbol),
            to_currency($data->total,$data->currency_symbol),
             to_currency($data->paid_amount,$data->currency_symbol),
           // to_currency($data->amount_paid,$data->currency_symbol),
            to_currency($due,$data->currency_symbol),
            $files_link.$files_links,
           // $status,
            $this->_get_vendor_invoice_status_label($data),
            //modal_anchor(get_uri("vendors_invoice_list/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vendor_invoice_list'), "data-post-id" => $data->id))
            modal_anchor(get_uri("vendors_invoice_list/task_view"), "<i class='fa fa-pencil'></i>", array("class" => "add_payment", "title" => lang('add_payment'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vendors_invoice_list/delete"), "data-action" => "delete-confirmation"))
        );
    }

private function _get_vendor_invoice_status_label($data, $return_html = true) {
        return get_vendor_invoice_status_label($data, $return_html);
    }

/* upadate a task status */

/*    function save_task_status($id = 0) {
        $this->access_only_team_members();
        $data = array(
            "status_id" => $this->input->post('value')
        );

        $save_id = $this->Vendors_invoice_list_model->save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, "message" => lang('record_saved')));

            $task_info = $this->Vendors_invoice_list_model->get_one($save_id);

           // log_notification("project_task_updated", array("id" => $task_info->id, "task_id" => $save_id, "activity_log_id" => get_array_value($data, "activity_log_id")));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    } */


    function task_view() {

        $task_id = $this->input->post('id');
        $model_info = $this->Vendors_invoice_list_model->get_details(array("id" => $task_id))->row();
        if (!$model_info->id) {
            show_404();
        }
     /*   $this->init_project_permission_checker($model_info->project_id);

        if (!$this->can_view_tasks($model_info->project_id)) {
            redirect("forbidden");
        } */

       // $view_data['can_edit_tasks'] = $this->can_edit_tasks();
        //$view_data['can_comment_on_tasks'] = $this->can_comment_on_tasks();

        $view_data['model_info'] = $model_info;
       // $view_data['collaborators'] = $this->_get_collaborators($model_info->collaborator_list);

        

        //$options = array("task_id" => $task_id);
        //$view_data['comments'] = $this->Project_comments_model->get_details($options)->result();
        $view_data['task_id'] = $task_id;

        //$view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("tasks", $task_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        //get checklist items
        $checklist_items_array = array();
        $checklist_items = $this->Vendors_invoice_payments_list_model->get_details(array("task_id" => $task_id))->result();
        foreach ($checklist_items as $checklist_item) {
            $checklist_items_array[] = $this->_make_checklist_item_row($checklist_item);
        }
       $view_data["checklist_items"] = json_encode($checklist_items_array);
     $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
      /*  $view_data["can_edit_task"] = true;
        if (!$this->can_edit_tasks()) {
            $view_data["can_edit_task"] = false;
        }

        $view_data['project_id'] = $model_info->project_id; */

        $this->load->view('vendors_invoice_list/view', $view_data);
    }


    function get_vendors_invoice_no_suggestion() {
        $item = $this->Vendors_invoice_list_model->get_invoice_no_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

 /* checklist */

    function save_checklist_item() {

        $task_id = $this->input->post("task_id");

        validate_submitted_data(array(
            "task_id" => "required|numeric"
        ));

        //$project_id = $this->Tasks_model->get_one($task_id)->project_id;

        //$this->init_project_permission_checker($project_id);

      /*  if ($task_id) {
            if (!$this->can_edit_tasks()) {
                redirect("forbidden");
            }
        } */
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "vendor_invoice_create");
        $new_files = unserialize($files_data);
        $data = array(
            "task_id" => $task_id,
            "title" => $this->input->post("checklist-add-item"),
             "payment_date" => $this->input->post("checklist-add-item-date"),
             "payment_method_id"=>$this->input->post("payment_method_id"),
             "description"=>$this->input->post("description"),
             "reference_number"=>$this->input->post("reference_number"),

        );

            $note_info = $this->Vendors_invoice_payments_list_model->get_one($id);
          $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $note_info->files, $new_files);

            $data["files"] = serialize($new_files);
        if($data["files"]=='a:0:{}'){
    echo json_encode(array("success" => false, 'message' => '*Uploading files are required'));
    exit();
}
        
        $save_id = $this->Vendors_invoice_payments_list_model->save($data);

        if ($save_id) {
            $item_info = $this->Vendors_invoice_payments_list_model->get_one($save_id);
            echo json_encode(array("success" => true, "data" => $this->_make_checklist_item_row($item_info), 'id' => $save_id));
        } else {
            echo json_encode(array("success" => false));
        }
    }



    private function _make_checklist_item_row($data = array(), $return_type = "row") {

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $file) {
                    $file_name = get_array_value($file, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("notes/file_preview/" . $file_name)));
                }
            }
        }
        $checkbox_class = "checkbox-blank";
        $title_class = "";
        $is_checked_value = 1;

        if ($data->is_checked == 1) {
            $is_checked_value = 0;
            $checkbox_class = "checkbox-checked";
            $title_class = "<span style='color:green;'> &nbsp Verified</span>";
        }
         $payment_title_info = $this->Payment_methods_model->get_one($data->payment_method_id);
         $vendors_invoice_info = $this->Vendors_invoice_list_model->get_one($data->task_id);
         $vendor_info = $this->Vendors_model->get_one($vendors_invoice_info->vendor_id);

        $status = js_anchor("<span class='$checkbox_class'></span>", array('title' => "", "data-id" => $data->id, "data-value" => $is_checked_value, "data-act" => "update-checklist-item-status-checkbox"));
     /*   if (!$this->can_edit_tasks()) {
            $status = "";
        } */
if ($data->is_checked == 1) {
        $title = "<span class='font-13 '>" .to_currency( $data->title,$vendor_info->currency_symbol)." , "."Payment Date:".$data->payment_date." ". $files_link." , ".$payment_title_info->title." No:".$data->reference_number.","."Payment Mode:".$payment_title_info->title." , "."Decription:".$data->description." ". $title_class. "</span>";
    }

    if ($data->is_checked == 0) {
         $title = "<span class='font-13 '>" .to_currency( $data->title,$vendor_info->currency_symbol)." , "."Payment Date:".$data->payment_date." ". $files_link." ,".$payment_title_info->title." No:".$data->reference_number.","."Payment Mode:".$payment_title_info->title." , "."Decription:".$data->description." ". $title_class. "</span>";

    }
        //$payment_date = "<span class='font-13 $title_class'>" . $data->payment_date . "</span>";
$delete = js_anchor("<i class='fa fa-times pull-right p3'></i>", array('title' => lang('delete_income'), "class" => "delete-checklist-item", "data-fade-out-on-success" => "#checklist-item-row-$data->id", "data-action-url" => get_uri("vendors_invoice_list/delete_checklist_item/$data->id"), "data-action" => "delete-confirmation"));

        //$delete = ajax_anchor(get_uri("vendors_invoice_list/delete_checklist_item/$data->id"), "<i class='fa fa-times pull-right p3'></i>", array("class" => "delete-checklist-item", "title" => lang("delete_checklist_item"), "data-fade-out-on-success" => "#checklist-item-row-$data->id"));
      /*  if (!$this->can_edit_tasks()) {
            $delete = "";
        } */

        if ($return_type == "data") {
            return $status . $title . $delete;
        }

        return "<div id='checklist-item-row-$data->id' class='list-group-item mb5 checklist-item-row' data-id='$data->id'>" . $status . $title . $delete . "</div>";
    }


    function save_checklist_item_status($id = 0) {
    $task_id = $this->Vendors_invoice_payments_list_model->get_one($id)->task_id;
        //$project_id = $this->Tasks_model->get_one($task_id)->project_id;

        //$this->init_project_permission_checker($project_id);

      /*  if (!$this->can_edit_tasks()) {
            redirect("forbidden");
        } */

        $data = array(
            "is_checked" => $this->input->post('value')
        );

$save_id = $this->Vendors_invoice_payments_list_model->save($data, $id);

        if ($save_id) {
            $item_info = $this->Vendors_invoice_payments_list_model->get_one($save_id);
            echo json_encode(array("success" => true, "data" => $this->_make_checklist_item_row($item_info, "data"), 'id' => $save_id));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    function save_checklist_items_sort() {
        $sort_values = $this->input->post("sort_values");
        if ($sort_values) {
            //extract the values from the comma separated string
            $sort_array = explode(",", $sort_values);

            //update the value in db
            foreach ($sort_array as $value) {
                $sort_item = explode("-", $value); //extract id and sort value

                $id = get_array_value($sort_item, 0);
                $sort = get_array_value($sort_item, 1);

                validate_numeric_value($id);

                $data = array("sort" => $sort);
            $this->Vendors_invoice_payments_list_model->save($data, $id);
            }
        }
    }

    function delete_checklist_item($id) {

        $task_id = $this->Vendors_invoice_payments_list_model->get_one($id)->task_id;
        //$project_id = $this->Tasks_model->get_one($task_id)->project_id;

        //$this->init_project_permission_checker($project_id);

      /*  if ($id) {
            if (!$this->can_edit_tasks()) {
                redirect("forbidden");
            }
        } */

        if ($this->Vendors_invoice_payments_list_model->delete($id)) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false));
        }
    }


    function get_vendors_invoice_paid_suggestion() {
        $item = $this->Vendors_invoice_list_model->get_vendors_invoice_paid_amount_suggestion($this->input->post("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    function get_purchase_orderid() {

        $options = array("vendor_id" => $_REQUEST["vendor_member"] );
$list_data = $this->Vendors_invoice_list_model->get_details($options)->result();
if($list_data){
        $loan_items = array();
foreach ($list_data as $code) {
            $loan_items[] = $code->purchase_order_id;
        }
$aa=json_encode($loan_items);
$vv=str_ireplace("[","(",$aa);
$loan_voucher_no=str_ireplace("]",")",$vv);
       
}else{
    $loan_voucher_no="('empty')";
}
        $itemss = $this->Vendors_invoice_list_model->get_purchase_orderid($this->input->post("vendor_member"),$loan_voucher_no);
         $suggestions = array();
      foreach ($itemss as $items) {
           $suggestions[] = array("id" => $items->id, "text" => $items->purchase_no?$items->purchase_no:get_purchase_order_id($items->id)/*.'['.$items->title.']'*/);
       }
        echo json_encode($suggestions);
    }


     /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for ticket */

    function validate_vendor_file() {
        return validate_post_file($this->input->post("file_name"));
    }

 
}

/* End of file taxes.php */
/* Location: ./application/controllers/taxes.php */