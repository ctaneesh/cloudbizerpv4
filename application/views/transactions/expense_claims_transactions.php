

<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('accounts/add') ?>"><?php echo $this->lang->line('Chart of Accounts') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Transaction')." #". $trans_ref_number; ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Transaction')." #".$trans_ref_number; ?>&nbsp;<a href="<?= base_url('expenseclaims/expense_claim_payment_edit?id=' . $trans_ref_number.'&csd='.$expenseclaim['supplierid']); ?>" class="btn btn-crud btn-sm btn-primary"><?php echo $this->lang->line('Edit'); ?></a></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <div id="invoice-customer-details" class="row">
                    <div class="col-md-4 col-sm-12 text-xs-center text-md-left">
                         <p class="text-muted"><?php echo $this->lang->line('Paid To') ?></p>
                        <ul class="px-0 list-unstyled">                       
                            <li class="text-bold-800"><a
                                href="<?php echo base_url('supplier/view?id=' . $expenseclaim['supplierid']) ?>"><strong
                                class="invoice_a"><?php echo $expenseclaim['suppliername'] . '</strong></a></li><li><li>' . $expenseclaim['supplieraddress'] . '</li><li>' . $expenseclaim['suppliercity'] . '</li><li>' . $this->lang->line('Phone') . ': ' . $expenseclaim['supplierphone'] . '</li><li>' . $this->lang->line('Email') . ': ' . $expenseclaim['supplieremail'] . '</li>';
                                ?>
                        </ul>
                    </div>
                    <div class="col-md-8 col-sm-12 text-right">
                        <?php
                        if($expenseclaim)
                        {
                            $receiptid = $expenseclaim['receiptid'];
                            $claim_number = $expenseclaim['claim_number'];
                            $validtoken = hash_hmac('ripemd160', $receiptid, $this->config->item('encryption_key'));
                            $link = base_url('expenseclaims/view?id=' . $claim_number);
                            $invdate = ($expenseclaim['claim_date']) ? date('d-m-Y',strtotime($expenseclaim['claim_date'])) : "" ;
                            $duedate = ($expenseclaim['claim_due_date']) ? date('d-m-Y',strtotime($expenseclaim['claim_due_date'])) : "" ;
                            echo '<p class="text-muted">'.$this->lang->line('Expense Claim').'</p>';
                            echo '<p class="text-muted"><a href="' . $link . '"><b>'.$expenseclaim['transaction_number'].'</b></a></p>';
                            echo '<p class="text-muted">'.$this->lang->line('Expense Claim Date').' : '.$invdate.'</p>';
                            echo '<p class="text-muted">'.$this->lang->line('Due Date').' : '.$duedate.'</p>';
                            echo '<p class="text-muted">'.$this->lang->line('Claim Amount').' : '.number_format($expenseclaim['claim_total'],2).'</p>';
                            echo '<p class="text-muted">'.$this->lang->line('Paid Amount').' : '.number_format($expenseclaim['payment_recieved_amount'],2).'</p>';
                            echo '<p class="text-muted">'.$this->lang->line('Payment Status').' : '.($expenseclaim['payment_status']).'</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="table-container table-scroll">
                    <table id="acctable1" class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo $this->lang->line('Account') ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Credit') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($transaction_records)
                            {
                                if($transaction_records['trans_type']=='Income')
                                {
                                    $debitamount = $transaction_records['trans_amount']; 
                                    $creditamount = '0.00'; 
                                    echo '<tr>';
                                    echo '<td class="responsive-width">'.$transaction_records['bank_holder'].'</td>';
                                    echo '<td class="text-right">'.$transaction_records['trans_amount'].'</td>';
                                    echo '<td class="text-right">0.00</td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<td class="responsive-width">'.$transaction_records['chart_holder'].'</td>';
                                    echo '<td class="text-right">0.00</td>';
                                    echo '<td class="text-right">'.$transaction_records['trans_amount'].'</td>';
                                    echo '</tr>';
                                }
                                else{
                                    echo '<tr>';
                                    echo '<td class="responsive-width">'.$transaction_records['bank_holder'].'</td>';
                                    echo '<td class="text-right">0.00</td>';
                                    echo '<td class="text-right">'.$transaction_records['trans_amount'].'</td>';
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<td class="responsive-width">'.$transaction_records['chart_holder'].'</td>';
                                    echo '<td class="text-right">'.$transaction_records['trans_amount'].'</td>';
                                    echo '<td class="text-right">0.00</td>';
                                    echo '</tr>';
                                }
                               
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function () {
        var columnlist = [
            { 'width': '5%' }, 
            { 'width': '10%' },
            { 'width': '10%' }, 
            { 'width': '10%' },
            { 'width': '10%' },
            { 'width': '10%' },
            { 'width': '' },
        ];
        $('#acctable').DataTable({
            paging: true,             // Enable pagination
            searching: true,          // Enable search functionality
            ordering: true,           // Enable column sorting
            // order: [[0, 'asc']],      // Default sort by the second column (Date)
            responsive: false,         // Make table responsive
            pageLength: 50,           // Default number of records to display
            columnDefs: [
                { targets: 0, orderable: false }, 
                { targets: [3, 4, 5], className: 'text-right' },
                { targets: [0,1,2], className: 'text-center' }
            ],
            columns: columnlist 
        });
    });
</script>
