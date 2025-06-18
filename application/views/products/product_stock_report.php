
<div class="content-body">

    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Products') ?> 
            <button onclick="showAlertQty()" class="btn btn-sm btn-secondary">show Low qty products</button>
            <button onclick="showAll()" class="btn btn-sm btn-secondary" >show all products</button></h5>
                <!-- <a href="<?php echo base_url('products/barcodeinvoke') ?>"
                        class="btn btn-primary btn-sm">
                    <?php echo "Barcode Generate" ?>
                </a>   -->
            </h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <div class="message"></div>
            </div>

            <div class="card-body">

                <hr>
                <div class="table-scroll1">
                <table id="productTable" class="table table-striped table-bordered w-100">
                    <thead>
                            <tr>
                                <th>Sl no</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Unit Cost</th>
                                <th>Selling Price</th>
                                <th>Onhand Qty</th>
                                <th>Alert Qty</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#productTable').DataTable({
            ajax: "<?= base_url('products/getallproduct') ?>",
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: "product_code" },
                { data: "product_name" },
                { data: "title" },
                { data: "product_cost" },
                { data: "product_price" },
                { data: "qty" },
                { data: "alert" }
            ]
        });
    });

    function showAlertQty() {
        var table = $('#productTable').DataTable();
        table.ajax.url("<?= base_url('products/getLowQtyproduct') ?>").load();
    }
    function showAll() {
        var table = $('#productTable').DataTable();
        table.ajax.url("<?= base_url('products/getallproduct') ?>").load();
    }
</script>