<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title"><?php echo $this->lang->line('New Credit Note'); ?></h4>
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
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 cmp-pnl">
                            <div id="customerpanel" class="inner-cmp-pnl">
                                <div class="form-group row">
                                    <div class="fcol-sm-12">
                                        <h3 class="title-sub">
                                            <?php echo $this->lang->line('Customer Details') ?>
                                    </div>
                                    <div class="frmSearch col-sm-12"><label for="cst"
                                                                            class="col-form-label"><?php echo $this->lang->line('Search Customer') ?> </label>
                                        <input type="text" class="form-control" name="cst" id="customer-box"
                                               placeholder="Enter Customer Name or Mobile Number to search"
                                               autocomplete="off"/>

                                        <div id="customer-box-result"></div>
                                    </div>

                                </div>
                                <div id="customer">
                                    <div class="clientinfo">
                                        <?php echo $this->lang->line('Customer Details') ?>
                                        <hr>
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


                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12  cmp-pnl">
                            <div class="inner-cmp-pnl">


                                <div class="form-group row">

                                    <div class="col-sm-12">
                                        <h3 class="title-sub"><?php echo $this->lang->line('Credit Note') ?> </h3>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Order') ?> </label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="icon-file-text-o" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Invoice #" name="invocieno" value="<?php echo $lastinvoice + 1 ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invocieno"  class="col-form-label"><?php echo $this->lang->line('Reference') ?> </label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-bookmark-o" aria-hidden="true"></span></div>
                                            <input type="text" class="form-control" placeholder="Reference #"  name="refer">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invociedate" class="col-form-label"><?php echo $this->lang->line('Order Date') ?> </label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <span class="icon-calendar4"  aria-hidden="true"></span>
                                            </div>
                                                <input type="text" class="form-control required" placeholder="Billing Date" name="invoicedate" data-toggle="datepicker" autocomplete="false">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="invocieduedate" class="col-form-label"><?php echo $this->lang->line('Order Due Date') ?> </label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="icon-calendar-o"
                                                                                 aria-hidden="true"></span></div>
                                            <input type="text" class="form-control required" id="tsn_due"
                                                   name="invocieduedate"
                                                   placeholder="Due Date" data-toggle="datepicker" autocomplete="false">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="taxformat"
                                               class="col-form-label"><?php echo $this->lang->line('Tax') ?> </label>
                                        <select class="form-control"
                                                onchange="changeTaxFormat(this.value)"
                                                id="taxformat">
                                            <?php echo $taxlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                            <label for="discountFormat"
                                                   class="col-form-label"><?php echo $this->lang->line('Discount') ?></label>
                                            <select class="form-control" onchange="changeDiscountFormat(this.value)"
                                                    id="discountFormat">
                                                <?php echo $this->common->disclist() ?>
                                            </select>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Warehouse') ?></label>
                                        <select id="s_warehouses" class="selectpicker form-control">
                                            <option value="0"><?php echo $this->lang->line('All') ?></option>
                                            <?php foreach ($warehouse as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="discountFormat" class="col-form-label"><?php echo $this->lang->line('Payment Terms') ?></label>
                                        <select name="pterms" class="selectpicker form-control"><?php foreach ($terms as $row) {
                                                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                            } ?>

                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
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
                                    <div class="col-xl-4 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                        <label for="toAddInfo"
                                               class="col-form-label"><?php echo $this->lang->line('Order Note') ?> </label>
                                        <textarea class="form-control" name="notes" rows="2"></textarea></div>
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


                    <div id="saman-row">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>

                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="30%" class="text-center"><?php echo $this->lang->line('Item Name') ?></th>
                                <th width="8%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Rate') ?></th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?>(%)</th>
                                <th width="10%" class="text-center"><?php echo $this->lang->line('Tax') ?></th>
                                <th width="7%" class="text-center"><?php echo $this->lang->line('Discount') ?></th>
                                <th width="10%" class="text-center">
                                    <?php echo $this->lang->line('Amount') ?>
                                    (<?php echo $this->config->item('currency'); ?>)
                                </th>
                                <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" class="form-control text-center" name="product_name[]"
                                           placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                           id='productname-0'>
                                </td>
                                <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off" value="1"></td>
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
                                <td><span class="currenty"><?php echo $this->config->item('currency'); ?></span>
                                    <strong><span class='ttlText' id="result-0">0</span></strong></td>
                                <td class="text-center">

                                </td>
                                <input type="hidden" name="taxa[]" id="taxa-0" value="0">
                                <input type="hidden" name="disca[]" id="disca-0" value="0">
                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                <input type="hidden" name="unit[]" id="unit-0" value="">
                                <input type="hidden" name="hsn[]" id="hsn-0" value="">
                            </tr>
                            <tr>
                                <td colspan="8"><textarea id="dpid-0" class="form-control" name="product_description[]"
                                                          placeholder="<?php echo $this->lang->line('Enter Product description'); ?>"
                                                          autocomplete="off"></textarea></td>
                            </tr>

                            <tr class="last-item-row tr-border">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-crud btn-secondary" id="addproduct">
                                        <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="6" align="right" class="no-border"><input type="hidden" value="0" id="subttlform"
                                                                     name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                </td>
                                <td align="left" colspan="2" class="no-border"><span
                                            class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                    <span id="taxr" class="lightMode">0</span></td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="6" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Discount') ?></strong></td>
                                <td align="left" colspan="2" class="no-border"><span
                                            class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                    <span id="discs" class="lightMode">0</span></td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="6" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                    onkeypress="return isNumber(event)"
                                                                    placeholder="Value"
                                                                    name="shipping" autocomplete="off"
                                                                    onkeyup="billUpyog();">
                                    ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> )
                                </td>
                            </tr>

                            <tr class="sub_c" style="display: table-row;">
                                <td colspan="2" class="no-border"><?php if ($exchange['active'] == 1){
                                    echo $this->lang->line('Payment Currency client') . ' <small>' . $this->lang->line('based on live market') ?></small>
                                    <select name="mcurrency"
                                            class="selectpicker form-control">
                                        <option value="0">Default</option>
                                        <?php foreach ($currency as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                        } ?>

                                    </select><?php } ?></td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="left" colspan="2" class="no-border"><input type="text" name="total" class="form-control"
                                                                    id="invoiceyoghtml" readonly="">

                                </td>
                            </tr>
                            <tr class="sub_c" style="display: table-row;">
                                
                                <td align="right" colspan="8" class="no-border"><input type="submit" class="btn btn-lg btn-primary sub-btn"
                                                                     value="<?php echo $this->lang->line('Generate Order') ?>"
                                                                     id="submit-data" data-loading-text="Creating...">

                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" value="stockreturn/action" id="action-url">
                    <input type="hidden" value="2" name="person_type">
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


