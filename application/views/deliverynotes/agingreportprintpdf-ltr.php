<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>DeliveryNote</title>

    <style>
        body {
            color: #2B2000;
        }

        table {
            width: 100%;
            line-height: 16pt;
            text-align: right;
            border-collapse: collapse;
            text-decoration:none;
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
  background-color: #f5f1f1;
}
div.inset {border:1px solid black;
}

    </style>
</head>

<body>

<div class="invoice-box inset" >
    <!-- <table>
        <tr>
            <td class="myco">
                <img src='<?php //echo base_url('userfiles/company/'.$this->config->item('logo')) ?>' style="max-width:260px;">
            </td>
            <td>

            </td>
            <td class="myw">
                <?php //echo $lang['statement'];
                //$balance = 0; ?>
            </td>
        </tr>
    </table>
    <br> -->
    <table>
        <!-- <thead>
        <tr>
            <td> Product Sales Report:</td>
            <td> </td>
            <td></td>
        </tr>
        </thead> -->
        <tbody>
        <tr>
            <td style="text-align: center;vertical-align: middle;" width="40%">
            <strong><h3><?php echo $companyNanme; ?></h3></strong>
            </td>

            <td style="font-size: 14px;" width="40%">
                <?=$lang['company'] ?>
            </td>
            <td style="text-align: right" width="40%"> 
            
            <?php $imgUrl="".base_url()."userfiles/company/company-logo.jpg";?>
            <img src="<?=$imgUrl?>" style="max-width:260px;">
            </td>
        </tr>
        </tbody>
    </table>
    <hr>
   <?php 
    // echo "<pre>";
    // print_r($products);
    // die();
   ?>
    <table>
    <thead>
        <tr>
            <td> <strong>Delivery Note No : #<?= $salesorder_id?></strong></td>
            <td><strong>Delivery Note Date : <?= date('d-m-Y',strtotime($deleverynote_createddate))?></strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-size: 14px; border-right:1px solid #000" width="50%">
            <strong>Delivered To</strong><br><?=$client?><br>
            <!-- Salesman : <?php //print_r($salesman['salesman']); ?>  -->
            </td>
            <td style="text-align: left;font-size: 14px; padding-left:50px;" width="10%">
            Prepared By : <?php echo ucfirst($this->session->userdata('orgname')); ?><br>
            Checked By &nbsp;:.................................................<br>
            Signature &nbsp;&nbsp;&nbsp;:.................................................<br>
            Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
            </td>
            
        </tr>
        </tbody>
    </table>
    <br>
    <hr>
    <table cellpadding="0" cellspacing="0" class="plist maintable">
        

        <tr>
            <!-- <td><strong><?php //echo $this->lang->line('Date') ?></strong></td>
            <td><strong><?php //echo $this->lang->line('Description') ?></strong></td>

            <td><strong><?php //echo $this->lang->line('Debit') ?></strong></td>
            <td><strong><?php //echo $this->lang->line('Credit') ?></strong></td>

            <td><strong><?php //echo $this->lang->line('Balance') ?></strong></td> -->
            <td style="font-size: 12px;text-align:center;" with="50%"><strong>No.</strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Item Name & No.</strong></td>
            <td style="font-size: 12px;text-align:center;" with="50%"><strong>Unit</strong></td>
            <td style="font-size: 12px;text-align:center;" with="50%"><strong>Ordered Qty</strong></td>
            <td style="font-size: 12px;text-align:center;" with="50%"><strong>Delivery Qty</strong></td>
            <?php
            if($priceFlg==1){ ?>
                <td style="font-size: 12px;text-align:right;" with="60%"><strong>Price</strong></td>
                <td style="font-size: 12px;text-align:right;" with="50%"><strong>Discount</strong></td>
                <td style="font-size: 12px;text-align:right;" with="50%"><strong>Amount</strong></td>
            <?php } ?>


        </tr>
        <?php
       $count=1;
        $k=0;
        $subtotal = 0;
        $totaldiscount = 0;
        $totaltax = 0;
        foreach ($products as $row) {
            if($row['product_qty']>0)
            {
                echo '<tr class="itemx" style="font-size: 14px;">';
                echo '<td style="text-align:center;">' . $count . '</td><td>' . $row['product'] . '</td><td style="text-align:center;">'.$row['productunit'].'</td><td style="text-align:center;">' . $row['salesorder_product_qty'] . '</td><td style="text-align:center;">'.$row['product_qty'].'</td>';

                if($priceFlg==1){ 
                    echo '<td style="text-align:right;">' . $row['product_price'] .'</td>';
                    // echo '<td>'.$row['product_tax'].'</td>';
                    // echo '<td>'.$row['totaltax'].'</td>';
                    
                    // erp2024 removed section 07-06-2024
                    echo '<td style="text-align:right;">'.$row['totaldiscount'].'</td>';
                    // erp2024 removed section 07-06-2024 ends

                    echo '<td style="text-align:right;">'.$row['subtotal'].'</td>';

                
                    $count=$count+1;
                    $k++;
                    $subtotal = $row['subtotal']+$subtotal;
                    $totaldiscount = $row['totaldiscount']+$totaldiscount;
                    $totaltax = $row['totaltax']+$totaltax;
                }
                echo '</tr>';
            }
        }
        ?>
    </table>

     <div style="margin-left:70%;">
        <?php
            if($priceFlg==1){ 
                $sub_total = $subtotal-$totaldiscount;
                ?>
            <h6 style="text-align:right;">Subtotal : <?=$sub_total?><br>
            <!-- // erp2024 removed section 07-06-2024 -->
            Total Discount : <?=$totaldiscount?><br>
            <!-- Total Tax : <?=$totaltax?><br> -->
            <!-- // erp2024 removed section 07-06-2024 -->
            Grand Total : <?php echo ($subtotal+$totaltax); ?>
            <!-- Grand Total : <?php echo ($subtotal+$totaltax)-$totaldiscount; ?> -->
            </h6>
        <?php } ?>
     </div>
     <!-- // erp2024 removed section 07-06-2024 -->
    <!-- <div class="sign">Authorized person</div> -->
     <!-- // erp2024 removed section 07-06-2024 -->
    <!-- <br> -->
    <table>
        <tbody>
            <tr>
                <td style="font-size: 14px;" width="50%">
                Delivered By :.................................................<br>
                Signature &nbsp;:.....................................................<br><br>
                <b>Customer</b><br>
                &nbsp;&nbsp;Received By :................................................<br>
                &nbsp;&nbsp;Mobile No. &nbsp;:.................................................<br>
                &nbsp;&nbsp;Signature &nbsp;&nbsp;&nbsp;:.................................................<br>
                &nbsp;&nbsp;Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
                </td>
                <!-- // erp2024 removed section 07-06-2024 -->
                <!-- <td style="text-align: right;font-size: 14px; padding-left:50px;" width="50%">
                    <h6>Subtotal : <?=$subtotal?><br>
                    Total Tax : <?=$totaltax?><br>
                    Grand Total : <?php echo ($subtotal+$totaltax); ?>
                    </h6>
                </td> -->
                
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
