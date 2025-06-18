<div class="content-body">
    <div class="card">
    <?php $invoice_number = $prefix.$lastinvoice + 1; ?>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices') ?>"><?php echo $this->lang->line('Invoices'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Invoice'); ?> #<?php echo $invoice_number ?></li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <h4 class="card-title"><?php echo $this->lang->line('Invoice'); ?> #<?php echo $lastinvoice + 1 ?></h4>
                </div>
                <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12">

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
            <!-- ============================================================================ -->
            <div class="card-body">
                <form method="post" id="data_form">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row d-none">
                                    <div class="fcol-sm-12">
                                        <h3 class="sub-title">
                                            <?php echo $this->lang->line('Bill To') ?> <a href='#'
                                                class="btn btn-primary btn-sm round" data-toggle="modal"
                                                data-target="#addCustomer">
                                                <?php echo $this->lang->line('Add Client') ?>
                                            </a>
                                    </div>
                                </div>

                                <div class="form-group1 row">
                                    <div class="frmSearch col-sm-12">
                                        <label for="cst"
                                            class="caption d-none"><?php echo $this->lang->line('Search Client'); ?></label>
                                        <input type="text" class="form-control d-none" name="cst" id="customer-box"
                                            placeholder="Enter Customer Name or Mobile Number to search"
                                            autocomplete="off" />
                                        <div id="customer-box-result">
                                        </div>
                                    </div>
                                </div>
                                <div id="customer">
                                    <div class="clientinfo">
                                        <h3 class="sub-title"><?php echo $this->lang->line('Client Details'); ?></h3>
                                        <?php 
                                        echo '<input type="hidden" name="customer_id" id="customer_id" value="'.$customerid.'">';
                                        ?>
                                        <div>
                                            <strong>
                                                <?php 
                                                echo $custname."\n<br>";
                                                echo $address."\n<br>";
                                                echo $city.",";
                                                echo $country."\n<br>";
                                                ?>
                                            </strong>
                                            <span>Email : <strong><?php echo $email."\n<br>"; ?></strong></span>
                                            <span>Phone : <strong><?php echo $phone."\n<br>"; ?></strong></span>
                                            <span><?php echo $this->lang->line('Company Credit Limit'); ?> : <strong><?php echo $credit_limit."\n<br>"; ?></strong></span>
                                            <span><?php echo $this->lang->line('Credit Period'); ?> : <strong><?php echo $credit_period."\n<br>"; ?></strong></span>
                                            <span><?php echo $this->lang->line('Available Credit Limit'); ?> : <strong><?php echo $avalable_credit_limit."\n<br>"; ?></strong></span>
                                        </div>

                                        <div id="customer_name"></div>
                                    </div>
                                    <div class="clientinfo">
                                        <div id="customer_address1"></div>
                                    </div>

                                    <div class="clientinfo">

                                        <div id="customer_phone"></div>
                                    </div>
                                    <hr>
                                    <div id="customer_pass"></div>

                                </div>


                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 cmp-pnl">
                            <div class="inner-cmp-pnl">
                                <div class="form-group row">

                                    <div class="col-sm-12">
                                        <h3 class="sub-title"><?php echo $this->lang->line('Invoice Properties') ?></h3>
                                    </div>

                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                                        <label for="invocieno"
                                            class="col-form-label"><?php echo $this->lang->line('Invoice Number') ?></label>
                                           
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-file-text-o"
                                                    aria-hidden="true"></span></div>
                                            <input type="hidden" class="form-control" placeholder="Invoice #"  name="invocieno" value="<?php echo $lastinvoice + 1 ?>" readonly>
                                            <input type="text" class="form-control" placeholder="Invoice #"  name="invoice_number" value="<?php echo $invoice_number ?>" readonly>
                                                
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12"><label for="invocieno"
                                            class="col-form-label"><?php echo $this->lang->line('Reference') ?></label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o"
                                                    aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #"
                                                name="refer">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12"><label for="invociedate"
                                            class="col-form-label"><?php echo $this->lang->line('Invoice Date'); ?></label>

                                        <div class="input-group">
                                            <input type="date" class="form-control required" placeholder="Billing Date"
                                                name="invoicedate" value="<?=date('Y-m-d')?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12"><label for="invocieduedate"
                                            class="col-form-label"><?php echo $this->lang->line('Invoice Due Date') ?><span class="compulsoryfld"> *</span></label>

                                        <div class="input-group">

                                            <input type="date" class="form-control required" name="invocieduedate"
                                                placeholder="Due Date" autocomplete="false" min="<?=date('Y-m-d')?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 ">
                                        <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                        <select id="s_warehouses"  name="s_warehouses" class="form-control" disabled>
                                        <?php 
                                        
                                        echo '<option value="">' . $this->lang->line('Select Warehouse') ?></option>
                                        <?php foreach ($warehouse as $row) {
                                            $sel="";
                                            if($master_data[0]['store_id'] == $row['id']){
                                                $sel="selected";
                                            }
                                            echo '<option value="' . $row['id'] . '" '.$sel.'>' . $row['title']. '</option>';
                                        } ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="toAddInfo"
                                            class="col-form-label"><?php echo $this->lang->line('Invoice Note') ?></label>
                                        <textarea class="form-textarea" name="notes" rows="2"></textarea>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>

                        <div class="col-12">
                            <!-- ===============Loop stars ====================== -->
                            <?php 
                                if(!empty($master_data))
                                {
                                
                                ?>
                                <div id="saman-row">
                                    <table class="table table-striped table-bordered zero-configuration dataTable">
                                        <thead>
                                        <tr class="item_header bg-gradient-directional-blue white">
                                            <th width="2%" class="text-center1 pl-1"><?php echo $this->lang->line('No') ?></th>
                                            <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Decription & No') ?></th>                                            
                                            <th width="3%" class="text-center1 pl-1"><?php echo "DelNote No." ?></th>
                                            <th width="3%" class="text-center1 pl-1"><?php echo $this->lang->line('Unit') ?></th>
                                            <th width="3%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                            <th width="10%" class="text-right"><?php echo $this->lang->line('Rate') ?></th>
                                            <?php 
                                                if($configurations['config_tax']!='0'){  ?>
                                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Tax(%)') ?></th>
                                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                                            <?php } ?>
                                            <th width="7%" class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                                            <th width="10%" class="text-right">
                                                <?php echo $this->lang->line('Amount') ?>
                                                (<?= currency($this->aauth->get_user()->loc); ?>)
                                            </th>
                                            <!-- <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th> -->
                                        </tr>

                                        </thead>
                                        <tbody>
                                        <?php 
                                        $i=0;
                                        $j=1;
                                        $grandtotal = 0;
                                        $totaldiscount = 0;
                                        $subtotal =0;
                                        foreach($master_data as $row)
                                        {
                                            $grandtotal += $row['subtotal'];
                                            $totaldiscount += $row['totaldiscount'];
                                            // $subtotal += intval($row['product_qty'])*$row['product_price'];

                                            $current_qty = intval($row['product_qty']) - intval($row['delivery_returned_qty']);                                            
                                            $subtotal += intval($current_qty)*$row['product_price'];
                                            $old_product_total = intval($row['product_qty'])*$row['product_price'];
                                            $old_product_subtotal += $old_product_total; 

                                            $discountPecrectage = ($row['delnote_discounttype']=='Perctype') ? $row['product_discount'] :  find_discount_perecntage_from_amount($old_product_total, $row['deliverytotaldiscount']);
                                            
                                            $actulprice = $current_qty*$row['product_price'];

                                            $discountamount_total = ($row['delnote_discounttype']=='Perctype') ? convert_order_discount_percentage_to_amount($actulprice, $row['product_discount']) :  number_format(($current_qty*$row['product_discount']),2);
                                        
                                            $total_product_amount = ($actulprice - $discountamount_total);

                                            $total_product_discount += $discountamount_total;

                                            $grand_total += $total_product_amount; 

                                            // echo "\n<br>".$total_product_amount;
                                           
                                        ?>
                                            <tr class="startRow">
                                                <?php 

                                                echo '<td><strong>'.$j.'</strong>';
                                                if($row['employeeid']>0)
                                                {
                                                    echo '<input type="hidden" name="eid" id="eid" value="'.$row['employeeid'].'">'; 
                                                } 
                                                if($row['store_id']>0)
                                                {
                                                    echo '<input type="hidden" name="store_id" id="store_id" value="'.$row['store_id'].'">'; 
                                                } 
                                                if(!empty($row['refer']))
                                                {
                                                    echo '<input type="hidden" name="refer" id="refer" value="'.$row['refer'].'">'; 
                                                } 
                                                if(!empty($row['term']))
                                                {
                                                    echo '<input type="hidden" name="term" id="term" value="'.$row['term'].'">'; 
                                                } 
                                                echo '</td>';
                                                echo '<td><strong>'.$row['product'].'</strong> </td>';

                                                echo '<td class="text-center"><strong>'.$row['delnote_number'].'</strong><input type="hidden" class="form-control" name="delnote_numbers[]" id="delnote_numbers-' . $i . '" value="'.$row['delnote_number'].'"><input type="hidden" class="form-control" name="delevery_note_ids[]" id="delevery_note_ids-' . $i . '" value="'.$row['delevery_note_id'].'"><input type="hidden" class="form-control" name="product_name[]" id="product_name-' . $i . '" value="'.$row['product'].'"></td>';

                                                echo '<td class="text-center"><strong>'.$row['unit'].'</strong> </td>';
                                                echo '<td class="text-center"><strong>'.intval($current_qty).'</strong><input type="hidden" class="form-control req amnt product_qty" name="product_qty[]" id="amount-' . $i . '" onkeypress="return isNumber(event)" onkeyup="checkqty(' . $i . '),rowTotal(' . $i . '), billUpyog()" autocomplete="off" value="'.$current_qty.'" min="0"><input type="hidden" class="form-control req"id="enteredamount-' . $i . '" value="'.$current_qty.'" min="0"></td>';

                                                echo '<td style="text-align:right;"><strong>'.$row['product_price'].'</strong><input type="hidden" class="form-control req prc" name="product_price[]" id="price-' . $i . '"value="' . $row['product_price'] . '"></td>';
                                                echo '<td class="text-right"><strong id="discount-amtlabel-' . $i . '" class="discount-amtlabel discount-amtlabel-' . $i . '">'.$discountamount_total.'</strong></td>';
                                                echo '<td class="text-right">
                                                <strong><span class="ttlText" id="result-' . $i . '">'.number_format($total_product_amount,3).'</span></strong></td>
                                                </td>';
                                                echo '<input type="hidden" name="taxa[]" id="taxa-' . $i . '" value="' .$row['totaltax'] . '">

                                                <input type="hidden" name="disca[]" id="disca-' . $i . '" value="' .$discountamount_total . '">
                                                
                                                <input type="hidden" class="form-control discount" name="product_discount[]"onkeypress="return isNumber(event)" id="discount-' . $i . '" onkeyup="rowTotal(' . $i . '), billUpyog()" autocomplete="off"  value="' . $row['product_discount'] . '">

                                                <input type="hidden" min="0" class="form-control discount" name="product_amt[]" id="discountamt-' . $i . '" autocomplete="off" onkeyup="discounttypeChange(' . $i . ')" value="' .$row['product_discount'] . '">

                                                <input type="hidden" class="form-control" name="discount_type[]" id="discounttype-' . $i . '"   value="'.$row['delnote_discounttype'].'">
                                            
                                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-' . $i . '" value="'.($total_product_amount).'">
                                                <input type="hidden" class="pdIn" name="pid[]" id="pid-' . $i . '" value="' . $row['product_id'] . '">
                                                <input type="hidden" name="unit[]" id="unit-' . $i . '" value="' . $row['unit'] . '">  <input type="hidden" name="hsn[]" id="unit-' . $i . '" value="' . $row['product_code'] . '">';
                                                $j++;
                                                $i++;
                                                ?>
                                            </tr>
                                        <?php } 
                                        $order_discount_percentage = order_discount_percentage($orderamount,$old_product_subtotal);
                                        
                                        $order_discountamount = convert_order_discount_percentage_to_amount($subtotal, $order_discount_percentage);
    
                                        $grand_total -= $order_discountamount;
                                        //end of foreach ?>

                                        <tr class="sub_c d-none" style="display: table-row;"> 
                                            <td colspan="6" class="reverse_align no-border"><input type="text" value="<?=$row['subtotal']?>" id="subttlform"  name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                            </td>
                                            <td align="left" colspan="2" class="no-border"><span
                                                        class="currenty lightMode"><?= $this->config->item('currency'); ?></span>
                                                <span id="taxr" class="lightMode">0</span></td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="7" class="reverse_align no-border">
                                                <strong><?php echo $this->lang->line('Subtotal') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                            <td align="right" colspan="2" class="no-border">
                                                <span class="lightMode"><?=number_format($subtotal,3)?></span>
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="7" class="reverse_align no-border">
                                                <strong><?php echo $this->lang->line('Total Product Discount') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                            <td align="right" colspan="2" class="no-border"><span
                                                        class="currenty lightMode"><?php 
                                                    if (isset($_GET['project'])) {
                                                        echo '<input type="hidden" value="' . intval($_GET['project']) . '" name="prjid">';
                                                    } ?></span>
                                                <span id="discs" class="lightMode"><?=$total_product_discount?></span>
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="7" class="reverse_align no-border">
                                                <strong><?php echo $this->lang->line('Order Discount') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                            <td align="right" colspan="2" class="no-border">
                                                <span  class="lightMode1"><?=number_format($order_discountamount,3)?></span>
                                                <input type="hidden" name="order_discount" id="order_discount" value="<?=$order_discountamount?>">
                                            </td>
                                        </tr>

                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="7" class="reverse_align no-border">
                                                <strong><?php echo $this->lang->line('Shipping') ?> (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong></td>
                                            <td align="left" colspan="2" class="no-border"><input type="text" class="form-control text-right shipVal" onkeypress="return isNumber(event)" placeholder="Value" name="shipping" autocomplete="off"  onkeyup="billUpyog()">
                                            </td>
                                        </tr>
                                        <tr class="sub_c d-none" style="display: table-row;">
                                            <td colspan="7" class="reverse_align no-border">
                                                <strong> <?php echo $this->lang->line('Extra') . ' ' . $this->lang->line('Discount') ?></strong>
                                            </td>
                                            <td align="left" colspan="2" class="no-border"><input type="text"  class="form-control form-control discVal" onkeypress="return isNumber(event)" placeholder="Value" name="disc_val" autocomplete="off" value="<?=$totaldiscount?>" onkeyup="billUpyog()">
                                                <input type="hidden" name="after_disc" id="after_disc" value="0">
                                                ( <?= $this->config->item('currency'); ?>
                                                <span id="disc_final">0</span> )
                                            </td>
                                        </tr>


                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="5" class="no-border"></td>
                                            <td colspan="2" class="reverse_align no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                                    (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                            </td>
                                            <td align="right" colspan="2" class="no-border">
                                                <span id="grandtotaltext"><?=number_format($grand_total,3)?></span>
                                                <input type="hidden" name="total" class="form-control" id="invoiceyoghtml" value="<?=$grand_total?>" readonly>
                                            </td>
                                        </tr>
                                        <tr class="sub_c" style="display: table-row;">
                                            <td colspan="6" class="no-border"></td>
                                            <td class="reverse_align no-border" colspan="3">
                                                <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn btn-lg"  value="<?php echo $this->lang->line('Generate Invoice') ?> " id="merge-deliverynotes-to-invoice-btn" data-loading-text="Creating...">
                                                <!-- <input type="submit" class="btn btn-lg btn-primary sub-btn btn-lg"  value="<?php echo $this->lang->line('Generate Invoice') ?> " id="submit-data" data-loading-text="Creating..."> -->
                                            </td>
                                        </tr>

                                        
                                    </table>
                                </div>
                                <input type="hidden" value="DeliveryNotes/actionconverttomultipleinvoice" id="action-url">
                                <?php
                                }
                                else{
                                echo "<h4>No Data Found</h4>";
                                } ?>
                            <!-- ===============Loop Ends ====================== -->
                        </div>



                </form>
            </div>
            <!-- ============================================================================ -->
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $("#data_form").validate({
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {
                invocieduedate: { required: true },
            },
            messages: {
                invocieduedate: "Enter Invoice Due Date"
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

        $('#merge-deliverynotes-to-invoice-btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $('#merge-deliverynotes-to-invoice-btn').prop('disabled', true); // Disable button to prevent multiple submissions

            // Validate the form
            if ($("#data_form").valid()) {                
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to create a new invoice?",
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
                            url: baseurl + 'DeliveryNotes/actionconverttomultipleinvoice', // Replace with your server endpoint
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                window.location.href = baseurl + 'invoices/view?id='+response.data; 
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Enable the button again if user cancels
                        $('#merge-deliverynotes-to-invoice-btn').prop('disabled', false);
                    }
                });
            } else {
                // If form validation fails, re-enable the button
                $('#merge-deliverynotes-to-invoice-btn').prop('disabled', false);
            }
        });

        

    });
</script>