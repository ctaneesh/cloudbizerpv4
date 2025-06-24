
<div class="content-body">
    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <?php
                    $invoice_retutn_number = ($action_type)? $invoice_retutn_number : $this->lang->line('Add New');
                ?>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoicecreditnotes') ?>"><?php echo $this->lang->line('Credit Notes'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $invoice_retutn_number; ?></li>
                </ol>
            </nav>
            
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $invoice_retutn_number; ?> </h4>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <ul id="trackingbar">
                    <?php
                    
                    
                    $delnotenum = (!empty($notemaster['delevery_note_number'])) ?$notemaster['delevery_note_number'] :$notemaster['delevery_note_number'];
                    $prefixs = get_prefix_72();
                    $suffix = $prefixs['suffix'];
                    if (!empty($trackingdata)) {  
                        if (!empty($trackingdata['lead_id'])) { 
                           echo '<li><a href="' . base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) . '">' . $trackingdata['lead_number'] . '</a></li>';
                        } 
                        if (!empty($trackingdata['quote_number'])) { 
                              echo '<li><a href="' . base_url('quote/create?id=' . $trackingdata['quote_number']) . '">' . $trackingdata['quote_number'] . '</a></li>';
                        }
                        if (!empty($trackingdata['salesorder_number'])) { 
                            if($trackingdata['sales_count']>1 && $trackingdata['quote_number'])
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
                        // if (!empty($trackingdata['deliverynote_number'])) { 
                        //     echo '<li class="active">' . $trackingdata['deliverynote_number'] . '</li>';
                           
                        // }
                        if (!empty($trackingdata['delivery_return_number'])) { 
                           echo '<li><a href="' . base_url('Deliveryreturn/deliveryreturn?delivery=' . $trackingdata['delivery_return_number']).'">' . $trackingdata['delivery_return_number'] . '</a></li>';
                        }
                        if (!empty($trackingdata['invoice_number'])) { 
                           echo '<li><a href="' . base_url('invoices/create?id=' . $trackingdata['invoice_number']).'">' . $trackingdata['invoice_number'] . '</a></li>';
                        }
                        if (!empty($trackingdata['invoice_retutn_number']) && !empty($action_type)) { 
                            echo '<li class="active">' . $trackingdata['invoice_retutn_number'] . '</li>';
                        }
                     } ?>
                           
                  </ul> 
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 current-status">
                  <?php
                   
                     $messagetext="";
                     $statustext="";
                     $savedraftbtn = "";
                     $invoice_disable = "disable-class";
                     $edit_customer_btn = "";
                     if($notemaster['payment_status'])
                     {
                     switch ($notemaster['payment_status']) {
                       
                        case 'Paid':
                           $statustext = "Paid";
                           $alertcls = "alert-success";
                           $messagetext = "Invoice Created & Payment Received";
                           $savedraftbtn = "disable-class";
                           $edit_customer_btn = "disable-class";
                           break;

                        case 'Due':
                           $statustext = "Due";
                           $alertcls = "alert-danger";
                           $messagetext = "";
                           break;

                        case 'Partial':
                           $statustext = "Partial";
                           $alertcls = "alert-partialconvert";
                           break;
                  
                        default:
                           // $status = ($invoices->status != 'Draft') ? '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>' : '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                           // $makepaymentbtn = '';
                           break;
                     }
                  }
                 
                     if($statustext)
                     {
                        echo '<div class="btn-group alert text-center '.$alertcls.'" role="alert">'.$statustext.'</div>';
                     } 
                  ?>
               </div>

            </div>
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
                <form method="post" id="data_form">
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-6">
                        <?php
                        $returnid = "";
                        $customerid = "";
                        if($notemaster['returnid'])
                        {
                            $returnid = $notemaster['returnid'];
                            $customerid = $notemaster['customer_id'];
                        }
                        if($notemaster['returnid'] && $notemaster['payment_status']!="Paid")
                        {
                            echo '<a href="' . base_url("invoices/payment_return_to_customer?id=" . $returnid . "&csd=" . $customerid) . '" class="btn btn-sm btn-secondary"><span class="fa fa-money"></span> Make Payment</a>';

                        } ?>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-6 text-right">
                        <?php
                        if($notemaster['returnid'])
                        {
                            
                            
                            if($receipt_numbers)
                            {
                                ?>
                                <div class="btn-group ">
                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                class="fa fa-print"></i> <?php echo $this->lang->line('Receipts') ?>
                                </button>
                                <div class="dropdown-menu">
                                    <?php
                                    foreach($receipt_numbers as $receipt_number)
                                    { 
                                        $receipt_number = $receipt_number['receipt_number'];
                                        ?>
                                        <a class="dropdown-item" href="<?= base_url("invoices/invoicereturn_print?delivery=" . $returnid . "&cust=" . $customerid. '&receipt_number=' . $receipt_number); ?>" target="_blank"><?php echo $receipt_number; ?></a>
                                        <?php
                                    }
                                    ?>
                                </div>
                                </div>
                                <?php
                            } 

                            echo '<a href="' . base_url("invoices/invoicereturn_print?delivery=" . $returnid . "&cust=" . $customerid) . '" class="btn btn-sm btn-secondary" target="_blank"><span class="fa fa-print"></span> Print</a>';

                        } 
                        
                        ?>
                    </div>
                </div>
                <!-- <input type="hidden" value="DeliveryNotes/delivery_return_action" id="action-url"> -->
                   <!-- ========================================================================= -->

                   <?php
                         $invoiceduedate = (!empty($notemaster['due_date']) && $notemaster['due_date'] != '0000-00-00') 
                         ? $notemaster['due_date'] 
                         : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['quote_validity'] . " days"));
                         $term = ($notemaster['term'])?$notemaster['payment_terms']:$validity['payment_terms'];
                         $customer_id = $notemaster['customer_id'];
                        $employee_id = $created_employee['id']; 
                        $headerclass= "d-none";
                        $pageclass= "page-header-data-section-dblock";
                        $customer_search_section = "";
                        if($action_type)
                        {
                            $headerclass = "page-header-data-section-dblock";
                            $pageclass   = "page-header-data-section";
                            $customer_search_section = "d-none";
                        }
                        
                        ?>
                        <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                                    <h3  class="title-sub"><?php echo $this->lang->line('Credit Note & Customer Details') ?> <i class="fa fa-angle-down"></i></h3>
                                </div>
                                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 responsive-text-right quickview-scroll order-1 order-lg-2">
                                        <div class="quick-view-section">
                                            <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Customer') ?></h4>
                                                <?php //echo "<b>".$notemaster['name']."</b>"; ?>                                            
                                                <?php
                                                echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($notemaster['name']) . "</b></a>";
                                                ?>
                                            </div>
                                            <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Invoice') ?></h4>
                                                <?php 
                                                $invoiceid = $notemaster['invoice_number'];
                                                echo "<a class='expand-link' href='" . base_url('invoices/create?id=' . urlencode($invoiceid)) . "' ><b>" . htmlspecialchars($notemaster['invoice_number']) . "</b></a>"; ?>
                                            </div>
                                            
                                            
                                            <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Created By') ?></h4>
                                                <?php 
                                                    echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                                ?>
                                            </div>
                                            <div class="item-class text-center">
                                                <h4><?php echo $this->lang->line('Total'); ?></h4>
                                                <?php echo "<p>".number_format($notemaster['total'],2)."</p>";?>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="<?=$pageclass?>">    
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12 cmp-pnl">
                                    <div id="customerpanel" class="inner-cmp-pnl">
                                        <div id="customer">
                                            <div class="clientinfo">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Customer Details'); ?></h3><hr>
                                            <?php 
                                            echo '<input type="hidden" name="customer_id" id="customer_id" value="' . $notemaster['customer_id'] . '">
                                                <div id="customer_name"><strong>' . $notemaster['name'] . '</strong></div>
                                                </div>
                                                <div class="clientinfo">

                                                    <div id="customer_address1"><strong>' . $notemaster['address'] . '<br>' . $notemaster['city'] . ',' . $notemaster['country'] . '</strong></div>
                                                </div>

                                                <div class="clientinfo">                                                <div type="text" id="customer_phone">Phone: <strong>' . $notemaster['phone'] . '</strong><br>Email: <strong>' . $notemaster['email'] . '</strong></div>
                                                </div>
                                                <div class="clientinfo">
                                                <div type="text" >'.$this->lang->line('Company Credit Limit').' : <strong>' . $notemaster['credit_limit'] . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $notemaster['credit_period'] . '</strong><br>'.$this->lang->line('Available Credit Limit').' : <strong>' . $notemaster['avalable_credit_limit'] . '</strong><input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="' . $notemaster['avalable_credit_limit'] . '"></div>
                                                </div>'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-12 col-sm-12 cmp-pnl">
                                    <div class="inner-cmp-pnl">
                                        
                                        <div class="form-row">

                                            <div class="col-sm-12">
                                                <h3 class="title-sub">
                                                    <?php echo $this->lang->line('Credit Note Details'); ?></h3><hr>
                                            </div>

                                            <!-- erp2024 modified section 07-06-2024 -->
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none"><label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Credit Note Number'); ?>
                                                </label>

                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-file-text-o"
                                                            aria-hidden="true"></span></div>
                                                    <input type="text" class="form-control"  placeholder="Delivery Return Number" name="invocieno" id="creditnotetid" value="<?php echo $invoice_retutn_number; ?>"  readonly>
                                                    <input type="text" class="form-control"  placeholder="Delivery Return Number" name="invoice_return_number" id="invoice_return_number" value="<?php echo $invoice_retutn_number; ?>"  readonly>

                                                    <input type="text" class="form-control"  name="action_type" value="<?php echo $action_type; ?>"  readonly>
                                                    
                                                    
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                    for="invocieno"
                                                    class="col-form-label"><?php echo $this->lang->line('Invoice Number'); ?>
                                                </label>
                                                <input type="hidden" class="form-control"  placeholder="Delivery Return Number" name="invocieno" id="creditnotetid" value="<?php echo $invoice_retutn_number; ?>"  readonly>
                                                    <input type="hidden" class="form-control"  name="invoice_number" id="invocieid" value="<?php echo $notemaster['invoice_number']; ?>"  readonly>
                                                    <input type="hidden" class="form-control" name="store_id" id="store_id" value="<?php echo $notemaster['store_id']; ?>">
                                                    <input type="hidden" class="form-control" name="payment_type" id="payment_type" value="<?php echo $notemaster['payment_type']; ?>">
                                                    <input type="hidden" class="form-control" name="store_id" id="store_id" value="<?php echo $notemaster['store_id']; ?>">
                                                    <input type="hidden" class="form-control" name="invoice_payment_status" id="invoice_payment_status" value="<?php echo $notemaster['notestatus']; ?>">
                                                <?php 
                                                $invoice_number = (!empty($notemaster['invoice_number'])) ? $notemaster['invoice_number']:$notemaster['invoice_number'];
                                                ?>
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="icon-file-text-o"
                                                            aria-hidden="true"></span></div>
                                                
                                                    <input type="hidden" name="invoice_id" id="invoice_id" class="form-control" value="<?php echo $notemaster['invoice_number']; ?>" readonly>
                                                    <input type="text" class="form-control" placeholder="Invoice Number" name="invoice_number" id="invoice_number" value="<?php echo $invoice_number; ?>" readonly>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                    for="invocieno"
                                                    class="col-form-label"><?php echo $this->lang->line('Reference'); ?></label>

                                                    <input type="text" class="form-control" name="refer" id="refer"
                                                        value="<?php echo $notemaster['reference']; ?>" readonly>
                                            </div>
                                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                    for="invocieno"
                                                    class="col-form-label"><?php echo $this->lang->line('Invoiced Date'); ?></label>
                                                    <input type="text" class="form-control" name="invoicedate" id="invoicedate"
                                                        value="<?php echo dateformat($notemaster['invoice_date']); ?>" readonly>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label for="notes"  class="col-form-label"><?php echo $this->lang->line('Note'); ?> <span class="compulsoryfld"> *</span></label>
                                                <textarea name="notes" id="notes" class="form-textarea" minlength="5" title=""><?=$notemaster['notes']?></textarea>
                                                    
                                            </div>
                                            <input type="hidden" class="form-control" name="invoiceduedate" id="invoiceduedate"  value="<?php echo date('d-m-Y', strtotime($notemaster['due_date'])); ?>" readonly>
                                            <input type="hidden" class="form-control" name="pterms" id="pterms" value="<?php echo $notemaster['payment_terms']; ?>">

                                            <input type="hidden" id="order_discount_percentage" name="order_discount_percentage" readonly value="<?= $notemaster['order_discount_percentage']?>">
                                            <input type="hidden" id="shipping_percentage" name="shipping_percentage" readonly value="<?php echo $notemaster['shipping_percentage']; ?>">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- ========================= tab starts ==================== -->
                        <ul class="nav nav-tabs mb-2 mt-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                    aria-controls="tab1" href="#tab1" role="tab"
                                    aria-selected="true"><?php echo $this->lang->line('Credit Notes') ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link breaklink navtab-caption" id="base-tab2" data-toggle="tab" aria-controls="tab3"
                                    href="#tab3" role="tab"
                                    aria-selected="false"><?php echo $this->lang->line('Payments Received') ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link breaklink navtab-caption" id="base-tab2" data-toggle="tab" aria-controls="tab4"
                                    href="#tab4" role="tab"
                                    aria-selected="false"><?php echo $this->lang->line('Journals') ?></a>
                            </li>
                            
                        </ul>

                        <div class="tab-content px-1">
                            <!-- single tab section starts  -->
                            <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                <div id="saman-row" class="row overflow-auto">
                                    <?php
                                    //existing credit note
                                    if($action_type)
                                    {
                                    
                                        ?>
                                        <input type="hidden" class="form-control" name="bank_transaction_ref_number" id="bank_transaction_ref_number" value="<?php echo $bank_transaction_ref_number['trans_ref_number']; ?>">
                                        <input type="hidden" class="form-control" name="invoice_returnid" id="invoice_returnid" value="<?php echo $notemaster['returnid']; ?>">
                                        <table class="table table-striped table-bordered zero-configuration dataTable">
                                            <thead>

                                                <tr class="item_header bg-gradient-directional-blue white">
                                                    <th width="4%" style="padding-left:10px;">Sl.No</th>
                                                    <!-- <th width="15%" style="padding-left:10px; text-align:left !important;"><?php echo $this->lang->line('Item Code');?>
                                                    </th> -->
                                                    <th width="10%" class="text-center1"><?php echo $this->lang->line('Item No');?></th>
                                                    <th width="20%" class="text-center1"><?php echo $this->lang->line('Item Name');?></th>
                                                    <th width="6%" class="text-center"><?php echo $this->lang->line('Unit');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Ordered Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Delivered Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Returned Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Return Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Damaged Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Rate');?></th>
                                                    <?php 
                                                    if($configurations['config_tax']!='0')
                                                    { ?>
                                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Tax');?> %</th>
                                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Tax');?></th>
                                                    <?php } ?>
                                                    <th width="10%" class="text-center">Discount</th>
                                                    <th width="12%" class="text-center">
                                                        Amount(<?php echo $this->config->item('currency'); ?>)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 0;$j = 1;
                                            $taxtotal = 0;
                                            $productrate = 0;
                                            $discountrate = 0;
                                            $total_product_price = 0;

                                         
                                            foreach ($products as $row) {
                                                $disableclass = "";
                                                // if($row['quantity'] == $row['product_qty']){
                                                //     $disableclass = "readonly";
                                                // }
                                                echo '<input type="hidden" class="form-control" name="product_name[]" value="' . $row['product_code'] . '">';
                                                echo '<input type="hidden" class="form-control" name="account_number[]" value="' . $row['account_number'] . '">';
                                                echo '<input type="hidden" class="form-control" name="product_code[]" value="' . $row['product_code'] . '">';
                                                echo '<input type="hidden" class="form-control" name="product_cost[]" value="' . $row['product_cost'] . '">';
                                                // if($row['totalQty']<=$row['alert']){
                                                //     echo '<tr style="background:#ffb9c2;">';
                                                // }
                                                // else{
                                                //     echo '<tr >';
                                                // }
                                                $product_name_with_code = $row['product_name'].'('.$row['product_code'].') - ';
                                                $taxtotal = $taxtotal+$row['deliverytaxtotal'];
                                                $productrate = $productrate+$row['deliverysubtotal'];
                                                $discountrate = $discountrate+$row['total_discount'];
                                                $row['quantity'] = ($row['quantity']) ? $row['quantity'] :  $row['qty'];
                                                $total_product_price += intval($row['quantity'])*$row['price'];

                                                  
                                                echo '<td width="2%">'.$j.' <input type="hidden" class="form-control" name="account_number[]" value="'.$row['account_number'].'" id="account_number-'.$i.'"></td>';

                                                echo '<td><strong>'.$row['product_code'].'</strong> </td>';
                                                echo '<td width="15%"><strong>'.$row['product_name'].'</strong> </td>';


                                                echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';

                                                echo '<td class="text-center"><strong>'.intval($row['quantity']).'</strong> </td>';
                                                
                                                echo '<td  class="text-center"><strong>'.intval($row['quantity']).'</strong> <input type="hidden" class="form-control" name="delivered_qty[]" value="'.intval($row['quantity']).'" id="delivered_qty-'.$i.'"></td>';

                                                echo '<td class="text-center"><strong>'.intval($row['quantity']).'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="0"></td>';
                                                
                                                //remove convert_shipping_percentage_to_amount
                                                // echo '<td style="text-align:center;"><input type="number" class="form-control req prc returnitem " '.$disableclass.' name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(),calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount(),convert_shipping_percentage_to_amount()" value="'.intval($row['quantity']).'" title="'.$product_name_with_code.'Return Quantity" data-original-value="'.intval($row['quantity']).'"><input type="hidden" class="form-control" name="return_qty_old[]"   value="'.intval($row['quantity']).'"></td>';

                                                echo '<td style="text-align:center;"><input type="number" class="form-control req prc returnitem " '.$disableclass.' name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(),calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount()" value="'.intval($row['quantity']).'" title="'.$product_name_with_code.'Return Quantity" data-original-value="'.intval($row['quantity']).'"><input type="hidden" class="form-control" name="return_qty_old[]"   value="'.intval($row['quantity']).'"></td>';
                                                
                                                echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.'  name="damaged_qty[]" id="damaged_qty-' . $i . '" value="'.intval($row['damaged_quantity']).'" onkeypress="return isNumber(event)" onkeyup="damageqtycheck(' . $i . ')" placeholder="'.$this->lang->line('Enter Qty').'" title="'.$product_name_with_code.'Damaged Quantity" data-original-value="'.intval($row['damaged_quantity']).'"><input type="hidden" class="form-control"name="damaged_qty_old[]"   value="'.intval($row['damaged_quantity']).'"></td>';

                                                echo '<td style="text-align:right;"><strong>'.$row['price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . amountExchange_s($row['price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                            if($configurations['config_tax']!='0')
                                            {
                                                echo '<td class="text-center"  style="font-weight:bold;">'.$row['total_tax'].'</td>';
                                                echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">0</td>';
                                            }

                                                // <!-- erp2024 modified section 07-06-2024 -->
                                                echo '<td class="text-center"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$row['total_discount'].'</strong></td>';

                                                    echo '<td class="text-right"><span class="currenty"></span>
                                                        <strong><span class="ttlText" id="result-' . $i . '">'.$row['subtotal'].'</span></strong></td>
                                                    </td>
                                                    
                                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="'.$row['total_discount'].'">
                                                    <input type="hidden" name="old_discount[]" id="old_discount-' . $i . '" value="'.$row['total_discount'].'">

                                                    <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['discount_type'].'">
                                                    <input type="hidden" name="product_subtotal_old[]"  value="'.$row['subtotal'].'">
                                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="'.$row['subtotal'].'">
                                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"> 
                                                    <input type="hidden" name="product_tax[]" id="vat-' . $i . '" readonly value="'.$row['product_tax'].'">

                                                    <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" value="' . amountFormat_general($row['discount']) . '">
                                                    
                                                    <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '">

                                                </tr>';
                                                $i++; $j++;
                                            } 
                                                
                                            ?>

                                            
                                                
                                                <tr class="sub_c tr-border d-none" style="display: table-row; ">    
                                                    <td colspan="9" align="right" class="no-border"><strong>Total Tax</strong></td>
                                                    <td align="left" colspan="2" class="no-border"><span
                                                            class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                                        <span id="taxr"
                                                            class="lightMode"><?php echo $taxtotal; ?></span>
                                                    </td>
                                                </tr>
                                                <!-- erp2024 removed section 07-06-2024 -->
                                            
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="7" class="no-border"></td>
                                                    <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                            (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                                    </td>
                                                    <td align="right" colspan="2" class="no-border">
                                                        <span id="grandamount"><?=number_format($total_product_price,2)?></span>
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="11" align="right"  class="no-border"><strong><?php echo $this->lang->line('Total Product Discount') ?>(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                    <td align="right" colspan="2"  class="no-border">
                                                        <span id="discs"  class="lightMode discount_total"><?=number_format($discountrate,2)?></span>
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="7" class="no-border"></td>
                                                    <td colspan="4" align="right"  class="no-border"><strong><?php echo $this->lang->line('Order Discount') ?>(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                    <td align="right" colspan="2"  class="no-border">
                                                        <span id="order_discount_text" class="lightMode"><?=$notemaster['order_discount']?></span>
                                                    </td>
                                                </tr>

                                                
                                                <tr class="sub_c d-none" style="display: table-row;">
                                                    <td colspan="11" align="right" class="no-border"><input type="hidden" value="0"
                                                            id="subttlform" name="subtotal"><strong>Shipping</strong></td>
                                                    <td align="right" colspan="2" class="no-border">
                                                        <span id="shipping_text_value" class="lightMode"><?=$notemaster['shipping']?></span>
                                                    
                                                    </td>
                                                </tr>
                                                
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="4" class="no-border">
                                                        <?php if ($exchange['active'] == 1){
                                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                                        <select name="mcurrency" class="selectpicker form-control">

                                                            <?php
                                                        echo '<option value="' . $notemaster['multi'] . '">Do not change</option><option value="0">None</option>';
                                                        foreach ($currency as $row) {

                                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                                        } ?>

                                                        </select><?php } ?>
                                                    </td>
                                                    <td colspan="7" align="right" class="no-border">
                                                        <strong><?php echo $this->lang->line('Grand Total') 
                                                    
                                                        ?>
                                                            (<span
                                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                                    </td>
                                                    <?php
                                                        $grandtotal = 0;
                                                        $grandtotal = ($taxtotal+$productrate)-$discountrate;
                                                    ?>
                                                    <td align="right" colspan="2" class="no-border">
                                                    <span id="grandtotaltext"><?=number_format($notemaster['total'],2)?></span>
                                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="<?=$notemaster['total']?>" readonly>
                                                        <input type="hidden" name="total_old" class="form-control" value="<?=$notemaster['total']?>" readonly>
                                                        <input type="hidden" id="order_discount_old" name="order_discount_old"  value="<?=$notemaster['order_discount']?>">
                                                        <input type="hidden" id="order_discount" name="order_discount"  value="<?=$notemaster['order_discount']?>">
                                                        <input type="hidden" id="shipping_amount" name="shipping"  value="<?=$notemaster['shipping']?>">
                                                        <input type="hidden" id="shipping_old" name="shipping_old"  value="<?=$notemaster['shipping']?>">
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="4"  class="no-border">
                                                    <button class="btn btn-crud btn-lg btn-secondary deleteinvoice-btn d-none" title="<?php echo 'This will Delete the Invocie Return '.$prefix['invoicereturn_prefix'].$notemaster['tid'].' and will Adjust the Accounts and Inventory;' ?> "><?php echo $this->lang->line('Delete Invoice'); ?></button>
                                                    </td>
                                                    <td align="right" colspan="8"  class="no-border">
                                                    <?php 
                                                    if($notemaster['payment_status']!='Paid')
                                                    { ?>
                                                    <input type="submit" id="submit-invoice-return-edit" class="btn btn-primary btn-crud btn-lg submitBtn" value="<?php echo $this->lang->line('Update'); ?>"/>
                                                    <?php } ?>
                                                    <!-- <input type="submit" id="submit-data" class="btn btn-primary btn-lg submitBtn" value="Generate Delivery Return"/> -->
                                                    <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                        <?php
                                    }
                                    else{
                                        //new creditnote
                                        ?>
                                        <table class="table table-striped table-bordered zero-configuration dataTable">
                                            <thead>

                                                <tr class="item_header bg-gradient-directional-blue white">
                                                    <th width="4%" style="padding-left:10px;"><?php echo $this->lang->line('SN');?></th>
                                                    <!-- <th width="15%" style="padding-left:10px; text-align:left !important;"><?php echo $this->lang->line('Item Code');?>
                                                    </th> -->
                                                    <th width="10%" class="text-center1"><?php echo $this->lang->line('Item No');?></th>
                                                    <th width="20%" class="text-center1"><?php echo $this->lang->line('Item Name');?></th>
                                                    <th width="6%" class="text-center"><?php echo $this->lang->line('Unit');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Ordered Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Delivered Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Returned Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Return Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Damaged Qty');?></th>
                                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Rate');?></th>
                                                    <?php 
                                                    if($configurations['config_tax']!='0')
                                                    { ?>
                                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Tax');?> %</th>
                                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Tax');?></th>
                                                    <?php } ?>
                                                    <th width="10%" class="text-center">Discount</th>
                                                    <th width="12%" class="text-center">
                                                        Amount(<?php echo $this->config->item('currency'); ?>)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 0;$j = 1;
                                            $taxtotal = 0;
                                            $productrate = 0;
                                            $discountrate = 0;
                                            foreach ($products as $row) {
                                                $disableclass = "";
                                                // if($row['quantity'] == $row['product_qty']){
                                                //     $disableclass = "readonly";
                                                // }
                                                echo '<input type="hidden" class="form-control" name="product_name[]" value="' . $row['prdname'] . '">';
                                                echo '<input type="hidden" class="form-control" name="account_number[]" value="' . $row['account_number'] . '">';
                                                echo '<input type="hidden" class="form-control" name="product_code[]" value="' . $row['prdcode'] . '">';
                                                echo '<input type="hidden" class="form-control" name="product_cost[]" value="' . $row['product_cost'] . '">';
                                                // if($row['totalQty']<=$row['alert']){
                                                //     echo '<tr style="background:#ffb9c2;">';
                                                // }
                                                // else{
                                                //     echo '<tr >';
                                                // }
                                                $taxtotal = $taxtotal+$row['deliverytaxtotal'];
                                                $productrate = $productrate+$row['deliverysubtotal'];
                                                $discountrate = $discountrate+$row['total_discount'];
                                                $product_name_with_code = $row['prdname'].'('.$row['prdcode'].') - ';

                                                echo '<td width="2%">'.$j.' <input type="hidden" class="form-control" name="product_id[]" value="'.$row['pid'].'" id="product_id-'.$i.'"></td>';

                                                echo '<td><strong>'.$row['prdcode'].'</strong> </td>';
                                                echo '<td width="15%"><strong>'.$row['prdname'].'</strong> </td>';


                                                echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';

                                                echo '<td class="text-center"><strong>'.intval($row['quantity']).'</strong> </td>';
                                                
                                                echo '<td  class="text-center"><strong>'.intval($row['quantity']).'</strong> <input type="hidden" class="form-control" name="delivered_qty[]" value="'.intval($row['quantity']).'" id="delivered_qty-'.$i.'"></td>';

                                                echo '<td class="text-center"><strong>'.$row['return_quantity'].'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="'.intval($row['return_quantity']).'" ></td>';
                                                // echo '<td class="text-center"><strong>'.$row['delivery_returned_qty'].'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="'.$row['delivery_returned_qty'].'"></td>';

                                                // echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.' title="'.$product_name_with_code.'ReturnQuantity" name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(),calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount(),convert_shipping_percentage_to_amount()" value="0"></td>';

                                                echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.' title="'.$product_name_with_code.'ReturnQuantity" name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(),calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount()" value="0"></td>';
                                                
                                                echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.'  title="'.$product_name_with_code.'DamagedQuantity" name="damaged_qty[]" id="damaged_qty-' . $i . '" value="0" onkeypress="return isNumber(event)" onkeyup="damageqtycheck(' . $i . ')" placeholder="'.$this->lang->line('Enter Qty').'"></td>';
                                                //rowTotal(0),billUpyog(), calculateDeliveryReturn(0),convert_order_discount_percentage_to_amount()
                                                echo '<td style="text-align:right;"><strong>'.$row['price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . amountExchange_s($row['price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                            if($configurations['config_tax']!='0')
                                            {
                                                echo '<td class="text-center"  style="font-weight:bold;">'.$row['total_tax'].'</td>';
                                                echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">0</td>';
                                            }

                                                // <!-- erp2024 modified section 07-06-2024 -->
                                                echo '<td class="text-center"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '"></strong></td>';

                                                    echo '<td class="text-right"><span class="currenty"></span>
                                                        <strong><span class="ttlText" id="result-' . $i . '"></span></strong></td>
                                                    </td>
                                                    
                                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="0">

                                                    <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['discount_type'].'">
                                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="0">
                                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"> 
                                                    <input type="hidden" name="product_tax[]" id="vat-' . $i . '" readonly value="'.$row['product_tax'].'">

                                                    <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" value="' . amountFormat_general($row['discount']) . '">
                                                    
                                                    <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '">
                                                

                                                </tr>';
                                                $i++; $j++;
                                            } 
                                                
                                            ?>

                                            
                                                <tr class="sub_c tr-border d-none" style="display: table-row; ">
                                                    <td colspan="9" align="right" class="no-border"><strong>Total Tax</strong></td>
                                                    <td align="left" colspan="2" class="no-border"><span
                                                            class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                                        <span id="taxr"
                                                            class="lightMode"><?php echo $taxtotal; ?></span>
                                                    </td>
                                                </tr>
                                                <!-- erp2024 removed section 07-06-2024 -->
                                            
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="7" class="no-border"></td>
                                                    <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                            (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                                    </td>
                                                    <td align="right" colspan="2" class="no-border">
                                                        <span id="grandamount">0.00</span>
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="11" align="right"  class="no-border"><strong><?php echo $this->lang->line('Total Product Discount') ?>(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                    <td align="right" colspan="2"  class="no-border">
                                                        <span id="discs"  class="lightMode discount_total" >0.00</span>
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="7" class="no-border"></td>
                                                    <td colspan="4" align="right"  class="no-border"><strong><?php echo $this->lang->line('Order Discount') ?>(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                    <td align="right" colspan="2"  class="no-border">
                                                        <span id="order_discount_text" class="lightMode">0.00</span>
                                                    </td>
                                                </tr>

                                                
                                                <tr class="sub_c d-none" style="display: table-row; ">
                                                    <td colspan="11" align="right" class="no-border"><input type="hidden" value="0"
                                                            id="subttlform" name="subtotal"><strong>Shipping</strong></td>
                                                    <td align="right" colspan="2" class="no-border">
                                                        <span id="shipping_text_value" class="lightMode">0.00</span>
                                                        <input type="hidden" class="form-control shipVal" readonly onkeypress="return isNumber(event)" placeholder="Value" value="0" name="shipping" autocomplete="off">
                                                    
                                                    </td>
                                                </tr>
                                                
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="4" class="no-border">
                                                        <?php if ($exchange['active'] == 1){
                                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                                        <select name="mcurrency" class="selectpicker form-control">

                                                            <?php
                                                        echo '<option value="' . $notemaster['multi'] . '">Do not change</option><option value="0">None</option>';
                                                        foreach ($currency as $row) {

                                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                                        } ?>

                                                        </select><?php } ?>
                                                    </td>
                                                    <td colspan="7" align="right" class="no-border">
                                                        <strong><?php echo $this->lang->line('Grand Total') 
                                                    
                                                        ?>
                                                            (<span
                                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                                    </td>
                                                    <?php
                                                        $grandtotal = 0;
                                                        $grandtotal = ($taxtotal+$productrate)-$discountrate;
                                                    ?>
                                                    <td align="right" colspan="2" class="no-border">
                                                    <span id="grandtotaltext">0.00</span>
                                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="" readonly>
                                                        <input type="hidden" id="order_discount" name="order_discount"  value="0">
                                                        <input type="hidden" id="shipping_amount" name="shipping_amount"  value="0">
                                                    </td>
                                                </tr>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="4"  class="no-border">
                                                    
                                                    </td>
                                                    <td align="right" colspan="8"  class="no-border">
                                                    <input type="submit" id="submit-invoice-return" class="btn btn-crud btn-primary btn-lg submitBtn" value="<?php echo $this->lang->line('Confirm & Return'); ?>"/>
                                                    <!-- <input type="submit" id="submit-data" class="btn btn-primary btn-lg submitBtn" value="Generate Delivery Return"/> -->
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                        <?php
                                    }
                                    ?>
                                        
                                  
                                    <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                                </div>
                            </div>
                            <!-- single tab section ends  -->

                             <!-- =================================================================== -->
                            <div class="tab-pane" id="tab3" role="tabpanel" aria-labelledby="base-tab3">
                                <div class="row">
                                    <div class="col-12">
                                            <!-- ===================================================== -->
                                            <div class="table-container overflow-auto">
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
                                            <div class="table-container overflow-auto">
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
                            <!-- =================================================================== -->
                        </div>

                        <!--========== Entire tab section ends =================== -->


                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    const changedFields = {};
$(document).ready(function() {


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

                  if (originalNumber !== newValue) {
                      changedFields[fieldId] = {
                          oldValue: originalNumber,
                          newValue: newValue,
                          fieldlabel : field_label
                      };
                  } else {
                      delete changedFields[fieldId]; // Remove if no change
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
         // erp2025 09-01-2025 ends
    $("#data_form").validate({
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            notes: { required: true }
        },
        messages: {
            notes: "Enter a reason for the return with a minimum of 5 characters",
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
        },
        invalidHandler: function(event, validator) {
            // Focus on the first invalid element
            if (validator.errorList.length) {
                $(validator.errorList[0].element).focus();
            }
        }
    });
});
function damageqtycheck(numb){
    
    if($("#damaged_qty-" + numb).val()>$("#delivered_qty-" + numb).val()){
        Swal.fire({
            icon: 'error',
            title: 'Invalid Input',
            text: 'Damaged Quantity is greater than Delivered Quantity',
            confirmButtonText: 'OK'
        });  
        $("#damaged_qty-" + numb).val(0);
    }
    
}
$('#deliverynoteFlg').change(function() {
    var currentValue = $(this).prop('checked');
    if (currentValue) {
        $(this).val('1');
        $("#deliverynoteFlg").attr("checked");
    } else {
        $(this).val('0');
        $("#deliverynoteFlg").removeAttr("checked");
    }
});

$("#submit-invoice-return").on("click", function(e) {
    e.preventDefault();
    if ($("#data_form").valid()) {  
        var validQtyFound = false; // Flag to track if a valid qty is found
        $("input[name='return_qty[]']").each(function() {
            if (parseInt($(this).val()) > 0) {
                validQtyFound = true;
                return ; // Exit loop once a valid qty is found
            }
        });

        if (!validQtyFound) {
            $("#amount-0").focus();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Return Quantity',
                text: 'Please enter at least one valid return quantity greater than 0.',
                confirmButtonText: 'OK'
            });
            $("#amount-0").val("");
            
            return; // Prevent form submission
        }

        // Use SweetAlert for confirmation
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to return these items?",
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
                var customerid = $("#customer_id").val();
                var formData = $("#data_form").serialize(); 
                formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'invoicecreditnotes/action',
                    data: formData,
                    success: function(response) {
                        var result = JSON.parse(response);
                        returnid = result.returnid;
                        window.open(baseurl + 'invoices/invoicereturn_print?delivery=' + returnid + '&cust=' + customerid, '_blank');
     
                         if (result.data === 'Customer Credit') {
                             window.location.href = baseurl + 'invoicecreditnotes';  
                         } else {
                            
                             window.location.href = baseurl + 'invoices/payment_return_to_customer?id='+returnid+'&csd='+customerid;
                         }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

            }
        });
    }
});

$("#submit-invoice-return-edit").on("click", function(e) {
    e.preventDefault();
    $('#submit-invoice-return-edit').prop('disabled',true);
    if ($("#data_form").valid()) {  
        var validQtyFound = false; // Flag to track if a valid qty is found
        $("input[name='return_qty[]']").each(function() {
            if (parseInt($(this).val()) > 0) {
                validQtyFound = true;
                $('#submit-invoice-return-edit').prop('disabled',false);
                return ; // Exit loop once a valid qty is found
            }
        });

        if (!validQtyFound) {
            $("#amount-0").focus();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Return Quantity',
                text: 'Please enter at least one valid return quantity greater than 0.',
                confirmButtonText: 'OK'
            });
            $("#amount-0").val("");
            $('#submit-invoice-return-edit').prop('disabled',false);
            return; // Prevent form submission
        }

        // Use SweetAlert for confirmation
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to return these items?",
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
                var invoiceid = parseInt($("#invoiceid").val());
                var customerid = $("#customer_id").val();
                var trans_ref_number = $("#bank_transaction_ref_number").val();
                var formData = $("#data_form").serialize(); 
                formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'invoicecreditnotes/invoice_creditnote_return_edit_action',
                    data: formData,
                    success: function(response) {
                        // Parse the JSON response
                        var result = JSON.parse(response);
                        returnid = result.returnid;
                        window.open(baseurl + 'invoices/invoicereturn_print?delivery=' + returnid + '&cust=' + customerid, '_blank');
                        // Check the data value
                        $('#submit-invoice-return-edit').prop('disabled',false);
                        if (result.data === 'Customer Credit') {
                            window.location.href = baseurl + 'invoicecreditnotes';  

                        } else {
                            
                            
                            window.location.href = baseurl + 'invoicecreditnotes/payment_return_to_customer_edit?id='+returnid+'&csd='+customerid+"&ref="+trans_ref_number;
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });

            }
        });
    }
    else{
        $('#submit-invoice-return-edit').prop('disabled',false);
    }
});



$('.deleteinvoice-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.deleteinvoice-btn').prop('disabled', true);

    var selectedProducts1 = [];
    $('.returnitem').each(function() {
        if ($(this).val() > 0) {
            selectedProducts1.push($(this).val());
        }
    });

    // Validate the form

    if (selectedProducts1.length === 0) {
        Swal.fire({
            text: "To proceed, please add at least one return quantity greater than zero.",
            icon: "info"
        });
        $('.deleteinvoice-btn').prop('disabled', false);
        return;
    }          
    var creditnotetid =  $("#creditnotetid").val();
    Swal.fire({
        title: "Are you sure?",
        text: "This will Delete the Invocie Return #"+creditnotetid+" and will Adjust the Accounts and Inventory.",
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
                url: baseurl + 'invoicecreditnotes/delete_invoice_return_action',
                type: 'POST',
                data: {
                    'invoiceid': $("#invocieid").val(),
                    'invoice_returnid': $("#invoice_returnid").val()
                },
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'Success') {
                            window.location.href = baseurl + 'invoicecreditnotes';
                    } else {
                        Swal.fire('Error', 'Failed to cancel the invoice', 'error');
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
</script>