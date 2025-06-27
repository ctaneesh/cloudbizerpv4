<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Leads</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jstree/dist/themes/default/style.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jstree/dist/jstree.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/themes/default/style.min.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/jstree.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->

    <style>
    /* Remove default toggle arrows */
    .jstree-default .jstree-icon {
        background: none !important;
    }

    /* Closed node (collapsed) */
    .jstree-default .jstree-node>.jstree-ocl {
        background: none !important;
        display: inline-block;
        width: 16px;
        text-align: center;
    }

    .jstree-default .jstree-closed>.jstree-ocl::before {
        font-family: "Font Awesome 6 Free";
        content: "\f105";
        /* angle-right */
        font-weight: 900;
        color: #666;
    }

    /* Open node (expanded) */
    .jstree-default .jstree-open>.jstree-ocl::before {
        font-family: "Font Awesome 6 Free";
        content: "\f107";
        /* angle-down */
        font-weight: 900;
        color: #666;
    }

    #lead-table thead th {
        background-color: rgb(152, 146, 163);
        color: #000;
    }

    body,
    html {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }

    #main {
        height: 100vh;
        display: flex;
    }

    #tree-container {
        width: 25%;
        border-right: 1px solid #ddd;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
    }

    #tree {
        min-height: 400px;
    }

    #details-container {
        width: 85%;
        padding: 1rem;
        overflow-y: auto;
    }
    </style>
</head>

<body>

    <div id="main">
        <!-- Left Panel: Tree -->
        <div id="tree-container">
            <h5>Leads</h5>
            <div id="tree"></div>
        </div>

        <!-- Right Panel: Item Details -->
        <div id="details-container">
            <div class="card h-100">
                <div class="card-header">
                    <h5 id="lead-title" class="mb-0">Lead Details</h5>
                </div>
                <div class="card-body" id="details-content">
                    <!-- <p>Select an item to view its details.</p> -->
                </div>
            </div>
        </div>
    </div>

    <script>
    //  Get Leads and Quotes tree view
    $(function() {
        $('#tree').jstree({
            'core': {
                'themes': {
                    'icons': true
                },
                'data': {
                    'url': function(node) {
                        if (node.id === '#') {
                            return '<?= base_url("tree/get_leads") ?>';
                        } else if (node.id.startsWith('quote_')) {
                            return '<?= base_url("tree/get_sales_orders") ?>';
                        } else if (node.id.startsWith('so_')) {
                            return '<?= base_url("tree/get_delivery_notes") ?>';
                        } else if (node.id.startsWith('delivery_')) {
                            return '<?= base_url("tree/get_delivery_returns_invoice") ?>';
                        } else {
                            return '<?= base_url("tree/get_quote") ?>';
                        }
                    },
                    'data': function(node) {
                        return {
                            'id': node.id
                        };
                    }
                }
            },
            'plugins': ["wholerow"] // optional: better row click behavior
        })


    });

    $('#tree').on("select_node.jstree", function(e, data) {
        const node = data.node;
        const tree = $('#tree').jstree(true);

        const nodeId = node.id;

        if (node.parent === '#') {
            // It's a lead node
            $.ajax({
                url: '<?= base_url("tree/lead_details") ?>',
                method: 'GET',
                data: {
                    lead_number: nodeId
                },
                success: function(res) {
                    $('#lead-title').text('Lead Details - ' + nodeId);
                    $('#details-content').html(res);
                    $('#items-table').DataTable();
                },
                error: function() {
                    $('#details-content').html(
                        '<p class="text-danger">Error loading lead details.</p>');
                }
            });
        } else if (nodeId.startsWith('quote_')) {
            const quoteNumber = nodeId.replace('quote_', '');
            const leadId = tree.get_parent(node.id); // Get parent node ID = lead ID
            $.ajax({
                url: '<?= base_url("tree/quote_details") ?>',
                method: 'GET',
                data: {
                    quote_number: quoteNumber
                },
                success: function(res) {
                    // $('#lead-title').text('Quote Details - ' + quoteNumber);
                    $('#lead-title').text('Lead Id : ' + leadId + ' - Quote Details : ' +
                        quoteNumber);
                    $('#details-content').html(res);
                    $('#items-table').DataTable();
                },
                error: function() {
                    $('#details-content').html(
                        '<p class="text-danger">Error loading quote details.</p>');
                }
            });
        } else if (nodeId.startsWith('so_')) {
            const salesOrderNumber = nodeId.replace('so_', '');
            const quoteId = tree.get_parent(node.id);
            const leadId = tree.get_parent(quoteId);

            $.ajax({
                url: '<?= base_url("tree/sales_order_details") ?>',
                method: 'GET',
                data: {
                    sales_order_number: salesOrderNumber
                },
                success: function(res) {
                    $('#lead-title').text('Lead ID: ' + leadId + ' - Sales Order: ' +
                        salesOrderNumber);
                    $('#details-content').html(res);
                    $('#items-table').DataTable();
                },
                error: function() {
                    $('#details-content').html(
                        '<p class="text-danger">Error loading sales order details.</p>');
                }
            });
        } else if (node.id.startsWith('delivery_')) {
            const deliveryNoteNumber = node.id.replace('delivery_', '');
            const salesOrderId = tree.get_parent(node.id);
            const quoteId = tree.get_parent(salesOrderId);
            const leadId = tree.get_parent(quoteId);

            $.ajax({
                url: '<?= base_url("tree/delivery_note_details") ?>',
                method: 'GET',
                data: {
                    delivery_note_number: deliveryNoteNumber
                },
                success: function(res) {
                    $('#lead-title').text('Lead ID: ' + leadId + ' - Delivery Note: ' +
                        deliveryNoteNumber);
                    $('#details-content').html(res);
                    // No DataTable assumed here, remove if needed
                },
                error: function() {
                    $('#details-content').html(
                        '<p class="text-danger">Error loading delivery note details.</p>');
                }
            });
        } else if (node.id.startsWith('return_')) {
            const returnNumber = node.id.replace('return_', '');
            const deliveryId = tree.get_parent(node.id);
            const salesOrderId = tree.get_parent(deliveryId);
            const quoteId = tree.get_parent(salesOrderId);
            const leadId = tree.get_parent(quoteId);

            $.ajax({
                url: '<?= base_url("tree/delivery_return_details") ?>',
                method: 'GET',
                data: {
                    delivery_return_number: returnNumber
                },
                success: function(res) {
                    $('#lead-title').text('Lead ID: ' + leadId + ' - Return Details: ' +
                        returnNumber);
                    $('#details-content').html(res);
                    // No DataTable assumed here, remove if needed
                },
                error: function() {
                    $('#details-content').html(
                        '<p class="text-danger">Error loading delivery note details.</p>');
                }
            });
        } else if (node.id.startsWith('invoice_')) {
            const invoiceNumber = node.id.replace('invoice_', '');
            const deliveryId = tree.get_parent(node.id);
            const salesOrderId = tree.get_parent(deliveryId);
            const quoteId = tree.get_parent(salesOrderId);
            const leadId = tree.get_parent(quoteId);

            $.ajax({
                url: '<?= base_url("tree/delivery_invoice_details") ?>',
                method: 'GET',
                data: {
                    delivery_return_number: returnNumber
                },
                success: function(res) {
                    $('#lead-title').text('Lead ID: ' + leadId + ' - Invoice Details: ' +
                        returnNumber);
                    $('#details-content').html(res);
                    // No DataTable assumed here, remove if needed
                },
                error: function() {
                    $('#details-content').html(
                        '<p class="text-danger">Error loading delivery note details.</p>');
                }
            });
        }
    });
    </script>
