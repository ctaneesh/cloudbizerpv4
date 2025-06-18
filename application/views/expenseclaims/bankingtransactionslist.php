<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Transactions') ?></li>
                </ol>
            </nav>
            
        <h5 class="title"> <?php echo $this->lang->line('Transactions') ?> <a href="<?php echo base_url('bankingtransactions/create?type=income') ?>" class="btn btn-primary btn-sm rounded"> <?php echo $this->lang->line('Add New Income') ?></a> <a href="<?php echo base_url('bankingtransactions/create?type=expense') ?>" class="btn btn-primary btn-sm rounded"> <?php echo $this->lang->line('Add New Expense') ?></a></h5>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
         </div>
      </div>
    <div class="card-body">
    
        
        
        <table id="transactionstbl" class="table table-striped table-bordered zero-configuration" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Relation') ?></th>
                <th><?php echo $this->lang->line('Transaction Number') ?></th>
                <th><?php echo $this->lang->line('Date') ?></th>
                <th><?php echo $this->lang->line('Type') ?></th>
                <th><?php echo $this->lang->line('Category') ?></th>
                <th><?php echo $this->lang->line('Account') ?></th>
                <th><?php echo $this->lang->line('Amount') ?></th>


            </tr>
            </thead>
            <tbody>
            </tbody>
           
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {        
        draw_data();
    });

    function draw_data() {
            var table = $('#transactionstbl').DataTable({
            'fixedHeader': true,
            'processing': true,
            'serverSide': true,
            'stateSave': true,
            <?php datatable_lang(); ?>
            'order': [],
            'ajax': {
                'url': "<?php echo site_url('bankingtransactions/ajax_list') ?>",
                'type': 'POST',
                'data': {
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                    
                    filter_status: $('#filter_status').val(),
                    
                }
            },
        //    'columnDefs': [
        //         {
        //             'targets': [0],
        //             'orderable': false,  // Only disable ordering for column 0
        //             'createdCell': function(td, cellData, rowData, row, col) {
        //                 addClassToColumns(td, col, ['text-center']);
        //             }
        //         },
        //         {
        //             'targets': [1, 4, 8,10,11],
        //             'createdCell': function(td, cellData, rowData, row, col) {
        //                 addClassToColumns(td, col, ['text-center']);
        //             }
        //         },
        //         {
        //             'targets': [5],
        //             'createdCell': function(td, cellData, rowData, row, col) {
        //                 addClassToColumns(td, col, ['text-right']);
        //             }
        //         }
        //     ],
            // 'columns': columnlist,
            columnGroup: false,
           
        });
      
    }
</script>