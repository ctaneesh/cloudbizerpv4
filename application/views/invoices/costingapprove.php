<div class="content-body" >
    <div class="card" >
    <div class="card-header border-bottom">
      <nav aria-label="breadcrumb">
        <?php

            if($purchaseid)
            {
                $srvNumber = $this->lang->line('Add New');
                // $srvNumber = $srvNumber;
            }
            else{
                $srvNumber = $purchasemasterdata['purchase_reciept_number'];
            }
           
        ?>
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('purchase/create?id=' . $purchaseorderdata['purchase_number']) ?>"><?php echo  $purchaseorderdata['purchase_number']; ?></a></li>               
               <li class="breadcrumb-item"><a href="<?= base_url('invoices/stockreciepts') ?>"><?php echo $this->lang->line('Stock Reciepts') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo $srvNumber ?></li>
            </ol>
      </nav>
      <div class="row">
            <div class="col-12">
                <div class="row">
                     <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <div class="fcol-sm-12">
                            <h3 class="title-sub">
                            <?php echo $srvNumber; ?>
                            </h3>
                        </div>
                    </div>
                   
                    
                    <div class="col-xl-7 col-lg-9 col-md-6 col-sm-12 col-xs-12">  
                        <!-- <div class="fcol-sm-12">
                            <h6 class="title-sub">
                                <?php //echo "Purchase Order : <b> #" .$purchaseorderdata['tid']. "</b><br> Purchase Order Date : <b>".dateformat($purchaseorderdata['purchasemasterdatadate'])."</b>"; ?> 
                            </h6>
                        </div> -->
                        <ul id="trackingbar">
                            <?php 
                                $reciept_status_class="";
                                if($purchasemasterdata['reciept_status']=="Received")
                                {
                                    $reciept_status_class="disable-class";
                                }
                                if (!empty($trackingdata)) {     
                                    if (!empty($trackingdata['purchase_order_number'])) { 
                                        echo '<li><a href="' . base_url('purchase/create?id=' . $trackingdata['purchase_order_number']) . '">' . $trackingdata['purchase_order_number'] . '</a></li>';
                                    } 
                                    if (!empty($trackingdata['purchase_reciept_id'])) { 
                                        // echo '<li class="active">' . $trackingdata['purchase_reciept_number'] . '</li>';
                                        echo '<li class="active">' . $trackingdata['purchase_reciept_number'] . '</li>';
                                    }
                                    if (!empty($trackingdata['purchase_reciept_return_number'])) { 
                                        $validtoken1 = hash_hmac('ripemd160', 'p' . $trackingdata['purchase_reciept_return_number'], $this->config->item('encryption_key'));
                                        echo '<li><a href="' . base_url('purchasereturns/create?pid=' . $trackingdata['purchase_reciept_return_number']).'&token='.$validtoken1.'">' . $trackingdata['purchase_reciept_return_number'] . '</a></li>';
                                      }
                                    
                                }
                            ?>  
                        </ul> 
                    </div>
                              
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 current-status">                  
                        <!-- ========================================= -->
                        <?php
                        $frmelmentdisable = "";
                        $frmselectdisable = "";
                        $accpetthenhide = "";
                        $frmbtndisable="";
                        // Using switch to handle different conditions
                            // Using switch to handle different conditions
                            switch (true) {
                                
                                case ($purchasemasterdata['reciept_status'] == 'Received'):
                                    $frmelmentdisable = "readonly";
                                    $frmselectdisable = "textarea-bg disable-class";
                                    $accpetthenhide = "disable-class";
                                    $frmbtndisable= "disabled";
                                    break;
                            
                                case ($purchasemasterdata['approvalflg'] == '1' && $purchasemasterdata['prepared_flg'] == '1' && $purchasemasterdata['approved_by'] != $this->session->userdata('id')):
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
                        
                        $generatebtn = "";
                        $approvebtn="";
                        $acceptbtn="";
                        $preparedbtn="";
                        $assigncls="disable-class";
                        if($purchasemasterdata['prepared_flg']==1)
                        {
                           $preparedbtn="d-none";
                           $required = "";
                           $compulsory = '';
                           // $required = "required";
                           // $compulsory = '<span class="compulsoryfld">*</span>';
                           $assigncls="";
                        }
                        if($purchasemasterdata['prepared_flg']!=1 || $purchasemasterdata['reciept_status'] =='Received')
                        {
                           $approvebtn="d-none";
                           $acceptbtn="d-none";
                        }
                        if($purchasemasterdata['approvalflg']==1)
                        {
                           $approvebtn="d-none";
                           // $acceptbtn="d-none";
                        }
      
                            $msgcls = "";
                            $messagetext = "";
                            $enabledisablecls="";
                            $marginbottom = "mb-2";
                            $assignseccls = "";
                            $acceptsendbtncls="";
                            switch (true) {
                                case ($purchasemasterdata['reciept_status'] == "Received"):
                                    $messagetext = "Purchase Receipt Items Received";
                                    $statustext = "Received";
                                    $msgcls = "alert-success";
                                    $enabledisablecls ="";
                                break;
                                case ($purchasemasterdata['reciept_status'] == "Reverted"):
                                    $messagetext = "Purchase Order Reciept Reverted. Now you can Reassign & Update  or Recieve items from here";
                                    $statustext = "Reverted";
                                    $msgcls = "alert-warning";
                                    $enabledisablecls ="";
                                break;

                                case ($purchasemasterdata['prepared_flg'] == 0 && $purchasemasterdata['reciept_status'] != "Draft"):
                                    $msgcls = "d-none";
                                    $enabledisablecls ="d-none";
                                    $marginbottom = "";
                                    $assignseccls = "d-none";
                                break;

                                case ($purchasemasterdata['reciept_status'] == "Draft"):
                                    $messagetext = "";
                                    $statustext = "Draft";
                                    $msgcls = "alert-secondary";
                                    $enabledisablecls ="";
                                break;

                                case (($purchasemasterdata['approvalflg'] != 1 && $purchasemasterdata['prepared_flg'] == 1) || ($purchasemasterdata['reciept_status']=="Pending" && $purchasemasterdata['prepared_flg'] == 1)):
                                    $messagetext = "Purchase Receipt Created";
                                    $enabledisablecls ="";
                                    $msgcls = "alert-partial";
                                    $statustext = "Created";
                                    $acceptsendbtncls ="d-none";
                                break;


                                case ($purchasemasterdata['approved_by']!=$this->session->userdata('id') && $purchasemasterdata['reciept_status'] == "Assigned"):
                                $messagetext = "Please Accept the Purchase Order Reciept. Assigned Date : ".date('d-m-Y h:i:s A', strtotime($purchasemasterdata['approved_dt']));
                                $msgcls = "alert-assigned";
                                $statustext = "Assigned";
                                $enabledisablecls ="disable-class";
                                break;

                                case ($purchasemasterdata['approved_by']==$this->session->userdata('id') && $purchasemasterdata['reciept_status'] == "Assigned"):
                                $msgcls = "alert-assigned";
                                $messagetext = $assignedperson['name']." has not accepted. Assigned Date : ".date('d-m-Y h:i:s A', strtotime($purchasemasterdata['approved_dt']));
                                $statustext = "Assigned";
                                break;

                                
                                
                                default:
                                // No action needed for the default case
                                break;
                            }
                            $items_not_recieved="";
                            $payment_disable="";
                            if((!$purchasemasterdata['received_by']))
                            {
                                $items_not_recieved="disable-class";
                            }
                            if($purchasemasterdata['payment_status']=='Paid')
                            {
                                $payment_disable="disable-class";
                            }
                            if($statustext)
                            {
                            ?>    
                                <div class="btn-group alert text-center <?=$msgcls?>" role="alert">
                                    <?php echo $statustext; ?>
                                </div>
                            <?php
                            }
                            ?>
                            
                        <!-- ========================================= -->
                    </div>

                </div>
            </div>
      </div>
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
      <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
      </div>
   </div>
        <form method="post" id="data_form" enctype="multipart/form-data">
            <div class="card-content">
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <div class="message"></div>
                </div>
                <div class="card-body" >    
                     <!-- ========================= Buttons start ===================== -->
                        <div class="row wrapper white-bg page-heading <?=$marginbottom?>">
                            <div class="col-lg-4 col-md-9 col-sm-12 col-12">
                                <?php
                                    $validtoken = hash_hmac('ripemd160', 'p' . $purchasemasterdata['id'], $this->config->item('encryption_key'));
                                    $link = base_url('billing/purchase_reciept_preview?id=' . $purchasemasterdata['id'] . '&token=' . $validtoken);
                                    if ($invoice['status'] != 'canceled') { ?>
                                        <div class="title-action">

                                            <?php
                                                // echo "<pre>"; print_r($purchasemasterdata); die();
                                            ?>
                                            <a href='<?= base_url("purchase/purchase_receipt_payment?id=" . $purchasemasterdata['purchase_reciept_number'] . "&csd=" . $purchasemasterdata['supplier_id']) ?>' class="btn btn-sm  btn-secondary <?=$items_not_recieved?> <?=$payment_disable?> btn-crud" title="Make Payment"><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a>

                                            <!-- <a href="#part_payment" data-toggle="modal" data-remote="false" data-type="reminder"
                                                class="btn btn-sm  btn-secondary  <?php echo $enabledisablecls; ?>" title="Partial Payment"><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a> -->

                                            <!-- <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle <?php echo $enabledisablecls; ?>" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <span class="fa fa-envelope-o"></span> <?php echo $this->lang->line('Send') ?>
                                                </button>
                                                <div class="dropdown-menu"><a href="#sendEmail" data-toggle="modal"
                                                data-remote="false" class="dropdown-item sendbill <?php echo $enabledisablecls; ?>"
                                                data-type="purchase"  ><?php echo $this->lang->line('Purchase Request') ?></a>
                                                </div>
                                            </div>  -->
                                            <a href="#sendEmail" data-toggle="modal" data-remote="false" class="btn btn-sm btn-secondary sendbill <?php echo $enabledisablecls; ?> <?=$items_not_recieved?> btn-crud" title="Email"  data-type="purchase"><span class="fa fa-envelope-o"></span> <?php echo $this->lang->line('Send') ?></a>

                                            <a href="#sendSMS" data-toggle="modal" data-remote="false" class="btn btn-sm btn-secondary <?php echo $enabledisablecls; ?> <?=$items_not_recieved?>  btn-crud" title="SMS"><span class="fa fa-mobile"></span> <?php echo $this->lang->line('SMS') ?></a>
                                            <?php
                                                $reciptid= $costid;
                                                $validtoken = hash_hmac('ripemd160', 'p' . $costid, $this->config->item('encryption_key'));
                                            ?>
                                            
                                            <a href="<?=base_url("purchasereturns/create?id=$reciptid&token=$validtoken")?>"  class="btn btn-sm btn-secondary <?php echo $enabledisablecls; ?> <?=$items_not_recieved?>  btn-crud" title="Email"  data-type="purchase"><span class="fa fa-undo"></span> <?php echo $this->lang->line('Purchase Return') ?></a>


                                            
                                            <a href="#pop_model" data-toggle="modal" data-remote="false"
                                                class="btn btn-sm btn-secondary d-none <?php echo $enabledisablecls; ?>" title="Change Status"
                                                ><span class="fa fa-retweet"></span> <?php echo $this->lang->line('Change Status') ?></a>
                                            <a href="#cancel-bill" class="btn btn-sm btn-secondary d-none <?php echo $enabledisablecls; ?>" id="cancel-bill_p"><i class="fa fa-minus-circle"> </i> <?php echo $this->lang->line('Cancel') ?>
                                            </a>
                                            <!-- <a  class="btn btn-sm btn-secondary <?php echo $enabledisablecls; ?>"  target="_blank" href="<?= base_url('Invoices/costing?pid=' . $invoice['iid'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Purchase Receipt') ?></a> -->
                                            <?php
                                            if($purchasemasterdata['reciept_status']!='Draft')
                                            { ?>
                                            <!-- hide -->
                                            <input type="submit" class="btn btn-sm btn-secondary cancelreceipt-btn d-none <?=$items_not_recieved?>" value="<?php echo $this->lang->line('Cancel Purchase Receipt') ?>">
                                            <?php } ?>
                                        </div>
                                        <?php
                                            if ($invoice['multi'] > 0) {
                                            
                                                echo '<div class="tag tag-info text-xs-center mt-2">' . $this->lang->line('Payment currency is different') . '</div>';
                                            }
                                    } 
                                    else{
                                            echo '<h2 class="btn btn-oval btn-secondary">' . $this->lang->line('Cancelled') . '</h2>';
                                        } ?>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-center messagetext_class">
                                <?php 
                                if($messagetext)
                                { ?>
                                    <div class="btn-group alert text-center <?=$msgcls?>" role="alert">
                                        <?php echo $messagetext; ?>
                                    </div>
                                <?php } ?>         
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-12 text-lg-right text-md-right text-sm-left">
                                    <a href="<?php echo $link; ?>" class="btn btn-sm btn-secondary  btn-crud d-none <?=$assignseccls?>"  target="_blank"><i
                                        class="fa fa-globe"></i> <?php echo $this->lang->line('Public Preview') ?>
                                    </a>
                                    <div class="btn-group ">
                                            <button type="button" class="btn btn-sm btn-secondary btn-min-width dropdown-toggle  btn-crud <?php echo $enabledisablecls; ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                            class="fa fa-print"></i> <?php echo $this->lang->line('Print') ?>
                                            </button>
                                            <div class="dropdown-menu">
                                            <a class="dropdown-item  btn-crud" target="_blank" href="<?= base_url('billing/printporeciept?id=' . $purchasemasterdata['purchase_reciept_number'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Print') ?></a>
                                            <div class="dropdown-divider"></div>
                                                <a class="dropdown-item  btn-crud"
                                                href="<?= base_url('billing/printporeciept?id=' . $purchasemasterdata['purchase_reciept_number'] . '&token=' . $validtoken); ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>
                                            </div>
                                    </div>
                            </div>
                        </div>
                        <!-- ========================= Buttons start ===================== -->                
                    <div class="row" >  
                       <style>
                        .error1{
                            border: 1px solid #ff0000 !important;
                        }
                       </style>
                       <?php
                        
                        // $term = ($invoice['term'])?$invoice['term']:$validity['payment_terms'];
                        $supplier_id = $purchasemasterdata['supplier_id'];
                        $employee_id = $created_employee['id']; 
                        $headerclass= "d-none";
                        $pageclass= "page-header-data-section-dblock";
                        $btnlabel = "Create";
                        $createbtn_class= "btn-primary";
                        $receivebtn_class= "btn-secondary";
                        if($costid)
                        {
                                $headerclass = "page-header-data-section-dblock";
                                $pageclass   = "page-header-data-section";
                                $btnlabel = "Update";
                                $receivebtn_class= "btn-primary";
                                $createbtn_class= "btn-secondary";
                        }
                        
                        ?>
                            <div class="col-12">
                                <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                                            <h3  class="title-sub"><?php echo $this->lang->line('Purchase Receipt Details') ?> <i class="fa fa-angle-down"></i></h3>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 responsive-text-right quickview-scroll order-1 order-lg-2">
                                            <div class="quick-view-section">
                                                <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Supplier') ?></h4>
                                                <?php //echo "<b>".$invoice['name']."</b>"; ?>                                            
                                                <?php
                                                echo "<a class='expand-link' href='" . base_url('supplier/view?id=' . urlencode($supplier_id)) . "' target='_blank'><b>" . htmlspecialchars($purchasemasterdata['supplier_name']) . "</b></a>";
                                                ?>
                                                </div>
                                                <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Created') ?></h4>
                                                <?php echo "<p>".dateformat($invoice['invoicedate'])."</p>"; ?>
                                                </div>
                                                <div class="item-class text-center d-none">
                                                    <h4><?php echo $this->lang->line('Validity') ?></h4>
                                                    <?php echo "<p>".dateformat($invoiceduedate)."</p>"; ?>
                                                </div>
                                                
                                                <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Created By') ?></h4>
                                                <?php 
                                                    echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                                ?>
                                                </div>
                                                <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Total'); ?></h4>
                                                <?php echo "<p>".number_format($purchasemasterdata['bill_amount'],2)."</p>";?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="<?=$pageclass?>">
                            <div class="form-row">
                            
                                <input type="hidden" name="purchase_id" id="purchase_id" value="<?=$purchasemasterdata['purchase_id']?>">
                                <input type="hidden" name="purchase_number" id="purchase_number" value="<?=$purchasemasterdata['purchase_number']?>">
                                <input type="hidden" name="preparedflg" id="preparedflg" value="<?=$purchasemasterdata['prepared_flg']?>">
                                <input type="hidden" name="token" id="token" value="<?=$token?>">
                                <input type="hidden" name="purchase_tid" id="purchase_tid" value="<?=$purchaseorderdata['tid']?>">
                                <input type="hidden" name="receipt_id" id="receipt_id" value="<?=$purchasemasterdata['id']?>">
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-row">
                                        <div class="col-12">
                                            <input type="hidden" name="transaction_number" id="transaction_number" value="<?php echo $purchasemasterdata['transaction_number']; ?>">
                                            <label class="col-form-label"><?php echo $this->lang->line('Sale Point'); ?></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="frmclasss">
                                                <input type="text" class="form-control customer_name" name="salepoint_name" id="warehouse-search" placeholder="Enter Sale point name" autocomplete="off" value="<?php echo $purchasemasterdata['salepoint_name']; ?>" required readonly/>
                                                <div id="warehouse-search-result" class="warehouse-search-result"></div>
                                            </div>
                                        </div> 
                                        <div class="col-3">
                                            <input type="text" class="form-control" value="<?php echo $purchasemasterdata['salepoint_id']; ?>" name="salepoint_id" id="salepoint_id" autocomplete="off"  readonly/>
                                        </div> 
                                    </div>   
                                </div>   
                                <!-- ================== Ends ======================= -->
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="col-form-label"><?php echo 'Supplier'; ?></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="frmclasss">
                                                <input type="text" class="form-control supplier_name" name="supplier_name" value="<?php echo $purchasemasterdata['supplier_name']; ?>" id="supplier-search" placeholder="Enter Supplier name or phone or email" autocomplete="off" required readonly/>
                                                <div id="supplier-search-result" class="supplier-search-result"></div>
                                            </div>
                                        </div> 
                                        <div class="col-3">
                                            <input type="text" value="<?php echo $purchasemasterdata['supplier_id']; ?>" class="form-control" name="supplier_id" id="supplier_id" autocomplete="off" readonly/>
                                        </div> 
                                    </div>   
                                </div>   
                                <!-- ================== Ends ======================= -->
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="col-form-label" for="party_name"><?php echo 'Party Name'; ?></label>
                                        </div>
                                        <div class="col-12">
                                            <div class="">
                                                <input type="text" class="form-control customer_name <?=$reciept_status_class?>" title="Party Name" name="party_name" id="party_name" placeholder="Party Name" value="<?php echo $purchasemasterdata['party_name']; ?>"  data-original-value="<?php echo $purchasemasterdata['party_name']; ?>" autocomplete="off" />
                                            </div>
                                        </div> 
                                    </div>   
                                </div>   
                                <!-- ================== Ends ======================= -->
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="col-form-label" for="account-search"><?php echo 'Damage Claim A/c'; ?></label>
                                        </div> 
                                        <div class="col-8">
                                            <div class="frmclasss">
                                                <input type="text" class="form-control customer_name <?=$reciept_status_class?>" title="Damage Claim A/c" name="damageclaim_ac_name" id="account-search" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['damageclaim_ac_name']; ?>" data-original-value="<?php echo $purchasemasterdata['damageclaim_ac_name']; ?>"/>
                                            </div>
                                            <div id="account-search-result" class="account-search-result"></div>
                                        </div> 
                                        <div class="col-4">
                                            <input type="text" class="form-control" name="damageclaim_ac" id="damageclaim_ac" placeholder="" autocomplete="off" readonly value="<?php echo $purchasemasterdata['damageclaim_ac']; ?>"/>
                                        </div>
                                    </div>   
                                </div>   
                                <!-- ================== Ends ======================= -->
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="bill_number"><?php echo 'Bill #'; ?><span class="compulsoryfld">*</span></label>
                                    <input type="number" class="form-control <?=$reciept_status_class?>" title="Bill" name="bill_number" id="bill_number" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['bill_number']; ?>" data-original-value="<?php echo $purchasemasterdata['bill_number']; ?>" required/>                           
                                </div>                             
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="bill_date"><?php echo 'Bill Date'; ?><span class="compulsoryfld">*</span></label>
                                    <input type="date" class="form-control <?=$reciept_status_class?>" title="Bill Date" name="bill_date" id="bill_date" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['bill_date']; ?>" data-original-value="<?php echo $purchasemasterdata['bill_date']; ?>"/>
                                </div>       
                                <!-- ================== Ends ======================= -->
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Currency'; ?></label>
                                    <input type="text" class="form-control <?=$reciept_status_class?>" name="currency_id" id="currency_id" autocomplete="off" value="<?php echo $purchasemasterdata['currency_id']; ?>" readonly/>
                                    <!-- <select name="currency_id" id="currency_id" class="form-control"></select> -->
                                </div> 
                                <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"><?php echo 'Currency Rate'; ?></label>
                                        <input type="number" class="form-control" name="currency_rate" id="currency_rate" placeholder="" autocomplete="off" value="<?php echo number_format($purchasemasterdata['currency_rate'],2); ?>" readonly/>
                                </div>     
                                <!-- ================== Ends ======================= -->
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="bill_description"><?php echo 'Description'; ?></label>
                                        <textarea name="bill_description" title="Description" id="bill_description" class="form-textarea <?=$reciept_status_class?>" data-original-value="<?php echo $purchasemasterdata['bill_description']; ?>"><?php echo $purchasemasterdata['bill_description']; ?></textarea>
                                </div>     
                                <!-- ================== Ends ======================= -->
                        
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo $this->lang->line('Purchase Type') ?><span class="compulsoryfld">*</span></label>
                                    <div class="frmSearch1">
                                        <input type="text" class="form-control customer_name" name="doctype" id="doctype"     autocomplete="off" value="<?php echo $purchasemasterdata['purchase_type']; ?>" readonly required title="Purchase Type"/>
                                    </div>
                                </div>  
                                <!-- ================== ends ===================== -->                                 
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"><?php echo 'Purchase Receipt No.'; ?><span class="compulsoryfld">*</span></label>
                                        <div class="frmSearch1">
                                            <input type="text" class="form-control customer_name" name="srv" id="srv" autocomplete="off" value="<?php echo $srvNumber; ?>" required readonly/>
                                        </div>
                                </div>  
                                <!-- ================== ends ===================== -->                                 
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="srvdate"><?php echo 'Purchase Receipt Date'; ?><span class="compulsoryfld">*</span></label>
                                        <div class="frmSearch1">
                                            <input type="date" class="form-control <?=$reciept_status_class?>" name="srvdate" id="srvdate"
                                                    autocomplete="off" value="<?php echo $purchasemasterdata['purchase_receipt_date']; ?>" data-original-value="<?php echo $purchasemasterdata['purchase_receipt_date']; ?>" required/>
                                        </div>
                                </div>  
                                <!-- ================== ends ===================== -->                                 
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"><?php echo 'Purchase Amount'; ?></label>
                                        <div class="frmSearch1">
                                            <input type="text" onkeypress="return isNumber(event)" class="form-control <?=$reciept_status_class?>" name="purchase_amount" id="purchase_amount"
                                                    autocomplete="off" value="<?php echo number_format($purchasemasterdata['purchase_amount'],2); ?>" readonly />
                                        </div>
                                </div>  
                                <!-- ================== ends ===================== -->    
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label" for="bill_amount"><?php echo 'Bill Amount '; ?><span class="compulsoryfld">*</span></label>
                                        <div class="frmSearch1">
                                            <?php $billamt = ($purchasemasterdata['bill_amount']>0) ? number_format($purchasemasterdata['bill_amount']):"";
                                            
                                            ?>
                                            <input type="text" onkeypress="return isNumber(event)" class="form-control disable-class" name="bill_amount" id="bill_amount" autocomplete="off" value="<?php echo ($billamt); ?>"  data-original-value="<?php echo $billamt; ?>" required/>
                                        </div>
                                </div>  
                                <!-- ================== ends ===================== -->                                     
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <?php
                                    $costfactor = ($purchasemasterdata['cost_factor'])?$purchasemasterdata['cost_factor']:"1.00";
                                    ?>
                                        <label class="col-form-label"><?php echo 'Cost Factor'; ?></label>
                                        <div class="frmSearch1">
                                            <input type="number" class="form-control" name="cost_factor" id="cost_factor"
                                                    autocomplete="off" value="<?php echo $costfactor; ?>" required readonly/>
                                        </div>
                                </div>  
                                <!-- ================== ends ===================== -->                                 
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label class="col-form-label"><?php echo 'Payment Date'; ?></label>
                                        <div class="frmSearch1">
                                            <input type="date" class="form-control" name="payment_date" id="payment_date"
                                                    autocomplete="off" value="<?php echo $purchasemasterdata['payment_date']; ?>" />
                                        </div>
                                </div>  
                                <!-- ================== ends ===================== --> 
                                <!-- ================== starts ===================== -->
                                <?php if (isset($employee)){?> 
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label for="employee" class="col-form-label"><?php echo $this->lang->line('Assign to') ?><?=$compulsory?></label>
                                        <select name="employee" id="employee" class=" col form-control <?=$assigncls?> <?=$frmselectdisable?>" <?=$required?> <?=$frmelmentdisable?>>
                                        <?php echo '<option value="">Select an Employee</option>'; ?>
                                                <?php foreach ($employee as $row) {
                                                    $sel = "";
                                                    if($purchasemasterdata['assign_to']==$row['id']){
                                                    $sel="Selected";
                                                    }
                                                    echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['name'].'</option>';
                                                } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <!-- ================== ends ===================== -->                                  
                                <!-- ================== starts ===================== -->
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label" for="note"><?php echo 'Note'; ?></label>
                                    <div class="frmSearch1">
                                        <textarea name="note" id="note" class="form-textarea <?=$reciept_status_class?>" data-original-value="<?php echo $purchasemasterdata['note']; ?>" title="Note"><?php echo $purchasemasterdata['note']; ?></textarea>
                                    </div>
                                </div>
                                <!-- ================== ends ===================== -->                                  
                                                            
                                <!-- ================== starts ===================== -->
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                    </div>
                                <!-- ================== ends ===================== -->                                 
                                <!-- ================== starts ===================== -->
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <?php $cost_per_item = (!empty($purchaseexpensesdata['0']['cost_per_item']) && $purchaseexpensesdata['0']['cost_per_item']>0) ? $purchaseexpensesdata['0']['cost_per_item'] :0.00; ?>
                                        <label for="employee1" class="fsize-17 mt-2 text-color-for-cost"><?php echo $this->lang->line('Additional cost per item') ?> : <strong id="additional_cost_per_item"><?=$cost_per_item?></strong></label>
                                        <input type="hidden" name="cost_per_item" id="cost_per_item" value="<?=$cost_per_item?>">
                                    </div>
                                <!-- ================== ends ===================== -->                                 
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- ===================Draft save section ==================== -->
                <?php   
                    if($purchasemasterdata['reciept_status']=='Draft')
                    { ?>
                        <!-- <div class="col-12">
                        <div class="alert alert-warning alert-success fade show" role="alert">
                            <strong>Draft</strong> Saved Successfully.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        </div> -->
                    <?php } ?>
                <!-- ===================Draft save section ==================== -->
                <input type="hidden" name="costmaserid" id="costmaserid" value="<?=$costid?>">
                <!-- ==================== tab secction starts ================ -->
                 
                <div class="card-body bg-white">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" id="item_li">
                            <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                aria-controls="tab1" href="#tab1" role="tab"
                                aria-selected="true"><?php echo $this->lang->line('Item Details') ?></a>
                        </li>
                        <li class="nav-item" id="costing_li">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                href="#tab2" role="tab"
                                aria-selected="false"><?php echo $this->lang->line('Expenses') ?></a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab3"
                                href="#tab3" role="tab" aria-selected="false"><?php echo $this->lang->line('Payments') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab4"
                                href="#tab4" role="tab" aria-selected="false"><?php echo $this->lang->line('Journals') ?></a>
                        </li>

                    </ul>
                    <div class="tab-content pt-1">
                        <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1" style="width: 100%; overflow-x: auto;">
                           <div class="table-scroll">
                                 <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width:50px !important;text-align:center">Sl#</th>
                                            <th ><?php echo $this->lang->line('Code'); ?></th>
                                            <th><?php echo $this->lang->line('Item Name'); ?></th>
                                            <th><?php echo $this->lang->line('Current Cost'); ?></th>
                                            <th><?php echo $this->lang->line('Unit'); ?></th>         
                                            
                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th class="d-none"><?php echo $this->lang->line('Unit Price'); ?></th>
                                            <th><?php echo $this->lang->line('New Cost'); ?></th>
                                            <th><?php echo $this->lang->line('Ordered')."<br>".$this->lang->line('Quantity'); ?></th>
                                            <th><?php echo $this->lang->line('Received')."<br>".$this->lang->line('Quantity'); ?></th>

                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th style="text-align:right" class="d-none"><?php echo $this->lang->line('Bill Amount'); ?></th>

                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th class="d-none"><?php echo $this->lang->line('Free of Charge(FOC)'); ?></th>

                                            <th><?php echo $this->lang->line('Damaged')."<br>".$this->lang->line('Quantity'); ?></th>
                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th  class="d-none"><?php echo $this->lang->line('Sales Price'); ?></th>
                                            <!-- <th style="width:5%;">Disc%</th> -->

                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th class="d-none"><?php echo $this->lang->line('Discount'); ?></th>
                                            <th  class="d-none"><?php echo $this->lang->line('Net Amount'); ?></th>

                                            <th class="text-right"><?php echo $this->lang->line('Net Amount'); //echo "(".$this->config->item('currency').")"; ?></th>
                                            <th><?php echo $this->lang->line('Action'); ?></th>
                                            <!-- <th style="width:45%;"><?php echo $this->lang->line('Description'); ?></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $j=0;
                                    $totalamount =0;
                                    $totalnetamount =0;
                                    $totaltax=0;
                                    $totaldiscount=0;
                                    $totalqaramount = 0;
                                    // echo "<pre>"; print_r($purchaseitemsdata);
                                    // echo $reciept_number;
                                    if(!empty($purchaseitemsdata))
                                    {
                                    
                                        echo '<input type="hidden" name="totalproducts" id="totalproducts" value="'.count($purchaseitemsdata).'">';
                                        foreach($purchaseitemsdata as $item){
                                            $receivedQty = ($reciept_number) ? $item['product_quantity_recieved'] : ($item['ordered_quantity']-$item['received_quantity']);
                                            $amount = $item['price']*$receivedQty;
                                            $item['qaramount'] = ($item['qaramount']) ? $item['qaramount'] :  $purchasemasterdata['currency_rate']*$item['netamount'];
                                            
                                            $totalamount = $totalamount+$item['amount'];
                                            $totalqaramount = $totalqaramount+$item['qaramount'];
                                            $totaldiscount = $totaldiscount+$item['discountamount'];
                                            $totalnetamount = $totalnetamount+$item['netamount'];
                                            $account_code = $item['account_code'];
                                            $product_name_with_code = $item['product_name'].'('.$item['product_code'].') - ';
                                            echo '<tr>';
                                            echo '<td class="text-center">'.++$j.'<input type="hidden" class="form-control" name="product_id[]" id="product_id-'.$j.'" value="'.$item['product_id'].'" readonly></td>'; 
                                            
                                            echo '<td><input type="text" name="product_code[]" id="product_code-'.$j.'" class="form-control" value="'.$item['product_code'].'" style="width:auto;" readonly><input type="hidden" class="form-control" name="account_code[]" id="account_code-'.$j.'" value="'.$account_code.'" readonly></td>';

                                            echo '<td><input type="text" class="form-control" name="product_name[]" id="productname-'.$j.'" value="'.$item['product_name'].'" style="width:auto;" readonly></td>';
                                        
                                            //current cost
                                            echo '<td class="text-right">'.number_format($item['cost'],2).'<input type="hidden" class="form-control" name="old_cost[]" id="old_cost-'.$j.'" value="'.$item['cost'].'" readonly></td>';



                                            echo '<td><input type="text" name="product_unit[]" id="product_unit-'.$j.'" class="form-control responsive-width-elements" value="'.$item['product_unit'].'" readonly></td>';
                                            
                                            //erp2024 hide 23-03-2025
                                            echo '<td class="text-right d-none"><strong class="item-data">'.number_format($item['price'],2).'</strong></td>';

                                            //new cost
                                            $latestcost = $cost_per_item + $item['price'];
                                        
                                            // echo '<td class="text-right"><strong class="item-data"  id="newcostabel-'.$j.'">'.number_format($latestcost,2).'</strong><input type="hidden" name="newcost[]" id="newcost-'.$j.'" class="form-control"  style="width:130px;" value="'.$latestcost.'" readonly></td>';

                                            echo '<td class="text-right"><strong class="item-data"  id="newcostabel-'.$j.'">'.number_format($item['price'],2).'</strong><input type="hidden" name="newcost[]" id="newcost-'.$j.'" class="form-control"  style="width:auto;" value="'.number_format($item['price'],2).'" readonly><input type="hidden" name="price[]" id="price-'.$j.'" class="form-control"   value="'.$item['price'].'" readonly></td>';
                                            // echo '<td class="text-right"><strong class="item-data"  id="newcostabel-'.$j.'">'.number_format($item['cost'],2).'</strong><input type="hidden" name="newcost[]" id="newcost-'.$j.'" class="form-control"  style="width:130px;" value="'.number_format($item['cost'],2).'" readonly></td>';

                                            echo '<td><input type="number" name="product_qty[]" id="product_qty-'.$j.'" class="form-control" value="'.intval($item['ordered_quantity']).'"  readonly></td>';

                                            //ordered_quantity
                                            echo '<td><input type="number" name="product_qty_recieved[]" id="product_qty_recieved-'.$j.'" class="form-control received_product '.$reciept_status_class.'"   onkeyup="recievedqty_check('.$j.'), productReceivedQty('.$j.'),productwise_costing('.$j.')" value="'.intval($receivedQty).'" title="'.$product_name_with_code.'Received Quantity" data-original-value="'.intval($receivedQty).'" data-id="'.$j.'" title="Received Quantity"></td>';

                                            // echo '<td><input type="number" name="product_qty_recieved[]" id="product_qty_recieved-'.$j.'" class="form-control received_product '.$reciept_status_class.'"   onkeyup="recievedqty_check('.$j.'), productReceivedQty('.$j.'),productwise_costing('.$j.')" value="'.intval($item['product_quantity_recieved']).'" title="'.$product_name_with_code.'Received Quantity" data-original-value="'.intval($item['product_quantity_recieved']).'" data-id="'.$j.'" title="Received Quantity"></td>';

                                            //erp2024 hide 23-03-2025
                                            echo '<td class="d-none"><input type="text" name="amount[]" id="amount-'.$j.'" class="form-control text-right"   value="'.number_format($amount,2).'" readonly></td>';

                                            //erp2024 hide 23-03-2025
                                            echo '<td class="d-none"><input type="number" name="product_foc[]" id="product_foc-'.$j.'" class="form-control text-right '.$reciept_status_class.'"  "onkeyup="productFoc('.$j.')" title="'.$product_name_with_code.'FOC" value="'.$item['product_foc'].'" data-original-value="'.$item['product_foc'].'" title="FOC"></td>';

                                            echo '<td><input type="number" name="damage[]" id="damage-'.$j.'" class="form-control '.$reciept_status_class.'"   onkeyup="damagedqty_check('.$j.'),productDamage('.$j.')" title="'.$product_name_with_code.'Damaged Quantity" value="'.intval($item['damaged_quantity']).'" data-original-value="'.intval($item['damaged_quantity']).'" title="Damage Quantity"></td>';

                                            //erp2024 hide 23-03-2025
                                            $saleprice = ($item['saleprice']==0)?$item['saleprice']:"";
                                            echo '<td class="d-none"><input type="number" name="saleprice[]" id="saleprice-'.$j.'" class="form-control text-right"   value="'.number_format($saleprice,2).'" readonly></td>';


                                            // echo '<td><input type="number" name="discountperc[]" id="discountperc-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['discountperc'].'" onkeyup="productDiscount('.$j.')"></td>';

                                            //erp2024 hide 23-03-2025
                                            echo '<td class="d-none"><input type="text" name="discountamount[]" id="discountamount-'.$j.'" class="form-control text-right"   value="'.number_format($item['discountamount'],2).'" readonly></td>';

                                            //erp2024 hide 23-03-2025
                                            echo '<td class="d-none"><input type="text" name="netamount[]" id="netamount-'.$j.'" class="form-control text-right"   value="'.number_format($item['netamount'],2).'" readonly></td>';

                                            echo '<td><input type="text" name="qaramount[]" id="qaramount-'.$j.'" class="form-control text-right responsive-width-elements"   value='.number_format($item['qaramount'],2).' readonly></td>';
                                            echo '<td><button type="button" class="btn btn-crud btn-sm btn-secondary '.$reciept_status_class.'" name="product_cost_update_btn" id="product_cost_update_btn'.$j.'" onclick="update_product_cost('.$j.')">Update Cost</button></td>';

                                            // echo '<td><textarea name="" id="" class="form-control"  style="width:auto;">'.$item['description'].'</textarea></td>';

                                            echo '</tr>';
                                        }
                                        
                                        ?>
                                        <tr>
                                            <th class="no-border" colspan="9" style="text-align:right;">Total</th>
                                            <th class="no-border text-right  d-none"><span id="totalamount"><?php echo number_format($totalamount,2); ?></span></th>
                                            <th class="no-border  d-none"></th><th class="no-border  d-none"></th><th class="no-border  d-none"></th>
                                            <th class="no-border text-right  d-none"><span id="totaldiscount"><?php echo number_format($totaldiscount,2); ?></span></th>
                                            <th class="no-border text-right d-none"><span id="totalnetamount"><?php echo number_format($totalnetamount,2); ?></span></th>
                                            <th class="no-border text-right"><span id="totalqaramount"><?php echo number_format($totalqaramount,2); ?></span></th>

                                            <!-- <th class="no-border" colspan="9" style="text-align:right;">Total</th>
                                            <th class="no-border text-right"><span id="totalqaramount"><?php echo number_format($totalqaramount,2); ?></span></th> -->
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                           </div>
                        </div>
                        <div class="tab-pane saman-row" id="tab2" role="tabpanel" aria-labelledby="base-tab2" style="width: 100%; overflow-x: auto;">                         

                        
                            <div id="saman-row" class="table-scroll">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                        <tr class="item_header bg-gradient-directional-blue white">
                                            <th style="width:5% !important; padding-left:10px;">Sl#</th>
                                            <th style="width:20% !important; padding-left:10px;">Expenses</th>
                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th class="d-none" style="width:10%; padding-left:10px;">Expenses #</th>
                                            <th style="width:15%; padding-left:10px;">Account Name</th>
                                            <th style="width:10%; padding-left:10px;">Payable A/c</th>
                                            <th style="width:250px; padding-left:10px;">Bill No.</th>
                                            <th style="width:10%; padding-left:10px;">Bill Date</th>
                                            <th style="width:10%; padding-left:10px;">Amount</th>
                                            <!-- erp2024 23-03-2025 hide  -->
                                            <th class="d-none" style="width:10%; padding-left:10px;">Currency</th>
                                            <th class="d-none" style="width:10%; padding-left:10px;">Currency Rate</th>
                                            <th class="d-none"  style="width:10%; padding-left:10px;">Net Amt.</th>
                                            <th class="d-none" style="width:10%; padding-left:10px;">Net Amt <?php //echo "(".$this->config->item('currency').")"; ?></th>
                                            <th style="width:45%; padding-left:10px;">Remarks</th>
                                            <th style="padding-left:10px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php if(count($purchaseexpensesdata)>0){ $ganakval = count($purchaseexpensesdata); } else{ $ganakval = 0; }
                                            $costamt = 0;
                                            $costnetamt = 0;
                                            $costqaramt = 0;
                                             if(empty($purchaseexpensesdata)){ ?>
                                                <tr class="startRow1">
                                                <td class="text-center serial-number">1</td>
                                                <td><input type="text" name="expense_name[]" id="expense_name-0" class="form-control expense_name1 <?=$reciept_status_class?>" style="width:150px;" data-id="0" placeholder="Expense name" title="Expense name"></td>

                                                <td class="d-none"><input type="text" name="expense_id[]" id="expense_id-0" class="form-control"  readonly></td>

                                                <td><input type="text" name="payable_acc[]" id="payable_acc-0" class="form-control <?=$reciept_status_class?>" style="width:230px;" placeholder="Account Name or Number"  title="Account"></td>

                                                <td><input type="number" name="payable_acc_no[]" id="payable_acc_no-0" class="form-control"  readonly title="Account Number"></td>

                                                <td><input type="text" name="bill_number_cost[]" id="bill_number_cost-0" class="form-control billnumber <?=$reciept_status_class?>" style="width:150px;" value="<?=$purchasemasterdata['bill_number']?>"></td>

                                                <td><input type="date" name="bill_date_cost[]" id="bill_date_cost-0" class="form-control billdate <?=$reciept_status_class?>" style="width:auto;" title="Bill Date"></td>

                                                <td><input type="number" name="costing_amount[]" id="costing_amount-0" class="form-control text-right <?=$reciept_status_class?>" style="width:100px;" onkeyup="costingamount(0)" title="Costing Amount"></td>

                                                <!-- erp2024 hide 23-03-2025 -->
                                                <td class="d-none"><input type="text" name="currency_cost[]" id="currency_cost-0" value="<?php echo $purchasemasterdata['currency_id']; ?>" class="form-control" readonly></td>
                                                <!-- erp2024 hide 23-03-2025 -->
                                                <td class="d-none"><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-0" value="<?php echo $purchasemasterdata['currency_rate']; ?>" class="form-control text-right"  readonly></td>
                                                  <!-- erp2024 hide 23-03-2025 -->
                                                <td class="d-none"><input type="number" name="costing_amount_net[]" id="costing_amount_net-0" class="form-control text-right" style="width:100px;" readonly></td>
                                                <!-- erp2024 hide 23-03-2025 -->
                                                <td class="d-none"><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-0" class="form-control text-right" style="width:100px;" readonly></td>

                                                <td><textarea name="remarks[]" id="remarks-0" class="form-control <?=$reciept_status_class?>" style="width:250px;" title="Remarks"></textarea></td>

                                                <td><button type="button" data-rowid="0" class="btn-sm btn-default btn-crud removeProd <?=$reciept_status_class?> border-0" title="Remove"> <i class="fa fa-trash"></i> </button></td>
                                                </tr>
                                            <?php }
                                            else{
                                                $k=0;
                                                $l=1;
                                                foreach($purchaseexpensesdata as $row){
                                                    $costamt = $costamt+$row['costing_amount'];
                                                    $costnetamt = $costnetamt+$row['costing_amount_net'];
                                                    $costqaramt = $costqaramt+$row['costing_amount_qar'];
                                                    echo '<tr class="startRow1">';
                                                    echo '<td class="text-center serial-number">'.$l++.'</td>';
                                                    echo '<td><input type="text" name="expense_name[]" id="expense_name-'.$k.'" class="form-control '.$reciept_status_class.'" style="width:150px;" data-id="'.$k.'" placeholder="Expense name" onkeyup="expensedynamicsearch('.$k.')" title="Expense"  data-original-value="'.$row['expense_name'].'" value="'.$row['expense_name'].'"></td>';
                                                    echo '<td class="d-none"><input type="text" name="expense_id[]" id="expense_id-'.$k.'" class="form-control '.$reciept_status_class.'"  style="width:100px;" readonly value="'.$row['expense_id'].'"></td>';

                                                    echo '<td><input type="text" name="payable_acc[]" id="payable_acc-'.$k.'" class="form-control '.$reciept_status_class.'" style="width:250px;" placeholder="Account Name or Number" onkeyup="accountdynamicsearch('.$k.')" data-id="'.$k.'" value="'.$row['payable_account'].'"  title="Account Name" data-original-value="'.$row['payable_account'].'"></td>';

                                                    echo '<td><input type="number" name="payable_acc_no[]" id="payable_acc_no-'.$k.'" class="form-control" style="width:150px;" value="'.$row['payable_account_number'].'" readonly></td>';

                                                    echo '<td><input type="number" name="bill_number_cost[]" id="bill_number_cost-'.$k.'" class="form-control billnumber" style="width:150px;"  value="'.$row['bill_number_cost'].'" readonly></td>';

                                                    echo '<td><input type="date" name="bill_date_cost[]" id="bill_date_cost-'.$k.'" class="form-control '.$reciept_status_class.'" style="width:150px;" value="'.$row['bill_date_cost'].'" data-original-value="'.$row['bill_date_cost'].'" title="Bill Date"></td>';
                                                   
                                                    echo ' <td class="d-none"><input type="number" name="costing_amount[]" id="costing_amount-'.$k.'" class="form-control text-right '.$reciept_status_class.'" style="width:100px;" onkeyup="costingamount('.$k.')"  value="'.($row['costing_amount']).'" title="Expense Amount" data-original-value="'.$row['costing_amount'].'" ></td>';

                                                    echo '<td class="d-none"><input type="text" name="currency_cost[]" id="currency_cost-'.$k.'"  value="'.$row['currency_cost'].'" class="form-control" style="width:150px;" readonly></td>';

                                                    echo '<td class="d-none"><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-'.$k.'" value="'.number_format($row['currency_rate_cost'],2).'" class="form-control text-right" style="width:150px;" readonly></td>';

                                                    echo '<td><input type="text" name="costing_amount_net[]" id="costing_amount_net-'.$k.'" class="form-control text-right" style="width:100px;" readonly value="'.number_format($row['costing_amount_net'],2).'"></td>';

                                                    echo '<td class="d-none"><input type="text" name="costing_amount_qar[]" id="costing_amount_qar-'.$k.'" class="form-control text-right" style="width:100px;" readonly value="'.number_format($row['costing_amount_qar'],2).'"></td>';

                                                    echo '<td><textarea name="remarks[]" id="remarks-'.$k.'" class="form-control" style="width:250px;" title="remarks" data-original-value="'.$row['remarks'].'">'.$row['remarks'].'</textarea></td>';
                                                    echo '<td><button type="button" data-rowid="' . $i . '" class="btn-sm btn-default removeProd border-0 '.$reciept_status_class.'" title="Remove"> <i class="fa fa-trash"></i> </button></td>';
                                                    echo '</tr>';
                                                    $k++;
                                                }
                                            } ?>
                                       
                                        <tr class="last-item-row sub_c no-border" >
                                            <td class="no-border"></td>
                                            <td class="add-row no-border">
                                                <button type="button" class="btn btn-secondary btn-crud <?=$reciept_status_class?>" aria-label="Left Align"
                                                        id="addcosting">
                                                    <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                                </button>
                                            </td>
                                            <td colspan="7" class="no-border"></td>
                                        </tr>
                                        <tr style="background:none !important;">
                                            
                                            <th colspan="5" class="no-border" ></th>
                                            <th class="no-border text-right">Total</th>
                                            <th class="no-border text-right"><span id="costingAmounts"><?=number_format($costamt,2)?></span></th>
                                            <!-- <th class="no-border d-none"></th> -->
                                            <!-- <th class="no-border d-none"></th> -->
                                            <th class="no-border d-none text-right"><span id="costingNetAmounts"><?=number_format($costnetamt,2)?></span></th>
                                            <th class="no-border d-none text-right"><span id="costingNetAmountQar"><?=number_format($costqaramt,2)?></span></th> 
                                            <!-- <th class="no-border d-none"></th> -->
                                            <!-- <th class="no-border d-none"></th> -->
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" value="expense_search" id="billtype">
                                
                                <input type="hidden" value="<?=$ganakval?>" name="counter" id="ganak">
                            </div>
                    
                        </div>

                         <!-- =================================================================== -->
                         <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                           <div class="row">
                              <div class="col-12">
                                    <!-- ===================================================== -->
                                    <div class="table-container table-scroll">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th style="width:3%;">#</th>
                                                   <th><?php echo $this->lang->line('Date') ?></th>
                                                   <th><?php echo $this->lang->line('Relation') ?></th>
                                                   <th  class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                                   <th><?php echo $this->lang->line('Payment Method') ?></th>
                                                   <th><?php echo $this->lang->line('Supplier') ?></th>
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
                                                            <a class='btn btn-sm btn-secondary' title='Edit' href='" . base_url('transactions/banking_transaction?ref=' . $relation) . "'>
                                                                    <span class='fa fa-pencil'></span>
                                                                </a>
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
                      <!-- =================================================================== -->
                       <div class="tab-pane" id="tab4" role="tabpanel" aria-labelledby="base-tab4">
                           <div class="row">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p><?php echo $this->lang->line('Journals are') ?></p>
                                    <!-- ===================================================== -->
                                    <div class="table-container table-scroll">
                                       <table id="acctable" class="table table-striped table-bordered zero-configuration dataTable">
                                             <thead>
                                                <tr>
                                                   <th style="width:3%;">#</th>
                                                   <th><?php echo $this->lang->line('Date') ?></th>
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
                      <!-- =================================================================== -->
                    </div>
                <!-- ==================== tab secction ends ================== -->
                 
            </div>
            <div class="col-12 row mb-3">                
                  <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                  
                     <?php 
                      if( ($purchaserecipts) && ($purchasemasterdata['reciept_status']!='Draft'))
                      { ?>
                         <!-- hide -->
                        <input type="submit" class="btn btn-crud btn-lg btn-secondary cancelreceipt-btn d-none" value="<?php echo $this->lang->line('Cancel Purchase Receipt') ?>">
                     <?php }
                        $draftcls ="";
                        if(((!empty($purchasemasterdata['approved_by'])) && ($purchasemasterdata['approved_by']!=$this->session->userdata('id') && $purchasemasterdata['prepared_flg']==1) && ($purchasemasterdata['approvalflg']=='1')))
                        {
                            $draftcls ="d-none";
                        ?>
                        
                        <button type="button" class="btn btn-crud btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?>" id="revert-btn"><?php echo $this->lang->line('Revert To the Assigned Employee') ?></button>&nbsp;
                        <?php }

                        if(($purchaserecipts) && ((!empty($purchasemasterdata['approved_by'])) && ($purchasemasterdata['approved_by']==$this->session->userdata('id') && $purchasemasterdata['prepared_flg']==1) && ($purchasemasterdata['approvalflg']=='1')))
                        {
                            $draftcls ="";
                            $revertcls = ($purchasemasterdata['reciept_status']=='Reverted') ? "disable-class" :"";
                        ?>
                            <button type="button" class="btn btn-crud btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?> <?=$revertcls?>" id="revert-by-admin-btn"><?php echo $this->lang->line('Revert To the Assigned Employee') ?></button>&nbsp;
                        <?php }
                        ?>
                         <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn <?=$draftcls?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Save As Draft') ?>" id="submit-purchase-orderbtn-draft" data-loading-text="Creating...">
                  </div> 
                  <div class="col-lg-7 col-md-7 col-sm-12 col-12 responsive-text-right" >
                   
                    <?php
                        if($purchasemasterdata['prepared_flg']!=1){ ?>
                        <!-- <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn <?=$generatebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Prepared') ?>" id="savebtn" data-loading-text="Creating..."> -->
                        <?php } ?>
                        <input type="submit" class="btn btn-crud btn-lg <?=$createbtn_class?> sub-btn <?=$generatebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line($btnlabel) ?>" id="prepared_btn" data-loading-text="Creating...">

                        <input type="submit" class="btn btn-crud btn-lg btn-secondary reciept-approve-btn <?=$approvebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Assign & Approve') ?>" data-loading-text="Creating...">
                        <!-- <input type="submit" class="btn btn-crud btn-lg btn-primary recieve-items-btn <?=$approvebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Recieve Items') ?>" data-loading-text="Creating..."> -->
                        <?php
                        if(($purchaserecipts) && ($purchasemasterdata['approved_by']==$this->session->userdata('id')  &&  $purchasemasterdata['approvalflg']=='1')){
                            ?>
                            <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn reciept-approve-btn <?=$accpetthenhide?>" value="<?php echo $this->lang->line('Reassign & Update') ?>">
                           
                        <?php }

                        if((!empty($purchasemasterdata['approved_by'])) && ($purchasemasterdata['approved_by']!=$this->session->userdata('id') && $purchasemasterdata['prepared_flg']==1))
                        { ?>  
                            <button type="button" class="btn btn-crud btn-lg btn-primary <?=$assign_personcls?> <?=$generatebtn?> <?=$accpetthenhide?> <?=$acceptsendbtncls?>" id="reciept-accept-btn"><?php echo $this->lang->line('Accept & Send') ?></button>&nbsp;
                        <?php } ?>

                        <input type="submit" class="btn btn-crud btn-lg <?=$receivebtn_class?> recieve-items-btn <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Recieve Items') ?>" data-loading-text="">
                    </div>         
            </div>
            
        </form>
       
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
            <form class="payment">
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
                  <button type="button" class="btn btn-secondary"
                     data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                  <input type="hidden" name="cid" value="<?php echo $invoice['cid'] ?>"><input type="hidden"
                     name="cname"
                     value="<?php echo $invoice['name'] ?>">
                  <button type="button" class="btn btn-primary"
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
            <form class="cancelbill">
               <div class="row">
                  <div class="col-12">
                     <?php echo $this->lang->line('this action! Are you sure') ?>
                  </div>
               </div>
               <div class="modal-footer mt-1">
                  <input type="hidden" class="form-control"
                     name="tid" value="<?php echo $invoice['iid'] ?>">
                  <button type="button" class="btn btn-secondary"
                     data-dismiss="modal"> <?php echo $this->lang->line('Close') ?></button>
                  <button type="button" class="btn btn-primary"
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
            <form id="sendbill">
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
            <button type="button" class="btn btn-secondary"
               data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
            <button type="button" class="btn btn-primary"
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
            <form id="form_model">
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
                  <button type="button" class="btn btn-secondary"
                     data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                  <input type="hidden" id="action-url" value="purchase/update_status">
                  <button type="button" class="btn btn-primary"
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
            <form id="sendsms">
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
            <button type="button" class="btn btn-secondary"
               data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            <button type="button" class="btn btn-primary"
               id="submitSMS"><?php echo $this->lang->line('Send'); ?></button>
         </div>
      </div>
   </div>
</div>

<script>
$(document).ready(function() {

});



$("#account-search").keyup(function () {
    $.ajax({
        type: "POST",
        url: baseurl + 'CostingCalculation/accountsearch',
        data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
        beforeSend: function () {
            $("#account-search").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
        },
        success: function (data) {
            $("#account-search-result").show();
            $("#account-search-result").html(data);
            $("#account-search").css("background", "none");

        }
    });
});
function selectedAccount(cid, holder,accno) {
    $('#account-search').val(holder);
    $('#damageclaim_ac').val(accno);
    $("#account-search-result").hide();
}

function productReceivedQty(j) {
    
    var receivedQty = parseFloat($("#product_qty_recieved-"+j).val());
    // var price = parseFloat($("#newcost-"+j).val());
    var price = parseFloat($("#price-"+j).val());
    if (!isNaN(receivedQty) && !isNaN(price)) {
        var amount = receivedQty * price;
        $("#amount-"+j).val(amount);
    }
    else{
        $("#amount-"+j).val(0);
    }
    calculateValues();
}
function productFoc(j) {
    var FOCAmt = $("#product_foc-"+j).val();
    calculateValues();
}
function productDamage(j) {
    var damageAmt = $("#damage-"+j).val();
    // calculateValues();
}
function productDiscount(j) {
    var discountPerc = parseFloat($("#discountperc-"+j).val());
    var netAmount = parseFloat($("#amount-"+j).val());
    if (!isNaN(discountPerc) && !isNaN(netAmount)) {
        var discountAmount = (netAmount * discountPerc) / 100;
        $("#discountamount-"+j).val(discountAmount);
    } else {
        $("#discountamount-"+j).val(0);
    }
    calculateValues();
}
function calculateValues() {
    var totalProducts = parseInt($("#totalproducts").val());
    if (!isNaN(totalProducts) && totalProducts > 0) {
        var totalAmount = 0;
        var totalDiscount = 0;
        var totalNetAmount = 0;
        var totalQarAmount = 0;
        var currencyRate = parseFloat($("#currency_rate").val());
        currencyRate = isNaN(currencyRate) ? 1 : currencyRate;
        for (var i = 1; i <= totalProducts; i++) {
            var productQtyInput = $("#product_qty_recieved-" + i).val();
            var productQty = parseFloat(productQtyInput);
            productQty = isNaN(productQty) ? 0 : productQty;

            var productFoc = parseFloat($("#product_foc-" + i).val());
            productFoc = isNaN(productFoc) ? 0 : productFoc;

            var damage = parseFloat($("#damage-" + i).val());
            damage = isNaN(damage) ? 0 : damage;

            // var price = parseFloat($("#newcost-" + i).val());
            var price = parseFloat($("#price-" + i).val());
            price = isNaN(price) ? 0 : price;

            var amount = parseFloat($("#amount-" + i).val());
            amount = isNaN(amount) ? 0 : amount;

            var discountPerc = parseFloat($("#discountperc-" + i).val());
            discountPerc = isNaN(discountPerc) ? 0 : discountPerc;

            var discountAmount = parseFloat($("#discountamount-" + i).val());
            discountAmount = isNaN(discountAmount) ? 0 : discountAmount;

            

            if (!isNaN(productQty)) {
                var netAmount = (productQty * price) - productFoc - damage - discountAmount;
                var qarAmount = currencyRate * netAmount;
                qarAmount1 = parseFloat(qarAmount.toFixed(2));
                $("#netamount-"+i).val(netAmount);
                $("#qaramount-"+i).val(qarAmount1);
                totalAmount += amount;
                totalDiscount += discountAmount;
                totalNetAmount += netAmount;
                totalQarAmount += qarAmount1;
            } 
            else {
            }
        }
        $("#totalamount").text(totalAmount.toFixed(2));
        $("#bill_amount").val(totalNetAmount.toFixed(2));
        $("#totaldiscount").text(totalDiscount.toFixed(2));
        $("#totalnetamount").text(totalNetAmount.toFixed(2));
        $("#totalqaramount").text(totalQarAmount.toFixed(2));

        // var netAmount = parseFloat($("#totalnetamount").text());
        // var productAmount = parseFloat($("#totalamount").text());
        // costfactor = (netAmount)/productAmount;
        // var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);
        // $("#cost_factor").val(costfactorFinal);
        
        var costingNetAmountval = parseFloat($("#costingNetAmounts").text());
        if(costingNetAmountval>0 && costingNetAmountval!="")
        {
            var calculatedNetAmount = parseFloat($("#totalnetamount").text());
            costfactor = (costingNetAmountval)/calculatedNetAmount;
            var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);
            $("#cost_factor").val(costfactorFinal);
        }

    } else {
        console.error('Invalid value for total products');
    }
}

function costingamount(id)
{
    // costing_amount
    //product_qty
    var totalrows = $("#totalproducts").val();
    costingAmounts = 0;
    costingNetAmounts  = 0;
    costingNetAmountQar   = 0;    
    totalQar   = 0;    
    totalNet   = 0;    
    var costing_amount = parseFloat($("#costing_amount-" + id).val());
    costing_amount = isNaN(costing_amount) ? 0 : costing_amount;
    var currencyRate = parseFloat($("#currency_rate").val());
    currencyRate = isNaN(currencyRate) ? 1 : currencyRate;
    counter = $('#ganak').val();
    $("#costingAmounts").text("");
    $("#costingNetAmounts").text("");
    $("#costingNetAmountQar").text("");
     

    for (var i = 0; i <= counter; i++) {
        costing_amount_single = parseFloat($("#costing_amount-" + i).val());
        costing_amount_single = isNaN(costing_amount_single) ? 0 : costing_amount_single;
        costingAmounts = costing_amount_single;
        costingNetAmountQar = currencyRate * costing_amount_single;
        totalQar += costingNetAmountQar;
        totalNet += costing_amount_single;
        $("#costing_amount_qar-" + i).val(costingNetAmountQar.toFixed(2));
        $("#costing_amount_net-" + i).val(costing_amount_single.toFixed(2));
    }
    
    $("#costingAmounts").text(totalNet.toFixed(3));
    $("#costingNetAmounts").text(totalNet.toFixed(3));
    $("#costingNetAmountQar").text(totalQar.toFixed(3));

    var calculatedNetAmount = $("#totalnetamount").text();
    calculatedNetAmount = parseFloat(calculatedNetAmount.replace(/,/g, ''));
    var costingNetAmountval = $("#costingNetAmounts").text();
    costingNetAmountval = parseFloat(costingNetAmountval.replace(/,/g, '')); 
    costfactor = (costingNetAmountval)/calculatedNetAmount;
    
    if (isNaN(costfactor)) {
      var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2); 
    }
    else{
      costfactorFinal = 1;
    }
    // console.log(costingNetAmountval);
    // console.log(totalrows);
    var rowwise_cost = (costingNetAmountval)/totalrows;
    rowwise_cost = rowwise_cost.toFixed(3);
    console.log(rowwise_cost);
    $("#cost_factor").val(costfactorFinal);
    var totalqty = 0
    $('.received_product').each(function() {
        let receivedQty = parseFloat($(this).val()) || 0; // Get the input value
        single_product_cost = rowwise_cost/receivedQty;
        single_product_cost = single_product_cost.toFixed(3);
        let dataId = $(this).data('id'); 
        oldprdval = $("#price-" + dataId).val();
        newcost = parseFloat(oldprdval) + parseFloat(single_product_cost); 
        newcost = newcost.toFixed(2);
        $("#newcostabel-" + dataId).text(newcost);
        $("#newcost-" + dataId).val(newcost);
        console.log("Data ID:", dataId, "Received Qty:", receivedQty, "Cost:", single_product_cost);
        totalqty += receivedQty; 
    }); 
    costperitem  = (costingNetAmountval)/totalqty;
    
    $("#additional_cost_per_item").text(costperitem.toFixed(2));
    $("#cost_per_item").val(costperitem.toFixed(2));

    // for (var j = 1; j <= totalrows; j++) {
    //     oldprdval = $("#price-" + j).val();
    //     newcost = parseFloat(oldprdval) + parseFloat(costperitem); 
    //     newcost = newcost.toFixed(2);
    //     $("#newcostabel-" + j).text(newcost);
    //     $("#newcost-" + j).val(newcost);
    // }
    
    
}

const changedFields = {};
$(document).ready(function() {
     // Add event listeners to all input fields
     document.querySelectorAll('input, textarea, select').forEach((input) => {
            input.addEventListener('change', function () {
                const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
                const originalValue = this.getAttribute('data-original-value');
                var label = $('label[for="' + fieldId + '"]');
                var field_label = label.text();
                // console.log();
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
                } 
                else if (this.type === 'number') {
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
                }
                 else if (this.tagName === 'SELECT') {
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
   
  
    // erp2024 newly added 14-06-2024 for detailed history log ends 
    $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel,{
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            doctype: { required: true },
            bill_number: { required: true },
            srv: { required: true },
            bill_amount: { required: true },
            srvdate: { required: true },
            bill_date: { required: true },
            
        },
        messages: {
            doctype: "Doc Type required",
            bill_number: "Enter Bill No.",
            srv: "Purchase Receipt Voucher No. required",
            bill_amount: "Enter Bill Amount equal to the Purchase Order Amount",
            srvdate: "Enter Purchase Receipt Date",
            bill_date: "Enter Bill Date",
        }
    }));
    $('#updatebtn').on('click', function(e) {
        e.preventDefault();
        var totalnetamount = 0;
        var costingNetAmountval = parseFloat($("#costingNetAmounts").text());
        var entered_bill_amount = parseFloat($("#bill_amount").val());

        if(costingNetAmountval>0 && costingNetAmountval!="")
        {
            calculatedNetAmount = parseFloat($("#totalnetamount").text());
            totalnetamount = calculatedNetAmount - costingNetAmountval;
        }
        else{
            totalnetamount = parseFloat($("#totalnetamount").text());
        }
         // Function to validate required fields
       function validateRequiredFields() {
            var isValid = true;
            $('#data_form input[required], #data_form select[required]').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    $(this).addClass('error1'); // Add a class for styling invalid fields
                    alert('Please fill out all required fields.');
                    return false; // Break out of the loop
                } else {
                    $(this).removeClass('error'); // Remove error class if field is valid
                }
            });
            return isValid;
        }

        // Check if required fields are valid
        if (validateRequiredFields()) {
            
            if(totalnetamount==entered_bill_amount)
            {
                if (confirm("Are you sure you want to udate the data?")) {
                    var form = $('#data_form')[0]; // Get the form element
                        $.ajax({
                            url: baseurl +'Invoices/dataoperationedit',
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            success: function(response) {
                                    window.location.href = baseurl + 'Invoices/stockreciepts';
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', status, error);
                            }
                        });
                } 
            }
              
            else{
                    alert("Bill Amount("+entered_bill_amount+") & Net Amount("+totalnetamount+") is not equal");
                }
        }
        

    });
   
});
    var amounttobill = parseFloat($("#totalnetamount").text().replace(/,/g, '')) || 0;
    $("#bill_amount").val(amounttobill.toFixed(2));
    $(".reciept-approve-btn").on("click", function(e) {

        var assignto = $('#employee').val();
        e.preventDefault();
        var selectedProducts1 = [];
        var validationFailed = false;
        $('.reciept-approve-btn').prop('disabled', true);
        if(assignto=="")
        {
            $("#employee").prop('required', true);
        }
        if ($("#data_form").valid()) {
            
            Swal.fire({
                    title: "Are you sure?",
                    // text: "Are you sure you want to update inventory? Do you want to proceed?",
                    "text":"Do you want to Approve this Purchase Order Reciept?",
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
                    var formData = $("#data_form").serialize(); 
                    formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
                    $.ajax({
                        type: 'POST',
                        url: baseurl +'Invoices/dataoperationedit',
                        data: formData,
                        success: function(response) {
                            window.location.href = baseurl + 'Invoices/stockreciepts'; 
                        },
                        error: function(xhr, status, error) {
                                // Handle error
                                console.error(xhr.responseText);
                        }
                    });
                    }
                    else{
                    $('.reciept-approve-btn').prop('disabled', false);
                    }
            });
        }
        else{
                $('.page-header-data-section').css('display','block');
                $('.reciept-approve-btn').prop('disabled', false);
            }
    });


    $("#revert-by-admin-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this purchase order reciept?",
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
                    url: baseurl + 'Invoices/revertorder_by_admin_action',
                    data: { 
                        po_id: $("#costmaserid").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    $(".recieve-items-btn").on("click", function(e) {
        e.preventDefault();
        var selectedProducts1 = [];
        var validationFailed = false;
        $('.recieve-items-btn').prop('disabled', true);
        $("#employee").prop('required', false);
        if ($("#data_form").valid()) {
            Swal.fire({
                    title: "Are you sure?",
                    // text: "Are you sure you want to update inventory? Do you want to proceed?",
                    "text":"Do you want to Recieve items Now?",
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
                    var formData = $("#data_form").serialize(); 
                    $.ajax({
                        type: 'POST',
                        url: baseurl +'Invoices/recieve_item_action',
                        data: formData,
                        success: function(response) {
                            
                            window.location.href = baseurl + 'invoices/stockreciepts'; 
                            // location.reload();
                        },
                        error: function(xhr, status, error) {
                                // Handle error
                                console.error(xhr.responseText);
                        }
                    });
                    }
                    else{
                    $('.recieve-items-btn').prop('disabled', false);
                    }
            });
        }
        else{            
            $('.page-header-data-section').css('display','block');
            $('.recieve-items-btn').prop('disabled', false);
        }
    });

    $("#reciept-accept-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to Accept this Purchase Receipt?",
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
                var formData = $("#data_form").serialize(); 
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'Invoices/receipt_accept_by_employee',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                       location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    $("#revert-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this purchase reciept?",
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
                    url: baseurl + 'Invoices/revert_reciept_by_employee_action',
                    data: {
                        po_id: $("#costmaserid").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'Invoices/stockreciepts';
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

   
    $("#submit-purchase-orderbtn-draft").on("click", function(e) {
        e.preventDefault();
        var formData = $("#data_form").serialize(); 
        var form = $('#data_form')[0];
        var formData = new FormData(form);        
        formData.append('changedFields', JSON.stringify(changedFields));
        srv = $("#srv").val();
        token = $("#token").val();
        $.ajax({
            type: 'POST',
            url: baseurl +'Invoices/draftaction',
            data: formData, 
            contentType: false, 
            processData: false,
            success: function(response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }         
                window.location.href = baseurl + 'Invoices/costing?id='+response.data+'&token='+token;
            },
            error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
            }
        });
    });


    //prepared
    $('#prepared_btn').on('click', function(e) {
           e.preventDefault();
           $('#prepared_btn').prop('disabled', true);
           var srvflg = "<?php echo $srvFlg; ?>";
           if(srvflg==1){
               targeturl = baseurl +'Invoices/dataoperationeditfrominsert';
           }
           else{
               targeturl = baseurl +'Invoices/dataoperation';
           }
           
         // Validate the form
         if($("#data_form").valid()) {                 
            var form = $('#data_form')[0];
            var formData = new FormData(form);
            formData.append('changedFields', JSON.stringify(changedFields));
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a Purchase Items Receipt?",
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
                        url: baseurl + 'Invoices/dataoperation',
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           if (typeof response === "string") {
                              response = JSON.parse(response);
                           } 
                           window.location.href = baseurl + 'Invoices/stockreciepts';
                           
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                           console.log(error); // Log any errors
                        }
                  });
               }
               else{
                  $('#prepared_btn').prop('disabled', false);
               }
            });

         }
         else{
            $('.page-header-data-section').css('display','block');
            $('#prepared_btn').prop('disabled', false);
         }           
   
       });
       
    $('#savebtn').on('click', function(e) {
           e.preventDefault();
           $('#savebtn').prop('disabled', true);
           var srvflg = "<?php echo $srvFlg; ?>";
          
           if(srvflg==1){
               targeturl = baseurl +'Invoices/dataoperationeditfrominsert';
           }
           else{
               targeturl = baseurl +'Invoices/dataoperation';
           }
           
         // Validate the form
         if($("#data_form").valid()) {                 
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object

            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a Purchase Items Receipt?",
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
                        url: baseurl + 'Invoices/dataoperation', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           if (typeof response === "string") {
                              response = JSON.parse(response);
                           } 
                           window.location.href = baseurl + 'Invoices/stockreciepts';
                           
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                           console.log(error); // Log any errors
                        }
                  });
               }
               else{
                  $('#savebtn').prop('disabled', false);
               }
            });

         }
         else{
            $('.page-header-data-section').css('display','block');
            $('#savebtn').prop('disabled', false);
         }
           
   
    });
    function recievedqty_check(id){
      var orderedqty = parseFloat($("#product_qty-" + id).val()) || 0;       
      var receivedqty = parseFloat($("#product_qty_recieved-" + id).val()) || 0;       
      var damageqty = parseFloat($("#damage-" + id).val()) || 0;    
      var totalqty = orderedqty - damageqty;
      
      if((receivedqty > totalqty)){
         $("#product_qty_recieved-" + id).val(orderedqty);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is invalid. please check'
         });
      }
    //   if(receivedqty < damageqty){
    //      $("#damage-" + id).val(0);
    //      Swal.fire({
    //            icon: 'error',
    //            title: 'Invalid Quantity',
    //            text: 'The entered damaged quantity is greater than the received quantity. Please double-check the damaged quantity.'
    //      });
    //   }
}
function damagedqty_check(id){
   
      var orderedqty = parseFloat($("#product_qty-" + id).val()) || 0;       
      var receivedqty = parseFloat($("#product_qty_recieved-" + id).val()) || 0;       
      var damagedqty = parseFloat($("#damage-" + id).val()) || 0;    
      var totalqty = parseFloat(orderedqty) - parseFloat(receivedqty);
      if((damagedqty > receivedqty)){
         $("#damage-" + id).val(0);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is invalid. please check'
         });
      }
}
function update_product_cost(id){
   
      var product_id = $("#product_id-" + id).val();       
      var newcost = $("#newcost-" + id).val();     
      var productname = $("#productname-" + id).val();     
      var product_code = $("#product_code-" + id).val();     
      var old_cost = $("#old_cost-" + id).val();     
      var receipt_id = $("#receipt_id").val();     
    //   $('#product_cost_update_btn'+id).prop('disabled', true);  
      Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update the product cost?",
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
                        url: baseurl + 'Products/product_cost_update', 
                        type: 'POST',                        
                        data: {
                            pid: product_id,
                            cost: newcost,
                            old_cost : old_cost,
                            receipt_id : receipt_id,
                            productname : productname,
                            product_code : product_code
                        },
                        dataType: 'json',
                        success: function(response) {
                           if (typeof response === "string") {
                              response = JSON.parse(response);
                           } 
                        //    location.reload();
                           
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                           console.log(error); // Log any errors
                        }
                  });
               }
               else{
                // $('#product_cost_update_btn'+id).prop('disabled', false); 
               }
            });
}
   
   
function productwise_costing(j)
{
    var totalproducts = $("#totalproducts").val();
    costingAmounts = 0;
    costingNetAmounts  = 0;
    costingNetAmountQar   = 0;    
    totalQar   = 0;    
    totalNet   = 0;    
   

    var calculatedNetAmount = $("#totalnetamount").text();
    calculatedNetAmount = parseFloat(calculatedNetAmount.replace(/,/g, ''));
    var costingNetAmountval = $("#costingNetAmounts").text();
    costingNetAmountval = parseFloat(costingNetAmountval.replace(/,/g, '')); 
    costfactor = (costingNetAmountval)/calculatedNetAmount;
    var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);  
    $("#cost_factor").val(costfactorFinal);


    var totalqty = 0
    $('.received_product').each(function() {
        totalqty += parseFloat($(this).val()) || 0; // Add the value or 0 if it’s empty
    }); 
    costperitem  = (costingNetAmountval)/totalqty;
    var productQtyInput = $("#product_qty_recieved-" + j).val();
    var productQty = parseFloat(productQtyInput);
    productQty = isNaN(productQty) ? 0 : productQty;
    if (isNaN(productQty)) {
        $("#additional_cost_per_item").text(costperitem.toFixed(2));
        $("#cost_per_item").val(costperitem.toFixed(2));
        oldprdval = $("#price-" + j).val();
        var newcost = parseFloat(oldprdval) + parseFloat(costperitem);
        newcost = newcost.toFixed(2);
        $("#newcostabel-" + j).text(newcost);
        $("#newcost-" + j).val(newcost);
    }
    
}

$('.cancelreceipt-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.cancelreceipt-btn').prop('disabled', true);

    var selectedProducts1 = [];
    $('.received_product').each(function() {
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
        // $('.cancelreceipt-btn').prop('disabled', false);
        return;
    }          

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to cancel the purchase receipt?",
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
                url: baseurl + 'purchase/cancel_purchasereceipt_action', 
                type: 'POST',
                data: {
                    'receipt_id': $("#receipt_id").val()
                },
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'Success') {
                            window.location.href = baseurl + 'invoices/stockreciepts';
                    } else {
                        Swal.fire('Error', 'Failed to cancel the invoice', 'error');
                        $('.cancelreceipt-btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while cancelling the invoice', 'error');
                    console.log(error); // Log any errors
                    $('.cancelreceipt-btn').prop('disabled', false);
                }
            });
        } else {
            $('.cancelreceipt-btn').prop('disabled', false);
        }
    });
   
});
</script>


