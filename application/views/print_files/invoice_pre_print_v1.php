<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print  <?php echo $invoice['invoice_number'] ?></title>
    <style>
        body {
            color: #2B2000;
            /* font-family: 'Courier New', monospace; */
            font-family: 'Helvetica';
        }

        .invoice-box {
            border: 0;
            font-size: 12pt;
            line-height: 14pt;
            color: #000;
        }
        .invoice-box h3
        {
            font-weight:600;
            text-align:center;
        }

        .maintable {
            width: 100%;
            margin-top:30px;
        }
        .maintable td, th {
            text-align: left;
            padding: 6px;
        }

        .toptable {
            width: 100%;
            border: 1px solid #ddd;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        
        }
        .heading1 td {
            border-bottom: 1px dotted #ccc;
        }   
        .toptable td {
            padding: 10px;
            vertical-align: top;
        }

        .toptable tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .toptable td strong {
            color: #333;
        }
        .footer-titles{
            text-align: right; padding: 8px; width: 62%;
        }
        .footer-titles-values
        {
            text-align: right; padding: 8px; width: 15%;
        }
        .goods-text
        {
            font-size:12px;
        }
    </style>
</head>
<body >
    <div class="invoice-box">
        <h3>INVOICE</h3>
        <table class="main-table" style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
            <tr>
                <!-- Customer Details -->
                <td style="width: 45%; vertical-align: middle; font-size:12px;">
                    <table style="width: 100%;">
                        <tr>
                            <td><?= $this->lang->line('Customer Name'); ?> : </td>
                            <td><?= $invoice['name'] ?></td>
                        </tr>
                        <tr>
                            <td><?= $this->lang->line('Phone'); ?> : </td>
                            <td><?= $invoice['phone'] ?></td>
                        </tr>
                        <tr>
                            <td><?= $this->lang->line('Email'); ?> : </td>
                            <td><?= $invoice['email'] ?></td>
                        </tr>
                    </table>
                </td>

                <!-- Invoice Details -->
                <td style="width: 55%;  vertical-align: middle; text-align: right;">
                    <table style="width: 100%;">
                        <tr>
                            <td>Invoice No : </td>
                            <td><?= $invoice['invoice_number'].$invoice['invoice_number'] ?></td>
                        </tr>
                        <tr>
                            <td>Invoice Date : </td>
                            <td><?= dateformat($invoice['invoicedate']); ?></td>
                        </tr>
                        <tr>
                            <td>Due Date : </td>
                            <td><?= dateformat($invoice['invoiceduedate']); ?></td>
                        </tr>
                        <!-- <tr>
                            <td>Currency : </td>
                            <td><?= currency($this->aauth->get_user()->loc) ?></td>
                        </tr> -->
                    </table>
                </td>
            </tr>
        </table>

        <table class="plist maintable" cellpadding="0" cellspacing="0">
        <tr class="heading1" style="border-bottom:1px dotted #ccc;">
            <td style="width: 1rem;">
                Sl.No
            </td>
            <td>
                <?php echo $this->lang->line('Descriptions') ?>
            </td> 
            <td>
                <?php echo $this->lang->line('UoM') ?>
            </td> 
            <td align="center">
                <?php echo $this->lang->line('Qty') ?>
            </td>
            <td align="right">
                <?php echo $this->lang->line('Unit Price') ?>
            </td>
            <?php if ($invoice['tax'] > 0) echo '<td style="text-align:center;">' . $this->lang->line('Tax') . '</td>';
            if ($invoice['discount'] > 0) echo '<td style="text-align:center;">' . $this->lang->line('Discount') . '</td>'; ?>
            <td align="right">
                <?php echo $this->lang->line('Total') ?>
            </td>
        </tr>
        <?php
        $fill = true;
        $sub_t = 0;
        $sub_t_col = 3;
        $n = 1;
        $granddiscount = 0;
        $producttotal=0;
        foreach ($products as $row) {
            $cols = 4;
            if ($fill == true) {
                $flag = ' mfill';
            } else {
                $flag = '';
            }
            $sub_t += $row['price'] * $row['qty'];
            $granddiscount = $granddiscount + $row['totaldiscount'];
            $grandtotal = $grandtotal + $row['subtotal'];
            $producttotal += $row['price'] * $row['qty'];
            if ($row['serial']) $row['product_des'] .= ' - ' . $row['serial'];
            echo '<tr class="item' . $flag . '">  <td style="text-align:center">' . $n . '</td>';
            echo '<td>' . $row['product_code'] .' | ' . $row['product_name'] . '<div style="float: right;">'.$row['arabic_name'].'</div></td>';
            echo '<td>' . $row['unit'] . '</td>';
            echo '<td style="width:12%;text-align:center;" >' . +$row['qty']  . '</td>   ';
            echo '<td style="width:12%; text-align:right;">' . number_format($row['price'],2) . '</td>';
            if ($invoice['tax'] > 0) {
                $cols++;
                echo '<td style="width:16%;">' . $row['totaltax'] . ' <span class="tax">(' . amountFormat_s($row['tax']) . '%)</span></td>';
            }
            if ($invoice['discount'] > 0) {
                $cols++;
                echo ' <td style="width:12%;text-align:center;">' . number_format($row['totaldiscount'],2) . '</td>';
            }
            echo '<td style="text-align:right;">' . number_format($row['subtotal'],2) . '</td></tr>';

            //erp2024 removed section 06-06-2024
            // if ($row['product_des']) {
            //     $cc = $cols++;

            //     echo '<tr class="item' . $flag . ' descr">  <td> </td><td colspan="' . $cc . '">' . $row['product_des'] . '&nbsp;</td></tr>';
            // }
             //erp2024 removed section 06-06-2024
            if (CUSTOM) {
                $p_custom_fields = $this->custom->view_fields_data($row['pid'], 4, 1);

                if (is_array($p_custom_fields[0])) {
                    $z_custom_fields = '';

                    foreach ($p_custom_fields as $row) {
                        $z_custom_fields .= $row['name'] . ': ' . $row['data'] . '<br>';
                    }

                    echo '<tr class="item' . $flag . ' descr">  <td> </td>
                            <td colspan="' . $cc . '">' . $z_custom_fields . '&nbsp;</td>
							
                        </tr>';
                }
            }
            $fill = !$fill;
            $n++;
        }

        if ($invoice['shipping'] > 0) {

            $sub_t_col++;
        }
        if ($invoice['tax'] > 0) {
            $sub_t_col++;
        }
        if ($invoice['discount'] > 0) {
            $sub_t_col++;
        }
        ?>


    </table>
    
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td class="footer-titles">
                <?php echo $this->lang->line('SubTotal') ?>: 
            </td>
            <td class="footer-titles-values">
                <?php echo currency($this->aauth->get_user()->loc)." ".number_format($producttotal, 2); ?>
            </td>
        </tr>
        
    </table>

    <table class="subtotal" style="width:100%">




<?php 
if ($invoice['tax'] > 0) {
    echo '<tr>
    <td  class="footer-titles"> ' . $this->lang->line('Total Tax') . ': </td>
    <td class="footer-titles-values">' . currency($this->aauth->get_user()->loc)." ".number_format($invoice['tax'],2) . '</td>
</tr>';
}
if ($invoice['discount'] > 0) {
    echo '<tr>
    <td  class="footer-titles">' . $this->lang->line('Total Discount') . ': </td>
    <td class="footer-titles-values">' . currency($this->aauth->get_user()->loc)." ".number_format($granddiscount,2) . '</td>
</tr>';
}
if ($invoice['order_discount'] > 0) {
    echo '<tr>
    <td  class="footer-titles">' . $this->lang->line('Order Discount') . ': </td>
    <td class="footer-titles-values">' . currency($this->aauth->get_user()->loc)." ".number_format($invoice['order_discount'],2) . '</td>
</tr>';
}
if ($invoice['shipping'] > 0) {
    echo '<tr>';
    echo '<td  class="footer-titles">' . $this->lang->line('Shipping') . ': </td>
    <td class="footer-titles-values">' . currency($this->aauth->get_user()->loc)." ".number_format($invoice['shipping'],2) . '</td>
</tr>';
}
// else{
//     echo '<tr>';
//     echo '<td  class="footer-titles">' . $this->lang->line('Shipping') . '</td>
//     <td class="footer-titles-values">' . number_format($invoice['shipping'],2) . '</td>
// </tr>';
// }
?>
<tr>
    <td class="footer-titles"><?php echo $this->lang->line('Total') ?>: </td>
    <td class="footer-titles-values"><strong><?php $rming = $invoice['total'] - $invoice['pamnt'];
        if ($rming < 0) {
            $rming = 0;
        }
        if (@$round_off['other']) {
            $rming = round($rming, $round_off['active'], constant($round_off['other']));
        }
        echo currency($this->aauth->get_user()->loc)." ".number_format($rming,2);
        echo '</strong></td>
</tr>
</table>';
?>

<p class="goods-text">Goods return or exchange will be acceptable with in 15 days from the invoice date</p>
    </div>
</body>
</html>