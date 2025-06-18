<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Profit & Loss Report</title>

    <style>
        body {
            color: #2B2000;
        }

        /* table {
            width: 100%;
            line-height: 16pt;
            text-align: right;
            border-collapse: collapse;
            text-decoration:none;
        } */

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

        div.inset {border:1px solid black;
        }
        .text-danger{
            color:#ff0000;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        /* Ensure all columns have equal widths */
        .report-top-table td:nth-child(1),
        .report-content-table td:nth-child(1),
        .report-top-table th:nth-child(1),
        .report-content-table th:nth-child(1) {
            width: 20%;
        }

        .report-top-table td:nth-child(2),
        .report-content-table td:nth-child(2),
        .report-top-table th:nth-child(2),
        .report-content-table th:nth-child(2) {
            width: 20%;
        }

        .report-top-table td:nth-child(3),
        .report-content-table td:nth-child(3),
        .report-top-table th:nth-child(3),
        .report-content-table th:nth-child(3) {
            width: 20%;
        }

        .report-top-table td:nth-child(4),
        .report-content-table td:nth-child(4),
        .report-top-table th:nth-child(4),
        .report-content-table th:nth-child(4) {
            width: 20%;
        }

        .report-top-table td:nth-child(5),
        .report-content-table td:nth-child(5),
        .report-top-table th:nth-child(5),
        .report-content-table th:nth-child(5) {
            width: 20%;
        }

        .report-top-table td:nth-child(6),
        .report-content-table td:nth-child(6),
        .report-top-table th:nth-child(6),
        .report-content-table th:nth-child(6) {
            width: 20%;
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
            <td> <strong>Profit & Loss Report</td>
                <td style="text-align:right"><strong>Prepared By  : <?php echo ucfirst($this->session->userdata('orgname')); ?> 
                <br><strong>Date & Time : <?php echo date('d-m-Y h:i:sa')?> </td>
                
            </tr>
        </thead>
    </table>
    <hr>
    <div style="padding:35px">

        <table style="width:100%; border-collapse: collapse; font-size:14px; " class="report-content-table">
            <thead>
                <tr>
                    <th colspan="6" style="text-align: left;font-size:18px;">Revenue <span class=""></th>
                </tr>
            </thead>
            <tbody class="transaction-details">

                <?php

                    $revenue_value = 0;
                    $grand_revenue = 0;
                    if($revenue_income)
                    {
                        foreach ($revenue_income as $key => $value) {
                            $account_id = $value['account_id'];
                            $revenue_value = ($value['amount']) ? ($value['amount']):0.00;      
                            $grand_revenue += $revenue_value;                             
                            $revenue_value1 = ($revenue_value>0) ? number_format($revenue_value,2) : "(".number_format(abs($revenue_value),2).")";
                            echo '<tr>';
                            echo '<td scope="col" style="padding: 10px 0; font-size: 14px;text-align:left;">'.$value['holder'].'</td>';
                            echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.$revenue_value1.'</td>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td scope="col" style="width:30%; padding: 10px 0; font-size: 15px;text-align:left; font-weight:bold;">Total Revenue</td>';
                        echo '<td style="padding: 10px 0; font-size: 15px;text-align:right; font-weight:bold;">'.number_format(abs($grand_revenue),2).'</td>';
                        echo '</tr>';
                    }

                ?>
            </tbody>   
        </table>

        <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
            <thead>
                <tr class="border-primary">
                    <th colspan="6" style="text-align: left;font-size:18px;">Costs of Goods Sold(COGS) <span class=""></th>
                </tr>
            </thead>
            <tbody class="transaction-details">
            <?php
            $cogs_value = 0;
            $grand_cogs = 0;
            if($cogs)
            {
                foreach ($cogs as $key => $value) {
                    $account_id = $value['account_id'];
                    $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                    $cogs_value = ($value['amount']) ? ($value['amount']):0.00;      
                    $grand_cogs += $cogs_value; 
                    echo '<tr>';
                    echo '<td scope="col" style="padding: 10px 0; font-size: 14px;text-align:left;">'.$value['holder'].'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format(abs($cogs_value),2).'</td>';
                    echo '</tr>';
                    
                }
                echo '<tr>';
                echo '<td scope="col" style="padding: 10px 0; font-size: 15px;text-align:left; font-weight:bold;">Total COGS</td>';
                echo '<td style="padding: 10px 0; font-size: 15px;text-align:right; font-weight:bold;">'.number_format(abs($grand_cogs),2).'</td>';
                echo '</tr>';
            }
            echo '<tr>';
            echo '<td scope="col" style="width:30%; padding: 10px 0; font-size: 15px;text-align:left; font-weight:bold;">Gross Profit</td>';
            echo '<td style="padding: 10px 0; font-size: 15px;text-align:right; font-weight:bold;">'.number_format(abs($grand_revenue)-abs($grand_cogs),2).'</td>';
            echo '</tr>';
            ?>
            </tbody>
        </table>

        <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
            <thead>
                <tr class="border-primary">
                    <th colspan="6" style="text-align: left;font-size:18px;">Other Income</th>
                </tr>
            </thead>
            <tbody class="transaction-details">
            <?php
            $otherincome_value = 0;
            $grand_otherincome = 0;
            if($otherincome)
            {
                foreach ($otherincome as $key => $value) {
                   $account_id = $value['account_id'];
                   $otherincome_value = ($value['amount']) ? ($value['amount']):0.00;      
                   $grand_otherincome += $otherincome_value;                             
                   $otherincome_value1 = ($otherincome_value>0) ? number_format($otherincome_value,2) : number_format(abs($otherincome_value),2);
                   echo '<tr>';
                   echo '<td scope="col" style="padding: 10px 0; font-size: 14px;text-align:left;">'.$value['holder'].'</td>';
                   echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.$otherincome_value1.'</td>';
                   echo '</tr>';
                }
                echo '<tr>';
                echo '<td scope="col" style=" width:30%;padding: 10px 0; font-size: 15px;text-align:left; font-weight:bold;">Total Other Income</td>';
                echo '<td style="padding: 10px 0; font-size: 15px;text-align:right; font-weight:bold;">'.number_format(abs($grand_otherincome),2).'</td>';
                echo '</tr>';
            }

            ?>
            </tbody>
        </table>

        <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
            <thead>
                <tr class="border-primary">
                    <th colspan="6" style="text-align: left;font-size:18px;">Other Expense</th>
                </tr>
            </thead>
            <tbody class="transaction-details">
            <?php
            $otherexpense_value = 0;
            $grand_otherexpense = 0;
            if($otherexpense)
            {
                foreach ($otherexpense as $key => $value) {
                   $account_id = $value['account_id'];
                   $otherexpense_value = ($value['amount']) ? ($value['amount']):0.00;      
                   $grand_otherexpense += $otherexpense_value;                             
                   $otherexpense_value1 = ($otherexpense_value>0) ?  "(".number_format(abs($otherexpense_value),2).")" : "(".number_format(abs($otherexpense_value),2).")";
                   echo '<tr>';
                   echo '<td scope="col" style="padding: 10px 0; font-size: 14px;text-align:left;">'.$value['holder'].'</td>';
                   echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.$otherexpense_value1.'</td>';
                   echo '</tr>';

                }
                echo '<tr>';
                echo '<td scope="col" style=" width:30%;padding: 10px 0; font-size: 15px;text-align:left; font-weight:bold;">Total Other Expense</td>';
                echo '<td style="padding: 10px 0; font-size: 15px;text-align:right; font-weight:bold;">'.number_format(abs($grand_otherexpense),2).'</td>';
                echo '</tr>';
            }
                $grandtotal = (abs($grand_revenue)+abs($grand_otherincome)) - (abs($grand_cogs)+abs($grand_otherexpense));
                echo '<tr><br>';
                echo '<td scope="col" style=" width:30%;padding: 10px 0; font-size: 15px;text-align:left; font-weight:bold;">Net Income</td>';
                echo '<td style="padding: 10px 0; font-size: 15px;text-align:right; font-weight:bold;">'.number_format($grandtotal,2).'</td>';
                echo '</tr>';

            ?>
            </tbody>
        </table>
  
    
    </div>
</div>
</body>
</html>
