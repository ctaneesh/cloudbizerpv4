<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print  <?php echo $invoice['invoice_number'] ?></title>
    <style>
        body {
            color: #2B2000;
            font-family: 'Helvetica';
        }
/* 
        .invoice-box {
            width: 210mm;
            height: 297mm;
            margin: auto;
            padding: 4mm;
            border: 0;
            font-size: 12pt;
            line-height: 14pt;
            color: #000;
        }

        table {
            width: 100%;
            line-height: 16pt;
            text-align: left;
            border-collapse: collapse;
        }

        .plist tr td {
            line-height: 12pt;
        }

        .subtotal {
            page-break-inside: avoid;
        }

        .subtotal tr td {
            line-height: 10pt;
            padding: 6pt;
        }

        .subtotal tr td {
            border: 1px solid #ddd;
        }

        .sign {
            text-align: right;
            font-size: 10pt;
            margin-right: 110pt;
        }

        .sign1 {
            text-align: right;
            font-size: 10pt;
            margin-right: 90pt;
        }

        .sign2 {
            text-align: right;
            font-size: 10pt;
            margin-right: 115pt;
        }

        .sign3 {
            text-align: right;
            font-size: 10pt;
            margin-right: 115pt;
        }

        .terms {
            font-size: 9pt;
            line-height: 16pt;
            margin-right: 20pt;
        }

        .invoice-box table td {
            padding: 10pt 4pt 8pt 4pt;
            vertical-align: top;
        }

        .invoice-box table.top_sum td {
            padding: 0;
            font-size: 12pt;
        }

        .party tr td:nth-child(3) {
            text-align: center;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20pt;
        }

        table tr.top table td.title {
            font-size: 45pt;
            line-height: 45pt;
            color: #555;
        }

        table tr.information table td {
            padding-bottom: 20pt;
        }

        table tr.heading td {
            background: #515151;
            color: #FFF;
            padding: 6pt;
        }

        table tr.details td {
            padding-bottom: 20pt;
        }

        .invoice-box table tr.item td {
            border: 1px solid #ddd;
        }

        table tr.b_class td {
            border-bottom: 1px solid #ddd;
        }

        table tr.b_class.last td {
            border-bottom: none;
        }

        table tr.total td:nth-child(4) {
            border-top: 2px solid #fff;
            font-weight: bold;
        }
        table td.right-align {
            text-align: right;
        }

        .myco {
            width: 400pt;
        }

        .myco2 {
            width: 200pt;
        }

        .myw {
            width: 300pt;
            font-size: 14pt;
            line-height: 14pt;
        }

        .mfill {
            background-color: #eee;
        }

        .descr {
            font-size: 10pt;
            color: #515151;
        }

        .tax {
            font-size: 10px;
            color: #515151;
        }

        .t_center {
            text-align: right;
        }

        .party {
            border-top: #ccc 1px solid;

        }
        .maintable {
            border-collapse: collapse;
            width: 100%;
            }
        .maintable td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 6px;
            }

            .maintable tr:nth-child(even) {
            background-color: #f5f1f1;
            }
        .top_logo {
            max-height: 180px;
            max-width: 250px;
        <?php if(LTR=='rtl') echo 'margin-left: 200px;' ?>
        }

        .toptable {
            border-collapse: collapse;
            width: 100%;
            }
        .toptable td, th {
            text-align: left;
            padding: 6px;
            } */


    </style>
</head>
<body >
<div class="invoice-box">
    <table class="toptable">
    <thead>
        <tr>
            <?php
                if($invoice['invoicestatus']=='due')
                {
                    $reciept = 'INVOICE';
                }
                else{
                    $reciept = 'Payment Reciept';
                }   
                $reciept = 'INVOICE';
            ?>
             <td colspan="3"><h3><?=$reciept?></h3></td>
            </tr>
            <tr>
             <td>
                <?php
                    echo '<strong>' . $invoice['name'] . '</strong><br>';
                    if ($invoice['company']) echo $invoice['company'] . '<br>';
    
                    echo $invoice['address'] . '<br>' . $invoice['city'] . ', ' . $invoice['region'];
                    // if ($invoice['country']) echo '<br>' . $invoice['country'];
                    if ($invoice['postbox']) echo ' - ' . $invoice['postbox'];
                    if ($invoice['phone']) echo '<br>' . $this->lang->line('Phone') . ': ' . $invoice['phone'];
                    if ($invoice['email']) echo '<br> ' . $this->lang->line('Email') . ': ' . $invoice['email'];
                    if($refdetails['customer_reference_number'])
                    {
                        echo 'Your Reference No : ' . $refdetails['customer_reference_number'].'<br>';
                        echo 'Date : ' . date('d-m-Y',strtotime($refdetails['refdate']));
                    }
                ?>
             </td>
        
            <td style="text-align:right;"><strong>Invoice No : <?= $invoice['invoice_number']?></strong><br> <strong>Invoice Date : <?= date('d-m-Y', strtotime($invoice['invoicedate']))?></strong><br>
            <strong>Invoice Due Date : <?= date('d-m-Y', strtotime($invoice['invoiceduedate']))?></strong><br>
            <strong>Currency : <?=currency($this->aauth->get_user()->loc)?></strong>
        </td>
        </tr>
        </thead>
        <!-- <tr><td>Currency : <?php //echo "<b>".currency($this->aauth->get_user()->loc)."</b>"; ?></td></tr> -->
    </table>
    <table class="party">
        <thead>
        <tr class="heading">
            <!-- erp2024 modified secion 06-06-2024 -->
            <!-- <td> <?php echo $this->lang->line('Our Info') ?>:</td> -->
            <!-- <td><?= $general['person'] ?>:</td><td></td> -->
            <!-- erp2024 modified secion 06-06-2024  ends-->
        </tr>
        </thead>
        <tbody>
        <tr>
            <!-- erp2024 removed secion 06-06-2024 -->
            <!-- <td><strong><?php
             $loc = location($invoice['loc']);
                    echo $loc['cname']; ?></strong><br>
                <?php echo
                    $loc['address'] . '<br>' . $loc['city'] . ', ' . $loc['region'] . '<br>' . $loc['country'] . ' -  ' . $loc['postbox'] . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] . '<br> ' . $this->lang->line('Email') . ': ' . $loc['email'];
                if ($loc['tax_id']) echo '<br>' . $this->lang->line('TaxID') . ': ' . $loc['tax_id'];
                ?>
            </td> -->
             <!-- erp2024 removed secion 06-06-2024 ends -->
            <td>
                <?php 
                // echo '<strong>' . $invoice['name'] . '</strong><br>';
                // if ($invoice['company']) echo $invoice['company'] . '<br>';

                // echo $invoice['address'] . '<br>' . $invoice['city'] . ', ' . $invoice['region'];
                // // if ($invoice['country']) echo '<br>' . $invoice['country'];
                // if ($invoice['postbox']) echo ' - ' . $invoice['postbox'];
                // if ($invoice['phone']) echo '<br>' . $this->lang->line('Phone') . ': ' . $invoice['phone'];
                // if ($invoice['email']) echo '<br> ' . $this->lang->line('Email') . ': ' . $invoice['email'];
              
                // if($refdetails['customer_reference_number'])
                // {
                //     echo 'Your Reference No : ' . $refdetails['customer_reference_number'].'<br>';
                //     echo 'Date : ' . date('d-m-Y',strtotime($refdetails['refdate']));
                // }
                ?>
            </td>

            <td style="text-align:right">
            <?php 
                if($refdetails['delnote_number'])
                {
                     echo 'Delivery Note No : ' . $refdetails['delnote_number'].'<br>';
                     echo 'Date : ' . date('d-m-Y',strtotime($refdetails['created_date']));
                }
                ?>
            </td>


        </tr>
       
         <!-- erp2024 removed secion 06-06-2024 -->
        <?php if (@$invoice['shipping_name']) { ?>
            <!-- <tr>
                <td>
                    <?php echo '<strong>' . $this->lang->line('Shipping Address') . '</strong>:<br>';
                    echo $invoice['shipping_name'] . '<br>';
                    echo $invoice['shipping_address_1'] . '<br>' . $invoice['shipping_city'] . ', ' . $invoice['shipping_region'];
                    if ($invoice['shipping_country']) echo '<br>' . $invoice['shipping_country'];
                    // if ($invoice['shipping_postbox']) echo ' - ' . $invoice['shipping_postbox'];
                    if ($invoice['shipping_phone']) echo '<br>' . $this->lang->line('Phone') . ': ' . $invoice['shipping_phone'];
                    if ($invoice['shipping_email']) echo '<br> ' . $this->lang->line('Email') . ': ' . $invoice['shipping_email'];

                    ?>
                </td>
            </tr> -->
        <?php } ?>
         <!-- erp2024 removed secion 06-06-2024  ends-->
        </tbody>
    </table>
    <br>
    <table class="plist maintable" cellpadding="0" cellspacing="0">
        <tr class="heading1">
            <td style="width: 1rem;" style="text-align:center;">
                #
            </td>
            <td>
                <?php echo $this->lang->line('Item No') ?>
            </td>
            <td>
                <?php echo $this->lang->line('Descriptions') ?>
            </td> 
            <td class="right-align">
                <?php echo $this->lang->line('Price') ?>
            </td>
            <td style="text-align:center;">
                <?php echo $this->lang->line('Qty') ?>
            </td>
            <?php if ($invoice['tax'] > 0) echo '<td style="text-align:center;">' . $this->lang->line('Tax') . '</td>';
            if ($invoice['discount'] > 0) echo '<td style="text-align:center;">' . $this->lang->line('Discount') . '</td>'; ?>
            <td class="right-align">
                <?php echo $this->lang->line('SubTotal') ?>
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
            echo '<td>' . $row['product_code'] . '</td>';
            echo '<td>' . $row['product_name'] . '</td>';
            echo '<td style="width:12%; text-align:right;">' . number_format($row['price'],2) . '</td>';
            echo '<td style="width:12%;text-align:center;" >' . +$row['qty'] ." ". $row['unit'] . '</td>   ';
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
    <br> <?php if (is_array(@$i_custom_fields)) {

        foreach ($i_custom_fields as $row) {
            echo $row['name'] . ': ' . $row['data'] . '<br>';
        }
        echo '<br>';
    }
    ?>
    <table class="subtotal">


        <tr>
            <td class="myco2" style="width:50%;" rowspan="<?php echo $sub_t_col ?>"><br>
            <?php if($invoice['invoicestatus']=='Due')
            { ?>
                <p><?php echo $this->lang->line('Payment Method') . ' : <strong>' . $this->lang->line(ucwords($invoice['pmethod'])) . '</strong></p>'?> <br>
           <?php  } ?>
            
                <p><?php echo $this->lang->line('Status') . ' : <strong>'. $this->lang->line(ucwords($invoice['invoicestatus'])) . '</strong></p>';
                    if($invoice['invoicestatus']=='post dated cheque')
                    {
                       
                        echo '<br>'.$this->lang->line('Cheque Date') . ' : <strong>' . date('d-m-Y',strtotime($checkdate['cheque_date'])).'</strong><br>';
                    }
                    if (!$general['t_type']) {
                        echo '<br><p>' . $this->lang->line('Total Amount') . ' : <strong>' . number_format($invoice['total'],2) . '</p><br><p>';
                        // echo '<br><p>' . $this->lang->line('Total Amount') . ': ' . amountExchange($invoice['total'], $invoice['multi'], $invoice['loc']) . '</p><br><p>';
                        // echo '<br><p>' . $this->lang->line('Total Amount') . ': ' . amountExchange($invoice['total'], $invoice['multi'], $invoice['loc']) . '</p><br><p>';
                        if (@$round_off['other']) {
                            $final_amount = round($invoice['total'], $round_off['active'], constant($round_off['other']));
                            echo '<p>' . $this->lang->line('Round Off') . ' ' . $this->lang->line('Amount') . ': ' . $final_amount . '</strong></p><br><p>';
                            // $final_amount = round($invoice['total'], $round_off['active'], constant($round_off['other']));
                            // echo '<p>' . $this->lang->line('Round Off') . ' ' . $this->lang->line('Amount') . ': ' . amountExchange($final_amount, $invoice['multi'], $invoice['loc']) . '</p><br><p>';
                        }

                       
                        // echo $this->lang->line('Paid Amount') . ': ' . amountExchange($invoice['pamnt'], $invoice['multi'], $invoice['loc']);
                        echo $this->lang->line('Paid Amount') . ' : <strong>' . number_format($invoice['pamnt'],2).'</strong><br>';
                    }

                    // if ($general['t_type'] == 1) {
                    //     echo '<hr>' . $this->lang->line('Proposal') . ': </br></br><small>' . $invoice['proposal'] . '</small>';
                    // }
                    ?></p>
            </td>
            <td style="width:30%;"><strong><?php echo $this->lang->line('Summary') ?></strong></td>
            <td style="width:20%;">&nbsp;</td>
        </tr>
        <tr class="f_summary">
            <td><?php echo $this->lang->line('SubTotal') ?></td>
            <td style="text-align:right"><?php echo number_format($producttotal,2); ?></td>
            <!-- <td><?php //echo amountExchange($sub_t, $invoice['multi'], $invoice['loc']); ?></td> -->
        </tr>
        <?php if ($invoice['tax'] > 0) {
            echo '<tr>
            <td> ' . $this->lang->line('Total Tax') . ' :</td>
            <td style="text-align:right">' . number_format($invoice['tax'],2) . '</td>
        </tr>';
        }
        if ($invoice['discount'] > 0) {
            echo '<tr>
            <td>' . $this->lang->line('Total Discount') . '</td>
            <td style="text-align:right">' . number_format($granddiscount,2) . '</td>
        </tr>';
        }
        // if ($invoice['order_discount'] > 0) {
            echo '<tr>
            <td>' . $this->lang->line('Order Discount') . '</td>
            <td style="text-align:right">' . number_format($invoice['order_discount'],2) . '</td>
        </tr>';
        // }
        if ($invoice['shipping'] > 0) {
            echo '<tr>';
            // echo '<td></td>';
            echo '<td>' . $this->lang->line('Shipping') . '</td>
            <td style="text-align:right">' . number_format($invoice['shipping'],2) . '</td>
        </tr>';
        }
        else{
            echo '<tr>';
            echo '<td></td>';
            echo '<td>' . $this->lang->line('Shipping') . '</td>
            <td style="text-align:right">' . number_format($invoice['shipping'],2) . '</td>
        </tr>';
        }
        ?>
        <tr> <?php
        // if ($invoice['order_discount'] > 0 || $invoice['shipping'] > 0)
        // {
            echo '<td></td>';
        // }?>
            <td><?php echo $this->lang->line('Balance Due') ?></td>
            <td style="text-align:right"><strong><?php $rming = $invoice['total'] - $invoice['pamnt'];
                if ($rming < 0) {
                    $rming = 0;
                }
                if (@$round_off['other']) {
                    $rming = round($rming, $round_off['active'], constant($round_off['other']));
                }
                echo number_format($rming,2);
                // echo amountExchange($rming, $invoice['multi'], $invoice['loc']);
                echo '</strong></td>
		</tr>
		</table>';
        if($this->session->userdata('shipping_charge_return')=='No')
        {
            echo ' <i style="font-size:12px; margin-top:15px; padding-top:10px;">*' . $this->lang->line('Shipping Charge is not Refundable') . '</i>';
        }
       
        echo '<br><div class="sign text-right" style="width:100%">' . $this->lang->line('Authorized person') . '</div>';
        if(!empty($employee['sign']))
        {
           
            echo '<div class="sign1 text-right" style="width:100%">';
            echo '<img src="' . FCPATH . 'userfiles/employee_sign/' . $employee['sign'] . '" width="160" height="50" border="0" alt=""></div>';
        }
        echo '<div class="sign2 text-right" style="width:100%">(' . $employee['name'] . ')</div>';
        // echo '<div class="terms"><hr><strong>' . $this->lang->line('Terms') . ':</strong><br>';
        // echo '<strong>' . $invoice['termtit'] . '</strong>';
    // echo '<br>' . $invoice['terms'];
    ?></div>
    <hr>
</div>
</body>
</html>