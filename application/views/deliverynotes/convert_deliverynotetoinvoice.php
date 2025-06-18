<div class="content-body">
    <div class="card">
        <?php $invoice_number = $prefix.$lastinvoice + 1; ?>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Invoice')." #".$invoice_number; ?></li>
                </ol>
            </nav>
                      
            <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Invoice'); ?></h4>
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12">  
                        <ul id="trackingbar">
                            <?php 
                            if(!empty($trackingdata))
                                {
                                    if(!empty($trackingdata['lead_id']))
                                    { ?>
                                        <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li>
                                    <?php } 
                                    if(!empty($trackingdata['quote_number']))
                                    { ?>
                                        <li><a href="<?= base_url('quote/view?id=' . $trackingdata['quote_number']) ?>" target="_blank">QT #<?= $trackingdata['quote_number']; ?></a></li>
                                    <?php } 
                                    if(!empty($master_data['salesorder_id']))
                                    { ?>
                                        <li><a href="<?= base_url('quote/salesorders?id=' . $master_data['sales_id']) ?>" target="_blank">SO #<?= $master_data['salesorder_number']; ?></a></li>
                                    <?php } 
                                    if(!empty($master_data['delevery_note_id']))
                                    { ?>
                                    <li><a href="<?= base_url('DeliveryNotes/deliverynote_view?id=' . $master_data['delevery_note_id']) ?>" target="_blank">DN #<?= ($master_data['delnote_number']); ?></a></li>           
                                <?php } 
                                ?> <li class="active">IN #<?php echo $lastinvoice + 1 ?></li><?php
                                }?>                     
                                
                            
                        </ul>  
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
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row d-none">
                                    <div class="fcol-sm-12">
                                        <h3 class="sub-title">
                                            <?php echo $this->lang->line('Bill To') ?> <a href='#'
                                                                                          class="btn btn-crud btn-primary btn-sm round"
                                                                                          data-toggle="modal"
                                                                                          data-target="#addCustomer">
                                                <?php echo $this->lang->line('Add Client') ?>
                                            </a>
                                    </div>
                                </div>
                                
                                <div class="form-group1 row">
                                    <div class="frmSearch col-sm-12">
                                        <label for="cst" class="caption d-none"><?php echo $this->lang->line('Search Client'); ?></label>                                        
                                        <input type="text" class="form-control d-none" name="cst" id="customer-box"
                                               placeholder="Enter Customer Name or Mobile Number to search"
                                               autocomplete="off"/>
                                        <div id="customer-box-result">                                            
                                        </div>
                                    </div>
                                </div>
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="sub-title"><?php echo $this->lang->line('Client Details'); ?></h3>
                                        <?php 
                                        echo '<input type="hidden" name="customer_id" id="customer_id" value="'.$customerid.'">';
                                        ?>
                                        <div>
                                            <strong>
                                                <?php 
                                                echo $custname."\n<br>";
                                                echo $address."\n<br>";
                                                echo $city.",";
                                                echo $country."\n<br>";
                                                ?>
                                            </strong>
                                            <span>Email :  <strong><?php echo $email."\n<br>"; ?></strong></span>
                                            <span>Phone :  <strong><?php echo $phone."\n<br>"; ?></strong></span>
                                            <span><?php echo $this->lang->line('Company Credit Limit'); ?> :  <strong><?php echo $customer_details['credit_limit']."\n<br>"; ?></strong></span>
                                            <span><?php echo $this->lang->line('Credit Period'); ?> :  <strong><?php echo $customer_details['credit_period']."(Days)\n<br>"; ?></strong></span>
                                            <span><?php echo $this->lang->line('Available Credit Limit'); ?> :  <strong><?php echo $customer_details['avalable_credit_limit']."\n<br>"; ?></strong></span>
                                        </div>
                                        
                                        <div id="customer_name"></div>
                                    </div>
                                    <div class="clientinfo">
                                        <div id="customer_address1"></div>
                                    </div>

                                    <div class="clientinfo">
                                        <div id="customer_phone"></div>
                                    </div>
                                    <hr>
                                    <div id="customer_pass"></div>                                    
                                    
                                </div>


                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 cmp-pnl">
                            <div class="inner-cmp-pnl">
                                <div class="form-group row">

                                    <div class="col-sm-12"><h3
                                                class="sub-title"><?php echo $this->lang->line('Invoice Properties') ?></h3>
                                    </div>

                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                                        <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Invoice Number') ?></label>
                                    
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="invoice_number #" name="invoice_number" value="<?php echo $invoice_number ?>" readonly>
                                            <input type="hidden" class="form-control"  name="transaction_number" value="<?php echo $master_data['transaction_number'] ?>" readonly>
                                            <input type="hidden" class="form-control" placeholder="Invoice #"  name="invocieno" value="<?php echo $lastinvoice + 1 ?>" readonly>
                                            <input type="hidden" class="form-control"   name="delevery_note_id" value="<?php echo $master_data['delevery_note_id'] ?>" readonly>
                                            <input type="hidden" class="form-control"   name="delivery_note_number" value="<?php echo $master_data['delivery_note_number'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference') ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #"  name="refer" value="<?php echo $master_data['refer'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12"><label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Invoice Date'); ?></label>

                                        <div class="input-group">
                                            <input type="date" class="form-control" placeholder="Billing Date" name="invoicedate" value="<?=date('Y-m-d')?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12"><label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Invoice Due Date') ?><span class="compulsoryfld"> *</span></label>
                                        <?php
                                        $today = date('Y-m-d');
                                        $new_due_date = date('Y-m-d', strtotime($date. ' + '. $customer_details['credit_period'] .' days'));
                                         ?>
                                        <div class="input-group">                                           
                                            <input type="date" class="form-control required" name="invocieduedate"  placeholder="Due Date" autocomplete="false" min="<?=date('Y-m-d')?>" value="<?=$new_due_date?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 d-none">
                                        <label for="taxformat" class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                                        <select class="form-control" onchange="changeTaxFormat(this.value)"  id="taxformat">
                                            <?php echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 d-none">
                                            <label for="discountFormat"
                                                   class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                            <select class="form-control"
                                                    onchange="changeDiscountFormat(this.value)"
                                                    id="discountFormat">

                                                <?php echo $this->common->disclist() ?>
                                            </select>
                                    </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none`">
                                            <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                            <select id="s_warehouses"  name="s_warehouses" class="form-control" disabled>
                                            <?php 
                                            
                                            echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                            <?php foreach ($warehouse as $row) {
                                                $sel="";
                                                if($master_data['store_id'] == $row['id']){
                                                    $sel="selected";
                                                }
                                                echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] ." ".$master_data['store_id']. '</option>';
                                            } ?>
                                            </select>
                                            <input type="hidden" name="store_id" id="store_id" value="<?=$master_data['store_id']?>">
                                        </div>
                                        <?php if (isset($employee)){ ?>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="employee" class="col-form-label"><?php echo $this->lang->line('Employee') ?></label>
                                            <select name="employee" class="col form-control" disabled>
                                                <?php 
                                                 $sel1 = "";
                                                if(!empty($master_data['eid']))
                                                {
                                                    $master_data['eid'] = $this->session->userdata('id');
                                                }
                                                // echo '<option value="">' . $this->lang->line('Select Employee') .'</option>';
                                                foreach ($employee as $row) {
                                                    if($master_data['eid'] == $row['id']){
                                                        $sel1="selected";
                                                     }
                                                    echo '<option value="' . $row['id'] . '" '.$sel1.'>' . $row['name'].'</option>';
                                                } ?>
                                            </select>
                                            <input type="hidden" name="eid" id="eid" value="<?=$master_data['eid']?>">
                                        </div>
                                        <?php } ?>
                                        <?php if ($exchange['active'] == 1){ ?>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="mcurrency" class="col-form-label"><?php echo $this->lang->line('Payment Currency client'). ' <small>' . $this->lang->line('based on live market').'</small>'; ?> ?></label>
                                            <select name="mcurrency" class="selectpicker form-control">
                                                <option value="0">Default</option>
                                                <?php foreach ($currency as $row) {echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';} ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="pterms" class="col-form-label"><?php echo $this->lang->line('Payment Terms'); ?></label>
                                            <select name="pterms" class="selectpicker form-control">
                                                <?php foreach ($terms as $row) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                            <select id="s_warehouses"  name="s_warehouses" class="form-control" disabled>
                                            <?php 
                                            
                                            echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                            <?php foreach ($warehouse as $row) {
                                                $sel="";
                                                if($master_data['store_id'] == $row['id']){
                                                    $sel="selected";
                                                }
                                                echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] ." ".$master_data['store_id']. '</option>';
                                            } ?>
                                            </select>
                                            <input type="hidden" name="store_id" id="store_id" value="<?=$master_data['store_id']?>">
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="toAddInfo"
                                               class="col-form-label"><?php echo $this->lang->line('Invoice Note') ?></label>
                                            <textarea class="form-textarea" name="notes" rows="2"></textarea>
                                        </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <?php    //echo "<pre>"; print_r($products); ?>
                    <div id="saman-row">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>
                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                <th width="25%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                <?php 
                                    if($configurations['config_tax']!='0'){  ?>
                                        <th width="10%" class="text-center"><?php echo $this->lang->line('Tax(%)') ?></th>
                                        <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                                <?php } ?>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                                <th width="10%" class="text-right">
                                    <?php echo $this->lang->line('Amount') ?>
                                    (<?= currency($this->aauth->get_user()->loc); ?>)
                                </th>
                            </tr>

                            </thead>
                            <tbody>
                            <tr class="startRow">
                            <?php 
                         
                                $subtotal = 0;
                                $total_product_discount = 0;
                                $grand_total = 0;
                                $order_discount_percentage = 0;
                                $order_discountamount = 0;
                                if($products)
                                {
                                    $i=0;
                                    foreach ($products as $key => $value) {

                                        $current_qty = intval($value['product_qty']) - intval($value['delivery_returned_qty']);
                                        if($current_qty > 0)
                                        {
                                            $subtotal += intval($current_qty)*$value['product_price'];
                                            $old_product_total = intval($value['product_qty'])*$value['product_price'];
                                            $old_product_subtotal += $old_product_total;                                            
                                            
                                            $discountPecrectage = ($value['delnote_discounttype']=='Perctype') ? $value['product_discount'] :  find_discount_perecntage_from_amount($old_product_total, $value['deliverytotaldiscount']);
                                            
                                            $actulprice = $current_qty*$value['product_price'];

                                            $discountamount_total = ($value['delnote_discounttype']=='Perctype') ? convert_order_discount_percentage_to_amount($actulprice, $value['product_discount']) :  number_format(($current_qty*$value['product_discount']),2);
                                        
                                            $total_product_amount = ($actulprice - $discountamount_total);

                                            $total_product_discount += $discountamount_total;

                                            $grand_total += $total_product_amount; 
                                        
                                            echo '<tr>';
                                            echo "<td>".$value['product_code']."<input type='hidden' name='product_name[]' id='productname-$i' readonly value='".$value['product_name']."'></td>";
                                            echo "<td>".$value['product_name']."</td>";
                                            echo "<td class='text-center'>".$current_qty."<input type='hidden' name='product_qty[]' id='amount-$i' readonly value='".$current_qty."'></td>";
                                            echo "<td class='text-right'>".$value['product_price']."<input type='hidden' name='product_price[]' id='price-$i' readonly value='".$value['product_price']."'></td>";
                                            echo "<td class='text-right'>".$discountamount_total."<input type='hidden' name='discount_type[]' id='discounttype-$i' readonly value='".$value['delnote_discounttype']."'><input type='hidden' name='discount[]' id='discount-$i' readonly value='".$value['product_discount']."'></td>";
                                            echo "<td class='text-right'>".number_format($total_product_amount,2)."<input type='hidden' name='taxa[]' id='taxa-$i' readonly value='".$value['tax']."'><input type='hidden' name='disca[]' id='disca-$i' readonly value='".$discountamount_total."'><input type='hidden' class='ttInput' name='product_subtotal[]' id='total-$i' readonly value='".$total_product_amount."'><input type='hidden' name='pid[]' id='pid-$i' readonly value='".$value['product_id']."'><input type='hidden' name='unit[]' id='unit-$i' readonly value='".$value['unit']."'><input type='hidden' name='hsn[]' id='hsn-$i' readonly value='".$value['product_code']."'><input type='hidden' name='pdIn[]' id='pdIn-$i' readonly value='".$value['product_id']."'><input type='hidden' name='income_account_number[]' id='income_account_number-$i' readonly value='".$value['income_account_number']."'><input type='hidden' name='serial[]' id='serial-$i' readonly ></td>";
                                            echo '</tr>';
                                            $i++;
                                        }
                                    }

                                    

                                    $order_discount_percentage = order_discount_percentage($master_data['order_discount'],$old_product_subtotal);

                                    $order_discountamount = convert_order_discount_percentage_to_amount($subtotal, $order_discount_percentage);

                                    $grand_total -= $order_discountamount;

                                }

                                ?>
                            </tr>
                            <tr class="last-item-row sub_c">
                                <td class="add-row">
                                    <!-- erp2024 hide 08-07-2024 -->
                                    <!-- <button type="button" class="btn btn-primary" aria-label="Left Align"
                                            id="addproduct">
                                        <i class="fa fa-plus-square"></i> <?php //echo $this->lang->line('Add Row') ?>
                                    </button> -->
                                    <!-- erp2024 hide 08-07-2024 -->
                                </td>
                                <td colspan="5"></td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="reverse_align no-border">
                                    <strong><?php echo $this->lang->line('Subtotal') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">                             
                                    <span id="grandamount" class="lightMode"><?=number_format($subtotal,2)?></span></td>
                            </tr>
                            <tr class="sub_c d-none" style="display: table-row;"> 
                                <td colspan="4" class="reverse_align no-border"><input type="hidden"  id="subttlform" name="subtotal" value="<?=number_format($grand_total,2)?>"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                </td>
                                <td align="left" colspan="2" class="no-border"><span
                                            class="currenty lightMode"><?= $this->config->item('currency'); ?></span>
                                    <span id="taxr" class="lightMode">0</span></td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="reverse_align no-border">
                                    <strong><?php echo $this->lang->line('Total Product Discount') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">                             
                                    <span id="discs" class="lightMode"><?=(number_format($total_product_discount,2))?></span></td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="reverse_align no-border">
                                    <strong><?php echo $this->lang->line('Order Discount') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">                             
                                    <span id="discs" class="lightMode"><?=number_format($master_data['order_discount'],2)?></span>
                                    <!-- <span id="discs" class="lightMode"><?=number_format($order_discountamount,2)?></span> -->
                                    <input type="hidden" name="order_discount" id="order_discount" value="<?=$master_data['order_discount']?>">
                                    <!-- <input type="hidden" name="order_discount" id="order_discount" value="<?=$order_discountamount?>"> -->
                                </td>
                            </tr>

                           
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="reverse_align no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <input type="number" class="form-control shipVal text-right" onkeypress="return isNumber(event)" placeholder="0.00" id="shipping" name="shipping"  autocomplete="off" onkeyup="billUpyog()">

                                        
                                    <!-- ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> ) -->
                                </td>
                            </tr>
                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="5" class="reverse_align no-border">
                                    <strong> <?php echo $this->lang->line('Extra') . ' ' . $this->lang->line('Discount') ?></strong>
                                </td>
                                <td align="right" colspan="2" class="no-border"><input type="text"
                                                                    class="form-control form-control discVal"
                                                                    onkeypress="return isNumber(event)"
                                                                    placeholder="Value"
                                                                    name="disc_val" autocomplete="off" value="0"
                                                                    onkeyup="billUpyog()">
                                    <input type="hidden"  name="after_disc" id="after_disc" value="<?=$total_product_discount?>">
                                    ( <?= $this->config->item('currency'); ?>
                                    <span id="disc_final">0</span> )
                                </td>
                            </tr>


                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="3" class="no-border"></td>
                                <td colspan="2" class="reverse_align no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandtotaltext"><?=number_format($grand_total,2)?></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly value="<?=$grand_total?>">
                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="3" class="no-border"> <a href="<?=base_url('deliverynotes')?>" class="btn btn-crud btn-lg btn-secondary sub-btn btn-lg" ><?php echo $this->lang->line('Back') ?></a></td>
                                <td class="reverse_align no-border" colspan="3">
                                   
                                    <?php if($master_data['status']!='Invoiced')
                                    { ?>
                                         <input type="submit"  class="btn btn-crud btn-lg btn-primary sub-btn btn-lg"  value="<?php echo $this->lang->line('Generate Invoice') ?> "   id="delivery-note-to-invoice-btn" data-loading-text="Creating...">
                                    <?php } 
                                    else{ ?>
                                        <input type="submit"  class="btn btn-lg btn-secondary disable-class"  value="<?php echo $this->lang->line('Already Converted to Invoice') ?> ">
                                    <?php }
                                        ?>
                                        <!-- delivery-note-to-invoice-btn -->
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

                                                <label class="col-sm-8"
                                                       for="document_id"><?= $row['name'] ?></label>

                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
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
                    <input type="hidden" value="new_i" id="inv_page">
                    <input type="hidden" value="DeliveryNotes/actionconverttoinvoice" id="action-url">
                    <input type="hidden" value="search" id="billtype">
                    <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                    <input type="hidden" value="<?= currency($this->aauth->get_user()->loc); ?>" name="currency">
                    <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
                    <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
                    <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
                    <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>" name="discountFormat" id="discount_format">
                    <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                           name="shipRate"
                           id="ship_rate">
                    <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                           id="ship_taxtype">
                    <input type="hidden" value="0" name="ship_tax" id="ship_tax">
                    <input type="hidden" value="0" id="custom_discount">

                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="addCustomer" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <form method="post" id="product_action" class="form-horizontal">
                <!-- Modal Header -->
                <div class="modal-header bg-gradient-directional-purple white">

                    <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Add Customer') ?></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php echo $this->lang->line('Close') ?></span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <p id="statusMsg"></p><input type="hidden" name="mcustomer_id" id="mcustomer_id" value="0">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <h5><?php echo $this->lang->line('Billing Address') ?></h5>
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
                                <label class="col-sm-2 col-form-label"
                                       for="email"><?php echo $this->lang->line('Email') ?></label>

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


                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="city" id="mcustomer_city">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Region" id="region"
                                           class="form-control margin-bottom" name="region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="country" id="mcustomer_country">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="PostBox" id="postbox"
                                           class="form-control margin-bottom" name="postbox">
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Company"
                                           class="form-control margin-bottom" name="company">
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="TAX ID"
                                           class="form-control margin-bottom" name="tax_id" id="mcustomer_city">
                                </div>


                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label  col-form-label-sm"
                                       for="customergroup"><?php echo $this->lang->line('Group') ?></label>

                                <div class="col-sm-10">
                                    <select name="customergroup" class="form-control form-control-sm">
                                        <?php
                                        foreach ($customergrouplist as $row) {
                                            $cid = $row['id'];
                                            $title = $row['title'];
                                            echo "<option value='$cid'>$title</option>";
                                        }
                                        ?>
                                    </select>


                                </div>
                            </div>


                        </div>

                        <!-- shipping -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <h5><?php echo $this->lang->line('Shipping Address') ?></h5>
                            <div class="form-group row">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="customer1s"
                                           id="copy_address">
                                    <label class="custom-control-label"
                                           for="copy_address"><?php echo $this->lang->line('Same As Billing') ?></label>
                                </div>


                                <div class="col-sm-10">
                                    <?php echo $this->lang->line("leave Shipping Address") ?>
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                       for="shipping_name"><?php echo $this->lang->line('Name') ?></label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Name"
                                           class="form-control margin-bottom" id="mcustomer_name_s" name="shipping_name"
                                           required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                       for="shipping_phone"><?php echo $this->lang->line('Phone') ?></label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Phone"
                                           class="form-control margin-bottom" name="shipping_phone" id="mcustomer_phone_s">
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                       for="shipping_email"><?php echo $this->lang->line('Email') ?></label>

                                <div class="col-sm-10">
                                    <input type="email" placeholder="Email"
                                           class="form-control margin-bottom" name="shipping_email"
                                           id="mcustomer_email_s">
                                </div>
                            </div>
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                       for="shipping_address_1"><?php echo $this->lang->line('Address') ?></label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Address"
                                           class="form-control margin-bottom " name="shipping_address_1"
                                           id="mcustomer_address1_s">
                                </div>
                            </div>
                            <div class="form-group row">


                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="shipping_city" id="mcustomer_city_s">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Region" id="shipping_region"
                                           class="form-control margin-bottom" name="shipping_region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="shipping_country" id="mcustomer_country_s">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                    <input type="text" placeholder="PostBox" id="shipping_postbox"
                                           class="form-control margin-bottom" name="shipping_postbox">
                                </div>
                            </div>


                        </div>

                    </div>
                    <?php
                    if(is_array($custom_fields_c)){
                        foreach ($custom_fields_c as $row) {
                            if ($row['f_type'] == 'text') { ?>
                                <div class="form-group row">

                                    <label class="col-sm-2 col-form-label"
                                            for="document_id"><?= $row['name'] ?></label>

                                    <div class="col-sm-8">
                                        <input type="text" placeholder="<?= $row['placeholder'] ?>"
                                                class="form-control margin-bottom b_input"
                                                name="custom[<?= $row['id'] ?>]">
                                    </div>
                                </div>


                            <?php }
                        }
                    }
                    ?>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-crud btn-default"
                            data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                    <input type="submit" id="mclient_add" class="btn btn-crud btn-primary submitBtn" value="ADD"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {
                invocieduedate: { required: true },
            },
            messages: {
                invocieduedate: "Enter Invoice Due Date",
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

        $('#delivery-note-to-invoice-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#delivery-note-to-invoice-btn').prop('disabled', true); // Disable button to prevent multiple submissions

            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create a new invoice?",
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
                            url: baseurl + 'DeliveryNotes/actionconverttoinvoice', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                window.location.href = baseurl + 'invoices/view?id='+response.data; 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#delivery-note-to-invoice-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#delivery-note-to-invoice-btn').prop('disabled', false);
            }
        });

        

    });

    $( "#shipping" ).blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    
</script>