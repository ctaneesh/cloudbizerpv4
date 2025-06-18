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
                  <div class="col-3"></div>
                  <div class="col-3">
                    <?php
                    $text_color = 'alert-success';
                    $creditlimit_class ="";
                    if($masterdetails['salesorders_status']=='deleted' || $masterdetails['salesorders_status']=='invoiced'){
                        $text_color = 'alert-danger';
                        $creditlimit_class = 'd-none';
                    }
                    ?>
                    <div class="alert <?=$text_color?>"> Current Status : <?=ucfirst($masterdetails['salesorders_status'])?></div>                                    
                  </div>
                  <div class="col-6"></div>

                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                     <div id="customerpanel" class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="fcol-sm-12">
                                 <h3 class="title-sub">
                                    <?php echo $this->lang->line('Customer Details') ?> 
                           </div>
                           <div class="frmSearch col-sm-12"><label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></label>
                                 <input type="text" class="form-control" name="cst" id="customer-box"
                                       placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>"
                                       autocomplete="off"/>

                                 <div id="customer-box-result"></div>
                           </div>

                        </div>
                        <div id="customer">
                           <div class="clientinfo">
                                 <?php echo $this->lang->line('Client Details') ?>
                                 <hr>
                                 <input type="hidden" name="customer_id" id="customer_id" value="<?=$masterdetails['cid']?>">
                                 <div id="customer_name"><strong><?=$masterdetails['name']?></strong></div>
                           </div>
                           <div class="clientinfo">
                                 <div id="customer_address1"><strong><?=$masterdetails['address']?></strong></div>
                           </div>

                           <div class="clientinfo">
                                 <div type="text" id="customer_phone">Phone : <strong><?=$masterdetails['phone']?></strong><br>Email : <strong><?=$masterdetails['email']?></strong><br>
                                    Company Credit Limit  : <strong><?=number_format($masterdetails['credit_limit'],2)?></strong><br>
                                    Credit Period  : <strong><?=$masterdetails['credit_period']?></strong> Days<br><br>
                                    <strong>Available Credit Limit  : <?=number_format($masterdetails['avalable_credit_limit'],2)?></strong>
                                    <input type="hidden" id="avalable_credit_limit" name="avalable_credit_limit" value="<?=$masterdetails['avalable_credit_limit']?>">
                                    <input type="hidden" id="available_credit" name="available_credit" value="<?=$masterdetails['avalable_credit_limit']?>">  
                                </div>
                           </div>
                          
                           <hr>
                           <div id="customer_pass"></div>
                           
                        </div>


                     </div>
                  </div>
                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                    <?php
                        $disableclass = ($masterdetails['salesorders_status']=='deleted' || $masterdetails['salesorders_status']=='invoiced') ? 'disable-class' : '';
                        $saleorder_number = ($masterdetails['salesorder_number']) ? $masterdetails['salesorder_number'] : $masterdetails['tid'];
                    ?>
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
                                 <input type="text" class="form-control" placeholder="Sales Order #" name="invocieno" id="invocienoId"
                                    value="<?php echo $saleorder_number; ?>" readonly>
                                 <input type="hidden" class="form-control"  name="salesorder_id" id="salesorder_id"
                                    value="<?php echo $salesorder_id; ?>" readonly>
                              </div>
                           </div>
                           <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference');?> <span class="compulsoryfld"> *</span></label>
                              
                                 <input type="text" class="form-control required" placeholder="<?php echo $this->lang->line('Quote Reference')?>" name="refer" id="refer" value="<?php echo $masterdetails['refer']; ?>" data-original-value="<?php echo $masterdetails['refer']; ?>">
                           </div>
                           <!--erp2024 newly added 29-09-2024  -->
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                 <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number"  value="<?php echo $masterdetails['customer_reference_number']; ?>" data-original-value="<?php echo $masterdetails['customer_reference_number']; ?>">
                                 </div>                                    
                           </div>
                           <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                 <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person"   value="<?php echo $masterdetails['customer_contact_person']; ?>" data-original-value="<?php echo $masterdetails['customer_contact_person']; ?>">
                                 </div>                                    
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                 <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Contact Person Number" value="<?php echo $masterdetails['customer_contact_number']; ?>" data-original-value="<?php echo $masterdetails['customer_contact_number']; ?>">
                                 </div>                                    
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                 <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email"  value="<?php echo $masterdetails['customer_contact_email']; ?>" data-original-value="<?php echo $masterdetails['customer_contact_email']; ?>">
                                 </div>                                    
                           </div>
                           <!--erp2024 newly added 29-09-2024 ends -->
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="Purchase Order" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." No.";?> <span class="compulsoryfld"> *</span></label>
                                 <input type="text" class="form-control required" placeholder="<?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order');?>" name="customer_purchase_order" id="customer_purchase_order" value="<?php echo $masterdetails['customer_purchase_order']; ?>" data-original-value="<?php echo $masterdetails['customer_purchase_order']; ?>">
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Customer')." ".$this->lang->line('Purchase Order')." ".$this->lang->line('Date');?><span class="compulsoryfld"> *</span></label>                           
                                 <input type="date" class="form-control required" name="customer_order_date" id="customer_order_date" placeholder="Order Date" autocomplete="false"  max="<?=date('Y-m-d')?>" value="<?php echo $masterdetails['customer_order_date']; ?>" data-original-value="<?php echo $masterdetails['customer_order_date']; ?>" title="customer order date">
                           </div>
                           
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                 <label for="invocieduedate" class="col-form-label"><?php echo  $this->lang->line('Delivery Deadline'); ?> <span class="compulsoryfld">*</span></label>
                                 <input type="date" class="form-control required" name="invocieduedate" id="invocieduedate"  placeholder="Validity Date" autocomplete="false" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $masterdetails['invoiceduedate']; ?>" data-original-value="<?php echo $masterdetails['invocieduedate']; ?>">
                           </div>

                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="invociedate" class="col-form-label">Sales Order Date</label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-calendar4"
                                    aria-hidden="true"></span></div>                                
                              </div>
                           </div>
                              <input type="hidden" class="form-control required" placeholder="Billing Date" name="invoicedate" id="invoicedate"  autocomplete="false"  value="<?php echo $masterdetails['invoicedate']; ?>" data-original-value="<?php echo $masterdetails['invoicedate']; ?>">
                                 <input type="hidden" name="iid" value="<?php echo $masterdetails['id']; ?>" >
                           
                           
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
                           

                           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <label for="Sales Order Note" class="col-form-label"><?=$this->lang->line('Sales Order Note') ?></label>
                              <textarea class="form-textarea" name="notes" id="salenote" data-original-value="<?php echo $masterdetails['notes']; ?>" title="Sales Order Note"><?php echo $masterdetails['notes']; ?></textarea>
                           </div>
                           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <label for="Customer Message" class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?> </label>
                              
                              <textarea class="form-textarea" name="propos" id="contents" rows="2" data-original-value="<?php echo $masterdetails['proposal']; ?>" title="Customer Message"><?php echo $masterdetails['proposal']; ?></textarea>
                              <!-- <textarea class="form-textarea" name="propos" id="contents" rows="2"><?php echo $invoice['proposal'] ?></textarea> -->
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none1">
                              <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Sale Point') ?></label>
                              <select id="s_warehouses" class="form-control" name="store_id">
                              <?php //echo $this->common->default_warehouse();
                                 echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                 <?php foreach ($warehouse as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>
                           
                        </div>
                     </div>
                  </div>
                   <!-- Image upload sections starts-->
                   <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                        <label for="cst" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
                                        <div class="row">                            
                                            <div class="col-8">
                                                <div class="d-flex">
                                                    <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                    <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                    <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn <?=$disableclass?>" style="height:30px; height:30px; margin:3px;"><i class="fa fa-trash" ></i></button>
                                                </div>
                                                <div id="uploadsection"></div>                                                
                                            </div>                        
                                            <div class="col-4">
                                                    <button class="btn btn-crud btn-secondary btn-sm mt-1 <?=$disableclass?>" id="addmore_img" type="button"><i class="fa fa-plus-circle"></i> Add More</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Image upload sections ends -->
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                     <!-- ===== Image sections starts ============== -->
                                    <div class="container-fluid">
                                        <div class="mt-2">
                                            
                                            <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12"> -->
                                                <?php 
                                            $imgcontains = 0;
                                            if (!empty($images)) {
                                                echo '<table class="table table-striped table-bordered table-responsive">';
                                                $imgcontains = 1;
                                            
                                                foreach ($images as $image) {
                                                    $file_extension = strtolower(pathinfo($image['file_name'], PATHINFO_EXTENSION));
                                                    $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png']);
                                                    $file_url = base_url("uploads/{$image['file_name']}");
                                                    $img_tag = $is_image ? "<img src='{$file_url}' class='img-thumbnail' alt='{$image['actual_name']}' style='width:70px; height:70px;'>" : '<i class="fa fa-file-code-o fsize-70"></i>';
                                                    $download_attr = $is_image ? 'download' : '';
                                                    $icon = "Click to download <i class='fa fa-download'></i><br>";
                                                    $imgname = $image['actual_name'];
                                            
                                                    if ($imgcontains % 5 == 1) {
                                                        echo '<tr>';
                                                    }
                                            
                                                    echo "<td class='text-center file-td-section'>";
                                                    echo "<div class='file-section'>";
                                                    echo $img_tag ? "{$img_tag}" : '';
                                                    echo '<p>'.$imgname.'</p>';
                                                    echo "<a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary $disableclass'>{$icon}</a>&nbsp;";
                                                    echo "<button class='btn btn-crud btn-sm btn-secondary $disableclass' onclick=\"deleteitem('{$image['id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
                                                    echo "</div>";
                                                    echo "";
                                                    echo "</td>";
                                            
                                                    if ($imgcontains % 5 == 0) {
                                                        echo '</tr>';
                                                    }
                                            
                                                    $imgcontains++;
                                                }
                                            
                                                // Close the last row if it wasn't closed
                                                if (($imgcontains - 1) % 5 != 0) {
                                                    echo '</tr>';
                                                }
                                            
                                                echo '</table>';
                                            }
                                            ?>

                                        </div>
                                    </div>
                                    <!-- ===== Image sections ends ============== -->

                                </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-1">
                            <button class="btn btn-crud btn-sm btn-primary mt-2 <?=$disableclass?>" type="button" name="writeoff_Btn" id="writeoff_Btn"><i class="fa fa-refresh"></i> <?php echo $this->lang->line('Write Off'); ?></button>
                        </div>
                        <div class="col-lg-11">                             
                                <div  class="creditlimit-check <?=$creditlimit_class?>"></div>
                        </div>
                    </div>

               
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
                                <th width="1%" style="padding-left:10px;"><input type="checkbox"  id="prdcheckbox" name="prdcheckbox"></th>
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
                            <?php
                             $i=0;
                            if($products)
                            {
                                $grandtotal = 0;
                                $totaldiscount=0;
                                $nettotal = 0;
                                
                                foreach ($products as $key => $row) {
                                    $product_name_with_code = $row['product'].'('.$row['code'].') - ';
                                    $totaldiscount += $row['totaldiscount'];
                                    $nettotal += $row['subtotal'];
                                    $writeoff_qty = intval($row['write_off_quantity']);
                                    $quantity = intval($row['qty'])-(intval($row['write_off_quantity']) +(intval($row['del_delivered_qty'])));                                    
                                    $grandtotal += intval($quantity)*$row['price'];
                                    if($row['prdstatus']==1){
                                        $chkbx = "--";
                                        $writeoff_complete="readonly";
                                        $writeoff_class="disable-class";
                                        // $prdstatus1 = '<span class="st-Closed">Completed</span>';
                                     }
                                     else{
                                        $writeoff_complete="";
                                        $writeoff_class="";
                                        // $prdstatus1 = '<span class="st-partial">Not Completed</span>'; rowTotal
                                        $chkbx = '<input type="checkbox" class="checkedproducts" name="product_id[]" value="'.$row['pid'].'" id="prd-'.$row['pid'].'">';
                                     }
                                   ?>
                                    <tr>
                                        <td width="1%"><?=$chkbx?></td>
                                        <td><input type="text" class="form-control" name="code[]" id='code-<?=$i?>' value="<?=$row['code']?>" title="<?=$product_name_with_code?>Code" <?=$writeoff_complete?>></td>
                                            <td><input type="text" class="form-control required" name="product_name[]" <?=$writeoff_complete?> required
                                                    placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                                    id='productname-<?=$i?>' title="<?=$product_name_with_code?>Product" value="<?=$row['product']?>">
                                            </td>
                                            <td class="text-center"><input type="text" class="form-control req amnt " name="product_qty[]" <?=$writeoff_complete?> id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal(<?=$i?>), billUpyog(),orderdiscount()"  autocomplete="off" value="<?=$quantity?>" title="<?=$product_name_with_code?>Quantity"></td>
                                            <td class="text-center"><strong id="onhandQty-<?=$i?>"><?=$row['totalQty']?></strong></td>
                                            <td class="text-right">    
                                                <strong id="pricelabel-<?=$i?>"><?=$row['price']?></strong>
                                                <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>"onkeypress="return isNumber(event)" onkeyup="rowTotal(<?=$i?>), billUpyog(), orderdiscount()" autocomplete="off" value="<?=$row['price']?>"></td>
                                            <td class="text-right">
                                                <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-<?=$i?>" onkeypress="return isNumber(event)" autocomplete="off" value="<?=$row['lowest_price']?>">
                                                <strong id="lowestpricelabel-<?=$i?>"><?=$row['lowest_price']?></strong>
                                            </td>
                                            <?php //Verify that tax is enabled 0
                                            if($configurations['config_tax']!='0'){ ?>           
                                                    <td class="text-center">
                                                        <div class="text-center">                                                
                                                            <input type="hidden" class="form-control" name="product_tax[]" id="vat-<?=$i?>"
                                                                onkeypress="return isNumber(event)" onkeyup="rowTotal(<?=$i?>), billUpyog(), orderdiscount()"
                                                                autocomplete="off">
                                                                <strong id="taxlabel-<?=$i?>"></strong>&nbsp;<strong  id="texttaxa-<?=$i?>"></strong>
                                                        </div>
                                                    </td>
                                            <?php } ?>
                                            <td class="text-center"><strong id='maxdiscountratelabel-<?=$i?>'><?=$row['max_disrate']?></strong>
                                            <input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-<?=$i?>"  value="<?=$row['max_disrate']?>"></td>
                                        
                                            <td class="text-center">
                                                <div class="input-group text-center">
                                                    <select name="discount_type[]" id="discounttype-<?=$i?>" class="form-control <?=$writeoff_class?>" onchange="discounttypeChange(<?=$i?>),orderdiscount()" <?=$writeoff_complete?>>
                                                        <option value="Perctype" <?php if($row['max_disrate']=="Perctype"){ echo "selected"; } ?>>%</option>
                                                        <option value="Amttype" <?php if($row['max_disrate']=="Amttype"){ echo "selected"; } ?>>Amt</option>
                                                    </select>&nbsp;
                                                    <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange(<?=$i?>),orderdiscount()"  title="<?=$product_name_with_code?>Discount" value="<?=$row['discount']?>" <?=$writeoff_complete?>>
                                                    <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange(<?=$i?>),orderdiscount()"  title="<?=$product_name_with_code?>Discount" value="<?=$row['discount']?>" <?=$writeoff_complete?>>
                                                </div>  
                                                <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel">Amount : <?=$row['totaldiscount']?>  </strong>
                                                <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                            </td>
                                        
                                            <td class="text-right">
                                                <strong><span class='ttlText' id="result-<?=$i?>"><?=$row['subtotal']?></span></strong></td>
                                            <td class="text-center">
                                                <button onclick='producthistory(<?=$i?>)' type="button" class="btn btn-crud  btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                <button onclick='single_product_details(<?=$i?>)' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                <button type="button" data-rowid="<?=$i?>" class="btn btn-crud btn-sm btn-secondary <?=$disableclass?> removeProd <?=$writeoff_class?>" title="Remove"> <i class="fa fa-trash"></i> </button>
                                            </td>
                                            <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="0">
                                            <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?=$row['totaldiscount']?>">
                                            <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?=$row['subtotal']?>">
                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$row['pid']?>">
                                            <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?=$row['unit']?>">
                                            <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$row['code']?>">
                                        </tr>
                                   <?php
                                   $i++;
                                }
                            }
                            else
                            {
                            ?>

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
                           <?php } ?>

                            <tr class="last-item-row sub_c tr-border">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-crud btn-secondary <?=$disableclass?>"  title="Add product row" id="salesorder_create_btn">
                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                    <!-- <button type="button" class="btn btn-crud btn-secondary <?=$disableclass?>"  title="Add product row" id="sales_create_btn">
                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                    </button> -->
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
                                <td colspan="5" class="no-border"></td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandamount"><?=number_format($grandtotal,2)?></span>
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Product Discount') ?> (<span  class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode"><?=number_format($totaldiscount,2)?></span></td>
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
                                <td align="right" colspan="2" class="no-border">
                                 <input type="number" class="form-control text-right w-50" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off"  onkeyup="orderdiscount()" value="<?=$masterdetails['order_discount']?>" data-original-value="<?php echo $masterdetails['order_discount']; ?>" >
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="5" class="no-border"></td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Net Total') ?>
                                        (<span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <?php
                                        $nettotal = $nettotal - ($masterdetails['order_discount']);
                                    ?>
                                    <span id="grandtotaltext"><?=number_format($nettotal,2)?></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly value="<?=$nettotal?>">

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="4" class="no-border">
                                    <?php
                                    if((empty($masterdetails['quote_id'])) && ($masterdetails['converted_status']=='0' || $masterdetails['converted_status']=='4' || $masterdetails['salesorders_status']='deleted'))
                                    {
                                        echo '<button type="button" class="btn btn-crud btn-lg btn-secondary revert-btncolor creditlimit-btn '.$disableclass.'" id="salesorder-delete-btn">'.$this->lang->line('Delete').'</button>';
                                    }
                                    
                                    $functions = array_column($permissions, "function");                                    
                                    $permission_flg = (in_array("Convert To Delivery Note", $functions)) ? 1 : 0;

                                    ?>
                                </td>
                                <td align="right" colspan="8" class="no-border"> 
                                    <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn creditlimit-btn <?=$disableclass?>" value="<?php echo "Save As Draft" ?>" id="salesorder-draft-btn">
                                    <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn creditlimit-btn <?=$disableclass?>" value="<?php echo $this->lang->line('Update') ?>" id="salesorder-update-btn" data-loading-text="Creating...">
                                    <input type="submit" class="btn btn-crud1 btn-lg btn-secondary sub-btn creditlimit-btn <?=$disableclass?>" value="<?php echo $this->lang->line('Convert To Delivery Note') ?>" id="salesorder-assign-btn" data-loading-text="Creating...">
                                    <?php if($permission_flg==1) { echo '<label class="col-form-label">< OR ></label>'; } ?>
                                    
                                    <input type="submit" class="btn btn-crud1 btn-lg btn-secondary sub-btn creditlimit-btn <?=$disableclass?>" value="<?php echo $this->lang->line('Convert to Invoice') ?>" id="convert-to-invoice-btn" data-loading-text="invoicing...">
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

               <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
               <input type="hidden" value="quote/saleorderaction" id="action-url">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
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

   <!--     erp2025 add 03-02-2025   Detailed hisory starts-->
    <button class="history-expand-button btn-crud">
        <span>History</span>
    </button>

    <div class="history-container">
        <button class="history-close-button">
            <span>Close</span>
        </button>
        
        <button class="logclose-btn"><span>X</span></button>
        <h2>History</h2>
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
<!--     erp2025 add 03-02-2025   Detailed hisory ends-->

<!-- ============================================== -->
<div id="write_off_model" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Write Off') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
<!-- ============================================== -->
<script type="text/javascript">
    const changedFields = {};
   $(document).ready(function() {
        // credit_limit_with_grand_total();
        $("#s_warehouses").prop('required', false).next(".error").remove();
        $(".history-expand-button").on("click", function() {
            $(".history-container").toggleClass("active");
        });
        $(".history-close-button").on("click", function () {
        $(".history-container").removeClass("active");
        });
        $(".logclose-btn").on("click", function () {
        $(".history-container").removeClass("active");
        }); 
        $('#log').DataTable({
            paging: true,      // Enable pagination
            searching: true,   // Enable search bar
            ordering: true,    // Enable column sorting
            info: true,        // Show table information
            lengthChange: true, // Enable changing number of rows displayed
            order: [[1, 'desc']],
        });
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
                    required: function() {
                        return parseInt($("#customer_id").val()) < 1;
                    }
                }
            },
            messages: {
                invocieduedate: "Enter Delivery Deadline",
                customer_purchase_order: "Purchase Order No.",
                customer_order_date: "Purchase Order Date",
                customer_contact_number: "Enter Valid Number",
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

        $('#salesorder-update-btn').on('click', function(e) {
            $("#s_warehouses").prop('required', false).next(".error").remove();
            e.preventDefault(); // Prevent the default form submission
            $('#salesorder-update-btn').prop('disabled', true); // Disable button to prevent multiple submissions
            let isValid = false;
            $(".amnt").each(function () {
                  if (parseFloat($(this).val()) > 0) {
                     isValid = true;
                     return false;
                  }
            });

            if (isValid==false) {
               $('#salesorder-update-btn').prop('disabled', false); 
                  Swal.fire({
                     icon: "error",
                     title: "Invalid Quantity",
                     text: "At least one product quantity must be greater than zero.",
                  });
                  return;
            }
            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 1);                      
                formData.append('changedFields', JSON.stringify(changedFields));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to update this sales order?",
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
                            url: baseurl + 'SalesOrders/saleordereditaction', // Replace with your server endpoint
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
                        $('#salesorder-update-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                
                $('#salesorder-update-btn').prop('disabled', false);
            }
        });

        $('#salesorder-assign-btn').on('click', function(e) {            
            e.preventDefault(); // Prevent the default form submission
            $('#salesorder-assign-btn').prop('disabled', true); // Disable button to prevent multiple submissions
            var salesorder_id = $("#salesorder_id").val();
            let isValid = false;
            
            $(".amnt").each(function () {
                  if (parseFloat($(this).val()) > 0) {
                     isValid = true;
                     return false;
                  }
            });
            if (isValid==false) {
               $('#salesorder-assign-btn').prop('disabled', false); 
                  Swal.fire({
                     icon: "error",
                     title: "Invalid Quantity",
                     text: "At least one product quantity must be greater than zero.",
                  });
                  $('#salesorder-assign-btn').prop('disabled', false);
                  return;
            }
            

            var s_warehouses = $('#s_warehouses').val();
            var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
            var available_credit_limit = parseFloat($("#avalable_credit_limit").val().replace(/,/g, '').trim());
           
            if (isNaN(total) || isNaN(available_credit_limit)) {
                
            }
             else if (total > available_credit_limit) {
            Swal.fire({
                    icon: 'error',
                    title: 'Credit Limit Exceeded',
                    text: 'The Grand Total Amount exceeds the Available Credit Limit. Please review.',
                });
                $('#salesorder-assign-btn').prop('disabled', false);
                return;
            }
            if (s_warehouses === null || s_warehouses === '') {
                // Swal.fire({
                //     icon: 'error',
                //     title: 'Sale Point',
                //     text: 'Please select a Warehouse/Shop before proceeding!',
                // });
                // return;
                $("#s_warehouses").prop('required', true);
                $("#s_warehouses").closest('.form-group').find('.error').remove(); // Remove existing errors
                $("#s_warehouses").after('<em class="error">Please Select a Sale Point.</em>'); // Show error
                $("#s_warehouses").focus();
                $('#salesorder-assign-btn').prop('disabled', false); 
                return false;
            }
  
            
            // Validate the form
            if ($("#data_form").valid()) {    
                       
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 1);                      
                formData.append('changedFields', JSON.stringify(changedFields));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to convert this sales order to delivery note?",
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
                            url: baseurl + 'SalesOrders/convert_salesorder_to_deliverynote', 
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                // window.location.href = baseurl + 'quote/salesorders?id='+salesorder_id; 
                                window.location.href = baseurl + 'DeliveryNotes';
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#salesorder-assign-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                
                $('#salesorder-assign-btn').prop('disabled', false);
            }
        });

        $('#convert-to-invoice-btn').on('click', function(e) {            
            e.preventDefault(); // Prevent the default form submission
            $('#convert-to-invoice-btn').prop('disabled', true); // Disable button to prevent multiple submissions
            var salesorder_id = $("#salesorder_id").val();
            let isValid = false;
            
            $(".amnt").each(function () {
                  if (parseFloat($(this).val()) > 0) {
                     isValid = true;
                     return false;
                  }
            });
            if (isValid==false) {
               $('#convert-to-invoice-btn').prop('disabled', false); 
                  Swal.fire({
                     icon: "error",
                     title: "Invalid Quantity",
                     text: "At least one product quantity must be greater than zero.",
                  });
                  return;
            }
            

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
                $('#convert-to-invoice-btn').prop('disabled', false);
                return;
            }
            if (s_warehouses === null || s_warehouses === '') {
                // Swal.fire({
                //     icon: 'error',
                //     title: 'Sale Point',
                //     text: 'Please select a Warehouse/Shop before proceeding!',
                // });
                // return;
                $("#s_warehouses").prop('required', true);
                $("#s_warehouses").closest('.form-group').find('.error').remove(); // Remove existing errors
                $("#s_warehouses").after('<em class="error">Please Select a Sale Point.</em>'); // Show error
                $("#s_warehouses").focus();
                $('#convert-to-invoice-btn').prop('disabled', false); 
                return false;
            }
  
            
            // Validate the form
            if ($("#data_form").valid()) {    
                       
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 1);                      
                formData.append('changedFields', JSON.stringify(changedFields));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to convert this sales order to an invoice?",
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
                            url: baseurl + 'SalesOrders/convert_salesorder_to_invoice', 
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                window.location.href = baseurl + 'quote/salesorders?id='+salesorder_id; 
                                window.location.href = baseurl + 'invoices/convert_salesorder_to_invoice?id='+salesorder_id;
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#convert-to-invoice-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                
                $('#convert-to-invoice-btn').prop('disabled', false);
            }
        });

        $('#salesorder-draft-btn').on('click', function(e) {
            e.preventDefault();
            $('#salesorder-draft-btn').prop('disabled', true); // Disable button to prevent multiple submissions
            $("#s_warehouses").prop('required', false).next(".error").remove();
            // Validate the form
            // if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('completed_status', 0);                      
                formData.append('changedFields', JSON.stringify(changedFields));
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
                                
                                window.location.href = baseurl + 'SalesOrders/draft_or_edit?id='+response.data;
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

    //erp2024 03-02-2025 for history log     
    document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function () {
            const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
            const originalValue = this.getAttribute('data-original-value');
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();                
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
                        fieldlabel: field_label
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
                            fieldlabel: field_label
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
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else if (this.tagName === 'SELECT') {
                // For select fields, use the option's label
                const selectedOption = this.options[this.selectedIndex];
                const newValue = selectedOption ? selectedOption.label : '';
                const originalLabel = this.getAttribute('data-original-value');

                if (originalLabel !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalLabel,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            } else {
                // For text and textarea fields
                const newValue = this.value;
                if (originalValue !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            }
        });
    });

    $(document).on('select2:select select2:unselect', '.select2-hidden-accessible', function (e) {
        const fieldId = this.id || this.name;
        const originalValue = $(this).data('original-label'); // Original value (could be string or array)
        const newValueArray = $(this).val(); // Get the current value(s) as an array
        const label = $('label[for="' + fieldId + '"]');
        let field_label = label.text();
        if (!field_label.trim()) {
            field_label = this.getAttribute('title') || 'Unknown Field';
        }

        if (Array.isArray(newValueArray)) {
            // Handle multiple select: Get the selected option labels
            const newValueLabels = newValueArray.map(function (value) {
                const option = $('option[value="' + value + '"]', e.target);
                return option.length ? option.text() : ''; // Get the label (text) of the selected option
            });

            const newValue = newValueLabels.join(','); // Convert array of labels to string
            const originalLabels = Array.isArray(originalValue) ? originalValue.map(function (value) {
                const option = $('option[value="' + value + '"]', e.target);
                return option.length ? option.text() : '';
            }).join(',') : originalValue;

            if (originalLabels !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalLabels,
                    newValue: newValue,
                    fieldlabel: field_label,
                };
            } else {
                delete changedFields[fieldId]; // No changes
            }
        } else {
            // Handle single select: Get the selected option label
            const newValue = newValueArray ? $('option[value="' + newValueArray + '"]', e.target).text() : '';
            if (originalValue !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalValue,
                    newValue: newValue,
                    fieldlabel: field_label,
                };
            } else {
                delete changedFields[fieldId]; // No changes
            }
        }
    });
    // erp2024 newly added 03-02-2025 for detailed history log ends 


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

   function deleteitem(id){

        swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'Invoices/deletesubItem',
                    data: { selectedProducts: id },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {}
                });
            }
        });
    }

    function deleteitem(id,img_name) {
        swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,
                focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: baseurl + 'Quote/deletesubItem',
                            data: { selectedProducts: id, image: img_name },
                            dataType: 'json',
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr, status, error) {}
                        });
                    }
            });
    }

    $('#salesorder-delete-btn').on('click', function(e) {
            
        e.preventDefault();
        $("#s_warehouses").prop('required', false).next(".error").remove();
        $('#salesorder-delete-btn').prop('disabled', true);
        var salesorder_id = $("#salesorder_id").val();  
        var salesorder_number = $("#invocienoId").val();  
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete this sales order?",
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
                    url: baseurl + 'SalesOrders/saleorderdeleteaction',
                    type: 'POST',
                    data: {
                        salesorder_id : salesorder_id,
                        salesorder_number : salesorder_number,
                    },
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
                $('#salesorder-delete-btn').prop('disabled', false);
            }
        });
        
    });

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




</script>
<script>
    $(document).ready(function() {
        <?php foreach ($products as $i => $row): ?>
            rowTotal(<?= $i ?>);
            billUpyog();
            orderdiscount();
        <?php endforeach; ?>
        // credit_limit_with_grand_total();
    });
</script>
