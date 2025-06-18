<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
  
    <div class="card-header border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line('Cash Flow'); ?></li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12 col-12">
                <h4 class="card-title"><?php  echo $this->lang->line('Cash Flow');?></h4>
                <p>As of the Date - <b><?=date('d-M-Y')?></b></p>
            </div>
                
            <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                <a href="<?php echo base_url(); ?>reports/cash_flow_to_prf" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">PDF</a>
                <a href="<?php echo base_url(); ?>reports/cash_flow_to_to_excel" class="btn btn-secondary btn-sm mt-1 btn-crud" target="_blank">Excel</a>
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
            <div class="container-fluid table-scroll">
               
                <div class="">
                  
                <?php
                    $revenue_value = 0;
                    $grand_revenue = 0;
                    if($revenue_income)
                    {
                        foreach ($revenue_income as $key => $value) {
                            $account_id = $value['account_id'];
                            $revenue_value = ($value['amount']) ? ($value['amount']):0.00;      
                            $grand_revenue += $revenue_value;                             
                            
                        }
                    }
                        
                    $cogs_value = 0;
                    $grand_cogs = 0;
                    if($cogs)
                    {
                        foreach ($cogs as $key => $value) {
                            $account_id = $value['account_id'];
                            $first = ($value['quarter_label']=='First') ? abs($value['amount']):0.00;
                            $cogs_value = ($value['amount']) ? ($value['amount']):0.00;      
                            $grand_cogs += $cogs_value; 
                            
                        }
                    }
                    $otherincome_value = 0;
                    $grand_otherincome = 0;
                    if($otherincome)
                    {
                        foreach ($otherincome as $key => $value) {
                            $account_id = $value['account_id'];
                            $otherincome_value = ($value['amount']) ? ($value['amount']):0.00;      
                            $grand_otherincome += $otherincome_value;                             
                            $otherincome_value1 = ($otherincome_value>0) ? number_format($otherincome_value,2) : number_format(abs($otherincome_value),2);
                            
                        }
                    }
                    
                    $otherexpense_value = 0;
                    $grand_otherexpense = 0;
                    if($otherexpense)
                    {
                        foreach ($otherexpense as $key => $value) {
                            $account_id = $value['account_id'];
                            $otherexpense_value = ($value['amount']) ? ($value['amount']):0.00;      
                            $grand_otherexpense += $otherexpense_value;                             
                            $otherexpense_value1 = ($otherexpense_value>0) ?  "(".number_format(abs($otherexpense_value),2).")" : "(".number_format(abs($otherexpense_value),2).")";
                            
                        }
                    }
                    
                        
                        $net_income = (abs($grand_revenue)+abs($grand_otherincome)) - (abs($grand_cogs)+abs($grand_otherexpense));

                        
                        
                    ?>

                    <table class="table profit-content-table">
                        <thead>
                            <tr class="border-primary">
                                <th colspan="2" class="account-name expand-transaction">Cash Flows from Operating Activities<span class=""><i class="fa fa-angle-down"></i></span></th>
                            </tr>
                        </thead>
                        <tbody class="transaction-details">
                            <tr>
                                <td><b><?php echo '<a href="' . base_url("reports/profit_and_loss_new") . '">Net Income</a>'; ?></b></td>
                                <td><b><?php echo number_format($net_income,2); ?></b></td>
                            </tr>
                            <?php

                              
                            $grand_total = 0;
                            if($ar_sales_on_credit)
                            {              
                                   $grand_total  -=abs($ar_sales_on_credit);   
                                   echo '<tr>';
                                   echo '<td scope="col">Account Receivable (Sales on Credit)</td>';
                                   echo '<td scope="col" class="text-right"><b>('.number_format((abs($ar_sales_on_credit)),2).')</b></td>';
                                   echo '</tr>';
                            }
                            if($ar_sales_payment_received)
                            {               
                                  $grand_total  +=abs($ar_sales_payment_received);            
                                   echo '<tr>';
                                   echo '<td scope="col">Account Receivable (Customer Payment Received)</td>';
                                   echo '<td scope="col" class="text-right"><b>'.number_format((abs($ar_sales_payment_received)),2).'</b></td>';
                                   echo '</tr>';
                            }
                            if($ar_sales_return)
                            {                  
                                  $grand_total  +=abs($ar_sales_return);               
                                   echo '<tr>';
                                   echo '<td scope="col">Account Receivable (Sales Returned by Customer)</td>';
                                   echo '<td scope="col" class="text-right"><b>'.number_format((abs($ar_sales_return)),2).'</b></td>';
                                   echo '</tr>';
                            }
                            if($ap_purchase_credit)
                            {                             
                                   $grand_total  +=abs($ap_purchase_credit);          
                                   echo '<tr>';
                                   echo '<td scope="col">Account Payable (Purchase on Credit)</td>';
                                   echo '<td scope="col" class="text-right"><b>'.number_format((abs($ap_purchase_credit)),2).'</b></td>';
                                   echo '</tr>';
                            }
                            if($ap_purchase_paid)
                            {                       
                                  $grand_total  -=abs($ap_purchase_paid);         
                                   echo '<tr>';
                                   echo '<td scope="col">Account Payable (Payment Made)</td>';
                                   echo '<td scope="col" class="text-right"><b>('.number_format((abs($ap_purchase_paid)),2).')</b></td>';
                                   echo '</tr>';
                            }
                            if($ap_purchase_return)
                            {                  
                                  $grand_total  -=abs($ap_purchase_return);              
                                   echo '<tr>';
                                   echo '<td scope="col">Account Payable (Purchase Returned)</td>';
                                   echo '<td scope="col" class="text-right"><b>('.number_format((abs($ap_purchase_return)),2).')</b></td>';
                                   echo '</tr>';
                            }
                            
                            ?>
                        </tbody>
                        <tfoot class="responsive-flex">
                           
                            <tr>
                                <th scope="col"><?php echo $this->lang->line('Ending Cash Balance')." (".date('F d, Y').")"; ?></th>
                                <?php
                                echo '<th scope="col" class="text-right amount-font1">'.number_format($grand_total,2).'</th>';
                             
                                ?>

                            </tr>
                        </tfoot>

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