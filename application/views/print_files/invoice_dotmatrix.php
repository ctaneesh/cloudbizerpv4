<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $invoice['invoice_number']; ?></title>
    <style>
/* For print */
    body {
        visibility: visible;
    }
@media print {
    @page {
        size: <?=$page_width?>mm <?=$page_height?>mm; /* width x height */
        margin: 0;
    }

    body {
        visibility: visible;
        margin: 0;
        padding: 0;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #000;
        -webkit-print-color-adjust: exact;
    }

    .page {
        height: <?=$page_height?>mm;
        width: <?=$page_width?>mm;
        position: relative;
        page-break-after: always;
        overflow: hidden;
    }

    .header-class {
        height: <?=$header_height?>mm;
        /* border-bottom: 1px dashed #000; */
        text-align: center;
        padding-top: 5mm;
    }

    .footer-class {
        height: <?=$footer_height?>mm;
        /* border-top: 1px dashed #000; */
        text-align: center;
        padding-top: 2mm;
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .content {
        height: calc(210mm - 25mm - 20mm); /* 165mm */
        overflow: hidden;
        padding: 0 5mm;
        margin-left : <?=$margin_left?>mm;
        margin-right : <?=$margin_right?>mm;
    }
    .headersection
    {

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
        width: <?=$page_width?>mm;
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

$pageHeight = $page_height; // mm
$headerHeight = $header_height; // mm
$footerHeight = $footer_height; // mm
$contentHeight = $pageHeight - ($headerHeight + $footerHeight); // 165mm
if($bill_details!='First Page') {
    $contentHeight = $contentHeight - $bill_details_height;
}
$rowHeightEstimate = $row_height; // mm (estimate per row based on font + padding)
$rowsPerPage = floor($contentHeight / $rowHeightEstimate);
$rowsFirstPage = $rowsPerPage - 1; // First page has header row
$totalItems = count($products);
$currentItem = 0;
$pageNumber = 1;
// echo $rowsPerPage;
$totalPages = ceil(($totalItems + 1) / $rowsPerPage); // +1 because table header

$isFirstPage = 1;
while ($currentItem < $totalItems):
    $isFirstPage = ($pageNumber == 1);
    $isLastPage = ($currentItem + ($isFirstPage ? $rowsFirstPage : $rowsPerPage) >= $totalItems);
?>
    <div class="page">
        <div class="header-class">
            <!-- <h1>Company Name</h1>
            <p>Address Line 1 | Address Line 2</p> -->
        </div>
        
        <div class="content">
        <?php if ($bill_details=='First Page' && $pageNumber==1){
            ?>
            <div class="headersection">
                <h2 class="text-center">INVOICE</h2>
                <table>
                    <tr>
                        <td>Customer: <?php echo $invoice['name']; ?></td>
                        <td class="text-right">Invoice : <?php echo $invoice['invoice_number']; ?></td>
                    </tr>
                    <tr>
                        <td>Phone: <?php echo $invoice['phone']; ?></td>
                        <td class="text-right">Date: <?php echo dateformat($invoice['invoicedate']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Prepared By: <?php echo ($employee['name']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Due Date: <?php echo dateformat($invoice['invoiceduedate']); ?></td>
                    </tr>
                </table>
            </div>
            <?php }
            else if($bill_details!='First Page'){ ?>
             <div class="headersection">
                <h2 class="text-center">INVOICE</h2>
                <table>
                    <tr>
                        <td>Customer: <?php echo $invoice['name']; ?></td>
                        <td class="text-right">Invoice : <?php echo $invoice['invoice_number']; ?></td>
                    </tr>
                    <tr>
                        <td>Phone: <?php echo $invoice['phone']; ?></td>
                        <td class="text-right">Date: <?php echo dateformat($invoice['invoicedate']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Prepared By: <?php echo ($employee['name']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right">Due Date: <?php echo dateformat($invoice['invoiceduedate']); ?></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
            <table>
                <?php if ($display_item_labels=='No' && $pageNumber==1){ ?>
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
                <?php }
                else if ($display_item_labels!='No'){ ?>
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
                    <?php }; ?>
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
                <p class="text-left margin-top">Goods return or exchange will be acceptable with in 15 days from the invoice date</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="footer-class">
            <!-- <p>Page <?= $pageNumber ?> of <?= $totalPages ?></p>
            <p>Footer Notes | Terms and Conditions</p> -->
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
