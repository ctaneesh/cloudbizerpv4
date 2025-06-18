<div class="content-body" >
<div class="card" >
   <div class="card-header border-bottom">
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
               <li class="breadcrumb-item"><a href="<?= base_url('purchase/create?id=' . $purchasemasterdata['id']) ?>"><?php echo $this->lang->line('Purchase Order')." #".$purchasemasterdata['tid']; ?></a></li>               
               <li class="breadcrumb-item"><a href="<?= base_url('Invoices/stockreciepts') ?>"><?php echo $this->lang->line('Stock Reciepts') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo " #".$srvNumber; ?></li>
            </ol>
      </nav>
      <div class="row">
      <div class="col-12">
               <div class="row">
                  <div class="col-md-4">
                     <div class="fcol-sm-12">
                        <h3 class="sub-title">
                           <?php echo $this->lang->line('Purchase Items Receipt')." #".$srvNumber; ?>
                        </h3>
                     </div>
                  </div>
                  
                  <!-- <div class="col-md-6 text-right">
                     <div class="fcol-sm-12">
                        <h6 class="sub-title">
                           <?php echo "Purchase Order : <b>#" .$purchasemasterdata['tid']. "<br></b> Purchase Order Date : <b>".dateformat($purchasemasterdata['invoicedate'])."</b>"; ?> 
                        </h6>
                     </div>
                  </div> -->

                  <div class="col-md-4"></div>
                    
                     <div class="col-md-4">
                        <!-- <div class="fcol-sm-12">
                            <h6 class="title-sub">
                                <?php //echo "Purchase Order : <b> #" .$purchaseorderdata['tid']. "</b><br> Purchase Order Date : <b>".dateformat($purchaseorderdata['purchasemasterdatadate'])."</b>"; ?> 
                            </h6>
                        </div> -->
                        <ul id="trackingbar">
                                    <li style="width:30% !important;"><a href="<?= base_url('purchase/create?id=' . $purchasemasterdata['id']) ?>" target="_blank">PO #<?= $purchasemasterdata['tid'] ?></a></li>
                                
                                    <li class="active" style="width:30% !important;">PR #<?php echo $srvNumber; ?></li>
                        </ul> 
                    </div>

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
<form method="post" id="data_form" enctype="multipart/form-data">
   <div class="card-content">
      <div id="notify" class="alert alert-success" style="display:none;">
         <a href="#" class="close" data-dismiss="alert">&times;</a>
         <div class="message"></div>
      </div>
      <div class="card-body" >
         <div class="row" >
            
            <style>
               .error1{
               border: 1px solid #ff0000 !important;
               }
            </style>
            <?php //echo "<pre>"; print_r($purchasemasterdata); //print_r($purchaseitemsdata);
               ?>
            <div class="col-12 mb-1">
               <input type="hidden" name="purchase_id" id="purchase_id" value="<?=$purchasemasterdata['id']?>">
               <input type="hidden" name="token" id="token" value="<?=$token?>">
               <input type="hidden" name="purchase_tid" id="purchase_tid" value="<?=$purchasemasterdata['tid']?>">
               <div class="form-group row">
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <div class="row">
                        <div class="col-12">
                           <label class="col-form-label"><?php echo 'Sale Point'; ?></label>
                        </div>
                        <div class="col-md-9">
                           <div class="frmclasss">
                              <input type="text" class="form-control customer_name" name="salepoint_name" id="warehouse-search" placeholder="Enter Sale point name" autocomplete="off" value="<?php echo $default_warehouse['title']; ?>"  readonly/>
                              <div id="warehouse-search-result" class="warehouse-search-result"></div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <input type="text" class="form-control" value="<?php echo $default_warehouse['id']; ?>" name="salepoint_id" id="salepoint_id" autocomplete="off"  readonly/>
                        </div>
                     </div>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <div class="row">
                        <div class="col-12">
                           <label class="col-form-label"><?php echo 'Supplier'; ?></label>
                        </div>
                        <div class="col-md-9">
                           <div class="frmclasss">
                              <input type="text" class="form-control supplier_name" name="supplier_name" value="<?php echo $purchasemasterdata['name']; ?>" id="supplier-search" placeholder="Enter Supplier name or phone or email" autocomplete="off" required readonly/>
                              <div id="supplier-search-result" class="supplier-search-result"></div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <input type="text" value="<?php echo $purchasemasterdata['csd']; ?>" class="form-control" name="supplier_id" id="supplier_id" autocomplete="off" readonly/>
                        </div>
                     </div>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <div class="row">
                        <div class="col-12">
                           <label class="col-form-label"><?php echo 'Party Name'; ?></label>
                        </div>
                        <div class="col-12">
                           <div class="">
                              <input type="text" class="form-control customer_name" name="party_name" id="party_name" placeholder="Party Name" value="<?php echo $purchasemasterdata['name']; ?>"  autocomplete="off" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <div class="row">
                        <div class="col-12">
                           <label class="col-form-label"><?php echo 'Damage Claim A/c'; ?></label>
                        </div>
                        <div class="col-8">
                           <div class="frmclasss">
                              <input type="text" class="form-control customer_name" name="damageclaim_ac_name" id="account-search" placeholder="" autocomplete="off"/>
                           </div>
                           <div id="account-search-result" class="account-search-result"></div>
                        </div>
                        <div class="col-4">
                           <input type="text" class="form-control" name="damageclaim_ac" id="damageclaim_ac" placeholder="" autocomplete="off" readonly/>
                        </div>
                     </div>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <div class="row">
                        <div class="col-12">
                           <label class="col-form-label"><?php echo 'Bill # '; ?><span class="compulsoryfld">*</span></label>
                        </div>
                        <div class="col-12">
                           <input type="text" class="form-control" name="bill_number" id="bill_number" placeholder="bill number" autocomplete="off" required/>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <div class="row">
                        <div class="col-md-12">
                           <label class="col-form-label"><?php echo 'Bill Date'; ?></label>
                        </div>
                        <div class="col-md-12">
                           <input type="date" class="form-control" name="bill_date" id="bill_date" placeholder="" autocomplete="off" />
                        </div>
                     </div>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Currency'; ?></label>
                     <input type="text" class="form-control" name="currency_id" id="currency_id" autocomplete="off" value="<?php echo $purchasemasterdata['code']; ?>" readonly/>
                     <!-- <select name="currency_id" id="currency_id" class="form-control"></select> -->
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Currency Rate'; ?></label>
                     <input type="number" class="form-control" name="currency_rate" id="currency_rate" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['rate']; ?>" readonly/>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Description'; ?></label>
                     <textarea name="bill_description" id="bill_description" class="form-textarea"><?php echo $purchasemasterdata['notes']; ?></textarea>
                  </div>
                  <!-- ================== Ends ======================= -->
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Doc Type'; ?><span class="compulsoryfld">*</span></label>
                     <div class="frmSearch1">
                        <input type="text" class="form-control customer_name" name="doctype" id="doctype"     autocomplete="off" value="<?php echo $purchasemasterdata['doc_type']; ?>" readonly required/>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Purchase Receipt No.'; ?><span class="compulsoryfld">*</span></label>
                     <div class="frmSearch1">
                        <input type="text" class="form-control customer_name" name="srv" id="srv"
                           autocomplete="off" value="<?php echo $srvNumber; ?>" required readonly/>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Purchase Receipt Date'; ?><span class="compulsoryfld">*</span></label>
                     <div class="frmSearch1">
                        <input type="date" class="form-control" name="srvdate" id="srvdate"
                           autocomplete="off" required/>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Purchase Order Amount'; ?></label>
                     <div class="frmSearch1">
                        <input type="text" class="form-control" name="purchase_amount" id="purchase_amount" autocomplete="off" value="<?php echo number_format($purchasemasterdata['total'],2); ?>" onkeypress="return isNumber(event)" readonly />
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Bill Amount '; ?><span class="compulsoryfld">*</span></label>
                     <div class="frmSearch12">
                        <input type="text" onkeypress="return isNumber(event)" class="form-control" name="bill_amount" id="bill_amount" autocomplete="off" value="<?php echo number_format($purchasemasterdata['total'],2); ?>" required/>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Cost Factor'; ?></label>
                     <div class="frmSearch1">
                        <input type="number" class="form-control" name="cost_factor" id="cost_factor"
                           autocomplete="off" value="1.00" required readonly/>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                     <label class="col-form-label"><?php echo 'Payment Date'; ?><span class="compulsoryfld">*</span></label>
                     <div class="frmSearch1">
                        <input type="date" class="form-control" name="payment_date" id="payment_date"
                           autocomplete="off"/>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                     <label class="col-form-label"><?php echo 'Note'; ?></label>
                     <div class="frmSearch1">
                        <textarea name="note" id="note" class="form-textarea"></textarea>
                     </div>
                  </div>
                  <!-- ================== ends ===================== -->  
                                                 
                  <!-- ================== starts ===================== -->
                  <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                        
                        <label for="employee1" class="fsize-17">Additional cost per item : <strong id="additional_cost_per_item">0.00</strong></label>
                        <input type="hidden" name="cost_per_item" id="cost_per_item" value="0.00">
                     </div>
                  <!-- ================== ends ===================== -->                                      
               </div>
               
            </div>
         </div>
         <!-- ==================== tab secction starts ================ -->
         <div class="card-body" style="background:#ffffff;">
            <ul class="nav nav-tabs" role="tablist">
               <li class="nav-item" id="item_li">
                  <a class="nav-link breaklink active show" id="base-tab1" data-toggle="tab"
                     aria-controls="tab1" href="#tab1" role="tab"
                     aria-selected="true"><?php echo $this->lang->line('Item Details') ?></a>
               </li>
               <li class="nav-item" id="costing_li">
                  <a class="nav-link breaklink" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                     href="#tab2" role="tab"
                     aria-selected="false"><?php echo $this->lang->line('Costing') ?></a>
               </li>
            </ul>
            <div class="tab-content px-1 pt-1">
               <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1" style="width: 100%; overflow-x: auto;">
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr>
                           <th style="width:150px !important;">Sl#</th>
                           <th style="width:10%;"><?php echo $this->lang->line('Code'); ?></th>
                           <th style="width:20%;"><?php echo $this->lang->line('Item Name'); ?></th>
                           <th style="width:15%;"><?php echo $this->lang->line('Current Cost'); ?></th>
                           <th style="width:15%;"><?php echo $this->lang->line('Unit'); ?></th>
                           
                           <th style="width:5%;"><?php echo $this->lang->line('Unit Price'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('New Cost'); ?></th>
                           <th style="width:25%;"><?php echo $this->lang->line('Ordered')."<br>".$this->lang->line('Quantity'); ?></th>
                           <th style="width:25%;"><?php echo $this->lang->line('Received')."<br>".$this->lang->line('Quantity'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Amount'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Free of Charge(FOC)'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Damaged')."<br>".$this->lang->line('Quantity'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Sales Price'); ?></th>
                           <!-- <th style="width:5%;">Disc%</th> -->
                           <th style="width:5%;"><?php echo $this->lang->line('Discount'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Net Amount'); ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Net Amount').'(QAR)'; ?></th>
                           <th style="width:5%;"><?php echo $this->lang->line('Action'); ?></th>
                           <!-- <th style="width:45%;"><?php echo $this->lang->line('Description'); ?></th> -->
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $j=0;
                           $totalamount =0;
                           $totalnetamount =0;
                           $totaltax=0;
                           $totaldiscount=0;
                           $totalqaramount = 0;
                           if(!empty($purchaseitemsdata))
                           {
                              //  echo "<pre>"; print_r($purchaseitemsdata); die();
                               echo '<input type="hidden" name="totalproducts" id="totalproducts" value="'.count($purchaseitemsdata).'">';
                               foreach($purchaseitemsdata as $item){
                                   $amount = $item['price'] * $item['qty'];
                                   $qtrprice = $purchasemasterdata['rate']*$item['subtotal'];
                                   $totalamount = $totalamount+$amount;
                                   $totalqaramount = $totalqaramount+$qtrprice;
                                   $totaldiscount = $totaldiscount+$item['discount'];
                                   $totalnetamount = $totalnetamount+$item['subtotal'];
                                   $account_code = $item['account_code'];
                                 //   $amount = number_format($amount,2);
                                   echo '<tr>';
                                   echo '<td>'.++$j.'<input type="hidden" class="form-control" name="product_id[]" id="product_id-'.$j.'" value="'.$item['pid'].'"  readonly></td>';                                   
                            
                                   echo '<td><input type="text" name="product_code[]" id="product_code-'.$j.'" class="form-control" value="'.$item['code'].'" style="width:200px;"  readonly><input type="hidden" class="form-control" name="account_code[]" id="account_code-'.$j.'" value="'.$account_code.'" readonly></td>';

                                   echo '<td><input type="text" class="form-control" name="product_name[]" id="productname-'.$j.'" value="'.$item['product'].'" readonly  style="width:350px;" ></td>';
                                   

                                    //current cost
                                   echo '<td class="text-right">'.number_format($item['cost'],2).'</td>';


                                   echo '<td><input type="text" name="product_unit[]" id="product_unit-'.$j.'" class="form-control" value="'.$item['unit'].'" style="width:100px;" readonly></td>';
                                   
                                   echo '<td class="text-right"><strong class="item-data">'.number_format($item['price'],2).'</strong><input type="hidden" name="price[]" id="price-'.$j.'" class="form-control"  style="width:120px;" value="'.$item['price'].'" readonly></td>';

                                   //new cost
                                   $latestcost = $item['price'];
                                   echo '<td class="text-right"><strong class="item-data"  id="newcostabel-'.$j.'">'.number_format($latestcost,2).'</strong><input type="hidden" name="newcost[]" id="newcost-'.$j.'" class="form-control"  style="width:130px;" value="'.$latestcost.'" readonly></td>';

                                   echo '<td><input type="number" name="product_qty[]" id="product_qty-'.$j.'" class="form-control" value="'.intval($item['qty']).'" style="width:100px;" readonly></td>';
                           
                                   echo '<td><input type="number" name="product_qty_recieved[]" id="product_qty_recieved-'.$j.'" class="form-control received_product"  style="width:100px;" onkeyup="recievedqty_check('.$j.'),productReceivedQty('.$j.'),productwise_costing('.$j.')" value="'.intval($item['qty']).'"></td>';

                                   echo '<td><input type="text" name="amount[]" id="amount-'.$j.'" class="form-control text-right"  style="width:150px;" value='.number_format($amount,2).' readonly></td>';
                           
                                   echo '<td><input type="number" name="product_foc[]" id="product_foc-'.$j.'" class="form-control text-right"  style="width:125px;" onkeyup="productFoc('.$j.')" value="0.00"></td>';

                                   echo '<td><input type="number" name="damage[]" id="damage-'.$j.'" class="form-control"  style="width:100px;" onkeyup="damagedqty_check('.$j.'),productDamage('.$j.')" value="0"></td>';

                                   echo '<td><input type="number" name="saleprice[]" id="saleprice-'.$j.'" class="form-control text-right"  style="width:100px;" readonly></td>';
                                 //   echo '<td><input type="number" name="discountperc[]" id="discountperc-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['discount'].'" onkeyup="productDiscount('.$j.')"></td>';
                                   echo '<td><input type="number" name="discountamount[]" id="discountamount-'.$j.'" class="form-control text-right"  style="width:150px;" value="'.number_format($item['discount'],2).'" readonly></td>';
                                   echo '<td><input type="text" name="netamount[]" id="netamount-'.$j.'" class="form-control text-right"  style="width:150px;" value="'.number_format($item['subtotal'],2).'" readonly></td>';
                                   echo '<td><input type="text" name="qaramount[]" id="qaramount-'.$j.'" class="form-control text-right"  style="width:150px;" value='.number_format($qtrprice,2).' readonly></td>';
                                   echo '<td></td>';
                                 //   echo '<td><textarea name="description[]" id="description-'.$j.'" class="form-control"  style="width:250px;">'.$item['product_des'].'</textarea></td>';
                                   echo '</tr>';
                               }
                               
                               ?>
                        <tr>
                           <th class="no-border" colspan="9" style="text-align:right;">Total</th>
                           <th class="no-border text-right"><span id="totalamount"><?php echo number_format($totalamount, 2); ?></span></th>
                           <th class="no-border"></th>
                           <th class="no-border"></th>
                           <th class="no-border"></th>
                           <th class="no-border text-right"><span id="totaldiscount" class="text-right"><?php echo number_format($totaldiscount,2); ?></span></th>
                           <th class="no-border text-right"><span id="totalnetamount"  class="text-right"><?php echo number_format($totalnetamount,2); ?></span></th>
                           <th class="no-border text-right"><span id="totalqaramount"  class="text-right"><?php echo number_format($totalqaramount,2); ?></span></th>
                           <th class="no-border"></th>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <div class="tab-pane saman-row" id="tab2" role="tabpanel" aria-labelledby="base-tab2" style="width: 100%; overflow-x: auto;">
                  <div id="saman-row">
                     <table class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                           <tr class="item_header bg-gradient-directional-blue white">
                              <th style="width:5% !important; padding-left:10px;">Sl#</th>
                              <th style="width:20% !important; padding-left:10px;">Expense</th>
                              <th style="width:10%; padding-left:10px;">Expense #</th>
                              <th style="width:15%; padding-left:10px;">Account Name</th>
                              <th style="width:10%; padding-left:10px;">Payable A/c</th>
                              <th style="width:250px; padding-left:10px;">Bill No.</th>
                              <th style="width:10%; padding-left:10px;">Bill Date</th>
                              <th style="width:10%; padding-left:10px;">Amount</th>
                              <th style="width:10%; padding-left:10px;">Currency</th>
                              <th style="width:10%; padding-left:10px;">Currency Rate</th>
                              <th style="width:10%; padding-left:10px;">Net Amt.</th>
                              <th style="width:10%; padding-left:10px;">Net Amt(QAR)</th>
                              <th style="width:45%; padding-left:10px;">Remarks</th>
                              <th style="padding-left:10px;">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr class="startRow">
                              <td style="width:50px;">1</td>
                              <td><input type="text" name="expense_name[]" id="expense_name-0" class="form-control expense_name1" style="width:150px;" data-id="0" placeholder="Expense name"></td>
                              <td><input type="text" name="expense_id[]" id="expense_id-0" class="form-control"  style="width:100px;" readonly></td>
                              <td><input type="text" name="payable_acc[]" id="payable_acc-0" class="form-control" style="width:250px;" placeholder="Account Name or Number"></td>
                              <td><input type="number" name="payable_acc_no[]" id="payable_acc_no-0" class="form-control disable-class" style="width:150px;" ></td>
                              <td><input type="text" name="bill_number_cost[]" id="bill_number_cost-0" class="form-control billnumber" style="width:150px;" value="<?=$purchasemasterdata['bill_number']?>"></td>
                              <td><input type="date" name="bill_date_cost[]" id="bill_date_cost-0" class="form-control billdate" style="width:150px;" ></td>
                              <td><input type="number" name="costing_amount[]" id="costing_amount-0" class="form-control text-right" style="width:100px;" onkeyup="costingamount(0)"></td>
                              <td><input type="text" name="currency_cost[]" id="currency_cost-0" value="<?php echo $purchasemasterdata['code']; ?>" class="form-control" style="width:150px;" readonly></td>
                              <td><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-0" value="<?php echo $purchasemasterdata['rate']; ?>" class="form-control text-right" style="width:150px;" readonly></td>
                              <td><input type="number" name="costing_amount_net[]" id="costing_amount_net-0" class="form-control text-right" style="width:100px;" readonly></td>
                              <td><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-0" class="form-control text-right" style="width:100px;" readonly></td>
                              <td><textarea name="remarks[]" id="remarks-0" class="form-control" style="width:250px;"></textarea></td>
                              <td><button type="button" data-rowid="0" class="btn-sm btn-default removeProd border-0" title="Remove"> <i class="fa fa-trash"></i> </button></td>
                           </tr>
                           <tr class="last-item-row sub_c">
                              <td  class="no-border"></td>
                              <td  class="no-border" class="add-row">
                                 <button type="button" class="btn btn-success" aria-label="Left Align"
                                    id="addcosting">
                                 <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                 </button>
                              </td>
                              <td  class="no-border" colspan="7"></td>
                           </tr>
                           <tr  class="no-border">
                              <th class="no-border" colspan="6"></th>
                              <th class="no-border">Total</th>
                              <th class="no-border text-right"><span id="costingAmounts"></span></th>
                              <th class="no-border"></th>
                              <th class="no-border"></th>
                              <th class="no-borde text-right"><span id="costingNetAmounts"></span></th>
                              <th class="no-border text-right"><span id="costingNetAmountQar"></span></th>
                              <th class="no-border"></th>
                              <th class="no-border"></th>
                           </tr>
                        </tbody>
                     </table>
                     <input type="hidden" value="expense_search" id="billtype">
                     <input type="hidden" value="0" name="counter" id="ganak">
                  </div>
               </div>
            </div>
            <!-- ==================== tab secction ends ================== -->
         </div>
         <div class="col-md-12 mb-3" style="border-top:1px #d0d0d0 solid;">
            <div class="text-right mt-2">
               <button class="btn btn-crud btn-secondary btn-lg" id="savebtn_draft" name="savebtn_draft"><?php echo $this->lang->line('Save As Draft') ?></button>
               <button class="btn btn-crud btn-primary btn-lg" id="savebtn" name="savebtn"><?php echo $this->lang->line('Prepared') ?></button>
            </div>
         </div>
</form>
</div>
</div>
<script>
   $("#account-search").keyup(function () {
       $.ajax({
           type: "POST",
           url: baseurl + 'CostingCalculation/accountsearch',
           data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
           beforeSend: function () {
               $("#account-search").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
           },
           success: function (data) {
               $("#account-search-result").show();
               $("#account-search-result").html(data);
               $("#account-search").css("background", "none");
   
           }
       });
   });
   function selectedAccount(cid, holder,accno) {
       $('#account-search').val(holder);
       $('#damageclaim_ac').val(accno);
       $("#account-search-result").hide();
   }
   
   function productReceivedQty(j) {
       
       var receivedQty = parseFloat($("#product_qty_recieved-"+j).val());
       var price = parseFloat($("#price-"+j).val());
       if (!isNaN(receivedQty) && !isNaN(price)) {
           var amount = receivedQty * price;
           $("#amount-"+j).val(amount);
       }
       else{
           $("#amount-"+j).val(0);
       }
       calculateValues();
   }
   function productFoc(j) {
       var FOCAmt = $("#product_foc-"+j).val();
       calculateValues();
   }
   function productDamage(j) {
       var damageAmt = $("#damage-"+j).val();
      //  calculateValues();
   }
   function productDiscount(j) {
       var discountPerc = parseFloat($("#discountperc-"+j).val());
       var netAmount = parseFloat($("#amount-"+j).val());
       if (!isNaN(discountPerc) && !isNaN(netAmount)) {
           var discountAmount = (netAmount * discountPerc) / 100;
           $("#discountamount-"+j).val(discountAmount);
       } else {
           $("#discountamount-"+j).val(0);
       }
       calculateValues();
   }
   function calculateValues() {
       var totalProducts = parseInt($("#totalproducts").val());
       
       if (!isNaN(totalProducts) && totalProducts > 0) {
           var totalAmount = 0;
           var totalDiscount = 0;
           var totalNetAmount = 0;
           var totalQarAmount = 0;
           var currencyRate = parseFloat($("#currency_rate").val());
           currencyRate = isNaN(currencyRate) ? 1 : currencyRate;
           for (var i = 1; i <= totalProducts; i++) {
               var productQtyInput = $("#product_qty_recieved-" + i).val();
               var productQty = parseFloat(productQtyInput);
               productQty = isNaN(productQty) ? 0 : productQty;
   
               var productFoc = parseFloat($("#product_foc-" + i).val());
               productFoc = isNaN(productFoc) ? 0 : productFoc;
   
               var damage = parseFloat($("#damage-" + i).val());
               damage = isNaN(damage) ? 0 : damage;
   
               var price = parseFloat($("#price-" + i).val());
               price = isNaN(price) ? 0 : price;
   
               var amount = parseFloat($("#amount-" + i).val());
               amount = isNaN(amount) ? 0 : amount;
   
               var discountPerc = parseFloat($("#discountperc-" + i).val());
               discountPerc = isNaN(discountPerc) ? 0 : discountPerc;
   
               var discountAmount = parseFloat($("#discountamount-" + i).val());
               discountAmount = isNaN(discountAmount) ? 0 : discountAmount;
   
               
   
               if (!isNaN(productQty)) {
                   var netAmount = (productQty * price) - (productFoc + damage + discountAmount);
                  
                   var qarAmount = currencyRate * netAmount;
                   qarAmount1 = parseFloat(qarAmount.toFixed(2));
                   $("#netamount-"+i).val(netAmount);
                   $("#qaramount-"+i).val(qarAmount1);
                   totalAmount += amount;
                   totalDiscount += discountAmount;
                   totalNetAmount += netAmount;
                   totalQarAmount += qarAmount1;
               } 
               else {
               }
           }
           $("#totalamount").text(totalAmount.toFixed(2));
           $("#bill_amount").val(totalNetAmount.toFixed(2));
           $("#totaldiscount").text(totalDiscount.toFixed(2));
           $("#totalnetamount").text(totalNetAmount.toFixed(2));
           $("#totalqaramount").text(totalQarAmount.toFixed(2));
   
           // var netAmount = parseFloat($("#totalnetamount").text());
           // var productAmount = parseFloat($("#totalamount").text());
           // costfactor = (netAmount)/productAmount;
           // var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);
           // $("#cost_factor").val(costfactorFinal);
   
                   
           var costingNetAmountval = parseFloat($("#costingNetAmounts").text());
           if(costingNetAmountval>0 && costingNetAmountval!="")
           {
               var calculatedNetAmount = parseFloat($("#totalnetamount").text());
               costfactor = (costingNetAmountval)/calculatedNetAmount;
               var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);
               $("#cost_factor").val(costfactorFinal);
           }
   
       } else {
           console.error('Invalid value for total products');
       }
   }
   
   // function costingamount(id)
   // {
   //     costingAmounts = 0;
   //     costingNetAmounts  = 0;
   //     costingNetAmountQar   = 0;    
   //     totalQar   = 0;    
   //     totalNet   = 0;    
   //     var costing_amount = parseFloat($("#costing_amount-" + id).val());
   //     costing_amount = isNaN(costing_amount) ? 0 : costing_amount;
   //     var currencyRate = parseFloat($("#currency_rate").val());
   //     currencyRate = isNaN(currencyRate) ? 1 : currencyRate;
   //     counter = $('#ganak').val();
   //     $("#costingAmounts").text("");
   //     $("#costingNetAmounts").text("");
   //     $("#costingNetAmountQar").text("");
           
   //     for (var i = 0; i <= counter; i++) {
   //         costing_amount_single = parseFloat($("#costing_amount-" + i).val());
   //         costing_amount_single = isNaN(costing_amount_single) ? 0 : costing_amount_single;
   //         costingAmounts = costing_amount_single;
   //         costingNetAmountQar = currencyRate * costing_amount_single;
   //         totalQar += costingNetAmountQar;
   //         totalNet += costing_amount_single;
   //         $("#costing_amount_qar-" + i).val(costingNetAmountQar.toFixed(2));
   //         $("#costing_amount_net-" + i).val(costing_amount_single.toFixed(2));
   //     }
       
   //     $("#costingAmounts").text(totalNet.toFixed(3));
   //     $("#costingNetAmounts").text(totalNet.toFixed(3));
   //     $("#costingNetAmountQar").text(totalQar.toFixed(3));
   
   //     var calculatedNetAmount = parseFloat($("#totalnetamount").text());
   //     var costingNetAmountval = parseFloat($("#costingNetAmounts").text());
   //     costfactor = (costingNetAmountval)/calculatedNetAmount;
   //     var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);
   //     $("#cost_factor").val(costfactorFinal);
       
   // }
   $(document).ready(function() {
      
         $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {               
               doctype: { required: true },
               bill_number: { required: true },
               srv: { required: true },
               bill_amount: { required: true },
               srvdate: { required: true },
               // payment_date: { required: true },
               
            },
            messages: {
               doctype: "Doc Type required",
               bill_number: "Enter Bill No.",
               srv: "Purchase Receipt Voucher No. required",
               bill_amount: "Enter Bill Amount equal to the Purchase Order Amount",
               srvdate: "Enter Purchase Receipt Date",
               // payment_date: "Enter Payment Date",
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
       
       $('#savebtn').on('click', function(e) {
           e.preventDefault();
           $('#savebtn').prop('disabled', true);
           var srvflg = "<?php echo $srvFlg; ?>";
          
           if(srvflg==1){
               targeturl = baseurl +'Invoices/dataoperationeditfrominsert';
           }
           else{
               targeturl = baseurl +'Invoices/dataoperation';
           }
           
         // Validate the form
         if($("#data_form").valid()) {                 
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object

            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a Purchase Items Receipt?",
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
                        url: baseurl + 'Invoices/dataoperation', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           if (typeof response === "string") {
                              response = JSON.parse(response);
                           } 
                           window.location.href = baseurl + 'Invoices/stockreciepts';
                           
                        },
                        error: function(xhr, status, error) {
                           Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                           console.log(error); // Log any errors
                        }
                  });
               }
               else{
                  $('#savebtn').prop('disabled', false);
               }
            });

         }
         else{
            $('#savebtn').prop('disabled', false);
         }
           
   
       });
       
       
   });
   var amounttobill = parseFloat($("#totalnetamount").text().replace(/,/g, '')) || 0;
   $("#bill_amount").val(amounttobill.toFixed(2));
   $("#savebtn_draft").on("click", function(e) {
      e.preventDefault();
      var formData = $("#data_form").serialize(); 
      srv = $("#srv").val();
      token = $("#token").val();
      $.ajax({
         type: 'POST',
         url: baseurl +'Invoices/draftaction',
         data: formData,
         success: function(response) {
            if (typeof response === "string") {
               response = JSON.parse(response);
            }         
            window.location.href = baseurl + 'Invoices/costing?id='+response.data+'&token='+token;
         },
         error: function(xhr, status, error) {
               // Handle error
               console.error(xhr.responseText);
         }
      });
   });
function recievedqty_check(id){
      var orderedqty = parseFloat($("#product_qty-" + id).val()) || 0;       
      var receivedqty = parseFloat($("#product_qty_recieved-" + id).val()) || 0;       
      var damageqty = parseFloat($("#damage-" + id).val()) || 0;    
      var totalqty = orderedqty - damageqty;
      
      if((receivedqty > totalqty)){
         $("#product_qty_recieved-" + id).val(orderedqty);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is invalid. please check'
         });
      }
}
function damagedqty_check(id){
   
      var orderedqty = parseFloat($("#product_qty-" + id).val()) || 0;       
      var receivedqty = parseFloat($("#product_qty_recieved-" + id).val()) || 0;       
      var damagedqty = parseFloat($("#damage-" + id).val()) || 0;    
      var totalqty = parseFloat(orderedqty) - parseFloat(receivedqty);
      if((damagedqty > receivedqty)){
         $("#damage-" + id).val(0);
         Swal.fire({
               icon: 'error',
               title: 'Invalid Quantity',
               text: 'The value you entered is invalid. please check'
         });
      }
}

// erp2024 07-10-2024
function costingamount(id)
{
    var totalproducts = $("#totalproducts").val();
    costingAmounts = 0;
    costingNetAmounts  = 0;
    costingNetAmountQar   = 0;    
    totalQar   = 0;    
    totalNet   = 0;    
    var costing_amount = parseFloat($("#costing_amount-" + id).val());
    costing_amount = isNaN(costing_amount) ? 0 : costing_amount;
    var currencyRate = parseFloat($("#currency_rate").val());
    currencyRate = isNaN(currencyRate) ? 1 : currencyRate;
    counter = $('#ganak').val();
    $("#costingAmounts").text("");
    $("#costingNetAmounts").text("");
    $("#costingNetAmountQar").text("");
       
    for (var i = 0; i <= counter; i++) {
        costing_amount_single = parseFloat($("#costing_amount-" + i).val());
        costing_amount_single = isNaN(costing_amount_single) ? 0 : costing_amount_single;
        costingAmounts = costing_amount_single;
        costingNetAmountQar = currencyRate * costing_amount_single;
        totalQar += costingNetAmountQar;
        totalNet += costing_amount_single;
        $("#costing_amount_qar-" + i).val(costingNetAmountQar.toFixed(2));
        $("#costing_amount_net-" + i).val(costing_amount_single.toFixed(2));
    }
    
    $("#costingAmounts").text(totalNet.toFixed(3));
    $("#costingNetAmounts").text(totalNet.toFixed(3));
    $("#costingNetAmountQar").text(totalQar.toFixed(3));

    var calculatedNetAmount = $("#totalnetamount").text();
    calculatedNetAmount = parseFloat(calculatedNetAmount.replace(/,/g, ''));
    var costingNetAmountval = $("#costingNetAmounts").text();
    costingNetAmountval = parseFloat(costingNetAmountval.replace(/,/g, '')); 
    costfactor = (costingNetAmountval)/calculatedNetAmount;
    if (isNaN(costfactor)) {
      var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2); 
    }
    else{
      costfactorFinal = 1;
    }
    
    $("#cost_factor").val(costfactorFinal);
    var totalqty = 0
    $('.received_product').each(function() {
        totalqty += parseFloat($(this).val()) || 0; // Add the value or 0 if it’s empty
    }); 
    costperitem  = (costingNetAmountval)/totalqty;
    $("#additional_cost_per_item").text(costperitem.toFixed(2));
    $("#cost_per_item").val(costperitem.toFixed(2));

    for (var j = 1; j <= totalproducts; j++) {
        oldprdval = $("#price-" + j).val();
        newcost = parseFloat(oldprdval) + parseFloat(costperitem);
        newcost = newcost.toFixed(2);
        $("#newcostabel-" + j).text(newcost);
        $("#newcost-" + j).val(newcost);
    }
    
}

function productwise_costing(j)
{
    var totalproducts = $("#totalproducts").val();
    costingAmounts = 0;
    costingNetAmounts  = 0;
    costingNetAmountQar   = 0;    
    totalQar   = 0;    
    totalNet   = 0;    
   
   
    var calculatedNetAmount = $("#totalnetamount").text();
    calculatedNetAmount = parseFloat(calculatedNetAmount.replace(/,/g, ''));
    var costingNetAmountval = $("#costingNetAmounts").text();
    costingNetAmountval = parseFloat(costingNetAmountval.replace(/,/g, '')); 
    costfactor = (costingNetAmountval)/calculatedNetAmount;
    if (isNaN(productQty)) {
      var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2); 
    }
    else{
      costfactorFinal = 1;
    }
    $("#cost_factor").val(costfactorFinal);

    var totalqty = 0
    $('.received_product').each(function() {
        totalqty += parseFloat($(this).val()) || 0; // Add the value or 0 if it’s empty
    }); 
    costperitem  = (costingNetAmountval)/totalqty;
    if (isNaN(productQty)) {
      $("#additional_cost_per_item").text(costperitem.toFixed(2));
      $("#cost_per_item").val(costperitem.toFixed(2));
      oldprdval = $("#price-" + j).val();
      var newcost = parseFloat(oldprdval) + parseFloat(costperitem);
      newcost = newcost.toFixed(2);
      $("#newcostabel-" + j).text(newcost);
      $("#newcost-" + j).val(newcost);
    }
    
}
</script>