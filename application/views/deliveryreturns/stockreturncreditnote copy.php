<div class="content-body">
    <?php       
        if (($msg = check_permission($permissions)) !== true) {
            echo $msg;
            return;
        }       
    ?>
    <div class="card">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('deliveryreturn') ?>"><?php echo $this->lang->line('Delivery Returns'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo ($lastinvoice); ?></li>
                </ol>
            </nav>
            
            
            <div class="row">
                <div class="col-xl-4 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                <h4 class="card-title"><?php echo ($lastinvoice); ?></h4>
                </div>
                <div class="col-xl-8 col-lg-9 col-md-8 col-sm-12 col-xs-12 ">
                    <ul id="trackingbar">
                    <?php 
                    
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
                            if(!empty($notemaster['delivery_note_number'])) { ?><li><a href="<?= base_url('DeliveryNotes/create?id=' . $notemaster['delivery_note_number']) ?>" target="_blank">DN #<?= $notemaster['delivery_note_number']; ?></a></li>
                            <?php } 
                            if (!empty($invoice_details) && $invoice_details > 0) { ?><li><a href="<?= base_url('invoices/view?id=' . $invoice_details) ?>" target="_blank">IN #<?= $invoice_details; ?></a></li>
                            <?php } ?>
                            <?php 
                            if (!empty($notemaster['delivery_return_number'])) { ?><li><a href="<?= base_url('Deliveryreturn/deliveryreturn_view?id=' . $notemaster['delivery_return_number']) ?>" target="_blank">DR #<?= $notemaster['delivery_return_number'] ?></a></li>
                            <?php } ?>
                            <!-- <li class="active">CN #<?php echo $lastinvoice; ?></li> -->
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
            
            <div class="card-body">
                
                <form method="post" id="data_form">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub">
                                            <?php echo $this->lang->line('Customer Details');?>
                                        </h3>
                                    </div>
                                    <!-- <div class="frmSearch col-sm-12"><label for="cst"
                                                                            class="col-form-label"><?php echo $this->lang->line('Search Customer') ?> </label>
                                        <input type="text" class="form-control" name="cst" id="customer-box"
                                               placeholder="Enter Customer Name or Mobile Number to search"
                                               autocomplete="off"/>

                                        <div id="customer-box-result"></div>
                                    </div> -->

                                </div>
                                <div id="customer">
                                    <div class="clientinfo">
                                        <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer['id']?>">
                                        <div id="customer_name"><?=$customer['name']?></div>
                                    </div>
                                    <div class="clientinfo">

                                        <div id="customer_address1">
                                        <?php echo $customer['address']."\n".$customer['city']."\n".$customer['postbox']."\n".$customer['postbox']."\n"; ?>
                                        </div>
                                    </div>

                                    <div class="clientinfo">
                                        <div type="text" id="customer_phone">
                                            <?php echo $customer['phone']."\n<br>".$customer['email']; ?></div>
                                    </div>


                                    <div class="clientinfo">
                                        <div type="text">
                                                <?php
                                                    echo $this->lang->line('Company Credit Limit').' : <strong>' . number_format($customer['credit_limit'],2) . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $customer['credit_period'] . '</strong><br>'.$this->lang->line('Available Credit Limit').' : <strong>' . number_format($customer['avalable_credit_limit'],2) . '</strong>';
                                                ?>
                                        </div>
                                    </div>
                                    
                                </div>


                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12  cmp-pnl">
                            <div class="inner-cmp-pnl">


                                <div class="form-group row">

                                    <div class="col-sm-12">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Credit Note Properties');?> </h3>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Credit Note Number') ?> </label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="icon-file-text-o" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Invoice #" name="delivery_return_number" value="<?php echo $prefix.($lastinvoice) ?>" readonly>
                                            <input type="hidden" class="form-control" placeholder="Invoice #" name="invocieno" value="<?php echo $lastinvoice ?>" readonly>
                                            <input type="hidden" class="form-control"  name="invoice_id" value="<?php echo $invoice_details['invoice_number'] ?>" readonly>
                                            <input type="hidden" class="form-control"  name="transaction_number" value="<?php echo $notemaster['return_transaction_number'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Reference') ?><span class="compulsoryfld">*</span> </label>
                                            <input type="text" class="form-control" placeholder="Reference #"  name="refer">
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 d-none">
                                        <label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Order Date') ?> </label>

                                        <div class="input-group">
                                            <input type="date" class="form-control required" name="invoicedate" min="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Order Due Date') ?> </label>

                                        <div class="input-group">
                                            <input type="date" class="form-control required" id="tsn_due"  name="invocieduedate" min="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 d-none">
                                        <label for="taxformat"
                                               class="col-form-label"><?php echo $this->lang->line('Tax') ?> </label>
                                        <select class="form-control"
                                                onchange="changeTaxFormat(this.value)"
                                                id="taxformat">
                                            <?php echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 d-none">
                                            <label for="discountFormat"
                                                   class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                            <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                    id="discountFormat">
                                                <?php echo $this->common->disclist() ?>
                                            </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 d-none">
                                        <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                        <select id="s_warehouses" class="selectpicker form-control">
                                            <option value="0"><?php echo $this->lang->line('All') ?></option>
                                            <?php foreach ($warehouse as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?></label>
                                        <select name="pterms" class="selectpicker form-control"><?php foreach ($terms as $row) {
                                                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>

                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="Update Stock" class="col-form-label"><?php echo $this->lang->line('Update Stock') ?> </label>
                                        <div class="mt-1">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight1" value="yes">
                                                <label class="form-check-label" for="customRadioRight1"><?php echo $this->lang->line('Yes') ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight2" value="no" checked>
                                                <label class="form-check-label" for="customRadioRight2"><?php echo $this->lang->line('No') ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo"
                                               class="col-form-label"><?php echo $this->lang->line('Note') ?><span class="compulsoryfld">*</span> </label>
                                        <textarea class="form-textarea" name="notes" id="notes" rows="2"></textarea></div>
                                </div>

                            </div>
                        </div>

                    </div>


                    <div id="saman-row">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>

                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="4%" class="text-center"><?php echo $this->lang->line('SN') ?></th>
                                <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                <th width="25%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Delivered Qty') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Return Qty') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Damaged Qty') ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th> -->
                                <th width="7%" class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                                <th width="10%" class="text-right">
                                    <?php echo $this->lang->line('Amount') ?>
                                    <?php //echo "(".$this->config->item('currency').")"; ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($products))
                                {
                                    $i=0;
                                    $totaltax = 0;
                                    $totaldiscount = 0;
                                    $grandtotal = 0;
                                    $k=1;
                                    foreach($products as $product){
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $k++;?></td>
                                            <td><?php echo $product['product_code'];?></td>
                                            <td><input type="hidden" class="form-control" name="product_code[]"
                                                    id='productcode-<?=$i?>' value="<?php echo $product['product_code'];?>" readonly><input type="hidden" class="form-control" name="product_name[]" placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' value="<?php echo $product['product_name'];?>" readonly>
                                                    <strong><?php echo $product['product_name']; ?></strong>
                                            </td>

                                            
                                            <td class="text-center"><?php echo $product['delivered_qty'];?></td>

                                            <td class="text-center"><input type="hidden" class="form-control req amnt" name="product_qty[]" id="amount-<?=$i?>" value="<?php echo $product['return_qty'];?>" readonly>
                                            <strong><?php echo intval($product['return_qty']);?></strong></td>


                                            <td class="text-center"><?php echo $product['damaged_qty'];?></td>
                                            <td class="text-right">
                                                <strong><?php echo number_format($product['product_price'],2);?></strong>
                                                <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>" value="<?php echo $product['product_price'];?>" readonly></td>
                                            <!-- <td>
                                                <input type="text" class="form-control vat " name="product_tax[]" id="vat-<?=$i?>"  autocomplete="off" value="<?php echo $product['product_tax'];?>" readonly>
                                            </td> -->
                                            <!-- <td class="text-center" id="texttaxa-<?=$i?>"><?php echo $product['totaltax'];?></td> -->
                                            <td class="text-right">
                                                <strong><?php echo number_format($product['totaldiscount'],2);?></strong>
                                                <input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off" value="<?php echo $product['totaldiscount'];?>" readonly>
                                            </td>
                                            <td class="text-right"> <strong><span class='ttlText' id="result-<?=$i?>"> <?php echo number_format($product['deliverysubtotal'],2);?></span></strong></td>
                                            <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="<?php echo $product['totaltax'];?>">
                                            <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?php echo $product['totaldiscount'];?>">
                                            <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?php echo $product['subtotal'];?>">
                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?php echo $product['product_id'];?>">
                                            <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?php echo $product['unit'];?>">
                                            <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$product['product_code']?>">
                                            <input type="hidden" class="form-control vat " name="product_tax[]" id="vat-<?=$i?>"  autocomplete="off" value="<?php echo $product['product_tax'];?>" readonly>
                                        </tr>
                                        <!-- <tr>
                                            <td colspan="10">
                                                <textarea id="dpid-<?=$i?>" class="form-control" name="product_description[]" placeholder="<?php echo $this->lang->line('Enter Product description'); ?>" autocomplete="off" readonly><?php echo $product['product_des'];?></textarea>
                                            </td>
                                        </tr> -->
                                    <?php
                                        $totaltax = $product['totaltax'] + $totaltax;
                                        $totaldiscount = $product['totaldiscount'] + $totaldiscount;
                                        $grandtotal = $product['subtotal'] + $grandtotal;
                                    }
                                }
                                ?>
                           
                            

                            <!-- <tr class="last-item-row tr-border">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-secondary" id="addproduct">
                                        <i class="fa fa-plus-square"></i> <?php //echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr> -->

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="7" align="right" class="no-border"> 
                                    <input type="hidden" value="<?=$grandtotal?>" id="subttlform" name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                </td>
                                <td align="left" colspan="2" class="no-border">
                                    <span id="taxr" class="lightMode"><?php echo $totaltax; ?></span></td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="8" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Discount') ?><?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode"><?php echo $totaldiscount;?></span></td>
                            </tr>

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="8" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="left" colspan="2" class="no-border">
                                    <input type="text" class="form-control shipVal" onkeypress="return isNumber(event)"  placeholder="Value"  name="shipping" autocomplete="off" onkeyup="billUpyog();">
                                    ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> )
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="1" class="no-border"><?php if ($exchange['active'] == 1){
                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                    <select name="mcurrency"
                                            class="selectpicker form-control">
                                        <option value="0">Default</option>
                                        <?php foreach ($currency as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                        } ?>

                                    </select><?php } ?></td>
                                <td colspan="7" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        <?php //echo '(<span class="currenty lightMode">' . $this->config->item('currency') . '</span>)'; ?></strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandtotaltext"><?php echo number_format($grandtotal,2);?></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly value="<?php echo $grandtotal; ?>">

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                
                                <td align="right" colspan="9" class="no-border">
                                    <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Generate Credit Note'); ?>" id="generate-credit-note" data-loading-text="Creating...">
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                    <!-- <input type="hidden" value="stockreturn/action" id="action-url"> -->
                    <input type="hidden" value="<?=$delivery_return_number?>" id="delivery_return_number" name="delivery_return_number">
                    <input type="hidden" value="2" name="person_type">
                    <input type="hidden" value="puchase_search" id="billtype">
                    <input type="hidden" value="0" name="counter" id="ganak">
                    <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
                    <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">

                    <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
                    <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">

                    <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                           name="discountFormat" id="discount_format">
                    <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                           name="shipRate"
                           id="ship_rate">
                    <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                           id="ship_taxtype">
                    <input type="hidden" value="0" name="ship_tax" id="ship_tax">
                    <input type="hidden" value="<?=$salesorderid?>" name="salesorderid" id="salesorderid">


                </form>
            </div>

        </div>
    </div>
</div>


<script>
$(document).ready(function() {

    $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {               

                refer: { required: true },
                notes: { required: true },
            },
            messages: {
                refer: "Enter Internal Reference",
                notes: "Enter Comments",
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

    $('#generate-credit-note').prop('disabled', false);
    
    $('#generate-credit-note').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        var deliverynoteid = $("#delivery_return_number").val();
        var salesorderid = $("#salesorderid").val();
        var customerid = $("#customer_id").val();
        if ($("#data_form").valid()) {    
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to proceed with generating the credit note?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No, cancel",
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with the AJAX request
                    $('#generate-credit-note').prop('disabled', true);
                    hasUnsavedChanges = false;
                    var form = $('#data_form')[0];
                    var formData = new FormData(form);

                    $.ajax({
                        url: baseurl + "stockreturn/action",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var data = JSON.parse(response);
                            // Swal.fire({
                            //         icon: 'success',
                            //         title: 'Credit Note',
                            //         text: 'Credit Note Created successfully!',
                            //         confirmButtonText: 'OK'
                            //     }).then((result) => {
                            //         if (result.isConfirmed) {
                                        window.open(baseurl + 'Deliveryreturn/reprint_converted_credit_note?delivery=' + deliverynoteid + '&sales=' + salesorderid + '&cust=' + customerid, '_blank');
                                        window.location.href = baseurl + 'Deliveryreturn';
                                //     }
                                // });
                            $('#generate-credit-note').prop('disabled', false);                    
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the material request', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                }
            });
        }
    });
});

</script>