<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
           <?php $this->load->view('estimates/estimate_parts/company_logo'); ?>
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right">
            <?php
            // Ensure $client_info, $color, and $invoice_info are defined and passed to the view
            $data = array(
                "client_info" => isset($client_info) ? $client_info : null,
                "color" => isset($color) ? $color : '#000', // Default color
                "invoice_info" => isset($invoice_info) ? $invoice_info : null
            );
            $this->load->view('estimates/estimate_parts/estimate_info', $data);
            ?>
        </td>
    </tr>
    
    <tr>
        <td>
            <?php
            $this->load->view('estimates/estimate_parts/estimate_from', $data);
            ?>
        </td>
        <td>
            <?php
            $this->load->view('estimates/estimate_parts/estimate_to', $data);
            ?>
        </td>
    </tr>
</table>
