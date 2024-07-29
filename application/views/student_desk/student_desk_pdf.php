<!--div style=" margin: auto;"-->

<?php
if($student_desk_info->state) {
$options = array(
            "id" =>$student_desk_info->state,
                   );
        $state_data = $this->States_model->get_details($options)->row();
    }
        ?>

<?php 
$optionss = array(
            "id" =>$student_desk_info->vap_category,
                   );
        $vap_category_data = $this->Vap_category_model->get_details($optionss)->row();
        ?> 
<?php 
$options_country = array(
            "id" => $student_desk_info->country,
                   );
      $student_country = $this->Countries_model->get_details($options_country)->row();
        ?>

               
<?php
    $color = get_setting("payslip_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $style = get_setting("payslip_style");
    ?>
    <?php
    $data = array(
        
        "color" => $color,
        "student_desk_info" => $student_desk_info
    );
    if ($style === "style_2") {
        $this->load->view('student_desk/student_desk_parts/header_style_2.php', $data);
    } else {
        $this->load->view('student_desk/student_desk_parts/header_style_1.php', $data);
    }
    ?>
 <table>   
<h3 style="text-align: center ;font-size: 20px,color:black ,font-weight:bold">Registration Form </h3>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("name") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $student_desk_info->name." ".$student_desk_info->last_name;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("college_name") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $student_desk_info->college_name;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("department") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->department;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:5px;"><p style="color:black ;"><?php echo lang("year_of_passed_out")." " . ": " .$student_desk_info->year; ?></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("parent_name") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $student_desk_info->parent_name;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"></p></td></tr>
<!-- <tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("parent_name") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->parent_name;?></p></td>
<td style="width:40%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;"></p></td></tr> -->
<tr><td style="width:25%;font-size:16px;
text-align: left;height:70px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("communication_address") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $student_desk_info->communication_address;?><br></p></td>
</tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("pincode") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->pincode;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo lang("country")." " . ": " .$student_country->countryName; ?></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("district") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->district;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo lang("state")." " . ": " .$state_data->title; ?></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("phone") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->phone;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo lang("alternative_phone")." " . ": " .$student_desk_info->alternative_phone; ?></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("email") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $student_desk_info->email;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("date_of_birth") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->dob;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo lang("gender")." " . ": " .$student_desk_info->gender; ?></p></td></tr>
<!-- <tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("vap_category") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$vap_category_data->title;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo lang("program_title")." " . ": " .$student_desk_info->program_title; ?></p></td></tr> -->
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("vap_category") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $vap_category_data->title;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("program_title") ?>
         </p></td><td style="width:1%;font-size:16px;
text-align: left;height:40px !important;padding-top:10px; border-right-color: white;border-left-color: white;"><p style="color:black ;">:
         </p></td><td style="width:74%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo $student_desk_info->program_title;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:50px !important;padding-top:10px; "><p style="color:black ;"><?php echo lang("duration_of_course") ?>
         </p></td><td style="width:35%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php echo ":"." ".$student_desk_info->start_date.""." to ".$student_desk_info->end_date;?></p></td>
<td style="width:40%;font-size:16px;text-align:left;padding-top:10px;"><p style="color:black ;"><?php 
            $end_time = is_date_exists($student_desk_info->end_time) ? $student_desk_info->end_time : "";
            $start_time = is_date_exists($student_desk_info->start_time) ? $student_desk_info->start_time : "";

            if ($time_format_24_hours) {
                $end_time = $end_time ? date("H:i", strtotime($end_time)) : "";
                $start_time = $start_time ? date("H:i", strtotime($start_time)) : "";
            } else {
                $end_time = $end_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($end_time))) : "";
                 $start_time = $start_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($start_time))) : "";
            } 

            echo lang("timing")." " . ": " .$start_time." to ".$end_time; ?></p></td></tr>



</table>
<!-- <table >
<tbody>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Name:<?php echo $student_desk_info->name." ".$student_desk_info->last_name; ?>
         </p></td><td style="width:35%;font-size:16px;text-align:center;padding-top:10px;"><p style="color:black ;">&nbsp;Name:<?php echo $student_desk_info->parent_name; ?><br></p></td>
<td style="width:40%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;For Gemicates Technologies Pvt Ltd <br></p></td></tr>
<tr><td style="width:25%;font-size:16px;
text-align: left;height:35px;padding-top:10px; "><p style="color:black ;">Student Signature
         </p></td><td style="width:35%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Parent / Guardian Signature<br></p></td>
<td style="width:40%;font-size:16px;text-align:right;padding-top:10px;"><p style="color:black ;">&nbsp;Authorised Signature <br></p></td></tr>
</tbody></table--> 





   
<!--div><span><?php echo lang("name") . ": " .$student_desk_info->name; ?></span></div>
<div><span><?php echo lang("department") . ": " .$student_desk_info->department; ?></span></div>
<div><span><?php echo lang("college_name") . ": " .$student_desk_info->college_name; ?></span></div>
<div><span><?php echo lang("address") . ": " .$student_desk_info->address; ?></span></div>
<div><span><?php echo lang("pincode") . ": " .$student_desk_info->pincode; ?></span></div>
<div><span><?php echo lang("district") . ": " .$student_desk_info->district; ?></span></div>
<div><span><?php echo lang("state") . ": " .$student_desk_info->state; ?></span></div>
<div><span><?php echo lang("phone") . ": " .$student_desk_info->phone; ?></span></div>
<div><span><?php echo lang("email") . ": " .$student_desk_info->email; ?></span></div>
<div><span><?php echo lang("date_of_birth") . ": " .$student_desk_info->dob; ?></span></div>
<div><span><?php echo lang("gender") . ": " .$student_desk_info->gender; ?></span></div>
<div><span><?php echo lang("category") . ": " .$student_desk_info->category; ?></span></div>
<div><span><?php echo lang("timing") . ": " .$student_desk_info->timing; ?></span></div>
<div><span><?php echo lang("studying_course") . ": " .$student_desk_info->study_course; ?></span></div--> 