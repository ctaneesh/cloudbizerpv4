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
            $prefix = $prefix['po_prefix'];
            $purchase_number =  $this->lang->line('Add New');
            $purchase_order_number =  "";
            // $purchase_number = (!empty($invoice['purchase_number'])) ? $invoice['purchase_number'] : $prefix.$lastinvoice+1000;
            if($invoice['purchase_number'])
            {
              $function_number = $invoice['purchase_number'];
              $purchase_number = $invoice['purchase_number'];
              $purchase_order_number = $purchase_number;
            }
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('purchase') ?>"><?php echo $this->lang->line('Purchase Orders') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"> <?php echo $purchase_number ?></li>
                </ol>
            </nav>
           
            
            <div class="row">
               <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                  <h4 class="card-title"><?php echo $purchase_number ?> </h4>            
               </div>
               <div class="col-xl-7 col-lg-9 col-md-6 col-sm-12 col-xs-12">  
                  <?php 
                  $validtoken = hash_hmac('ripemd160', 'p' . $invoice['iid'], $this->config->item('encryption_key'));
                  $generatebtn = "";
                  $approvebtn="";
                  $acceptbtn="";
                  $preparedbtn="";
                  $assigncls="disable-class";
                  if($invoice['prepared_flag']==1)
                  {
                     $preparedbtn="d-none";
                     $required = "";
                     $compulsory = '';
                     // $required = "required";
                     // $compulsory = '<span class="compulsoryfld">*</span>';
                     $assigncls="";
                  }
                  if($invoice['prepared_flag']!=1 || $invoice['order_status'] =='Sent')
                  {
                     $approvebtn="d-none";
                     $acceptbtn="d-none";
                  }
                  if($invoice['approval_flag']==1)
                  {
                     $approvebtn="d-none";
                     // $acceptbtn="d-none";
                  }

                  
                 

                  $frmelmentdisable = "";
                  $frmselectdisable = "";
                  $accpetthenhide = "";
                  $frmbtndisable="";
                  // Using switch to handle different conditions
                  switch (true) {
                     case ($invoice['receipt_status'] == "1"):
                        $frmelmentdisable = "readonly";
                        $frmselectdisable = "textarea-bg disable-class";
                        $accpetthenhide = "disable-class";
                        $frmbtndisable= "disabled";
                        $fully_received_class="disable-class";
                        break;
                     case ($invoice['order_status'] == "Dummy"):
                           $frmelmentdisable = "readonly";
                           $frmselectdisable = "textarea-bg disable-class";
                           $accpetthenhide = "disable-class";
                           $frmbtndisable= "disabled";
                        break;
                      case ($invoice['order_status'] == 'Sent'):
                          $frmelmentdisable = "readonly";
                          $frmselectdisable = "textarea-bg disable-class";
                          $accpetthenhide = "disable-class";
                          $frmbtndisable= "disabled";
                          break;
                  
                      case ($invoice['approval_flag'] == '1' && $invoice['prepared_flag'] == '1' && $invoice['approved_by'] != $this->session->userdata('id')):
                          $frmelmentdisable = "readonly";
                          $frmselectdisable = "textarea-bg disable-class";
                          $frmbtndisable= "disabled";
                          break;
                  
                      default:
                          $frmelmentdisable = "";
                          $frmselectdisable = "";
                          $frmbtndisable="";
                          break;
                  }
                     $msgcls = "";
                     $messagetext = "";
                     $enabledisablecls="";
                     $marginbottom = "mb-2";
                     $assignseccls = "";
                     $acceptsendbtncls="";
                     $statustext ="";  
                     $hidedraftbtn = "";                  
                     switch (true) {
                        case ($invoice['order_status'] == "Dummy"):
                           $messagetext = "No manual action is needed—this is generated automatically during product creation.";
                           $statustext = "Dummy";
                           $msgcls = "alert-secondary";
                           $enabledisablecls ="d-none";
                        break;
                        case ($invoice['order_status'] == "Draft"):
                           $messagetext = "Data Saved As Draft";
                           $statustext = "Draft";
                           $enabledisablecls ="";                           
                           $msgcls = "alert-secondary";
                           $acceptsendbtncls ="d-none";
                           break;
                        case ($invoice['prepared_flag'] == 0 &&  $invoice['order_status'] != "Draft"):
                           $msgcls = "d-none";
                           $enabledisablecls ="d-none";
                           $marginbottom = "";
                           $assignseccls = "d-none";
                           break;

                        case ($invoice['approval_flag'] != 1 && $invoice['prepared_flag'] == 1):
                           $messagetext = "";
                           // $messagetext = "Created & Waiting for approval";
                           $statustext = "Created";                      
                           $msgcls = "alert-partial";
                           $enabledisablecls ="";
                           $acceptsendbtncls ="d-none";
                           break;
                        case ($invoice['approval_flag'] == 1 &&  $invoice['order_status'] == "Draft"):
                           $messagetext = "Data Saved As Draft";
                           $statustext = "Draft";
                           $enabledisablecls ="";                           
                           $msgcls = "alert-success";
                           $acceptsendbtncls ="d-none";
                           break;
                         case ($invoice['approval_flag'] != 1 &&  $invoice['order_status'] == "Draft"):
                              $messagetext = "Data Saved As Draft";
                              $statustext = "Draft";
                              $msgcls = "alert-secondary";
                              $enabledisablecls ="disable-class";
                              break;
                        case ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['order_status'] == "Assigned"):
                           $messagetext = "Please Accept the Purchase Order";
                           $statustext = "Assigned";
                           $msgcls = "alert-warning";
                           $enabledisablecls ="disable-class";
                           break;
                        case ($invoice['approval_flag']=="1" && $invoice['order_status'] == "Assigned"):
                           $messagetext = " The Purchase Order has been approved. Approved Date : ".date('d-m-Y h:i:s A', strtotime($invoice['approved_date']));
                           $statustext = "Approved";
                           $msgcls = "alert-warning";
                           $hidedraftbtn = "d-none";
                           break;
                        // case ($invoice['approved_by']==$this->session->userdata('id') && $invoice['order_status'] == "Assigned"):
                        //    $messagetext = $assignedperson['name']." has not Sent this purchase order yet. Assigned Date : ".date('d-m-Y h:i:s A', strtotime($invoice['approved_date']));
                        //    break;
                        case ($invoice['approval_flag']=="1" && $invoice['order_status'] == "Pending"):
                           $messagetext = " The Purchase Order has been approved. Approved Date : ".date('d-m-Y h:i:s A', strtotime($invoice['approved_date']));
                           $statustext = "Created";                      
                           $msgcls = "alert-partial";
                           $hidedraftbtn = "d-none";
                           break;

                        case ($invoice['order_status'] == "Sent"):
                           $messagetext = "The Purchase Order has been Sent";
                           $statustext = "Sent";
                           $msgcls = "alert-success";
                           $enabledisablecls ="";
                           break;
                        case ($invoice['order_status'] == "Reverted"):
                           $messagetext = "Purchase Order Reverted. Now you can Reassign & Update  or Send Purchase Order from here";
                           $statustext = "Reverted";
                           $msgcls = "alert-danger";
                           $enabledisablecls ="";
                           break;

                        default:
                           // No action needed for the default case
                           break;
                     }
                     ?>    
                    <ul id="trackingbar">
                     <?php 
           
                        if (!empty($trackingdata)) {     
                            if (!empty($trackingdata['purchase_order_number'])) { 
                                echo '<li class="active">' . $trackingdata['purchase_order_number'] . '</li>';
                            } 
                            else{
                              echo '<li class="active">' . $purchase_number . '</li>';
                            }
                            if (!empty($trackingdata['purchase_reciept_id'])) { 
                                  echo '<li><a href="' . base_url('Invoices/costing?id=' . $trackingdata['purchase_reciept_number']) . '&token='.$validtoken.'">' . $trackingdata['purchase_reciept_number'] . '</a></li>';
                            }
                            
                            if (!empty($trackingdata['purchase_reciept_return_number'])) { 
                              $validtoken1 = hash_hmac('ripemd160', 'p' . $trackingdata['purchase_reciept_return_number'], $this->config->item('encryption_key'));
                              echo '<li><a href="' . base_url('purchasereturns/create?pid=' . $trackingdata['purchase_reciept_return_number']).'&token='.$validtoken1.'">' . $trackingdata['purchase_reciept_return_number'] . '</a></li>';
                            }
                            
                        }
                    ?>                        
                  </ul> 
                  <!-- ========================================= -->
               </div>

               <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 current-status">                  
                     <div class="btn-group alert text-center <?=$msgcls?>" role="alert">
                        <?php echo $statustext; ?>
                     </div>
               </div>
               <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
               <?php
               if($purchase_order_number){ 
                  if ($invoice['order_status'] == "Sent"){ ?>
                     <script>
                        setTimeout(function () {
                              $('.breadcrumb-approvals .cancel_level[data-level="4"]').closest('li').remove();
                        }, 1000);
                     </script>
                  <?php } ?>
                  <ul class="breadcrumb-approvals">
                     <li><a href="#" class="first_level breaklink" data-level="1"><?php echo $this->lang->line('First Level');  ?></a></li>
                     <li><a href="#" class="second_level breaklink" data-level="2"><?php echo $this->lang->line('Second Level');  ?></a></li> 
                  </ul>
                <?php } ?>
               <div class="heading-elements">
                     <ul class="list-inline mb-0">
                        <?php
                           if($invoice && ($invoice['approval_flag'] == 1 && $invoice['prepared_flag'] == 1))
                           {
                              $approval_message = '<span class="btn-sm alert alert-sm alert-success">'.$this->lang->line('Approved Level').'</span>';
                           }
                           else if($invoice && ($invoice['approval_flag'] != 1 && $invoice['prepared_flag'] == 1) && $invoice['order_status'] != "Draft")
                           {
                              $approval_message = '<span class="btn-sm alert alert-sm alert-danger">'.$this->lang->line('Approval Pending').'</span>';
                           }
                           else{
                              $approval_message ="";
                           }
                        ?>
                        <!-- <li><?=$approval_message?></li> -->
                        <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                     </ul>
               </div>
            </div>

        <?php //approval cancelation section starts
        if($purchase_order_number)
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

            <!-- ========================= Buttons start ===================== -->
            <div class="row wrapper white-bg page-heading <?=$marginbottom?>">
               <div class="col-lg-3 col-md-3 col-sm-12">
                  
                  <?php
                     
                     $link = base_url('billing/purchase?id=' . $invoice['iid'] . '&token=' . $validtoken);
                     if ($invoice['status'] != 'canceled') { ?>
                  <div class="title-action">

                    
                     <!-- <a href='<?= base_url("purchase/purchase_order_payment?id=" . $invoice['iid'] . "&csd=" . $invoice['customer_id']) ?>' class="btn btn-sm  btn-secondary  <?php echo $enabledisablecls; ?>" title="Partial Payment"><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a> -->

                     <!-- <div class="btn-group">
                        <button type="button" class="btn btn-crud btn-sm btn-secondary dropdown-toggle <?php echo $enabledisablecls; ?>" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                        <span class="fa fa-envelope-o"></span> <?php echo $this->lang->line('Send') ?>
                        </button>
                        <div class="dropdown-menu"><a href="#sendEmail" data-toggle="modal"
                           data-remote="false" class="dropdown-item sendbill <?php echo $enabledisablecls; ?>"
                           data-type="purchase"  ><?php echo $this->lang->line('Purchase Request') ?></a>
                        </div>
                     </div> -->
                     <a href="#sendEmail" data-toggle="modal" 
                           data-remote="false" class="btn btn-crud btn-sm btn-secondary sendbill <?php echo $enabledisablecls." ".$fully_received_class ?>"
                           data-type="purchase" ><span class="fa fa-envelope-o"></span> <?php echo $this->lang->line('Email') ?></a>

                     <a href="#sendSMS" data-toggle="modal" data-remote="false"
                        class="btn btn-crud btn-sm btn-secondary <?php echo $enabledisablecls." ".$fully_received_class; ?>" title="SMS"
                        ><span class="fa fa-mobile"></span> <?php echo $this->lang->line('SMS') ?></a>


                     
                     <a href="#pop_model" data-toggle="modal" data-remote="false" class="btn btn-sm btn-secondary d-none <?php echo $enabledisablecls; ?>" title="Change Status"><span class="fa fa-retweet"></span> <?php echo $this->lang->line('Change Status') ?></a>
                     <a href="#cancel-bill" class="btn btn-sm btn-secondary d-none <?php echo $enabledisablecls; ?>" id="cancel-bill_p"><i class="fa fa-minus-circle"> </i> <?php echo $this->lang->line('Cancel') ?>
                     </a>
                     <?php 
                     $purchase_receiptbtn = "";
                     if($invoice['order_status'] =='Sent')
                     { 
                        $purchase_receiptbtn = '<a class="btn btn-lg btn-crud btn-primary '.$fully_received_class.'" href="' . base_url('Invoices/costing?pid=' . $invoice['purchase_number'] . '&token=' . $validtoken) . '">' . $this->lang->line('Purchase Receipt') . '</a>';

                        ?>
                           <a  class="btn btn-crud btn-sm btn-secondary <?php echo $enabledisablecls." ".$fully_received_class; ?>"  href="<?= base_url('Invoices/costing?pid=' . $invoice['purchase_number'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Purchase Receipt') ?></a>

                            
                    <?php } ?>
                    
                  </div>
                  <?php
                     if ($invoice['multi'] > 0) {
                     
                         echo '<div class="tag tag-info text-xs-center mt-2">' . $this->lang->line('Payment currency is different') . '</div>';
                     }
                     } else {
                         echo '<h2 class="btn btn-crud btn-oval btn-secondary">' . $this->lang->line('Cancelled') . '</h2>';
                     } ?>
               </div>
               <div class="col-lg-6 col-md-6 col-sm-12 text-center messagetext_class">
                  <?php
                     if($messagetext)
                     {?>
                     <div class="btn-group alert text-center alert-success" role="alert">
                        <?php echo $messagetext; ?>
                     </div>
                     <?php } ?>
               </div>
               <div class="col-lg-3 col-md-5 col-sm-12 text-lg-right text-md-right text-sm-left">
                     <a href="<?php echo $link; ?>" class="d-none btn btn-sm btn-secondary <?=$assignseccls?>"  target="_blank"><i
                        class="fa fa-globe"></i> <?php echo $this->lang->line('Public Preview') ?>
                     </a>
                     <div class="btn-group ">
                            <button type="button" class="btn btn-crud btn-sm btn-secondary btn-min-width dropdown-toggle <?php echo $enabledisablecls; ?>"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="fa fa-print"></i> <?php echo $this->lang->line('Print') ?>
                            </button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item" target="_blank" href="<?= base_url('billing/printorder?id=' . $invoice['purchase_number'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Print') ?></a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                href="<?= base_url('billing/printorder?id=' . $invoice['purchase_number'] . '&token=' . $validtoken); ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>
                            </div>
                     </div>
               </div>
            </div>
            <!-- ========================= Buttons start ===================== -->
            <form method="post" id="data_form" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="iid" value="<?=$invoice['iid']?>">            
            <input type="hidden" name="function_number" class="function_number" value="<?=$function_number?>">
            <input type="hidden" name="order_status" value="<?=$invoice['order_status']?>">

            
                  <?php
                  $duedate = (!empty($invoice['duedate']) && $invoice['duedate'] != '0000-00-00') 
                     ? $invoice['duedate'] 
                     : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['quote_validity'] . " days"));
                  // $term = ($invoice['term'])?$invoice['term']:$validity['payment_terms'];
                  $customer_id = $invoice['customer_id'];
                  $employee_id = $created_employee['id']; 
                  $headerclass= "d-none";
                  $edit_customer_btn = "d-none";
                  $pageclass= "page-header-data-section-dblock";
                  if($poid)
                  {
                        $headerclass = "page-header-data-section-dblock";
                        $pageclass   = "page-header-data-section";
                        $edit_customer_btn = "";
                  }
                
                  ?>

                  <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                     <div class="row">
                           <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                              <h3  class="title-sub"><?php echo $this->lang->line('Purchase Order & Supplier Details') ?> <i class="fa fa-angle-down"></i></h3>
                           </div>
                           <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 responsive-text-right quickview-scroll order-1 order-lg-2">
                              <div class="quick-view-section">
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Supplier') ?></h4>
                                       <?php //echo "<b>".$invoice['name']."</b>"; ?>                                            
                                       <?php
                                       echo "<a class='expand-link' href='" . base_url('supplier/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($invoice['name']) . "</b></a>";
                                       ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Created') ?></h4>
                                       <?php echo "<p>".dateformat($invoice['invoicedate'])."</p>"; ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Validity') ?></h4>
                                       <?php echo "<p style='color:".$colorcode."'>".dateformat($duedate)."</p>"; ?>
                                    </div>
                                    
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Created By') ?></h4>
                                       <?php 
                                          echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                       ?>
                                    </div>
                                    <div class="item-class text-center">
                                       <h4><?php echo $this->lang->line('Total'); ?></h4>
                                       <?php echo "<p>".number_format($invoice['order_total'],2)."</p>";?>
                                    </div>
                              </div>
                           </div>
                     </div>
                  </div>

                  
                  <div class="<?=$pageclass?>">
                     <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12 cmp-pnl">
                           <div id="customerpanel" class="inner-cmp-pnl">
                              <div class="form-group row">
                                 <div class="col-sm-12">
                                    <h3 class="title-sub"><?php echo $this->lang->line('Supplier Details');
                                     $customer_search_section = ($invoice['customer_id']>0) ? "d-none" : "";
                                    ?></h3><hr>
                                 </div>
                                 <div class="frmSearch col-sm-12 customer-search-section <?=$customer_search_section?>">
                                    <!-- <label for="cst"class="col-form-label"><?php //echo $this->lang->line('Search Supplier') ?><span class="compulsoryfld">*</span> </label> -->

                                    <label for="customer_name" class="frmSearch customer-search-section1 <?=$customer_search_section?> d-flex justify-content-between align-items-center" id="customerLabel" > <span><?php echo $this->lang->line('Search Supplier') ?> <span class="compulsoryfld">*</span></span>
                                    <input type="button" value="Add New Supplier" class="btn btn-sm btn-secondary btn-crud add_supplier_btn" autocomplete="off" title="Add New Supplier" >
                                    </label>


                                    <input type="text" class="form-control" name="cst" id="supplier-box" title="<?php echo $this->lang->line('Search Supplier') ?>"
                                       placeholder="Enter Supplier Name or Mobile Number to search"
                                       autocomplete="off" <?=$frmelmentdisable?>/>
                                    <div id="supplier-box-result"></div>
                                 </div>
                              </div>
                              <div id="customer">
                                 <?php
                                     echo '<div class="existingcustomer_details">';
                                     echo '<div class="clientinfo">
                                     <div id="customer_name"><strong>' . $invoice['name'] . '</strong><button type="button" class="btn btn-sm btn-crud btn-secondary ml-1 searchsectionedit '.$edit_customer_btn.' '.$fully_received_class.'">'.$this->lang->line("Supplier Edit").'</button><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectioncancel d-none">'.$this->lang->line("Customer Cancel").'</button></div></div></div>';
                                 ?>
                                 <div class="clientinfo">
                                    <?php 
                                 
                                       if($invoice['customer_id']>0)
                                       {
                                          $phone = "Phone:";
                                          $email = "Email:";
                                          $coma = ',';
                                       }
                                       $csd = ($invoice['customer_id']>0)? $invoice['customer_id']:0;
                                       $invoicetid = $lastinvoice + 1000;
                                       if(!empty($invoice['tid'])){
                                          $invoicetid = $invoice['tid'];
                                       }
                                    $invoicedate = (!empty($invoice['invoicedate']))?$invoice['invoicedate']:date('Y-m-d H:i:s');
                                    ?>
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?=$csd?>" data-original-value="<?=$csd?>">
                                    <div id="customer_name">
                                             <?php echo '  
                                               
                                             </div>
                                             <div class="clientinfo">

                                                <div id="customer_address1"><strong>' . $invoice['address'] . '<br>' . $invoice['city']  .$coma. $invoice['countryname'] . '</strong></div>
                                             </div>

                                             <div class="clientinfo">

                                                <div type="text" id="customer_phone">'.$phone.' <strong>' . $invoice['phone'] . '</strong><br>'.$email.' <strong>' . $invoice['email'] . '</strong></div>
                                             </div>'; ?>
                                 </div>
                                 <div class="clientinfo">
                                    <div id="customer_address1"></div>
                                 </div>
                                 <div class="clientinfo">
                                    <div type="text" id="customer_phone"></div>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 
                                 
                              </div>
                              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 row d-none">
                                    <label for="toAddInfo" class="col-form-label"></label>
                                    <button type="button" class="btn btn-crud btn-sm btn-secondary mt-3" id="attachment-btn"><i class="fa fa-paperclip" aria-hidden="true"></i> Add Attachment</button>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-xs-12 cmp-pnl">
                           <div class="inner-cmp-pnl">
                              <div class="form-row">
                                 <div class="col-sm-12">
                                    <h3  class="title-sub"><?php echo $this->lang->line('Purchase Order Details') ?> </h3><hr>
                                 </div>

                                 <input type="hidden" name="po_id" id="po_id" value="<?php echo $poid; ?>">
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="invocieno"
                                       class="col-form-label"><?php echo $this->lang->line('Order Number') ?> </label>
                                    <div class="input-group">
                                       <div class="input-group-addon"><span class="icon-file-text-o"
                                          aria-hidden="true"></span></div>                                 
                                       <input type="text" class="form-control" placeholder="Purchase Order #" id="purchase_number" name="purchase_number" value="<?php echo $purchase_order_number; ?>" readonly>
                                    </div>
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="reference"
                                       class="col-form-label" ><?php echo $this->lang->line('Reference') ?><span class="compulsoryfld">*</span> </label>
                                       <input type="hidden" class="form-control" placeholder="Purchase Order #" name="invocieno" value="<?php echo $invoicetid; ?>" readonly title="<?php echo $this->lang->line('Reference') ?>">
                                       <input type="text" class="form-control" placeholder="Reference #" name="refer" id="reference"  value="<?php echo $invoice['internal_reference'] ?>" data-original-value="<?php echo $invoice['internal_reference'] ?>" <?=$frmelmentdisable?>>
                                 </div>

                                 <!--erp2024 newly added 29-09-2024 ends -->
                                 
                                 <input type="hidden" class="form-control required" placeholder="Billing Date" name="invoicedate" value="<?=$invoicedate?>">
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="tsn_due1" class="col-form-label">
                                       <?php 
                                       $duedate = (!empty($invoice['duedate']) && $invoice['duedate'] != '0000-00-00') 
                                       ? $invoice['duedate'] 
                                       : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['purchase_order_validity'] . " days"));
                                       echo $this->lang->line('PO Due Date') ?><span class="compulsoryfld">*</span></label>
                                       <input type="date" class="form-control" id="tsn_due1" name="invocieduedate" placeholder="Due Date" min="<?=date('Y-m-d')?>"  value="<?php echo $duedate; ?>" data-original-value="<?php echo $invoice['duedate']; ?>" <?=$frmelmentdisable?> title="<?=$this->lang->line('PO Due Date')?>">
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="doc_type"   class="col-form-label"><?php echo $this->lang->line('Payment Type') ?></label><label><span class="compulsoryfld">*</span></label>
                                       <select name="doc_type" id="doc_type" class="form-control required <?=$frmselectdisable?>" required <?=$frmelmentdisable?> data-original-value="<?php echo $invoice['doc_type'] ?>" title="<?=$this->lang->line('Payment Type')?>">
                                          <!-- <option value="">Select Document Type</option> -->
                                          <option value="Local Cash Purchase" <?php if($invoice['doc_type']=='Local Cash Purchase') { echo "selected";} ?>>Local Cash Purchase</option>
                                          <option value="Local Credit Purchase" <?php if($invoice['doc_type']=='Local Credit Purchase') { echo "selected";} ?>>Local Credit Purchase</option>
                                          <option value="International Purchase" <?php if($invoice['doc_type']=='International Purchase') { echo "selected";} ?>>International Purchase</option>
                                       </select>
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="currency_id" class="col-form-label"><?php echo $this->lang->line('Currency') ?></label><label><span class="compulsoryfld">*</span></label>
                                       <select name="currency_id" id="currency_id" class="form-control required <?=$frmselectdisable?>" required <?=$frmelmentdisable?> data-original-value="<?=$invoice['currency_id']?>" title="<?=$this->lang->line('Currency')?>">
                                          <!-- <option value="">Select Currency</option> -->
                                          <?php
                                             foreach($currencies as $currency){
                                                $sel="";
                                                if($invoice['currency_id']==$currency['id']){
                                                   $sel="selected";
                                                }
                                                if(strtolower($currency['symbol']) == 'qar')
                                                {
                                                   echo "<option value='".$currency['id']."'>".$currency['code']."</option>";
                                                }
                                             }
                                             ?>
                                       </select>
                                 </div>

                                 <!--erp2024 newly added 29-09-2024  -->
                                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                       <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Supplier Reference Number'); ?></label>
                                       <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Supplier Reference Number" value="<?php echo $invoice['customer_reference'] ?>" data-original-value="<?php echo $invoice['customer_reference'] ?>" <?=$frmelmentdisable?> title="<?=$this->lang->line('Supplier Reference Number')?>">
                                       </div>                                    
                                 </div>
                                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                       <div class="frmclasss"><label for="customer_contact_person" class="col-form-label"><?php echo $this->lang->line('Supplier Contact Person'); ?></label>
                                       <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Supplier Contact Person" value="<?php echo $invoice['customer_contact_person'] ?>"  data-original-value="<?php echo $invoice['customer_contact_person'] ?>" <?=$frmelmentdisable?> title="<?=$this->lang->line('Supplier Contact Person')?>">
                                       </div>                                    
                                 </div>
                                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                       <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                       <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number" value="<?php echo $invoice['customer_contact_number']; ?>" data-original-value="<?php echo $invoice['customer_contact_number']; ?>" <?=$frmelmentdisable?> title="<?=$this->lang->line('Contact Person Number')?>">
                                       </div>                                    
                                 </div>
                                 <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                       <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Supplier Contact Email'); ?></label>
                                       <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Supplier Contact Email" value="<?php echo $invoice['customer_contact_email']; ?>" data-original-value="<?php echo $invoice['customer_contact_email']; ?>" <?=$frmelmentdisable?> title="<?=$this->lang->line('Contact Contact Email')?>">
                                       </div>                                    
                                 </div>                           
                                 
                                 <?php if (isset($employee)){?> 
                                       <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 <?=$assignseccls?> d-none">
                                 
                                          <label for="employee" class="col-form-label"><?php echo $this->lang->line('Assign to') ?><?=$compulsory?></label>
                                             <select name="employee" id="employee" class=" col form-control <?=$assigncls?> <?=$frmselectdisable?>" <?=$required?> <?=$frmelmentdisable?> data-original-value="<?=$invoice['assign_to']?>" title="<?=$this->lang->line('Assign to')?>">
                                             <?php echo '<option value="">Select an Employee</option>'; ?>
                                                   <?php foreach ($employee as $row) {
                                                      $sel = "";
                                                      if($invoice['assign_to']==$row['id']){
                                                         $sel="Selected";
                                                      }
                                                      echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['name'].'</option>';
                                                   } ?>
                                             </select>
                                       </div>
                                 <?php } ?>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="taxformat"
                                       class="col-form-label"><?php echo $this->lang->line('Tax') ?> </label>
                                    <select class="form-control <?=$frmselectdisable?>"
                                       onchange="changeTaxFormat(this.value)"
                                       id="taxformat" <?=$frmelmentdisable?>>
                                    <?php echo $taxlist; ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="discountFormat"
                                       class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                    <select class="form-control <?=$frmselectdisable?>" onchange="changeDiscountFormat(this.value)"
                                       id="discountFormat" <?=$frmelmentdisable?>>
                                    <?php echo $this->common->disclist() ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none1">
                                    <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?> *</label>
                                    <select id="s_warehouses" name="store_id" class="selectpicker form-control <?=$frmselectdisable?>">
                                    <?php 
                                       
                                       echo '<option value=""> Select Warehouse'; ?></option><?php 
                                      foreach ($warehouse as $row) {
                                          $sel = ($row['store_id'] == $invoice['store_id']) ? "selected" : "";
                                          echo '<option value="' . $row['store_id'] . '" ' . $sel . '>' . $row['store_name'] . '</option>';
                                       }
                                       ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?> </label>
                                    <select name="pterms" class="selectpicker form-control <?=$frmselectdisable?>" <?=$frmelmentdisable?>>
                                    <?php foreach ($terms as $row){
                                       echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                       } ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="Update Stock" class="col-form-label"><?php echo $this->lang->line('Update Stock') ?> </label>
                                    <div class="mt-1">
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight1" value="yes" <?=$frmelmentdisable?>>
                                          <label class="form-check-label" for="customRadioRight1"><?php echo $this->lang->line('Yes') ?></label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight2" value="no" checked <?=$frmelmentdisable?>>
                                          <label class="form-check-label" for="customRadioRight2"><?php echo $this->lang->line('No') ?></label>
                                       </div>
                                    </div>
                                    <!-- erp2024 old radio buttons -->
                                    <!-- <fieldset class="right-radio">
                                       <div class="custom-control custom-radio">
                                       <input type="radio" class="custom-control-input" name="update_stock" id="customRadioRight1" value="yes" >
                                       <label class="custom-control-label"
                                          for="customRadioRight1"><?php //echo $this->lang->line('Yes') ?></label>
                                       </div>
                                       </fieldset>
                                       <fieldset class="right-radio">
                                       <div class="custom-control custom-radio">
                                       <input type="radio" class="custom-control-input" name="update_stock"
                                          id="customRadioRight2" value="no" checked="">
                                       <label class="custom-control-label"
                                          for="customRadioRight2"><?php //echo $this->lang->line('No') ?></label>
                                       </div>
                                       </fieldset> -->
                                    <!-- erp2024 old radio buttons ends-->
                                 </div>
                                 <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="notes" class="col-form-label"><?php echo $this->lang->line('Order Note') ?> </label>
                                    <textarea class="form-textarea <?=$frmselectdisable?>" data-original-value="<?php echo $invoice['notes'] ?>" id="notes" name="notes" rows="2" <?=$frmelmentdisable?> title="<?=$this->lang->line('Order Note')?>"><?=$invoice['notes']?></textarea>
                                 </div>
                                    <!-- Image upload sections starts-->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                             <label for="upfile-0" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
                                             <div class="row">                            
                                                <div class="col-8">
                                                      <div class="d-flex">
                                                         <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file <?=$fully_received_class?>" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                         <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                         <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn <?=$fully_received_class?>" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="fa fa-trash" ></i></button>
                                                      </div>
                                                      <div id="uploadsection"></div>                                                
                                                </div>                        
                                                <div class="col-4">
                                                         <button class="btn btn-crud btn-secondary btn-sm mt-1 <?=$fully_received_class?>" id="addmore_img"  title="Add More Files" type="button"><i class="fa fa-plus-circle"></i> Add More</button>
                                                      
                                                </div>
                                             </div>
                                          </div>
                                          <!-- Image upload sections ends -->
                                       <!-- ===== Image sections starts ============== -->
                                 <div class="container-fluid">
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
                                                echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"deleteitem('{$image['id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
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
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               <?php   
                    if($invoice['order_status']=='Draft')
                    { ?>
                        <div class="alert alert-warning alert-success fade show d-none" id="hide_alert" role="alert">
                            <strong>Draft</strong> Saved Successfully.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
               <label class=" col-12 row col-form-label">*Free of Charge Coming Soon..</label>
               <input type="hidden" class="form-control deleted_item" name="deleted_item">
               <div id="saman-row" class="table-scroll">
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                           <th width="2%" class="text-center"><?php echo $this->lang->line('SN') ?></th>
                           <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Code') ?></th>
                           <th width="25%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                           <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                           <th width="10%" class="text-right"><?php echo $this->lang->line('Last Purchased Price') ?></th>
                           <th width="10%" class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                           <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                           <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th> -->
                           <th width="7%" class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                           <th width="4%" class="text-center"><?php echo $this->lang->line('Free Of Charge') ?></th>
                           <th width="10%" class="text-right">
                              <?php echo $this->lang->line('Amount'); //echo "(".$this->config->item('currency').")"; ?>
                   
                           </th>
                           <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i = 0;
                         $totaldiscount =0;
                         $grandtotal =0;
                         $k=1;
                        if(!empty($products))
                        {
                           foreach ($products as $row) {
                              $totaldiscount = $totaldiscount + $row['discount'];
                              $grandtotal = $grandtotal + $row['subtotal'];
                              $product_name_with_code = $row['product'].'('.$row['product_code'].') - ';
                              $productcode = $row['product_code'];  //qty
                              echo '<tr><td class="text-center serial-number">'.$k++.'</td>
                              <td><input type="text" data-product-code="'.$productcode.'" placeholder="Search by Item No." class="form-control code" name="code[]" id="purchasecode-' . $i . '" value="' . $row['product_code'] . '" '. $frmelmentdisable.' title="'.$product_name_with_code.'Code" data-original-value="' . $row['product_code'] . '" '. $frmelmentdisable.'><input type="hidden" class="form-control" name="hsn[]" id="unit-' . $i . '" value="' . $row['product_code'] . '" ><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-' . $i . '" value="' . $row['account_code'] . '"></td>

                              <td><span class="d-flex"><input type="text" data-product-code="'.$productcode.'" class="form-control" name="product_name[]" title="'.$product_name_with_code.'Product" placeholder="Enter Product name"   value="' . $row['product'] . '" '. $frmelmentdisable.' data-original-value="' . $row['product'] . '" '. $frmelmentdisable.' id="productname-'.$i.'">&nbsp;';
                              ?>
                              <button type="button" title="change account"
                                       class="btn btn-crud btn-sm btn-secondary"
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
                                                <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn btn-crud btn-primary btn-sm">Change</button></div>
                                             </form>'
                                    >
                                       <i class="fa fa-bank"></i>
                                    </button></span>
                              <?php
                              echo '</td><td><input type="text" data-product-code="'.$productcode.'" class="form-control req amnt text-right responsive-width-elements" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog(),discountWithTotal(' . $i . ')" autocomplete="off" value="' . intval($row['quantity']) . '" '. $frmelmentdisable.' title="'.$product_name_with_code.'Quantity" data-original-value="' . intval($row['quantity']) . '" '. $frmelmentdisable.'><input type="hidden" name="old_product_qty[]" value="' . intval($row['quantity']) . '"></td>';

                              echo '<td class="text-right"><span>'.$row['last_purchase_price'].'</span></td>';
                              echo '<td><input type="text" data-product-code="'.$productcode.'" class="form-control text-right req prc responsive-width-elements" name="product_price[]" id="price-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="checkCost(' . $i . '),discountWithTotal(' . $i . '), rowTotal(' . $i . '), billUpyog()"  autocomplete="off" value="' . ($row['price']) . '" '. $frmelmentdisable.' title="'.$product_name_with_code.'Price" data-original-value="' . ($row['price']) . '" '. $frmelmentdisable.'></td>';
                              echo '<td class="text-right"><input type="text" data-product-code="'.$productcode.'" class="form-control text-right discount" name="product_discount[]" onkeypress="return isNumber(event)"  autocomplete="off" value="' . ($row['discount']) . '" data-original-value="' . ($row['discount']) . '" title="'.$product_name_with_code.'Discount" id="discount-' . $i . '" onkeyup="discountWithTotal(' . $i . ')"  autocomplete="off" value="' . ($row['totaldiscount']) . '"autocomplete="off" '. $frmelmentdisable.'></td>
                              <td></td>
                              <td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">' . number_format($row['subtotal'],2) . '</span></strong>
                               <div class="costvaluation_section" id="costvaluation_section-' . $i . '"><strong class="text-danger" id="cost_warning_val-' . $i . '"></strong></div></td>
                              <td class="text-center"><button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Details"><i class="fa fa-info"></i></button>
                              <button type="button" data-rowid="' . $i . '" class="btn btn-crud btn-sm btn-secondary removeProd" title="Remove" '. $frmbtndisable.'> <i class="fa fa-trash"></i> </button>
                              </td>
                              <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . ($row['totaltax']) . '">
                              <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . ($row['totaldiscount']) . '">
                              <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . ($row['subtotal']) . '">
                              <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['product_code'] . '">
                              <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">   
                              </tr>';
                              $i++;
                           }
                        } 
                        
                        else{
                        ?>
                           <tr class="startRow">
                           <td class="text-center serial-number">1</td>
                           <td><input type="text" placeholder="Search by Item No." class="form-control code" name="code[]" id="purchasecode-0" value="" ><input type="hidden" class="form-control" name="hsn[]" id="hsn-0" value="" readonly><input type="hidden" class="form-control" name="expense_account_number[]" id="expense_account_number-0">
                           </td>
                              <td><span class="d-flex"><input type="text" class="form-control" name="product_name[]"
                                 placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                 id='purchaseproduct-0'>&nbsp;
                                 <button type="button" title="change account"
                                       class="btn btn-crud btn-sm btn-secondary"
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
                                                <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn btn-crud btn-primary btn-sm">Change</button></div>
                                             </form>'
                                    >
                                       <i class="fa fa-bank"></i>
                                    </button></span>
                              </td>
                              
                              <td><input type="text" class="form-control req amnt text-right responsive-width-elements" name="product_qty[]" id="amount-0"
                                 onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(),discountWithTotal('0')"
                                 autocomplete="off" value="0">
                              <td class="text-right"><span id="last_purchase_price_label-0"></span></td>  
                              <td><input type="text" class="form-control text-right req prc responsive-width-elements" name="product_price[]" id="price-0"  onkeypress="return isNumber(event)" onkeyup="checkCost('0'), rowTotal('0'), billUpyog(),discountWithTotal('0')"  autocomplete="off" value="0" ></td>
                              <td class="d-none"><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                                 onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                 autocomplete="off"></td>
                              <td class="text-center d-none" id="texttaxa-0">0</td>
                              <td><input type="text" class="form-control text-right discount" name="product_discount[]" onkeypress="return isNumber(event)"  id="discount-0" onkeyup="discountWithTotal('0')" autocomplete="off"></td>
                              <td></td>
                              <td class="text-right">
                                 <strong><span class='ttlText' id="result-0">0</span></strong>
                                 <div class="costvaluation_section" id="costvaluation_section-0"><strong class="text-danger" id="cost_warning_val-0"></strong></div>
                              </td>
                              <td class="text-center"><button onclick="single_product_details('0')" type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Details"><i class="fa fa-info"></i></button>
                              <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>
                              </td>
                              <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                              <input type="hidden" name="disca[]" id="disca-0" value="0">
                              <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                              <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                              <input type="hidden" name="unit[]" id="unit-0" value="">
                           </tr>
                        <?php 
                        } ?>
                        <tr class="last-item-row tr-border" >
                           <td class="add-row no-border" colspan="9">
                              <?php 
                              if(!empty($invoice['approved_by']) && ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['prepared_flag']==1))
                              { ?> <?php }else { ?>
                              <button type="button" class="btn btn-crud btn-secondary <?=$accpetthenhide?>" aria-label="Left Align"
                                 id="addpurchaseproduct"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                              </button>
                              <?php } ?>
                           </td>
                           <td colspan="7" class="no-border"></td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="4" align="right" class="no-border"><input type="hidden" value="0" id="subttlform"
                              name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><span
                              class="currenty lightMode"><?php //echo $this->config->item('currency'); ?></span>
                              <span id="taxr" class="lightMode">0</span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="8" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Total Discount') ?></strong>
                           </td>
                           <td align="right" colspan="2" class="no-border"><span
                              class="currenty lightMode"></span>
                              <span id="discs" class="lightMode"><?=number_format($totaldiscount, 2);?></span>
                           </td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="4" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Shipping') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                              onkeypress="return isNumber(event)"
                              placeholder="Value"
                              name="shipping" autocomplete="off"
                              onkeyup="billUpyog();">
                              ( <?php echo $this->lang->line('Tax') ?>
                              <span id="ship_final">0</span> )
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <!-- <td colspan="2" class="no-border">
                              <?php if ($exchange['active'] == 1){
                                 echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                              <select name="mcurrency"
                                 class="selectpicker form-control">
                                 <option value="0">Default</option>
                                 <?php foreach ($currency as $row) {
                                    if(strtolower($row['symbol']) == 'qar')
                                    {
                                       echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['product_code'] . ')</option>';
                                    }
                                    
                                    } ?>
                              </select>
                              <?php } ?>
                           </td> -->
                           <td colspan="8" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                           <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                           </td>
                           <td align="right" colspan="2" class="no-border">
                              <span id="grandtotaltext"><?=number_format($grandtotal, 2);?></span>
                              <input type="hidden" name="total" class="form-control"
                              id="invoiceyoghtml" readonly="" value="<?=$grandtotal?>">
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                          
                           <td colspan="5" class="no-border">
                              <?php 
                              $draftcls ="";
                              if((!empty($invoice['approved_by'])) && ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['prepared_flag']==1) && ($invoice['approval_flag']=='1'))
                              {
                                 $draftcls ="d-none";
                               ?>
                              <!-- <button type="button" class="btn btn-crud btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?>" id="revert-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp; -->
                              <?php }
                              if((!empty($invoice['approved_by'])) && ($invoice['approved_by']==$this->session->userdata('id') && $invoice['prepared_flag']==1) && ($invoice['approval_flag']=='1'))
                              {
                                 $draftcls ="";
                                 $revertcls = ($invoice['order_status']=='Reverted') ? "disable-class" :"";
                               ?>
                                 <!-- <button type="button" class="btn btn-crud btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?> <?=$revertcls?>" id="revert-by-admin-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp; -->
                              <?php }
                              $approval_permission = ($my_approval_permissions[0]['first_level_approval']=="Yes" && $invoice['approval_flag'] != 1) ?"":"d-none";
                              $approval_permission_send = (($my_approval_permissions[0]['first_level_approval']!="Yes") && $invoice['approval_flag'] != 1) ?"d-none":"";
                              $approval_permission_enabled = (($my_approval_permissions[0]['first_level_approval']=="Yes") && $invoice['approval_flag'] != 1) ?"disable-class":"";
                              $approval_cancel_permission = ($my_approval_permissions[0]['first_level_approval']=="Yes" && $invoice['approval_flag'] == 1) ?"":"d-none";
                              ?>
                               <input type="submit" class="btn btn-crud btn-lg btn-secondary revert-btncolor <?=$approval_cancel_permission?> <?=$accpetthenhide?>"   id="approve-canceled-btn"  value="<?php echo $this->lang->line('Approve Canceled') ?>" data-loading-text="Creating...">
                          
                              <input type="submit" class="btn  btn-crud btn-lg btn-secondary sub-btn <?=$hidedraftbtn?> <?=$draftcls1?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Save As Draft') ?>" id="prepared-btn-draft" data-loading-text="Creating...">
                           </td>
                           <td align="right" colspan="8" class="no-border">
                              <?php
                             
                              if($invoice['prepared_flag']!=1){ ?>
                                  <input type="submit" class="btn btn-crud btn-crud btn-lg btn-primary sub-btn <?=$generatebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Prepared') ?>" id="prepared-btn" data-loading-text="Creating...">
                              <?php } ?>

                              <input type="submit" class="btn btn-crud btn-lg btn-secondary purchase-approve-btn <?=$approvebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Creating...">

                              

                              <!-- <input type="submit" class="btn btn-crud btn-lg btn-primary purchase-send-btn <?=$approvebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Send Purchase Order') ?>" data-loading-text="Creating..."> -->

                             
                              
                              <?php
                              if($invoice['approved_by']==$this->session->userdata('id')  &&  $invoice['approval_flag']=='1'){
                                    ?>
                                    <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn purchase-approve-btn <?=$accpetthenhide?>" value="<?php echo $this->lang->line('Update') ?>">
                              <?php }
                              if((!empty($invoice['approved_by'])) && ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['prepared_flag']==1))
                              { ?>  
                               <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn purchase-approve-btn d-none <?=$accpetthenhide?>" value="<?php echo $this->lang->line('Update') ?>">
                              <?php 
                              } 
                              if($last_approval_step==2)
                              {
                                    $approval_complete_class = "";
                                    $approval_complete_label = $this->lang->line('Send Purchase Order');
                              }
                              else{
                                    $approval_complete_class = 'disabled';
                                    $approval_complete_label = "All approvals are not completed.";
                              }
                              ?>

                              <!-- <input type="submit" class="btn btn-crud btn-lg btn-secondary first-level-approve-btn <?=$approvebtn?> <?=$approval_permission?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('First Level Approval') ?>" data-loading-text="Creating..."> -->
                               <!-- newly placed from above commented 18-02-2025  -->
                              <?php if($purchase_order_number){ ?>  
                              <input type="submit" class="btn btn-lg btn-primary purchase-send-btn <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Send Purchase Order') ?>" title="<?=$approval_complete_label?>" <?=$approval_complete_class?>>
                              <?php echo $purchase_receiptbtn; ?>
                              <?php } ?>

                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <!-- <input type="hidden" value="purchase/action" id="action-url"> -->
               
               <input type="hidden" value="puchase_search" id="billtype">
               <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
               <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
               <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
               <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
               <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
               <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                  name="discountFormat" id="discount_format">
               <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                  name="shipRate"
                  id="ship_rate">
               <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                  id="ship_taxtype">
               <input type="hidden" value="0" name="ship_tax" id="ship_tax">
            </form>
         </div>
      </div>
   </div>
</div>


<!-- ======================Additional Forms sms,email,cancel etc starts ========================== -->
 <!-- Modal HTML -->
<div id="part_payment" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Debit Payment Confirmation') ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form class="payment" autocomplete="off">
               <div class="row">
                  <div class="col">
                     <div class="input-group">
                        <div class="input-group-addon"><?php //echo $this->config->item('currency') ?></div>
                        <input type="text" class="form-control" placeholder="Total Amount" name="amount"
                           id="rmpay" value="<?php echo $rming ?>">
                     </div>
                  </div>
                  <div class="col">
                     <div class="input-group">
                        <div class="input-group-addon"><span class="icon-calendar4"
                           aria-hidden="true"></span></div>
                        <input type="text" class="form-control required" id="tsn_date"
                           placeholder="Billing Date" name="paydate"
                           value="<?php echo dateformat($this->config->item('date')); ?>">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1">
                     <label class="col-form-label" for="pmethod"><?php echo $this->lang->line('Payment Method') ?></label>
                     <select name="pmethod" class="form-control mb-1">
                        <option value="Cash"><?php echo $this->lang->line('Cash') ?></option>
                        <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                        <option value="Bank">Bank</option>
                     </select>
                     <label for="account" class="col-form-label"><?php echo $this->lang->line('Account') ?></label>
                     <select name="account" class="form-control">
                     <?php foreach ($acclist as $row) {
                        echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                        }
                        ?>
                     </select>
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1"><label class="col-form-label" for="shortnote"><?php echo $this->lang->line('Note') ?></label>
                     <input type="text" class="form-control"
                        name="shortnote" placeholder="Short note"
                        value="Payment for purchase #<?php echo $invoice['tid'] ?>">
                  </div>
               </div>
               <div class="modal-footer">
                  <input type="hidden" class="form-control required"
                     name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <button type="button" class="btn btn-crud btn-secondary btn-crud"
                     data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                  <input type="hidden" name="cid" value="<?php echo $invoice['cid'] ?>"><input type="hidden"
                     name="cname"
                     value="<?php echo $invoice['name'] ?>">
                  <button type="button" class="btn btn-crud btn-primary btn-crud"
                     id="purchasepayment"><?php echo $this->lang->line('Do Payment') ?></button>
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
            <h4 class="modal-title"><?php echo $this->lang->line('Cancel Purchase Order') ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form class="cancelbill"  autocomplete="off">
               <div class="row">
                  <div class="col-12">
                     <?php echo $this->lang->line('this action! Are you sure') ?>
                  </div>
               </div>
               <div class="modal-footer mt-1">
                  <input type="hidden" class="form-control"
                     name="tid" value="<?php echo $invoice['iid'] ?>">
                  <button type="button" class="btn btn-crud btn-secondary"
                     data-dismiss="modal"> <?php echo $this->lang->line('Close') ?></button>
                  <button type="button" class="btn btn-crud btn-primary"
                     id="send"> <?php echo $this->lang->line('Cancel') ?></button>
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
            <h4 class="modal-title">Email</h4>
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
            <form id="sendbill" autocomplete="off">
               <div class="row">
                  <div class="col">
                     <label class="col-form-label" for="shortnote"><?php echo $this->lang->line('Email') ?></label>
                     <div class="input-group">
                        <div class="input-group-addon"><span class="icon-envelope-o"
                           aria-hidden="true"></span></div>
                        <input type="text" class="form-control" placeholder="Email" name="mailtoc"
                           value="<?php echo $invoice['email'] ?>">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1"><label class="col-form-label"  for="shortnote"><?php echo $this->lang->line('Supplier') ?></label>
                     <input type="text" class="form-control"
                        name="customername" value="<?php echo $invoice['name'] ?>">
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1"><label class="col-form-label" for="shortnote"><?php echo $this->lang->line('Subject') ?></label>
                     <input type="text" class="form-control"
                        name="subject" id="subject">
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1"><label class="col-form-label" for="shortnote"><?php echo $this->lang->line('Message') ?></label>
                     <textarea name="text" class="summernote" id="contents" title="Contents"></textarea>
                  </div>
               </div>
               <input type="hidden" class="form-control"
                  id="invoiceid" name="tid" value="<?php echo $invoice['iid'] ?>">
               <input type="hidden" class="form-control"
                  id="emailtype" value="">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-crud btn-secondary"
               data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
            <button type="button" class="btn btn-crud btn-primary"
               id="sendM"><?php echo $this->lang->line('Send') ?></button>
         </div>
      </div>
   </div>
</div>
<div id="pop_model" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Change Status') ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form id="form_model" autocomplete="off">
               <div class="row">
                  <div class="col mb-1">
                     <label
                        for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
                     <select name="status" class="form-control mb-1">
                        <option value="paid"><?php echo $this->lang->line('Paid') ?></option>
                        <option value="due"><?php echo $this->lang->line('Due') ?></option>
                        <option value="partial"><?php echo $this->lang->line('Partial') ?></option>
                     </select>
                  </div>
               </div>
               <div class="modal-footer">
                  <input type="hidden" class="form-control required"
                     name="tid" id="invoiceid" value="<?php echo $invoice['iid'] ?>">
                  <button type="button" class="btn btn-crud btn-crud btn-secondary"
                     data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                  <input type="hidden" id="action-url" value="purchase/update_status">
                  <button type="button" class="btn btn-crud btn-crud btn-primary"
                     id="submit_model"><?php echo $this->lang->line('Change Status') ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div id="sendSMS" class="modal fade">
   <div class="modal-dialog modal-xl">
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
            <form id="sendsms" autocomplete="off">
               <div class="row">
                  <div class="col">
                     <label class="col-form-label" for="shortnote"><?php echo $this->lang->line('Phone'); ?></label>
                     <div class="input-group">
                        <div class="input-group-addon"><span class="icon-envelope-o"
                           aria-hidden="true"></span></div>
                        <input type="text" class="form-control" placeholder="SMS" name="mobile"
                           value="<?php echo $invoice['phone'] ?>">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1"><label class="col-form-label" for="shortnote"><?php echo $this->lang->line('Customer Name'); ?></label>
                     <input type="text" class="form-control"
                        value="<?php echo $invoice['name'] ?>">
                  </div>
               </div>
               <div class="row">
                  <div class="col mb-1"><label class="col-form-label" 
                     for="shortnote"><?php echo $this->lang->line('Message'); ?></label>
                     <textarea class="form-control" name="text_message" id="sms_tem" title="Contents"
                        rows="3"></textarea>
                  </div>
               </div>
               <input type="hidden" class="form-control"
                  id="smstype" value="">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-crud btn-secondary"
               data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            <button type="button" class="btn btn-crud btn-primary"
               id="submitSMS"><?php echo $this->lang->line('Send'); ?></button>
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
                <th><?php //echo "#" ?></th>
                <th><?php //echo $this->lang->line('Action_performed') ?></th>
                <th><?php //echo $this->lang->line('IP address')?></th>
                <th><?php //echo $this->lang->line('Performed By') ?></th>
                <th><?php //echo $this->lang->line('Performed At')?></th>
            </tr>
        </thead>
        <tbody>
            <?php //$i = 1;
       //     foreach ($log as $row) { ?>
               <tr>
                  <td><?php //echo $i?></td>
                  <td><?php //echo $row['action_performed']?></td>
                  <td><?php //echo $row['ip_address']?></td>
                  <td><?php //echo $row['name']?></td>
                  <td><?php //echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
               </tr>
               <?php 
          //     $i++; 
         //   } ?>
        </tbody>
    </table>

    </form>
</div> -->
 
<script>
// erp2025 09-01-2025 detailed history start
const changedFields = {};
let productCode;   
let changedProducts = new Set();
let wholeProducts = new Set();

$(document).ready(function() {
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
 

    $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel, {
        rules: {
            cst: {
               required: function() {
                  return $('#customer_id').val() == 0;
               }
            },
            refer: {required:true},
            doc_type: {required:true},
            currency_id: {required:true},
            invocieduedate: {required:true},
            store_id: {required:true},
            customer_contact_number: {
               phoneRegex :true
            },
        },
        messages: {
            cst    : "Search Supplier",
            refer  : "Enter Reference",
            doc_type  : "Doc Type",
            currency_id  : "Currency",
            invocieduedate  : "Order Due Date",
            store_id  : "Warehouse ",
            customer_contact_number  : "Enter Valid Number",
        }
      }));
      document.querySelectorAll('[data-product-code]').forEach(function(element) {
         let productCode = element.getAttribute('data-product-code');
         // Add the productCode to the Set (duplicates will be automatically discarded)
         wholeProducts.add(productCode);
      });
     // Add event listeners to all input fields
     document.querySelectorAll('input, textarea, select').forEach((input) => {
          input.addEventListener('change', function () {
              const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
              const originalValue = this.getAttribute('data-original-value');
              var label = $('label[for="' + fieldId + '"]');
              var field_label = label.text();
              productCode = this.getAttribute('data-product-code');
             
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

                  if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue && productCode) {
                    changedProducts.add(productCode);
                   }
              } 
              else if (this.tagName === 'SELECT') 
              {
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
                      delete changedFields[fieldId]; 
                  }
                if (originalValue !== newValue && productCode) {
                    changedProducts.add(productCode);
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
         // erp2025 09-01-2025 ends
     $("#employee").prop('required', false);
      
});

$(".purchase-approve-btn").on("click", function(e) {

    e.preventDefault();
   //  console.log(changedProducts);
   //  console.log(wholeProducts);
    var selectedProducts1 = [];
    var validationFailed = false;
   //  $('.purchase-approve-btn').prop('disabled', true);
    var assignto = $('#employee').val();
    if(assignto=="")
    {
      // $("#employee").prop('required', true);
    }
    var rateflg=0;
    $('.amnt').each(function() {
         if ($(this).val() > 0) {
            var elementId = $(this).attr('id');
            var lastChar = elementId.slice(-1);
            var priceElement = $("#price-" + lastChar); 
            if (priceElement.val() > 0) {
               priceElement.rules('remove', 'required');
            } else {
               rateflg =1;
               priceElement.val("");
               priceElement.rules('add', {
                  required: true,
                  messages: {
                     required: "This field is required."
                  }
               });
               $(".help-block").css("display", "block");

            }
            selectedProducts1.push({
                  value: $(this).val()
            });
         }
   });
    if ($("#data_form").valid()) {
      // var selectedProducts1 = [];
      // $('.amnt').each(function() {
      //       if($(this).val()>0)
      //       {
      //          selectedProducts1.push($(this).val());
      //       }
      // });

      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter an item or a valid quantity",
            icon: "info"
         });
         $('.purchase-approve-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#purchase-approve-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Modify this Purchase Order?",
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
            // var formData = $("#data_form").serialize(); 
            // formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            // formData += '&completed_status=1';
              var form = $('#data_form')[0]; // Get the form element
               var formData = new FormData(form); // Create FormData object
               formData.append('completed_status',1);
               formData.append('approval_level',0);
               formData.append('changedFields', JSON.stringify(changedFields));
               formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
               formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
              
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/approval_action',
                  data: formData,
                  contentType: false, 
                  processData: false,
                  success: function(response) {
                     // window.location.href = baseurl + 'purchase'; 
                     location.reload();
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('.purchase-approve-btn').prop('disabled', false);
            }
      });
    }
    else{
         $('.page-header-data-section').css('display','block');
         $('.purchase-approve-btn').prop('disabled', false);
      }
});

$(".first-level-approve-btn").on("click", function(e) {

   e.preventDefault();
   var selectedProducts1 = [];
   var validationFailed = false;
   $('.first-level-approve-btn').prop('disabled', true);
   // var assignto = $('#employee').val();
   // if(assignto=="")
   // {
   //   $("#employee").prop('required', true);
   // }
   var rateflg=0;
   $('.amnt').each(function() {
      if ($(this).val() > 0) {
         var elementId = $(this).attr('id');
         var lastChar = elementId.slice(-1);
         var priceElement = $("#price-" + lastChar); 
         if (priceElement.val() > 0) {
            priceElement.rules('remove', 'required');
         } else {
            rateflg =1;
            priceElement.val("");
            priceElement.rules('add', {
               required: true,
               messages: {
                  required: "This field is required."
               }
            });
            $(".help-block").css("display", "block");

         }
         selectedProducts1.push({
               value: $(this).val()
         });
      }
   });
   if ($("#data_form").valid()) {
      // var selectedProducts1 = [];
      // $('.amnt').each(function() {
      //       if($(this).val()>0)
      //       {
      //          selectedProducts1.push($(this).val());
      //       }
      // });

      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter an item or a valid quantity",
            icon: "info"
         });
         $('.first-level-approve-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#first-level-approve-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Approve this Purchase Order?",
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
            // var formData = $("#data_form").serialize(); 
            // formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            // formData += '&completed_status=1';
               var form = $('#data_form')[0]; // Get the form element
               var formData = new FormData(form); // Create FormData object
               formData.append('completed_status',1);           
               formData.append('approval_level',1);
               formData.append('changedFields', JSON.stringify(changedFields));          
               formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
               formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/action',
                  data: formData,
                  contentType: false, 
                  processData: false,
                  success: function(response) {
                     window.location.href = baseurl + 'purchase'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('.first-level-approve-btn').prop('disabled', false);
            }
      });
   }
   else{
      $('.page-header-data-section').css('display','block');
      $('.first-level-approve-btn').prop('disabled', false);
   }
});

$(".purchase-send-btn").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    $('.purchase-send-btn').prop('disabled', true);
    $("#hide_alert").hide();
    $("#employee").prop('required', false);
    var rateflg=0;
    $('.amnt').each(function() {
         if ($(this).val() > 0) {
            var elementId = $(this).attr('id');
            var lastChar = elementId.slice(-1);
            var priceElement = $("#price-" + lastChar); 
            if (priceElement.val() > 0) {
               priceElement.rules('remove', 'required');
            } else {
               rateflg =1;
               priceElement.val("");
               priceElement.rules('add', {
                  required: true,
                  messages: {
                     required: "This field is required."
                  }
               });
               $(".help-block").css("display", "block");

            }
            selectedProducts1.push({
                  value: $(this).val()
            });
         }
   });
    if ($("#data_form").valid()) {
      // $('.amnt').each(function() {
      //       if($(this).val()>0)
      //       {
      //          selectedProducts1.push($(this).val());
      //       }
      // });
      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter quantity for at least one item",
            icon: "info"
         });
         $('.purchase-send-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#purchase-send-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Send this Purchase Order Now?",
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
             //  var formData = $("#data_form").serialize(); 
            //   formData += '&completed_status=1';
               var form = $('#data_form')[0]; // Get the form element
               var formData = new FormData(form); // Create FormData object
               formData.append('completed_status',1);               
               formData.append('changedFields', JSON.stringify(changedFields));          
               formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
               formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/send_po_action',
                  data: formData,
                  contentType: false, 
                  processData: false,
                  success: function(response) {
                     dataid = response.data;
                     //  window.location.href = baseurl + 'purchase';
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('.purchase-send-btn').prop('disabled', false);
            }
      });
    }
    else{
         $('.purchase-send-btn').prop('disabled', false);
         $("#data_form").find(".error:visible").first().focus();
      }
});

$("#prepared-btn").on("click", function(e) {
    e.preventDefault();
    $('#prepared-btn').prop('disabled', true);
    $("#hide_alert").hide();
    var selectedProducts1 = [];
    var validationFailed = false;
    var selectedProducts1 = [];
    var rateflg=0;
    $('.amnt').each(function() {
         if ($(this).val() > 0) {
            var elementId = $(this).attr('id');
            var lastChar = elementId.slice(-1);
            var priceElement = $("#price-" + lastChar); 
            if (priceElement.val() > 0) {
               priceElement.rules('remove', 'required');
            } else {
               rateflg =1;
               priceElement.val("");
               priceElement.rules('add', {
                  required: true,
                  messages: {
                     required: "This field is required."
                  }
               });
               $(".help-block").css("display", "block");

            }
            selectedProducts1.push({
                  value: $(this).val()
            });
         }
   });
    if ($("#data_form").valid()) {
      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter an item or a valid quantity",
            icon: "info"
         });
         $('#prepared-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#prepared-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to complete this Purchase Order?",
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
            /*   var formData = $("#data_form").serialize(); 
               formData += '&completed_status=1';*/
            // formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            formData.append('completed_status',1);              
            formData.append('changedFields', JSON.stringify(changedFields));
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/action',
                  data: formData,
                  contentType: false, 
                  processData: false,
                  success: function(response) {
                      window.location.href = baseurl + 'purchase'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('#prepared-btn').prop('disabled', false);
            }
      });
    }
    else{
      $('.page-header-data-section').css('display','block');
      $('#prepared-btn').prop('disabled', false);
    }
});


$("#revert-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this purchase order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/revertorder_action',
                    data: {
                        po_id: $("#po_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'purchase';
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

   $("#revert-by-admin-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this purchase order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/revertorder_by_admin_action',
                    data: {
                        po_id: $("#po_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'purchase/create?id='+$("#po_id").val();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    
   
    
    $("#po-accept-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to Accept this Purchase Order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/po_accept',
                    data: {
                        po_id: $("#po_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'purchase/create?id='+$("#po_id").val();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    $(function () {
       $('.summernote').summernote({
           height: 100,
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
   
   $(document).on('click', "#cancel-bill_p", function (e) {
       e.preventDefault();
   
       $('#cancel_bill').modal({backdrop: 'static', keyboard: false}).one('click', '#send', function () {
           var acturl = 'transactions/cancelpurchase';
           cancelBill(acturl);
   
       });
   });

   function discountWithTotal(numb) {
      var price = accounting.unformat($("#price-" + numb).val(), accounting.settings.number.decimal);
      var discount = accounting.unformat($("#discount-" + numb).val(), accounting.settings.number.decimal);
      total_items_count = $("#ganak").val();
      
      // Reset discount if it exceeds the price
      if (parseFloat(discount) >= parseFloat(price)) {
         $("#discount-" + numb).val(0.00);
      }

      var grandTotal = 0;
      var granddiscount = 0;
      // Loop through each item and calculate totals
      for (var i = 0; i <= total_items_count; i++) {
         var productqty = accounting.unformat($("#amount-" + i).val(), accounting.settings.number.decimal);
         var price = accounting.unformat($("#price-" + i).val(), accounting.settings.number.decimal);
         var discount = accounting.unformat($("#discount-" + i).val(), accounting.settings.number.decimal);
         
         var single_product_total = parseFloat(productqty) * parseFloat(price);
         var discountedtotal = parseFloat(single_product_total) - parseFloat(discount);
         granddiscount += parseFloat(discount);
         // Update each item's total with discount
         $("#result-" + i).text(accounting.formatNumber(discountedtotal));
         $("#total-" + i).val(accounting.formatNumber(discountedtotal));
         
         grandTotal += discountedtotal; // Accumulate grand total
      }

      // Format and display the grand total
      grandTotal = accounting.formatNumber(grandTotal);
      granddiscount = accounting.formatNumber(granddiscount);
      $("#discs").text(granddiscount);
      $("#grandtotaltext").text(grandTotal);
      $("#invoiceyoghtml").val(grandTotal); 
   }
   $("#attachment-btn").on('click',function(){
        Swal.fire({
        title: "Coming Soon",
        icon: "info",
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
            'actheader': 'Expenses',
            'accountnumber':$('#expense_account_number-'+index).val()
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
            $("#expense_account_number-" + index).val(account_selected);
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
function deleteitem(id,img_name) {
   swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!",
        icon: "warning",
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
                    url: baseurl + 'Quote/deletesubItem',
                    data: { selectedProducts: id, image: img_name },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {}
                });
            }
    });
}

$("#approve-canceled-btn").on('click', function(e){
      e.preventDefault();
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Are you sure you want to cancel the approval of this purchase order?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the AJAX request
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'purchase/cancel_purchase_order_approval_action',
                    data: {
                        po_id: $("#po_id").val(),
                        purchase_number: $("#purchase_number").val(),
                    },
                    dataType: 'json',
                    success: function(response) {
                     window.location.href = baseurl + 'purchase';
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
$(".first-level-approve-btn").on("click", function(e) {

   e.preventDefault();
   var selectedProducts1 = [];
   var validationFailed = false;
   $('.first-level-approve-btn').prop('disabled', true);
   // var assignto = $('#employee').val();
   // if(assignto=="")
   // {
   //   $("#employee").prop('required', true);
   // }
   var rateflg=0;
   $('.amnt').each(function() {
      if ($(this).val() > 0) {
         var elementId = $(this).attr('id');
         var lastChar = elementId.slice(-1);
         var priceElement = $("#price-" + lastChar); 
         if (priceElement.val() > 0) {
            priceElement.rules('remove', 'required');
         } else {
            rateflg =1;
            priceElement.val("");
            priceElement.rules('add', {
               required: true,
               messages: {
                  required: "This field is required."
               }
            });
            $(".help-block").css("display", "block");

         }
         selectedProducts1.push({
               value: $(this).val()
         });
      }
   });
   if ($("#data_form").valid()) {
      // var selectedProducts1 = [];
      // $('.amnt').each(function() {
      //       if($(this).val()>0)
      //       {
      //          selectedProducts1.push($(this).val());
      //       }
      // });

      if (selectedProducts1.length === 0) {
            Swal.fire({
            text: "To proceed, please enter an item or a valid quantity",
            icon: "info"
         });
         $('.first-level-approve-btn').prop('disabled', false);
            return;
      }
      if (rateflg == 1) {
            Swal.fire({
            text: "To proceed, please enter rate for each item",
            icon: "info"
         });
         $('#first-level-approve-btn').prop('disabled', false);
            return;
      }
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Approve this Purchase Order?",
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
            // var formData = $("#data_form").serialize(); 
            // formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            // formData += '&completed_status=1';
               var form = $('#data_form')[0]; // Get the form element
               var formData = new FormData(form); // Create FormData object
               formData.append('completed_status',1);           
               formData.append('approval_level',1);
               formData.append('changedFields', JSON.stringify(changedFields));          
               formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
               formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/action',
                  data: formData,
                  contentType: false, 
                  processData: false,
                  success: function(response) {
                     window.location.href = baseurl + 'purchase'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('.first-level-approve-btn').prop('disabled', false);
            }
      });
   }
   else{
      $('.page-header-data-section').css('display','block');
      $('.first-level-approve-btn').prop('disabled', false);
   }
});
    
</script>