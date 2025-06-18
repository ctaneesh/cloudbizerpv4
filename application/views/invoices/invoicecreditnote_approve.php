
<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>">Invoices</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Invoice Credit Note')." #".$notemaster['creditnote_number']; ?></li>
                </ol>
            </nav>
            
            <div class="row">
                <div class="col-xl-4 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Invoice Credit Note')." #".$notemaster['creditnote_number'];?> </h4>
                    <!-- <h4 class="card-title"><?php echo $this->lang->line('Delivery Return')." #".$creditnotetid + 1; ?> </h4> -->
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
                                                <?php echo $this->lang->line('Credit Note Properties'); ?></h3>
                                        </div>

                                        <!-- erp2024 modified section 07-06-2024 -->
                                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Credit Note Number'); ?>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control"  placeholder="Delivery Return Number" name="invocieno" id="creditnotetid" value="<?php echo $notemaster['creditnote_number']; ?>"  readonly>
                                                <input type="hidden" class="form-control"  name="invocieid" id="invocieid" value="<?php echo $notemaster['invoiceid']; ?>"  readonly>
                                                <input type="hidden" class="form-control" name="store_id" id="store_id" value="<?php echo $notemaster['store_id']; ?>">
                                                <input type="hidden" class="form-control" name="creditnote_id" id="creditnote_id" value="<?php echo $notemaster['creditnote_id']; ?>">
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
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Invoice Due Date'); ?></label>

                                                <input type="text" class="form-control" name="invoiceduedate" id="invoiceduedate"
                                                    value="<?php echo date('d-m-Y', strtotime($notemaster['invoiceduedate'])); ?>" readonly>
                                                <input type="hidden" class="form-control" name="pterms" id="pterms"
                                                    value="<?php echo $notemaster['term']; ?>">
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
                                        <th width="20%" class="text-center1"><?php echo $this->lang->line('Item Name');?></th>
                                        <th width="10%" class="text-center1"><?php echo $this->lang->line('Item No');?></th>
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
                                    // if($row['qty'] == $row['product_qty']){
                                    //     $disableclass = "readonly";
                                    // }
                                    echo '<input type="hidden" class="form-control" name="product_name[]" value="' . $row['prdname'] . '">';
                                    echo '<input type="hidden" class="form-control" name="product_code[]" value="' . $row['prdcode'] . '">';
                                    // if($row['totalQty']<=$row['alert']){
                                    //     echo '<tr style="background:#ffb9c2;">';
                                    // }
                                    // else{
                                    //     echo '<tr >';
                                    // }
                                    $taxtotal = $taxtotal+$row['deliverytaxtotal'];
                                    $productrate = $productrate+$row['deliverysubtotal'];
                                    $discountrate = $discountrate+$row['totaldiscount'];
                                    echo '<td width="2%">'.$j.' <input type="hidden" class="form-control" name="product_id[]" value="'.$row['pid'].'" id="product_id-'.$i.'"></td>';

                                    echo '<td width="15%"><strong>'.$row['prdname'].'</strong> </td>';

                                    echo '<td><strong>'.$row['prdcode'].'</strong> </td>';

                                    echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';

                                    echo '<td class="text-center"><strong>'.intval($row['qty']).'</strong> </td>';
                                    
                                    echo '<td  class="text-center"><strong>'.intval($row['qty']).'</strong> <input type="hidden" class="form-control" name="delivered_qty[]" value="'.intval($row['qty']).'" id="delivered_qty-'.$i.'"></td>';

                                    echo '<td class="text-center"><strong>'.$row['approved_return_qty'].'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="'.intval($row['approved_return_qty']).'"></td>';
                                    // echo '<td class="text-center"><strong>'.$row['delivery_returned_qty'].'</strong> <input type="hidden"  id="delivery_returned_qty-' . $i . '" value="'.$row['delivery_returned_qty'].'"></td>';

                                    echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.' name="return_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)"  placeholder="'.$this->lang->line('Enter Qty').'" onkeyup="calculateDeliveryReturn(' . $i . ')" value="'.$row['return_qty'].'"></td>';
                                    
                                    echo '<td style="text-align:center;"><input type="number" class="form-control req prc" '.$disableclass.'  name="damaged_qty[]" id="damaged_qty-' . $i . '" value="'.$row['damaged_qty'].'" onkeypress="return isNumber(event)" onkeyup="damageqtycheck(' . $i . ')" placeholder="'.$this->lang->line('Enter Qty').'"></td>';

                                    echo '<td style="text-align:right;"><strong>'.$row['price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                   onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                   autocomplete="off" value="' . amountExchange_s($row['price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';
                                   if($configurations['config_tax']!='0')
                                   {
                                     echo '<td class="text-center"  style="font-weight:bold;">'.$row['totaltax'].'</td>';
                                     echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">0</td>';
                                   }

                                    // <!-- erp2024 modified section 07-06-2024 -->
                                       echo '<td class="text-center"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$row["totaldiscount"].'</strong></td>';

                                        echo '<td class="text-right"><span class="currenty"></span>
                                            <strong><span class="ttlText" id="result-' . $i . '">'.number_format($row["approved_return_amount"],2).'</span></strong></td>
                                        </td>
                                        
                                        <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                        <input type="hidden" name="disca[]" id="disca-' . $i . '" value="'.$row["totaldiscount"].'">

                                        <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['discount_type'].'">
                                        <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="'.$row["approved_return_amount"].'">
                                        <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"> 
                                        <input type="hidden" name="product_tax[]" id="vat-' . $i . '" readonly value="'.$row['tax'].'">

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
                                    <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="9" align="right"  class="no-border"><strong>Total Discount(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                    <td align="left" colspan="2"  class="no-border">
                                        <span id="discs"  class="lightMode discount_total" ></span>
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
                                        <span id="grandtotaltext">0.00</span>
                                            <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="" readonly>

                                        </td>
                                    </tr>
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="4"  class="no-border">
                                           
                                        </td>
                                        <td align="right" colspan="8"  class="no-border">
                                        <input type="submit" id="submit-invoice-return" class="btn btn-primary btn-lg submitBtn" value="Approve Credit Note"/>
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
            var formData = $("#data_form").serialize(); 
            $.ajax({
                type: 'POST',
                url: baseurl +'invoices/invoice_creditnote_return_action',
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
                                    // window.open(baseurl + 'Deliveryreturn/reprintnote?delivery=' + deliverynoteid + '&sales=' + salesorderid + '&cust=' + customerid, '_blank');
                                    window.location.href = baseurl + 'invoicecreditnotes';
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