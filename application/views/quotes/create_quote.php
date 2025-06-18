<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <?php
                $quote_number = $prefix.($latest_quote_number);
                $quote_tid = $latest_quote_number;
                $exist_record_class_hidden = "d-none";
                $function_number ="";
                if($quote['quote_number'])
                {
                    $exist_record_class_hidden = "";
                    $quote_number = $quote['quote_number'];
                    $function_number = $quote_number;
                    $quote_tid = $quote['quote_number'];
                }
                
            ?>
             <input type="hidden" id="function_number" value="<?=$function_number?>">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?= base_url('quote') ?>"><?php echo $this->lang->line('Quotes') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $quote_number;  ?></li>
                    <!-- <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Quote')."# ".$quote_number;  ?></li> -->
                </ol>
            </nav>
                    <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <h4 class="card-title"><?php echo $quote_number;  ?></h4>
                </div>
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <?php 
                                $add_customer_class="";
                                if (!empty($trackingdata)) { 
                                    echo '<ul id="trackingbar">';                   
                                        $prefixs = get_prefix_72();
                                        $suffix = $prefixs['suffix'];
                                        if (!empty($trackingdata['lead_id'])) { 
                                            $add_customer_class = "disable-class";
                                            echo '<li><a href="' . base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) . '">' . $trackingdata['lead_number'] . '</a></li>';
                                        } 
                                        if (!empty($trackingdata['quote_number'])) { 
                                            echo '<li class="active">' . $trackingdata['quote_number'] . '</li>';
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
                                            echo '<li><a href="' . base_url('invoices/create?id=' . $trackingdata['invoice_number']).'">' . $trackingdata['invoice_number'] . '</a></li>';
                                        }
                                        if (!empty($trackingdata['invoice_retutn_number'])) { 
                                            echo '<li><a href="' . base_url('invoicecreditnotes/create?iid=' . $trackingdata['invoice_retutn_number']).'">' . $trackingdata['invoice_retutn_number'] . '</a></li>';
                                        }
                                    echo '</ul>';
                                }
                            ?>
                    </ul>
                </div>
                 <div class="col-lg-2 col-md-3 col-sm-12 current-status">

                    <?php
                            if($quote)
                             {
                            print_r( $this->session->userdata('key'));
                            $validtoken = hash_hmac('ripemd160', 'q' . $quote['quote_number'], $this->config->item('encryption_key'));
                            $link = base_url('billing/quoteview?id=' . $quote['quote_number'] . '&token=' . $validtoken);
                            $approvedcls = "";
                            $frmelmentdisable = "";
                            $frmselectdisable = "";
                            $converted_class = "disable-class";
                            if($quote['prepared_flag']=='0')
                            {
                                $approvedcls = "disable-class";
                            }
                            if($quote['quotestatus']=='Assigned' && $quote['employee_id']!=$this->session->userdata('id'))
                            {
                                $prepared_class = "";
                                $approvedcls = "disable-class";
                            }
                            else{
                                // $prepared_class =($quote) ? "d-none" : "";
                            }
                            if($quote['quotestatus'] == "Sent")
                            {
                                $converted_class = "";
                                $alertcls = "alert-success";
                            }
                            $required = "";
                            $compulsory = '';
                            $addrowcls ="";
                            $revertbtncls="";

                            if($quote['prepared_flag']!='0'){
                                $required = "required";
                                $compulsory = '<span class="compulsoryfld">*</span>';
                                $addrowcls ="d-none";
                                $revertbtncls="";
                            }
                            
                            if(empty($quote['approved_by']) && $quote['prepared_flag']!='0'){
                                $addrowcls ="";
                                $revertbtncls="d-none";
                            }
                            else if($quote['approval_flag']=='1' && $quote['prepared_flag']!='0'){
                                $frmelmentdisable = "readonly";
                                $frmselectdisable = "textarea-bg disable-class";
                            }

                            $hidecls="";
                            $hideapprovedbymels="";
                            $approverdisablecls="disable-class";
                            if($quote['approved_by']==$this->session->userdata('id') && $quote['quotestatus']!='accepted'){
                                $hidecls = "d-none";
                                $approverdisablecls = "disable-class";
                                $addrowcls ="";
                                $revertbtncls="d-none";
                                $frmelmentdisable="";
                                $frmselectdisable = "";
                            }
                            else if($quote['quotestatus']=='accepted'){
                                $hideapprovedbymels="d-none";
                                $addrowcls ="";
                                $revertbtncls="d-none";
                                $frmelmentdisable="";
                                $frmselectdisable = "";
                            }
                            else{
                                // $revertbtncls="";
                            }
                        
                            $acceptedcls = "";
                        
                             
                             $msgcls = "";
                             $messagetext = "";
                             $enabledisablecls="";
                             $marginbottom = "mb-2";
                             $assignseccls = "";
                             $acceptsendbtncls="";
                             $stage_after_sent = "";
                             $sent_input="";
                             $sent_input_disable="";
                             $statustext="";
                                $status = ucwords($quote['quotestatus']);
                                switch (true) {
                                    case ($quote['prepared_flag'] == 0 && $quote['quotestatus'] != "draft"):
                                        $msgcls = "d-none";
                                        $enabledisablecls ="d-none";
                                        $marginbottom = "";
                                        $assignseccls = "d-none";
                                        break;
        
                                    case ($quote['approval_flag'] != 1 && $quote['prepared_flag'] == 1 && $quote['quotestatus'] == "pending"):
                                        $statustext = "Created";
                                        $messagetext = "Now you can Assign this to an Employee or Send Quote from here";
                                        $enabledisablecls ="";
                                        $msgcls = "";
                                        $alertcls = "alert-partial";
                                        $acceptsendbtncls ="d-none";
                                        break;
        
                                    case ($quote['approved_by']!=$this->session->userdata('id') && $quote['quotestatus'] == "Assigned"):
                                        $messagetext = "Accept the Quote";
                                        $statustext = "Accept the Quote";
                                        $msgcls = "";
                                        $enabledisablecls ="disable-class";
                                        $alertcls = "alert-success";
                                        break;
                                    case ($quote['approved_by']==$this->session->userdata('id') && $quote['quotestatus'] == "Assigned"):
                                        $msgcls = "";
                                        $messagetext = "<b>".$assignedperson['name']."</b>&nbsp;  has not accept this quote yet. Assigned Date : <b>&nbsp;".date('d-m-Y h:i:s A', strtotime($quote['approved_date']))."</b>";
                                        $statustext = "Assigned";
                                        $alertcls = "alert-success";
                                        break;
        
                                    case ($quote['quotestatus'] == "Sent"):
                                        $messagetext = "The Quote has been Sent";
                                        $statustext = "Sent";
                                        if($quote['convert_flag']=="1")
                                        {
                                            $messagetext = "The Quote has been Converted.";
                                            $statustext = "Converted.";
                                            $alertcls = "alert-success";
                                        }
                                        else if($quote['convert_flag']=="2"){
                                            $messagetext = "The Quote has been Partially Converted.";
                                            $statustext = "Partially Converted.";
                                            $alertcls = "alert-partial";
                                        }
                                        else{
                                            $messagetext = "The Quote has been Sent";
                                            $statustext = "Sent";
                                            $alertcls = "alert-success";
                                        }
                                        
                                        $msgcls = "";
                                        $enabledisablecls ="";
                                        $stage_after_sent = "d-none";
                                        $sent_input = "readonly";
                                        $sent_input_disable = "disable-class";
                                        break;
                                    case ($quote['quotestatus'] == "Reverted"):
                                        $messagetext = "Quote Reverted.";
                                        $statustext = "Quote Reverted.";
                                        $msgcls = "";
                                        $enabledisablecls ="";
                                        $revertbtncls="d-none";
                                        $alertcls = "alert-danger";
                                        break;
                                    case ($quote['quotestatus'] == "draft"):
                                        $messagetext = "Data Saved As Draft";
                                        $statustext = "Draft";
                                        $msgcls = "";
                                        $enabledisablecls ="";
                                        $revertbtncls="d-none";
                                        $alertcls = "alert-secondary";
                                        break;
        
                                    default:
                                    // No action needed for the default case
                                    $alertcls = "alert-partial";
                                    $messagetext = "";
                                    $statustext = "Created";
                                    break;
                                }
                             }
                             if(($statustext))
                             {
                                echo '<div class="btn-group alert text-center '.$msgcls.' '.$alertcls.'" role="alert">'.$statustext.'</div>';
                              } 
                        ?>

                </div>
            </div>
              <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <?php
            
            if($quote && $quote['quote_number'])
            {
               if ($quote['quotestatus'] == "Sent"){ ?>
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
        if($quote)
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
                            <input type="hidden" id="function_number" name="function_number" value="<?=$function_number?>">
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
                    <input type="hidden" name="config_tax" id="config_tax" value="<?=$configurations['config_tax']?>">
                    <input type="hidden" name="discount_flag" class="discount_flag" value="0">

                    <div class="row">

                        <!-- ========================================================================= -->
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <?php 
                             if($quote['convert_flag']=='2')
                             {
                                 echo '<a href="' . base_url("SalesOrders/salesorder_new?id=$quote_id") . '&token=1" class="btn btn-crud btn-sm btn-secondary converttobtn mt-14px"  title="sales order">Sales Order(s)</span></a>';
                              }
                            if(($quote) && $quote['convert_flag']==0){ ?>

                            <button type="button"
                                class="btn btn-crud btn-sm btn-secondary converttobtn mt-14px <?php echo $approvedcls; ?> <?=$convertbtn?> <?=$converted_class?> d-none1"
                                title="Sales Order" onclick="convertToSalesOrder1('<?=$quote['quote_number']?>')">
                                <?php echo $this->lang->line('Convert to Sales Order') ?> </button>
                            <?php } ?>
                            <div class="btn-group">
                                <button type="button"
                                    class="btn btn-crud btn-sm btn-secondary dropdown-toggle mb-1 <?php echo $approvedcls." ".$sendbtn." ".$exist_record_class_hidden; ?>"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    title="EMail"><span class="fa fa-envelope"></span> EMail
                                </button>
                                <div class="dropdown-menu"><a href="#sendEmail" data-toggle="modal" data-remote="false"
                                        class="dropdown-item sendbill"
                                        data-type="quote"><?php echo $this->lang->line('Send Proposal') ?></a>
                                </div>

                            </div>

                            <div class="btn-group">
                                <button type="button"
                                    class="btn btn-crud btn-sm btn-secondary dropdown-toggle mb-1 <?php echo $approvedcls." ".$sendbtn." ".$exist_record_class_hidden; ?>"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="SMS"> <span
                                        class="fa fa-mobile"></span> SMS
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#sendSMS" data-toggle="modal" data-remote="false"
                                        class="dropdown-item sendsms"
                                        data-type="quote"><?php echo $this->lang->line('Send Proposal') ?></a>
                                </div>
                            </div>
                        </div>
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


                        <div class="col-lg-3 col-md-2 col-sm-12 text-lg-right text-sm-left">

                            <div class="">
                             <?php 
                                if($related_salesorders)
                                {
                                    echo '<div class="dropdown d-inline-block">';
                                    echo '<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                    echo '<i class="fa fa-file-alt"></i> Related Salesorders <span class="badge badge-light">'.count($related_salesorders).'</span>';
                                    echo '</button>';
                                    echo '<div class="dropdown-menu dropdown-menu-right">';
                                    
                                    foreach($related_salesorders as $related_salesorder)
                                    {
                                        $related_order = $related_salesorder['salesorder_number'];
                                        echo '<a class="dropdown-item" href="'.base_url("SalesOrders/salesorder_new?id=$related_order&token=2").'">';
                                        echo $related_order;
                                        echo '</a>';
                                    }
                                    
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                                 <a class="btn btn-sm btn-secondary btn-crud" target="_blank" href="<?= base_url('billing/printquote?id=' . $quote['quote_number'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Print') ?></a>
                                
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================================= -->

                    <?php
                         $due_date = (!empty($quote['due_date']) && $quote['due_date'] != '0000-00-00') 
                         ? $quote['due_date'] 
                         : date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['quote_validity'] . " days"));
                         $term = ($quote['payment_term'])?$quote['payment_term']:$validity['payment_terms'];
                         $customer_id = $quote['customer_id'];
                        $employee_id = $created_employee['id']; 
                        $headerclass= "d-none";
                        $pageclass= "page-header-data-section-dblock";
                        $customer_search_section = "";
                        if($quote_id)
                        {
                            $headerclass = "page-header-data-section-dblock";
                            $pageclass   = "page-header-data-section";
                            $customer_search_section = "d-none";
                        }
                        
                        ?>
                    <div class="header-expand-btn breaklink <?=$headerclass?>" data-target=".page-header-data-section">
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 order-2 order-lg-1">
                                <h3 class="title-sub"><?php echo $this->lang->line('Quote & Customer Details') ?> <i
                                        class="fa fa-angle-down"></i></h3>
                            </div>
                            <div
                                class="col-lg-9 col-md-12 col-sm-12 col-xs-12 text-right quickview-scroll order-1 order-lg-2">
                                <div class="quick-view-section">
                                    <div class="item-class text-center">
                                        <h4><?php echo $this->lang->line('Customer') ?></h4>
                                        <?php //echo "<b>".$quote['name']."</b>"; ?>
                                        <?php
                                                echo "<a class='expand-link' href='" . base_url('customers/view?id=' . urlencode($customer_id)) . "' target='_blank'><b>" . htmlspecialchars($quote['name']) . "12</b></a>";
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
                                        <?php echo "<p>".dateformat($quote['quote_date'])."</p>"; ?>
                                    </div>
                                    <div class="item-class text-center">
                                        <h4><?php echo $this->lang->line('Due Date') ?></h4>
                                        <?php echo "<p style='color:".$colorcode."'>".dateformat($due_date)."</p>"; ?>
                                    </div>

                                    <div class="item-class text-center">
                                        <h4><?php echo $this->lang->line('Created By') ?></h4>
                                        <?php 
                                                    echo "<a href='" . base_url('employee/view?id=' . urlencode($employee_id)) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($created_employee['name']) . "</b></a>";
                                                ?>
                                    </div>
                                    <?php
                                    if($quote['quotestatus']=="Sent")
                                    {
                                    ?>
                                    <div class="item-class text-center">
                                        <h4><?php echo $this->lang->line('Sent By') ?></h4>
                                        <?php 
                                        echo "<a href='" . base_url('employee/view?id=' . urlencode($sent_employee['id'])) . "' target='_blank' class='expand-link'><b>" . htmlspecialchars($sent_employee['name']) . "</b></a>";
                                      ?>
                                    </div>
                                    <div class="item-class text-center">
                                        <h4><?php echo $this->lang->line('Sent Date') ?></h4>                                        
                                        <?php echo "<p>".dateformat_time($quote['sent_date'])."</p>"; ?>
                                    </div>
                                    <?php } ?>
                                    <div class="item-class text-center">
                                        <h4><?php echo $this->lang->line('Total'); ?></h4>
                                        <?php echo "<p>".number_format($quote['total'],2)."</p>";?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="<?=$pageclass?>">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                                <div id="customerpanel" class="inner-cmp-pnl">
                                    <div class="form-group row">
                                        <div class="fcol-sm-12">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Customer Details') ?>
                                            </h3>
                                        </div>
                                        <div
                                            class="frmSearch col-sm-12 customer-search-section <?=$customer_search_section?>">

                                            <label for="customer_name"
                                                class="col-form-label d-flex justify-content-between align-items-center"
                                                id="customerLabel">
                                                <span><?php echo $this->lang->line('Search Client') ?> <span
                                                        class="compulsoryfld">*</span></span>
                                                <input type="button" value="Add New Customer"
                                                    class="btn btn-sm btn-secondary add_customer_btn" autocomplete="off"
                                                    title="Add New Customer">
                                            </label>

                                            <input type="text" class="form-control" name="cst" id="customer-box"
                                                title="Customer Search"
                                                placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>"
                                                autocomplete="off" />
                                            <div id="customer-box-result"></div>
                                        </div>

                                    </div>
                                    <div id="customer">
                                        <input type="hidden" name="quote_id" id="quote_id" value="<?=$quote_id?>">
                                        <?php 
                                                echo '  <input type="hidden" name="customer_id" id="customer_id" value="' . $quote['customer_id'] . '">';
                                                if($quote['customer_id']>0)
                                                {
                                                 echo '<div class="existingcustomer_details">';
                                                    echo '<div class="clientinfo">
                                                    <div id="customer_name"><strong>' . $quote['name'] . '</strong><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectionedit">'.$this->lang->line("Customer Edit").'</button><button type="button" class="btn btn-sm btn-secondary ml-1 searchsectioncancel d-none">'.$this->lang->line("Customer Cancel").'</button></div>
                                                </div>
                                                <div class="clientinfo">

                                                    <div id="customer_address1"><strong>' . $quote['address'] . '<br>' . $quote['city'] . ',' . $quote['country'] . '</strong></div>
                                                </div>

                                                <div class="clientinfo">
                                                    <div type="text" id="customer_phone">Phone: <strong>' . $quote['phone'] . '</strong><br>Email: <strong>' . $quote['email'] . '</strong></div>
                                                </div>
                                                <div class="clientinfo creditsection">                              
                                                    <div type="text" >Company Credit Limit &nbsp;: <strong>' . number_format($quote['credit_limit'],2) . '</strong><br>Credit Period &nbsp;: <strong>' . $quote['credit_period'] . '(Days)</strong><br><br><strong>Available Credit Limit&nbsp;: ' . number_format($quote['avalable_credit_limit'],2) . '</strong>
                                                    <input type="hidden" id="available_credit" value="'.number_format($quote['avalable_credit_limit'],2).'"><input type="hidden" id="avalable_credit_limit" value="'.number_format($quote['avalable_credit_limit'],2).'"></div>
                                                </div>';
                                                echo '</div>';
                                                }
                                                else{
                                                ?>
                                        <div class="clientinfo">
                                            <?php //echo $this->lang->line('Client Details') ?>
                                            <div id="customer_name"></div>
                                        </div>
                                        <div class="clientinfo">

                                            <div id="customer_address1"></div>
                                        </div>

                                        <div class="clientinfo">

                                            <div type="text" id="customer_phone"></div>
                                        </div>
                                        <div id="customer_pass"></div>
                                        <?php } ?>
                                        <div id="customer_pass"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                                <?php
                                        if($quote['prepared_flag']=='1')
                                        {
                                            $disableclass = "";
                                        }
                                        else{
                                            $disableclass = "disable-class";
                                        }
                                    ?>
                                <div class="inner-cmp-pnl">
                                    <div class="form-row">

                                        <div class="col-sm-12">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Quote Properties') ?>
                                            </h3>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Quote Number') ?></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="hidden" class="form-control" placeholder="Quote #"
                                                    name="invocieno" id="invocieno" value="<?php echo $quote_tid; ?>"
                                                    readonly>
                                                <input type="text" class="form-control" placeholder="Quote #"
                                                    name="quote_number" id="quote_number"
                                                    value="<?php echo $quote_number; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="reference" class="col-form-label">
                                                <?php echo $this->lang->line('Reference') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" <?=$frmelmentdisable?>
                                                    placeholder="Reference #" name="reference" id="reference"
                                                    value="<?php echo $quote['reference']; ?>"
                                                    data-original-value="<?php echo $quote['reference']; ?>">

                                            </div>
                                        </div>
                                        <!--erp2024 newly added 29-09-2024  -->
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_reference_number"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                                <input type="text" name="customer_reference_number"
                                                    id="customer_reference_number" class="form-control"
                                                    placeholder="Customer Reference Number"
                                                    value="<?php echo $quote['customer_reference_number'] ?>"
                                                    <?=$frmelmentdisable?>
                                                    data-original-value="<?php echo $quote['customer_reference_number']; ?>">
                                            </div>
                                        </div>

                                        <?php //if (isset($employee)){?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="employee"
                                                class="col-form-label"><?php echo $this->lang->line('Assign to') ?></label>
                                            <?php
                                                        if($quote['quotestatus']=='Assigned' && $quote['employee_id']==$this->session->userdata('id')){ ?>
                                            <button type="button"
                                                class="btn btn-crud btn-sm revert-btncolor <?=$stage_after_sent?>"
                                                id="reverted-by-admin-btn"><?php echo $this->lang->line('Revert To') ?></button>
                                            <?php } ?>
                                            <input type="hidden" name="oldemployee" value="<?=$quote['employee_id']?>">
                                            <select name="employee" id="employee"
                                                class=" col form-control <?=$frmselectdisable?>" <?=$frmelmentdisable?>
                                                data-original-value="<?php echo $quote['employee_id']; ?>">
                                                <?php echo '<option value="">* Not Assigned</option>'; ?>
                                                <?php foreach ($employee as $row) {
                                                                $sel = "";
                                                                if($quote['employee_id']==$row['id']){
                                                                    $sel="Selected";
                                                                }
                                                                echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['name'].'</option>';
                                                            } ?>
                                            </select>
                                        </div>
                                        <?php //} ?>
                                        <?php if ($exchange['active'] == 1){ ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="mcurrency"
                                                class="col-form-label"><?php echo $this->lang->line('Payment Currency client'). ' <small>' . $this->lang->line('based on live market'); ?></label>
                                            <select name="mcurrency"
                                                class="selectpicker form-control <?=$frmselectdisable?>"
                                                <?=$frmelmentdisable?>>
                                                <option value="0">Default</option>
                                                <?php foreach ($currency as $row) {
                                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['product_code'] . ')</option>';
                                                        } ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="pterms"
                                                class="col-form-label"><?php echo $this->lang->line('Payment Terms'); ?></label>
                                            <select name="pterms" title="Payment Terms"
                                                data-original-value="<?php echo $term; ?>"
                                                class="selectpicker form-control" <?=$required?>>
                                                <option value="">Select Payment Term</option>
                                                <?php 
                                                    foreach ($terms as $row) {
                                                        $selected="";
                                                        if($row['id'] == $term){
                                                            $selected="selected";
                                                        }   
                                                        echo '<option value="' . $row['id'] . '" '.$selected.'>' . $row['title'] . '</option>';
                                                    } ?>
                                            </select>
                                        </div>

                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_contact_person"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                                <input type="text" name="customer_contact_person"
                                                    id="customer_contact_person" class="form-control"
                                                    placeholder="Customer Contact Person"
                                                    value="<?php echo $quote['customer_contact_person'] ?>"
                                                    data-original-value="<?php echo $quote['customer_contact_person']; ?>"
                                                    <?=$frmelmentdisable?>>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_contact_number"
                                                    class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                                <input type="text" name="customer_contact_number"
                                                    id="customer_contact_number" class="form-control"
                                                    placeholder="Contact Person Number"
                                                    value="<?php echo $quote['customer_contact_number'] ?>"
                                                    <?=$frmelmentdisable?>
                                                    data-original-value="<?php echo $quote['customer_contact_number']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <div class="frmclasss"><label for="customer_contact_email"
                                                    class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                                <input type="email" name="customer_contact_email"
                                                    id="customer_contact_email" class="form-control"
                                                    placeholder="Customer Contact Email"
                                                    value="<?php echo $quote['customer_contact_email'] ?>"
                                                    <?=$frmelmentdisable?>
                                                    data-original-value="<?php echo $quote['customer_contact_email']; ?>">
                                            </div>
                                        </div>
                                        <!--erp2024 newly added 29-09-2024 ends -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="invociedate" class="col-form-label">
                                                <?php echo $this->lang->line('Quote Date') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <span class="icon-calendar4" aria-hidden="true"></span>
                                                </div>
                                                <input type="date" class="form-control" <?=$frmelmentdisable?>
                                                    placeholder="Billing Date" name="quote_date"
                                                    value="<?php echo $quote['quote_date'] ?>"
                                                    data-original-value="<?php echo $quote['quote_date']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieduedate"
                                                class="col-form-label"><?php echo $this->lang->line('Quote Validity') ?>
                                                <span class="compulsoryfld">*</span></label>
                                            <input type="date" class="form-control" name="invocieduedate"
                                                placeholder="Due Date" autocomplete="false"
                                                value="<?php echo $due_date ?>" <?=$frmelmentdisable?>
                                                data-original-value="<?php echo $due_date; ?>">
                                            <!-- <input type="text" class="form-control required date30_plus" name="invocieduedate" placeholder="Due Date" data-toggle="datepicker" autocomplete="false"> -->
                                        </div>
                                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="taxformat"
                                                    class="col-form-label"> <?php echo $this->lang->line('Tax') ?></label>
                                                <select class="form-control"
                                                        onchange="changeTaxFormat(this.value)"
                                                        id="taxformat">
                                                    <?php echo $taxlist; ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                    <label for="discountFormat"
                                                        class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                                    <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                            id="discountFormat">
                                                        <?php //echo $this->common->disclist() ?>
                                                    </select>
                                            </div> -->

                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label class="col-form-label"
                                                for="name"><?php echo $this->lang->line('Warehouse'); ?></label>
                                            <select id="s_warehouses"
                                                class="selectpicker form-control <?=$frmselectdisable?>"
                                                <?=$frmelmentdisable?>>
                                                <?php echo default_warehouse();
                                                            echo '<option value="">' . $this->lang->line('All') ?>
                                                </option><?php foreach ($warehouse as $row) {
                                                                $sel ="";
                                                                if($quote['due_date'] == $row['id'])
                                                                {
                                                                    $sel = "selected";
                                                                }
                                                                echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] . '</option>';
                                                            } ?>

                                            </select>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="notes"
                                                class="col-form-label"><?php echo $this->lang->line('Quote Note') ?></label>
                                            <textarea class="form-textarea <?=$frmselectdisable?>" id="notes"
                                                name="notes" rows="2" <?=$frmelmentdisable?>
                                                title="<?php echo $this->lang->line('Quote Note') ?>"
                                                data-original-value="<?php echo $quote['notes']; ?>"><?php echo $quote['notes'] ?></textarea>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="contents"
                                                class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                                            <textarea class="summernote1 form-textarea <?=$frmselectdisable?>"
                                                name="propos" id="contents" rows="2"
                                                title="<?php echo $this->lang->line('Proposal Message') ?>"
                                                <?=$frmelmentdisable?>
                                                data-original-value="<?php echo $quote['proposal']; ?>"><?php echo $quote['customer_message'] ?></textarea>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="toAddInfo" class="col-form-label"></label>
                                            <button type="button" class="btn  btn-crud btn-sm btn-secondary mt-3"
                                                id="attachment-btn"><i class="fa fa-paperclip" aria-hidden="true"></i>
                                                Add Attachment</button>
                                        </div>

                                        <!-- Image upload sections starts-->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                            <label for="upfile-0"
                                                class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="d-flex">
                                                        <input type="file" name="upfile[]" id="upfile-0"
                                                            class="form-control1 input-file"
                                                            accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                        <img class="blah" src="" alt="your image"
                                                            style="margin-left:10px; width:50px; height:50px;">
                                                        <button type="button"
                                                            class="btn btn-crud btn-secondary btn-sm delete-btn"
                                                            style="height:30px; height:30px; margin:3px;"
                                                            title="Remove"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                    <div id="uploadsection"></div>
                                                </div>
                                                <div class="col-4">
                                                    <button class="btn btn-crud btn-secondary btn-sm mt-1"
                                                        id="addmore_img" title="Add More Files" type="button"><i
                                                            class="fa fa-plus-circle"></i> Add More</button>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- Image upload sections ends -->
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
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
                                                                echo "<br><a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary file-download'>{$icon}</a>&nbsp;";
                                                                echo "<button class='btn btn-crud btn-sm btn-secondary file-delete' onclick=\"deleteitem('{$image['id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
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
                    </div>

                    <!-- <div class="row">
                            <div class="col">
                                <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                                <textarea class="summernote1 form-textarea" name="propos" id="contents" rows="2"></textarea></div>
                        </div> -->
                    <?php
                   
                    if($quote['quotestatus']=='draft')
                    { ?>
                    <!-- <div class="alert alert-warning alert-success fade show" role="alert">
                            <strong>Draft</strong> Saved Successfully.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div> -->
                    <?php } ?>

                    <div class="col-12 form-row mt-1 discount-toggle">
                        <div class="col-lg-4 col-md-5 col-12">
                            <div class="form-check">
                                <input class="form-check-input discountshowhide" type="checkbox" value="2"
                                    name="discountshowhide" id="discountshowhide">
                                <label class="form-check-label dicount-checkbox" for="discountshowhide">
                                    <b><?php echo $this->lang->line('Would you like to add a discount for these products?'); ?></b>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-12">
                            <div id="notification-color">
                                <label class="form-check-label">
                                    <i
                                        style="background:#ffb9c2; width:15px; height:15px; display:inline-block; border-radius:3px; margin-right:5px;"></i>
                                    <span style="font-size:14px;"><b>Onhand quantity is less than Reorder
                                            quantity.</b></span></label>
                            </div>
                        </div>


                    </div>


                    <div id="compare_result"></div>
                    <div id="saman-row" class="overflow-auto">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>


                                <tr class="item_header bg-gradient-directional-blue white">
                                    <!-- <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Decription & No') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Rate') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Min. Price') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Discount') ?>(%)</th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                                <th width="10%" class="text-center">
                                    <?php echo $this->lang->line('Amount') ?>
                                    (<?php //echo $this->config->item('currency'); ?>)
                                </th>
                                <th width="" class="text-center"><?php echo $this->lang->line('Action') ?></th> -->
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="4%" class="text-center"><?php echo $this->lang->line('SN') ?></th>
                                    <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?>
                                    </th>
                                    <th width="22%" class="text-center1 pl-1">
                                        <?php echo $this->lang->line('Item Name') ?></th>
                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                                    <th width="7%" class="text-right"><?php echo $this->lang->line('Selling Price') ?>
                                    </th>
                                    <th width="7%" class="text-right"><?php echo $this->lang->line('Lowest Price') ?>
                                    </th>
                                    <?php  //Verify that tax is enabled
                                $colspan = 8;
                                $discount_flag=0;
                                if($configurations['config_tax']!='0'){ 
                                    $colspan = 10;    
                                ?>
                                    <th width="10%" class="text-right"><?php echo $this->lang->line('Tax'); ?>(%) /
                                        <?php echo $this->lang->line('Amount'); ?></th>
                                    <?php } ?>
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Max discount %')?>
                                    </th>
                                    <th width="12%" class="text-center discountcoloumn d-none">
                                        <?php echo $this->lang->line('Discount')?>/
                                        <?php echo $this->lang->line('Amount'); ?></th>
                                    <th width="10%" class="text-right">
                                        <?php echo $this->lang->line('Amount') ?>
                                        <!-- (<?php //echo $this->config->item('currency'); ?>) -->
                                    </th>
                                    <th width="8%" class="text-center1"><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>


                                <?php 
                            $i = 0;
                            $totaldiscount =0;
                            $grandtotal =0;
                            $withtax =0;
                            $grandtotal = 0;
                            $k=1;
                            $alert_notification = 0;
                            if(!empty($products))
                            {
                              
                                foreach ($products as $row) {                                    
                                    $product_name_with_code = $row['product_name'].'('.$row['product_code'].') - ';

                                    if($row['discount']>0 && $discount_flag==0)
                                    {
                                        $discount_flag =1;
                                    }
                                    if($row['totalQty']<=$row['alert']){
                                        echo '<tr style="background:#ffb9c2;">';
                                         $alert_notification = 1;
                                    }
                                    else{
                                        echo '<tr >';
                                    }
                                    // echo '<td width="1%"><input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" onclick="selectPrdts(\''.$row['pid'].'\')"> </td>';
                                    echo "<td class='text-center serial-number'>".$k++."</td>";
                                    echo '<td><input type="text" class="form-control " name="code[]" placeholder="Enter Product Code / ID"  value="' . $row['product_code'] . '" '.$frmelmentdisable.' id="code-' . $i . '" onkeyup="product_edit_autocomplete(' . $i . ')"> </td>';

                                    echo '<td><input type="text" class="form-control" name="product_name[]" placeholder="Enter Product name or Code"  value="' . $row['product_name'] . '" '.$frmelmentdisable.' id="productname-' . $i . '" onkeyup="product_edit_autocomplete(' . $i . ')" > </td>';
                                    
                                    echo '<td class="position-relative"><input type="number" class="form-control req amnt" name="product_qty[]" title="'.$product_name_with_code.'Quantity" id="amount-' . $i . '" onkeypress="return isNumber(event)" oninput="isPositiveNumber(event, this)" onkeyup="rowTotal(' . $i . '), billUpyog(), compare_with_old_new_grand_totals(),check_on_hand_quantity()"autocomplete="off" value="' . intval($row['quantity']) . '" '.$frmelmentdisable.' '.$sent_input.' data-original-value="' . intval($row['quantity']) . '"><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['quantity']) . '" ><div class="tooltip1"></div></td>'; 
                                    echo '<td class="text-center"><strong id="onhandQty-'.$i.'">'.$row['totalQty'].'</strong></td>';

                                    echo '<td class="text-right"><strong id="pricelabel-' . $i . '">' . amountExchange_s($row['price'], $quote['multi'], $this->aauth->get_user()->loc) . '</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off" value="' . amountExchange_s($row['price'], $quote['multi'], $this->aauth->get_user()->loc) . '"></td>'; 

                                   echo '<td class="text-right">
                                        <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' .$row['lowest_price']. '">
                                        <strong id="lowestpricelabel-' . $i . '">' .$row['lowest_price']. '</strong>
                                        </td>';

                                    $taxtd ="";
                                    $totaltax = $totaltax+amountExchange_s($row['total_tax'], $quote['multi'], $this->aauth->get_user()->loc);
                                    $totaldiscount = $totaldiscount+amountExchange_s($row['total_discount'], $quote['multi'], $this->aauth->get_user()->loc);
                                    $prdtotal = amountExchange_s($row['total_amount'], $quote['multi'], $this->aauth->get_user()->loc);
                                    $withtax = $withtax +$prdtotal + $totaltax;
                                    $grandtotal = ($grandtotal + $prdtotal);
                                    // erp2024 27-03-2025 discount amount calcualation
                                    $maxdiscountamount=0;
                                    $productprice = amountExchange_s($row['price'], $quote['multi'], $this->aauth->get_user()->loc);
                                    $maxdiscountamount = round(($productprice * $row['maximum_discount_rate']) / 100, 2);
                                    $row['maximum_discount_rate'] = (intval($row['maximum_discount_rate']) == floatval($row['maximum_discount_rate']))  ? intval($row['maximum_discount_rate']) : number_format($row['maximum_discount_rate'], 2);

                                    if($configurations["config_tax"]!="0"){        
                                        echo '<td class="text-center">
                                            <div class="text-center">                                                
                                                <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off"  value="' . amountFormat_general($row['tax']) . '">
                                                <strong id="taxlabel-' . $i . '"></strong>&nbsp;<strong  id="texttaxa-' . $i . '">' .$row['tax']. '/'. amountExchange_s($row['total_tax'], $quote['multi'], $this->aauth->get_user()->loc) . '</strong>
                                            </div>
                                        </td>';
                                    } 
                                //    echo '<td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off"  value="' . amountFormat_general($row['tax']) . '">/</td>';
                                echo '<td class="text-center"><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-'.$i.'" value="' . $row['maximum_discount_rate'] . '"><input type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-'.$i.'" value="' . $maxdiscountamount . '"><strong id="maxdiscountratelabel-' . $i . '">' .$row['maximum_discount_rate'].'% ('.$maxdiscountamount.')</strong></td>';                              
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
                                echo '<td class="text-center discountcoloumn d-none">
                                        <div class="input-group text-center">
                                            <select name="discount_type[]" title="'.$product_name_with_code.'Discount Type" id="discounttype-' . $i . '" class="form-control element-height '.$frmselectdisable.' '.$sent_input_disable.'" onchange="discounttypeChange(' . $i . ')" '.$frmelmentdisable.' '.$sent_input.' data-original-value="' . $row['discount_type'].'">
                                                <option value="Perctype" '.$percsel.'>%</option>
                                                <option value="Amttype" '.$amtsel.'>Amt</option>
                                            </select>&nbsp;
                                            <input type="number" min="0" class="form-control discount element-height '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '" '.$frmelmentdisable.' '.$sent_input.' title="'.$product_name_with_code.'Discount Percentage" data-original-value="' . $disperc.'">
                                            <input type="number" min="0" class="form-control discount element-height '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '" '.$frmelmentdisable.' '.$sent_input.' title="'.$product_name_with_code.'Discount Amount" data-original-value="' . $disamt.'">
                                        </div>                                    
                                        <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel">Amount : ' . amountExchange_s($row['total_discount'], $quote['multi'], $this->aauth->get_user()->loc) . '</strong>
                                        <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                    </td>';
                                    
                                    // echo '<td><input type="text" class="form-control discount" name="product_discount[]"
                                    //         onkeypress="return isNumber(event)" id="discount-' . $i . '"
                                    //         onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '"></td>
                                    echo '<td class="text-right ">
                                        <strong><span class="ttlText" id="result-' . $i . '">' . amountExchange_s($row['total_amount'], $quote['multi'], $this->aauth->get_user()->loc) . '</span></strong></td>
                                    <td class="text-center1 d-flex"><button onclick="producthistory('.$i.')" type="button" class="btn btn-crud btn-sm btn-secondary producthis" title="Previous Quoted History"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary" title="Product Informations"><i class="fa fa-info"></i></button>&nbsp;';
                                    if(empty($sent_input_disable))
                                    {
                                        echo '<button type="button" data-rowid="' . $i . '" class="btn btn-crud btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button> ';
                                        
                                    }
                                    echo ' </td>
                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['total_tax'], $quote['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['total_discount'], $quote['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . amountExchange_s($row['total_amount'], $quote['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['product_code'] . '">
                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['product_code'] . '">
                                </tr>';
                                $i++;
                                } 
                            }
                            else{
                            ?>
                                <tr>
                                    <td class="text-center serial-number">1</td>
                                    <td><input type="text" class="form-control required" name="code[]"
                                            placeholder="<?php echo $this->lang->line('Enter Product Code / ID') ?>"
                                            id='code-0'>
                                    </td>
                                    <td><input type="text" class="form-control required" name="product_name[]"
                                            placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                            id='productname-0'>
                                    </td>
                                    <td class="text-center position-relative"><input type="number"
                                            class="form-control req amnt" name="product_qty[]" id="amount-0"
                                            onkeypress="return isNumber(event)"
                                            onkeyup="rowTotal('0'), billUpyog(),check_on_hand_quantity()"
                                            autocomplete="off" value="" oninput="isPositiveNumber(event, this)">
                                        <div class="tooltip1"></div>
                                    </td>
                                    <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                    <td class="text-right">
                                        <strong id="pricelabel-0"></strong>
                                        <input type="hidden" class="form-control req prc" name="product_price[]"
                                            id="price-0" onkeypress="return isNumber(event)"
                                            onkeyup="rowTotal('0'), billUpyog()" autocomplete="off">
                                    </td>
                                    <td class="text-right">
                                        <input type="hidden" class="form-control" name="lowest_price[]"
                                            id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                        <strong id="lowestpricelabel-0"></strong>
                                    </td>
                                    <?php //Verify that tax is enabled
                                if($configurations['config_tax']!='0'){ ?>
                                    <td class="text-center">
                                        <div class="text-center">
                                            <input type="hidden" class="form-control" name="product_tax[]" id="vat-0"
                                                onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                autocomplete="off">
                                            <strong id="taxlabel-0"></strong>&nbsp;<strong id="texttaxa-0"></strong>
                                        </div>
                                    </td>
                                    <?php } ?>
                                    <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input
                                            type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"><input
                                            type="hidden" name="maxdiscountamount[]" id="maxdiscountamount-0" value="">
                                    </td>

                                    <td class="text-center discountcoloumn d-none">
                                        <div class="input-group text-center">
                                            <select name="discount_type[]" id="discounttype-0"
                                                class="form-control element-height <?=$frmselectdisable?>"
                                                onchange="discounttypeChange(0)">
                                                <option value="Perctype">%</option>
                                                <option value="Amttype">Amt</option>
                                            </select>&nbsp;
                                            <input type="number" min="0" class="form-control discount element-height"
                                                name="product_discount[]" onkeypress="return isNumber(event)"
                                                id="discount-0" autocomplete="off" onkeyup="discounttypeChange(0)">
                                            <input type="number" min="0"
                                                class="form-control discount d-none element-height" name="product_amt[]"
                                                onkeypress="return isNumber(event)" id="discountamt-0"
                                                autocomplete="off" onkeyup="discounttypeChange(0)">
                                        </div>
                                        <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                        <div><strong id="discount-error-0"></strong></div>
                                    </td>

                                    <td class="text-right">
                                        <strong><span class='ttlText' id="result-0">0</span></strong>
                                    </td>
                                    <td class="text-center1">
                                        <button onclick='producthistory("0")' type="button"
                                            class="btn btn-crud btn-sm btn-secondary producthis"
                                            title="Previous Quoted History"><i class="fa fa-history"></i>
                                        </button>&nbsp;
                                        <button onclick='single_product_details("0")' type="button"
                                            class="btn btn-crud btn-sm btn-secondary" title="Product Informations"><i
                                                class="fa fa-info"></i></button>&nbsp;
                                        <button type="button" data-rowid="0" class="btn btn-sm btn-secondary removeProd"
                                            title="Remove" fdprocessedid="bl1z3o"> <i class="fa fa-trash"></i> </button>
                                    </td>
                                    <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                    <input type="hidden" name="disca[]" id="disca-0" value="0">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0"
                                        value="0">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                    <input type="hidden" name="unit[]" id="unit-0" value="">
                                    <input type="hidden" name="hsn[]" id="hsn-0" value="">

                                </tr>

                                <?php } ?>
                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border" colspan="9">
                                        <?php 
                                    //if(($quote['approval_flag']==1)  && $approvedby['id']==$this->session->userdata('id')){ ]
                                    //quote_draft_add_btn ?>
                                        <button type="button"
                                            class="btn btn-crud btn-secondary <?=$hideapprovedbymels?> <?=$acceptedcls?> <?=$addrowcls?> <?=$stage_after_sent?> add-row-btn"
                                            title="Add product row" id="lead_create_btn">
                                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                        <?php //} ?>
                                    </td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>

                                <tr>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">

                                    </div>
                                </tr>
                                <?php 
                            if($configurations['config_tax']!='0'){ ?>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="9" align="right" class="no-border td-colspan">
                                        <input type="text" value="0" id="subttlform"
                                            name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?>
                                            <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?>
                                        </strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border">
                                        <span id="taxr" class="lightMode">0</span>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="9" align="right" class="no-border td-colspan">
                                        <strong><?php echo $this->lang->line('Total Discount') ?>
                                            <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                        <span id="discs" class="lightMode"><?=number_format($totaldiscount,2)?></span>
                                    </td>
                                </tr>

                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="9" align="right" class="no-border td-colspan">
                                        <strong><?php echo $this->lang->line('Shipping') ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border"><input type="text"
                                            class="form-control shipVal" onkeypress="return isNumber(event)"
                                            placeholder="Value" name="shipping" autocomplete="off"
                                            onkeyup="billUpyog()">
                                        ( <?php echo $this->lang->line('Tax') ?>
                                        <span id="ship_final">0</span> )
                                    </td>
                                </tr>

                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="9" align="right" class="no-border td-colspan">
                                        <strong><?php echo $this->lang->line('Grand Total') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                        <span id="grandtotaltext"><?=number_format($grandtotal,2)?></span>
                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"
                                            value="<?=$grandtotal?>" readonly>

                                    </td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="4" class="no-border">
                                        <?php
                                $prepared_label =  $this->lang->line('Update');
                                $prepared_btn_color = "btn-secondary";
                                $convert_btn_color = "btn-primary";
                                $unsavedisable_btns = "unsavedisable-btns";
                                if(empty($quote) || $quote['prepared_flag']=='0') 
                                { 
                                    $unsavedisable_btns = "";
                                    $prepared_label =  $this->lang->line('Prepared');
                                    $prepared_btn_color = "btn-primary";
                                    $convert_btn_color = "btn-secondary";
                                }
                                 if($quote['quotestatus']!='draft'){ ?>
                                        <button type="button"
                                            class="btn btn-crud btn-lg btn-secondary revert-btncolor d-none <?=$hideapprovedbymels?> <?=$revertbtncls?> <?=$stage_after_sent?>"
                                            id="completion-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                                        <?php
                                 }
                                 if($quote['approval_flag']=='1' && $quote['approved_by']==$this->session->userdata('id')  && $quote['quotestatus']!='accepted'){ ?>
                                        <button type="button"
                                            class="btn btn-crud btn-lg btn-secondary revert-btncolor d-none <?=$stage_after_sent?>"
                                            id="reverted-by-admin-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                                        <?php }  ?>
                                        <input type="submit"
                                            class="btn btn-crud btn-lg btn-secondary sub-btn <?=$unsavedisable_btns?> <?=$hideapprovedbymels?> <?=$addrowcls?> <?=$stage_after_sent?>"
                                            value="<?php echo $this->lang->line('Save As Draft') ?>"
                                            title="<?php echo $this->lang->line('Save As Draft') ?>"
                                            id="quote_draft_btn">
                                    </td>
                                    <td align="right" colspan="7" class="no-border">


                                        <?php                                         
                                    if($quote['approval_flag']=='0' && $quote['prepared_flag']=='1'){ ?>
                                        <input type="submit"
                                            class="btn  btn-crud btn-lg btn-secondary sub-btn d-none <?=$stage_after_sent?>"
                                            value="<?php echo $this->lang->line('Assign To An Employee') ?>"
                                            title="<?php echo $this->lang->line('Assign To An Employee') ?>"
                                            id="quote_approve_btn">
                                        <!-- <input type="submit" class="btn  btn-crud btn-lg btn-primary quote-send-btn <?=$approvebtn?> <?=$accpetthenhide?> <?=$stage_after_sent?>"  value="<?php echo $this->lang->line('Send')." ".$this->lang->line('Quote') ?>" data-loading-text="Creating..."> -->
                                        <?php  }
                                        else if($quote['approval_flag']=='1' && $quote['prepared_flag']=='1'){ 
                                        if(($quote['quotestatus']=='Sent')){ ?>
                                        <a href="<?= base_url('quote') ?>"
                                            class="btn btn-crud btn-lg btn-secondary sub-btn d-none"
                                            title="<?php echo $this->lang->line('Back to Quotes') ?>"><?php echo $this->lang->line('Back to Quotes') ?></a>&nbsp;
                                        <?php if($quote['convert_flag']==0){ ?>
                                        <button type="button"
                                            class="btn btn-crud btn-lg btn-primary d-none sub-btn <?=$converted_class?>"
                                            onclick="convertToSalesOrder1('<?=$quote_id?>')"
                                            title="<?php echo $this->lang->line('Convert to Sales Order') ?>"><?php echo $this->lang->line('Convert to Sales Order') ?></button>&nbsp;

                                        <?php } }?>

                                        <button type="button"
                                            class="btn btn-crud btn-lg btn-primary sub-btn <?=$printpick_bgcolor?> <?=$hidecls?>  <?=$hideapprovedbymels?> <?=$stage_after_sent?> d-none"
                                            id="quote-accept-btn"
                                            title="<?php echo $this->lang->line('Accept & Send') ?>"><?php echo $this->lang->line('Accept & Send') ?></button>

                                        <!-- <i class="fa fa-forward" aria-hidden="true"></i>
                                        <input type="submit" class="btn btn-lg btn-primary sub-btn <?=$pickrecievedflg?>" value="Create Delivery Note" id="submit-deliverynote"  data-loading-text="Updating..."> -->
                                        <?php }
                                    else{ }

                                    if($quote['approval_flag']=='1' && $quote['approved_by']==$this->session->userdata('id') && $quote['accepted']!='1' && $quote['quotestatus']!='accepted')
                                    {
                                    ?>
                                        <input type="submit"
                                            class="btn btn-crud btn-lg btn-secondary sub-btn  d-none <?=$stage_after_sent?>"
                                            value="<?php echo $this->lang->line('Reassign To An Employee') ?>"
                                            title="Assign/Reassign to an Employee" id="quote_approve_btn">


                                        <?php }
                                    if($quote['convert_flag']=='2')
                                    {
                                        echo '<a href="' . base_url("SalesOrders/salesorder_new?id=$quote_id") . '&token=1" class="btn btn-crud btn-lg btn-secondary sub-btn"  title="sales order">Sales Order(s)</span></a>';
                                     }
                                     
                                    
                                    ?>
                                        <input type="submit"
                                            class="btn btn-crud btn-lg sub-btn <?=$prepared_btn_color?> sub-btn <?=$stage_after_sent?> <?= $prepared_class?> <?=$unsavedisable_btns?>"
                                            value="<?php echo $prepared_label; ?>" id="quote_create_btn"
                                            title="<?php echo $prepared_label; ?>">
                                        <?php
                                    //  if(empty($quote) || $quote['prepared_flag']=='0')
                                    //  {
                                    if($last_approval_step==2)
                                    {
                                        $approval_complete_class = "";
                                        $approval_complete_label = "Send Quote";
                                    }
                                    else{
                                        $approval_complete_class = 'disabled';
                                        $approval_complete_label = "All approvals are not completed.";
                                    }
                                        
                                    ?>
                                        <input type="submit" class="btn btn-crud btn-lg sub-btn <?=$convert_btn_color?> quote-send-btn <?=$approvebtn?> <?=$accpetthenhide?> <?=$stage_after_sent?>"
                                            value="<?php echo $this->lang->line('Send')." ".$this->lang->line('Quote') ?>"
                                            title="<?php echo $approval_complete_label; ?>"
                                            data-loading-text="Creating..." <?=$approval_complete_class?>>
                                        <?php //}
                                     if(($quote) && ($quote['convert_flag']==0 && $quote['quotestatus'] == "Sent") ){ ?>

                                        <button type="button"
                                            class="btn btn-crud btn-lg btn-primary converttobtn sub-btn <?php echo $approvedcls; ?> <?=$convertbtn?> <?=$converted_class?> d-none1"
                                            title="Convert to Sales Order"
                                            onclick="convertToSalesOrder1('<?=$quote['quote_number']?>')">
                                            <?php echo $this->lang->line('Convert to Sales Order') ?> </button>
                                        <?php } ?>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                    <!-- <input type="hidden" value="quote/action" id="action-url"> -->
                    <input type="hidden" value="search" id="billtype">
                    <input type="hidden" value="<?=$quote['lead_number']?>" name="lead_number" id="lead_number">
                    <input type="hidden" value="<?=count($products)?>" name="counter" id="ganak">
                    <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
                    <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
                    <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax"
                        id="configured_tax">
                    <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
                    <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">

                    <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                        name="discountFormat" id="discount_format">
                    <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                        name="shipRate" id="ship_rate">
                    <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                        id="ship_taxtype">
                    <input type="hidden" value="0" name="ship_tax" id="ship_tax">
                    <input type="hidden" value="0" name="drafttxt" id="drafttxt">
                    <input type="hidden" value="<?=$this->session->userdata('draftquote_id')?>" name="draftid"
                        id="draftid">
                    <input type="hidden" name="oldtotal" id="oldtotal" class="form-control"
                        value="<?= amountExchange_s($quote['total'], $quote['multi'], $this->aauth->get_user()->loc); ?>"
                        readonly>
                    <input type="hidden" value="<?=$quote['quotestatus']?>" name="quote_status" id="quote_status">
                    <input type="hidden" value="<?=$quote['prepared_flag']?>" name="prepared_flag" id="prepared_flag">
                    <input type="hidden" value="<?=$quote['approval_flag']?>" name="approvalflg" id="approvalflg">


                </form>
            </div>

        </div>
    </div>
</div>


<!-- Modal HTML -->
<div id="sendEmail" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Send Proposal</h4>
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
                            <label for="email" class="col-form-label"><?php echo $this->lang->line('Email') ?></label>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-envelope-o" aria-hidden="true"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Email" name="mailtoc"
                                    value="<?php echo $quote['email'] ?>">
                            </div>

                        </div>

                    </div>


                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Customer Name') ?></label>
                            <input type="text" class="form-control" name="customername"
                                value="<?php echo $quote['name'] ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Subject') ?></label>
                            <input type="text" class="form-control" name="subject" id="subject">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Message') ?></label>
                            <textarea name="text" class="summernote" id="content" title="Contents"></textarea>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" id="invoicemployee_id" name="tid"
                        value="<?php echo $quote['quote_number'] ?>">
                    <input type="hidden" class="form-control" id="emailtype" value="">


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn  btn-crud btn-secondary"
                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                <button type="button" class="btn  btn-crud btn-primary"
                    id="sendM"><?php echo $this->lang->line('Send') ?></button>
            </div>
        </div>
    </div>
</div>
<!--sms-->
<!-- Modal HTML -->
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
                    <h3>Coming soon...</h3>
                    <!-- <div class="row">
                        <div class="col">
                            <label for="phone" class="col-form-label"><?php echo $this->lang->line('Phone') ?></label>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="icon-envelope-o" aria-hidden="true"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="SMS" name="mobile"
                                    value="<?php echo $quote['phone'] ?>">
                            </div>

                        </div>

                    </div>


                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Customer Name'); ?></label>
                            <input type="text" class="form-control" value="<?php echo $quote['name'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col"><label for="shortnote"
                                class="col-form-label"><?php echo $this->lang->line('Message'); ?></label>
                            <textarea class="form-control summernote" name="text_message" id="sms_tem" title="Contents"
                                rows="3"></textarea>
                        </div>
                    </div>


                    <input type="hidden" class="form-control" id="smstype" value=""> -->


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn  btn-crud btn-secondary"
                    data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                <!-- <button type="button" class="btn btn-primary"id="submitSMS"><?php echo $this->lang->line('Send'); ?></button> -->
            </div>
        </div>
    </div>
</div>



<script>
const changedFields = {};


$(document).ready(function() {
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

    $("#notification-color").hide();
    var discountflag = <?=$discount_flag?>;
    if (discountflag == 1) {
        showdiscount_potion();
    }
    if ($("#quote_status").val() == "Sent") {
        disable_items();
        $(".converttobtn, .dropdown-toggle").removeClass("disable-class").prop("disabled", false);
    }
    $("#employee").select2({
        width: "100%" // Sets the width to 100%
    });

    // Add event listeners to all input fields
    document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function() {
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
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            }
        });
    });

    var alert_notification = <?=$alert_notification?>;
    if (alert_notification == 1) {
        $("#notification-color").show();
    }

});

//Function for select2 type dropdown
$(document).on('select2:select select2:unselect', '.select2-hidden-accessible', function(e) {
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
        const newValueLabels = newValueArray.map(function(value) {
            const option = $('option[value="' + value + '"]', e.target);
            return option.length ? option.text() : ''; // Get the label (text) of the selected option
        });

        const newValue = newValueLabels.join(','); // Convert array of labels to string
        const originalLabels = Array.isArray(originalValue) ? originalValue.map(function(value) {
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



$(function() {
    $('.summernote').summernote({
        height: 100,
        tooltip: false,
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

$(document).ready(function() {
    $("#data_form").validate($.extend(true, {}, globalValidationExpandLevel, {
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {
            cst: {
                required: function() {
                    return $('#customer_id').val() == 0;
                }
            },
            invocieduedate: {
                required: true
            },
            customer_contact_number: {
                phoneRegex: true
            },
            // customer_reference_number : { required: true },
            // employee: { required: true }
        },
        messages: {
            cst: "Select customer",
            invocieduedate: "Enter Quote Deadline",
            // customer_reference_number: "Enter Customer Reference",
            // employee: "Select an Employee"
        }
    }));
    $("#approved_cancellation_form").validate($.extend(true, {}, globalValidationExpandLevel, {
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {          
            cancelreason: {
                required: true
            },
        },
        messages: {
            cancelreason: "Enter the Reason For Cancellation ",
        }
    }));

    $('#quote_create_btn').on('click', function(e) {

        e.preventDefault(); // Prevent the default form submission
        $('#quote_create_btn').prop('disabled', true); // Disable button to prevent multiple submissions
        var selectedProducts1 = [];
        if (parseInt($("#customer_id").val()) == 0) {
            Swal.fire({
                text: "Please Select Customer",
                icon: "info"
            });
            $('#quote_create_btn').prop('disabled', false);
            return;
        }
        $('.amnt').each(function() {
            if ($(this).val() > 0) {
                selectedProducts1.push($(this).val());
            }
        });

        if (selectedProducts1.length === 0) {
            Swal.fire({
                text: "To proceed, please enter quantity for at least one item",
                icon: "info"
            });
            $('#quote_create_btn').prop('disabled', false);
            return;
        }

        // Validate the form
        if ($("#data_form").valid()) {
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            formData.append('changedFields', JSON.stringify(changedFields));
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create a new quote?",
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
                        url: baseurl +
                        'quote/action', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }


                            if ($("#quote_id").val()) {
                                location.reload();
                            } else {
                                window.location.href = baseurl + 'quote';
                            }

                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error',
                                'An error occurred while generating the lead',
                                'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    $('#quote_create_btn').prop('disabled', false);
                }
            });
        } else {
            $('#quote_create_btn').prop('disabled', false);
        }
    });
    $('#quote_approve_btn').on('click', function(e) {

        e.preventDefault(); // Prevent the default form submission
        var assignto = $('#employee').val();
        if (assignto == "") {
            $("#employee").prop('required', true);
        }
        // $('#quote_approve_btn').prop('disabled', true); 
        var selectedProducts1 = [];
        if (parseInt($("#customer_id").val()) == 0) {
            Swal.fire({
                text: "Please Select Customer",
                icon: "info"
            });
            $('#quote_approve_btn').prop('disabled', false);
            return;
        }
        $('.amnt').each(function() {
            if ($(this).val() > 0) {
                selectedProducts1.push($(this).val());
            }
        });

        if (selectedProducts1.length === 0) {
            Swal.fire({
                text: "To proceed, please enter quantity for at least one item",
                icon: "info"
            });
            $('#quote_approve_btn').prop('disabled', false);
            return;
        }
        // Validate the form
        if ($("#data_form").valid()) {
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            formData.append('changedFields', JSON.stringify(changedFields));
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to approve this quote?",
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
                        url: baseurl +'quote/quote_approval_action', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            // window.location.href = baseurl + 'quote/view?id='+invoiceno; 
                            window.location.href = baseurl + 'quote';

                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error',
                                'An error occurred while generating the lead',
                                'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#quote_approve_btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#quote_approve_btn').prop('disabled', false);
            // Form is not valid, gather and display errors
            // var validator = $("#data_form").validate();
            // var errors = [];
            // $.each(validator.errorList, function(index, error) {
            //     errors.push(error.message);
            // });
            // swal.fire({
            //     icon: 'error',
            //     title: 'Error',
            //     html: '<ul>' + errors.map(function(error) { return '<li>' + error + '</li>'; }).join('') + '</ul>',
            //     confirmButtonText: 'OK'
            // });
        }
    });
});



$('#quote_draft_btn').on('click', function(e) {
    e.preventDefault();
    $('#quote_draft_btn').prop('disabled', true); // Disable button to prevent multiple submissions
    if ($("#customer_id").val() < 1) {
        if (!$("#customer-box").valid()) {
            $('html, body').animate({
                scrollTop: $("#customer-box").offset().top - 200
            }, 500);
            $('#quote-to-salesorder-draft-btn').prop('disabled', false);
            return;
        }
    }
    var invoicetid = parseInt($("#invocieno").val());
    var invoiceno = invoicetid - 1000; // Directly compute invoiceno
    var form = $('#data_form')[0]; // Get the form element
    var formData = new FormData(form); // Create FormData object

    $.ajax({
        url: baseurl + 'quote/draftaction', // Replace with your server endpoint
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }
            window.location.href = baseurl + 'quote/create?id=' + response.quote;

        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
            console.log(error); // Log any errors
        }
    });
});

function compare_with_old_new_grand_totals() {

    if ($('#prepared_flag').val() == '1' && $('#approvalflg').val() == '1' && $('#quote_status').val() != 'draft') {

        var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
        var oldtotalamount = parseFloat($("#oldtotal").val().replace(/,/g, '').trim());

        var textdata = "";
        if (isNaN(total) || isNaN(oldtotalamount)) {
            textdata = '<div class="alert alert-warning">Invalid numbers. Please check the values again.</div>';
        } else if (total > oldtotalamount) {
            textdata =
                '<div class="alert alert-danger">The new Grand Total amount exceeds the old Grand Total amount, so you need authorization approval</div>';
        } else {
            // textdata = '<div class="alert alert-success">The Available Credit Limit is below the order total amount. Please procced.</div>';
        }
        $("#compare_result").html(textdata);
    }
}

$("#completion-btn").on('click', function() {
    Swal.fire({
        title: "Are you Sure ?",
        "text": "Do yo want to revert this quote?",
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
                url: baseurl + 'quote/quote_reassigned',
                data: {
                    quote_id: $("#quote_id").val()
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = baseurl + 'quote';
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});
$("#reverted-by-admin-btn").on('click', function() {
    Swal.fire({
        title: "Are you Sure ?",
        "text": "Do yo want to revert this quote?",
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
                url: baseurl + 'quote/quote_reverted_by_dmin',
                data: {
                    quote_id: $("#quote_id").val()
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
$("#quote-accept-btn").on('click', function() {
    Swal.fire({
        title: "Are you Sure ?",
        "text": "Do yo want to Accept this quote?",
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
                url: baseurl + 'quote/quote_accept',
                data: {
                    quote_id: $("#quote_id").val()
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = baseurl + 'quote/create?id=' + $("#quote_id")
                        .val();
                    // window.location.href = baseurl + 'quote';
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});

$("#attachment-btn").on('click', function() {
    Swal.fire({
        title: "Coming Soon",
        icon: "info",
    });
});


$(".quote-send-btn").on("click", function(e) {
    e.preventDefault();
    var validationFailed = false;
    $('.quote-send-btn').prop('disabled', true);
    $("#employee").prop('required', false);
    if ($("#data_form").valid()) {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to Send this Quote Now?",
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
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('changedFields', JSON.stringify(changedFields));
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'Quote/quote_send_by_admin_action',
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting content type header
                    success: function(response) {
                        // location.reload(); // Reload page on success
                        window.location.href = baseurl + 'quote';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log the error
                        $('.quote-send-btn').prop('disabled',
                        false); // Re-enable button on error
                    }
                });
            } else {
                $('.quote-send-btn').prop('disabled', false); // Re-enable button on cancel
            }
        });
    } else {
        $('.quote-send-btn').prop('disabled', false); // Re-enable button if form is invalid
    }
});

function deleteitem(id, img_name) {
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
                data: {
                    selectedProducts: id,
                    image: img_name
                },
                dataType: 'json',
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }
    });
}


</script>