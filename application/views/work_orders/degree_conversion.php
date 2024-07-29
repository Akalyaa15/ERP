<?php 
 $vars = explode(".",'80.2117664');
    $deg = $vars[0];
    $tempma = "0.".$vars[1];

    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = $tempma - ($min*60);
    echo  array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
 ?>