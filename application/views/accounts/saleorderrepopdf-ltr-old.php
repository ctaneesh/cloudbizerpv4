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
            <?php $imgUrl="".base_url()."userfiles/company/cmpny.jpg";?>
            <img src="<?=$imgUrl?>" style="max-width:260px;">
            </td>
        </tr>
        </tbody>
    </table>
    <hr>
   
    <table>
    <thead>
        <tr>
            <td> <strong>Salesorder No : #<?= $invocienoId?></strong></td>
            <td> </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-size: 14px; border-right:1px solid #000" width="50%">
            <strong>Sold To</strong><br><?=$client?>
            </td>
            <td style="text-align: right;font-size: 14px;" width="50%">
            <strong>Ship To</strong> <br><?=$client?>
            </td>
            
        </tr>
        </tbody>
    </table>
    <br>
    <hr>
    <table cellpadding="0" cellspacing="0" class="plist maintable">
        <tr>
        <td style="font-size: 12px;" with="50%" colspan="2"><strong>Customer : #<?=$custId?></strong></td>
        <td style="font-size: 12px;" with="50%"></td>
        <td style="font-size: 12px;"with="50%" colspan="2"><strong>Received : <?=dateformat($invoicedate)?></strong></td>
        <td style="font-size: 12px;" with="50%" colspan="2"><strong>Due : <?=dateformat($invocieduedate)?></strong></td>
        
        

        </tr>

        <tr>
            <!-- <td><strong><?php //echo $this->lang->line('Date') ?></strong></td>
            <td><strong><?php //echo $this->lang->line('Description') ?></strong></td>

            <td><strong><?php //echo $this->lang->line('Debit') ?></strong></td>
            <td><strong><?php //echo $this->lang->line('Credit') ?></strong></td>

            <td><strong><?php //echo $this->lang->line('Balance') ?></strong></td> -->

            <td style="font-size: 12px;" with="50%"><strong>No.</strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Product</strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Qty Ordered </strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Qty To Ship</strong></td>
            <td style="font-size: 12px;" with="60%"><strong>Description</strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Qty BO</strong></td>
            <td style="font-size: 12px;" with="50%"><strong>Ship City</strong></td>


        </tr>

        <?php
       $count=1;
      
        foreach ($products as $row) {
            
            echo '<tr class="itemx" style="font-size: 14px;"><td>' . $count . '</td><td>' . $row['product'] . '</td><td>' . $row['qty'] . '</td><td>' . $row['qty'] . '</td><td> Code : #' . $row['code'] . '<br>Description : ' . $row['product_des'] .'<br>Note :' . $row['notes'] .'</td><td></td><td></tr>';
            $count=$count+1;;
        }
        ?>
        
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
