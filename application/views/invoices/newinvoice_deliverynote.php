<div class="content-body">
   <?php       
   if (($msg = check_permission($permissions)) !== true) {
      echo $msg;
      return;
   }
   ?>
   <div class="card">
  
      <div class="card-header border-bottom">
            <?php 
               $status = ($master['invoicestatus']) ? $master['invoicestatus'] : 'due';
               $disable_class="";
               $dnone_class="";
               $invoice_number = (!empty($master['invoice_number']) && $master['invoice_number'] && empty($convert_type) && ($invoice_action_type)) ? $master['invoice_number'] : $this->lang->line('Add New');
               $function_number =  (!empty($master['invoice_number'])) ? $invoice_number :"";
               // $invoiceid = $lastinvoice + 1;
               // Invoice(Delivery Note)
               $invoice_label = $this->lang->line('Invoice(Delivery Note)');
              
               if($create_type)
               {
                  $invoice_label = $this->lang->line('New Invoice');
               }
               
               $topbuttons_class = (($invoiced_id && $master['paymentstatus'] != 'Draft') ? "" : "d-none");
               $addnew_btns = "";
               $update_btns = "d-none";
               $addrow_class_update = "";
               if($master && empty($convert_type) && $master['paymentstatus'] != 'Draft')
               {
                  $addnew_btns = "d-none";
                  $update_btns = "";
                  $disable_class="disable-class";
                  $dnone_class="d-none";
                  $addrow_class_update = "d-none";
               }
               $dotmatrix_print_class="d-none";
               $regular_print_class="";
               if($default_print=='Dot Matrix Print')
               {
                  $regular_print_class="d-none";
                  $dotmatrix_print_class="";
               }
               else{
                  $regular_print_class="";
                  $dotmatrix_print_class="d-none";
               }
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo 
                    $invoice_number; ?></li>
                </ol>
            </nav>
            <div class="row">
               <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                  <h4 class="card-title"><?php echo $invoice_number;?></h4>
               </div>
               <div class="col-xl-7 col-lg-10 col-md-7 col-sm-12 col-xs-12">  
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
                           // else{
                           //    echo '<li class="active">'. $invoice_number . '</li>';
                           // }
                           if (!empty($trackingdata['invoice_retutn_number'])) { 
                              echo '<li><a href="' . base_url('invoicecreditnotes/create?iid=' . $trackingdata['invoice_retutn_number']).'">' . $trackingdata['invoice_retutn_number'] . '</a></li>';
                           }
                     }
                  
                     ?>
                  </ul>  
               </div>
               <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 current-status">
                  <?php
                     if($invoice['iid'])
                     {
                        $validtoken = hash_hmac('ripemd160', $invoice['iid'], $this->config->item('encryption_key'));                     
                        $link = base_url('billing/view?id=' . $invoice['iid'] . '&token=' . $validtoken);
                     }
                     $messagetext="";
                     $statustext="";
                     // echo $master['paymentstatus'];
                     $savedraftbtn = "";
                     $invoice_disable = "disable-class";
                     $edit_customer_btn = "";
                     if($master['paymentstatus'])
                     {
                     switch ($master['paymentstatus']) {
                        // case 'post dated cheque':
                        //    $status = '<span class="st-rejected">' . $this->lang->line($invoices->status) . '</span>';
                        //    break;
                        case 'Deleted':
                           $statustext = "Deleted";
                           $alertcls = "alert-danger";
                           $messagetext = "The invoice has been deleted.";
                           $edit_customer_btn = "disable-class";
                           break;
                        case 'Draft':
                           $statustext = "Draft";
                           $alertcls = "alert-secondary";
                           $messagetext = "Data Saved As Draft";
                           $invoice_disable ="";
                           break;
                        case 'paid':
                           $statustext = "Paid";
                           $alertcls = "alert-success";
                           $messagetext = "Invoice Created & Payment Received";
                           $savedraftbtn = "disable-class";
                           $edit_customer_btn = "disable-class";
                           break;

                        case 'due' && $returned_status !=1:
                           $statustext = "Created";
                           $alertcls = "alert-partial";
                           $messagetext = "";
                           break;

                        case 'due' && $returned_status ==1:
                           $statustext = "Fully Returned";
                           $alertcls = "alert-danger";
                           $messagetext = "All invoiced items have been fully returned";
                           $edit_customer_btn = "disable-class";
                           break;
                  
                           case 'partial':
                           $statustext = "Partial";
                           $alertcls = "alert-partial";
                           $messagetext = "Invoice Created & Partial Payment Received";
                           $savedraftbtn = "disable-class";
                           $edit_customer_btn = "disable-class";
                           // if ($pending_invoice == 1) {
                           //    $makepaymentbtn = '<a href="' . base_url("invoices/customer_payment?id=$invoices->id&csd=$invoices->csd") . '" class="btn btn-secondary btn-sm"><span class="fa fa-money"></span> Make Payment</a>';
                           // } else {
                           //    $makepaymentbtn = '';
                           // }
                           // $status = ($invoices->status != 'Draft') ? '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>' : $invoices->status;
                           break;
                  
                        default:
                           // $status = ($invoices->status != 'Draft') ? '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>' : '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                           // $makepaymentbtn = '';
                           break;
                     }
                  }
                  if ($invoices->payment_type == 'Customer Credit') {
                        $status = '<span class="st-paid">Paid</span>';
                        $makepaymentbtn = '';
                  }
                     if($statustext)
                     {
                        echo '<div class="btn-group alert text-center '.$alertcls.'" role="alert">'.$statustext.'</div>';
                     } 
                  ?>
               </div>
            </div>
          
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <?php
               if($function_number){ 
                  //if ($invoice['order_status'] == "Sent"){ ?>
                     <script>
                        // setTimeout(function () {
                        //       $('.breadcrumb-approvals .cancel_level[data-level="4"]').closest('li').remove();
                        // }, 1000);
                     </script>
                  <?php //} ?>
                  <ul class="breadcrumb-approvals d-none">
                     <li><a href="#" class="first_level breaklink" data-level="1"><?php echo $this->lang->line('First Level');  ?></a></li>
                     <li><a href="#" class="second_level breaklink" data-level="2"><?php echo $this->lang->line('Second Level');  ?></a></li> 
                  </ul>
                <?php } ?>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
             <?php //approval cancelation section starts
               if($function_number)
               { ?>
                     <div class="approval-cancel-container"> 
                        <form method="POST" name="approved_cancellation_form" id="approved_cancellation_form">
                           <h4 class="card-title1 text-center"><?php echo $this->lang->line('Cancel Approved Levels');  ?></h4>
                           <hr>
                           <div>
                                    <?php 
                                    $last_approval_step = 0;
                                    if($my_approval_permissions)
                                    {
                                       $first_level_display = ($my_approval_permissions[0]['first_level_approval']=='No') ? 'd-none' : '';
                                       $second_level_display = ($my_approval_permissions[0]['second_level_approval']=='No') ? 'd-none' : '';
                                       $third_level_display = ($my_approval_permissions[0]['third_level_approval']=='No') ? 'd-none' : '';
                                    }
                                    if($approved_levels)
                                    {
                                       $last_approval_step = max(array_column($approved_levels, 'approval_step'));
                                       $step1_disable = ($last_approval_step==1) ? "" : "disable-class"; 
                                       $step2_disable = ($last_approval_step==2) ? "" : "disable-class"; 
                                       $step1_checked = ($last_approval_step==1) ? "checked" : ""; 
                                       $step2_checked = ($last_approval_step==2) ? "checked" : ""; 
                                    }
                                    ?>

                                 <div class="mt-1 col-12">
                                    <input type="hidden" name="module_number" value="<?=$module_number?>">
                                    <input type="hidden" name="function_number" value="<?=$function_number?>">
                                    <input type="hidden" name="approval_step" id="latest_level" value="<?=$last_approval_step?>">
                                    <label class="col-form-label mb-1">Select Cancel Level</label><br>
                                    <div class="form-check form-check-inline <?php echo $step1_disable." ".$first_level_display; ?> disable-class">                     
                                       <input class="form-check-input main-permission-level checkallmodules CRM-Module" type="checkbox" name="first_level_cancel" id="first_level_cancel" value="0" autocomplete="off" title="First Level Approval" <?=$step1_checked?>><label class="form-check-label label-size" for="first_level_cancel">First Level</label></div>   <div class="form-check form-check-inline <?php echo $step2_disable." ".$second_level_display; ?> disable-class"><input class="form-check-input main-permission-level checkallmodules CRM-Module" type="checkbox" name="second_level_cancel" id="second_level_cancel" value="0" autocomplete="off" title="Second Level Approval" <?=$step2_checked?>><label class="form-check-label label-size" for="second_level_cancel">Second Level</label></div>
                                       <div>
                                             <label for="contents" class="col-form-label">Reason For Cancellation <span class="compulsoryfld">*</span></label>
                                             <textarea class="summernote1 form-textarea " name="cancelreason" id="cancelreason" rows="4" title="Notes to Customer" data-original-value="" autocomplete="off" required></textarea>
                                       </div>
                                       <div class="text-right mt-2 mb-2">
                                             <button class="approval-close-button btn btn-crud  sub-btn btn-secondary" title="Close" >
                                                <span>Close</span>
                                             </button>   
                                             <input type="submit" class="btn btn-crud  sub-btn btn-primary cancel-approved-btn" value="Save" title="Cancel Selected Approved Level" data-loading-text="cancelling..." autocomplete="off" fdprocessedid="n3b9lh">

                                       </div>
                                 </div>
                           </div>
                        </form>
                        <!-- sidebar close btn -->
                        <button class="approval-close-button approval-close-button-style" title="Close" >
                           <span>Close</span>
                        </button>      
                     
                     </div>
               <?php
               } //approval cancelation section ends ?>
      </div>

      <div class="card-content">
         <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
         </div>
         <div class="card-body">
            <form method="post" id="data_form" enctype="multipart/form-data">               
               <input type="hidden" name="function_number" class="function_number" value="<?=$function_number?>">
               <input type="hidden" name="invoice_type" class="invoice_type" value="<?=$invoice['invoice_type']?>">
               <input type="hidden" name="invoice_action_type" value="<?=$invoice_action_type?>">
               <div class="title-action row"> 
                  <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12 <?=$topbuttons_class?>">                  
                     <!-- <a href="<?php echo 'edit?id=' . $invoice['iid']; ?>" class="btn btn-sm btn-secondary mb-1"><i
                           class="fa fa-pencil"></i> <?php echo $this->lang->line('Edit Invoice') ?></a> -->
                     <?php
                     $disablecls ="";
                     $deletedclass = "";
                     if($invoice['paymentstatus']=='paid' || $invoice['paymentstatus']=='post dated cheque'  || $invoice['paymentstatus']=='Deleted')
                     { 
                        $disablecls = "disable-class";
                     }
                     
                     if($invoice['paymentstatus']=='Deleted' || $returned_status==1 || $invoice['paymentstatus']=='post dated cheque'){
                        $deletedclass = "disable-class";
                     }
                        ?>
                     <a href="<?php echo base_url('invoices/customer_payment?id=' . $invoice['iid'] . '&csd=' . $invoice['cid']); ?>" class="btn btn-sm btn-secondary mb-1 <?=$disablecls?> <?=$deletedclass?>" title="Make Payment" ><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a>
                     
                     <a href="<?php echo base_url('invoicecreditnotes/create?id=' . $invoice['iid']); ?>" class="btn btn-sm btn-secondary mb-1  <?=$deletedclass?>" title="Return Items" ><span class="fa fa-undo"></span> <?php echo $this->lang->line('Return Items') ?> </a>
                     
                     <!-- $creditnoteBtn = '<a href="' . base_url("invoicecreditnotes/create?id=$invoices->id") . '" class="btn btn-sm btn-secondary ' . $disablecls . '"><i class="fa fa-undo"></i> ' . $this->lang->line('Return Items') . '</a>'; -->
         
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
                           class="btn btn-sm btn-secondary mb-1 disable-class <?=$deletedclass?> d-none" title="Change Status"
                           ><span class="fa fa-retweet"></span> <?php echo $this->lang->line('Change Status') ?></a>
                     <!-- <a href="#cancel-bill" class="btn btn-sm btn-secondary mb-1" id="cancel-bill"><i
                           class="fa fa-minus-circle"> </i> <?php echo $this->lang->line('Cancel') ?>
                     </a> -->
                     <button class="btn btn-sm btn-secondary mb-1 cancelinvoice-btn <?=$deletedclass?>" title="After canceling the invoice, all related transactions will be reverted, and the invoice can be reused as a draft."><?php echo $this->lang->line('Cancel') ?></button>
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
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center <?=$topbuttons_class?> messagetext_class">
                     <?php               
                     if(($messagetext)){
                     ?>    
                        <div class="btn-group alert alert-success text-center <?=$msgcls?>" role="alert">
                           <?php echo $messagetext; ?>
                        </div>
                     <?php } ?>
                     
                  </div>   
                  <?php
                  
                  if(($topbuttons_class))
                     { ?>
                        <!-- <div class="col-lg-12 text-center">
                           <div class="btn-group" id="creditlimit-check"></div>
                        </div> -->
                     <?php } ?>       
                  <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 text-lg-right text-md-right text-sm-left text-xs-left <?=$topbuttons_class?>">
                     <a href="<?php echo $link; ?>" class="btn btn-sm btn-secondary mb-1 d-none"  target="_blank"><i class="fa fa-globe"></i> <?php echo $this->lang->line('Preview') ?>
                     </a>
                     <div class="btn-group ">
                           <button type="button" class="btn btn-sm btn-secondary dropdown-toggle mb-1"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                           class="fa fa-print"></i> <?php echo $this->lang->line('Print') ?>
                           </button>
                           <div class="dropdown-menu">
                           <a class="dropdown-item <?=$regular_print_class?>"
                              href="<?= base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>" target="_blank"><?php echo $this->lang->line('Print') ?></a>
                           <div class="dropdown-divider <?=$regular_print_class?>"></div>
                           <a class="dropdown-item <?=$dotmatrix_print_class?>" href="<?= base_url('billing/pre_print_invoice?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>" target="_blank"><?php echo $this->lang->line('Print') ?></a>
                           <!-- <div class="dropdown-divider <?=$dotmatrix_print_class?>"></div> -->
                           <a class="dropdown-item  <?=$regular_print_class?>"
                              href="<?= base_url('billing/printinvoice?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>&d=1"  target="_blank"><?php echo $this->lang->line('PDF Download') ?></a>
                           <div class="dropdown-divider <?=$regular_print_class?>"></div>
                           <a class="dropdown-item  <?=$regular_print_class?>" href="<?= base_url() . 'pos_invoices/thermal_pdf?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('PDF Print') ?></a>
                           <div class="dropdown-divider <?=$regular_print_class?> d-none"></div>
                           <a class="dropdown-item  <?=$regular_print_class?> d-none"
                              href="<?= base_url() . 'invoices/delivery?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('Delivery Note') ?></a>
                           <div class="dropdown-divider <?=$regular_print_class?> d-none"></div>
                           <a class="dropdown-item  <?=$regular_print_class?> d-none"
                              href="<?= base_url() . 'invoices/proforma?id=' . $invoice['iid']; ?>"  target="_blank"><?php echo $this->lang->line('Proforma Invoice') ?></a>
                           </div>
                     </div> 
                  </div>
               </div>
               <?php
               $duedate = (!empty($master['invoiceduedate']) && $master['invoiceduedate'] != '0000-00-00') 
               ? $master['invoiceduedate'] 
               : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['invoice_validity'] . " days"));
               $term = ($master['term'])?$master['term']:$validity['payment_terms'];
               $disable_class = ($notemaster['approval_flg']=='1') ? "disable-class" : "";              
               echo '<input type="hidden" name="approval_flg" id="approval_flg" value="'.$notemaster['approval_flg'].'">';
               $headerclass= "d-none";
               $pageclass= "page-header-data-section-dblock";
               $savedraft_class= "";
               $new_invoice_class="d-none";
               if(($invoiced_id))
               {
                   $headerclass = "page-header-data-section-dblock";
                   $pageclass   = "page-header-data-section";
                   if($master['paymentstatus'] != 'Draft')
                   {
                     $savedraft_class= "d-none";
                   }                     
                   $new_invoice_class="";
               }
               $customer_id = $assigned_customer['customer_id'];
               $employee_id = $created_employee['id']; 
               
               $deliverynote_single_class="";
               if($convert_type=="Single")
               {
                  $deliverynote_single_class ="disable-class";
               }
               ?>
                 <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                     <div class="row">
                              <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                                 <h3  class="title-sub"><?php echo $this->lang->line('Invoice & Customer Details') ?> <i class="fa fa-angle-down"></i></h3>
                              </div>
                              <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 responsive-text-right quickview-scroll order-1 order-lg-2">
                                 <div class="quick-view-section">
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Customer') ?></h4>
                                       <?php
                                             echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($master['name']) . "</b></a>";
                                       ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Credit Limit') ?></h4>
                                       <?php
                                       echo "<b>".$assigned_customer['avalable_credit_limit'] . "</b> / <b>".$assigned_customer['credit_limit'] . "</b>";
                                       ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Credit Period') ?></h4>
                                       <?php
                                       echo "<b>".htmlspecialchars($assigned_customer['credit_period']) . " Days</b></a>";
                                       ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Created') ?></h4>
                                       <?php echo "<p>".dateformat($master['created_date'])."</p>"; ?>
                                    </div>
                                    
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Sales Point') ?></h4>
                                       <?php echo "<p>".$master['warehousename']."</p>"; ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Due Date') ?></h4>
                                       <?php echo "<p style='color:".$colorcode."'>".dateformat($master['invoiceduedate'])."</p>"; ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Created By') ?></h4>
                                       <?php 
                                             echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                       ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Paid'); ?></h4>
                                       <?php 
                                       echo "<p>".number_format($master['payment_recieved_amount'],2)."</p>";?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Due'); ?></h4>
                                       <?php 
                                       $dueAmt = $master['total'] - $master['payment_recieved_amount'];
                                       echo "<p>".number_format($dueAmt,2)."</p>";?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Total'); ?></h4>
                                       <?php echo "<p>".number_format($master['total'],2)."</p>";?>
                                    </div>
                                 </div>
                        </div>
                     </div>
                  </div>
                  

               <div class="<?=$pageclass?>">
                  <div class="row">
                     <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                        <div id="customerpanel" class="inner-cmp-pnl">
                           <div class="form-group row">
                              <div class="fcol-sm-12">
                                 <h3 class="title-sub">
                                 <?php 
                                    $customer_search_section = ($customer) ? "d-none" : "";
                                    echo $this->lang->line('Client Details'); ?><h3>
                              </div>
                              <div class="frmSearch customer-search-section col-sm-12 <?=$customer_search_section?>">
                                 <label for="cst" class="col-form-label d-flex justify-content-between align-items-center" id="customerLabel">
                                       <span><?php echo $this->lang->line('Search Client'); ?><span class="compulsoryfld">*</span></span>
                                       <input type="button" value="Add New Customer" class="btn btn-sm btn-secondary add_customer_btn" autocomplete="off" title="Add New Customer">
                                 </label>

                                 <input type="text" class="form-control" name="cst" id="customer-box" title="Customer Search"
                                    placeholder="<?php echo $this->lang->line('Enter Customer Name or Mobile Number to search'); ?>"
                                    autocomplete="off" />
                                 <div id="customer-box-result"></div>
                              </div>
                           </div>
                           <div id="customer">
                           <?php
                              if($customer){
                                 echo '<div class="existingcustomer_details">';
                                 echo '<div class="clientinfo">
                                 <div id="customer_name"><strong>' . $master['name'] . '</strong><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectionedit '.$edit_customer_btn.'">'.$this->lang->line("Customer Edit").'</button><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectioncancel d-none">'.$this->lang->line("Customer Cancel").'</button></div></div></div>';
                                 ?>
                                 <div class="clientinfo">
                                       <?php
                                          $customer_id = (!empty($customer['customer_id']) && $customer['customer_id']>0) ? $customer['customer_id'] : 0; 
                                       ?>
                                       <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer_id?>">
                                       
                                 </div>
                                 <div class="clientinfo">

                                          <div id="customer_address1"><?php echo '<div id="customer_address1"><strong>' . $customer['address'] . '<br>' . $customer['city'] . ',' . $customer['countryname'] . '</strong></div>'; ?></div>
                                 </div>

                                 <div class="clientinfo">
                                          <div type="text" id="customer_phone">
                                             <?php echo ' <div type="text" id="customer_phone">Phone : <strong>' . $customer['phone'] . '</strong><br>Email : <strong>' . $customer['email'] . '</strong></div>';
                                             echo '<div type="text" >'.$this->lang->line('Company Credit Limit').' : <strong>' . number_format($customer['credit_limit'],2) . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $customer['credit_period'] . '(Days)</strong><br><br><strong><span class=avail_creditlimit '.$cls.'>'.$this->lang->line('Available Credit Limit').' : ' . number_format($customer['avalable_credit_limit'],2) . '</strong></span><input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="' . $customer['avalable_credit_limit'] . '"><input type="hidden" id="available_credit" value="' . $customer['avalable_credit_limit'] . '"></div>';
                                             ?>
                                          </div>
                                 </div>
                                 <?php
                              }
                              else{
                                 ?>
                                 <div class="clientinfo">
                                 
                                    <input type="hidden" name="customer_id" id="customer_id" value="0">
                                    <div id="customer_name"></div>
                                 </div>
                                 <div class="clientinfo">
                                    <div id="customer_address1"></div>
                                 </div>
                                 <div class="clientinfo">
                                    <div id="customer_phone"></div>
                                 </div>
                                 <div id="customer_pass"></div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                     <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                        <div class="inner-cmp-pnl">
                           <?php 
                        
                              $delevery_note_number = ($master['delevery_note_number']) ? $master['delevery_note_number'] : '0';
                              $transaction_number = ($master['transaction_number']) ? $master['transaction_number'] : '0';
                              echo '<input type="hidden" name="convert_type" id="convert_type" value="'.$convert_type.'">';
                              

                           ?>
                           <input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice['iid']; ?>">
                           <input type="hidden" name="iid" id="iid" value="<?php echo $invoice['iid']; ?>">
                           <div class="form-row">
                              <div class="col-2">
                                 <h3 class="title-sub"><?php echo $this->lang->line('Invoice Properties') ?></h3>
                              </div>
                              <!-- <div class="col-6"> -->
                                 <?php
                                 // if($deliverynote_number)
                                 // {
                                 //    echo '<div class="btn-group alert alert-success"> Delivery Note Number : '.$deliverynote_number.'</div>';       
                                 // }
                                 ?>                
                              <!-- </div> -->
                              <div class="col-12"></div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="invocieno"
                                    class="col-form-label"><?php echo $this->lang->line('Invoice Number') ?></label>
                                 <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-file-text-o"
                                       aria-hidden="true"></span></div>
                                    
                                    

                                 </div>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <input type="hidden" class="form-control" placeholder="Invoice #" name="invoice_number"  id="invoice_number" value="<?php echo $invoice_number ?>" readonly>
                                 <input type="hidden" class="form-control" placeholder="Invoice #" name="invocieno" id="invocieno" value="<?php echo $invoiceid ?>" readonly>
                                 <input type="hidden" class="form-control" placeholder="Invoice #" name="status" value="<?php echo $status ?>" readonly>
                                 <label for="invocieno"
                                    class="col-form-label"><?php echo $this->lang->line('Reference') ?><span class="compulsoryfld"> *</span></label>
                                    <input type="text" class="form-control <?=$deliverynote_single_class?>" placeholder="Reference" name="refer" value="<?=$master['reference']?>" data-original-value="<?=$master['reference']?>" title="Reference">
                              </div>
                              <?php
                              if($convert_type=="Single")
                              { ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                       <label for="invocieduedate"
                                          class="col-form-label"><?php echo $this->lang->line('Delivery Note') ?></label>
                                       
                                          <input type="text" class="form-control <?=$deliverynote_single_class?>"  placeholder="Due Date" autocomplete="false"  Value="<?=$deliverynote_number?>" data-original-value="<?=$deliverynote_number?>" title="Delivery Note">
                                    </div>
                              <?php 
                              }?>
                              
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="invocieduedate"
                                    class="col-form-label"><?php echo $this->lang->line('Invoice Due Date') ?><span class="compulsoryfld"> *</span></label>
                                 
                                    <input type="date" class="form-control" name="invocieduedate"
                                       placeholder="Due Date" autocomplete="false" min="<?=date('Y-m-d')?>" Value="<?=$duedate?>"  data-original-value="<?=$duedate?>" title="<?php echo $this->lang->line('Invoice Due Date') ?>">
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="taxformat" class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                                 <select class="form-control" onchange="changeTaxFormat(this.value)"
                                    id="taxformat"> <?php echo $taxlist; ?>
                                 </select>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="discountFormat"
                                    class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                 <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                    id="discountFormat">
                                 <?php echo $this->common->disclist() ?>
                                 </select>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouses') ?><span class="compulsoryfld"> *</span> </label>
                                 <?php
                                    $master['store_id']  = ($master['store_id']) ? $master['store_id'] : $store_id;
                                    
                                 ?>
                                 <select id="s_warehouses" name="s_warehouses" class="form-control <?=$deliverynote_single_class?>" data-original-value="<?=$master['store_id']?>" title="<?php echo $this->lang->line('Warehouses') ?>">
                                 <?php 
                                    if(empty($master['store_id']))
                                    {
                                       echo '<option value="">' . $this->lang->line('Select Warehouse').'</option>';
                                    }
                                    
                                    
                                    foreach ($warehouse as $row) {
                                       // $sel = ($master['store_id'] == $row['id']) ? "selected" : "";
                                       if(($master['store_id'] == $row['store_id']))
                                       {
                                          echo '<option value="' . $row['store_id'] . '" '.$sel.'>' . $row['store_name'] . '</option>';
                                       }
                                       else{
                                          echo '<option value="' . $row['store_id'] . '" >' . $row['store_name'] . '</option>';
                                       }
                                       
                                    } ?>
                                 </select>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <?php if (isset($employee)){ ?>
                                 <label for="employee"
                                    class="col-form-label"><?php echo $this->lang->line('Employee') ?> </label>
                                 <select name="employee" class="col form-control disable-class" readonly>
                                 <?php 
                                 foreach ($employee as $row) {
                                    $sel = ($row['id']==$this->session->userdata('id')) ? "selected" : "" ;
                                    echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['name'] . '</option>';
                                 } ?>
                                 </select><?php } ?>
                                 <?php if ($exchange['active'] == 1){
                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                 <select name="mcurrency" class="selectpicker form-control">
                                    <option value="0">Default</option>
                                    <?php foreach ($currency as $row) {
                                       echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                       } ?>
                                 </select>
                                 <?php } ?>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="pterms"
                                    class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?>
                                 </label>
                                 <select name="pterms" class="selectpicker form-control" data-original-value="<?=$term?>" title="<?php echo $this->lang->line('Payment Terms') ?>">
                                    <?php foreach ($terms as $row) {
                                       $selected = ($term == $row['id']) ? "selected" : "";
                                       echo '<option value="' . $row['id'] . '" '.$selected.'>' . $row['title'] . '</option>';
                                    } ?>
                                 </select>
                              </div>
                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                 <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Note') ?></label>
                                 <textarea class="form-textarea" name="notes" rows="2" data-original-value="<?=$master['notes']?>" title="<?php echo $this->lang->line('Note') ?>"><?=$master['notes']?></textarea>
                              </div>

                              <div class="col-12 d-none">
                                 <label class="col-form-label font-13"><strong><?php echo $this->lang->line('Payment Type') ?></strong></label><br>
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="cashOption" value="Cash">
                                    <label class="form-check-label font-13" for="cashOption"><b><?php echo $this->lang->line('Cash') ?></b></label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="creditCardOption" value="Credit Card">
                                    <label class="form-check-label font-13" for="creditCardOption"><b><?php echo $this->lang->line('Credit Card') ?></b></label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="chequeOption" value="Cheque">
                                    <label class="form-check-label font-13" for="chequeOption"><b><?php echo $this->lang->line('Cheque') ?></b></label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                    <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="customerCreditOption" value="Customer Credit">
                                    <label class="form-check-label font-13" for="customerCreditOption"><b><?php echo $this->lang->line('Customer Credit') ?></b></label>
                                 </div>


                              </div>
                              <!-- Image upload sections starts-->
                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                          <label for="upfile-0" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
                                          <div class="row">                            
                                             <div class="col-8">
                                                   <div class="d-flex">
                                                      <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                      <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                      <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="fa fa-trash" ></i></button>
                                                   </div>
                                                   <div id="uploadsection"></div>                                                
                                             </div>                        
                                             <div class="col-4">
                                                      <button class="btn btn-crud btn-secondary btn-sm mt-1" id="addmore_img"  title="Add More Files" type="button"><i class="fa fa-plus-circle"></i> Add More</button>
                                                   
                                             </div>
                                          </div>
                                    </div>
                              <!-- Image upload sections ends -->

                              <!-- ===== Image sections starts ============== -->
                              <div class="container-fluid overflow-auto">
                                 <div class="mt-2">
                                    
                                    <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12"> -->
                                       <?php 
                                    $imgcontains = 0;
                                    if (!empty($images)) {
                                       echo '<table class="table table-striped table-bordered table-responsive">';
                                       $imgcontains = 1;
                                    
                                       foreach ($images as $image) {
                                             $file_extension = strtolower(pathinfo($image['file_name'], PATHINFO_EXTENSION));
                                             $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png']);
                                             $file_url = base_url("uploads/{$image['file_name']}");
                                             $img_tag = $is_image ? "<img src='{$file_url}' class='img-thumbnail' alt='{$image['actual_name']}' style='width:70px; height:70px;'>" : '<i class="fa fa-file-code-o fsize-70"></i>';
                                             $download_attr = $is_image ? 'download' : '';
                                             $icon = "Click to download <i class='fa fa-download'></i><br>";
                                             $imgname = $image['actual_name'];
                                    
                                             if ($imgcontains % 5 == 1) {
                                                echo '<tr>';
                                             }
                                    
                                             echo "<td class='text-center file-td-section'>";
                                             echo "<div class='file-section'>";
                                             echo $img_tag ? "{$img_tag}" : '';
                                             // echo '<p>'.$imgname.'</p>';
                                             echo "<br><a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary'>{$icon}</a>&nbsp;";
                                             echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"deleteitem('{$image['lead_attachment_id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
                                             echo "</div>";
                                             echo "";
                                             echo "</td>";
                                    
                                             if ($imgcontains % 5 == 0) {
                                                echo '</tr>';
                                             }
                                    
                                             $imgcontains++;
                                       }
                                    
                                       // Close the last row if it wasn't closed
                                       if (($imgcontains - 1) % 5 != 0) {
                                             echo '</tr>';
                                       }
                                    
                                       echo '</table>';
                                    }

                                    
                                    ?>

                                 </div>
                           </div>
                           <!-- ===== Image sections ends ============== -->
                              
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               
               <!-- ---------- alert message ----- -->
               <div class="alert alert-danger alert-dismissible creditlimit-alert d-none" role="alert">
                  <?php echo $this->lang->line("Your available credit limit"); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <!-- <div class="col-lg-12">
                  <div id="creditlimit-check"></div>
               </div> -->
               <!-- ---------- alert message ----- -->
               

                  <!-- ========================= tab starts ==================== -->
                  <ul class="nav nav-tabs mb-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                aria-controls="tab1" href="#tab1" role="tab"
                                aria-selected="true"><?php echo $this->lang->line('Invoice Properties') ?></a>
                        </li>
                        <li class="nav-item <?=$new_invoice_class?>">
                            <a class="nav-link breaklink navtab-caption" id="base-tab1" data-toggle="tab"
                                aria-controls="tab5" href="#tab5" role="tab"
                                aria-selected="true"><?php echo $this->lang->line('Delivery Notes') ?></a>
                        </li>
                        <li class="nav-item <?=$new_invoice_class?> d-none">
                            <a class="nav-link breaklink navtab-caption" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                href="#tab2" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Payment Details') ?></a>
                        </li>
                        <li class="nav-item <?=$new_invoice_class?>">
                            <a class="nav-link breaklink navtab-caption" id="base-tab2" data-toggle="tab" aria-controls="tab3"
                                href="#tab3" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Payments Received') ?></a>
                        </li>
                        <li class="nav-item <?=$new_invoice_class?>">
                            <a class="nav-link breaklink navtab-caption" id="base-tab2" data-toggle="tab" aria-controls="tab4"
                                href="#tab4" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Journals') ?></a>
                        </li>
                           
                  </ul>
                  <!-- ======================== Tab conent section starts ================ -->
                  <div class="tab-content px-1 pt-1">
                     <!-- ====================tab table ================ -->
                        <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                              <div class="col-12 form-row mt-1 discount-toggle">
                              <div class="form-check" >
                                 <input class="form-check-input discountshowhide" type="checkbox" value="2"  name="discountshowhide" id="discountshowhide">
                                 <label class="form-check-label dicount-checkbox" for="discountshowhide">
                                 <b><?php echo $this->lang->line('Would you like to add a discount for these products?'); ?></b>
                                 </label>
                              </div>
                        </div>
                        <input type="hidden" name="discount_flg" class="discount_flg" value="0">                                         
                        <input type="hidden" class="form-control deleted_item" name="deleted_item">
                        <div id="saman-row" class="overflow-auto">
                           <table class="table table-striped table-bordered zero-configuration dataTable">
                              <thead>
                                 <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="4%" class="text-center"><?php echo $this->lang->line('SN') ?></th>
                                    <th width="10%" class="text-center1 pl-1">
                                       <?php echo $this->lang->line('Item No') ?>
                                    </th>
                                    <th width="25%" class="text-center1 pl-1">
                                       <?php echo $this->lang->line('Item Name') ?>
                                    </th>
                                    <th width="8%" class="text-center1 pl-1"><?php echo $this->lang->line('Quantity') ?>
                                    </th>
                                    
                                    <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                                    <th width="7%" class="text-right"><?php echo $this->lang->line('Selling Price') ?></th>
                                    <th width="7%" class="text-right"><?php echo $this->lang->line('Lowest Price') ?></th>
                                    <!-- <th width="8%" class="text-center1 pl-1"><?php echo $this->lang->line('Rate') ?></th> -->
                                    <?php
                                    $colspan=9;
                                    $colspansmall=4;
                                    $colspangrandtotal = 5;
                                    if($configurations['config_tax']!=0)
                                    {
                                       $colspan=11; 
                                       $colspansmall=6;
                                       $colspangrandtotal = 7;
                                      
                                       ?>
                                       <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Tax(%)') ?></th>
                                       <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Tax') ?></th>
                                       <?php  
                                    } ?>
                                    
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Max discount %')?></th>
                                    <th width="12%" class="text-center discountcoloumn d-none"><?php echo $this->lang->line('Discount')?>/ <?php echo $this->lang->line('Amount'); ?></th>
                                    <th width="10%" class="text-right pl-1">
                                       <?php echo $this->lang->line('Amount'); ?>
                                       <!-- (<?= currency($this->aauth->get_user()->loc); ?>) -->
                                    </th>
                                    <th width="9%" class="text-center1 pl-1"><?php echo $this->lang->line('Action') ?>
                                    </th>
                                 </tr>
                              </thead>
                              <tbody>
                                 
                              <?php
                                       $i=0;
                                       $totaldiscount = 0;
                                       $totaltax      = 0;
                                       $subtotal      = 0;
                                       $k=1;
                                       $discount_flg=0;
                                       if(!empty($products))
                                       {
                                             
                                             foreach($products as $row)
                                             {
                                                if($row['product_discount']>0 && $discount_flg==0)
                                                {
                                                   $discount_flg =1;
                                                }   
                                                $totaldiscount += $row['deliverytotaldiscount'];
                                                $totaltax   += $row['totaltax'];
                                                $subtotal    += ($row['product_qty'] * $row['product_price']);
                                             
                                                $delevery_note_number = (($convert_type == 'Single')) ? $master['delivery_note_number'] : $row['delevery_note_number'];
                                                $delivery_note_number = (($convert_type == 'Single')) ? $master['delivery_note_number'] : $row['delivery_note_number'];
                                                if($convert_type == 'Single' || empty($convert_type))
                                                {
                                                   $transaction_number =  $master['transaction_number'] ;
                                                }
                                                else{
                                                   $transaction_number =  $row['transaction_number'];
                                                }
                                                $productcode = $row['product_code'];
                                                
                                                ?>
                                                <tr>        
                                                   
                                                   <td class="text-center serial-number"><?=$k++?></td>
                                                   <td><input type="text" class="form-control code <?=$invoice_disable?>" name="code[]" id="code-<?=$i?>" value="<?=$row['product_code']?>" data-product-code="<?=$productcode?>">
                                                   <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-<?=$i?>" value="<?=$row['product_cost']?>" data-product-code="<?=$productcode?>">
                                                   <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-<?=$i?>" value="<?=$row['income_account_number']?>" data-product-code="<?=$productcode?>">
                                                   </td>
                                                   <td><span class="d-flex"><input type="text" class="form-control product_name  <?=$invoice_disable?>" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' value="<?=$row['product_name']?>">&nbsp;<button type="button" title="change account"
                                                         class="btn btn-crud btn-sm btn-secondary  <?=$invoice_disable?>"
                                                         id="btnclk-<?= $i ?>"
                                                         data-toggle="popover"
                                                         onclick="loadPopover(<?= $i ?>)"
                                                         data-html="true"
                                                         data-content='
                                                            <form id="popoverForm-<?= $i ?>">
                                                               <div class="form-group">
                                                                     <label for="accountList-<?= $i ?>">Select Account</label>
                                                                     <select class="form-control" id="accountList-<?= $i ?>">
                                                                        <!-- Options will be loaded dynamically -->
                                                                     </select>
                                                               </div>
                                                               <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn  btn-crud btn-primary btn-sm">Change</button></div>
                                                            </form>'
                                                   >
                                                         <i class="fa fa-bank"></i>
                                                   </button></span></td>

                                                   <td class="text-center position-relative"><input type="text" class="form-control req amnt product_qty  <?=$invoice_disable?>" name="product_qty[]" id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog(), orderdiscount()" autocomplete="off" value="<?=intval($row['product_qty'])?>" data-product-code="<?=$productcode?>"><div class="tooltip1"></div></td>

                                                   <td class="text-center"><strong id="onhandQty-<?=$i?>"><?=$row['totalQty']?></strong></td>
                                                   <td class="text-right">    
                                                         <strong id="pricelabel-<?=$i?>"><?=$row['product_price']?></strong>
                                                         <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>" value="<?=$row['product_price']?>"  onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()" autocomplete="off"></td>
                                                   <td class="text-right">
                                                         <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-<?=$i?>" onkeypress="return isNumber(event)" autocomplete="off" value="<?=$row['minprice']?>">
                                                         <strong id="lowestpricelabel-<?=$i?>"><?=$row['minprice']?></strong>
                                                   </td>
                                                   <?php //Verify that tax is enabled
                                                   if($configurations['config_tax']!='0'){ ?>           
                                                            <td class="text-center">
                                                               <div class="text-center">                                                
                                                                     <input type="hidden" class="form-control" name="product_tax[]" id="vat-<?=$i?>"
                                                                        onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                                                        autocomplete="off">
                                                                        <strong id="taxlabel-<?=$i?>"></strong>&nbsp;<strong  id="texttaxa-<?=$i?>"></strong>
                                                               </div>
                                                            </td>
                                                   <?php } 

                                                   
                                                   // erp2024 27-03-2025 discount amount calcualation
                                                   $maxdiscountamount=0;
                                                   $productprice = amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc);
                                                   $maxdiscountamount = round(($productprice * $row['maximumdiscount']) / 100, 2);
                                                   $row['maximumdiscount'] = (intval($row['maximumdiscount']) == floatval($row['maximumdiscount']))  ? intval($row['maximumdiscount']) : number_format($row['maximumdiscount'], 2);
                                                   $discountamount = $row['maximumdiscount']."% (".$maxdiscountamount.")";
                                                   echo '<td class="text-center"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-'.$i.'" value="' . $maxdiscountamount . '"><strong id="maxdiscountratelabel-' . $i . '">' .$discountamount. '</strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-'.$i.'" value="'.$row['maximumdiscount'].'"></td>';  

                                                   ?>

                                                   <td class="text-center discountcoloumn d-none">
                                                         <div class="input-group text-center">
                                                            <select name="discount_type[]" id="discounttype-<?=$i?>" class="form-control   <?=$invoice_disable?>" onchange="discounttypeChange(0), orderdiscount()">
                                                               <option value="Perctype" <?php if($row['delnote_discounttype'] =='Perctype'){ echo 'selected'; }?>>%</option>
                                                               <option value="Amttype"  <?php if($row['delnote_discounttype'] =='Amttype'){ echo 'selected'; }?>>Amt</option>
                                                            </select>&nbsp;
                                                            <input type="number" min="0" class="form-control discount  <?=$invoice_disable?>" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()" value="<?=$row['product_discount']?>" data-product-code="<?=$productcode?>">
                                                            <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()" value="<?=$row['product_discount']?>" data-product-code="<?=$productcode?>">
                                                         </div>  
                                                         <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel">Amount : <?=$row['deliverytotaldiscount']?></strong>
                                                         <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                                   </td>

                                                   <td class="text-right">
                                                         <strong><span class='ttlText' id="result-<?=$i?>"><?=number_format($row['deliverysubtotal'],2)?></span></strong></td>
                                                   <td class="text-center">
                                                         <button onclick='producthistory("<?=$i?>")' type="button" class="btn  btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                         <button onclick='single_product_details("<?=$i?>")' type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                         <button type="button" data-rowid="<?=$i?>" class="btn  btn-crud btn-sm btn-default removeProd  <?=$invoice_disable?>"  title="Remove" > <i class="fa fa-trash"></i> </button>
                                                   </td>
                                                   <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="<?=$row['deliverytaxtotal']?>">
                                                   <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?=$row['deliverytotaldiscount']?>">
                                                   <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?=$row['deliverysubtotal']?>">
                                                   <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$row['product_id']?>">
                                                   <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?=$row['unit']?>">
                                                   <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$row['product_code']?>">
                                                   <input type="hidden" name="serial[]" id="serial-<?=$i?>" value="">
                                                   
                                                   <input type="hidden" name="delevery_note_number[]" id="delevery_note_number-<?=$i?>" value="<?=$delivery_note_number?>">
                                                   <input type="hidden" name="delivery_note_id[]" id="delivery_note_id-<?=$i?>" value="<?=$delivery_note_number?>">
                                                   <input type="hidden" name="transaction_number[]" id="transaction_number-<?=$i?>" value="<?=$transaction_number?>">
                                             
                                                </tr>
                                                <?php
                                                $i++;
                                             }
                                       
                                       }
                                       else{
                                       ?>
                                       <tr class="startRow">
                                          <td class="text-center serial-number">1</td>
                                          <td><input type="text" class="form-control code" name="code[]"
                                             placeholder="<?php echo $this->lang->line('Item No') ?>"
                                             id='code-0'>
                                             <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-0">
                                             <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-0">
                                          </td>
                                          <td><span class='d-flex'><input type="text" class="form-control wid90per1" name="product_name[]"
                                             placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                             id='productname-0'>
                                             &nbsp;<button type="button" title="change account"
                                                         class="btn  btn-crud btn-sm btn-secondary"
                                                         id="btnclk-0"
                                                         data-toggle="popover"
                                                         onclick="loadPopover(0)"
                                                         data-html="true"
                                                         data-content='
                                                            <form id="popoverForm-0">
                                                               <div class="form-group">
                                                                     <label for="accountList-0">Select Account</label>
                                                                     <select class="form-control" id="accountList-0">
                                                                        <!-- Options will be loaded dynamically -->
                                                                     </select>
                                                               </div>
                                                               <div class="text-right"><button type="button" onclick="cancelPopover(0)" class="btn  btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(0)" class="btn btn-crud  btn-primary btn-sm">Change</button></div>
                                                            </form>'
                                                   ><i class="fa fa-bank"></i>
                                                   </button></span>
                                          </td>
                                          <td class="position-relative"><input type="text" class="form-control req amnt" name="product_qty[]"
                                             id="amount-0" onkeypress="return isNumber(event)"
                                             onkeyup="rowTotal('0'), billUpyog(), orderdiscount()" autocomplete="off" value=""><input
                                             type="hidden" id="alert-0" value="" name="alert[]">
                                             <div class="tooltip1"></div>
                                          </td>
                                             <!-- <td><input type="text" class="form-control req prc" name="product_price[]"
                                             id="price-0" onkeypress="return isNumber(event)"
                                             onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td> -->
                                             <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                             <td class="text-right">    
                                                   <strong id="pricelabel-0"></strong>
                                                   <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0"  onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off">
                                             </td>
                                             <td class="text-right">
                                                   <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                                   <strong id="lowestpricelabel-0"></strong>
                                             </td>
                                          
                                          <?php
                                          $colspan=9;
                                          $colspansmall=4;
                                          $colspangrandtotal = 5;
                                          if($configurations['config_tax']!=0)
                                          {
                                             $colspan=11; 
                                             $colspansmall=6;
                                             $colspangrandtotal = 7;
                                          ?>
                                          <td><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                                             onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                             autocomplete="off"></td>
                                          <td class="text-center" id="texttaxa-0">0</td>
                                          <?php } ?>
                                          <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"></td>
                                          <td class="text-center discountcoloumn d-none">
                                             <!-- <input type="text" class="form-control discount" name="product_discount[]"
                                             onkeypress="return isNumber(event)" id="discount-0"
                                             onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"> -->
                                             
                                             <div class="input-group text-center ">
                                                   <select name="discount_type[]" id="discounttype-0" class="form-control" onchange="discounttypeChange(0), orderdiscount()">
                                                      <option value="Perctype">%</option>
                                                      <option value="Amttype">Amt</option>
                                                   </select>&nbsp;
                                                   <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()">
                                                   <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()">
                                                </div>  
                                                <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                                <div><strong id="discount-error-0"></strong>
                                             </div>        
                                          </td>
                                          <td class="text-right"></span>
                                             <strong><span class='ttlText' id="result-0"> </span></strong>
                                          </td>
                                          <td class="text-center">
                                                <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                <button onclick='single_product_details("0")' type="button" class="btn btn-crud  btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                          </td>
                                          <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                          <input type="hidden" name="disca[]" id="disca-0" value="0">
                                          <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0"
                                             value="0">
                                          <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                          <input type="hidden" name="unit[]" id="unit-0" value="">
                                          <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                          <input type="hidden" name="serial[]" id="serial-0" value="">
                                          <input type="hidden" name="transaction_number[]" id="transaction_number-0" value="">
                                       </tr>

                                 <?php } ?>
                                 <tr class="last-item-row sub_c tr-border <?=$deletedclass?>">
                                    <td class="add-row no-border" colspan="9">
                                       <?php 
                                     
                                       if($create_type=='direct'){
                                       // if($master['invoice_type']=="POS" || $create_type=='direct'){
                                       ?>
                                          <button type="button" class="btn btn-crud btn-secondary <?=$addrow_class_update?>" aria-label="Left Align"
                                             id="row_btn">
                                          <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                          </button>
                                       <?php } ?>
                                       <div class="btn-group ml-1 mt-1" class="creditlimit-check"></div>
                                    </td>
                                 </tr>
                                 <tr class="sub_c" style="display: table-row;">
                                       <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Sub Total') ?>
                                                <span class="currenty lightMode"><?php //echo "(". $this->config->item('currency').")"; ?></span></strong>
                                       </td>
                                       <td align="right" colspan="2" class="no-border">
                                          <span id="grandamount"><?=number_format($subtotal,2)?></span>
                                       </td>
                                    </tr>
                                 <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="<?=$colspan?>" class="reverse_align no-border td-colspan">
                                       <input type="hidden" value="0" id="subttlform" name="subtotal">
                                       <strong><?php echo $this->lang->line('Total Tax') ?><?php //echo "(". $this->config->item('currency').")"; ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border"><span
                                       class="currenty lightMode"></span>
                                       <span id="taxr" class="lightMode"><?=number_format($totaltax,2)?></span>
                                    </td>
                                 </tr>
                                 <tr class="sub_c" style="display: table-row;">
                                    <td colspan="<?=$colspan?>" class="reverse_align no-border td-colspan">
                                       <strong><?php echo $this->lang->line('Total Product Discount');
                                       //"(".$this->config->item('currency').")"; ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border"><span class="currenty lightMode">
                                       <?php
                                       if (isset($_GET['project'])) {
                                          echo '<input type="hidden" value="' . intval($_GET['project']) . '" name="prjid">';
                                       } ?></span>
                                       <span id="discs" class="lightMode"><?=number_format($totaldiscount,2)?></span>
                                    </td>
                                 </tr>
                                 <tr class="sub_c" style="display: table-row;">
                                    <td colspan="<?=$colspan?>" class="reverse_align no-border td-colspan">
                                       <strong><?php echo $this->lang->line('Shipping') ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                       <input type="number" class="form-control shipVal text-right disable-class1"
                                       onkeypress="return isNumber(event)" placeholder="0.00" name="shipping" autocomplete="off" onkeyup="billUpyog()" value="<?=$master['shipping']?>" min="0" style="width:70%;" title="<?php echo $this->lang->line('Shipping') ?>" data-original-value="<?=$master['shipping']?>">
                                    </td>
                                 </tr>
                                 <tr class="sub_c" style="display: table-row;">
                                    <td colspan="<?=$colspan?>" align="right" class="no-border td-colspan">
                                       <strong><?php echo $this->lang->line('Order Discount') ?></strong></td>
                                    <td align="right" colspan="2" class="no-border">
                                    <?php 
                                       $master['order_discount'] = ($convert_type=='Multiple') ? $orderamount : $master['order_discount'];
                                    ?>

                                    <input type="number" class="form-control text-right <?=$disable_class?>" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$master['order_discount']?>" style="width:70%;" title="<?php echo $this->lang->line('Order Discount') ?>" data-original-value="<?=$master['order_discount']?>">
                                    
                                    </td>
                                 </tr>
                                 <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="<?=$colspan?>" class="reverse_align no-border td-colspan">
                                       <strong>
                                       <?php echo $this->lang->line('Extra') . ' ' . $this->lang->line('Discount') ?></strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border">
                                       <input type="text" class="form-control form-control-sm discVal"
                                          onkeypress="return isNumber(event)" placeholder="Value" name="disc_val"
                                          autocomplete="off" value="0" onkeyup="billUpyog()">
                                       <input type="hidden" name="after_disc" id="after_disc" value="0">
                                        <?php //echo $this->config->item('currency'); ?>
                                       <span id="disc_final">0</span> 
                                    </td>
                                 </tr>
                                 <tr class="sub_c" style="display: table-row;">
                                    <td colspan="9" class="reverse_align no-border td-colspan">
                                       <strong><?php echo $this->lang->line('Total') ?>
                                       <span
                                          class="currenty lightMode"><?php //echo "(".$this->config->item('currency').")"; ?></span></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                       <?php 
                                          $grand_nettotal = 0;
                                          
                                          $grand_nettotal = ($subtotal + $master['shipping']) -($totaldiscount + $master['order_discount']);
                                       ?>
                                       <span id="grandtotaltext"><?=number_format($grand_nettotal,2)?></span>
                                       <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly="" value="<?=$grand_nettotal?>">
                                       <input type="hidden" class="form-control text-right"   name="old_order_discount" id="old_order_discount" autocomplete="off"  value="<?=$master['order_discount']?>">
                                    </td>
                                 </tr>
                                 
                              </tbody>
                           </table>
                           <?php
                              if(is_array($custom_fields)){
                              echo'<div class="card">';
                                 foreach ($custom_fields as $row) {
                                       if ($row['f_type'] == 'text') { ?>
                                          <div class="row mt-1">
                                             <label class="col-sm-8" for="document_id"><?= $row['name'] ?></label>
                                             <div class="col-md-6 col-sm-12">
                                                <input type="text" placeholder="<?= $row['placeholder'] ?>"
                                                   class="form-control margin-bottom b_input <?= $row['other'] ?>"
                                                   name="custom[<?= $row['id'] ?>]">
                                             </div>
                                          </div>
                                          <?php }
                              }
                              echo'</div>';
                              }
                              ?>
                        </div>
                        <input type="hidden" class="form-control" placeholder="Billing Date" name="invoicedate" value="<?php echo date('Y-m-d'); ?>" readonly>
                        <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
                        <input type="hidden" value="new_i" id="inv_page">
                        <input type="hidden" value="invoices/action" id="action-url">
                        <input type="hidden" value="search" id="billtype">
                        <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                        <input type="hidden" value="<?= currency($this->aauth->get_user()->loc); ?>" name="currency">
                        <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
                        <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
                        <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
                        <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                           name="discountFormat" id="discount_format">
                        <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                           name="shipRate" id="ship_rate">
                        <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                           id="ship_taxtype">
                        <input type="hidden" value="0" name="ship_tax" id="ship_tax">
                        <input type="hidden" value="0" id="custom_discount">
                     </div>
                     <!-- ====================tab table ends================ -->

                     <!--================== Payment Details starts============ -->
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
                     <!--================== Delivery notes ends---============ -->
                     <!-- ===================Payments Received starts================== -->
                     <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                           <div class="row">
                              <div class="col-12">
                                    <!-- ===================================================== -->
                                    <div class="table-container overflow-auto">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th class='text-center' style="width:3%;"><?php echo $this->lang->line('SN') ?></th>
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
                                                      echo "<td class='text-center'>$i</td>
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
                                                                </a>&nbsp;";
                                                               //  echo "<button class='btn btn-sm btn-secondary' title='Delete'>
                                                               //      <span class='fa fa-trash'></span>
                                                               //  </button>";
                                                            echo "</td>";
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
                        <!-- ===================Payments Received Ends================== -->
                        <!-- ====================Journals Start==================== -->
                        <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="base-tab4">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p><?php echo $this->lang->line('Journals are') ?></p>
                                    <!-- ===================================================== -->
                                    <div class="table-container overflow-auto">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th class="text-center" style="width:3%;"><?php echo $this->lang->line('SN') ?></th>
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
                                                         echo "<td class='text-center'>$i</td>
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
                                    <div class="table-container overflow-auto">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th class='text-center' style="width:3%;"><?php echo $this->lang->line('SN') ?></th>
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
                                                      echo "<td class='text-center'>$i</td>
                                                            <td><a href='" . base_url('DeliveryNotes/create?id=' . $row['delivery_note_number']) . "'>".$row['delivery_note_number']."</a></td>
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
                  <!-- ======================== Tab conent section ends ================ -->
                  <hr>
                  <div class="row">
                     
                     <div class="col-lg-6 col-md-6 col-sm-12 col-12"> 
                        <input type="submit" class="btn btn-crud1 btn-secondary sub-btn btn-lg d-none1 <?=$savedraftbtn?> <?=$deletedclass?> <?=$savedraft_class?>" value="<?php echo $this->lang->line('Save As Draft') ?>" title="Save As Draft" id="invoice-preview-btn" data-loading-text="Creating...">
                        <?php 
                           if($invoice && $invoice['paymentstatus']!='Draft')
                           { 
                              echo '<input type="submit" class="btn btn-crud1 btn-lg btn-secondary cancelinvoice-btn '.$deletedclass.' '.$update_btns.'" value="'.$this->lang->line('Cancel Invoice').'" title="After canceling the invoice, all related transactions will be reverted, and the invoice can be reused as a draft.">';
                           }
                           
                           if( ($invoice && ($invoice['paymentstatus']!='Deleted') && (($invoice['paymentstatus']!='partial') && ($invoice['paymentstatus']!='paid'))))
                           {                             
                              echo '&nbsp;<input type="submit" class="btn btn-crud1 btn-lg btn-secondary deleteinvoice-btn '.$deletedclass.' '.$update_btns.' d-none" value="'.$this->lang->line('Delete Invoice').'" title="Invoice will be permanently deleted and cannot be reused.">';
                           }
                        ?>
                     </div>
                     <div class="col-lg-6 col-md-6 col-sm-12 col-12 responsive-text-right"> 
                                    
                           <input type="submit" class="btn btn-crud1 btn-secondary sub-btn btn-lg d-none" value="<?php echo $this->lang->line('Cancel & Add New') ?> " id="invoice-cancel-bt" data-loading-text="Creating...">
                           <input type="submit" class="btn btn-crud1 btn-secondary sub-btn btn-lg <?=$savedraftbtn?> <?=$deletedclass?> <?=$addnew_btns?>" value="<?php echo $this->lang->line('Confirm & Pay Now') ?> " id="confirm-paynow-btn" data-loading-text="Creating..." title="The invoice will be created and will directly navigate to the payment screen.">
                           
                           <input type="submit" class="btn btn-crud btn-primary sub-btn btn-lg <?=$savedraftbtn?> <?=$deletedclass?> <?=$addnew_btns?>" value="<?php echo $this->lang->line('Confirm & Add New') ?> " id="invoice-confirm-btn" data-loading-text="Creating..." title="Invoice created and redirected to the invoice list.">

                           <input type="submit" class="btn btn-crud1 btn-lg btn-primary sub-btn <?=$deletedclass?> <?=$update_btns?>" value="<?php echo $this->lang->line('Update') ?>" id="invoice-edit-btn"  data-loading-text="Updating...">
                     </div>
                  </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
   const changedFields = {};   
   let productCode;   
   let changedProducts = new Set();
   let wholeProducts = new Set();
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
   $(document).ready(function() 
   {

       //*** To redo for a common funtion -  May 31st, 2025 */
      // Approval levels starts 
            function approvals(module_number, function_number, approval_step) {
                Swal.fire({
                    title: 'Approve this request?',
                    input: 'textarea',
                    inputLabel: 'Approval Remarks',
                    inputPlaceholder: 'Enter your message or reason...',
                    inputAttributes: {
                        'aria-label': 'Approval message'
                    },
                    showCancelButton: true,  
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: "No - Cancel",
                    reverseButtons: true,  
                    focusCancel: true,      
                    allowOutsideClick: false,  // Disable outside click
                    preConfirm: (message) => {
                        // if (!message) {
                        //     Swal.showValidationMessage('Approval message is required');
                        // }
                        return message;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const message = result.value;

                        // Proceed with AJAX call
                        $.ajax({
                            url: baseurl +'quote/approvel_level_action',
                            type: 'POST',
                            data: {
                                module_number: module_number,
                                function_number: function_number,
                                approval_step: approval_step,
                                approval_comments: message,
                                target_url: "<?= getCurrentUrl() ?>",
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error submitting the approval.',
                                    'error'
                                );
                                console.error(error);
                            }
                        });
                    }
                });
            }

            var approvedLevels = <?php echo json_encode($approved_levels); ?>;
            var approval_permissions = <?= json_encode($my_approval_permissions[0]); ?>;

            // Initialize buttons
            $('.first_level, .second_level')
                .addClass('approval-disabled')
                .attr('title', 'You have no permission');

            // Enable buttons based on permissions
            if(approvedLevels)
            {
               if (approval_permissions.first_level_approval === 'Yes') {
                $('.first_level')
                    .removeClass('approval-disabled')
                    .attr('title', 'First Level Approval');
               }

               if (approval_permissions.second_level_approval === 'Yes') {
                  $('.second_level')
                     .removeClass('approval-disabled')
                     .attr('title', 'Second Level Approval');
               }
            }

            // Highlight approved levels and check for cancel button
            let shouldAddCancel = false;
            let hasFirstLevelApproved = false;
            if (approvedLevels && approvedLevels.length > 0) {
                approvedLevels.forEach(function(level) {
                    if (level.approval_step == 1) {
                        $('.first_level').addClass('highlighted');
                        hasFirstLevelApproved = true;
                        if (approval_permissions.first_level_approval === 'Yes') {
                            shouldAddCancel = true;
                        }
                    }
                    if (level.approval_step == 2) {
                        $('.second_level').addClass('highlighted');
                        if (approval_permissions.second_level_approval === 'Yes') {
                            shouldAddCancel = true;
                        }
                    }
                });
            }

            // Add Cancel button if conditions are met
            if (shouldAddCancel && $('.cancel_level').length === 0) {
                $('.breadcrumb-approvals').append(
                    '<li><a href="#" class="cancel_level breaklink" data-level="4">Cancel</a></li>'
                );
            }

            // Handle button clicks
            $(document).on('click', '.breadcrumb-approvals a', function(e) {
                e.preventDefault();                
                if ($(this).hasClass('approval-disabled')) {
                    return false;
                }
                
                const $button = $(this);
                const level = $button.data('level');
                
                if (level == 4) {
                    $('.approval-cancel-container').addClass('show');
                    return;
                }
                
                // Prevent approval for already highlighted levels
                if ($button.hasClass('highlighted')) {
                    $button.attr('title', 'This level is already approved');
                    return false;
                }
                function_number = $(".function_number").val();
                // Handle approval based on level
                switch(level) {
                    case 1:
                        // First level can always be approved if not highlighted
                        approvals('<?=$module_number?>',function_number, level);
                        break;
                        
                    case 2:
                        // Second level requires first level to be approved
                        if (!hasFirstLevelApproved) {
                        //   $('.second_level').attr('title', 'Second level approval is only possible after the first level is approved');
                        Swal.fire({
                            title: 'First Level is not Approved',
                            text: 'Second level approval is only possible after the first level is approved.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return false;
                        } else {
                            approvals('<?=$module_number?>',function_number, level);
                        }
                        break;
                }
            });
            // Close button handler
            $(document).on('click', '.approval-close-button', function(e) {
                e.preventDefault();
                $('.approval-cancel-container').removeClass('show');
            });

            $('.cancel-approved-btn').on('click', function(e) {
                e.preventDefault();
                $('.cancel-approved-btn').prop('disabled', true); 
        
                if ($("#approved_cancellation_form").valid()) {
                
                    var latest_level = ($("#latest_level").val() == 2) ?"Second Level" : "First Level";
                    var form = $('#approved_cancellation_form')[0];
                    var formData = new FormData(form);
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you want to cancel " + latest_level,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: "No - Cancel",
                        reverseButtons: true,
                        focusCancel: true,
                        allowOutsideClick: false, // Disable outside click
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                url: baseurl +'quote/approval_cancellation', 
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    location.reload();

                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Error',
                                        'An error occurred while cancelation',
                                        'error');
                                    console.log(error); // Log any errors
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('.cancel-approved-btn').prop('disabled', false);
                        }
                    });
                } else {
                    $('.cancel-approved-btn').prop('disabled', false);
                }
            });
      // approval levels ends redo ends

      var discountflag = <?=$discount_flg?>;
      if(discountflag==1){
         showdiscount_potion();
      } 

       document.querySelectorAll('[data-product-code]').forEach(function(element) {
         let productCode = element.getAttribute('data-product-code');
         // Add the productCode to the Set (duplicates will be automatically discarded)
         wholeProducts.add(productCode);
      });
      // Custom method to check if the bank deposit date is not greater than the current date
      // Add event listeners to all input fields
      document.querySelectorAll('input, textarea, select').forEach((input) => {
            input.addEventListener('change', function () {
                const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
                const originalValue = this.getAttribute('data-original-value');
                var label = $('label[for="' + fieldId + '"]');
                var field_label = label.text();
                // console.log();
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
                  if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue && productCode) {
                     changedProducts.add(productCode);
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
                if (originalLabel !== newValue && productCode) {
                    changedProducts.add(productCode);
                }
            }
                else {
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
                     if (originalValue !== newValue && productCode) {
                        changedProducts.add(productCode);
                     }
                }
            });
        });

        $('select').each(function () {
            if (!$(this).attr('multiple')) {
                 const selectedLabel = $(this).find(':selected').text();
                $(this).attr('data-original-label',selectedLabel);
            } else {
            // For multi-select, get all selected options text and join them with a comma
            const selectedLabels = $(this)
                .find(':selected')
                .map(function () {
                return $(this).text();
                })
                .get()
                .join(', ');
            $(this).attr('data-original-label', selectedLabels);
            }
        });
      //   $('#confirm-paynow-btn').removeClass('disable-class');
        $('.creditlimit-alert').addClass('d-none');
         $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel,{
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {               
                 cst: {
                     required: function() {
                        return $('#customer_id').val() == 0;
                     }
                  },
                s_warehouses: { required: true },
                refer: { required: true },
                invocieduedate: { required: true }
            },
            messages: {
                invocieduedate: "Enter Invoice Due Date",
                refer: "Enter Internal Reference",
                s_warehouses: "Select Warehous/shop",
                cst: "Select Customer",
            }
        }));

        $('#invoice-preview-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#invoice-preview-btn').prop('disabled', true);
         
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });
            

            // Validate the form
            if ($("#data_form").valid()) {  
               $('#invoice-preview-btn').prop('disabled', false);
               if (selectedProducts1.length === 0) {
                  Swal.fire({
                  text: "To proceed, please add  at least one item",
                  icon: "info"
               });
               
                  return;
               }          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form);
                formData.append('changedFields', JSON.stringify(changedFields));                
                formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
                formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
                formData.append('stage', 'preview');                        
               $.ajax({
                     url: baseurl + 'invoices/save_as_draft_action',
                  //  url: baseurl + 'invoices/invoice_preview_action',
                     type: 'POST',
                     data: formData,
                     contentType: false, 
                     processData: false,
                     success: function(response) {
                        if (typeof response === "string") {
                           response = JSON.parse(response);
                        }
                        // window.open(baseurl + 'invoices/create?id='+response.id); 
                        // window.location.href = response.link;    
                        window.location.href = 'create?id='+response.id; 
                     },
                     error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                     }
               });
            } else {
                // If form validation fails, re-enable the button
                $('.page-header-data-section').css('display','block');
                $('#invoice-preview-btn').prop('disabled', false);
            }
        });
       
        $('.cancelinvoice-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('.cancelinvoice-btn').prop('disabled', true);

            Swal.fire({
               title: "Are you sure?",
               html: `
                     <p>Do you want to cancel the invoice?</p>
                     <textarea id="cancel_reason" class="form-textarea" style="width: 100%; box-sizing: border-box;" placeholder="Enter reason for cancellation" rows="4"></textarea>
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


         $('#invoice-confirm-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#invoice-confirm-btn').prop('disabled', false);
            // $('#invoice-confirm-btn').prop('disabled', true);
         
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });           

            if ($('.payment-type-radio:checked').val() === 'Customer Credit') {
               var available_credit = parseFloat($('#available_credit').val()) || 0;
               var totalbillamount = parseFloat($('#invoiceyoghtml').val()) || 0; 
               $('.creditlimit-alert').addClass('d-none');
               if (totalbillamount > available_credit) {
                  $('.creditlimit-alert').removeClass('d-none');
                  $('#invoice-confirm-btn').prop('disabled', false);
                  return;
               }
            }

            // Validate the form
            if ($("#data_form").valid()) {      
               if (selectedProducts1.length === 0) {
                  Swal.fire({
                  text: "To proceed, please add  at least one item",
                  icon: "info"
               });
               $('#invoice-confirm-btn').prop('disabled', false);
                  return;
               }          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); 
                formData.append('changedFields', JSON.stringify(changedFields));                
                formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
                formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create new invoice?",
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
                            url: baseurl + 'invoices/action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                              //   window.open(baseurl + 'invoices'); 
                                window.location.href = baseurl + 'invoices';                              
                              //   window.location.href = response.link;                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#invoice-confirm-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('.page-header-data-section').css('display','block');
                $('#invoice-confirm-btn').prop('disabled', false);
            }
         });


         $('#confirm-paynow-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#confirm-paynow-btn').prop('disabled', true);
            
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });
            
            if ($('.payment-type-radio:checked').val() === 'Customer Credit') {
               var available_credit = parseFloat($('#available_credit').val()) || 0;
               var totalbillamount = parseFloat($('#invoiceyoghtml').val()) || 0; 
               $('.creditlimit-alert').addClass('d-none');
               if (totalbillamount > available_credit) {
                  $('.creditlimit-alert').removeClass('d-none');
                  $('#iconfirm-paynow-btn').prop('disabled', false);
                  return;
               }
            }
            // Validate the form
            if ($("#data_form").valid()) {      
               if (selectedProducts1.length === 0) {
                  Swal.fire({
                  text: "To proceed, please add  at least one item",
                  icon: "info"
               });
               $('#confirm-paynow-btn').prop('disabled', false);
                  return;
               }          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); 
                formData.append('changedFields', JSON.stringify(changedFields));                
                formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
                formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create new invoice and pay now?",
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
                            url: baseurl + 'invoices/action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                              //   console.log(response);
                                invoice_id = response.id;       
                                var csd = $("#customer_id").val(); 
                                window.open(baseurl + 'invoices/customer_payment?id='+invoice_id+'&csd='+csd);                       
                                window.location.href = response.link;                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#confirm-paynow-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('.page-header-data-section').css('display','block');
                $('#confirm-paynow-btn').prop('disabled', false);
            }
        });


   });

   
function loadPopover(index) {
    const popoverButton = $('#btnclk-' + index);    
    // Set up popover content and show it
    popoverButton.popover('show');

    // AJAX request to load options based on the product code
    $.ajax({
        url: baseurl + 'invoices/load_product_accounts',
        method: 'POST',
        dataType: 'json',
        data: {
            'actheader': 'Income',
            'accountnumber':$('#income_account_number-'+index).val()
        },
        success: function(response) {
            if (response.status === 'Success') {
                const accountList = $('#accountList-' + index);
                accountList.empty(); // Clear any existing options
                accountList.html(response.data);
               
            } else {
                alert('Failed to load options');
            }
        },
        error: function() {
            alert('Error loading options');
        }
    });
}

// Function to handle save action within popover form
function change_product_account(index) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change the product account?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            var account_selected = $("#accountList-" + index).val();
            $("#income_account_number-" + index).val(account_selected);
            $('#btnclk-' + index).popover('hide');
        }
        else{
            $('#btnclk-' + index).popover('show');
        }
    });
}

function cancelPopover(index) {
    $('#btnclk-' + index).popover('hide');
}

$('.payment-type-radio').on('change', function () {
   if ($(this).val() === 'Customer Credit' && $(this).is(':checked')) {
      $('#confirm-paynow-btn').addClass('disable-class');
   } else {
      $('#confirm-paynow-btn').removeClass('disable-class');
   }
});


$('.cancelinvoice-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.cancelinvoice-btn').prop('disabled', true);

    var selectedProducts1 = [];
    $('.code').each(function() {
        if ($(this).val() !== "") {
            selectedProducts1.push($(this).val());
        }
    });

    // Validate the form

    if (selectedProducts1.length === 0) {
        Swal.fire({
            text: "To proceed, please add at least one item",
            icon: "info"
        });
        $('.cancelinvoice-btn').prop('disabled', false);
        return;
    }          

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to cancel the invoice?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,  
        focusCancel: true,      
        allowOutsideClick: false  // Disable outside click
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurl + 'invoices/cancelinvoiceaction', // Replace with your server endpoint
                type: 'POST',
                data: {
                    'invoiceid': $("#invoiceid").val()
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
$('.deleteinvoice-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.deleteinvoice-btn').prop('disabled', true);

    var selectedProducts1 = [];
    $('.code').each(function() {
        if ($(this).val() !== "") {
            selectedProducts1.push($(this).val());
        }
    });

    // Validate the form

    if (selectedProducts1.length === 0) {
        Swal.fire({
            text: "To proceed, please add at least one item",
            icon: "info"
        });
        $('.deleteinvoice-btn').prop('disabled', false);
        return;
    }          

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to cancel the invoice?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,  
        focusCancel: true,      
        allowOutsideClick: false  // Disable outside click
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurl + 'invoices/deleteinvoiceaction', // Replace with your server endpoint
                type: 'POST',
                data: {
                    'invoiceid': $("#invoice_id").val(),
                    'invoice_number': $("#invoice_number").val(),
                },
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'Success') {
                            window.location.href = baseurl + 'invoices';
                    } else {
                        Swal.fire('Error', 'Failed to delete the invoice', 'error');
                        $('.deleteinvoice-btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while cancelling the invoice', 'error');
                    console.log(error); // Log any errors
                    $('.deleteinvoice-btn').prop('disabled', false);
                }
            });
        } else {
            // Re-enable the button if the user cancels
            $('.deleteinvoice-btn').prop('disabled', false);
        }
    });
   
});

$('#invoice-edit-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#invoice-edit-btn').prop('disabled', true);
    
    var selectedProducts1 = [];
    $('.code').each(function() {
        if($(this).val()!="")
        {
            selectedProducts1.push($(this).val());
        }
    });
    

    // Validate the form
    if ($("#data_form").valid()) {      
        if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please add  at least one item",
            icon: "info"
        });
        $('#invoice-edit-btn').prop('disabled', false);
            return;
        }          
        var form = $('#data_form')[0]; // Get the form element
        var formData = new FormData(form); 
        formData.append('changedFields', JSON.stringify(changedFields));                
        formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
        formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to update invoice?",
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
                    url: baseurl + 'invoices/editaction', // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        location.reload();
                        // window.location.href = baseurl + 'invoices';                
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Enable the button again if user cancels
                $('#invoice-edit-btn').prop('disabled', false);
            }
        });
    } else {
        // If form validation fails, re-enable the button
        $('.page-header-data-section').css('display','block');
        $('#invoice-edit-btn').prop('disabled', false);
    }
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
                           <?php //echo $this->config->item('currency') ?>
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