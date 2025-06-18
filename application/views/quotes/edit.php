<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('quote') ?>"><?php echo $this->lang->line('Quotes') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Quote') . " #".$invoice['tid'];?></li>
                </ol>
            </nav>
       
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Quote') . " #".$invoice['tid'];?>
                    <button type="button" id="purchaseOrderBtn" class="btn btn-primary btn-sm "><?php echo $this->lang->line('Add to purchase Order'); ?></button></h4>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">  
                        <ul id="trackingbar">
                        <?php if(!empty($trackingdata))
                            {
                                if(!empty($trackingdata['lead_id']))
                                { ?>
                                    <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li>
                                    <li class="active"><a href="<?= base_url('quote/view?id=' . $invoice['iid']) ?>" target="_blank">   QT #<?php echo $invoice['tid'];?></a></li> 
                                <?php }
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
                <input type="hidden" name="config_tax" id="config_tax" value="<?=$configurations['config_tax']?>">
                <input type="hidden" name="approvalflg" id="approvalflg" value="<?=$invoice['approvalflg']?>">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Client Details'); ?>
                                        </h3>
                                    </div>
                                    <div class="frmSearch col-sm-12"><label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Client'); ?></label>
                                        <input type="text" class="form-control" name="cst" id="customer-box"
                                               placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>"
                                               autocomplete="off"/>
                                        <div id="customer-box-result"></div>
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
                                        <hr>
                                        <div id="customer_pass"></div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12  cmp-pnl">
                                <div class="inner-cmp-pnl">


                                    <div class="form-group row">

                                        <div class="col-sm-12"><h3 class="title-sub"><?php echo $this->lang->line('Quote Properties'); ?></h3></div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Quote Number'); ?></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Quote #"
                                                       name="invocieno"
                                                       value="<?php echo $invoice['tid']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference'); ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Reference #"
                                                       name="refer"
                                                       value="<?php echo $invoice['refer'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Quote Date'); ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar4"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="date" class="form-control required"
                                                       placeholder="Billing Date" name="invoicedate" value="<?php echo $invoice['invoicedate'] ?>" readonly> 
                                                       <input type="hidden" name="iid" value="<?php echo $invoice['iid']; ?>" >
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Quote Validity'); ?><span class="compulsoryfld">*</span></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="date" class="form-control required editdate" name="invocieduedate" placeholder="Validity Date" autocomplete="false"  value="<?php echo $invoice['invoiceduedate'] ?>">
                                            </div>
                                        </div>
                                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="taxformat" class="col-form-label"><?php echo $this->lang->line('Tax'); ?></label>
                                            <select class="form-control" onchange="changeTaxFormat(this.value)"
                                                    id="taxformat">

                                                <?php //echo $taxlist; ?>
                                            </select>
                                        </div> -->
                                        <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Discount'); ?></label>
                                                <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                        id="discountFormat">
                                                    <?php //echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                    <?php //echo $this->common->disclist() ?>
                                                </select>
                                        </div> -->
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label class="col-form-label" for="name"><?php echo $this->lang->line('Warehouse'); ?></label>
                                                <select id="s_warehouses"
                                                        class="selectpicker form-control">
                                                    <?php echo $this->common->default_warehouse();
                                                    echo '<option value="">' . $this->lang->line('All') ?></option><?php foreach ($warehouse as $row) {
                                                        $sel ="";
                                                        if($invoice['invoiceduedate'] == $row['id'])
                                                        {
                                                            $sel = "selected";
                                                        }
                                                        echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] . '</option>';
                                                    } ?>

                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="employee" class="col-form-label"><?php echo $this->lang->line('Assign to') ?> <span class="compulsoryfld">*</span></label>
                                                <input type="hidden" name="oldemployee" value="<?=$invoice['eid']?>">
                                                <select name="employee" class=" col form-control required" required>
                                                   <?php  echo '<option value="">Select an Employee</option>';?>
                                                    <?php foreach ($employee as $row) {
                                                        $sel = "";
                                                        if($invoice['eid']==$row['id']){
                                                            $sel="Selected";
                                                        }
                                                        echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['name'].'</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label" for="name"><?php echo $this->lang->line('Payment Terms'); ?></label>
                                                <select name="pterms"  class="selectpicker form-control">
                                                    <?php echo '<option value="' . $invoice['termid'] . '">*' . $invoice['termtit'] . '</option>';
                                                    foreach ($terms as $row) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Quote Note'); ?></label>
                                                <textarea class="form-textarea" name="notes"
                                                      rows="2"><?php echo $invoice['notes'] ?></textarea>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Proposal Message'); ?></label>
                                                <textarea class="summernote1 form-textarea" name="propos" id="contents" rows="2"><?php echo $invoice['proposal'] ?></textarea>
                                            </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div id="compare_result"></div>                           
                        <div id="draftsection"></div>                           
                        <div id="saman-row">
                            <table class="table table-striped table-bordered zero-configuration dataTable" >
                                <thead>

                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th  style="padding-left:10px; width:10px !important;"><input type="checkbox" id="prdcheckbox" name="prdcheckbox"></th>
                                    <th width="22%" class="text-center1 pl-1">Item Description & No</th>
                                    <th width="8%" class="text-center">Quantity</th>
                                    <th width="6%" class="text-center">On Hand</th> 
                                    <th width="7%" class="text-right"><?php echo $this->lang->line('Selling Price') ?></th>
                                    <th width="7%" class="text-right"><?php echo $this->lang->line('Lowest Price') ?></th>
                                    <?php 
                                    if($configurations['config_tax']!='0'){  ?>
                                       <th width="10%" class="text-right"><?php echo $this->lang->line('Tax'); ?>(%) / <?php echo $this->lang->line('Amount'); ?></th>                              
                                    <?php } ?>
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Max discount %')?></th>
                                    <th width="12%" class="text-center"><?php echo $this->lang->line('Discount')?>/ <?php echo $this->lang->line('Amount'); ?></th>
                                    <th width="10%" class="text-center">
                                        Amount(<?php echo $this->config->item('currency'); ?>)
                                    </th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;
                                foreach ($products as $row) {
                                    if($row['totalQty']<=$row['alert']){
                                        echo '<tr style="background:#ffb9c2;">';
                                    }
                                    else{
                                        echo '<tr >';
                                    }
                                    echo '<td width="1%"><input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'" onclick="selectPrdts(\''.$row['pid'].'\')"> </td>';
                                    
                                    echo '<td><input type="text" class="form-control" name="product_name[]" placeholder="Enter Product name or Code"  value="' . $row['product'] . '"> </td>
                                    <td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog(), compare_with_old_new_grand_totals()"
                                   autocomplete="off" value="' . intval($row['qty']) . '" ><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['qty']) . '" ></td>'; 
                                   echo '<td class="text-center"><strong id="onhandQty-'.$i.'">'.$row['totalQty'].'</strong></td>';

                                   echo '<td class="text-right"><strong id="pricelabel-' . $i . '">' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off" value="' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '"></td>'; 

                                   echo '<td class="text-right">
                                        <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' .$row['lowest_price']. '">
                                        <strong id="lowestpricelabel-' . $i . '">' .$row['lowest_price']. '</strong>
                                        </td>';

                                    $taxtd ="";
                                    $totaltax = $totaltax+amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc);
                                    $totaldiscount = $totaldiscount+amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc);
                                    if($configurations["config_tax"]!="0"){        
                                        echo '<td class="text-center">
                                            <div class="text-center">                                                
                                                <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off"  value="' . amountFormat_general($row['tax']) . '">
                                                <strong id="taxlabel-' . $i . '"></strong>&nbsp;<strong  id="texttaxa-' . $i . '">' .$row['tax']. '/'. amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                            </div>
                                        </td>';
                                    } 
                                //    echo '<td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"  autocomplete="off"  value="' . amountFormat_general($row['tax']) . '">/</td>';
                                echo '<input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-'.$i.'" value="' . $row['max_disrate'] . '"><td class="text-center"><strong id="maxdiscountratelabel-' . $i . '">' .$row['max_disrate']. '</strong></td>';                              
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
                                echo '<td class="text-center" >
                                        <div class="input-group text-center">
                                            <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control" onchange="discounttypeChange(' . $i . ')">
                                                <option value="Perctype" '.$percsel.'>%</option>
                                                <option value="Amttype" '.$amtsel.'>Amt</option>
                                            </select>&nbsp;
                                            <input type="number" min="0" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '">
                                            <input type="number" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">
                                        </div>                                    
                                        <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel">Amount : ' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                        <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                    </td>';
                                    
                                    // echo '<td><input type="text" class="form-control discount" name="product_discount[]"
                                    //         onkeypress="return isNumber(event)" id="discount-' . $i . '"
                                    //         onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '"></td>
                                    echo '<td class="text-right">
                                        <strong><span class="ttlText" id="result-' . $i . '">' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span></strong></td>
                                    <td class="text-center"><button onclick="producthistory('.$i.')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button>&nbsp;<button type="button" data-rowid="' . $i . '" class="btn btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button> 
                                    </td>
                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                                </tr>';
                                    $i++;
                                } ?>
                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-secondary" id="quote_edit_btn">
                                            <i class="fa fa-plus-square"></i> Add Row
                                        </button>
                                    </td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>
                                <?php 
                                    if($configurations['config_tax']!='0'){ ?>
                                    <tr class="sub_c" style="display: table-row;">
                                        <td colspan="8" align="right" class="no-border"><strong>Total Tax (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                        <td align="left" colspan="2" class="no-border">
                                            <span id="taxr"
                                                class="lightMode"><?php echo amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="9" align="right" class="no-border"><strong>Total Discount(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                    <td align="left" colspan="2" class="no-border">
                                        <span id="discs"
                                              class="lightMode"><?php echo amountExchange_s($invoice['discount'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                    </td>
                                </tr>

                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="8" align="right" class="no-border"><input type="hidden"
                                                                         value="<?php echo amountExchange_s($invoice['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) ?>"
                                                                         id="subttlform"
                                                                         name="subtotal"><strong>Shipping</strong></td>
                                    <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                        onkeypress="return isNumber(event)"
                                                                        placeholder="Value"
                                                                        name="shipping" autocomplete="off"
                                                                        onkeyup="billUpyog()"
                                                                        value="<?php if ($invoice['ship_tax_type'] == 'excl') {
                                                                            $invoice['shipping'] = $invoice['shipping'] - $invoice['ship_tax'];
                                                                        }
                                                                        echo amountExchange_s($invoice['shipping'], $invoice['multi'], $this->aauth->get_user()->loc); ?>">( <?= $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                        <span id="ship_final"><?= amountExchange_s($invoice['ship_tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?> </span>
                                        )
                                    </td>
                                </tr>

                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="3" class="no-border"><?php if ($exchange['active'] == 1){
                                        echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                        <select name="mcurrency"
                                                class="selectpicker form-control">

                                            <?php
                                            echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                                            foreach ($currency as $row) {

                                                echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                            } ?>

                                        </select><?php } ?></td>
                                    <td colspan="6" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                            (<span
                                                    class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border">
                                        <span id="grandtotaltext"><?= amountExchange_s($invoice['total'], $invoice['multi'], $this->aauth->get_user()->loc); ?></span>
                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?= amountExchange_s($invoice['total'], $invoice['multi'], $this->aauth->get_user()->loc); ?>" readonly="">

                                    <input type="hidden" name="oldtotal" id="oldtotal" class="form-control" value="<?= amountExchange_s($invoice['total'], $invoice['multi'], $this->aauth->get_user()->loc); ?>" readonly>

                                    </td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2" class="no-border">
                                        <button type="button" class="btn btn-lg btn-secondary revert-btncolor" id="revert-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                                    </td>
                                    <td align="right" colspan="8" class="no-border">
                                        <input type="submit" class="btn btn-lg btn-secondary" value="Save As Draft" id="quote_draf_btn" >
                                        <input type="submit" class="btn btn-lg btn-primary sub-btn" value="Accept" id="quote_create_btn">
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <!-- <input type="hidden" value="quote/editaction" id="action-url"> -->
                        <input type="hidden" value="search" id="billtype">
                        <input type="hidden" name="convertflg" value="<?=$invoice['convertflg']?>">
                        <input type="hidden" value="<?php echo $i; ?>" name="counter" id="ganak">
                        <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
                        <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
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
                        echo amountFormat_general(@number_format((($invoice['ship_tax'] / $invoice['shipping']) * 100) + $tt, 3, '.', '')); ?>"
                               name="shipRate" id="ship_rate">
                        <input type="hidden" value="<?= $invoice['ship_tax_type']; ?>" name="ship_taxtype"
                               id="ship_taxtype">
                        <input type="hidden" value="<?= amountFormat_general($invoice['ship_tax']); ?>" name="ship_tax"
                               id="ship_tax">


                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addCustomer" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="product_action" class="form-horizontal">
                <!-- Modal Header -->
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Add Customer</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <p id="statusMsg"></p><input type="hidden" name="mcustomer_id" id="mcustomer_id" value="0">


                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label" for="name">Name</label>

                        <div class="col-sm-10">
                            <input type="text" placeholder="Name"
                                   class="form-control margin-bottom" id="mcustomer_name" name="name" required>
                        </div>
                    </div>

                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label" for="phone">Phone</label>

                        <div class="col-sm-10">
                            <input type="text" placeholder="Phone"
                                   class="form-control margin-bottom" name="phone" id="mcustomer_phone">
                        </div>
                    </div>
                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label" for="email">Email</label>

                        <div class="col-sm-10">
                            <input type="email" placeholder="Email"
                                   class="form-control margin-bottom crequired" name="email" id="mcustomer_email">
                        </div>
                    </div>
                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label" for="address">Address</label>

                        <div class="col-sm-10">
                            <input type="text" placeholder="Address"
                                   class="form-control margin-bottom " name="address" id="mcustomer_address1">
                        </div>
                    </div>
                    <div class="form-group row">


                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <input type="text" placeholder="City"
                                   class="form-control margin-bottom" name="city" id="mcustomer_city">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <input type="text" placeholder="Region"
                                   class="form-control margin-bottom" name="region">
                        </div>

                    </div>

                    <div class="form-group row">


                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <input type="text" placeholder="Country"
                                   class="form-control margin-bottom" name="country" id="mcustomer_country">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <input type="text" placeholder="PostBox"
                                   class="form-control margin-bottom" name="postbox">
                        </div>
                    </div>

                    <div class="form-group row">

                        <label class="col-sm-2 col-form-label" for="customergroup">Group</label>

                        <div class="col-sm-10">
                            <select name="customergroup" class="form-control">
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

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="mclient_add" class="btn btn-primary submitBtn" value="ADD"/>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
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

</script>
<script type="text/javascript"> 
$('.editdate').datepicker({
    autoHide: true,
    format: '<?php echo $this->config->item('dformat2'); ?>'
});
    
$( document ).ready(function() {
        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {
                invocieduedate: { required: true },
                employee: { required: true }
            },
            messages: {
                invocieduedate: "Enter Quote Deadline",
                employee: "Select an Employee"
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

        $('#quote_create_btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#quote_create_btn').prop('disabled', true); // Disable button to prevent multiple submissions
            if (parseInt($("#customer_id").val()) == 0) {
                Swal.fire({
                text: "Please Select Customer",
                icon: "info"
              });
              $('#quote_create_btn').prop('disabled', false);
                return;
            }
            var selectedProducts1 = [];
            $('.amnt').each(function() {
                if($(this).val()>0)
                {
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
                var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
                var oldtotalamount = parseFloat($("#oldtotal").val().replace(/,/g, '').trim());
                if(total > oldtotalamount)
                {
                    txtmsg = "The new Grand Total amount exceeds the old Grand Total amount, so you need authorization approval";
                }
                else{
                    txtmsg = "Do you want to accept this quote now?";
                }
                Swal.fire({
                    title: "Are you sure?",
                    text: txtmsg,
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
                            url: baseurl + 'quote/editaction', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                window.location.href = baseurl + 'quote'; 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#quote_create_btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#quote_create_btn').prop('disabled', false);
            }
        });
        $('#quote_draf_btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#quote_draf_btn').prop('disabled', true); // Disable button to prevent multiple submissions
          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
                var oldtotalamount = parseFloat($("#oldtotal").val().replace(/,/g, '').trim());
                if(total > oldtotalamount)
                {
                    txtmsg = "The new Grand Total amount exceeds the old Grand Total amount, so you need authorization approval";
                }
                else{
                    txtmsg = "Do you want to accept this quote now?";
                }
                
                $.ajax({
                    url: baseurl + 'quote/editdraftaction', // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        localStorage.setItem('draftSaved', 'true');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        
                        console.log(error); // Log any errors
                    }
                });
        });

      

// On page load, check if the draft was saved
$(document).ready(function() {
    if (localStorage.getItem('draftSaved')) {
        $("#draftsection").html('<div class="alert alert-warning alert-success fade show" role="alert"><strong>Draft</strong> Saved Successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        // Clear the storage flag
        localStorage.removeItem('draftSaved');
    }
});

    });

    function compare_with_old_new_grand_totals() {
        var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
        var oldtotalamount = parseFloat($("#oldtotal").val().replace(/,/g, '').trim());

        var textdata="";
        if (isNaN(total) || isNaN(oldtotalamount)) {
            textdata = '<div class="alert alert-warning">Invalid numbers. Please check the values again.</div>';
        } else if (total > oldtotalamount) {
            textdata = '<div class="alert alert-danger">The new Grand Total amount exceeds the old Grand Total amount, so you need authorization approval</div>';
        } else {
            // textdata = '<div class="alert alert-success">The Available Credit Limit is below the order total amount. Please procced.</div>';
        }
        $("#compare_result").html(textdata);
    }
</script>
