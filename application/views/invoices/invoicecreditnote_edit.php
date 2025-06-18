
<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <?php
                
                $prefix = $prefix['stockreturn_prefix'];
                ?>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoicecreditnotes') ?>"><?php echo $this->lang->line('Credit Notes'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $prefix.$notemaster['tid']; ?></li>
                </ol>
            </nav>
            
            <div class="row">
                <div class="col-xl-4 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $prefix.$notemaster['tid']; ?> </h4>
                    <!-- <h4 class="card-title"><?php echo $this->lang->line('Delivery Return')." #".$notemaster['tid']; ?> </h4> -->
                </div>
                <div class="col-xl-8 col-lg-9 col-md-8 col-sm-12 col-xs-12">
                    <ul id="trackingbar">
                    <?php
                    
                    
                    $delnotenum = (!empty($notemaster['delnote_number'])) ?$notemaster['delnote_number'] :$notemaster['delnotenumber'];
                    if(!empty($trackingdata))
                        {
                            if(!empty($trackingdata['lead_id']))
                            { ?> 
                                <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li>
                                <?php } 
                            if(!empty($trackingdata['quote_number'])) { ?><li><a href="<?= base_url('quote/view?id=' . $trackingdata['quote_number']) ?>" target="_blank">QT #<?= $trackingdata['quote_number']; ?></a></li>
                            <?php } 
                            if(!empty($notemaster['salesorder_number'])) { ?><li><a href="<?= base_url('quote/salesorders?id=' . $notemaster['salesorder_id']) ?>" target="_blank">SO #<?= $notemaster['salesorder_number']; ?></a></li>
                            <?php } 
                            if(!empty($notemaster['delevery_note_id'])) { ?><li><a href="<?= base_url('DeliveryNotes/create?id=' . $notemaster['delevery_note_id']) ?>" target="_blank">DN #<?= $delnotenum; ?></a></li>
                            <?php } 
                            if (!empty($invoiceid) && $invoiceid> 0)  { ?><li><a href="<?= base_url('invoices/view?id=' . $invoiceid) ?>" target="_blank">IN #<?= $invoiceid+1000; ?></a></li>
                            <?php } ?>
                            <li class="active">DR #<?php echo $delivery_return_number+1; ?></li>
                        <?php
                           } ?> 
                           
                           
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
               
                <!-- <input type="hidden" value="DeliveryNotes/delivery_return_action" id="action-url"> -->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Customer Details'); ?></h3>
                                        <?php echo '<input type="hidden" name="customer_id" id="customer_id" value="' . $notemaster['csd'] . '">
                                            <div id="customer_name"><strong>' . $notemaster['name'] . '</strong></div>
                                            </div>
                                            <div class="clientinfo">

                                                <div id="customer_address1"><strong>' . $notemaster['address'] . '<br>' . $notemaster['city'] . ',' . $notemaster['country'] . '</strong></div>
                                            </div>

                                            <div class="clientinfo"> <div type="text" id="customer_phone">Phone: <strong>' . $notemaster['phone'] . '</strong><br>Email: <strong>' . $notemaster['email'] . '</strong></div>
                                            </div>
                                            <div class="clientinfo">
                                            <div type="text" >'.$this->lang->line('Company Credit Limit').' : <strong>' . $notemaster['credit_limit'] . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $notemaster['credit_period'] . '</strong><br>'.$this->lang->line('Available Credit Limit').' : <strong>' . $notemaster['avalable_credit_limit'] . '</strong><input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="' . $notemaster['avalable_credit_limit'] . '"></div>
                                            </div>'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-12 cmp-pnl">
                                <div class="inner-cmp-pnl">
                                    <div class="form-group row">

                                        <div class="col-sm-12">
                                            <h3 class="title-sub">
                                                <?php echo $this->lang->line('Credit Note Details'); ?></h3>
                                        </div>
                                        <!-- erp2024 modified section 07-06-2024 -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Credit Note Number'); ?>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control"  placeholder="Invoice Return Number" name="invoice_return_number" id="invoice_return_number" value="<?php echo $prefix.$notemaster['tid']; ?>"  readonly>
                                                <input type="hidden" class="form-control"  placeholder="Invoice Return Number" name="invocieno" id="creditnotetid" value="<?php echo $notemaster['tid']; ?>"  readonly>
                                                <input type="hidden" class="form-control"  name="invocieid" id="invocieid" value="<?php echo $notemaster['invoiceid']; ?>"  readonly>
                                                <input type="hidden" class="form-control" name="store_id" id="store_id" value="<?php echo $notemaster['store_id']; ?>">
                                                <input type="hidden" class="form-control" name="payment_type" id="payment_type" value="<?php echo $notemaster['payment_type']; ?>">
                                                <input type="hidden" class="form-control" name="invoice_returnid" id="invoice_returnid" value="<?php echo $notemaster['returnid']; ?>">
                                                <input type="hidden" class="form-control" name="bank_transaction_ref_number" id="bank_transaction_ref_number" value="<?php echo $bank_transaction_ref_number['trans_ref_number']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Invoice Number'); ?>
                                            </label>
                                            <?php 
                                            $invoice_number = (!empty($notemaster['invoice_number'])) ? $notemaster['invoice_number']:$notemaster['tid'];
                                            ?>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                              
                                                <input type="hidden" name="invoice_id" id="invoice_id" class="form-control" value="<?php echo $notemaster['invoiceid']; ?>" readonly>
                                                <input type="text" class="form-control" placeholder="Invoice Number" name="invoice_number" id="invoice_number" value="<?php echo $invoice_number; ?>" readonly>
                                                   
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Reference'); ?></label>

                                                <input type="text" class="form-control" name="refer" id="refer"
                                                    value="<?php echo $notemaster['refer']; ?>" readonly>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Invoiced Date'); ?></label>
                                                <input type="text" class="form-control" name="invoicedate" id="invoicedate"
                                                    value="<?php echo date('d-m-Y', strtotime($notemaster['invoicedate'])); ?>" readonly>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="notes"  class="col-form-label"><?php echo $this->lang->line('Note'); ?> <span class="compulsoryfld"> *</span></label>
                                            <textarea name="notes" id="notes" data-original-value="" class="form-textarea" minlength="5"><?php echo $notemaster['notes']; ?></textarea>
                                                
                                        </div>
                                        <input type="hidden" class="form-control" name="invoiceduedate" id="invoiceduedate"  value="<?php echo date('d-m-Y', strtotime($notemaster['invoiceduedate'])); ?>" readonly>
                                        <input type="hidden" class="form-control" name="pterms" id="pterms" value="<?php echo $notemaster['term']; ?>">
                                        <input type="hidden" class="form-control" name="transaction_number" id="transaction_number" value="<?php echo $notemaster['transaction_number']; ?>">

                                        <input type="hidden" id="order_discount_percentage" name="order_discount_percentage" readonly value="<?= $notemaster['order_discount_percentage']?>">
                                        <input type="hidden" id="shipping_percentage" name="shipping_percentage" readonly value="<?php echo $notemaster['shipping_percentage']; ?>">
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div id="saman-row">
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
                                    // if($row['qty'] == $row['product_qty']){
                                    //     $disableclass = "readonly";
                                    // }
                                    echo '<input type="hidden" class="form-control" name="product_name[]" value="' . $row['product_name'] . '">';
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
                                    $discountrate = $discountrate+$row['totaldiscount'];
                                    $total_product_price += intval($row['qty'])*$row['price'];
                                    echo '<td width="2%">'.$j.' <input type="hidden" class="form-control" name="product_id[]" value="'.$row['pid'].'" id="product_id-'.$i.'"><input type="hidden" class="form-control" name="account_number[]" value="'.$row['account_number'].'" id="account_number-'.$i.'"></td>';

                                    echo '<td><strong>'.$row['product_code'].'</strong> </td>';
                                    echo '<td width="15%"><strong>'.$row['product_name'].'</strong> </td>';


                                    echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';

                                    echo '<td class="text-center"><strong>'.intval($row['qty']).'</strong> </td>';
                                    
                                    echo '<td  class="text-center"><strong>'.intval($row['qty']).'</strong> <input type="hidden" class="form-control" name="delivered_qty[]" value="'.intval($row['qty']).'" id="delivered_qty-'.$i.'"></td>';

                                    echo '<td class="text-center"><strong>'.intval($row['qty']).'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="0"></td>';
                                    
                                    //remove convert_shipping_percentage_to_amount
                                    // echo '<td style="text-align:center;"><input type="number" class="form-control req prc returnitem " '.$disableclass.' name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(),calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount(),convert_shipping_percentage_to_amount()" value="'.intval($row['qty']).'" title="'.$product_name_with_code.'Return Quantity" data-original-value="'.intval($row['qty']).'"><input type="hidden" class="form-control" name="return_qty_old[]"   value="'.intval($row['qty']).'"></td>';

                                    echo '<td style="text-align:center;"><input type="number" class="form-control req prc returnitem " '.$disableclass.' name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(),calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount()" value="'.intval($row['qty']).'" title="'.$product_name_with_code.'Return Quantity" data-original-value="'.intval($row['qty']).'"><input type="hidden" class="form-control" name="return_qty_old[]"   value="'.intval($row['qty']).'"></td>';
                                    
                                    echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.'  name="damaged_qty[]" id="damaged_qty-' . $i . '" value="'.intval($row['damaged_qty']).'" onkeypress="return isNumber(event)" onkeyup="damageqtycheck(' . $i . ')" placeholder="'.$this->lang->line('Enter Qty').'" title="'.$product_name_with_code.'Damaged Quantity" data-original-value="'.intval($row['damaged_qty']).'"><input type="hidden" class="form-control"name="damaged_qty_old[]"   value="'.intval($row['damaged_qty']).'"></td>';

                                    echo '<td style="text-align:right;"><strong>'.$row['price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                   onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                   autocomplete="off" value="' . amountExchange_s($row['price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                   if($configurations['config_tax']!='0')
                                   {
                                     echo '<td class="text-center"  style="font-weight:bold;">'.$row['totaltax'].'</td>';
                                     echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">0</td>';
                                   }

                                    // <!-- erp2024 modified section 07-06-2024 -->
                                       echo '<td class="text-center"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$row['totaldiscount'].'</strong></td>';

                                        echo '<td class="text-right"><span class="currenty"></span>
                                            <strong><span class="ttlText" id="result-' . $i . '">'.$row['subtotal'].'</span></strong></td>
                                        </td>
                                        
                                        <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                        <input type="hidden" name="disca[]" id="disca-' . $i . '" value="'.$row['totaldiscount'].'">
                                        <input type="hidden" name="old_discount[]" id="old_discount-' . $i . '" value="'.$row['totaldiscount'].'">

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
                                        <button class="btn btn-crud btn-lg btn-secondary deleteinvoice-btn" title="<?php echo 'This will Delete the Invocie Return #'.$notemaster['tid'].' and will Adjust the Accounts and Inventory;' ?> "><?php echo $this->lang->line('Delete Invoice'); ?></button>
                                        </td>
                                        <td align="right" colspan="8"  class="no-border">
                                        <input type="submit" id="submit-invoice-return" class="btn btn-primary btn-crud btn-lg submitBtn" value="<?php echo $this->lang->line('Update'); ?>"/>
                                        <!-- <input type="submit" id="submit-data" class="btn btn-primary btn-lg submitBtn" value="Generate Delivery Return"/> -->
                                        <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>


                </form>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
// erp2024 newly added 11-01-2024 for detailed history log ends 
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
    // alert(numb);
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