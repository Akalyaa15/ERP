<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('delivery/delivery_parts/company_logo'); ?>
        </td>
        <!--td style="width: 10%;">
        </td-->
        <td style="width: 50%; vertical-align: top; text-align: right"><?php
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "estimate_info" => $estimate_info
            );
            $this->load->view('delivery/delivery_parts/delivery_info', $data);
            ?>
        </td>
    </tr>
    <!--tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr-->
    <tr>
        <td><?php
            $this->load->view('delivery/delivery_parts/delivery_from', $data);
            ?>
        </td>
        <!--td></td-->
        <td><?php
            $this->load->view('delivery/delivery_parts/delivery_to', $data);
            ?>
        </td>
    </tr>
</table>