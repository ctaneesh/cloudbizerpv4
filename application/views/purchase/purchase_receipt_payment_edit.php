<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices/stockreciepts') ?>"><?php echo $this->lang->line('Purchase Receipts'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Purchase Payment'); ?></li>
                </ol>
            </nav>
         
         <div class="row">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <h4 class="card-title">
                     <?php echo $this->lang->line('Purchase Payment'); ?>
                     <?php //echo $this->lang->line('Customer Payment for Invoice')." #".$invoice['tid']; ?>
                  </h4>
               </div>
              
         </div>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
               <ul class="list-inline mb-0">
                  <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
               </ul>
         </div>
      </div>
      <div class="card-content mt-1">
         <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
         </div>
         <div id="thermal_a" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
         </div>
         <div id="invoice-template" class="card-body">
            <div class="row">
               <div class="col-12">
                  <?php
                     $validtoken = hash_hmac('ripemd160', $invoice['purchase_reciept_number'], $this->config->item('encryption_key')); 
                     $link = base_url('Invoices/costing?id=' . $invoice['purchase_reciept_number'] . '&token=' . $validtoken);
                 ?>
               </div>
            </div>
            
            <!-- Invoice Customer Details -->
            <form class="payment" name="customerPaymentForm" id="customerPaymentForm" method="POST">
               <div id="invoice-customer-details" class="row">
                  <div class="col-sm-12 text-xs-center text-md-left">
                     <p class="text-muted"><?php echo $this->lang->line('Supplier Details') ?></p>
                  </div>
                  <div class="col-md-4 col-sm-12 text-xs-center text-md-left">
                        <?php 
                           
                           echo '<a href="'.base_url('supplier/view?id=' . $invoice['id']).'"><strong  class="invoice_a">'.$invoice['name'] . '</strong></a><br>';
                           echo "Company&nbsp;: <b>" .$invoice['company']."</b>\n<br>"; 
                           echo $invoice['address']."<br>"; 
                           echo $invoice['city']."<br>"; 
                           echo "Company Phone &nbsp;&nbsp;&nbsp;: <b>" .$invoice['phone']."</b>\n<br>"; 
                           echo "Company Email &nbsp;&nbsp;&nbsp;: <b>" .$invoice['email']."</b>\n<br>";  
                           echo "Contact Person &nbsp;&nbsp;&nbsp;: <b>" .$invoice['contact_person']."</b>\n<br>";  
                           echo "Contact Person Phone &nbsp;&nbsp;&nbsp;: <b>" .$invoice['contact_phone1']."</b>\n<br>";  
                           echo "Contact Person Email &nbsp;&nbsp;&nbsp;: <b>" .$invoice['contact_email1']."</b>\n<br>";  
                        
                        ?>
                        
                  </div>
                  <!-- ================================== Customer Section ends ========================== -->
                  <div class="col-md-8 col-sm-12">
                     <div class="row">
                   
                        <!-- ======================================================== -->
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-group">
                              <label for="Total Amount" class='col-form-label'><?=$this->lang->line('Payment Date')?><span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control required" placeholder="Payment Date" name="paydate" value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-group">
                           <?php
                                     $al_payment_method = $transaction_ai['payment_method'];
                                ?>
                              <label for="pmethod" class="col-form-label"><?php echo $this->lang->line('Payment Method') ?><span class="compulsoryfld"> *</span></label>
                              <select name="pmethod" id="pmethod" class="form-control mb-1 required" required>
                                 <option value=""><?php echo $this->lang->line('Select Payment Method') ?></option>
                                 <option value="Cash"  <?php if($al_payment_method=='Cash'){ echo 'selected'; } ?>><?php echo $this->lang->line('Cash') ?></option>
                                 <option value="Card" <?php if($al_payment_method=='Card'){ echo 'selected'; } ?>> <?php echo $this->lang->line('Card') ?></option>
                                 <option value="Cheque" <?php if($al_payment_method=='Cheque'){ echo 'selected'; } ?>><?php echo $this->lang->line('Cheque') ?></option>
                                 <option value="Bank" <?php if($al_payment_method=='Bank'){ echo 'selected'; } ?>>  <?php echo $this->lang->line('Bank') ?></option>
                                 <option value="Balance" <?php if($al_payment_method=='Balance'){ echo 'selected'; } ?>><?php echo $this->lang->line('Client Balance') ?></option>
                              </select>
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        

                       <!-- ================= type = card ================================= -->
                       <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="row">
                              <div class="col-8">
                                 <div class="form-group">
                                    <label for="Card Number" class='col-form-label'>Card Number<span class="compulsoryfld"> *</span></label>
                                    <input type="number" class="form-control cardrequired paymentrequired" placeholder="Card Number" name="card_number" id="card_number" value="<?=$transaction_ai['card_number']?>">
                                 </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-group">
                                    <label for="Card Number" class='col-form-label'>CVC<span class="compulsoryfld"> *</span></label>
                                    <input type="text" class="form-control cardrequired paymentrequired" placeholder="CVC" name="cvc" id="cvc" value="<?=$transaction_ai['cvc']?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Card Holder Name" class='col-form-label'>Card Holder Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control cardrequired paymentrequired" placeholder="Card Holder Name" name="card_holder" id="card_holder" value="<?=$transaction_ai['card_holder']?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Card Expiry Date" class='col-form-label'>Card Expiry Date<span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control cardrequired paymentrequired" placeholder="Card Expiry Date" name="card_expiry_date" id="card_expiry_date" value="<?=$transaction_ai['card_expiry_date']?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                       <!-- ================= type = card ================================= -->

                        <!-- ================= type = Cheque ================================= -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Pay From" class='col-form-label'>Pay From<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control chequerequired paymentrequired" placeholder="Pay From" name="cheque_pay_from" id="cheque_pay_from" value="<?=$transaction_ai['cheque_pay_from']?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Cheque Account Number" class='col-form-label'>Cheque Account Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control chequerequired paymentrequired" placeholder="Cheque Account Number" name="cheque_account_number" id="cheque_account_number" value="<?=$transaction_ai['cheque_account_number']?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Cheque Number" class='col-form-label'>Cheque Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control chequerequired paymentrequired" placeholder="Cheque Number" name="cheque_number" id="cheque_number" value="<?=$transaction_ai['cheque_number']?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Cheque Date" class='col-form-label'>Cheque Date<span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control chequerequired paymentrequired" placeholder="Cheque Date" name="cheque_date" id="cheque_date"  value="<?=$transaction_ai['cheque_date']?>">
                           </div>
                        </div>
                        <!-- ================= type = Cheque ================================= -->

                        <!-- =================  type = Account ================================= -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Account Name" class='col-form-label'>Bank Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Bank Name" name="account_bank_name" id="account_bank_name" value="<?=$transaction_ai['account_bank_name']?>">>
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Account Address" class='col-form-label'>Bank Address<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Bank Address" name="account_bank_address" id="account_bank_address" value="<?=$transaction_ai['account_bank_address']?>">>
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Account Number" class='col-form-label'>Account <span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control bankrequired paymentrequired" placeholder="Account Number" name="account_number" id="account_number" value="<?=$transaction_ai['account_number']?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group">
                              <label for="Account Holder Name" class='col-form-label'>Account Holder Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Account Holder Name" name="account_holder_name" id="account_holder_name" value="<?=$transaction_ai['account_holder_name']?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group">
                              <label for="IFSC Code" class='col-form-label'>IFSC Code</label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="IFSC Code" name="account_ifsc_code" id="account_ifsc_code" value="<?=$transaction_ai['account_ifsc_code']?>">
                           </div>
                        </div>
                        <!-- ================= type = Account ================================= -->
                              <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 paymentmethod amountsection d-none">
                           <fieldset class="form-group">
                              <label for="Total Amount" class='col-form-label'><?php echo $this->lang->line('Payable Amount') ?><span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control" required placeholder="Payable Amount" name="amount" id="rmpay"  value="<?=$transaction_data['trans_amount']?>">
                              <input type="hidden" class="form-control" required placeholder="Received Amount" name="old_amount" id="old_amount" value="<?=$transaction_data['trans_amount']?>">
                           </fieldset>
                        </div>
                        <!-- ======================================================== -->


                        <!-- ======================================================== -->

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-group"> 
                              <label for="account" class="col-form-label"><?php echo $this->lang->line('Account') ?><span class="compulsoryfld"> *</span></label>
                              <select name="bank_account" id="bank_account" class="form-control required" required>
                                 <option value=""><?php echo $this->lang->line('Select') . " " . $this->lang->line('Account'); ?></option>
                                 <?php foreach ($bankaccounts as $row) {
                                    $sel = "";
                                    if ($transaction_data['banktransfer_accountid'] == $row['code']) {
                                          $sel = 'selected';
                                    }
                                    echo '<option value="' . $row['code'] . '" ' . $sel . '>' . $row['code'] . ' - ' . $row['name'] . '</option>';
                                 }
                                 ?>
                              </select>

                           </div>
                        </div>

                        <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-group">
                              <label for="account" class="col-form-label"><?php echo $this->lang->line('Debit Account') ?><span class="compulsoryfld"> *</span></label>
                              <select name="account" class="form-control required" required>
                              <option value=""><?php echo $this->lang->line('Select')." ".$this->lang->line('Debit Account'); ?></option>
                              <?php foreach ($acclist as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                                 }
                                 ?>
                              </select>
                           </div>
                        </div> -->
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                           <label class="col-form-label" for="address"><?php echo $this->lang->line('Chart of Account') ?><span class="compulsoryfld">*</span></label>
                              
                           <?php
                              echo '<select name="account_type_id" id="account_type_id" class="form-control">';
                              echo '<option value="">Select Type</option>';
                              foreach ($accountheaders as $parentItem) {
                                 $coaHeaderId = $parentItem['coa_header_id'];
                                 $coaHeader = $parentItem['coa_header'];
                                 if (isset($accountlists[$coaHeaderId])) {
                                    echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';                                          
                                 foreach ($accountlists[$coaHeaderId] as $row) {
                                       $aid = $row['id'];
                                       $holder = $row['holder'];
                                       $balance = $row['lastbal'];
                                       $type = $row['account_type'];
                                       $acn = $row['acn'];
                                       $selted = "";
                                       if ($transaction_data['trans_chart_of_account_id'] == $acn) {
                                          $selted = 'selected';
                                       }
                                       echo '<option value="' . htmlspecialchars($acn) . '" data-id="' . htmlspecialchars($holder) . '" ' . $selted . '>' . $acn." - ".htmlspecialchars($holder) . '</option>';
                                       
                                 }
                              }
                           }
                           echo '</select>';
                           ?>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                           <div class="form-group">
                              <label class="col-form-label" for="shortnote" ><?php echo $this->lang->line('Note') ?></label>
                              <textarea type="text" class="form-textarea"  name="shortnote" placeholder="Note"><?=$transaction_ai['note']?></textarea>
                           </div>
                        </div>
                        <!-- ======================================================== -->

                        
                        
                     </div>
                  </div>
                        
                  <!-- ============================ -->
                     <div class="col-12 table-scroll">
                           <!-- <div class="btn-group alert alert-danger text-center mt-2" role="alert">
                        <?php //echo $this->lang->line("Select atleast one invoice"); ?>
                           </div> -->
                           <table class="table table-striped table-bordered zero-configuration dataTable"
                              id="paymentTable">
                              <thead>
                                 <tr>
                                       <th width="5%" class="text-center">
                                          <?php echo $this->lang->line("Purchase Receipt No."); ?></th>
                                       <th width="5%" class="text-center">
                                          <?php echo $this->lang->line("Receipt Date"); ?></th>
                                       <th width="5%" class="text-center">
                                          <?php echo $this->lang->line("Bill No."); ?></th>
                                       <th width="7%"><?php echo $this->lang->line("Bill Amount"); ?></th>
                                       <th width="7%"><?php echo $this->lang->line("Expense Amount"); ?></th>
                                       <th width="7%"><?php echo $this->lang->line("Paid Amount"); ?></th>
                                       <th width="7%"><?php echo $this->lang->line("Due Amount"); ?></th>
                                       <th><?php echo $this->lang->line("Payment Status"); ?></th>
                                 </tr>

                              </thead>
                              <tbody>
                                 <?php 
                                 
                                 if(!empty($invoice)){
                                    // $balanceamount = $invoice['bill_amount'] - $invoice['payment_recieved_amount'];
                                    $balanceamount = (($invoice['bill_amount'] + $invoice['costing_amount']) - $invoice['payment_recieved_amount']);
                                    $totalinvoiceamount = (($invoice['bill_amount'] + $invoice['costing_amount']));
                                    $billdate = (!empty($invoice['purchase_receipt_date'])) ? dateformat($invoice['purchase_receipt_date']):"";
                                    $totalval = 0;
                                    $paymentamount = (($invoice['bill_amount'] + $invoice['costing_amount']) - $invoice['purchase_paid_amount']);
                                       $invoice_number = ($transaction_data['invoice_number']) ? $transaction_data['invoice_number'] : $transaction_data['invoiceumber'];
                                       echo "<tr>"; 
                                       echo "<td class='text-center'><a href='".$link."'>".$invoice['purchase_reciept_number']."</a></td>";
                                       echo "<td class='text-center'>".$billdate."</td>";
                                       echo "<td class='text-center'>".$invoice['bill_number']."</td>";
                                       echo "<td class='text-right'>".number_format($invoice['bill_amount'],2)."</td>";
                                       echo "<td class='text-right'>".number_format($invoice['costing_amount'],2)."</td>";
                                       echo "<td class='text-right'>".number_format($invoice['purchase_paid_amount'],2)."</td>";
                                       echo "<td class='text-right'>".number_format($paymentamount,2)."</td>";
                                       echo "<td>".$invoice['payment_status']."</td>";
                                       echo "</tr>";
                                       $totalval = $totalval + $paymentamount;
                                       echo "<input type='hidden' value='".$totalval."' name='totalamount' id='totalamount'>";
                                       echo "<input type='hidden' name='selectedInvoiceamount' id='selectedInvoiceamount' value='".$totalval."'>";
                                       echo "<input type='hidden' name='totalinvoiceamount' id='totalinvoiceamount' value='".$totalinvoiceamount."'>";
                                       echo "<input type='hidden' name='totaldueamt' id='totaldueamt' value='".$paymentamount."'>";
                                       echo "<input type='hidden' name='available-limit-amount' id='available-limit-amount'>";
                                       $paidamt = ($invoice['payment_recieved_amount'])?$invoice['payment_recieved_amount']:0;
                                       echo "<input type='hidden' name='paid_amount' id='paid_amount' value='".$paidamt."'>";
                                      
                                 }
                                 ?>
                                  <input type="hidden" name="bank_transaction_number" id="bank_transaction_number" value="<?=$transaction_data['bank_transaction_number']?>">
                                 <input type="hidden" name="transaction_number" id="transaction_number"  value="<?=$transaction_data['transaction_number']?>">
                                 <input type="hidden" name="trans_ref_number" id="trans_ref_number" value="<?=$transaction_data['trans_ref_number']?>">
                                 <input type="hidden" name="invoicestatus" id="invoicestatus" value="<?=$invoice['payment_status']?>">
                              </tbody>
                           </table>
                     </div>
                  <!-- ============================ -->
                  
               </div>
           
            <!--/ Invoice Customer Details -->

            <!-- ======================================================== -->
            <div class="col-12 text-right">
               <div class="form-group">
                  
                  <input type="hidden" name="trans_ai_id" id="trans_ai_id" value="<?=$transaction_ai['id']?>">
                  <input type="hidden" class="form-control required"  name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <input type="hidden" class="form-control required"  name="receipt_number" id="receipt_number" value="<?php echo $invoice['srv'] ?>">
                  <input type="hidden" class="form-control required"  name="receipt_id" id="receipt_id" value="<?php echo $invoice['iid'] ?>">
                  <input type="hidden" class="form-control required"  name="purchase_id" id="purchase_id" value="<?php echo $invoice['purchase_id'] ?>">
                  <input type="hidden" class="form-control required"  name="credit_limit" id="credit_limit" value="<?php echo $invoice['credit_limit'] ?>">
                  <!-- <button type="button" class="btn btn-md btn-default" ><?php echo $this->lang->line('Close') ?></button> -->
                  <input type="hidden" name="cid" value="<?php echo $invoice['cid'] ?>"><input type="hidden" name="cname" value="<?php echo $invoice['name'] ?>">
                  <input type="hidden" class="form-control required"  name="purchase_number" id="purchase_number" value="<?php echo $invoice['purchase_number'] ?>">
                  <button type="submit" class="btn bt-crud btn-lg btn-primary" id="update_payment_btn"><?php echo $this->lang->line('Update'); ?></button>
                  <!-- <button type="button" class="btn btn-lg btn-primary" id="submitpayment"><?php echo $this->lang->line('Make Payment'); ?></button> -->
               </div>
            </div>
         <!-- ======================================================== -->
         </form>
           
         </div>
      </div>
   </div>
</div>
<script src="<?php echo base_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
<script src="<?php echo base_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
   /*jslint unparam: true */
   /*global window, $ */
   $(function () {
       'use strict';
       // Change this to the location of your server-side upload handler:
       var url = '<?php echo base_url() ?>invoices/file_handling?id=<?php echo $invoice['iid'] ?>';
       $('#fileupload').fileupload({
           url: url,
           dataType: 'json',
           formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
           done: function (e, data) {
               $.each(data.result.files, function (index, file) {
                   $('#files').append('<tr><td><a data-url="<?php echo base_url() ?>invoices/file_handling?op=delete&name=' + file.name + '&invoice=<?php echo $invoice['iid'] ?>" class="aj_delete red"><i class="btn-sm  bt-crud fa fa-trash"></i></a> ' + file.name + ' </td></tr>');
               });
   
           },
           progressall: function (e, data) {
   
               var progress = parseInt(data.loaded / data.total * 100, 10);
   
               $('#progress .progress-bar').css(
                   'width',
                   progress + '%'
               );
   
           }
       }).prop('disabled', !$.support.fileInput)
           .parent().addClass($.support.fileInput ? undefined : 'disabled');
   });
   
   $(document).on('click', ".aj_delete", function (e) {
       e.preventDefault();
   
       var aurl = $(this).attr('data-url');
       var obj = $(this);
   
       jQuery.ajax({
   
           url: aurl,
           type: 'GET',
           dataType: 'json',
           success: function (data) {
               obj.closest('tr').remove();
               obj.remove();
           }
       });
   
   });
</script>
<!-- Modal HTML -->

<!-- cancel -->
<div id="cancel_bill" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Cancel Invoice'); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form class="cancelbill">
               <?php echo $this->lang->line('You can not revert'); ?>
         </div>
         <div class="modal-footer">
         <input type="hidden" class="form-control"
            name="tid" value="<?php echo $invoice['iid'] ?>">
         <button type="button" class="btn btn-md btn-default  bt-crud"
            data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
         <button type="button" class="btn  bt-crud btn-md btn-primary"
            id="send"><?php echo $this->lang->line('Cancel Invoice'); ?></button>
         </div>
         </form>
      </div>
   </div>
</div>
</div>
</div>
<!-- Modal HTML -->

<div id="pop_model" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Change Status'); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form id="form_model">
               <div class="row">
                  <div class="col">
                     <label for="status"><?php echo $this->lang->line('Mark As') ?></label>
                     <select name="status" class="form-control mb-1">
                        <option value="paid"><?php echo $this->lang->line('Paid'); ?></option>
                        <option value="due"><?php echo $this->lang->line('Due'); ?></option>
                        <option value="partial"><?php echo $this->lang->line('Partial'); ?></option>
                     </select>
                  </div>
               </div>
               <div class="modal-footer">
                  <input type="hidden" class="form-control required"
                     name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <button type="button" class="btn bt-crud  btn-md btn-default"
                     data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                  <input type="hidden" id="action-url" value="invoices/update_status">
                  <button type="button" class="btn  bt-crud btn-md btn-primary"
                     id="submit_model"><?php echo $this->lang->line('Change Status'); ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">

$(document).ready(function(){
   $("#pmethod").trigger('change');
  $("#account_type_id").select2({
      placeholder: "Type Account", 
      allowClear: true,
      width: '100%'
   });  
  $("#bank_account").select2({
      placeholder: "Type Account", 
      allowClear: true,
      width: '100%'
   });  
   $("#customerPaymentForm").validate({
        rules: {
            pmethod: {
                required: true
            },
            amount: {
                required: true
            },
            account: {
                required: true,
            }
        },
        messages: {
            pmethod: {
                required: "Please select a payment method"
            },
            amount: {
                required: "Enter Valid Amount"
            },
            account: {
                required: "Please enter an account number",
                minlength: "Your account number must be at least 4 characters long"
            }
        },
        submitHandler: function(form) {
            // Handle form submission
            form.submit(); // You can replace this with your own logic
        }
    });


    

});



    //paymet type
    $("#pmethod").on('change', function() {
      var selectedValue = $(this).val();
      
      // Hide all payment method sections and remove required fields
      $('.paymentmethod').addClass('d-none');
      $('.paymentrequired').prop('required', false);

      // Display the amount section if a valid method is selected
      if (selectedValue != "") {
         $('.amountsection').removeClass('d-none');
      }

      // Conditional logic based on payment method
      if (selectedValue == 'Cash') {
         $('.paymentmethod').addClass('d-none'); // Hide all payment methods
         $('.amountsection').removeClass('d-none'); // Show amount section
         $('.paymentrequired').prop('required', false); // No required fields for Cash
      } 
      else if (selectedValue == 'Card') {
         $('.cardtype').removeClass('d-none'); // Show card section
         $('.cardrequired').prop('required', true); // Make card fields required
      } 
      else if (selectedValue == 'Cheque') {
         $('.chequetype').removeClass('d-none'); // Show cheque section
         $('.chequerequired').prop('required', true); // Make cheque fields required
      } 
      else if (selectedValue == 'Bank') {
         $('.accounttype').removeClass('d-none'); // Show bank account section
         $('.bankrequired').prop('required', true); // Make bank fields required
      }
   });

   $('#invoicecheckbox').change(function(){
      if($(this).is(':checked')){
         $(".checkeditems").prop('checked', true);
      } 
      else {
         $(".checkeditems").prop('checked', false);
      }
   });

   $('#update_payment_btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#update_payment_btn').prop('disabled',true);
    // Validate the form    
    hasUnsavedChanges = false;
    if ($("#customerPaymentForm").valid()) {
        var recievedamt = $('#rmpay').val();
        var old_amount = $('#old_amount').val();
        var dueamt = parseFloat($('#totaldueamt').val());
      
         if(parseFloat(old_amount)+parseFloat(dueamt) < parseFloat(recievedamt))
         {
               $("#rmpay").val("");
               $('#update_payment_btn').click();
               return;
         }
        var form = $('#customerPaymentForm')[0];
        var formData = new FormData(form);
        Swal.fire({
                  title: "Are you sure?",
                  text: "Do you want to make payment now?",
                  icon: "question",
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, proceed!',
                  cancelButtonText: "No - Cancel",
                  reverseButtons: true,  
                  focusCancel: true,      
                  allowOutsideClick: false,  // Disable outside click
                  }).then((result) => {
                  if (result.isConfirmed) {
                     $.ajax({
                           url: baseurl + "transactions/paypurchase_edit",
                           type: 'POST',
                           data: formData,
                           contentType: false,
                           processData: false,
                           success: function(response) {
                              
                              var data = JSON.parse(response);
                              
                              window.location.href = baseurl + 'Invoices/stockreciepts';
                           },
                           error: function(xhr, status, error) {
                              Swal.fire('Error', 'An error occurred while generating the material request', 'error');
                              console.log(error); // Log any errors
                           }
                     });
                  } else if (result.dismiss === Swal.DismissReason.cancel) {
                     // Enable the button again if user cancels
                     $('#update_payment_btn').prop('disabled', false);
                  }
               });
    
    }
    else{
        $('#update_payment_btn').prop('disabled',false);
    }
});
   
</script>