<?php

/*$statuses = $this->Vendors_invoice_status_model->get_details()->result();

             $status_dropdown = array(
                array("id" => "", "text" => "- " . lang("status") . " -")
            );

            foreach ($statuses as $status) {
                $status_dropdown[] = array("id" => $status->id, "text" => ( $status->key_name ? lang($status->key_name) : $status->title));
            }

        echo json_encode($status_dropdown); */


$vendors_invoice_list_dropdown = array(
    array("id" => "", "text" => "- " . lang("status") . " -"),
   // array("id" => "overdue", "text" => lang("overdue")),
    //array("id" => "draft", "text" => lang("draft")),
    array("id" => "not_paid", "text" => lang("not_paid")),
    array("id" => "partially_paid", "text" => lang("partially_paid")),
    array("id" => "fully_paid", "text" => lang("fully_paid"))
);
echo json_encode($vendors_invoice_list_dropdown);

?>