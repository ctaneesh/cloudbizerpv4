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

        $existing_lead_class = "";
        $existing_lead_class_hide = "d-none";        
        $lead_number = $this->lang->line('Add New'); 
        // $lead_number = $prefix.($lastenquirynumber);
        $default_checked = "checked";
        if(($enquirymain))
        {
            $existing_lead_class = "disable-class";
            $existing_lead_class_hide = "";
            $lead_number = $enquirymain['lead_number'];
            $default_checked ="";
        }

        $btns_class="";
        $acceptbtns_class="";
        // if(($enquirymain['enquiry_status']!="Accepted") && ($enquirymain['enquiry_status']!="Closed") && ($enquirymain['enquiry_status']!="Draft") && ($enquirymain['assigned_to'] == $this->session->userdata('id'))) {
        //     $btns_class="disable-class";
        // }
        if(($enquirymain['enquiry_status']=="Accepted") && ($enquirymain['enquiry_status']!="Closed") && ($enquirymain['assigned_to'] == $this->session->userdata('id'))) {
            $acceptbtns_class="disable-class";
        }

        $draftbtn_class="";
         ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('invoices/leads') ?>"><?php echo $this->lang->line('Leads') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $lead_number; ?></li>
                <!-- <li class="breadcrumb-item active" aria-current="page"><?php //echo $this->lang->line('Lead')."# ".($lead_number); ?></li> -->
            </ol>
        </nav>
        <?php 
            $status_closed_btn="";
            
            $existing_lead_class = ($enquirymain) ? "disable-class" : "";
            if(($enquirymain['enquiry_status']!="Closed") && ($enquirymain['assigned_to'] != $this->session->userdata('id'))){
                // $approvedcls = "disable-class";
            }
                if($enquirymain['pickup_flag']=='1' && $enquirymain['picked_by'] != $this->session->userdata('id'))
                {
                    $approvedcls = "";
                    // $approvedcls = "disable-class";
                }
            
            else if($enquirymain['enquiry_status']=="Closed"){
                $approvedcls = "disable-class";
                $status_closed_btn= "disable-class";
            }
            else{
                $approvedcls = "";
            }
            if($enquirymain['enquiry_status']=="Open"){
                $approvedcls = "";
            }
            
            $button_access="";
            // if(($enquirymain['enquiry_status']=="Completed")){
            //     $button_access = "disable-class";
            // }
            $draft_hidden = "";
            ?>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $lead_number; ?>&nbsp;
                   
                    </h4>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 col-xs-12">  
                  <ul id="trackingbar">
                    <?php 
     
                        if (($trackingdata)) {                    
                            $prefixs = get_prefix_72();
                            $suffix = $prefixs['suffix'];
                            if (!empty($trackingdata['lead_id'])) { 
                                echo '<li class="active">' . $trackingdata['lead_number'] . '</li>';
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
               </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12 text-right">
                    <?php 
                        if (($enquirymain['enquiry_status'] == "Open")) {
                            $statustext = "Open";
                            $messagetext = "";    
                        ?>
                        <div class="btn-group alert alert-danger text-center" role="alert">
                            <?php echo "<span>".$statustext."</span>"; ?>                          
                        </div>
                    <?php } 
                    else if (($enquirymain['enquiry_status'] == "Closed")) {
                        $statustext = "Converted";
                        $messagetext = "Converted To Quote";    
                        ?>
                        <div class="btn-group alert alert-success text-center" role="alert">
                            <?php 
                                echo "<span>".$statustext."</span>";
                                // echo "<span>This Lead# (".($lead_number).") is Converted To Quote</span>";
                             ?>                          
                        </div>
                    <?php } 
                    
                     else if (($enquirymain['enquiry_status'] == "Assigned")){
                        $statustext = "Assigned";
                        $messagetext = "Assigned To <b> &nbsp;".$employee_name1."</b>";    
                         ?>
                        <div class="btn-group alert alert-info text-center" role="alert">
                            <?php 
                                echo "<span>".$statustext."</b></span>";
                             ?>                          
                        </div>
                    <?php }

                    else if (($enquirymain['enquiry_status'] == "Accepted")){
                        $statustext = "Accepted";
                        $messagetext = "Accepted by <b>&nbsp;" .$employee_name1."</b>&nbsp; on &nbsp;<b>".date('d-m-Y h:i:sa', strtotime($enquirymain['accepted_dt']))."</b>";    
                        ?>
                        <div class="btn-group alert alert-success text-center" role="alert">
                            <?php echo "<span>".$statustext." </span>"; ?>                          
                        </div>
                    <?php }
                    else if (($enquirymain['enquiry_status'] == "Draft")){
                            $draft_hidden = "d-none";
                        ?>
                        <div class="btn-group alert alert-secondary text-center" role="alert">
                            <?php echo "<span>Draft </span>"; ?>                          
                        </div>
                    <?php }
                    else if (($enquirymain['enquiry_status'] == "Completed")){ 
                        // if($this->aauth->get_user()->roleid == 5)
                        // {
                        ?>
                        <div class="btn-group alert alert-partial text-center" role="alert"> 
                            <?php 
                            echo "<span>Created"; ?>                          
                            <!-- echo "<span>All processes for this Lead (#".($lead_number).")  have been completed. You can now convert it or assign it to an employee. </span>"; ?>                           -->
                        </div>
                    <?php 
                    // }
                    // else{
                        ?>
                        <!-- <div class="btn-group alert alert-danger text-center" role="alert"> 
                            <?php //echo "<span>All processes for this Lead (#".($lead_number).")  have been completed.The authorized person will follow the next step </span>"; ?>                          
                        </div> -->
                    <?php
                    // }
                    }
                 
                    // Assign 'due_date' from $enquirymain if it's not empty, otherwise use the current date
                    $created_date = (!empty($enquirymain['created_date']) && $enquirymain['created_date'] != '0000-00-00') ? $enquirymain['created_date'] : date('Y-m-d');
                
                
                    $due_date = (!empty($enquirymain['due_date']) && $enquirymain['due_date'] != '0000-00-00') 
                    ? $enquirymain['due_date'] 
                    : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['lead_validity'] . " days"));
                    // Assign 'date_received' from $enquirymain if it's not empty, otherwise use the current date
                    $date_received = (!empty($enquirymain['date_received']) && $enquirymain['date_received'] != '0000-00-00') ? dateformat_ymd($enquirymain['date_received']) : date('Y-m-d');

              
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
                <form method="post" id="data_form" enctype="multipart/form-data">
                    <?php
                    $headerclass= "d-none";
                    $pageclass= "page-header-data-section-dblock";
                    if($enquirymain['lead_id'])
                    {
                        $headerclass = "page-header-data-section-dblock";
                        $pageclass   = "page-header-data-section";
                    }
                        $customer_id = $enquirymain['customer_id'];
                        $employee_id = $enquirymain['created_by'];
                    ?>
                    <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12"></div>
                    <div class="col-lg-6 col-md-6 col-sm-12 text-center messagetext_class">
                            <!-- ========================================= -->
                            <?php
                        
                            if(($messagetext)){
                            ?>    
                                <div class="btn-group alert alert-success text-center " role="alert">
                                    <?php echo $messagetext; ?>
                                </div>
                            <?php } ?>
                            <!-- ========================================= -->
                        
                        </div>
                    </div>
                    <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                                    <h3  class="title-sub row"><?php echo $this->lang->line('Lead & Customer Details') ?> <i class="fa fa-angle-down"></i></h3>
                                </div>
                                <div class="col-lg-9 col-md-12 quickview-scroll col-sm-12 col-xs-12 text-right order-1 order-lg-2">
                                    <div class="quick-view-section">                                            
                                        <div class="item-class text-center">
                                            <h4><?php echo $this->lang->line('Customer') ?></h4>
                                            <?php
                                            echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($assigned_customer['name']) . "</b></a>";
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
                                            <?php echo "<p>".dateformat($created_date)."</p>"; ?>
                                        </div>
                                        <div class="item-class text-center">
                                            <h4><?php echo $this->lang->line('Due Date') ?></h4>
                                            <?php echo "<p style='color:".$colorcode."'>".dateformat($due_date)."</p>"; ?>
                                        </div>
                                        <div class="item-class text-center">
                                            <h4><?php echo $this->lang->line('Source') ?></h4>
                                            <?php echo "<p>".($enquirymain['source_of_enquiry']) ? $enquirymain['source_of_enquiry'] : "Direct</p>"; ?>
                                        </div>
                                        <div class="item-class text-center">
                                            <h4><?php echo $this->lang->line('Created By') ?></h4>
                                            <?php 
                                                if($enquirymain['user_type']=='Customer')
                                                {
                                                    echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>Customer</b></a>";
                                                }
                                                else
                                                {
                                                    echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($enquirymain['employee']) . "</b></a>";
                                                }
                                            ?>
                                        </div>
                                        <div class="item-class text-center">
                                            <h4><?php echo $this->lang->line('Total'); ?></h4>
                                            <?php echo "<p>".number_format($enquirymain['total'],2)."</p>";?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="<?=$pageclass?>">
                         
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12 cmp-pnl">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Customer Details') ?></h3>
                                        </div>
                                        <input type="hidden" name="config_tax" id="config_tax" value="<?=$configurations['config_tax']?>">
                                        <input type="hidden" name="discount_flg" class="discount_flg" value="0">
                                        <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $enquirymain['lead_id']; ?>">
                                        <input type="hidden" name="enquiry_status" id="enquiry_status" value="<?php echo $enquirymain['enquiry_status']; ?>">
                                       
                                        <div class="col-sm-5 d-none">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="col-form-label"><?php echo $this->lang->line('Customer Type'); ?></label>
                                                </div>
                                                <?php if($enquirymain['customer_type']=='new'){ $new = "checked";   $existing = ""; $guest="";}
                                                else if($enquirymain['customer_type']=='existing'){
                                                    $new = "";
                                                    $existing = "checked";
                                                    $guest ="";
                                                }else{
                                                    $new = "";
                                                    $existing = "";
                                                    $guest = "checked";
                                                } ?>
                                                <div class="col-12">
                                                    
                                                    <?php //echo ucfirst($enquirymain['customer_type']); ?>
                                                </div>
                                                <!-- <input type="hidden" value="<?=$enquirymain['customer_type']?>" name="customerType"> -->
                                                <div class="form-check col-6" style="margin-left:10px;">
                                                    <input class="form-check-input" type="radio" name="customerType" id="customerType2" value="existing" <?php if($enquirymain['customer_type']=='existing') { echo 'checked'; } echo $default_checked; ?>>
                                                    <label class="form-check-label" for="customerType2">
                                                        Existing
                                                    </label>
                                                </div>
                                                <div class="form-check col-4">
                                                    <input class="form-check-input" type="radio" name="customerType" id="customerType1" value="new" <?php if($enquirymain['customer_type']=='new') { echo 'checked'; } ?>>
                                                    <label class="form-check-label" for="customerType1">
                                                        New
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                            
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="frmSearch">
                                                <!-- <label for="customer_name" class="col-form-label" id="customerLabel"><?php echo $this->lang->line('Customer Name'); ?><span class="compulsoryfld">*</span> <span class="text-right"><input type="buton" value="<?php echo $this->lang->line('Add New Customer'); ?>" class="btn btn-sm btn-secondary btn-crud add_customer_btn"></span></label> -->
                                               
                                                <label  class="col-form-label d-flex justify-content-between align-items-center" id="customerLabel">
                                                    <span>Customer Name<span class="compulsoryfld">*</span></span>
                                                    <input type="button" value="Add New Customer" class="btn btn-sm btn-secondary add_customer_btn" autocomplete="off" title="Add New Customer">
                                                </label>

                                                <!-- <input type="text" class="form-control" name="cst" id="" -->
                                                <input type="text" class="form-control customer_name <?=$existing_lead_class?>" name="customer_name" id="customer-search" placeholder="Enter Customer Name or Mobile Number to search" autocomplete="off" required value="<?php echo $enquirymain['customer_name']; ?>"  data-original-value="<?php echo $enquirymain['customer_name']; ?>"/>
                                                <div id="customer-search-result" class="customer-search-result customer-search-overlay"></div>
                                                <input type="hidden" value="<?= $enquirymain['customer_id']; ?>" name="customer_id" id="customer_id">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_phone"
                                                    class="col-form-label"><?php echo 'Phone'; ?><span class="compulsoryfld">*</span></label>
                                                <input type="number" class="form-control <?=$existing_lead_class1?>" name="customer_phone" id="customer_phone" placeholder="Contact Number" autocomplete="off" value="<?php echo $enquirymain['customer_phone']; ?>"  data-original-value="<?php echo $enquirymain['customer_phone']; ?>"/>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_email"
                                                    class="col-form-label"><?php echo 'Email'; ?><span class="compulsoryfld">*</span></label>
                                                <input type="text" class="form-control <?=$existing_lead_class?>" name="customer_email" id="customer_email" placeholder="Contact Email" autocomplete="off" value="<?php echo $enquirymain['customer_email']; ?>" data-original-value="<?php echo $enquirymain['customer_email']; ?>"/>
                                            </div>
                                        </div>


                                        
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_address"
                                                    class="col-form-label"><?php echo 'Address'; ?></label>
                                                <!-- <input type="text" class="form-textarea <?=$existing_lead_class?>" name="customer_address" id="customer_address" placeholder="Contact Address" autocomplete="off"  value="<?php echo $enquirymain['customer_address']; ?>" /> -->
                                                <textarea name="customer_address" id="customer_address" placeholder="Contact Address" class="form-textarea <?=$existing_lead_class?>" title="Contact Address" data-original-value="<?php echo $enquirymain['customer_address']; ?>"><?php echo $enquirymain['customer_address']; ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                                            
                                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-xs-12 cmp-pnl">
                                    <div class="form-row">
                                        <div class="col-sm-12">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Lead Details') ?></h3>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none"><label
                                                class="col-form-label"><?php echo $this->lang->line('Lead Number'); ?></label>
                                            <input type="text" class="form-control" name="lead_number" id="lead_number"
                                                placeholder="Lead Number" autocomplete="off"
                                                value="<?php echo $lead_number; ?>" readonly />
                                        </div>
                                    
                                        
                                        
                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none"><label
                                                class="col-form-label"><?php echo $this->lang->line('Created Date'); ?></label>
                                            <input type="date" class="form-control" name="enquiry_number1" id="enquiry_number1"
                                                placeholder="Date" autocomplete="off"
                                                value="<?php echo $created_date; ?>" readonly min="<?=$created_date?>" />
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="date_received"
                                                    class="col-form-label"><?php echo 'Date Received'; ?></label>
                                                <input type="date" class="form-control" name="date_received" id="date_received"
                                                    placeholder="Date Received" autocomplete="off" data-original-value="<?php echo $date_received; ?>" value="<?php echo $date_received; ?>" required />
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="due_date"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer Enquiry Deadline'); ?></label>
                                                <input type="date" class="form-control" name="due_date" id="due_date" required
                                                    placeholder="Due Date" autocomplete="off" value="<?php echo $due_date; ?>" min="<?php echo $due_date; ?>" data-original-value="<?php echo $due_date; ?>"/>
                                            </div>
                                        </div>
                                    


                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss">
                                                <label for="source_of_enquiry" class="col-form-label"><?php echo 'Source of Enquiry'; ?><span class="compulsoryfld">*</span></label>
                                                <select class="form-control form-select" id="source_of_enquiry" data-original-value="<?php echo trim($enquirymain['source_of_enquiry']); ?>" name="source_of_enquiry" required>
                                                    <option value="">Select Source</option>
                                                    <option value="Email"
                                                        <?php if($enquirymain['source_of_enquiry']=="Email"){ echo "selected"; } ?>>
                                                        Email</option>
                                                    <option value="Direct"
                                                        <?php if($enquirymain['source_of_enquiry']=="Direct"){ echo "selected"; } ?>>
                                                        Direct</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 assignedsection d-none1">
                                            <div class="frmclasss">
                                                <label for="assignedto" class="col-form-label"><?php echo 'Assigned To '; ?></label>
                                                <?php
                                                if (($enquirymain['assigned_to']) &&  ($enquirymain['assigned_to'] == $this->session->userdata('id')) &&  ($enquirymain['enquiry_status'] != 'Closed')) {
                                                    ?>
                                                        <button type="button" class="btn btn-sm btn-secondary revert-btncolor <?=$drafthide_class?>" id="revert-btn"><?php echo $this->lang->line('Revert To') ?></button>
                                                    <?php }
                                                    $readonly="";
                                                    $disableclass="";
                                                    if($enquirymain['assigned_to'] == $this->session->userdata('id'))
                                                    {
                                                        $readonly = "readonly";
                                                        $disableclass="disable-class";
                                                    }
                                                ?>
                                                <select class="form-control form-select <?=$disableclass?>" id="assignedto" name="assignedto"  data-original-value="<?php echo $enquirymain['assigned_to']; ?>">
                                                    <option value="">All in the List </option>
                                                    <?php foreach ($approval_level_users as $row) {
                                                        // if($this->session->userdata('id')== $row['id'])
                                                        // {
                                                        //     continue;
                                                        // }
                                                            echo '<option value="' . $row['user_id'] . '"';
                                                            if ($enquirymain['assigned_to'] == $row['user_id']) {
                                                                echo ' selected';
                                                            }                                            
                                                            echo '>' . $row['name'] . '</option>';
                                                    } ?>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                   

                                        <!--erp2024 newly added 28-09-2024  -->
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                            <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Reference#" value="<?=$enquirymain['customer_reference_number']?>" data-original-value="<?php echo $enquirymain['customer_reference_number']; ?>">
                                            </div>                                    
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_contact_person" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                            <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person"  value="<?=$enquirymain['customer_contact_person']?>" data-original-value="<?php echo $enquirymain['customer_contact_person']; ?>">
                                            </div>                                    
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Number'); ?></label>
                                            <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number"  value="<?=$enquirymain['customer_contact_number']?>"  data-original-value="<?php echo $enquirymain['customer_contact_number']; ?>">
                                            </div>                                    
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                            <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email" value="<?php echo $enquirymain['customer_contact_email'] ?>" data-original-value="<?php echo $enquirymain['customer_contact_email']; ?>">
                                            </div>                                    
                                        </div>
                                        <!--erp2024 newly added 28-09-2024 ends -->
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none">
                                            <div class="frmclasss"><label for="enquiry_status"
                                                    class="col-form-label"><?php echo 'Status'; ?></label>
                                                    <!-- <select class="form-control form-select" id="enquiry_status" name="enquiry_status">
                                                        <?php if($enquirymain['enquiry_status']=="Closed")
                                                        { ?>
                                                            <option value="Closed"
                                                                <?php if($enquirymain['enquiry_status']=="Closed"){ echo "selected"; } ?>><?php echo $this->lang->line('Converted To Quote'); ?>
                                                            </option>
                                                    <?php }
                                                    else{
                                                        ?>
                                                            <option value="">Select Status</option>
                                                            <option value="Open"
                                                                <?php if($enquirymain['enquiry_status']=="Open"){ echo "selected"; } ?>><?php echo $this->lang->line('Open'); ?>
                                                            </option>
                                                            <option value="Assigned"
                                                                <?php if(($enquirymain['enquiry_status']=="Assigned") || ($enquirymain['enquiry_status']=="Accepted")){ echo "selected"; } ?>>
                                                                <?php echo $this->lang->line('Assigned'); ?></option>
                                                                
                                                        <?php } ?>
                                                    </select> -->
                                                    <div class="col-12 row">
                                                        <?php 
                                                        $openval = "";
                                                        $assignedval ="";
                                                        $completedval="";
                                                        switch ($enquirymain['enquiry_status']) {
                                                            case 'Open':
                                                                $openval = "checked";
                                                                break;
                                                            case 'Assigned':
                                                            case 'Accepted':
                                                                $assignedval = "checked";
                                                                break;
                                                            case 'Completed':
                                                                $completedval = "checked";
                                                                break;
                                                            default:
                                                                // Handle the default case if needed
                                                                $completedval = "checked";
                                                                break;
                                                        }
                                                        ?>
                                                        <div class="form-check col-4">
                                                            <input class="form-check-input" type="radio" name="enquiry_status" id="enquiry_status1" value="Open" <?=$openval?>>
                                                            <label class="form-check-label" for="enquiry_status1">
                                                            Open
                                                            </label>
                                                        </div>
                                                        <div class="form-check col-5">
                                                            <input class="form-check-input" type="radio" name="enquiry_status" id="enquiry_status2" value="Assigned" <?=$assignedval?>>
                                                            <label class="form-check-label" for="enquiry_status2">
                                                            Assigned
                                                            </label>
                                                        </div>
                                                        <div class="form-check col-5">
                                                            <input class="form-check-input" type="radio" name="enquiry_status" id="enquiry_status3" value="Completed" <?=$completedval?>>
                                                            <label class="form-check-label" for="enquiry_status3">
                                                            Completed
                                                            </label>
                                                        </div>
                                                        <?php
                                                        if($enquirymain['enquiry_status']=="Closed"){ ?>
                                                        <div class="form-check col-3">
                                                            <input class="form-check-input" type="radio" name="enquiry_status" id="enquiry_status3" value="Closed" checked>
                                                            <label class="form-check-label" for="enquiry_status3">
                                                            Closed
                                                            </label>
                                                        </div>
                                                        <?php } ?>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-12"></div>

                                        <!-- <div class="col-md-6 mb-1">
                                            <div class="col-sm-12"><label for="comments" class="col-form-label"><?php echo 'Comments'; ?></label>
                                            <textarea class="form-control"  placeholder="Comments" name="comments" id="comments"><?php echo  $enquirymain['comments']; ?></textarea>
                                        </div>                                    
                                    </div> -->
                                        <?php 
                                        if($enquirymain['enquiry_status']=="Closed")
                                        {
                                        ?>
                                        <div class="col-xl-6 col-lg-3 col-md-4 col-sm-12 col-xs-12 mb-1 closingreason d-none">
                                            <?php } 
                                        else { ?>
                                            <div class="col-xl-6 col-lg-3 col-md-4 col-sm-12 col-xs-12 mb-1 d-none closingreason">
                                                <?php } ?>
                                                
                                                <label for="comments" class="col-form-label"><?php echo 'Closing Reason *'; ?></label>
                                                <textarea placeholder="Reason for closing" id="comments" name="comments" rows="2"
                                                    class="summernote1 wid100per form-textarea" data-original-value="<?php echo $enquirymain['comments']; ?>"><?php echo  $enquirymain['comments']; ?></textarea>
                                            </div>

                                            <div class="col-xl-6 col-lg-5 col-md-6 col-sm-12 col-xs-12 mb-1">
                                                <div class="frmclasss">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label for="email_contents"
                                                                class="col-form-label"><?php echo $this->lang->line('Email Contents'); ?></label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-12 text-right">
                                                            <label class="col-form-label"><a
                                                                    href="<?php echo base_url() ?>Invoices/convert_to_deals"
                                                                    class="btn btn-crud btn-secondary btn-sm <?php echo $approvedcls; ?>" type="button"><i
                                                                        class="fa fa-share"></i>
                                                                    <?php echo $this->lang->line("Convert to Deals"); ?></a></label>
                                                        </div>
                                                    </div>
                                                    <textarea class="summernote1 form-textarea" placeholder="email contents" id="email_contents"  name="email_contents" rows="2" data-original-value="<?php echo $enquirymain['email_contents']; ?>"><?php echo  $enquirymain['email_contents']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12">
                                                <label for="note" class="col-form-label"><?php echo $this->lang->line('Note'); ?></label>
                                                <textarea rows="2" class="summernote1 form-textarea"  placeholder="Note" id="note" name="note" data-original-value="<?php echo $enquirymain['note']; ?>"><?php echo $enquirymain['note'];?></textarea>
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
                                                                <button class="btn btn-crud btn-secondary btn-sm mt-1" id="addmore_img"  title="Add More Files" type="button" ><i class="fa fa-plus-circle"></i> Add More</button>
                                                            
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
                                                                echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"delete_attachment('{$image['lead_attachment_id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
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

                        <hr>
                        <div class="col-12 form-row mt-1 discount-toggle">
                            <div class="form-check" >
                                <input class="form-check-input discountshowhide" type="checkbox" value="2"  name="discountshowhide" id="discountshowhide">
                                <label class="form-check-label dicount-checkbox" for="discountshowhide">
                                <b><?php echo $this->lang->line('Would you like to add a discount for these products?'); ?></b>
                                </label>
                            </div>
                        </div>
                                              
                        

                        <!-- ============================================================ -->
                        <div id="saman-row" class="table-scroll">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>

                                    <tr class="item_header bg-gradient-directional-blue white">
                                        <th width="4%" class="text-center"><?php echo $this->lang->line('SN') ?></th>
                                        <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                        <th width="22%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                        <th width="5%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
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
                                            <!-- (<?php //echo $this->config->item('currency'); ?>) -->
                                        </th>
                                        <th  class="text-center1"><?php echo $this->lang->line('Action') ?></th>
                                    </tr>
                                </thead>
                                
                                <?php 
                                    $itemcount = 0;
                                    $discount_flg=0;
                                    if($products)
                                    {
                                        echo '<tbody>';
                                            $i = 0;
                                            $k=1;
                                            $totaldiscount =0;
                                            $totaltax =0;
                                            $itemcount = count($products);
                                            if(!empty($products))
                                            {
                                                
                                                $readonly_class = ($enquirymain['enquiry_status']=="Closed") ? "readonly": "";
                                                $converted_class = ($enquirymain['enquiry_status']=="Closed") ? "disable-class": "";
                                                
                                                foreach ($products as $row) {
                                                    if($row['totalQty']<=$row['alert']){
                                                        echo '<tr style="background:#ffb9c2;">';
                                                    }
                                                    else{
                                                        echo '<tr >';
                                                    }
                                                    $taxtd ="";
                                                    $totaltax = $totaltax+amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc);
                                                    $totaldiscount = $totaldiscount+amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc);

                                                    $product_name_with_code = $row['product_name'].'('.$row['product_code'].') - ';

                                                    if($configurations["config_tax"]!="0"){        
                                                        $taxtd = '<td class="text-center">
                                                            <div class="text-center">
                                                                
                                                                <input type="hidden" class="form-control" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . amountFormat_general($row['tax']) . '">
                                                                    <strong id="taxlabel-' . $i . '"></strong>&nbsp;<strong  id="texttaxa-' . $i . '">' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                                            </div>
                                                        </td>';
                                                    } 
                                                    echo "<td class='text-center serial-number'>".$k++."</td>";
                                                    echo '<td><input type="text" onkeyup="leadedit_autocomplete(' . $i . ')" class="form-control" name="code[]" placeholder="Enter Product name or Code"  value="' . $row['product_code'] . '" id="code-'.$i.'" autocomplete="off" title="'.$product_name_with_code.'Code" data-original-value="' . $row['code'].'" '.$readonly_class.' ></td>';
                                                    
                                                    echo '<td><input type="text" class="form-control" name="product_name[]" placeholder="Enter Product name or Code"  value="' . $row['product_name'] . '" id="leadproductname-'.$i.'" autocomplete="off" onkeyup="leadedit_autocomplete(' . $i . ')" title="'.$product_name_with_code.'Product" data-original-value="' . $row['product'].'" '.$readonly_class.'>
                                                    
                                                    <input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-'.$i.'" value="' . $row['max_disrate'] . '" > </td>';


                                                    echo '<td class="position-relative"><input type="product_qty" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog(),check_on_hand_quantity()" autocomplete="off" value="' . intval($row['quantity']) . '" title="'.$product_name_with_code.'Quantity" data-original-value="' . $row['quantity'].'"><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['quantity']) . '"><div class="tooltip1"></div></td>';

                                                    echo '<td class="text-center"><strong id="onhandQty-'.$i.'">'.$row['totalQty'].'</strong></td>';

                                                    echo '<td class="text-right"><strong id="pricelabel-' . $i . '">' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off" value="' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                                    
                                                    echo '<td class="text-right">
                                                    <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' .$row['lowest_price']. '">
                                                    <strong id="lowestpricelabel-' . $i . '">' .$row['lowest_price']. '</strong>
                                                    </td>';
                                                    
                                                    
                                                    //Verify that tax is enabled
                                                    echo $taxtd;   
                                                    // erp2024 27-03-2025 discount amount calcualation
                                                    $maxdiscountamount=0;
                                                    $productprice = amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc);
                                                    $maxdiscountamount = round(($productprice * $row['max_disrate']) / 100, 2);
                                                    $row['max_disrate'] = (intval($row['max_disrate']) == floatval($row['max_disrate']))  ? intval($row['max_disrate']) : number_format($row['max_disrate'], 2);
                                                    $discountamount = $row['max_disrate']."% (".$maxdiscountamount.")";
                                                    echo '<td class="text-center"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-'.$i.'" value="' . $maxdiscountamount . '"><strong id="maxdiscountratelabel-' . $i . '">' .$discountamount. '</strong></td>';            

                                                    if($row['discount_type']=='Perctype'){
                                                        $percsel = "selected";
                                                        $amtsel = "";
                                                        $perccls = '';
                                                        $amtcls = 'd-none';
                                                        $disperc = amountFormat_general($row['discount']);
                                                        $disamt = 0;
                                                    }
                                                    else{
                                                        $amtsel = "selected";
                                                        $percsel = "";
                                                        $perccls = 'd-none';
                                                        $amtcls = '';
                                                        $disamt = amountFormat_general($row['discount']);
                                                        $disperc = 0;
                                                    }
                                                    if($row['discount']>0 && $discount_flg==0)
                                                    {
                                                        $discount_flg =1;
                                                    }
                                                    echo '<td class="text-center discountcoloumn d-none" >
                                                            <div class="input-group text-center">
                                                                <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control element-height" onchange="discounttypeChange(' . $i . ')" title="'.$product_name_with_code.'Discount Type" data-original-value="' . $row['discount_type'].'">
                                                                    <option value="Perctype" '.$percsel.'>%</option>
                                                                    <option value="Amttype" '.$amtsel.'>Amt</option>
                                                                </select>&nbsp;
                                                                <input type="number" min="0" class="form-control discount element-height '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '" title="'.$product_name_with_code.'Discount Percentage" data-original-value="' . $disperc.'">
                                                                
                                                                <input type="number" min="0" class="form-control discount element-height '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '" title="'.$product_name_with_code.'Discount Amount" data-original-value="' . $disamt.'">
                                                            </div>                                    
                                                            <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel">Amount : ' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                                            <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                                        </td>';


                                                    
                                                    echo '<td class="text-right">
                                                        <strong><span class="ttlText" id="result-' . $i . '">' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span></strong></td>';

                                                    echo '<td class="text-center1 d-flex"><button onclick="producthistory('.$i.')" type="button" class="btn btn-crud btn-sm btn-secondary producthis '.$converted_class.'" title="Previous Quoted History12" ><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary '.$converted_class.'" title="Product Informations"><i class="fa fa-info"></i></button>&nbsp;<button type="button" data-rowid="' . $i . '" class="btn btn-crud btn-sm btn-secondary removeProd '.$converted_class.'" title="Remove"> <i class="fa fa-trash"></i> </button>
                                                    </td>
                                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['total_tax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['total_discount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['product_code'] . '">
                                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['product_code'] . '">
                                                    </tr>';
                                                    $i++;
                                                }
                                            }
                                            if($enquirymain['total'] >0){
                                                $dnonecls = '';
                                            }
                                            else{
                                                $dnonecls = 'd-none';
                                            }
                                            $drafthide_class="";
                                            if($enquirymain['enquiry_status']=="Draft")
                                            {
                                                $approvedcls = '';
                                                $drafthide_class="d-none";
                                            }
                                            ?>
                                            <tr class="last-item-row sub_c tr-border">
                                                <td class="add-row no-border" colspan="9">
                                                    <?php if($enquirymain['enquiry_status']!="Closed") { ?>
                                                    <button type="button" class="btn btn-crud btn-secondary <?php echo $approvedcls; ?> <?=$btns_class?> <?=$button_access?> <?=$status_closed_btn?> add-row-btn" id="lead_create_btn" title="Add product row">
                                                        <i class="fa fa-plus-square"></i> Add Row
                                                    </button>
                                                    <?php } ?>
                                                </td>
                                                <td colspan="7" class="no-border"></td>
                                            </tr>
                                            <?php 
                                            if($configurations['config_tax']!='0'){ ?>
                                                <tr class="sub_c noproduct-section <?=$dnonecls?>">
                                                    <td colspan="7" align="right" class="no-border">
                                                        <input type="hidden" value="0" id="subttlform"                                                                     name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                                    </td>
                                                    <td align="left" colspan="2" class="no-border"><span
                                                                class="currenty lightMode"><?php //echo $this->config->item('currency'); ?></span>
                                                        <span id="taxr" class="lightMode"><?php echo $totaltax; ?></span></td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="sub_c noproduct-section <?=$dnonecls?>">
                                                <td colspan="9" align="right" class="no-border td-colspan">
                                                    <strong><?php echo $this->lang->line('Total Discount') ?> <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                </td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs" class="lightMode"><?php echo number_format($totaldiscount,2); ?></span>
                                                </td>
                                            </tr>

                                            <tr class="sub_c " style="display: none;">
                                                <td colspan="8" align="right" class="no-border">
                                                    <strong><?php echo $this->lang->line('Shipping') ?></strong>
                                                </td>
                                                <td align="right" colspan="3" class="no-border"><input type="text"
                                                        class="form-control shipVal" onkeypress="return isNumber(event)"
                                                        placeholder="Value" name="shipping" autocomplete="off"
                                                        onkeyup="billUpyog()">
                                                    <?php echo $this->lang->line('Tax') ?>
                                                    <?php  //echo $this->config->item('currency'); ?>
                                                    <span id="ship_final">0</span> 
                                                </td>
                                            </tr>

                                            <tr class="sub_c noproduct-section <?=$dnonecls?>">
                                                <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                </td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span class="grandtotaltext"><?php echo number_format($enquirymain['total'],2);?></span>
                                                    <input type="hidden" name="total" class="form-control invoiceyoghtml" value="<?php echo $enquirymain['total'];?>"   readonly>

                                                </td>
                                            </tr>
                                        </tbody>

                                    <?php }
                                          else
                                          { ?>
                                        <tbody>
                                            <tr>
                                                <td class="text-center serial-number">1</td>
                                                <td><input type="text" class="form-control" name="code[]" id='code-0' placeholder="<?php echo $this->lang->line('Search by Item No') ?>"></td>
                                                <td><input type="text" class="form-control" name="product_name[]"
                                                        placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                                        id='productname-0'>
                                                </td>
                                            
                                                <td class="position-relative"><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-0" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(), check_on_hand_quantity()"  autocomplete="off" value="0" maxlength="6" onkeypress="return isPositiveNumber(event, this)"  oninput="isPositiveNumber(event, this)" title="Quantity">
                                                <div class="tooltip1"></div>
                                                </td>
                                                <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                                <td class="text-right">
                                                    <strong id="pricelabel-0"></strong>
                                                    <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off">
                                                </td>
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

                                                <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-0" value=""></td>
                                                <td class="text-center discountcoloumn d-none">
                                                    <div class="input-group text-center">
                                                        <select name="discount_type[]" id="discounttype-0" class="form-control element-height" onchange="discounttypeChange(0)">
                                                            <option value="Perctype">%</option>
                                                            <option value="Amttype">Amt</option>
                                                        </select>&nbsp;
                                                        <input type="number"  min="0" class="form-control discount element-height" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0)">
                                                        <input type="number" min="0" class="form-control discount d-none element-height" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0)">
                                                    </div>                                    
                                                    <input type="hidden" name="disca[]" id="disca-0" value="0">
                                                    <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                                    <div><strong id="discount-error-0"></strong></div>                                    
                                                </td>

                                                <td class="text-right">
                                                    <strong><span class='ttlText' id="result-0">0</span></strong></td>

                                                <td class="text-center1  d-flex">
                                                <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis" title="Previous Quoted History"><i class="fa fa-history"></i></button>&nbsp;
                                                <button onclick='single_product_details("0")' type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button>&nbsp;<button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>
                                                </td>
                                                <input type="hidden" name="taxa[]" id="taxa-0" value="0">                                
                                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                                <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                                <input type="hidden" name="unit[]" id="unit-0" value="">
                                                <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                                <input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0">
                                                <!-- <textarea id="dpid-0" class="form-control" name="product_description[]" placeholder="<?php echo $this->lang->line('Enter Product description'); ?>"  autocomplete="off"></textarea> -->
                                            </tr>

                                            <tr class="last-item-row sub_c tr-border">
                                                <td class="add-row no-border" colspan="9">
                                                    <button type="button" class="btn btn-crud btn-secondary" aria-label="Left Align"
                                                            data-placement="top" id="lead_create_btn">
                                                        <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add Row') ?>
                                                    </button>
                                                </td>
                                                <td colspan="7" class="no-border"></td>
                                            </tr>
                                            <?php 
                                            if($configurations['config_tax']!='0'){ ?>
                                                <tr class="sub_c noproduct-section d-none">
                                                    <td colspan="7" align="right" class="no-border">
                                                        <input type="hidden" value="0" id="subttlform"                                                                     name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                                    </td>
                                                    <td align="right" colspan="2" class="no-border"><span
                                                                class="currenty lightMode"><?php //echo $this->config->item('currency'); ?></span>
                                                        <span id="taxr" class="lightMode">0</span></td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="sub_c noproduct-section d-none1" >
                                                <td colspan="9" align="right" class="no-border td-colspan">
                                                    <strong><?php //echo $this->lang->line('Total Discount').'('.$this->config->item('currency').')'
                                                    echo $this->lang->line('Total Discount'); ?></strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs" class="lightMode">0.00</span></td>
                                            </tr>

                                            <!-- <tr class="sub_c" >
                                                <td colspan="6" align="right" class="no-border">
                                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                                <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                                    onkeypress="return isNumber(event)"
                                                                                    placeholder="Value"
                                                                                    name="shipping" autocomplete="off"
                                                                                    onkeyup="billUpyog()">
                                                    <?php //echo $this->lang->line('Tax') ?>
                                                </td>
                                            </tr> -->

                                            <tr class="sub_c noproduct-section d-none1" >
                                                <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                                </td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span class="grandtotaltext">0.00</span>
                                                    <input type="hidden" name="total" class="form-control invoiceyoghtml"  value="0" readonly>
                                                   
                                                </td>
                                            </tr>
                                        </tbody>
                                    
                                        <?php } ?>


                            </table>
                        </div>

                        <input type="hidden" value="search" id="billtype">
                        <input type="hidden" value="<?=$itemcount?>" name="counter" id="ganak">
                        <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
                        <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
                        <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
                        <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
                        <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">

                        <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                            name="discountFormat" id="discount_format">
                        <input type="hidden"
                            value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                            name="shipRate" id="ship_rate">
                        <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>"
                            name="ship_taxtype" id="ship_taxtype">
                        <input type="hidden" value="0" name="ship_tax" id="ship_tax">
                        
                        <!-- ============================================================ -->
                         <hr>
                        <div class="row" >
                        <!-- <div class="row pt-3 pb-2" style="border-top:1px #d0d0d0 solid;"> -->
                                <div class="col-xl-3 col-lg-3 col-sm-12">
                                    <?php

                                    if (($enquirymain['assigned_to']) && ($enquirymain['assigned_to'] == $this->session->userdata('id')  &&  ($enquirymain['enquiry_status'] != 'Closed'))) { ?>
                                        <!-- <button type="button" class="btn btn-lg btn-secondary revert-btncolor <?=$drafthide_class?>" id="revert-btn"><?php echo $this->lang->line('Revert To') ?></button> -->
                                    <?php }
                                    if($enquirymain){
                                        ?>
                                        <button class="btn btn-crud1 btn-secondary btn-lg unsavedisable-btns sub-btn <?=$approvedcls?> <?=$button_access?> <?=$status_closed_btn?> <?=$btns_class?>" type="submit" id="save_as_draft_btn_edit" title="Save As Draft"><?php echo $this->lang->line('Save As Draft'); ?></button>
                                    <?php } 
                                    else { ?>
                                        <button class="btn btn-crud1 btn-secondary btn-lg sub-btn  <?=$approvedcls?> <?=$button_access?> <?=$status_closed_btn?> <?=$btns_class?>" type="submit" id="save_as_draft_btn" title="Save As Draft"><?php echo $this->lang->line('Save As Draft'); ?></button>

                                    <?php }
                                    ?>
                                </div>  
                                <div class="col-xl-9 col-lg-9 col-sm-12 responsive-text-right">
                                <?php
                                if(($enquirymain['enquiry_status']!="Closed")){ ?>   
                                <!-- if(($this->aauth->get_user()->roleid ==5) && ($enquirymain['enquiry_status']!="Closed")){ ?>    -->
                                    <!-- <button class="btn btn-crud btn-secondary btn-lg" id="assignto-btn"><?php echo $this->lang->line('Assign To An Employee'); ?></button> -->
                                <?php } 
                               
                                if(($enquirymain['enquiry_status']!="Closed") && (($enquirymain['assigned_to'] == $this->session->userdata('id')) || $this->aauth->get_user()->roleid ==5)){ 
                                    // $draftbtn_class="disable-class";
                                }
                                if($enquirymain['enquiry_status']=="Draft" && $this->aauth->get_user()->roleid !=5){
                                    $draftbtn_class="disable-class";
                                }
                                    $assigned_class = "";
                                    if(($enquirymain['assigned_to'] == $this->session->userdata('id'))){
                                        $assigned_class = "d-none";
                                    ?>  
                                        <button class="btn btn-crud btn-secondary btn-lg unsavedisable-btns sub-btn <?=$approvedcls?> <?=$acceptbtns_class?> <?=$button_access?> <?=$draftbtn_class?> <?=$status_closed_btn?> <?=$existing_lead_class_hide?>" id="lead-accept-btn" title="Accept the lead"><?php echo $this->lang->line('Accept Lead'); ?></button>
                                       
                                    <?php } 
                                         ?>

                                            <button class="btn btn-crud btn-secondary btn-lg d-none sub-btn <?=$approvedcls?> <?=$btns_class?> <?=$button_access?>  <?=$status_closed_btn?> <?=$assigned_class?> <?=$existing_lead_class_hide?>  <?=$draft_hidden?>" id="assignto-btn" title="Assign/Reassign To An Employee">
                                                <?php $label = ($enquirymain['assigned_to']) ?  $this->lang->line('Reassign To An Employee') : $this->lang->line('Assign To An Employee'); 
                                                echo $label; ?></button>

                                            <button class="btn btn-crud1 btn-secondary btn-lg unsavedisable-btns sub-btn <?=$approvedcls?> <?=$btns_class?> <?=$button_access?>  <?=$status_closed_btn?> <?=$assigned_class?> <?=$existing_lead_class_hide?> " id="generateleadeditbtn" title="If any changes occur, it will work"><?php echo $this->lang->line('Update'); ?></button>
                                            
                                           <?php
                                           if(($enquirymain) && $enquirymain['enquiry_status']!="Completed")
                                           {?>
                                                <button class="btn btn-crud btn-secondary btn-lg d-none sub-btn <?=$approvedcls?> <?=$btns_class?> <?=$button_access?> <?=$status_closed_btn?>" id="complete-proccess-btn" title="All processes are completed, but it is not converting to a quote now"><?php echo $this->lang->line('Complete'); ?></button>
                                            <?php }
                                            $buttoncolor = "btn-primary";                                            
                                            $button_label = 'Convert to Quote';
                                            if(empty($enquirymain))
                                            {
                                                echo '<button class="btn btn-crud1 btn-primary btn-lg sub-btn" id="generatelead" title="Create">'.$this->lang->line("Create").'</button>';
                                                $buttoncolor = "btn-secondary";
                                                $button_label = 'Create & Convert to Quote';
                                            }
                                            
                                            ?>
                                            <button class="btn btn-crud1 sub-btn <?=$buttoncolor?> btn-lg <?=$approvedcls?> <?=$btns_class?> <?=$button_access?>  <?=$status_closed_btn?>" id="convert-to-quote-btn" title="Convert to Quote"><?php echo $this->lang->line($button_label); ?></button>
                                                   
                                </div>  
                        </div>


                </form>
            </div>
        </div>
    </div>
  

<script>
const changedFields = {};
$(document).ready(function() {
    var discountflag = <?=$discount_flg?>;
    if(discountflag==1){
        showdiscount_potion();
    }    
    if ($("#enquiry_status").val() == "Closed") {
        disable_items();
    }
    $("#assignedto").select2({
        width: "100%" // Sets the width to 100%
    });
    $('select').each(function () {
        const selectedLabel = $(this).find(':selected').text();
        $(this).attr('data-original-label', selectedLabel);
    });
    
    var hasUnsavedChanges = false;
    $('input:not(:checkbox), select, textarea').on('input change', function() {
        hasUnsavedChanges = true;
    });
    $(document).on('input change', 'input:not(:checkbox), select, textarea', function() {
        hasUnsavedChanges = true;
    });
    
    // Track form submission
    $('form').submit(function() {
        hasUnsavedChanges = false;
    });

    
    // erp2024 newly added 14-06-2024 for detailed history log

    //erp2024 06-01-2025 for history log
  
    $(document).ready(function () {       
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

                    if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalNumber,
                            newValue: newValue,
                            fieldlabel: field_label
                        };
                    } else {
                        delete changedFields[fieldId];
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
  
    if ($('#enquiry_status').val() == 'Closed') {
        $('#comments').prop('required', true);
    }
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

    function toggleAssignedSection() {
        if ($('#enquiry_status').val() !== 'Assigned') {
            $('.assignedsection').addClass('d-none');
            $('#assignedto').removeAttr('required');
        } else {
            $('.assignedsection').removeClass('d-none');
            $('#assignedto').attr('required', 'required');
        }
    }

    $('#enquiry_status').change(toggleAssignedSection);

    // Call the function on page load in case the initial value of enquiry_status is "Assigned"
    // toggleAssignedSection();

   $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel, {
            ignore: "",
            rules: {
                customer_name: { required: true },
                customer_phone: { required: true },
                source_of_enquiry: { required: true },
                due_date: { required: true },
                customer_email: { required: true, email: true },
                customer_contact_number: { phoneRegex: true }
            },
            messages: {
                customer_name: "Enter Name",
                customer_phone: "Enter Phone Number",
                customer_email: "Enter Email",
                source_of_enquiry: "Select a source",
                due_date: "Enter a valid date"
            }
    }));


    // Handle form submission
    $('#generateleadeditbtn').prop('disabled', false);
    $('#generateleadeditbtn').on('click', function(e) {
        e.preventDefault();

        if(hasUnsavedChanges==true)
        {
            if ($("#data_form").valid()) 
            {
                    $('#generateleadeditbtn').prop('disabled', true);
                    var emailContents = $('#email_contents').val(); 
                    if(emailContents=="<p><br></p>"){
                        emailContents="";
                    }
                    // Check if any product_name[] input has a value
                    var productNameFilled = false;
                    var imageFilled = false;
                    $("input[name='product_name[]']").each(function() {
                        if ($(this).val()) {
                            productNameFilled = true;
                            return false; // Exit loop early if we find a filled product name
                        }
                    });
                    $("input[name='upfile[]']").each(function() {
                        if ($(this).val()) {
                            imageFilled = true;
                            return false; // Exit loop early if we find a filled product name
                        }
                    });

                    var imgcontains = <?=$imgcontains?>;
                    var enquiry_status = $("#enquiry_status").val();
                    var msg="";
                    if(enquiry_status == "Open")
                    {
                        msg = "Please Assign the Customer Enquiry to an Employee for Further Action.";
                    }
                    if (productNameFilled) {
                    // if ((emailContents && emailContents.trim() !== '') || productNameFilled || imageFilled || imgcontains == 1) {
                        var form = $('#data_form')[0];
                        var formData = new FormData(form);
                        formData.append('employeeassign', '1');                        
                        formData.append('changedFields', JSON.stringify(changedFields));
                        Swal.fire({
                            title: "Are you sure?",
                            "text": msg+" Do you want to update lead?",
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
                                        url: baseurl + 'Invoices/customerenquiryeditaction',
                                        type: 'POST',
                                        data: formData,
                                        contentType: false, 
                                        processData: false, 
                                        success: function(response) {
                                            location.reload();
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                            console.log(error); // Log any errors
                                        }
                                    });
                                }
                                else{
                                    $('#generateleadeditbtn').prop('disabled', false);
                                }
                            });   
                    } else {
                        Swal.fire({
                            title: 'Input Required',
                            text: 'To generate a lead, please enter at least one value in Product',
                            // text: 'To generate a lead, please enter at least one value in either Email Contents, Image, or Product',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        $('#generateleadeditbtn').prop('disabled', false);
                    }
                } 
                else {
                    $('.page-header-data-section').css('display','block');
                    $('#generateleadeditbtn').prop('disabled', false);
                }
        }
         
    });

    $('#generatelead').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#generatelead').prop('disabled',true);
        // Validate the form
        if ($("#data_form").valid()) {
            var emailContents = $('#email_contents').val();
            var fileInput = $('#upfile-0').val(); // Get the file input value
            
            // Check if any product_name[] input has a value
            var productNameFilled = false;
            var imageFilled = false;
            $("input[name='product_name[]']").each(function() {
                if ($(this).val()) {
                    productNameFilled = true;
                    return false;
                }
            });
            $("input[name='upfile[]']").each(function() {
                if ($(this).val()) {
                    imageFilled = true;
                    return false; // Exit loop early if we find a filled product name
                }
            });
            if (productNameFilled) {
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                Swal.fire({
                        title: "Are you sure?",
                        // text: "Are you sure you want to update inventory? Do you want to proceed?",
                        "text":"Do you want to create a lead?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: "No - Cancel",
                        reverseButtons: true,
                        focusCancel: true,
                        allowOutsideClick: false,
                        showCancelButton: true, 
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: baseurl + 'Invoices/customerenquiryaction',
                                type: 'POST',
                                data: formData,
                                contentType: false, 
                                processData: false,
                                success: function(response) {
                                    window.location.href = baseurl + 'invoices/leads';
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                    console.log(error); // Log any errors
                                }
                            });
                        }
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('#generatelead').prop('disabled', false);
                        }
                        
                    });
            } else {
                Swal.fire({
                    title: 'Input Required',
                    text: 'To generate a lead, please enter at least one value in Product.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#generatelead').prop('disabled',false);
            }
        }
        else{
            
            $('.page-header-data-section').css('display','block');
            $('#generatelead').prop('disabled',false);
        }
    });


    $('#assignto-btn').on('click', function(e) {
        e.preventDefault();
        if ($("#data_form").valid()) {
            $('#assignto-btn').prop('disabled', true);
            var emailContents = $('#email_contents').val(); 
            if(emailContents=="<p><br></p>"){
                emailContents="";
            }
            // Check if any product_name[] input has a value
            var productNameFilled = false;
            var imageFilled = false;
            $("input[name='product_name[]']").each(function() {
                if ($(this).val()) {
                    productNameFilled = true;
                    return false; // Exit loop early if we find a filled product name
                }
            });
            $("input[name='upfile[]']").each(function() {
                if ($(this).val()) {
                    imageFilled = true;
                    return false; // Exit loop early if we find a filled product name
                }
            });

            var imgcontains = <?=$imgcontains?>;
            var enquiry_status = $("#assignedto").val();
            var msg="";
            if(enquiry_status == "")
            {
                Swal.fire({
                    title: 'Input Required',
                    text: 'Please Assign the Customer Enquiry to an Employee for Further Action',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#assignto-btn').prop('disabled', false);
                return false;
                msg = "Please Assign the Customer Enquiry to an Employee for Further Action.";
            }
            if ((emailContents && emailContents.trim() !== '') || productNameFilled || imageFilled || imgcontains == 1) {
                var form = $('#data_form')[0];
                var formData = new FormData(form);
                formData.append('employeeassign', '1');
                formData.append('changedFields', JSON.stringify(changedFields));
                Swal.fire({
                    title: "Are you sure?",
                    "text": msg+" Do you want to assign this lead to an employee?",
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
                                url: baseurl + 'Invoices/customerenquiryeditaction',
                                type: 'POST',
                                data: formData,
                                contentType: false, 
                                processData: false, 
                                success: function(response) {
                                    window.location.href = baseurl + 'invoices/leads';
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                    console.log(error); // Log any errors
                                }
                            });
                        }
                        else{
                            $('#assignto-btn').prop('disabled', false);
                        }
                    });   
            } else {
                Swal.fire({
                    title: 'Input Required',
                    text: 'To generate a lead, please enter at least one value in either Email Contents, Image, or Product',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#assignto-btn').prop('disabled', false);
            }
        } 
        else {
            
            $('.page-header-data-section').css('display','block');
            $('#assignto-btn').prop('disabled', false);
        }
    });


});

document.addEventListener("DOMContentLoaded", function() {
        var customerTypeRadios = document.querySelectorAll('input[name="customerType"]');
        var customerLabel = document.getElementById('customerLabel');

        customerTypeRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                $(".customer_name").removeAttr("id");
                $(".customer-search-result").removeAttr("id");
                $('.customer_name').val("");
                $('#customer_phone').val("");
                $('#customer_email').val("");
                $('#customer_id').val("");
                $('#customer_address').val("");
                if (this.value === 'new') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    $('.customer_name').attr('placeholder', '<?php echo $this->lang->line("customer_name"); ?>');                    
                }
                else if (this.value === 'guest') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    $('.customer_name').attr('placeholder', '<?php echo $this->lang->line("customer_name"); ?>');
                    
                } else {
                    customerLabel.textContent = "<?php echo $this->lang->line('Search Customer'); ?>";
                    $(".customer_name").attr("id","customer-search");
                    $(".customer-search-result").attr("id","customer-search-result");
                    $('.customer_name').attr('placeholder', '<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>');
                }
            });
        });
    });


$("#customer-search").keyup(function() {
    $.ajax({
        type: "GET",
        url: baseurl + 'search_products/customersearch',
        data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
        beforeSend: function() {
            $("#customer-search").css("background", "#FFF url(" + baseurl +
                "assets/custom/load-ring.gif) no-repeat 165px");
        },
        success: function(data) {
            $("#customer-search-result").show();
            $("#customer-search-result").html(data);
            $("#customer-search").css("background", "none");

        }
    });
});

function selectedCustomer(cid, cname, cadd2, ph, email) {
    $('#customer-search').val(cname);
    $('#customer_phone').val(ph);
    $('#customer_email').val(email);
    // $('#customer_address').val(cadd1);
     $('#customer_id').val(cid);
    $("#customer-search-result").hide();
    $.ajax({
        type: 'POST',
        url: baseurl + 'customers/customer_details_byid',
        data: {
            'cid': cid
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'Success') {
                $('#customer_address').val(response.data);
            } else {
                console.error('Failed to get customer details');
            }
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
}

function delete_attachment(id,img_name) {

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
                    url: baseurl + 'Invoices/deletesubItem',
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
// $("#assignedto").on('change', function() {
//     if ($("#assignedto").val() !== "") {
//         $("#enquiry_status").val("Assigned");
//     } else {
//         $("#enquiry_status").val("Open");
//     }
// });
// $('#enquiry_status').change(function() {
//     var status = $(this).val();
    
//     if (status === 'Closed') {
//         $('.closingreason').removeClass('d-none');
//         $('#comments').prop('required', true);
//     }
//     else if(status === 'Assigned'){
//         $('#generateleadeditbtn').removeClass('d-none');
//     }
    
//      else {
//         $('.closingreason').addClass('d-none');
//         $('#comments').prop('required', false);
//     }
// });

$("#assignedto").on('change', function() {
    if ($("#assignedto").val() !== "") {
        // Set the "Assigned" radio button as checked
        $("#enquiry_status2").prop('checked', true);
    } else {
        // Set the "Open" radio button as checked
        $("#enquiry_status1").prop('checked', true);
    }
});

$("#revert-btn").on('click', function(e){
    e.preventDefault();
    Swal.fire({
    title: "Are you Sure ?",
    "text":"Do yo want to revert this lead from assigned employee now?",
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
            $.ajax({
                type: 'POST',
                url: baseurl + 'quote/lead_reassigned',
                data: {
                    "leadid" : $("#lead_id").val(),
                    "assigned_val" : $("#assignedto").attr("data-original-label")
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = baseurl + 'invoices/leads';
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});
$("#lead-accept-btn").on('click', function(e){
    e.preventDefault();
    Swal.fire({
    title: "Are you Sure ?",
    "text":"Do yo want to accept this lead now?",
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
            $.ajax({
                type: 'POST',
                url: baseurl + 'quote/lead_accept',
                data: {
                    "leadid" : $("#lead_id").val()
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

$('#complete-proccess-btn').on('click', function(e) {
    e.preventDefault();
    if ($("#data_form").valid()) {
        $('#complete-proccess-btn').prop('disabled', true);
        var emailContents = $('#email_contents').val(); 
        if(emailContents=="<p><br></p>"){
            emailContents="";
        }
        // Check if any product_name[] input has a value
        var productNameFilled = false;
        var imageFilled = false;
        $("input[name='product_name[]']").each(function() {
            if ($(this).val()) {
                productNameFilled = true;
                return false; // Exit loop early if we find a filled product name
            }
        });
        $("input[name='upfile[]']").each(function() {
            if ($(this).val()) {
                imageFilled = true;
                return false; // Exit loop early if we find a filled product name
            }
        });

        var imgcontains = <?=$imgcontains?>;
        var enquiry_status = $("#enquiry_status").val();
        var msg="";
        if(enquiry_status == "Open")
        {
            msg = "Please Assign the Customer Enquiry to an Employee for Further Action.";
        }
        if ((emailContents && emailContents.trim() !== '') || productNameFilled || imageFilled || imgcontains == 1) {
            var form = $('#data_form')[0];
            var formData = new FormData(form);
            formData.append('changedFields', JSON.stringify(changedFields));
            Swal.fire({
                title: "Are you sure?",
                "text": " Have you completed all processes for the lead? Converting to a quote is not happening now.",
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
                            url: baseurl + 'Invoices/customerenquiry_complete_action',
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false, 
                            success: function(response) {
                                window.location.href = baseurl + 'invoices/leads';
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    }
                    else{
                        $('#complete-proccess-btn').prop('disabled', false);
                    }
                });   
        } else {
            Swal.fire({
                title: 'Input Required',
                text: 'To generate a lead, please enter at least one value in either Email Contents, Image, or Product',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#complete-proccess-btn').prop('disabled', false);
        }
    } else {        
         $('.page-header-data-section').css('display','block');
        $('#complete-proccess-btn').prop('disabled', false);
    }
});

$('#convert-to-quote-btn').on('click', function(e) {
    e.preventDefault();
    if ($("#data_form").valid()) 
    {
        $('#convert-to-quote-btn').prop('disabled', true);
        var emailContents = $('#email_contents').val(); 
        if(emailContents=="<p><br></p>"){
            emailContents="";
        }
        // Check if any product_name[] input has a value
        var productNameFilled = false;
        var imageFilled = false;
        $("input[name='product_name[]']").each(function() {
            if ($(this).val()) {
                productNameFilled = true;
                return false; // Exit loop early if we find a filled product name
            }
        });
        $("input[name='upfile[]']").each(function() {
            if ($(this).val()) {
                imageFilled = true;
                return false; // Exit loop early if we find a filled product name
            }
        });

        var imgcontains = <?=$imgcontains?>;
        var enquiry_status = $("#enquiry_status").val();
        var msg="";
        if(enquiry_status == "Open")
        {
            msg = "Please Assign the Customer Enquiry to an Employee for Further Action.";
        }
        if ((emailContents && emailContents.trim() !== '') || productNameFilled || imageFilled || imgcontains == 1) {
            var form = $('#data_form')[0];
            var formData = new FormData(form);
            Swal.fire({
                title: "Are you sure?",
                "text": "Have you completed all processes for the lead? Converting to a quote is happening now.",
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
                            url: baseurl + 'Invoices/customerenquiry_to_quote_action',
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false, 
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response.trim());
                                }
                                quoteid = response.data;
                                window.location.href = baseurl + 'quote/create?id='+quoteid;
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    }
                    else{
                        $('#convert-to-quote-btn').prop('disabled', false);
                    }
                });   
        } else {
            Swal.fire({
                title: 'Input Required',
                text: 'To generate a lead, please enter at least one value in either Email Contents, Image, or Product',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#convert-to-quote-btn').prop('disabled', false);
        }
    } 
    else {
        $('.page-header-data-section').css('display','block');
        $('#convert-to-quote-btn').prop('disabled', false);
    }
});


$('#save_as_draft_btn').on('click', function(e) {
    e.preventDefault(); 
    if (!$(".customer_name").valid()) {
        $(".customer_name").focus();
        $('#save_as_draft_btn').prop('disabled', false); 
        $('html, body').animate({
            scrollTop: $(".customer_name").offset().top - 200
        }, 500);
        return;
    }
    var form = $('#data_form')[0];
    var formData = new FormData(form); 

    $.ajax({
        url: baseurl + 'Invoices/customerenquiry_draft_action', // Replace with your server endpoint
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {
        if (typeof response === "string") {
            response = JSON.parse(response.trim());
        }
        var enqid = response.data;
        window.location.href = baseurl + 'invoices/customer_leads?id='+enqid;
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
            console.log(error); // Log any errors
        }
    });
});

$('#save_as_draft_btn_edit').on('click', function(e) {
    e.preventDefault(); 
    var form = $('#data_form')[0];
    var formData = new FormData(form);    
    formData.append('changedFields', JSON.stringify(changedFields));
    if (!$(".customer_name").valid()) {
        $(".customer_name").focus();
        $('#save_as_draft_btn_edit').prop('disabled', false); 
        $('html, body').animate({
            scrollTop: $(".customer_name").offset().top - 200
        }, 500);
        return;
    }

    $.ajax({
        url: baseurl + 'Invoices/customerenquiry_draft_edit_action', 
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {       
        location.reload();
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
            console.log(error); // Log any errors
        }
    });
});

</script>