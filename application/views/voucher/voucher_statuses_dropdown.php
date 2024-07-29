<?php

$estimate_statuses_dropdown = array(
    array("id" => "", "text" => "- " . lang("status") . " -"),
    array("id" => "draft", "text" => lang("draft")),
    array("id" => "accepted", "text" => lang("accepted")),
    array("id" => "applied", "text" => lang("applied")),
    array("id" => "verified_by_manager", "text" => lang("verified_by_manager")),
    array("id" => "rejected_by_manager", "text" => lang("rejected_by_manager")),
    array("id" => "approved_by_accounts", "text" => lang("approved_by_accounts")),
    array("id" => "rejected_by_accounts", "text" => lang("rejected_by_accounts")),
    array("id" => "payment_in_progress", "text" => lang("payment_in_progress")),
    array("id" => "payment_hold", "text" => lang("payment_hold")),
    array("id" => "payment_done", "text" => lang("payment_done")),
     array("id" => "payment_received", "text" => lang("payment_received")),
    array("id" => "closed", "text" => lang("closed"))
       );
echo json_encode($estimate_statuses_dropdown);
