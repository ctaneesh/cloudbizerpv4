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
            <div class="col-lg-3 col-md-4 col-sm-12 col-12">

                <h4 class="card-title">
                    <?php                         
                        echo $this->lang->line('Profit & Loss');
                        
                        ?>
                </h4>
                <p>As of the Date - <b><?=date('d-M-Y')?></b></p>
            </div>
                
            <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                <a href="<?php echo base_url(); ?>reports/profit_and_loss_to_prf_new" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">PDF</a>
                <a href="<?php echo base_url(); ?>reports/profit_and_loss_to_excel_new" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">Excel</a>
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
               
                <div class="table-scroll">
                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="2" class="account-name expand-transaction">Revenue <span class=""><i
                                            class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">

                            <?php
                            $revenue_value = 0;
                            $grand_revenue = 0;
                            if($revenue_income)
                            {
                                foreach ($revenue_income as $key => $value) {
                                   $account_id = $value['account_id'];
                                   $revenue_value = ($value['amount']) ? ($value['amount']):0.00;      
                                   $grand_revenue += $revenue_value;                             
                                   $revenue_value1 = ($revenue_value>0) ? number_format($revenue_value,2) : "(".number_format(abs($revenue_value),2).")";
                                   echo '<tr>';
                                   echo '<td scope="col"><a href="' . base_url("transactions/account_transactions?code=$account_id") . '"><b>'.$value['holder'].'</b></a></td>';
                                   echo '<td scope="col" class="text-right">'.($revenue_value1).'</td>';
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
                                <th scope="col"><?php echo $this->lang->line('Total Revenue'); ?></th>
                                <?php
                                echo '<th scope="col" class="text-right amount-font">'.number_format(abs($grand_revenue),2).'</th>';
                             
                                ?>

                            </tr>
                        </tfoot>

                    </table>

                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="2" class="account-name expand-transaction">Costs of Goods Sold(COGS) <span><i
                                            class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">
                        <?php
                        $cogs_value = 0;
                        $grand_cogs = 0;
                        if($cogs)
                        {
                            foreach ($cogs as $key => $value) {
                               $account_id = $value['account_id'];
                               $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                               $cogs_value = ($value['amount']) ? ($value['amount']):0.00;      
                               $grand_cogs += $cogs_value; 
                               echo '<tr>';
                               echo '<td scope="col"><a href="' . base_url("transactions/account_transactions?code=$account_id") . '"><b>'.$value['holder'].'</b></a></td>';
                               echo '<td scope="col" class="text-right">'.number_format(abs($cogs_value),2).'</td>';
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
                                <th scope="col"><?php echo $this->lang->line('Total COGS'); ?></th>
                                <?php
                                 echo '<th scope="col" class="text-right amount-font">'.number_format(abs($grand_cogs),2).'</th>';                                
                                ?>
                            </tr>
                        </tfoot>
                    </table>

                    <table class="table profit-content-table mt-2">
                        <thead>

                            <tr>
                                <th scope="col"><?php echo $this->lang->line('Gross Profit'); ?></th>
                                <?php
                                echo '<th scope="col" class="text-right1 amount-font">'.number_format(abs($grand_revenue)-abs($grand_cogs),2).'</th>';
                            ?>
                            </tr>
                        </thead>
                    </table>

                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="2" class="account-name expand-transaction">Other Income<span class=""> <i class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">

                            <?php
                            $otherincome_value = 0;
                            $grand_otherincome = 0;
                            if($otherincome)
                            {
                                foreach ($otherincome as $key => $value) {
                                   $account_id = $value['account_id'];
                                   $otherincome_value = ($value['amount']) ? ($value['amount']):0.00;      
                                   $grand_otherincome += $otherincome_value;                             
                                   $otherincome_value1 = ($otherincome_value>0) ? number_format($otherincome_value,2) : number_format(abs($otherincome_value),2);
                                   echo '<tr>';
                                   echo '<td scope="col"><a href="' . base_url("transactions/account_transactions?code=$account_id") . '"><b>'.$value['holder'].'</b></a></td>';
                                   echo '<td scope="col" class="text-right">'.($otherincome_value1).'</td>';
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
                                <th scope="col"><?php echo $this->lang->line('Total Other Income'); ?></th>
                                <?php
                                echo '<th scope="col" class="text-right amount-font">'.number_format(abs($grand_otherincome),2).'</th>';
                             
                                ?>

                            </tr>
                        </tfoot>

                    </table>

                    
                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="2" class="account-name expand-transaction">Other Expense<span class=""> <i class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">

                            <?php
                            $otherexpense_value = 0;
                            $grand_otherexpense = 0;
                            if($otherexpense)
                            {
                                foreach ($otherexpense as $key => $value) {
                                   $account_id = $value['account_id'];
                                   $otherexpense_value = ($value['amount']) ? ($value['amount']):0.00;      
                                   $grand_otherexpense += $otherexpense_value;                             
                                   $otherexpense_value1 = ($otherexpense_value>0) ?  "(".number_format(abs($otherexpense_value),2).")" : "(".number_format(abs($otherexpense_value),2).")";
                                   echo '<tr>';
                                   echo '<td scope="col"><a href="' . base_url("transactions/account_transactions?code=$account_id") . '"><b>'.$value['holder'].'</b></a></td>';
                                   echo '<td scope="col" class="text-right">'.($otherexpense_value1).'</td>';
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
                                <th scope="col"><?php echo $this->lang->line('Total Other Expense'); ?></th>
                                <?php
                                echo '<th scope="col" class="text-right amount-font">'.number_format(abs($grand_otherexpense),2).'</th>';
                             
                                ?>

                            </tr>
                        </tfoot>

                    </table>

                    
                    <table class="table profit-content-table mt-2">
                        <thead>

                            <tr>
                                <th scope="col"><?php echo $this->lang->line('Net Income'); ?></th>
                                <?php
                               
                                $grandtotal = (abs($grand_revenue)+abs($grand_otherincome)) - (abs($grand_cogs)+abs($grand_otherexpense));
                                echo '<th scope="col" class="text-right1 amount-font">'.number_format($grandtotal,2).'</th>';
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