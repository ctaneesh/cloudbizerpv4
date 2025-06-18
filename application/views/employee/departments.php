<div class="content-body">
    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>
    <div class="card">
        <div class="card-header">
            <h5 class="title">
                <?php echo $this->lang->line('Departments') ?> <a href="<?php echo base_url('employee/adddep') ?>"
                                                                  class="btn btn-primary btn-sm rounded">
                    <?php echo $this->lang->line('Add new') ?>
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
                    <table id="emptable" class="table table-striped table-bordered zero-configuration" cellspacing="0"
                        width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>


                            <th><?php echo $this->lang->line('Actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;

                        foreach ($department_list as $row) {
                            $aid = $row['id'];
                            $username = $row['val1'];
                            $name = $row['val2'];


                            echo "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . base_url("employee/department?id=$aid") . "' title='View'>" . $row['val1'] . "</a></td>
                            <td> <a href='" . base_url("employee/editdep?id=$aid") . "' class='btn btn-crud btn-secondary  btn-sm' title='Edit'><i class='fa fa-pencil'></i></a> <a href='#' data-object-id='$aid' class='btn btn-crud btn-secondary btn-sm delete-object  btn-sm' title='Delete'><span class='fa fa-trash'></span></a></td></tr>";
                            $i++;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>


                            <th><?php echo $this->lang->line('Actions') ?></th>
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
            { 'width': '5%' }, 
            { 'width': '25%' },
            { 'width': '' }
            ];
            //datatables
            $('#emptable').DataTable({
                // responsive: true, 
                <?php datatable_lang();?> dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ],
                "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                },
                ],
                'columns': columnlist
            });

            // $('#emptable').parent().css({
            //     'max-width': '100%', // Set the maximum height as per your requirement
            //     'overflow-x': 'scroll'
            // });
        });

        // $('.delemp').click(function (e) {
        //     e.preventDefault();
        //     $('#empid').val($(this).attr('data-object-id'));

        // });
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
                    <?php echo $this->lang->line('Delete');
                    echo ' ' . $this->lang->line('Department'); ?>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="object-id" value="">
                    <input type="hidden" id="action-url" value="employee/delete_dep">
                    <button type="button" data-dismiss="modal" class="btn btn-primary"
                            id="delete-confirm"><?php echo $this->lang->line('Yes') ?></button>
                    <button type="button" data-dismiss="modal"
                            class="btn"><?php echo $this->lang->line('No') ?></button>
                </div>
            </div>
        </div>
    </div>