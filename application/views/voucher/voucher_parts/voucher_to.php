<table>
    <tr style="border: 1px solid #666; text-align: left; padding: 5px;">
        <td colspan="2" style="border: 1px solid #dddddd; color: #666; font-size:14px; text-align: left; padding: 5px; height:145px;">
            <strong style="font-weight: bold; color:black;"><?php echo lang("voucher_to"); ?></strong><br>
            <div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"></div>
            <div style="line-height: 3px;"></div>
            <?php
           // Check if $estimate_items is set and is an array
           if (isset($estimate_items) && is_array($estimate_items) && !empty($estimate_items)) {
            foreach ($estimate_items as $item) {
            // Check if necessary properties are set
             if (isset($item->receiver_client_country, $item->receiver_vendor_country, $item->r_member_type)) {
            // Fetch country details
            $client_countries = $this->Countries_model->get_one($item->receiver_client_country);
            $vendor_countries = $this->Countries_model->get_one($item->receiver_vendor_country);
             // Display data based on member type
            switch ($item->r_member_type) {
                case 'tm':
                case 'om':
                    ?>
                    <strong><?php echo "Name: " . htmlspecialchars($item->r_linked_user_name); ?></strong><br>
                    <strong><?php echo "Employee ID: " . htmlspecialchars($item->r_employee_id); ?></strong><br>
                    <strong><?php echo "Designation: " . htmlspecialchars($item->r_job_title); ?></strong><br>
                    <?php
                    break;
                case 'others':
                    ?>
                    <strong><?php echo "Name: " . htmlspecialchars($item->r_f_name . " " . $item->r_l_name); ?></strong><br>
                    <strong><?php echo "Address: " . htmlspecialchars($item->r_address); ?></strong><br>
                    <strong><?php echo "Contact: " . htmlspecialchars($item->r_phone); ?></strong><br>
                    <?php
                    break;
                case 'clients':
                    ?>
                    <strong><?php echo htmlspecialchars($item->r_rep); ?></strong><br>
                    <strong><?php echo htmlspecialchars($item->receiver_client_name); ?></strong><br>
                    <strong><?php echo htmlspecialchars($item->receiver_client_address); ?></strong><br>
                    <?php if ($item->receiver_client_city && $client_countries->numberCode == '356') { ?>
                        <strong><?php echo htmlspecialchars($item->receiver_client_city . '-' . $item->receiver_client_pincode . ','); ?></strong><br>
                    <?php } elseif ($item->receiver_client_city) { ?>
                        <strong><?php echo htmlspecialchars($item->receiver_client_city . ','); ?></strong><br>
                    <?php } ?>
                    <?php if ($client_countries->countryName) { ?>
                        <strong><?php echo htmlspecialchars($client_countries->countryName . '.'); ?></strong><br>
                    <?php } ?>
                    <?php
                    break;
                case 'vendors':
                    ?>
                    <strong><?php echo htmlspecialchars($item->r_rep); ?></strong><br>
                    <strong><?php echo htmlspecialchars($item->receiver_vendor_name); ?></strong><br>
                    <strong><?php echo htmlspecialchars($item->receiver_vendor_address); ?></strong><br>
                    <?php if ($item->receiver_vendor_city && $vendor_countries->numberCode == '356') { ?>
                        <strong><?php echo htmlspecialchars($item->receiver_vendor_city . '-' . $item->receiver_vendor_pincode . ','); ?></strong><br>
                    <?php } elseif ($item->receiver_vendor_city) { ?>
                        <strong><?php echo htmlspecialchars($item->receiver_vendor_city . ','); ?></strong><br>
                    <?php } ?>
                    <?php if ($vendor_countries->countryName) { ?>
                        <strong><?php echo htmlspecialchars($vendor_countries->countryName . '.'); ?></strong><br>
                    <?php } ?>
                    <?php
                    break;
                default:
                    echo "Error: Unknown member type.";
                    log_message('error', 'Unknown member type: ' . htmlspecialchars($item->r_member_type));
            }
        } else {
            echo "Error: Missing properties in estimate item.";
            log_message('error', 'Missing properties in estimate item. Item details: ' . print_r($item, true));
        }
    }
} else {
    echo "Error: No estimate items available.";
    log_message('error', 'No estimate items available.');
}
?>
</table>