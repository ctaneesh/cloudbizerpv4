<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stock List Report</title>

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
        <thead>
            <tr>
                <th style="font-size: 12px;">Product Code</th>
                <th style="font-size: 12px;text-align:center;">Product Name</th>
                <th style="font-size: 12px;text-align:center;">Unit Cost</th>
                <th style="font-size: 12px;text-align:center;">Selling Price</th>
                <th style="font-size: 12px;text-align:center;">On Hand</th>
                <th style="font-size: 12px;text-align:center;"> Total Value</th>
                <th style="font-size: 12px;text-align:center;"> Purchase<br>Orders</th>
                <th style="font-size: 12px;text-align:center;"> Customer<br>Sales Orders</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($stocklist))
            {
                
                foreach($stocklist as $row)
                    {
                        $totalvalue = number_format($row['qty'] * $row['product_cost'], 2);
                        echo '<tr>
                                <td style="font-size: 11px;border:1px solid #ccc;">'.$row['product_code'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['product_name'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['product_cost'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['product_price'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$row['qty'].'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;">'.$totalvalue.'</td>
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;"></td>            
                                <td style="font-size: 11px;border:1px solid #ccc;text-align:center;"></td>            
                            </tr>';
                    }
            }
            ?>
        </tbody>
    </table>

    
</div>
</body>
</html>
