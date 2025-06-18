<div class="content-body">
    <div class="card">
		<div class="card-header border-bottom">   
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('stockreturn') ?>"><?php echo $this->lang->line('Suppliers') . ' ' . $this->lang->line('Stock Return') ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $this->lang->line('Stock Return')." #".$lastinvoice + 1; ?></li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12">
                    <h5 class="title"><?php echo $this->lang->line('Stock Return')." #".$lastinvoice + 1 ?> </h5>
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
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub">
                                            <?php echo $this->lang->line('Supplier Details') ?> </h3>
                                    </div>
                                    <div class="frmSearch col-sm-12">
                                        <label for="cst" class="col-form-label"><?php echo $this->lang->line('Search Supplier') ?> </label>
                                        <input type="text" class="form-control" name="cst" id="supplier-box"
                                               placeholder="<?php echo $this->lang->line('Enter Customer Name or Mobile Number to search') ?>"
                                               autocomplete="off"/>

                                        <div id="supplier-box-result"></div>
                                    </div>

                                </div>
                                <div id="customer">
                                        <div class="clientinfo">                          
                                            <div id="customer_name">
                                                <?php 
                                                $csd=0;
                                                if($invoice['csd']>0)
                                                {
                                                    $phone = "Phone:";
                                                    $email = "Email:";
                                                    $coma = ',';
                                                    echo '<div id="customer_name"><strong>' . $invoice['name'] . '</strong></div>
                                                       
                                                        <div class="clientinfo">

                                                            <div id="customer_address1"><strong>' . $invoice['address'] . '<br>' . $invoice['city']  .$coma. $invoice['countryname'] . '</strong></div>
                                                        </div>

                                                        <div class="clientinfo">

                                                            <div type="text" id="customer_phone">'.$phone.' <strong>' . $invoice['phone'] . '</strong><br>'.$email.' <strong>' . $invoice['email'] . '</strong></div>
                                                        </div>';
                                                        $csd = ($invoice['csd']>0)? $invoice['csd']:0;
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="customer_id" id="customer_id" value="<?=$csd?>">
                                        <div class="clientinfo">
                                            <div id="customer_address1"></div>
                                        </div>

                                        <div class="clientinfo">
                                            <div type="text" id="customer_phone"></div>
                                        </div>                                    
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                            <div class="inner-cmp-pnl">


                                <div class="form-group row">

                                    <div class="col-sm-12">
                                        <h3  class="title-sub"><?php echo $this->lang->line('Details') ?></h3>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Stock Return Number') ?> </label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o"
                                                                                 aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Invoice #"
                                                   name="invocieno"
                                                   value="<?php echo $lastinvoice + 1 ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <label for="invocieno" class="col-form-label"> <?php echo $this->lang->line('Purchase Order/Receipt(If Any)') ?></label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="icon-file-text-o" aria-hidden="true"></span></div>
                                                <!-- $invoice['tid'] -->
                                                <?php 
                                                $purchase_order_data = "";
                                                $po_class="";
                                                if($invoice['tid'])
                                                {
                                                    $purchase_order_data = $invoice['tid'];
                                                    $po_class="readonly";
                                                } ?>
                                                <input type="text" class="form-control" placeholder="Purchase Order #"   name="purchase_order"  value="<?=$purchase_order_data?>" <?=$po_class?>>
                                            </div>
                                        </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference') ?> </label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"  aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #"
                                                   name="refer">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Order Date') ?> </label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="icon-calendar4" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control"
                                                   placeholder="Billing Date" name="invoicedate"
                                                   data-toggle="datepicker"
                                                   autocomplete="false">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Order Due Date') ?> </label>
                                            <input type="date" class="form-control" id="tsn_due"
                                                   name="invocieduedate"
                                                   placeholder="Due Date" data-toggle="datepicker" autocomplete="false">
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="taxformat"
                                               class="col-form-label"><?php echo $this->lang->line('Tax') ?> </label>
                                        <select class="form-control"
                                                onchange="changeTaxFormat(this.value)"
                                                id="taxformat">
                                            <?php echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                        <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                        <select class="form-control" onchange="changeDiscountFormat(this.value)"  id="discountFormat">
                                            <?php echo $this->common->disclist() ?>
                                        </select>
                                    </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="pterms" class="col-form-label"><?php echo $this->lang->line('Payment Terms'); ?></label>
                                            <select name="pterms" class="selectpicker form-control">
                                                   <option value="">Select Payment Term</option>
                                                    <?php foreach ($terms as $row) {
                                                        $sel="";
                                                        if($row['id']==$invoice['term']){
                                                            $sel = "selected";
                                                        }
                                                        echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title'] . '</option>';
                                                    } ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                                            <label for="Update Stock" class="col-form-label"><?php echo $this->lang->line('Update Stock') ?> </label>
                                            <div class="mt-1">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight1" value="yes">
                                                    <label class="form-check-label" for="customRadioRight1"><?php echo $this->lang->line('Yes') ?></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="update_stock" id="customRadioRight2" value="no" checked>
                                                    <label class="form-check-label" for="customRadioRight2"><?php echo $this->lang->line('No') ?></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none1">
                                            <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                            <select id="s_warehouses" name="s_warehouses" class="selectpicker form-control">
                                                    <?php foreach ($warehouse as $row) {
                                                        if($default_warehouse['id']==$row['id'])
                                                        {
                                                            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                                        }
                                                    } ?>
                                            </select>
                                        </div>
                                    <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo"
                                               class="col-form-label"><?php echo $this->lang->line('Notes') ?> </label>
                                        <textarea class="form-textarea" name="notes" rows="2"></textarea>
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

                                    <!-- ========================= Purchse order detaisl ======================== -->
                                     <?php
                                     if(!empty($invoice['tid']))
                                     { ?>
                                        <input type="hidden" name="purchase_id" id="purchase_id" value="<?=$invoice['iid']?>">
                                        <input type="hidden" name="purchase_reciept_id" id="purchase_reciept_id" value="<?=$invoice['receiptid']?>">
                                        <input type="hidden" name="tid" id="tid" value="<?=$invoice['tid']?>">
                                        <input id="iid" type="hidden"  name="iid" value="0">
                                        <div class="col-12">
                                            <div class="row">
                                                
                                                <div class="col-sm-12 mt-2">
                                                    <h3  class="title-sub"><?php echo $this->lang->line('Purchase Receipt Details') ?></h3>
                                                </div>
                                                <!--erp2024 newly added 29-09-2024  -->
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="Purchase Receipt No." class="col-form-label"><?php echo $this->lang->line('Purchase Receipt No.'); ?></label>
                                                        <input type="text" class="form-control" name="purchase_reciept_number" value="<?php echo $invoice['receiptnumber'] ?>" readonly> 
                                                    </div>                                    
                                                </div>
                                                
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="Bill" class="col-form-label"><?php echo $this->lang->line('Bill Number'); ?></label>
                                                        <input type="text" class="form-control" placeholder="Bill" value="<?php echo $invoice['bill_number'] ?>" readonly> 
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="Bill Date" class="col-form-label"><?php echo $this->lang->line('Bill Date'); ?></label>
                                                        <?php
                                                            $billdate = ($invoice['bill_date']) ? date('d-m-Y',strtotime($invoice['bill_date'])) : "";
                                                        ?>
                                                        <input type="text" name="bill_date" id="bill_date" class="form-control" placeholder="Bill Date" value="<?php echo $billdate; ?>" readonly> 
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <?php
                                                            $srvdate = ($invoice['srvdate']) ? date('d-m-Y',strtotime($invoice['srvdate'])) : "";
                                                        ?>
                                                        <div class="frmclasss"><label for="srvdate" class="col-form-label"><?php echo $this->lang->line('Purchase Receipt Date'); ?></label>
                                                        <input type="text" name="srvdate" id="srvdate" class="form-control" placeholder="Supplier Contact Person" value="<?php echo $srvdate; ?>" readonly>
                                                        </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                                        <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number" value="<?php echo $invoice['customer_contact_number']; ?>" readonly>
                                                        </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Supplier Contact Email'); ?></label>
                                                        <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Supplier Contact Email" value="<?php echo $invoice['customer_contact_email']; ?>" readonly>
                                                        </div>                                    
                                                </div>
                                               
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="frmclasss"><label for="" class="col-form-label"><?php echo $this->lang->line('Purchase Receipt Note'); ?></label>
                                                        <textarea class="form-textarea disable-class-textarea" name="receiptnote" id="receiptnote" readonly ><?php echo $invoice['note']; ?></textarea>
                                                        
                                                        </div>                                    
                                                </div>
                                                <!--erp2024 newly added 29-09-2024 ends -->
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    <!-- ========================= Purchse order detaisl ======================== -->
                                </div>

                            </div>
                        </div>
                        
                    </div>

                 
                    <div id="saman-row">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>

                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="8%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                <?php if(!empty($products[0]['product_id']))
                                { ?>
                                    <th width="8%" class="text-center"><?php echo $this->lang->line('Received')."<br>".$this->lang->line('Quantity'); ?></th>
                                    <th width="8%" class="text-right"><?php echo $this->lang->line('Damaged')."<br>".$this->lang->line('Quantity'); ?></th>
                               <?php } ?>
                               <th width="8%" class="text-left"><?php echo $this->lang->line('Return')."<br>".$this->lang->line('Quantity'); ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th> -->
                                <!-- <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th> -->
                                <th width="10%" class="text-right">
                                    <?php echo $this->lang->line('Amount') ?>
                                    (<?php echo $this->config->item('currency'); ?>)
                                </th>
                                <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            if(!empty($products[0]['product_id']))
                            { 
                            $colspanbig = 7;
                            $colspansmall=5;
                            $i=0;    
                            foreach($products as $item){
                                // if (preg_match('/\(([^()]*)\)[^()]*$/', $item['product_name'], $matches, PREG_OFFSET_CAPTURE)) {
                                //         $productcode = $matches[1][0];
                                //         $start_pos = $matches[0][1];
                                //         $productname = substr($item['product_name'], 0, $start_pos);
                                //     }
                                $productname = $item['product_name'];
                                $productcode = $item['product_code'];
                               ?>
                                <tr>
                                   
                                    <td>
                                        <input type="text" class="form-control disable-class" name="code[]"  id='code-<?=$i?>' value="<?=$productcode?>" readonly placeholder="Enter Product code">
                                    </td> 
                                    <td>
                                        <input type="text" class="form-control productname disable-class" name="product_name[]" placeholder="<?php echo $this->lang->line('Enter Product name') ?>"  id='productname-<?=$i?>' value="<?=$productname?>" readonly>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-center"><?=$item['product_qty_recieved']?></strong>
                                        <input type="hidden" class="form-control disable-class" name="received_qty[]"   id='received_qty-<?=$i?>' value="<?=$item['product_qty_recieved']?>" readonly>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-center"><?=$item['damage']?></strong>
                                        <input type="hidden" name="damage[]"   id="damage-<?=$i?>" value="<?=$item['damage']?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control req amnt" name="product_qty[]" id="amount-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkqty('<?=$i?>'),rowTotal('<?=$i?>'), billUpyog()" autocomplete="off" value="0">
                                    </td>
                                    <td class="text-center">
                                        <strong><?=$item['price']?></strong>
                                        <input type="hidden" class="form-control text-right req prc" name="product_price[]" id="price-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                           autocomplete="off" value="<?=$item['price']?>" readonly>
                                    </td>
                                    <td class="text-right"><strong><span class='ttlText' id="result-<?=$i?>">0.00</span></strong></td>
                                    <td ><button type="button" data-rowid="<?=$i?>" class="btn btn-crud btn-sm btn-default removeProd" title="Remove"> <i class="fa fa-trash"></i> </button></td>
                                    <input type="hidden" name="product_discount[]" id="discount-<?=$i?>" onkeypress="return isNumber(event)" id="discount-<?=$i?>" onkeyup="rowTotal('<?=$i?>'), billUpyog()" value="0">
                                    <input type="hidden" class="form-control vat " name="product_tax[]" id="vat-<?=$i?>"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()" value="0" autocomplete="off">  
                                    <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="0">
                                    <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="0">
                                    <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="0">
                                    <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?=$item['product_id']?>">
                                    <input type="hidden" name="unit[]" id="unit-0" value="">
                                    <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                </tr>
                               <?php
                               $i++;
                            }
                            ?>
                           <?php }
                           else{ 
                                $colspanbig = 4;
                                $colspansmall=3;
                            ?>
                            <tr>
                                <td><input type="text" class="form-control" name="code[]" id='purchasereturncode-0' placeholder="Enter Product code" >
                                </td>
                                <td><input type="text" class="form-control productname" name="product_name[]"
                                           placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                           id='purchasereturnproduct-0'>
                                </td>
                                <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off" value="0"></td>
                                <td><input type="text" class="form-control text-right req prc" name="product_price[]" id="price-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off"></td>
                                <!-- <td><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off"></td>
                                <td class="text-center" id="texttaxa-0">0</td> -->
                                <!-- <td><input type="text" class="form-control discount" name="product_discount[]"
                                           onkeypress="return isNumber(event)" id="discount-0"
                                           onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td> -->
                                <td class="text-right"><strong><span class='ttlText' id="result-0">0</span></strong></td>
                                <td class="text-center">
                                <button type="button" data-rowid="0" class="btn btn-crud btn-sm btn-default removeProd" title="Remove" fdprocessedid="uymmde"> <i class="fa fa-trash"></i> </button>
                                </td>
                                <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                <input type="hidden" name="disca[]" id="disca-0" value="0">
                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                <input type="hidden" name="unit[]" id="unit-0" value="">
                                <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                <input type="hidden" name="product_discount[]" id="discount-0" onkeypress="return isNumber(event)" id="discount-0" onkeyup="rowTotal('0'), billUpyog()"> 
                            </tr>


                                <tr class="last-item-row tr-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-crud btn-secondary" id="addstockreturnproduct">
                                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                    </td>
                                    <td colspan="5" class="no-border"></td>
                                </tr>
                           <?php } ?>

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="<?=$colspanbig?>" align="right" class="no-border"><input type="hidden" value="0" id="subttlform"
                                                                     name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                </td>
                                <td align="left" colspan="2" class="no-border"><span
                                            class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                    <span id="taxr" class="lightMode">0</span></td>
                            </tr>
                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="<?=$colspanbig?>" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Discount') ?> (<?php echo $this->config->item('currency'); ?>)</strong></td>
                                <td align="left" colspan="2" class="no-border"><span
                                            class="currenty lightMode"></span>
                                    <span id="discs" class="lightMode">0</span></td>
                            </tr>

                            <tr class="sub_c d-none" style="display: table-row;">
                                <td colspan="<?=$colspanbig?>" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="left" colspan="2" class="no-border">
                                    <input type="text" class="form-control shipVal" onkeypress="return isNumber(event)"  placeholder="Value" name="shipping" autocomplete="off" onkeyup="billUpyog();">
                                     
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="<?=$colspansmall?>" class="no-border"><?php if ($exchange['active'] == 1){
                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                    <select name="mcurrency"
                                            class="selectpicker form-control">
                                        <option value="0">Default</option>
                                        <?php foreach ($currency as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                        } ?>

                                    </select><?php } ?></td>
                                <td colspan="2" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="left" colspan="2" class="no-border">
                                    <span id="grandtotaltext" class="grandtotaltext"></span>
                                    <input type="hidden" name="total" class="form-control invoiceyoghtml" readonly>

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <!-- <td colspan="2" class="no-border"></td> -->
                                <td colspan="<?=$colspansmall?>" class="no-border"></td>
                                <td align="right" colspan="4" class="no-border">
                                    <button class="btn btn-crud btn-secondary btn-lg" id="stock_return_prepared_btn_draft" name="stock_return_prepared_btn_draft"><?php echo $this->lang->line('Save As Draft') ?></button>
                                    <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn <?=$generatebtn?> <?=$accpetthenhide?>"  value="<?php echo $this->lang->line('Prepared') ?>" id="stock_return_prepared_btn" data-loading-text="Creating...">

                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" value="stockreturn/action" id="action-url">
                    <input type="hidden" value="0" name="person_type">
                    <input type="hidden" value="puchase_search" id="billtype">
                    <input type="hidden" value="0" name="counter" id="ganak">
                    <input type="hidden" value="<?php echo currency($this->aauth->get_user()->loc); ?>" name="currency">
                    <input type="hidden" value="<?= $taxdetails['handle']; ?>" name="taxformat" id="tax_format">

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

                    <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Add Supplier') ?></h4>
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
                                   class="form-control margin-bottom" id="mcustomer_name" name="name">
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
                                   class="form-control margin-bottom crequired" name="email" id="mcustomer_email">
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


                        <div class="col-sm-4">
                            <input type="text" placeholder="City"
                                   class="form-control margin-bottom" name="city" id="mcustomer_city">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" placeholder="Region"
                                   class="form-control margin-bottom" name="region">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" placeholder="Country"
                                   class="form-control margin-bottom" name="country" id="mcustomer_country">
                        </div>

                    </div>

                    <div class="form-group row">


                        <div class="col-sm-6">
                            <input type="text" placeholder="PostBox"
                                   class="form-control margin-bottom" name="postbox">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" placeholder="TAX ID"
                                   class="form-control margin-bottom" name="tax_id" id="tax_id">
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
<script>
   $(document).ready(function() {
        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {  
                // Add your validation rules here
            },
            messages: {
                // Add your custom messages here
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

    function checkqty(id){
      var enteredqty = parseFloat($("#amount-" + id).val()) || 0;       
      var damageqty = parseFloat($("#damage-" + id).val()) || 0;      
      var received_qty = parseFloat($("#received_qty-" + id).val()) || 0;
      var totalqty = received_qty - damageqty;
      if(enteredqty > totalqty){
         $("#amount-" + id).val(0);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is invalid. please check'
         });
      }
    }



    $('#stock_return_prepared_btn').on('click', function(e) {
        e.preventDefault();
        $('#stock_return_prepared_btn').prop('disabled', true);
        var customer_id = $("#customer_id").val();
        var selectedProducts = [];
        var selectedqty = [];

        // Check if customer ID is valid
        if(customer_id == '0'){
            $("#supplier-box").prop('required', true);
        }

        // Collect selected products
        $('.productname').each(function() {
            if($(this).val() != "") {
                selectedProducts.push($(this).val());
            }
        });

        // Collect quantities greater than 0
        $('.amnt').each(function(index) {
            var currentQty = parseFloat($(this).val());
            if(currentQty > 0) {
                selectedqty.push(currentQty);
            }
        });

     

        // Validate the form
        if($("#data_form").valid()) {                 
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            // Check if at least one product is added
            if (selectedProducts.length === 0) {
                Swal.fire({
                    text: "To proceed, please add at least one product",
                    icon: "info"
                });
                $('#stock_return_prepared_btn').prop('disabled', false);
                return;
            }

            // Check if at least one quantity is added
            if (selectedqty.length === 0) {
                Swal.fire({
                    text: "To proceed, please add a return quantity for at least one item",
                    icon: "info"
                });
                $('#stock_return_prepared_btn').prop('disabled', false);
                return;
            }
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create a stock return?",
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
                        url: baseurl + 'stockreturn/action', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            // Uncomment or adjust this for redirection
                            window.location.href = baseurl + 'stockreturn';
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while creating the stock return', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else {
                    $('#stock_return_prepared_btn').prop('disabled', false);
                }
            });

        } else {
            $('#stock_return_prepared_btn').prop('disabled', false);
        }
    });
    $("#stock_return_prepared_btn_draft").on("click", function(e) {
        e.preventDefault();
      //  var formData = $("#data_form").serialize(); 
        var form = $('#data_form')[0];
        var formData = new FormData(form); // Create FormData object

        $.ajax({
            type: 'POST',
            url: baseurl +'stockreturn/draftaction',
            data: formData,
            contentType: false, 
            processData: false,
            success: function(response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }         
                window.location.href = baseurl + 'stockreturn/create?pid='+response.data+'&token='+response.token;
            },
            error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
            }
        });
    });
      
</script>