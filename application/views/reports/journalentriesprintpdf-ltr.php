<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Journal Entries Report</title>

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

        /* .report-top-table td:nth-child(4),
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
        } */
      
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
            <td> <strong>Journal Entries Report</td>
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
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Debit</th>
            <th style="text-align: right; padding: 10px;border-bottom: 1px solid #ddd;">Credit</th>
        </tr>
    </table>
    <?php
        if(!empty($journal_headers))
        {
            foreach ($journal_headers as $value) 
            {
                $header_flg=1;
                $creditamount_total = 0.00;
                $debitamount_total = 0.00;    
                ?>
            <table style="width:100%; border-collapse: collapse; font-size:14px; margin-top:25px;" class="report-content-table">
                <?php
                foreach ($journal_data[$value['transaction_number']] as $key => $row) {
                    if($header_flg==1)
                    {
                        $invoiceid = $row['invoiceid'];
                        $refnumber = $row['bank_transaction_refernce'];
                        $date = ($row['date']) ? date('d M Y', strtotime($row['date'])) : "";
                        $relation = ($row['bank_transaction_number']) ? $row['bank_transaction_refernce'] : $row['transaction_number'];
                        echo '<thead>
                            <tr>
                                <th colspan="3" style="padding: 10px 0; font-weight: bold; font-size: 16px;border-bottom: 1px solid #ddd;text-align:left;">
                                    '.date('d M Y', strtotime($value['date'])).' <span style="font-size: 12px;font-weight: 500;">'.$relation.'</span>
                                    <span><i class="fa fa-angle-down"></i></span>
                                </th>
                            </tr>
                        </thead>';
                        $header_flg=0;
                    }
                    $creditamount = 0.00;
                    $debitamount = 0.00;
                    $code = $row['acn'];
                    $debitamount = $row['debitamount'];
                    $creditamount = $row['creditamount'];
                    $creditamount_total += $row['creditamount'];
                    $debitamount_total += $row['debitamount'];
                    echo '<tbody >
                                <tr style="border-bottom: 1px solid #ddd; font-weight: 400;">
                                    <td scope="col">'.$row['holder'].'</td>
                                    <td style="padding: 10px; text-align: right;font-weight: 400;">'.number_format($debitamount,2).'</td>
                                    <td style="padding: 10px; text-align: right;font-weight: 400;">'.number_format($creditamount,2).'</td>
                                </tr>
                            </tbody>';
                }
                echo '<tfoot>
                        <tr>
                            <th  style="padding: 10px; text-align: right;font-weight: bold;">'.$this->lang->line('Total').'</th>
                            <th  style="padding: 10px; text-align: right;font-weight: bold;">'.number_format($creditamount_total,2).'</th>
                            <th style="padding: 10px; text-align: right;font-weight: bold;">'.number_format($debitamount_total,2).'</th>
                        </tr>
                    </tfoot>';
                ?>
            </table>
               
    <?php
        }
        }
        
        ?>
    </div>
</div>
</body>
</html>
