<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard');?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('DeliveryNotes') ?>">Delivery Notes</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Delivery Return')." #".$delivery_return_number+1; ?></li>
                </ol>
            </nav>
            
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Delivery Return')." #".$delivery_return_number+1; ?> </h4>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-xs-12">
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
                                        <?php echo '<input type="hidden" name="customer_id" id="customer_id" value="' . $notemaster['customer_id'] . '">
                                            <div id="customer_name"><strong>' . $notemaster['name'] . '</strong></div>
                                            </div>
                                            <div class="clientinfo">

                                                <div id="customer_address1"><strong>' . $notemaster['address'] . '<br>' . $notemaster['city'] . ',' . $notemaster['country'] . '</strong></div>
                                            </div>

                                            <div class="clientinfo">                                                <div type="text" id="customer_phone">Phone: <strong>' . $notemaster['phone'] . '</strong><br>Email: <strong>' . $notemaster['email'] . '</strong></div>
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
                                                <?php echo $this->lang->line('Delivery Return Properties'); ?></h3>
                                        </div>

                                        <!-- erp2024 modified section 07-06-2024 -->
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Delivery Return Number'); ?>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control"  placeholder="Delivery Return Number" name="invocieno" id="invocienoId" value="<?php echo $delivery_return_number+1; ?>"  readonly>
                                                <input type="hidden" class="form-control" name="store_id" id="store_id" value="<?php echo $notemaster['store_id']; ?>">
                                                <input type="hidden" class="form-control" name="transaction_number" id="transaction_number" value="<?php echo $notemaster['transaction_number']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Delivery Note Number'); ?>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                              
                                                <input type="text" name="delnote_number" id="delnote_number" class="form-control" value="<?php echo $delnotenum; ?>" readonly>
                                                <input type="hidden" class="form-control"
                                                    placeholder="Delivery Note Number" name="delivery_note_number" id="delivery_note_number"
                                                    value="<?php echo $notemaster['delevery_note_id']+1000; ?>"
                                                    readonly>
                                                    <input type="hidden" name="delevery_note_id" readonly value="<?php echo $notemaster['delevery_note_id']; ?>">
                                                    <input type="hidden" name="salesorder_number" readonly value="<?php echo $notemaster['salesorder_number']; ?>">
                                                    <input type="hidden" name="salesorder_id" readonly value="<?php echo $notemaster['salesorder_id']; ?>">
                                                    <input type="hidden" id="order_discount_percentage" name="order_discount_percentage" readonly value="<?php echo $notemaster['delivery_order_discount_percentage']; ?>">
                                                
                                                   
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Sales Order'); ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" name="refer1" id="refer1"
                                                    value="<?php echo $notemaster['salesorder_number']; ?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <!-- <label  for="toAddInfo" class="col-form-label"><?php //echo $this->lang->linex('Delivery Note')." ".$this->lang->line('Status'); ?></label> -->
                                            <input type="hidden" class="form-control" name="status" id="status"
                                                value="<?php echo $notemaster['notestatus']; ?>" readonly>

                                                <label
                                                for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Is it Invoiced?'); ?></label>
                                                <?php
                                                
                                                if($deliverynote_status=='Invoiced'){
                                                    $invoicenumber =  $invoice_details['invoice_number'];
                                                    $invoicestatus = "Yes";
                                                ?>
                                                    <br><strong><?php echo $invoicestatus; ?></strong>
                                                    <br> Invoice Number : <strong><a href="<?= base_url('invoices/view?id=' . $invoice_details['id']) ?>"><?php echo "#".$invoicenumber; ?></a></strong>
                                                <?php
                                                }
                                                else{
                                                    $invoicestatus = "No"; 
                                                    ?>
                                                    <br><strong><?php echo $invoicestatus; ?></strong>
                                                    <?php
                                                }
                                                
                                                ?>
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
                                foreach ($products as $row) {
                                    $disableclass = "";
                                    if($row['delivery_returned_qty'] == $row['product_qty']){
                                        $disableclass = "readonly";
                                    }
                                    echo '<input type="hidden" class="form-control" name="product_code[]" value="' . $row['prdcode'] . '">';
                                    echo '<input type="hidden" class="form-control" name="product_name[]" value="' . $row['prdname'] . '">';
                                   
                                    echo '<input type="hidden" class="form-control" name="product_cost[]" value="' . $row['product_cost'] . '">';
                                    // if($row['totalQty']<=$row['alert']){
                                    //     echo '<tr style="background:#ffb9c2;">';
                                    // }
                                    // else{
                                    //     echo '<tr >';
                                    // }
                                    $income_account_number = ($income_account_number) ? $income_account_number : default_chart_of_account('product_income');
                                    $taxtotal = $taxtotal+$row['deliverytaxtotal'];
                                    $productrate = $productrate+$row['deliverysubtotal'];
                                    $discountrate = $discountrate+$row['totaldiscount'];
                                    $product_name_with_code = $row['product'].'('.$row['product_code'].') - ';
                                    echo '<td width="2%">'.$j.' 
                                    <input type="hidden" class="form-control" name="product_id[]" value="'.$row['product_id'].'" id="product_id-'.$i.'">
                                    <input type="hidden" class="form-control" name="income_account_number[]" value="'.$income_account_number.'" id="product_id-'.$i.'">
                                    </td>';

                                    echo '<td width="15%"><strong>'.$row['product'].'</strong> </td>';

                                    echo '<td><strong>'.$row['product_code'].'</strong> </td>';

                                    echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';

                                    echo '<td class="text-center"><strong>'.$row['salesorder_product_qty'].'</strong> </td>';
                                    
                                    echo '<td  class="text-center"><strong>'.$row['product_qty'].'</strong> <input type="hidden" class="form-control" name="delivered_qty[]" value="'.$row['product_qty'].'" id="delivered_qty-'.$i.'"></td>';

                                    echo '<td class="text-center"><strong>'.$row['delivery_returned_qty'].'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="'.$row['delivery_returned_qty'].'"></td>';

                                    echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.' title="'.$product_name_with_code.'Returned Quantuty"  name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="rowTotal(' . $i . '),billUpyog(), calculateDeliveryReturn(' . $i . '),convert_order_discount_percentage_to_amount()" value="0" data-original-value="0"></td>';
                                    echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.'  title="'.$product_name_with_code.'Damaged Quantuty" name="damaged_qty[]" id="damaged_qty-' . $i . '" value="0" onkeypress="return isNumber(event)" onkeyup="damageqtycheck(' . $i . ')" placeholder="'.$this->lang->line('Enter Qty').'" data-original-value="0"></td>';

                                    echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                   onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                   autocomplete="off" value="' . amountExchange_s($row['product_price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                   if($configurations['config_tax']!='0')
                                   {
                                     echo '<td class="text-center"  style="font-weight:bold;">'.$row['product_tax'].'</td>';
                                     echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">0</td>';
                                   }

                                    // <!-- erp2024 modified section 07-06-2024 -->
                                       echo '<td class="text-center"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '"></strong></td>';

                                        echo '<td class="text-right"><span class="currenty"></span>
                                            <strong><span class="ttlText" id="result-' . $i . '"></span></strong></td>
                                        </td>
                                        
                                        <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                        <input type="hidden" name="disca[]" id="disca-' . $i . '" value="0">

                                        <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['discount_type'].'">
                                        <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="0">
                                        <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"> 
                                        <input type="hidden" name="product_tax[]" id="vat-' . $i . '" readonly value="'.$row['product_tax'].'">

                                        <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" value="' . amountFormat_general($row['product_discount']) . '">
                                        
                                        <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off"  value="' . amountFormat_general($row['product_discount']) . '">

                                    </tr>';
                                    $i++; $j++;
                                } 
                                    
                                ?>

                                   
                                    <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
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
                                            <span id="grandamount">0.00</span>
                                        </td>
                                    </tr>
                                     
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="7" class="no-border"></td>
                                        <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Total Discount') ?>
                                                (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                        </td>
                                        <td align="right" colspan="2" class="no-border">
                                            <span id="discs"  class="lightMode discount_total" >0.00</span>
                                        </td>
                                    </tr>

                                    
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="7" class="no-border"></td>
                                        <td colspan="4" align="right"  class="no-border"><strong>Order Discount(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                        <td align="right" colspan="2"  class="no-border">
                                            <span id="order_discount_text" class="lightMode">0.00</span>
                                            <input type="hidden" id="order_discount" name="order_discount" value="0">
                                        </td>
                                    </tr>

                                    <tr class="sub_c d-none" style="display: table-row;">
                                        <td colspan="9" align="right" class="no-border"><input type="hidden" value="0"
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
                                            <strong><?php echo $this->lang->line('Total') 
                                           
                                            ?>
                                                (<span
                                                    class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                        </td>
                                        <?php
                                             $grandtotal = 0;
                                             $grandtotal = ($taxtotal+$productrate)-$discountrate;
                                        ?>
                                        <td align="right" colspan="2" class="no-border">
                                        <span id="grandtotaltext">0.00</span>
                                            <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="" readonly>

                                        </td>
                                    </tr>
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="4"  class="no-border">
                                            <?php echo "<i style='font-size:15px;' class='text-primary'>*Returned Items are subject to Inspection before Credit to the Customer</i>"; ?>
                                        </td>
                                        <td align="right" colspan="8"  class="no-border">
                                        <input type="submit" id="submit-delivery-return" class="btn btn-crud btn-primary btn-lg submitBtn" value="Create Delivery Return"/>
                                        <!-- <input type="submit" id="submit-data" class="btn btn-primary btn-lg submitBtn" value="Generate Delivery Return"/> -->
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
function damageqtycheck(numb){
    // alert(numb);
    if($("#damaged_qty-" + numb).val()>$("#amount-" + numb).val()){
        Swal.fire({
            icon: 'error',
            title: 'Invalid Input',
            text: 'Damaged Quantity is greater than Return Quantity',
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

$("#submit-delivery-return").on("click", function(e) {
    e.preventDefault();
    
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
            var deliverynoteid = parseInt($("#invocienoId").val());
            deliverynoteid  = deliverynoteid-1000;
            var salesorderid = $("#salesorder_id").val();
            var customerid = $("#customer_id").val();
            var formData = $("#data_form").serialize(); 
            formData += `&changedFields=${encodeURIComponent(JSON.stringify(changedFields))}`;
            $.ajax({
                type: 'POST',
                url: baseurl +'DeliveryNotes/delivery_return_action',
                data: formData,
                success: function(response) {
                    // deliveryReport();     
                    // Swal.fire({
                    //             icon: 'success',
                    //             title: 'Items Returned',
                    //             text: 'Items Returned successfully!',
                    //             confirmButtonText: 'OK'
                    //         }).then((result) => {
                    //             if (result.isConfirmed) {
                                    window.open(baseurl + 'Deliveryreturn/reprintnote?delivery=' + deliverynoteid + '&sales=' + salesorderid + '&cust=' + customerid, '_blank');
                                    window.location.href = baseurl + 'Deliveryreturn';
                            //     }
                            // });
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
<script>
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
    });
</script>