<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
            <?php 
               $status = ($master['invoicestatus']) ? $master['invoicestatus'] : 'due';
               $invoiceid = ($master['tid']) ? $master['tid'] :$lastinvoice + 1;
               $invoice_number = ($master['invoice_number']) ? $master['invoice_number'] : $prefix.$invoiceid;
               // $invoiceid = (!empty($master['tid']) && $master['tid'] > 0) ? $master['tid'] : $lastinvoice + 1;
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('New Invoice')." - ".
                    $invoice_number; ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('New Invoice').' - '.$invoice_number; ?> </h4>
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
                  <!--  -->
               <?php if(($master['invoicestatus'])){ ?>
                     <div class="col-3"></div>
                     <div class="col-3">
                     <?php
                     $text_color = 'alert-success';
                     $creditlimit_class ="";
                     ?>
                     <div class="alert <?=$text_color?>"> Current Status : <?=ucfirst($master['invoicestatus'])?></div>                                    
                     </div>
                     <div class="col-6"></div>
                  <?php } ?>
                  <!--  -->
                  <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                     <div id="customerpanel" class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="fcol-sm-12">
                              <h3 class="title-sub">
                              <?php 
                                 echo $this->lang->line('Client Details'); ?><h3>
                           </div>
                           <div class="frmSearch col-sm-12">
                              <label for="cst"
                                 class="col-form-label"><?php echo $this->lang->line('Search Client'); ?><span class="compulsoryfld"> *</span></label>
                              <input type="text" class="form-control" name="cst" id="customer-box"
                                 placeholder="<?php echo $this->lang->line('Enter Customer Name or Mobile Number to search'); ?>"
                                 autocomplete="off" />
                              <div id="customer-box-result"></div>
                           </div>
                        </div>
                        <div id="customer">
                        <?php
                            if(!empty($master['csd'])){
                                ?>
                                <div class="clientinfo">
                                    <?php
                                        $customer_id = (!empty($master['csd']) && $master['csd']>0) ? $master['csd'] : 0; 
                                    ?>
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer_id?>">
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
                              
                                 <input type="hidden" name="customer_id" id="customer_id" value="0">
                                 <div id="customer_name"></div>
                              </div>
                              <div class="clientinfo">
                                 <div id="customer_address1"></div>
                              </div>
                              <div class="clientinfo">
                                 <div id="customer_phone"></div>
                              </div>
                              <div id="customer_pass"></div>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                     <div class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <h3 class="title-sub"><?php echo $this->lang->line('Invoice Properties') ?></h3>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Invoice Number') ?></label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-file-text-o"
                                    aria-hidden="true"></span></div>
                                 <input type="hidden" name="convert_type" id="convert_type" value="">
                                 <input type="text" class="form-control" placeholder="Invoice #" name="invoice_number" value="<?php echo $invoice_number ?>" readonly>
                                 <input type="hidden" class="form-control" placeholder="Invoice #" name="invocieno" value="<?php echo $invoiceid ?>" readonly>
                                 <input type="hidden" class="form-control" placeholder="Invoice #" name="status" value="<?php echo $status ?>" readonly>

                              </div>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Reference') ?><span class="compulsoryfld"> *</span></label>
                                 <input type="text" class="form-control" placeholder="Reference #" name="refer" value="<?=$master['refer']?>">
                           </div>
                           
                           
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieduedate"
                                 class="col-form-label"><?php echo $this->lang->line('Invoice Due Date') ?><span class="compulsoryfld"> *</span></label>
                                 <?php
                                    $duedate = ($master['invoiceduedate']) ? date('Y-m-d', strtotime($master['invoiceduedate'])) :date('Y-m-d');
                                 ?>
                                 <input type="date" class="form-control" name="invocieduedate"
                                    placeholder="Due Date" autocomplete="false" min="<?=date('Y-m-d')?>" Value="<?=$duedate?>">
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="taxformat" class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                              <select class="form-control" onchange="changeTaxFormat(this.value)"
                                 id="taxformat"> <?php echo $taxlist; ?>
                              </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="discountFormat"
                                 class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                              <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                 id="discountFormat">
                              <?php echo $this->common->disclist() ?>
                              </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouses') ?><span class="compulsoryfld"> *</span> </label>
                              <select id="s_warehouses" name="s_warehouses" class="form-control">
                              <?php 
                                 echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                 <?php foreach ($warehouse as $row) {
                                    $sel = ($master['store_id'] == $row['id']) ? "selected" : "";
                                    echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <?php if (isset($employee)){ ?>
                              <label for="employee"
                                 class="col-form-label"><?php echo $this->lang->line('Employee') ?> </label>
                              <select name="employee" class="col form-control disable-class" readonly>
                              <?php 
                              foreach ($employee as $row) {
                                 $sel = ($row['id']==$this->session->userdata('id')) ? "selected" : "" ;
                                 echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['name'] . '</option>';
                              } ?>
                              </select><?php } ?>
                              <?php if ($exchange['active'] == 1){
                                 echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                              <select name="mcurrency" class="selectpicker form-control">
                                 <option value="0">Default</option>
                                 <?php foreach ($currency as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                    } ?>
                              </select>
                              <?php } ?>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="pterms"
                                 class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?>
                              </label>
                              <select name="pterms" class="selectpicker form-control">
                                 <?php foreach ($terms as $row) {
                                    $selected = ($master['term'] == $row['id']) ? "selected" : "";
                                    echo '<option value="' . $row['id'] . '" '.$selected.'>' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>
                           <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Note') ?></label>
                              <textarea class="form-textarea" name="notes" rows="2"><?=$master['notes']?></textarea>
                           </div>

                           <div class="col-12 d-none">
                              <label class="col-form-label font-13"><strong><?php echo $this->lang->line('Payment Type') ?></strong></label><br>
                              <div class="form-check form-check-inline">
                                 <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="cashOption" value="Cash">
                                 <label class="form-check-label font-13" for="cashOption"><b><?php echo $this->lang->line('Cash') ?></b></label>
                              </div>
                              <div class="form-check form-check-inline">
                                 <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="creditCardOption" value="Credit Card">
                                 <label class="form-check-label font-13" for="creditCardOption"><b><?php echo $this->lang->line('Credit Card') ?></b></label>
                              </div>
                              <div class="form-check form-check-inline">
                                 <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="chequeOption" value="Cheque">
                                 <label class="form-check-label font-13" for="chequeOption"><b><?php echo $this->lang->line('Cheque') ?></b></label>
                              </div>
                              <div class="form-check form-check-inline">
                                 <input class="form-check-input payment-type-radio" type="radio" name="payment_type" id="customerCreditOption" value="Customer Credit">
                                 <label class="form-check-label font-13" for="customerCreditOption"><b><?php echo $this->lang->line('Customer Credit') ?></b></label>
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
                  </div>
               </div>
               
               <!-- ---------- alert message ----- -->
               <div class="alert alert-danger alert-dismissible creditlimit-alert d-none" role="alert">
                  <?php echo $this->lang->line("Your available credit limit"); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="col-lg-12">
                        <div class="creditlimit-check"></div>
                  </div>
               <!-- ---------- alert message ----- -->
               <div id="saman-row">
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                           <th width="10%" class="text-center1 pl-1">
                              <?php echo $this->lang->line('Item No') ?>
                           </th>
                           <th width="25%" class="text-center1 pl-1">
                              <?php echo $this->lang->line('Item Name') ?>
                           </th>
                           <th width="8%" class="text-center1 pl-1"><?php echo $this->lang->line('Quantity') ?>
                           </th>
                           
                           <th width="4%" class="text-center"><?php echo $this->lang->line('On Hand') ?></th>
                           <th width="7%" class="text-right"><?php echo $this->lang->line('Selling Price') ?></th>
                           <th width="7%" class="text-right"><?php echo $this->lang->line('Lowest Price') ?></th>
                           <!-- <th width="8%" class="text-center1 pl-1"><?php echo $this->lang->line('Rate') ?></th> -->
                           <?php
                            $colspan=9;
                            $colspansmall=4;
                            $colspangrandtotal = 5;
                            if($configurations['config_tax']!=0)
                            {
                               $colspan=11; 
                               $colspansmall=6;
                               $colspangrandtotal = 7;
                               ?>
                              <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Tax(%)') ?></th>
                              <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Tax') ?></th>
                              <?php  
                           } ?>
                           
                           <th width="5%" class="text-center"><?php echo $this->lang->line('Max discount %')?></th>
                           <th width="12%" class="text-center"><?php echo $this->lang->line('Discount')?>/ <?php echo $this->lang->line('Amount'); ?></th>
                           <th width="10%" class="text-center1 pl-1">
                              <?php echo $this->lang->line('Amount') ?>
                              (<?= currency($this->aauth->get_user()->loc); ?>)
                           </th>
                           <th width="9%" class="text-center1 pl-1"><?php echo $this->lang->line('Action') ?>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        
                     <?php
                                $i=0;
                                if(!empty($products))
                                {
                                    $totaldiscount = 0;
                                    $totaltax      = 0;
                                    $subtotal      = 0;
                                    foreach($products as $row)
                                    {
                                        $totaldiscount += $row['totaldiscount'];
                                        $totaltax      += $row['totaltax'];
                                        $subtotal      += ($row['qty'] * $row['price']);
                                        ?>
                                        <tr>        
                                            <td><input type="text" class="form-control code" name="code[]" id="code-<?=$i?>" value="<?=$row['product_code']?>">
                                            <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-<?=$i?>" value="<?=$row['product_cost']?>">
                                            <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-<?=$i?>" value="<?=$row['income_account_number']?>">
                                            </td>
                                            <td><span class="d-flex"><input type="text" class="form-control product_name" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' value="<?=$row['product_name']?>">&nbsp;<button type="button" title="change account"
                                                class="btn btn-crud btn-sm btn-secondary"
                                                id="btnclk-<?= $i ?>"
                                                data-toggle="popover"
                                                onclick="loadPopover(<?= $i ?>)"
                                                data-html="true"
                                                data-content='
                                                    <form id="popoverForm-<?= $i ?>">
                                                        <div class="form-group">
                                                            <label for="accountList-<?= $i ?>">Select Account</label>
                                                            <select class="form-control" id="accountList-<?= $i ?>">
                                                                <!-- Options will be loaded dynamically -->
                                                            </select>
                                                        </div>
                                                        <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn  btn-crud btn-primary btn-sm">Change</button></div>
                                                    </form>'
                                            >
                                                <i class="fa fa-bank"></i>
                                            </button></span></td>

                                            <td class="text-center"><input type="text" class="form-control req amnt product_qty" name="product_qty[]" id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog(), orderdiscount()" autocomplete="off" value="<?=intval($row['qty'])?>"></td>

                                            <td class="text-center"><strong id="onhandQty-<?=$i?>"><?=$row['onhandqty']?></strong></td>
                                            <td class="text-right">    
                                                <strong id="pricelabel-<?=$i?>"><?=$row['price']?></strong>
                                                <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>" value="<?=$row['price']?>"  onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()" autocomplete="off"></td>
                                            <td class="text-right">
                                                <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-<?=$i?>" onkeypress="return isNumber(event)" autocomplete="off" value="<?=$row['minprice']?>">
                                                <strong id="lowestpricelabel-<?=$i?>"><?=$row['minprice']?></strong>
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

                                            <td class="text-center">
                                                <div class="input-group text-center">
                                                    <select name="discount_type[]" id="discounttype-<?=$i?>" class="form-control" onchange="discounttypeChange(0), orderdiscount()">
                                                        <option value="Perctype" <?php if($row['discounttype'] =='Perctype'){ echo 'selected'; }?>>%</option>
                                                        <option value="Amttype"  <?php if($row['discounttype'] =='Amttype'){ echo 'selected'; }?>>Amt</option>
                                                    </select>&nbsp;
                                                    <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()" value="<?=$row['discount']?>">
                                                    <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()" value="<?=$row['discount']?>">
                                                </div>  
                                                <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel"><?=$row['deliverytotaldiscount']?></strong>
                                                <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                            </td>

                                            <td class="text-right">
                                                <strong><span class='ttlText' id="result-<?=$i?>"><?=$row['subtotal']?></span></strong></td>
                                            <td class="text-center">
                                                <button onclick='producthistory("<?=$i?>")' type="button" class="btn  btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                <button onclick='single_product_details("<?=$i?>")' type="button" class="btn btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                <button type="button" data-rowid="<?=$i?>" class="btn  btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                            </td>
                                            <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="<?=$row['totaltax']?>">
                                            <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?=$row['totaldiscount']?>">
                                            <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?=$row['subtotal']?>">
                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$row['pid']?>">
                                            <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?=$row['unit']?>">
                                            <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$row['product_code']?>">
                                            <input type="hidden" name="serial[]" id="serial-<?=$i?>" value="">
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                
                                }
                            else{
                                ?>
                              <tr class="startRow">
                                 <td><input type="text" class="form-control code" name="code[]"
                                    placeholder="<?php echo $this->lang->line('Item No') ?>"
                                    id='code-0'>
                                    <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-0">
                                    <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-0">
                                 </td>
                                 <td><span class='d-flex'><input type="text" class="form-control wid90per" name="product_name[]"
                                    placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                    id='productname-0'>
                                    &nbsp;<button type="button" title="change account"
                                                class="btn  btn-crud btn-sm btn-secondary"
                                                id="btnclk-0"
                                                data-toggle="popover"
                                                onclick="loadPopover(0)"
                                                data-html="true"
                                                data-content='
                                                    <form id="popoverForm-0">
                                                        <div class="form-group">
                                                            <label for="accountList-0">Select Account</label>
                                                            <select class="form-control" id="accountList-0">
                                                                <!-- Options will be loaded dynamically -->
                                                            </select>
                                                        </div>
                                                        <div class="text-right"><button type="button" onclick="cancelPopover(0)" class="btn  btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(0)" class="btn btn-crud  btn-primary btn-sm">Change</button></div>
                                                    </form>'
                                            ><i class="fa fa-bank"></i>
                                            </button></span>
                                 </td>
                                 <td><input type="text" class="form-control req amnt" name="product_qty[]"
                                    id="amount-0" onkeypress="return isNumber(event)"
                                    onkeyup="rowTotal('0'), billUpyog(), orderdiscount()" autocomplete="off" value="1"><input
                                    type="hidden" id="alert-0" value="" name="alert[]"></td>
                                    <!-- <td><input type="text" class="form-control req prc" name="product_price[]"
                                    id="price-0" onkeypress="return isNumber(event)"
                                    onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td> -->
                                    <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                    <td class="text-right">    
                                          <strong id="pricelabel-0"></strong>
                                          <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0"  onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off">
                                    </td>
                                    <td class="text-right">
                                          <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                          <strong id="lowestpricelabel-0"></strong>
                                    </td>
                                 
                                 <?php
                                 $colspan=9;
                                 $colspansmall=4;
                                 $colspangrandtotal = 5;
                                 if($configurations['config_tax']!=0)
                                 {
                                    $colspan=11; 
                                    $colspansmall=6;
                                    $colspangrandtotal = 7;
                                 ?>
                                 <td><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                    autocomplete="off"></td>
                                 <td class="text-center" id="texttaxa-0">0</td>
                                 <?php } ?>
                                 <td class="text-center"><strong id='maxdiscountratelabel-0'></strong><input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0"></td>
                                 <td class="text-center">
                                    <!-- <input type="text" class="form-control discount" name="product_discount[]"
                                    onkeypress="return isNumber(event)" id="discount-0"
                                    onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"> -->
                                    
                                    <div class="input-group text-center">
                                          <select name="discount_type[]" id="discounttype-0" class="form-control" onchange="discounttypeChange(0), orderdiscount()">
                                             <option value="Perctype">%</option>
                                             <option value="Amttype">Amt</option>
                                          </select>&nbsp;
                                          <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()">
                                          <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0), orderdiscount()">
                                       </div>  
                                       <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                       <div><strong id="discount-error-0"></strong>
                                    </div>        
                                 </td>
                                 <td class="text-right"></span>
                                    <strong><span class='ttlText' id="result-0"> </span></strong>
                                 </td>
                                 <td class="text-center">
                                       <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                       <button onclick='single_product_details("0")' type="button" class="btn btn-crud  btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                       <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
                                 </td>
                                 <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                 <input type="hidden" name="disca[]" id="disca-0" value="0">
                                 <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0"
                                    value="0">
                                 <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                 <input type="hidden" name="unit[]" id="unit-0" value="">
                                 <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                 <input type="hidden" name="serial[]" id="serial-0" value="">
                              </tr>

                        <?php } ?>
                        <tr class="last-item-row sub_c tr-border">
                           <td class="add-row no-border">
                              <button type="button" class="btn btn-crud btn-secondary" aria-label="Left Align"
                                 id="row_btn">
                              <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                              </button>
                           </td>
                           <td colspan="7" class="no-border"></td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                              <td colspan="5" class="no-border"></td>
                              <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Sub Total') ?>
                                       (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                              </td>
                              <td align="right" colspan="2" class="no-border">
                                 <span id="grandamount"><?=number_format($subtotal,2)?></span>
                              </td>
                           </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="<?=$colspan?>" class="reverse_align no-border">
                              <input type="hidden" value="0" id="subttlform" name="subtotal">
                              <strong><?php echo $this->lang->line('Total Tax') ?>(<?= $this->config->item('currency'); ?>)</strong>
                           </td>
                           <td align="right" colspan="2" class="no-border"><span
                              class="currenty lightMode"></span>
                              <span id="taxr" class="lightMode"><?=number_format($totaltax,2)?></span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="<?=$colspan?>" class="reverse_align no-border">
                              <strong><?php echo $this->lang->line('Total Product Discount')."(".$this->config->item('currency').")"; ?></strong>
                           </td>
                           <td align="right" colspan="2" class="no-border"><span class="currenty lightMode">
                              <?php
                              if (isset($_GET['project'])) {
                                  echo '<input type="hidden" value="' . intval($_GET['project']) . '" name="prjid">';
                              } ?></span>
                              <span id="discs" class="lightMode"><?=number_format($totaldiscount,2)?></span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="<?=$colspan?>" class="reverse_align no-border">
                              <strong><?php echo $this->lang->line('Shipping') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border">
                              <input type="number" class="form-control shipVal text-right"
                              onkeypress="return isNumber(event)" placeholder="0.00" name="shipping" autocomplete="off" onkeyup="billUpyog()" value="<?=$master['shipping']?>">
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="<?=$colspan?>" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Order Discount') ?></strong></td>
                           <td align="right" colspan="1" class="no-border">
                           <input type="number" class="form-control text-right" onkeypress="return isNumber(event)"  placeholder="0.00"  name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$master['order_discount']?>">
                           
                           </td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="<?=$colspan?>" class="reverse_align no-border">
                              <strong>
                              <?php echo $this->lang->line('Extra') . ' ' . $this->lang->line('Discount') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border">
                              <input type="text" class="form-control form-control-sm discVal"
                                 onkeypress="return isNumber(event)" placeholder="Value" name="disc_val"
                                 autocomplete="off" value="0" onkeyup="billUpyog()">
                              <input type="hidden" name="after_disc" id="after_disc" value="0">
                              ( <?= $this->config->item('currency'); ?>
                              <span id="disc_final">0</span> )
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="<?=$colspangrandtotal?>" class="no-border"></td>
                           <td colspan="4" class="reverse_align no-border">
                              <strong><?php echo $this->lang->line('Total') ?>
                              (<span
                                 class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                           </td>
                           <td align="right" colspan="2" class="no-border">
                              <?php 
                                 $grand_nettotal = 0;
                                 
                                 $grand_nettotal = ($subtotal + $master['shipping']) -($totaldiscount + $master['order_discount']);
                              ?>
                              <span id="grandtotaltext"><?=number_format($grand_nettotal,2)?></span>
                              <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly="" value="<?=$grand_nettotal?>">
                              <input type="hidden" class="form-control text-right"   name="old_order_discount" id="old_order_discount" autocomplete="off"  value="<?=$master['order_discount']?>">
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td class="reverse_align no-border" colspan="10">                              

                              <input type="submit" class="btn btn-crud btn-secondary sub-btn btn-lg" value="<?php echo $this->lang->line('Draft & Preview') ?> " id="invoice-preview-btn" data-loading-text="Creating...">&nbsp;

                              <input type="submit" class="btn btn-crud btn-secondary sub-btn btn-lg d-none" value="<?php echo $this->lang->line('Cancel & Add New') ?> " id="invoice-cancel-bt" data-loading-text="Creating...">&nbsp;
                              <input type="submit" class="btn btn-crud btn-secondary sub-btn btn-lg" value="<?php echo $this->lang->line('Confirm & Pay Now') ?> " id="confirm-paynow-btn" data-loading-text="Creating...">&nbsp;
                              
                              <input type="submit" class="btn btn-crud btn-primary sub-btn btn-lg" value="<?php echo $this->lang->line('Confirm & Add New') ?> " id="invoice-confirm-btn" data-loading-text="Creating...">
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <?php
                     if(is_array($custom_fields)){
                       echo'<div class="card">';
                        foreach ($custom_fields as $row) {
                              if ($row['f_type'] == 'text') { ?>
                                 <div class="row mt-1">
                                    <label class="col-sm-8" for="document_id"><?= $row['name'] ?></label>
                                    <div class="col-md-6 col-sm-12">
                                       <input type="text" placeholder="<?= $row['placeholder'] ?>"
                                          class="form-control margin-bottom b_input <?= $row['other'] ?>"
                                          name="custom[<?= $row['id'] ?>]">
                                    </div>
                                 </div>
                                 <?php }
                     }
                     echo'</div>';
                     }
                     ?>
               </div>
               <input type="hidden" class="form-control" placeholder="Billing Date" name="invoicedate" value="<?php echo date('Y-m-d'); ?>" readonly>
               <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
               <input type="hidden" value="new_i" id="inv_page">
               <input type="hidden" value="invoices/action" id="action-url">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="0" name="counter" id="ganak">
               <input type="hidden" value="<?= currency($this->aauth->get_user()->loc); ?>" name="currency">
               <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">
               <input type="hidden" value="<?= $taxdetails['format']; ?>" name="tax_handle" id="tax_status">
               <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
               <input type="hidden" value="<?= $this->common->disc_status()['disc_format']; ?>"
                  name="discountFormat" id="discount_format">
               <input type="hidden" value="<?= amountFormat_general($this->common->disc_status()['ship_rate']); ?>"
                  name="shipRate" id="ship_rate">
               <input type="hidden" value="<?= $this->common->disc_status()['ship_tax']; ?>" name="ship_taxtype"
                  id="ship_taxtype">
               <input type="hidden" value="0" name="ship_tax" id="ship_tax">
               <input type="hidden" value="0" id="custom_discount">
            </form>
         </div>
      </div>
   </div>
</div>


<!--     erp2025 add 25-02-2025   Detailed hisory starts-->
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
            foreach ($groupedInvoice as $seqence_number => $invoices){
            $flag=0;
            ?>              
                  <tr>
                     <td>        
                     <?php    foreach ($invoices as $invoice) {
                     if($flag==0)
                     {?>
                     <div class="userdata">
                     <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$invoice['picture'])?>' style="width:50px; height:50px;" ?>
                     <?php  echo $invoice['name'];
                              $flag=1;
                     } ?>
                     </div>           
                        <ul><li>  <?php echo $invoice['old_value'];?> > <b><span class="newdata"><?php echo $invoice['new_value']?></span></b> (<?php if($invoice['field_label']==""){echo $invoice['field_name'];}else{echo $invoice['field_label'];}?>)
                        </li></ul>
                        <?php } ?>
                     </td>               
                     <td><?php echo date('d-m-Y H:i:s', strtotime($invoice['changed_date'])); ?></td>
                     <td><?php echo $invoice['ip_address']?></td> 
                     
                  </tr>  
                  <?php 
                  $i++; 
               
            }?>
            </tbody>
         </table>

         </form>
      </div>   
    <!--     erp2025 add 25-02-2025   Detailed hisory ends-->
<script>
   const changedFields = {};
   $(document).ready(function() {

      $(".history-expand-button").on("click", function() {
        $(".history-container").toggleClass("active");
      });
      $(".history-close-button").on("click", function () {
         $(".history-container").removeClass("active");
      });
      $(".logclose-btn").on("click", function () {
         $(".history-container").removeClass("active");
      });   

       // Add event listeners to all input fields
       document.querySelectorAll('input, textarea, select').forEach((input) => {
            input.addEventListener('change', function () {
                const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
                const originalValue = this.getAttribute('data-original-value');
                var label = $('label[for="' + fieldId + '"]');
                var field_label = label.text();
                // console.log();
                if (this.type === 'checkbox') {
                    // For checkboxes, use the "checked" state
                    const newValue = this.checked ? this.value : null;
                    const originalChecked = originalValue === this.value;

                    if (originalChecked !== this.checked) {
                        changedFields[fieldId] = {
                            oldValue: originalChecked ? this.value : null,
                            newValue: newValue,
                            fieldlabel : field_label
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
                                fieldlabel : field_label
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
                            fieldlabel : field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                } else if (this.tagName === 'SELECT') {
                // For select fields, use the option's label
                const selectedOption = this.options[this.selectedIndex];
                const newValue = selectedOption ? selectedOption.label : '';
                const originalLabel = this.getAttribute('data-original-label');

                if (originalLabel !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalLabel,
                        newValue: newValue,
                        fieldlabel: field_label
                    };
                } else {
                    delete changedFields[fieldId];
                }
            }
                else {
                    // For text, textarea, and select fields
                    const newValue = this.value;
                    if (originalValue !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel : field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                }
            });
        });

        $('select').each(function () {
            if (!$(this).attr('multiple')) {
                 const selectedLabel = $(this).find(':selected').text();
                $(this).attr('data-original-label',selectedLabel);
            } else {
            // For multi-select, get all selected options text and join them with a comma
            const selectedLabels = $(this)
                .find(':selected')
                .map(function () {
                return $(this).text();
                })
                .get()
                .join(', ');
            $(this).attr('data-original-label', selectedLabels);
            }
        });

        $('#confirm-paynow-btn').removeClass('disable-class');
        $('.creditlimit-alert').addClass('d-none');
         $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {               
                s_warehouses: { required: true },
                refer: { required: true },
                invocieduedate: { required: true }
            },
            messages: {
                invocieduedate: "Enter Invoice Due Date",
                refer: "Enter Internal Reference",
                s_warehouses: "Select Warehous/shop"
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

        $('#invoice-preview-btn').on('click', function(e) {
            if($("#customer-box").val()==""){
               $("#customer-box").prop('required',true);
               return;
            }
            e.preventDefault(); // Prevent the default form submission
           
            $('#invoice-preview-btn').prop('disabled', true);
         
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });
            
  
            
            // Validate the form
            // if ($("#data_form").valid()) {      
               // if (selectedProducts1.length === 0) {
               //    Swal.fire({
               //    text: "To proceed, please add  at least one item",
               //    icon: "info"
               //    });
               //    $('#invoice-preview-btn').prop('disabled', false);
               //    return;
               // }          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                formData.append('stage', 'preview');
                formData.append('changedFields', JSON.stringify(changedFields));                
               //  Swal.fire({
               //      title: "Are you sure?",
               //      text: "Do you want to print preview invoice?",
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
                            url: baseurl + 'invoices/invoice_preview_action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                window.open(baseurl + 'invoices/create?id='+response.id); 
                                window.location.href = response.link;                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                  //   } else if (result.dismiss === Swal.DismissReason.cancel) {
                  //       // Enable the button again if user cancels
                  //       $('#invoice-preview-btn').prop('disabled', false);
                  //   }
                  //     });
                  // } else {
                  //     // If form validation fails, re-enable the button
                  //     $('#invoice-preview-btn').prop('disabled', false);
                  // }
        });
       
        $('.cancelinvoice-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('.cancelinvoice-btn').prop('disabled', true);

            Swal.fire({
               title: "Are you sure?",
               html: `
                     <p>Do you want to cancel the invoice?</p>
                     <textarea id="cancel_reason" class="swal2-textarea" style="width: 100%; box-sizing: border-box;" placeholder="Enter reason for cancellation" rows="4"></textarea>
               `,
               icon: "question",
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, proceed!',
               cancelButtonText: "No - Cancel",
               reverseButtons: true,  
               focusCancel: true,      
               allowOutsideClick: false,  // Disable outside click
               preConfirm: () => {
                     const reason = document.getElementById('cancel_reason').value.trim();
                     if (!reason) {
                        Swal.showValidationMessage('Reason for cancellation is required');
                        return false;
                     }
                     return reason; // Pass the reason to the `.then` block
               }
            }).then((result) => {
               if (result.isConfirmed) {
                     const cancelReason = result.value; // Get the reason entered by the user
                     $.ajax({
                        url: baseurl + 'invoices/cancelinvoiceaction', // Replace with your server endpoint
                        type: 'POST',
                        data: {
                           'invoiceid': $("#invoice_id").val(),
                           'cancel_reason': cancelReason
                        },
                        success: function(response) {
                           if (typeof response === "string") {
                                 response = JSON.parse(response);
                           }

                           if (response.status === 'Success') {
                                 window.location.href = baseurl + 'invoices';
                           } else {
                                 Swal.fire('Error', 'Failed to cancel the invoice', 'error');
                                 $('.cancelinvoice-btn').prop('disabled', false);
                           }
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while cancelling the invoice', 'error');
                           console.log(error); // Log any errors
                           $('.cancelinvoice-btn').prop('disabled', false);
                        }
                     });
               } else {
                     // Re-enable the button if the user cancels
                     $('.cancelinvoice-btn').prop('disabled', false);
               }
            });
         });


         $('#invoice-confirm-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#invoice-confirm-btn').prop('disabled', true);
         
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });           

            if ($('.payment-type-radio:checked').val() === 'Customer Credit') {
               var available_credit = parseFloat($('#available_credit').val()) || 0;
               var totalbillamount = parseFloat($('#invoiceyoghtml').val()) || 0; 
               $('.creditlimit-alert').addClass('d-none');
               if (totalbillamount > available_credit) {
                  $('.creditlimit-alert').removeClass('d-none');
                  $('#invoice-confirm-btn').prop('disabled', false);
                  return;
               }
            }

            // Validate the form
            if ($("#data_form").valid()) {      
               if (selectedProducts1.length === 0) {
                  Swal.fire({
                  text: "To proceed, please add  at least one item",
                  icon: "info"
               });
               $('#invoice-confirm-btn').prop('disabled', false);
                  return;
               }          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); 
                formData.append('changedFields', JSON.stringify(changedFields));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create new invoice?",
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
                            url: baseurl + 'invoices/action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                              console.log(response);
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                window.open(baseurl + 'invoices/create'); 
                                window.location.href = response.link;                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#invoice-confirm-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#invoice-confirm-btn').prop('disabled', false);
            }
         });


         $('#confirm-paynow-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#confirm-paynow-btn').prop('disabled', true);
            
            var selectedProducts1 = [];
            $('.code').each(function() {
                if($(this).val()!="")
                {
                    selectedProducts1.push($(this).val());
                }
            });
            
            if ($('.payment-type-radio:checked').val() === 'Customer Credit') {
               var available_credit = parseFloat($('#available_credit').val()) || 0;
               var totalbillamount = parseFloat($('#invoiceyoghtml').val()) || 0; 
               $('.creditlimit-alert').addClass('d-none');
               if (totalbillamount > available_credit) {
                  $('.creditlimit-alert').removeClass('d-none');
                  $('#iconfirm-paynow-btn').prop('disabled', false);
                  return;
               }
            }
            // Validate the form
            if ($("#data_form").valid()) {      
               if (selectedProducts1.length === 0) {
                  Swal.fire({
                  text: "To proceed, please add  at least one item",
                  icon: "info"
               });
               $('#confirm-paynow-btn').prop('disabled', false);
                  return;
               }          
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form);                 
                formData.append('changedFields', JSON.stringify(changedFields));
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create new invoice and pay now?",
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
                            url: baseurl + 'invoices/action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                              //   console.log(response);
                                invoice_id = response.id;       
                                var csd = $("#customer_id").val(); 
                                window.open(baseurl + 'invoices/customer_payment?id='+invoice_id+'&csd='+csd);                       
                                window.location.href = response.link;                              
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#confirm-paynow-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#confirm-paynow-btn').prop('disabled', false);
            }
        });


   });

   
function loadPopover(index) {
    const popoverButton = $('#btnclk-' + index);    
    // Set up popover content and show it
    popoverButton.popover('show');

    // AJAX request to load options based on the product code
    $.ajax({
        url: baseurl + 'invoices/load_product_accounts',
        method: 'POST',
        dataType: 'json',
        data: {
            'actheader': 'Income',
            'accountnumber':$('#income_account_number-'+index).val()
        },
        success: function(response) {
            if (response.status === 'Success') {
                const accountList = $('#accountList-' + index);
                accountList.empty(); // Clear any existing options
                accountList.html(response.data);
               
            } else {
                alert('Failed to load options');
            }
        },
        error: function() {
            alert('Error loading options');
        }
    });
}

// Function to handle save action within popover form
function change_product_account(index) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change the product account?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            var account_selected = $("#accountList-" + index).val();
            $("#income_account_number-" + index).val(account_selected);
            $('#btnclk-' + index).popover('hide');
        }
        else{
            $('#btnclk-' + index).popover('show');
        }
    });
}

function cancelPopover(index) {
    $('#btnclk-' + index).popover('hide');
}

$('.payment-type-radio').on('change', function () {
   if ($(this).val() === 'Customer Credit' && $(this).is(':checked')) {
      $('#confirm-paynow-btn').addClass('disable-class');
   } else {
      $('#confirm-paynow-btn').removeClass('disable-class');
   }
});


</script>