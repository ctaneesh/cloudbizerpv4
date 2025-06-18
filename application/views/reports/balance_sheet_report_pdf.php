<!DOCTYPE html>
<html>
<head>
    <style>
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
<div style="padding:35px">
    <table class="report-top-table" style="width:100%; border-collapse: collapse; font-size:14px;">
        <!-- Header Row -->
        <tr style="border-bottom: 2px solid #ddd;">
            <th style="text-align: left; padding: 10px;">Date</th>
            <th style="text-align: left; padding: 10px;">Relation</th>
            <th style="text-align: right; padding: 10px;">Debit</th>
            <th style="text-align: right; padding: 10px;">Credit</th>
            <th style="text-align: right; padding: 10px;">Balance</th>
        </tr>
    </table>
<?php
                
if(!empty($coa_accounts))
{
    foreach($coa_accounts as $account)
    {
        ?>
            
                <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
                    <!-- Header Row -->
                    <!-- <tr style="border-bottom: 2px solid #ddd;">
                        <th style="text-align: left; padding: 10px;">Date</th>
                        <th style="text-align: left; padding: 10px;">Relation</th>
                        <th style="text-align: right; padding: 10px;">Debit</th>
                        <th style="text-align: right; padding: 10px;">Credit</th>
                        <th style="text-align: right; padding: 10px;">Balance</th>
                    </tr> -->

                    <!-- Section Header -->
                    <thead>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td colspan="5" style="padding: 10px 0; font-weight: bold; font-size: 16px;"><?php echo $account['holder']."(".$account['typename'].")";?></td>
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
                            <th colspan="2"><?php echo 'Totals and Closing Balance'; ?></th>
                            <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($total_debit), 2); ?></th>
                            <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($total_credit), 2); ?></th>
                            <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($balance), 2); ?></th>
                        </tr>
                        <tr>
                            <th colspan="4">Balance</th>
                            <th style="padding: 10px; text-align: right;"><?php echo number_format(abs($balance), 2); ?></th>
                        </tr>
                    </tfoot> 
                  
                </table>
        <?php
    }
}

?>
</div>
</body>
</html>
