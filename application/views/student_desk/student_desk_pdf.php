<?php
// Define the variables before loading the view
$state_data = null;
$vap_category_data = null;
$student_country = null;

if($student_desk_info->state) {
    $options = array(
        "id" => $student_desk_info->state,
    );
    $state_data = $this->States_model->get_details($options)->row();
}

$optionss = array(
    "id" => $student_desk_info->vap_category,
);
$vap_category_data = $this->Vap_category_model->get_details($optionss)->row();

$options_country = array(
    "id" => $student_desk_info->country,
);
$student_country = $this->Countries_model->get_details($options_country)->row();

$color = get_setting("payslip_color");
if (!$color) {
    $color = "#2AA384";
}

$style = get_setting("payslip_style");

// Define the time format variable
$time_format_24_hours = true; // or false depending on your application setting

$data = array(
    "color" => $color,
    "student_desk_info" => $student_desk_info,
    "state_data" => $state_data,
    "vap_category_data" => $vap_category_data,
    "student_country" => $student_country,
    "time_format_24_hours" => $time_format_24_hours
);

if ($style === "style_2") {
    $this->load->view('student_desk/student_desk_parts/header_style_2.php', $data);
} else {
    $this->load->view('student_desk/student_desk_parts/header_style_1.php', $data);
}
?>

<h3 style="text-align: center; font-size: 20px; color: black; font-weight: bold">Registration Form</h3>
<table>
    <tr>
        <td style="width: 25%; font-size: 16px; text-align: left; height: 50px; padding-top: 10px;">
            <p style="color: black;"><?php echo lang("name"); ?></p>
        </td>
        <td style="width: 1%; font-size: 16px; text-align: left; height: 40px; padding-top: 10px; border-right-color: white; border-left-color: white;">
            <p style="color: black;">:</p>
        </td>
        <td style="width: 74%; font-size: 16px; text-align: left; padding-top: 10px;">
            <p style="color: black;"><?php echo $student_desk_info->name . " " . $student_desk_info->last_name; ?></p>
        </td>
    </tr>
    <!-- Add more rows as needed -->
    <tr>
        <td style="width: 25%; font-size: 16px; text-align: left; height: 50px; padding-top: 10px;">
            <p style="color: black;"><?php echo lang("duration_of_course"); ?></p>
        </td>
        <td style="width: 35%; font-size: 16px; text-align: left; padding-top: 10px;">
            <p style="color: black;"><?php echo ":" . " " . $student_desk_info->start_date . " to " . $student_desk_info->end_date; ?></p>
        </td>
        <td style="width: 40%; font-size: 16px; text-align: left; padding-top: 10px;">
            <p style="color: black;">
                <?php
                $end_time = is_date_exists($student_desk_info->end_time) ? $student_desk_info->end_time : "";
                $start_time = is_date_exists($student_desk_info->start_time) ? $student_desk_info->start_time : "";

                if ($time_format_24_hours) {
                    $end_time = $end_time ? date("H:i", strtotime($end_time)) : "";
                    $start_time = $start_time ? date("H:i", strtotime($start_time)) : "";
                } else {
                    $end_time = $end_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($end_time))) : "";
                    $start_time = $start_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($start_time))) : "";
                }

                echo lang("timing") . " " . ": " . $start_time . " to " . $end_time;
                ?>
            </p>
        </td>
    </tr>
</table>
