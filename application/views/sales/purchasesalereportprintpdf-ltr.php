<!doctype html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Sales Report</title>

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
                <td> <strong>Customer Sales Report </td>
                <td style="text-align:center"><strong>Prepared By  : <?php echo ucfirst($this->session->userdata('orgname')); ?> </td>
                <td style="text-align:right;"><strong>Date & Time : <?php echo date('d-m-Y h:i:sa')?> </td>
                
            </tr>
        </thead>
    </table>
  
    <table>
        
            <thead>
                            <tr class="item_header bg-gradient-directional-blue white">
                                <th class="text-center1 pl-1"><?php echo $this->lang->line('Sl No') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Item Code') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Item Description') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Quantity') ?> <?php echo $this->lang->line('sold') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Cost') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Sale Date') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Purchase Date') ?></th>
                                <th class="text-center no-sort"><?php echo $this->lang->line('Quantity Purchased') ?></th>
                                <th class="text-right no-sort"><?php echo $this->lang->line('Onhand') ?>
                            
                            </tr>
                </thead>
         <tbody>
                            <!-- List -->
                            <?php
                            $report_total = 0;
                            $report_item_total = 0;
                            $report_sub_total = 0;
                            $report_cost_total = 0; ?>
                    
                                <div class="collapse" id="<?=$customerId?>">
                                    <?php
                                    $item_total = 0;
                                    $purchitem_total = 0;
                                    $sub_total_cost = 0;
                                 
                                     $i=1;
                                    foreach ($lists as $key => $list) {
                                       
                                            $grand =0;
                                            $created_date = !empty($list['created_date']) ? date('d-m-Y', strtotime($list['created_date'])) : $list['created_date'];
                                         
                                            $product_name = $list['product_name'];
                                            $product_des = $list['product_des'];
                                            $product_qty = $list['product_qty'];
                                            $purchase_date = $list['purchase_date'];
                                            $cost = $list['cost'];
                                            $purchqty = $list['purchqty'];
                                            $total_cost = $list['cost'];
                                            $onhand = $list['onhand'];
                                            $item_total = $item_total +  $product_qty;
                                            $purchitem_total = $purchitem_total +  $purchqty;
                                            $sub_total_cost = $sub_total_cost + $total_cost;
                                            
                                            if($product_qty<1){ $txtclsqty = "text-danger";   } else{ $txtclsqty = ""; }
                                            if($product_price<1){ $txtclsprice = "text-danger";   } else{ $txtclsprice = ""; }
                                            if($profit<1){ $txtclsprofit = "text-danger";   } else{ $txtclsprofit = ""; }
                                            
                                           
                                            ?>

                                            <tr class="" >
                                                <td><?=$i?></td>            
                                                <td class="text-center"><?=$product_name?></td>
                                                <td><?=$product_des?></td>  
                                                <td class="text-center <?=$txtclsqty?>"><?=$product_qty?></td>
                                                <td class="text-right <?=$txtclsprice?>"><?=$cost?></td>
                                                <td class="text-center"><?=$created_date?></td>
                                                <td class="text-center"><?=$purchase_date?></td>
                                                <td class="text-center"><?=$purchqty?></td>
                                                <td class="text-center"><?=$onhand?></td>
                                               
                                            </tr>
                                            
                                            <?php $i++;
                                        }        
                                   
                                    
                                    ?>
                                   
                                        <tr class="">
                                            <td><strong>Total</strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center "><strong><?=number_format($item_total)?></strong></td>
                                            <td class="text-right "><strong> <?php echo number_format($sub_total_cost,2); ?></strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center "><strong> <?php echo number_format($purchitem_total); ?></strong></td>
                                            
                                        </tr>

                                        
                                </div>
                                <?php
                               
                                $report_total = $profit_total + $report_total;
                                $report_item_total = $item_total + $report_item_total;
                                $report_cost_total = $sub_total_cost + $report_cost_total;
                                $report_purchitem_total = $purchitem_total + $report_purchitem_total;
                            
                             if($report_total<1)
                             {
                                $report_total =  $report_total * -1;
                             }
                      
                            ?>
                                       <tr>
                                            <td class="text-center"></td> 
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center "><strong>Total Sold Items : <?=number_format($report_item_total)?></strong></td>
                                            <td class="text-right "><strong>Sub Total Cost : <?php echo number_format($report_cost_total,2); ?></strong></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center "><strong>Total Purchased Items : <?=number_format($report_purchitem_total)?></strong></td>
                                        </tr> 
                        </tbody>

                    </table>
                   
    <hr>
  
       
     </div> 
    
</div>
</body>
</html>
