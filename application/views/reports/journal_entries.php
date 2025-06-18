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
                <li class="breadcrumb-item active"><?php echo $this->lang->line('Journal Entries'); ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-lg-3 col-md-5 col-sm-12 col-12">                   
                    <h4 class="card-title"><?php echo $this->lang->line('Journal Entries'); ?></h4>
                    <p>As of the Date - <b><?=date('d-M-Y')?></b><br>Detailed list of all journal entries</p>
                </div>
                
                <div class="col-lg-3 col-md-5 col-sm-12 col-12">
                    <a href="<?php echo base_url(); ?>reports/journal_entries_to_pdf" class="btn btn-crud btn-secondary btn-sm mt-1" target="_blank">PDF</a>
                    <a href="<?php echo base_url(); ?>reports/export_to_excel" class="btn btn-crud btn-secondary btn-sm mt-1" target="_blank">Excel</a>
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
                        if($journal_headers)
                        {
                            
                            foreach ($journal_headers as $value) {
                                $header_flg=1;
                                echo '<table class="table trail-content-table">';
                                
                                $creditamount_total = 0.00;
                                $debitamount_total = 0.00;    
                                foreach ($journal_data[$value['transaction_number']] as $key => $row) {
                                    if($header_flg==1)
                                    {
                                        $invoiceid = $row['invoiceid'];
                                        $refnumber = $row['bank_transaction_refernce'];
                                        $date = ($row['date']) ? date('d M Y', strtotime($row['date'])) : "";
                                        $relation = ($row['bank_transaction_number']) ? $row['bank_transaction_refernce'] : $row['transaction_number'];
                                        switch ($row['transcategory']) {
                                            case 'Invoice':
                                                if (!$refnumber) {
                                                    $relation = '<a href="' . base_url("invoices/view?id=$invoiceid") . '">' . $relation . '</a>';
                                                } else {
                                                    $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                                }
                                                break;
                                        
                                            case 'Invoice Return':
                                                if (!$refnumber) {
                                                    $invoice_returnid = $row['invoice_returnid'];
                                                    $transaction_number = $row['transaction_number'];
                                                    $link = base_url('invoicecreditnotes/view?id=' . $invoice_returnid);
                                                    $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                                } else {
                                                    $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                                }
                                                
                                                break;
                                        
                                            case 'Purchase':
                                                if (!$refnumber) {
                                                    $receipt_number = $row['receipt_number'];
                                                    $validtoken = hash_hmac('ripemd160', $receipt_number, $this->config->item('encryption_key'));
                                                    $link = base_url('Invoices/costing?id=' . $receipt_number . '&token=' . $validtoken);
                                                    $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                                } else {
                                                    $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                                }
                                                break;
                                            case 'Purchase Return':
                                                if (!$refnumber) {
                                                    $receipt_number = $row['invoice_returnid'];
                                                    $validtoken = hash_hmac('ripemd160', $receipt_number, $this->config->item('encryption_key'));
                                                    $link = base_url('stockreturn/create?pid=' . $receipt_number . '&token=' . $validtoken);
                                                    $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                                }
                                                
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
                                                    $link = ($row['deliverynote_invoice_number']) ?  base_url('invoices/view?id=' . $deliverynote_invoiceid) : base_url('DeliveryNotes/create?id=' . $deliverynoteid);
                                                    $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                               
                                                break;
                                            case 'Delivery Return':                                            
                                                    $delivery_return_number =  $row['delivery_return_number'];
                                                    $transaction_number = $row['transaction_number'];
                                                    $link = base_url('Deliveryreturn/deliveryreturn_view?id=' . $delivery_return_number);
                                                    $relation = '<a href="' . $link . '">' . $relation . '</a>';
                                               
                                                break;
    
                                           
                                            default:
                                                if ($refnumber) {
                                                    $relation = '<a href="' . base_url("transactions/banking_transaction?ref=$refnumber") . '">' . $relation . '</a>';
                                                } else {
                                                    $relation = '<a href="' . base_url("invoices/view?id=$invoiceid") . '">' . $relation . '</a>';
                                                }
                                                break;
                                        }
                                        echo '<thead>
                                            <tr class="border-primary">
                                                <th  class="account-name expand-transaction">
                                                    '.date('d M Y', strtotime($value['date'])).' <span class="journal-link-class">'.$relation.'</span>
                                                    <span><i class="fa fa-angle-down"></i></span>
                                                </th>
                                            </tr>
                                        </thead>';
                                        $header_flg=0;
                                    }
                                   
                                    $creditamount = 0.00;
                                    $debitamount = 0.00;
                                    $code = $row['acn'];
                                    $debitamount = $row['debitamount'];
                                    $creditamount = $row['creditamount'];
                                    $creditamount_total += $row['creditamount'];
                                    $debitamount_total += $row['debitamount'];
                                    echo '<tbody class="transaction-details">
                                                <tr>
                                                    <td scope="col"><a href="' . base_url("transactions/account_transactions?code=$code") . '"><b>'.$row['holder'].'</b></a></td>
                                                    <td scope="col" class="text-right">'.number_format($debitamount,2).'</td>
                                                    <td scope="col" class="text-right">'.number_format($creditamount,2).'</td>
                                                </tr>
                                            </tbody>';
                                    
                                }
                                echo '<tfoot>
                                        <tr>
                                            <th scope="col" class="text-right">'.$this->lang->line('Total').'</th>
                                            <th scope="col" class="text-right amount-font">'.number_format($creditamount_total,2).'</th>
                                            <th scope="col" class="text-right amount-font">'.number_format($debitamount_total,2).'</th>
                                        </tr>
                                    </tfoot>';
                                echo '</table>';
                            }
                        }
                        ?>
                        <table class="table trail-content-table">
                        
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