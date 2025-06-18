<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
        <?php

        $caption = $this->lang->line('Manual Journal');
          ?>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line('Trial Balance'); ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-3">                   
                    <h4>
                        <?php                         
                        echo $this->lang->line('Trial Balance');
                        
                        ?>
                    </h4>
                    <p> As of the Date - <b><?=date('d-M-Y')?></b> <br>Detailed list of all transactions and their total</p>
                </div>                       
                <div class="col-9">
                    <a href="<?php echo base_url(); ?>reports/trial_balance_to_pdf" class="btn btn-crud btn-secondary btn-sm mt-1" target="_blank">PDF</a>
                    <a href="<?php echo base_url(); ?>reports/trial_balance_to_excel" class="btn btn-crud btn-secondary btn-sm mt-1" target="_blank">Excel</a>
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
        <div class="card card-block ">



            <div class="container-fluid">
                <table class="table trail-top-table">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" class="text-right"><?php echo $this->lang->line('Debit'); ?></th>
                        <th scope="col" class="text-right"><?php echo $this->lang->line('Credit'); ?></th>
                    </tr>
                </table>

                <div class="mt-3">
                    

                        <?php 
                        if($trail_account_headers)
                        {
                            $debit_total =0;
                            $credit_total =0;
                            foreach ($trail_account_headers as $value) {
                                echo '<table class="table trail-content-table">';
                                echo '<thead>
                                            <tr class="border-primary">
                                                <th  class="account-name expand-transaction">
                                                    '.$value['coa_header'].'
                                                    <span><i class="fa fa-angle-down"></i></span>
                                                </th>
                                            </tr>
                                        </thead>';
                                    
                                foreach ($trail_account_details[$value['coa_header_id']] as $key => $row) {
                                    $credit = 0.00;
                                    $debit = 0.00;
                                    $code = $row['acid'];
                                    if($row['amount']>0)
                                    {
                                        $debit = $row['amount'];
                                        $debit_total += $row['amount'];
                                    }
                                    else{
                                        $credit = abs($row['amount']);
                                        $credit_total += abs($row['amount']);
                                    }
                                    echo '<tbody class="transaction-details">
                                                <tr>
                                                    <td scope="col"><a href="' . base_url("transactions/account_transactions?code=$code") . '"><b>'.$row['holder'].'</b></a></td>
                                                    <td scope="col" class="text-right">'.number_format($debit,2).'</td>
                                                    <td scope="col" class="text-right">'.number_format($credit,2).'</td>
                                                </tr>
                                            </tbody>';
                                }
                                echo '</table>';
                            }
                        }
                        ?>
                        <table class="table trail-content-table">
                        
                       <tfoot>
                            <tr>
                                <th scope="col" class="text-right"><?php echo $this->lang->line('Total'); ?></th>
                                <th scope="col" class="text-right amount-font"><?=number_format($debit_total,2)?></th>
                                <th scope="col" class="text-right amount-font"><?=number_format($credit_total,2)?></th>
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
    // Toggle expand/collapse on click
    $('.expand-transaction').on('click', function () {
        // Find the corresponding transaction-details tbody
        let details = $(this).closest('table').find('.transaction-details');

        // Toggle visibility
         details.fadeToggle(500);

        // Switch icon
        let icon = $(this).find('i');
        if (icon.hasClass('fa-angle-down')) {
            icon.removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            icon.removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });
});


</script>