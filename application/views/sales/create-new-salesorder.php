<div class="content-body">
<div class="card">
   <div class="card-header border-bottom">
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('SalesOrders') ?>"><?php echo $this->lang->line('Sales Orders'); ?></a></li>                 
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Sales Order'); ?> #<?php echo $id+1000;?></li>
            </ol>
      </nav>
      
      <div class="row">
            <div class="col-xl-4 col-lg-3 col-md-4 col-sm-12 col-xs-12">
               <h4 class="card-title"><?php echo $this->lang->line('Sales Order'); ?> #<?php echo $id+1000;?></h4>
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
                           <?php } ?> 
                           <li class="active">SO #<?php echo $id+1000;?></li><?php
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
         <form method="post" id="data_form" enctype="multipart/form-data">
            <div class="row">
               
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                     <div id="customerpanel" class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="fcol-sm-12">
                                 <h3 class="title-sub">
                                    <?php echo $this->lang->line('Customer Details') ?> 
                           </div>
                           <div class="frmSearch col-sm-12"><label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></label>
                                 <input type="text" class="form-control required" name="cst" id="customer-box" placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>" autocomplete="off"/>
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
                        <div class="form-group form-row">
                           <div class="col-sm-12">
                              <h3 class="title-sub"><?php echo $this->lang->line('Sales Order Properties') ?></h3><hr>
                           </div>
                           <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label">Sales Order Number</label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-file-text-o"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control" placeholder="Sales Order #"
                                    name="invocieno" id="invocienoId"
                                    value="<?php echo $id+1000; ?>" readonly>
                              </div>
                           </div>
                           <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference');?> <span class="compulsoryfld"> *</span></label>
                              
                                 <input type="text" class="form-control required"
                                    placeholder="<?php echo $this->lang->line('Quote Reference')?>" name="refer" id="refer">
                           </div>
                           <!--erp2024 newly added 29-09-2024  -->
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                 <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number"  >
                                 </div>                                    
                           </div>
                           <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                 <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person"   >
                                 </div>                                    
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                 <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Contact Person Number" >
                                 </div>                                    
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                 <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email"  >
                                 </div>                                    
                           </div>
                           <!--erp2024 newly added 29-09-2024 ends -->
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="Purchase Order" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." No.";?> <span class="compulsoryfld"> *</span></label>
                                 <input type="text" class="form-control required" placeholder="<?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order');?>" name="customer_purchase_order" id="customer_purchase_order" >
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." ".$this->lang->line('Date');?><span class="compulsoryfld"> *</span></label>                           
                                 <input type="date" class="form-control required" name="customer_order_date" id="customer_order_date" placeholder="Order Date" autocomplete="false"  max="<?=date('Y-m-d')?>" >
                           </div>
                           
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <label for="invocieduedate" class="col-form-label"><?php echo  $this->lang->line('Delivery Deadline'); ?> <span class="compulsoryfld">*</span></label>
                                 <input type="date" class="form-control required" name="invocieduedate" id="invocieduedate"  placeholder="Validity Date" autocomplete="false" min="<?php echo date("Y-m-d"); ?>" >
                           </div>

                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="invociedate" class="col-form-label">Sales Order Date</label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-calendar4"
                                    aria-hidden="true"></span></div>                                
                              </div>
                           </div>
                              <input type="hidden" class="form-control required" placeholder="Billing Date" name="invoicedate" id="invoicedate"  autocomplete="false" min="<?php echo date("Y-m-d"); ?>"  value="<?php echo date("Y-m-d"); ?>" >
                                 <input type="hidden" name="iid" value="<?php echo $invoice['iid']; ?>">
                           
                           
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="taxformat" class="col-form-label">Tax</label>
                              <select class="form-control" onchange="changeTaxFormat(this.value)"
                                 id="taxformat">
                              <?php echo $taxlist; ?>
                              </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                 <label for="discountFormat" class="col-form-label">Discount</label>
                                 <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                    id="discountFormat">
                                 <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                 <?php echo $this->common->disclist() ?>
                                 </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Sale Point') ?></label>
                              <select id="s_warehouses" class="selectpicker form-control">
                              <?php //echo $this->common->default_warehouse();
                                 echo '<option value="0">' . $this->lang->line('Select Warehouse') ?></option><?php foreach ($warehouse as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>

                           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Sales Order Note') ?></label>
                              <textarea class="form-textarea" name="notes" id="salenote"></textarea>
                           </div>
                           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?> </label>
                              
                              <textarea class="form-textarea" name="propos" id="contents" rows="2"></textarea>
                              <!-- <textarea class="form-textarea" name="propos" id="contents" rows="2"><?php echo $invoice['proposal'] ?></textarea> -->
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>
               
                    <div class="creditlimit-check"></div>
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
                              
                              <td><input type="text" class="form-control required" name="code[]" required id='code-0'></td>
                                <td><input type="text" class="form-control required" name="product_name[]" required
                                           placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                           id='productname-0'>
                                </td>
                                <td class="text-center"><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(),orderdiscount()"
                                           autocomplete="off" value="1"></td>
                                <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                <td class="text-right">    
                                    <strong id="pricelabel-0"></strong>
                                    <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(), orderdiscount()"
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
                                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog(), orderdiscount()"
                                                    autocomplete="off">
                                                    <strong id="taxlabel-0"></strong>&nbsp;<strong  id="texttaxa-0"></strong>
                                            </div>
                                        </td>
                                <?php } ?>
                                <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"></td>

                                <td class="text-center">
                                    <div class="input-group text-center">
                                        <select name="discount_type[]" id="discounttype-0" class="form-control" onchange="discounttypeChange(0),orderdiscount()">
                                            <option value="Perctype">%</option>
                                            <option value="Amttype">Amt</option>
                                        </select>&nbsp;
                                        <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()">
                                        <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()">
                                    </div>  
                                    <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                    <div><strong id="discount-error-0"></strong></div>                                    
                                </td>

                                <td class="text-right">
                                    <strong><span class='ttlText' id="result-0">0</span></strong></td>
                                <td class="text-center">
                                    <button onclick='producthistory("0")' type="button" class="btn btn-crud  btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
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
                                    <button type="button" class="btn btn-crud btn-secondary"  title="Add product row" id="sales_create_btn">
                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>
                            <tr>
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
                                <td colspan="5" class="no-border"></td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandamount"></span>
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Product Discount') ?> (<span  class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode"></span></td>
                            </tr>

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="8" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="right" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                    onkeypress="return isNumber(event)"
                                                                    placeholder="Value"
                                                                    name="shipping" autocomplete="off"
                                                                    onkeyup="billUpyog(),orderdiscount()">
                                    ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> )
                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Order Discount') ?></strong></td>
                                <td align="right" colspan="1" class="no-border">
                                 <input type="number" class="form-control text-right" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()">
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="no-border"></td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Net Total') ?>
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
                                    <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn1" value="<?php echo "Save As Draft" ?>" id="salesorder-draft-btn">
                                    <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Create Sales Order') ?>" id="salesorder-btn" data-loading-text="Creating...">

                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

               <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="0" name="counter" id="ganak">
               <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
               <input type="hidden" value="<?= $this->common->taxhandle_edit($invoice['taxstatus']) ?>"
                  name="taxformat" id="tax_format">
               <input type="hidden" value="<?= $invoice['format_discount']; ?>" name="discountFormat"
                  id="discount_format">
               <input type="hidden" value="<?= $invoice['taxstatus']; ?>" name="tax_handle" id="tax_status">
               <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
               <input type="hidden"
                  value="<?php
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
         <form method="post" id="product_action" class="form-horizontal" enctype="multipart/form-data">
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
               <p id="statusMsg"></p>
               <input type="hidden" name="mcustomer_id" id="mcustomer_id" value="0">
               <div class="form-group row">
                  <label class="col-sm-2 col-form-label" for="name">Name</label>
                  <div class="col-sm-10">
                     <input type="text" placeholder="Name" class="form-control margin-bottom" id="mcustomer_name"
                        name="name" required>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-2 col-form-label" for="phone">Phone</label>
                  <div class="col-sm-10">
                     <input type="text" placeholder="Phone" class="form-control margin-bottom" name="phone"
                        id="mcustomer_phone">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-2 col-form-label" for="email">Email</label>
                  <div class="col-sm-10">
                     <input type="email" placeholder="Email" class="form-control margin-bottom crequired"
                        name="email" id="mcustomer_email">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-2 col-form-label" for="address">Address</label>
                  <div class="col-sm-10">
                     <input type="text" placeholder="Address" class="form-control margin-bottom " name="address"
                        id="mcustomer_address1">
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-sm-6">
                     <input type="text" placeholder="City" class="form-control margin-bottom" name="city"
                        id="mcustomer_city">
                  </div>
                  <div class="col-sm-6">
                     <input type="text" placeholder="Region" class="form-control margin-bottom" name="region">
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-sm-6">
                     <input type="text" placeholder="Country" class="form-control margin-bottom" name="country"
                        id="mcustomer_country">
                  </div>
                  <div class="col-sm-6">
                     <input type="text" placeholder="PostBox" class="form-control margin-bottom" name="postbox">
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
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <input type="submit" id="mclient_add" class="btn btn-secondary submitBtn" value="ADD" />
            </div>
         </form>
      </div>
   </div>
</div>
<script type="text/javascript">
   $(document).ready(function() {
      
       $('#discountchecked').val(2);
       //erp2024 new code for matrial request screen 07-06-2024 starts
       $('#MaterialReport').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               alert("Please select at least one product.");
               return;
           }
   
           if (selectedProducts.length > 0) {
               var form = $('<form action="<?php echo site_url('SalesOrders/materialrequest')?>" method="POST" target="_blank"></form>');
               form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts +'">');
               $('body').append(form);
               form.submit();
           }
       });
       //erp2024 new code for matrial request screen 07-06-2024 ends
       //erp2024 new code for purchase request screen 18-06-2024 starts
       $('#PurchaseRequest').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               alert("Please select at least one product.");
               return;
           }
   
           if (selectedProducts.length > 0) {
               var form = $('<form action="<?php echo site_url('Productrequest/purchaserequest')?>" method="POST" target="_blank"></form>');
               form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts +'">');
               $('body').append(form);
               form.submit();
           }
       });
       //erp2024 new code for purchase request screen 18-06-2024 ends
   
   
   
       $('#DeliveryReport').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               alert("Please select at least one product.");
               return;
           }
           var invocienoId = $('#invocienoId').val();
           var customer_id = $('#customer_id').val();
           var invocieduedate = $('#invocieduedate').val();
           var invoicedate = $('#invoicedate').val();
           var refer = $('#refer').val();
           var taxformat = $('#taxformat').val();
           var discountFormat = $('#discountFormat').val();
           var salenote = $('#salenote').val();
           var contents = $('textarea#contents').val();
   
           // Create the form dynamically
           var form = $('<form action="<?php echo site_url('pos_invoices/deliverNoteexportpdf')?>" method="POST"></form>');
           // Add hidden input fields for start_date and end_date
           form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts + '">');
           form.append('<input type="hidden" name="invocienoId" value="' + invocienoId + '">');
           form.append('<input type="hidden" name="customer_id" value="' + customer_id + '">');
           form.append('<input type="hidden" name="invoicedate" value="' + invoicedate + '">');
           form.append('<input type="hidden" name="invocieduedate" value="' + invocieduedate + '">');
   
           form.append('<input type="hidden" name="refer" value="' + refer + '">');
           form.append('<input type="hidden" name="taxformat" value="' + taxformat + '">');
           form.append('<input type="hidden" name="discountFormat" value="' + discountFormat + '">');
           form.append('<input type="hidden" name="salenote" value="' + salenote + '">');
           form.append('<input type="hidden" name="contents" value="' + contents + '">');
           // Append form to container
           $('body').append(form); // Append to body or another suitable element in the DOM
           // Programmatically submit the form
           form.submit();
       });

       $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {               
                invocieduedate: { required: true },
                customer_order_date: { required: true },
                customer_purchase_order: { required: true },
                customer_contact_number: {
                    phoneRegex :true
                },
                cst: {
                    required: function () {
                        return $("#customer_id").val() == "0";
                    }
                }
            },
            messages: {
                invocieduedate: "Enter Delivery Deadline",
                customer_purchase_order: "Purchase Order No.",
                customer_order_date: "Purchase Order Date",
                customer_contact_number: "Enter Valid Number",
                cst: "Select a Customer",
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

        $('#salesorder-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#salesorder-btn').prop('disabled', true); // Disable button to prevent multiple submissions

            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 1);
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create a new sales order?",
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
                            url: baseurl + 'SalesOrders/saleorderaction', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                              window.location.href = baseurl + 'SalesOrders'; 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#salesorder-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#salesorder-btn').prop('disabled', false);
            }
        });

        $('#salesorder-draft-btn').on('click', function(e) {
           
            
            e.preventDefault();
            $('#salesorder-draft-btn').prop('disabled', true); // Disable button to prevent multiple submissions
            // if($("#customer_id").val()==0)
            // {
            //     $("#customer-box").prop('required',true);
            //     return;
            // }
            if (!$("#customer-box").valid()) {
                $("#customer-box").focus();
                $('#salesorder-draft-btn').prop('disabled', false); // Re-enable button if validation fails
                return;
            }
           
            // Validate the form
            // if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 0);
                var quote_id = $("#quote_id").val();
               //  Swal.fire({
               //      title: "Are you sure?",
               //      text: "Do you want to save the data as draft?",
               //      icon: "question",
               //      showCancelButton: true,
               //      confirmButtonColor: '#3085d6',
               //      cancelButtonColor: '#d33',
               //      confirmButtonText: 'Yes, proceed!',
               //      cancelButtonText: "No - Cancel",
               //      reverseButtons: true,  
               //      focusCancel: true,      
               //      allowOutsideClick: false,  // Disable outside click
               //  }).then((result) => {
               //      if (result.isConfirmed) {
                        
                        $.ajax({
                            url: baseurl + 'quote/saleorderdraftaction', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                $('#salesorder-draft-btn').prop('disabled', false);
                                if(response.data != undefined)
                                {
                                    window.location.href = baseurl + 'SalesOrders/draft_or_edit?id='+response.data;
                                }
                                
                                // location.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
               //      } else if (result.dismiss === Swal.DismissReason.cancel) {
               //          // Enable the button again if user cancels
               //          $('#salesorder-draft-btn').prop('disabled', false);
               //      }
               //  });
            // } else {
                // If form validation fails, re-enable the button
            //     $('#salesorder-draft-btn').prop('disabled', false);
            // }
        });


});
   
   $("#refreshBtn").on("click", function() {
       location.reload();
   });
   $('.editdate').datepicker({
       autoHide: true,
       format: '<?php echo $this->config->item('dformat2'); ?>'
   });
   function checkqty(id){
      var qty = parseFloat($("#amount-" + id).val()) || 0;
      var quoteqty = parseFloat($("#orderedqty-" + id).val()) || 0;
      var deliveredqty = parseFloat($("#deliveredqty-" + id).val()) || 0;
      var total = qty + deliveredqty;  
      if(quoteqty < qty){
         $("#amount-" + id).val(0);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'Sales order quantity is greater than the Quote quantity.'
         });
      }
   }
   $('#discountchecked').on('change', function() {
      if ($(this).is(':checked')) {
         $('.discountpotion').removeClass('d-none');
         $('.discountpotionnotedit').addClass('d-none'); 
         $('.pricelabel').addClass('d-none'); 
         $('input[name="product_price[]"]').attr('type', 'text');
         $('#discountchecked').val(1);
      } else {
         $('.discountpotion').addClass('d-none');           
         $('.pricelabel').removeClass('d-none'); 
         $('.discountpotionnotedit').removeClass('d-none'); 
         $('input[name="product_price[]"]').attr('type', 'hidden');
         $('#discountchecked').val(2);
      }
   });

   // $('#quote_draft_btn').on('click', function(e) {
   //    e.preventDefault();
   //    $('#quote_draft_btn').prop('disabled', true); // Disable button to prevent multiple submissions

   //    var invoicetid = parseInt($("#invocieno").val());
   //    var invoiceno = invoicetid - 1000; // Directly compute invoiceno
   //    var form = $('#data_form')[0]; // Get the form element
   //    var formData = new FormData(form); // Create FormData object

   //    $.ajax({
   //       url: baseurl + 'quote/draftaction', // Replace with your server endpoint
   //       type: 'POST',
   //       data: formData,
   //       contentType: false,
   //       processData: false,
   //       success: function(response) {

   //             try {
   //                // Attempt to parse the response
   //                if (typeof response === "string") {
   //                   response = JSON.parse(response);
   //                }
   //                window.location.href = baseurl + 'quote/create?id='+response.quote;
   //             } catch (e) {
   //                console.error("JSON parse error:", e);
   //                console.error("Response received:", response); // Log the problematic response
   //                return; // Exit if there's an error
   //             }

   //             // Further processing if needed
   //       },
   //       error: function(xhr, status, error) {
   //             Swal.fire('Error', 'An error occurred while generating the lead', 'error');
   //             console.log(error); // Log any errors
   //       }
   //    });
   // });


</script>

