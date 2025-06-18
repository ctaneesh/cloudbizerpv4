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
                <div class="col-lg-3 col-md-4 col-sm-12 col-12">                   
                    <h4 class="card-title">
                        <?php                         
                        echo $this->lang->line('Trial Balance');                        
                        ?>
                    </h4>
                    <p> As of the Date - <b><?=date('d-M-Y')?></b> <br>Detailed list of all transactions and their total</p>
                </div>                       
                <div class="col-lg-9 col-md-8 col-sm-12 col-12">
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
                        <th ></th>
                        <th  class="text-right"><?php echo $this->lang->line('Debit'); ?></th>
                        <th  class="text-right"><?php echo $this->lang->line('Credit'); ?></th>
                    </tr>
                </table>

                <div class="mt-1 table-scroll">
                    

                        <?php 
                        if($trail_account_headers)
                        {
                            $debit_total =0;
                            $credit_total =0;
                            $processed_parents = [];
                            foreach ($trail_account_headers as $value) {
                                // $total_debit_amount = 0;
                                // $total_credit_amount = 0;
                                // $total_debit_amount = array_sum(array_column($trail_account_details[$value['coa_header_id']], 'debit'));
                                // $total_credit_amount = array_sum(array_column($trail_account_details[$value['coa_header_id']], 'credit'));

                        
                                echo '<table class="table trail-content-table">';
                                echo '<thead>
                                            <tr class="border-primary">
                                                <th  class="account-name expand-transaction">
                                                    '.$value['coa_header'].'
                                                    <span><i class="fa fa-angle-down"></i></span>
                                                </th>
                                               
                                            </tr>
                                        </thead>';
                                    $coaHeaderId = $value['coa_header_id'];
                                   
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
                                    // echo $row['coa_header_id']."\n<br>";
                                    $parentItem = $accountparent[$coaHeaderId][$row['parent_account_id']];

                                    // echo "<pre>"; print_r($parentItem);
                                    echo '<tbody class="transaction-details">';
                                    if($parentItem)
                                    {
                                        $parent_account_number      = $parentItem[0]['parent_account_number'];
                                        $parent_account_name = $parentItem[0]['parent_account_name'];
                                        $parent_account_id = $parentItem[0]['parent_account_id'];
                                        $total_amount = array_sum(array_column($parentItem, 'amount'));
                                        if (!in_array($parent_account_id, $processed_parents)) 
                                        {

                                            // Mark this parent as processed
                                            $processed_parents[] = $parent_account_id;
                                    
                                            echo '<tr class="border-primary1 ">
                                                    <td colspan="3" class="account-parent expand-transaction1 responsive-width">
                                                        '.$parent_account_number." - ".$parent_account_name.'
                                                        <span><i class="fa fa-angle-down"></i></span>
                                                    </td>
                                                </tr>';
                                            foreach($parentItem as $child)
                                            {
                                                $typeclass = 'collapse_'.$coaHeaderId;
                                                $childacn = $child['acid'];
                                                $childdebit = 0.00;
                                                $childcredit = 0.00;
                                                if($child['amount']>0)
                                                {
                                                    $childdebit = $child['amount'];
                                                    $debit_total += $child['amount'];
                                                }
                                                else{
                                                    $childcredit = abs($child['amount']);
                                                    $credit_total += abs($child['amount']);
                                                }
                                                echo '<tr>
                                                    <td scope="col1" class="child-padding-60 responsive-width"><a href="' . base_url("transactions/account_transactions?code=$childacn") . '"><b>'.$childacn." - ".$child['holder'].'</b></a></td>
                                                    <td scope="col1" class="text-right">'.number_format($childdebit,2).'</td>
                                                    <td scope="col1" class="text-right">'.number_format($childcredit,2).'</td>
                                                </tr>';
                                            }
                                        }
                                    }
                                    else{
                                        
                                        echo '<tr>
                                                    <td scope="col1" class="child-padding-30 responsive-width"><a href="' . base_url("transactions/account_transactions?code=$code") . '"><b>'.$code." - ".$row['holder'].'</b></a></td>
                                                    <td scope="col1" class="text-right">'.number_format($debit,2).'</td>
                                                    <td scope="col1" class="text-right">'.number_format($credit,2).'</td>
                                                </tr>';
                                    }
                                                
                                    echo '</tbody>';
                                }
                                echo '</table>';
                            }
                        }
                        ?>
                        <table class="table trail-content-table">
                        
                       <tfoot>
                            <tr>
                                <th scope="col1" class="text-right"><?php echo $this->lang->line('Total'); ?></th>
                                <th scope="col1" class="text-right amount-font"><?=number_format($debit_total,2)?></th>
                                <th scope="col1" class="text-right amount-font"><?=number_format($credit_total,2)?></th>
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
    // Toggle for Parent-level transactions
    $('.expand-transaction1').on('click', function () {
        let tbody = $(this).closest('tr').nextUntil('tr.border-primary1, tr.border-primary', 'tr');
        tbody.fadeToggle(500);

        let icon = $(this).find('i');
        icon.toggleClass('fa-angle-down fa-angle-up');
    });
});


</script>