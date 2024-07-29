<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('student_desk/student_desk_parts/company_logo'); ?>
        </td>
        <!--td style="width: 20%;">
        </td-->
        <td style="width: 50%; vertical-align: top; text-align: right"><?php
            $data = array(
              // "job_info" => $job_info,
                "color" => $color,
                "student_desk_info" => $student_desk_info
            );
            
            $this->load->view('student_desk/student_desk_parts/student_desk_info', $data);
            ?>
        </td>
    </tr>
    
    
</table>