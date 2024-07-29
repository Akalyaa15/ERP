<?php

$statuses = $this->Vendors_invoice_status_model->get_details()->result();

             $status_dropdown = array(
                array("id" => "", "text" => "- " . lang("status") . " -")
            );

            foreach ($statuses as $status) {
                $status_dropdown[] = array("id" => $status->id, "text" => ( $status->key_name ? lang($status->key_name) : $status->title));
            }

        echo json_encode($status_dropdown);
?>