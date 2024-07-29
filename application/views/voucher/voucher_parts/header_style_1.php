<?php if (isset($client_info)) : ?>
    <table style="color: #444; width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <?php $this->load->view('voucher/voucher_parts/company_logo'); ?>
            </td>
            <!--td style="width: 20%;">
            </td-->
            <td style="width: 50%; vertical-align: top; text-align: right">
                <?php
                $data = array(
                    "client_info" => $client_info,
                    "color" => isset($color) ? $color : '', // Provide default value if $color is not set
                    "estimate_info" => isset($estimate_info) ? $estimate_info : '', // Provide default value if $estimate_info is not set
                );
                $this->load->view('voucher/voucher_parts/voucher_info', $data);
                ?>
            </td>
        </tr>
        <!--tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr-->
        <tr>
            <td>
                <?php
                $this->load->view('voucher/voucher_parts/voucher_from', $data);
                ?>
            </td>
            <!--td></td-->
            <td>
                <?php
                $this->load->view('voucher/voucher_parts/voucher_to', $data);
                ?>
            </td>
        </tr>
    </table>
<?php else : ?>
    <p>Error: Client information is missing.</p>
<?php endif; ?>
