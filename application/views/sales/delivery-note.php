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
            $salesorder_id = $invoice['salesorder_number'];
            $disable_class = ($id && $salesorder_id > 0) ? "disable-class" : "";
            $disable_class_invoiced ="";
            if($invoice['notestatus']=="Invoiced" || $returned_status=='1')
            {
                $disable_class_invoiced = "disable-class";
            }
            // $dnone_class = ($id && $invoice['notestatus']=='Draft') ? "d-none" : "";
            if(!$id)
            {
                $dnone_class = "d-none";
            }
            else if($id && $invoice['notestatus']=='Draft'  && $salesorder_id > 0)
            {
                $dnone_class = "d-none";
            }
            else{
                $dnone_class = "";
            }
           
            if($id && $salesorder_id > 0 && $invoice['notestatus']!='Draft')
            {
                $dnone_class_reverse =  "d-none";
                // $dnone_class_reverse = ($id && $salesorder_id > 0 && $invoice['notestatus']=='Draft') ? "d-none" : "";
            }
            $dnone_class_reverse_draft="";
            if($id && $invoice['notestatus']!='Draft')
            {
                $dnone_class_reverse_draft =  "d-none";
            }
            
            $shoptype = ($invoice['shop_type']=='Retail Shop') ? "checked" : "";
            $deliverynoteNumber = (!empty($invoice['delivery_note_number']) && !empty($action_type)) ? $invoice['delivery_note_number'] : $this->lang->line('Add New');
            $delivery_note_number = (!empty($invoice['delivery_note_number']) && !empty($action_type)) ? $invoice['delivery_note_number'] : $this->lang->line('Add New');
            $second_url = "";
            $prefixs = get_prefix_72();
            $suffix = $prefixs['suffix'];
            if (!empty($trackingdata) && $trackingdata['delivery_count'] > 1) {
                
                if ($trackingdata['salesorder_number']) {
                    $deliverynotenumber_track = remove_after_last_dash($trackingdata['deliverynote_number']);
                    $second_url = '<li class="breadcrumb-item"><a href="' . base_url('SalesOrders/delivery_notes?id=' . $trackingdata['salesorder_number']) . '">' . $deliverynotenumber_track . '-'.$suffix.'</a></li>';
                }
            }

            $pick_ticket_status1 = $invoice['pick_ticket_status'];
            $pick_item_recieved_status1 = $invoice['pick_item_recieved_status'];
            $pick_item_recieved_note1 = $invoice['pick_item_recieved_note'];
            $credit_checkcls = "";
            $disable_element_flg = 0;
            $draft_hiddenclass="";
            $input_disable="";
            $notrecivedflg = ($invoice['deliveryduedate'] && $invoice['pick_item_recieved_status']=="0" && $invoice['notestatus'] != 'Draft') ? "disable-class" : "";
            $deliveryduedate = (!empty($invoice['deliveryduedate']) && $invoice['deliveryduedate'] != '0000-00-00') 
            ? $invoice['deliveryduedate'] 
            : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['deliverynote_validity'] . " days"));


            if($invoice['notestatus'])
            {
                switch(true) {
                    case ($returned_status == '1'):
                        $messagetext = "All items in the delivery note Fully Returned";
                        $status = "Completed";
                        $text_color = "alert-success";
                        $disable_element_flg = 1;
                        break;
                    case ($invoice['notestatus'] == "Canceled"):
                        $messagetext = "Delivery Note has been Canceled";
                        $status = "Canceled";
                        $text_color = "alert-danger";
                        $credit_checkcls = "d-none";
                        $disable_element_flg = 1;
                        break;
                    case ($invoice['notestatus'] == 'Created'):
                        $messagetext = "Assigned But Not Print the Picking List Yet";
                        $status = "Created";
                        $text_color = "alert-partial";
                        break;
                    case ($invoice['notestatus'] == 'Invoiced'):
                        $messagetext = $invoice['notestatus'];
                        $invoice_numbers = "(" . $invoice['invoice_number'] . ")";
                        $status = "Completed";
                        $text_color = "alert-success";
                        $disable_element_flg = 1;
                        break;
                    case ($invoice['notestatus'] == 'Draft'):
                        $messagetext = "";
                        $status = "Draft";
                        $text_color = "alert-secondary";
                        $draft_hiddenclass="d-none";
                        $disable_element_flg = 0;
                        break;                   
                  
                
                    case ($pick_item_recieved_status1 == 1 && $invoice['notestatus'] == 'In Progress'):
                        $messagetext = "Items Picked";
                        $status = "In Progress";
                        $text_color = "alert-progress";
                        break;
                
                    case ($pick_ticket_status1 == 1 && $invoice['notestatus'] == 'In Progress'):
                        $messagetext = "Picking List Printed";
                        $text_color = "alert-progress";
                        $status = "In Progress";
                        break;
                
                    case ($invoice['notestatus'] == "Completed"):
                        $messagetext = "Delivery Note has been Completed";
                        $status = "Completed";
                        $text_color = "alert-success";
                        $input_disable = "disable-class";
                        break;
                
                
                
                    default:
                        // Handle the default case if necessary
                        break;
                }
            }
            
        ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('DeliveryNotes') ?>"><?php echo $this->lang->line('Delivery Notes'); ?></a></li>   
                    <?php echo $second_url; ?>                
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $delivery_note_number; ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-2 col-md-3 col-sm-12 col-xs-12">
                <h4 class="card-title"><?php echo $delivery_note_number; ?></h4>
                
                </div>
                <div class="col-xl-7 col-lg-10 col-md-7 col-sm-12 col-xs-12">  
                    <ul id="trackingbar" class="<?=$dnone_class?>">
                        
                        
                        <?php 
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
                                echo '<li class="active">' . $trackingdata['deliverynote_number'] . '</li>';
                               
                            }
                            if (!empty($trackingdata['delivery_return_number'])) { 
                               echo '<li><a href="' . base_url('Deliveryreturn/deliveryreturn?delivery=' . $trackingdata['delivery_return_number']).'">' . $trackingdata['delivery_return_number'] . '</a></li>';
                            }
                            if (!empty($trackingdata['invoice_number'])) { 
                               echo '<li><a href="' . base_url('invoices/create?id=' . $trackingdata['invoice_number']).'">' . $trackingdata['invoice_number'] . '</a></li>';
                            }
                            if (!empty($trackingdata['invoice_retutn_number'])) { 
                               echo '<li><a href="' . base_url('invoicecreditnotes/create?iid=' . $trackingdata['invoice_retutn_number']).'">' . $trackingdata['invoice_retutn_number'] . '</a></li>';
                            }
                         }
                        
                       
                    ?>
                            
                    </ul>  

                    <!-- -------------------- -->
                    <div class="btn-group alert alert-warning text-center <?=$dnone_class_reverse?> <?=$dnone_class_reverse_draft?>" role="alert">
                        <!-- -------------------- -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shoptype" <?=$shoptype?>>
                            <label class="form-check-label" for="shoptype">
                                <strong class="fsize-14"><?php echo $this->lang->line('Are you a retail shop?'); ?></strong>
                            </label>
                        </div>
                        <!-- -------------------- -->
                    </div> 
                    <!-- -------------------- -->

                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 current-status">
                  <?php  if($status) {
                        echo '<div class="btn-group alert text-center '.$text_color.'" role="alert">'.$status.'</div>';
                   } ?>
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
                <form method="post" id="data_form" enctype="multipart/form-data">
                <div id="deliverynote_status"></div>
                    
                    <div class="row">
                            
                            <div class="col-12 row">
                                <div class="col-lg-5 col-md-5 col-sm-12">
                                    <div class="title-action">
                                        <?php 
                                         $delivernoteid = $invoice['delivery_note_number'];
                                         $customer_id = $invoice['customer_id'];
                                         $salesorder_number = $invoice['salesorder_number'];
                                        echo '<input type="hidden" name="notestatus" id="notestatus" value="' . $invoice['notestatus'] . '">';
                                        echo '<input type="hidden" name="elementflg" id="elementflg" value="' . $disable_element_flg . '">';
                                        $cancel_status_btn= (strtolower($invoice['notestatus'])=="canceled") ? "d-none" : "";
                                        if(strtolower($invoice['notestatus'])!="canceled")
                                        { 
                                            
                                            ?>
                                            <button class="btn btn-sm btn-secondary mb-1 d-none" title="purchase Order" id="purchaseOrderBtn" type="button"><span class="fa fa-file-code-o"></span>
                                            <?php echo $this->lang->line('Add to purchase Order') ?> </button>
                                            <a href="javascript:void(0)" class="btn  btn-sm btn-secondary mb-1 breaklink <?=$dnone_class?> d-none" id="MaterialReport" ><i class="fa fa-book"></i> <?php echo $this->lang->line('Material Request') ?> </a>
                                            <a href="javascript:void(0)" class="btn  btn-sm btn-secondary mb-1 <?=$dnone_class?> d-none" id="PurchaseRequest"><i class="fa fa-file-text"></i> <?php echo $this->lang->line('Purchase Request') ?> </a>

                                            
                                            <?php
                                            if(($invoice['notestatus'] == 'Completed' || $invoice['notestatus'] == 'Invoiced') && $return_status = 1)
                                            {
                                                
                                                
                                                    echo '<a href="'.base_url('invoices/create?dnid='.$delivernoteid).'"  class="btn btn-sm btn-secondary mb-1 '.$disable_class_invoiced.'" title="Convert to Invoice"><i class="fa fa-exchange"></i> Convert to Invoice</a>';

                                                   

                                                    // echo '<a href="'.base_url('DeliveryNotes/convert_deliverynote_to_invoice?id='.$delivernoteid).'"  class="btn btn-sm btn-secondary mb-1"><i class="fa fa-exchange"></i> Convert to Invoice</a>';
                                                
                                            }
                                            if($action_type)
                                            {
                                             echo ' <a href="'.base_url('Deliveryreturn/deliveryreturn?delivery='.$delivernoteid.'&type=new').'"  class="btn btn-sm btn-secondary mb-1 '.$disable_class_invoiced1.'" title="Delivery Return"><i class="fa fa-undo"></i> Delivery Return</a>';
                                            }
                                            
                                            ?>
                                            <!-- erp2024 hide Update Inventory and refresh screen 18-06-2024 -->
                                            <!-- <button class="btn  btn-sm btn-info mb-1" type="button" name="updateInventoryBtn"
                                                id="updateInventoryBtn"><i class="fa fa-refresh"></i>
                                                <?php echo $this->lang->line('Update Inventory'); ?></button>
                                                
                                                <button class="btn  btn-sm btn-danger mb-1" type="button" name="refreshBtn"
                                                id="refreshBtn"><i class="fa fa-refresh"></i>
                                                <?php echo $this->lang->line('Refresh Screen'); ?></button> -->
                                            <!-- erp2024 hide Update Inventory and refresh screen 18-06-2024 ends-->
                                            <!-- erp2024 write off button 06-09-2024 -->
                                        <?php } ?>
                                    
                                    </div>
                                </div>
                                <!-- ============= -->
                                <div class="col-lg-5 col-md-5 col-sm-12 <?=$dnone_class1?> text-center messagetext_class">
                                    <?php 
                                    if($messagetext)
                                    {
                                        echo '<div class="btn-group alert alert-success">'.$messagetext.' '.$invoice_numbers.'</div>';
                                    }
                                     ?>         
                                                                  
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 text-lg-right text-sm-left <?=$dnone_class?>">
                                    <?php echo '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=$delivernoteid&sales=$salesorder_number&cust=$customer_id") . '" target="_blank" class="btn btn-sm btn-secondary mb-1 btn-crud" title="Print"><i class="fa fa-print"></i> Print</a>';  ?>                                 
                                </div>
                                <!-- ============= -->
                            </div>
                            </div>
                        
                            <?php
                            $invoiceduedate = (!empty($invoice['due_date']) && $invoice['due_date'] != '0000-00-00') 
                            ? $invoice['due_date'] 
                            : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['quote_validity'] . " days"));
                            $term = ($invoice['payment_term'])?$invoice['payment_term']:$validity['payment_terms'];
                            
                            $headerclass= "d-none";
                            $pageclass= "page-header-data-section-dblock";
                            if($id)
                            {
                                $headerclass = "page-header-data-section-dblock";
                                $pageclass   = "page-header-data-section";
                            }
                            $customer_id = $invoice['customer_id'];
                            $employee_id = $created_employee['id']; 
                            ?>

                            <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                                        <h3  class="title-sub"><?php echo $this->lang->line('Delivery Note & Customer Details') ?> <i class="fa fa-angle-down"></i></h3>
                                    </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 responsive-text-right quickview-scroll order-1 order-lg-2">
                                            <div class="quick-view-section">
                                                <div class="item-class text-center">
                                                    <h4><?php echo $this->lang->line('Customer') ?></h4>
                                                    <?php
                                                        echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($invoice['name']) . "</b></a>";
                                                    ?>
                                                </div>
                                                <div class="item-class text-center d-none">
                                                    <h4><?php echo $this->lang->line('Credit Limit') ?></h4>
                                                    <?php
                                                        echo "<b>".$assigned_customer['avalable_credit_limit'] . "</b> / <b>".$assigned_customer['credit_limit'] . "</b>";
                                                    ?>
                                                </div>
                                                <div class="item-class text-center  d-none">
                                                    <h4><?php echo $this->lang->line('Credit Period') ?></h4>
                                                    <?php
                                                    echo "<b>".htmlspecialchars($assigned_customer['credit_period']) . " Days</b></a>";
                                                    ?>
                                                </div>
                                                <div class="item-class text-center">
                                                    <h4><?php echo $this->lang->line('Created') ?></h4>
                                                    <?php echo "<p>".dateformat($invoice['created_date'])."</p>"; ?>
                                                </div>
                                                <div class="item-class text-center">
                                                    <h4><?php echo $this->lang->line('Sales Point') ?></h4>
                                                    <?php echo "<p>".$invoice['warehousename']."</p>"; ?>
                                                </div>
                                                <div class="item-class text-center">
                                                    <h4><?php echo $this->lang->line('Created By') ?></h4>
                                                    <?php 
                                                        echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                                    ?>
                                                </div>
                                                <div class="item-class text-center">
                                                    <h4><?php echo $this->lang->line('Due Date') ?></h4>
                                                    <?php echo "<p style='color:".$colorcode."'>".dateformat($deliveryduedate)."</p>"; ?>
                                                </div>
                                                <div class="item-class text-center">
                                                    <h4><?php echo $this->lang->line('Total'); ?></h4>
                                                    <?php echo "<p>".number_format($invoice['total_amount'],2)."</p>";?>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="<?=$pageclass?>">
                                <div class="row">
                                    <div class="col-lg-3 col-md-5 col-sm-12 cmp-pnl">
                                        <div id="customerpanel" class="inner-cmp-pnl"> 
                                            <h3 class="title-sub"><?php echo $this->lang->line('Customer Details'); ?></h3> 
                                            <?php
                                                $customer_search_section = ($id) ? "d-none" : "";
                                            //  if($id)
                                            //  { 
                                                ?>
                                                    <div class="frmSearch customer-search-section <?=$customer_search_section?>">
                                                        <!-- <label for="cst" class="col-form-label">
                                                        <?php //echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></label> -->
                                                        <label for="customer_name" class="col-form-label d-flex justify-content-between align-items-center" id="customerLabel">
                                                        <span><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></span>
                                                            <input type="button" value="Add New Customer" class="btn btn-sm btn-secondary add_customer_btn" autocomplete="off" title="Add New Customer">
                                                        </label>
                                                        <input type="text" class="form-control" name="cst" id="customer-box" placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>" autocomplete="off" title="Customer Search"/>
                                                        <div id="customer-box-result"></div>
                                                    </div>
                                                <?php
                                            //  }  ?>                      
                                            <div id="customer" class="mt-2">
                                                
                                                <?php
                                                if($id)
                                                {
                                                    echo '<div class="existingcustomer_details">';
                                                    echo '<div class="clientinfo">
                                                    <div id="customer_name"><strong>' . $invoice['name'] . '</strong><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectionedit '.$input_disable.'">'.$this->lang->line("Customer Edit").'</button><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectioncancel d-none">'.$this->lang->line("Customer Cancel").'</button></div></div></div>';
                                                    ?>
                                                    <div class="clientinfo">                                            
                                                        <?php
                                                        
                                                        if($creditlimtcompare=='1'){
                                                            $cls = "text-danger";
                                                        }
                                                        else{
                                                            $cls = "";
                                                        }
                                                        $cls = $cls." avalable_credit_limit";
                                                        echo '<input type="hidden" name="customer_id" id="customer_id" value="' . $invoice['customer_id'] . '">
                                                        </div>
                                                        <div class="clientinfo">

                                                            <div id="customer_address1"><strong>' . $invoice['address'] . '<br>' . $invoice['city'] . ',' . $invoice['country'] . '</strong></div>
                                                        </div>

                                                        <div class="clientinfo">
                                                            <div type="text" id="customer_phone">Phone: <strong>' . $invoice['phone'] . '</strong><br>Email: <strong>' . $invoice['email'] . '</strong></div>
                                                        </div>
                                                        <div class="clientinfo">
                                                        <div class="display-section" >'.$this->lang->line('Company Credit Limit').' : <strong>' . $invoice['credit_limit'] . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $invoice['credit_period'] . '(Days)</strong><br><br><span class='.$cls.'  id="avalable_credit_limit" >'.$this->lang->line('Available Credit Limit').' : <strong>' . $invoice['avalable_credit_limit'] . '</strong></span><input type="hidden" name="avalable_credit_limit1" id="available_credit" value="' . number_format($invoice['avalable_credit_limit'],2) . '">
                                                        </div>';
                                                        
                                                        $delivery_transaction_number = ($invoice['transaction_number']) ? $invoice['transaction_number'] : "0";
                                                        ?>
                                                    </div>
                                                <?php
                                                }
                                                else{
                                                    ?>
                                                    <div class="clientinfo">
                                                        <?php //echo $this->lang->line('Client Details');?>
                                                        <!-- <hr> -->
                                                        <input type="hidden" name="customer_id" id="customer_id" value="0">
                                                        <div id="customer_name"></div>
                                                    </div>
                                                    <div class="clientinfo">
                                                            <div id="customer_address1"></div>
                                                    </div>

                                                    <div class="clientinfo">
                                                            <div type="text" id="customer_phone"></div>
                                                    </div>
                                                    <?php
                                                }?>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-12 col-sm-12 cmp-pnl">
                                    
                                        <div class="inner-cmp-pnl">
                                            <div class="form-row">
                                        
                                                <div class="col-sm-12"><h3 class="title-sub"><?php echo $this->lang->line('Delivery Note Properties'); ?></h3></div>

                                                <!-- erp2024 modified section 07-06-2024 -->
                                                
                                            
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="completedstatus" id="completedstatus" value="<?=$invoice['status']?>">
                                                    <input type="hidden" name="action_type" id="action_type" value="<?=$action_type?>">
                                                    <input type="hidden" name="pick_item_recieved_status" id="pick_item_recieved_status" value="<?=$invoice['pick_item_recieved_status']?>">
                                                    <input type="hidden" name="delevery_note_type" id="delevery_note_type" value="<?=$delevery_note_type?>">
                                                    <input type="hidden" name="delivery_transaction_number" id="delivery_transaction_number" value="<?=$delivery_transaction_number?>">
                                                    <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Sale Point') ?><span class="compulsoryfld"> *</span></label>
                                                    <select name="s_warehouses" id="s_warehouses" class="selectpicker form-control <?=$disable_class?>" title="<?php echo $this->lang->line('Sale Point') ?>">
                                                    <?php 
                                                        echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                                        <?php foreach ($warehouse as $row) {
                                                            $sel="";
                                                            if($invoice['store_id']== $row['store_id']){
                                                                $sel="selected";
                                                            }
                                                        echo '<option value="' . $row['store_id'] . '" '.$sel.'>' . $row['store_name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                    <input type="hidden" class="form-control" name="store_id" id="store_id" value="<?php echo $invoice['store_id']; ?>">
                                                    <?php 
                                                    
                                                    if($this->session->userdata('repeatsubmit') > 1) {
                                                        $delnoteexpect_num = $invoice['current_delnote_number'];
                                                    
                                                    }
                                                    else{?>
                                                        <?php 
                                                        if($invoice['delnote_tid']>0){
                                                            $delnoteexpect_num = intval($invoice['delnote_tid'])."-".intval($invoice['delnote_seq_number']+1);
                                                        }
                                                        else{
                                                            $delnoteexpect_num =(intval($this->session->userdata('latest_delnote_id'))+1000);
                                                        }
                                                        ?>
                                                        
                                                    <?php }
                                            
                                                    $invoiceid = $prefix.$lastinvoice + 1;
                                                    if($new_id)
                                                    {
                                                        $invoice['delevery_note_id'] = $new_id;
                                                        $invoice['delivery_note_number'] = $new_id;
                                                    }

                                                    ?>
                                                    <input type="hidden" class="form-control" name="invocieno_demo" id="invocieno_demo" value="<?php echo $invoice['delivery_note_number']; ?>" readonly>
                                                    <!-- <input type="text" class="form-control" name="invoice_number" id="invoice_number" value="<?php echo $invoiceid; ?>" readonly> -->
                                                    <input type="hidden" class="form-control" name="delnote_seq_number" id="delnote_seq_number" value="<?php echo $invoice['delnote_seq_number']; ?>">
                                                    <input type="hidden" class="form-control" name="delnote_tid" id="delnote_tid" value="<?php echo $invoice['delnote_tid']; ?>">
                                                    <input type="hidden" class="form-control" name="delevery_note_id" id="delevery_note_id" value="<?php echo $invoice['delivery_note_number']; ?>">
                                                    <input type="hidden" class="form-control" name="salesorder_number" id="salesorder_number" value="<?php echo $invoice['salesorder_number']; ?>">
                                                    <input type="hidden" class="form-control" placeholder="Delivery Note Number" name="invocieno" id="invocienoId" value="<?php echo $invoice['delnote_tid']; ?>" readonly>
                                                </div>
                                                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                    <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Delivery Note Number'); ?>  </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>                                               
                                                    <input type="text" class="form-control" name="delivery_note_number" id="delivery_note_number" title="<?php echo $this->lang->line('Delivery Note Number') ?>" value="<?php echo $delivery_note_number; ?>" readonly>
                                                    <!-- <input type="text" class="form-control" placeholder="Delivery Note Number" name="invocieno" id="invocienoId" value="<?php echo (intval($this->session->userdata('latest_delnote_id'))+1000); ?>" readonly> -->
                                                        
                                                    
                                                    </div>
                                                    <!-- erp2024 modified section 07-06-2024 Ends -->
                                                </div>
                                                
                                                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12 <?=$dnone_class?>"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Sales Order Number'); ?></label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="icon-bookmark-o" aria-hidden="true"></span></div>
                                                        <input type="text" class="form-control"
                                                            name="salesorder_number1" id="salesorder_number" value="<?php echo $invoice['salesorder_number']; ?>" readonly>
                                                    </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12  <?=$dnone_class?>">
                                                    <label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Sales Order Date'); ?></label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="icon-calendar4"
                                                                                            aria-hidden="true"></span></div>

                                                                                           
                                                        <input type="text" class="form-control"
                                                            placeholder="Billing Date" name="invoicedate" id="invoicedate"
                                                            autocomplete="false" value="<?php echo (!empty($invoice['invoicedate']))?date("d-m-Y", strtotime($invoice['invoicedate'])):""; ?>" readonly> 
                                                            <input type="hidden" name="iid" value="<?php echo $invoice['salesorder_number']; ?>" >
                                                    </div>
                                                </div>

                                                
                                                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference'); ?><span class="compulsoryfld">*</span></label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="icon-bookmark-o" aria-hidden="true"></span></div>
                                                        <input type="text" class="form-control <?=$disable_class?>" placeholder="Reference #" title="<?php echo $this->lang->line('Reference') ?>" name="refer" id="refer" value="<?php echo (!empty($invoice['refer']))?$invoice['refer']:$invoice['delnoterefer']; ?>" >
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="reference_date" class="col-form-label"><?php echo $this->lang->line('Reference Date'); ?></label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="icon-calendar-o"  aria-hidden="true"></span></div>
                                                        <?php

                                                            $invoiceDate = (($id) && !empty($invoice['reference_date']))?$invoice['reference_date']:dateformat_ymd($invoice['delivery_note_date']);
                                                        ?>
                                                        <input type="date" class="form-control <?=$disable_class?>" name="reference_date" id="reference_date"  placeholder="Validity Date" autocomplete="false" value="<?php echo $invoiceDate; ?>" title="<?php echo $this->lang->line('Reference Date') ?>" data-original-value="<?php echo $invoiceDate; ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12"></div>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="deliveryduedate" class="col-form-label"><?php echo $this->lang->line('Deliverynote Validity') ?> <span class="compulsoryfld">*</span></label>
                                                    <input type="date" class="form-control" name="deliveryduedate" placeholder="Due Date" autocomplete="false" value="<?php echo $deliveryduedate ?>"  data-original-value="<?php echo $deliveryduedate; ?>">
                                                   
                                                </div>
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="customer_po_reference" class="col-form-label"><?php echo $this->lang->line('Customer PO / Reference'); ?></label> 
                                                    <input type="text" class="form-control <?=$disable_class?>" name="customer_po_reference" id="customer_po_reference" value="<?php echo $invoice['customer_po_reference']; ?>" title="<?php echo $this->lang->line('Customer PO / Reference') ?>" data-original-value="<?php echo $invoice['customer_po_reference']; ?>">
                                                </div>

                                                
                                                
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="customer_contact_person" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label> 
                                                    <input type="text" class="form-control <?=$disable_class?>" name="customer_contact_person" id="customer_contact_person" value="<?php echo $invoice['customer_contact_person']; ?>" title="<?php echo $this->lang->line('Customer Contact Person') ?>">
                                                </div>
                                                
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label> 
                                                    <input type="text" class="form-control <?=$disable_class?>" name="customer_contact_number" id="customer_contact_number" value="<?php echo $invoice['customer_contact_number']; ?>" title="<?php echo $this->lang->line('Contact Person Number') ?>">
                                                </div>
                                                
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label> 
                                                    <input type="email" class="form-control <?=$disable_class?>" name="customer_contact_email" id="customer_contact_email" value="<?php echo $invoice['customer_contact_email']; ?>" title="<?php echo $this->lang->line('Customer Contact Email') ?>">
                                                </div>
                                                
                                            
                                            
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12  <?=$dnone_class?> d-none">
                                                    <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Sales Order Note') ?></label>
                                                    <textarea class="form-textarea textarea-bg" name="notes" id="proposal" rows="2" readonly title="<?php echo $this->lang->line('Sales Order Note') ?>"><?php echo $invoice['notes'] ?></textarea>
                                                </div>
                                                
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12  <?=$dnone_class?> d-none">
                                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?></label>
                                                        <textarea class="form-textarea textarea-bg" name="proposal" id="proposal" rows="2" readonly><?php echo $invoice['proposal'] ?></textarea>
                                                <div>
                                                    
                                            </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="form-row">
                                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 <?=$dnone_class?> d-none">
                                                        
                                                        <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Current Status') ?></label><br>
                                                        <strong class=""><?=$status?></strong>
                                                    </div>  
                                                    <?php
                                                     if($pick_item_recieved_status1== '1' && !empty($invoice['pick_item_recieved_note'])){ ?>
                                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                            <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Items Picked Note') ?></label>
                                                            <textarea class="form-textarea textarea-bg" name="pick_item_recieved_note" id="pick_item_recieved_note" rows="2" readonly><?php echo $invoice['pick_item_recieved_note'] ?></textarea>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                        <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Note') ?></label>
                                                        <textarea class="form-textarea" name="note" id="note" rows="2" title="<?php echo $this->lang->line('Note') ?>" data-original-value="<?php echo $invoice['note']; ?>"><?php echo $invoice['note'] ?></textarea>
                                                    </div>
                                                </div>                                       
                                            </div>

                                            <div class="form-group row d-none">
                                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="taxformat" class="col-form-label">Tax</label>
                                                    <select class="form-control" onchange="changeTaxFormat(this.value)" id="taxformat">
                                                        <?php echo $taxlist; ?>
                                                    </select>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                    <div class="form-group">
                                                        <label for="discountFormat" class="col-form-label">Discount</label>
                                                        <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                                id="discountFormat">
                                                            <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                            <?php echo $this->common->disclist() ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
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
                                            <!-- <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1"> -->
                                                <!-- ===== Image sections starts ============== -->
                                                <div class="container-fluid overflow-auto">
                                                    <div class="mt-2">
                                                        
                                                        <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12"> -->
                                                            <?php 
                                                        
                                                        $imgcontains = 0;
                                                        if (!empty($images)) {
                                                            echo '<table class="table table-striped table-bordered">';
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
                                                <!-- ===== Image sections ends ============== -->

                                            <!-- </div> -->
                                            </div>
                                    </div>

                                </div>
                            </div>

                        <!-- <div class="container1">
                            <div class="form-check" style="font-size:16px; margin-top:10px;  margin-bottom:10px;"> -->
                                <input class="form-check-input d-none" type="checkbox" value="1" id="deliverynoteFlg" checked>
                                <!-- <label class="form-check-label" for="deliverynoteFlg">
                                    Delivery note with price
                                </label>
                            </div>
                        </div> -->
                        
                    
                        <!-- <div class="<?=$credit_checkcls?>" id="creditlimit-check"></div> -->

                        <!-- ========================= tab starts ==================== -->
                        <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                        aria-controls="tab1" href="#tab1" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Delivery Note Properties') ?></a>
                                </li>
                               
                                <!-- <li class="nav-item">
                                    <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab3"
                                        href="#tab3" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Payments Received') ?></a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link breaklink navtab-caption" id="base-tab4" data-toggle="tab" aria-controls="tab4"
                                        href="#tab4" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Journals') ?></a>
                                </li>

                                
                        </ul>
                        
                        <div class="tab-content px-1 pt-1">
                            <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                                  
                                <input type="hidden" class="form-control deleted_item" name="deleted_item">
                                <div id="saman-row" class="overflow-auto">
                                    <?php
                                     $i=0;
                                     $discount_flg=0;
                                    if($id && $salesorder_id)
                                    { ?>
                                        <table class="table table-striped table-bordered zero-configuration dataTable">
                                            <thead>

                                            <tr class="item_header bg-gradient-directional-blue white">
                                                <!-- <th width="2%" style="padding-left:10px;">
                                                    <input type="checkbox" id="prdcheckbox" name="prdcheckbox">
                                                </th> -->
                                                <th width="2%" class="text-center"><?php echo $this->lang->line('SN') ?></th> 
                                                <!-- <th width="4%" style="padding-left:10px;">Sl.No</th> -->
                                                <!-- <th width="15%" style="padding-left:10px; text-align:left !important;">Item Code</th> -->                             
                                                 
                                                <th width="10%" class="text-center1">Item No.</th>   
                                                <th width="20%" class="text-center1">Item Name</th>                              
                                                <th width="6%" class="text-center">Unit</th>
                                                <th width="6%" class="text-center">Stock in Sales Points</th>
                                                <th width="8%" class="text-center">Ordered Qty</th>
                                                <th width="8%" class="text-center">Rem. Qty</th>
                                                <th width="8%" class="text-center">Transfered Qty</th>
                                                <th width="8%" class="text-center">Delivery Qty</th>  
                                                <th width="10%" class="text-right">Rate</th>
                                                <?php 
                                                    if($configurations['config_tax']!='0'){  ?>
                                                        <th width="10%" class="text-center">Tax</th>
                                                <?php } ?>
                                                <th width="5%" class="text-right">Unit Cost</th>
                                                <th width="5%" class="text-right">Total Discount</th>
                                                <th width="10%" class="text-right">
                                                    Amount<?php //echo "(".$this->config->item('currency').")"; ?>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 0;$j = 1;
                                            $unicost = 0;
                                            $totaldiscount=0;
                                            $grandtotal =0;
                                            $subtotal = 0;
                                            $grantamount=0;
                                            $k=1;
                                            // echo "<pre>"; print_r($products);
                                            foreach ($products as $row) {
                                                $prdQty=0;
                                                $totaldiscountdraft=0;
                                                $product_name_with_code = $row['product_name'].'('.$row['product_code'].') - ';
                                                $productcode = $row['product_code'];
                                                // if($invoice['notestatus']!='Assigned')
                                                // {
                                                    $prdQty = $row['deliverynote_quantity'];
                                                    $totaldiscountdraft = $row['totaldiscount'];
                                                // }
                                                $rem_qty = (intval($row['del_remaining_qty'])>0) ? intval($row['del_remaining_qty']) : intval($row['deliverynote_quantity']);
                                                
                                                $unicost = ($row['deliverysubtotal']>0) ?round($row['deliverysubtotal'] / $row['deliverynote_quantity'], 2):0;
                                            
                                                echo '<input type="hidden" class="form-control" name="product_name[]" id="productname-' . $i . '"  value="' . $row['product_name'] . '"   >';
                                                echo '<input type="hidden" class="form-control code" name="product_code[]" value="' . $row['product_code'] . '">';
                                                // echo '<input type="hidden" class="form-control" name="hsn[]" value="' . $row['product_code'] . '">';
                                                if($row['totalQty']<=$row['alert']){
                                                    echo '<tr style="background:#ffb9c2;">';
                                                }
                                                else{
                                                    echo '<tr >';
                                                }
                                                echo '<td width="2%"><input type="checkbox" class="checkedproducts d-none" name="product_id[]" value="'.$row['product_id'].'" id="prd-'.$row['product_id'].'">'.$k++.'<input type="checkbox" class="checkedproducts1 d-none" name="product_id_sub[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" checked >
                                                <input type="hidden" class="form-control" name="income_account_number[]" value="'.$row['income_account_number'].'"><input type="hidden" class="form-control" name="product_cost[]" value="'.$row['product_cost'].'"></td>';
                                                // echo '<td width="4%">'.$j.' <input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" checked></td>';
                                                // echo '<td width="15%"><strong>'.$row['product_code'].'</strong> </td>';
                                                echo '<td><strong>'.$row['product_code'].'</strong> </td>';
                                                echo '<td><strong>'.$row['product_name'].'</strong> </td>';
                                                echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';
                                                echo '<td class="text-center"><strong id="onhandQty-'.$i.'">'.$row['onhandqty'].'</strong>&nbsp; <button onclick="single_product_stock(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary"  title="Stock List"><i class="fa fa-info"></i></button></td>';
                                                $ordered_qty = ($row['salesorder_product_qty']) ?intval($row['salesorder_product_qty']) : intval($row['deliverynote_quantity']);
                                                echo '<td class="text-center"><strong>'.$ordered_qty.'</strong> </td>';

                                            
                                                if($this->session->userdata('repeatsubmit')>1)
                                                {
                                                    echo '<td class="text-center"><strong>'.$rem_qty.'</strong> </td>';
                                                    // echo '<td class="text-center"><strong>'.intval($row['del_remaining_qty']).'</strong> </td>';
                                                }
                                                else{
                                                    echo '<td class="text-center"><strong>'.$rem_qty.'</strong> </td>';
                                                    // echo '<td class="text-center"><strong>'.intval($row['qty']).'</strong> </td>';
                                                }

                                                echo '<td class="text-center"><strong>'.intval($row['del_transfered_qty']).'</strong><input type="hidden" class="form-control req" name="rem_qty[]" id="remqty-' . $i . '" value="'.$rem_qty.'" ></td>';
                                                // echo '<td class="text-center"><strong>'.intval($row['qty']).'</strong> </td>';

                                                // $this->db->select('cberp_delivery_note_items.product_qty AS current_product_qty,cberp_delivery_note_items.subtotal AS current_subtotal,cberp_delivery_note_items.totaltax AS current_totaltax,cberp_delivery_note_items.totaldiscount AS current_totaldiscount'); product_qty
                                                
                                                if($this->session->userdata('repeatsubmit') > 1) { 
                                                    $productqty = intval($row['deliverynote_quantity']);
                                                    $prdqtyfld = '<input type="number" class="form-control req amnt product_qty" name="product_qty[]" title="'.$product_name_with_code.'Quantity" id="amount-' . $i . '" data-original-value="' . $productqty . '" onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowDiscountTotal(' . $i . '), billUpyog(), credit_limit_with_grand_total(), orderdiscount(), credit_limit_with_grand_total()" autocomplete="off" value="' . $productqty . '" min="0">';
                                                    // $prdqtyfld = '<input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowDiscountTotal(' . $i . '), billUpyog(), credit_limit_with_grand_total(), orderdiscount()" autocomplete="off" value="' . $rem_qty . '" min="0">';
                                                    // erp2024 old section
                                                    // if(intval($row['deliverynote_quantity'])>0 && $invoice['notestatus']!='Assigned')
                                                    // erp2024 old section ends
                                                    if(intval($row['deliverynote_quantity'])>0)
                                                    {
                                                        $currentdiscount = $row['deliverytotaldiscount'];
                                                        $current_subtotal = $row['deliverysubtotal'];
                                                        $totaldiscount = $currentdiscount + $totaldiscount;
                                                        $grandtotal = $grandtotal + $current_subtotal;
                                                    }
                                                    else{
                                                        $currentdiscount = 0;
                                                        $current_subtotal=0.00;
                                                    }
                                                    
                                                }
                                                else{
                                                    $productqty = intval($row['deliverynote_quantity']);
                                                    $prdqtyfld = '<input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowDiscountTotal(' . $i . '), billUpyog(), credit_limit_with_grand_total(), orderdiscount()" autocomplete="off" value="'.$productqty.'" min="0" data-original-value="' . $productqty . '" title="'.$product_name_with_code.'Quantity">';
                                                    // $prdqtyfld = '<input type="number" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowDiscountTotal(' . $i . '), billUpyog(), credit_limit_with_grand_total(), orderdiscount()" autocomplete="off" value="'.$rem_qty.'" min="0">';
                                                    //erp2024 old section 26-09-2024
                                                    // $currentdiscount = 0;
                                                    // $current_subtotal=0.00;
                                                    //erp2024 old section 26-09-2024
                                                    $currentdiscount = $row['deliverytotaldiscount'];
                                                    $current_subtotal = $row['deliverysubtotal'];
                                                    $totaldiscount = $currentdiscount + $totaldiscount;
                                                    $grandtotal = $grandtotal + $current_subtotal;
                                                    // $subtotal      += $row['deliverytotaldiscount'];
                                                    
                                                }
                                                $old_product_qty = ($row['salesorder_product_qty']) ?intval($row['salesorder_product_qty']) : intval($row['deliverynote_quantity']);
                                                $subtotal      += ($row['deliverynote_quantity'] * $unicost) + $row['deliverytotaldiscount'];
                                                echo '<td>'.$prdqtyfld.'<input type="hidden" name="old_product_qty[]" value="' .$old_product_qty . '" id="oldproductqty-' . $i . '"></td>';
                                            
                                            echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowDiscountTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . amountExchange_s($row['product_price'], $invoice['multi'], $this->aauth->get_user()->loc) . '"></td>';

                                            //old one
                                            //    echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            //    onkeypress="return isNumber(event)" onkeyup="rowDiscountTotal(' . $i . '), billUpyog()"
                                            //    autocomplete="off" value="' . amountExchange_s($row['product_price'], $invoice['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                            echo '<td class="text-right"><strong>'.$unicost.'</strong></td>';
                                            echo '<td class="text-right"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$currentdiscount.'</strong></td>';
                                            
                                            if($configurations['config_tax']!='0'){
                                                echo '<td style="text-align:center;"><strong>'.$row['tax'].'</strong> </td>';
                                                echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">0</td>';
                                            }
                                                // <!-- erp2024 modified section 07-06-2024 -->
                                                    

                                                    echo '<td class="text-right">
                                                        <strong><span class="ttlText" id="result-' . $i . '">'.$current_subtotal.'</span></strong></td>
                                                    </td>
                                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . $currentdiscount . '">
                                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="'.$current_subtotal.'">
                                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['product_id'] . '">
                                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['product_code'] . '">
                                                    <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowDiscountTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['product_discount']) . '">
                                                    
                                                    <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' . amountFormat_general($row['product_discount']) . '">

                                                    <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="' . $row['delnote_discounttype'] . '">

                                                    <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowDiscountTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['tax']) . '">
                                                </tr>';
                                                $i++; $j++;
                                            } ?>
                                        
                                            <?php if($configurations["config_tax"]!="0"){    ?>
                                            <tr class="sub_c tr-border" style="display: table-row; " >
                                                <td colspan="8" align="right" class="no-border"><strong>Total Tax<?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                                <td align="left" colspan="2"  class="no-border">
                                                    <span id="taxr"  class="lightMode"><?php echo amountExchange_s(0, 0, $this->aauth->get_user()->loc) ?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <!-- erp2024 removed section 07-06-2024 -->
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Subtotal'); ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="grandamount"
                                                        class="lightMode"><?php echo number_format($subtotal, 2); ?></span>
                                                </td>
                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Total Product Discount'); ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs"
                                                        class="lightMode"><?php echo number_format($totaldiscount, 2); ?></span>
                                                </td>
                                            </tr>
                                            
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Order Discount'); ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <input type="hidden" name="order_discount" value="<?=$invoice['order_discount']?>">
                                                    <span  class="lightMode"><?php echo number_format($invoice['delivery_order_discount'], 2); ?></span>
                                                    <input type="hidden" class="form-control text-right" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$invoice['delivery_order_discount']?>">
                                                    <input type="hidden" class="form-control text-right"  name="old_order_discount" id="old_order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$invoice['delivery_order_discount']?>">
                                                </td>
                                            </tr>
                                            <tr class="sub_c no-border" style="display: table-row;">
                                                <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Total Discount'); ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                <?php 
                                                    $granddiscount = $totaldiscount + $invoice['order_discount'];
                                                    if($this->session->userdata('repeatsubmit') > 1) { ?>
                                                        <span id="granddiscount" class="lightMode"><?php echo number_format($granddiscount, 2); ?></span>
                                                    <?php }
                                                    else{ ?>
                                                        <span id="granddiscount" class="lightMode"><?php echo number_format($granddiscount,2);?></span>
                                                        <!-- erp2024 old section 26-09-2024 -->
                                                        <!-- <span id="discs" class="lightMode"><?php //echo amountExchange_s(0, 0, $this->aauth->get_user()->loc) ?></span> -->
                                                        <!-- erp2024 old section 26-09-2024 -->
                                                <?php } ?>
                                                    
                                                </td>
                                            </tr>

                                            <tr class="sub_c d-none" style="display: table-row;">
                                                <td colspan="9" align="right"  class="no-border"><input type="hidden"
                                                                                    value="0"
                                                                                    id="subttlform"
                                                                                    name="subtotal"><strong>Shipping</strong></td>
                                                <td align="left" colspan="2"  class="no-border"><input type="text" class="form-control shipVal" readonly onkeypress="return isNumber(event)" placeholder="Value" name="shipping" autocomplete="off" onkeyup="billUpyog()"  
                                                value="<?php if ($invoice['ship_tax_type'] == 'excl') {
                                                            $invoice['shipping'] = $invoice['shipping'] - $invoice['ship_tax'];
                                                        }
                                                        echo amountExchange_s(0, 0, $this->aauth->get_user()->loc); ?>">( <?= $this->lang->line('Tax') ?>
                                                    <span id="ship_final"><?= amountExchange_s(0, 0, $this->aauth->get_user()->loc) ?> </span>
                                                    )
                                                </td>
                                            </tr>

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="3"  class="no-border"><?php if ($exchange['active'] == 1){
                                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                                    <select name="mcurrency"
                                                            class="selectpicker form-control">

                                                        <?php
                                                        echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                                                        foreach ($currency as $row) {

                                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                                        } ?>

                                                    </select><?php } ?></td>
                                                <td colspan="9" align="right"  class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                        <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                </td>
                                                <td align="right" colspan="2"  class="no-border">
                                                    <?php 
                                                    $grantamount = $subtotal-($totaldiscount+$invoice['order_discount']);
                                                    if($this->session->userdata('repeatsubmit') > 1) { ?>
                                                    
                                                        <span id="grandtotaltext"><?=number_format($invoice['total_amount'],2)?></span>

                                                    <?php }
                                                    else{ ?>
                                                        <!-- erp2024 old section -->
                                                        <!-- <span id="grandtotaltext"><?= amountExchange_s(0, 0, $this->aauth->get_user()->loc); ?></span>
                                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?= amountExchange_s(0, 0, $this->aauth->get_user()->loc); ?>"  readonly> -->
                                                        <!-- erp2024 old section -->
                                                        <span id="grandtotaltext"><?=number_format($invoice['total_amount'],2)?></span>
                                                <?php } ?>
                                                        
                                                <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?=$invoice['total_amount']?>"  readonly>
                                                </td>
                                            </tr>
                                            <?php  
                                                // $this->db->select('cberp_delivery_notes.delevery_note_id as current_delevery_note_id, cberp_delivery_notes.delnote_number as current_delnote_number,cberp_delivery_notes.total_amount as current_total_amount,cberp_delivery_notes.discount as '); 
                                        
                                            ?>

                                            </tbody>
                                        </table>
                                    <?php 
                                    }
                                    else
                                    { ?>
                                        <div class="col-12 form-row mt-1 discount-toggle">
                                            <div class="form-check" >
                                                <input class="form-check-input discountshowhide" type="checkbox" value="2"  name="discountshowhide" id="discountshowhide">
                                                <label class="form-check-label dicount-checkbox" for="discountshowhide">
                                                <b><?php echo $this->lang->line('Would you like to add a discount for these products?'); ?></b>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="discount_flg" class="discount_flg" value="0">
                                        <table class="table table-striped table-bordered zero-configuration dataTable">
                                            <thead>


                                            <tr class="item_header bg-gradient-directional-blue white">
                                                <tr class="item_header bg-gradient-directional-blue white">
                                                <th width="2%" class="text-center"><?php echo $this->lang->line('SN') ?></th> 
                                                <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                                <th width="22%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                                <th width="7%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                                <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                                                <th width="7%" class="text-right"><?php echo $this->lang->line('Selling Price') ?></th>
                                                <th width="7%" class="text-right"><?php echo $this->lang->line('Lowest Price') ?></th>
                                                <?php  //Verify that tax is enabled
                                                $colspan = 8;
                                                if($configurations['config_tax']!='0'){ 
                                                    $colspan = 10;    
                                                ?>
                                                    <th width="10%" class="text-right"><?php echo $this->lang->line('Tax'); ?>(%) / <?php echo $this->lang->line('Amount'); ?></th>
                                                <?php } ?>
                                                <th width="5%" class="text-center"><?php echo $this->lang->line('Max discount %')?></th>
                                                <th width="12%" class="text-center discountcoloumn d-none"><?php echo $this->lang->line('Discount')?>/ <?php echo $this->lang->line('Amount'); ?></th>
                                                <th width="10%" class="text-right">
                                                    <?php echo $this->lang->line('Amount') ?>
                                                    <?php //echo "(".$this->config->item('currency').")"; ?>
                                                </th>
                                                <th width="8%" class="text-center1"><?php echo $this->lang->line('Action') ?></th>
                                            </tr>
                                            </tr>
                                            </thead>
                                            <tbody>
                            
                                            <?php
                                               
                                                if(!empty($products))
                                                {
                                                    $totaldiscount = 0;
                                                    $totaltax      = 0;
                                                    $subtotal      = 0;
                                                    $k=1;
                                                    
                                                    foreach($products as $row)
                                                    {
                                                        
                                                        $productcode = $row['product_code'];
                                                        $totaldiscount += $row['deliverytotaldiscount'];
                                                        $totaltax      += $row['totaldiscount'];
                                                        $subtotal      += ($row['deliverynote_quantity'] * $row['product_price']);
                                                        if($row['product_discount']>0 && $discount_flg==0)
                                                        {
                                                            $discount_flg =1;
                                                        }
                                                        $product_name_with_code = $row['product_name'].'('.$row['product_code'].') - ';
                                                        ?>
                                                        <tr>  
                                                            <td class="text-center serial-number"><?=$k++?></td>
                                                            <td><input type="text" class="form-control code" name="code[]" id="code-<?=$i?>" value="<?=$row['product_code']?>" title="<?=$product_name_with_code?>Code" data-product-code="<?=$productcode?>">
                                                            <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-<?=$i?>" value="<?=$row['income_account_number']?>">
                                                            <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-<?=$i?>" value="<?=$row['product_cost']?>"></td>
                                                            <td><input type="text" class="form-control product_name" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' value="<?=$row['product_name']?>" title="<?=$product_name_with_code?>Product" data-product-code="<?=$productcode?>"></td>
                                                            <td class="text-center position-relative"><input type="text" class="form-control req amnt product_qty" name="product_qty[]" id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog(), orderdiscount()" autocomplete="off" value="<?=$row['deliverynote_quantity']?>" title="<?=$product_name_with_code?>Quantity" data-original-value="<?=$row['deliverynote_quantity']?>" data-product-code="<?=$productcode?>"><div class="tooltip1"></div></td>
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
                                                            $productprice = amountExchange_s($row['product_price'], $invoice['multi'], $this->aauth->get_user()->loc);
                                                            $maxdiscountamount = round(($productprice * $row['maximumdiscount']) / 100, 2);
                                                            $row['maximumdiscount'] = (intval($row['maximumdiscount']) == floatval($row['maximumdiscount']))  ? intval($row['maximumdiscount']) : number_format($row['maximumdiscount'], 2);
                                                            $discountamount = $row['maximumdiscount']."% (".$maxdiscountamount.")";
                                                            // echo '<td class="text-center"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-'.$i.'" value="' . $maxdiscountamount . '"><strong id="maxdiscountratelabel-' . $i . '">' .$discountamount. '</strong></td>';  
                                                            ?>
                                                            <td class="text-center"><strong id='maxdiscountratelabel-<?=$i?>'><?=$discountamount?></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-<?=$i?>" value="<?=$row['maximumdiscount']?>"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-<?=$i?>" value="<?=$maxdiscountamount?>"></td>

                                                            <td class="text-center discountcoloumn d-none">
                                                                <div class="input-group text-center">
                                                                    <select name="discount_type[]" id="discounttype-<?=$i?>" data-product-code="<?=$productcode?>" class="form-control" onchange="discounttypeChange(0),orderdiscount()" title="<?=$product_name_with_code?>Type">
                                                                        <option value="Perctype" <?php if($row['delnote_discounttype'] =='Perctype'){ echo 'selected'; }?>>%</option>
                                                                        <option value="Amttype"  <?php if($row['delnote_discounttype'] =='Amttype'){ echo 'selected'; }?>>Amt</option>
                                                                    </select>&nbsp;
                                                                    <input type="number" min="0" class="form-control discount" data-product-code="<?=$productcode?>"  name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()" value="<?=$row['product_discount']?>" title="<?=$product_name_with_code?>Discount">
                                                                    <input type="number"  min="0" class="form-control discount d-none" data-product-code="<?=$productcode?>" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()" value="<?=$row['product_discount']?>" title="<?=$product_name_with_code?>Discount">
                                                                </div>  
                                                                <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel">Amount : <?=$row['deliverytotaldiscount']?></strong>
                                                                <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                                            </td>

                                                            <td class="text-right">
                                                                <strong><span class='ttlText' id="result-<?=$i?>"><?=$row['deliverysubtotal']?></span></strong></td>
                                                            <td class="text-center1 d-flex1">
                                                                <button onclick='producthistory("<?=$i?>")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>
                                                                <button onclick='single_product_details("<?=$i?>")' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                                <button type="button" data-rowid="<?=$i?>" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                                            </td>
                                                            <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="<?=$row['deliverytaxtotal']?>">
                                                            <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?=$row['deliverytotaldiscount']?>">
                                                            <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?=$row['deliverysubtotal']?>">
                                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$row['product_id']?>">
                                                            <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?=$row['unit']?>">
                                                            <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$row['product_code']?>">
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                
                                                }
                                            else{
                                                ?>
                                                    <tr>
                                                        <td class="text-center serial-number">1</td>
                                                        <td>
                                                            <input type="text" class="form-control code" name="code[]" id='code-0'>
                                                            <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-0">
                                                            <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-0">
                                                        </td>
                                                        <td><input type="text" class="form-control product_name" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-0'></td>
                                                        <td class="text-center position-relative"><input type="text" class="form-control req amnt product_qty" name="product_qty[]"  id="amount-0" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(),orderdiscount()" autocomplete="off" value=""><div class="tooltip1"></div></td>
                                                        <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                                        <td class="text-right">    
                                                            <strong id="pricelabel-0"></strong>
                                                            <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0"  onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td>
                                                        <td class="text-right">
                                                            <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                                            <strong id="lowestpricelabel-0"></strong>
                                                        </td>
                                                        <?php //Verify that tax is enabled
                                                        if($configurations['config_tax']!='0'){ ?>           
                                                                <td class="text-center">
                                                                    <div class="text-center">                                                
                                                                        <input type="hidden" class="form-control" name="product_tax[]" id="vat-0"
                                                                            onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                                            autocomplete="off">
                                                                            <strong id="taxlabel-0"></strong>&nbsp;<strong  id="texttaxa-0"></strong>
                                                                    </div>
                                                                </td>
                                                        <?php } ?>
                                                        <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"></td>

                                                        <td class="text-center discountcoloumn d-none">
                                                            <div class="input-group text-center">
                                                                <select name="discount_type[]" id="discounttype-0" class="form-control" onchange="discounttypeChange(0),orderdiscount()">
                                                                    <option value="Perctype">%</option>
                                                                    <option value="Amttype">Amt</option>
                                                                </select>&nbsp;
                                                                <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()">
                                                                <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()">
                                                            </div>  
                                                            <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                                            <div><strong id="discount-error-0"></strong></div>                                    
                                                        </td>

                                                        <td class="text-right">
                                                            <strong><span class='ttlText' id="result-0">0</span></strong></td>
                                                        <td class="text-center1 d-flex">
                                                            <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>
                                                            <button onclick='single_product_details("0")' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                            
                                                            <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                                        </td>
                                                        <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                                        <input type="hidden" name="disca[]" id="disca-0" value="0">
                                                        <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                                        <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                                        <input type="hidden" name="unit[]" id="unit-0" value="">
                                                        <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                                    </tr>
                                                 <?php
                                            }
                                            ?>

                                            
                                            <tr class="last-item-row sub_c tr-border ">
                                                <td class="add-row no-border" colspan="9">
                                                    <button type="button" class="btn btn-crud btn-secondary  <?=$dnone_class_reverse?> <?=$cancel_status_btn?> <?=$input_disable?>"  title="Add product row" id="sales_create_btn">
                                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                                    </button>
                                                    <div class="btn-group ml-1 mt-1 creditlimit-check"></div>
                                                </td>
                                                <td colspan="7" class="no-border"></td>
                                            </tr>
                                            <tr>
                                                
                                            </tr>
                                            <?php 
                                            if($configurations['config_tax']!='0'){ ?>
                                                <tr class="sub_c" style="display: table-row;">
                                                    <td colspan="9" align="right" class="no-border td-colspan">
                                                        <input type="hidden" value="0" id="subttlform" name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="no-border">
                                                        <span id="taxr" class="lightMode">0</span></td>
                                                </tr>
                                            <?php } ?>

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                       <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                </td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="grandamount"><?=number_format($subtotal,2)?></span>
                                                </td>
                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border td-colspan">
                                                    <strong><?php echo $this->lang->line('Total Product Discount') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs" class="lightMode"><?=number_format($totaldiscount,2);?></span></td>
                                            </tr>

                                            <tr class="sub_c d-none" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border td-colspan">
                                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                                <td align="right" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                                    onkeypress="return isNumber(event)"
                                                                                    placeholder="Value"
                                                                                    name="shipping" autocomplete="off"
                                                                                    onkeyup="billUpyog()">
                                                    ( <?php echo $this->lang->line('Tax') ?>
                                                    <span id="ship_final">0</span> )
                                                </td>
                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border td-colspan">
                                                    <strong><?php echo $this->lang->line('Order Discount') ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                <input type="number" class="form-control" style="text-align:end; width:50%" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$invoice['order_discount']?>">
                                                </td>
                                            </tr>

                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Net Total') ?>
                                                       <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                </td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <?php
                                                    $nettotal = $subtotal - $totaldiscount;
                                                    ?>
                                                    <span id="grandtotaltext"><?=number_format($nettotal,2)?></span>
                                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly value="<?=$nettotal?>">

                                                </td>
                                            </tr>
                                            
                                            </tbody>
                                        </table>

                                    <?php
                                    }
                                   ?>
                                   
                                </div>
                                <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
                                <input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="<?=$invoice['avalable_credit_limit']?>">
                                <input type="hidden" value="" id="action-url">
                                <input type="hidden" value="search" id="billtype">
                                <input type="hidden" value="<?php echo $i; ?>" name="counter" id="ganak">
                                <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">

                                <input type="hidden" value="<?= $this->common->taxhandle_edit($invoice['taxstatus']) ?>"
                                    name="taxformat" id="tax_format">
                                <input type="hidden" value="<?= $invoice['format_discount']; ?>" name="discountFormat"
                                    id="discount_format">
                                <input type="hidden" value="<?= $invoice['taxstatus']; ?>" name="tax_handle" id="tax_status">
                                <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">

                                <input type="hidden" value="<?php
                                if($invoice['shipping']==0)  $invoice['shipping']=1;
                                $tt = 0;
                                if ($invoice['ship_tax_type'] == 'incl') $tt = @number_format(($invoice['shipping'] - $invoice['ship_tax']) / $invoice['shipping'], 2, '.', '');
                                echo amountFormat_general(@number_format((($invoice['ship_tax'] / $invoice['shipping']) * 100) + $tt, 3, '.', '')); ?>"
                                    name="shipRate" id="ship_rate">
                                <input type="hidden" value="<?= $invoice['ship_tax_type']; ?>" name="ship_taxtype"
                                    id="ship_taxtype">
                                <input type="hidden" value="<?= amountFormat_general($invoice['ship_tax']); ?>" name="ship_tax"
                                    id="ship_tax">
                            </div>

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
                             <div class="mt-3">
                                <?php
                                
                                $createdflg ="";
                                $create_btn_flg ="";                                
                                $pickrecievedflg = "";
                                $create_btn_class = "";
                                // print_r($invoice);
                                if($invoice['notestatus'] && $invoice['pick_item_recieved_status']!='1')
                                {
                                    // $create_btn_class = "disable-class";
                                }
                                else
                                {
                                    $createdflg = "disable-class";
                                    $create_btn_flg ="";
                                    $create_btn_class = "";
                                }
                                if($invoice['notestatus']=='Created' || $invoice['notestatus']=='Draft')
                                {
                                    $pickprintflg = "";
                                    if($invoice['pick_ticket_status']!='1' && $invoice['notestatus']!='Draft')
                                    {
                                        $pickprintflg = "disable-class";
                                    }
                                    if($invoice['pick_item_recieved_status']!='1')
                                    {
                                        $pickrecievedflg = "disable-class";
                                    }
                                    $printpick_bgcolor ="";
                                    $itemrecieve_bgcolor ="";
                                    if($pick_item_recieved_status1==1){
                                        $printpick_bgcolor ="alert-success";
                                        $itemrecieve_bgcolor ="alert-success";
                                    }

                                    if($pick_ticket_status1==1){                                                
                                        $printpick_bgcolor ="alert-success";
                                    }
                                    
                                    ?>
                                <?php }
                                $completed_status_class = "";
                                if($invoice['completed_status']==1 || $invoice['notestatus']=="Invoiced"){    
                                    $completed_status_class = "disable-class";
                                }
                                
                                ?>
                                <div class="row">
                                    <div class="col-lg-5 col-md-6 col-12">
                                        
                                        <?php 
                                        $draft_btnid = ($id && $invoice['salesorder_number']) ? "submit-deliverynotedraft" : "deliverynote-draft-btn";
                                        if(empty($invoice['salesorder_number']))
                                        {
                                        ?>
                                        <input type="submit" class="btn responsive-mb-1 btn-lg btn-secondary sub-btn <?=$cancel_status_btn?> <?=$completed_status_class?>" value="Save As Draft" title="Save As Draft" id="<?=$draft_btnid?>"  data-loading-text="Updating...">
                                        <?php } ?>
                                        <!-- <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn <?=$cancel_status_btn?> <?=$completed_status_class?>" value="Save As Draft" id="submit-deliverynotedraft"  data-loading-text="Updating...">&nbsp; -->
                                         
                                        <button type="button" class="btn responsive-mb-1 btn-lg btn-secondary sub-btn <?=$cancel_status_btn?> <?=$createdflg?> <?=$dnone_class?> <?=$draft_hiddenclass?>" id="change-sales-point-btn" title="<?php echo $this->lang->line('Change Sales Point') ?>"><?php echo $this->lang->line('Change Sales Point') ?></button>
                                        
                                        <button type="button" class="btn responsive-mb-1 btn-lg btn-secondary revert-btncolor sub-btn <?=$cancel_status_btn?> <?=$dnone_class?> <?=$disable_class_invoiced?> <?=$draft_hiddenclass?>" id="cancel-btn" title="<?php echo $this->lang->line('Cancel') ?>"><?php echo $this->lang->line('Cancel') ?></button>
                                    </div>
                                    <div class="col-lg-7 col-md-6 col-12 responsive-textright">

                                        <?php 
                                         if($pick_ticket_status1=="0")
                                         { ?>
                                            <button type="button" class="btn responsive-mb-1 btn-lg btn-secondary sub-btn <?=$printpick_bgcolor?> <?=$cancel_status_btn?> <?=$completed_status_class?> <?=$dnone_class?> <?=$draft_hiddenclass?>" id="print-picking-btn" title="<?php echo $this->lang->line('Print Picking List') ?>"><?php echo $this->lang->line('Print Picking List') ?></button>&nbsp;
                                            <i class="responsive-mb-1 responsive-filter-icon fa fa-forward <?=$cancel_status_btn?> <?=$completed_status_class?> <?=$dnone_class?> <?=$draft_hiddenclass?>" aria-hidden="true"></i>
                                        <?php } 
                                        if($pick_item_recieved_status1=="0")
                                        { ?>

                                        <button  type="button" class="btn responsive-mb-1 btn-lg btn-secondary sub-btn <?=$pickprintflg?> <?=$itemrecieve_bgcolor?> <?=$cancel_status_btn?> <?=$completed_status_class?> <?=$dnone_class?> <?=$draft_hiddenclass?>" id="item_pick_recieved-btn" title="<?php echo $this->lang->line('Items Picked') ?>"><?php echo $this->lang->line('Items Picked') ?></button>&nbsp;
                                        <i class="responsive-mb-1 responsive-filter-icon fa fa-forward <?=$cancel_status_btn?> <?=$completed_status_class?> <?=$dnone_class?> <?=$draft_hiddenclass?>" aria-hidden="true"></i>
                                        
                                        <?php 
                                        }
                                        $btnid = "save_and_continue";
                                        // $btnid = (($id && $invoice['salesorder_number'])) ? "submit-deliverynote" : "save_and_continue";
                                        ?>
                                        <input type="submit" class="btn responsive-mb-1 btn-lg btn-primary sub-btn <?=$pickprintflg?> <?=$cancel_status_btn?> <?=$completed_status_class?> <?=$create_btn_flg?> <?=$create_btn_class?> <?=$notrecivedflg?>" value="Create" title="Create" id="<?=$btnid?>"  data-loading-text="Updating...">
                                        
                                    </div>
                                </div>
                                        
                                    
                             </div>
                        </div>


                </form>
            </div>

        </div>
    </div>
</div>

<!-- ============================================== -->
<div id="material_request_model" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Material Request') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- ======================================================= -->
                <form  method="post" id="material_request_form">
                        <div class="container-fluid">
                            <div class="col-md-4">
                                <label class="col-form-label">To Warehouse*</label>
                                <!-- <select name="warehouse_to" id="warehouse_to" class="form-control required" required>
                                    <option value="">Select Warehouse</option>
                                    <?php
                                        // foreach($warehouse as $warehouse){
                                        //     echo '<option value="'.$warehouse['id'].'">'.$warehouse['title'].'</option>';
                                        // }
                                    ?>
                                </select> -->
                                <input type="hidden" name="warehouse_to" id="warehouse_to" value="<?=$invoice['store_id']?>" >
                                <input type="text" name="warehouse_title" id="warehouse_title" value="<?=$warehouse_title?>" class="form-control" readonly>
                            </div>
                            <hr>

                            <!-- ====================================================================  -->
                                <div class="saman-row" class="overflow-auto">
                                    <table class="table table-striped table-bordered zero-configuration dataTable">
                                        <thead>
                                        <tr class="item_header bg-gradient-directional-blue white">
                                            <th width="30%" class="pl-14"><?php echo $this->lang->line('Product') ?></th>
                                            <th width="30%" class="pl-14"><?php echo $this->lang->line('Transfer From') ?></th>
                                            <th width="7%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                            <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>                                       

                                            <tr class="last-item-row sub_c tr-border">
                                                <td class="add-row no-border">
                                                    <button type="button" class="btn btn-crud btn-secondary" aria-label="Left Align"
                                                            data-toggle="tooltip"
                                                            data-placement="top" id="materialrequest-create">
                                                        <i class="icon-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                                    </button>
                                                </td>
                                                <td colspan="7" class="no-border"></td>
                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="2" class="no-border"></td>
                                                <td align="right" colspan="7" class="no-border">
                                                    <input type="hidden" name="ganak" id="ganak" value="<?=$i?>">
                                                    <input type="hidden" name="selectedProducts" id="selectedProducts" value="<?=$selectedProducts?>">
                                                    <input type="submit" class="btn btn-crud btn-lg btn-primary" value="<?php echo $this->lang->line('Request Now') ?>" id="material-request-btn" data-loading-text="Creating...">

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <!-- ====================================================================  -->
                        </div>
                </form>
                <!-- ======================================================= -->
            </div>
            
        </div>
    </div>
</div>
<!-- ============================================== -->

<!-- ============================================== -->
<div id="write_off_model" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Write Off') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- ======================================================= -->
                <form  method="post" id="write_off_form">
                        <div class="container-fluid" id="table-potion"></div>                        
                </form>
                <!-- ======================================================= -->
            </div>
            
        </div>
    </div>
</div>
<!-- ============================================== -->
<!--                
                formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
                formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts))); -->
<script>
const changedFields = {};
let productCode;   
let changedProducts = new Set();
let wholeProducts = new Set();
$(document).ready(function() {
    var discountflag = <?=$discount_flg?>;
    if(discountflag==1){                 
        showdiscount_potion();
    }    
    if($("#elementflg").val()=="1"){
        disable_items();
    }
    
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
   
    });

    // credit_limit_with_grand_total();
    $('#write_off_submit_btn').prop('disabled',false);
    $('#DeliveryReport').click(function () {
        var selectedProducts = [];
        var deliveredItems = [];
        var i =0;
        $('.checkedproducts1:checked').each(function() {
            selectedProducts.push($(this).val());
            deliveredItems.push($("#amount-"+i).val());
            i++;
        });
        if (selectedProducts.length === 0) {
            alert("Please select at least one product.");
            return;
        }
        var invocienoId= $('#invocienoId').val();
        var customer_id= $('#customer_id').val();
        var invocieduedate= $('#deliveryduedate').val();
        var invoicedate= $('#invoicedate').val();
        var refer= $('#refer').val();
        var taxformat= $('#taxformat').val();
        var discountFormat= $('#discountFormat').val();
        var  salenote= $('#salenote').val();
        // Create the form dynamically
        var form = $('<form action="<?php echo site_url('pos_invoices/deliverNoteexportpdf')?>" method="POST"></form>');
        form.append('<input type="hidden" name="deliveredItems" value="' + deliveredItems + '">');
        form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts + '">');
        form.append('<input type="hidden" name="invocienoId" value="' + invocienoId + '">');
        form.append('<input type="hidden" name="customer_id" value="' + customer_id + '">');
        form.append('<input type="hidden" name="invoicedate" value="' + invoicedate + '">');
        form.append('<input type="hidden" name="invocieduedate" value="' + invocieduedate + '">');

        form.append('<input type="hidden" name="refer" value="' + refer + '">');
        form.append('<input type="hidden" name="taxformat" value="' + taxformat + '">');
        form.append('<input type="hidden" name="discountFormat" value="' + discountFormat + '">');
        form.append('<input type="hidden" name="salenote" value="' + salenote + '">');
        $('body').append(form);
        form.submit();   
    });

    $.validator.addMethod("valueNotEmpty", function(value, element) {
        return value !== ""; // Ensure the value is not empty
    }, "Please select a product.");

    $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel,{
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            s_warehouses: { required: true },
            deliveryduedate: { required: true },
            cst: {
                required: function() {
                    return $('#customer_id').val() == 0;
                }
            },
            refer: { required: true },
            customer_purchase_order: { required: true },
            customer_contact_number: {
                phoneRegex :true
            },
        },
        messages: {
            s_warehouses: "Select Sale Point",
            refer: "Enter Internal Reference",
            cst: "Select Customer",
            customer_contact_number: "Enter a Valid Number",
            deliveryduedate: "Enter a Vlidity Date",
        }
    }));


    $("#material_request_form").validate({
        rules: {
            
           
            "product-name[]": {
                valueNotEmpty: function() {
                    return $("#material_request_form select[name='product-name[]']:first");
                }
            },
            "warehousefrom[]": {
                valueNotEmpty: function() {
                    return $("#material_request_form select[name='warehousefrom[][]']:first");
                }
            },
            "transferqty[]": {
                valueNotEmpty: function() {
                    return $("#material_request_form input[name='transferqty[]']").first();
                }
            }
        },
        messages: {
           
            "product-name[]": {
                valueNotEmpty: "Please select a valid product." // Custom error message
            },
            "warehousefrom[]": {
                valueNotEmpty: "Please select a valid warhouse." // Custom error message
            },
            "transferqty[]": {
                valueNotEmpty: "Valid Quantity." // Custom error message
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
      },
      invalidHandler: function(event, validator) {
         // Focus on the first invalid element
         if (validator.errorList.length) {
            $(validator.errorList[0].element).focus();
         }
      }
   });
   $('#material-request-btn').prop('disabled',false);

});



$('#material-request-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#material-request-btn').prop('disabled',true);
    // Validate the form    
    hasUnsavedChanges = false;
    if ($("#material_request_form").valid()) {
    
        var form = $('#material_request_form')[0];
        var formData = new FormData(form);
        $.ajax({
            url: baseurl + "SalesOrders/materialrequestaction",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
               
                var data = JSON.parse(response);
                Swal.fire({
                    title: data.status === 'Success' ? 'Success' : 'Error',
                    html: data.message,
                    icon: data.status === 'Success' ? 'success' : 'error',
                    confirmButtonText: 'OK'
                });
                $('#material-request-btn').prop('disabled',false);
                $('#material_request_model').modal('hide'); 

            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'An error occurred while generating the material request', 'error');
                console.log(error); // Log any errors
            }
        });
    
    }
    else{
        $('#material-request-btn').prop('disabled',false);
    }
});

$("#refreshBtn").on("click", function(){
    location.reload();
});
$('.editdate').datepicker({
        autoHide: true,
        format: '<?php echo $this->config->item('dformat2'); ?>'
    });
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
 //erp2024 new code for matrial request screen 07-06-2024 starts 
 $('#MaterialReport').click(function() {
        var selectedProducts = [];
        $('.checkedproducts:checked').each(function() {
            selectedProducts.push($(this).val());
        });
        if (selectedProducts.length === 0) {
            Swal.fire({
             text: "Please select at least one product",
             icon: "info"
           });
            return;
        }
       
        if (selectedProducts.length > 0) {
            $('#material_request_form')[0].reset();
            $("#selectedProducts").val(selectedProducts);
            selproducts = $("#selectedProducts").val();
            $.ajax({
                url: baseurl + 'SalesOrders/selected_items_for_material_request',
                method: 'POST',
                dataType: 'html',
                data: {
                    'selectedProducts': selproducts
                },
                success: function(response) {
                    $('.saman-row tbody').prepend(response);
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
            $('#material_request_model').modal('show');        
            $('.appendeditems').empty();       
            // $('#material_request_form')[0].reset();
        }
    });
    //erp2024 new code for purchase request screen 18-06-2024 starts
        $('#PurchaseRequest').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               Swal.fire({
                text: "Please select at least one product",
                icon: "info"
              });
               return;
           }
   
           if (selectedProducts.length > 0) {
               var form = $('<form action="<?php echo site_url('Productrequest/purchaserequest')?>" method="POST" ></form>');
               form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts +'">');
               $('body').append(form);
               form.submit();
           }
       });

       $("#warehouse_to").on('change', function(){
            $(".warehousefrom").empty();
            $(".warehousefrom").append('<option value="">Select Warehouse</option>');
         
        });
        function warehouseList(id){
            var productVal = $("#product-name-" + id).val();
            var mainwarehouse = $("#warehouse_to").val();
            $.ajax({
                url: baseurl + 'Products/warehouse_by_productid',
                dataType: 'json',
                method: 'POST',
                data: {
                    'prdid': productVal,
                    'mainwarehouse' : mainwarehouse
                },
                success: function(data) {
                    var selectElement = $("#warehousefrom-" + id); // Ensure this is the correct selector for your select element
                    selectElement.empty(); // Clear existing options
                    selectElement.append($('<option>', {
                        value: '',
                        text: 'Select Warehouse'
                    }));
                    if (data.length > 0) {
                        $.each(data, function(index, item) {
                            selectElement.append($('<option>', {
                                value: item.id,
                                text: item.title+" - Stock("+item.stock_qty+")"
                            }));
                        });
                    } else {
                        selectElement.append($('<option>', {
                            value: '',
                            text: 'No warehouses found'
                        }));
                    }
                }
            });
        }
       
    function selectedProductList(){
        var selectedProducts = $("#selectedProducts").val();
        id = $("#ganak").val();
        $.ajax({
            url: baseurl + 'Products/products_by_id',
            dataType: 'json',
            method: 'POST',
            data: {
                'selectedProducts': selectedProducts
            },
            success: function(data) {
                
                var selectElement = $("#product-name-" + id);
                selectElement.empty(); 
                selectElement.append($('<option>', {
                    value: '',
                    text: 'Select Product'
                }));
                if (data.length > 0) {
                    $.each(data, function(index, item) {
                        selectElement.append($('<option>', {
                            value: item.id,
                            text: item.title+" - "+item.code
                        }));
                    });
                } else {
                    selectElement.append($('<option>', {
                        value: '',
                        text: 'No Products found'
                    }));
                }
            }
        });
    }
    $('#materialrequest-create').on('click', function () {
        var cvalue = parseInt($('#ganak').val()) + 1;
        var nxt = parseInt(cvalue);
        $('#ganak').val(nxt);
        var functionNum = "'" + cvalue + "'";
        count = $('.saman-row div').length;
        var data = '<tr class="appendeditems""><td><select name="product-name[]" id="product-name-'+cvalue+'" class="form-control breaklink" onchange="warehouseList('+ cvalue +')"></select></td><td><select name="warehousefrom[]" id="warehousefrom-'+cvalue+'" class="form-control breaklink"><option value="">Select Warehouse</option></select></td><td><input type="text" class="form-control req prc breaklink" name="transferqty[]" id="transferqty-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td></tr>';
        
        $('tr.last-item-row').before(data);
        row = cvalue;
        selectedProductList();
    });

    function checkqty(id){
      var enteredqty = parseFloat($("#amount-" + id).val()) || 0;
      var old_product_qty = parseFloat($("#remqty-" + id).val()) || 0;
      if(enteredqty > old_product_qty){
         $("#amount-" + id).val(0);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is greater than the remaining quantity'
         });
      }
    }
    //erp2024 new code for purchase request screen 18-06-2024 ends
    
    $("#cancel-btn").on('click', function(){

        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to cancel this delivery note now?",
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
                var form = $('#data_form')[0];
                var formData = new FormData(form); 
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'quote/deliverynote_reassigned',
                    // data: {
                    //     salesorder_id: $("#salesorder_id").val(),
                    //     delevery_note_id: $("#delevery_note_id").val()
                    // },
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        // if($("#salesorder_id").val() > 0)
                        // {
                        //     window.location.href = baseurl + 'SalesOrders';
                        // }
                        // else{
                        //     window.location.href = baseurl + 'DeliveryNotes';
                        // }
                        window.location.href = baseurl + 'DeliveryNotes';
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    $("#print-picking-btn").on("click", function(e) {
        e.preventDefault();
        // Get the data attributes
        selectedProducts =[];
        var delivery = $("#delevery_note_id").val();
        var sales =  $("#salesorder_number").val();
        var cust =  $("#customer_id").val();
        
        var total = parseFloat($("#invoiceyoghtml").val());
        var avalable_credit_limit = parseFloat($("#available_credit").val());

        var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
        var available_credit_limit = parseFloat($("#available_credit").val().replace(/,/g, '').trim());
        if (isNaN(total) || isNaN(available_credit_limit)) {
            Swal.fire({
                title: "Credit Limit Exceeded",
                text: "The Grand Total amount exceeds the Available Credit Limit. Please review.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        } else if (total > available_credit_limit) {
            Swal.fire({
                title: "Credit Limit Exceeded",
                text: "The Grand Total amount exceeds the Available Credit Limit. Please review your order.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        } 
        else {
            
        }
        $('.product_qty').each(function(index) {
            var currentQty = parseFloat($(this).val());
            if (!isNaN(currentQty) && currentQty > 0) {
                selectedProducts.push(currentQty);
            }
        });
        if (selectedProducts.length === 0) {
                Swal.fire({
                    text: "To proceed, please add a delivery quantity for at least one item",
                    icon: "info"
                });
            return;
        }
        var priceFlg = 1;
       //var formData = $("#data_form").serialize(); 
      // formData += '&completed_status=0&print_status=1';
      var form = $('#data_form')[0];
      var formData = new FormData(form); 
      formData.append('completed_status', '0');
      formData.append('print_status', '1');      
      formData.append('changedFields', JSON.stringify(changedFields));
        $.ajax({
            type: 'POST',
            // url: baseurl + 'DeliveryNotes/pickticket_print_status',
            // data: {
            //     delevery_note_id: $("#delevery_note_id").val()
            // },
            
            url: baseurl +'deliverynotes/delivery_print_action',
            data: formData,
            contentType: false, 
            processData: false,
            dataType: 'json',
            success: function(response) {
                window.open(baseurl + 'DeliveryNotes/create?id='+delivery, '_blank');
                // location.reload();
                window.location.href = baseurl + 'DeliveryNotes/print_picking_list?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg;
                // window.open(baseurl + 'DeliveryNotes/print_picking_list?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg, '_blank');
                // window.location.href = baseurl + 'DeliveryNotes';
                $("#item_pick_recieved-btn").removeClass("disable-class");
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
        
    });

    $("#item_pick_recieved-btn").on("click", function(e) {
        e.preventDefault();
        $('#item_pick_recieved-btn').prop('disabled', true);
        // Get the data attributes
        var delivery = $("#delivery_note_number").val();
        var sales = $("#salesorder_number").val();
        var cust = $("#customer_id").val();
        var priceFlg = 1;
        var form = $('#data_form')[0];
        var formData = new FormData(form); 
        formData.append('changedFields', JSON.stringify(changedFields));
        formData.append('completed_status', '1');
        Swal.fire({
            title: "Are you sure?",
            text: "Have you received the picked item list?",
            icon: "question",
            input: 'textarea',  // Add a textarea input field
            inputPlaceholder: 'Enter additional details here...if any',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                formData.append('extradata', result.value);
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'DeliveryNotes/pick_item_recieved_status',
                    // data: {
                    //     delevery_note_id: $("#delevery_note_id").val(),
                    //     store_id: $("#store_id").val(),
                    //     extradata: result.value 
                    // },
                    data : formData,                 
                    contentType: false, 
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#item_pick_recieved-btn').prop('disabled', false);
                        $("#submit-deliverynotedraft").removeClass("disable-class");
                        $("#submit-deliverynote").removeClass("disable-class");
                        location.reload();
                        // window.location.href = baseurl + 'DeliveryNotes';
                        
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
            else{
                $('#item_pick_recieved-btn').prop('disabled', false);
            }
            
        });
    });


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

$("#change-sales-point-btn").on('click', function(){

    Swal.fire({
    title: "Are you Sure ?",
    "text":"Do yo want to change this Sales Point?",
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
            $("#s_warehouses").removeClass("disable-class");
            $(".page-header-data-section").slideToggle();
            $("#s_warehouses").focus();
        }
    });
});

$("#s_warehouses").on('change', function(){
    $("#store_id").val($(this).find("option:selected").val());
    // $(this).find("option:selected").attr("selected", true);
});


$('#save_and_continue').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#save_and_continue').prop('disabled', true);
    var retailflg = 0;
    shoptype="";
    if ($("#shoptype").is(":checked")) 
    {
        retailflg = 1;
        shoptype= "Retail Shop";
    }
    var selectedProducts1 = [];
    $('.code').each(function() {
        if($(this).val()!="")
        {
            selectedProducts1.push($(this).val());
        }
    });
    if (selectedProducts1.length === 0) {
        Swal.fire({
        text: "To proceed, please add  at least one item",
        icon: "info"
        });
        $('#save_and_continue').prop('disabled', false);
        return;
    }
    
    // Validate the form
    if ($("#data_form").valid()) {    
        
        credit_limit_with_grand_total();
        var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
        var available_credit_limit = parseFloat($("#available_credit").val().replace(/,/g, '').trim());
        if (total > available_credit_limit) {
            $('#save_and_continue').prop('disabled', false);
            return;
        }
        var targeturl = 'DeliveryNotes/deliverynote_save_and_new_action';
        if($("#pick_item_recieved_status").val()==1)
        {
            var targeturl = 'DeliveryNotes/deliverynote_save_for_existing_action';
        }
        var form = $('#data_form')[0]; // Get the form element
        var formData = new FormData(form); // Create FormData object
        formData.append('shoptype', shoptype);
        formData.append('completed_status', '0');
        formData.append('changedFields', JSON.stringify(changedFields));
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to create a new delivery note and update inventory?",
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
                    url: baseurl + targeturl, // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        var targeturl = baseurl + 'DeliveryNotes/create';
                        var targeturl = baseurl + 'DeliveryNotes';
                        if((retailflg==1))
                        {                                    
                            window.open(targeturl); 
                            window.location.href = baseurl + 'DeliveryNotes/deliverynote_shop_print?deliverynoteid=' + response.id;

                            // window.location.href = baseurl + 'DeliveryNotes/print_picking_list?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg;
                            
                        }
                        else{
                           window.location.href =targeturl;
                            location.reload();
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Enable the button again if user cancels
                $('#save_and_continue').prop('disabled', false);
            }
        });
    } else {
        // If form validation fails, re-enable the button
        $('.page-header-data-section').css('display','block');
        $('#save_and_continue').prop('disabled', false);
    }
});

$('#deliverynote-draft-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    
    $('#deliverynote-draft-btn').prop('disabled', true);
    var retailflg = 0;
    shoptype="";
    if ($("#shoptype").is(":checked")) 
    {
        retailflg = 1;
        shoptype= "Retail Shop";
    }
    var selectedProducts1 = [];
    $('.code').each(function() {
        if($(this).val()!="")
        {
            selectedProducts1.push($(this).val());
        }
    });
    if (!$("#customer-box").valid()) {
            $("#customer-box").focus();
            $('#deliverynote-draft-btn').prop('disabled', false); 
            return;
    }            
    var form = $('#data_form')[0]; // Get the form element
    var formData = new FormData(form); // Create FormData object
    formData.append('shoptype', shoptype);    
    formData.append('changedFields', JSON.stringify(changedFields));        
    $.ajax({
        url: baseurl + 'DeliveryNotes/deliverynoteaction', // Replace with your server endpoint
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }
            var targeturl = baseurl + 'DeliveryNotes/create?id='+response.id;
            window.location.href =targeturl;
            // if((retailflg==1))
            // {
            //comment print
                //window.location.href = baseurl + 'DeliveryNotes/deliverynote_shop_print?deliverynoteid=' + response.id;
            // }
            
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
            console.log(error); // Log any errors
        }
    });
});

$("#submit-deliverynotedraft").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    var avalable_credit_limit = $("#available_credit").val().replace(/,/g, '');
    var totalval = $("#invoiceyoghtml").val();
    totalval = totalval.replace(/,/g, '');
    var grandamount = parseFloat(totalval);
    $('.product_qty').each(function(index) {
        var currentQty = parseFloat($(this).val());
        var oldQty = parseFloat($(this).closest('td').find('input[name="old_product_qty[]"]').val());

        if (!isNaN(currentQty) && currentQty > 0) {
            if (currentQty <= oldQty) {
                selectedProducts1.push(currentQty);
            } else {
                validationFailed = true;
                return false; 
            }
        }
    });

    // if (validationFailed) {
    //     Swal.fire({
    //         text: "Delivery quantity cannot exceed the old product quantity.",
    //         icon: "error"
    //     });
    //     return;
    // }

    // if (selectedProducts1.length === 0) {
    //     Swal.fire({
    //         text: "To proceed, please add a delivery quantity for at least one item",
    //         icon: "info"
    //     });
    //     return;
    // }
    
    
    if (grandamount > avalable_credit_limit) {
        Swal.fire({
            text: "Customer doesn't have enough credit balance. Please contact Credit Manager",
            icon: "error"
        });
        return;
    }

    // Use SweetAlert for confirmation
    // Swal.fire({
    //     title: "Are you sure?",
    //     // text: "Are you sure you want to update inventory? Do you want to proceed?",
    //     "text":"Do you want to save temporarily? This will allow future edits, but it won't proceed to the next level.",
    //     icon: "question",
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes, proceed!',
    //     cancelButtonText: "No - Cancel",
    //     reverseButtons: true,
    //     focusCancel: true
    // }).then((result) => {
    //     if (result.isConfirmed) {
           
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            formData.append('shoptype', shoptype);    
            formData.append('completed_status', '0');    
            formData.append('changedFields', JSON.stringify(changedFields));        
            $.ajax({
                type: 'POST',
                url: baseurl +'DeliveryNotes/deliverynoteaction',
                data: formData,
                contentType: false, 
                processData: false,
                success: function(response) {                            
                    
                    // location.reload();
                    // $("#checkflg").val(1);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        // }
    // });
});
</script>
