<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print Credit Note</title>

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
            <td> <strong>Credit Note : <?= $delevery_return_id?></strong>
            <br><strong>Invoice Number : <?= $products[0]['invoice_number']?></strong></td>
            <td><strong>Credit Note Date : <?= date('d-m-Y H:i:s',strtotime($products[0]['created_date']))?></strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-size: 14px; border-right:1px solid #000" width="50%">
            <strong>Return From</strong><br><?=$client?><br>
            <!-- Salesman : <?php //print_r($salesman['salesman']); ?>  -->
            </td>
            <td style="text-align: left;font-size: 14px; padding-left:50px;" width="10%">
            Prepared By : <?php echo ucfirst($this->session->userdata('orgname')); ?><br>
            Checked By &nbsp;:.................................................<br>
            Signature &nbsp;&nbsp;&nbsp;:.................................................<br>
            Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
            </td>
            
        </tr>
        <tr><td>Currency : <?php echo "<b>".currency($this->aauth->get_user()->loc)."</b>"; ?></td></tr>
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

            <td style="font-size: 12px;" with="50%"><strong><?php echo "#" ?></strong></td>
            <td style="font-size: 12px;" with="50%"><strong><?php echo $this->lang->line('Code') ?></strong></td>
            <td style="font-size: 12px;" with="50%"><strong><?php echo $this->lang->line('Description') ?></strong></td>
            <td style="font-size: 12px;" with="50%"><strong><?php echo $this->lang->line('Unit') ?></strong></td>
            <td style="font-size: 12px;text-align:center;" with="50%"><strong><?php echo $this->lang->line('Return Quantity') ?></strong></td>
            <td style="font-size: 12px;text-align:center;" with="50%"><strong><?php echo $this->lang->line('Damage Quantity') ?></strong></td>
            <td style="font-size: 12px;" with="60%"><strong><?php echo $this->lang->line('Price') ?></strong></td>
            <!-- <td style="font-size: 12px;" with="50%"><strong>Tax(%)</strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Tax</strong></td> -->

            <!-- erp2024 removed section 07-06-2024 -->
            <td style="font-size: 12px;" with="50%"><strong><?php echo $this->lang->line('Discount') ?></strong></td>

            <td style="font-size: 12px;" with="50%"><strong><?php echo $this->lang->line('Amount') ?></strong></td>


        </tr>
        <?php
       $count=1;
        $k=0;
        $subtotal = 0;
        $totaldiscount = 0;
        $totaltax = 0;
        $grand_prodcut_total =0;
        foreach ($products as $row) {
            // echo '<tr class="itemx" style="font-size: 14px;"><td>' . $count . '</td><td>' . $row['product_code'] . '</td><td>'.$row['product_name'].'</td><td>'.$row['productunit'].'</td><td>' . $row['delivered_qty'] . '</td><td>'.$row['return_qty'].'</td><td>'.$row['damaged_qty'].'</td><td>' . $row['product_price'] .'</td><td>'.$row['product_tax'].'</td><td>'.$row['totaltax'].'</td>';
            echo '<tr class="itemx" style="font-size: 14px;"><td>' . $count . '</td><td>' . $row['product_code'] . '</td><td>'.$row['product_name'].'</td><td>'.$row['productunit'].'</td><td style="text-align:center;">'.intval($row['quantity']).'</td><td style="text-align:center;">'.intval($row['damaged_quantity']).'</td><td style="text-align:right;">' . number_format($row['price'],2) .'</td>';
            
            // erp2024 removed section 07-06-2024
            echo '<td style="text-align:right;">'.number_format($row['total_discount'],2).'</td>';
             // erp2024 removed section 07-06-2024 ends

            echo '<td style="text-align:right;">'.number_format($row['subtotal'],2).'</td></tr>';
            $count=$count+1;
            $k++;
            $subtotal = $row['subtotal']+$subtotal;
            $totaldiscount = $row['total_discount']+$totaldiscount;
            $totaltax = $row['total_tax']+$totaltax;
            $grand_prodcut_total += ($row['price']*intval($row['quantity']));
        }
        ?>
    </table>

     <div style="text-align:right">
        <?php 
            // $sub_total = $subtotal-$totaldiscount;
        ?>
        <h6>Subtotal : <?=number_format($grand_prodcut_total, 2)?><br>
        <!-- // erp2024 removed section 07-06-2024 -->
        Total Discount : <?=number_format($totaldiscount, 2)?><br>
        <!-- Total Tax : <?=$totaltax?><br> -->
        <!-- // erp2024 removed section 07-06-2024 -->
        Grand Total : <?php echo number_format(($subtotal+$totaltax), 2); ?>
        <!-- Grand Total : <?php echo ($subtotal+$totaltax)-$totaldiscount; ?> -->
        </h6>
     </div>
     <!-- // erp2024 removed section 07-06-2024 -->
    <!-- <div class="sign">Authorized person</div> -->
     <!-- // erp2024 removed section 07-06-2024 -->
    <!-- <br> -->
    <!-- <table>
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
                
                <td style="text-align: right;font-size: 14px; padding-left:50px;" width="50%">
                    <h6>Subtotal : <?=$subtotal?><br>
                    Total Tax : <?=$totaltax?><br>
                    Grand Total : <?php echo ($subtotal+$totaltax); ?>
                    </h6>
                </td>
                
            </tr>
        </tbody>
    </table> -->
</div>
</body>
</html>
