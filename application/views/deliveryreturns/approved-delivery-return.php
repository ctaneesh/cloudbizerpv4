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
            $delivery_return_tid = ($delivery_return_number) ? $delivery_return_number : $notemaster['delivery_return_number'];
            $delivery_return_number = ($type=='new') ? $this->lang->line('Add New') : $notemaster['delivery_return_number'];
            $function_number = ($type=='new') ? "" : $notemaster['delivery_return_number'];
            // $delivery_return_number = ($delivery_return_number) ? $prefix.$delivery_return_number+1 : $notemaster['delivery_return_number'];
            
            ?> 
            
            <input type="hidden" id="function_number" name="function_number" value="<?=$function_number?>">
            <ol class="breadcrumb">
            
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('deliveryreturn') ?>"><?php echo $this->lang->line('Delivery Returns'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $delivery_return_number; ?></li>
            </ol>
        </nav>
            
        <?php
        $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn_to_creditnote?delivery=$id") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Convert to Credit Note').'</a>';
        ?>
            
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $delivery_return_number; ?> </h4>
                </div>
                <div class="col-xl-7 col-lg-9 col-md-6 col-sm-12 col-xs-12">
                    <ul id="trackingbar">
                    <?php 
                    $delnotenum = (!empty($notemaster['delnote_number'])) ? $notemaster['delnote_number'] : $notemaster['deliverynotenum'];
                    
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
                            if (!empty($trackingdata['delivery_return_number']) && empty($type)) { 
                                echo '<li class="active">' . $trackingdata['delivery_return_number'] . '</li>';
                            }
                            // else{
                            //     echo '<li class="active">' . $delivery_return_number . '</li>';
                            // }
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
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 col-xs-12 current-status">
                <?php
                     $messagetext="";
                     $statustext="";
                    //  echo $notemaster['status'];
                     switch ($notemaster['notestatus']) {
                        // case 'post dated cheque':
                        //    $status = '<span class="st-rejected">' . $this->lang->line($invoices->status) . '</span>';
                        //    break;
                        case 'Delivered':
                           $statustext = "Delivered";
                           $alertcls = "alert-danger";
                           $messagetext = "The invoice has been deleted.";
                           break;
                        case 'Approved':
                           $statustext = "Approved" ;
                           $alertcls = "alert-success";
                           $messagetext = "Approved";
                           break;
                        case 'Pending':
                           $statustext = "Created";
                           $alertcls = "alert-partial";
                           $messagetext = "Created";
                           break;

                  
                        default:
                           // $status = ($invoices->status != 'Draft') ? '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>' : '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
                           // $makepaymentbtn = '';
                           break;
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
            if(empty($type))
            {
  
               if ($notemaster['notestatus'] == "Approved"){ ?>
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
            
        <?php
        if(empty($type))
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
        } ?>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <form method="post" id="data_form">
                <?php
                $disable_class = ($notemaster['notestatus']=='Approved') ? "disable-class" : "";              
                echo '<input type="hidden" name="approval_flg" id="approval_flg" value="'.$notemaster['notestatus'].'">';
                $headerclass= "d-none";
                $pageclass= "page-header-data-section-dblock";
                if(empty($type))
                {
                    $headerclass = "page-header-data-section-dblock";
                    $pageclass   = "page-header-data-section";
                }
                $customer_id = $notemaster['customer_id'];
                $employee_id = $created_employee['id']; 
                ?>

                <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                            <h3  class="title-sub"><?php echo $this->lang->line('Delivery Return & Customer Details') ?> <i class="fa fa-angle-down"></i></h3>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 responsive-text-right quickview-scroll order-1 order-lg-2">
                            <div class="quick-view-section">
                                <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Customer') ?></h4>
                                    <?php
                                        echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($notemaster['name']) . "</b></a>";
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
                                    <?php echo "<p>".dateformat($notemaster['created_date'])."</p>"; ?>
                                </div>
                                <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Sales Point') ?></h4>
                                    <?php echo "<p>".$notemaster['warehousename']."</p>"; ?>
                                </div>
                                <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Created By') ?></h4>
                                    <?php 
                                        echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                    ?>
                                </div>
                                <div class="item-class text-center">
                                    <h4><?php echo $this->lang->line('Total'); ?></h4>
                                    <?php echo "<p>".number_format($notemaster['total_amount'],2)."</p>";?>
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
                                        <h3 class="title-sub"><?php echo $this->lang->line('Customer Details'); ?></h3>
                                        <input type="hidden" name="config_tax" id="config_tax" value="<?=$configurations['config_tax']?>">
                                        <input type="hidden" name="deliverynote_status" id="deliverynote_status" value="<?=$deliverynote_status?>">
                                        <input type="hidden" name="delivery_return_number" id="delivery_return_number" value="<?=$delivery_return_number?>">
                                        <input type="hidden" name="return_type"  value="<?=$type?>">
                                        <input type="hidden" name="store_id" id="store_id" value="<?=$notemaster['warehouseid']?>">
                                        <?php echo '<input type="hidden" name="customer_id" id="customer_id" value="' . $notemaster['customer_id'] . '">
                                            <div id="customer_name"><strong>' . $notemaster['name'] . '</strong></div>
                                            </div>
                                            <div class="clientinfo">

                                                <div id="customer_address1"><strong>' . $notemaster['address'] . '<br>' . $notemaster['city'] . ',' . $notemaster['country'] . '</strong></div>
                                            </div>

                                            <div class="clientinfo">

                                                <div type="text" id="customer_phone">Phone: <strong>' . $notemaster['phone'] . '</strong><br>Email: <strong>' . $notemaster['email'] . '</strong></div>
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
                                            <?php echo $this->lang->line('Delivery Return Properties');
                                            ?></h3>
                                    </div>

                                    <!-- erp2024 modified section 07-06-2024 -->
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none"><label
                                            for="invocieno"
                                            class="col-form-label"><?php echo $this->lang->line('Delivery Return Number'); ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"
                                                    aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" name="delivery_return_number" id="delivery_return_number" value="<?php echo $delivery_return_number; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="invocieno"
                                            class="col-form-label"><?php echo $this->lang->line('Delivery Note Number'); ?>
                                        </label>
                                        
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Delivery Note Number" name="delivery_note_number" id="delivery_note_number" value="<?php echo $notemaster['delivery_note_number']; ?>" readonly>

                                            <input type="hidden" class="form-control" placeholder="Delivery Note Number" name="invocieno" id="invocienoId" value="<?php echo $delnotenum; ?>" readonly>
                                            <input type="hidden" class="form-control"
                                                placeholder="Delivery Note Number" name="invoice_number" id="invoice_number"
                                                value="<?php echo $invoice_details['invoice_number']; ?>"
                                                readonly>
                                            <input type="hidden" class="form-control"  placeholder="Delivery Note Number" name="delivery_return_tid" id="delivery_return_tid" value="<?php echo $delivery_return_tid; ?>" readonly>
                                            <input type="hidden" class="form-control" name="transaction_number" id="transaction_number" value="<?php echo $notemaster['transaction_number']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="order_discount_percentage" id="order_discount_percentage" value="<?php echo $notemaster['return_order_discount_percentage']; ?>" readonly>
                                            <input type="hidden" name="delevery_note_id" readonly value="<?php echo $notemaster['delevery_note_id']; ?>">
                                            <input type="hidden" name="salesorder_number" readonly value="<?php echo $notemaster['salesorder_number']; ?>">
                                            <input type="hidden" name="salesorder_id" readonly value="<?php echo $notemaster['salesorder_number']; ?>">
                                            <input type="hidden" id="order_discount_percentage" name="order_discount_percentage" readonly value="<?php echo $notemaster['delivery_order_discount_percentage']; ?>">
                                        </div>
                                        <!-- erp2024 modified section 07-06-2024 Ends -->
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="invocieno"
                                            class="col-form-label"><?php echo $this->lang->line('Sales Order'); ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"
                                                    aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" name="refer1" id="refer1"
                                                value="<?php echo $notemaster['salesorder_number']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="invociedate"
                                            class="col-form-label"><?php echo $this->lang->line('Created Date'); ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-calendar4"
                                                    aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Billing Date"
                                                name="invoicedate" id="invoicedate" autocomplete="false"
                                                value="<?php echo date("d-m-Y", strtotime($notemaster['created_date'])); ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Reference'); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-calendar-o"
                                                    aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" name="invocieduedate"
                                                id="invocieduedate" value="<?php echo $notemaster['refer']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Reference Date'); ?> </label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-calendar-o"
                                                    aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" name="invocieduedate"
                                                id="invocieduedate" placeholder="Validity Date" autocomplete="false"
                                                value="<?php echo (!empty($notemaster['invoicedate'])) ? date("d-m-Y", strtotime($notemaster['invoicedate'])) : ""; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Comments'); ?></label>
                                        <textarea class="form-textarea disable-class" name="notes" id="salenote" rows="2"
                                            readonly><?php echo $notemaster['notes'] ?></textarea>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Status'); ?></label>
                                        <input type="text" class="form-control" name="status" id="status"
                                            value="<?php echo $notemaster['notestatus']; ?>" readonly>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Warehouse'); ?></label>
                                        <input type="text" class="form-control" name="warehousename" id="warehousename"
                                            value="<?php echo $notemaster['warehousename']; ?>" readonly>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                            for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Is it Invoiced?'); ?></label>
                                            <?php
                                            
                                            if($deliverynote_status=='Invoiced'){
                                                $invoicenumber =  $invoice_details['invoice_number'];
                                                $invoicestatus = "Yes";
                                            ?>
                                                <br><strong><?php echo $invoicestatus; ?></strong>
                                                <br> Invoice Number : <strong><a href="<?= base_url('invoices/view?id=' . $invoice_details['id']) ?>"><?php echo "".$invoicenumber; ?></a></strong>
                                            <?php
                                            }
                                            else{
                                                $invoicestatus = "No"; 
                                                ?>
                                                <br><strong><?php echo $invoicestatus; ?></strong>
                                                <?php
                                            }
                                            
                                            ?>
                                        <!-- <input type="text" class="form-control" value="<?php echo $invoicestatus; ?>" readonly> -->
                                        
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"><label for="approval_comments" class="col-form-label">Approval Comments</label>
                                    <textarea class="form-textarea" name="approval_comments" id="approval_comments" rows="2" data-original-value=""></textarea>
                                    </div>

                                    <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="toAddInfo" class="col-form-label">Approval Comments</label>
                                            <textarea class="form-textarea" name="approval_comments" id="approval_comments" rows="2"></textarea>
                                    <div> -->
                                </div>

                                <div class="form-group row d-none">
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="taxformat" class="col-form-label"><?php echo $this->lang->line('Reference'); ?>Tax</label>
                                        <select class="form-control" onchange="changeTaxFormat(this.value)"
                                            id="taxformat">

                                            <?php //echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">

                                        <div class="form-group">
                                            <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Reference'); ?>Discount</label>
                                            <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                id="discountFormat">
                                                <?php echo '<option value="' . $notemaster['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                <?php //echo $this->common->disclist() ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>

                    <ul class="nav nav-tabs mb-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                    aria-controls="tab1" href="#tab1" role="tab"
                                    aria-selected="true"><?php echo $this->lang->line('Delivery Return Properties') ?></a>
                            </li>
                            
                            <!-- <li class="nav-item">
                                <a class="nav-link navtab-caption breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab3"
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
                            <div id="saman-row" class="overflow-auto">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>

                                        <tr class="item_header bg-gradient-directional-blue white">
                                            <th width="4%" class="text-center"><?php echo $this->lang->line('SN'); ?></th>
                                            <!-- <th width="15%" style="padding-left:10px; text-align:left !important;"><?php echo $this->lang->line('Item Code'); ?>
                                            </th> -->
                                            
                                            <th width="10%" class="text-center1"><?php echo $this->lang->line('Item No'); ?></th>
                                            <th width="22%" class="text-center1"><?php echo $this->lang->line('Item Name'); ?></th>
                                            <th width="6%" class="text-center"><?php echo $this->lang->line('Unit'); ?></th>
                                            <th width="5%" class="text-center"><?php echo $this->lang->line('Delivered Qty'); ?></th>
                                            <th width="5%" class="text-center"><?php echo $this->lang->line('Returned Qty'); ?></th>
                                            <!-- <th width="5%" class="text-center"><?php echo $this->lang->line('Damaged Qty'); ?></th> -->
                                            <th width="7%" class="text-center"><?php echo $this->lang->line('Approve1')." ".$this->lang->line('Return Qty'); ?></th>
                                            <th width="8%" class="text-center"><?php echo $this->lang->line('Approve1')." ".$this->lang->line('Damaged Qty'); ?></th>
                                            <th width="10%" class="text-right"><?php echo $this->lang->line('Rate'); ?></th>
                                            <!-- <th width="7%" class="text-center"><?php echo $this->lang->line('Tax'); ?></th> -->
                                            <th width="10%" class="text-right">Discount</th>
                                            <th width="10%" class="text-right">
                                            <?php echo $this->lang->line('Amount'); ?><?php //echo "(".$this->config->item('currency').")"; ?>

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $i = 0;
                                    $j = 1;
                                    $taxtotal = 0;
                                    $productrate = 0;
                                    $discountrate = 0;
                                    $totalamount = 0;
                                    $grand_product_total = 0;
                                    foreach ($products as $row) {
                                        
                                            echo '<input type="hidden" class="form-control" name="product_id[]" value="' . $row['product_id'] . '">';
                                            echo '<input type="hidden" class="form-control" name="income_account_number[]" value="' . $row['income_account_number'] . '">';
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
                                            $discountrate = $discountrate+$row['totaldiscount'];
                                            $totalamount = $totalamount + $row['subtotal'];
                                            $returnQty = ($type=='new')?0:intval($row['return_qty']);
                                            $row['total_discount'] = ($type=='new')?0:$row['total_discount'];
                                            // $returnQty = intval($row['delivered_qty']) - intval($row['return_qty']);
                                            $grand_product_total += intval($returnQty)*$row['product_price'];
                                            $total =  intval($returnQty)*$row['product_price'];
                                        
                                            echo '<td class="text-center">'.$j.' <input type="checkbox" class="checkedproducts d-none" name="product_id1[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" checked></td>';
                                            echo '<td width="15%"><strong>'.$row['product_code'].'</strong> </td>';
                                            echo '<td width="15%"><strong>'.$row['product_name'].'</strong> </td>';
                                            echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';
                                            echo '<td class="text-center"><strong>'.$row['delivered_quantity'].'</strong><input type="hidden" name="delivered_qty[]" id="delivered_qty-' . $i . '" value="'.$row['delivered_quantity'].'"> </td>';
                                                
                                            $damaged_qty = ($row['damaged_quantity']) ? $row['damaged_quantity'] : 0;
                                            echo '<td class="text-center"><strong>'.$row['return_qty'].'</strong> <input type="hidden"  id="oldreturn-qty-' . $i . '" value="' . $row['return_qty'] . '">
                                            <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="'.$row['return_qty'].'"><input type="hidden" id="olddamaged-qty-' . $i . '" value="' . $damaged_qty . '"></td>';

                                            // echo '<td class="text-center"><strong>'.$row['damaged_quantity'].'</strong> </td>';

                                            echo '<td class="text-center"><input type="number" class="form-control req prc" name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="calculateDeliveryReturn(' . $i . '), rowDiscountTotal(' . $i . '), billUpyog(),convert_order_discount_percentage_to_amount()" title="'.$product_name_with_code.'Return Quantity" value="'.$returnQty.'" data-original-value="'.$returnQty.'"></td>';
                                            
                                            // echo '<td class="text-center"><input type="number" class="form-control req prc" name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="returnqtycheck(' . $i . '), rowDiscountTotal(' . $i . '), billUpyog(),convert_order_discount_percentage_to_amount()" title="'.$product_name_with_code.'Approve Return Quantity" value="'.$row['return_qty'].'" data-original-value="'.$row['return_qty'].'"></td>';
                                            
                                            // echo '<td class="text-center"><input type="number" class="form-control req prc" name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="checkqty(' . $i . '),rowDiscountTotal(' . $i . '), billUpyog()" value="'.$row['return_qty'].'"></td>';

                                            echo '<td class="text-center"><input type="number" class="form-control req prc" '.$disableclass.'  name="damaged_qty[]" id="damaged_qty-' . $i . '" value="'.$row['damaged_quantity'].'" onkeypress="return isNumber(event)" onkeyup="damageqtycheck(' . $i . ')" placeholder="'.$this->lang->line('Enter Qty').'" title="'.$product_name_with_code.'Damaged Quantity" data-original-value="'.$row['damaged_quantity'].'"></td>';


                                            echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowDiscountTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . amountExchange_s($row['product_price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';

                                            // echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">'.$row['totaltax'].'</td>';

                                            // <!-- erp2024 modified section 07-06-2024 --> 
                                                echo '<td class="text-right"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$row['total_discount'].'</strong></td>';

                                                echo '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">'.$total.'</span></strong></td>
                                                </td>';
                                                // echo '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">'.($row['subtotal']).'</span></strong></td>
                                                // </td>';

                                                echo '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="0">

                                                <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['total_discount'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">';


                                                echo '<input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . $row['subtotal'] . '">
                                                <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                                <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="hsn-' . $i . '" value="' . $row['product_code'] . '">
                                                <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['discount_type'].'">

                                                <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" value="' . amountFormat_general($row['product_discount']) . '">
                                                
                                                <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off"  value="' . amountFormat_general($row['product_discount']) . '">
                                                
                                            </tr>';
                                            $i++; $j++;
                                    } ?>


                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="6" class="no-border"></td>
                                            <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                    <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                            </td>
                                            <td align="right" colspan="2" class="no-border">
                                                <!-- <span id="grandamount">0.00</span> -->
                                                <span id="grandamount"><?=number_format($grand_product_total,2)?></span>
                                            </td>
                                        </tr>
                                        <tr class="sub_c tr-border d-none" style="display: table-row; ">
                                            <td colspan="9" align="right" class="no-border"><strong>Total Tax</strong></td>
                                            <td align="left" colspan="2" class="no-border"><span class="currenty lightMode"><?php //echo $this->config->item('currency'); ?></span>
                                                <span id="taxr"
                                                    class="lightMode"><?php echo $taxtotal; ?></span>
                                            </td>
                                        </tr>
                                        <!-- erp2024 removed section 07-06-2024 -->
                                        <tr class="sub_c" style="display: table-row;">
                                        <td colspan="10" align="right" class="no-border"><strong>Total Product Discount<?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                        <td align="right" colspan="2" class="no-border">
                                            <!-- <span id="discs" class="lightMode">0.00</span> -->
                                            <span id="discs" class="lightMode"><?=number_format($discountrate,2)?></span>
                                        </td>
                                    </tr>

                                        <!-- <tr class="sub_c" style="display: table-row;">
                                            <td colspan="8" align="right" class="no-border"><input type="hidden" value="0"
                                                    id="subttlform" name="subtotal"><strong>Shipping</strong></td>
                                            <td align="left" colspan="2" class="no-border"><input type="text"
                                                    class="form-control shipVal" readonly
                                                    onkeypress="return isNumber(event)" placeholder="Value" name="shipping"
                                                    autocomplete="off" onkeyup="billUpyog()"
                                                    value="<?php if ($notemaster['ship_tax_type'] == 'excl') {
                                                                                $notemaster['shipping'] = $notemaster['shipping'] - $notemaster['ship_tax'];
                                                                            }
                                                                            echo amountExchange_s(0, 0, $this->aauth->get_user()->loc); ?>">(
                                                <?= $this->lang->line('Tax') ?> <?php //echo "(".$this->config->item('currency').")"; ?>

                                                <span
                                                    id="ship_final"><?= amountExchange_s(0, 0, $this->aauth->get_user()->loc) ?>
                                                </span>
                                                )
                                            </td>
                                        </tr> -->
                                                                            
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="6" class="no-border"></td>
                                            <td colspan="4" align="right"  class="no-border"><strong>Order Discount<?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                            <td align="right" colspan="2"  class="no-border">
                                                <span id="order_discount_text" class="lightMode">0.00</span>
                                                <input type="hidden" id="order_discount" name="order_discount" value="0">
                                                <!-- <span id="order_discount_text" class="lightMode"><?=number_format($notemaster['return_order_discount'],2)?></span>
                                                <input type="hidden" id="order_discount" name="order_discount" value="<?=$notemaster['return_order_discount']?>"> -->
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
                                            <td colspan="6" align="right" class="no-border">
                                                <strong><?php echo $this->lang->line('Grand Total') 
                                                
                                                ?>
                                                    <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                            </td>
                                            <?php
                                                    $grandtotal = 0;
                                                    $grandtotal = ($productrate)-$discountrate;
                                                    if($type=='new')
                                                    {
                                                        $notemaster['total_amount']=0.00;
                                                    }
                                            ?>
                                            <td align="right" colspan="2" class="no-border">
                                                <span id="grandtotaltext"><?php echo number_format($notemaster['total_amount'],2); ?></span>
                                                <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="<?php echo $notemaster['total_amount']; ?>" readonly>
                                                <!-- <span id="grandtotaltext">0.00</span> -->
                                                <!-- <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  readonly> -->
                                                <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                                            </td>
                                        </tr>

                                       

                                    </tbody>
                                </table>
                            </div>
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
                        <div class="col-12 responsive-text-right">
                            <hr>
                            <?php 
                            $btnclass="btn-primary";
                            if($type && $notemaster['notestatus']!="Pending")
                            {
                                $btnclass="btn-secondary";
                                ?> <input type="submit" id="submit-delivery-return" class="btn btn-primary btn-lg submitBtn responsive-mb-1 <?=$disable_class?>" value="Create Delivery Return"/><?php
                            }
                             if($last_approval_step==2)
                            {
                                $approval_complete_class = "";
                                $approval_complete_label = "Approve Delivery Return";
                            }
                            else{
                                $approval_complete_class = 'disabled';
                                $approval_complete_label = "All approvals are not completed.";
                            }
                            if(empty($type))
                            {
                            ?>
                            
                            <input type="submit" id="approve-delivery-return" class="btn  <?=$btnclass?> btn-lg submitBtn responsive-mb-1 <?=$disable_class?>" value="Approve Delivery Return" title="<?=$approval_complete_label?>" <?=$approval_complete_class?>/>

                            <?php } ?>
                                            
                        </div>
                        <!-- =================================================================== -->
                    </div>


                </form>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
const changedFields = {};
$(document).ready(function() {
   
    if($("#approval_flg").val()=='1')
    {
        disable_items();
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
        var return_type = "<?=$type?>";

        // Initialize buttons
        $('.first_level, .second_level')
            .addClass('approval-disabled')
            .attr('title', 'You have no permission');

        // Enable buttons based on permissions
        if(return_type=="")
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

    // $('#DeliveryReport').click(function () {
    //     var selectedProducts = [];
    //     var deliveredItems = [];
    //     var i =0;
    //     $('.checkedproducts:checked').each(function() {
    //         selectedProducts.push($(this).val());
    //         deliveredItems.push($("#amount-"+i).val());
    //         i++;
    //     });
    //     if (selectedProducts.length === 0) {
    //         alert("Please select at least one product.");
    //         return;
    //     }
    //     var invocienoId= $('#invocienoId').val();
    //     var customer_id= $('#customer_id').val();
    //     var invocieduedate= $('#invocieduedate').val();
    //     var invoicedate= $('#invoicedate').val();
    //     var refer= $('#refer').val();
    //     var taxformat= $('#taxformat').val();
    //     var discountFormat= $('#discountFormat').val();
    //     var  salenote= $('#salenote').val();
    //     // Create the form dynamically
    //     var form = $('<form action="<?php echo site_url('pos_invoices/deliverNoteexportpdf')?>" method="POST"></form>');
    //     form.append('<input type="hidden" name="deliveredItems" value="' + deliveredItems + '">');
    //     form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts + '">');
    //     form.append('<input type="hidden" name="invocienoId" value="' + invocienoId + '">');
    //     form.append('<input type="hidden" name="customer_id" value="' + customer_id + '">');
    //     form.append('<input type="hidden" name="invoicedate" value="' + invoicedate + '">');
    //     form.append('<input type="hidden" name="invocieduedate" value="' + invocieduedate + '">');

    //     form.append('<input type="hidden" name="refer" value="' + refer + '">');
    //     form.append('<input type="hidden" name="taxformat" value="' + taxformat + '">');
    //     form.append('<input type="hidden" name="discountFormat" value="' + discountFormat + '">');
    //     form.append('<input type="hidden" name="salenote" value="' + salenote + '">');
    //     $('body').append(form);
    //     form.submit();   
    // });
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
});

$("#refreshBtn").on("click", function() {
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

function damageqtycheck(numb){
    if($("#damaged_qty-" + numb).val()>$("#amount-" + numb).val()){
    // if($("#damaged_qty-" + numb).val()>$("#oldreturn-qty-" + numb).val()){
        Swal.fire({
            icon: 'error',
            title: 'Invalid Input',
            text: 'Approve Damaged Quantity is greater than actual Retured Quantity',
            confirmButtonText: 'OK'
        });  
        $("#damaged_qty-" + numb).val(0);
    }    
}
function returnqtycheck(numb){
    if($("#amount-" + numb).val()>$("#oldreturn-qty-" + numb).val()){
        Swal.fire({
            icon: 'error',
            title: 'Invalid Input',
            text: 'Approve Return Quantity is greater than actual Retured Quantity',
            confirmButtonText: 'OK'
        });  
        $("#amount-" + numb).val(0);
    }    
}

$("#approve-delivery-return").on("click", function(e) {
    e.preventDefault();
    $('#approve-delivery-return').prop('disabled', true);
    var validQtyFound = false; // Flag to track if a valid qty is found
    $("input[name='return_qty[]']").each(function() {
        if (parseInt($(this).val()) > 0) {
            validQtyFound = true;
            $('#approve-delivery-return').prop('disabled', false);
            return ; // Exit loop once a valid qty is found
        }
    });

    if (!validQtyFound) {
        $("#amount-0").focus();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Return Quantity',
            text: 'Please enter at least one valid approve return quantity greater than 0.',
            confirmButtonText: 'OK'
        });
        $('#approve-delivery-return').prop('disabled', false);
        return; // Prevent form submission
    }

    // Use SweetAlert for confirmation
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to approve these items?",
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
            var delivery_return_number = $("#delivery_return_number").val();
            var salesorderid = $("#salesorder_id").val();
            var customerid = $("#customer_id").val();
            var formData = $("#data_form").serialize(); 
           formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            $.ajax({
                type: 'POST',
                url: baseurl +'Deliveryreturn/delivery_return_approval_action',
                data: formData,
                success: function(response) {        
                    window.open(baseurl + 'Deliveryreturn/reprintnote?delivery=' + delivery_return_number + '&sales=' + salesorderid + '&cust=' + customerid, '_blank');
                    window.location.href = baseurl + 'Deliveryreturn';
                    $('#approve-delivery-return').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});

$("#submit-delivery-return").on("click", function(e) {
    e.preventDefault();
    $('#submit-delivery-return').prop('disabled', true);
    var validQtyFound = false; // Flag to track if a valid qty is found
    $("input[name='return_qty[]']").each(function() {
        if (parseInt($(this).val()) > 0) {
            validQtyFound = true;
            $('#submit-delivery-return').prop('disabled', false);
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
        $('#submit-delivery-return').prop('disabled', false);
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
            var deliverynoteid = parseInt($("#invocienoId").val());
            deliverynoteid  = deliverynoteid;
            var salesorderid = $("#salesorder_id").val();
            var customerid = $("#customer_id").val();
            var formData = $("#data_form").serialize(); 
            formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            $.ajax({
                type: 'POST',
                url: baseurl +'Deliveryreturn/delivery_return_action',
                data: formData,
                success: function(response) {
                    // deliveryReport();     

                    
                    delivery_return_number = response.data;         
                    window.open(baseurl + 'Deliveryreturn/reprintnote?delivery=' + delivery_return_number + '&sales=' + salesorderid + '&cust=' + customerid, '_blank');
                        window.location.href = baseurl + 'Deliveryreturn';
                    $('#submit-delivery-return').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});

</script>