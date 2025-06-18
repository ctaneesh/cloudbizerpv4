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
         $so_prefix_number = ($invoice['salesorder_number'] && $invoice['salesorder_date']) ? $invoice['salesorder_number'] : "Add New";
         $salesorder_number = ($invoice['salesorder_number']) ? $invoice['salesorder_number'] : "Add New";
         $convert_hide_class="";
         $convert_disable_class="";
         $item_no_width = "6%";
         $discount_width = "11%";
         $function_number ="";
         if($action_type){  $function_number = $invoice['salesorder_number']; }
         // $trackingdata['sales_count']
         if($token==3)
         { 
            $item_no_width = "11%";
            $discount_width = "12%";
            $convert_hide_class="d-none";
            $convert_disable_class="disable-class";
            $second_url = '<li class="breadcrumb-item"><a href="'.base_url('SalesOrders').'">Sales Orders</a></li>';
            $last_url = '<li class="breadcrumb-item active" aria-current="page">'.$so_prefix_number.'</li>';            
            $prefixs = get_prefix_72();
            $suffix = $prefixs['suffix'];
            if (!empty($trackingdata) && $trackingdata['sales_count'] > 1) {
               if ($trackingdata['quote_number']) {
                   $sales_number = remove_after_last_dash($trackingdata['salesorder_number']);
                   $second_url .= '<li class="breadcrumb-item"><a href="' . base_url('SalesOrders/salesorder_new?id=' . $trackingdata['quote_number'] . '&token=1') . '">' . $sales_number . '-'.$suffix.'</a></li>';
               }
           }
           
         }
         else{
            $convert_hide_class="";
            $convert_disable_class="";
            $second_url = '<li class="breadcrumb-item"><a href="'.base_url('SalesOrders').'">Sales Orders</a></li>';
            $last_url = '<li class="breadcrumb-item active" aria-current="page">'.$so_prefix_number.'</li>';
            // $last_url = '<li class="breadcrumb-item active" aria-current="page">'.$this->lang->line('Quote#')."# ".($trackingdata['quote_number'])." ".$this->lang->line('to Sales Order Transfer').'# '.$so_prefix_number.'</li>';
         }  
      ?>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
               <?php echo $second_url.$last_url; ?>

               
            </ol>
      </nav>
      
      <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
               <h4 class="card-title"><?php echo $so_prefix_number;?></h4>
            </div>
            <div class="col-xl-7 col-lg-9 col-md-6 col-sm-12 col-xs-12">  
                  <ul id="trackingbar">
                     <?php 

                     if (!empty($trackingdata)) {  
                        if (!empty($trackingdata['lead_id'])) { 
                           echo '<li><a href="' . base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) . '">' . $trackingdata['lead_number'] . '</a></li>';
                        } 
                        if (!empty($trackingdata['quote_number'])) { 
                              echo '<li><a href="' . base_url('quote/create?id=' . $trackingdata['quote_number']) . '">' . $trackingdata['quote_number'] . '</a></li>';
                        }
                        if (!empty($trackingdata['salesorder_number']) && !empty($invoice['salesorder_date'])) { 
                           echo '<li class="active">' . $invoice['salesorder_number'] . '</li>';
                           
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
            <div class="col-lg-2 col-md-3 col-sm-12 current-status">
               <?php
                  $draft_disable = "";
                  $messagetext = "";
                  if($invoice['salesorder_number'])
                  {  
                     switch (true) {
                        case ($invoice['salesorders_status']== "draft" && $invoice['converted_status']== "4"):
                           $statustext = "Draft";
                           $alertcls = "alert-secondary";
                           $messagetext = "Data Saved As Draft";                           
                           break;

                           case ($invoice['converted_status']== "3" && $invoice['salesorders_status']!= "deleted"):
                              $statustext = "Assigned";
                              $messagetext = "Assigned to ".$invoice['warehouse'];
                              $alertcls = "alert-assigned";
                              $acceptsendbtncls ="d-none";
                              $draft_disable = "disable-class";
                           break;

                           case ($invoice['converted_status']== "1" && $invoice['salesorders_status']!= "deleted"):
                              $statustext = "Converted";
                              $messagetext = "The sales order has been completely converted to a delivery note.";
                              $alertcls = "alert-success";
                              $acceptsendbtncls ="d-none";
                              $draft_disable = "disable-class";
                           break;

                           case ($invoice['converted_status']== "2" && $invoice['salesorders_status']!= "deleted"):
                              $statustext = "Partialy Converted";
                              $messagetext = "The sales order has been partialy converted to a delivery note.";
                              $alertcls = "alert-partialconvert";
                              $acceptsendbtncls ="d-none";
                              $draft_disable = "disable-class";
                           break;

                           case ($invoice['converted_status']== "4" && $invoice['salesorders_status']!= "deleted"):
                              $statustext = "Draft";
                              $messagetext = "Data Saved As Draft";
                              $alertcls = "alert-secondary";
                              $acceptsendbtncls ="d-none";
                           break;
                           
                           case ($invoice['salesorders_status']== "pending"):
                              $statustext = "Created";
                              $messagetext = "Now you can convert to delivery note or invoice";
                              $alertcls = "alert-partial";
                              $acceptsendbtncls ="d-none";
                           break;
                           case ($invoice['salesorders_status']== "invoiced"):
                              $statustext = "Invoiced";
                              $messagetext = "";
                              $alertcls = "alert-success";
                              $acceptsendbtncls ="d-none";
                           break;

                     
                        default:
                           // No action needed for the default case
                           $alertcls = "";
                           $messagetext = "";
                           $statustext = "";
                           break;
                     }  
                  }
                  
                  if($statustext)
                  {
                     echo '<div class="btn-group alert text-center statustext-class '.$alertcls.'" role="alert">'.$statustext.'</div>';
                  } 
                 
               ?>
            </div>
      </div>
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
      <?php
    
         if($action_type)
            {
               if ($invoice['converted_status'] == "1" || $invoice['converted_status'] == "5"){ ?>
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
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
      </div>
   </div>
    <?php
        if($action_type)
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
                            <input type="hidden" name="module_number" id="module_number" value="<?=$module_number?>">
                            <input type="hidden" name="function_number" id="function_number" value="<?=$function_number?>">
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
        } ?>
   <div class="card-content">
      <div id="notify" class="alert alert-success" style="display:none;">
         <a href="#" class="close" data-dismiss="alert">&times;</a>
         <div class="message"></div>
      </div>
      <div class="card-body">
         <form method="post" id="data_form">

            <?php
                $invoiceduedate = (!empty($invoice['invoiceduedate']) && $invoice['invoiceduedate'] != '0000-00-00') 
                ? $invoice['invoiceduedate'] 
                : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['salesorder_validity'] . " days"));
                $headerclass= "d-none";
                $employee_id = $created_employee['id']; 
                $pageclass= "page-header-data-section-dblock";
                if($action_type)
                {
                    $headerclass = "page-header-data-section-dblock";
                    $pageclass   = "page-header-data-section";
                }
                $customer_id = $enquirymain['customer_id'];
            ?>
              <!-- ===================== Status Starts ==================== -->
               <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-12 text-center"></div>
                  <div class="col-lg-6 col-md-6 col-sm-12 text-center messagetext_class">
                     <!-- ========================================= -->
                     <?php      
                     if($token!=3)
                     {
                        echo '<div class="btn-group ml-1 mt-1 creditlimit-check '.$creditlimit_class.'"></div>';
                     }
                     if(($messagetext)){
                     ?>    
                        <div class="btn-group alert alert-success text-center <?=$msgcls?> message" role="alert">
                           <?php echo $messagetext; ?>
                        </div>
                     <?php } ?>
                     <!-- ========================================= -->
                  </div>
                   <div class="col-lg-3 col-md-3 col-sm-12 text-right">
                    <?php 
                     if($related_salesorders)
                     {
                        echo '<div class="dropdown d-inline-block">';
                        echo '<button type="button" class="btn btn-sm btn-primary" data-toggle="dropdown">';
                        echo 'Related Salesorders <span class="badge badge-primary">'.count($related_salesorders).'</span>';
                        echo '</button>';
                        echo '<div class="dropdown-menu dropdown-menu-right">';
                        
                        foreach($related_salesorders as $related_salesorder)
                        {
                           $related_order = $related_salesorder['salesorder_number'];
                           echo '<a class="dropdown-item text-dark related-item-size" href="'.base_url("SalesOrders/salesorder_new?id=$related_order&token=2").'">';
                           echo $related_order;
                           echo '</a>';
                        }
                        
                        echo '</div>';
                        echo '</div>';
                     }
                     ?>

                  </div>
               </div>
               <!-- ===================== Status Ends ======================= -->
               <!-- <?=$headerclass?>" data-target=".page-header-data-section" -->
               <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                  <div class="col-lg-12">
                        <div class="row">
                           <div class="row col-lg-3 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                              <h3  class="title-sub"><?php echo $this->lang->line('Sales Order & Customer Details') ?> <i class="fa fa-angle-down"></i></h3>
                           </div>
                           <div class="col-lg-9 col-md-12 quickview-scroll col-sm-12 col-xs-12 text-right order-1 order-lg-2">
                              <div class="quick-view-section">
                                 <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Customer') ?></h4>
                                    <?php
                                        echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($invoice['name']) . "</b></a>";
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
                                    <?php echo "<p>".dateformat($invoice['salesorder_date'])."</p>"; ?>
                                 </div>
                                 <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Due Date') ?></h4>
                                    <?php echo "<p style='color:".$colorcode."'>".dateformat($invoice['due_date'])."</p>"; ?>
                                 </div>
                                 <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Created By') ?></h4>
                                    <?php 
                                       echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                    ?>
                                 </div>
                                 <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Total'); ?></h4>
                                    <?php echo "<p>".number_format($invoice['total'],2)."</p>";?>
                                 </div>
                              </div>
                           </div>
                        </div>
                  </div>
               </div>

               
               <div class="<?=$pageclass?>">        
                  <div class="row">       
                     <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                        <div id="customerpanel" class="inner-cmp-pnl">
                           <h3 class="title-sub"><?php echo $this->lang->line('Client Details') ?></h3>
                           <hr>
                           <?php
                            $customer_search_section = ($customer) ? "d-none" : "";
                           ?>
                           <div class="form-group frmSearch customer-search-section <?=$customer_search_section?>">
                              <!-- <label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></label> -->
                              <label for="cst" class="col-form-label d-flex justify-content-between align-items-center" id="customerLabel">
                                    <span><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></span>
                                    <input type="button" value="Add New Customer" class="btn btn-sm btn-secondary add_customer_btn" autocomplete="off" title="Add New Customer">
                              </label>
                              <input type="text" class="form-control" title="Customer Search" name="cst" id="customer-box" placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>"  autocomplete="off"/>
                                 <div id="customer-box-result"></div>
                           </div>
                           
                           <div  id="customer">
                              
                                 
                                 <?php 
                                    if($customer)
                                    {
                                       echo '<div class="existingcustomer_details">';
                                       echo '<div class="clientinfo">';
                                       echo '<div id="customer_name"><strong>' . $customer['name'] . '</strong>';
                                       if(empty($invoice['quote_number']))
                                       {
                                           echo '<button type="button" class="btn btn-sm btn-secondary ml-1 searchsectionedit">'.$this->lang->line("Customer Edit").'</button><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectioncancel d-none">'.$this->lang->line("Customer Cancel").'</button>';
                                       }
                                       echo '</div></div></div>';
                                       echo '<div class="clientinfo">';
                                       echo '  <input type="hidden" name="customer_id" id="customer_id" value="' . $customer['customer_id'] . '">
                                      
                                       </div>
                                       <div class="clientinfo">                              
                                          <div id="customer_address1"><strong>' . $customer['address'] . '<br>' . $customer['city'] . ',' . $customer['shipping_country'] . '</strong></div>
                                       </div>
                                       
                                       <div class="clientinfo">                              
                                          <div type="text" id="customer_phone">Phone: <strong>' . $customer['phone'] . '</strong><br>Email: <strong>' . $customer['email'] . '</strong></div>
                                       </div>
                                       <div class="clientinfo creditsection">                              
                                          <div type="text" id="customer_phone">Company Credit Limit &nbsp;: <strong>' . number_format($customer['credit_limit'],2) . '</strong><br>Credit Period &nbsp;: <strong>' . $customer['credit_period'] . '(Days)</strong><br><br><strong>Available Credit Limit&nbsp;: ' . number_format($customer['avalable_credit_limit'],2) . '</strong>
                                          <input type="hidden" id="available_credit" value="'.number_format($customer['avalable_credit_limit'],2).'"><input type="hidden" id="avalable_credit_limit" value="'.number_format($customer['avalable_credit_limit'],2).'"></div>
                                       </div>';

                                    }
                                    else
                                    {
                                       ?>
                                       <div class="clientinfo mt-2">  
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
                                    }
                                    ?>
                                 <hr>
                                 <div id="customer_pass"></div>
                                 
                              </div>
                        </div>
                     </div>
                     <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-xs-12 cmp-pnl">     
                        <?php

                        // echo $invoice['salesorders_status'];
                           $disableclass = ($invoice['salesorders_status']=='deleted' || $invoice['salesorders_status']=='invoiced') ? 'disable-class' : '';
                           $saleorder_number = ($invoice['salesorder_number']) ? $invoice['salesorder_number'] : $invoice['tid'];
                        ?>
                        <div class="inner-cmp-pnl">
                           <div class="form-row">
                              <div class="col-sm-12">
                                 <h3 class="title-sub"><?php echo $this->lang->line('Sales Order Properties') ?></h3><hr>
                              </div>
                              <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="invocieno" class="col-form-label">Sales Order Number</label>
                                 <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-file-text-o"
                                       aria-hidden="true"></span></div>                               
                                    <input type="text" class="form-control" placeholder="Sales Order #" name="so_prefix_number" id="so_prefix_number" value="<?php echo $salesorder_number; ?>" readonly>
                                    <input type="text" class="form-control" placeholder="Sales Order #" name="salesorder_date" id="salesorder_date" value="<?php echo $invoice['salesorder_date']; ?>" readonly>
                                 </div>
                              </div>
                              <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <input type="hidden" name="action_type" id="action_type" value="<?=$action_type?>">
                                 <input type="hidden" name="salesorders_status" id="salesorders_status" value="<?=$invoice['salesorders_status']?>">
                                 <input type="hidden" name="tokenid" id="tokenid" value="<?=$token?>">
                                 <input type="hidden" class="form-control" placeholder="Sales Order #" name="salesorder_id" id="salesorder_id" value="<?php echo $id; ?>" readonly>
                                 <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference');?><span class="compulsoryfld">*</span></label>
                                 
                                    <input type="text" class="form-control"
                                       placeholder="<?php echo $this->lang->line('Reference')?>" name="refer" id="refer"
                                       value="<?php echo $invoice['reference'] ?>" data-original-value="<?php echo $invoice['reference']; ?>" title="<?php echo $this->lang->line('Reference')?>">
                              </div>
                              <?php
                              
                                 $customer_purchase_order = "";
                                 $customer_order_date = "";
                                 $proposal="";
                                 if(!empty($invoice['customer_purchase_order']) && !empty($invoice['customer_order_date']))
                                 {
                                    $invoiceduedate = $invoice['due_date'];
                                    $customer_purchase_order = $invoice['customer_purchase_order'];
                                    $customer_order_date = $invoice['customer_order_date'];
                                    $proposal = $invoice['customer_message'];
                                 }
                              ?>
                              <!--erp2024 newly added 29-09-2024  -->
                           
                              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="invocieduedate" class="col-form-label"><?php echo  $this->lang->line('Delivery Deadline'); ?> <span class="compulsoryfld">*</span></label>
                                    <input type="date" class="form-control required" name="invocieduedate" id="invocieduedate"  placeholder="Validity Date" autocomplete="false"  value="<?=$invoiceduedate?>"  data-original-value="<?php echo $invoiceduedate; ?>" title="<?php echo $this->lang->line('Delivery Deadline')?>">
                              </div>
                              
                             
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                    <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number" value="<?php echo $invoice['customer_reference_number'] ?>" data-original-value="<?php echo $invoice['customer_reference_number']; ?>" title="<?php echo $this->lang->line('Customer Reference Number')?>">
                                    </div>                                    
                              </div>
                              <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="frmclasss"><label for="customer_contact_person" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                    <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person" value="<?php echo $invoice['customer_contact_person'] ?>"  data-original-value="<?php echo $invoice['customer_contact_person']; ?>" title="<?php echo $this->lang->line('Customer Contact Person')?>">
                                    </div>                                    
                              </div>
                           

                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="s_warehouses1" class="col-form-label"><?php echo $this->lang->line('Sale Point') ?></label>
                                 <!-- <select id="s_warehouses" class="form-control"> -->
                                 <?php //echo $this->common->default_warehouse();
                                    // echo '<option value="0">' . $this->lang->line('Select Warehouse') ?></option>
                                    <?php //foreach ($warehouse as $row) {
                                    //echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                    //} ?>
                                 <!-- </select> -->
                              </div>

                              <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                    <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Contact Person Number" value="<?php echo $invoice['customer_contact_number'] ?>" data-original-value="<?php echo $invoice['customer_contact_number']; ?>" title="<?php echo $this->lang->line('Contact Person Number')?>">
                                    </div>                                    
                              </div>
                              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                    <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email" value="<?php echo $invoice['customer_contact_email'] ?>" data-original-value="<?php echo $invoice['customer_contact_email']; ?>" title="<?php echo $this->lang->line('Customer Contact Email')?>">
                                    </div>                                    
                              </div>
                              <!--erp2024 newly added 29-09-2024 ends -->
                              <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="Purchase Order" class="col-form-label"><?php echo $this->lang->line('Purchase Order')." No.";?> <span class="compulsoryfld"> *</span></label>
                                 <!-- <label for="Purchase Order" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." No.";?> <span class="compulsoryfld"> *</span></label> -->
                                    <input type="text" class="form-control required" placeholder="<?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order');?>" name="customer_purchase_order" id="customer_purchase_order" value="<?=$customer_purchase_order?>" data-original-value="<?php echo $customer_purchase_order; ?>" title="<?php echo $this->lang->line('Purchase Order')?>">
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." ".$this->lang->line('Date');?><span class="compulsoryfld"> *</span></label>                           
                                 <!-- <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Purchase Order')." ".$this->lang->line('Date');?><span class="compulsoryfld"> *</span></label>                            -->
                                    <input type="date" class="form-control required" name="customer_order_date" id="customer_order_date" placeholder="Order Date" autocomplete="false" value="<?=$customer_order_date?>" data-original-value="<?php echo $customer_order_date; ?>" title="<?php echo 'Purchase Order Date'?>" >
                              </div>
                              
                              
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="invociedate" class="col-form-label">Sales Order Date</label>
                                 <div class="input-group">
                                    <div class="input-group-addon"><span class="icon-calendar4"
                                       aria-hidden="true"></span></div>                                
                                 </div>
                              </div>
                                 <input type="hidden" class="form-control required" placeholder="Billing Date" name="invoicedate" id="invoicedate"  autocomplete="false" min="<?php echo date("Y-m-d"); ?>"  value="<?php echo date("Y-m-d"); ?>" >
                                    <input type="hidden" name="iid" value="<?php echo $invoice['iid']; ?>">
                                    <input type="hidden" name="quote_number" id="quote_number" value="<?php echo $invoice['quote_number']; ?>">
                              
                              
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="taxformat" class="col-form-label">Tax</label>
                                 <select class="form-control" onchange="changeTaxFormat(this.value)"
                                    id="taxformat">
                                 <?php echo $taxlist; ?>
                                 </select>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="discountFormat" class="col-form-label">Discount</label>
                                    <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                       id="discountFormat">
                                    <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                    <?php echo $this->common->disclist() ?>
                                    </select>
                              </div>
                              

                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                 <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Sales Order Note') ?></label>
                                 <textarea class="form-textarea" name="notes" id="salenote" data-original-value="<?php echo $invoice['notes']; ?>" title="<?php echo $this->lang->line('Sales Order Note')?>"><?php echo $invoice['notes'] ?></textarea>
                              </div>
                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                 <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?> </label>
                                 
                                 <textarea class="form-textarea" name="propos" id="contents" rows="2" data-original-value="<?php echo $invoice['customer_message']; ?>" title="<?php echo $this->lang->line('Customer Message')?>"><?php echo $invoice['customer_message'] ?></textarea>
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
                              
                              <!-- ========image display starts ------------ -->
                              <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-xs-12 mb-1">
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
                                                    echo '<br>';
                                                    echo "<a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary'>{$icon}</a>&nbsp;";
                                                    echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"deleteitem('{$image['id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
                                                    echo "</div>";
                                                    echo "";
                                                    echo "</td>";
                                                   //  echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"deleteFiles('{$image['id']}','{$image['file_name']}','Salesorder','{$invoice['iid']}')\" type='button'><i class='fa fa-trash'></i></button>";
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
                              <!-- ========image display ENDS ------------ -->


                              
                           </div>  
                           
                           
                        
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                     <?php 
                         if($last_approval_step==2)
                        {
                              $approval_complete_class = "";
                              $deliverynote_title = $this->lang->line("Convert To Delivery Note");
                              $invoice_label_title = $this->lang->line("Convert to Invoice");
                        }
                        else{
                              $approval_complete_class = 'disabled';
                              $deliverynote_title =  $this->lang->line("All approvals are not completed");
                              $invoice_label_title =  $this->lang->line("All approvals are not completed");
                        }
                        if($action_type)
                        {
                           
                           $convertion_label = ($invoice['salesorders_status']=='draft' || empty($invoice['salesorder_date']))?$this->lang->line("Create"):$this->lang->line("Update");
                           $unsavedisable_btns = "unsavedisable-btns";
                           $invoice_label = $this->lang->line("Convert to Invoice");
                           $deliverynote_label = $this->lang->line("Convert To Delivery Note");
                           // $deliverynote_title = $this->lang->line("Convert To Delivery Note");
                           // $invoice_label_title="Convert to Invoice";
                           $writeoffclass = ($invoice['converted_status']== "1") ? "disable-class":"";
                           ?>
                           <div class="col-lg-1 d-none">
                              <button class="btn btn-crud btn-sm btn-primary mt-2 <?=$disableclass?> <?=$writeoffclass?>" type="button" name="writeoff_Btn" id="writeoff_Btn"><i class="fa fa-refresh"></i> <?php echo $this->lang->line('Write Off'); ?></button>
                           </div>
                           <div class="col-lg-11">    
                           <?php
                        }
                        else
                        {
                           $unsavedisable_btns="";
                           $convertion_label = $this->lang->line("Create");
                           $invoice_label = $this->lang->line("Create & Convert to Invoice");
                           $deliverynote_label = $this->lang->line("Create & Convert To Delivery Note");
                           $deliverynote_title = "Create Sales Order & Convert To Delivery Note";
                           $invoice_label_title = "Create Sales Order & Convert To Invoice";
                           ?>
                           <div class="col-lg-12">
                           <?php
                        } ?>
                                             
                        
                     </div>
               </div>
               <?php 
               if($token==3)
               { ?>
               <div class="col-12 form-row mt-1 discount-toggle">
                     <div class="form-check" >
                        <input class="form-check-input discountshowhide" type="checkbox" value="2"  name="discountshowhide" id="discountshowhide">
                        <label class="form-check-label dicount-checkbox" for="discountshowhide">
                        <b><?php echo $this->lang->line('Would you like to add a discount for these products?'); ?></b>
                        </label>
                     </div>
               </div>
               <?php } ?>
               <input type="hidden" name="discount_flg" class="discount_flg" value="0">                  
               <input type="hidden" class="form-control deleted_item" name="deleted_item">
               <div id="saman-row" class="overflow-auto1">
                  <div class="col-12 form-row mt-1 <?=$convert_hide_class?>">
                     <div class="form-check" >
                        <input class="form-check-input" type="checkbox" value="2" id="discountchecked" name="discountchecked">
                        <label class="form-check-label" for="discountchecked" style="font-size:14px;color:#404E67;">
                           <b><?php echo $this->lang->line('Do you want to modify the prices or discounts for the items below'); ?></b>
                        </label>
                     </div>
                  </div>
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                        <?php 
                           if($token==3 && ($action_type))
                           { ?>
                              <th width="4%" class="text-center"><?php echo $this->lang->line('SN'); ?>&nbsp;<span><input type="checkbox"  id="prdcheckbox" name="prdcheckbox" class="d-none"></span></th>
                           <?php }
                              else{
                                 echo '<th width="4%" class="text-center">'.$this->lang->line('SN').'</th>';
                              }
                           ?>
                           <th width="<?=$item_no_width?>" class="text-center1 pl-1">Item No.</th>
                           <th width="17%" class="text-center1 pl-1">Item Name</th>
                           <th width="5%" class="text-center">Curr. Price</th>
                           <th width="4%" class="text-center">Min. Price</th>
                           <th width="8%" class="text-center">Max dis(%)</th>
                           <?php 
                           if($token!=3)
                           { ?>
                              <th width="7%" class="text-center">Lead</th>
                              <th width="9%" class="text-center">Quote</th>
                              <th width="7%" class="text-center">Sales Order</th> 
                          <?php }
                              else
                              {
                                 echo '<th width="7%" class="text-center">Quantity</th>';
                              }
                          ?>
                          
                           <!-- <th width="8%" class="text-center">On Hand</th> -->
                           <th width="<?=$discount_width?>" class="text-center discountpotion1 discountcoloumn d-none">Discount/ Amount</th>                                          
               
                           <?php 
                           if($token!=3)
                           { ?>
                              <th width="6%" class="text-right">Quote Price</th>
                              <th width="4%" class="text-right">Unit Price</th>
                          <?php }
                          else{
                            echo '<th width="8%" class="text-center">On Hand</th>'; 
                          }
                          ?>

                           
                           <th width="9%" class="text-right">Total</th>
                           <?php 
                              // if($configurations['config_tax']!='0'){  ?>
                                  <!-- <th width="10%" class="text-right"><?php echo $this->lang->line('Tax'); ?>(%) / <?php echo $this->lang->line('Amount'); ?></th>      -->
                           <?php // } ?>
                           <!-- <th width="10%" class="text-center">Discount Amt / Type</th> -->
                           <!-- <th width="10%" class="text-center">
                              Amount(<?php //echo $this->config->item('currency'); ?>)
                           </th> -->
                           <!-- <th width="5%" class="text-center1">Status</th> -->
                           <th width="50%" >Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php 
                           $i = 0;
                           $gandtax = 0;
                           $ganddiscount = 0;
                           $gandttotal = 0;
                           $totaldiscount = 0;
                           $subtotal = 0;   
                           $k=1;                             
                           $discount_flg=0;
                           if(($products) && $token!=3)    
                           {  
                           
                              //converted  sales order from quote                 
                              foreach ($products as $row) {        
                                 $product_name_with_code = $row['product'].'('.$row['code'].') - ';
                                 $productcode = $row['code'];
                                 if($row['discount']>0 && $discount_flg==0)
                                 {
                                    $discount_flg =1;
                                 }                     
                                 if($row['totalQty']<=$row['alert']){
                                    echo '<tr style="background:#ffb9c2;">';
                                 }
                                 else{
                                    echo '<tr >';
                                 }
                                 if(!empty($row['lead_id'])){
                                    $leadid = $row['lead_id']; 
                                    $leadurl =  '<a href="' . base_url("invoices/customer_leads?id=$leadid") . '"   title="Lead" >'.($row['lead_number']).'</a>';
                                    $leadQty = $row['leadqty'];
                                    $leadDate = date('d-m-Y', strtotime($row['leaddate']));
                                 }
                                 else{
                                    $leadurl ='';
                                    $leadQty = '--';
                                    $leadDate ='';
                                 }
                                 if(!empty($row['quote_number'])){
                                    $quote_number = $row['quote_number']; 
                                    $quoteurl =  '<a href="' . base_url("quote/create?id=$quote_number") . '"   title="Quote">'.($row['quote_number']).'</a>';
                                    $quoteQty = $row['quoteqty'];
                                    $quoteDate = date('d-m-Y', strtotime($row['quotedate']));
                                 }
                                 else{
                                    $quoteurl ='';
                                    $quoteQty = '--';
                                    $quoteDate ='';
                                 }
                                 if($row['discount_type']=='Perctype'){
                                    $percsel = "selected";
                                    $amtsel = "";
                                    $perccls = '';
                                    $amtcls = 'd-none';
                                    $disperc = amountFormat_general($row['discount']);
                                    $disamt = 0;
                                    $distype = "%";
                                 }
                                 else{
                                       $amtsel = "selected";
                                       $percsel = "";
                                       $perccls = 'd-none';
                                       $amtcls = '';
                                       $disamt = amountFormat_general($row['discount']);
                                       $disperc = 0;
                                       $distype = "Amt";
                                 }
                                 $unitcost = 0;
                                 $unitcost = (intval($row['quantity'])>0) ?round($row['total_amount'] / intval($row['quantity']), 2):0;
                                 //  $gandtax = $gandtax + amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc);
                                 //  $ganddiscount = $ganddiscount + ;
                                 //  $gandttotal = $gandttotal + amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc);
                                 //  echo '<td width="2%"><input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'"> </td>';
                                 echo "<td class='text-center serial-number'>".$k++."</td>";
                                 echo '<td><strong id="productlabel' . $i . '">'.$row['code'].'</strong><input type="hidden" class="form-control code" name="code[]" id="code-'.$i.'" value="' . $row['code'] . '" title="'.$product_name_with_code.'Code" data-original-value="' . $row['code'].'" data-product-code="'.$productcode.'"> </td>';
                                 echo '<td><strong id="productlabel' . $i . '">'.$row['product'].'</strong><input type="hidden" class="form-control" name="product_name[]" id="productname-'.$i.'" placeholder="'.$this->lang->line('Enter Product name').'"  value="' . $row['product'] . '" title="'.$product_name_with_code.'Code" data-original-value="' . $row['product'].'" data-product-code="'.$productcode.'"> </td>';
                                 
                                 
                                 
                                 echo '<td class="text-right"><strong >'.number_format($row['product_price'],2).'</strong></td>';

                                 echo '<td class="text-right"><strong >'.number_format($row['product_lowest_price'],2).'</strong></td>';

                                 // erp2024 27-03-2025 discount amount calcualation
                                 $maxdiscountamount=0;
                                 $productprice = amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc);
                                 $maxdiscountamount = round(($productprice * $row['product_max_discount']) / 100, 2);

                                 $row['max_disrate'] = (intval($row['max_disrate']) == floatval($row['max_disrate']))  ? intval($row['max_disrate']) : number_format($row['max_disrate'], 2);
                                 $discountamount = $row['product_max_discount']."% (".$maxdiscountamount.")";
                                 echo '<td class="text-center"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-'.$i.'" value="' . $maxdiscountamount . '"><strong id="maxdiscountratelabel-' . $i . '">' .$discountamount. '</strong></td>';  

                                 // echo '<td class="text-right"><strong >'.number_format($row['product_max_discount'],2).'</strong></td>';

                                 echo '<td class="text-center"><strong>'.intval($row['leadqty']).'</strong><br>'.$leadurl.'<br>'.$leadDate.'</td>';
                                 
                                 // echo '<td class="text-center"><strong id="leadqty">'.intval($row['leadqty']).'</strong></td>';

                                 //  echo '<td class="text-center"><strong id="orderedqty">'.intval($row['orderedqty']).'</strong><input type="hidden" class="form-control req" name="ordered_qty[]" id="orderedqty-' . $i . '" value="' .intval($row['orderedqty']) . '"></td>';

                                 echo '<td class="text-center"><strong>'.intval($row['quoteqty']).'</strong><br>'.$quoteurl.'<br>'.$quoteDate.'<input type="hidden" class="form-control req" name="ordered_qty[]" id="orderedqty-' . $i . '" value="' .intval($row['quoteqty']) . '"><input type="hidden" class="form-control req" name="remaining_qty[]" id="remainingqty-' . $i . '" value="' .intval($row['remainingqty']) . '"></td>';
                               
                                 echo '<td class="position-relative"><input data-product-code="'.$productcode.'" type="number" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '), rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . intval($row['quantity']) . '" title="'.$product_name_with_code.'Quantity" data-original-value="' . $row['quantity'].'"><input type="hidden" name="old_product_qty[]" value="' . intval($row['quantity']) . '" >';
                                 
                                 echo '<strong id="deliveredqty" class="d-none">'.intval($row['deliveredqty']).'</strong>';  

                                 //  echo '<strong id="remainingqty" class="d-none">'.intval($row['remainingqty']).'</strong><input type="hidden" class="form-control req" name="remaining_qty[]" id="remainingqty-' . $i . '" value="' .intval($row['remainingqty']) . '">';                              

                                 
                                 echo '<strong id="onhandQty-'.$i.'" class="d-none">'.intval($row['totalQty']).'</strong><div class="tooltip1"></div></td>';
                                 
                                 echo '<td class="text-center discountpotion d-none">
                                       <div class="input-group text-center">
                                          <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control" onchange="discounttypeChange(' . $i . ')"  data-original-value="' . $row['discount_type'].'" title="'.$product_name_with_code.'Type">
                                                <option value="Perctype" '.$percsel.'>%</option>
                                                <option value="Amttype" '.$amtsel.'>Amt</option>
                                          </select>&nbsp;
                                          <input type="number" min="0" class="form-control discount '.$perccls.'" data-product-code="'.$productcode.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '" data-original-value="' . $disperc.'" title="'.$product_name_with_code.'Amount">
                                          <input type="number" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '" data-original-value="' . $disamt.'"  title="'.$product_name_with_code.'Amount">
                                       </div>                                    
                                       <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">Amount : ' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                       <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                    </td>';

                                 echo '<td class="text-center discountpotionnotedit d-none">
                                 <div class="text-center"> ';
                                 echo '<strong id="discount_type_label-' . $i . '" >' .$distype . '</strong> / '; 
                                 
                                 if($percsel!=""){
                                                                  
                                    echo '<strong id="discount_typeval_label-' . $i . '" >' .$disperc . '</strong>';
                                 }
                                 else{
                                    echo '<strong id="discount_typeval_label-' . $i . '" >' .$disamt . '</strong>';
                                 }
                                 echo '</div>                                    
                                 <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">Amount : ' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                 <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                 </td>';

                                 //  echo '<td class="text-center"><strong><span id="discount-amtlabel-' . $i . '">'.$row['totaldiscount'].'</span><span id="discounttype1-' . $i . '">('.$row['discount']." ".$distype.')</span></strong><input type="hidden" class="form-control discount" name="discount_type[]" onkeypress="return isNumber(event)" id="discounttype-' . $i . '"value="' . $row['discount_type'] . '"><input type="hidden" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '"><input type="hidden" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '"> </td>';

                                 

                                 if($row['discount_type']=='Perctype'){
                                    $distype = '%';
                                    $percsel = "selected";
                                    $amtsel = "";
                                    $perccls = '';
                                    $amtcls = 'd-none';
                                    $disperc = amountFormat_general($row['discount']);
                                    $disamt = 0;
                                 }
                                 else{
                                       $distype = 'Amt';
                                       $amtsel = "selected";
                                       $percsel = "";
                                       $perccls = 'd-none';
                                       $amtcls = '';
                                       $disamt = amountFormat_general($row['discount']);
                                       $disperc = 0;
                                 }

                                 //echo '<strong class="d-none"><span id="discount-amtlabel-' . $i . '">'.$row['totaldiscount'].'</span><span id="discounttype1-' . $i . '">('.$row['discount']." ".$distype.')</span></strong><input type="hidden" class="form-control discount" name="discount_type[]" onkeypress="return isNumber(event)" id="discounttype-' . $i . '"value="' . $row['discount_type'] . '"><input type="hidden" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . $disperc . '"><input type="hidden" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">';

                                 // if($configurations['config_tax']!='0'){
                                 //    echo '<td class="text-right"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['tax']) . '"><span class="text-right" id="texttaxa-' . $i . '">' . amountExchange_s($row['tax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span> / <span class="text-right" id="texttaxa-' . $i . '">' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span></td>';
                                 // }
                                 echo '</td>';

                                 
                                 echo '<td class="text-right"><strong id="pricelabel' . $i . '" class="pricelabel">'.number_format($row['price'],2).'</strong><input type="hidden" class="form-control req prc " data-product-code="'.$productcode.'" name="product_price[]" id="price-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '" >';


                                 echo '<td class="text-right"><strong class="ttlText1" id="unitcost-' . $i . '">' . number_format($unitcost,2) .'</strong></td>';
                                 echo '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">' . number_format(amountExchange_s($row['total_amount'], $invoice['multi'], $this->aauth->get_user()->loc),2) . '</span></strong></td>';
                                 
                                 // echo '<td>'.ucfirst($row['status']).'</td>';

                                 echo '<td class="text-left d-flex"><button onclick="producthistory('.$i.')" type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button>&nbsp;<button type="button" data-rowid="' . $i . '" class="btn btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>
                                 </td>';
                                 // echo '&nbsp;<button type="button" data-rowid="' . $i . '" class="btn btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>';
                                 echo ' <input type="hidden" name="lowest_price[]" id="lowest_price-' . $i . '" value="' .$row['product_lowest_price']. '">';
                                 echo ' <input type="hidden" name="max_disrate[]" id="max_disrate-' . $i . '" value="' .$row['product_max_discount']. '">';

                                 echo '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                 <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                 <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                 <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . amountExchange_s($row['total_amount'], $invoice['multi'], $this->aauth->get_user()->loc) . '"> <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                                 <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $row['lowest_price'] . '">
                                 <input type="hidden" class="form-control" name="maxdiscountrate[]" id="maxdiscountrate-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $row['max_disrate'] . '"><input type="hidden" class="form-control" name="delivered_qty[]" id="deliveredqty-' . $i . '" value="' .intval($row['deliveredqty']) . '"><input type="hidden" class="form-control" name="transfered_qty[]" id="trasferedqty-' . $i . '" value="' .intval($row['trasferedqty']) . '">
                                 </tr>';
                                 $i++;
                                 $totaldiscount +=  amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc);
                                 $subtotal += amountExchange_s($row['total_amount'], $invoice['multi'], $this->aauth->get_user()->loc);
                              } 
                           }
                           else if($token==3)    
                           { 
                              //direct sales order  product_qty
                              if($products)
                              {
                                  $grandtotal = 0;
                                  $totaldiscount=0;
                                  $nettotal = 0;
                                  $completed_prdoduct_operations = 0;
                                  foreach ($products as $key => $row) {
                                    if($row['discount']>0 && $discount_flg==0)
                                    {
                                       $discount_flg =1;
                                    } 
                                      $productcode = $row['code'];
                                      $product_name_with_code = $row['product'].'('.$row['code'].') - ';
                                      $totaldiscount += $row['totaldiscount'];
                                      $subtotal += $row['total_amount'];
                                      $writeoff_qty = intval($row['write_off_quantity']);
                                         
                                      if($invoice['converted_status']=="1")
                                      {
                                       $quantity = intval($row['quantity']);
                                      }
                                      else{
                                       $quantity = intval($row['quantity'])-(intval($row['write_off_quantity']) +(intval($row['del_delivered_qty'])));
                                      }
                                            

                                      $grandtotal += intval($quantity)*$row['price'];
                                      if($row['prdstatus']==1){
                                          $completed_prdoduct_operations++;
                                          $chkbx = "";
                                          $writeoff_complete="readonly";
                                          $writeoff_class="disable-class";
                                          // $prdstatus1 = '<span class="st-Closed">Completed</span>';
                                       }
                                       else{
                                          $writeoff_complete="";
                                          $writeoff_class="";
                                          // $prdstatus1 = '<span class="st-partial">Not Completed</span>'; rowTotal
                                          $chkbx = '<input type="checkbox" class="checkedproducts d-none" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" >';
                                       }
                                     ?>
                                      <tr>
                                          <td class='text-center serial-number'><?php echo $k++." ".$chkbx; ?></td>
                                          <td><input type="text" placeholder="Item No." class="form-control code" name="code[]" id='code-<?=$i?>' value="<?=$row['code']?>" title="<?=$product_name_with_code?>Code" <?=$writeoff_complete?> data-original-value="<?=$row['code']?>" onkeyup="salesorder_edit_autocomplete('<?=$i?>')"  data-product-code="<?=$productcode?>"></td>
                                              <td><input type="text" class="form-control required" name="product_name[]" <?=$writeoff_complete?> required placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' title="<?=$product_name_with_code?>Product" value="<?=$row['product']?>"  data-original-value="<?=$row['product']?>" data-product-code="<?=$productcode?>" onkeyup="salesorder_edit_autocomplete('<?=$i?>')">
                                              </td>

                                              <td class="text-right">    
                                                  <strong id="pricelabel-<?=$i?>"><?=$row['price']?></strong>
                                                  <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>"onkeypress="return isNumber(event)" onkeyup="rowTotal(<?=$i?>), billUpyog(), orderdiscount()" autocomplete="off" value="<?=$row['price']?>"  data-product-code="<?=$productcode?>">
                                             </td>

                                              <td class="text-right">
                                                  <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-<?=$i?>" onkeypress="return isNumber(event)" autocomplete="off" value="<?=$row['lowest_price']?>">
                                                  <strong id="lowestpricelabel-<?=$i?>"><?=$row['lowest_price']?></strong>
                                              </td>
                                             <?php
                                                 // erp2024 27-03-2025 discount amount calcualation
                                                $maxdiscountamount=0;
                                                $productprice = amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc);
                                                $maxdiscountamount = round(($productprice * $row['max_disrate']) / 100, 2);
                                                $row['max_disrate'] = (intval($row['max_disrate']) == floatval($row['max_disrate']))  ? intval($row['max_disrate']) : number_format($row['max_disrate'], 2);
                                                $discountamount = $row['max_disrate']."% (".$maxdiscountamount.")";
                                                
                                             ?>
                                              <td class="text-center"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-<?=$i?>" value="<?=$maxdiscountamount?>"><strong id='maxdiscountratelabel-<?=$i?>'><?=$discountamount?></strong>
                                              <input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-<?=$i?>"  value="<?=$row['max_disrate']?>"></td>
                                              <td class="text-center position-relative"><input  data-product-code="<?=$productcode?>" type="text" class="form-control req amnt " name="product_qty[]" <?=$writeoff_complete?> id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal(<?=$i?>), billUpyog(),orderdiscount()"  autocomplete="off" value="<?=$quantity?>" title="<?=$product_name_with_code?>Quantity" data-original-value="<?=$quantity?>"><div class="tooltip1"></div></td>
                                             
                                              
                                              <?php //Verify that tax is enabled 0
                                              if($configurations['config_tax']!='0'){ ?>           
                                                      <td class="text-center">
                                                          <div class="text-center">                                                
                                                              <input type="hidden" class="form-control" name="product_tax[]" id="vat-<?=$i?>"
                                                                  onkeypress="return isNumber(event)" onkeyup="rowTotal(<?=$i?>), billUpyog(), orderdiscount()"
                                                                  autocomplete="off">
                                                                  <strong id="taxlabel-<?=$i?>"></strong>&nbsp;<strong  id="texttaxa-<?=$i?>"></strong>
                                                          </div>
                                                      </td>
                                              <?php } ?>
                                             
                                          
                                              <td class="text-center discountcoloumn d-none">
                                                  <div class="input-group text-center">
                                                      <select name="discount_type[]" id="discounttype-<?=$i?>" class="form-control <?=$writeoff_class?>" onchange="discounttypeChange(<?=$i?>),orderdiscount()" <?=$writeoff_complete?> data-original-value="<?=$row['discount_type']?>">
                                                          <option value="Perctype" <?php if($row['discount_type']=="Perctype"){ echo "selected"; } ?>>%</option>
                                                          <option value="Amttype" <?php if($row['discount_type']=="Amttype"){ echo "selected"; } ?>>Amt</option>
                                                      </select>&nbsp;
                                                      <input type="number" min="0" class="form-control discount"  name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange(<?=$i?>),orderdiscount()"  title="<?=$product_name_with_code?>Discount" value="<?=$row['discount']?>" <?=$writeoff_complete?> data-original-value="<?=$row['discount']?>"  data-product-code="<?=$productcode?>">
                                                      <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange(<?=$i?>),orderdiscount()"  title="<?=$product_name_with_code?>Discount" value="<?=$row['discount']?>" <?=$writeoff_complete?> data-original-value="<?=$row['discount']?>">
                                                  </div>  
                                                  <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel">Amount : <?=$row['totaldiscount']?>  </strong>
                                                  <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                              </td>

                                              <td class="text-center"><strong id="onhandQty-<?=$i?>"><?=$row['totalQty']?></strong></td>
                                             
                                          
                                              <td class="text-right">
                                                  <strong><span class='ttlText' id="result-<?=$i?>"><?=$row['total_amount']?></span></strong></td>
                                              <td class="d-flex">
                                                  <button onclick='producthistory(<?=$i?>)' type="button" class="btn btn-crud  btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                  <button onclick='single_product_details(<?=$i?>)' type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button>&nbsp;
                                                  <button type="button" data-rowid="<?=$i?>" class="btn btn-crud btn-sm btn-secondary <?=$disableclass?> removeProd <?=$writeoff_class?>" title="Remove"> <i class="fa fa-trash"></i> </button>
                                              </td>
                                              <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="0">
                                              <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?=$row['totaldiscount']?>">
                                              <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?=$row['subtotal']?>">
                                              <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$row['pid']?>">
                                              <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?=$row['unit']?>">
                                              <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$row['code']?>">
                                          </tr>
                                     <?php
                                     echo '<script>
                                     $(document).ready(function() {                                       
                                             var index = ' . $i . ';
                                             rowTotal(index);
                                             billUpyog();
                                     });
                                     </script>';
                                     $i++;
                                  }
                                  if($completed_prdoduct_operations == count($products))
                                  {
                                    $disableclass = 'disable-class';
                                  }
                              }
                              else
                              {
                              ?>
  
                                  <tr>
                                  <td class='text-center serial-number'>1</td>
                                  <td><input type="text" placeholder="Item No." class="form-control required code" name="code[]" required id='code-0'></td>
                                      <td><input type="text" class="form-control required" name="product_name[]" required
                                              placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                              id='productname-0'>
                                      </td>
                                      
                                      <td class="text-right">    
                                          <strong id="pricelabel-0"></strong>
                                          <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(), orderdiscount()" autocomplete="off">
                                       </td>
                                       <td class="text-right">
                                          <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                          <strong id="lowestpricelabel-0"></strong>
                                      </td>
                                      <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-0" value=""></td>
                                      

                                      <td class="text-center position-relative"><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(),orderdiscount()" autocomplete="off" value="0"><div class="tooltip1"></div></td>
                                     
                                      
                                      
                                      <?php //Verify that tax is enabled
                                      if($configurations['config_tax']!='0'){ ?>           
                                              <td class="text-center">
                                                  <div class="text-center">                                                
                                                      <input type="hidden" class="form-control" name="product_tax[]" id="vat-0"
                                                          onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(), orderdiscount()"
                                                          autocomplete="off">
                                                          <strong id="taxlabel-0"></strong>&nbsp;<strong  id="texttaxa-0"></strong>
                                                  </div>
                                              </td>
                                      <?php } ?>
                                      
  
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

                                      <td class="text-center"><strong id="onhandQty-0"></strong></td>
  
                                      <td class="text-right">
                                          <strong><span class='ttlText' id="result-0">0</span></strong></td>
                                      <td class="text-center1 d-flex">
                                          <button onclick='producthistory("0")' type="button" class="btn btn-crud  btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                          <button onclick='single_product_details("0")' type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button>&nbsp;                                        
                                          <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>
                                      </td>
                                      <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                      <input type="hidden" name="disca[]" id="disca-0" value="0">
                                      <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                      <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                      <input type="hidden" name="unit[]" id="unit-0" value="">
                                      <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                  </tr>
                             <?php } ?>
                             <tr class="last-item-row sub_c tr-border">
                                <td class="add-row no-border" colspan="9">
                                 <?php 
                                 if(empty($invoice['quote_number']))
                                 { ?>
                                    <button type="button" class="btn btn-crud btn-secondary <?=$disableclass?>"  title="Add product row" id="sales_order_create_btn">
                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                    <div class="btn-group ml-1 mt-1 creditlimit-check <?=$creditlimit_class?>"></div>
                                 <?php } 
                                 
                                 ?>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>
                             <?php
                            
                           }
                           ?>
                           <!-- <tr class="last-item-row sub_c tr-border">
                              <td class="add-row no-border">
                                 <button type="button" class="btn btn-secondary" id="addquote_salesorder">
                                 <i class="fa fa-plus-square"></i> Add Row
                                 </button>
                              </td>
                              <td colspan="5" class="no-border"></td>
                           </tr> -->
                        
                     </tbody>
                  </table>
                  
               </div>
               
                  <div class="row mt-2">
                     <?php  if($configurations['config_tax']!='0'){ ?>
                        <div class="col-lg-11 col-md-10 col-sm-10 col-8 text-right mb-1">
                           <strong>Total Tax <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                        </div>
                        <div class="col-1">
                           <span id="taxr" class="lightMode"><?php echo amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                        </div>
                     <?php } ?>
                     <div class="col-12">
                         <?php 
                         if($token!=3)
                        {
                           echo '<div class="btn-group ml-1 mt-1 creditlimit-check '.$creditlimit_class.'"></div>';
                        }
                         ?>
                     </div>
                     <div class="col-lg-11 col-md-10 col-sm-10 col-8 text-right mb-1">
                       
                        <strong  class="d-none1"><?php echo $this->lang->line('Total Product Discount') ?> <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                     </div>
                     <div class="col-lg-1 col-md-2 col-sm-2 col-4 text-right mb-1"> <span id="discs" class="lightMode d-none1"><?php echo $totaldiscount; ?></span></div>
                     <div class="col-12 text-right">
                        <?php if ($exchange['active'] == 1){
                           echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                        <select name="mcurrency" class="selectpicker form-control">
                        <?php
                           echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                           foreach ($currency as $row) {
                           
                                 echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                           } ?>
                        </select><?php } ?>
                     </div>
            

                     <div class="col-lg-11 col-md-10 col-sm-10 col-8 text-right mb-1">
                        <strong class="d-none1"><?php echo $this->lang->line('Order Discount') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                     </div>
                     <div class="col-lg-1 col-md-2 col-sm-2 col-4 text-right mb-1">
                        <input type="number" class="form-control text-right" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$invoice['order_discount']?>" title="<?php echo $this->lang->line('Order Discount') ?>" data-original-value="<?=$invoice['order_discount']?>">
                     </div>

                     <div class="col-lg-11 col-md-10 col-sm-10 col-8 text-right mb-1">
                        <strong class="d-none1"><?php echo $this->lang->line('Grand Total') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                     </div>
                     <div class="col-lg-1 col-md-2 col-sm-2 col-4 text-right mb-1">
                            <?php
                                  $subtotal = $subtotal - ($invoice['order_discount']);
                              ?>
                           <span id="grandtotaltext"><?= number_format($subtotal,2); ?></span>
                           <span class="d-none" id="grandamount"><?= number_format($subtotal,2); ?></span>
                           <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?= $subtotal; ?>" readonly="">
                     </div>
                        
                        <!-- ============================== -->
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-8 col-xs-12 d-none1">
                           <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Sale Point') ?></label>
                           <select id="s_warehouses" class="form-control" name="store_id">
                           <?php //echo $this->common->default_warehouse();
                              echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                              <?php foreach ($warehouse as $row) {
                                 $selt = ($invoice['store_id']==$row['store_id']) ? "selected" : "";
                              echo '<option value="' . $row['store_id'] . '" '.$selt.'>' . $row['store_name'] . '</option>';
                              } ?>
                           </select>
                        </div>
                        <div class="col-12 d-none1"></div>
                        <!-- =========================== -->
                        <!-- <div class="col-8 text-right">
                           <strong class="d-none1"><?php echo $this->lang->line('Grand Total') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                        </div>
                        <div class="col-1 text-right">
                              <?php
                                  $subtotal = $subtotal - ($invoice['order_discount']);
                              ?>
                           <span id="grandtotaltext"><?= number_format($subtotal,2); ?></span>
                           <span class="d-none" id="grandamount"><?= number_format($subtotal,2); ?></span>
                           <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?= $subtotal; ?>" readonly="">
                        </div> -->
                     <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 responsive-mt-2">
                     <?php 
                           if(($action_type) && ((empty($invoice['quote_number'])) && ($invoice['converted_status']=='0' || $invoice['converted_status']=='4')))
                           {
                              // echo '<button type="button" class="btn btn-crud btn-lg btn-secondary revert-btncolor creditlimit-btn '.$disableclass.'" id="salesorder-delete-btn">'.$this->lang->line('Delete').'</button>';
                           }
                        // if($token!=2)
                        // {
                        ?>

                        <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn <?=$disableclass?> <?=$draft_disable?> <?=$unsavedisable_btns?>" value="<?php echo $this->lang->line("Save As Draft");?>" id="quote-to-salesorder-draft-btn" data-loading-text="Adding..." title="<?php echo $this->lang->line("Save As Draft");?>">

                        <?php //} ?>

                     </div>
                     <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 resposive-textright responsive-mt-2">
                       &nbsp;
                        <!-- <input type="submit" class="btn btn-lg btn-secondary sub-btn" value="<?php echo $this->lang->line("Save As Draft");?>" id="submit-data-draft" data-loading-text="Adding...">&nbsp; -->
                        <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn responsive-mb-1 <?=$disableclass?> <?=$unsavedisable_btns1?>" value="<?php echo $convertion_label;?> " id="create-salesorder-btn" data-loading-text="Adding..." title="Create Sales Order">
                        <?php 
                         $functions = array_column($permissions, "function");                                    
                         $permission_flg = (in_array("Create & Convert To Delivery Note", $functions)) ? 1 : 0;
                        if($action_type)
                        { ?>
                        <input type="submit" class="btn btn-crud1 btn-lg responsive-mb-1 btn-secondary sub-btn creditlimit-btn <?=$disableclass?>" value="<?php echo $this->lang->line($deliverynote_label) ?>" id="salesorder-assign-btn" title="<?= $deliverynote_title?>" data-loading-text="Creating..." <?=$approval_complete_class?>>                        
                        <input type="submit" class="btn responsive-mb-1 btn-crud1 btn-lg btn-secondary sub-btn creditlimit-btn <?=$disableclass?>" value="<?php echo $this->lang->line($invoice_label) ?>" id="convert-to-invoice-btn" data-loading-text="invoicing..." title="<?=$invoice_label_title?>" <?=$approval_complete_class?>>
                        <?php
                        } ?>

                   

                     </div>
                  </div>
               <input type="hidden" value="quote/saleorderaction" id="action-url">
               <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="<?php echo $i; ?>" name="counter" id="ganak">
               <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
               <input type="hidden" value="<?= $this->common->taxhandle_edit($invoice['taxstatus']) ?>"
                  name="taxformat" id="tax_format">
               <input type="hidden" value="<?= $invoice['format_discount']; ?>" name="discountFormat"
                  id="discount_format">
               <input type="hidden" value="<?= $invoice['taxstatus']; ?>" name="tax_handle" id="tax_status">
               <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
               <input type="hidden"
                  value="<?php
                     if($invoice['shipping']==0)  $invoice['shipping']=1;
                     $tt = 0;
                     if ($invoice['ship_tax_type'] == 'incl') $tt = @number_format(($invoice['shipping'] - $invoice['ship_tax']) / $invoice['shipping'], 2, '.', '');
                     echo amountFormat_general(@number_format((($invoice['ship_tax'] / $invoice['shipping']) * 100) + $tt, 3, '.', '')); ?>"
                  name="shipRate" id="ship_rate">
               <input type="hidden" value="<?= $invoice['ship_tax_type']; ?>" name="ship_taxtype" id="ship_taxtype">
               <input type="hidden" value="<?= amountFormat_general($invoice['ship_tax']); ?>" name="ship_tax" id="ship_tax">
         </form>
         </div>
      </div>
   </div>
</div>

<!-- ============================================== -->
<div id="write_off_model" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Write Off') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- ======================================================= -->
                <form  method="post" id="write_off_form">
                        <div class="container-fluid overflow-auto" id="table-potion"></div>                        
                </form>
                <!-- ======================================================= -->
            </div>
            
        </div>
    </div>
</div>
<!-- ============================================== -->



<script type="text/javascript">
   const changedFields = {};
   let productCode;   
   let changedProducts = new Set();
   let wholeProducts = new Set();
   $(document).ready(function() {
      if($('#customer_purchase_order').val()=="")
      {
          $('.page-header-data-section').css('display','block');
      }           
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
            if (approval_permissions && approval_permissions.first_level_approval === 'Yes') {
                $('.first_level')
                    .removeClass('approval-disabled')
                    .attr('title', 'First Level Approval');
            }

            if (approval_permissions && approval_permissions.second_level_approval === 'Yes') {
                $('.second_level')
                    .removeClass('approval-disabled')
                    .attr('title', 'Second Level Approval');
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
                function_number = $("#function_number").val();
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
            // approval levels ends


      var discountflag = <?=$discount_flg?>;
      if(discountflag==1){
                
         showdiscount_potion(); 
         if($("#tokenid").val()==2)
         {
            $('.discountcoloumn').removeClass('d-none');
            $('.discountpotionnotedit').removeClass('d-none'); 
         } 
      } 
      else{
         $('.discountpotionnotedit').addClass('d-none'); 
      }   
      if ($("#salesorders_status").val() == "invoiced") {
         disable_items();
      }
      if($("#available_credit").val())
      {
         credit_limit_with_grand_total();
      }
      
      $("#s_warehouses").prop('required', false).next(".error").remove();
     
       //erp2024 removed pdf code ends
       $('#discountchecked').val(2);
       //erp2024 new code for matrial request screen 07-06-2024 starts
       $('#MaterialReport').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               alert("Please select at least one product.");
               return;
           }
   
           if (selectedProducts.length > 0) {
               var form = $('<form action="<?php echo site_url('SalesOrders/materialrequest')?>" method="POST" target="_blank"></form>');
               form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts +'">');
               $('body').append(form);
               form.submit();
           }
       });
       //erp2024 new code for matrial request screen 07-06-2024 ends
       //erp2024 new code for purchase request screen 18-06-2024 starts
       $('#PurchaseRequest').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               alert("Please select at least one product.");
               return;
           }
   
           if (selectedProducts.length > 0) {
               var form = $('<form action="<?php echo site_url('Productrequest/purchaserequest')?>" method="POST" target="_blank"></form>');
               form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts +'">');
               $('body').append(form);
               form.submit();
           }
       });
       //erp2024 new code for purchase request screen 18-06-2024 ends
   
   
   
       $('#DeliveryReport').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               alert("Please select at least one product.");
               return;
           }
           var invocienoId = $('#invocienoId').val();
           var customer_id = $('#customer_id').val();
           var invocieduedate = $('#invocieduedate').val();
           var invoicedate = $('#invoicedate').val();
           var refer = $('#refer').val();
           var taxformat = $('#taxformat').val();
           var discountFormat = $('#discountFormat').val();
           var salenote = $('#salenote').val();
           var contents = $('textarea#contents').val();
   
           // Create the form dynamically
           var form = $('<form action="<?php echo site_url('pos_invoices/deliverNoteexportpdf')?>" method="POST"></form>');
           // Add hidden input fields for start_date and end_date
           form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts + '">');
           form.append('<input type="hidden" name="invocienoId" value="' + invocienoId + '">');
           form.append('<input type="hidden" name="customer_id" value="' + customer_id + '">');
           form.append('<input type="hidden" name="invoicedate" value="' + invoicedate + '">');
           form.append('<input type="hidden" name="invocieduedate" value="' + invocieduedate + '">');
   
           form.append('<input type="hidden" name="refer" value="' + refer + '">');
           form.append('<input type="hidden" name="taxformat" value="' + taxformat + '">');
           form.append('<input type="hidden" name="discountFormat" value="' + discountFormat + '">');
           form.append('<input type="hidden" name="salenote" value="' + salenote + '">');
           form.append('<input type="hidden" name="contents" value="' + contents + '">');
           // Append form to container
           $('body').append(form); // Append to body or another suitable element in the DOM
           // Programmatically submit the form
           form.submit();
       });

      $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel,{
         ignore: [], // Important: Do not ignore hidden fields (used by summernote)
         rules: {               
               invocieduedate: { required: true },
               customer_order_date: { required: true },
               customer_purchase_order: { required: true },
               customer_contact_number: {
                  phoneRegex :true
               },
               cst: {
                  required: function () {
                     return $("#customer_id").val() == "0";
                  }
               }
         },
         messages: {
               invocieduedate: "Enter Delivery Deadline",
               customer_purchase_order: "Purchase Order No.",
               customer_order_date: "Purchase Order Date",
               customer_contact_number : "Enter a Valid Number",
               cst : "Enter a Name"
         }
      }));
      document.querySelectorAll('[data-product-code]').forEach(function(element) {
         let productCode = element.getAttribute('data-product-code');
         // Add the productCode to the Set (duplicates will be automatically discarded)
         wholeProducts.add(productCode);
      });

    //erp2024 03-02-2025 for history log     
    document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function () {
            const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
            const originalValue = this.getAttribute('data-original-value');
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();    
            var  productCode = this.getAttribute('data-product-code');            
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
               if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue && productCode) {
                  changedProducts.add(productCode);
               }
            } else if (this.tagName === 'SELECT') {
                // For select fields, use the option's label
                const selectedOption = this.options[this.selectedIndex];
                const newValue = selectedOption ? selectedOption.label : '';
                const originalLabel = this.getAttribute('data-original-value');

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

    $('#create-salesorder-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#create-salesorder-btn').prop('disabled', true); 

            let isValid = false;
            $(".amnt").each(function () {
                  if (parseFloat($(this).val()) > 0) {
                     isValid = true;
                     return false;
                  }
            });

            if (isValid==false) {
               $('#create-salesorder-btn').prop('disabled', false); 
                  Swal.fire({
                     icon: "error",
                     title: "Invalid Quantity",
                     text: "At least one product quantity must be greater than zero.",
                  });
                  return;
            }

            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 1);                
                formData.append('changedFields', JSON.stringify(changedFields));                
                formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
                formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
                var quote_number = $("#quote_number").val();
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create/update sales order?",
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
                            url: baseurl + 'salesorders/action_direct', 
                           //  url: baseurl + 'quote/saleorderaction', 
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                              // window.location.href = baseurl + 'SalesOrders/salesorder_new?id='+quote_number+'&token=1'
                              if($("#action_type").val()) 
                              {
                                 location.reload();
                              }
                              else{
                                 window.location.href = baseurl + 'SalesOrders'; 
                              }
                             
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#create-salesorder-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('.page-header-data-section').css('display','block');
                $('#create-salesorder-btn').prop('disabled', false);
            }
        });

        $('#quote-to-salesorder-draft-btn').on('click', function(e) {
            e.preventDefault();
            $('#quote-to-salesorder-draft-btn').prop('disabled', true);
            if($("#customer_id").val() < 1)
            {
               if (!$("#customer-box").valid()) {
                  $("#customer-box").focus();
                  $('#quote-to-salesorder-draft-btn').prop('disabled', false);
                  return;
               }
            }
            // Validate the form
            // if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 0);
                var quote_number = $("#quote_number").val();
                var salesorder_id = $("#salesorder_id").val();                
                var tokenid = $("#tokenid").val();
                var target_id = (tokenid==3) ? salesorder_id : quote_number;
               $.ajax({
                     url: baseurl + 'SalesOrders/saleorderdraftaction', // Replace with your server endpoint
                     type: 'POST',
                     data: formData,
                     contentType: false, 
                     processData: false,
                     success: function(response) {
                        if (typeof response === "string") {
                           response = JSON.parse(response);
                        }
                        
                        $('#quote-to-salesorder-draft-btn').prop('disabled', false);
                        //   location.reload();

                        window.location.href = baseurl + 'SalesOrders/salesorder_new?id='+response.data+'&token='+tokenid; 
                     },
                     error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                     }
               });
        });
}); 
   $(function() {
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
   });
   $("#refreshBtn").on("click", function() {
       location.reload();
   });
   $('.editdate').datepicker({
       autoHide: true,
       format: '<?php echo $this->config->item('dformat2'); ?>'
   });

   function checkqty(id){
      var qty = parseFloat($("#amount-" + id).val()) || 0;
      var quoteqty = parseFloat($("#orderedqty-" + id).val()) || 0;
      var deliveredqty = parseFloat($("#deliveredqty-" + id).val()) || 0;
      var total = qty + deliveredqty;  
      if(quoteqty < qty){
         // $("#amount-" + id).val(0);
         // Swal.fire({
         //       icon: 'warning',
         //       title: 'Quantity',
         //       text: 'Sales order quantity is greater than the Quote quantity.'
         // });
         Swal.fire({
                    title: "Do you want to proceed?",
                    text: "Sales order quantity is greater than the Quote quantity",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: "No - Cancel",
                    reverseButtons: true, 
                    focusCancel: true, 
                    allowOutsideClick: false, 
                }).then((result) => {
                    if (result.isConfirmed) {

                    }
                    else
                    {
                     $("#amount-" + id).val(0);
                    }
         });
      }
   }
   $('#discountchecked').on('change', function() {
      var discountflag = <?=$discount_flg?>;
      if ($(this).is(':checked')) {
         $('.discountpotion').removeClass('d-none');
         $('.discountpotionnotedit').addClass('d-none'); 
         $('.pricelabel').addClass('d-none'); 
         $('input[name="product_price[]"]').attr('type', 'text');
         $('#discountchecked').val(1);
         $('.discountcoloumn').removeClass('d-none');
         
      } else {
         $('.discountcoloumn').addClass('d-none');
         $('.discountpotion').addClass('d-none');    
         if(discountflag==1)
         {
            $('.discountcoloumn').removeClass('d-none');                    
            // $('.discountpotion').removeClass('d-none'); 
         }
                
         $('.pricelabel').removeClass('d-none'); 
         // $('.discountpotionnotedit').removeClass('d-none'); 
         $('input[name="product_price[]"]').attr('type', 'hidden');
         $('#discountchecked').val(2);
         
      }
   });


   $('#salesorder-assign-btn').on('click', function(e) {    
      e.preventDefault(); // Prevent the default form submission
      $('#salesorder-assign-btn').prop('disabled', true); // Disable button to prevent multiple submissions
      var salesorder_id = $("#salesorder_id").val();
      let isValid = false;
      
      $(".amnt").each(function () {
            if (parseFloat($(this).val()) > 0) {
               isValid = true;
               return false;
            }
      });
      if (isValid==false) {
         $('#salesorder-assign-btn').prop('disabled', false); 
            Swal.fire({
               icon: "error",
               title: "Invalid Quantity",
               text: "At least one product quantity must be greater than zero.",
            });
            $('#salesorder-assign-btn').prop('disabled', false);
            return;
      }
      

      
      if($("#avalable_credit_limit").val() != undefined)
      {
         var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
         var available_credit_limit = parseFloat($("#avalable_credit_limit").val().replace(/,/g, '').trim());
         
         if (isNaN(total) || isNaN(available_credit_limit)) {
               
         } 
         else if (total > available_credit_limit) 
         {
            Swal.fire({
                  icon: 'error',
                  title: 'Credit Limit Exceeded',
                  text: 'The Grand Total Amount exceeds the Available Credit Limit. Please review.',
               });
               $('#salesorder-assign-btn').prop('disabled', false);
               return;
         }
      }



      
      // Validate the form
      if ($("#data_form").valid()) {    
                  

         var s_warehouses = $('#s_warehouses').val();
         if (s_warehouses === null || s_warehouses === '' || s_warehouses == '0') {
               // Swal.fire({
               //     icon: 'error',
               //     title: 'Sale Point',
               //     text: 'Please select a Warehouse/Shop before proceeding!',
               // });
               // return;
               $("#s_warehouses").prop('required', true);
               $("#s_warehouses").closest('.form-group').find('.error').remove(); // Remove existing errors
               $("#s_warehouses").after('<em class="error">Please Select a Sale Point.</em>'); // Show error
               $("#s_warehouses").focus();
               $("#s_warehouses").addClass('focusclass');
               $('#salesorder-assign-btn').prop('disabled', false); 
               return false;
         }

            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            formData.append('completed_status', 1);                      
            formData.append('changedFields', JSON.stringify(changedFields));                  
            formData.append('changedProducts', JSON.stringify(Array.from(changedProducts))); 
            formData.append('wholeProducts', JSON.stringify(Array.from(wholeProducts)));
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to convert this sales order to delivery note?",
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
                        url: baseurl + 'SalesOrders/convert_salesorder_to_deliverynote', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           if (typeof response === "string") {
                              response = JSON.parse(response);
                           }
                           window.location.href = baseurl + 'DeliveryNotes';
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                           console.log(error); // Log any errors
                        }
                  });
               } else if (result.dismiss === Swal.DismissReason.cancel) {
                  // Enable the button again if user cancels
                  $('#salesorder-assign-btn').prop('disabled', false);
               }
            });
      } else {
            // If form validation fails, re-enable the button            
            $('.page-header-data-section').css('display','block');
            $('#salesorder-assign-btn').prop('disabled', false);
      }
   });
   
   $('#convert-to-invoice-btn').on('click', function(e) {            
      e.preventDefault(); // Prevent the default form submission
      $('#convert-to-invoice-btn').prop('disabled', true); // Disable button to prevent multiple submissions
      var salesorder_id = $("#salesorder_id").val();
      let isValid = false;
      
      $(".amnt").each(function () {
            if (parseFloat($(this).val()) > 0) {
               isValid = true;
               return false;
            }
      });
      if (isValid==false) {
         $('#convert-to-invoice-btn').prop('disabled', false); 
            Swal.fire({
               icon: "error",
               title: "Invalid Quantity",
               text: "At least one product quantity must be greater than zero.",
            });
            return;
      }
      

      var s_warehouses = $('#s_warehouses').val();
      var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
      var available_credit_limit = parseFloat($("#avalable_credit_limit").val().replace(/,/g, '').trim());
      
      if (isNaN(total) || isNaN(available_credit_limit)) {
            
      } else if (total > available_credit_limit) {
      Swal.fire({
               icon: 'error',
               title: 'Credit Limit Exceeded',
               text: 'The Grand Total Amount exceeds the Available Credit Limit. Please review.',
            });
            $('#convert-to-invoice-btn').prop('disabled', false);
            return;
      }
      if (s_warehouses === null || s_warehouses === '') {
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Sale Point',
            //     text: 'Please select a Warehouse/Shop before proceeding!',
            // });
            // return;
            $("#s_warehouses").prop('required', true);
            $("#s_warehouses").closest('.form-group').find('.error').remove(); // Remove existing errors
            $("#s_warehouses").after('<em class="error">Please Select a Sale Point.</em>'); // Show error
            $("#s_warehouses").focus();
            $('#convert-to-invoice-btn').prop('disabled', false);             
            $(".page-header-data-section").slideToggle();
            return false;
      }

      
      // Validate the form
      if ($("#data_form").valid()) {    
                  
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            formData.append('completed_status', 1);                      
            formData.append('changedFields', JSON.stringify(changedFields));
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to convert this sales order to an invoice?",
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
                        url: baseurl + 'SalesOrders/convert_salesorder_to_invoice', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           if (typeof response === "string") {
                              response = JSON.parse(response);
                           }
                           window.location.href = baseurl + 'quote/salesorders?id='+salesorder_id; 
                           window.location.href = baseurl + 'invoices/convert_salesorder_to_invoice?id='+salesorder_id;
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                           console.log(error); // Log any errors
                        }
                  });
               } else if (result.dismiss === Swal.DismissReason.cancel) {
                  // Enable the button again if user cancels
                  $('#convert-to-invoice-btn').prop('disabled', false);
               }
            });
      } else {
            // If form validation fails, re-enable the button
            $('.page-header-data-section').css('display','block');
            $('#convert-to-invoice-btn').prop('disabled', false);
      }
   });
   function washoutqty_validate(i) {
      var remqty = parseInt($("#del_rem_qty" + i).val());  // Convert to number
      var write_off_quantity = parseInt($("#write_off_quantity" + i).val());  // Convert to number
      var actualqty = remqty - write_off_quantity;
      if (write_off_quantity > remqty) {
         Swal.fire({
               text: "Write-off quantity (" + write_off_quantity + ") is greater than the remaining quantity (" + remqty + ")",
               icon: "info"
         });
         
         $("#write_off_quantity" + i).val("");  // Clear the input field
         return; 
      }
      $("#amount-" + i).val(actualqty);
   }
   $('#writeoff_Btn').click(function() {
        var salesorder_id = $("#salesorder_id").val();
        var selectedProducts = [];
        $('.checkedproducts:checked').each(function() {
            selectedProducts.push($(this).val());
        });

      //   if (selectedProducts.length === 0) {
      //       Swal.fire({
      //       text: "Please select at least one product",
      //       icon: "info"
      //       });
      //       return;
      //   }
        $.ajax({
            url: baseurl + 'SalesOrders/write_off',
            dataType: 'json',
            method: 'POST',
            data: {
               //  'selectedProducts': selectedProducts,
                'salesorder_id' : salesorder_id
            },
            success: function(data) {
                $("#table-potion").html(data.table);
                $('#write_off_model').modal('show');              
            }
        });
        
    });

    $('#salesorder-delete-btn').on('click', function(e) {
            
      e.preventDefault();
      $("#s_warehouses").prop('required', false).next(".error").remove();
      $('#salesorder-delete-btn').prop('disabled', true);
      var salesorder_id = $("#salesorder_id").val();  
      var salesorder_number = $("#invocienoId").val();  
      Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete this sales order?",
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
                  url: baseurl + 'SalesOrders/saleorderdeleteaction',
                  type: 'POST',
                  data: {
                        salesorder_id : salesorder_id,
                        salesorder_number : salesorder_number,
                  },
                  success: function(response) {
                        if (typeof response === "string") {
                           response = JSON.parse(response);
                        }
                        window.location.href = baseurl + 'SalesOrders'; 
                  },
                  error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                  }
               });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
               // Enable the button again if user cancels
               $('#salesorder-delete-btn').prop('disabled', false);
            }
      });
      
   });
function write_off_btn_click() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to proceed with the write-off operation?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel',
        focusCancel: true,
        reverseButtons: true  // This reverses the order of buttons
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with AJAX request if the user confirms
            $('#write_off_submit_btn').prop('disabled', true);
            hasUnsavedChanges = false;

            var form = $('#write_off_form')[0];
            var formData = new FormData(form);
            formData.append('changedFields', JSON.stringify(changedFields));
            $.ajax({
                url: baseurl + "SalesOrders/write_off_action",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var data = JSON.parse(response);

                    // Re-enable the submit button and hide the modal on success
                    $('#write_off_submit_btn').prop('disabled', false);
                    if (data.status === 'Success') {
                        $('#write_off_model').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while generating the material request', 'error');
                    console.log(error); // Log any errors
                    $('#write_off_submit_btn').prop('disabled', false);
                }
            });
        }
    });
}

</script>

