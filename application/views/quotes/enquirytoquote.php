<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices/leads') ?>"><?php echo $this->lang->line('Leads') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices/customer_leads?id='.$leadid.'') ?>"><?php echo $this->lang->line('Lead')." #".($enquirymain['lead_number']); ?></a></li>
                    <!-- <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Lead')." #".($enquirymain['id']); ?></li> -->
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl-6">
                <h4 class="card-title"><?php //echo $this->lang->line('Convert Lead to Quote');
                    echo "Convert Lead #".($enquirymain['lead_number'])." To Quote - Landing"; ?></h4>
                </div>
                <div class="col-xl-6">
                    <?php 
                  
                    if(empty($enquirymain['accepted_dt']))
                    { 
                        $clsdisable ='';    
                    ?>
                    <div class="btn-group alert alert-danger text-center" role="alert">
                        <?php echo $this->lang->line("Please accept the lead"); ?>
                    </div>
                    <?php }
                    else{
                        $clsdisable = 'disable-class';
                        ?>
                            <div class="btn-group alert alert-success text-center" role="alert">
                                 <?php echo $this->lang->line("Lead Accepted"); ?>
                            </div>
                        <?php                            
                    } ?>
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
                <?php
                    $approvedcls = "disable-class";
                    if($enquirymain['enquiry_status']=='Accepted')
                    {
                        $approvedcls = "";
                    }               
                ?>
                <form method="post" id="data_form">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="">
                                    <div class="fcol-sm-12">
                                        <h3 class="sub-title">
                                            <?php echo $this->lang->line('Bill To') ?> 
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="leadid" id="leadid"  value="<?=$leadid;?>"/>
                                <input type="hidden" name="config_tax" id="config_tax" value="<?=$configurations['config_tax']?>">                                                                    
                                <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
                                <input type="hidden" class="form-control" name="customer_id" id="customer_id" autocomplete="off" value="<?=$enquirymain['customer_id'];?>"/>
                                <div id="customer" class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-<?=$i?>01">
                                        <label for="customer_name" class="col-form-label" id="customerLabel"><?php echo $this->lang->line('Customer Name'); ?></label>
                                        <input type="text" class="form-control customer_name" name="customer_name" id="customer-search" placeholder="Customer Name"  autocomplete="off" readonly value="<?=$enquirymain['customer_name']?>"/>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-<?=$i?>01">
                                            <div class="frmclasss"><label for="customer_phone" class="col-form-label"><?php echo 'Phone'; ?></label>
                                            <input type="number" class="form-control" name="customer_phone" id="customer_phone" placeholder="Contact Number" autocomplete="off" value="<?=$enquirymain['customer_phone'];?>" readonly/>
                                        </div>                                    
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-<?=$i?>01">
                                            <div class="frmclasss"><label for="customer_email" class="col-form-label"><?php echo 'Email'; ?></label>
                                            <input type="text" class="form-control" name="customer_email" id="customer_email"placeholder="Contact Email" autocomplete="off" value="<?=$enquirymain['customer_email'];?>" readonly/>
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 mb-<?=$i?>01">
                                            <div class="frmclasss"><label for="customer_address"  class="col-form-label"><?php echo 'Address'; ?></label>
                                                <textarea name="customer_address" id="customer_address" class="form-control" autocomplete="off" readonly><?=$enquirymain['customer_address'];?></textarea>
                                        </div>                                    
                                    </div> 
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 d-none mb-<?=$i?>01">
                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                        
                                         <select id="s_warehouses"     class="selectpicker form-control">
                                            <?php echo $this->common->default_warehouse();
                                            echo '<option value="0">' . $this->lang->line('All') ?></option><?php foreach ($warehouse as $row) {
                                                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>

                                        </select>
                                    </div>
                                    <!-- erp2024 new section starts -->
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 d-none mb-<?=$i?>01">
                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?></label>
                                        <select name="pterms" class="selectpicker form-control">
                                            <option value="">Select Payment Term</option>
                                            <?php foreach ($terms as $row) {
                                                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-<?=$i?>01">
                                        <label for="employee" class="col-form-label"><?php if (isset($employee)){
                                        echo $this->lang->line('Assigned To Employee')
                                        ?></label>
                                        <select name="employee" class="form-control" readonly>
                                        <?php 
                                        //$enquirymain['assigned_to']
                                        foreach ($employee as $row) {
                                           
                                            if ($enquirymain['assigned_to'] == $row['id']) {
                                                echo '<option value="' . $row['id'] . '"';
                                                echo ' selected';                                               
                                                echo '>' . $row['name'] . '</option>';
                                            }
                                           // echo '<option value="' . $row['id'] . '">' . $row['name'] . ' (' . $row['name'] . ')</option>';
                                        } ?>

                                    </select><?php } ?>
                                    </div>


                                     <!-- erp2024 new section ends -->
                                      <!--erp2024 newly added 29-09-2024  -->
                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?><span class="compulsoryfld">*</span></label>
                                        <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number" value="<?=$enquirymain['customer_reference_number']?>" readonly>
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                        <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person" value="<?=$enquirymain['customer_contact_person']?>" readonly>
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                                        <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number" value="<?=$enquirymain['customer_contact_number']?>" readonly>
                                        </div>                                    
                                    </div>
                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                        <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email" value="<?=$enquirymain['customer_contact_email']?>" readonly>
                                        </div>                                    
                                    </div>
                                    <!--erp2024 newly added 29-09-2024 ends -->
                                </div>


                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="inner-cmp-pnl">

                                <div class="form-group1 row">

                                    <div class="col-sm-12">
                                        <h3 class="sub-title"><?php echo $this->lang->line('Quote Properties') ?></h3>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12"><label for="invocieno"
                                                                 class="col-form-label"><?php echo $this->lang->line('Quote Number') ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o"
                                                                                 aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Quote #"
                                                   name="invocieno"
                                                   value="<?php echo $lastinvoice + 1 ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 d-none"><label for="invocieno"
                                                                 class="col-form-label"> <?php echo $this->lang->line('Reference') ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                 aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #"
                                                   name="refer">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 d-none">
                                        <label for="invociedate" class="col-form-label"> <?php echo $this->lang->line('Quote Date') ?><span class="compulsoryfld">*</span></label>
                                        <input type="date" class="form-control required"   placeholder="Billing Date" name="invoicedate" autocomplete="false" max="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>" readonly>
                                    </div>
                                   
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                        <label for="invocieno"  class="col-form-label"> <?php echo $this->lang->line('Reference') ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"
                                                                                 aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #"
                                                   name="refer">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label for="invocieduedate"  class="col-form-label"><?php echo $this->lang->line('Deadline For Quote') ?><span class="compulsoryfld">*</span></label>
                                        <input type="date" class="form-control required" name="invocieduedate" min="<?=date('Y-m-d')?>"  placeholder="Due Date" autocomplete="false">
                                    </div>
                                    
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 d-none">
                                        <label for="taxformat"
                                               class="col-form-label"> <?php echo $this->lang->line('Tax') ?></label>
                                        <select class="form-control"
                                                onchange="changeTaxFormat(this.value)"
                                                id="taxformat">
                                            <?php echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 d-none">
                                            <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                            <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                    id="discountFormat">
                                                <?php echo $this->common->disclist() ?>
                                            </select>
                                    </div>
                                    <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Quote Note') ?></label>
                                        <textarea class="form-textarea" name="notes" rows="2"></textarea>
                                    </div>
                                    
                                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="toAddInfo"
                                            class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                                        <textarea class="summernote1 form-textarea" name="propos" rows="2"></textarea>
                                    </div>
                                    
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo" class="col-form-label"></label>
                                        <button type="button" class="btn btn-sm btn-secondary mt-3 d-none" id="attachment-btn"><i class="fa fa-paperclip" aria-hidden="true"></i> Add Attachment</button>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- <div class="row">
                        <div class="col-6">
                            <label for="toAddInfo"
                                   class="col-form-label"><?php echo $this->lang->line('Proposal Message') ?></label>
                            <textarea class="summernote1 form-textarea" name="propos" id="contents" rows="2"></textarea>
                        </div>
                    </div> -->

                    <div id="saman-row">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                <th width="12%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
                                    <th width="22%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Name') ?></th>
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
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
                            </thead>
                            <tbody>
                            <?php 
                            $i=0;
                            $grandtotal_amt=0;
                            $discounttotal_amt=0;
                            $taxtotal_amt =0;
                            $totalprdts = (!empty($products))?count($products):0;
                            if(!empty($products)){
                                
                                foreach($products as $product){ 
                                    $discounttotal_amt= $discounttotal_amt + $product['totaldiscount'];
                                    $taxtotal_amt = $product['totaltax'] + $taxtotal_amt;                                    
                                    $grandtotal_amt= $product['subtotal'] + $grandtotal_amt;
                                    if($configurations["config_tax"]!="0"){        
                                        $taxtd = '<td class="text-center">
                                            <div class="text-center">
                                                
                                                <input type="hidden" class="form-control" name="product_tax[]" id="vat-' . $i . '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="' . amountFormat_general($row['tax']) . '">
                                                    <strong id="taxlabel-' . $i . '"></strong>&nbsp;<strong  id="texttaxa-' . $i . '">' . amountExchange_s($row['totaltax'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                            </div>
                                        </td>';
                                    } 
                                ?>
                                <tr>
                                <td><input type="text" class="form-control" name="code[]" id='code-<?=$i?>' value="<?php echo $product['productcode']; ?>" required readonly>

                                <td><input type="text" class="form-control" name="product_name[]" id='leadproductname-<?=$i?>' onkeyup="leadedit_autocomplete(<?=$i?>)" value="<?php echo $product['productname']; ?>" required readonly>


                                <input  type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-<?=$i?>" value="<?php echo $product['max_disrate'];?>">
                                </td>
                                <td><input type="text" class="form-control req amnt product_qty" name="product_qty[]" id="amount-<?=$i?>"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                           autocomplete="off" value="<?php echo intval($product['qty']);?>"></td>
                                <td class="text-center"><strong id="onhandQty-<?=$i?>"><?php echo $product['onhand'];?></strong></td>
                                <td class="text-right"><strong id="pricelabel-<?=$i?>"><?php echo $product['price'];?></strong>
                                <input type="hidden" class="form-control req prc" name="product_price[]" id="price-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()" autocomplete="off" value="<?php echo $product['price'];?>"></td>
                                <?php 
                                        echo '<td class="text-right">
                                        <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-' . $i . '" onkeypress="return isNumber(event)" autocomplete="off" value="' .$product['lowest_price']. '">
                                        <strong id="lowestpricelabel-' . $i . '">' .$product['lowest_price']. '</strong>
                                        </td>';
                                        //Verify that tax is enabled
                                        echo $taxtd;
                                        echo '<td class="text-center"><strong id="maxdiscountratelabel-' . $i . '">' .$product['max_disrate']. '</strong></td>';                              
                                        if($product['discount_type']=='Perctype'){
                                            $percsel = "selected";
                                            $amtsel = "";
                                            $perccls = '';
                                            $amtcls = 'd-none';
                                            $disperc = amountFormat_general($product['discount']);
                                            $disamt = 0;
                                        }
                                        else{
                                            $amtsel = "selected";
                                            $percsel = "";
                                            $perccls = 'd-none';
                                            $amtcls = '';
                                            $disamt = amountFormat_general($product['discount']);
                                            $disperc = 0;
                                        }
                                        echo '<td class="text-center" >
                                                <div class="input-group text-center">
                                                    <select name="discount_type[]" id="discounttype-' . $i . '" class="form-control" onchange="discounttypeChange(' . $i . ')">
                                                        <option value="Perctype" '.$percsel.'>%</option>
                                                        <option value="Amttype" '.$amtsel.'>Amt</option>
                                                    </select>&nbsp;
                                                    <input type="number"  min="0" class="form-control discount '.$perccls.'" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-' . $i . '"  autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disperc. '">
                                                    <input type="number"  min="0" class="form-control discount '.$amtcls.'" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$disamt. '">
                                                </div>                                    
                                                <strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel">Amount : ' . amountExchange_s($product['totaldiscount'], $invoice['multi'], $this->aauth->get_user()->loc) . '</strong>
                                                <div><strong id="discount-error-' . $i . '"></strong></div>                                    
                                            </td>';   ?>
                                <td class="text-right"><strong><span class='ttlText' id="result-<?=$i?>"><?php echo $product['subtotal'];?></span></strong></td>
                                <?php 
                                    echo '<td class="text-center"><button onclick="producthistory('.$i.')" type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button>&nbsp;<button onclick="single_product_details(' . $i . ')" type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button></td>';
                                ?>
                                <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="<?php echo $product['totaltax'];?>">
                                <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="<?php echo $product['totaldiscount'];?>">
                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="<?php echo $product['subtotal'];?>">
                                <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="<?php echo $product['pid'];?>">
                                <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="<?php echo $product['unit'];?>">
                                <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="<?=$product['productcode']?>">
                            </tr>
                            <!-- <tr>
                                <td colspan="9"><textarea id="dpid-<?=$i?>" class="form-control" name="product_description[]"
                                                          placeholder="<?php echo $this->lang->line('Enter Product description'); ?>"
                                                          autocomplete="off"><?php echo $product['productdes'];?></textarea><br></td>
                            </tr> -->
                            <?php
                            $i++;
                                }
                                
                            }
                            else{
                            ?>
                                    <tr>
                                        <td><input type="text" class="form-control" name="product_name[]"
                                                placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                                id='productname-<?=$i?>'>
                                        </td>
                                        <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-<?=$i?>"
                                                onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                                autocomplete="off" value="1"></td>
                                        <td class="text-center"><strong id="onhandQty-<?=$i?>"></strong></td>
                                        <td><input type="text" class="form-control req prc" name="product_price[]" id="price-<?=$i?>"
                                                onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                                autocomplete="off"></td>
                                        <td><input type="text" class="form-control vat " name="product_tax[]" id="vat-<?=$i?>"
                                                onkeypress="return isNumber(event)" onkeyup="rowTotal('<?=$i?>'), billUpyog()"
                                                autocomplete="off"></td>
                                        <td class="text-center" id="texttaxa-<?=$i?>">0</td>
                                        <td><input type="text" class="form-control discount" name="product_discount[]"
                                                onkeypress="return isNumber(event)" id="discount-<?=$i?>"
                                                onkeyup="rowTotal('<?=$i?>'), billUpyog()" autocomplete="off"></td>
                                        <td><span class="currenty"><?php echo $this->config->item('currency'); ?></span>
                                            <strong><span class='ttlText' id="result-<?=$i?>">0</span></strong></td>
                                        <td class="text-center">

                                        </td>
                                        <input type="hidden" name="taxa[]" id="taxa-<?=$i?>" value="0">
                                        <input type="hidden" name="disca[]" id="disca-<?=$i?>" value="0">
                                        <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-<?=$i?>" value="0">
                                        <input type="hidden" class="pdIn" name="pid[]" id="pid-<?=$i?>" value="0">
                                        <input type="hidden" name="unit[]" id="unit-<?=$i?>" value="">
                                        <input type="hidden" name="hsn[]" id="hsn-<?=$i?>" value="">    
                                    </tr>
                                  
                            <?php } ?>

                            <tr class="last-item-row sub_c tr-border d-none">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-secondary" id="lead_create_btn">
                                        <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>
                            <?php 
                                if($configurations['config_tax']!='0'){ ?>
                                    <tr class="sub_c no-border" style="display: table-row;">
                                        <td colspan="7" align="right" class="no-border"><input type="hidden" value="0" id="subttlform"
                                                                            name="subtotal"><strong><?php echo $this->lang->line('Total Tax').'('.$this->config->item('currency').')'; ?></strong>
                                        </td>
                                        <td align="left" colspan="2" class="no-border">
                                            <span id="taxr" class="lightMode"><?php echo $taxtotal_amt;?></span></td>
                                    </tr>
                            <?php } ?>
                            <tr class="sub_c" style="display: table-row; ">
                                <td colspan="9" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Discount').'('.$this->config->item('currency').')'; ?></strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode"><?php echo number_format($discounttotal_amt,2);?></span></td>
                            </tr>
                            
                            <tr class="sub_c d-none" style="display: table-row; ">
                                <td colspan="8" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                    onkeypress="return isNumber(event)"
                                                                    placeholder="Value"
                                                                    name="shipping" autocomplete="off"
                                                                    onkeyup="billUpyog()">
                                    ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> )
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="3" class="no-border">
                                    <br><?php if ($exchange['active'] == 1){
                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                    <select name="mcurrency"
                                            class="selectpicker form-control">
                                        <option value="0">Default</option>
                                        <?php foreach ($currency as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                        } ?>

                                    </select><?php } ?>
                                </td>
                                <td colspan="6" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandtotaltext"><?php echo number_format($grandtotal_amt,2);?></span>
                                    <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" readonly="" value="<?php echo $grandtotal_amt;?>">

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <!-- erp2024 removed section starts -->
                                <!-- <td colspan="2"><?php echo $this->lang->line('Payment Terms') ?> 
                                    <select name="pterms" class="selectpicker form-control">
                                        <?php foreach ($terms as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                        } ?>
                                    </select>
                                </td> -->
                                <td colspan="2" class="no-border">
                                    <button type="button" class="btn btn-lg btn-secondary revert-btncolor" id="completion-btn"><?php echo $this->lang->line('Revert To') ?></button>&nbsp;
                                </td>
                                <!-- erp2024 removed section ends -->
                               
                                <td align="right" colspan="8" class="no-border">
                                   
                                    <button type="button" class="btn btn-lg btn-secondary <?=$clsdisable?>" id="lead-accept-btn">Accept Lead</button>&nbsp;
                                    <i class="fa fa-forward" aria-hidden="true"></i>
                                    <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn <?=$approvedcls?>" value="<?php echo $this->lang->line('Convert Lead to Quote'); ?>"  id="lead-to-quote-btn" data-loading-text="Creating...">

                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" value="quote/action" id="action-url">
                    <input type="hidden" value="search" id="billtype">
                    <input type="hidden" value="<?=$totalprdts?>" name="counter" id="ganak">
                    <input type="hidden" value="<?php echo $this->config->item('currency'); ?>" name="currency">
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <form method="post" id="product_action" class="form-horizontal">
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


                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="city" id="mcustomer_city">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="Region" id="region"
                                           class="form-control margin-bottom" name="region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="country" id="mcustomer_country">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="PostBox" id="postbox"
                                           class="form-control margin-bottom" name="postbox">
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="Company"
                                           class="form-control margin-bottom" name="company">
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
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


                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="City"
                                           class="form-control margin-bottom" name="shipping_city" id="mcustomer_city_s">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="Region" id="shipping_region"
                                           class="form-control margin-bottom" name="shipping_region">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <input type="text" placeholder="Country"
                                           class="form-control margin-bottom" name="shipping_country" id="mcustomer_country_s">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
                invoicedate: { required: true },
                invocieduedate: { required: true },
                source_of_enquiry: { required: true },
                due_date: { required: true },
                due_date: { required: true },
                customer_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                invoicedate: "Enter date",
                invocieduedate: "Enter Quote Validity Due Date",
                customer_email: "Enter Email",
                source_of_enquiry: "Select a source",
                due_date: "Enter a valid date"
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

        $('#lead-to-quote-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#lead-to-quote-btn').prop('disabled', true); // Disable button to prevent multiple submissions
            var selectedProducts1 = [];
            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                $('.product_qty').each(function(index) {
                    var currentQty = parseFloat($(this).val());
                    if (!isNaN(currentQty) && currentQty > 0) {
                        selectedProducts1.push(currentQty);
                    }
                });
                if (selectedProducts1.length === 0) {
                    Swal.fire({
                        text: "To proceed, please add a quantity for at least one item",
                        icon: "info"
                    });
                    $('#lead-to-quote-btn').prop('disabled',false);
                    return;
                }
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to convert lead to quote?",
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
                            url: baseurl + 'quote/action', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                // window.location.href = baseurl + 'quote/view?id='+response.quote; 
                                window.location.href = baseurl + 'quote'; 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#lead-to-quote-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#lead-to-quote-btn').prop('disabled', false);
            }
        });

        
        // leadid
        $.ajax({
                url: baseurl + 'quote/alreadyconverted_or_not',
                type: 'POST',
                data: {
                    "leadid" : $("#leadid").val()
                },
                dataType: "json",
                success: function(response) {
                    console.log(response.data);
                   if(response.data=="Closed"){
                        $('#lead-to-quote-btn').addClass("disable-class");
                   }
                   else{
                    // $('#lead-to-quote-btn').removeClass("disable-class");
                   }
                   //$('#lead-to-quote-btn')
                   
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                    console.log(error); // Log any errors
                }
        });

    });

    
    $("#completion-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to revert this lead now?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'quote/lead_reassigned',
                    data: {
                        "leadid" : $("#leadid").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = baseurl + 'invoices/leads';
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    $("#lead-accept-btn").on('click', function(){
        Swal.fire({
        title: "Are you Sure ?",
        "text":"Do yo want to accept this lead now?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed!',
        cancelButtonText: "No, cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'quote/lead_accept',
                    data: {
                        "leadid" : $("#leadid").val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    $("#attachment-btn").on('click',function(){
        Swal.fire({
        title: "Coming Soon",
        icon: "info",
        });
    });
</script>
