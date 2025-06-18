<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('quote') ?>"><?php echo $this->lang->line('Quotes') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Quote(New)') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Quote(New)') ?></h4>
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
                    <div class="row">
                        
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub">
                                            <?php echo $this->lang->line('Customer Details') ?> 
                                    </div>
                                    <div class="frmSearch col-sm-12"><label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></label>
                                        <input type="text" class="form-control required" name="cst" id="customer-box"
                                               placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>"
                                               autocomplete="off"/>

                                        <div id="customer-box-result"></div>
                                    </div>

                                </div>
                                <div id="customer">
                                    <div class="clientinfo">
                                        <?php echo $this->lang->line('Client Details') ?>
                                        <hr>
                                        <input type="hidden" name="customer_id" id="customer_id" value="0">
                                        <div id="customer_name"></div>
                                    </div>
                                    <div class="clientinfo">

                                        <div id="customer_address1"></div>
                                    </div>

                                    <div class="clientinfo">

                                        <div type="text" id="customer_phone"></div>
                                    </div>
                                    <hr>
                                    <div id="customer_pass"></div>
                                    
                                </div>


                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                            <div class="inner-cmp-pnl">
                                <div class="form-group row">

                                    <div class="col-sm-12"><h3
                                                class="title-sub"><?php echo $this->lang->line('Quote Properties') ?></h3>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Quote Number') ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>
                                            <input type="hidden" class="form-control" placeholder="Quote #"  name="invocieno" id="invocieno" value="<?php echo $lastinvoice + 1 ?>" readonly>
                                            <input type="text" class="form-control" placeholder="Quote #"  name="quote_number" id="quote_number" value="<?php echo $prefix.($lastinvoice + 1); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="invocieno"  class="col-form-label"> <?php echo $this->lang->line('Reference') ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                 aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #" name="refer">
                                        </div>
                                    </div>
                                    <!--erp2024 newly added 29-09-2024  -->
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                        <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number">
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                        <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person">
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                        <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number">
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                        <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email">
                                        </div>                                    
                                    </div>
                                    <!--erp2024 newly added 29-09-2024 ends -->
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="invociedate" class="col-form-label"> <?php echo $this->lang->line('Quote Date') ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="icon-calendar4" aria-hidden="true"></span></div>
                                            <input type="date" class="form-control required"
                                                   placeholder="Billing Date" name="invoicedate" min="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Quote Validity') ?> <span class="compulsoryfld">*</span></label>
                                            <input type="date" class="form-control required" name="invocieduedate" placeholder="Due Date" autocomplete="false" min="<?=date('Y-m-d')?>">
                                            <!-- <input type="text" class="form-control required date30_plus" name="invocieduedate" placeholder="Due Date" data-toggle="datepicker" autocomplete="false"> -->
                                    </div>
                                    <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="taxformat"
                                               class="col-form-label"> <?php echo $this->lang->line('Tax') ?></label>
                                        <select class="form-control"
                                                onchange="changeTaxFormat(this.value)"
                                                id="taxformat">
                                            <?php echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="discountFormat"
                                                   class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                            <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                    id="discountFormat">
                                                <?php //echo $this->common->disclist() ?>
                                            </select>
                                    </div> -->

                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?><span class="compulsoryfld"> *</span></label>
                                        <select id="s_warehouses" name="s_warehouses" class="selectpicker form-control" >
                                        <?php 
                                        echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                        <?php foreach ($warehouse as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                        } ?>
                                        </select>
                                    </div>
                                    <?php if (isset($employee)){?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="employee" class="col-form-label"><?php echo $this->lang->line('Assign to') ?></label>
                                            <select name="employee" class=" col form-control disable-class">
                                                <?php echo '<option value="">Select an Employee</option>'; ?>
                                                <?php foreach ($employee as $row) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['name'].'</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                    <?php if ($exchange['active'] == 1){ ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="mcurrency" class="col-form-label"><?php echo $this->lang->line('Payment Currency client'). ' <small>' . $this->lang->line('based on live market'); ?></label>
                                            <select name="mcurrency" class="selectpicker form-control">
                                                <option value="0">Default</option>
                                                <?php foreach ($currency as $row) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="pterms" class="col-form-label"><?php echo $this->lang->line('Payment Terms'); ?></label>
                                        
                                        <select name="pterms" class="selectpicker form-control disable-class">
                                        <option value="">Select Payment Term</option>
                                            <?php foreach ($terms as $row) {
                                                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Quote Note') ?></label>
                                        <textarea class="form-textarea" name="notes" rows="2"></textarea>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                                        <textarea class="summernote1 form-textarea" name="propos"  rows="2"></textarea>
                                    </div>
                                    <!-- Image upload sections starts-->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                        <label for="cst" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
                                        <div class="row">                            
                                            <div class="col-8">
                                                <div class="d-flex">
                                                    <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                    <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                    <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="fa fa-trash" ></i></button>
                                                </div>
                                                <div id="uploadsection"></div>                                                
                                            </div>                        
                                            <div class="col-4">
                                                    <button class="btn btn-crud btn-secondary btn-sm mt-1" id="addmore_img"  title="Add More Files" type="button"><i class="fa fa-plus-circle"></i> Add More</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Image upload sections ends -->
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- <div class="row">
                        <div class="col">
                            <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                            <textarea class="summernote1 form-textarea" name="propos" id="contents" rows="2"></textarea></div>
                    </div> -->

                    <div id="saman-row" class="overflow-auto">  
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>


                            <tr class="item_header bg-gradient-directional-blue white">
                                <!-- <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Decription & No') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Rate') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Min. Price') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Discount') ?>(%)</th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                                <th width="10%" class="text-center">
                                    <?php echo $this->lang->line('Amount') ?>
                                    (<?php echo $this->config->item('currency'); ?>)
                                </th>
                                <th width="" class="text-center"><?php echo $this->lang->line('Action') ?></th> -->
                                <tr class="item_header bg-gradient-directional-blue white">
                                <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                <th width="22%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('Selling Price') ?></th>
                                <th width="7%" class="text-right"><?php echo $this->lang->line('Lowest Price') ?></th>
                                <?php  //Verify that tax is enabled
                                $colspan = 8;
                                if($configurations['config_tax']!='0'){ 
                                    $colspan = 10;    
                                ?>
                                    <th width="10%" class="text-right"><?php echo $this->lang->line('Tax'); ?>(%) / <?php echo $this->lang->line('Amount'); ?></th>
                                <?php } ?>
                                <th width="5%" class="text-center"><?php echo $this->lang->line('Max discount %')?></th>
                                <th width="12%" class="text-center"><?php echo $this->lang->line('Discount')?>/ <?php echo $this->lang->line('Amount'); ?></th>
                                <th width="10%" class="text-right">
                                    <?php echo $this->lang->line('Amount') ?>
                                    (<?php echo $this->config->item('currency'); ?>)
                                </th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                            </tr>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                
                            <td><input type="text" class="form-control required" name="code[]"  id='code-0' placeholder="<?php echo $this->lang->line('Search by Item No') ?>"></td>
                                <td><input type="text" class="form-control required" name="product_name[]" 
                                           placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                           id='productname-0'>
                                </td>
                                <td class="text-center"><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off" value="1"></td>
                                <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                <td class="text-right">    
                                    <strong id="pricelabel-0"></strong>
                                    <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off"></td>
                                <td class="text-right">
                                    <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                    <strong id="lowestpricelabel-0"></strong>
                                </td>
                                <?php //Verify that tax is enabled
                                if($configurations['config_tax']!='0'){ ?>           
                                        <td class="text-center">
                                            <div class="text-center">                                                
                                                <input type="hidden" class="form-control" name="product_tax[]" id="vat-0"
                                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                    autocomplete="off">
                                                    <strong id="taxlabel-0"></strong>&nbsp;<strong  id="texttaxa-0"></strong>
                                            </div>
                                        </td>
                                <?php } ?>
                                <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"></td>

                                <td class="text-center">
                                    <div class="input-group text-center">
                                        <select name="discount_type[]" id="discounttype-0" class="form-control" onchange="discounttypeChange(0)">
                                            <option value="Perctype">%</option>
                                            <option value="Amttype">Amt</option>
                                        </select>&nbsp;
                                        <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0)">
                                        <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0)">
                                    </div>  
                                    <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                    <div><strong id="discount-error-0"></strong></div>                                    
                                </td>

                                <td class="text-right">
                                    <strong><span class='ttlText' id="result-0">0</span></strong></td>
                                <td class="text-center">
                                    <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                    <button onclick='single_product_details("0")' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                </td>
                                <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                <input type="hidden" name="disca[]" id="disca-0" value="0">
                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                <input type="hidden" name="unit[]" id="unit-0" value="">
                                <input type="hidden" name="hsn[]" id="hsn-0" value="">
                            </tr>
                            <!-- <tr>
                                <td colspan="9"><textarea id="dpid-0" class="form-control" name="product_description[]" placeholder="<?php //echo $this->lang->line('Enter Product description'); ?>" autocomplete="off"></textarea></td>
                            </tr> -->

                            <tr class="last-item-row sub_c tr-border">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-crud btn-secondary"  title="Add product row" id="lead_create_btn">
                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>
                            <?php 
                            if($configurations['config_tax']!='0'){ ?>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="7" align="right" class="no-border">
                                        <input type="hidden" value="0" id="subttlform" name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?> (<span  class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </td>
                                    <td align="left" colspan="2" class="no-border">
                                        <span id="taxr" class="lightMode">0</span></td>
                                </tr>
                            <?php } ?>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Discount') ?> (<span  class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode">0</span></td>
                            </tr>

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="8" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="right" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                    onkeypress="return isNumber(event)"
                                                                    placeholder="Value"
                                                                    name="shipping" autocomplete="off"
                                                                    onkeyup="billUpyog()">
                                    ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> )
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="no-border"></td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandtotaltext"></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly>

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="4" class="no-border"></td>
                                <td align="right" colspan="6" class="no-border">
                                    <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn" value="<?php echo "Save As Draft" ?>" id="quote_draft_btn">
                                    <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Prepared') ?>" id="quote_create_btn" data-loading-text="Creating...">

                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                    <!-- <input type="hidden" value="quote/action" id="action-url"> -->
                    <input type="hidden" value="search" id="billtype">
                    <input type="hidden" value="leadid" id="leadid">
                    <input type="hidden" value="0" name="counter" id="ganak">
                    <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
                    <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
                    <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
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
                    <input type="hidden" value="0" name="drafttxt" id="drafttxt">
                    <input type="hidden" value="<?=$this->session->userdata('draftquote_id')?>" name="draftid" id="draftid">


                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addCustomer" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <form method="post" id="product_action" class="form-horizontal" enctype="multipart/form-data">
                <!-- Modal Header -->
                <div class="modal-header">

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
                        <div class="col">
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


                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="city" id="mcustomer_city">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Region" id="region"
                                           class="form-control margin-bottom" name="region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="country" id="mcustomer_country">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="PostBox" id="postbox"
                                           class="form-control margin-bottom" name="postbox">
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Company"
                                           class="form-control margin-bottom" name="company">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="TAX ID"
                                           class="form-control margin-bottom" name="tax_id" id="mcustomer_city">
                                </div>


                            </div>

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label"
                                       for="customergroup"><?php echo $this->lang->line('Group') ?></label>

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

                        <!-- shipping -->
                        <div class="col">
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


                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="shipping_city" id="mcustomer_city_s">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Region" id="shipping_region"
                                           class="form-control margin-bottom" name="shipping_region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="shipping_country" id="mcustomer_country_s">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <input type="text" placeholder="PostBox" id="shipping_postbox"
                                           class="form-control margin-bottom" name="shipping_postbox">
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
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
            tooltip:false,
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
    
    $( document ).ready(function() {

        

        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {
                cst: {
                    required: function () {
                        return $("#customer_id").val() == "0";
                    }
                },
                invocieduedate: { required: true },
                // customer_reference_number : { required: true },
                // employee: { required: true } 
                customer_contact_number: {
                    phoneRegex :true
                },
            },
            messages: {
                cst: "Select customer",
                invocieduedate: "Enter Quote Validity",
                // customer_reference_number: "Enter Customer Reference",
                // employee: "Select an Employee"
                customer_contact_number: "Enter a Valid Number"
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

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create a new quote?",
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
                            url: baseurl + 'quote/action', 
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                // window.location.href = baseurl + 'quote/view?id='+invoiceno; 
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


        
      

    });
    $('#quote_draft_btn').on('click', function(e) {
        e.preventDefault();
        $('#quote_draft_btn').prop('disabled', true); // Disable button to prevent multiple submissions

        var invoicetid = parseInt($("#invocieno").val());
        var invoiceno = invoicetid - 1000; // Directly compute invoiceno
        var form = $('#data_form')[0]; // Get the form element
        var formData = new FormData(form); // Create FormData object
        if (!$("#customer-box").valid()) {
            $("#customer-box").focus();
            $('#quote_draft_btn').prop('disabled', false);
            return;
        }
        $.ajax({
            url: baseurl + 'quote/draftaction', // Replace with your server endpoint
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                try {
                    // Attempt to parse the response
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    window.location.href = baseurl + 'quote/create?id='+response.quote;
                } catch (e) {
                    console.error("JSON parse error:", e);
                    console.error("Response received:", response); // Log the problematic response
                    return; // Exit if there's an error
                }

                // Further processing if needed
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                console.log(error); // Log any errors
            }
        });
    });


</script>
