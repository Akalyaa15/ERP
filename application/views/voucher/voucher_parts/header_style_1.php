<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('voucher/voucher_parts/company_logo'); ?>
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right">
            <?php
            // Check if $client_info is set, if not, initialize it as an empty array
            $client_info = isset($client_info) ? $client_info : array();
            
            $data = array(
                "client_info" => $client_info,
                "color" => isset($color) ? $color : '',
                "estimate_info" => isset($estimate_info) ? $estimate_info : array()
            );
            $this->load->view('voucher/voucher_parts/voucher_info', $data);
            ?>
        </td>
    </tr>
    <tr>
        <td><?php
            $this->load->view('voucher/voucher_parts/voucher_from', $data);
        ?>
        </td>
        <td><?php
            $this->load->view('voucher/voucher_parts/voucher_to', $data);
        ?>
        </td>
    </tr>
</table>