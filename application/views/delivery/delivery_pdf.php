<span style="padding-left: 43% !important;text-align:center;font-size: 20px;">DELIVERY CHALLAN</span>
<div style=" margin: auto;">
    <?php
    $color = get_setting("delivery_color") ?: "#2AA384";
    $style = get_setting("delivery_style");
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "estimate_info" => $estimate_info
    );

    $this->load->view('delivery/delivery_parts/header_' . ($style === "style_2" ? 'style_2' : 'style_1') . '.php', $data);
    ?>
</div>
<br />
<table style="width: 100%; color: #444;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;">
        <th style="text-align: center;width: 10%; border-right: 1px solid #eee;"> <?php echo lang("s.no"); ?> </th>
        <th style="width: 25%; border-right: 1px solid #eee;"> <?php echo lang("item"); ?> </th>
        <th style="width: 10%; border-right: 1px solid #eee;"> <?php echo lang("category"); ?> </th>
        <th style="text-align: center; width: 15%; border-right: 1px solid #eee;"> <?php echo lang("make"); ?> </th>
        <th style="text-align: center; width: 10%; border-right: 1px solid #eee;"> <?php echo lang("quantity"); ?></th>
        <th style="text-align: center; width: 15%; border-right: 1px solid #eee;"> <?php echo lang("rate"); ?></th>
        <th style="text-align: center; width: 15%; border-right: 1px solid #eee;"> <?php echo lang("total"); ?></th>
    </tr>
    <?php
    $counter = 0;
    foreach ($estimate_items as $item) {
        // Fetch category name and handle potential errors
        $category = $this->Product_categories_model->get_one($item->category);
        $category_name = $category->title ?: "-";

        // Fetch make name and handle potential errors
        $make = $this->Manufacturer_model->get_one($item->make);
        $make_name = $make->title ?: "-";

        // Calculate the total price
        $total = $item->quantity * $item->rate;
        ?>
        <tr style="background-color: #f4f4f4;">
            <td style="width: 10%; border: 1px solid #fff;text-align: center; padding: 10px;"><?php echo ++$counter; ?></td>
            <td style="width: 25%; border: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo nl2br($item->description); ?></span>
            </td>
            <td style="text-align: center; width: 10%; border: 1px solid #fff;"><?php echo $category_name; ?></td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;"><?php echo $make_name; ?></td>
            <td style="text-align: center; width: 10%; border: 1px solid #fff;"><?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;">
                <?php echo $item->price_visibility === 'yes' ? to_currency($item->rate, $item->currency_symbol) : '-'; ?>
            </td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;">
                <?php echo $item->price_visibility === 'yes' ? to_currency($total, $item->currency_symbol) : '-'; ?>
            </td>
        </tr>
    <?php } ?>
</table>

<p class="b-t b-info pt10 m15"></p>

<table style="width:110%;padding-top: 200px;">
    <tr>
        <th style="font-size: 18px;color:white">Receiver's Signature</th>
        <th style="font-size: 18px;color:white">Signature:</th>
        <th style="font-size: 18px;color:white">Authorized Signature</th>
    </tr>
</table>
