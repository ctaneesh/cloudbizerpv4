<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Customer Payments'); ?></li>
                </ol>
            </nav>
         
         <div class="row">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <h4 class="card-title">
                     <?php echo $this->lang->line('Customer Payments'); ?>
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
                     $link = base_url('billing/view?id=' . $invoice['iid'] . '&token=' . $validtoken);
                 ?>
               </div>
            </div>
            
            <!-- Invoice Customer Details -->
            <form class="payment" name="customerPaymentForm" id="customerPaymentForm" method="POST">
               <div id="invoice-customer-details" class="row">
                  <div class="col-lg-3 col-md-12 col-sm-12 text-xs-center text-md-left">
                     <h3 class="title-sub"><?php echo $this->lang->line('Customer Details') ?></h3><hr>
                        <input type="hidden" name="customerid" id="customerid" value="<?=$invoice['cid']?>">
                        <?php 
                           
                           $avail_credit_limit = (!empty($invoice['avalable_credit_limit']))?$invoice['avalable_credit_limit']:$invoice['credit_limit'];
                           echo '<a href="'.base_url('customers/view?id=' . $invoice['customer_id']).'"><strong  class="invoice_a">'.$invoice['name'] . '</strong></a><br>';
                           echo "Company&nbsp;: <b>" .$invoice['company']."</b>\n<br>"; 
                           echo $invoice['address']."<br>"; 
                           echo $invoice['city']."<br>"; 
                           echo "Phone &nbsp;&nbsp;&nbsp;: <b>" .$invoice['phone']."</b>\n<br>"; 
                           echo "Email &nbsp;&nbsp;&nbsp;: <b>" .$invoice['email']."</b>\n<br>"; 
                           echo "Company Credit Limit &nbsp;&nbsp;&nbsp;: <b>" .$invoice['credit_limit']."</b>\n<br>"; 
                           echo "Credit Period : <b>" .$invoice['credit_period']."</b>(Days)\n<br>"; 
                           // echo "Available Credit Limit : <span ><b>".$avail_credit_limit."</b><span></span>"; 
                           echo "Available Credit Limit : <span ><b id='available-limit'></b><span></span>"; 
                        
                        ?>
                        
                  </div>
                  <!-- ================================== Customer Section ends ========================== -->
                  <div class="col-lg-9 col-md-12 col-sm-12">
                  <h3 class="title-sub"><?php echo $this->lang->line('Payment Details') ?></h3><hr>
                     <div class="form-row">
                        
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                           <div class="form-group1">
                              <label for="paydate" class='col-form-label'><?=$this->lang->line('Payment Date')?><span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control required" placeholder="Payment Date" id="paydate" name="paydate" value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>" data-original-value="<?=date('Y-m-d')?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                           <div class="form-group1">
                              <label for="pmethod" class="col-form-label" ><?php echo $this->lang->line('Payment Method') ?><span class="compulsoryfld"> *</span></label>
                              <select name="pmethod" id="pmethod" class="form-control mb-1 required" title="<?php echo $this->lang->line('Payment Method') ?>" data-original-value="" required>
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
                       <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="row">
                              <div class="col-8">
                                 <div class="form-group1">
                                    <label for="card_number" class='col-form-label'>Card Number<span class="compulsoryfld"> *</span></label>
                                    <input type="number" class="form-control cardrequired paymentrequired" placeholder="Card Number" name="card_number" id="card_number" title="<?php echo 'Card Number'; ?>">
                                 </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-group1">
                                    <label for="cvc" class='col-form-label'>CVC<span class="compulsoryfld"> *</span></label>
                                    <input type="text" class="form-control cardrequired paymentrequired" placeholder="CVC" name="cvc" id="cvc" title="<?php echo 'CVC'; ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="card_holder" class='col-form-label'>Card Holder Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control cardrequired paymentrequired" placeholder="Card Holder Name" name="card_holder" id="card_holder" title="<?php echo 'Card Holder Name'; ?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 cardtype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="card_expiry_date" class='col-form-label'>Card Expiry Date<span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control cardrequired paymentrequired" placeholder="Card Expiry Date" name="card_expiry_date" id="card_expiry_date" title="<?php echo 'Card Expiry Date'; ?>">
                           </div>
                        </div>
                        <!-- ======================================================== -->
                       <!-- ================= type = card ================================= -->

                        <!-- ================= type = Cheque ================================= -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="cheque_pay_from" class='col-form-label'>Pay From<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control chequerequired paymentrequired" placeholder="Pay From" name="cheque_pay_from" id="cheque_pay_from" title="<?php echo 'Pay From'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="cheque_account_number" class='col-form-label'>Cheque Account Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control chequerequired paymentrequired" placeholder="Cheque Account Number" name="cheque_account_number" id="cheque_account_number" title="<?php echo 'Cheque Account Number'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="cheque_number" class='col-form-label'>Cheque Number<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control chequerequired paymentrequired" placeholder="Cheque Number" name="cheque_number" id="cheque_number" title="<?php echo 'Cheque Number'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 chequetype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="cheque_date" class='col-form-label'>Cheque Date<span class="compulsoryfld"> *</span></label>
                              <input type="date" class="form-control chequerequired paymentrequired" placeholder="Cheque Date" name="cheque_date" id="cheque_date" title="<?php echo 'Cheque Date'; ?>">
                           </div>
                        </div>
                        <!-- ================= type = Cheque ================================= -->

                        <!-- =================  type = Account ================================= -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="account_bank_name" class='col-form-label'>Bank Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Bank Name" name="account_bank_name" id="account_bank_name" title="<?php echo 'Bank Name'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="account_bank_address" class='col-form-label'>Bank Address<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Bank Address" name="account_bank_address" id="account_bank_address" title="<?php echo 'Bank Address'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="account_number" class='col-form-label'>Account <span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control bankrequired paymentrequired" placeholder="Account Number" name="account_number" id="account_number" title="<?php echo 'Account'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="account_holder_name" class='col-form-label'>Account Holder Name<span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="Account Holder Name" name="account_holder_name" id="account_holder_name" title="<?php echo 'Account Holder Name'; ?>">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 accounttype paymentmethod d-none">
                           <div class="form-group1">
                              <label for="account_ifsc_code" class='col-form-label' title="<?php echo 'IFSC Code'; ?>">IFSC Code</label>
                              <input type="text" class="form-control bankrequired paymentrequired" placeholder="IFSC Code" name="account_ifsc_code" id="account_ifsc_code" >
                           </div>
                        </div>
                        <!-- ================= type = Account ================================= -->
                              <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12 paymentmethod amountsection d-none">
                           <fieldset class="form-group1">
                              <label for="rmpay" class='col-form-label'>Received Amount<span class="compulsoryfld"> *</span></label>
                              <input type="number" class="form-control" required placeholder="Received Amount" name="amount" id="rmpay" title="<?php echo 'Received Amount'; ?>" data-original-value="0">
                           </fieldset>
                        </div>
                        <!-- ======================================================== -->


                        <!-- ======================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                           <div class="form-group1"> 
                              <label for="bank_account" class="col-form-label"><?php echo $this->lang->line('Account') ?><span class="compulsoryfld"> *</span></label>
                              <select name="bank_account" id="bank_account" title="<?php echo 'Account'; ?>" class="form-control required" data-original-value="<?php echo $bankaccounts ?>" required>
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
                           <div class="form-group1">
                              <label for="account" class="col-form-label"><?php echo $this->lang->line('Credit Account') ?><span class="compulsoryfld"> *</span></label>
                              <select name="account" class="form-control required" required>
                              <option value=""><?php echo $this->lang->line('Select')." ".$this->lang->line('Credit Account'); ?></option>
                              <?php 
                              // foreach ($acclist as $row) {
                              //    echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                              //    }
                                 ?>
                              </select>
                           </div>
                        </div> -->

                           <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="account_type_id"><?php echo $this->lang->line('Chart of Account') ?><span class="compulsoryfld">*</span></label>
                                    
                                <?php
                                    echo '<select name="account_type_id" id="account_type_id" class="form-control" title="'.$this->lang->line('Chart of Account').'">';
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
                           <div class="form-group1">
                              <label class="col-form-label" for="shortnote" ><?php echo $this->lang->line('Note') ?> <span class="compulsoryfld">*</span></label>
                              <textarea type="text" class="form-textarea"  id="shortnote" name="shortnote" placeholder="Note" required minlength="5" title="Note"></textarea>
                           </div>
                        </div>
                        <!-- ======================================================== -->

                        
                        
                     </div>
                  </div>

                  <div class="col-12 overflow-auto">
                        <div class="btn-group alert alert-danger text-center mt-2" role="alert">
                           <?php echo $this->lang->line("Select atleast one invoice"); ?>
                        </div>
                     <table class="table table-striped table-bordered zero-configuration dataTable" id="paymentTable">
                        <thead>
                           <tr>
                              <th width="2%" class="text-center"><input type="checkbox" name="invoicecheckbox" id="invoicecheckbox" title="Invoices Checkbox"></th>
                              <th width="5%" class="text-center"><?php echo $this->lang->line("Invoice No."); ?></th>
                              <th width="5%" class="text-center"><?php echo $this->lang->line("Invoice Date"); ?></th>
                              <th width="5%" class="text-center"><?php echo $this->lang->line("Invoice Due Date"); ?></th>
                              <th width="7%"><?php echo $this->lang->line("Invoice Amount"); ?></th>
                              <th width=""><?php echo $this->lang->line("Due Amount"); ?></th>
                           </tr>
                          
                        </thead>
                        <tbody >
                        <?php 
                           if(!empty($dew_invoices)){
                              $totalval = 0;
                              foreach($dew_invoices as $row){
                                 $paymentamount = $row['total'] - $row['payment_recieved_amount'];
                                 $invoice_number = ($row['invoice_number']) ? $row['invoice_number'] : $row['invoiceumber'];
                                 echo "<tr>"; 
                                 echo '<td><input type="checkbox" class="checkeditems"  title="Invoices Checkbox" value="'.$invoice_number.'" id="invoice-'.$invoice_number.'" data-id="'.$paymentamount.'"  name="invoice_ids[]"></td>';
                                 echo "<td class='text-center'><a href='" . base_url("invoices/create?id=" . $row['invoice_number']) . "'>&nbsp;" . $invoice_number . "</a></td>";
                                 echo "<td class='text-center'>".date('d-m-Y', strtotime($row['invoice_date']))."</td>";
                                 echo "<td class='text-center'>".date('d-m-Y', strtotime($row['due_date']))."</td>";
                                 echo "<td >".number_format($row['total'],2)."</td>";
                                 echo "<td >".number_format($paymentamount,2)."</td>";
                                 echo "</tr>";
                                 $totalval = $totalval + $paymentamount;
                              }
                               echo "<input type='hidden' value='".$totalval."' name='totalamount' id='totalamount'>";
                               echo "<input type='hidden' name='selectedInvoiceamount' id='selectedInvoiceamount' value='0'>";
                               echo "<input type='hidden' name='available-limit-amount' id='available-limit-amount'>";
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               </div>
           
            <!--/ Invoice Customer Details -->
            <!-- ======================================================== -->
            <hr>
            <div class="col-12 text-right">
              
               <div class="form-group1">
                  <input type="hidden" class="form-control required"  name="invoice_type" id="invoice_type" value="<?php echo $invoice['invoice_type'] ?>">
                  <input type="hidden" class="form-control required"  name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <input type="hidden" class="form-control required"  name="credit_limit" id="credit_limit" value="<?php echo $invoice['credit_limit'] ?>">
                  <!-- <button type="button" class="btn btn-md btn-default" ><?php echo $this->lang->line('Close') ?></button> -->
                  <input type="hidden" name="cid" value="<?php echo $invoice['cid'] ?>"><input type="hidden" name="cname" value="<?php echo $invoice['name'] ?>">
                  <button type="submit" class="btn btn-crud btn-lg btn-primary" id="submitpayment1"><?php echo $this->lang->line('Make Payment'); ?></button>
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
         <button type="button" class="btn btn-crud btn-md btn-default"

            data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
         <button type="button" class="btn btn-crud btn-md btn-primary"
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
                  <button type="button" class="btn btn-crud btn-md btn-default"
                     data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                  <input type="hidden" id="action-url" value="invoices/update_status">
                  <button type="button" class="btn btn-crud  btn-md btn-primary"
                     id="submit_model"><?php echo $this->lang->line('Change Status'); ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">

$(document).ready(function(){
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
            },
            shortnote: {
                required: true,
            }
        },
        messages: {
            pmethod:  "Please select a payment method",
            amount: "Received amount must be less than or equal the sum of the selected invoices",
            account: {
                required: "Please enter an account number",
                minlength: "Your account number must be at least 4 characters long"
            },
            shortnote:  "Please enter a short description above 5 characters"
        },

        submitHandler: function(form) {
            // Handle form submission
            form.submit(); // You can replace this with your own logic
        }
    });


   var credit_limit = $("#credit_limit").val();
   var totalinvoiceamt = $("#totalamount").val();
   var avalaialble_limit = credit_limit - totalinvoiceamt;
   avalaialble_limit = avalaialble_limit.toFixed(2);
   $("#available-limit").text(avalaialble_limit);
   $("#available-limit-amount").val(avalaialble_limit);
    let selectedValues = [];
    let totalSum = 0;
    $('#invoicecheckbox').on('click', function() {
        $('.checkeditems').prop('checked', this.checked);
        updateSelectedValues();
    });
    $('.checkeditems').on('click', function() {
        if ($('.checkeditems:checked').length === $('.checkeditems').length) {
            $('#invoicecheckbox').prop('checked', true);
        } else {
            $('#invoicecheckbox').prop('checked', false);
        }
        updateSelectedValues();
    });

    function updateSelectedValues() {
      selectedValues = [];
      totalSum = 0;

      $('.checkeditems:checked').each(function() {
         const invoiceId = $(this).val();
         const subtotal = parseFloat($(this).data('id')); // Get the data-id (subtotal)
         
         selectedValues.push(invoiceId);
         totalSum += subtotal; // Sum the subtotals from data-id
      });
      totalSum = totalSum.toFixed(2);
      $("#rmpay").val(totalSum);
      $("#selectedInvoiceamount").val(totalSum);        
   }
});

    $("#rmpay").on('keyup', function(){
      var credit_limit = $("#credit_limit").val();
      var rmpay = $("#rmpay").val();
      var avalaialble_limit = credit_limit - rmpay;
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
      var selectedInvoiceamount = $('#selectedInvoiceamount').val();

      var availablelimitamount = $('#available-limit-amount').val();
      
      var recievedamt = $('#rmpay').val();
      if (selectedInvoiceamount == 0) {
         Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Selected Atleat an invoice from the list',
            confirmButtonText: 'OK'
         });
         return;
      }
      //check recieved amount and invoice amount
      if (parseFloat(selectedInvoiceamount) < parseFloat(recievedamt) || parseFloat(recievedamt)<=0) {       
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
                           url: baseurl + "transactions/pay_multipleinvoices",
                           type: 'POST',
                           data: formData,
                           contentType: false,
                           processData: false,
                           success: function(response) {
                              
                              var data = JSON.parse(response);
                              window.location.href = baseurl + 'invoices';
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
      //   $('#submitpayment1').prop('disabled',false);
    }
});
   
</script>
<script>
   // erp2025 09-01-2025 detailed history start
   const changedFields = {};
$(document).ready(function() {
    // Add event listeners to all input fields
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
                          fieldlabel : field_label
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
                              fieldlabel : field_label
                          };
                      } else {
                          delete changedFields[fieldId]; // Remove if no change
                      }
                  }
              } else if (this.type === 'number') {
                  // For numeric fields
                  const newValue = parseFloat(this.value);
                  const originalNumber = parseFloat(originalValue);

                  if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue) {
                      changedFields[fieldId] = {
                          oldValue: originalNumber,
                          newValue: newValue,
                          fieldlabel : field_label
                      };
                  } else {
                      delete changedFields[fieldId]; // Remove if no change
                  }
              } else {
                  // For text, textarea, and select fields
                  const newValue = this.value;
                  if (originalValue !== newValue) {
                      changedFields[fieldId] = {
                          oldValue: originalValue,
                          newValue: newValue,
                          fieldlabel : field_label
                      };
                  } else {
                      delete changedFields[fieldId]; // Remove if no change
                  }
              }
          });
      });
      // Function to initialize Select2 and track changes
      function initializeSelect2(selector) {
          // Initialize Select2

          // Attach change event listener to track changes
          $(selector).each(function () {
              const element = $(this);
              const fieldId = element.attr('id');
              const originalValue = element.data('original-value'); // Access `data-original-value`
              var label = $('label[for="' + fieldId + '"]');
              var field_label = label.text();
              element.on('change', function () {
                  const newValue = element.val();
                  if (originalValue != newValue) {
                      changedFields[fieldId] = {
                          oldValue: originalValue,
                          newValue: newValue,
                          fieldlabel: field_label,
                      }; // Store the original and new value
                  } else {
                      delete changedFields[fieldId]; // Remove if no change
                  }
              });
          });
      }
      initializeSelect2('.multi-select');
        // erp2025 09-01-2025 detailed history end

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
   });
</script>