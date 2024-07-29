<?php

$estimate_statuses_dropdown = array(
    array("id" => "", "text" => "- " . lang("status") . " -"),
    array("id" => "draft", "text" => lang("draft")),
    array("id" => "given", "text" => lang("given")),
    array("id" => "received", "text" => lang("received")),
    array("id" => "sold", "text" => lang("sold")),
    array("id" => "approve_ret_sold", "text" => lang("approve_ret_sold")),
    array("id" => "invoice_created", "text" => lang("invoice_created"))
);
echo json_encode($estimate_statuses_dropdown);
