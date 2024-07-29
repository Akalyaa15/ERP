<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('payslip/payslip_parts/company_logo'); ?>
        </td>
        <!--td style="width: 20%;">
        </td-->
        <td style="width: 50%; vertical-align: top; text-align: right"><?php
            $data = array(
              // "job_info" => $job_info,
                "color" => $color,
                "payslip_info" => $payslip_info
            );
            $this->load->view('payslip/payslip_parts/payslip_info', $data);
            ?>
        </td>
    </tr>
    
    <tr>
        <td><?php
            $this->load->view('payslip/payslip_parts/payslip_from', $data);
            ?>
        </td>
        <!--td></td-->
        <td><?php
            $this->load->view('payslip/payslip_parts/payslip_to', $data);
            ?>
        </td>
    </tr>
</table>