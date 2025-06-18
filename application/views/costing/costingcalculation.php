<div class="content-body" >
    <div class="card" >
        <form method="post" id="data_form" enctype="multipart/form-data" action="dataoperation">
            <div class="card-content">
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <div class="message"></div>
                </div>
                <div class="card-body" style="background:#f2f2f2;">
                    
                        <div class="row" >
                        
                        <div class="col-12 mb-2" style="border-bottom:1px #d0d0d0 solid; padding:5px;">
                            <div class="row">
                                <div class="col-9">
                                    <div class="fcol-sm-12">
                                        <h3 class="title">
                                            <?php echo "Costing Calculation" ?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php //echo "<pre>"; print_r($purchasemasterdata); print_r($purchaseitemsdata);
                        ?>
                        <div class="col-md-6 mb-1">
                        <input type="hidden" name="purchase_id" id="purchase_id" value="<?=$purchasemasterdata['id']?>">
                            <!-- ================== starts ===================== -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Sale Point'; ?></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="frmclasss">
                                            <input type="text" class="form-control customer_name" name="salepoint_name" id="warehouse-search" placeholder="Enter Sale point name" autocomplete="off" value="<?php echo $purchasemasterdata['title']; ?>" required readonly/>
                                            <div id="warehouse-search-result" class="warehouse-search-result"></div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="<?php echo $purchasemasterdata['store_id']; ?>" name="salepoint_id" id="salepoint_id" autocomplete="off"  readonly/>
                                    </div> 
                                </div>   
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Supplier'; ?></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="frmclasss">
                                            <input type="text" class="form-control supplier_name" name="supplier_name" value="<?php echo $purchasemasterdata['name']; ?>" id="supplier-search" placeholder="Enter Supplier name or phone or email" autocomplete="off" required readonly/>
                                            <div id="supplier-search-result" class="supplier-search-result"></div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <input type="text" value="<?php echo $purchasemasterdata['csd']; ?>" class="form-control" name="supplier_id" id="supplier_id" autocomplete="off" readonly/>
                                    </div> 
                                </div>   
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Party Name'; ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="">
                                            <input type="text" class="form-control customer_name" name="party_name" id="party_name" placeholder="Party Name" value="<?php echo $purchasemasterdata['name']; ?>"  autocomplete="off" />
                                        </div>
                                    </div> 
                                </div>   
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Damage Claim A/c'; ?></label>
                                    </div> 
                                    <div class="col-md-5">
                                        <div class="frmclasss">
                                            <input type="text" class="form-control customer_name" name="damageclaim_ac_name" id="account-search" placeholder="" autocomplete="off"/>
                                        </div>
                                        <div id="account-search-result" class="account-search-result"></div>
                                    </div> 
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="damageclaim_ac" id="damageclaim_ac" placeholder="" autocomplete="off" readonly/>
                                    </div>
                                </div>   
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Bill #'; ?></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="bill_number" id="bill_number" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['tid']; ?>" readonly/>
                                    </div> 
                                    <div class="col-md-2">
                                        <label class="mt-1"><?php echo 'Bill Date'; ?></label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" name="bill_date" id="bill_date" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['invoicedate']; ?>"/>
                                    </div> 
                                </div>     
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Currency'; ?></label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="currency_id" id="currency_id" autocomplete="off" value="<?php echo $purchasemasterdata['code']; ?>" readonly/>
                                        <!-- <select name="currency_id" id="currency_id" class="form-control"></select> -->
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Currency Rate'; ?></label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="currency_rate" id="currency_rate" placeholder="" autocomplete="off" value="<?php echo $purchasemasterdata['rate']; ?>" readonly/>
                                    </div> 
                                </div>     
                            <!-- ================== Ends ======================= -->
                            <!-- ================== starts ===================== -->
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="mt-1"><?php echo 'Description'; ?></label>
                                    </div>
                                    
                                    <div class="col-md-9">
                                        <textarea name="bill_description" id="bill_description" class="form-control"><?php echo $purchasemasterdata['notes']; ?></textarea>
                                    </div> 
                                </div>     
                            <!-- ================== Ends ======================= -->
                        </div>
                        
                        <div class="col-md-4 mb-1">
                            <!-- ================== starts ===================== -->
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="mt-1"><?php echo 'Doc Type'; ?><i clasa="text-danger">*</i></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="frmSearch1">
                                        <input type="text" class="form-control customer_name" name="doctype" id="doctype"     autocomplete="off" value="<?php echo $purchasemasterdata['doc_type']; ?>" readonly required/>
                                    </div>
                                </div> 
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label class="mt-1"><?php echo 'Stock Receipt Voucher No.'; ?><i clasa="text-danger">*</i></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="frmSearch1">
                                        <input type="text" class="form-control customer_name" name="srv" id="srv"
                                                autocomplete="off" required/>
                                    </div>
                                </div> 
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label class="mt-1"><?php echo 'SRV Date'; ?><i clasa="text-danger">*</i></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="frmSearch1">
                                        <input type="date" class="form-control" name="srvdate" id="srvdate"
                                                autocomplete="off" required/>
                                    </div>
                                </div> 
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label class="mt-1"><?php echo 'Purchase Amount'; ?></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="frmSearch1">
                                        <input type="number" class="form-control" name="purchase_amount" id="purchase_amount"
                                                autocomplete="off" value="<?php echo $purchasemasterdata['total']; ?>" readonly />
                                    </div>
                                </div> 
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label class="mt-1"><?php echo 'Cost Factor'; ?></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="frmSearch1">
                                        <input type="number" class="form-control" name="cost_factor" id="cost_factor"
                                                autocomplete="off" required readonly/>
                                    </div>
                                </div> 
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                            <!-- ================== starts ===================== -->
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label class="mt-1"><?php echo 'Payment Date'; ?></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="frmSearch1">
                                        <input type="date" class="form-control" name="payment_date" id="payment_date"
                                                autocomplete="off" required/>
                                    </div>
                                </div> 
                            </div>  
                            <!-- ================== ends ===================== -->                                 
                        </div>
                        <hr>
                        
                        <div class="col-md-12 mb-3" style="border-top:1px #d0d0d0 solid;">
                            <div class="text-right mt-2">
                                <button type="button" class="btn btn-secondary">Print</button>
                                <button class="btn btn-primary">Save</button>
                            </div>  
                        </div>


                
                </div>

                <!-- ==================== tab secction starts ================ -->
                <div class="card-body" style="background:#ffffff;">
                    <button type="button" name="addrow_btn" id="addrow_btn" style="float:right;" class="btn btn-primary">Add row</button>
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
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th style="width:150px !important;">Sl#</th>
                                        <th style="width:20%;">Item Name</th>                                    
                                        <th style="width:10%;">Item #</th>
                                        <th style="width:15%;">Unit</th>
                                        <th style="width:25%;">Qty</th>
                                        <th style="width:5%;">Free of Charge(FOC)</th>
                                        <th style="width:5%;">Damage</th>
                                        <th style="width:5%;">Price</th>
                                        <th style="width:5%;">Sales Price</th>
                                        <th style="width:5%;">Amount</th>
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
                                foreach($purchaseitemsdata as $item){
                                        $amount = $item['price']*$item['qty'];
                                        $qtrprice = $purchasemasterdata['rate']*$item['subtotal'];
                                        $totalamount = $totalamount+$amount;
                                        $totalqaramount = $totalqaramount+$qtrprice;
                                        $totaldiscount = $totaldiscount+$item['totaldiscount'];
                                        $totalnetamount = $totalnetamount+$item['subtotal'];
                                        echo '<tr>';
                                        echo '<td>'.++$j.'</td>';                                   
                                        echo '<td><input type="text" class="form-control" name="product_name[]" id="productname-'.$j.'" value="'.$item['product'].'" style="width:250px;" readonly></td>';
                                        echo '<td><input type="text" name="product_code[]" id="product_code-'.$j.'" class="form-control" value="'.$item['code'].'" style="width:100px;" readonly></td>';
                                        echo '<td><input type="text" name="product_unit[]" id="product_unit-'.$j.'" class="form-control" value="'.$item['unit'].'" style="width:100px;" readonly></td>';
                                        echo '<td><input type="text" name="product_qty[]" id="product_qty-'.$j.'" class="form-control" value="'.$item['qty'].'" style="width:100px;" readonly></td>';
                                        echo '<td><input type="number" name="product_foc[]" id="product_foc-'.$j.'" class="form-control"  style="width:150px;"></td>';
                                        echo '<td><input type="number" name="damage[]"-'.$j.'" class="form-control"  style="width:150px;"></td>';
                                        echo '<td><input type="text" name="price[]" id="price-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['price'].'" readonly></td>';
                                        echo '<td><input type="text" name="saleprice[]" id="saleprice-'.$j.'" class="form-control"  style="width:150px;" readonly></td>';
                                        echo '<td><input type="text" name="amount[]" id="amount-'.$j.'" class="form-control"  style="width:150px;" value='.$amount.' readonly></td>';
                                        echo '<td><input type="text" name="discountperc[]" id="discountperc-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['discount'].'" readonly></td>';
                                        echo '<td><input type="text" name="discountamount[]" id="discountamount-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['totaldiscount'].'" readonly></td>';
                                        echo '<td><input type="text" name="netamount[]" id="netamount-'.$j.'" class="form-control"  style="width:150px;" value="'.$item['subtotal'].'" readonly></td>';
                                        echo '<td><input type="text" name="qaramount[]" id="qaramount-'.$j.'" class="form-control"  style="width:150px;" value='.$qtrprice.' readonly></td>';
                                        echo '<td><textarea name="" id="" class="form-control"  style="width:250px;">'.$item['product_des'].'</textarea></td>';
                                        echo '</tr>';
                                    } ?>
                                    <!-- $totalamount = $totalamount+$amount;
                                        $totalqaramount = $totalqaramount+$qtrprice;
                                        $totaldiscount = $totaldiscount+$item['totaldiscount'];
                                        $totalnetamount = $totalnetamount+$item['subtotal']; -->
                                    <tr>
                                        <th colspan="9" style="text-align:right;">Total</th>
                                        <th><span id="totalamount"><?php echo $totalamount; ?></span></th>
                                        <th></th>
                                        <th><span id="totaldiscount"><?php echo $totaldiscount; ?></span></th>
                                        <th><span id="totalnetamount"><?php echo $totalnetamount; ?></span></th>
                                        <th><span id="totalqaramount"><?php echo $totalqaramount; ?></span></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane saman-row" id="tab2" role="tabpanel" aria-labelledby="base-tab2" style="width: 100%; overflow-x: auto;">
                            


                        <div id="saman-row">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                        <tr >
                                            <th style="width:250px !important;">Sl#</th>
                                            <th style="width:200px !important;">Expense</th>
                                            <th style="width:10%;">Expense #</th>
                                            <th style="width:15%;">Account Name</th>
                                            <th style="width:10%;">Payable A/c</th>
                                            <th style="width:250px;">Bill No.</th>
                                            <th style="width:10%;">Bill Date</th>
                                            <th style="width:10%;">Amount</th>
                                            <th style="width:10%;">Currency</th>
                                            <th style="width:10%;">Currency Rate</th>
                                            <th style="width:10%;">Net Amt.</th>
                                            <th style="width:45%;">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="last-item-row">   

                                            <td>1</td>
                                            <td><input type="hidden" name="ganak" id="ganak" value="1">
                                            <input type="text" name="expense_name[]" id="expense_name-0" class="form-control expense_name1" style="width:150px;" data-id="0">
                                            <div id="expense-search-result-0" class="expense-search-result-0"></div></td>
                                            <td><input type="text" name="expense_id[]" id="expense_id-0" class="form-control"  style="width:100px;" readonly></td>
                                            <td><input type="text" name="payable_acc[]" id="payable_acc-0" class="form-control" style="width:250px;"></td>
                                            <td><input type="text" name="payable_acc_no[]" id="payable_acc_no-0" class="form-control" style="width:150px;"></td>
                                            <td><input type="text" name="bill_number_cost[]" id="bill_number_cost-0" value="<?php echo $purchasemasterdata['tid']; ?>" class="form-control" style="width:150px;"></td>
                                            <td><input type="text" name="bill_date_cost[]" id="bill_date_cost-0" class="form-control" style="width:150px;" value="<?php echo $purchasemasterdata['invoicedate']; ?>"></td>
                                            <td><input type="text" name="costing_amount[]" id="costing_amount-0" class="form-control" style="width:100px;"></td>
                                            <td><input type="text" name="currency_cost[]" id="currency_cost-0" value="<?php echo $purchasemasterdata['code']; ?>" class="form-control" style="width:150px;" readonly></td>
                                            <td><input type="text" name="currency_rate_cost[]" id="currency_rate_cost-0" value="<?php echo $purchasemasterdata['rate']; ?>" class="form-control" style="width:150px;" readonly></td>
                                            <td><input type="text" name="costing_amount_qar[]" id="costing_amount_qar-0" class="form-control" style="width:100px;" readonly></td>
                                            <td><textarea name="remarks[]" id="remarks-0" class="form-control" style="width:250px;"></textarea></td>
                                        </tr>


                                        <tr>
                                            <td colspan="11" style="text-align:right;">Total</td>
                                            <td><span id="costing-total"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                     
                         
                                <input type="hidden" value="expense_search" id="billtype">      
                        </div>
                    
                    </div>
                </div>
                <!-- ==================== tab secction ends ================== -->
            </div>
        </form>
    </div>
</div>

<script>
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
    $(document).ready(function() {
        // $('#currency_rate').val("");
        $("#addrow_btn").hide();
        // currencysearch();
        $('#addmore_img').click(function() {
            var fileId = $('.form-control').length; // Get the number of existing file inputs
            var newInput = '<div class="col-10 mt-1"><input type="file" name="upfile[]" id="upfile-' + fileId + '" class="form-control"></div>';
            newInput += '<div class="col-2 mt-1"><button type="button" class="btn  btn-danger delete-btn"><i class="fa fa-trash"></i></button></div>';
            $('#uploadsection').append('<div class="row">' + newInput + '</div>');
        });

        // Event delegation to handle delete button clicks on dynamically added elements
        $('#uploadsection').on('click', '.delete-btn', function() {
            $(this).closest('.row').remove(); // Remove the parent row containing the file input and delete button
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var customerTypeRadios = document.querySelectorAll('input[name="customerType"]');
        var customerLabel = document.getElementById('customerLabel');

        customerTypeRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                $(".customer_name").removeAttr("id");
                $(".customer-search-result").removeAttr("id");
                $('.customer_name').val("");
                $('#customer_phone').val("");
                $('#customer_email').val("");
                $('#customer_address').val("");
                if (this.value === 'new') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    
                }
                else if (this.value === 'guest') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    
                } else {
                    customerLabel.textContent = "<?php echo $this->lang->line('Search Customer'); ?>";
                    $(".customer_name").attr("id","customer-search");
                    $(".customer-search-result").attr("id","customer-search-result");
                }
            });
        });
    });

    $("#supplier-search").keyup(function () {
        $.ajax({
            type: "POST",
            url: baseurl + 'CostingCalculation/suppliersearch',
            data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
            beforeSend: function () {
                $("#supplier-search").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
            },
            success: function (data) {
                $("#supplier-search-result").show();
                $("#supplier-search-result").html(data);
                $("#supplier-search").css("background", "none");

            }
        });
    });
    function selectedSupplier(cid, title,phone) {
        $('#supplier-search').val(title);
        $('#supplier_id').val(cid);
        $("#supplier-search-result").hide();
    }
    $("#warehouse-search").keyup(function () {
        $.ajax({
            type: "POST",
            url: baseurl + 'CostingCalculation/warehousesearch',
            data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
            beforeSend: function () {
                $("#warehouse-search").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
            },
            success: function (data) {
                console.log(data);
                $("#warehouse-search-result").show();
                $("#warehouse-search-result").html(data);
                $("#warehouse-search").css("background", "none");

            }
        });
    });
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
    function currencysearch() {
        $.ajax({
            type: "POST",
            url: baseurl + 'CostingCalculation/currencysearch',
            data:  crsf_token + '=' + crsf_hash,
            success: function (data) {
                $("#currency_id").html(data);

            }
        });
    }

    $('#currency_id').change(function() {
        var rate = $(this).find(':selected').data('rate');
        $('#currency_rate').val(rate);
    });

function selectedWarehouse(cid, title) {
    $('#warehouse-search').val(title);
    $('#salepoint_id').val(cid);
    $("#warehouse-search-result").hide();
}

function selectedAccount(cid, holder,accno) {
    $('#account-search').val(holder);
    $('#damageclaim_ac').val(accno);
    $("#account-search-result").hide();
}
$("#item_li").click(function(){
    $("#addrow_btn").hide();
});
$("#costing_li").click(function(){
    $("#addrow_btn").show();
});


</script>