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
    <table class="report-top-table" style="width:100%; border-collapse: collapse; font-size:14px;">
        <!-- Header Row -->
        <tr style="border-bottom: 1px solid #ddd;">
            <th style="text-align: left; padding: 10px;border-bottom: 1px solid #ddd;"></th>
            <th style="text-align: left; padding: 10px;border-bottom: 1px solid #ddd;">Jan - Mar(<?=date('Y')?>)</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Apr - Jun(<?=date('Y')?>)</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Jul - Sep(<?=date('Y')?>)</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Oct-Dec(<?=date('Y')?>)</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Total</th>
        </tr>
    </table>

    <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: left;font-size:18px;">Income <span class=""></th>
            </tr>
        </thead>
        <tbody class="transaction-details">

            <?php
             $grand_income_first_credit = 0;
             $grand_income_second_credit = 0;
             $grand_income_third_credit = 0;
             $grand_income_fourth_credit = 0;
             $grand_income_total_credit = 0;

             $grand_income_first_debit = 0;
             $grand_income_second_debit = 0;
             $grand_income_third_debit = 0;
             $grand_income_fourth_debit = 0;
             $grand_income_total_debit = 0;

             $grand_income_first = 0;
             $grand_income_second = 0;
             $grand_income_third = 0;
             $grand_income_fourth = 0;
             $grand_income_total = 0;
            if($income)
            {
                foreach ($income as $key => $value) {
                    $account_id = $value['account_id'];
                    $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                    $second = ($value['quarter_label']=='Second') ? abs($value['amount']):0.00;
                    $third = ($value['quarter_label']=='Third') ? abs($value['amount']):0.00;
                    $fourth = ($value['quarter_label']=='Fourth') ? abs($value['amount']):0.00;
                    $total = ($first+$second+$third+$fourth);

                    if($value['transtype']=='credit')
                    {
                         $grand_income_first_credit += $first;
                         $grand_income_second_credit += $second;
                         $grand_income_third_credit += $third;
                         $grand_income_fourth_credit += $fourth;
                         $grand_income_total_credit += $total;
                    }
                    else{
                         $grand_income_first_debit += $first;
                         $grand_income_second_debit += $second;
                         $grand_income_third_debit += $third;
                         $grand_income_fourth_debit += $fourth;
                         $grand_income_total_debit += $total;
                    }
                    echo '<tr>';
                    echo '<td scope="col" style="padding: 10px 0; font-size: 14px;text-align:left;">'.$value['holder'].'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($first,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($second,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($third,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($fourth,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($total,2).'</td>';
                    echo '</tr>';
                }
            }
            else{
                
                echo '<tr>';
                echo '<td scope="col">'.$this->lang->line('No records found').'</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="col"><?php echo $this->lang->line('Total'); ?></th>
                <?php
                $grand_income_first  = abs($grand_income_first_debit   - $grand_income_first_credit);   
                $grand_income_second = abs($grand_income_second_debit  - $grand_income_second_credit);  
                $grand_income_third  = abs($grand_income_third_debit   - $grand_income_third_credit);  
                $grand_income_fourth = abs($grand_income_fourth_debit  - $grand_income_fourth_credit); 
                $grand_income_total  = abs($grand_income_total_debit   - $grand_income_total_credit); 
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_first,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_second,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_third,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_fourth,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_total,2).'</th>';
                ?>

            </tr>
        </tfoot>

    </table>
    <hr>
    <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: left;font-size:18px;">Expense <span class=""></th>
            </tr>
        </thead>
        <tbody class="transaction-details">

            <?php
            $grand_expense_first = 0;
            $grand_expense_second = 0;
            $grand_expense_third = 0;
            $grand_expense_fourth = 0;
            $grand_expense_total = 0;

            $grand_expense_first_credit = 0;
            $grand_expense_second_credit = 0;
            $grand_expense_third_credit = 0;
            $grand_expense_fourth_credit = 0;
            $grand_expense_total_credit = 0;

            $grand_expense_first_debit = 0;
            $grand_expense_second_debit = 0;
            $grand_expense_third_debit = 0;
            $grand_expense_fourth_debit = 0;
            $grand_expense_total_debit = 0;
            if($expense)
            {
                foreach ($expense as $key => $value) {
                    $account_id = $value['account_id'];
                    $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                    $second = ($value['quarter_label']=='Second') ? abs($value['amount']):0.00;
                    $third = ($value['quarter_label']=='Third') ? abs($value['amount']):0.00;
                    $fourth = ($value['quarter_label']=='Fourth') ? abs($value['amount']):0.00;
                    $total = ($first+$second+$third+$fourth);

                    if($value['transtype']=='credit')
                    {
                        $grand_expense_first_credit += $first;
                        $grand_expense_second_credit += $second;
                        $grand_expense_third_credit += $third;
                        $grand_expense_fourth_credit += $fourth;
                        $grand_expense_total_credit += $total;
                    }
                    else{
                        $grand_expense_first_debit += $first;
                        $grand_expense_second_debit += $second;
                        $grand_expense_third_debit += $third;
                        $grand_expense_fourth_debit += $fourth;
                        $grand_expense_total_debit += $total;
                    }
                    echo '<tr>';
                    echo '<td scope="col" style="padding: 10px 0; font-size: 14px;text-align:left;">'.$value['holder'].'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($first,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($second,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($third,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($fourth,2).'</td>';
                    echo '<td style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($total,2).'</td>';
                    echo '</tr>';
                }
            }
            else{
                
                echo '<tr>';
                echo '<td scope="col">'.$this->lang->line('No records found').'</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        <tfoot>
            <tr>
                <th scope="col"><?php echo $this->lang->line('Total'); ?></th>
                <?php
                $grand_expense_first  = abs($grand_expense_first_debit   - $grand_expense_first_credit);   
                $grand_expense_second = abs($grand_expense_second_debit  - $grand_expense_second_credit);  
                $grand_expense_third  = abs($grand_expense_third_debit   - $grand_expense_third_credit);  
                $grand_expense_fourth = abs($grand_expense_fourth_debit  - $grand_expense_fourth_credit); 
                $grand_expense_total  = abs($grand_expense_total_debit   - $grand_expense_total_credit); 
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_expense_first,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_expense_second,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_expense_third,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_expense_fourth,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_expense_total,2).'</th>';
                ?>

            </tr>
        </tfoot>

    </table>
    <hr>
    <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
        <thead>

            <tr>
                <th scope="col"><?php echo $this->lang->line('Net Profit'); ?></th>
                <?php
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_first-$grand_expense_first,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_second-$grand_expense_second,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_third-$grand_expense_third,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_fourth-$grand_expense_fourth,2).'</th>';
                echo '<th style="padding: 10px 0; font-size: 14px;text-align:right;">'.number_format($grand_income_total-$grand_expense_total,2).'</th>';
            ?>
            </tr>
        </thead>
    </table>
    
    </div>
</div>
</body>
</html>
