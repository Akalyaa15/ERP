<table style="color: #444; width: 100%;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
           <?php $this->load->view('purchase_orders/purchase_order_parts/company_logo'); ?>
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right">
            <?php
            // Define $data array with required keys
            $data = array(
                "vendor_info" => $vendor_info,
                "color" => $color,
                "invoice_info" => isset($invoice_info) ? $invoice_info : null // Ensure $invoice_info is defined
            );
            $this->load->view('purchase_orders/purchase_order_parts/purchase_order_info', $data);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            // Pass the same $data array to both views
            $this->load->view('purchase_orders/purchase_order_parts/purchase_order_from', $data);
            ?>
        </td>
        <td>
            <?php
            // Pass the same $data array to both views
            $this->load->view('purchase_orders/purchase_order_parts/purchase_order_to', $data);
            ?>
        </td>
    </tr>
</table>
