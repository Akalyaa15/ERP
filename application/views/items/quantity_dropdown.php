<?php

$quantity_dropdown = array(
    array("id" => "", "text" => "- " . lang("quantity") . " -"),
    array("id" => "0", "text" => "0"),
    array("id" => "10", "text" =>"1 to 10"),
    array("id" => "30", "text" => "11 to 30"),
    array("id" => "50", "text" => "31 to 50"),
    array("id" => "51", "text" => "More than 50"),
     array("id" => "101", "text" => "More than 100")
      );
echo json_encode($quantity_dropdown);
