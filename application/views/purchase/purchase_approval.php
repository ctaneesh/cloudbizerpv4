<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('authorization_approval') ?>">Authorization Requests</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo "Approval Request #".$invoice['tid']; ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo "Approval Request #".$invoice['tid']; ?> </h4>
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

                        <!-- ==================================================================== -->
                        <div class="col-12 border-bottom">
                            <div class="form-group row">
                                <div class="col-sm-12"><h3 class="title-sub"><?php echo $this->lang->line('Approval Details'); ?></h3></div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="Requested By" class="col-form-label"><?php echo $this->lang->line('Requested By'); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-file-text-o"  aria-hidden="true"></span></div>
                                        <input type="text" class="form-control"  name="requested_by" value="<?php echo $authrizationdata['name']; ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="Requested By" class="col-form-label"><?php echo $this->lang->line('Requested Amount'); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-file-text-o"  aria-hidden="true"></span></div>
                                        <input type="text" class="form-control"  name="requested_amount"  value="<?php echo $authrizationdata['requested_amount']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="Requested By" class="col-form-label"><?php echo $this->lang->line('Requested Date'); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-file-text-o"  aria-hidden="true"></span></div>
                                        <input type="date" class="form-control"  name="requested_by" value="<?php echo $authrizationdata['requested_date']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="authorized_amount" class="col-form-label"><?php echo $this->lang->line('Approved Amount'); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-file-text-o"  aria-hidden="true"></span></div>
                                        <input type="text" class="form-control"  name="authorized_amount" id="authorizedamounthtml" value="<?php echo $authrizationdata['requested_amount']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <?php $statusval = $authrizationdata['status'];  ?>
                                    <?php 
                                        
                                        if($authrizationdata['status']=='Hold'){ $hold = "checked";   $approved = ""; $rejected="";}
                                        else if($authrizationdata['status']=='Approve'){
                                            $hold = "";   $approved = "checked"; $rejected="";
                                        }else if($authrizationdata['status']=='Reject'){
                                            $hold = "";   $approved = ""; $rejected="checked";
                                        }
                                        else{
                                            $hold = "";   $approved = "checked"; $rejected="";
                                        } 
                                        
                                        ?>
                                    <label for="authorized_amount" class="col-form-label"><?php echo $this->lang->line('Approved Status'); ?></label>
                                    <div class="col-12 row">
                                        <div class="form-check col-3">
                                            <input class="form-check-input" type="radio" name="statusType" id="statusType3" value="Hold" <?php echo $hold;  ?>>
                                            <label class="form-check-label" for="statusType3">
                                                Hold
                                            </label>
                                        </div>
                                        <div class="form-check col-3">
                                            <input class="form-check-input" type="radio" name="statusType" id="statusType1" value="Approve" <?php echo $approved; ?>>
                                            <label class="form-check-label" for="statusType1">
                                                Approve
                                            </label>
                                        </div>
                                        <div class="form-check col-4">
                                            <input class="form-check-input" type="radio" name="statusType" id="statusType2" value="Reject" <?php echo $rejected; ?>>
                                            <label class="form-check-label" for="statusType2">
                                                Reject
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                    <label for="authorized_amount" class="col-form-label"><?php echo $this->lang->line('Authorized person comment'); ?><span class="compulsoryfld">*</span></label>
                                    <textarea class="form-textarea" name="comments" rows="2" required><?php echo $authrizationdata['comments'] ?></textarea>
                                </div>

                            </div>                                

                        </div>
                        <!-- ==================================================================== -->

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                
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

                                        <div class="col-sm-12"><h3
                                                    class="title-sub"> <?php echo $this->lang->line('Purchase Order Properties') ?></h3>
                                        </div>
                                        
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieno"  class="col-form-label"> <?php echo $this->lang->line('Purchase Order') ?>#</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Purchase Order #"
                                                       name="invocieno"
                                                       value="<?php echo $invoice['tid']; ?>" readonly><input
                                                        type="hidden"
                                                        name="iid"
                                                        value="<?php echo $invoice['iid']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno"
                                                                     class="col-form-label"> <?php echo $this->lang->line('Reference') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Reference #"
                                                       name="refer"
                                                       value="<?php echo $invoice['refer'] ?>" readonly>
                                            </div>
                                        </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="Doc Type"   class="col-form-label"><?php echo $this->lang->line('Doc Type') ?><span class="compulsoryfld">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o"  aria-hidden="true"></span></div>
                                            <select name="doc_type" id="doc_type" class="form-control" required readonly>
                                                <option value="">Select Document Type</option>
                                                <option value="Local Cash Purchase" <?php if($invoice['doc_type']=="Local Cash Purchase"){ echo "selected"; } ?>>Local Cash Purchase</option>
                                                <option value="Local Credit Purchase" <?php if($invoice['doc_type']=="Local Credit Purchase"){ echo "selected"; } ?>>Local Credit Purchase</option>
                                                <option value="International Purchase" <?php if($invoice['doc_type']=="International Purchase"){ echo "selected"; } ?>>International Purchase</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="currency" class="col-form-label"><?php echo $this->lang->line('Currency') ?><span class="compulsoryfld">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o"  aria-hidden="true"></span></div>
                                            <select name="currency_id" id="currency_id" class="form-control" required readonly>
                                                <option value="">Select Currency</option>
                                                <?php
                                                    foreach($currencies as $currency){
                                                        $sel="";
                                                        if($invoice['currency_id']==$currency['id']){
                                                            $sel="selected";
                                                        }
                                                        echo "<option value='".$currency['id']."' $sel>".$currency['code']."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invociedate" class="col-form-label"> <?php echo $this->lang->line('Order Date') ?><span class="compulsoryfld">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar4"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control required editdate"
                                                       placeholder="Billing Date" name="invoicedate"
                                                       autocomplete="false" readonly 
                                                       value="<?php echo dateformat($invoice['invoicedate']) ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Order Due Date') ?><span class="compulsoryfld">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control required editdate" readonly 
                                                       name="invocieduedate"  placeholder="Due Date" autocomplete="false"
                                                       value="<?php echo dateformat($invoice['invoiceduedate']) ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="taxformat" class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                                            <select class="form-control" onchange="changeTaxFormat(this.value)"
                                                    id="taxformat" readonly>
                                                <?php echo $taxlist; ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                                <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                        id="discountFormat" readonly>
                                                    <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                    <?php echo $this->common->disclist() ?>
                                                </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                            <select id="s_warehouses" class="selectpicker form-control" readonly>
                                                <?php echo $this->common->default_warehouse();
                                                echo '<option value="0">' . $this->lang->line('All') ?></option>
                                                <?php foreach ($warehouse as $row) {
                                                    $sel="";
                                                    if($row['id'] == $invoice['store_id']){
                                                        $sel = "selected";
                                                    }
                                                    echo '<option value="'.$row['id'].'" '.$sel.'>' . $row['title'].'</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?></label>
                                            <select name="pterms" class="selectpicker form-control" readonly><?php echo '<option value="' . $invoice['termid'] . '">*' . $invoice['termtit'] . '</option>';
                                                foreach ($terms as $row) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="Update Stock" class="col-form-label"><?php echo $this->lang->line('Update Stock') ?> </label>
                                            <div class="">
                                                <div class="form-check form-check-inline" readonly>
                                                    <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight1" value="yes" disabled>
                                                    <label class="form-check-label" for="customRadioRight1"><?php echo $this->lang->line('Yes') ?></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight2" value="no" checked disabled>
                                                    <label class="form-check-label" for="customRadioRight2"><?php echo $this->lang->line('No') ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                            <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Order Note') ?></label>
                                            <textarea class="form-control" name="notes" rows="2" readonly ><?php echo $invoice['notes'] ?></textarea>
                                        </div>
                                    </div>                                  

                                </div>
                            </div>

                        </div>


                        <div id="saman-row">
                            <table class="table table-striped table-bordered zero-configuration dataTable">

                                <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="25%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                    <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Rate') ?></th>
                                    <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th> -->
                                    <!-- <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th> -->
                                    <th width="10%" class="text-right"><?php echo $this->lang->line('Amount') ?>
                                        (<?php echo $this->config->item('currency'); ?>)
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;
                                foreach ($products as $row) {
                                    echo '<tr >
                                    <td><input type="text" class="form-control" name="product_name[]" placeholder="Enter Product name or Code"  value="' . $row['product_name'] . '" readonly>
                                    </td>
                                    <td><input type="text" class="form-control" name="product_code[]"  value="' . $row['product_code'] . '" readonly>
                                    </td>
                                    <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . intval($row['qty']) . '" ><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['qty']) . '" ></td>
                                    <td><input type="text" class="form-control req prc" name="product_price[]" id="price-' . $i . '"
                                            onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                            autocomplete="off" value="' . $row['price'] . '"></td>';
                                    // echo '<td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                    //             autocomplete="off"  value="' . amountFormat_general($row['tax']) . '"></td>
                                    // <td class="text-center" id="texttaxa-' . $i . '">' . edit_amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</td>';

                                    echo '<td class="d-none"><input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                                autocomplete="off"  value="' . amountFormat_general($row['tax']) . '"><input type="hidden" class="form-control discount" name="product_discount[]"
                                            onkeypress="return isNumber(event)" id="discount-' . $i . '"
                                            onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' .$row['discount'] . '"></td>
                                    <td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">' . number_format($row['subtotal'], 2) . '</span></strong></td>
                                    
                                    <input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . edit_amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . edit_amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . edit_amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                                    <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">   <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                                    </tr>';
                                    $i++;
                                } ?>
                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border"></td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>

                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="6" align="right" class="no-border">
                                        <strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border"><span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                        <span id="taxr"
                                              class="lightMode"><?= edit_amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                    </td>
                                </tr>
                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="4" align="right" class="no-border">
                                        <strong><?php echo $this->lang->line('Total Discount') ?> <span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span></strong></td>
                                    <td align="left" colspan="2" class="no-border">
                                        <span id="discs"
                                              class="lightMode"><?php echo edit_amountExchange_s($invoice['discount'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                                    </td>
                                </tr>

                                <tr class="sub_c d-none" style="display: table-row;">
                                    <td colspan="4" align="right" class="no-border">
                                        <input type="hidden" value="<?php echo edit_amountExchange_s($invoice['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) ?>" id="subttlform" name="subtotal"><strong><?php echo $this->lang->line('Shipping') ?></strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border">
                                        <input type="text" class="form-control shipVal" onkeypress="return isNumber(event)" placeholder="Value" name="shipping" autocomplete="off" onkeyup="billUpyog()" value="<?php if ($invoice['ship_tax_type'] == 'excl') 
                                        {
                                            $invoice['shipping'] = $invoice['shipping'] - $invoice['ship_tax'];
                                        }
                                        echo amountExchange_s($invoice['shipping'], $invoice['multi'], $this->aauth->get_user()->loc); ?>">( <?= $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                        <span id="ship_final"><?= edit_amountExchange_s($invoice['ship_tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?> </span>
                                        )
                                    </td>
                                </tr>

                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2" class="no-border"><?php if ($exchange['active'] == 1){
                                        echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                        <select name="mcurrency"
                                                class="selectpicker form-control">

                                            <?php
                                            echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                                            foreach ($currency as $row) {

                                                echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                            } ?>

                                        </select><?php } ?></td>
                                    <td colspan="2" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                            (<span
                                                    class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                        <span id="grandtotaltext"><?= $invoice['total']; ?></span>
                                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="<?= $invoice['total']; ?>" readonly="">
                                        <input type="hidden" name="oldtotal" class="form-control"  value="<?= $invoice['total']; ?>">

                                    </td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    
                                    <td align="right" colspan="8" class="no-border">
                                        <input type="submit" class="btn btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Update Order') ?>" id="purchase-request-approval-btn" data-loading-text="Updating...">
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>

                        <input type="hidden" value="purchase/approvalaction" id="action-url">
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
                        $tt = 0;

                        if($invoice['shipping']==0)  $invoice['shipping']=1;
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
    </div>
</div>

<div class="modal fade" id="addCustomer" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="product_action" class="form-horizontal">
                <!-- Modal Header -->
                <div class="modal-header">

                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo $this->lang->line('Add Supplier') ?></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php echo $this->lang->line('Close') ?></span>
                    </button>
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


                        <div class="col-sm-6">
                            <input type="text" placeholder="City"
                                   class="form-control margin-bottom" name="city" id="mcustomer_city">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" placeholder="Region"
                                   class="form-control margin-bottom" name="region">
                        </div>

                    </div>

                    <div class="form-group row">


                        <div class="col-sm-6">
                            <input type="text" placeholder="Country"
                                   class="form-control margin-bottom" name="country" id="mcustomer_country">
                        </div>
                        <div class="col-sm-6">
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
<script type="text/javascript"> $('.editdate').datepicker({
        autoHide: true,
        format: '<?php echo $this->config->item('dformat2'); ?>'
    });

$("#purchase-request-approval-btn").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    if ($("#data_form").valid()) {
      Swal.fire({
            title: "Are you sure?",
            "text":"Do you want to proceed with this request?",
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
                  url: baseurl +'purchase/approvalaction',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'authorization_approval'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
      });
    }
});   
</script>
