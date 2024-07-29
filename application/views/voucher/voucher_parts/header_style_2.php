<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;"><?php
            $data = array(
                "estimate_items" => $estimate_items,
                "color" => $color,
                "estimate_info" => $estimate_info
            );
            $this->load->view('voucher/voucher_parts/voucher_info', $data);
            ?>
        </td>
        <!--td style="width: 20%;">
        </td-->
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('voucher/voucher_parts/company_logo'); ?>
        </td>
    </tr>
    <!--tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr-->
    <tr>
        <td><?php
            $this->load->view('voucher/voucher_parts/voucher_to', $data);
            ?>
        </td>
        <!--td></td-->
        <td><?php
            $this->load->view('voucher/voucher_parts/voucher_from', $data);
            ?>
        </td>
    </tr>
</table>