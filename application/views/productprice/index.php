<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <div class="message"></div>
    </div>
    <div class="card-body">
        <h5 class="title"> <?php echo $this->lang->line('Product With Stock') ?> </h5>

        <hr>
        <!-- Wrap the table inside a div with overflow handling -->
        <div style="overflow-x: auto;">
            <table id="catgtable" class="table table-striped1 table-bordered zero-configuration1" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo $this->lang->line('ProductID') ?></th>
                        <th><?php echo $this->lang->line('Description') ?></th>
                        <th><?php echo $this->lang->line('Unit') ?></th>
                         <th><?php echo $this->lang->line('Onhand') ?></th>
                        <th><?php echo $this->lang->line('Customer Order') ?></th>
                        <th><?php echo $this->lang->line('Purchase Order') ?></th>
                        <th><?php echo $this->lang->line('IN Transist') ?></th>
                        <th><?php echo $this->lang->line('Cost') ?></th>
                        <th><?php echo $this->lang->line('Selling Price') ?></th>
                        <th><?php echo $this->lang->line('Wholesale Price') ?></th>
                        <th><?php echo $this->lang->line('Web Price') ?></th>
                        <th><?php echo $this->lang->line('Minimum Price') ?></th>
                        <th><?php echo $this->lang->line('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($units as $row) {
                        $cid = $row['pid'];
                        $minprice = $row['min_price'];
                        $sellingprice = $row['product_price'];
                        $wholesale = $row['wholesale_price'];
                        $webprice = $row['web_price'];
                        $cost = $row['item_cost'];
                        $product_code = $row['product_code'];
                        $product_name = $row['product_name'];
                        $unit = $row['unit'];
                        $onhand = $row['qty'];
                        $customer_order = $row['customer_order'];
                        $purchase_order = $row['purchase_order'];

                        echo "<tr>
                            <td>$i</td>
                            <td>$product_code</td>
                            <td>$product_name</td>
                            <td>$unit</td>
                            <td>$onhand</td>
                            <td>$customer_order</td>
                            <td>$purchase_order</td>
                            <td>0</td>
                            <td>$cost</td>
                            <td>$sellingprice</td>
                            <td>$wholesale</td>
                            <td>$webprice</td>
                            <td>$minprice</td>                 
                            <td>--</td>
                        </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        //datatables
        $('#catgtable').DataTable({responsive: false});

    });
</script>