<div class="content-body">
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
    <div class="card">
        <div class="card-header">
            <h5 class="title"> <?php echo $this->lang->line('Brands') ?> <a
                        href="<?php echo base_url('brand/add') ?>"
                        class="btn btn-primary btn-sm rounded"><?php echo $this->lang->line('Add new'); ?>
                </a> 
            </h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table id="catgtable" class="table table-striped table-bordered zero-configuration w-100">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>
                            <th><?php echo $this->lang->line('Status') ?></th>
                            <th><?php echo $this->lang->line('Action') ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                        foreach ($cat as $row) {
                            $cid = $row['id'];
                            $title = $row['title'];
                            echo "<tr>
                                <td>$i</td>
                                <td><a href='" . base_url("brand/edit?id=$cid") . "' >$title</a></td>
                                <td>".$row['status']."</td>
                                <td><a href='" . base_url("brand/edit?id=$cid") . "' class='btn btn-secondary btn-sm btn-crud' title='Edit'><i class='fa fa-pencil'></i> </a></td>
                            </tr>";


                            $i++;
                        }
                        ?>
                        </tbody>
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
        { 'width': '10%' }, 
        { 'width': '' }
        ];

            //datatables
            $('#catgtable').DataTable({
                //responsive: true,
                <?php datatable_lang();?> 
                "columnDefs": [
                        {
                            "targets": [0], //first column / numbering column
                            "orderable": false, //set not orderable
                        },
                    ],
                'columns': columnlist,
                dom: 'Blfrtip',
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
                    <p><?php echo $this->lang->line('delete this product category') ?></strong></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="object-id" value="">
                    <input type="hidden" id="action-url" value="productcategory/delete_i">
                    <button type="button" data-dismiss="modal" class="btn btn-primary"
                            id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                    <button type="button" data-dismiss="modal"
                            class="btn"><?php echo $this->lang->line('Cancel') ?></button>
                </div>
            </div>
        </div>
    </div>