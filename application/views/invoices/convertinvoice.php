<div class="content-body">
   <div class="card">
      <div class="card-header border-bottom">
            <h4 class="card-title"><?php echo $this->lang->line('Convert to Invoice'); ?> </h4>
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
                        <div class="form-group row d-none">
                           <div class="fcol-sm-12">
                              <h3 class="title">
                              <?php echo $this->lang->line('Bill To') ?> <a href='#'
                                 class="btn btn-primary btn-sm round"
                                 data-toggle="modal"
                                 data-target="#addCustomer">
                              <?php echo $this->lang->line('Add Client') ?>
                              </a>
                           </div>
                        </div>
                        <div class="form-group row d-none">
                           <div class="frmSearch col-sm-12">
                              <label for="cst"
                                 class="caption d-none"><?php echo $this->lang->line('Search Client'); ?></label>
                              <input type="text" class="form-control d-none" name="cst" id="customer-box"
                                 placeholder="Enter Customer Name or Mobile Number to search"
                                 autocomplete="off"/>
                              <div id="customer-box-result">
                              </div>
                           </div>
                        </div>
                        <div id="customer">
                           <div class="clientinfo">
                              <h3 class="title-sub"><?php echo $this->lang->line('Client Details'); ?> </h3>
                              <?php  echo '<input type="hidden" name="customer_id" id="customer_id" value="'.$customerid.'">';
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
                                 <span>Email :  <strong><?php echo $email."\n<br>"; ?></strong></span>
                                 <span>Phone :  <strong><?php echo $phone."\n<br>"; ?></strong></span>
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
                  <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12 cmp-pnl">
                     <div class="inner-cmp-pnl">
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <h3 class="title-sub"><?php echo $this->lang->line('Invoice Properties') ?></h3>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Invoice Number') ?></label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-file-text-o"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control" placeholder="Invoice #"
                                    name="invocieno"
                                    value="<?php echo $lastinvoice + 1 ?>">
                              </div>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieno" class="col-form-label"><?php echo $this->lang->line('Reference') ?></label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-bookmark-o"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control" placeholder="Reference #"
                                    name="refer">
                              </div>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invociedate"
                                 class="col-form-label"><?php echo $this->lang->line('Invoice Date'); ?></label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-calendar4"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control required"
                                    placeholder="Billing Date" name="invoicedate"
                                    data-toggle="datepicker"
                                    autocomplete="false">
                              </div>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="invocieduedate"
                                 class="col-form-label"><?php echo $this->lang->line('Invoice Due Date') ?></label>
                              <div class="input-group">
                                 <div class="input-group-addon"><span class="icon-calendar-o"
                                    aria-hidden="true"></span></div>
                                 <input type="text" class="form-control required"
                                    name="invocieduedate"
                                    placeholder="Due Date" autocomplete="false"    data-toggle="datepicker">
                              </div>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="taxformat"
                                 class="col-form-label"><?php echo $this->lang->line('Tax') ?></label>
                              <select class="form-control"
                                 onchange="changeTaxFormat(this.value)"
                                 id="taxformat">
                              <?php echo $taxlist; ?>
                              </select>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                 <label for="discountFormat"
                                    class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                 <select class="form-control"
                                    onchange="changeDiscountFormat(this.value)"
                                    id="discountFormat">
                                 <?php echo $this->common->disclist() ?>
                                 </select>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="s_warehouses" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                              <select id="s_warehouses"  name="s_warehouses" class="form-control">
                              <?php echo $this->common->default_warehouse();
                                 echo '<option value="0">' . $this->lang->line('All') ?></option><?php foreach ($warehouse as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <?php if (isset($employee)){ ?>
                              <label for="Employee" class="col-form-label"><?php echo $this->lang->line('Employee'); ?></label>
                              <select name="employee" class="form-control"> 
                              <?php foreach ($employee as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['name'] . ' (' . $row['name'] . ')</option>';
                                 } ?>
                              </select>
                              <?php } ?>
                           </div>
                           <?php if ($exchange['active'] == 1){ ?>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="Employee" class="col-form-label"><?php echo $this->lang->line('Payment Currency client').' <small>'.$this->lang->line('based on live market').'</small>'; ?></label>
                              <select name="mcurrency" class="selectpicker form-control">
                                 <option value="0">Default</option>
                                 <?php foreach ($currency as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                    } ?>
                              </select>
                           </div>
                           <?php } ?>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="Employee" class="col-form-label"><?php echo $this->lang->line('Payment Terms'); ?></label>
                              <select name="pterms" class="selectpicker form-control">
                              <?php foreach ($terms as $row) {
                                 echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                 } ?>
                              </select>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                              <label for="toAddInfo"  class="col-form-label"><?php echo $this->lang->line('Invoice Note') ?></label>
                              <textarea class="form-control" name="notes" rows="2"></textarea>
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
                           <th width="10%" class="text-center"><?php echo $this->lang->line('Tax(%)') ?></th>
                           <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                           <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                           <th width="10%" class="text-center">
                              <?php echo $this->lang->line('Amount') ?>
                              (<?= currency($this->aauth->get_user()->loc); ?>)
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
                              autocomplete="off" value="1"><input type="hidden" id="alert-0" value=""
                              name="alert[]"></td>
                           <td><input type="text" class="form-control req prc" name="product_price[]" id="price-0"
                              onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                              autocomplete="off"></td>
                           <td><input type="text" class="form-control vat " name="product_tax[]" id="vat-0"
                              onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                              autocomplete="off"></td>
                           <td class="text-center" id="texttaxa-0">0</td>
                           <td><input type="text" class="form-control discount" name="product_discount[]"
                              onkeypress="return isNumber(event)" id="discount-0"
                              onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td>
                           <td><span class="currenty"><?= currency($this->aauth->get_user()->loc); ?></span>
                              <strong><span class='ttlText' id="result-0">0</span></strong>
                           </td>
                           <td class="text-center">
                           </td>
                           <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                           <input type="hidden" name="disca[]" id="disca-0" value="0">
                           <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                           <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                           <input type="hidden" name="unit[]" id="unit-0" value="">
                           <input type="hidden" name="hsn[]" id="hsn-0" value="">
                           <input type="hidden" name="serial[]" id="serial-0" value="">
                        </tr>
                        <tr class="startRow">
                           <td colspan="8"><textarea id="dpid-0" class="form-control" name="product_description[]"
                              placeholder="<?php echo $this->lang->line('Enter Product description'); ?> (Optional)"
                              autocomplete="off"></textarea></td>
                        </tr>
                        <tr class="last-item-row sub_c tr-border">
                           <td class="add-row no-border">
                              <button type="button" class="btn btn-secondary" aria-label="Left Align"
                                 id="addproduct">
                              <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                              </button>
                           </td>
                           <td colspan="7" class="no-border"></td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="6" class="reverse_align no-border"><input type="hidden" value="0" id="subttlform"
                              name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><span
                              class="currenty lightMode"><?= $this->config->item('currency'); ?></span>
                              <span id="taxr" class="lightMode">0</span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="6" class="reverse_align no-border">
                              <strong><?php echo $this->lang->line('Total Discount') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><span
                              class="currenty lightMode"><?php echo $this->config->item('currency');
                              if (isset($_GET['project'])) {
                                  echo '<input type="hidden" value="' . intval($_GET['project']) . '" name="prjid">';
                              } ?></span>
                              <span id="discs" class="lightMode">0</span>
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="6" class="reverse_align no-border">
                              <strong><?php echo $this->lang->line('Shipping') ?></strong>
                           </td>
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
                           <td colspan="6" class="reverse_align no-border">
                              <strong> <?php echo $this->lang->line('Extra') . ' ' . $this->lang->line('Discount') ?></strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><input type="text"
                              class="form-control form-control discVal"
                              onkeypress="return isNumber(event)"
                              placeholder="Value"
                              name="disc_val" autocomplete="off" value="0"
                              onkeyup="billUpyog()">
                              <input type="hidden"
                                 name="after_disc" id="after_disc" value="0">
                              ( <?= $this->config->item('currency'); ?>
                              <span id="disc_final">0</span> )
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="2"></td>
                           <td colspan="4" class="reverse_align no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                              (<span
                                 class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                           </td>
                           <td align="left" colspan="2" class="no-border"><input type="text" name="total" class="form-control"
                              id="invoiceyoghtml" readonly="">
                           </td>
                        </tr>
                        <tr class="sub_c" style="display: table-row;">
                           <td colspan="2" class="no-border"></td>
                           <td class="reverse_align no-border" colspan="6"><input type="submit"
                              class="btn btn-lg btn-primary sub-btn btn-lg"
                              value="<?php echo $this->lang->line('Generate Invoice') ?> "
                              id="submit-data" data-loading-text="Creating...">
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
                     <label class="col-sm-8"
                        for="document_id"><?= $row['name'] ?></label>
                     <div class="col-sm-6">
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
               <input type="hidden" value="new_i" id="inv_page">
               <input type="hidden" value="invoices/actionconvertinvoice" id="action-url">
               <input type="hidden" value="search" id="billtype">
               <input type="hidden" value="0" name="counter" id="ganak">
               <input type="hidden" value="<?= currency($this->aauth->get_user()->loc); ?>" name="currency">
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
               <input type="hidden" value="0" id="custom_discount">
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
            <div class="modal-header bg-gradient-directional-purple white">
               <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('Add Customer') ?></h4>
               <button type="button" class="close" data-dismiss="modal">
               <span aria-hidden="true">&times;</span>
               <span class="sr-only"><?php echo $this->lang->line('Close') ?></span>
               </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
               <p id="statusMsg"></p>
               <input type="hidden" name="mcustomer_id" id="mcustomer_id" value="0">
               <div class="row">
                  <div class="col-sm-6">
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
                        <div class="col-sm-6">
                           <input type="text" placeholder="City"
                              class="form-control margin-bottom" name="city" id="mcustomer_city">
                        </div>
                        <div class="col-sm-6">
                           <input type="text" placeholder="Region" id="region"
                              class="form-control margin-bottom" name="region">
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-6">
                           <input type="text" placeholder="Country"
                              class="form-control margin-bottom" name="country" id="mcustomer_country">
                        </div>
                        <div class="col-sm-6">
                           <input type="text" placeholder="PostBox" id="postbox"
                              class="form-control margin-bottom" name="postbox">
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-6">
                           <input type="text" placeholder="Company"
                              class="form-control margin-bottom" name="company">
                        </div>
                        <div class="col-sm-6">
                           <input type="text" placeholder="TAX ID"
                              class="form-control margin-bottom" name="tax_id" id="mcustomer_city">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label  col-form-label-sm"
                           for="customergroup"><?php echo $this->lang->line('Group') ?></label>
                        <div class="col-sm-10">
                           <select name="customergroup" class="form-control form-control-sm">
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
                  <div class="col-sm-6">
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
                        <div class="col-sm-6">
                           <input type="text" placeholder="City"
                              class="form-control margin-bottom" name="shipping_city" id="mcustomer_city_s">
                        </div>
                        <div class="col-sm-6">
                           <input type="text" placeholder="Region" id="shipping_region"
                              class="form-control margin-bottom" name="shipping_region">
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-6">
                           <input type="text" placeholder="Country"
                              class="form-control margin-bottom" name="shipping_country" id="mcustomer_country_s">
                        </div>
                        <div class="col-sm-6">
                           <input type="text" placeholder="PostBox" id="shipping_postbox"
                              class="form-control margin-bottom" name="shipping_postbox">
                        </div>
                     </div>
                  </div>
               </div>
               <?php
                  if(is_array($custom_fields_c)){
                      foreach ($custom_fields_c as $row) {
                          if ($row['f_type'] == 'text') { ?>
               <div class="form-group row">
                  <label class="col-sm-2 col-form-label"
                     for="document_id"><?= $row['name'] ?></label>
                  <div class="col-sm-8">
                     <input type="text" placeholder="<?= $row['placeholder'] ?>"
                        class="form-control margin-bottom b_input"
                        name="custom[<?= $row['id'] ?>]">
                  </div>
               </div>
               <?php }
                  }
                  }
                  ?>
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