<div class="content-body">
    <div class="card">
      <div class="card-header border-bottom">

      <?php 
               $status = ($invoice['invoicestatus']) ? $invoice['invoicestatus'] : 'due';
               $inv_transaction_number = ($invoice['inv_transaction_number']) ? $invoice['inv_transaction_number'] : 0;
               $invoicenumber = (($invoice['invoice_number'])) ? $invoice['invoice_number'] : $invoice['tid'];
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $invoicenumber; ?></li>
                </ol>
            </nav>
            <div class="row">
               <div class="col-xl-3 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                  <h4 class="card-title"><?php echo $invoicenumber; ?> 
                  
                  <?php  if($invoice['paymentstatus']!='Draft'){ ?><button class="btn btn-crud btn-sm btn-secondary cancelinvoice-btn"><?php echo $this->lang->line('Cancel Invoice'); ?></button> <?php } ?></h4>
               </div>
               <div class="col-xl-9 col-lg-10 col-md-10 col-sm-12 col-xs-12">  
                  <ul id="trackingbar">
                    <?php if(!empty($trackingdata))
                    {
                        if(!empty($trackingdata['lead_id']))
                        { ?>
                            <li><a href="<?= base_url('invoices/customer_leads?id=' . $trackingdata['lead_id']) ?>" target="_blank">LD #<?= $trackingdata['lead_number']; ?></a></li><?php }
                        if(!empty($trackingdata['quote_number']))
                        { ?>
                            <li><a href="<?= base_url('quote/view?id=' . $trackingdata['quote_number']) ?>" target="_blank">QT #<?= $trackingdata['quote_number']; ?></a></li><?php }
                        if(!empty($trackingdata['salesorder_number']))
                        { ?>
                            <li><a href="<?= base_url('quote/salesorders?id=' . $trackingdata['salesorder_number']) ?>" target="_blank">SO #<?= $trackingdata['salesorder_number']; ?></a></li><?php }
                        if(!empty($trackingdata['deliverynote_number']))
                        { ?>
                            <li><a href="<?= base_url('DeliveryNotes/deliverynote_view?id=' . $trackingdata['deliverynote_number']) ?>" target="_blank">DN #<?= ($trackingdata['deliverynote_number']+1000); ?></a></li><?php }
                   ?> <li class="active">IN #<?php echo $invoice['tid']; ?></li>
                   <?php }
                   ?>
                        
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
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 cmp-pnl">
                                <div id="customerpanel" class="inner-cmp-pnl">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Client Details'); ?></h3>
                                        </div>
                                        <div class="frmSearch col-sm-12"><label for="cst"  class="col-form-label"><?php echo $this->lang->line('Search Client'); ?></label>
                                            <input type="text" class="form-control" name="cst" id="customer-box" placeholder="<?php echo $this->lang->line('Enter Customer Name or Mobile Number to search'); ?>"  autocomplete="off"/>
                                            <div id="customer-box-result"></div>
                                        </div>

                                    </div>
                                    <div id="customer">
                                        <div class="clientinfo">
                                            <?php echo '<input type="hidden" name="customer_id" title="customer_id"  id="customer_id" value="' . $invoice['csd'] . '" data-original-value="' . $invoice['csd'] . '">
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
                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                                <div class="inner-cmp-pnl">
                                    <div class="form-group row">

                                        <div class="col-sm-12">
                                            <h3 class="title-sub"><?php echo $this->lang->line('Invoice Properties') ?></h3>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Invoice Number') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Invoice #"  name="invoice_number" id="invoice_number" value="<?php echo $invoicenumber; ?>" readonly> 
                                                <input type="hidden" class="form-control" placeholder="Invoice #"  name="invocieno" value="<?php echo $invoice['tid']; ?>" readonly> 
                                                <input  type="hidden" name="iid" id="invoiceid" value="<?php echo $invoice['iid']; ?>">
                                                <input type="hidden" class="form-control" placeholder="Invoice #" name="status" value="<?php echo $status ?>" readonly>
                                                <input type="hidden" class="form-control" placeholder="Invoice #" name="transaction_number" id="transaction_number" value="<?php echo $inv_transaction_number ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="refer" class="col-form-label"><?php echo $this->lang->line('Reference') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                     aria-hidden="true"></span></div>
                                                <input type="text" class="form-control" placeholder="Reference #"
                                                       id="refer"   titile="refer" name="refer"
                                                       value="<?php echo $invoice['refer'] ?>"  data-original-value="<?php echo $invoice['refer'] ?>">
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Invoice Date'); ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-calendar4" aria-hidden="true"></span></div>
                                                <input type="text" class="form-control required editdate"
                                                       placeholder="Billing Date" name="invoicedate" autocomplete="false"
                                                       value="<?php echo dateformat($invoice['invoicedate']) ?>">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Invoice Due Date') ?></label>
                                                <input type="date" class="form-control required" id="invocieduedate" name="invocieduedate" placeholder="Due Date" autocomplete="false" value="<?php echo $invoice['invoiceduedate'] ?>" data-original-value="<?php echo $invoice['invoiceduedate'] ?>">
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="taxformat"
                                                   class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                                            <select class="form-control" onchange="changeTaxFormat(this.value)"
                                                    id="taxformat">

                                                <?php echo $taxlist; ?>
                                            </select>
                                        </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                                <label for="discountFormat"
                                                       class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                                <select class="form-control"
                                                        onchange="changeDiscountFormat(this.value)"
                                                        id="discountFormat">
                                                    <?php echo '<option value="' . $invoice['format_discount'] . '">' . $this->lang->line('Do not change') . '</option>'; ?>
                                                    <?php echo $this->common->disclist() ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?> </label>                                
                                                <select id="s_warehouses" name="s_warehouses" class="selectpicker form-control" data-original-value="<?php echo $warehouse; ?>">
                                                    <?php 
                                                    echo '<option value="0">' . $this->lang->line('All') ?></option>
                                                    <?php foreach ($warehouse as $row) {
                                                     //   $sel = ($invoice['store_id']==$row['id'])? "selected":"";
                                                        
                                                       // echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] . '</option>';
                                                       $cid = $row['id'];
                                                       $title = $row['title'];
                                                       $code = $row['code'];
                                                       $sel="";
                                                       if($cid == $invoice['store_id']){
                                                           $sel = "selected";
                                                       }
                                                       echo "<option value='$cid' $sel>$title</option>";
                                                        
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
                                                <label for="pterms" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?> </label>
                                                <select  name="pterms"  class="selectpicker form-control" data-original-value="<?php echo $invoice['termtit'] ?>">
                                                    <?php echo '<option value="' . $invoice['termid'] . '">*' . $invoice['termtit'] . '</option>';
                                                    foreach ($terms as $row) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="notes"  class="col-form-label" ><?php echo $this->lang->line('Note') ?></label>
                                            <textarea class="form-textarea" name="notes" id="notes"  data-original-value="<?php echo $invoice['notes'] ?>" rows="2"><?php echo $invoice['notes'] ?></textarea></div>
                                    

                                        <div class="col-12">
                                            <label class="col-form-label font-13"><strong><?php echo $this->lang->line('Payment Type') ?></strong></label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_type" title="payment type" id="cashOption" data-original-value="<?php echo $invoice['payment_type'] ?>" value="Cash" <?php if($invoice['payment_type'] == 'Cash') { echo 'checked'; } ?>>
                                                    <label class="form-check-label font-13" ><b><?php echo $this->lang->line('Cash') ?></b></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_type" title="payment type" id="creditCardOption" data-original-value="<?php echo $invoice['payment_type'] ?>" value="Credit Card" <?php if($invoice['payment_type'] == 'Credit Card') { echo 'checked'; } ?>>
                                                    <label class="form-check-label font-13" for=""><b><?php echo $this->lang->line('Credit Card') ?></b></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_type" title="payment type" id="chequeOption" data-original-value="<?php echo $invoice['payment_type'] ?>" value="Cheque" <?php if($invoice['payment_type'] == 'Cheque') { echo 'checked'; } ?>>
                                                    <label class="form-check-label font-13" for=""><b><?php echo $this->lang->line('Cheque') ?></b></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="payment_type" title="payment type" id="customerCreditOption" data-original-value="<?php echo $invoice['payment_type'] ?>" value="Customer Credit" <?php if($invoice['payment_type'] == 'Customer Credit') { echo 'checked'; } ?>>
                                                    <label class="form-check-label font-13" for=""><b><?php echo $this->lang->line('Customer Credit') ?></b></label>
                                                </div>
                                        </div>
                                    </div>
                                    <!-- Image upload sections starts-->
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                                        <label for="upfile-0" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
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
                                                    echo "<a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary'>{$icon}</a>&nbsp;";
                                                    echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"deleteitem('{$image['id']}','{$image['file_name']}')\" type='button'><i class='fa fa-trash'></i></button>";
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

                        </div>


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
                                        $accountnumber = ($row['account_number']) ? $row['account_number'] : $row['income_account_number'];
                                        $product_name_with_code = $row['product_name'].'('.$row['product_code'].') - ';
                                        ?>
                                        <tr>        
                                            <td><input type="text" class="form-control code" name="code[]" id="code-<?=$i?>" title='<?=$product_name_with_code?>Code' value="<?=$row['product_code']?>" data-original-value="<?=$row['product_code']?>" >
                                            <input type="hidden" class="form-control" name="income_account_number[]" id="income_account_number-<?=$i?>" value="<?=$accountnumber?>">
                                            <input type="hidden" class="form-control" name="product_cost[]" id="product_cost-<?=$i?>" value="<?=$row['product_cost']?>">
                                            </td>
                                            <td><span class="d-flex"><input type="text" class="form-control product_name wid90per" name="product_name[]"  placeholder="<?php echo $this->lang->line('Enter Product name') ?>" id='productname-<?=$i?>' value="<?=$row['product_name']?>" title='<?=$product_name_with_code?>Product' data-original-value="<?=$row['product_name']?>">&nbsp;<button type="button" title="change account"
                                                class="btn  btn-crud btn-sm btn-secondary"
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
                                                        <div class="text-right"><button type="button" onclick="cancelPopover(<?= $i ?>)" class="btn  btn-crud btn-secondary btn-sm">Cancel</button>&nbsp;<button type="button" onclick="change_product_account(<?= $i ?>)" class="btn  btn-crud  btn-primary btn-sm">Change</button></div>
                                                    </form>'
                                            >
                                                <i class="fa fa-bank"></i>
                                            </button></span></td>

                                            <td class="text-center"><input type="text" class="form-control req amnt product_qty" name="product_qty[]" id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()" autocomplete="off" value="<?=intval($row['qty'])?>" title="<?=$product_name_with_code?>Quantity" data-original-value="<?=$row['qty']?>" ><input type="hidden" class="form-control" name="product_qty_old[]" value="<?=intval($row['qty'])?>"></td>

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
                                                <?php echo $row['discount_type']; ?>
                                                <div class="input-group text-center">
                                                    <select name="discount_type[]" id="discounttype-<?=$i?>" class="form-control" onchange="discounttypeChange('<?=$i?>')" title="<?=$product_name_with_code?>Discount Type" data-original-value="<?=$row['discount_type']?>">
                                                        <option value="Perctype" <?php if($row['discount_type'] =='Perctype'){ echo 'selected'; }?>>%</option>
                                                        <option value="Amttype"  <?php if($row['discount_type'] =='Amttype'){ echo 'selected'; }?>>Amt</option>
                                                    </select>&nbsp;
                                                    <input type="number" min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-<?=$i?>"  autocomplete="off" onkeyup="discounttypeChange('<?=$i?>')" value="<?=$row['discount']?>" title="<?=$product_name_with_code?>Discount Percentage" data-original-value="<?=$row['discount']?>">
                                                    <input type="number"  min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-<?=$i?>" autocomplete="off" onkeyup="discounttypeChange('<?=$i?>')" value="<?=$row['discount']?>" title="<?=$product_name_with_code?>Discount Amount" data-original-value="<?=$row['discount']?>">
                                                </div>  
                                                <strong id="discount-amtlabel-<?=$i?>" class="discount-amtlabel"><?=$row['deliverytotaldiscount']?></strong>
                                                <div><strong id="discount-error-<?=$i?>"></strong></div>                                    
                                            </td>

                                            <td class="text-right">
                                                <strong><span class='ttlText' id="result-<?=$i?>"><?=$row['subtotal']?></span></strong></td>
                                            <td class="text-center">
                                                <button onclick='producthistory("<?=$i?>")' type="button" class="btn  btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i> </button>&nbsp;
                                                <button onclick='single_product_details("<?=$i?>")' type="button" class="btn  btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>
                                                <button type="button" data-rowid="<?=$i?>" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button>
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
                                ?>
                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-crud btn-secondary" id="row_btn">
                                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                    </td>
                                    <td colspan="7" style="border:none !important;"></td>
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
                                    <td colspan="9" class="reverse_align no-border">
                                        <input type="hidden" value="<?php echo edit_amountExchange_s($invoice['subtotal'], $invoice['multi'], $this->aauth->get_user()->loc) ?>" id="subttlform" name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border"><span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                        <span id="taxr" class="lightMode"><?php echo edit_amountExchange_s($invoice['tax'], $invoice['multi'], $this->aauth->get_user()->loc) ?></span>
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
                                    <td colspan="9" class="reverse_align no-border">
                                        <strong><?php echo $this->lang->line('Shipping') ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                        <input type="text" class="form-control shipVal text-right" onkeypress="return isNumber(event)" placeholder="0.00" title="shipping" name="shipping" autocomplete="off" onkeyup="billUpyog()"
                                        value="<?=$invoice['shipping']?>" data-original-value="<?=$invoice['shipping']?>">
                                    </td>
                                </tr>
                                <!-- <tr class="sub_c" style="display: table-row;">
                                    <td colspan="5" class="no-border"></td>
                                    <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                            (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                        <span id="grandamount"><?=number_format($subtotal,2)?></span>
                                    </td>
                                </tr> -->
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="<?=$colspan?>" align="right" class="no-border">
                                        <strong><?php echo $this->lang->line('Order Discount') ?></strong></td>
                                    <td align="right" colspan="1" class="no-border">
                                    <input type="number" class="form-control text-right" onkeypress="return isNumber(event)"  placeholder="0.00"  title="order_discount" name="order_discount" id="order_discount" autocomplete="off" onkeyup="orderdiscount()" value="<?=$invoice['order_discount']?>" data-original-value="<?=$invoice['order_discount']?>">
                                    <input type="hidden" class="form-control text-right"  name="old_order_discount" id="old_order_discount" autocomplete="off"  value="<?=$invoice['order_discount']?>" >

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
                                    <td colspan="7" class="reverse_align no-border"><strong><?php echo $this->lang->line('Total') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border">
                                        
                                    <span id="grandtotaltext"><?= number_format($invoice['total'],2); ?></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml"  value="<?= $invoice['total']?>"  readonly>
                                    </td>
                                </tr>
                                

                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="6" class="no-border">
                                        <?php 
                                        if($invoice['paymentstatus']!='Draft')
                                        { 
                                            echo '<input type="submit" class="btn btn-crud btn-lg btn-secondary cancelinvoice-btn" value="'.$this->lang->line('Cancel Invoice').'" >';
                                        }
                                        if( ($invoice['paymentstatus']!='Deleted') && (($invoice['paymentstatus']!='partial') || ($invoice['paymentstatus']!='paid')))
                                        {
                                            echo '&nbsp;<input type="submit" class="btn btn-crud btn-lg btn-secondary deleteinvoice-btn" value="'.$this->lang->line('Delete Invoice').'" >';
                                        }
                                        ?>
                                    </td>
                                    <td class="reverse_align no-border" colspan="6">
                                        <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Update') ?>" id="invoice-confirm-btn"  data-loading-text="Updating...">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
                        <input type="hidden" value="invoices/editaction" id="action-url">
                        <input type="hidden" value="search" id="billtype">
                        <input type="hidden" value="<?php echo $i-1; ?>" name="counter" id="ganak">
                        <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
                        <input type="hidden" value="<?= $this->common->taxhandle_edit($invoice['taxstatus']) ?>"  name="taxformat" id="tax_format">
                        <input type="hidden" value="<?= $invoice['format_discount']; ?>" name="discountFormat" id="discount_format">
                        <input type="hidden" value="<?= $invoice['taxstatus']; ?>" name="tax_handle" id="tax_status">
                        <input type="hidden" value="yes" name="applyDiscount" id="discount_handle">
                        <input type="hidden" value="<?php
                        $tt = 0;

                        if($invoice['shipping']==0.00) $invoice['shipping']=1;
                        if ($invoice['ship_tax_type'] == 'incl') $tt = @number_format(($invoice['shipping'] - $invoice['ship_tax']) / $invoice['shipping'], 2, '.', '');
                        echo amountFormat_general(@number_format((($invoice['ship_tax'] / $invoice['shipping']) * 100) + $tt, 3, '.', '')); ?>"
                               name="shipRate" id="ship_rate">
                        <input type="hidden" value="<?= $invoice['ship_tax_type']; ?>" name="ship_taxtype"
                               id="ship_taxtype">
                        <input type="hidden" value="<?= amountFormat_general($invoice['ship_tax']); ?>" name="ship_tax"
                               id="ship_tax">
                        <?php
                        if(is_array($custom_fields)){
                            foreach ($custom_fields as $row) {
                                if ($row['f_type'] == 'text') { ?>
                                    <div class="form-group row">

                                        <label class="col-sm-2 col-form-label"
                                               for="document_id"><?= $row['name'] ?></label>

                                        <div class="col-sm-8">
                                            <input type="text" placeholder="<?= $row['placeholder'] ?>"
                                                   class="form-control margin-bottom b_input"
                                                   name="custom[<?= $row['id'] ?>]"
                                                   value="<?= @$row['data'] ?>">
                                        </div>
                                    </div>


                                <?php }


                            }
                        }
                        ?>
                </form>
            </div>


        </div>



<script type="text/javascript"> 
   // erp2025 09-01-2025 start
const changedFields = {};
$(document).ready(function() {
    // Add event listeners to all input fields
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
      // Function to initialize Select2 and track changes
      function initializeSelect2(selector) {
          // Initialize Select2

          // Attach change event listener to track changes
          $(selector).each(function () {
              const element = $(this);
              const fieldId = element.attr('id');
              const originalValue = element.data('original-value'); // Access `data-original-value`
              var label = $('label[for="' + fieldId + '"]');
              var field_label = label.text();
              element.on('change', function () {
                  const newValue = element.val();
                  if (originalValue != newValue) {
                      changedFields[fieldId] = {
                          oldValue: originalValue,
                          newValue: newValue,
                          fieldlabel: field_label,
                      }; // Store the original and new value
                  } else {
                      delete changedFields[fieldId]; // Remove if no change
                  }
              });
          });
      }
      initializeSelect2('.multi-select');
         // erp2025 09-01-2025 ends

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
            text: "Do you want to update invoice?",
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
                    url: baseurl + 'invoices/editaction', // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        window.location.href = baseurl + 'invoices';                
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


$('.cancelinvoice-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.cancelinvoice-btn').prop('disabled', true);

    var selectedProducts1 = [];
    $('.code').each(function() {
        if ($(this).val() !== "") {
            selectedProducts1.push($(this).val());
        }
    });

    // Validate the form

    if (selectedProducts1.length === 0) {
        Swal.fire({
            text: "To proceed, please add at least one item",
            icon: "info"
        });
        $('.cancelinvoice-btn').prop('disabled', false);
        return;
    }          

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to cancel the invoice?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,  
        focusCancel: true,      
        allowOutsideClick: false  // Disable outside click
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurl + 'invoices/cancelinvoiceaction', // Replace with your server endpoint
                type: 'POST',
                data: {
                    'invoiceid': $("#invoiceid").val()
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




$('.editdate').datepicker({
    autoHide: true,
    format: '<?php echo $this->config->item('dformat2'); ?>'
});

// window.onload = function () {
//     billUpyog();
// };

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

$(document).ready(function() {
  // Initialize popover
  $('[data-toggle="popover"]').popover();

  // Handle popover shown event to load options
  $('[data-toggle="popover"]').on('shown.bs.popover', function() {
    const accountList = $('#accountList');
    if (accountList.children().length === 0) {
        // Make AJAX request to load options
        $.ajax({
            url: baseurl + 'invoices/load_product_accounts',
            method: 'POST',
            dataType: 'json',
            data: {
            'actheader': 'Income'
            },
            success: function(response) {
            if (response.status === 'Success') {
                // Directly insert the HTML options
                accountList.html(response.data);
            } else {
                alert('Failed to load options');
            }
            },
            error: function() {
            alert('Failed to load options');
            }
        });
    }

  });

  // Toggle popover on button click
  $('[data-toggle="popover"]').on('click', function(e) {
    e.stopPropagation(); // Prevent immediate closure
    const $this = $(this);

    // Close other open popovers
    $('[data-toggle="popover"]').not($this).popover('hide');
    
    // Toggle the current popover
    $this.popover('toggle');
  });

  // Close popover on clicking outside
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.popover, [data-toggle="popover"]').length) {
      $('[data-toggle="popover"]').popover('hide');
    }
  });
});

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

$('.deleteinvoice-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('.deleteinvoice-btn').prop('disabled', true);

    var selectedProducts1 = [];
    $('.code').each(function() {
        if ($(this).val() !== "") {
            selectedProducts1.push($(this).val());
        }
    });

    // Validate the form

    if (selectedProducts1.length === 0) {
        Swal.fire({
            text: "To proceed, please add at least one item",
            icon: "info"
        });
        $('.deleteinvoice-btn').prop('disabled', false);
        return;
    }          

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to cancel the invoice?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,  
        focusCancel: true,      
        allowOutsideClick: false  // Disable outside click
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseurl + 'invoices/deleteinvoiceaction', // Replace with your server endpoint
                type: 'POST',
                data: {
                    'invoiceid': $("#invoiceid").val(),
                    'invoice_number': $("#invoice_number").val(),
                },
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    if (response.status === 'Success') {
                            window.location.href = baseurl + 'invoices';
                    } else {
                        Swal.fire('Error', 'Failed to delete the invoice', 'error');
                        $('.deleteinvoice-btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while cancelling the invoice', 'error');
                    console.log(error); // Log any errors
                    $('.deleteinvoice-btn').prop('disabled', false);
                }
            });
        } else {
            // Re-enable the button if the user cancels
            $('.deleteinvoice-btn').prop('disabled', false);
        }
    });
   
});
</script>
