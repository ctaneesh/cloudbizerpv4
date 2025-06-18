<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>AR Aging Report</title>

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
    
    <table>
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
                <td> <strong>AR Aging Report </td>
                <td style="text-align:center"><strong>Prepared By  : <?php echo ucfirst($this->session->userdata('orgname')); ?> </td>
                <td style="text-align:right;"><strong>Date & Time : <?php echo date('d-m-Y h:i:sa')?> </td>
                
            </tr>
        </thead>
    </table>
    <?php
    
    ?>
    <table>
        <thead>
            <tr>
                <th style="font-size: 12px;">Company </th>
                <th style="font-size: 12px;text-align:center;">Current</th>
                <th style="font-size: 12px;text-align:center;">1-30 Days</th>
                <th style="font-size: 12px;text-align:center;">31-60 Days</th>
                <th style="font-size: 12px;text-align:center;">61-90 Days</th>
                <th style="font-size: 12px;text-align:center;"> >90 Days</th>
                <th style="font-size: 12px;text-align:center;"> Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rowtotal=0;
            $current_total = 0;
            $days30_total = 0;
            $days60_total = 0;
            $days90_total = 0;
            $days90plus_total = 0;
            $grand_total = 0;
            if(!empty($paymentlist))
            {
                
                foreach($paymentlist as $row)
                    {
                        $current_total    = $current_total + $row['today_total'];
                        $days30_total     = $days30_total + $row['30days'];
                        $days60_total     = $days60_total + $row['60days'];
                        $days90_total     = $days90_total + $row['90days'];
                        $days90plus_total = $days90plus_total + $row['90plus'];
                       
                        $rowtotal = $row['today_total'] + $row['30days'] + $row['60days'] + $row['90days'] + $row['90plus'];
                        echo '<tr>
                                <td style="font-size: 11px;border:1px solid #ccc;">'.$row['company'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['today_total'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['30days'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['60days'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['90days'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['90plus'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;"><strong>'.$rowtotal.'</strong></td>            
                            </tr>';
                    }
            }
            $grand_total = $current_total + $days30_total + $days60_total + $days90_total + $days90plus_total
            ?>
        </tbody>
        <tfoot>
            <tr>
            <th style="font-size: 12px;">Total </th>
            <th style="font-size: 12px;text-align:center;"><?=$current_total?></th>
            <th style="font-size: 12px;text-align:center;"><?=$days30_total?></th>
            <th style="font-size: 12px;text-align:center;"><?=$days60_total ?></th>
            <th style="font-size: 12px;text-align:center;"><?=$days90_total?></th>
            <th style="font-size: 12px;text-align:center;"> <?=$days90plus_total?></th>
            <th style="font-size: 12px;text-align:center;"> <?= $grand_total;?></th>
            </tr>
        </tfoot>
    </table>
    <div style="text-align:right;">
            <h6>
                Grand Total : <?php echo $config_currency." ".$grand_total; ?>
            </h6>
     </div>
    
</div>
</body>
</html>
