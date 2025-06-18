<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('purchase') ?>">Purchase Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('New Purchase Order') ?> #<?php echo $lastinvoice + 1000 ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('New Purchase Order'); ?> </h4>
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
            <form method="post" id="data_form1">
               <div class="row">
                  <div class="col-xl-4 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                     <div id="customerpanel" class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="fcol-sm-12">
                              <h3 class="title-sub">
                              <?php echo $this->lang->line('Supplier Details') ?> 
                           </div>
                           <div class="frmSearch col-sm-12">
                              <label for="cst"class="col-form-label"><?php echo $this->lang->line('Search Supplier') ?> </label>
                              <input type="text" class="form-control required" name="cst" id="supplier-box"
                                 placeholder="Enter Supplier Name or Mobile Number to search"
                                 autocomplete="off" required/>
                              <div id="supplier-box-result"></div>
                           </div>
                        </div>
                        <div id="customer">
                           <div class="clientinfo">
                              <input type="hidden" name="customer_id" id="customer_id" value="0">
                              <div id="customer_name"></div>
                           </div>
                           <div class="clientinfo">
                              <div id="customer_address1"></div>
                           </div>
                           <div class="clientinfo">
                              <div type="text" id="customer_phone"></div>
                           </div>
                        </div>
                        <div class="form-group row">
                           
                           
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-8 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                     <div class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <h3
                                 class="title-sub"><?php echo $this->lang->line('Order Details') ?> </h3>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Order Number') ?> </label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-file-text-o"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control" placeholder="Purchase Order #" name="invocieno" value="<?php echo $lastinvoice + 1000 ?>" readonly>
                              </div>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno"
                                 class="col-form-label"><?php echo $this->lang->line('Reference') ?> </label>
                              
                                 <input type="text" class="form-control" placeholder="Reference #"
                                    name="refer">
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="Doc Type"   class="col-form-label"><?php echo $this->lang->line('Doc Type') ?><span class="compulsoryfld">*</span></label>
                                 <select name="doc_type" id="doc_type" class="form-control required" required>
                                    <option value="">Select Document Type</option>
                                    <option value="Local Cash Purchase">Local Cash Purchase</option>
                                    <option value="Local Credit Purchase">Local Credit Purchase</option>
                                    <option value="International Purchase">International Purchase</option>
                                 </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="currency" class="col-form-label"><?php echo $this->lang->line('Currency') ?><span class="compulsoryfld">*</span></label>
                                 <select name="currency_id" id="currency_id" class="form-control required" required>
                                    <option value="">Select Currency</option>
                                    <?php
                                       foreach($currencies as $currency){
                                          echo "<option value='".$currency['id']."'>".$currency['code']."</option>";
                                       }
                                       ?>
                                 </select>
                           </div>
                           
                           <input type="hidden" class="form-control required" placeholder="Billing Date" name="invoicedate" value="<?=date('Y-m-d')?>">
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Order Due Date') ?><span class="compulsoryfld">*</span></label>
                                 <input type="date" class="form-control" id="tsn_due1" name="invocieduedate" placeholder="Due Date" min="<?=date('Y-m-d')?>">
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
                              <label for="discountFormat"
                                 class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                              <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                 id="discountFormat">
                              <?php echo $this->common->disclist() ?>
                              </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?> *</label>
                              <select id="s_warehouses" name="store_id" class="selectpicker form-control required">
                              <?php 
                                 echo '<option value=""> Select Warehouse'; ?></option><?php foreach ($warehouse as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>
                           <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 d-none">
                              <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?> </label>
                              <select name="pterms" class="selectpicker form-control">
                              <?php foreach ($terms as $row){
                                 echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
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
                              <!-- erp2024 old radio buttons -->
                              <!-- <fieldset class="right-radio">
                                 <div class="custom-control custom-radio">
                                 <input type="radio" class="custom-control-input" name="update_stock" id="customRadioRight1" value="yes" >
                                 <label class="custom-control-label"
                                     for="customRadioRight1"><?php //echo $this->lang->line('Yes') ?></label>
                                 </div>
                                 </fieldset>
                                 <fieldset class="right-radio">
                                 <div class="custom-control custom-radio">
                                 <input type="radio" class="custom-control-input" name="update_stock"
                                     id="customRadioRight2" value="no" checked="">
                                 <label class="custom-control-label"
                                     for="customRadioRight2"><?php //echo $this->lang->line('No') ?></label>
                                 </div>
                                 </fieldset> -->
                              <!-- erp2024 old radio buttons ends-->
                           </div>
                           <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo" class="col-form-label"><?php echo $this->lang->line('Order Note') ?> </label>
                              <textarea class="form-textarea" name="notes" rows="2"></textarea>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="saman-row">
                  <table class="table table-striped table-bordered zero-configuration dataTable">
                     <thead>
                        <tr class="item_header bg-gradient-directional-blue white">
                           <th width="30%" class="text-center1 pl-1"><?php echo $this->lang->line('Item Decription & No') ?></th>
                           <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                           <th width="10%" class="text-center"><?php echo $this->lang->line('Rate') ?></th>
                           <!-- <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                           <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th> -->
                           <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                           <th width="10%" class="text-right">
                              <?php echo $this->lang->line('Amount') ?>
                              (<?php echo $this->config->item('currency'); ?>)
                           </th>
                           <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr class="startRow">
                           <td><input type="text" class="form-control" name="product_name[]"
                              placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                              id='productname-0'>
                           </td>
                           <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                              onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                              autocomplete="off" value="1"></td>
                           <td><input type="text" class="form-control req prc" name="product_price[]" id="price-0"
                              onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                              autocomplete="off" readonly></td>
                           <td class="d-none"><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                              onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                              autocomplete="off"></td>
                           <td class="text-center d-none" id="texttaxa-0">0</td>
                           <td><input type="text" class="form-control discount" name="product_discount[]"
                              onkeypress="return isNumber(event)" id="discount-0" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off" readonly></td>
                           <td class="text-right"><strong><span class='ttlText' id="result-0">0</span></strong>
                           </td>
                           <td class="text-center">
                           </td>
                           <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                           <input type="hidden" name="disca[]" id="disca-0" value="0">
                           <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                           <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                           <input type="hidden" name="unit[]" id="unit-0" value=""><input type="hidden"
                              name="hsn[]" id="hsn-0"
                              value="">
                        </tr>
                        <!-- <tr  class="startRow">
                           <td colspan="8"><textarea id="dpid-0" class="form-control" name="product_description[]"
                              placeholder="<?php echo $this->lang->line('Enter Product description'); ?>"
                              autocomplete="off"></textarea></td>
                        </tr> -->
                        <tr class="last-item-row tr-border">
                           <td class="add-row no-border">
                              <button type="button" class="btn btn-secondary" aria-label="Left Align"
                                 id="addproduct1">
                              <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                              </button>
                           </td>
                           <td colspan="7" class="no-border"></td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="4" align="right" class="no-border"><input type="hidden" value="0" id="subttlform"
                              name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><span
                              class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                              <span id="taxr" class="lightMode">0</span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="4" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Total Discount') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><span
                              class="currenty lightMode"></span>
                              <span id="discs" class="lightMode">0</span>
                           </td>
                        </tr>
                        <tr class="sub_c d-none" style="display: table-row;">
                           <td colspan="4" align="right" class="no-border">
                              <strong><?php echo $this->lang->line('Shipping') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                              onkeypress="return isNumber(event)"
                              placeholder="Value"
                              name="shipping" autocomplete="off"
                              onkeyup="billUpyog();">
                              ( <?php echo $this->lang->line('Tax') ?>
                              <span id="ship_final">0</span> )
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <!-- <td colspan="2" class="no-border">
                              <?php if ($exchange['active'] == 1){
                                 echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                              <select name="mcurrency"
                                 class="selectpicker form-control">
                                 <option value="0">Default</option>
                                 <?php foreach ($currency as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                    } ?>
                              </select>
                              <?php } ?>
                           </td> -->
                           <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                              (<span
                                 class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                           </td>
                           <td align="left" colspan="2" class="no-border">
                              <span id="grandtotaltext"></span>
                              <input type="hidden" name="total" class="form-control"
                              id="invoiceyoghtml" readonly>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="2" class="no-border"></td>
                           <td align="right" colspan="4" class="no-border">
                              <input type="submit" class="btn btn-lg btn-secondary sub-btn"  value="<?php echo $this->lang->line('Save As Draft') ?>" id="submit-purchase-orderbtn-draft" data-loading-text="Creating...">
                              <input type="submit" class="btn btn-lg btn-primary sub-btn"  value="<?php echo $this->lang->line('Generate Order') ?>" id="submit-purchase-orderbtn" data-loading-text="Creating...">
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <!-- <input type="hidden" value="purchase/action" id="action-url"> -->
               <input type="hidden" value="puchase_search" id="billtype">
               <input type="hidden" value="0" name="counter" id="ganak">
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

<script>
$(document).ready(function() {
   $("#data_form1").validate({
        rules: {
            cst: {required:true},
            refer: {required:true},
            doc_type: {required:true},
            currency_id: {required:true},
            invocieduedate: {required:true},
            store_id: {required:true},
        },
        messages: {
            cst    : "Search Supplier",
            refer  : "Enter Reference",
            doc_type  : "Doc Type",
            currency_id  : "Currency",
            invocieduedate  : "Order Due Date",
            store_id  : "Warehouse ",
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {             
            error.addClass( "help-block" ); 
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            }else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        },
    });
});
$("#submit-purchase-orderbtn").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    if ($("#data_form1").valid()) {
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to complete this Purchase Order?Once completed, You can't edit.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
      }).then((result) => {
            if (result.isConfirmed) {
               var formData = $("#data_form1").serialize(); 
               formData += '&completed_status=1';
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/action',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'purchase'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
      });
    }
});
$("#submit-purchase-orderbtn-draft").on("click", function(e) {
    e.preventDefault();
    var selectedProducts1 = [];
    var validationFailed = false;
    if ($("#data_form1").valid()) {
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to save this Purchase Order as draft?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: "No - Cancel",
            reverseButtons: true,
            focusCancel: true
      }).then((result) => {
            if (result.isConfirmed) {
               var formData = $("#data_form1").serialize(); 
               formData += '&completed_status=0';
               $.ajax({
                  type: 'POST',
                  url: baseurl +'purchase/action',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'purchase'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
      });
   }
});
</script>