
<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">   
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('stockreturn') ?>"><?php echo $this->lang->line('Suppliers') . ' ' . $this->lang->line('Stock Return') ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $this->lang->line('Stock Return')." #".($invoice['tid']); ?></li>
                </ol>
            </nav>
            
            <div class="row">
                <div class="col-4">
                    <h4 class="card-title"><?php echo $this->lang->line('Supplier')." ".$this->lang->line('Stock Return'). " #".($invoice['tid']); ?></h4>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-7 col-sm-12">
                     <!-- ========================================= -->
                     <?php
                        
                        $frmelmentdisable = "";
                        $frmselectdisable = "";
                        $accpetthenhide = "";
                        $frmbtndisable="";
                        // Using switch to handle different conditions
                            switch (true) {
                                case ($invoice['return_status'] == 'Sent'):
                                    $frmelmentdisable = "readonly";
                                    $frmselectdisable = "textarea-bg disable-class";
                                    $accpetthenhide = "disable-class";
                                    $frmbtndisable= "disabled";
                                    break;
                            
                                case ($invoice['approvalflg'] == '1' && $invoice['prepared_flg'] == '1' && $invoice['approved_by'] != $this->session->userdata('id')):
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
                        $updatestockcls="d-none";
                        $assigncls="disable-class";
                        $element_readonly="";
                        if($invoice['prepared_flg']==1)
                        {
                           $preparedbtn="d-none";
                           $required = "";
                           $compulsory = '';
                           // $required = "required";
                           // $compulsory = '<span class="compulsoryfld">*</span>';
                           $assigncls="";
                        }
                        if($invoice['prepared_flg']!=1 || $invoice['return_status'] =='Sent')
                        {
                           $approvebtn="d-none";
                           $acceptbtn="d-none";
                        }
                        if($invoice['approvalflg']==1)
                        {
                           $approvebtn="d-none";
                           $updatestockcls ="";
                        }
      
                            $msgcls = "";
                            $messagetext = "";
                            $enabledisablecls="";
                            $marginbottom = "mb-2";
                            $assignseccls = "";
                            $acceptsendbtncls="";
                            $addrowclass_withemp="";
                            switch (true) {
                                case ($invoice['prepared_flg'] == 0):
                                $msgcls = "d-none";
                                $enabledisablecls ="d-none";
                                $marginbottom = "";
                                $assignseccls = "d-none";
                                break;

                                case ($invoice['approvalflg'] != 1 && $invoice['prepared_flg'] == 1):
                                $messagetext = "Waiting for approval";
                                $enabledisablecls ="";
                                $msgcls = "";
                                $acceptsendbtncls ="d-none";
                                
                                break;


                                case ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['return_status'] == "Assigned"):
                                    $messagetext = "Please Accept the Purchase Order Reciept. Assigned Date : ".date('d-m-Y h:i:s A', strtotime($invoice['approved_dt']));
                                    $msgcls = "";
                                    $enabledisablecls ="disable-class";
                                    $addrowclass_withemp ="disable-class";
                                    $element_readonly = "readonly";
                                break;

                                case ($invoice['approved_by']==$this->session->userdata('id') && $invoice['return_status'] == "Assigned"):
                                    $msgcls = "";
                                    $messagetext = $assignedperson['name']." has not accepted. Assigned Date : ".date('d-m-Y h:i:s A', strtotime($invoice['approved_dt']));
                                break;

                                
                                case ($invoice['return_status'] == "Sent"):
                                    $messagetext = "Current Status : Already Sent";
                                    $msgcls = "";
                                    $enabledisablecls ="";
                                break;
                                case ($invoice['return_status'] == "Reverted"):
                                    $messagetext = "Purchase Order Reciept Reverted. Now you can Reassign & Update  or Recieve items from here";
                                    $msgcls = "";
                                    $enabledisablecls ="";
                                break;

                                default:
                                // No action needed for the default case
                                break;
                            }
                            ?>    
                            <div class="btn-group alert alert-danger <?=$msgcls?>" role="alert">
                                <?php echo $messagetext; ?>
                            </div>
                        <!-- ========================================= -->
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
                <!-- ========================= Buttons start ===================== -->
                <div class="row wrapper white-bg page-heading <?=$marginbottom?>">
                    <div class="col-lg-7">
                        <?php
                        $validtoken = hash_hmac('ripemd160', 'p' . $invoice['iid'], $this->config->item('encryption_key'));
                        
                        $link = base_url('billing/stockreturn?id=' . $invoice['iid'] . '&token=' . $validtoken);
                        if ($invoice['status'] != 'canceled') { ?>
                        <div class="title-action">
                            <!-- <a href='<?= base_url("purchase/purchase_order_payment?id=" . $invoice['iid'] . "&csd=" . $invoice['csd']) ?>' class="btn btn-sm  btn-secondary  <?php echo $enabledisablecls; ?>" title="Partial Payment"><span class="fa fa-money"></span> <?php echo $this->lang->line('Make Payment') ?> </a> -->

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
                            <a href="#sendEmail" data-toggle="modal" data-remote="false"
                                class="btn btn-sm btn-secondary sendbill <?php echo $enabledisablecls; ?>" title="Email"
                                data-type="purchase"><span class="fa fa-envelope-o"></span> <?php echo $this->lang->line('Send') ?></a>

                            <a href="#sendSMS" data-toggle="modal" data-remote="false"
                                class="btn btn-sm btn-secondary <?php echo $enabledisablecls; ?>" title="SMS"><span
                                    class="fa fa-mobile"></span> <?php echo $this->lang->line('SMS') ?></a>



                            <a href="#pop_model" data-toggle="modal" data-remote="false"
                                class="btn btn-sm btn-secondary d-none <?php echo $enabledisablecls; ?>" title="Change Status"><span
                                    class="fa fa-retweet"></span> <?php echo $this->lang->line('Change Status') ?></a>
                            <a href="#cancel-bill" class="btn btn-sm btn-secondary d-none <?php echo $enabledisablecls; ?>"
                                id="cancel-bill_p"><i class="fa fa-minus-circle"> </i> <?php echo $this->lang->line('Cancel') ?>
                            </a>
                            <!-- <a  class="btn btn-sm btn-secondary <?php echo $enabledisablecls; ?>"  target="_blank" href="<?= base_url('Invoices/costing?pid=' . $invoice['iid'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Purchase Receipt') ?></a> -->
                        </div>
                        <?php
                            if ($invoice['multi'] > 0) {
                            
                                echo '<div class="tag tag-info text-xs-center mt-2">' . $this->lang->line('Payment currency is different') . '</div>';
                            }
                            } else {
                                echo '<h2 class="btn btn-oval btn-secondary">' . $this->lang->line('Cancelled') . '</h2>';
                            } ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 text-lg-right text-md-right text-sm-left">
                        <a href="<?php echo $link; ?>" class="btn btn-sm btn-secondary <?=$assignseccls?>" target="_blank"><i
                                class="fa fa-globe"></i> <?php echo $this->lang->line('Preview') ?>
                        </a>
                        <div class="btn-group ">
                            <button type="button"
                                class="btn btn-sm btn-secondary btn-min-width dropdown-toggle <?php echo $enabledisablecls; ?>"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i>
                                <?php echo $this->lang->line('Print Order') ?>
                            </button>  
                            <div class="dropdown-menu">
                                <a class="dropdown-item" target="_blank"
                                    href="<?= base_url('billing/printstockreturn?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>"><?php echo $this->lang->line('Print') ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                    href="<?= base_url('billing/printstockreturn?id=' . $invoice['iid'] . '&token=' . $validtoken); ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ========================= Buttons start ===================== -->
                <form method="post" id="data_form">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub">
                                            <?php echo $this->lang->line('Supplier Details') ?> 
                                        </h3>
                                    </div>
                                    <div class="frmSearch col-sm-12">
                                        <label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Supplier') ?></label>
                                        <input type="text" class="form-control" name="cst" id="supplier-box"
                                               placeholder="<?php echo $this->lang->line('Enter Supplier Name or Mobile Number to search') ?>" autocomplete="off" <?=$frmelmentdisable?>/>
                                        <div id="supplier-box-result"></div>
                                    </div>

                                </div>
                                <div id="customer">
                                    <div class="clientinfo">
                                        <?php echo '  <input type="hidden" name="customer_id" id="customer_id" value="' . $invoice['csd'] . '">
                                                <div id="customer_name"><strong>' . $invoice['name'] . '</strong></div>
                                            </div>
                                            <div class="clientinfo">

                                                <div id="customer_address1"><strong>' . $invoice['address'] . '<br>' . $invoice['city'] . ',' . $invoice['country'] . '</strong></div>
                                            </div>

                                            <div class="clientinfo">

                                                <div type="text" id="customer_phone">Phone: <strong>' . $invoice['phone'] . '</strong><br>Email: <strong>' . $invoice['email'] . '</strong></div>
                                            </div>'; ?>
                                    </div>


                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                                <div class="inner-cmp-pnl">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <h3 class="title-sub"> <?php echo $this->lang->line('Details') ?></h3>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieno" class="col-form-label"> <?php echo $this->lang->line('Stock Return') ?>
                                                #</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Purchase Order #"
                                                       name="invocieno"  value="<?php echo $invoice['tid']; ?>" readonly>
                                                       <input id="receipt_id" type="hidden"  name="iid" value="<?php echo $invoice['iid']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieno" class="col-form-label"> <?php echo $this->lang->line('Reference') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Reference #"  name="refer" value="<?php echo $invoice['refer'] ?>" <?=$element_readonly?> >
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none"><label for="invociedate" class="col-form-label"> <?php echo $this->lang->line('Order Date') ?></label>
                                                <input type="date" class="form-control"
                                                       placeholder="Billing Date" name="invoicedate"
                                                       autocomplete="false"
                                                       value="<?php echo ($invoice['invoicedate']) ?>">
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Order Due Date') ?></label>
                                                <input type="date" class="form-control"
                                                       name="invocieduedate"
                                                       placeholder="Due Date" autocomplete="false"
                                                       value="<?php echo $invoice['invoiceduedate']; ?>">
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="taxformat"
                                                   class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                                            <select class="form-control" onchange="changeTaxFormat(this.value)"
                                                    id="taxformat">

                                                <?php echo $taxlist; ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">

                                            <div class="form-group">
                                                <label for="discountFormat"
                                                       class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                                <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                        id="discountFormat">
                                                    <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                    <option value="%"><?php echo $this->lang->line('% Discount') . ' ' . $this->lang->line('After TAX') ?> </option>
                                                    <option value="flat"><?php echo $this->lang->line('Flat Discount') . ' ' . $this->lang->line('After TAX') ?></option>
                                                    <option value="b_p"><?php echo $this->lang->line('% Discount') . ' ' . $this->lang->line('Before TAX') ?></option>
                                                    <option value="bflat"><?php echo $this->lang->line('Flat Discount') . ' ' . $this->lang->line('Before TAX') ?></option>
                                                    <!-- <option value="0">Off</option> -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="pterms" class="col-form-label"><?php echo $this->lang->line('Payment Terms'); ?></label>
                                            <select name="pterms" class="selectpicker form-control">
                                                <?php echo '<option value="' . $invoice['termid'] . '">*' . $invoice['termtit'] . '</option>';
                                                foreach ($terms as $row) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none <?=$updatestockcls?> ">
                                            <label for="Update Stock" class="col-form-label"><?php echo $this->lang->line('Update Stock') ?> </label>
                                            <div class="mt-1">
                                                <div class="form-check form-check-inline" >
                                                    <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight1" value="yes" checked >
                                                    <label class="form-check-label" for="customRadioRight1"><?php echo $this->lang->line('Yes') ?></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight2" value="no">
                                                    <label class="form-check-label" for="customRadioRight2"><?php echo $this->lang->line('No') ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                            <select id="s_warehouses" name="s_warehouses" class="selectpicker form-control"  <?=$element_readonly?>>
                                                <?php foreach ($warehouse as $row) {
                                                    if($default_warehouse['id']==$row['id'])
                                                    {
                                                        echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                    }
                                                    
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="toAddInfo"
                                                   class="col-form-label"><?php echo $this->lang->line('Notes') ?></label>
                                            <textarea class="form-textarea" name="notes"  <?=$element_readonly?>
                                                      rows="2"><?php echo $invoice['notes'] ?></textarea>
                                        </div>
                                        <!-- ================== starts ===================== -->
                                        <?php if (isset($employee)){?> 
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-2 <?=$assignseccls?>">
                                                <label for="employee" class="col-form-label"><?php echo $this->lang->line('Assign to') ?><?=$compulsory?></label>
                                                <select name="employee" id="employee" class=" col form-control <?=$assigncls?> <?=$frmselectdisable?>" <?=$required?> <?=$frmelmentdisable?>>
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
                                    <!-- ================== ends ===================== -->    
                                    <!-- ========================= Purchse order detaisl ======================== -->
                                    <?php
                                     if(!empty($invoice['purchase_id']))
                                     { ?>
                                        <input type="hidden" name="purchase_id" id="purchase_id" value="<?=$invoice['iid']?>">
                                        <input type="hidden" name="purchase_reciept_id" id="purchase_reciept_id" value="<?=$invoice['purchase_reciept_id']?>">
                                        <input type="hidden" name="purchase_reciept_number" id="purchase_reciept_number" value="<?=$invoice['purchase_reciept_number']?>">
                                        <div class="col-12">
                                            <div class="row">
                                                
                                                <div class="col-sm-12">
                                                    <h3  class="title-sub"><?php echo $this->lang->line('Purchase Order Details') ?></h3>
                                                </div>
                                                <!--erp2024 newly added 29-09-2024  -->
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="Purchase Order Number" class="col-form-label"><?php echo $this->lang->line('Purchase Order Number'); ?></label>
                                                        <input type="text" name="purchase_order_number" class="form-control"  value="<?php echo $invoice['ponumber'] ?>" readonly> 
                                                    </div>                                    
                                                </div>
                                                
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="Purchase Order Reference" class="col-form-label"><?php echo $this->lang->line('Purchase Order Reference'); ?></label>
                                                        <input type="text" class="form-control"  value="<?php echo $invoice['poreference'] ?>" readonly> 
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Supplier Reference Number'); ?></label>
                                                        <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control"  value="<?php echo $invoice['supplier_reference'] ?>" readonly> 
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Supplier Contact Person'); ?></label>
                                                        <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control"  value="<?php echo $invoice['supplier_contactperson'] ?>" readonly>
                                                        </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                                        <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number" value="<?php echo $invoice['supplier_contactno']; ?>" readonly>
                                                        </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Supplier Contact Email'); ?></label>
                                                        <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Supplier Contact Email" value="<?php echo $invoice['supplier_contacctemail']; ?>" readonly>
                                                        </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="" class="col-form-label"><?php echo $this->lang->line('Doc Type'); ?></label>
                                                        <input type="text" class="form-control" placeholder="Supplier Contact Email" value="<?php echo $invoice['supplier_doctype']; ?>" readonly>
                                                        </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="" class="col-form-label"><?php echo $this->lang->line('Currency'); ?></label>
                                                        <?php foreach ($currency as $row) {
                                                            if($row['id'] == $invoice['supplier_currency'])
                                                            { ?>
                                                            <input type="text" class="form-control" placeholder="Supplier Contact Email" value="<?php echo $row['code']; ?>" readonly>
                                                        <?php }
                                                        } ?>
                                                        
                                                        </div>                                    
                                                </div>
                                                <!--erp2024 newly added 29-09-2024 ends -->
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    <!-- ========================= Purchse order detaisl ======================== -->
                                </div>
                            </div>
                            </div>

                        </div>

                        <!-- ===================Draft save section ==================== -->
                        <?php   
                            if($invoice['return_status']=='Draft')
                            { ?>
                                <div class="col-12">
                                <div class="alert alert-warning alert-success fade show" role="alert">
                                    <strong>Draft</strong> Saved Successfully.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                </div>
                            <?php } ?>
                        <!-- ===================Draft save section ==================== -->
                        <div id="saman-row">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                    <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                        <?php 
                                        $addrowclass = "";
                                        $reciptclass="";
                                        if(!empty($products[0]['purchase_reciept_id']))
                                            { 
                                                $colspanbig = 7;
                                                $colspansmall=2;
                                                $addrowclass = "d-none";
                                                
                                                ?>
                                                <th width="8%" class="text-center"><?php echo $this->lang->line('Received')."<br>".$this->lang->line('Quantity'); ?></th>
                                                <th width="8%" class="text-center"><?php echo $this->lang->line('Damaged')."<br>".$this->lang->line('Quantity'); ?></th>
                                        <?php } 
                                                else{
                                                    $colspanbig = 5;
                                                    $colspansmall=1;
                                                    
                                                }
                                        ?>
                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Return')."<br>".$this->lang->line('Quantity'); ?></th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Rate') ?></th>
                                    <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th> -->
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Amount') ?>
                                        (<?php echo $this->config->item('currency'); ?>)
                                    </th>
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;
                                foreach ($products as $row) {
                                    $productcode="";
                                    
                                    echo '<tr >
                                    <td><input type="text" class="form-control productname" name="product_name[]" placeholder="Enter Product name or Code"  value="' . $row['product'] . '" '.$reciptclass.' '.$element_readonly.'>
                                    </td>';
                                    echo '<td><input type="text" class="form-control" id="code-' . $i . '" name="code[]" value="' . $row['code'] . '" '.$reciptclass.' '.$element_readonly.'>
                                    </td>';
                                    if(!empty($row['purchase_reciept_id']))
                                    {
                                        echo '<td class="text-center"><strong class="text-center">'.$row['product_qty_recieved'].'</strong><input type="hidden" name="received_qty[]"   id="received_qty-' . $i . '" value="'.$row['product_qty_recieved'].'" '.$element_readonly.'></td>';
                                        echo '<td class="text-center"><strong class="text-center">'.$row['damage'].'</strong><input type="hidden" name="damage[]"   id="damage-' . $i . '" value="'.$row['damage'].'" '.$element_readonly.'></td>';
                                    }
                                    echo '<td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . intval($row['qty']) . '" '.$element_readonly.'><input type="hidden" name="old_product_qty[]" value="' . intval($row['qty']) . '" '.$element_readonly.'></td>
                                    <td><input type="text" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . edit_amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '" '.$element_readonly.'></td>
                                   
                                   
                                    <td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">' . number_format($row['subtotal'], 2) . '</span></strong></td>
                                    <td class="text-center">
                                    <button type="button" data-rowid="' . $i . '" class="btn btn-sm btn-default removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>
                                    </td>
                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . edit_amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . edit_amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . edit_amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"><input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                                    <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['tax']) . '">
                                    <input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '">
                                    </tr>';
                                    $i++;
                                } ?>
                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-secondary <?=$addrowclass?> <?=$addrowclass_withemp?> <?=$accpetthenhide?>" id="addstockreturnproduct">
                                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                    </td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>

                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="6" align="right" class="no-border">
                                        <strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border"><span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                        <span id="taxr"
                                              class="lightMode"><?php echo edit_amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                    </td>
                                </tr>
                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="4" align="right" class="no-border">
                                        <strong><?php echo $this->lang->line('Total Discount') ?></strong></td>
                                    <td align="left" colspan="2" class="no-border"><span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                        <span id="discs"
                                              class="lightMode"><?php echo edit_amountExchange_s($invoice['discount'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                    </td>
                                </tr>

                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="4" align="right" class="no-border"><input type="hidden"
                                                                         value="<?php echo edit_amountExchange_s($invoice['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) ?>"
                                                                         id="subttlform"
                                                                         name="subtotal"><strong><?php echo $this->lang->line('Shipping') ?></strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                        onkeypress="return isNumber(event)"
                                                                        placeholder="Value"
                                                                        name="shipping" autocomplete="off"
                                                                        onkeyup="billUpyog()"
                                                                        value="<?php if ($invoice['ship_tax_type'] == 'excl') {
                                                                            $invoice['shipping'] = $invoice['shipping'] - $invoice['ship_tax'];
                                                                        }
                                                                        echo edit_amountExchange_s($invoice['shipping'], $invoice['multi'], $this->aauth->get_user()->loc); ?>">( <?= $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                        <span id="ship_final"><?= edit_amountExchange_s($invoice['ship_tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                        )
                                    </td>
                                </tr>

                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="<?=$colspanbig?>" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                            (<span
                                                    class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </td>
                                    <td align="left" colspan="3" class="no-border">
                                        <span id="grandtotaltext"><?= number_format($invoice['total'],2); ?></span>
                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="<?= edit_amountExchange_s($invoice['total'], $invoice['multi'], $this->aauth->get_user()->loc); ?>" readonly="">

                                    </td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <!-- <td colspan="2" class="no-border"></td> -->
                                    <td colspan="<?=$colspansmall?>" class="no-border">
                                        <?php 
                                        $draftcls ="";
                                        if((!empty($invoice['approved_by'])) && ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['prepared_flg']==1) && ($invoice['approvalflg']=='1'))
                                        {
                                            $draftcls ="d-none";
                                        ?>
                                        <button type="button" class="btn btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?>" id="revert-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                                        <?php }
                                        if((!empty($invoice['approved_by'])) && ($invoice['approved_by']==$this->session->userdata('id') && $invoice['prepared_flg']==1) && ($invoice['approvalflg']=='1'))
                                        {
                                            $draftcls ="";
                                            $revertcls = ($invoice['return_status']=='Reverted') ? "disable-class" :"";
                                        ?>
                                            <button type="button" class="btn btn-lg btn-secondary revert-btncolor <?=$assign_personcls?> <?=$revertbtncls?> <?=$generatebtn?> <?=$accpetthenhide?> <?=$revertcls?>" id="revert-by-admin-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                                        <?php }
                                        ?>
                                    </td>
                                    <td align="right" colspan="6" class="no-border">
                                        <button class="btn btn-secondary btn-lg <?=$draftcls?> <?=$accpetthenhide?>" id="stock_return_prepared_btn_draft1" name="stock_return_prepared_btn_draft" type="button"><?php echo $this->lang->line('Save As Draft') ?></button>
                                        <!-- <input type="submit" class="btn btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Update Order') ?>" id="submit-data"data-loading-text="Updating..."> -->

                                        <?php
                                        if($invoice['prepared_flg']!=1){ ?>
                                        <input type="submit" class="btn btn-lg btn-primary sub-btn <?=$generatebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Prepared') ?>" id="stock_return_prepared_btn" data-loading-text="Creating...">
                                        <?php } ?>

                                        <input type="submit" class="btn btn-lg btn-secondary stock-return-approve-btn <?=$approvebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Assign & Approve') ?>" data-loading-text="Creating...">
                                        <input type="submit" class="btn btn-lg btn-primary send-strock-return-btn <?=$approvebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Send Stock Return') ?>" data-loading-text="Creating...">
                                        <?php
                                        if($invoice['approved_by']==$this->session->userdata('id')  &&  $invoice['approvalflg']=='1'){
                                                ?>
                                                <input type="submit" class="btn btn-lg btn-secondary sub-btn stock-return-approve-btn <?=$accpetthenhide?>" value="<?php echo $this->lang->line('Reassign & Update') ?>">
                                                <input type="submit" class="btn btn-lg btn-primary send-strock-return-btn <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Send Stock Return') ?>" data-loading-text="">
                                        <?php }
                                        if((!empty($invoice['approved_by'])) && ($invoice['approved_by']!=$this->session->userdata('id') && $invoice['prepared_flg']==1))
                                        { ?>  
                                            <button type="button" class="btn btn-lg btn-primary <?=$assign_personcls?> <?=$generatebtn?> <?=$accpetthenhide?> <?=$acceptsendbtncls?>" id="stock-return-accept-employee-btn"><?php echo $this->lang->line('Accept & Send') ?></button>&nbsp;
                                        <?php } ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" value="stockreturn/editaction" id="action-url">
                        <input type="hidden" value="0" name="person_type">
                        <input type="hidden" value="puchase_search" id="billtype">
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
                        echo amountFormat_general(number_format((($invoice['ship_tax'] / $invoice['shipping']) * 100) + $tt, 3, '.', '')); ?>"
                               name="shipRate" id="ship_rate">
                        <input type="hidden" value="<?= $invoice['ship_tax_type']; ?>" name="ship_taxtype"
                               id="ship_taxtype">
                        <input type="hidden" value="<?= amountFormat_general($invoice['ship_tax']); ?>" name="ship_tax"
                               id="ship_tax">
                </form>
            </div>

        </div>

        <div class="modal fade" id="addCustomer" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="product_action" class="form-horizontal">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only"><?php echo $this->lang->line('Close') ?></span>
                            </button>
                            <h4 class="modal-title"
                                id="myModalLabel"><?php echo $this->lang->line('Add Supplier') ?></h4>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <p id="statusMsg"></p><input type="hidden" name="mcustomer_id" id="mcustomer_id" value="0">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                       for="name"><?php echo $this->lang->line('Name') ?></label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="Name"
                                           class="form-control margin-bottom" id="mcustomer_name" name="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                       for="phone"><?php echo $this->lang->line('Phone') ?></label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="Phone"
                                           class="form-control margin-bottom" name="phone" id="mcustomer_phone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="email">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" placeholder="Email"
                                           class="form-control margin-bottom crequired" name="email"
                                           id="mcustomer_email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                       for="address"><?php echo $this->lang->line('Address') ?></label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Address"
                                           class="form-control margin-bottom " name="address" id="mcustomer_address1">
                                </div>
                            </div>
                            <div class="form-group row">


                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="city" id="mcustomer_city">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Region"
                                           class="form-control margin-bottom" name="region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="country" id="mcustomer_country">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="PostBox"
                                           class="form-control margin-bottom" name="postbox">
                                </div>
                            </div>


                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                            <input type="submit" id="msupplier_add" class="btn btn-primary submitBtn"
                                   value="<?php echo $this->lang->line('ADD') ?>"/>
                        </div>
                    </form>
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
            <form class="payment">
               <div class="row">
                  <div class="col">
                     <div class="input-group">
                        <div class="input-group-addon"><?php echo $this->config->item('currency') ?></div>
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
<!-- ======================Additional Forms sms,email,cancel etc starts ========================== -->
<script type="text/javascript"> 

$(document).ready(function() {
        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {  
                // Add your validation rules here
            },
            messages: {
                // Add your custom messages here
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

    function checkqty(id){
      var enteredqty = parseFloat($("#amount-" + id).val()) || 0;       
      var damageqty = parseFloat($("#damage-" + id).val()) || 0;      
      var received_qty = parseFloat($("#received_qty-" + id).val()) || 0;
      var totalqty = received_qty - damageqty;
     
      if(totalqty >0 && (enteredqty > totalqty)){
         $("#amount-" + id).val(0);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is invalid. please check'
         });
      }
    }
    $('.editdate').datepicker({
        autoHide: true,
        format: '<?php echo $this->config->item('dformat2'); ?>'
    });
    $(".stock-return-approve-btn").on("click", function(e) {
        var assignto = $('#employee').val();
        e.preventDefault();
        var selectedProducts1 = [];
        var validationFailed = false;
        $('.stock-return-approve-btn').prop('disabled', true);
        if(assignto=="")
        {
            $("#employee").prop('required', true);
        }
        if ($("#data_form").valid()) {
            
            Swal.fire({
                    title: "Are you sure?",
                    "text":"Do you want to Approve this Stock Return?",
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
                        url: baseurl + 'stockreturn/editaction',
                        data: formData,
                        success: function(response) {
                            window.location.href = baseurl + 'stockreturn';
                        },
                        error: function(xhr, status, error) {
                                // Handle error
                                console.error(xhr.responseText);
                        }
                    });
                    }
                    else{
                    $('.stock-return-approve-btn').prop('disabled', false);
                    }
            });
        }
        else{
                $('.stock-return-approve-btn').prop('disabled', false);
            }
    });    

    $(".send-strock-return-btn").on("click", function(e) {
        e.preventDefault();
        var selectedProducts1 = [];
        var validationFailed = false;
        $('.send-strock-return-btn').prop('disabled', true);
        $("#employee").prop('required', false);
        if ($("#data_form").valid()) {
            Swal.fire({
                    title: "Are you sure?",
                    "text":"Do you want to sent items Now?",
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
                        url: baseurl +'stockreturn/stock_return_send_by_admin_action',
                        data: formData,
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
                    $('.send-strock-return-btn').prop('disabled', false);
                    }
            });
        }
        else{
            $('.send-strock-return-btn').prop('disabled', false);
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
                    url: baseurl + 'stockreturn/revert_return_by_admin_action',
                    data: { 
                        receipt_id: $("#receipt_id").val(),
                        purchase_id: $("#purchase_id").val()
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

    $("#revert-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this stock return?",
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
                    url: baseurl + 'stockreturn/revert_stock_return_by_employee_action',
                    data: {
                        receipt_id: $("#receipt_id").val(),
                        purchase_id: $("#purchase_id").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'stockreturn';
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    $("#stock-return-accept-employee-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to Accept this stock return?",
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
                    url: baseurl + 'stockreturn/stock_return_accept_by_employee',
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
    });

    $('#stock_return_prepared_btn').on('click', function(e) {
        e.preventDefault();
        $('#stock_return_prepared_btn').prop('disabled', true);
        var selectedProducts1 = [];
        $('.amnt').each(function(index) {
            var currentQty = parseFloat($(this).val());
            if(currentQty>0)
            {
                selectedProducts1.push(currentQty);
            }
            
        });
    }); 
    $("#stock_return_prepared_btn_draft1").on("click", function(e) {        
        e.preventDefault();

        var formData = $("#data_form").serialize(); 
        $.ajax({
            type: 'POST',
            url: baseurl +'stockreturn/draftaction_sub',
            data: formData,
            success: function(response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }         
                location.reload();
            },
            error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
            }
        });
    });

    
    $('#stock_return_prepared_btn').on('click', function(e) {
        e.preventDefault();
        $('#stock_return_prepared_btn').prop('disabled', true);
        var customer_id = $("#customer_id").val();
        var selectedProducts = [];
        var selectedqty = [];

        // Check if customer ID is valid
        if(customer_id == '0'){
            $("#supplier-box").prop('required', true);
        }

        // Collect selected products
        $('.productname').each(function() {
            if($(this).val() != "") {
                selectedProducts.push($(this).val());
            }
        });

        // Collect quantities greater than 0
        $('.amnt').each(function(index) {
            var currentQty = parseFloat($(this).val());
            if(currentQty > 0) {
                selectedqty.push(currentQty);
            }
        });
        // Validate the form
        if($("#data_form").valid()) {                 
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            // Check if at least one product is added
            if (selectedProducts.length === 0) {
                Swal.fire({
                    text: "To proceed, please add at least one product",
                    icon: "info"
                });
                $('#stock_return_prepared_btn').prop('disabled', false);
                return;
            }

            // Check if at least one quantity is added
            if (selectedqty.length === 0) {
                Swal.fire({
                    text: "To proceed, please add a return quantity for at least one item",
                    icon: "info"
                });
                $('#stock_return_prepared_btn').prop('disabled', false);
                return;
            }
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create a stock return?",
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
                        url: baseurl + 'stockreturn/action', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            // Uncomment or adjust this for redirection
                            window.location.href = baseurl + 'stockreturn';
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while creating the stock return', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else {
                    $('#stock_return_prepared_btn').prop('disabled', false);
                }
            });

        } else {
            $('#stock_return_prepared_btn').prop('disabled', false);
        }
    });
</script>
