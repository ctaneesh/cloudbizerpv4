<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $invoice['invoice_number']; ?></title>
    <style>
/* For print */
@media print {
    @page {
        size: 180mm 210mm; /* width x height */
        margin: 0; /* Remove default margins */
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #000;
        -webkit-print-color-adjust: exact;
    }

    .page {
        height: 210mm; /* Total page height */
        width: 180mm;
        position: relative;
        page-break-after: always;
        overflow: hidden;
    }

    .header-class {
        height: 25mm; /* Header height */
        border-bottom: 1px dashed #000;
        text-align: center;
        padding-top: 5mm;
    }

    .footer-class {
        height: 20mm; /* Footer height */
        border-top: 1px dashed #000;
        text-align: center;
        padding-top: 2mm;
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .content {
        height: calc(107mm - 45mm); /* Page height - (header + footer) */
        overflow: hidden;
        padding: 0 5mm;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td, th {
        padding: 5px 0;
        text-align: left;
        line-height: 1.2;
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

    tr {
        page-break-inside: avoid;
    }

    .summary {
        margin-top: 5mm;
    }
}

/* For screen preview */
@media screen {
    body {
        width: 210mm;
        margin: 10px auto;
        font-family: 'Courier New', monospace;
        font-size: 12px;
    }
    .page {
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }
}
</style>
</head>
<body>
    <?php
    // Calculate available content height (107mm total - 25mm header - 20mm footer)
    $availableHeight = 50; // in mm (107 - 45)
    
    // Calculate row height based on your content
    // Approximate row height in mm (adjust based on your actual content)
    $rowHeight = 10; // mm per row (estimate)
    
    // Calculate how many rows fit per page
    $rowsPerPage = floor($availableHeight / $rowHeight);
    
    // For the first page, subtract space for table header
    $rowsFirstPage = $rowsPerPage - 1;
    
    $totalItems = count($products);
    $currentItem = 0;
    $pageNumber = 1;
    $totalPages = ceil(($totalItems + 1) / $rowsPerPage); // +1 for header row
    
    while ($currentItem < $totalItems):
        $isFirstPage = ($pageNumber == 1);
        $isLastPage = ($currentItem + ($isFirstPage ? $rowsFirstPage : $rowsPerPage) >= $totalItems);
    ?>
    <div class="page">
        <div class="header-class">
            <h1>Company Name</h1>
            <p>Address Line 1 | Address Line 2</p>
        </div>
        
        <div class="content">
            <!-- <h2 class="text-center">INVOICE</h2>
            <table>
                <tr>
                    <td>Customer: <?php echo $invoice['name']; ?></td>
                    <td class="text-right">Invoice #: <?php echo $invoice['invoice_number']; ?></td>
                </tr>
                <tr>
                    <td>Phone: <?php echo $invoice['phone']; ?></td>
                    <td class="text-right">Date: <?php echo dateformat($invoice['invoicedate']); ?></td>
                </tr>
            </table> -->
            <table>
                <?php if ($isFirstPage): ?>
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
                <?php endif; ?>
                <tbody>
                    <?php 
                    $itemsThisPage = $isFirstPage ? $rowsFirstPage : $rowsPerPage;
                    $endItem = min($currentItem + $itemsThisPage, $totalItems);
                    
                    for ($i = $currentItem; $i < $endItem; $i++): 
                    ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= $products[$i]['product_code'] .' | ' .$products[$i]['product_name'].'<br>'.$products[$i]['arabic_name']; ?></td>
                        <td><?= $products[$i]['unit'] ?></td>
                        <td class="text-center"><?= $products[$i]['qty'] ?></td>
                        <td class="text-right"><?= number_format($products[$i]['price'], 2) ?></td>
                        <td class="text-right"><?= number_format($products[$i]['qty'] * $products[$i]['price'], 2) ?></td>
                    </tr>
                    <?php 
                    endfor;
                    $currentItem = $endItem;
                    ?>
                </tbody>
            </table>
            
            <?php if ($isLastPage): ?>
            <div class="summary margin-top">
                <table>
                    <tr>
                        <td colspan="3" class="text-right">SubTotal:</td>
                        <td class="text-right"><?= number_format($total, 2) ?></td>
                    </tr>
                    <?php if ($invoice['tax'] > 0): ?>
                    <tr>
                        <td colspan="3" class="text-right">Tax:</td>
                        <td class="text-right"><?= number_format($invoice['tax'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($invoice['discount'] > 0): ?>
                    <tr>
                        <td colspan="3" class="text-right">Discount:</td>
                        <td class="text-right"><?= number_format($invoice['discount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="3" class="text-right bold">Grand Total:</td>
                        <td class="text-right bold"><?= number_format($invoice['total'], 2) ?></td>
                    </tr>
                </table>
                <p class="text-center margin-top">Thank you for your business!</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="footer-class">
            <p>Page <?= $pageNumber ?> of <?= $totalPages ?></p>
            <p>Footer Notes | Terms and Conditions</p>
        </div>
    </div>
    <?php 
    $pageNumber++;
    endwhile; 
    ?>
</body>
</html>
<script>
window.addEventListener('load', function() {
    setTimeout(function() {
        window.print();
    }, 200);
});
</script>