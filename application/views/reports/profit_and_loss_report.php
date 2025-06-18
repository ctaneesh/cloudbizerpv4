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
                <li class="breadcrumb-item"><a
                        href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line('Profit & Loss'); ?></li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-3">

                <h4>
                    <?php                         
                        echo $this->lang->line('Profit & Loss');
                        
                        ?>
                </h4>
                <p>Quarterly profit & loss by chart of account</p>
            </div>
                
            <div class="col-9">
                <a href="<?php echo base_url(); ?>reports/profit_and_loss_to_prf" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">PDF</a>
                <a href="<?php echo base_url(); ?>reports/profit_and_loss_to_excel" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">Excel</a>
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
                <table class="table profit-top-table">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col" class="text-right">Jan <?=date('Y')?> - Mar <?=date('Y')?></th>
                        <th scope="col" class="text-right">Apr <?=date('Y')?> - Jun <?=date('Y')?></th>
                        <th scope="col" class="text-right">Jul <?=date('Y')?> - Sep <?=date('Y')?></th>
                        <th scope="col" class="text-right">Oct <?=date('Y')?> - Dec <?=date('Y')?></th>
                        <th scope="col" class="text-right"><?php echo $this->lang->line('Total') ?></th>
                    </tr>
                </table>

                <div class="mt-3">
                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="6" class="account-name expand-transaction">Income <span class=""><i
                                            class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">

                            <?php
                            $grand_income_first_credit = 0;
                            $grand_income_second_credit = 0;
                            $grand_income_third_credit = 0;
                            $grand_income_fourth_credit = 0;
                            $grand_income_total_credit = 0;

                            $grand_income_first_debit = 0;
                            $grand_income_second_debit = 0;
                            $grand_income_third_debit = 0;
                            $grand_income_fourth_debit = 0;
                            $grand_income_total_debit = 0;

                            $grand_income_first = 0;
                            $grand_income_second = 0;
                            $grand_income_third = 0;
                            $grand_income_fourth = 0;
                            $grand_income_total = 0;
                            if($income)
                            {
                                foreach ($income as $key => $value) {
                                   $account_id = $value['account_id'];
                                   $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                                   $second = ($value['quarter_label']=='Second') ? abs($value['amount']):0.00;
                                   $third = ($value['quarter_label']=='Third') ? abs($value['amount']):0.00;
                                   $fourth = ($value['quarter_label']=='Fourth') ? abs($value['amount']):0.00;
                                   $total = ($first+$second+$third+$fourth);
                                
                                   if($value['transtype']=='credit')
                                   {
                                        $grand_income_first_credit += $first;
                                        $grand_income_second_credit += $second;
                                        $grand_income_third_credit += $third;
                                        $grand_income_fourth_credit += $fourth;
                                        $grand_income_total_credit += $total;
                                   }
                                   else{
                                        $grand_income_first_debit += $first;
                                        $grand_income_second_debit += $second;
                                        $grand_income_third_debit += $third;
                                        $grand_income_fourth_debit += $fourth;
                                        $grand_income_total_debit += $total;
                                   }
                                   echo '<tr>';
                                   echo '<td scope="col"><a href="' . base_url("transactions/account_transactions?code=$account_id") . '"><b>'.$value['holder'].'</b></a></td>';
                                   echo '<td scope="col" class="text-right">'.number_format($first,2).'</td>';
                                   echo '<td scope="col" class="text-right">'.number_format($second,2).'</td>';
                                   echo '<td scope="col" class="text-right">'.number_format($third,2).'</td>';
                                   echo '<td scope="col" class="text-right">'.number_format($fourth,2).'</td>';
                                   echo '<td scope="col" class="text-right">'.number_format($total,2).'</td>';
                                   echo '</tr>';
                                }
                            }
                            else{
                                
                                echo '<tr>';
                                echo '<td scope="col">'.$this->lang->line('No records found').'</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col"><?php echo $this->lang->line('Total'); ?></th>
                                <?php
                                $grand_income_first  = abs($grand_income_first_debit   - $grand_income_first_credit);   
                                $grand_income_second = abs($grand_income_second_debit  - $grand_income_second_credit);  
                                $grand_income_third  = abs($grand_income_third_debit   - $grand_income_third_credit);  
                                $grand_income_fourth = abs($grand_income_fourth_debit  - $grand_income_fourth_credit); 
                                $grand_income_total  = abs($grand_income_total_debit   - $grand_income_total_credit); 
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_first,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_second,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_third,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_fourth,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_total,2).'</th>';
                                ?>

                            </tr>
                        </tfoot>

                    </table>

                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="5" class="account-name expand-transaction">Expenses <span><i
                                            class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">
                        <?php
                        $grand_expense_first = 0;
                        $grand_expense_second = 0;
                        $grand_expense_third = 0;
                        $grand_expense_fourth = 0;
                        $grand_expense_total = 0;

                        $grand_expense_first_credit = 0;
                        $grand_expense_second_credit = 0;
                        $grand_expense_third_credit = 0;
                        $grand_expense_fourth_credit = 0;
                        $grand_expense_total_credit = 0;

                        $grand_expense_first_debit = 0;
                        $grand_expense_second_debit = 0;
                        $grand_expense_third_debit = 0;
                        $grand_expense_fourth_debit = 0;
                        $grand_expense_total_debit = 0;
                        if($expense)
                        {
                            foreach ($expense as $key => $value) {
                               $account_id = $value['account_id'];
                               $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                               $second = ($value['quarter_label']=='Second') ? abs($value['amount']):0.00;
                               $third = ($value['quarter_label']=='Third') ? abs($value['amount']):0.00;
                               $fourth = ($value['quarter_label']=='Fourth') ? abs($value['amount']):0.00;
                               $total = ($first+$second+$third+$fourth);

                               if($value['transtype']=='credit')
                                {
                                    $grand_expense_first_credit += $first;
                                    $grand_expense_second_credit += $second;
                                    $grand_expense_third_credit += $third;
                                    $grand_expense_fourth_credit += $fourth;
                                    $grand_expense_total_credit += $total;
                                }
                                else{
                                    $grand_expense_first_debit += $first;
                                    $grand_expense_second_debit += $second;
                                    $grand_expense_third_debit += $third;
                                    $grand_expense_fourth_debit += $fourth;
                                    $grand_expense_total_debit += $total;
                                }
                               echo '<tr>';
                               echo '<td scope="col"><a href="' . base_url("transactions/account_transactions?code=$account_id") . '"><b>'.$value['holder'].'</b></a></td>';
                               echo '<td scope="col" class="text-right">'.number_format($first,2).'</td>';
                               echo '<td scope="col" class="text-right">'.number_format($second,2).'</td>';
                               echo '<td scope="col" class="text-right">'.number_format($third,2).'</td>';
                               echo '<td scope="col" class="text-right">'.number_format($fourth,2).'</td>';
                               echo '<td scope="col" class="text-right">'.number_format($total,2).'</td>';
                               echo '</tr>';
                            }
                        }
                        else{
                            
                            echo '<tr>';
                            echo '<td scope="col">'.$this->lang->line('No records found').'</td>';
                            echo '</tr>';
                        }
                       ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col"><?php echo $this->lang->line('Total'); ?></th>
                                <?php
                                 $grand_expense_first  = abs($grand_expense_first_debit   - $grand_expense_first_credit);   
                                 $grand_expense_second = abs($grand_expense_second_debit  - $grand_expense_second_credit);  
                                 $grand_expense_third  = abs($grand_expense_third_debit   - $grand_expense_third_credit);  
                                 $grand_expense_fourth = abs($grand_expense_fourth_debit  - $grand_expense_fourth_credit); 
                                 $grand_expense_total  = abs($grand_expense_total_debit   - $grand_expense_total_credit); 
                                 echo '<th scope="col" class="text-right amount-font">'.number_format($grand_expense_first,2).'</th>';
                                 echo '<th scope="col" class="text-right amount-font">'.number_format($grand_expense_second,2).'</th>';
                                 echo '<th scope="col" class="text-right amount-font">'.number_format($grand_expense_third,2).'</th>';
                                 echo '<th scope="col" class="text-right amount-font">'.number_format($grand_expense_fourth,2).'</th>';
                                 echo '<th scope="col" class="text-right amount-font">'.number_format($grand_expense_total,2).'</th>';
                                ?>
                            </tr>
                        </tfoot>
                    </table>

                    <table class="table profit-content-table mt-3">
                        <thead>

                            <tr>
                                <th scope="col"><?php echo $this->lang->line('Net Profit'); ?></th>
                                <?php
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_first-$grand_expense_first,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_second-$grand_expense_second,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_third-$grand_expense_third,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_fourth-$grand_expense_fourth,2).'</th>';
                                echo '<th scope="col" class="text-right amount-font">'.number_format($grand_income_total-$grand_expense_total,2).'</th>';
                            ?>
                            </tr>
                        </thead>
                    </table>
                </div>


            </div>



        </div>


    </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    // Toggle expand/collapse on click
    $('.expand-transaction').on('click', function() {
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