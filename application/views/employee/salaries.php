<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5 class="title">
                <?php echo $this->lang->line('Employee') ?> <a href="<?php echo base_url('employee/add') ?>"
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
                    <table id="emptable" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>
                            <th><?php echo $this->lang->line('Salary') ?></th>
                            <th>Role</th>
                            <th><?php echo $this->lang->line('Status') ?></th>

                            <th><?php echo $this->lang->line('Actions') ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;

                        foreach ($employee as $row) {
                            $aid = $row['id'];
                            $username = $row['username'];
                            $name = $row['name'];
                            $role = user_role($row['roleid']);
                            $status = $row['banned'];
                            $salary = amountExchange($row['salary'], 0, $row['loc']);

                            if ($status == 1) {
                                $status = '<span class="st-inactive">Deactive</span>';

                            } else {
                                $status = '<span class="st-active">Active</span>';

                            }

                            echo "<tr>
                        <td>$i</td>
                        <td>$name</td>
                            <td>$salary</td>
                        <td>$role</td>                 
                        <td>$status</td>
                    
                        <td><a href='" . base_url("employee/history?id=$aid") . "' class='btn btn-crud btn-success btn-sm' target='_blank' title='History'><i class='fa fa-list-ul'></i> </a></td></tr>";
                            $i++;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Name') ?></th>
                            <th><?php echo $this->lang->line('Salary') ?></th>
                            <th>Role</th>
                            <th><?php echo $this->lang->line('Status') ?></th>
                            <th><?php echo $this->lang->line('Actions') ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    

    $(document).ready(function () {
            var columnlist = [
            { 'width': '4%' }, 
            { 'width': '25%' },
            { 'width': '15%' }, 
            { 'width': '15%' },
            { 'width': '10%' },
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
                'columns': columnlist,
            });

            // $('#emptable').parent().css({
            // 'max-width': '100%', // Set the maximum height as per your requirement
            // 'overflow-x': 'scroll'
            // });
        });


    $('.delemp').click(function (e) {
        e.preventDefault();
        $('#empid').val($(this).attr('data-object-id'));

    });
</script>