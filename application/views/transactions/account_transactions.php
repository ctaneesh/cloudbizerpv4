

<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('accounts/add') ?>"><?php echo $this->lang->line('Chart of Accounts') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $account_master_details['holder']."(".$account_master_details['typename'].")"; ?></li>
                </ol>
            </nav>
            <h4><?php echo $account_master_details['holder']."(".$account_master_details['typename'].")"; ?> <a  href="<?php echo base_url('manualjournals/create') ?>"  class="btn btn-crud btn-primary btn-sm rounded">  <?php echo $this->lang->line('Add New Manual Journal') ?></a></h4>
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
                <div class="table-container">
                    <table id="acctable" class="table table-striped table-bordered zero-configuration w-100">
                        <thead>
                            <tr>
                                <th style="width:5%;">#</th>
                                <th><?php echo $this->lang->line('Date') ?></th>
                                <th><?php echo $this->lang->line('Relation') ?></th>
                                <th class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                                <th class="text-right"><?php echo $this->lang->line('Credit') ?></th>
                                <th class="text-right"><?php echo $this->lang->line('Balance') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $total_credit = 0;
                            $total_debit = 0;
                            $total_balance = 0;
                            $balance =0;
                            // echo "<pre>"; print_r($transaction_records); die();
                            if (!empty($transaction_records)) {

                                //if any modification here - please add it on journal_entries.php also
                                foreach ($transaction_records as $row) {
                                    $invoiceid = $row['invoiceid'];
                                    $deliverynote_invoiceid = $row['deliverynote_invoiceid'];
                                    $refnumber = $row['bank_transaction_refernce'];
                                    $date = ($row['date']) ? date('d M Y', strtotime($row['date'])) : "";
                                    $relation = ($row['bank_transaction_number']) ? $row['bank_transaction_refernce'] : $row['transaction_number'];
                                    // echo $row['transcategory']."<br>";
                                    switch ($row['transcategory']) {
                                        case 'Invoice':
                                            if (!$refnumber) {
                                                $invoiceid = ($invoiceid) ? $invoiceid : $deliverynote_invoiceid;
                                                $transaction_number = $row['transaction_number'];
                                                $relation = '<a href="' . base_url("invoices/create?id=$invoiceid") . '">' . $transaction_number . '</a>';
                                            } else {
                                                $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                            }
                                            break;
                                    
                                        case 'Invoice Return':
                                            if (!$refnumber) {
                                                $invoice_returnid = $row['invoice_returnid'];
                                                $transaction_number = $row['transaction_number'];
                                                $link = base_url('invoicecreditnotes/create?iid=' . $invoice_returnid);
                                                // $link = base_url('invoicecreditnotes/view?id=' . $invoice_returnid);invoicecreditnotes/create?iid=2
                                                $relation = '<a href="' . $link . '">' . $transaction_number . '</a>';
                                            } else {
                                                
                                                $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                            }
                                            
                                            break;
                                    
                                        case 'Purchase':
                                            if (!$refnumber) {
                                                $receipt_number = $row['receipt_number'];
                                                $purchase_reciept_number = $row['purchase_reciept_number'];
                                                $validtoken = hash_hmac('ripemd160', $receipt_number, $this->config->item('encryption_key'));
                                                $link = base_url('Invoices/costing?id=' . $purchase_reciept_number . '&token=' . $validtoken);
                                                $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                            } else {
                                                $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                            }
                                            break;
                                        case 'Purchase Return':
                                            if (!$refnumber) {
                                                $receipt_number = $row['receipt_return_number'];
                                                $validtoken = hash_hmac('ripemd160', $receipt_number, $this->config->item('encryption_key'));
                                                $link = base_url('purchasereturns/create?pid=' . $receipt_number . '&token=' . $validtoken);
                                                $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                            }
                                            //  else {
                                            //     $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                            // }
                                            break;

                                        case 'Expense Claim':
                                            if (!$refnumber) {
                                                $receipt_number = $row['receipt_number'];
                                                $claim_number = $row['claim_number'];
                                                $validtoken = hash_hmac('ripemd160', $receipt_number, $this->config->item('encryption_key'));
                                                $link = base_url('expenseclaims/view?id=' . $claim_number);
                                                $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                            } else {
                                                $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                            }
                                            break;
                                        case 'Journal Entry':
                                                $transaction_number = $row['transaction_number'];
                                                $link = base_url('manualjournals/view?id=' . $transaction_number);
                                                $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                           
                                            break;
                                        case 'Deliverynote':
                                                $deliverynoteid =  $row['delevery_note_id'];
                                                $transaction_number = $row['transaction_number'];
                                                $deliverynote_invoiceid = $row['deliverynote_invoiceid'];
                                                $link = ($row['deliverynote_invoice_number']) ?  base_url('invoices/create?id=' . $deliverynote_invoiceid) : base_url('DeliveryNotes/create?id=' . $deliverynoteid);
                                                $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                           
                                            break;
                                        case 'Delivery Return':                                            
                                                $delivery_return_number =  $row['delivery_return_number'];
                                                $transaction_number = $row['transaction_number'];
                                                $link = base_url('Deliveryreturn/deliveryreturn_view?id=' . $delivery_return_number);
                                                $relation = '<a href="' . $link . '">' . $transaction_number . '</a>';
                                           
                                            break;

                                       
                                        default:
                                            if ($refnumber) {
                                                $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                            } else {
                                                $relation = '<a href="' . base_url("invoices/create?id=$invoiceid") . '">' . $relation . '</a>';
                                            }
                                            break;
                                    }
                                    
                                    $credit = $row['creditamount'];
                                    $debit = $row['debitamount'];
                                    $balance = $balance + ($debit-$credit);
                                    $total_balance += $balance;
                                    $total_credit += $credit;
                                    $total_debit += $debit;
                                   
                                    echo "<tr>";
                                    echo "<td>$i</td>
                                        <td>$date</td>
                                        <td>$relation</td>
                                        <td class='text-right'>" . number_format(abs($debit), 2) . "</td>
                                        <td class='text-right'>" . number_format(abs($credit), 2) . "</td>
                                        <td class='text-right'>" . number_format(abs($balance), 2) . "</td>
                                        <td></td>";
                                    echo "</tr>";
                                    $i++;
                                }
                                // echo '<tr><td></td><td></td><td class="text-right">'.$this->lang->line('Totals and Closing Balance').'</td><td>'.number_format($total_debit,2).'</td><td>'.number_format($total_credit,2).'</td><td>'.number_format($total_balance,2).'</td><td></td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right"><?php echo $this->lang->line('Totals and Closing Balance'); ?></th>
                                <th class="text-right"><?php echo number_format(abs($total_debit), 2); ?></th>
                                <th class="text-right"><?php echo number_format(abs($total_credit), 2); ?></th>
                                <th class="text-right"><?php echo number_format(abs($balance), 2); ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
