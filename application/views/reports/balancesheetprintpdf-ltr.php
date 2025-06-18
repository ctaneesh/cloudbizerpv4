<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aged Receivables Report</title>

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
            <td> <strong>Balance Sheet Report</td>
                <td style="text-align:right"><strong>Prepared By  : <?php echo ucfirst($this->session->userdata('orgname')); ?> 
                <br><strong>Date & Time : <?php echo date('d-m-Y h:i:sa')?> </td>
                
            </tr>
        </thead>
    </table>
    <hr>

    <?php
    $output ="";
    if (!empty($nestedArray)) {
        $assettotal = 0;
        $liabilitytotal = 0;
        $output = '<table border="0" cellpadding="5" cellspacing="0" style="width:100%; border: none; ">';
        
        foreach ($nestedArray as $headerId => $types) {
            $accountHeader = $types[array_key_first($types)][0]['account_header'];
    
            // Sum assets and liabilities
            if ($accountHeader === 'Assets') {
                $assettotal += $headerSums[$headerId];
            } elseif ($accountHeader === 'Liabilities') {
                $liabilitytotal += $headerSums[$headerId];
            }
           
            // Account Header Row
            $output .= '<tr ><td colspan="2" style="font-size:18px; font-weight:600; border:none;">' . $accountHeader . '</td>';
            $output .= '<td style="text-align:right; font-size:18px; font-weight:600; border:none;">' . number_format($headerSums[$headerId], 2) . '</td></tr>';
            
            // Loop through account types
            foreach ($types as $typeId => $accounts) {
                $output .= '<tr><td colspan="3" style="font-size:14px; font-weight:600; padding-left:20px; ">' . $accounts[0]['account_type'] . '</td></tr>';
                $output .= '<tr><td colspan="3" style="border:none;">';
                $output .= '<table cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">';
                
                foreach ($accounts as $account) {
                    $account_type = $account['account_type'];   
                    $amount = ($account_type=='Contra-Asset') ? "(".number_format($account['amount'], 2).")" : number_format($account['amount'], 2);
                    $output .= '<tr>';
                    $output .= '<td style="padding-left:25px;font-size:13px; font-weight:400;">' . htmlspecialchars($account['account_name']) . '</td>';
                    $output .= '<td style="text-align:right; font-size:13px; font-weight:400;">' .$amount . '</td>';
                    $output .= '</tr>';
                }
              
                $output .= '</table></td></tr>';
            }
        }
    
        // Add Equity Section
        $equitytotal = $assettotal - abs($liabilitytotal);
        // $output .= '<tr><td colspan="2" style="font-size:18px; font-weight:600; border:none;">Equity</td>';
        // $output .= '<td style="text-align:right; font-size:18px; font-weight:600; border:none;">' . number_format($equitytotal, 2) . '</td></tr>';
    
        // $output .= '<tr><td colspan="3" style="font-size:15px; font-weight:600; padding-left:20px; border:none;">Equity</td></tr>';
        // $output .= '<tr><td colspan="3" style="border:none;">
        //                 <table cellpadding="5" cellspacing="0" style="width:100%;  border-collapse: collapse;">
        //                     <tr>
        //                         <td style="padding-left:25px; font-size:13px; font-weight:400;">Current Year Earnings</td>
        //                         <td style="text-align:right;font-size:13px; font-weight:400; ">' . number_format($equitytotal, 2) . '</td>
        //                     </tr>
        //                 </table>
        //             </td></tr>';
    
        $output .= '</table>'; // End the main table
    
        // Generate the PDF using a library like TCPDF, MPDF, or DomPDF
        echo $output;
    } else {
        echo '<p>No data available for the balance sheet.</p>';
    }
    
    ?>
</div>
</body>
</html>
