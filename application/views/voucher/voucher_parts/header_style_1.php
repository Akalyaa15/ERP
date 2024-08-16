<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <?php $this->load->view('voucher/voucher_parts/company_logo'); ?>
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right">
            <?php
            // Debugging output
            echo '<pre>';
            echo 'client_info: ' . (isset($client_info) ? 'set' : 'not set') . '<br>';
            echo 'color: ' . (isset($color) ? 'set' : 'not set') . '<br>';
            echo 'estimate_info: ' . (isset($estimate_info) ? 'set' : 'not set') . '<br>';
            echo '</pre>';
           // Initialize data array
            $data = array();
            // Check for required variables and set them in the data array
            if (isset($client_info)) {
                $data["client_info"] = $client_info;
            } else {
                echo "Error: Missing client information.";
                log_message('error', 'Missing client information.');
            }

            if (isset($color)) {
                $data["color"] = $color;
            } else {
                echo "Error: Missing color information.";
                log_message('error', 'Missing color information.');
            }

            if (isset($estimate_info)) {
                $data["estimate_info"] = $estimate_info;
            } else {
                echo "Error: Missing estimate information.";
                log_message('error', 'Missing estimate information.');
            }

            // Load voucher info only if required data is available
            if (isset($client_info) && isset($color) && isset($estimate_info)) {
                $this->load->view('voucher/voucher_parts/voucher_info', $data);
            } else {
                echo "Error: Missing required information for voucher header.";
                log_message('error', 'Missing required information for voucher header. client_info: ' . (isset($client_info) ? 'set' : 'not set') . ', color: ' . (isset($color) ? 'set' : 'not set') . ', estimate_info: ' . (isset($estimate_info) ? 'set' : 'not set'));
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            // Ensure $data is properly set before using it
            if (isset($client_info) && isset($color) && isset($data)) {
                // Load the voucher_from view
                $this->load->view('voucher/voucher_parts/voucher_from', $data);
            } else {
                echo "Error: Missing data for voucher_from section.";
                log_message('error', 'Missing data for voucher_from section. client_info: ' . (isset($client_info) ? 'set' : 'not set') . ', color: ' . (isset($color) ? 'set' : 'not set') . ', data: ' . (isset($data) ? 'set' : 'not set'));
            }
            ?>
        </td>
        <td>
            <?php
            // Load voucher_to section
            if (isset($client_info)) {
                $this->load->view('voucher/voucher_parts/voucher_to', $data);
            } else {
                echo "Error: Missing data for voucher_to section.";
                log_message('error', 'Missing data for voucher_to section. client_info: ' . (isset($client_info) ? 'set' : 'not set'));
            }
            ?>
        </td>
    </tr>
</table>
