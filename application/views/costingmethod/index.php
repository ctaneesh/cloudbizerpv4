<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header">
          <h4 class="card-title"> <?php echo $this->lang->line('Costing Method') ?>
           <!-- <a href="<?php echo base_url('costingmethod/create') ?>" class="btn btn-primary btn-sm rounded"> <?php echo $this->lang->line('Add new') ?></a> -->
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
       

        <table id="catgtable" class="table table-striped table-bordered zero-configuration" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Costing Method') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($costing as $row) {
                $cid = $row->id;
                $cberp_costing_method = $row->costing_method;
                // $date = ($row->created_date)?date('d-m-Y', strtotime($row->created_date)) : "";
                echo "<tr>
                    <td>$i</td>
                    <td>$cberp_costing_method</td>           
                    <td><a href='" . base_url("costingmethod/edit?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;</td></tr>";
                $i++;
            }
            ?>
            </tbody>
           
        </table>
    </div>
</div>

<script type="text/javascript">
var columnlist = [
    { 'width': '4%' }, 
    { 'width': '10%' },
    { 'width': 'auto' }  // Use 'auto' or '' for dynamic width adjustment
];

$(document).ready(function () {
    // Initialize DataTable with column widths and other settings
    $('#catgtable').DataTable({
        responsive: false,
        columns: columnlist  // Apply the column width configuration here
    });
});

</script>