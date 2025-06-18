<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Balance Sheet'); ?></li>
                </ol>
                <!-- invoices/customer_leads?id=1 -->

                
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Balance Sheet'); ?></h4>
                    <p>As of the Date - <b><?=date('d-M-Y')?></b><br>Snapshot of your business.</p>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                <!-- <button class="btn btn-secondary btn-sm mt-1">
                    <i class="fa fa-calendar"></i> As of <strong><?=date('d-m-Y')?></strong>
                </button> -->
                <!-- <button class="btn btn-secondary btn-sm mt-1" onclick="convert_to_prf()">
                    <i class="fa fa-pdf"></i> PDF</strong>
                </button>-->
                    <a href="<?php echo base_url(); ?>reports/balance_sheet_to_pdf" class="btn btn-crud btn-secondary btn-sm mt-1" target="_blank">PDF</a>
                    <a href="<?php echo base_url(); ?>reports/balance_sheet_to_excel" class="btn btn-crud btn-secondary btn-sm mt-1" target="_blank">Excel</a>
                </div> 
            </div>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content">            
                <?php if(!empty($nestedArray))
                {  
                    $assettotal = 0;
                    $liabilitytotal =0;
                    // $parentItem = $accountparent[$coaHeaderId][$row['parent_account_id']];
                //    echo "<pre>";  print_r($nestedArray); die();
                    $processed_parents = [];
                    foreach ($nestedArray as $headerId => $types)
                    {
                        // echo "<pre>";print_r($accountparent[$headerId]); die();
                        // echo "<pre>";print_r($parentItem[$types[array_key_first($types)][0]['parent_account_id']]); die();
                        if($types[array_key_first($types)][0]['account_header']=='Assets')
                        {
                            $assettotal += $headerSums[$headerId];
                        }
                        else if($types[array_key_first($types)][0]['account_header']=='Liabilities'){
                            $liabilitytotal += $headerSums[$headerId];
                        }
                        echo '<div class="balancesheet-sheet transaction-box mt-3 mb-3">';
                        ?>
                        <div class="balancesheet-header coaheader">
                            <h3><?php echo $types[array_key_first($types)][0]['account_header']; ?> <span class="balancesheet-toggle-icon"><i class="fa fa-angle-right"></i></span></h3>
                            <span class="balancesheet-total-amount"><?php echo number_format($headerSums[$headerId], 2); ?></span>
                        </div>
                        <?php
                        //  echo "<pre>";  print_r($types); die();
                       
                        foreach ($types as $typeId => $accounts)
                        {
                            // $parent_account_id = $accounts['parent_account_id'][0];
                            
                            // echo "<pre>";print_r($accountparent[$headerId][$typeId][$parent_account_id]); die();
                            
                          
                            echo '<div class="balancesheet-group">'; 
                            echo '<div class="balancesheet-header coatype">
                                <span class="balancesheet-accountname">'.$accounts[0]['account_type'].'<span class="balancesheet-toggle-icon"><i class="fa fa-angle-right"></i></span></span>
                                <span class="group-total">'.number_format($typeSums[$typeId], 2).'</span>
                            </div>';
                            
                            echo '<ul class="balancesheet-details">';
                            foreach ($accounts as $account)
                            {
                                $parent_account_id = $account['parent_account_id'];
                                $parent_account_data = $accountparent[$headerId][$typeId][$parent_account_id];
                                // echo "<pre>";  print_r($parent_account_data); die();
                                if($parent_account_data)
                                {
                                   
                                    if (!in_array($parent_account_id, $processed_parents)) 
                                    {
                                        $total_parent_amount = array_sum(array_column($parent_account_data, 'amount'));
                                        echo '<div class="balancesheet-parent-group">'; 
                                        echo '<div class="balancesheet-header balancesheetparent">
                                        <span class="balancesheet-accountname-parent">'.$parent_account_data[0]['parent_account_number']." - ".$parent_account_data[0]['parent_account_name'].'<span class="balancesheet-toggle-icon"><i class="fa fa-angle-right"></i></span></span>
                                        <span class="group-total">'.number_format($total_parent_amount, 2).'</span>
                                        </div>';
                                        echo '<ul class="balancesheet-details-parent">';
                                        // Mark this parent as processed
                                        $processed_parents[] = $parent_account_id;
                                
                                        $code = $account['code'];                          
                                        $account_type = $account['account_type']; 
                                        foreach($parent_account_data as $child)
                                        {  
                                            $childcode = $child['code'];
                                            $childaccount = $child['code']." - ".$child['account_name'];
                                            echo '<li>';
                                                echo '<span>';
                                                echo '<a href="' . base_url("transactions/account_transactions?code=$childcode") . '">'.$childaccount;
                                                echo '</a>';
                                                echo '</span>';
                                                $amount = ($account_type=='Contra-Asset') ? "(".number_format($child['amount'], 2).")" : number_format($child['amount'], 2);
                                                echo '<span class="text-right">'.$amount.'</span>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                        echo '</div>';
                                    }
                                    else{
                                        continue;
                                    }
                                   
                                }
                                else{
                                        echo '<li>';
                                            echo '<span>';
                                            echo '<a href="' . base_url("transactions/account_transactions?code=$code") . '">'.$code." - ".$account['account_name'];
                                            echo '</a>';
                                            echo '</span>';
                                            $amount = ($account_type=='Contra-Asset') ? "(".number_format($account['amount'], 2).")" : number_format($account['amount'], 2);
                                            echo '<span class="text-right">'.$amount.'</span>';
                                        echo '</li>';
                                }
                            }
                            
                            echo '</ul>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                } 
                ?>            
                
                <!-- equity section  -->
                <div class="balancesheet-sheet transaction-box mt-3 mb-3 d-none">
                    <div class="balancesheet-header coaheader">
                        <?php
                            $equitytotal =  $assettotal - $liabilitytotal;
                        ?>
                        <h3><?php echo $this->lang->line('Equity'); ?> <span class="balancesheet-toggle-icon"><i class="fa fa-angle-right"></i></span></h3>
                        <span class="balancesheet-total-amount"><?php echo number_format($equitytotal, 2); ?></span>
                    </div>
                    <div class="balancesheet-group">
                        <div class="balancesheet-header coatype">
                            <span class="balancesheet-accountname"><?php echo $this->lang->line('Equity'); ?><span class="balancesheet-toggle-icon"><i class="fa fa-angle-right"></i></span></span>
                            <span class="group-total"><?php echo number_format($liabilitytotal, 2); ?></span>
                        </div>
                        <ul class="balancesheet-details">
                            <li>
                                <span>Current Year Earnings</span>
                                <span><?php echo number_format($equitytotal, 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                    <!-- =================== Right Section Ends=============================== -->
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
    // Toggle for .coaheader
    $('.coaheader').click(function() {
        var $icon = $(this).find('i');
        var $parent = $(this).closest('.balancesheet-sheet');
        $parent.find('.balancesheet-group').slideToggle();
        if ($icon.hasClass('fa-angle-right')) {
            $icon.removeClass('fa-angle-right').addClass('fa-angle-up');
        } else {
            $icon.removeClass('fa-angle-up').addClass('fa-angle-right');
        }
    });

    // Toggle for .coatype
    $('.coatype').click(function() {
        var $icon = $(this).find('i');
        var $parent = $(this).closest('.balancesheet-group');
        $parent.find('.balancesheet-details').slideToggle();
        if ($icon.hasClass('fa-angle-right')) {
            $icon.removeClass('fa-angle-right').addClass('fa-angle-up');
        } else {
            $icon.removeClass('fa-angle-up').addClass('fa-angle-right');
        }
    });
    $('.balancesheet-accountname-parent').click(function() {
        var $icon = $(this).find('i');
        var $parent = $(this).closest('.balancesheet-parent-group');
        $parent.find('.balancesheet-details-parent').slideToggle();
        if ($icon.hasClass('fa-angle-right')) {
            $icon.removeClass('fa-angle-right').addClass('fa-angle-up');
        } else {
            $icon.removeClass('fa-angle-up').addClass('fa-angle-right');
        }
    });
});

</script>