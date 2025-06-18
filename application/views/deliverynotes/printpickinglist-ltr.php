<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print Picking List</title>

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
    <table>
    <thead>
        <tr>
            <td> <strong>Picking List No : <?= $salesorder_number?></strong></td>
            <td style="text-align:right;"><strong>Picking List Date : <?= date('d-m-Y',strtotime($deleverynote_createddate))?></strong></td>
        </tr>
        </thead>
        <!-- <tbody>
        <tr>
            <td style="font-size: 14px;" width="50%">
            Prepared By : <?php echo ucfirst($this->session->userdata('orgname')); ?><br>
            Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
            Signature &nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
            Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br> 
            </td>
            <td style="text-align: left;font-size: 14px; padding-left:50px;" width="10%">
            
            </td>
            
        </tr>
        </tbody> -->
    </table>

    <hr>

    <table cellpadding="0" cellspacing="0" class="plist maintable">
    <thead>
        <tr>
            <!-- <th style="font-size: 12px;text-align:center;" width="10%" rowspan="2">Sl No.</th> -->
            <th style="font-size: 12px;" width="15%" rowspan="2">Item No.</th>
            <th style="font-size: 12px;" width="28%" rowspan="2">Description</th>
            <th style="font-size: 12px;text-align:center;" width="10%" rowspan="2">Qty Ordered</th>
            <th style="font-size: 12px;text-align:center;" width="8%" rowspan="2">Qty to Pick</th>
            <th style="font-size: 12px;text-align:center;" width="40%" colspan="4">Location</th>
            <th style="font-size: 12px;text-align:center;" width="10%" rowspan="2">Picked Qty</th>
        </tr>
        <tr>
            <th style="font-size: 12px;text-align:center;">Aisel</th>
            <th style="font-size: 12px;text-align:center;">Rack No.</th>
            <th style="font-size: 12px;text-align:center;">Shelf</th>
            <th style="font-size: 12px;text-align:center;">Bin</th>
        </tr>
    </thead>
    <tbody>
    <?php
       $count=1;
        $k=0;
        $subtotal = 0;
        $totaldiscount = 0;
        $totaltax = 0;
        foreach ($products as $row) {
            if($row['quantity']>0)
            {
                $orderQty = ($row['salesorder_product_quantity'] > 0) ? $row['salesorder_product_quantity'] : $row['quantity'] ;
                echo '<tr class="itemx" style="font-size: 14px;">';
                // echo '<td style="text-align:center;">' . $count . '</td>';
                echo '<td>' . $row['product_code'] . '</td>';
                echo '<td>' . $row['product_name'] . '</td>';
                echo '<td style="text-align:center;">' . $orderQty . '</td>';
                echo '<td style="text-align:center;">'.$row['quantity'].'</td>';
                echo '<td style="text-align:center;">'.$row['aisel'].'</td>';
                echo '<td style="text-align:center;">'.$row['rack_number'].'</td>';
                echo '<td style="text-align:center;">'.$row['shelf_number'].'</td>';
                echo '<td style="text-align:center;">'.$row['bin_number'].'</td>';
                echo '<td style="text-align:center;"></td>';
                echo '</tr>';
                $count=$count+1;
            }
        }
        ?>
       
    </tbody>
</table>
     <!-- // erp2024 removed section 07-06-2024 -->
    <!-- <div class="sign">Authorized person</div> -->
     <!-- // erp2024 removed section 07-06-2024 -->
    <br>
    <table>
        <tbody>
            <tr>
                <td style="font-size: 14px;" width="50%">
                Prepared By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo ucfirst($this->session->userdata('orgname')); ?><br>
                Contact Number : <?php echo $employee['phone']; ?><br>
                Contact Email &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $employee['email']; ?><br>
                Picked By&nbsp;&nbsp;   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
                Signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
                Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:.................................................<br>
                </td>
                <td style="text-align: left;font-size: 14px; padding-left:50px;" width="10%">
                
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
