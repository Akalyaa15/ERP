<table>
    <tr style="border: 1px solid #666; text-align: left; padding: 5px;">
        <td colspan="2" style="border: 1px solid #dddddd; color: #666; font-size: 14px; text-align: left; padding: 5px; height: 145px;">
            <?php
            $company_address = nl2br(get_setting("company_address"));
            $company_phone = get_setting("company_phone");
            $company_website = get_setting("company_website");
            ?>
            <div style="font-weight: bold; color: black;">
                <strong><?php echo get_setting("company_name"); ?></strong>
            </div>
            <div style="line-height: 3px;"></div>
            <span class="invoice-meta" style="font-size: 90%; color: #666;">
                <?php
                if ($company_address) {
                    echo $company_address;
                }
                if ($company_phone) {
                    echo "<br />" . lang("phone") . ": " . $company_phone;
                }
                if ($company_website) {
                    echo "<br />" . lang("website") . ": <a style='color:#666; text-decoration: none;' href='" . $company_website . "'>" . $company_website . "</a>";
                }
                ?>
            </span>
        </td>
    </tr>
</table>