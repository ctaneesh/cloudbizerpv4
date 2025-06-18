<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header border-bottom">
       

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Reconciliations') ?></li>
                </ol>
            </nav>
            
        
        <div class="row">
            <div class="col-5">
                <h5 class="title"> <?php echo $this->lang->line('Reconciliations') ?> <a href="<?php echo base_url('reconciliations/create') ?>" class="btn btn-primary btn-sm rounded"><?php echo $this->lang->line('Add New') ?></a></h5>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                        <div class="bank-summary">
                            <h4><?=number_format($total_closing_balance,2)?><div class="font-13"><?php echo $this->lang->line('Reconciled') ?></div></h4>
                        </div>
                    </div>
            <!-- <div class="col-2">  
                <a  target="_blank" class="btn btn-secondary btn-sm rounded" href="<?php echo base_url('bankingtransactions/bank_transaction_pdf?type='.$trans_type) ?>"><?php echo $this->lang->line('PDF') ?></a>
                <a  target="_blank" class="btn btn-secondary btn-sm rounded" href="<?php echo base_url('bankingtransactions/bank_transaction_csv?type='.$trans_type) ?>"><?php echo $this->lang->line('Excel') ?></a>
                
            </div> -->
        </div>
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
                <th><?php echo $this->lang->line('Reconciliation #') ?></th>
                <th><?php echo $this->lang->line('Created Date') ?></th>
                <th><?php echo $this->lang->line('Account') ?></th>
                <th><?php echo $this->lang->line('Date From') ?></th>
                <th><?php echo $this->lang->line('Date To') ?></th>
                <th><?php echo $this->lang->line('Opening Balance') ?></th>
                <th><?php echo $this->lang->line('Closing Balance') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            </tbody>
           
        </table>
    </div>
</div>

<script type="text/javascript">
var columnlist = [
        { 'width': '4%' }, 
        { 'width': '14%' },
        { 'width': '15%' }, 
        { 'width': '10%' },
        { 'width': '6%' },
        { 'width': '8%' },
        { 'width': '7%' },
        { 'width': '7%' },
        { 'width': '' }
    ];
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
            'url': "<?php echo site_url('reconciliations/ajax_list') ?>",
            'type': 'POST',
            'data': function(d) {
                // Add CSRF token dynamically using JS
                d['<?=$this->security->get_csrf_token_name()?>'] = '<?=$this->security->get_csrf_hash()?>';
            }
        },
        'columnDefs': [
                {
                    'targets': [0],
                    'orderable': false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [4,5],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [6,7],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-right']);
                    }
                }
            ],
            'columns': columnlist,
    });
    
}
function delete_action(id)
{

    Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete this Reconciliation?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,  
            focusCancel: true,      
            allowOutsideClick: false,  // Disable outside click
        }).then((result) => {
            if (result.isConfirmed) {
                
                $.ajax({
                    url: baseurl + 'reconciliations/deleteaction',
                    type: 'POST',
                    data: {
                        id:id
                    },
                    success: function(response) {
                        
                        window.location.href = baseurl + 'reconciliations';
                        
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the reconciliation', 'error');
                        console.log(error); // Log any errors
                    }
                });
            } 
        });
}

</script>