<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $invoice['invoice_number']; ?></title>
    <style>
    /* For print */
    @media print {
        @page {
            /* size: 80mm auto;   */
            margin: 0;
        }
        
        body {
            /* width: 80mm; */
            margin: 0;
            padding: 5mm;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #000;
            -webkit-print-color-adjust: exact;
            width: 210mm;
            height :150mm;
        }
        
        .no-print {
            display: none !important;
        }
    }
    
    /* For screen preview */
    @media screen {
        body {
            /* width: 180mm; */
            /* border: 1px dashed #ccc; */
            width: 210mm;
            height :150mm;
            margin: 10px auto;
            padding: 5mm;
        }
    }
    
    /* Common styles */
    body {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #000;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    td, th {
        padding: 5px 0;
        text-align: left;
    }
    
    .border-bottom {
        border-bottom: 1px dashed #000;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-center {
        text-align: center;
    }
    
    .margin-top {
        margin-top: 10px;
    }
    
    .bold {
        font-weight: bold;
    }
    .header-class {
        height: <?php echo $header_height; ?>mm;
    }
    .footer-class {
        height: <?php echo $footer_height; ?>mm;
        border : 1px solid #ccc;
    }
    .wholebody
    {
        margin-left : <?php echo $margin_left; ?>mm;
        margin-right : <?php echo $margin_right; ?>mm;
    }
</style>
</head>
<body class="wholebody">
<!-- For testing: <button onclick="window.print()" class="no-print">Print Invoice</button> -->
<div >
    <div class="header-class">Header Section</div>
    <h2 class="text-center">INVOICE</h2>
    <table>
        <tr>
            <td>Customer: <?php echo $invoice['name']; ?></td>
            <td class="text-right">Invoice #: <?php echo $invoice['invoice_number']; ?></td>
        </tr>
        <tr>
            <td>Phone: <?php echo $invoice['phone']; ?></td>
            <td class="text-right">Date: <?php echo dateformat($invoice['invoicedate']); ?></td>
        </tr>
    </table>

    <table class="margin-top">
        <thead class="border-bottom">
            <tr>
                <th>Sl</th>
                <th>Description</th>
                <th>UoM</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n = 1;
            $total = 0;
            foreach ($products as $row):
                $line_total = $row['qty'] * $row['price'];
                $total += $line_total;
            ?> 
            <tr>
                <td><?php echo $n++; ?></td>
                <td><?php echo $row['product_code'] .' | ' .$row['product_name'].'<br>'.$row['arabic_name']; ?></td>
                <td><?php echo $row['unit']; ?></td>
                <td class="text-center"><?php echo $row['qty']; ?></td>
                <td class="text-right"><?php echo number_format($row['price'], 2); ?></td>
                <td class="text-right"><?php echo number_format($line_total, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="margin-top">
        <tr>
            <td colspan="3" class="text-right">SubTotal:</td>
            <td class="text-right"><?php echo number_format($total, 2); ?></td>
        </tr>
        <?php if ($invoice['tax'] > 0): ?>
        <tr>
            <td colspan="3" class="text-right">Tax:</td>
            <td class="text-right"><?php echo number_format($invoice['tax'], 2); ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($invoice['discount'] > 0): ?>
        <tr>
            <td colspan="3" class="text-right">Discount:</td>
            <td class="text-right"><?php echo number_format($invoice['discount'], 2); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td colspan="3" class="text-right bold">Grand Total:</td>
            <td class="text-right bold"><?php echo number_format($invoice['total'], 2); ?></td>
        </tr>
    </table>

    <p class="text-center margin-top">Thank you for your business!</p>
    <div class="footer-class">Footer Section</div>
</div>
<script>
    // Auto-print when page loads (remove if you want manual print)
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 200);
    });
</script>

</body>
</html>