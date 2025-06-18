<div class="content-body">
<div class="card">
   <div class="card-header border-bottom">
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('SalesOrders') ?>">Sales Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sales Order</li>
         </ol>
      </nav>
      <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
               <h4 class="card-title"><?php echo $this->lang->line('Sales Order'). " #".$invoice['salesorder_number']; ?></h4>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-xs-12">  
                  <ul id="trackingbar">
                  <?php 
                     if(!empty($trackingdata))
                     {
                       if(!empty($trackingdata['lead_id']))
                       { 
                              ?>
                              <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>">LD #<?= $trackingdata['lead_id'] + 1000; ?></a></li>
                              <?php } 
                             if(!empty($trackingdata['quote_number']))
                             { ?>
                           <li><a href="<?= base_url('quote/view?id=' . $trackingdata['quote_number']) ?>">QT #<?= $trackingdata['quote_number'] + 1000; ?></a></li>
                        <?php }
                           $sales_numbers_array = explode(',', $trackingdata['salesorder_number']);                          
                           $sales_id_array = explode(',', $trackingdata['salesorder_number']);
                           if(!empty($sales_numbers_array))
                           {
                         ?>
                           <li class="active">
                           <?php 
                                 $flg = 1;
                                 $sales_orders_list = '<ul>';  // Start unordered list
                                 foreach ($sales_numbers_array as $key => $item) {
                                       
                                       // For the first sales order, display a clickable link
                                       if ($flg == 1) {
                                          echo '<a href="' . base_url('quote/salesorders?id=' . $sales_id_array[$key]) . '">SO #' . $item . ' </a>';
                                       }
                                       
                                       // Build the sales orders list to include in the popover
                                       $sales_orders_list .= '<li><a href="' . base_url('quote/salesorders?id=' . $sales_id_array[$key]) . '">SO #' . $item . '</a></li>';
                                       
                                       $flg++;
                                 }
                                 $sales_orders_list .= '</ul>';  // Close unordered list
                                 echo '<a class="badge badge-pill badge-default btn-primary" style="color:#ffffff;" href="#" data-toggle="popover" title="Sales Order Lists" data-html="true" data-content="' . htmlspecialchars($sales_orders_list) . '">' . count($sales_numbers_array) . '</a><br>';                                
                                ?>
                        </li>
                        <?php
                           }
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
               <div class="col-12">
                  <div class="title-action">
                     <!-- <a  href="javascript:void(0)" class='btn btn-secondary btn-sm mb-1 breaklink' onclick='confirmRedirect("<?php echo $invoice["iid"]; ?>")'><i class='fa fa-id-card-o'></i> <?php echo $this->lang->line('Convert To Delivery Note'); ?> </a> -->
                     <!-- <button class="btn  btn-sm btn-secondary mb-1" type="button" name="convertedInvoiceBtn"
                        id="convertedInvoiceBtn"><i class="fa fa-exchange"></i>
                     <?php //echo $this->lang->line('Convert to invoice'); ?></button> -->
                     <!-- erp2024 hide Update Inventory and refresh screen 18-06-2024 -->
                     <!-- <button class="btn  btn-sm btn-info mb-1" type="button" name="updateInventoryBtn"
                        id="updateInventoryBtn"><i class="fa fa-refresh"></i>
                        <?php echo $this->lang->line('Update Inventory'); ?></button>
                        
                        <button class="btn  btn-sm btn-danger mb-1" type="button" name="refreshBtn"
                        id="refreshBtn"><i class="fa fa-refresh"></i>
                        <?php echo $this->lang->line('Refresh Screen'); ?></button> -->
                     <!-- erp2024 hide Update Inventory and refresh screen 18-06-2024 ends-->
                     
                  </div>
               </div>
               <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                  <div id="customerpanel" class="inner-cmp-pnl">
                     <!-- <div class="form-group row">
                        <div class="frmSearch col-sm-12">
                           <label for="cst" class="col-form-label">Search
                           Client</label>
                           <input type="text" class="form-control" name="cst" id="customer-box"
                              placeholder="<?php echo $this->lang->line('Enter Customer Name or Mobile Number to search') ?>"
                              autocomplete="off" />
                           <div id="customer-box-result"></div>
                        </div>
                     </div> -->
                     <div id="customer">
                        <div class="clientinfo">
                           <div class="col-sm-12 row"><h3 class="title-sub"><?php echo $this->lang->line('Client Details') ?></h3></div>
                           <?php echo '  <input type="hidden" name="customer_id" id="customer_id" value="' . $invoice['csd'] . '">
                              <div id="customer_name"><strong>' . $invoice['name'] . '</strong></div>
                              </div>
                              <div class="clientinfo">
                              
                              <div id="customer_address1"><strong>' . $invoice['address'] . '<br>' . $invoice['city'] . ',' . $invoice['country'] . '</strong></div>
                              </div>
                              
                              <div class="clientinfo">
                              
                              <div type="text" id="customer_phone">Phone : <strong>' . $invoice['phone'] . '</strong><br>Email : <strong>' . $invoice['email'] . '</strong></div>
                              </div>
                              <div class="clientinfo">
                              
                              <div type="text" id="customer_phone">Company Credit Limit : <strong>' . $invoice['credit_limit'] . '</strong><br>Credit Period : <strong>' . $invoice['credit_period'] . '</strong>(Days)
                              <br><span class="avail_creditlimit">Available Credit Limit : <strong>' . $invoice['avalable_credit_limit'] . '</strong>
                              </div><input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="' . $invoice['avalable_credit_limit'] . '">
                              </div>'; ?>
                           <hr>
                           <div id="customer_pass"></div>

                           <!-- echo "Company Credit Limit &nbsp;&nbsp;&nbsp;: <b>" .$invoice['credit_limit']."</b>\n<br>"; 
                           echo "Credit Period : <b>" .$invoice['credit_period']."</b>(Days)\n<br>"; 
                           // echo "Available Credit Limit : <span ><b>".$avail_credit_limit."</b><span></span>"; 
                           echo "Available Credit Limit : <span ><b id='available-limit'></b><span></span>";  -->
                           
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                     <div class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="col-sm-12"><h3 class="title-sub"><?php echo $this->lang->line('Sales Order Properties') ?></h3>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label">Sales Order Number</label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-file-text-o"
                                    aria-hidden="true"></span></div>
                                 <input type="hidden" class="form-control" placeholder="Quote #" name="invocieno" id="invocienoId" value="<?php echo $invoice['tid']; ?>" readonly>
                                 <input type="hidden" class="form-control"  name="salesorder_id" id="salesorder_id" value="<?php echo $invoice['iid']; ?>" readonly>
                                 <input type="text" class="form-control" placeholder="Quote #"
                                    name="salesorder_number" id="salesorder_number"
                                    value="<?php echo $invoice['salesorder_number']; ?>" readonly>
                              </div>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Customer PO/Reference') ?></label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-bookmark-o"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control"
                                    placeholder="Customer Reference No." name="refer" id="refer"
                                    value="<?php echo $invoice['refer'] ?>" readonly>
                              </div>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Sales Order Date') ?></label>
                              <div class="input-group">
                                 <input type="date" class="form-control required"
                                    placeholder="Billing Date" name="invoicedate" id="invoicedate"
                                    autocomplete="false" value="<?php echo $invoice['invoicedate'] ?>" readonly>
                                 <input type="hidden" name="iid" id="iid" value="<?php echo $invoice['iid']; ?>">
                              </div>
                              </div>
                              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Sales Order Validity To') ?></label>
                              <div class="input-group">
                                 <input type="date" class="form-control required"
                                    name="invocieduedate" id="invocieduedate"
                                    placeholder="Validity Date" autocomplete="false"
                                    value="<?php echo $invoice['invoiceduedate'] ?>" readonly>
                              </div>
                           </div>
                           <!-- <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="taxformat" class="col-form-label">Tax</label>
                              <select class="form-control" onchange="changeTaxFormat(this.value)"
                                 id="taxformat">
                              <?php echo $taxlist; ?>
                              </select>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="discountFormat" class="col-form-label">Discount</label>
                                 <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                    id="discountFormat">
                                 <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                 <?php echo $this->common->disclist() ?>
                                 </select>
                           </div> -->
                           
                           
                           <?php
                           if($invoice["converted_status"]=='3')
                           {
                           ?>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label">Assigned Warehouse</label>
                              <br><strong><?=$warehouse_details?></strong>
                           </div>
                           <?php
                           }
                           ?>
                           
                           <div class="col-xl-5 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label">Sales Order Note</label>
                              <textarea class="form-textarea" name="notes" id="salenote" rows="2" readonly style="background:#dddddd !important;"><?php echo $invoice['notes'] ?></textarea>
                           </div>

                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label">Customer Message</label>
                              <textarea class="form-textarea" name="proposal" id="salenote"
                                 rows="2" readonly style="background:#dddddd !important;"><?php echo $invoice['proposal'] ?></textarea>
                           </div>

                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group row d-none">
                  <div class="col-sm-12">
                     <label for="toAddInfo" class="col-form-label">Proposal Message</label>
                     <textarea class="summernote" name="propos" id="contents"
                        rows="2"><?php echo $invoice['proposal'] ?></textarea>
                  </div>
               </div>
               
               <div id="saman-row">
                  <?php 
                  $dis_clss="";
                  if($invoice["converted_status"]=='3')
                  {
                     $dis_clss="disable-class";
                  }?>
                  <div class="row">
                     <div class="col-lg-1">
                        <button class="btn btn-sm btn-primary btn-crud <?=$dis_clss?> mt-2" type="button" name="writeoff_Btn" id="writeoff_Btn"><i class="fa fa-refresh"></i> <?php echo $this->lang->line('Write Off'); ?></button>
                     </div>
                     <div class="col-lg-11">
                        <?php if($invoice['converted_status']!=3){ ?>
                           <div class="creditlimit-check"></div>
                        <?php } ?>
                     </div>
                  </div>

                 
                  <div class="table-table-scroll1">
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                           <th width="2%" style="padding-left:10px;"><input type="checkbox"  id="prdcheckbox" name="prdcheckbox"></th>
                           <th width="10%" class="text-center1 pl-1">Item No.</th>
                           <th width="22%" class="text-center1 pl-1">Item Name</th>
                           <th width="8%" class="text-center">Ordered Qty</th>
                           <th width="8%" class="text-center">Delivered Qty</th>
                           <th width="8%" class="text-center">Remaining Qty</th>
                           <th width="8%" class="text-center">Writeoff Qty</th>
                           <th width="8%" class="text-center">On Hand</th>
                           <th width="10%" class="text-right">Rate</th>
                           <?php 
                              if($configurations['config_tax']!='0'){  ?>
                                 <th width="10%" class="text-center">Tax</th>
                           <?php } ?>
                           <th width="5%" class="text-right">Unit Cost</th>
                           <th width="15%" class="text-center">Status</th>
                           <th width="5%" class="text-right">Total Discount</th>
                           <th width="10%" class="text-right">
                              Amount(<?php echo $this->config->item('currency'); ?>)
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i = 0;
                           $unicost = 0;
                           $totaldiscount = 0;
                           $totaldiscount = 0;
                           $grandtotal = 0;
                           $subtotal =0;
                           foreach ($products as $row) {
                              if(intval($row['del_remaining_qty']>0))
                              {
                                 $unicost = ($row['subtotal']>0) ?round($row['subtotal'] / $row['del_remaining_qty'], 2):0;
                              }
                              else{
                                 $unicost = ($row['subtotal']>0) ?round($row['subtotal'] / $row['qty'], 2):0;
                              }
                              
                              $grandtotal = $grandtotal + $row['subtotal'];
                               if($row['totalQty']<=$row['alert']){
                                   echo '<tr style="background:#ffb9c2;">';
                               }
                               else{
                                   echo '<tr >';
                               }

                              if($row['prdstatus']==1){
                                 $chkbx = "--";
                                 $prdstatus1 = '<span class="st-Closed">Completed</span>';
                              }
                              else{
                                 $prdstatus1 = '<span class="st-partial">Not Completed</span>';
                                 $chkbx = '<input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'">';
                              }
                              echo '<td width="2%">'.$chkbx.'</td>';
                               
                              echo '<td><strong>'.$row['code'].'</strong></td>';
                               echo '<td><strong>' . $row['product'] . '</strong><input type="hidden" class="form-control" name="product_name[]" id="productname-' . $i . '" placeholder="'.$this->lang->line('Enter Product name').'"  value="' . $row['product'] . '"> </td>';
                              //  echo '<td><input type="text" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '"><strong id="productcode">'.$row['product_code'].'</strong></td>';
                               
                              echo '<td class="text-center"><input type="hidden" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount1-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . amountFormat_general($row['discount']) . '"><strong id="onhandQty-'.$i.'">'.intval($row['qty']).'</strong><input type="hidden" class="form-control req amnt" name="product_qty[]" id="amount1-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                              autocomplete="off" value="' . amountFormat_general($row['qty']) . '" ><input type="hidden" name="old_product_qty[]" value="' . amountFormat_general($row['qty']) . '" ></td>';
                              
                              $rem_qty = (intval($row['write_off_quantity'])>0 || intval($row['del_remaining_qty']>0)) ? intval($row['del_remaining_qty']) : intval($row['qty']);

                              echo '<td class="text-center"><strong>'.intval($row['del_transfered_qty']).'</strong></td>';
                              echo '<td class="text-center"><strong>'.$rem_qty.'</strong></td>';
                              echo '<td class="text-center"><strong>'.intval($row['write_off_quantity']).'</strong></td>'; 
                              echo '<td class="text-center"><strong id="onhandQty-'.$i.'">'.intval($row['totalQty']).'</strong>&nbsp; <button onclick="single_product_stock(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary"  title="Stock List"><i class="fa fa-info"></i></button></td>
                              <td class="text-right"><strong>' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong><input type="hidden" class="form-control1 req prc remove-textstyle" name="product_price[]" id="price1-' . $i . '"
                              onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                              autocomplete="off" value="' . amountExchange_s($row['price'], $invoice['multi'], $this->aauth->get_user()->loc) . '" ></td>';
                              if($configurations["config_tax"]!="0"){             
                                 echo '<td> <input type="text" class="form-control vat" name="product_tax[]" id="vat-' . $i . '"
                                 onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()"
                                 autocomplete="off"  value="' . amountFormat_general($row['tax']) . '"></td>';

                                 echo '<td class="text-center" id="texttaxa-' . $i . '">' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</td>';
                              }

                              
                             
                              echo '<td class="text-right"><strong>'.$unicost.'</strong></td>';
                              echo '<td class="text-center">'.$prdstatus1.'</td>';
                              echo '<td class="text-right"><strong>'.$row['totaldiscount'].'</strong></td>';
                              echo '<td class="text-right">
                              <strong><span class="ttlText" id="result-' . $i . '">' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '</span></strong></td>';
                              echo '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                              <input type="hidden" name="disca[]" id="disca1-' . $i . '" value="' . amountExchange_s($row['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                              <input type="hidden" class="ttInput" name="product_subtotal[]" id="total1-' . $i . '" value="' . amountExchange_s($row['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) . '">
                              <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['pid'] . '">
                              <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['code'] . '">
                              </tr>';
                              // echo '<tr class="desc_p1"><td colspan="11"><textarea id="dpid-' . $i . '" class="form-control" name="product_description[]" placeholder="Enter Product description" autocomplete="off">' . $row['product_des'] . '</textarea></td></tr>';
                               $i++;
                               $totaldiscount = $totaldiscount + $row['totaldiscount'];
                               $subtotal  += $row['price'] * $rem_qty;
                           } ?>
                        <!-- <tr class="last-item-row sub_c tr-border">
                           <td class="add-row no-border">
                              <button type="button" class="btn btn-secondary" id="addproduct_salesedit">
                              <i class="fa fa-plus-square"></i> Add Row
                              </button>
                           </td>
                           <td colspan="7" class="no-border"></td>
                        </tr> -->
                        <!-- erp2024 changed colspan 6 to 8 06-06-2024 -->
                         <?php if($configurations["config_tax"]!="0"){    ?>
                           <tr class="sub_c" style="display: table-row;">
                              <td colspan="7" align="right" class="no-border"><strong><?php echo $this->lang->line('Total Tax'); ?></strong></td>
                              <td align="left" colspan="2" class="no-border"><span
                                 class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                 <span id="taxr"
                                    class="lightMode"><?php echo amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
                              </td>
                           </tr>
                        <?php } 
                           $granddiscount = $totaldiscount + $invoice['order_discount'];
                        ?>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Subtotal'); ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                           <td align="right" colspan="2" class="no-border">
                              <span 
                                 class="lightMode"><?php echo number_format($subtotal, 2); ?></span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Total Product Discount'); ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                           <td align="right" colspan="2" class="no-border">
                              <span id="discs"
                                 class="lightMode"><?php echo number_format($totaldiscount, 2); ?></span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Order Discount'); ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                           <td align="right" colspan="2" class="no-border">
                              <input type="hidden" name="order_discount" value="<?=$invoice['order_discount']?>">
                              <span  class="lightMode"><?php echo number_format($invoice['order_discount'], 2); ?></span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="12" align="right" class="no-border"><strong><?php echo $this->lang->line('Total Discount'); ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                           <td align="right" colspan="2" class="no-border">
                              <span  class="lightMode"><?php echo number_format($granddiscount, 2); ?></span>
                           </td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row; ">
                           <td colspan="6" align="right" class="no-border"><input type="hidden"
                              value="<?php echo amountExchange_s($invoice['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) ?>"
                              id="subttlform" name="subtotal"><strong><?php echo $this->lang->line('Shipping'); ?></strong></td>
                           <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                              onkeypress="return isNumber(event)" placeholder="Value" name="shipping"
                              autocomplete="off" onkeyup="billUpyog()"
                              value="<?php if ($invoice['ship_tax_type'] == 'excl') {
                                 $invoice['shipping'] = $invoice['shipping'] - $invoice['ship_tax'];
                                 }
                                 echo amountExchange_s($invoice['shipping'], $invoice['multi'], $this->aauth->get_user()->loc); ?>">(
                              <?= $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                              <span
                                 id="ship_final"><?= amountExchange_s($invoice['ship_tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?>
                              </span>
                              )
                           </td>
                        </tr>
                        <!-- erp2024 changed colspan 6 to 8 06-06-2024 ends -->
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="7" class="no-border">
                              <?php if ($exchange['active'] == 1){
                                 echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                              <select name="mcurrency" class="selectpicker form-control">
                              <?php
                                 echo '<option value="' . $invoice['multi'] . '">Do not change</option><option value="0">None</option>';
                                 foreach ($currency as $row) {
                                 
                                     echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                 } ?>
                              </select><?php } ?>
                           </td>
                           <!-- erp2024 changed colspan 4 to 6 06-06-2024 -->
                           <td colspan="5" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Grand Total') ?>
                              (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                           </td>
                           <td align="right" colspan="2" class="no-border">
                              <span id="grandtotaltext"><?=number_format($invoice['subtotal'],2)?></span>

                              <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?=$invoice['subtotal']?>" readonly="">
                              <!-- <input type="text" name="total" class="form-control" id="invoiceyoghtml"  value="<?= amountExchange_s($invoice['total'], $invoice['multi'], $this->aauth->get_user()->loc); ?>" readonly=""> -->
                           </td>
                           <!-- erp2024 changed colspan 4 to 6 06-06-2024 ends-->
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <!-- // erp2024 removed 06-06-2024 -->
                           <!-- <td colspan="2">Payment Terms <select name="pterms"
                              class="selectpicker form-control">
                              <?php //echo '<option value="' . $invoice['termid'] . '">*' . $invoice['termtit'] . '</option>';
                                 // foreach ($terms as $row) {
                                 //     echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                 // } ?>
                              
                              
                              </select></td> -->
                           <!-- // erp2024 removed 06-06-2024 ends -->
                           <?php if((($invoice["converted_status"]=='0' || $invoice["completed_status"]=='1') || ($invoice["converted_status"]=='2')) && ($invoice["converted_status"]!='3')){ ?>
                              <td  colspan="13" class="no-border">
                                 <div class="row">
                                    <div class="col-7"></div>
                                    <div class="col-3">
                                       <label for="s_warehouses" class="col-form-label1"><?php echo $this->lang->line('Sale Point') ?><span class="compulsoryfld"> *</span></label>
                                       <select id="s_warehouses" class="selectpicker form-control">
                                       <?php 
                                          echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                          <?php foreach ($warehouse as $row) {
                                          echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                          } ?>
                                       </select>
                                    </div>
                                    <div class="col-2 text-right">
                                       <?php
                                          $disableclass="";
                                          if($prdstatus==1)
                                          {
                                              $disableclass="disable-class";
                                          }
                                       ?>
                                       <a  href="javascript:void(0)" class='btn btn-crud btn-lg btn-primary sub-btn breaklink <?=$disableclass?>' onclick='confirmRedirect("<?php echo $invoice["iid"]; ?>")'><?php echo $this->lang->line('Assign for Delivery'); ?> </a>

                                    </div>
                                 </div>
                              </td>
                           <?php } ?>
                        </tr>
                     </tbody>
                  </table>
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
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <input type="submit" id="mclient_add" class="btn btn-secondary submitBtn" value="ADD" />
            </div>
         </form>
      </div>
   </div>
</div>

<!-- ============================================== -->
<div id="write_off_model" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Write Off') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- ======================================================= -->
                <form  method="post" id="write_off_form">
                        <div class="container-fluid" id="table-potion"></div>                        
                </form>
                <!-- ======================================================= -->
            </div>
            
        </div>
    </div>
</div>
    <!-- =================================History section=========================== -->
    <!-- <button class="history-expand-button">
                           <span>History</span>
                           </button>

                           <div class="history-container">
                            <button class="history-close-button">
                                <span>Close</span>
                            </button>
                           <h2>History</h2>
                           <form>
                           <table id="logtable" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                        <thead>
                        <tr>         
                            <th><?php echo "#" ?></th>
                            <th><?php echo $this->lang->line('Action_performed') ?></th>  
                            <th><?php echo $this->lang->line('IP address')?></th>
                            <th><?php echo $this->lang->line('Performed By') ?></th>
                            <th><?php echo $this->lang->line('Performed At')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                           foreach ($log as $row) { ?>
                          <tr>    
                            <td><?php echo $i?></td>
                            <td><?php echo $row['action_performed']?></td>
                            <td><?php echo $row['ip_address']?></td>
                            <td><?php echo $row['name']?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
                          </tr>
                  
                          <?php $i++; } ?>
                        </tbody>
                           </table>
                           </div> -->
               <!--     erp2025 add 06-01-2025   Detailed hisory starts-->
               <button class="history-expand-button">
                    <span>History</span>
                </button>

                <div class="history-container">
                   <button class="history-close-button">
                     <span>Close</span>
                    </button>
                    <span>X</span>
                    <h2>History</h2>
                    <button class="logclose-btn">
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
                        foreach ($groupedSalesorders as $seqence_number => $Salesorders){
                        $flag=0;
                        ?>              
                            <tr>
                               <td>        
                                <?php    foreach ($Salesorders as $Salesorder) {
                                if($flag==0)
                                {?>
                                <div class="userdata">
                                <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$Salesorder['picture'])?>' style="width:50px; height:50px;" ?>
                                <?php  echo $Salesorder['name'];
                                        $flag=1;
                                } ?>
                                </div>           
                                    <ul><li>  <?php echo $Salesorder['old_value'];?> > <b><span class="newdata"><?php echo $Salesorder['new_value']?></span></b> (<?php if($Salesorder['field_label']==""){echo $Salesorder['field_name'];}else{echo $Salesorder['field_label'];}?>)
                                    </li></ul>
                                    <?php } ?>
                                </td>               
                                <td><?php echo date('d-m-Y H:i:s', strtotime($Salesorder['changed_date'])); ?></td>
                                <td><?php echo $Salesorder['ip_address']?></td> 
                                
                            </tr>  
                            <?php 
                            $i++; 
                          
                        }?>
                        </tbody>
                    </table>

                    </form>
                </div>   
              <!--     erp2025 add 06-01-2025   Detailed hisory ends-->
                <!-- =========================History End=================== -->   
<!-- ============================================== -->

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
      credit_limit_with_grand_total();
      $('[data-toggle="popover"]').popover(); 
      $(document).on('click', function(e) {
         if (!$(e.target).closest('[data-toggle="popover"], .popover').length) {
               $('[data-toggle="popover"]').popover('hide');
         }
      });
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
   

       //erp2024 new code for matrial request screen 07-06-2024 ends
       //erp2024 new code for purchase request screen 18-06-2024 starts
       $('#PurchaseRequest').click(function() {
           var selectedProducts = [];
           $('.checkedproducts:checked').each(function() {
               selectedProducts.push($(this).val());
           });
           if (selectedProducts.length === 0) {
               Swal.fire({
                text: "Please select at least one product",
                icon: "info"
              });
               return;
           }
   
           if (selectedProducts.length > 0) {
               var form = $('<form action="<?php echo site_url('Productrequest/purchaserequest')?>" method="POST" ></form>');
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
               Swal.fire({
                text: "Please select at least one product",
                icon: "info"
              });
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

function confirmRedirect(invoiceId) {
    var s_warehouses = $('#s_warehouses').val();
    var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
    var available_credit_limit = parseFloat($("#avalable_credit_limit").val().replace(/,/g, '').trim());
    if (isNaN(total) || isNaN(available_credit_limit)) {
        
    } else if (total > available_credit_limit) {
      Swal.fire({
            icon: 'error',
            title: 'Credit Limit Exceeded',
            text: 'The Grand Total Amount exceeds the Available Credit Limit. Please review.',
        });
        return;
    }
    if (s_warehouses === null || s_warehouses === '') {
        Swal.fire({
            icon: 'error',
            title: 'Sale Point',
            text: 'Please select a Warehouse/Shop before proceeding!',
        });
        return;
    }
    if (s_warehouses === null || s_warehouses === '') {
        Swal.fire({
            icon: 'error',
            title: 'Sale Point',
            text: 'Please select a Warehouse/Shop before proceeding!',
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "Convert this sales order to a delivery note?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'No, Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurl + 'SalesOrders/insert_delivery_note_from_sales_order', 
                type: 'POST',
                data: {
                    salesorder_id: $('#salesorder_id').val()
                },
                success: function(response) {
                      window.location.href = baseurl + 'DeliveryNotes';
                },
                error: function(xhr, status, error) {
                  //   console.error("AJAX Error:", error);
                  //   Swal.fire({
                  //       icon: 'error',
                  //       title: 'Error',
                  //       text: 'An error occurred during the AJAX request.'
                  //   });
                }
            });
        }
    });
}


   $('#s_warehouses').change(function() {
        var warehouseId = $(this).val();
        var invocienoId = $('#iid').val();

        $.ajax({
            url:  baseurl + 'SalesOrders/update_order_warehouse', 
            type: 'POST',
            data: {
                invocieno_id: invocienoId,
                store_id: warehouseId
            },
            success: function(response) {
               //  swal.fire({
               //      title: 'Success!',
               //      text: 'Warehouse ID has been updated.',
               //      icon: 'success',
               //      confirmButtonText: 'OK'
               //  });
            },
            error: function(xhr, status, error) {
                
            }
        });
    });

    //erp2024 new code for write off request screen 06-09-2024 ends

    $('#writeoff_Btn').click(function() {
        var salesorder_id = $("#salesorder_id").val();
        var selectedProducts = [];
        $('.checkedproducts:checked').each(function() {
            selectedProducts.push($(this).val());
        });
        if (selectedProducts.length === 0) {
            Swal.fire({
            text: "Please select at least one product",
            icon: "info"
            });
            return;
        }
        $.ajax({
            url: baseurl + 'SalesOrders/write_off',
            dataType: 'json',
            method: 'POST',
            data: {
                'selectedProducts': selectedProducts,
                'salesorder_id' : salesorder_id
            },
            success: function(data) {
                $("#table-potion").html(data.table);
                $('#write_off_model').modal('show');              
            }
        });
        
    });


    
    function write_off_btn_click() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to proceed with the write-off operation?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel',
        focusCancel: true,
        reverseButtons: true  // This reverses the order of buttons
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with AJAX request if the user confirms
            $('#write_off_submit_btn').prop('disabled', true);
            hasUnsavedChanges = false;

            var form = $('#write_off_form')[0];
            var formData = new FormData(form);

            $.ajax({
                url: baseurl + "SalesOrders/write_off_action",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var data = JSON.parse(response);

                    // Re-enable the submit button and hide the modal on success
                    $('#write_off_submit_btn').prop('disabled', false);
                    if (data.status === 'Success') {
                        $('#write_off_model').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while generating the material request', 'error');
                    console.log(error); // Log any errors
                    $('#write_off_submit_btn').prop('disabled', false);
                }
            });
        }
    });
}

    
function washoutqty_validate(i) {
   var remqty = parseInt($("#del_rem_qty" + i).val());  // Convert to number
   var write_off_quantity = parseInt($("#write_off_quantity" + i).val());  // Convert to number
   var actualqty = remqty - write_off_quantity;
   if (write_off_quantity > remqty) {
      Swal.fire({
            text: "Write-off quantity (" + write_off_quantity + ") is greater than the remaining quantity (" + remqty + ")",
            icon: "info"
      });
      
      $("#write_off_quantity" + i).val("");  // Clear the input field
      return; 
   }
   $("#amount-" + i).val(actualqty);
}

function credit_limit_with_grand_total() {
    var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
    var available_credit_limit = parseFloat($("#avalable_credit_limit").val().replace(/,/g, '').trim());
    if (isNaN(total) || isNaN(available_credit_limit)) {
        textdata = '<div class="alert alert-warning">Invalid numbers. Please check the values again.</div>';
    } else if (total > available_credit_limit) {
        textdata = '<div class="alert alert-danger mb-0">The Grand Total Amount exceeds the Available Credit Limit. Please review.</div>';
        $(".avail_creditlimit").addClass('text-danger');
    } else {
        textdata = '<div class="alert alert-success mb-0">The Available Credit Limit is sufficient for the Grand Total Amount. Please procced.</div>';
        $(".avail_creditlimit").removeClass('text-danger');
    }

    $("#creditlimit-check").html(textdata);
}
</script>