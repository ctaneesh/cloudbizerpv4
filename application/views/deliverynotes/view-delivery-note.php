<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <?php
            if($notemaster['notestatus']=="Printed" && $delivery_return_status !=1){
                $invoiceBtn = '<button onclick="invoicing(\'' . $id . '\')" class="btn btn-sm btn-secondary"><i class="fa fa-exchange"></i> Convert to Invoice</button>';
                $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn?delivery=$id") . '" target="_blank" class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Delivery Return').'</a>';
            }
            else{
                $invoiceBtn="";
                $deliveryBtn ="";
            }
            
            ?>           
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('DeliveryNotes') ?>"><?php echo $this->lang->line('Delivery Notes') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $this->lang->line('Delivery Note'). " #".$notemaster['delnote_number']; ?></li>
                    </ol>
                </nav>
                <h4 class="card-title"><?php echo $this->lang->line('Delivery Note'). " #".$notemaster['delnote_number']." ".$invoiceBtn." ".$deliveryBtn; ?></h4>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">  
                    <ul id="trackingbar">
                    <?php if(!empty($trackingdata))
                        {
                            if(!empty($trackingdata['lead_id']))
                            { ?>
                                <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li>
                            <?php }
                            if(!empty($trackingdata['quote_number']))
                            { ?>
                                <li><a href="<?= base_url('quote/view?id=' . $trackingdata['quote_number']) ?>" target="_blank">QT #<?= $trackingdata['quote_number']; ?></a></li>
                            <?php }
                            if(!empty($trackingdata['salesorder_number']))
                            { ?>
                                <li><a href="<?= base_url('quote/salesorders?id=' . $trackingdata['salesorder_number']) ?>" target="_blank">SO #<?= $trackingdata['salesorder_number']; ?></a></li>
                            <?php }
                            ?> <li class="active">DN #<?php echo (1000+$id); ?></li><?php
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
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Customer Details'); ?></h3>
                                        <?php
                                        echo '<input type="hidden" name="customer_id" id="customer_id" value="' . $notemaster['csd'] . '">
                                            <div id="customer_name"><strong>' . $notemaster['name'] . '</strong></div>
                                            </div>
                                            <div class="clientinfo">

                                                <div id="customer_address1"><strong>' . $notemaster['address'] . '<br>' . $notemaster['city'] . ',' . $notemaster['country'] . '</strong></div>
                                            </div>

                                            <div class="clientinfo">

                                                <div type="text" id="customer_phone">Phone: <strong>' . $notemaster['phone'] . '</strong><br>Email: <strong>' . $notemaster['email'] . '</strong></div>
                                            </div>
                                            
                                            <div class="clientinfo">
                                            <div type="text" >'.$this->lang->line('Company Credit Limit').' : <strong>' . $notemaster['credit_limit'] . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $notemaster['credit_period'] . '</strong><br><span class='.$cls.'>'.$this->lang->line('Available Credit Limit').' : <strong>' . $notemaster['avalable_credit_limit'] . '</strong></span><input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="' . $notemaster['avalable_credit_limit'] . '"></div>
                                            </div>'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-12 cmp-pnl">
                                <div class="inner-cmp-pnl">
                                    <div class="form-group row">

                                        <div class="col-sm-12">
                                            <h3 class="title-sub">
                                                <?php echo $this->lang->line('Delivery Note Properties'); ?></h3>
                                        </div>

                                        <!-- erp2024 modified section 07-06-2024 -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Delivery Note Number'); ?>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="hidden" class="form-control"
                                                    placeholder="Delivery Note Number" name="delevery_note_id" id="delevery_note_id"
                                                    value="<?php echo $notemaster['delevery_note_id']; ?>"
                                                    readonly>
                                                <input type="text" class="form-control"  placeholder="Delivery Note Number" name="invocieno" id="invocienoId"  value="<?php echo $notemaster['delnote_number']; ?>" readonly>
                                                <input type="hidden" class="form-control"  name="store_id" id="store_id"  value="<?php echo $notemaster['store_id']; ?>" readonly>
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
                                                for="invocieduedate" class="col-form-label">Reference</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" name="invocieduedate"
                                                    id="invocieduedate" value="<?php echo $notemaster['refer']; ?>"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieduedate" class="col-form-label">Reference Date</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" name="invocieduedate"
                                                    id="invocieduedate" placeholder="Validity Date" autocomplete="false"
                                                    value="<?php echo date("d-m-Y", strtotime($notemaster['invoicedate'])); ?>"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="toAddInfo" class="col-form-label">Comments</label>
                                            <textarea class="form-control" name="notes" id="salenote" rows="2"
                                                readonly><?php echo $notemaster['notes'] ?></textarea>
                                        </div>
                                        <?php
                                            if($delivery_return_status==1)
                                            {
                                                $notemaster['notestatus'] = "Fully Returned";
                                            }
                                        ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="toAddInfo" class="col-form-label">Status</label>
                                            <input type="text" class="form-control" name="status" id="status"
                                                value="<?php echo $notemaster['notestatus']; ?>" readonly>
                                        </div>

                                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="toAddInfo" class="col-form-label">Comments</label>
                                                <textarea class="form-control" name="notes" id="salenote" rows="2" readonly><?php echo $notemaster['notes'] ?></textarea>
                                        <div> -->
                                    </div>

                                    <div class="form-group row d-none">
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="taxformat" class="col-form-label">Tax</label>
                                            <select class="form-control" onchange="changeTaxFormat(this.value)"
                                                id="taxformat">

                                                <?php //echo $taxlist; ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">

                                            <div class="form-group">
                                                <label for="discountFormat" class="col-form-label">Discount</label>
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
                        
                        <div id="saman-row">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>

                                    <tr class="item_header bg-gradient-directional-blue white">
                                        <th width="4%" style="padding-left:10px;">Sl.No</th>
                                        <th width="25%" class="text-center1"><?=$this->lang->line('Item Name')?></th>
                                        <th width="10%" class="text-center1"><?=$this->lang->line('Item No')?></th>
                                        <th width="6%" class="text-center"><?=$this->lang->line('Unit')?></th>
                                        <th width="8%" class="text-center"><?=$this->lang->line('Ordered Qty')?></th>
                                        <th width="8%" class="text-center">Delivery Qty</th>
                                        <th width="8%" class="text-center"><?=$this->lang->line('Returned Qty')?></th>
                                        <th width="10%" class="text-right"><?=$this->lang->line('Rate')?></th>
                                        <?php 
                                        if($configurations['config_tax']!='0'){  ?>
                                            <!-- <th width="10%" class="text-center">Tax</th> -->
                                         <?php } ?>
                                        <th width="10%" class="text-right"><?=$this->lang->line('Discount')?> Discount</th>
                                        <!-- <th width="7%" class="text-center">Discount</th> -->
                                        <th width="15%" class="text-right">
                                            Amount(<?php echo $this->config->item('currency'); ?>)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;$j = 1;
                                $taxtotal = 0;
                                $productrate = 0;
                                $discountrate = 0;

                                
                                // echo "<pre>"; print_r($products); die();
                                foreach ($products as $row) {
                                    echo '<input type="hidden" class="form-control" name="product_name[]" value="' . $row['product'] . '">';
                                    echo '<input type="hidden" class="form-control" name="product_code[]" value="' . $row['product_code'] . '">';
                                    // if($row['totalQty']<=$row['alert']){
                                    //     echo '<tr style="background:#ffb9c2;">';
                                    // }
                                    // else{
                                    //     echo '<tr >';
                                    // }
                                    $taxtotal = $taxtotal+$row['deliverytaxtotal'];
                                    $productrate = $productrate+$row['deliverysubtotal'];
                                    $discountrate = $discountrate+$row['deliverytotaldiscount'];
                                    echo '<td width="2%">'.$j.' <input type="checkbox" class="checkedproducts d-none" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" checked></td>';
                                   
                                    echo '<td><strong>'.$row['product'].'</strong> </td>';
                                    echo '<td><strong>'.$row['code'].'</strong> </td>';
                                    echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';
                                    echo '<td class="text-center"><strong>'.intval($row['salesorder_product_qty']).'</strong><input type="hidden" class="form-control req amnt" name="old_product_qty[]" id="oldproductqty-' . $i . '" onkeypress="return isNumber(event)" value="'.intval($row['salesorder_product_qty']).'" > </td>';
                                    
                                    
                                    echo '<td class="text-center"><strong>'.$row['product_qty'].'</strong><input type="hidden" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="'.$row['product_qty'].'" min="0"><input type="hidden" class="form-control req"id="enteredamount-' . $i . '" value="'.$row['product_qty'].'" min="0"> </td>';

                                    echo '<td class="text-center"><strong>'.$row['delivery_returned_qty'].'</strong> </td>';

                                    echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                   onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                   autocomplete="off" value="' . amountExchange_s($row['product_price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';

                                    // echo '<td style="text-align:center;"><strong>'.$row['tax'].'</strong> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['product_tax']) . '"></td>';

                                    // echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">'.$row['deliverytaxtotal'].'</td>';

                                    // echo '<td class="text-right"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">0</strong></td>';
                                    // <!-- erp2024 modified section 07-06-2024 -->
                                    //  echo '<td><strong>'.$row['discount'].'</strong></td>';
                                        echo '<td class="text-right"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$row['deliverytotaldiscount'].'</strong></td>';
                                        echo '<td class="text-right">
                                            <strong><span class="ttlText" id="result-' . $i . '">'.($row['deliverysubtotal']).'</span></strong></td>
                                        </td>
                                        <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                        <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['deliverytotaldiscount'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">
                                        
                                        <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '">

                                        <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' . amountFormat_general($row['discount']) . '">

                                        <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['discount_type'].'">
                                       
                                        <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="0">
                                        <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                        <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                                    </tr>';
                                    $i++; $j++;
                                } ?>


                                    <tr class="sub_c tr-border d-none" style="display: table-row; ">
                                        <td colspan="7" align="right" class="no-border"><strong>Total Tax(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                        <td align="left" colspan="2" class="no-border">
                                            <span id="taxr"
                                                class="lightMode"><?php echo $taxtotal; ?></span>
                                        </td>
                                    </tr>
                                    <!-- erp2024 removed section 07-06-2024 -->
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="9" class="no-border" align="right"><strong>Total Discount(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                        <td align="right" class="no-border" colspan="2">
                                            <span id="discs"  class="lightMode"><?=number_format($discountrate,2)?></span>
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
                                            <?= $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                            <span
                                                id="ship_final"><?= amountExchange_s(0, 0, $this->aauth->get_user()->loc) ?>
                                            </span>
                                            )
                                        </td>
                                    </tr> -->
                                    
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="3" class="no-border">
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
                                                (<span
                                                    class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                        </td>
                                        <?php
                                             $grandtotal = 0;
                                             $grandtotal = ($productrate)-$discountrate;
                                        ?>
                                        <td align="right" colspan="2" class="no-border">
                                            <span><?php echo number_format($grandtotal,2); ?></span>
                                            <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="<?php echo ($grandtotal); ?>" readonly>

                                        </td>
                                    </tr>
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="2"  class="no-border" align="right">
                                                
                                        </td>
                                        <td align="right" colspan="8"  class="no-border">
                                            
                                            <!-- <input type="submit" class="btn btn-lg btn-primary sub-btn" value="Update" id="update-deliverynote"  data-loading-text="Updating..."> -->
                                            <?php
                                            if($delivery_return_status !=1)
                                            { ?>
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="form-check" style="font-size:16px; margin-top:10px;  margin-bottom:10px;">
                                                    <input class="form-check-input" type="checkbox" value="1" id="deliverynoteFlg" checked>
                                                        <label class="form-check-label" for="deliverynoteFlg">
                                                            Would you like a delivery note with the price?
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <a href="#" id="printBtn" class="btn btn-lg btn-primary sub-btn" data-delivery="<?=$notemaster['delevery_note_id'] ?>" data-sales="<?=$notemaster['salesorder_number']?>" data-cust="<?=$notemaster['csd']?>">
                                                        <i class="fa fa-print"></i> Print
                                                    </a>
                                                </div>
                                            </div>

                                            <?php
                                            }
                                            // $reprintBtn = '<a href="' . base_url("DeliveryNotes/reprintnote?delivery=" . $notemaster['delevery_note_id'] . "&sales=" . $notemaster['salesorder_number'] . "&cust=" . $notemaster['csd']) . '" target="_blank" class="btn btn-lg btn-primary sub-btn"><i class="fa fa-print"></i> Print</a>';
                                            // echo $reprintBtn;
                                            ?>

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
$(document).ready(function() {

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
function checkqty(id){
      var enteredqty = parseFloat($("#amount-" + id).val()) || 0;      
      var oldqtyentered = parseFloat($("#enteredamount-" + id).val()) || 0;      
      var old_product_qty = parseFloat($("#oldproductqty-" + id).val()) || 0;
      if(enteredqty > old_product_qty){
         $("#amount-" + id).val(oldqtyentered);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is greater than the ordered quantity'
         });
      }
    }


$("#update-deliverynote").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    // Use SweetAlert for confirmation
    Swal.fire({
        title: "Are you sure?",
        // text: "Are you sure you want to update inventory? Do you want to proceed?",
        "text":"Do you want to complete this delivery note? Once completed, it will proceed to the next level. If you need to make any changes before it moves to the next level, use the 'Save AS Draft' button",
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
            formData += '&completed_status=1';
            $.ajax({
                type: 'POST',
                url: baseurl +'Quote/deliverynoteeditaction',
                data: formData,
                success: function(response) {
                    // deliveryReport();     
                    Swal.fire({
                                icon: 'success',
                                title: 'Data Saved',
                                text: 'Your data has been saved successfully!',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Reload the same screen
                                    location.reload();
                                }
                            });
                    
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});

$("#printBtn").on("click", function(e) {
    e.preventDefault();

    // Get the data attributes
    var delivery = $(this).data("delivery");
    var sales = $(this).data("sales");
    var cust = $(this).data("cust");
    var selectedProducts = [];
    $('.checkedproducts:checked').each(function() {
        selectedProducts.push($(this).val());
    });
    var store_id = $("#store_id").val();
    var status = $("#status").val();
    var salesorder_number = $("#refer1").val();
    var priceFlg = $("#deliverynoteFlg").val();
    // SweetAlert confirmation popup
    if(status=='Created'){
        messagetxt = "Want to update inventory and print the delivery note?";
    }
    else{
        messagetxt = "Want to print the delivery note?";
    }
    Swal.fire({
        title: "Are you Sure ?",
        "text":messagetxt,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, print it!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the AJAX request
            $.ajax({
                type: 'POST',
                url: baseurl + 'DeliveryNotes/deliverynote_status_update',
                data: {
                    delivery: delivery,
                    selectedProducts: selectedProducts,
                    store_id: store_id,
                    status: status,
                    salesorder_number: salesorder_number
                },
                dataType: 'json',
                success: function(response) {
                    window.open(baseurl + 'DeliveryNotes/reprintnote?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg, '_blank');
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});


        // var selectedProducts = [];
        // $('.checkedproducts:checked').each(function() {
        //     selectedProducts.push($(this).val());
        // });
        // if (selectedProducts.length === 0) {
        //     alert("Please select at least one product.");
        //     return;
        // }
        // if (confirm("Are you sure you want to update inventory?")) {
            
        //     $.ajax({
        //         type: 'POST',
        //         url: baseurl + 'Invoices/updateInventory',
        //         data: { selectedProducts: selectedProducts },
        //         dataType: 'json',
        //         success: function(response) {
        //             alert(response.message);
        //             // window.location.href = baseurl + 'purchase/create'; 
        //             //window.open(baseurl + 'purchase/create', '_blank');
        //         },
        //         error: function(xhr, status, error) {
        //         }
        //     });
        // }
</script>