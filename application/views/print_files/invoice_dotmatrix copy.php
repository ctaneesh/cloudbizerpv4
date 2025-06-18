<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $invoice['invoice_number']; ?></title>
    <style>
/* For print */
@media print {
    @page {
        size: 180mm 107mm; /* width x height, set as you want */
        margin: 5mm;       /* inner margin */
    }

    html, body {
        width: 180mm;
        height: 107mm; /* If fixed page height is needed */
        margin: 0;
        padding: 0;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #000;
        -webkit-print-color-adjust: exact;
    }

    .no-print {
        display: none !important;
    }

    thead {
        display: table-header-group; /* Repeat table headers on each page */
    }
    
    tfoot {
        display: table-footer-group; /* Repeat table footers on each page */
    }
}

/* For screen preview */
@media screen {
    html, body {
        width: 210mm;
        margin: 10px auto;
        padding: 5mm;
        font-family: 'Courier New', monospace;
        font-size: 12px;
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
    border: 1px solid #ccc;
}

.wholebody {
    margin-left: <?php echo $margin_left; ?>mm;
    margin-right: <?php echo $margin_right; ?>mm;
}

/* Fixed Header */
.header-class {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 25mm; /* SMALLER! */
    background: #fff;
    border-bottom: 1px dashed #000;
    text-align: center;
    padding-top: 5mm;
    box-sizing: border-box;
}

/* Fixed Footer */
.footer-class {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20mm; /* SMALLER! */
    background: #fff;
    border-top: 1px dashed #000;
    text-align: center;
    padding-top: 2mm;
    box-sizing: border-box;
}

/* Main Content Area */
.content {
    margin-top: 30mm; /* leave space for header */
    margin-bottom: 25mm; /* leave space for footer */
    padding: 0 5mm;
    box-sizing: border-box;
}
.summary {
    page-break-inside: avoid;
    page-break-before: auto;
    page-break-after: auto;
}

</style>

</head>
<body >
<!-- For testing: <button onclick="window.print()" class="no-print">Print Invoice</button> -->
 <!-- Fixed Header -->
<div class="header-class">
    <h1>Company Name</h1>
    <p>Address Line 1 | Address Line 2</p>
</div>
<!-- Fixed Footer -->
<div class="footer-class">
    <p>Footer Notes | Terms and Conditions</p>
    <p>Contact: +123456789</p>
</div>
<div class="wholebody1 content">
    <!-- <div class="header-class">Header Section</div> -->
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
    <div class="summary">
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
    </div>
    <!-- <div class="footer-class">Footer Section</div> -->
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