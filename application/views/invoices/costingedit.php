<div class="content-body" >
    <div class="card" >
        <form method="post" id="data_form_edit" enctype="multipart/form-data">
            <div class="card-content">
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <div class="message"></div>
                </div>
                <div class="card-body" >                    
                    <div class="row" >                        
                        <div class="col-12 mb-2" style="border-bottom:1px #d0d0d0 solid;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub">
                                            <?php echo "Purchase Items Receipt" ?> 
                                        </h3>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="fcol-sm-12">
                                        <h5 class="title-sub">
                                            <?php echo "Purchase Order : <b>" .$purchaseorderdata['tid']. "</b> Purchase Order Date : <b>".dateformat($purchaseorderdata['invoicedate'])."</b>"; ?> 
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                       <style>
                        .error1{
                            border: 1px solid #ff0000 !important;
                        }
                       </style>
                        <div class="col-12 form-group row">
                            <!-- ================== starts ===================== -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="col-form-label"><?php echo 'Sale Point'; ?></label>
                                    </div>
                                    <div class="col-9">
                                        <div class="frmclasss">
                                            <input type="text" class="form-control customer_name" name="salepoint_name" id="warehouse-search" placeholder="Enter Sale point name" autocomplete="off" value="<?php echo $purchasemasterdata['salepoint_name']; ?>" required readonly/>
                                            <div id="warehouse-search-result" class="warehouse-search-result"></div>
                                        </div>
                                    </div> 
                                    <div class="col-3">
                                        <input type="text" class="form-control" value="<?php echo $purchasemasterdata['salepoint_id']; ?>" name="salepoint_id" id="salepoint_id" autocomplete="off"  readonly/>
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
                                    <div class="col-9">
                                        <div class="frmclasss">
                                            <input type="text" class="form-control supplier_name" name="supplier_name" value="<?php echo $purchasemasterdata['supplier_name']; ?>" id="supplier-search" placeholder="Enter Supplier name or phone or email" autocomplete="off" required readonly/>
                                            <div id="supplier-search-result" class="supplier-search-result"></div>
                                        </div>
                                    </div> 
                                    <div class="col-3">
                                        <input type="text" value="<?php echo $purchasemasterdata['supplier_id']; ?>" class="form-control" name="supplier_id" id="supplier_id" autocomplete="off" readonly/>
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
                                            <input type="text" class="form-control customer_name" name="party_name" id="party_name" placeholder="Party Name" value="<?php echo $purchasemasterdata['party_name']; ?>"  autocomplete="off" />
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
                                            <input type="text" class="form-control customer_name" name="damageclaim_ac_name" id="account-search" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['damageclaim_ac_name']; ?>"/>
                                        </div>
                                        <div id="account-search-result" class="account-search-result"></div>
                                    </div> 
                                    <div class="col-4">
                                        <input type="text" class="form-control" name="damageclaim_ac" id="damageclaim_ac" placeholder="" autocomplete="off" readonly value="<?php echo $purchasemasterdata['damageclaim_ac']; ?>"/>
                                    </div>
                                </div>   
                            </div>   
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"><?php echo 'Bill #'; ?></label>
                                <input type="number" class="form-control" name="bill_number" id="bill_number" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['bill_number']; ?>" required/>                           
                            </div>                             
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"><?php echo 'Bill Date'; ?></label>
                                <input type="date" class="form-control" name="bill_date" id="bill_date" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['bill_date']; ?>"/>
                            </div>       
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"><?php echo 'Currency'; ?></label>
                                <input type="text" class="form-control" name="currency_id" id="currency_id" autocomplete="off" value="<?php echo $purchasemasterdata['currency_id']; ?>" readonly/>
                                <!-- <select name="currency_id" id="currency_id" class="form-control"></select> -->
                            </div> 
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Currency Rate'; ?></label>
                                    <input type="number" class="form-control" name="currency_rate" id="currency_rate" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['currency_rate']; ?>" readonly/>
                            </div>     
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Description'; ?></label>
                                    <textarea name="bill_description" id="bill_description" class="form-control"><?php echo $purchasemasterdata['bill_description']; ?></textarea>
                            </div>     
                            <!-- ================== Ends ======================= -->
                       
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"><?php echo 'Doc Type'; ?><i clasa="text-danger">*</i></label>
                                <div class="frmSearch1">
                                    <input type="text" class="form-control customer_name" name="doctype" id="doctype"     autocomplete="off" value="<?php echo $purchasemasterdata['doctype']; ?>" readonly required/>
                                </div>
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Stock Receipt Voucher No.'; ?><i clasa="text-danger">*</i></label>
                                    <div class="frmSearch1">
                                        <input type="text" class="form-control customer_name" name="srv" id="srv"
                                                autocomplete="off" value="<?php echo $purchasemasterdata['srv']; ?>" required readonly/>
                                    </div>
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'SRV Date'; ?><i clasa="text-danger">*</i></label>
                                    <div class="frmSearch1">
                                        <input type="date" class="form-control" name="srvdate" id="srvdate"
                                                autocomplete="off" value="<?php echo $purchasemasterdata['srvdate']; ?>" required/>
                                    </div>
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Purchase Amount'; ?></label>
                                    <div class="frmSearch1">
                                        <input type="number" class="form-control" name="purchase_amount" id="purchase_amount"
                                                autocomplete="off" value="<?php echo $purchasemasterdata['purchase_amount']; ?>" readonly />
                                    </div>
                            </div>  
                            <!-- ================== ends ===================== -->    
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Bill Amount *'; ?></label>
                                    <div class="frmSearch1">
                                        <input type="number" class="form-control" name="bill_amount" id="bill_amount" autocomplete="off" value="<?php echo $purchasemasterdata['bill_amount']; ?>" required/>
                                    </div>
                            </div>  
                            <!-- ================== ends ===================== -->                                     
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Cost Factor'; ?></label>
                                    <div class="frmSearch1">
                                        <input type="number" class="form-control" name="cost_factor" id="cost_factor"
                                                autocomplete="off" value="<?php echo $purchasemasterdata['cost_factor']; ?>" required readonly/>
                                    </div>
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                             <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label"><?php echo 'Payment Date'; ?></label>
                                    <div class="frmSearch1">
                                        <input type="date" class="form-control" name="payment_date" id="payment_date"
                                                autocomplete="off" value="<?php echo $purchasemasterdata['payment_date']; ?>" required/>
                                    </div>
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                        </div>
                        <hr>
                        
                        <div class="col-md-12 mb-3" style="border-top:1px #d0d0d0 solid;">
                            <div class="text-right mt-2">
                                <!-- <button type="button" class="btn btn-secondary">Print</button> -->
                                <button class="btn btn-primary btn-lg" id="updatebtn" name="updatebtn">Update</button>
                            </div>  
                        </div>
                    </div>
                </div>
                <input type="hidden" name="costmaserid" id="costmaserid" value="<?=$costid?>">
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
                            <table class="table-responsive tfr my_stripe">
                                <thead>
                                    <tr>
                                        <th style="width:150px !important;">Sl#</th>
                                        <th style="width:20%;">Item</th>                                    
                                        <th style="width:10%;">Item No.</th>
                                        <th style="width:15%;">Unit</th>
                                        <th style="width:25%;">Qty Ordered</th>
                                        <th style="width:25%;">Qty Recieved</th>                                        
                                        <th style="width:5%;">Amount</th>
                                        <th style="width:5%;">Free of Charge(FOC)</th>
                                        <th style="width:5%;">Damage</th>
                                        <th style="width:5%;">Price</th>
                                        <th style="width:5%;">Sales Price</th>
                                        <th style="width:5%;">Disc%</th>
                                        <th style="width:5%;">Discount</th>
                                        <th style="width:5%;">Net Amt.</th>
                                        <th style="width:5%;">Net Amt(QAR)</th>
                                        <th style="width:45%;">Discription</th>
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
                                    echo '<input type="hidden" name="totalproducts" id="totalproducts" value="'.count($purchaseitemsdata).'">';
                                    foreach($purchaseitemsdata as $item){
                                        $amount = $item['price']*$item['product_qty_recieved'];
                                        // $qtrprice = $purchasemasterdata['rate']*$item['netamount'];
                                        $totalamount = $totalamount+$item['amount'];
                                        $totalqaramount = $totalqaramount+$item['qaramount'];
                                        $totaldiscount = $totaldiscount+$item['discountamount'];
                                        $totalnetamount = $totalnetamount+$item['netamount'];
                                        echo '<tr>';
                                        echo '<td>'.++$j.'<input type="hidden" class="form-control" name="product_id[]" id="product_id-'.$j.'" value="'.$item['product_id'].'" style="width:250px;" readonly></td>'; 
                                        echo '<td><input type="text" class="form-control" name="product_name[]" id="productname-'.$j.'" value="'.$item['product_name'].'" style="width:250px;" readonly></td>';

                                        echo '<td><input type="text" name="product_code[]" id="product_code-'.$j.'" class="form-control" value="'.$item['product_code'].'" style="width:100px;" readonly></td>';

                                        echo '<td><input type="text" name="product_unit[]" id="product_unit-'.$j.'" class="form-control" value="'.$item['product_unit'].'" style="width:100px;" readonly></td>';

                                        echo '<td><input type="number" name="product_qty[]" id="product_qty-'.$j.'" class="form-control" value="'.$item['product_qty'].'" style="width:100px;" readonly></td>';

                                        echo '<td><input type="number" name="product_qty_recieved[]" id="product_qty_recieved-'.$j.'" class="form-control"  style="width:100px;" onkeyup="productReceivedQty('.$j.')" value="'.$item['product_qty_recieved'].'"></td>';

                                        echo '<td><input type="number" name="amount[]" id="amount-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['amount'].'" readonly></td>';

                                        echo '<td><input type="number" name="product_foc[]" id="product_foc-'.$j.'" class="form-control"  style="width:150px;" onkeyup="productFoc('.$j.')" value="'.$item['product_foc'].'"></td>';

                                        echo '<td><input type="number" name="damage[]" id="damage-'.$j.'" class="form-control"  style="width:150px;" onkeyup="productDamage('.$j.')" value="'.$item['damage'].'"></td>';

                                        echo '<td><input type="number" name="price[]" id="price-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['price'].'" readonly></td>';
                                        $saleprice = ($item['saleprice']==0)?$item['saleprice']:"";
                                        echo '<td><input type="number" name="saleprice[]" id="saleprice-'.$j.'" class="form-control"  style="width:150px;" value="'.$saleprice.'" readonly></td>';


                                        echo '<td><input type="number" name="discountperc[]" id="discountperc-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['discountperc'].'" onkeyup="productDiscount('.$j.')"></td>';

                                        echo '<td><input type="number" name="discountamount[]" id="discountamount-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['discountamount'].'" readonly></td>';

                                        echo '<td><input type="number" name="netamount[]" id="netamount-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['netamount'].'" readonly></td>';

                                        echo '<td><input type="number" name="qaramount[]" id="qaramount-'.$j.'" class="form-control"  style="width:150px;" value='.$item['qaramount'].' readonly></td>';

                                        echo '<td><textarea name="" id="" class="form-control"  style="width:250px;">'.$item['description'].'</textarea></td>';

                                        echo '</tr>';
                                    }
                                    
                                    ?>
                                    <tr>
                                        <th colspan="6" style="text-align:right;">Total</th>
                                        <th><span id="totalamount"><?php echo $totalamount; ?></span></th>
                                        <th></th><th></th><th></th><th></th><th></th>
                                        <th><span id="totaldiscount"><?php echo $totaldiscount; ?></span></th>
                                        <th><span id="totalnetamount"><?php echo $totalnetamount; ?></span></th>
                                        <th><span id="totalqaramount"><?php echo $totalqaramount; ?></span></th>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane saman-row" id="tab2" role="tabpanel" aria-labelledby="base-tab2" style="width: 100%; overflow-x: auto;">
                            

                        
                            <div id="saman-row">
                            <table class="table-responsive tfr my_stripe">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php if(count($purchaseexpensesdata)>0){ $ganakval = count($purchaseexpensesdata); } else{ $ganakval = 0; }
                                            $costamt = 0;
                                            $costnetamt = 0;
                                            $costqaramt = 0;
                                             if(empty($purchaseexpensesdata)){ ?>
                                                <tr class="startRow">
                                                <td style="width:50px;">1</td>

                                                <td><input type="text" name="expense_name[]" id="expense_name-0" class="form-control expense_name1" style="width:150px;" data-id="0" placeholder="Expense name"></td>

                                                <td><input type="text" name="expense_id[]" id="expense_id-0" class="form-control"  style="width:100px;" readonly></td>
                                                <td><input type="text" name="payable_acc[]" id="payable_acc-0" class="form-control" style="width:250px;" placeholder="Account Name or Number"></td>
                                                <td><input type="number" name="payable_acc_no[]" id="payable_acc_no-0" class="form-control" style="width:150px;" ></td>
                                                <td><input type="text" name="bill_number_cost[]" id="bill_number_cost-0" class="form-control billnumber" style="width:150px;" value="<?=$purchasemasterdata['bill_number']?>"></td>
                                                <td><input type="date" name="bill_date_cost[]" id="bill_date_cost-0" class="form-control billdate" style="width:150px;" ></td>

                                                <td><input type="number" name="costing_amount[]" id="costing_amount-0" class="form-control" style="width:100px;" onkeyup="costingamount(0)"></td>

                                                <td><input type="text" name="currency_cost[]" id="currency_cost-0" value="<?php echo $purchasemasterdata['code']; ?>" class="form-control" style="width:150px;" readonly></td>

                                                <td><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-0" value="<?php echo $purchasemasterdata['rate']; ?>" class="form-control" style="width:150px;" readonly></td>
                                                <td><input type="number" name="costing_amount_net[]" id="costing_amount_net-0" class="form-control" style="width:100px;" readonly></td>
                                                <td><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-0" class="form-control" style="width:100px;" readonly></td>
                                                <td><textarea name="remarks[]" id="remarks-0" class="form-control" style="width:250px;"></textarea></td>
                                                </tr>
                                            <?php }
                                            else{
                                                $k=0;
                                                $l=1;
                                                foreach($purchaseexpensesdata as $row){
                                                    $costamt = $costamt+$row['costing_amount'];
                                                    $costnetamt = $costnetamt+$row['costing_amount_net'];
                                                    $costqaramt = $costqaramt+$row['costing_amount_qar'];
                                                    echo '<tr class="startRow1">';
                                                    echo '<td style="width:50px;">'.$l++.'</td>';
                                                    echo '<td><input type="text" name="expense_name[]" id="expense_name-'.$k.'" class="form-control" style="width:150px;" data-id="'.$k.'" placeholder="Expense name" onkeyup="expensedynamicsearch('.$k.')" value="'.$row['expense_name'].'"></td>';
                                                    echo '<td><input type="text" name="expense_id[]" id="expense_id-'.$k.'" class="form-control"  style="width:100px;" readonly value="'.$row['expense_id'].'"></td>';
                                                    echo '<td><input type="text" name="payable_acc[]" id="payable_acc-'.$k.'" class="form-control" style="width:250px;" placeholder="Account Name or Number" onkeyup="accountdynamicsearch('.$k.')" data-id="'.$k.'" value="'.$row['payable_acc'].'"></td>';
                                                    echo '<td><input type="number" name="payable_acc_no[]" id="payable_acc_no-'.$k.'" class="form-control" style="width:150px;" value="'.$row['payable_acc_no'].'" readonly></td>';
                                                    echo '<td><input type="number" name="bill_number_cost[]" id="bill_number_cost-'.$k.'" class="form-control billnumber" style="width:150px;"  value="'.$row['bill_number_cost'].'" readonly></td>';

                                                    echo '<td><input type="date" name="bill_date_cost[]" id="bill_date_cost-'.$k.'" class="form-control" style="width:150px;" value="'.$row['bill_date_cost'].'" ></td>';
                                                    echo ' <td><input type="number" name="costing_amount[]" id="costing_amount-'.$k.'" class="form-control" style="width:100px;" onkeyup="costingamount(0)"  value="'.$row['costing_amount'].'"></td>';
                                                    echo '<td><input type="text" name="currency_cost[]" id="currency_cost-'.$k.'"  value="'.$row['currency_cost'].'" class="form-control" style="width:150px;" readonly></td>';
                                                    echo '<td><input type="number" name="currency_rate_cost[]" id="currency_rate_cost-'.$k.'" value="'.$row['currency_rate_cost'].'" class="form-control" style="width:150px;" readonly></td>';
                                                    echo '<td><input type="number" name="costing_amount_net[]" id="costing_amount_net-'.$k.'" class="form-control" style="width:100px;" readonly value="'.$row['costing_amount_net'].'"></td>';
                                                    echo '<td><input type="number" name="costing_amount_qar[]" id="costing_amount_qar-'.$k.'" class="form-control" style="width:100px;" readonly value="'.$row['costing_amount_qar'].'"></td>';
                                                    echo '<td><textarea name="remarks[]" id="remarks-'.$k.'" class="form-control" style="width:250px;">'.$row['remarks'].'</textarea></td>';
                                                    echo '</tr>';
                                                    $k++;
                                                }
                                            } ?>
                                       
                                        <tr class="last-item-row sub_c">
                                            <td></td>
                                            <td class="add-row">
                                                <button type="button" class="btn btn-success" aria-label="Left Align"
                                                        id="addcosting">
                                                    <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                                </button>
                                            </td>
                                            <td colspan="7"></td>
                                        </tr>
                                        <tr>
                                            
                                            <th colspan="6"></th>
                                            <th>Total</th>
                                            <th><span id="costingAmounts"><?=$costamt?></span></th>
                                            <th></th>
                                            <th></th>
                                            <th><span id="costingNetAmounts"><?=$costnetamt?></span></th>
                                            <th><span id="costingNetAmountQar"><?=$costqaramt?></span></th>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" value="expense_search" id="billtype">
                                
                                <input type="hidden" value="<?=$ganakval?>" name="counter" id="ganak">
                            </div>
                    
                        </div>
                    </div>
                <!-- ==================== tab secction ends ================== -->
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
    calculateValues();
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
                var netAmount = (productQty * price) - productFoc - damage - discountAmount;
                var qarAmount = currencyRate * netAmount;
                qarAmount1 = parseFloat(qarAmount.toFixed(2));
                $("#netamount-"+i).val(netAmount);
                $("#qaramount-"+i).val(qarAmount1);
                totalAmount += amount;
                totalDiscount += discountAmount;
                totalNetAmount += netAmount;
                totalQarAmount += qarAmount1;
            } else {
            }
        }
        $("#totalamount").text(totalAmount.toFixed(2));
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

function costingamount(id)
{
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
        $("#costing_amount_qar-" + i).val(costingNetAmountQar.toFixed(3));
        $("#costing_amount_net-" + i).val(costing_amount_single.toFixed(3));
    }
    
    $("#costingAmounts").text(totalNet.toFixed(3));
    $("#costingNetAmounts").text(totalNet.toFixed(3));
    $("#costingNetAmountQar").text(totalQar.toFixed(3));

    var calculatedNetAmount = parseFloat($("#totalnetamount").text());
    var costingNetAmountval = parseFloat($("#costingNetAmounts").text());
    costfactor = (costingNetAmountval)/calculatedNetAmount;
    var costfactorFinal = (parseFloat(costfactor.toFixed(2)) + 1).toFixed(2);
    $("#cost_factor").val(costfactorFinal);
    
}
$(document).ready(function() {
    $('#updatebtn').on('click', function(e) {
        e.preventDefault();
        var totalnetamount = 0;
        var costingNetAmountval = parseFloat($("#costingNetAmounts").text());
        var entered_bill_amount = parseFloat($("#bill_amount").val());

        if(costingNetAmountval>0 && costingNetAmountval!="")
        {
            calculatedNetAmount = parseFloat($("#totalnetamount").text());
            totalnetamount = calculatedNetAmount - costingNetAmountval;
        }
        else{
            totalnetamount = parseFloat($("#totalnetamount").text());
        }
         // Function to validate required fields
       function validateRequiredFields() {
            var isValid = true;
            $('#data_form input[required], #data_form select[required]').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    $(this).addClass('error1'); // Add a class for styling invalid fields
                    alert('Please fill out all required fields.');
                    return false; // Break out of the loop
                } else {
                    $(this).removeClass('error'); // Remove error class if field is valid
                }
            });
            return isValid;
        }

        // Check if required fields are valid
        if (validateRequiredFields()) {
            
            if(totalnetamount==entered_bill_amount)
            {
                if (confirm("Are you sure you want to udate the data?")) {
                    var formData = $('#data_form_edit').serialize();
                        $.ajax({
                            url: baseurl +'Invoices/dataoperationedit',
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            success: function(response) {
                                alert(response.message);
                                    window.location.href = baseurl + 'Invoices/stockreciepts';
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', status, error);
                            }
                        });
                } 
            }
              
            else{
                    alert("Bill Amount("+entered_bill_amount+") & Net Amount("+totalnetamount+") is not equal");
                }
        }
        

    });
});


</script>


