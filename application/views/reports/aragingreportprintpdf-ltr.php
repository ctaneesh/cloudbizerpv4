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
                <td> <strong>Aged Receivables Report </td>
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
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Company') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Invoice Date') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Due Date') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Amount') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Paid') ?></th>
                <!-- <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Currency') ?></th> -->
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('1-30 Days') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('31-60 Days') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('61-90 Days') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('90 Days') ?></th>
                <th style="font-size: 12px;text-align:center;"><?php echo $this->lang->line('Total') ?></th>
            </tr>
        </thead>
        <tbody>
            <!-- Customer List -->
            <?php
            $report_total = 0;
            foreach ($customer_list as $row) {
                $customerId = $row['id'];
                ?>
                <tr class="customermain" style="border:solid 1px #ccc;">
                    <td colspan="11">
                        <button class="btn btn-info btn-sm expand-btn" data-toggle="collapse" data-target=".<?=$customerId?>">
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <?php echo $row['company']; ?>
                    </td>
                </tr>
                <div class="collapse" id="<?=$customerId?>">
                    <?php
                    $days30_total = 0;
                    $days60_total = 0;
                    $days90_total = 0;
                    $days90plus_total = 0;
                    $colwise_total = 0;
                    $txtcls1 = "";
                    $grand_total = 0;
                    $paid_total = 0;
                    foreach ($paymentlist as $key => $list) {
                        if (isset($paymentlist[$key][$customerId])) {
                            $grand =0;
                            $invoiceids = $list[$customerId]['invoiceid'];
                            $invoicetid = $list[$customerId]['invoice_number'];
                            $invoice_date = !empty($list[$customerId]['invoice_date']) ? date('d-m-Y', strtotime($list[$customerId]['invoice_date'])) : $list[$customerId]['invoice_date'];
                            $invoice_due_date = !empty($list[$customerId]['invoice_due_date']) ? date('d-m-Y', strtotime($list[$customerId]['invoice_due_date'])) : $list[$customerId]['invoice_due_date'];
                            $subtotal = $list[$customerId]['subtotal'];
                            $grand_total += $list[$customerId]['subtotal'];
                            $paid_total += $list[$customerId]['payment_recieved_amount'];
                            $days30 = ($list[$customerId]['30days'] > 0) ? $list[$customerId]['30days'] : 0.00;
                            $days60 = ($list[$customerId]['60days'] > 0) ? $list[$customerId]['60days'] : 0.00;
                            $days90 = ($list[$customerId]['90days'] > 0) ? $list[$customerId]['90days'] : 0.00;
                            $days90plus = ($list[$customerId]['90plus'] > 0) ? $list[$customerId]['90plus'] : 0.00;
                            $status = $list[$customerId]['status'];
                            if(($status== 'due' || $status==  'partial') && $days30>=1)
                            {
                                $txtcls1 = "text-danger";
                                $days30 =  $days30 * -1;
                                
                            }
                            else{
                                $txtcls1 = "";
                            }
                            if(($status== 'due' || $status==  'partial') && $days60>=1)
                            {
                                $txtclsdays60 = "text-danger";
                                $days60 =  $days60 * -1;
                            }
                            else{
                                $txtclsdays60 = "";
                            }
                            if(($status== 'due' || $status==  'partial') && $days90>=1)
                            {
                                $txtclsdays90 = "text-danger";
                                $days90 =  $days90 * -1;
                            }
                            else{
                                $txtclsdays90 = "";
                            }
                            if(($status== 'due' || $status==  'partial') && $days90plus>=1)
                            {
                                $txtclsdays90plus = "text-danger";
                                $days90plus =  $days90plus * -1;
                            }
                            else{
                                $txtclsdays90plus = "";
                            }
                            $days30_total = $days30_total + $days30;
                            $days60_total = $days60_total +  $days60;
                            $days90_total = $days90_total +  $days90;
                            $days90plus_total = $days90plus_total + $days90plus;
                            $grand = $days30 + $days60 + $days90 + $days90plus;
                            $colwise_total = $grand + $colwise_total;
                            
                            if($grand<1)
                            {
                                $txtclsgrand = "text-danger";
                            }
                            else{
                                $txtclsgrand = "";
                            }
                            ?>
                            <tr>
                                <td><?=$invoicetid?></td>
                                <td style="font-size: 12px;text-align:center;"><?=$invoice_date?></td>
                                <td style="font-size: 12px;text-align:center;"><?=$invoice_due_date?></td>
                                <td style="font-size: 12px;text-align:right;"><?=number_format($subtotal,2)?></td>
                                <td style="font-size: 12px;text-align:right;"><?=number_format($list[$customerId]['payment_recieved_amount'],2)?></td>
                                <!-- <td style="font-size: 12px;text-align:center;"><?=$config_currency?></td> -->
                                <td style="font-size: 12px;text-align:center;" class="<?=$txtcls1?>"><?=number_format($days30,2)?></td>
                                <td style="font-size: 12px;text-align:center;" class="<?=$txtclsdays60?>"><?=number_format($days60,2)?></td>
                                <td style="font-size: 12px;text-align:center;" class="<?=$txtclsdays90?>"><?=number_format($days90,2)?></td>
                                <td style="font-size: 12px;text-align:center;" class="<?=$txtclsdays90plus?>"><?=number_format($days90plus,2)?></td>
                                <td style="font-size: 12px;text-align:center;" class="<?=$txtclsgrand?>"><?=number_format($grand,2)?></td>
                            </tr>
                            
                            <?php
                        }
                        
                    }
                    if($colwise_total<1)
                    {
                        $txtclscolwise = "text-danger";
                    }
                    else{
                        $txtclscolwise = "";
                    }
                    if($days30_total<1)
                    {
                        $txtclsdays30_total = "text-danger";
                    }
                    else{
                        $txtclsdays30_total = "";
                    }
                    if($colwise_total<1)
                    {
                        $txtclscolwise = "text-danger";
                    }
                    else{
                        $txtclscolwise = "";
                    }
                    if($days60_total<1)
                    {
                        $txtclsdays60_total = "text-danger";
                    }
                    else{
                        $txtclsdays60_total = "";
                    }
                    if($days90_total<1)
                    {
                        $txtclsdays90_total = "text-danger";
                    }
                    else{
                        $txtclsdays90_total = "";
                    }
                    if($days90plus_total<1)
                    {
                        $txtclsdays90plus_total = "text-danger";
                    }
                    else{
                        $txtclsdays90plus_total = "";
                    }
                    ?>
                        <tr>
                        
                        
                            <td ><strong>Total</strong></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center"><strong><?=number_format($grand_total,2)?></strong></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center"><strong><?=number_format($paid_total,2)?></strong></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center <?=$txtclsdays30_total?>"><strong><?=number_format($days30_total,2)?></strong></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center <?=$txtclsdays60_total?>"><strong><?=number_format($days60_total,2)?></strong></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center <?=$txtclsdays90_total?>"><strong><?=number_format($days90_total,2)?></strong></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center <?=$txtclsdays90plus_total?>"><strong><?=number_format($days90plus_total,2)?></strong></td>
                            <td style="font-size: 12px;text-align:center;" class="text-center <?=$txtclscolwise?>"><strong><?php echo number_format($colwise_total,2); ?></strong></td>
                        </tr>
                </div>
                
                <?php
                
                $report_total = $colwise_total + $report_total;
                
            }
            ?>
        </tbody>
        
    </table>
    <hr>
    <div style="text-align:right;">
             <?php
                if($report_total<1)
                {
                $report_total =  $report_total * -1;
                }
                ?>
            <h6>
                Grand Total : <?php echo $config_currency." ".number_format($report_total,2); ?>
            </h6>
     </div>
    
</div>
</body>
</html>
