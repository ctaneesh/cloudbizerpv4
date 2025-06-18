<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>General Ledger Report</title>

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
            <td> <strong>General Ledger Report</td>
                <td style="text-align:right"><strong>Prepared By  : <?php echo ucfirst($this->session->userdata('orgname')); ?> 
                <br><strong>Date & Time : <?php echo date('d-m-Y h:i:sa')?> </td>
                
            </tr>
        </thead>
    </table>
    <hr>
    <div style="padding:35px">
    <table class="report-top-table" style="width:100%; border-collapse: collapse; font-size:14px;">
        <!-- Header Row -->
        <tr style="border-bottom: 1px solid #ddd;">
            <th style="text-align: left; padding: 10px;border-bottom: 1px solid #ddd;">Date</th>
            <th style="text-align: left; padding: 10px;border-bottom: 1px solid #ddd;">Relation</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Debit</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Credit</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Balance</th>
        </tr>
    </table>
    <?php
        if(!empty($coa_accounts))
        {
            foreach($coa_accounts as $account)
            {
                ?>
    <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
       
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <td colspan="5" style="padding: 10px 0; font-weight: bold; font-size: 16px;border-bottom: 1px solid #ddd;"><?php echo $account['holder']."(".$account['typename'].")";?></td>
            </tr>
            <!-- Opening Balance -->
            <tr style="border-bottom: 1px solid #ddd;">
                <td colspan="4" style="padding: 10px; font-size: 14px; font-weight: bold;">Opening Balance</td>
                <td colspan="1" style="padding: 10px; font-size: 14px; font-weight: bold; text-align:right">0.00</td>
            </tr>
        </thead>
        <?php 
            if (!empty($transaction_records[$account['acn']]))
            {
                $total_credit = 0;
                $total_debit = 0;
                $total_balance = 0;
                $balance =0;
            ?>
        <tbody class="transaction-details">
            <?php 
                foreach ($transaction_records[$account['acn']] as $row)
                { 
                    $invoiceid = $row['invoiceid'];
                    $refnumber = $row['bank_transaction_refernce'];
                    $date = ($row['date']) ? date('d M Y', strtotime($row['date'])) : "";
                    $relation = ($row['bank_transaction_number']) ? $row['bank_transaction_refernce'] : $row['transaction_number'];
                    $credit = $row['creditamount'];
                    $debit = $row['debitamount'];
                    $balance = $balance + ($debit-$credit);
                    $total_balance += $balance;
                    $total_credit += $credit;
                    $total_debit += $debit;
                    ?>
            <!-- Data Rows -->
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;"><?=$date?></td>
                <td style="padding: 10px;"><?=$relation?></td>
                <td style="padding: 10px; text-align: right;"><?=number_format(abs($debit), 2)?></td>
                <td style="padding: 10px; text-align: right;"><?=number_format(abs($credit), 2)?></td>
                <td style="padding: 10px; text-align: right;"><?=number_format(abs($balance), 2)?></td>
            </tr>
            <?php
                } ?>
        </tbody>
        <?php
            }?>
        <tfoot>
            <tr  style="border-bottom: 1px solid #ddd; font-weight: bold;">
                <th colspan="2" style="padding: 10px; text-align: right;"><?php echo 'Totals and Closing Balance'; ?></th>
                <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($total_debit), 2); ?></th>
                <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($total_credit), 2); ?></th>
                <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($balance), 2); ?></th>
            </tr>
            <tr>
                <th colspan="2" style="padding: 10px; text-align: right;border-top: 1px solid #ddd;">Balance</th>
                <th colspan="4" style="padding: 10px; text-align: right;border-top: 1px solid #ddd;"><?php echo number_format(abs($balance), 2); ?></th>
            </tr>
        </tfoot>
    </table>
    <?php
        }
        }
        
        ?>
    </div>
</div>
</body>
</html>
