<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="title"> <?php echo $this->lang->line('Warehouses') ?> <a
                        href="<?php echo base_url('productcategory/addwarehouse') ?>"
                        class="btn btn-crud btn-primary btn-sm rounded">
                    <?php echo $this->lang->line('Add new') ?>
                </a>
            </h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <hr>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>

            <div class="card-body">

                <div class="table-container">
                    <table id="catgtable" class="table table-striped table-bordered zero-configuration" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>
                            <th><?php echo $this->lang->line('Store Owner') ?></th>
                            <th><?php echo $this->lang->line('Email') ?></th>
                            <th><?php echo $this->lang->line('Phone') ?></th>
                            <th><?php echo $this->lang->line('Currency') ?></th>
                            <th><?php echo $this->lang->line('Warehouse Type') ?></th>
                            <th><?php echo $this->lang->line('Action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;

                        foreach ($cat as $row) {
                            $cid = $row['store_id'];
                            $title = $row['store_name'];                           
                            $warehousetype = ($row['warehouse_type']=='Main') ? 'Default': $row['warehouse_type'];
                            echo "<tr>
                        <td>$i</td>
                        <td><a href='" . base_url("productcategory/viewwarehouse?id=$cid") . "' title='View'>".$title."</a></td>
                        <td>".$row['store_owner']."</td>
                        <td>".$row['store_email']."</td>
                        <td>".$row['store_phone']."</td>
                        <td>".$row['symbol']."</td>
                        <td>".$warehousetype."</td>
                        <td><a class='btn btn-crud btn-secondary  btn-sm' target='_blank' href='" . base_url() . "productcategory/warehouse_report?id=" . $cid . "' title='Reports'> <span class='fa fa-pie-chart'></span></a>&nbsp;<a href='" . base_url("productcategory/editwarehouse?id=$cid") . "' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></a>&nbsp;<a href='#' data-object-id='" . $cid . "' class='btn btn-crud btn-secondary btn-sm delete-object' title='Delete'><i class='fa fa-trash-o'></i></a></td></tr>";
                            $i++;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>
                            <th><?php echo $this->lang->line('Warehouse Type') ?></th>
                            <th><?php echo $this->lang->line('Total Products') ?></th>
                            <th><?php echo $this->lang->line('Stock Quantity') ?></th>
                            <th><?php echo $this->lang->line('Worth (Sales/Stock)') ?></th>
                            <th><?php echo $this->lang->line('Action') ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var columnlist = [
                { 'width': '3%' }, 
                { 'width': '20%' },
                { 'width': '13%' },
                { 'width': '10%' }, 
                { 'width': '10%' },
                { 'width': '5%' },
                { 'width': '' },
                ];
            //datatables
            $('#catgtable').DataTable({
                // responsive: true, 
                <?php datatable_lang();?>
                 dom: 'Blfrtip',
                'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,
                    }
                ],
                'columns': columnlist,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ],
            });
            // $('#catgtable').parent().css({
            // 'max-width': '100%', // Set the maximum height as per your requirement
            // 'overflow-x': 'scroll'
            // });



        });
    </script>
    <div id="delete_model" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p><?php echo $this->lang->line('delete this product warehouse') ?></strong></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="object-id" value="">
                    <input type="hidden" id="action-url" value="productcategory/delete_warehouse">
                    <button type="button" data-dismiss="modal" class="btn btn-primary"
                            id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                    <button type="button" data-dismiss="modal"
                            class="btn"><?php echo $this->lang->line('Cancel') ?></button>
                </div>
            </div>
        </div>
    </div>