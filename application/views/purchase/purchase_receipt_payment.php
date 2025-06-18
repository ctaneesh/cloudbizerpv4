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
                     $validtoken = hash_hmac('ripemd160', $invoice['iid'], $this->config->item('encryption_key')); 
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

                        <?php 
                           $balanceamount = (($invoice['bill_amount'] + $invoice['costing_amount']) - $invoice['purchase_paid_amount']);
                           // $balanceamount = $invoice['bill_amount'] - $invoice['purchase_paid_amount'];
                           $billdate = (!empty($invoice['bill_date'])) ? date('d-m-Y',strtotime($invoice['bill_date'])):"";
                           // echo '<p class="text-muted mt-2"><u>'.$this->lang->line('Purchase Receipt').$this->lang->line('Details').'</u></p>';
                           // echo $this->lang->line('Purchase Receipt No.')." : &nbsp;<a href='".$link."'>".$invoice['purchase_reciept_number']."</a></b>\n<br>"; 
                           // echo $this->lang->line('Bill No.')."&nbsp;: <b>" .$invoice['bill_number']."</b>\n<br>"; 
                           // echo $this->lang->line('Bill Date')." &nbsp;: <b>" .$billdate."</b>\n<br>"; 
                           // echo $this->lang->line('Payment Status')." &nbsp;: <b>" .$invoice['payment_status']."</b>\n<br>"; 
                           // echo $this->lang->line('Bill Amount')."&nbsp;: <b>" .number_format($invoice['bill_amount'],2)."</b>\n<br>";
                           // echo $this->lang->line('Paid Amount')." &nbsp;: &nbsp;<b>" .number_format($invoice['purchase_paid_amount'],2)."</b>\n<br>";
                           // echo $this->lang->line('Balance Amount To Pay')." : &nbsp;<b>" .number_format($balanceamount,2)."</b>\n<br>";
                        
                        ?>
                        
                  </div>
                  <!-- ================================== Customer Section ends ========================== -->
                  <div class="col-md-8 col-sm-12">
                     <div class="row">
                   
                        <!-- ======================================================== -->
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-row">
                              <label for="paydate" class='col-form-label'><?=$this->lang->line('Payment Date')?><span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control required" placeholder="Payment Date" name="paydate" value="<?=date('Y-m-d')?>" data-original-value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-row">
                              <label for="pmethod" class="col-form-label"><?php echo $this->lang->line('Payment Method') ?><span class="compulsoryfld"> *</span></label>
                              <select name="pmethod" id="pmethod" class="form-control mb-1 required" data-original-value="" required>
                                 <option value=""><?php echo $this->lang->line('Select Payment Method') ?></option>
                                 <option value="Cash"><?php echo $this->lang->line('Cash') ?></option>
                                 <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                                 <option value="Cheque"><?php echo $this->lang->line('Cheque') ?></option>
                                 <option value="Bank"><?php echo $this->lang->line('Bank') ?></option>
                                 <option value="Balance"><?php echo $this->lang->line('Client Balance') ?></option>
                              </select>
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        

                       <!-- ================= type = card ================================= -->
                       <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="row">
                              <div class="col-8">
                                 <div class="form-row">
                                    <label for="card_number" class='col-form-label'>Card Number<span class="compulsoryfld"> *</span></label>
                                    <input type="number" class="form-control cardrequired paymentrequired" placeholder="Card Number" name="card_number" id="card_number" data-original-value="">
                                 </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-row">
                                    <label for="cvc" class='col-form-label'>CVC<span class="compulsoryfld"> *</span></label>
                                    <input type="text" class="form-control cardrequired paymentrequired" placeholder="CVC" name="cvc" id="cvc" data-original-value="">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="form-row">
                              <label for="card_holder" class='col-form-label'>Card Holder Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control cardrequired paymentrequired" placeholder="Card Holder Name" name="card_holder" id="card_holder" data-original-value="">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="form-row">
                              <label for="card_expiry_date" class='col-form-label'>Card Expiry Date<span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control cardrequired paymentrequired" placeholder="Card Expiry Date" name="card_expiry_date" id="card_expiry_date" data-original-value="">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                       <!-- ================= type = card ================================= -->

                        <!-- ================= type = Cheque ================================= -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-row">
                              <label for="cheque_pay_from" class='col-form-label'>Pay From<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control chequerequired paymentrequired" placeholder="Pay From" name="cheque_pay_from" id="cheque_pay_from" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-row">
                              <label for="cheque_account_number" class='col-form-label'>Cheque Account Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control chequerequired paymentrequired" placeholder="Cheque Account Number" name="cheque_account_number" id="cheque_account_number" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-row">
                              <label for="cheque_number" class='col-form-label'>Cheque Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control chequerequired paymentrequired" placeholder="Cheque Number" name="cheque_number" id="cheque_number" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-row">
                              <label for="cheque_date" class='col-form-label'>Cheque Date<span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control chequerequired paymentrequired" placeholder="Cheque Date" name="cheque_date" id="cheque_date" data-original-value="">
                           </div>
                        </div>
                        <!-- ================= type = Cheque ================================= -->

                        <!-- =================  type = Account ================================= -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-row">
                              <label for="account_bank_name" class='col-form-label'>Bank Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Bank Name" name="account_bank_name" id="account_bank_name" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-row">
                              <label for="account_bank_address" class='col-form-label'>Bank Address<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Bank Address" name="account_bank_address" id="account_bank_address" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-row">
                              <label for="account_number" class='col-form-label'>Account Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control bankrequired paymentrequired" placeholder="Account Number" name="account_number" id="account_number" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-row">
                              <label for="account_holder_name" class='col-form-label'>Account Holder Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Account Holder Name" name="account_holder_name" id="account_holder_name" data-original-value="">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-row">
                              <label for="account_ifsc_code" class='col-form-label'>IFSC Code</label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="IFSC Code" name="account_ifsc_code" id="account_ifsc_code" data-original-value="">
                           </div>
                        </div>
                        <!-- ================= type = Account ================================= -->
                              <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12 paymentmethod amountsection d-none">
                           <fieldset class="form-row">
                              <label for="rmpay" class='col-form-label'><?php echo $this->lang->line('Payable Amount') ?><span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control" required placeholder="Payable Amount" name="amount" id="rmpay" value="<?=$balanceamount?>" data-original-value="<?=$balanceamount?>">
                              <input type="hidden" class="form-control" placeholder="Payable Amount" name="receipt_amount" id="receipt_amount" value="<?=$balanceamount?>">
                           </fieldset>
                        </div>
                        <!-- ======================================================== -->


                        <!-- ======================================================== -->

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-row"> 
                              <label for="bank_account" class="col-form-label"><?php echo $this->lang->line('Account') ?><span class="compulsoryfld"> *</span></label>
                              <select name="bank_account" id="bank_account" class="form-control required" data-original-value="<?php echo $default_bankaccount['code']  ?>" required>
                                 <option value=""><?php echo $this->lang->line('Select') . " " . $this->lang->line('Account'); ?></option>
                                 <?php foreach ($bankaccounts as $row) {
                                    $sel = "";
                                    if ($default_bankaccount['code'] == $row['code']) {
                                          $sel = 'selected';
                                    }
                                    echo '<option value="' . $row['code'] . '" ' . $sel . '>' . $row['code'] . ' - ' . $row['name'] . '</option>';
                                 }
                                 ?>
                              </select>

                           </div>
                        </div>

                        <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
                           <div class="form-row">
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
                           <label class="col-form-label" for="account_type_id"><?php echo $this->lang->line('Chart of Account') ?><span class="compulsoryfld">*</span></label>
                              
                           <?php
                              echo '<select name="account_type_id" id="account_type_id" class="form-control" data-original-value="<?php echo $default_receivableaccount; ?>">';
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
                                       if ($default_receivableaccount == $acn) {
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
                           <div class="form-row">
                              <label class="col-form-label" for="shortnote" ><?php echo $this->lang->line('Note') ?></label>
                              <textarea type="text" class="form-textarea"  id="shortnote" name="shortnote" placeholder="Note" data-original-value=""></textarea>
                           </div>
                        </div>
                        <!-- ======================================================== -->

                        
                        
                     </div>
                  </div>

                  
               </div>
           
            <!--/ Invoice Customer Details -->
                     <!-- ============================ -->
                     <div class="mb-3 mt-3 table-scroll">
                        <h4><?php echo $this->lang->line('Purchase Receipt').$this->lang->line('Details'); ?></h4>
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
                                    $balanceamount = (($invoice['bill_amount'] + $invoice['costing_amount']) - $invoice['purchase_paid_amount']);
                                    $billdate = (!empty($invoice['purchase_receipt_date'])) ? $invoice['purchase_receipt_date']:"";
                                    $totalval = 0;
                                       $paymentamount = (($invoice['bill_amount'] + $invoice['costing_amount']) - $invoice['purchase_paid_amount']);
                                       $invoice_number = ($transaction_data['invoice_number']) ? $transaction_data['invoice_number'] : $transaction_data['invoiceumber'];
                                       echo "<tr>"; 
                                       echo "<td class='text-center'><a href='".$link."'>".$invoice['purchase_reciept_number']."</a></td>";
                                       echo "<td class='text-center'>".$invoice['bill_number']."</td>";
                                       echo "<td class='text-center'>".$invoice['bill_number']."</td>";
                                       echo "<td class='text-right'>".number_format($invoice['bill_amount'],2)."</td>";
                                       echo "<td class='text-right'>".number_format($invoice['costing_amount'],2)."</td>";
                                       echo "<td class='text-right'>".number_format($invoice['purchase_paid_amount'],2)."</td>";
                                       echo "<td class='text-right'>".number_format($paymentamount,2)."</td>";
                                       echo "<td>".$invoice['payment_status']."</td>";
                                       echo "</tr>";
                                       // $totalval = $totalval + $paymentamount;
                                       // echo "<input type='hidden' value='".$totalval."' name='totalamount' id='totalamount'>";
                                       // echo "<input type='hidden' name='selectedInvoiceamount' id='selectedInvoiceamount' value='".$totalval."'>";
                                       // echo "<input type='hidden' name='totalinvoiceamount' id='totalinvoiceamount' value='".$invoice['bill_amount']."'>";
                                       // echo "<input type='hidden' name='totaldueamt' id='totaldueamt' value='".$paymentamount."'>";
                                       // echo "<input type='hidden' name='available-limit-amount' id='available-limit-amount'>";
                                       // $paidamt = ($invoice['purchase_paid_amount'])?$invoice['purchase_paid_amount']:0;
                                       // echo "<input type='hidden' name='paid_amount' id='paid_amount' value='".$paidamt."'>";
                                      
                                 }
                                 ?>
                                  <!-- <input type="hidden" name="bank_transaction_number" id="bank_transaction_number" value="<?=$transaction_data['bank_transaction_number']?>">
                                 <input type="hidden" name="transaction_number" id="transaction_number"  value="<?=$transaction_data['transaction_number']?>">
                                 <input type="hidden" name="trans_ref_number" id="trans_ref_number" value="<?=$transaction_data['trans_ref_number']?>">
                                 <input type="hidden" name="invoicestatus" id="invoicestatus" value="<?=$invoice['payment_status']?>"> -->
                              </tbody>
                           </table>
                     </div>
                  <!-- ============================ -->
            <!-- ======================================================== -->
            <div class="col-12 text-right">
                  <input type="hidden" class="form-control required"  name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <input type="hidden" class="form-control required"  name="receipt_number" id="receipt_number" value="<?php echo $invoice['purchase_reciept_number'] ?>">
                  <input type="hidden" class="form-control required"  name="receipt_id" id="receipt_id" value="<?php echo $invoice['iid'] ?>">
                  <input type="hidden" class="form-control required"  name="purchase_id" id="purchase_id" value="<?php echo $invoice['purchase_id'] ?>">
                  <input type="hidden" class="form-control required"  name="purchase_number" id="purchase_number" value="<?php echo $invoice['purchase_number'] ?>">
                  <input type="hidden" class="form-control required"  name="credit_limit" id="credit_limit" value="<?php echo $invoice['credit_limit'] ?>">
                  <!-- <button type="button" class="btn btn-md btn-default" ><?php echo $this->lang->line('Close') ?></button> -->
                  <input type="hidden" name="cid" value="<?php echo $invoice['cid'] ?>"><input type="hidden" name="cname" value="<?php echo $invoice['name'] ?>">
                  
                  <button type="submit" class="btn btn-crud btn-lg btn-primary" id="submitpayment1"><?php echo $this->lang->line('Make Payment'); ?></button>
                  <!-- <button type="button" class="btn btn-lg btn-primary" id="submitpayment"><?php echo $this->lang->line('Make Payment'); ?></button> -->
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
                   $('#files').append('<tr><td><a data-url="<?php echo base_url() ?>invoices/file_handling?op=delete&name=' + file.name + '&invoice=<?php echo $invoice['iid'] ?>" class="aj_delete red"><i class="btn-sm fa fa-trash"></i></a> ' + file.name + ' </td></tr>');
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
         <button type="button" class="btn btn-md btn-default"
            data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
         <button type="button" class="btn btn-md btn-primary"
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
                  <button type="button" class="btn  btn-md btn-default"
                     data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                  <input type="hidden" id="action-url" value="invoices/update_status">
                  <button type="button" class="btn  btn-md btn-primary"
                     id="submit_model"><?php echo $this->lang->line('Change Status'); ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
const changedFields = {};
$(document).ready(function(){
    // Add event listeners to all input fields
 //Function for standard form fields.
 document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function () {
            const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
            const originalValue = this.getAttribute('data-original-value');
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();                
            if (!field_label.trim()) {
                field_label = this.getAttribute('title') || 'Unknown Field';
            }

            if (this.type === 'checkbox') {
                // For checkboxes, use the "checked" state
                const newValue = this.checked ? this.value : null;
                const originalChecked = originalValue === this.value;

                if (originalChecked !== this.checked) {
                    changedFields[fieldId] = {
                        oldValue: originalChecked ? this.value : null,
                        newValue: newValue,
                        fieldlabel: field_label
                    }; // Track changes
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            } else if (this.type === 'radio') {
                // For radio buttons, track the selected option
                if (this.checked) {
                    const newValue = this.value;
                    if (originalValue !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel: field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                }
            } else if (this.type === 'number') {
                // For numeric fields
                const newValue = parseFloat(this.value);
                const originalNumber = parseFloat(originalValue);

                if (originalNumber !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalNumber,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else if (this.tagName === 'SELECT') {
                // For select fields, use the option's label
                const selectedOption = this.options[this.selectedIndex];
                const newValue = selectedOption ? selectedOption.label : '';
                const originalLabel = this.getAttribute('data-original-label');

                if (originalLabel !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalLabel,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else {
                // For text and textarea fields
                const newValue = this.value;
                if (originalValue !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            }
        });
    });


    //Function for select2 type dropdown
    $(document).on('select2:select select2:unselect', '.select2-hidden-accessible', function (e) {
        const fieldId = this.id || this.name;
        const originalValue = $(this).data('original-label'); // Original value (could be string or array)
        const newValueArray = $(this).val(); // Get the current value(s) as an array
        const label = $('label[for="' + fieldId + '"]');
        let field_label = label.text();
        if (!field_label.trim()) {
            field_label = this.getAttribute('title') || 'Unknown Field';
        }

        if (Array.isArray(newValueArray)) {
            // Handle multiple select: Get the selected option labels
            const newValueLabels = newValueArray.map(function (value) {
                const option = $('option[value="' + value + '"]', e.target);
                return option.length ? option.text() : ''; // Get the label (text) of the selected option
            });

            const newValue = newValueLabels.join(','); // Convert array of labels to string
            const originalLabels = Array.isArray(originalValue) ? originalValue.map(function (value) {
                const option = $('option[value="' + value + '"]', e.target);
                return option.length ? option.text() : '';
            }).join(',') : originalValue;

            if (originalLabels !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalLabels,
                    newValue: newValue,
                    fieldlabel: field_label,
                };
            } else {
                delete changedFields[fieldId]; // No changes
            }
        } else {
            // Handle single select: Get the selected option label
            const newValue = newValueArray ? $('option[value="' + newValueArray + '"]', e.target).text() : '';
            if (originalValue !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalValue,
                    newValue: newValue,
                    fieldlabel: field_label,
                };
            } else {
                delete changedFields[fieldId]; // No changes
            }
        }
    });


   
  
    // erp2024 newly added 14-06-2024 for detailed history log ends 
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

   $('#submitpayment1').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
   //  $('#submitpayment1').prop('disabled',true);
    // Validate the form    
    hasUnsavedChanges = false;
    if ($("#customerPaymentForm").valid()) {
     
      var receipt_amount = $('#receipt_amount').val();
      var recievedamt = $('#rmpay').val();
      if (receipt_amount == 0) {
         Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Selected Atleat an invoice from the list',
            confirmButtonText: 'OK'
         });
         return;
      }
      //check recieved amount and invoice amount
      if (parseFloat(receipt_amount) < parseFloat(recievedamt)) {
       
        $("#rmpay").val("");
        $('#submitpayment1').click();
        return;
      }
        var form = $('#customerPaymentForm')[0];
        var formData = new FormData(form);
        formData.append('changedFields', JSON.stringify(changedFields));
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
                           url: baseurl + "transactions/paypurchase",
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
                     $('#submitpayment1').prop('disabled', false);
                  }
               });
    
    }
    else{
        $('#submitpayment1').prop('disabled',false);
    }
});
   
</script>