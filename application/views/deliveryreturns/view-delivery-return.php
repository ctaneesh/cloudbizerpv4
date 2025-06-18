<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('deliveryreturn') ?>"><?php echo $this->lang->line('Delivery Returns'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Delivery Return'). "# ".$notemaster['delivery_return_number']; ?></li>
            </ol>
        </nav>
            <?php
                $deliveryBtn =""; 
                
                if(!empty($notemaster['invoice_number']) && ($notemaster['convert_to_credit_note_flag']!=1) && ($notemaster['approval_flg']==1)){
                    $deliveryBtn = '<a href="' . base_url("Deliveryreturn/deliveryreturn_to_creditnote?delivery=$id") . '"  class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> '.$this->lang->line('Convert to Credit Note').'</a>';
                }
                $salesorder_number = $notemaster['salesorder_number'];
                $customer_id = $notemaster['csd'];
                $printbtn =  '<a href="' . base_url("Deliveryreturn/reprintnote?delivery=$id&sales=$salesorder_number&cust=$customer_id") . '" target="_blank" class="btn btn-sm btn-secondary btn-crud"><i class="fa fa-print"></i> Print</a>';
            ?>
            
            
            <div class="row">
                <div class="col-xl-4 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                <h4 class="card-title"><?php echo $this->lang->line('Delivery Return'). "# ".$notemaster['delivery_return_number']." ".$invoiceBtn; ?> </h4>
                </div>
                <div class="col-xl-8 col-lg-9 col-md-8 col-sm-12 col-xs-12">
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
                            if(!empty($notemaster['deliverynote_id'])) { ?><li><a href="<?= base_url('DeliveryNotes/create?id=' . $notemaster['deliverynote_id']) ?>" target="_blank">DN #<?= $deliverynotedetails['delnote_number']; ?></a></li>
                            <?php } 
                            if (!empty($invoiceid) && $invoiceid> 0)  { ?><li><a href="<?= base_url('invoices/view?id=' . $invoiceid) ?>" target="_blank">IN #<?= $invoiceid+1000; ?></a></li>
                            <?php } ?>
                            <li class="active">DR #<?php echo $notemaster['delivery_return_number']; ?></li>
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
                    <div class="row">
                        <div class="col-3">
                            <?php echo $deliveryBtn; ?>
                        </div>
                        <div class="col-5">
                            <?php
                                if($notemaster['delivery_note_status']!='Invoiced' && $notemaster['approval_flg']=='1')
                                {
                                    $status = "Delivery Return Created.";
                                }
                                else if($notemaster['delivery_note_status']=='Invoiced' && $notemaster['approval_flg']=='1' && $notemaster['convert_to_credit_note_flag']=='0')
                                {
                                    $status = "Delivery Return Created and Credit Note not Created.";
                                }
                                else if($notemaster['delivery_note_status']=='Invoiced' && $notemaster['approval_flg']=='1' && $notemaster['convert_to_credit_note_flag']=='1')
                                {
                                    $status = "Delivery Return and Credit Note Created.";
                                }
                                else{}
                            ?>
                            <div class="alert alert-danger"> Current Status : <?=$status?></div>                                    
                        </div>
                        <div class="col-4 text-right">
                            <?php echo $deliveryBtn." ".$printbtn; ?>                           
                        </div>
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

                                            <div class="clientinfo">

                                                <div type="text" id="customer_phone">Phone: <strong>' . $notemaster['phone'] . '</strong><br>Email: <strong>' . $notemaster['email'] . '</strong></div>
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
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Delivery Return Number'); ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" name="delivery_return_number" id="delivery_return_number" value="<?php echo $notemaster['delivery_return_number']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="invocieno"
                                                class="col-form-label"><?php echo $this->lang->line('Delivery Note Number'); ?>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                        aria-hidden="true"></span></div>
                                                <input type="hidden" class="form-control"  placeholder="Delivery Note Number" name="invocieno" id="invocienoId" value="<?php echo $notemaster['deliverynote_id']+1000; ?>"  readonly>
                                                <input type="hidden" class="form-control" placeholder="Delivery Note Number" name="deliverynote_number" id="deliverynote_number" value="<?php echo $deliverynotedetails['delnote_number']; ?>"  readonly>
                                                <input type="text" class="form-control" placeholder="Delivery Note Number" name="delivery_note_number" id="delivery_note_number" value="<?php echo $deliverynotedetails['delivery_note_number']; ?>"  readonly>
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
                                                    id="invocieduedate" placeholder="" autocomplete="false"
                                                    value="<?php echo ($notemaster['invoicedate'])?date("d-m-Y", strtotime($notemaster['invoicedate'])):""; ?>"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Comments'); ?></label>
                                            <textarea class="form-control" name="notes" id="salenote" rows="2"
                                                readonly><?php echo $notemaster['notes'] ?></textarea>
                                        </div>

                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label
                                                for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Status'); ?></label>
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
                        <!-- ========================= tab starts ==================== -->
                        <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                                        aria-controls="tab1" href="#tab1" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Delivery Return Properties') ?></a>
                                </li>
                                
                                <!-- <li class="nav-item">
                                    <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab3"
                                        href="#tab3" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Payments Received') ?></a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link breaklink" id="base-tab4" data-toggle="tab" aria-controls="tab4"
                                        href="#tab4" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Journals') ?></a>
                                </li>
                                
                        </ul>

                        <div class="tab-content px-1 pt-1">
                            <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">   
                                <div id="saman-row">
                                    <table class="table table-striped table-bordered zero-configuration dataTable">
                                        <thead>

                                            <tr class="item_header bg-gradient-directional-blue white">
                                                <th width="4%" style="padding-left:10px;"><?php echo $this->lang->line('Sl.No'); ?></th>
                                                <!-- <th width="15%" style="padding-left:10px; text-align:left !important;"><?php //echo $this->lang->line('Item Code'); ?>
                                                </th> -->
                                                <th width="10%" class="text-center1"><?php echo $this->lang->line('Item No'); ?></th>
                                                <th width="22%" class="text-center1"><?php echo $this->lang->line('Item Name'); ?></th>
                                                <th width="6%" class="text-center"><?php echo $this->lang->line('Unit'); ?></th>
                                                <th width="8%" class="text-center"><?php echo $this->lang->line('Delivered Qty'); ?></th>
                                                <th width="8%" class="text-center"><?php echo $this->lang->line('Return Qty'); ?></th>
                                                <th width="8%" class="text-center"><?php echo $this->lang->line('Damaged Qty'); ?></th>
                                                <th width="10%" class="text-right"><?php echo $this->lang->line('Rate'); ?></th>
                                                <!-- <th width="7%" class="text-center"><?php echo $this->lang->line('Tax'); ?></th> -->
                                                <th width="7%" class="text-right">Discount</th>
                                                <th width="15%" class="text-right">
                                                <?php echo $this->lang->line('Amount'); ?>(<?php echo $this->config->item('currency'); ?>)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 0;$j = 1;
                                        $taxtotal = 0;
                                        $productrate = 0;
                                        $discountrate = 0;
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
                                            $productrate = $productrate+($row['return_qty']*$row['product_price']);
                                            $discountrate = $discountrate+$row['totaldiscount'];
                                            echo '<td width="2%">'.$j.' <input type="checkbox" class="checkedproducts d-none" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" checked></td>';
                                            echo '<td><strong>'.$row['product_code'].'</strong> </td>';
                                            echo '<td width="15%"><strong>'.$row['product_name'].'</strong> </td>';
                                            echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';
                                            echo '<td class="text-center"><strong>'.$row['delivered_qty'].'</strong> </td>';
                                            echo '<td class="text-center"><strong>'.$row['return_qty'].'</strong> </td>';
                                            echo '<td class="text-center"><strong>'.$row['damaged_qty'].'</strong> </td>';


                                            echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                        onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                        autocomplete="off" value="' . amountExchange_s($row['product_price'], $notemaster['multi'], $this->aauth->get_user()->loc) . '"></td>';

                                            // echo '<td class="text-center" id="texttaxa-' . $i . '" style="font-weight:bold;">'.$row['totaltax'].'</td>';

                                            // <!-- erp2024 modified section 07-06-2024 -->
                                            echo '<td class="text-right"><strong>'.$row['totaldiscount'].'</strong><input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['totaldiscount']) . '"></td>';

                                                echo '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">'.($row['deliverysubtotal']).'</span></strong></td>
                                                </td>
                                                <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['deliverytaxtotal'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                                <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['totaldiscount'], $notemaster['multi'], $this->aauth->get_user()->loc) . '">

                                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="0">
                                                <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                                <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                                            </tr>';
                                            $i++; $j++;
                                        } ?>


                                            <!-- <tr class="sub_c tr-border" style="display: table-row; ">
                                                <td colspan="8" align="right" class="no-border"><strong>Total Tax</strong></td>
                                                <td align="left" colspan="2" class="no-border"><span
                                                        class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                                    <span id="taxr"
                                                        class="lightMode"><?php echo $taxtotal; ?></span>
                                                </td>
                                            </tr> -->
                                            <!-- erp2024 removed section 07-06-2024 -->
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border"><strong>Subtotal (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs" class="lightMode"><?php echo number_format($productrate,2) ?></span>
                                                </td>
                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border"><strong>Total Discount (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs" class="lightMode"><?php echo number_format($discountrate,2) ?></span>
                                                </td>
                                            </tr>
                                            <tr class="sub_c" style="display: table-row;">
                                                <td colspan="9" align="right" class="no-border"><strong>Order Discount (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="discs" class="lightMode"><?php echo number_format($notemaster['return_order_discount'],2) ?></span>
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
                                                <td colspan="2" class="no-border">
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
                                                    $grandtotal = ($productrate)-$discountrate;
                                                ?>
                                                <td align="right" colspan="2" class="no-border">
                                                    <span id="grandtotaltext"><?php echo number_format($notemaster['total_amount'],2);?></span>
                                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?php echo $notemaster['total_amount']; ?>"  readonly="">

                                                </td>
                                            </tr>
                                            <?php
                                                if(!empty($notemaster['invoice_number']) && ($notemaster['convert_to_credit_note_flag']!=1) && ($notemaster['approval_flg']==1)){
                                                echo '
                                                    <tr class="sub_c" style="display: table-row;">                                        
                                                        <td align="right" colspan="10" class="no-border d-none">
                                                            <a href="' . base_url("Deliveryreturn/deliveryreturn_to_creditnote?delivery=$id") . '"  class="btn btn-lg btn-primary"><i class="fa fa-undo"></i> '.$this->lang->line('Convert to Credit Note').'</a>
                                                        </td>
                                                    </tr>';
                                                }
                                                
                                            ?>
                                            
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
                                            <div class="table-container">
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
                            <!-- =================================================================== -->
                        </div>

                </form>
            </div>

        </div>
    </div>
            <!--     erp2025 add 06-01-2025   Detailed hisory starts-->
            <button class="history-expand-button">
                    <span>History</span>
                </button>

                <div class="history-container">
                <button class="history-close-button">
                <span>Close</span>
                    </button>
                    <h2>History</h2>
                    <button class="logclose-btn">
                    <span>X</span>
                    </button>
                    <form>
                        <table id="log" class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('Action Performed') ?></th>
                                    <!-- <th><?php// echo $this->lang->line('Action Performed') ?></th> -->
                                    <th><?php echo $this->lang->line('Performed At')?></th>
                                    <!-- <th><?php //echo $this->lang->line('Performed By') ?></th> -->
                                    <th><?php echo $this->lang->line('IP Address')?></th>
                                            
                                </tr>
                            </thead>
                            <tbody>
                        <?php $i = 1;
                            foreach ($groupedDelreturns as $seqence_number => $Returns){
                            $flag=0;
                            ?>              
                                <tr>
                                <td>        
                                    <?php    foreach ($Returns as $Return) {
                                    if($flag==0)
                                    {?>
                                    <div class="userdata">
                                    <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$Return['picture'])?>' style="width:50px; height:50px;" ?>
                                    <?php  echo $Return['name'];
                                            $flag=1;
                                    } ?>
                                    </div>           
                                        <ul><li>  <?php echo $Return['old_value'];?> > <b><span class="newdata"><?php echo $Return['new_value']?></span></b> (<?php if($Return['field_label']==""){echo $Return['field_name'];}else{echo $Return['field_label'];}?>)
                                        </li></ul>
                                        <?php } ?>
                                    </td>               
                                    <td><?php echo date('d-m-Y H:i:s', strtotime($Return['changed_date'])); ?></td>
                                    <td><?php echo $Return['ip_address']?></td> 
                                    
                                </tr>  
                                <?php 
                                $i++; 
                            
                            }?>
                            </tbody>
                        </table>

                    </form>
                </div>   
         <!--     erp2025 add 06-01-2025   Detailed hisory ends-->
</div>

<script type="text/javascript">
$(document).ready(function() {
      $(".history-expand-button").on("click", function () {
            $(".history-container").toggleClass("active");
        });
        $(".history-close-button").on("click", function () {
            $(".history-container").removeClass("active");
        });
        $(".logclose-btn").on("click", function () {
            $(".history-container").removeClass("active");
        });
        var columnlist = [
            { 'width': '4%' }, 
            { 'width': '5%' },
            { 'width': '25%' }, 
            { 'width': '10%' },
            { 'width': '10%' },
            { 'width': '9%' },
            { 'width': '' }
        ]; 
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
</script>