<div class="content-body">
<div class="card">
   <div class="card-header border-bottom">
    <?php 
        $shoptype = ($delnotedetails['shop_type']=='Retail Shop') ? "checked" : "";
        $deliverynoteNumber = (!empty($delnotedetails['tid'])) ? $delnotedetails['tid'] : $id+1000;
    ?>
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('DeliveryNotes') ?>"><?php echo $this->lang->line('Delivery Notes'); ?></a></li>                 
               <li class="breadcrumb-item active" aria-current="page"><?php echo $prefix.$deliverynoteNumber;?></li>
            </ol>
      </nav>
      
      <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-4 col-sm-12 col-xs-12">
               <h4 class="card-title"><?php echo $prefix.$deliverynoteNumber;?></h4>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-xs-12">  
                <div class="btn-group alert alert-warning text-center <?=$msgcls?>" role="alert">
                     <!-- -------------------- -->
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="shoptype" <?=$shoptype?>>
                        <label class="form-check-label" for="shoptype">
                            <strong class="fsize-14"><?php echo $this->lang->line('Are you a retail shop?'); ?></strong>
                        </label>
                    </div>
                    <!-- -------------------- -->
                </div> 
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
               
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                     <div id="customerpanel" class="inner-cmp-pnl">
                        <div class="form-group row">
                           <!-- <div class="fcol-sm-12">
                                 <h3 class="title-sub">
                                    <?php //echo $this->lang->line('Customer Details') ?> 
                           </div> -->
                           <div class="frmSearch col-sm-12"><label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Client') ?> <span class="compulsoryfld">*</span></label>
                                 <input type="text" class="form-control" name="cst" id="customer-box" placeholder="<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>" autocomplete="off"/>

                                 <div id="customer-box-result"></div>
                           </div>

                        </div>
                        <div id="customer">
                           <?php
                            if(!empty($delnotedetails['customer_id'])){
                                ?>
                                <input type="hidden" name="customer_id" id="customer_id" value="<?=$delnotedetails['customer_id']?>">
                                <div class="clientinfo">
                                    <?php //echo $this->lang->line('Client Details');
                                        $customer_id = (!empty($delnotedetails['customer_id']) && $delnotedetails['customer_id']>0) ? $delnotedetails['customer_id'] : 0; 
                                    ?>
                                    <hr>
                                    
                                    <div id="customer_name">
                                        <?php echo '<div id="customer_name"><strong>' . $customer['name'] . '</strong></div>'; ?>
                                    </div>
                                </div>
                                <div class="clientinfo">

                                        <div id="customer_address1"><?php echo '<div id="customer_address1"><strong>' . $customer['address'] . '<br>' . $customer['city'] . ',' . $customer['countryname'] . '</strong></div>'; ?></div>
                                </div>

                                <div class="clientinfo">
                                        <div type="text" id="customer_phone">
                                            <?php echo ' <div type="text" id="customer_phone">Phone : <strong>' . $customer['phone'] . '</strong><br>Email : <strong>' . $customer['email'] . '</strong></div>';
                                            echo '<div type="text" >'.$this->lang->line('Company Credit Limit').' : <strong>' . $customer['credit_limit'] . '</strong><br>'.$this->lang->line('Credit Period').' : <strong>' . $customer['credit_period'] . '(Days)</strong><br><br><strong><span class=avail_creditlimit '.$cls.'>'.$this->lang->line('Available Credit Limit').' : ' . $customer['avalable_credit_limit'] . '</strong></span><input type="hidden" name="avalable_credit_limit" id="avalable_credit_limit" value="' . $customer['avalable_credit_limit'] . '"><input type="hidden" id="available_credit" value="' . $customer['avalable_credit_limit'] . '"></div>';
                                            ?>
                                        </div>
                                </div>
                                <?php
                            }
                            else{
                                ?>

                                <div class="clientinfo">
                                    <?php //echo $this->lang->line('Client Details');?>
                                    <!-- <hr> -->
                                    <input type="hidden" name="customer_id" id="customer_id" value="0">
                                    <div id="customer_name"></div>
                                </div>
                                <div class="clientinfo">
                                        <div id="customer_address1"></div>
                                </div>

                                <div class="clientinfo">
                                        <div type="text" id="customer_phone"></div>
                                </div>
                                <?php
                            }
                           ?>

                           <div id="customer_pass"></div>
                           
                        </div>


                     </div>
                  </div>
                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                  <div class="inner-cmp-pnl">
                        <div class="form-group row">
                            
                                <div class="col-sm-12"><h3 class="title-sub"><?php echo $this->lang->line('Delivery Note Properties'); ?></h3></div>

                                <!-- erp2024 modified section 07-06-2024 -->
                                    
                                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Delivery Note Number'); ?>  </label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>
                                     
                                        <input type="text" class="form-control" name="delivery_note_number" id="delivery_note_number" value="<?php echo $prefix.$deliverynoteNumber; ?>" readonly>
                                        <input type="hidden" class="form-control" name="invocieno_demo" id="invocieno_demo" value="<?php echo $deliverynoteNumber; ?>" readonly>
                                    </div>
                                    <!-- erp2024 modified section 07-06-2024 Ends -->
                                </div>
                         
                            
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Sale Point') ?><span class="compulsoryfld"> *</span></label>
                                    <select id="s_warehouses" name="s_warehouses" class="form-control">
                                    <?php 
                                        echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                        <?php foreach ($warehouse as $row) {
                                            $sel="";
                                            if($delnotedetails['store_id'] == $row['id']){
                                                $sel = "selected";
                                            }
                                        echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference'); ?> <span class="compulsoryfld">*</span></label>
                                    <input type="text" class="form-control" placeholder="Reference #" name="refer" id="refer" value="<?php echo $delnotedetails['refer']; ?>">
                                </div>
                                <div class="col-12"></div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_po_reference" class="col-form-label"><?php echo $this->lang->line('Customer PO / Reference'); ?></label> 
                                    <input type="text" class="form-control" name="customer_po_reference" id="customer_po_reference" value="<?php echo $delnotedetails['customer_po_reference']; ?>">
                                </div>
                                
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_contact_person" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label> 
                                    <input type="text" class="form-control" name="customer_contact_person" id="customer_contact_person" value="<?php echo $delnotedetails['customer_contact_person']; ?>">
                                </div>
                                
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label> 
                                    <input type="text" class="form-control" name="customer_contact_number" id="customer_contact_number" value="<?php echo $delnotedetails['customer_contact_number']; ?>">
                                </div>
                                
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label> 
                                    <input type="email" class="form-control" name="customer_contact_email" id="customer_contact_email" value="<?php echo $delnotedetails['customer_contact_email']; ?>">
                                </div>



                                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none"><label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Sales Order Number'); ?></label>

                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-bookmark-o" aria-hidden="true"></span></div>
                                        <input type="text" class="form-control"
                                                name="salesorder_number1" id="salesorder_number" value="<?php echo $delnotedetails['salesorder_number']; ?>" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none"><label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Reference Date'); ?></label>

                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-calendar-o" aria-hidden="true"></span></div>
                                        <input type="text" class="form-control" name="invocieduedate" id="invocieduedate" placeholder="Validity Date" autocomplete="false" value="<?php echo date("d-m-Y"); ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none"><label for="created_date" class="col-form-label"><?php echo $this->lang->line('Created Date'); ?></label>

                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="icon-calendar-o" aria-hidden="true"></span></div>
                                        <input type="date" class="form-control" name="created_date" id="created_date" placeholder="Created Date" autocomplete="false" value="<?php echo date("Y-m-d"); ?>" >
                                    </div>
                                </div>

                              
                            
                                <!-- <div class="col-xl-5 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Sales Order Note') ?></label>
                                    <textarea class="form-textarea textarea-bg" name="notes" id="proposal" rows="2" readonly><?php echo $invoice['notes'] ?></textarea>
                                </div> -->
                                <div class="col-xl-5 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Note') ?></label>
                                    <textarea class="form-textarea" name="note" id="note" rows="2" ><?php echo $delnotedetails['note']; ?></textarea>
                                </div>
                                
                                <div class="col-xl-5 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Customer Message'); ?></label>
                                        <textarea class="form-textarea" name="proposal" id="proposal" rows="2" ></textarea>
                                <div>
                                    
                            </div>
                            </div>
                            
                            <!-- <div class="container">
                                <div class="row">
                                   
                                    <div class="col-xl-5 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo" class="col-form-label"><?=$this->lang->line('Note') ?></label>
                                        <textarea class="form-textarea" name="note" id="note" rows="2"><?php echo $invoice['note'] ?></textarea>
                                    </div>
                                </div>                                       
                            </div> -->

                            <div class="form-group row d-none">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label for="taxformat" class="col-form-label">Tax</label>
                                    <select class="form-control" onchange="changeTaxFormat(this.value)" id="taxformat">
                                        <?php echo $taxlist; ?>
                                    </select>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                    <div class="form-group">
                                        <label for="discountFormat" class="col-form-label">Discount</label>
                                        <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                id="discountFormat">
                                            <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                            <?php echo $this->common->disclist() ?>
                                        </select>
                                    </div>
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
                     <div class="col-lg-12">
                        <div class="creditlimit-check"></div>
                     </div>
                    <div class="col-12 form-row mt-1 discount-toggle">
                        <div class="form-check" >
                            <input class="form-check-input discountshowhide" type="checkbox" value="2"  name="discountshowhide" id="discountshowhide">
                            <label class="form-check-label dicount-checkbox" for="discountshowhide">
                            <b><?php echo $this->lang->line('Would you like to add a discount for these products?'); ?></b>
                            </label>
                        </div>
                    </div>
                     <input type="hidden" name="discount_flg" class="discount_flg" value="0">
                     <div id="saman-row" class="overflow-auto">  
                       
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>


                            <tr class="item_header bg-gradient-directional-blue white">
                                <tr class="item_header bg-gradient-directional-blue white">
                                <th width="2%" class="text-center"><?php echo $this->lang->line('SN') ?></th>
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
                                <th width="12%" class="text-center discountcoloumn d-none"><?php echo $this->lang->line('Discount')?>/ <?php echo $this->lang->line('Amount'); ?></th>
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
                                $discount_flg=0;
                                if(!empty($products))
                                {
                                    $totaldiscount = 0;
                                    $totaltax      = 0;
                                    $subtotal      = 0;
                                    $k=1;
                                    foreach($products as $row)
                                    {
                                        $totaldiscount += $row['deliverytotaldiscount'];
                                        $totaltax      += $row['totaldiscount'];
                                        $subtotal      += ($row['product_qty'] * $row['product_price']);
                                        if($row['discount']>0 && $discount_flg==0)
                                        {
                                            $discount_flg =1;
                                        }
                                        ?>
                                        <tr>        
                                            <td class="text-center serial-number"><?=$k++?></td>
                                            <td><input type="text" class="form-control code" name="code[]" id="code-<?=$i?>" value="<?=$row['product_code']?>">
                                            <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-<?=$i?>" value="<?=$row['income_account_number']?>">
                                            <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-<?=$i?>" value="<?=$row['product_cost']?>"></td>
                                            <td><input type="text" class="form-control product_name" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' value="<?=$row['product_name']?>"></td>
                                            <td class="text-center"><input type="text" class="form-control req amnt product_qty" name="product_qty[]" id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog(), orderdiscount()" autocomplete="off" value="<?=$row['product_qty']?>"></td>
                                            <td class="text-center"><strong id="onhandQty-<?=$i?>"><?=$row['totalQty']?></strong></td>
                                            <td class="text-right">    
                                                <strong id="pricelabel-<?=$i?>"><?=$row['product_price']?></strong>
                                                <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>" value="<?=$row['product_price']?>"  onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()" autocomplete="off"></td>
                                            <td class="text-right">
                                                <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-<?=$i?>" onkeypress="return isNumber(event)" autocomplete="off" value="<?=$row['min_price']?>">
                                                <strong id="lowestpricelabel-<?=$i?>"><?=$row['min_price']?></strong>
                                            </td>
                                            <?php //Verify that tax is enabled
                                            if($configurations['config_tax']!='0'){ ?>           
                                                    <td class="text-center">
                                                        <div class="text-center">                                                
                                                            <input type="hidden" class="form-control" name="product_tax[]" id="vat-<?=$i?>"
                                                                onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                                                autocomplete="off">
                                                                <strong id="taxlabel-<?=$i?>"></strong>&nbsp;<strong  id="texttaxa-<?=$i?>"></strong>
                                                        </div>
                                                    </td>
                                            <?php } ?>
                                            <td class="text-center"><strong id='maxdiscountratelabel-<?=$i?>'><?=$row['maximumdiscount']?></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-<?=$i?>" value="<?=$row['maximumdiscount']?>"></td>

                                            <td class="text-center discountcoloumn d-none">
                                                <div class="input-group text-center">
                                                    <select name="discount_type[]" id="discounttype-<?=$i?>" class="form-control" onchange="discounttypeChange(0),orderdiscount()">
                                                        <option value="Perctype" <?php if($row['delnote_discounttype'] =='Perctype'){ echo 'selected'; }?>>%</option>
                                                        <option value="Amttype"  <?php if($row['delnote_discounttype'] =='Amttype'){ echo 'selected'; }?>>Amt</option>
                                                    </select>&nbsp;
                                                    <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()" value="<?=$row['product_discount']?>">
                                                    <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange(0),orderdiscount()" value="<?=$row['product_discount']?>">
                                                </div>  
                                                <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel">Amount : <?=$row['deliverytotaldiscount']?></strong>
                                                <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                            </td>

                                            <td class="text-right">
                                                <strong><span class='ttlText' id="result-<?=$i?>"><?=$row['deliverysubtotal']?></span></strong></td>
                                            <td class="text-center">
                                                <button onclick='producthistory("<?=$i?>")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                <button onclick='single_product_details("<?=$i?>")' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                <button type="button" data-rowid="<?=$i?>" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                            </td>
                                            <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="<?=$row['deliverytaxtotal']?>">
                                            <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?=$row['deliverytotaldiscount']?>">
                                            <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?=$row['deliverysubtotal']?>">
                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$row['product_id']?>">
                                            <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?=$row['unit']?>">
                                            <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$row['product_code']?>">
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                
                                }
                            else{
                                ?>
                                <tr>
                                    <td class="text-center serial-number">1</td>
                                    <td>
                                        <input type="text" class="form-control code" name="code[]" id='code-0'>
                                        <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-0">
                                        <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-0">
                                    </td>
                                    <td><input type="text" class="form-control product_name" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-0'></td>
                                    <td class="text-center"><input type="text" class="form-control req amnt product_qty element-border" name="product_qty[]" id="amount-0" onkeypress="return isPositiveNumber(event, this)"  oninput="isPositiveNumber(event, this)" onkeyup="rowTotal('0'), billUpyog(),orderdiscount()" autocomplete="off" value=""></td>

                                     
      
                                    <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                    <td class="text-right">    
                                        <strong id="pricelabel-0"></strong>
                                        <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0"  onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td>
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

                                    <td class="text-center discountcoloumn d-none">
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
                                        <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                        <button onclick='single_product_details("0")' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                        
                                        <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                    </td>
                                    <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                    <input type="hidden" name="disca[]" id="disca-0" value="0">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                    <input type="hidden" name="unit[]" id="unit-0" value="">
                                    <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                </tr>
                            <?php
                            }

                            ?>
                              
                            <tr class="last-item-row sub_c tr-border">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-crud btn-secondary"  title="Add product row" id="sales_create_btn">
                                    <i class="fa fa-plus-square"></i>  <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>
                            <tr>
                                
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
                                <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandamount"><?=number_format($subtotal,2)?></span>
                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border td-colspan">
                                    <strong><?php echo $this->lang->line('Total Product Discount') ?> (<span  class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode"><?=number_format($totaldiscount,2);?></span></td>
                            </tr>

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border td-colspan">
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
                                <td colspan="9" align="right" class="no-border td-colspan">
                                    <strong><?php echo $this->lang->line('Order Discount') ?></strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                 <input type="number" class="form-control" style="text-align:end;width:50%;" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" >
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="9" align="right" class="no-border td-colspan"><strong><?php echo $this->lang->line('Net Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <?php
                                    $nettotal = $subtotal - $totaldiscount;
                                     ?>
                                    <span id="grandtotaltext"><?=number_format($nettotal,2)?></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly value="<?=$nettotal?>">

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="4" class="no-border">
                                <input type="submit" class="btn btn-crud btn-lg btn-secondary sub-btn" value="<?php echo "Save As Draft" ?>" id="deliverynote-btn" data-loading-text="Creating...">
                                </td>
                                <td align="right" colspan="7" class="no-border">                                    
                                    
                                   

                                    <?php 
                                    // if($delnotedetails['shop_type']=='Retail Shop'){
                                        echo '<input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="Create Delivery Note" id="save_and_continue">';
                                    // }
                                    ?>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

               <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
               <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
               <input type="hidden" value="<?= $this->common->taxhandle_edit($invoice['taxstatus']) ?>" name="taxformat" id="tax_format">
               <input type="hidden" value="<?= $invoice['format_discount']; ?>" name="discountFormat" id="discount_format">
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

<script type="text/javascript">
   $(document).ready(function() {
       var discountflag = <?=$discount_flg?>;
        if(discountflag==1){
            showdiscount_potion();
        }
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

        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {               
                s_warehouses: { required: true },
                cst: {
                    required: function() {
                        return $('#customer_id').val() == 0;
                    }
                },
                refer: { required: true },
                customer_purchase_order: { required: true },
                customer_contact_number: {
                    phoneRegex :true
                },
            },
            messages: {
                invocieduedate: "Select Sale Pont",
                refer: "Enter Internal Reference",
                cst: "Select Customer",
                customer_contact_number: "Enter a Valid Number",
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

        $('#deliverynote-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#deliverynote-btn').prop('disabled', true);
            var retailflg = 0;
            shoptype="";
            if ($("#shoptype").is(":checked")) 
            {
                retailflg = 1;
                shoptype= "Retail Shop";
            }
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });
            // if (selectedProducts1.length === 0) {
            //     Swal.fire({
            //     text: "To proceed, please add  at least one item",
            //     icon: "info"
            //   });
            //   $('#deliverynote-btn').prop('disabled', false);
            //     return;
            // }
            if (!$("#customer-box").valid()) {
                    $("#customer-box").focus();
                    $('#deliverynote-btn').prop('disabled', false); 
                    return;
            }
            // Validate the form
            // if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('shoptype', shoptype);
                // Swal.fire({
                //     title: "Are you sure?",
                //     text: "Do you want to print a delivery note?",
                //     icon: "question",
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Yes, proceed!',
                //     cancelButtonText: "No - Cancel",
                //     reverseButtons: true,  
                //     focusCancel: true,      
                //     allowOutsideClick: false,  // Disable outside click
                // }).then((result) => {
                //     if (result.isConfirmed) {
                        
                        $.ajax({
                            url: baseurl + 'DeliveryNotes/deliverynoteaction', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                //var targeturl = (retailflg==1) ? baseurl + 'DeliveryNotes/create?id='+response.id : baseurl + 'DeliveryNotes';
                                var targeturl = baseurl + 'DeliveryNotes/create?id='+response.id ; 
                                // window.href(targeturl); 
                                window.location.href =targeturl;
                                // if((retailflg==1))
                                // {
                                //comment print
                                    //window.location.href = baseurl + 'DeliveryNotes/deliverynote_shop_print?deliverynoteid=' + response.id;
                                // }
                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                //     } else if (result.dismiss === Swal.DismissReason.cancel) {
                //         $('#deliverynote-btn').prop('disabled', false);
                //     }
                // });
            // } 
            // else {
            //     $('#deliverynote-btn').prop('disabled', false);
            // }
        });


        $('#save_and_continue').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#save_and_continue').prop('disabled', true);
            var retailflg = 0;
            shoptype="";
            if ($("#shoptype").is(":checked")) 
            {
                retailflg = 1;
                shoptype= "Retail Shop";
            }
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });
            if (selectedProducts1.length === 0) {
                Swal.fire({
                text: "To proceed, please add  at least one item",
                icon: "info"
              });
                $('#save_and_continue').prop('disabled', false);
                return;
            }
            
            // Validate the form
            if ($("#data_form").valid()) {    
                
                credit_limit_with_grand_total();
                var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
                var available_credit_limit = parseFloat($("#available_credit").val().replace(/,/g, '').trim());
                if (total > available_credit_limit) {
                    $('#save_and_continue').prop('disabled', false);
                    return;
                }
                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('shoptype', shoptype);
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create a new delivery note and update inventory?",
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
                            url: baseurl + 'DeliveryNotes/deliverynote_save_and_new_action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                // var targeturl = baseurl + 'DeliveryNotes/create';
                                var targeturl = baseurl + 'DeliveryNotes';
                                if((retailflg==1))
                                {                                    
                                    window.open(targeturl); 
                                    window.location.href = baseurl + 'DeliveryNotes/deliverynote_shop_print?deliverynoteid=' + response.id;
                                    
                                }
                                else{
                                    window.location.href =targeturl;
                                    // location.reload();
                                }
                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#save_and_continue').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#save_and_continue').prop('disabled', false);
            }
        });



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

function credit_limit_with_grand_total() {
    var total = parseFloat($("#invoiceyoghtml").val().replace(/,/g, '').trim());
    var available_credit_limit = parseFloat($("#available_credit").val().replace(/,/g, '').trim());
    if (isNaN(total) || isNaN(available_credit_limit)) {
        textdata = '<div class="alert alert-warning">Invalid numbers. Please check the values again.</div>';
    } else if (total > available_credit_limit) {
        textdata = '<div class="alert alert-danger mb-0">The Grand Total Amount exceeds the Available Credit Limit. Please review.</div>';
        $(".avail_creditlimit").addClass('text-danger');
        $("#save_and_continue").addClass('disable-class');
        $("#deliverynote-btn").addClass('disable-class');
    } else {
        textdata = '<div class="alert alert-success mb-0">The Available Credit Limit is sufficient for the Grand Total Amount. Please procced.</div>';
        $(".avail_creditlimit").removeClass('text-danger');
        $("#save_and_continue").removeClass('disable-class');
        $("#deliverynote-btn").removeClass('disable-class');
    }

    $("#creditlimit-check").html(textdata);
    $('.sub-btn').prop('disabled', false);
    $(".sub-btn").removeClass('disable-class');
    return;
}
   
</script>

