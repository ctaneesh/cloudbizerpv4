<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $invoice['invoice_number']; ?></li>
                </ol>
            </nav>
        
         <div class="row">
               <div class="col-xl-3 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <h4 class="card-title"><?php echo  $invoice['invoice_number']; ?> </h4>
               </div>
               <div class="col-xl-7 col-lg-10 col-md-10 col-sm-12 col-xs-12">  
                  <ul id="trackingbar">
                     <?php 
                     if (!empty($trackingdata)) {                    
                           $prefixs = get_prefix_72();
                           $suffix = $prefixs['suffix'];
                           if (!empty($trackingdata['lead_id'])) { 
                              echo '<li><a href="' . base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) . '">' . $trackingdata['lead_number'] . '</a></li>';
                           } 
                           if (!empty($trackingdata['quote_number'])) { 
                                 echo '<li><a href="' . base_url('quote/create?id=' . $trackingdata['quote_number']) . '">' . $trackingdata['quote_number'] . '</a></li>';
                           }
                           if (!empty($trackingdata['salesorder_number'])) { 
                              if($trackingdata['sales_count']>1  && $trackingdata['quote_number'])
                              {
                                 $sales_number = remove_after_last_dash($trackingdata['salesorder_number']);
                                 echo '<li><a href="' . base_url('SalesOrders/salesorder_new?id=' . $trackingdata['quote_number']) . '&token=1">' . $sales_number . '-'.$suffix.'</a></li>';
                              }
                              else{
                                 echo '<li><a href="' . base_url('SalesOrders/salesorder_new?id=' . $trackingdata['salesorder_number']) . '&token=3">' . $trackingdata['salesorder_number'] . '</a></li>';
                              }
                              
                           }
                           if (!empty($trackingdata['deliverynote_number'])) { 
                              if($trackingdata['delivery_count']>1 && $trackingdata['salesorder_number'])
                              {
                                 $deliverynotenumber = remove_after_last_dash($trackingdata['deliverynote_number']);
                                 echo '<li><a href="' . base_url('SalesOrders/delivery_notes?id=' . $trackingdata['salesorder_number']).'">' . $deliverynotenumber . '-'.$suffix.'</a></li>';
                              }
                              else{
                                 echo '<li><a href="' . base_url('DeliveryNotes/create?id=' . $trackingdata['deliverynote_number']).'">' . $trackingdata['deliverynote_number'] . '</a></li>';
                              }
                              
                           }
                           if (!empty($trackingdata['delivery_return_number'])) { 
                              echo '<li><a href="' . base_url('Deliveryreturn/deliveryreturn?delivery=' . $trackingdata['delivery_return_number']).'">' . $trackingdata['delivery_return_number'] . '</a></li>';
                           }
                           if (!empty($trackingdata['invoice_number'])) { 
                              echo '<li class="active">'. $trackingdata['invoice_number'] . '</li>';
                           }
                           if (!empty($trackingdata['invoice_retutn_number'])) { 
                              echo '<li><a href="' . base_url('invoicecreditnotes/create?iid=' . $trackingdata['invoice_retutn_number']).'">' . $trackingdata['invoice_retutn_number'] . '</a></li>';
                           }
                     }
                
                     ?>
                  </ul>  
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
               <input type="hidden" class="form-control" name="invoiceid" id="invoice_id" value="<?php echo $invoice['iid'] ?>">
                  <?php
                     $validtoken = hash_hmac('ripemd160', $invoice['iid'], $this->config->item('encryption_key'));                     
                     $link = base_url('billing/view?id=' . $invoice['iid'] . '&token=' . $validtoken);
                     if ($invoice['paymentstatus'] != 'canceled') { ?>
                  <div class="title-action row"> 
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">                  
                        <!-- <a href="<?php echo 'edit?id=' . $invoice['iid']; ?>" class="btn btn-sm btn-secondary mb-1"><i
                            class="fa fa-pencil"></i> <?php echo $this->lang->line('Edit Invoice') ?></a> -->
                        <?php
                        $disablecls ="";
                        $deletedclass = "";
                        if($invoice['paymentstatus']=='paid' || $invoice['paymentstatus']=='post dated cheque'  || $invoice['paymentstatus']=='Deleted')
                        { 
                           $disablecls = "disable-class";
                        }
                        if($invoice['paymentstatus']=='Deleted'){
                           $deletedclass = "disable-class";
                        }
                         ?>
                        <a href="<?php echo base_url('invoices/customer_payment?id=' . $invoice['id'] . '&csd=' . $invoice['cid']); ?>" class="btn btn-sm btn-secondary mb-1 <?=$disablecls?> <?=$deletedclass?>" title="Make Payment" ><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a>
                        
                        <a href="<?php echo base_url('invoices/invoice_creditnote?id=' . $invoice['id']); ?>" class="btn btn-sm btn-secondary mb-1 <?=$disablecls?> <?=$deletedclass?>" title="Return Items" ><span class="fa fa-undo"></span> <?php echo $this->lang->line('Return Items') ?> </a>
                        
                        <!-- $creditnoteBtn = '<a href="' . base_url("invoices/invoice_creditnote?id=$invoices->id") . '" class="btn btn-sm btn-secondary ' . $disablecls . '"><i class="fa fa-undo"></i> ' . $this->lang->line('Return Items') . '</a>'; -->
            
                        <a href="#part_payment" data-toggle="modal" data-remote="false" data-type="reminder"
                            class="btn btn-sm btn-secondary mb-1 d-none " title="Partial Payment"
                            ><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a>
                        <div class="btn-group ">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle mb-1 <?=$deletedclass?>"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span
                            class="fa fa-envelope-o"></span> Email
                            </button>
                            <div class="dropdown-menu">
                            <a href="#sendEmail" data-toggle="modal"
                                data-remote="false" class="dropdown-item sendbill"
                                data-type="notification"><?php echo $this->lang->line('Invoice Notification') ?></a>
                            <div class="dropdown-divider"></div>
                            <a href="#sendEmail" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendbill"
                                data-type="reminder"><?php echo $this->lang->line('Payment Reminder') ?></a>
                            <a href="#sendEmail" data-toggle="modal" data-remote="false" class="dropdown-item sendbill" data-type="received"><?php echo $this->lang->line('Payment Received') ?></a>
                            <div class="dropdown-divider"></div>
                            <a href="#sendEmail" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendbill" href="#"
                                data-type="overdue"><?php echo $this->lang->line('Payment Overdue') ?></a><a
                                href="#sendEmail" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendbill"
                                data-type="refund"><?php echo $this->lang->line('Refund Generated') ?></a>
                            </div>
                        </div>
                        <!-- SMS -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle mb-1 <?=$deletedclass?>"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span
                            class="fa fa-mobile"></span> SMS
                            </button>
                            <div class="dropdown-menu">
                            <a href="#sendSMS" data-toggle="modal"
                                data-remote="false" class="dropdown-item sendsms"
                                data-type="notification"><?php echo $this->lang->line('Invoice Notification') ?></a>
                            <div class="dropdown-divider"></div>
                            <a href="#sendSMS" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendsms"
                                data-type="reminder"><?php echo $this->lang->line('Payment Reminder') ?></a>
                            <a
                                href="#sendSMS" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendsms"
                                data-type="received"><?php echo $this->lang->line('Payment Received') ?></a>
                            <div class="dropdown-divider"></div>
                            <a href="#sendSMS" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendsms" href="#"
                                data-type="overdue"><?php echo $this->lang->line('Payment Overdue') ?></a><a
                                href="#sendSMS" data-toggle="modal" data-remote="false"
                                class="dropdown-item sendsms"
                                data-type="refund"><?php echo $this->lang->line('Refund Generated') ?></a>
                            </div>
                        </div>
                                               
                        <a href="#pop_model" data-toggle="modal" data-remote="false"
                            class="btn btn-sm btn-secondary mb-1 disable-class <?=$deletedclass?>" title="Change Status"
                            ><span class="fa fa-retweet"></span> <?php echo $this->lang->line('Change Status') ?></a>
                        <!-- <a href="#cancel-bill" class="btn btn-sm btn-secondary mb-1" id="cancel-bill"><i
                            class="fa fa-minus-circle"> </i> <?php echo $this->lang->line('Cancel') ?>
                        </a> -->
                        <button class="btn btn-sm btn-secondary mb-1 cancelinvoice-btn <?=$deletedclass?>"><?php echo $this->lang->line('Cancel') ?></button>
                        <!-- <div class="btn-group ">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle mb-1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="icon-anchor"></i> <?php echo $this->lang->line('Extra') ?>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item"
                                href="<?= base_url() . 'invoices/delivery?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('Delivery Note') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="<?= base_url() . 'invoices/proforma?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('Proforma Invoice') ?></a>
                            </div>
                        </div> -->
                    </div>                      
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-lg-right text-md-right text-sm-left text-xs-left">
                        <a href="<?php echo $link; ?>" class="btn btn-sm btn-secondary mb-1"  target="_blank"><i class="fa fa-globe"></i> <?php echo $this->lang->line('Preview') ?>
                        </a>
                        <div class="btn-group ">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle mb-1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="fa fa-print"></i> <?php echo $this->lang->line('Print') ?>
                            </button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item"
                                href="<?= base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>" target="_blank"><?php echo $this->lang->line('Print') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="<?= base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>&d=1"  target="_blank"><?php echo $this->lang->line('PDF Download') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url() . 'pos_invoices/thermal_pdf?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('PDF Print') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="<?= base_url() . 'invoices/delivery?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('Delivery Note') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="<?= base_url() . 'invoices/proforma?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('Proforma Invoice') ?></a>
                            </div>
                        </div> 
                    </div>
                  </div>
                  <?php if ($invoice['multi'] > 0) {
                     echo '<div class="badge bg-blue text-xs-center mt-2 white">' . $this->lang->line('Payment currency is different') . '</div>';
                     }
                     } else {
                     echo '<h2 class="btn btn-sm btn-oval btn-secondary">' . $this->lang->line('Cancelled') . '</h2>';
                     } ?>
               </div>
            </div>
            <!-- Invoice Company Details -->
            <div id="invoice-company-details" class="row">
               <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                  <p></p>
                  <img src="<?php $loc = location($invoice['loc']);
                     echo base_url('userfiles/company/' . $loc['logo']) ?>"
                     class="img-responsive p-1 m-b-2" style="max-height: 120px;">
                  <p class="ml-2"><?= $loc['cname'] ?></p>
               </div>
               <div class="col-md-6 col-sm-12 text-xs-center text-md-right">
                  <!-- <h2><?php //echo $this->lang->line('INVOICE') ?></h2> -->
                  <p class=""> <?php echo $this->config->item('prefix') . ' ' . $invoice['tid'] . '</p>
                     <p class="">' . $this->lang->line('Reference') . ' : ' . $invoice['refer'] . '</p>'; ?>
                  <ul class="px-0 list-unstyled">
                     <li>
                        <?php echo $this->lang->line('Gross Amount')." : "; ?>
                        <span class="lead text-bold-800"><?php //echo amountExchange($invoice['total'], 0, $this->aauth->get_user()->loc) ?><?php echo number_format($invoice['total'],2); ?></span>
                     </li>
                     <?php if($invoice['payment_recieved_amount']>0)
                     { ?>
                     <li>
                        <?php echo $this->lang->line('Paid Amount'). " : "; ?>
                        <span class="lead text-bold-800"><?php //echo amountExchange($invoice['total'], 0, $this->aauth->get_user()->loc) ?><?php echo '<b>'.number_format($invoice['payment_recieved_amount'],2).'</b>'; ?></span>
                     </li>
                     <?php } ?>
                  </ul>
               </div>
            </div>
            <!--/ Invoice Company Details -->
            <!-- Invoice Customer Details -->
            <div id="invoice-customer-details" class="row">
               <div class="col-sm-12 text-xs-center text-md-left">
                  <p class="text-muted"><?php echo $this->lang->line('Bill To') ?></p>
               </div>
               <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                  <ul class="px-0 list-unstyled">
                     <li class="text-bold-800"><a
                        href="<?php echo base_url('customers/view?id=' . $invoice['cid']) ?>"><strong
                        class="invoice_a"><?php echo $invoice['name'] . '</strong></a></li><li>' . $invoice['company'] . '</li><li>' . $invoice['address'] . '</li><li>' . $invoice['city'] . ',' . $invoice['country'] . '</li><li>' . $this->lang->line('Phone') . ': ' . $invoice['phone'] . '</li><li>' . $this->lang->line('Email') . ': ' . $invoice['email'] . '</li>';
                        foreach ($c_custom_fields
                        
                        as $row) {
                        echo '  <li>' . $row['name'] . ': ' . $row['data'] ?></li>
                     <?php } ?>
                  </ul>
                 <?php  
                 $display_class = ($delnotedetails['customer_reference_number']) ? "" : "d-none";
                 echo '<p  class="'.$display_class.'"><span class="text-muted">' . $this->lang->line('Customer Reference') . ' :</span> ' . $delnotedetails['customer_reference_number'] . '</p>
                 <p class="'.$display_class.'"><span class="text-muted">' . $this->lang->line('Date') . ' :</span> ' . date('d-m-Y',strtotime($delnotedetails['refdate'])) . '</p>
                 <p><span class="text-muted">' . $this->lang->line('Terms') . ' :</span> ' . $invoice['termtit'] . '</p>';
                  ?><br>
               </div>
               <div class="offset-md-3 col-md-3 col-sm-12 text-right">
                  <p class="<?=$display_class?>">
                     <?php echo $this->lang->line('Delivery Note'); ?> : #<b><?=$delnotedetails['delnote_number']?></b>
                  </p>
                  <p>
                     <?php echo $this->lang->line('Payment Status'); ?>:
                     
                        <u><strong id="pstatus"><?php echo $this->lang->line(ucwords($invoice['paymentstatus'])); ?></strong></u>
                     
                  </p>
                  <?php echo '<p><span class="text-muted">' . $this->lang->line('Invoice Date') . '  :</span> ' . dateformat($invoice['invoicedate']) . '</p> <p><span class="text-muted">' . $this->lang->line('Due Date') . ' :</span> ' . dateformat($invoice['invoiceduedate']) . '</p>  ';
                     ?>
               </div>
            </div>
            <!--/ Invoice Customer Details -->
                <!-- ========================= tab starts ==================== -->
                  <ul class="nav nav-tabs mb-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                aria-controls="tab1" href="#tab1" role="tab"
                                aria-selected="true"><?php echo $this->lang->line('Invoice Properties') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab1" data-toggle="tab"
                                aria-controls="tab5" href="#tab5" role="tab"
                                aria-selected="true"><?php echo $this->lang->line('Delivery Notes') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                href="#tab2" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Payment Details') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab3"
                                href="#tab3" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Payments Received') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab4"
                                href="#tab4" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Journals') ?></a>
                        </li>
                           
                  </ul>

                  <div class="tab-content px-1 pt-1">
                    <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                     <!-- -=============== invoice Details ================ -->
                           <div id="invoice-items-details" class="pt-2">
                              <div class="row">
                                 <div class="table-responsive col-sm-12">
                                    <table class="table table-striped table-bordered zero-configuration dataTable">
                                       <thead>
                                          <?php if ($invoice['taxstatus'] == 'cgst'){ ?>
                                          <tr>
                                             <th>#</th>
                                             <th><?php echo $this->lang->line('Item No') ?></th>
                                             <th><?php echo $this->lang->line('Item Name') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('HSN') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('Rate') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('Qty') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('Discount') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('CGST') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('SGST') ?></th>
                                             <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <?php $c = 1;
                                             $sub_t = 0;
                                             
                                             foreach ($products as $row) {
                                                $sub_t += $row['price'] * $row['qty'];
                                                $gst = $row['totaltax'] / 2;
                                                $rate = $row['tax'] / 2;
                                                   if($row['serial']) $row['product_des'].=' - '.$row['serial'];
                                                echo '<tr>
                                                   <th scope="row">' . $c . '</th>
                                                   <td>' . $row['code'] . '</td>     
                                                   <td>' . $row['product'] . '</td>                      
                                                   <td>' . amountExchange($row['price'], 0, $this->aauth->get_user()->loc) . '</td>
                                                   <td>' . amountFormat_general($row['qty']) . $row['unit'] . '</td>
                                                   <td>' . amountExchange($row['totaldiscount'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                                   <td>' . amountExchange($gst, 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($rate) . '%)</td>
                                                   <td>' . amountExchange($gst, 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($rate) . '%)</td>                           
                                                   <td>' . amountExchange($row['subtotal'], 0, $this->aauth->get_user()->loc) . '</td>
                                                </tr>';
                                             
                                                //  echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
                                                $c++;
                                             } ?>
                                       </tbody>
                                       <?php
                                          } elseif ($invoice['taxstatus'] == 'igst') {
                                             ?>
                                       <tr>
                                          <th>#</th>
                                          <th><?php echo $this->lang->line('Item No') ?></th>
                                          <th><?php echo $this->lang->line('Item Name') ?></th>
                                          <th class="text-xs-left"><?php echo $this->lang->line('HSN') ?></th>
                                          <th class="text-xs-left"><?php echo $this->lang->line('Rate') ?></th>
                                          <th class="text-xs-left"><?php echo $this->lang->line('Qty') ?></th>
                                          <th class="text-xs-left"><?php echo $this->lang->line('Discount') ?></th>
                                          <th class="text-xs-left"><?php echo $this->lang->line('IGST') ?></th>
                                          <th class="text-xs-left"><?php echo $this->lang->line('Amount') ?></th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                          <?php $c = 1;
                                             $sub_t = 0;
                                             
                                             foreach ($products as $row) {
                                                $sub_t += $row['price'] * $row['qty'];
                                                if($row['serial']) $row['product_des'].=' - '.$row['serial'];
                                                   echo '<tr>
                                                         <th scope="row">' . $c . '</th>
                                                         <td>' . $row['code'] . '</td>     
                                                         <td>' . $row['product'] . '</td>                      
                                                         <td>' . amountExchange($row['price'], 0, $this->aauth->get_user()->loc) . '</td>
                                                         <td>' . amountFormat_general($row['qty']) . $row['unit'] . '</td>
                                                         <td>' . amountExchange($row['totaldiscount'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['discount']) . $this->lang->line($invoice['format_discount']) . ')</td>
                                                         <td>' . amountExchange($row['totaltax'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['tax']) . '%)</td>
                                                                        
                                                         <td>' . amountExchange($row['subtotal'], 0, $this->aauth->get_user()->loc) . '</td>
                                                   </tr>';
                                             
                                                //  echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
                                                $c++;
                                             } ?>
                                       </tbody>
                                       <?php
                                          } else {
                                             ?>
                                       <tr>
                                          <th>#</th>
                                          <th><?php echo $this->lang->line('Item No') ?></th>
                                          <th><?php echo $this->lang->line('Item Name') ?></th>
                                          <th class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                          <th class="text-center"><?php echo $this->lang->line('Qty') ?></th>
                                          <!-- <th class="text-xs-left"><?php echo $this->lang->line('Tax') ?></th> -->
                                          <th class="text-right"><?php echo $this->lang->line('Discount') ?> (<?php echo $this->config->item('currency') ?>)</th>
                                          <th class="text-right"><?php echo $this->lang->line('Amount') ?> (<?php echo $this->config->item('currency') ?>)</th>
                                       </tr>
                                       </thead>
                                       <tbody>
                                          <?php $c = 1;
                                             $sub_t = 0;
                                             
                                             foreach ($products as $row) {
                                                $sub_t += $row['price'] * $row['qty'];
                                                if($row['serial']) $row['product_des'].=' - '.$row['serial'];
                                                echo '<tr><th scope="row">' . $c . '</th>                     
                                                   <td>' . $row['code'] . '</td>                 
                                                   <td>' . $row['product'] . '</td>                                 
                                                   <td class="text-right">' . $row['price']. '</td>
                                                   <td class="text-center">' . intval($row['qty']) ." ". $row['unit'] . '</td>';
                                                      // echo '<td  class="text-right">' . amountExchange($row['totaltax'], 0, $this->aauth->get_user()->loc) . ' (' . amountFormat_s($row['tax']) . '%)</td>';
                                                      echo '<td  class="text-right">' . $row['totaldiscount'] .'</td>
                                                   <td  class="text-right">' . $row['subtotal'] . '</td>
                                                </tr>';
                                             
                                                //  echo '<tr><td colspan=5>' . $row['product_des'] . '</td></tr>';
                                                $c++;
                                             } ?>
                                       </tbody>
                                       <?php } ?>
                                    </table>
                                 </div>
                              </div>
                              <p></p>
                              <div class="row">
                                 <div class="col-md-9 col-sm-12 text-xs-center text-md-left">
                                    <div class="row">
                                       <div class="col-md-8">
                                          <p class="lead">
                                             <?php echo $this->lang->line('Payment Status'); ?> :
                                             
                                                <u><strong id="pstatus"><?php echo $this->lang->line(ucwords($invoice['paymentstatus'])); ?></strong></u>
                                             
                                          </p>
                                          <?php 
                                          if($invoice['pmethod'])
                                          {
                                             ?>
                                              <p class="lead"><?php echo $this->lang->line('Payment Method') ?> : <u><strong  id="pmethod"><?php echo $this->lang->line($invoice['pmethod']) ?></strong></u>
                                              </p>
                                              <?php
                                          }
                                         ?>
                                          <p class="lead mt-1"><br><?php echo $this->lang->line('Note') ?>:</p>
                                          <?php echo $invoice['notes'] ?>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-12">
                                    <p class="lead"><?php echo $this->lang->line('Summary') ?></p>
                                    <div class="table-responsive">
                                       <table class="table">
                                          <tbody>
                                             <tr>
                                                <td><?php echo $this->lang->line('Sub Total') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-right"> <b><?php echo number_format($sub_t,2); ?></b></td>
                                             </tr>
                                             <!-- <tr>
                                                <td class="no-border"><?php echo $this->lang->line('Tax') ?></td>
                                                <td class="text-xs-right no-border"><b><?php echo number_format($invoice['tax'],2); ?></b></td>
                                             </tr> -->
                                             <tr>
                                                <td class="no-border"><?php echo $this->lang->line('Total Product Discount') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-right no-border"><b><?php echo number_format($invoice['discount'],2); ?></b></td>
                                             </tr>
                                             <tr>
                                                <td class="no-border"><?php echo $this->lang->line('Order Discount') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-right no-border"><b><?php echo number_format($invoice['order_discount'],2); ?></b></td>
                                             </tr>
                                             <tr>
                                                <td class="no-border"><?php echo $this->lang->line('Shipping') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-right no-border"><b><?php echo number_format($invoice['shipping'],2); ?></b></td>
                                             </tr>
                                             <tr>
                                                <td class="text-bold-800 no-border"><?php echo $this->lang->line('Total') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-bold-800 text-right no-border"><b><?php echo number_format($invoice['total'],2); ?></b></td>
                                             </tr>
                                             <!-- <tr>
                                                <td class="no-border"><?php echo $this->lang->line('Payment Made') ?></td>
                                                <td class="pink text-xs-right no-border"><b>
                                                   (-) <?php echo ' <span id="paymade">' . amountExchange($invoice['pamnt'], 0, $this->aauth->get_user()->loc) ?></b></span>
                                                </td>
                                             </tr> -->
                                             <tr class="bg-grey bg-lighten-4">
                                                <td class="text-bold-800"><?php echo $this->lang->line('Total Paid Amount') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-bold-800 text-right"> <b><?php $myp = '';
                                                   $paidamts = $invoice['pamnt'];
                                                   echo ' <span id="paydue1">' . number_format($paidamts,2) . '</span></strong>'; ?></b></td>
                                             </tr>
                                             <tr class="bg-grey bg-lighten-4">
                                                <td class="text-bold-800"><?php echo $this->lang->line('Balance Amount To Pay') ?> (<?php echo $this->config->item('currency') ?>)</td>
                                                <td class="text-bold-800 text-right"> <b><?php $myp = '';
                                                   $rming = $invoice['total'] - $invoice['pamnt'];
                                                   if ($rming < 0) {
                                                      $rming = 0;
                                                   
                                                   }
                                                   echo ' <span id="paydue">' . number_format($rming,2) . '</span></strong>'; ?></b></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <div class="text-right d-none">
                                       <p><?php echo $this->lang->line('Authorized person') ?></p>
                                       <?php 
                                       if(!empty($employee['sign'])){
                                          echo '<img src="' . base_url('userfiles/employee_sign/' . $employee['sign']) . '" alt="signature" class="height-100"/>
                                          
                                          <h6>(' . $employee['name'] . ')</h6>
                                          <p class="text-muted">' . user_role($employee['roleid']) . '</p>'; } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>                           
        
                              <!-- Invoice Footer -->
                              <?php if(is_array($custom_fields)) {
                                 echo '<div class="card">';
                                 foreach ($custom_fields as $row) {
                                    ?>
                              <hr>
                              <div class="row m-t-lg">
                                 <div class="col-md-2">
                                    <strong><?php echo $row['name'] ?></strong>
                                 </div>
                                 <div class="col-md-10">
                                    <?php echo $row['data'] ?>
                                 </div>
                              </div>
                              <?php
                                 }   echo '</div>';
                                 }
                                                   ?>
                              <!-- <div id="invoice-footer">
                                 <p class="lead"><?php echo $this->lang->line('Credit Transactions') ?>:</p>
                                 <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                       <tr>
                                          <th><?php echo $this->lang->line('Date') ?></th>
                                          <th class="text-center"><?php echo $this->lang->line('Method') ?></th>
                                          <th class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                                          <th class="text-right"><?php echo $this->lang->line('Credit') ?></th>
                                          <th><?php echo $this->lang->line('Note') ?></th>
                                       </tr>
                                    </thead>
                                    <tbody id="activity">
                                       <?php foreach ($activity as $row) {
                                          echo '<tr>
                                          <td><a href="view_payslip?id=' . $row['id'] . '&inv=' . $invoice['iid'] . '" class="btn btn-blue btn-sm"><span class="icon-print" aria-hidden="true"></span> ' . $this->lang->line('Print') . '  </a> ' . $row['date'] . '</td>
                                          <td class="text-center">' . $this->lang->line($row['method']) . '</td>
                                          
                                          <td class="text-right">' . $row['debit'] . '</td>
                                             <td class="text-right">' . $row['credit'] . '</td>
                                          <td>' . $row['note'] . '</td>
                                          </tr>';
                                          } ?>
                                    </tbody>
                                 </table>
                                 <div class="row">
                                    <div class="col-md-7 col-sm-12 d-none">
                                       <h6><?php echo $this->lang->line('Terms & Condition') ?></h6>
                                       <p> <?php
                                          echo '<strong>' . $invoice['termtit'] . '</strong><br>' . $invoice['terms'];
                                          ?></p>
                                    </div>
                                 </div>
                              </div> -->
                              <!--/ Invoice Footer -->
                              <!-- <hr>
                              <pre><?php //echo $this->lang->line('Public Access URL') ?>: <?php
                                // echo $link ?></pre> -->
                              <!-- <div class="col-12 row d-none">
                                 <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                       <tr>
                                          <th><?php echo $this->lang->line('Files') ?></th>
                                       </tr>
                                    </thead>
                                    <tbody id="activity">
                                       <?php foreach ($attach as $row) {
                                          echo '<tr><td><a data-url="' . base_url() . 'invoices/file_handling?op=delete&name=' . $row['col1'] . '&invoice=' . $invoice['iid'] . '" class="aj_delete"><i class="btn-danger btn-lg fa fa-trash"></i></a> <a class="n_item" href="' . base_url() . 'userfiles/attach/' . $row['col1'] . '"> ' . $row['col1'] . ' </a></td></tr>';
                                          } ?>
                                    </tbody>
                                 </table>
                              </div>
                              <div class="card1 d-none">
                                 <pre>Allowed: gif, jpeg, png, docx, docs, txt, pdf, xls </pre>
                                 <br>
                                
                                 <div class="btn  btn-outline-light fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>Select files...</span>
                                    <input id="fileupload" type="file" name="files[]" multiple>
                                 </div>
                              </div> -->
                              <!-- The global progress bar -->
                              <div id="progress" class="progress progress-sm mt-1 mb-0 d-none">
                                 <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <!-- The container for the uploaded files -->
                              <!-- <table id="files" class="files table table-striped table-bordered zero-configuration dataTable"></table> -->
                              <br>

                     <!-- -=============== invoice Details ================ -->

                    </div>


                    <div class="tab-pane" id="tab2" role="tabpanel" aria-labelledby="base-tab2">
                        <div class="row">
                           <div class="col-lg-5 col-md-6 col-sm-12">
                              <div class="row">
                                 
                                 <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Payment Status') ?></div>
                                 <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo ucwords($invoice['paymentstatus']); ?></strong></div>
                                 <?php
                                    if($invoice['pmethod']=='Cheque')
                                    {
                                       ?>
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Payment Method') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $invoice['pmethod']; ?></strong></div>
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Cheque Number') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['cheque_number']; ?></strong></div>

                                          
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Pay From') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo ucwords($paymentmethod_details['cheque_pay_from']); ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Cheque Date') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo date('d-m-Y', strtotime($paymentmethod_details['cheque_date'])); ?></strong>
                                             <input type="hidden" name="chequedate" id="chequedate" value="<?php echo $paymentmethod_details['cheque_date']; ?>">
                                          </div>


                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Cheque Account') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['cheque_account_number']; ?></strong></div>
                                       <?php
                                       
                                    }
                                    else if($invoice['pmethod']=='Cash')
                                    {
                                       ?>
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Payment Method') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $invoice['pmethod']; ?></strong></div>
                                          
                                       <?php
                                       
                                    }
                                    else if($invoice['pmethod']=='Bank')
                                    {
                                       ?>
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Payment Method') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $invoice['pmethod']; ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Account Number') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['account_number']; ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('IFSC Code') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['account_ifsc_code']; ?></strong></div>

                                          
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Account Holder') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo ucwords($paymentmethod_details['account_holder_name']); ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Bank Name') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo date('d-m-Y', strtotime($paymentmethod_details['account_bank_name'])); ?></strong></div>


                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Bank Address') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['account_bank_address']; ?></strong></div>
                                       <?php
                                       
                                    }
                                    else if($invoice['pmethod']=='Card')
                                    {
                                       ?>
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Payment Method') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $invoice['pmethod']; ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Card Holder') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['card_holder']; ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Card Number') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo $paymentmethod_details['card_number']; ?></strong></div>

                                          
                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('CVC') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo ucwords($paymentmethod_details['cvc']); ?></strong></div>

                                          <div class="col-lg-3 col-md-5 col-sm-12 mb-1"><?php echo $this->lang->line('Card Expiry Date') ?></div>
                                          <div class="col-lg-9 col-md-7 col-sm-12 mb-1">: <strong><?php echo date('d-m-Y', strtotime($paymentmethod_details['card_expiry_date'])); ?></strong></div>
                                       <?php
                                       
                                    }
                                       
                                 ?>
                              </div>
                           </div>
                           <div class="col-7">
                           <?php
                           
                              if($invoice['paymentstatus']=='post dated cheque')
                              {
                                 ?>
                                 <form method="post" name="chequeapproval_frm" id="chequeapproval_frm">
                                    <div class="row">
                                       <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                          <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Bank Desposit Date') ?></label>
                                             <input type="date" class="form-control" placeholder="Invoice #"
                                                name="bankdepositdate" id="bankdepositdate" >
                                       </div>
                                       <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                          <input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice['iid']; ?>">
                                          <input type="hidden" name="totalamount" id="totalamount" value="<?php echo $invoice['total']; ?>">
                                          <input type="hidden" name="trans_ai_id" id="trans_ai_id" value="<?php echo $paymentmethod_details['id']; ?>">
                                          <input type="hidden" name="trans_ai_accid" id="trans_ai_accid" value="<?php echo $paymentmethod_details['transfered_account_id']; ?>">
                                          <input type="hidden" name="transfered_account_name" id="transfered_account_name" value="<?php echo $paymentmethod_details['transfered_account_name']; ?>">
                                          <input type="hidden" name="cheque_date" id="cheque_date" value="<?php echo $paymentmethod_details['cheque_date']; ?>">
                                          <button type="submit" class="btn btn--md btn-primary mt-35" id="acceptchquepayment" >Confirm Deposit</button>
                                       </div>
                                    </div>

                                 </form>
                                 <?php
                              }
                              ?>
                           </div>
                        </div>
                    </div>

                    <!-- =================================================================== -->
                        <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                           <div class="row">
                              <div class="col-12">
                                    <!-- ===================================================== -->
                                    <div class="table-container">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th style="width:3%;">#</th>
                                                   <th><?php echo $this->lang->line('Date') ?></th>
                                                   <th><?php echo $this->lang->line('Relation') ?></th>
                                                   <th  class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                                   <th><?php echo $this->lang->line('Payment Method') ?></th>
                                                   <th><?php echo $this->lang->line('Customer') ?></th>
                                                   <th><?php echo $this->lang->line('Bank Account') ?></th>
                                                   <th><?php echo $this->lang->line('Chart of Account') ?></th>
                                                   <th><?php echo $this->lang->line('Status') ?></th>
                                                   <th><?php echo $this->lang->line('Action') ?></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php 
                                                $i = 1;
                                                if (!empty($payment_records)) {
                                                   foreach ($payment_records as $row) {
                                                      $created_dt = (!empty($row['created_dt'])) ? date('d-m-Y H:i:s', strtotime($row['created_dt'])) : "";
                                                      $relation = $row['trans_ref_number'];
                                                      echo "<tr>";
                                                      echo "<td>$i</td>
                                                            <td>$created_dt</td>
                                                            <td><a href='" . base_url('transactions/banking_transaction?ref=' . $relation) . "'>$relation</a></td>
                                                            <td class='text-right'>" . number_format($row['trans_amount'], 2) . "</td>
                                                            <td>" . htmlspecialchars($row['trans_payment_method']) . "</td>
                                                            <td>" . htmlspecialchars($row['customer']) . "</td>
                                                            <td><a href='" . base_url('transactions/account_transactions?code=' . $row['trans_account_id']) . "'>". htmlspecialchars($row['trans_account_id']) . " - " . htmlspecialchars($row['trans_holder']) . "</a></td>
                                                            <td><a href='" . base_url('transactions/account_transactions?code=' . $row['trans_chart_of_account_id']) . "'>". htmlspecialchars($row['trans_chart_of_account_id']) . " - " . htmlspecialchars($row['chart_holder']) . "</a></td>
                                                            <td>" . htmlspecialchars($row['status']) . "</td>
                                                            <td>
                                                                <button class='btn btn-sm btn-secondary' title='Send Receipt'>
                                                                    <span class='fa fa-paper-plane'></span>
                                                                </button>&nbsp;<a class='btn btn-sm btn-secondary' title='Edit' href='" . base_url('transactions/banking_transaction?ref=' . $relation) . "'>
                                                                    <span class='fa fa-pencil'></span>
                                                                </a>&nbsp;
                                                                <button class='btn btn-sm btn-secondary' title='Delete'>
                                                                    <span class='fa fa-trash'></span>
                                                                </button>
                                                            </td>";
                                                      echo "</tr>";
                                                      $i++;
                                                   }
                                                  
                                                }
                                                ?>
                                             </tbody>
                                       </table>

                                    </div>
                                    <!-- ===================================================== -->
                              </div>
                           </div>
                        </div>
                    <!-- =================================================================== -->
                       <!-- ====================Journals Start==================== -->
                        <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="base-tab4">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p><?php echo $this->lang->line('Journals are') ?></p>
                                    <!-- ===================================================== -->
                                    <div class="table-container">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th style="width:3%;">#</th>
                                                   <th><?php echo $this->lang->line('Date') ?></th>
                                                   <th><?php echo $this->lang->line('Transaction Number') ?></th>
                                                   <th><?php echo $this->lang->line('Account') ?></th>
                                                   <th  class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                                                   <th class="text-right"><?php echo $this->lang->line('Credit') ?></th>
                                                   <th><?php echo $this->lang->line('Created By') ?></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php 
                                                $i = 1;
                                                if (!empty($journals_records)) {
                                                   foreach ($journals_records as $row) {
                                                         $created_dt = ($row['date']) ? date('d M Y', strtotime($row['date'])) : "";
                                                         $relation = $row['trans_ref_number'];
                                                         echo "<tr>";
                                                         echo "<td>$i</td>
                                                            <td>$created_dt</td>
                                                            <td>".$row['transaction_number']."</td>
                                                            <td><a href='" . base_url('transactions/account_transactions?code=' . $row['acn']) . "'>".$row['acn']." - ".$row['holder']. "</a></td>
                                                            <td class='text-right'>" . number_format($row['debit'], 2) . "</td>
                                                            <td class='text-right'>" . number_format($row['credit'], 2) . "</td>
                                                            <td>" . $row['employee'] . "</td>";
                                                         echo "</tr>";
                                                         $i++;
                                                   }
                                                }
                                                ?>
                                             </tbody>
                                       </table>

                                    </div>
                                    <!-- ===================================================== -->
                              </div>
                           </div>
                        </div>
                       <!-- ====================Journals Ends====================== -->

                        <!-- ===================Delivery notes starts=================== -->
                        <div class="tab-pane" id="tab5" role="tabpanel" aria-labelledby="base-tab5">
                           <div class="row">
                              <div class="col-12">
                                    <!-- ===================================================== -->
                                    <div class="table-container">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th style="width:3%;">#</th>
                                                   <th><?php echo $this->lang->line('Delivery Note') ?></th>
                                                   <th><?php echo $this->lang->line('Date') ?></th>
                                                   <th><?php echo $this->lang->line('Transaction Number') ?></th>
                                                   <th  class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                                   <th><?php echo $this->lang->line('Status') ?></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <?php 
                                                $i = 1;
                                                if (!empty($merged_deliverynote)) {
                                                   foreach ($merged_deliverynote as $row) {
                                                      $created_date = (!empty($row['created_date'])) ? date('d-m-Y', strtotime($row['created_date'])) : "";
                                                      $created_time = (!empty($row['created_time'])) ? date('H:i:s', strtotime($row['created_time'])) : "";
                                                      $relation = $row['trans_ref_number'];
                                                      echo "<tr>";
                                                      echo "<td>$i</td>
                                                            <td><a href='" . base_url('DeliveryNotes/create?id=' . $row['delevery_note_id']) . "'>".$row['delivery_note_number']."</a></td>
                                                            <td>".$created_time." ".$created_date."</td>
                                                            
                                                            <td>" . $row['transaction_number'] . "</td>
                                                            <td class='text-right'>" . number_format($row['total_amount'], 2) . "</td>
                                                           
                                                            <td>" . htmlspecialchars($row['payment_status']) . "</td>
                                                            ";
                                                      echo "</tr>";
                                                      $i++;
                                                   }
                                                  
                                                }
                                                ?>
                                             </tbody>
                                       </table>

                                    </div>
                                    <!-- ===================================================== -->
                              </div>
                           </div>
                        </div>
                        <!-- ===================Delivery notes ends===================== -->
                  </div>
            <!-- ========================= tab starts ==================== -->

            <!-- Invoice Items Details -->
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
<div id="part_payment" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Payment Confirmation') ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form class="payment">
               <div class="row">
                  <div class="col">
                     <fieldset class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" placeholder="Total Amount" name="amount"
                           id="rmpay"
                           value="<?= amountExchange_s($rming, 0, $this->aauth->get_user()->loc) ?>">
                        <div class="form-control-position">
                           <?php echo $this->config->item('currency') ?>
                        </div>
                     </fieldset>
                  </div>
                  <div class="col">
                     <fieldset class="form-group position-relative has-icon-left">
                        <input type="date" class="form-control required" placeholder="Billing Date" name="paydate" data-toggle="datepicker">
                        <!--          <div class="form-control-position">-->
                        <!--<span class="fa fa-calendar"-->
                        <!--      aria-hidden="true"></span>-->
                        <!--          </div>-->
                     </fieldset>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <label for="pmethod"><?php echo $this->lang->line('Payment Method') ?></label>
                     <select name="pmethod" class="form-control mb-1">
                        <option value="Cash"><?php echo $this->lang->line('Cash') ?></option>
                        <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                        <option value="Balance"><?php echo $this->lang->line('Client Balance') ?></option>
                        <option value="Bank"><?php echo $this->lang->line('Bank') ?></option>
                     </select>
                     <label for="account"><?php echo $this->lang->line('Account') ?></label>
                     <select name="account" class="form-control">
                     <?php foreach ($acclist as $row) {
                        echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                        }
                        ?>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="col"><label
                     class="col-form-label" for="shortnote" ><?php echo $this->lang->line('Note') ?></label>
                     <input type="text" class="form-control"
                        name="shortnote" placeholder="Short note"
                        value="Payment for invoice #<?php echo $invoice['tid'] ?>">
                  </div>
               </div>
               <div class="modal-footer">
                  <input type="hidden" class="form-control required"
                     name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <button type="button" class="btn btn-md btn-default"
                     data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                  <input type="hidden" name="cid" value="<?php echo $invoice['cid'] ?>"><input type="hidden"
                     name="cname"
                     value="<?php echo $invoice['name'] ?>">
                  <button type="button" class="btn btn--md btn-primary" id="submitpayment"><?php echo $this->lang->line('Make Payment'); ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
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
<div id="sendEmail" class="modal fade">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Send Invoice Notification'); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div id="request">
            <div id="ballsWaveG">
               <div id="ballsWaveG_1" class="ballsWaveG"></div>
               <div id="ballsWaveG_2" class="ballsWaveG"></div>
               <div id="ballsWaveG_3" class="ballsWaveG"></div>
               <div id="ballsWaveG_4" class="ballsWaveG"></div>
               <div id="ballsWaveG_5" class="ballsWaveG"></div>
               <div id="ballsWaveG_6" class="ballsWaveG"></div>
               <div id="ballsWaveG_7" class="ballsWaveG"></div>
               <div id="ballsWaveG_8" class="ballsWaveG"></div>
            </div>
         </div>
         <div class="modal-body" id="emailbody" style="display: none;">
            <form id="sendbill">
               <div class="row">
                  <div class="col">
                     <label for="shortnote"  class="col-form-label"><?php echo $this->lang->line('Email') ?></label>
                     <div class="input-group">
                        <div class="input-group-addon"><span class="icon-envelope-o"
                           aria-hidden="true"></span></div>
                        <input type="text" class="form-control" placeholder="Email" name="mailtoc"
                           value="<?php echo $invoice['email'] ?>">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col"><label
                     class="col-form-label" for="shortnote"><?php echo $this->lang->line('Customer Name'); ?></label>
                     <input type="text" class="form-control"
                        name="customername" value="<?php echo $invoice['name'] ?>">
                  </div>
               </div>
               <div class="row">
                  <div class="col"><label
                     class="col-form-label" for="shortnote"><?php echo $this->lang->line('Subject'); ?></label>
                     <input type="text" class="form-control"
                        name="subject" id="subject">
                  </div>
               </div>
               <div class="row">
                  <div class="col"><label
                     class="col-form-label" for="shortnote"><?php echo $this->lang->line('Message'); ?></label>
                     <textarea name="text" class="summernote" id="contents" title="Contents"></textarea>
                  </div>
               </div>
               <input type="hidden" class="form-control"
                  id="invoiceid" name="tid" value="<?php echo $invoice['iid'] ?>">
               <input type="hidden" class="form-control"
                  id="emailtype" value=""><input type="hidden" class="form-control"
                  name="attach" value="true">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-md btn-default"
               data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            <button type="button" class="btn btn-md btn-primary"
               id="sendM"><?php echo $this->lang->line('Send'); ?></button>
         </div>
      </div>
   </div>
</div>
<!--sms-->
<!-- Modal HTML -->
<div id="sendSMS" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Send'); ?> SMS</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div id="request_sms">
            <div id="ballsWaveG1">
               <div id="ballsWaveG_1" class="ballsWaveG"></div>
               <div id="ballsWaveG_2" class="ballsWaveG"></div>
               <div id="ballsWaveG_3" class="ballsWaveG"></div>
               <div id="ballsWaveG_4" class="ballsWaveG"></div>
               <div id="ballsWaveG_5" class="ballsWaveG"></div>
               <div id="ballsWaveG_6" class="ballsWaveG"></div>
               <div id="ballsWaveG_7" class="ballsWaveG"></div>
               <div id="ballsWaveG_8" class="ballsWaveG"></div>
            </div>
         </div>
         <div class="modal-body" id="smsbody" style="display: none;">
            <form id="sendsms">
               <div class="row">
                  <div class="col">
                     <div class="input-group">
                        <div class="input-group-addon"><span class="icon-envelope-o"
                           aria-hidden="true"></span></div>
                        <input type="text" class="form-control" placeholder="SMS" name="mobile"
                           value="<?php echo $invoice['phone'] ?>">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col"><label
                     class="col-form-label" for="shortnote" ><?php echo $this->lang->line('Customer Name'); ?></label>
                     <input type="text" class="form-control"
                        value="<?php echo $invoice['name'] ?>">
                  </div>
               </div>
               <div class="row">
                  <div class="col"><label
                     class="col-form-label" for="shortnote" ><?php echo $this->lang->line('Message'); ?></label>
                     <textarea class="form-control summernote" name="text_message" id="sms_tem" title="Contents" rows="3"></textarea>
                  </div>
               </div>
               <input type="hidden" class="form-control"
                  id="smstype" value="">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-md btn-default"
               data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            <button type="button" class="btn btn-md btn-primary"
               id="submitSMS"><?php echo $this->lang->line('Send'); ?></button>
         </div>
      </div>
   </div>
</div>
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
                     <label
                        for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
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
<!-- =================================History section=========================== -->
<!-- <button class="history-expand-button">
    <span>History</span>
</button>

<div class="history-container">
    <button class="history-close-button">
        <span>Close</span>
    </button>
    <h2>History</h2>
    <table id="logtable" class="table table-striped table-bordered zero-configuration dataTable">
        <thead>
            <tr>
                <th><?php ////echo "#" ?></th>
                <th><?php //echo $this->lang->line('Action_performed') ?></th>
                <th><?php //echo $this->lang->line('IP address')?></th>
                <th><?php// echo $this->lang->line('Performed By') ?></th>
                <th><?php //echo $this->lang->line('Performed At')?></th>
            </tr>
        </thead>
        <tbody>
            <?php// $i = 1;
           /// foreach ($log as $row) { ?>
               <tr>
                  <td><?php// echo $i?></td>
                  <td><?php// echo $row['action_performed']?></td>
                  <td><?php// echo $row['ip_address']?></td>
                  <td><?php// echo $row['name']?></td>
                  <td><?php //echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
               </tr>

               <?php 
              // $i++; 
         //   } ?>
        </tbody>
    </table>

    </form>
</div> -->
  <!--     erp2025 add 06-01-2025   Detailed hisory starts-->
      <button class="history-expand-button">
         <span>History</span>
      </button>

      <div class="history-container">
      <button class="history-close-button">
      <span>Close</span>
         </button>
         <h2>History</h2>
         <button class="logclose-btn">
      <span>X</span>
         </button>
         <form>
         <table id="log" class="table table-striped table-bordered zero-configuration dataTable">
            <thead>
                  <tr>
                     <th><?php echo $this->lang->line('Action Performed') ?></th>
                     <!-- <th><?php// echo $this->lang->line('Action Performed') ?></th> -->
                     <th><?php echo $this->lang->line('Performed At')?></th>
                     <!-- <th><?php //echo $this->lang->line('Performed By') ?></th> -->
                     <th><?php echo $this->lang->line('IP Address')?></th>
                              
                  </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($groupedInvoice as $seqence_number => $invoices){
            $flag=0;
            ?>              
                  <tr>
                     <td>        
                     <?php    foreach ($invoices as $invoice) {
                     if($flag==0)
                     {?>
                     <div class="userdata">
                     <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$invoice['picture'])?>' style="width:50px; height:50px;" ?>
                     <?php  echo $invoice['name'];
                              $flag=1;
                     } ?>
                     </div>           
                        <ul><li>  <?php echo $invoice['old_value'];?> > <b><span class="newdata"><?php echo $invoice['new_value']?></span></b> (<?php if($invoice['field_label']==""){echo $invoice['field_name'];}else{echo $invoice['field_label'];}?>)
                        </li></ul>
                        <?php } ?>
                     </td>               
                     <td><?php echo date('d-m-Y H:i:s', strtotime($invoice['changed_date'])); ?></td>
                     <td><?php echo $invoice['ip_address']?></td> 
                     
                  </tr>  
                  <?php 
                  $i++; 
               
            }?>
            </tbody>
         </table>

         </form>
      </div>   
    <!--     erp2025 add 06-01-2025   Detailed hisory ends-->
<!-- =========================History End=================== -->
<script type="text/javascript">
   $(function () {
       $('.summernote').summernote({
           height: 150,
           toolbar: [
               // [groupName, [list of button]]
               ['style', ['bold', 'italic', 'underline', 'clear']],
               ['font', ['strikethrough', 'superscript', 'subscript']],
               ['fontsize', ['fontsize']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['height', ['height']],
               ['fullscreen', ['fullscreen']],
               ['codeview', ['codeview']]
           ]
       });
   
       $('#sendM').on('click', function (e) {
           e.preventDefault();
   
           sendBill($('.summernote').summernote('code'));
   
       });
   
   });
   

   
$( document ).ready(function() {
   $(".history-expand-button").on("click", function() {
        $(".history-container").toggleClass("active");
    });
    $(".history-close-button").on("click", function () {
      $(".history-container").removeClass("active");
    });
    $(".logclose-btn").on("click", function () {
      $(".history-container").removeClass("active");
    });   
    $('#log').DataTable({
            paging: true,      // Enable pagination
            searching: true,   // Enable search bar
            ordering: true,    // Enable column sorting
            info: true,        // Show table information
            lengthChange: true, // Enable changing number of rows displayed
            order: [[1, 'desc']],
        });
     // Custom method to check if the bank deposit date is not greater than the current date
      $.validator.addMethod("notGreaterThanCurrentDate", function(value, element) {
         var currentDate = new Date();
         // Check if the date is valid and not greater than the current date
         return this.optional(element) || new Date(value) <= currentDate;
      }, "Deposit date cannot be greater than the current date.");

      // Existing method to check that bankdepositdate is greater than or equal to chequedate
      $.validator.addMethod("depositGreaterThanCheque", function(value, element) {
         var bankdepositdate = $("#bankdepositdate").val();
         var chequedate = $("#chequedate").val();

         // Compare the dates
         return (new Date(bankdepositdate) >= new Date(chequedate));
      }, "Deposit date must be greater than or equal to Cheque date.");

      // Validation setup for the form
      $("#chequeapproval_frm").validate({
         ignore: [], // Important: Do not ignore hidden fields
         rules: {
            bankdepositdate: {
                  required: true,
                  notGreaterThanCurrentDate: true, // New rule to check if the date is not greater than current date
                  depositGreaterThanCheque: true // Existing rule
            },
            chequedate: { required: true }
         },
         messages: {
            bankdepositdate: {
                  required: "Enter a valid Deposit date",
                  notGreaterThanCurrentDate: "Deposit date cannot be greater than the current date.", // New message for the new rule
                  depositGreaterThanCheque: "Deposit date must be greater than or equal to Cheque date."
            },
            chequedate: {
                  required: "Enter a valid Cheque date"
            }
         },
         errorElement: "em",
         errorPlacement: function(error, element) {
            error.addClass("help-block");
            if (element.prop("type") === "checkbox") {
                  error.insertAfter(element.parent("label"));
            } else {
                  error.insertAfter(element);
            }
         },
         highlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
         },
         unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
         }
      });



      $("#acceptchquepayment").on('click',function(e){
         e.preventDefault();
      
         if ($("#chequeapproval_frm").valid()) {   
            var deposit_date = $("#bankdepositdate").val();
            var cheque_date = $("#cheque_date").val();
            var current_date = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
            // $('#acceptchquepayment').prop('disabled',true);
            // Validation 1: deposit_date must be less than current date
            
            // If both validations pass, show confirmation SweetAlert
            Swal.fire({
                  title: "Are you sure?",
                  text: "Do you want to proceed?",
                  icon: "question",
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, proceed!',
                  cancelButtonText: "No - Cancel",
                  reverseButtons: true,
                  focusCancel: true
            }).then((result) => {
                  if (result.isConfirmed) {
                     $.ajax({
                           type: 'POST',
                           url: baseurl +'transactions/confirm_deposit',
                           data: {
                              'invoice_id'      : $("#invoice_id").val(),
                              'trans_ai_id'     : $("#trans_ai_id").val(),
                              'bankdepositdate' : $("#bankdepositdate").val()
                           },
                           dataType: 'json',
                           success: function(response) {
                               $('#acceptchquepayment').prop('disabled',false);
                               location.reload();
                              
                           },
                           error: function(xhr, status, error) {
                              console.error(xhr.responseText);
                           }
                     });
                  }
                  else{
                     $$('#acceptchquepayment').prop('disabled',false);
                  }
            });
         }
      });


});
// $('.cancelinvoice-btn').on('click', function(e) {
//     e.preventDefault(); // Prevent the default form submission
//     $('.cancelinvoice-btn').prop('disabled', true);

//     Swal.fire({
//         title: "Are you sure?",
//         text: "Do you want to cancel the invoice?",
//         icon: "question",
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, proceed!',
//         cancelButtonText: "No - Cancel",
//         reverseButtons: true,  
//         focusCancel: true,      
//         allowOutsideClick: false  // Disable outside click
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: baseurl + 'invoices/cancelinvoiceaction', // Replace with your server endpoint
//                 type: 'POST',
//                 data: {
//                     'invoiceid': $("#invoice_id").val()
//                 },
//                 success: function(response) {
//                     if (typeof response === "string") {
//                         response = JSON.parse(response);
//                     }

//                     if (response.status === 'Success') {
//                             window.location.href = baseurl + 'invoices';
//                     } else {
//                         Swal.fire('Error', 'Failed to cancel the invoice', 'error');
//                         $('.cancelinvoice-btn').prop('disabled', false);
//                     }
//                 },
//                 error: function(xhr, status, error) {
//                     Swal.fire('Error', 'An error occurred while cancelling the invoice', 'error');
//                     console.log(error); // Log any errors
//                     $('.cancelinvoice-btn').prop('disabled', false);
//                 }
//             });
//         } else {
//             // Re-enable the button if the user cancels
//             $('.cancelinvoice-btn').prop('disabled', false);
//         }
//     });
   
// });

$('.cancelinvoice-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.cancelinvoice-btn').prop('disabled', true);

    Swal.fire({
        title: "Are you sure?",
        html: `
            <p>Do you want to cancel the invoice?</p>
            <textarea id="cancel_reason" class="swal2-textarea" placeholder="Enter reason for cancellation" rows="4"></textarea>
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,  
        focusCancel: true,      
        allowOutsideClick: false,  // Disable outside click
        preConfirm: () => {
            const reason = document.getElementById('cancel_reason').value.trim();
            if (!reason) {
                Swal.showValidationMessage('Reason for cancellation is required');
                return false;
            }
            return reason; // Pass the reason to the `.then` block
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const cancelReason = result.value; // Get the reason entered by the user
            $.ajax({
                url: baseurl + 'invoices/cancelinvoiceaction', // Replace with your server endpoint
                type: 'POST',
                data: {
                    'invoiceid': $("#invoice_id").val(),
                    'cancel_reason': cancelReason
                },
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'Success') {
                        window.location.href = baseurl + 'invoices';
                    } else {
                        Swal.fire('Error', 'Failed to cancel the invoice', 'error');
                        $('.cancelinvoice-btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while cancelling the invoice', 'error');
                    console.log(error); // Log any errors
                    $('.cancelinvoice-btn').prop('disabled', false);
                }
            });
        } else {
            // Re-enable the button if the user cancels
            $('.cancelinvoice-btn').prop('disabled', false);
        }
    });
});


</script>