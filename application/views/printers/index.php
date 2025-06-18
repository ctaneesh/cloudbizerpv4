<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
         <div class="card-header">
            <h4 class="card-title"> Printers <a href="<?php echo base_url('printer/add') ?>" class="btn btn-primary btn-sm rounded">
                    <?php echo $this->lang->line('Add new') ?> </a>
            </h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">                    
                        <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                    </ul>
                </div>
            <hr>
        </div>

        <div class="card-body">
            
            <table id="catgtable" class="table table-striped table-bordered zero-configuration dataTable" style="width:100% !important;">
                <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:25%;"><?php echo $this->lang->line('Name') ?></th>
                    <th style="width:25%;"><?php echo $this->lang->line('Business Locations') ?></th>
                    <th style="width:10%;"><?php echo $this->lang->line('Type') ?></th>
                    <th ><?php echo $this->lang->line('Action') ?></th>


                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($printers as $row) {
                    $loc = location($row['val4']);
                    echo "<tr>
                    <td>$i</td>
                    <td>" . $row['val1'] . "</td>
                    <td>" . $loc['cname'] . "</td>
                    <td>" . $row['val2'] . "</td>
                   
                    <td><a href='" . base_url('printer/view?id=' . $row['id']) . "' class='btn btn-secondary btn-sm'  title='View'><i class='icon-eye'></i></a>&nbsp;<a href='" . base_url('printer/edit?id=' . $row['id']) . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;<a href='#' data-object-id='" . $row['id'] . "' class='btn btn-secondary btn-sm delete-object' title='Delete'><i class='fa fa-trash'></i></a></td></tr>";
                    $i++;
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th><?php echo $this->lang->line('Name') ?></th>
                    <th><?php echo $this->lang->line('Location') ?></th>
                    <th><?php echo $this->lang->line('Type') ?></th>
                    <th><?php echo $this->lang->line('Action') ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(document).ready(function () {

        //datatables
        $('#catgtable').DataTable({responsive: false});

    });
</script>
<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
            </div>
            <div class="modal-body">
                <p>Delete this printer ? </strong></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="printer/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>