
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header border-bottom">
        <?php
            $expense = 0;
            $income = 0;
            $total_profit = 0;

            switch ($trans_type) {
                case 'Income':
                    $expense = 0;
                    $income = $bank_summary['total_income'];
                    break;
                
                case 'Expense':
                    $expense = $bank_summary['total_expense'];
                    $income = 0;
                    break;

                default:
                    $expense = $bank_summary['total_expense'];
                    $income = $bank_summary['total_income'];
                    break;
            }

            $total_profit = $income - $expense;
        ?>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Bank Transactions') ?></li>
                </ol>
            </nav>
            
        
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                <h5 class="card-title"> <?php echo $this->lang->line('Bank Transactions') ?> <a href="<?php echo base_url('bankingtransactions/create?type=income') ?>" class="btn btn-primary btn-sm rounded responsive-mb-1"> <?php echo $this->lang->line('Add New Income') ?></a> <a href="<?php echo base_url('bankingtransactions/create?type=expense') ?>" class="btn btn-primary btn-sm rounded responsive-mb-1"> <?php echo $this->lang->line('Add New Expense') ?></a>  <a href="<?php echo base_url('bankingtransactions') ?>" class="btn btn-secondary btn-sm rounded responsive-mb-1"> <?php echo $this->lang->line('All Transactions') ?></a></h5>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                <div class="row">
                    <input type="hidden" name="trans_type" id="trans_type" value="<?=$trans_type?>">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center responsive-border-bottom-with-padding">
                        <div class="bank-summary">
                            <h4><a href="<?php echo base_url('bankingtransactions?type=Income') ?>"><?=number_format($income,2)?></a><div class="font-13"><?php echo $this->lang->line('Income'); ?></div></h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center responsive-border-bottom-with-padding">
                        <div class="bank-summary">
                            <h4><a href="<?php echo base_url('bankingtransactions?type=Expense') ?>"><?=number_format($expense,2)?></a><div class="font-13"><?php echo $this->lang->line('Expenses'); ?></div></h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center responsive-border-bottom-with-padding responsive-mb-1">
                        <div class="bank-summary">
                        
                            <h4><?=number_format($total_profit,2)?><div class="font-13"><?php echo $this->lang->line('Profit'); ?></div></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-12">  
                <a  target="_blank" class="btn btn-secondary btn-sm rounded" href="<?php echo base_url('bankingtransactions/bank_transaction_pdf?type='.$trans_type) ?>"><?php echo $this->lang->line('PDF') ?></a>
                <a  target="_blank" class="btn btn-secondary btn-sm rounded" href="<?php echo base_url('bankingtransactions/bank_transaction_csv?type='.$trans_type) ?>"><?php echo $this->lang->line('Excel') ?></a>
                
            </div>
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
                <th><?php echo $this->lang->line('Relation') ?></th>
                <th><?php echo $this->lang->line('Transaction Number') ?></th>
                <th><?php echo $this->lang->line('Date') ?></th>
                <th><?php echo $this->lang->line('Type') ?></th>
                <th><?php echo $this->lang->line('Category') ?></th>
                <th><?php echo $this->lang->line('Account') ?></th>
                <th><?php echo $this->lang->line('Customer/Supplier') ?></th>
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
                    trans_type: $('#trans_type').val(),
                }
            },
            columnGroup: false
    });
}

</script>