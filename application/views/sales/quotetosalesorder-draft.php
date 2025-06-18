<div class="content-body">
<div class="card">
   <div class="card-header border-bottom">
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('quote') ?>">Quotes</a></li>                  
               <li class="breadcrumb-item"><a href="<?= base_url('SalesOrders/salesorder_new?id='.$quote_id.'&token=1') ?>"><?php echo $this->lang->line('Sales Order Landing'); ?></a></li>                      
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Sales Order'); ?> #<?php echo $id+1000;?></li>
            </ol>
      </nav>
      
      <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
               <h4 class="card-title"><?php echo $this->lang->line('Sales Order Landing'); ?></h4>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-xs-12">  
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
         <form method="post" id="data_form">
            <div class="row">
               
               <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                  <div id="customerpanel" class="inner-cmp-pnl">
                     <div id="customer">
                        <div class="clientinfo">
                           <h3 class="title-sub"><?php echo $this->lang->line('Client Details') ?></h3>
                           <hr>
                           <?php echo '  <input type="hidden" name="customer_id" id="customer_id" value="' . $customer['id'] . '">
                              <div id="customer_name"><strong>' . $customer['name'] . '</strong></div>
                              </div>
                              <div class="clientinfo">                              
                                 <div id="customer_address1"><strong>' . $customer['address'] . '<br>' . $customer['city'] . ',' . $customer['shipping_country'] . '</strong></div>
                              </div>
                              
                              <div class="clientinfo">
                              
                              <div type="text" id="customer_phone">Phone: <strong>' . $customer['phone'] . '</strong><br>Email: <strong>' . $customer['email'] . '</strong></div>
                              </div>
                              <div class="clientinfo">                              
                                 <div type="text" id="customer_phone">Company Credit Limit &nbsp;: <strong>' . $customer['credit_limit'] . '</strong><br>Credit Period &nbsp;: <strong>' . $customer['credit_period'] . '(Days)</strong><br>Available Credit Limit&nbsp;: <strong>' . $customer['avalable_credit_limit'] . '</strong></div>
                              </div>'; ?>
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
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference');?></label>
                              
                                 <input type="text" class="form-control required"
                                    placeholder="<?php echo $this->lang->line('Quote Reference')?>" name="refer" id="refer"
                                    value="<?php echo $invoice['refer'] ?>">
                           </div>
                           <?php
                              $invoiceduedate = "";
                              $customer_purchase_order = "";
                              $customer_order_date = "";
                              $proposal="";
                              if(!empty($invoice['customer_purchase_order']) && !empty($invoice['customer_order_date']))
                              {
                                 $invoiceduedate = $invoice['invoiceduedate'];
                                 $customer_purchase_order = $invoice['customer_purchase_order'];
                                 $customer_order_date = $invoice['customer_order_date'];
                                 $proposal = $invoice['proposal'];
                              }
                           ?>

                                            
                           <!--erp2024 newly added 29-09-2024  -->
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                   <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                   <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number" value="<?php echo $invoice['customer_reference_number'] ?>" >
                                   </div>                                    
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                   <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                   <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person" value="<?php echo $invoice['customer_contact_person'] ?>"  >
                                   </div>                                    
                           </div>
                           <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                   <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                   <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Contact Person Number" value="<?php echo $invoice['customer_contact_number'] ?>" >
                                   </div>                                    
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                   <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                   <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email" value="<?php echo $invoice['customer_contact_email'] ?>" >
                                   </div>                                    
                           </div>
                           <!--erp2024 newly added 29-09-2024 ends -->
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <label for="invocieduedate" class="col-form-label"><?php echo  $this->lang->line('Delivery Deadline'); ?> <span class="compulsoryfld">*</span></label>
                                 <input type="date" class="form-control required" name="invocieduedate" id="invocieduedate"  placeholder="Validity Date" autocomplete="false" min="<?php echo date("Y-m-d"); ?>" value="<?=$invoiceduedate?>">
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="Purchase Order" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." No.";?> <span class="compulsoryfld"> *</span></label>
                                 <input type="text" class="form-control required" placeholder="<?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order');?>" name="customer_purchase_order" id="customer_purchase_order" value="<?=$customer_purchase_order?>">
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." ".$this->lang->line('Date');?><span class="compulsoryfld"> *</span></label>                           
                                 <input type="date" class="form-control required" name="customer_order_date" id="customer_order_date" placeholder="Order Date" autocomplete="false" value="<?=$customer_order_date?>" max="<?php echo date("Y-m-d"); ?>">
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
                                 <input type="hidden" name="quote_id" id="quote_id" value="<?php echo $quote_id; ?>">
                           
                           
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
                              <textarea class="form-textarea" name="notes" id="salenote"><?php echo $invoice['notes'] ?></textarea>
                           </div>
                           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?> </label>
                              <textarea class="form-textarea" name="propos" id="contents" rows="2"><?php echo $invoice['proposal'] ?></textarea>
                              <!-- <textarea class="form-textarea" name="propos" id="contents" rows="2"><?php echo $invoice['proposal'] ?></textarea> -->
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>
               <div id="saman-row">
                  <div class="col-12 form-row mt-1">
                     <div class="form-check" >
                        <input class="form-check-input" type="checkbox" value="2" id="discountchecked" name="discountchecked">
                        <label class="form-check-label" for="discountchecked" style="font-size:14px;color:#404E67;">
                           <b> <?php echo $this->lang->line('Do you want to modify the prices or discounts for the items below'); ?></b>
                        </label>
                     </div>
                  </div>
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                           <th width="22%" class="text-center1 pl-1">Item Name</th>
                           <th width="10%" class="text-center1 pl-1">Item No.</th>
                           <th width="5%" class="text-center">Curr. Price</th>
                           <th width="4%" class="text-center">Min. Price</th>
                           <th width="4%" class="text-center">Max dis(%)</th>
                           <th width="7%" class="text-center">Lead</th>
                           <th width="8%" class="text-center">Quote</th> 
                           <th width="7%" class="text-center">Sales Order</th>
                           <!-- <th width="8%" class="text-center">On Hand</th> -->
                           <th width="12%" class="text-center discountpotion1">Discount</th>                                                    
                           <th width="4%" class="text-right">Quote Price</th>
                           <th width="4%" class="text-right">Unit Price</th>
                           <th width="8%" class="text-right">Total</th>
                           <?php 
                              // if($configurations['config_tax']!='0'){  ?>
                                  <!-- <th width="10%" class="text-right"><?php echo $this->lang->line('Tax'); ?>(%) / <?php echo $this->lang->line('Amount'); ?></th>      -->
                           <?php // } ?>
                           <!-- <th width="10%" class="text-center">Discount Amt / Type</th> -->
                           <!-- <th width="10%" class="text-center">
                              Amount(<?php echo $this->config->item('currency'); ?>)
                           </th> -->
                           <!-- <th width="5%" class="text-center1">Status</th> -->
                           <th width="55%" >Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php 
                           $i = 0;
                           $gandtax = 0;
                           $ganddiscount = 0;
                           $gandttotal = 0;
                           // $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity, cberp_products.product_code, cberp_products.unit, cberp_products.product_price, cberp_sales_orders_items.delivered_qty AS deliveredqty, cberp_sales_orders_items.remaining_qty AS remainingqty, cberp_sales_orders_items.transfered_qty AS trasferedqty, cberp_sales_orders_items.ordered_qty AS orderedqty,cberp_customer_lead_items.qty as leadqty,cberp_customer_lead_items.id as leadid, cberp_quotes.id as quoteid, cberp_quotes_items.qty as quoteqty');
                           
                           foreach ($products as $row) {
                               if($row['totalQty']<=$row['alert']){
                                   echo '<tr style="background:#ffb9c2;">';
                               }
                               else{
                                   echo '<tr >';
                               }
                               if(!empty($row['lead_id'])){
                                 $leadid = $row['lead_id'];
                                 $leadurl =  '<a href="' . base_url("invoices/customer_leads?id=$leadid") . '"   title="Lead" target="_blank">'.($row['lead_id']+1000).'</a>';
                                 $leadQty = $row['leadqty'];
                                 $leadDate = date('d-m-Y', strtotime($row['leaddate']));
                              }
                              else{
                                 $leadurl ='';
                                 $leadQty = '--';
                                 $leadDate ='';
                              }
                              if(!empty($row['quote_id'])){
                                 $quote_id = $row['quote_id'];
                                 $quoteurl =  '<a href="' . base_url("quote/view?id=$quote_id") . '"   title="Quote" target="_blank">'.($row['quote_id']+1000).'</a>';
                                 $quoteQty = $row['quoteqty'];
                                 $quoteDate = date('d-m-Y', strtotime($row['quotedate']));
                              }
                              else{
                                 $quoteurl ='';
                                 $quoteQty = '--';
                                 $quoteDate ='';
                              }
                              if($row['discount_type']=='Perctype'){
                                 $percsel = "selected";
                                 $amtsel = "";
                                 $perccls = '';
                                 $amtcls = 'd-none';
                                 $disperc = amountFormat_general($row['discount']);
                                 $disamt = 0;
                                 $distype = "%";
                             }
                             else{
                                 $amtsel = "selected";
                                 $percsel = "";
                                 $perccls = 'd-none';
                                 $amtcls = '';
                                 $disamt = amountFormat_general($row['discount']);
                                 $disperc = 0;
                                 $distype = "Amt";
                             }
                             $unitcost = 0;
                             $unitcost = (intval($row['qty'])>0) ?round($row['subtotal'] / intval($row['qty']), 2):0;
                              //  $gandtax = $gandtax + amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc);
                              //  $ganddiscount = $ganddiscount + ;
                              //  $gandttotal = $gandttotal + amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc);
                              //  echo '<td width="2%"><input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'"> </td>';
                               
                               echo '<td><strong id="productlabel' . $i . '">'.$row['product'].'</strong><input type="hidden" class="form-control" name="product_name[]" placeholder="'.$this->lang->line('Enter Product name').'"  value="' . $row['product'] . '"> </td>';
                               
                               echo '<td><strong >'.($row['code']).'</strong></td>';
                               echo '<td class="text-right"><strong >'.($row['product_price']).'</strong></td>';

                               echo '<td class="text-right"><strong >'.($row['product_lowest_price']).'</strong></td>';

                               echo '<td class="text-right"><strong >'.($row['product_max_discount']).'</strong></td>';

                               echo '<td class="text-center"><strong>'.intval($row['leadqty']).'</strong><br>'.$leadurl.'<br>'.$leadDate.'</td>';
                               
                              // echo '<td class="text-center"><strong id="leadqty">'.intval($row['leadqty']).'</strong></td>';

                              //  echo '<td class="text-center"><strong id="orderedqty">'.intval($row['orderedqty']).'</strong><input type="hidden" class="form-control req" name="ordered_qty[]" id="orderedqty-' . $i . '" value="' .intval($row['orderedqty']) . '"></td>';

                               echo '<td class="text-center"><strong>'.intval($row['quoteqty']).'</strong><br>'.$quoteurl.'<br>'.$quoteDate.'<input type="hidden" class="form-control req" name="ordered_qty[]" id="orderedqty-' . $i . '" value="' .intval($row['quoteqty']) . '"><input type="hidden" class="form-control req" name="remaining_qty[]" id="remainingqty-' . $i . '" value="' .intval($row['remainingqty']) . '"></td>';

                               echo '<td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '), rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . intval($row['qty']) . '" ><input type="hidden" name="old_product_qty[]" value="' . intval($row['qty']) . '" >';
                               
                               echo '<strong id="deliveredqty" class="d-none">'.intval($row['deliveredqty']).'</strong>';  

                              //  echo '<strong id="remainingqty" class="d-none">'.intval($row['remainingqty']).'</strong><input type="hidden" class="form-control req" name="remaining_qty[]" id="remainingqty-' . $i . '" value="' .intval($row['remainingqty']) . '">';                              

                              
                               echo '<strong id="onhandQty-'.$i.'" class="d-none">'.intval($row['totalQty']).'</strong></td>';

                               echo '<td class="text-center discountpotion d-none">
                                    <div class="input-group text-center">
                                       <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control" onchange="discounttypeChange(' . $i . ')">
                                             <option value="Perctype" '.$percsel.'>%</option>
                                             <option value="Amttype" '.$amtsel.'>Amt</option>
                                       </select>&nbsp;
                                       <input type="number" min="0" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '">
                                       <input type="number" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">
                                    </div>                                    
                                    <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">Amount : ' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                    <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                 </td>';

                           echo '<td class="text-center discountpotionnotedit">
                               <div class="text-center"> ';
                               echo '<strong id="discount_type_label-' . $i . '" >' .$distype . '</strong> / '; 
                               
                               if($percsel!=""){
                                                                 
                                 echo '<strong id="discount_typeval_label-' . $i . '" >' .$disperc . '</strong>';
                               }
                               else{
                                 echo '<strong id="discount_typeval_label-' . $i . '" >' .$disamt . '</strong>';
                               }
                               echo '</div>                                    
                               <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">Amount : ' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                               <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                           </td>';

                              //  echo '<td class="text-center"><strong><span id="discount-amtlabel-' . $i . '">'.$row['totaldiscount'].'</span><span id="discounttype1-' . $i . '">('.$row['discount']." ".$distype.')</span></strong><input type="hidden" class="form-control discount" name="discount_type[]" onkeypress="return isNumber(event)" id="discounttype-' . $i . '"value="' . $row['discount_type'] . '"><input type="hidden" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '"><input type="hidden" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '"> </td>';

                              

                           if($row['discount_type']=='Perctype'){
                              $distype = '%';
                              $percsel = "selected";
                              $amtsel = "";
                              $perccls = '';
                              $amtcls = 'd-none';
                              $disperc = amountFormat_general($row['discount']);
                              $disamt = 0;
                          }
                          else{
                              $distype = 'Amt';
                              $amtsel = "selected";
                              $percsel = "";
                              $perccls = 'd-none';
                              $amtcls = '';
                              $disamt = amountFormat_general($row['discount']);
                              $disperc = 0;
                          }

                           //echo '<strong class="d-none"><span id="discount-amtlabel-' . $i . '">'.$row['totaldiscount'].'</span><span id="discounttype1-' . $i . '">('.$row['discount']." ".$distype.')</span></strong><input type="hidden" class="form-control discount" name="discount_type[]" onkeypress="return isNumber(event)" id="discounttype-' . $i . '"value="' . $row['discount_type'] . '"><input type="hidden" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . $disperc . '"><input type="hidden" min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">';

                           // if($configurations['config_tax']!='0'){
                           //    echo '<td class="text-right"> <input type="hidden" class="form-control vat" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['tax']) . '"><span class="text-right" id="texttaxa-' . $i . '">' . amountExchange_s($row['tax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span> / <span class="text-right" id="texttaxa-' . $i . '">' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span></td>';
                           // }
                           echo '</td>';

                           
                           echo '<td class="text-right"><strong id="pricelabel' . $i . '" class="pricelabel">'.($row['price']).'</strong><input type="hidden" class="form-control req prc " name="product_price[]" id="price-' . $i . '"  onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '" >';


                           echo '<td class="text-right"><strong class="ttlText1" id="unitcost-' . $i . '">' . $unitcost .'</strong></td>';
                           echo '<td class="text-right"><strong><span class="ttlText" id="result-' . $i . '">' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span></strong></td>';
                           
                           // echo '<td>'.ucfirst($row['status']).'</td>';

                           echo '<td class="text-left"><button onclick="producthistory('.$i.')" type="button" class="btn btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                           </td>';
                           //echo '&nbsp;<button type="button" data-rowid="' . $i . '" class="btn btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>';
                           echo '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                           <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                           <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                           <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '"><input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '"> <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                           <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $row['lowest_price'] . '">
                           <input type="hidden" class="form-control" name="maxdiscountrate[]" id="maxdiscountrate-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' . $row['max_disrate'] . '"><input type="hidden" class="form-control" name="delivered_qty[]" id="deliveredqty-' . $i . '" value="' .intval($row['deliveredqty']) . '"><input type="hidden" class="form-control" name="transfered_qty[]" id="trasferedqty-' . $i . '" value="' .intval($row['trasferedqty']) . '">';
                           echo '<input type="hidden" name="lowest_price[]" id="lowest_price-' . $i . '" value="' . $row['lowest_price'] . '">';
                           echo '<input type="hidden" name="max_disrate[]" id="max_disrate-' . $i . '" value="' . $row['max_disrate'] . '">
                           </tr>';
                               $i++;
                           } ?>
                        <!-- <tr class="last-item-row sub_c tr-border">
                           <td class="add-row no-border">
                              <button type="button" class="btn btn-secondary" id="addquote_salesorder">
                              <i class="fa fa-plus-square"></i> Add Row
                              </button>
                           </td>
                           <td colspan="5" class="no-border"></td>
                        </tr> -->
                        
                     </tbody>
                  </table>
                  <div class="col-12 row mt-3">
                     <?php  if($configurations['config_tax']!='0'){ ?>
                        <div class="col-11 text-right">
                           <strong>Total Tax (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                        </div>
                        <div class="col-1">
                           <span id="taxr" class="lightMode"><?php echo amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                        </div>
                     <?php } ?>
                     <div class="col-10 text-right">
                        <strong  class="d-none1">Total Discount (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                     </div>
                     <div class="col-2"> <span id="discs" class="lightMode d-none1"><?php echo $invoice['discount']; ?></span></div>
                     <div class="col-12 text-right">
                        <?php if ($exchange['active'] == 1){
                           echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                        <select name="mcurrency" class="selectpicker form-control">
                        <?php
                           echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                           foreach ($currency as $row) {
                           
                                 echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                           } ?>
                        </select><?php } ?>
                     </div>
                     <div class="col-10 text-right">
                        <strong class="d-none1"><?php echo $this->lang->line('Grand Total') ?>(<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                     </div>
                     <div class="col-2">
                        <span id="grandtotaltext"><?= amountExchange_s($invoice['total'], $invoice['multi'],$this->aauth->get_user()->loc); ?></span>
                        <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?= amountExchange_s($invoice['total'], $invoice['multi'],$this->aauth->get_user()->loc); ?>" readonly="">
                     </div>

                     <div class="col-12 text-right mt-2">
                        <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn" value="<?php echo $this->lang->line("Save As Draft");?>" id="quote-to-salesorder-draft-btn" data-loading-text="Adding...">&nbsp;
                        <!-- <input type="submit" class="btn btn-lg btn-secondary sub-btn" value="<?php echo $this->lang->line("Save As Draft");?>" id="submit-data-draft" data-loading-text="Adding...">&nbsp; -->
                        <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line("Create Sales Order");?>" id="quote-to-salesorder-btn" data-loading-text="Adding...">
                     </div>

                  </div>
               </div>
               <input type="hidden" value="quote/saleorderaction" id="action-url">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="<?php echo $i; ?>" name="counter" id="ganak">
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
               <button type="button" class="btn btn-crud btn-secondary" data-dismiss="modal">Close</button>
               <input type="submit" id="mclient_add" class="btn btn-crud btn-secondary submitBtn" value="ADD" />
            </div>
         </form>
      </div>
   </div>
</div>
<script type="text/javascript">
   $(document).ready(function() {
      
       //erp2024 removed pdf code don't delete
       // $('#MaterialReport').click(function() {
       //     var selectedProducts = [];
       //     $('.checkedproducts:checked').each(function() {
       //         selectedProducts.push($(this).val());
       //     });
       //     if (selectedProducts.length === 0) {
       //         alert("Please select at least one product.");
       //         return;
       //     }
       //     var invocienoId = $('#invocienoId').val();
       //     var customer_id = $('#customer_id').val();
       //     var invocieduedate = $('#invocieduedate').val();
       //     var invoicedate = $('#invoicedate').val();
       //     var refer = $('#refer').val();
       //     var taxformat = $('#taxformat').val();
       //     var discountFormat = $('#discountFormat').val();
       //     var salenote = $('#salenote').val();
       //     var contents = $('textarea#contents').val();
   
       //     if (selectedProducts.length > 0) {
       //         var form = $(
       //             '<form action="<?php echo site_url('pos_invoices/materialrequestreportpdf')?>" method="POST" target="_blank"></form>'
       //             );
       //         form.append('<input type="hidden" name="selectedProducts" value="' + selectedProducts +
       //             '">');
       //         form.append('<input type="hidden" name="invocienoId" value="' + invocienoId + '">');
       //         form.append('<input type="hidden" name="customer_id" value="' + customer_id + '">');
       //         form.append('<input type="hidden" name="invoicedate" value="' + invoicedate + '">');
       //         form.append('<input type="hidden" name="invocieduedate" value="' + invocieduedate + '">');
   
       //         form.append('<input type="hidden" name="refer" value="' + refer + '">');
       //         form.append('<input type="hidden" name="taxformat" value="' + taxformat + '">');
       //         form.append('<input type="hidden" name="discountFormat" value="' + discountFormat + '">');
       //         form.append('<input type="hidden" name="salenote" value="' + salenote + '">');
       //         form.append('<input type="hidden" name="contents" value="' + contents + '">');
       //         $('body').append(form);
       //         form.submit();
       //     }
       // });
       //erp2024 removed pdf code ends
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
                customer_purchase_order: { required: true }
            },
            messages: {
                invocieduedate: "Enter Delivery Deadline",
                customer_purchase_order: "Purchase Order No.",
                customer_order_date: "Purchase Order Date"
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

        $('#quote-to-salesorder-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#quote-to-salesorder-btn').prop('disabled', true); // Disable button to prevent multiple submissions

            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 1);
                var quote_id = $("#quote_id").val();
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
                            url: baseurl + 'quote/saleorderaction_for_draft_convertion', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                              window.location.href = baseurl + 'SalesOrders/salesorder_new?id='+quote_id+'&token=1'; 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#quote-to-salesorder-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#quote-to-salesorder-btn').prop('disabled', false);
            }
        });

        $('#quote-to-salesorder-draft-btn').on('click', function(e) {
            e.preventDefault();
            $('#quote-to-salesorder-draft-btn').prop('disabled', true); // Disable button to prevent multiple submissions

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
                                Swal.fire({
                                      title: "Success",
                                      text: "Data saved successfully",
                                      icon: "success"
                                  });
                                $('#quote-to-salesorder-draft-btn').prop('disabled', false);
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
               //      } else if (result.dismiss === Swal.DismissReason.cancel) {
               //          // Enable the button again if user cancels
               //          $('#quote-to-salesorder-draft-btn').prop('disabled', false);
               //      }
               //  });
            // } else {
            //     $('#quote-to-salesorder-draft-btn').prop('disabled', false);
            // }
        });


});
   
   $(function() {
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
</script>

