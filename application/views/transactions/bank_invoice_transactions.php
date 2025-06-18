

<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('accounts/add') ?>"><?php echo $this->lang->line('Chart of Accounts') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Transaction')." #". $transaction_records['trans_ref_number']; ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Transaction')." #".$transaction_records['trans_ref_number']; ?>&nbsp;<a href="<?= base_url('transactions/transaction_edit?ref=' . $transaction_records['trans_ref_number']); ?>" class="btn btn-crud btn-sm btn-primary"><?php echo $this->lang->line('Edit'); ?></a></h4>
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
                         <p class="text-muted"><?php echo $this->lang->line('Bill To') ?></p>
                        <ul class="px-0 list-unstyled">
                            <li class="text-bold-800"><a
                                href="<?php echo base_url('customers/view?id=' . $transaction_records['trans_customer_id']) ?>"><strong
                                class="invoice_a"><?php echo $transaction_records['customer'] . '</strong></a></li><li>' . $transaction_records['company'] . '</li><li>' . $transaction_records['address'] . '</li><li>' . $transaction_records['city'] . ',' . $transaction_records['country'] . '</li><li>' . $this->lang->line('Phone') . ': ' . $transaction_records['phone'] . '</li><li>' . $this->lang->line('Email') . ': ' . $transaction_records['email'] . '</li>';
                                foreach ($c_custom_fields
                                
                                as $row) {
                                echo '  <li>' . $row['name'] . ': ' . $row['data'] ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-8 col-sm-12 responsive-text-right">
                        <?php
                        if($invoice)
                        {
                            $invoiceid = $invoice['id'];
                            $invdate = ($invoice['invoicedate']) ? date('d-m-Y',strtotime($invoice['invoicedate'])) : "" ;
                            echo '<p class="text-muted">'.$this->lang->line('Related Invoice').'</p>';
                            echo '<p class="text-muted"><a href="' . base_url("invoices/view?id=$invoiceid") . '"><b>'.$invoice['invoice_number'].'</b></a></p>';
                            echo '<p class="text-muted">'.$this->lang->line('Invoice Date').' : '.$invdate.'</p>';
                            echo '<p class="text-muted">'.$this->lang->line('Invoice Amount').' : '.number_format($invoice['total'],2).'</p>';
                            echo '<p class="text-muted">'.$this->lang->line('Receipt Amount').' : '.number_format($transaction_records['trans_amount'],2).'</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="table-container table-scroll">
                    <table id="acctable1" class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                            <tr>
                                <th width="25%"><?php echo $this->lang->line('Account') ?></th>
                                <th width="15%" class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                                <th width="15%" class="text-right"><?php echo $this->lang->line('Credit') ?></th>
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
