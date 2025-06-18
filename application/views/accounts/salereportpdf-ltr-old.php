<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print Statement</title>

    <style>
        body {
            color: #2B2000;
        }

        table {
            width: 100%;
            line-height: 16pt;
            text-align: right;
            border-collapse: collapse;
        }

        .mfill {
            background-color: #eee;
        }

        .descr {
            font-size: 10pt;
            color: #515151;
        }

        .invoice-box {
            width: 210mm;
            height: 297mm;
            margin: auto;
            padding: 4mm;
            border: 0;

            font-size: 16pt;
            line-height: 24pt;

            color: #000;
        }

        .invoice-box table {
            width: 100%;
            line-height: 17pt;
            text-align: left;
        }

        .plist tr td {
            line-height: 12pt;
        }

        .subtotal tr td {
            line-height: 10pt;
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
        }

        .invoice-box table td {
            padding: 10pt 4pt 5pt 4pt;
            vertical-align: top;

        }

        .invoice-box table tr td:nth-child(2) {
            text-align: left;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20pt;

        }

        .invoice-box table tr.top table td.title {
            font-size: 45pt;
            line-height: 45pt;
            color: #555;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 20pt;
        }

        .invoice-box table tr.heading td {
            background: #515151;
            color: #FFF;
            padding: 6pt;

        }

        .invoice-box table tr.details td {
            padding-bottom: 20pt;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #fff;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(4) {
            border-top: 2px solid #fff;
            font-weight: bold;
        }

        .myco {
            width: 500pt;
        }

        .myco2 {
            width: 290pt;
        }

        .myw {
            width: 180pt;
            font-size: 14pt;
            line-height: 30pt;
        }
        .maintable {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.maintable td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

.maintable tr:nth-child(even) {
  background-color: #dddddd;
}

    </style>
</head>

<body>
<div class="invoice-box">
    <table>
        <tr>
            <td class="myco">
                <img src='<?php echo base_url('userfiles/company/'.$this->config->item('logo')) ?>' style="max-width:260px;">
            </td>
            <td>

            </td>
            <td class="myw">
                <?php echo $lang['statement'];
                $balance = 0; ?>
            </td>
        </tr>
    </table>
    <br>
    <table>
        <thead>
        <tr class="heading">
            <td> Product Sales Report:</td>
            <td> </td>
            <td><?php echo $lang['title'] ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?=$lang['company'] ?>
            </td>

            <td>
                <strong><?php echo  $lang['var1'] . '</strong><br>' .  $lang['var2']; ?>
            </td>
            <td style="text-align: right">
            Processed Date : <?php echo date("d-m-Y");?></strong><br> 
            From           : <?php echo $start_date;?></strong><br> 
            To             : <?php echo $end_date;?></strong>

        </td>
        </tr>
        </tbody>
    </table>
    <hr>
    <table class="plist" cellpadding="0" cellspacing="0" class="maintable">

        <tr>
            <!-- <td><strong><?php //echo $this->lang->line('Date') ?></strong></td>
            <td><strong><?php //echo $this->lang->line('Description') ?></strong></td>

            <td><strong><?php //echo $this->lang->line('Debit') ?></strong></td>
            <td><strong><?php //echo $this->lang->line('Credit') ?></strong></td>

            <td><strong><?php //echo $this->lang->line('Balance') ?></strong></td> -->

            <td><strong>Date</strong></td>
            <td><strong>Customer</strong></td>
            <td><strong>Product </strong></td>
            <td><strong>Qty</strong></td>
            <td><strong> Amount</strong></td>
            <td><strong> Discount</strong></td>
            <td><strong> Tax</strong></td>


        </tr>

        <?php
        $grandTotal=0;
        $grandqty=0;
        $granddiscount=0;
        $totalTax=0;



        foreach ($list as $row) {
            $ordval=str_replace("$","",$row['amount'] );
            $ordval1=str_replace("$","",$row['discount'] );
            $ordval2=str_replace("$","",$row['tax'] );
            $grandTotal=$grandTotal+ $ordval;
            $grandqty= $grandqty+$row['qty'];
            $granddiscount=$granddiscount+$ordval1;
            $totalTax=$totalTax+$ordval2;
            echo '<tr class="item"><td>' . $row['date'] . '</td><td>' . $row['name'] . '</td><td>' . $row['product'] . '</td><td>' . $row['qty'] . '</td><td>' . $row['amount'] . '</td><td>' . $row['discount'] . '</td><td>' . $row['tax'] . '</td></tr>';
           
        }
        ?>
        <tr style="background-color: #dff3ffff;">
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Total Qty</strong></td>
            <td><strong>Total Amount</strong></td>
            <td><strong>Total Discount</strong></td>
            <td><strong>Total Tax</strong></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><strong><?php echo $grandqty;?></strong></td>
            <td><strong>$<?php echo $grandTotal;?></strong></td>
            <td><strong>$<?php echo $granddiscount;?></strong></td>
            <td><strong>$<?php echo $totalTax;?></strong></td>
        </tr>
    </table>

     <hr>

    <table class="subtotal">
        <thead>
        <tbody>
        <tr>
            <td class="myco2" rowspan="2"><br><br><br>

            </td>
            <!-- <td><strong><?php// echo $this->lang->line('Summary') ?>:</strong></td> -->
            <td></td>
            <td></td>

        </tr>
        <tr>
            <td></td>
            <td><strong>Grand Total:</strong></td>

            <td><strong><?php echo "$".$grandTotal;?></strong></td>
        </tr>

        </tbody>
    </table>
    <br>
    <div class="sign">Authorized person</div>
    <div class="sign1"></div>
    <div class="sign2"></div>
    <div class="sign3"></div>
    <br>
    <div class="terms">
        <!-- <hr> -->

    </div>
</div>
</body>
</html>
