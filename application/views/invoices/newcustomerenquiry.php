<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
        <?php $lead_number = $prefix.($lastenquirynumber+1); ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('invoices/leads') ?>"><?php echo $this->lang->line('Leads') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Lead(New)') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Lead(New)'). "# ".$lead_number;  ?></h4>
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
                
                <form method="post" id="data_form" name="data_form" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="config_tax" id="config_tax" value="<?=$configurations['config_tax']?>">
                
                    <div class="row" >
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-sm-7"><label class="col-form-label"><?php echo $this->lang->line('Lead Number'); ?></label>
                                
                                <input type="text" class="form-control" name="lead_number" id="lead_number" placeholder="Lead Number" autocomplete="off" value="<?php echo $lead_number; ?>" readonly/>
                            </div> 
                            <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-12 row"><label class="col-form-label"><?php echo $this->lang->line('Customer Type'); ?></label>
                                    </div>
                                        
                                    <div class="form-check col-6">
                                        <input class="form-check-input" type="radio" name="customerType" id="customerType2" value="existing" checked>
                                        <label class="form-check-label" for="customerType2">
                                            Existing
                                        </label>
                                    </div>
                                    <div class="form-check col-6">
                                        <input class="form-check-input" type="radio" name="customerType" id="customerType1" value="new">
                                        <label class="form-check-label" for="customerType1">
                                            New
                                        </label>
                                    </div>
                                    <!-- <div class="form-check col-4">
                                        <input class="form-check-input" type="radio" name="customerType" id="customerType2" value="guest">
                                        <label class="form-check-label" for="customerType2">
                                            Guest
                                        </label>
                                    </div> -->
                                </div>
                            </div> 
                        </div>                                   
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmSearch frmclasss"><label for="customer_name" class="col-form-label" id="customerLabel"><?php echo $this->lang->line('Search Customer'); ?><span class="compulsoryfld">*</span></label>
                                <!-- <input type="text" class="form-control" name="cst" id="" -->
                                <input type="text" class="form-control customer_name" name="customer_name" id="customer-search" placeholder="<?php echo $this->lang->line('Name or Mobile Number to search'); ?>" autocomplete="off"/>
                                <div id="customer-search-result" class="customer-search-result"></div>
                        </div>                                    
                    </div>
                    <input type="hidden" class="form-control" name="customer_id" id="customer_id" autocomplete="off"/>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmclasss"><label for="customer_phone" class="col-form-label"><?php echo $this->lang->line('Phone'); ?></label>
                                <input type="number" class="form-control" name="customer_phone" id="customer_phone" placeholder="Contact Number" autocomplete="off"/>
                        </div>                                    
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmclasss"><label for="date_received" class="col-form-label"><?php echo $this->lang->line('Date Received'); ?></label>
                                <input type="date" class="form-control" name="date_received" id="date_received" placeholder="Date Received" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>"/>
                        </div>                                    
                    </div>
                    
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmclasss"><label for="due_date" class="col-form-label"><?php echo $this->lang->line('Customer Enquiry Deadline');  ?> <span class="compulsoryfld">*</span></label>
                                <input type="date" class="form-control" name="due_date" id="due_date" placeholder="Due Date" autocomplete="off"  min="<?php echo date('Y-m-d'); ?>" />
                        </div>                                    
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmclasss"><label for="customer_email" class="col-form-label"><?php echo $this->lang->line('Email'); ?></label>
                                <input type="email" class="form-control" name="customer_email" id="customer_email"placeholder="Contact Email" autocomplete="off"/>
                        </div>                                    
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmclasss"><label for="customer_address"  class="col-form-label"><?php echo $this->lang->line('Address'); ?></label>
                                <input type="text" class="form-control" name="customer_address"  id="customer_address" placeholder="Contact Address" autocomplete="off"/>
                        </div>                                    
                    </div>                    
                    
                    
                    
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <div class="frmclasss"><label for="source_of_enquiry" class="col-form-label"><?php echo $this->lang->line('Source of Enquiry'); ?><span class="compulsoryfld">*</span></label>
                               <select class="form-control form-select" id="source_of_enquiry" name="source_of_enquiry">
                                    <option value="">Select Source</option>
                                    <option value="Email">Email</option>
                                    <option value="Direct">Direct</option>
                               </select>
                        </div>                                    
                    </div>
                    <!--erp2024 newly added 28-09-2024  -->
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?><span class="compulsoryfld">*</span></label>
                        <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Customer Reference Number">
                        </div>                                    
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                        <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person">
                        </div>                                    
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Contact Person Number'); ?></label>
                        <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Contact Person Number">
                        </div>                                    
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                        <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email">
                        </div>                                    
                    </div>
                    <!--erp2024 newly added 28-09-2024 ends -->
                    <div class="col-12"></div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12 d-none">
                        <div class="frmclasss">
                            <label for="enquiry_status" class="col-form-label"><?php echo $this->lang->line('Lead Status'); ?></label>
                            <!-- <select class="form-control form-select" id="enquiry_status" name="enquiry_status">
                                <option value="Open">Open</option>
                                <option value="Assigned">Assigned</option>
                            </select> -->
                           <div class="col-12 row">
                                <div class="form-check col-4">
                                    <input class="form-check-input" type="radio" name="enquiry_status" id="enquiry_status1" value="Open" checked>
                                    <label class="form-check-label" for="enquiry_status1">
                                    Open
                                    </label>
                                </div>
                                <div class="form-check col-3">
                                    <input class="form-check-input" type="radio" name="enquiry_status" id="enquiry_status2" value="Assigned">
                                    <label class="form-check-label" for="enquiry_status2">
                                    Assigned
                                    </label>
                                </div>
                           </div>
                        </div>                                    
                    </div>
                    

                   
                   

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 assignedsection d-none1">
                        <div class="frmclasss"><label for="assignedto" class="col-form-label"><?php echo $this->lang->line('Assigned To'); ?></label>
                        <?php $disable = ($this->aauth->get_user()->roleid !=5) ? "disable-class" : '';  ?>
                        <select class="form-control form-select" id="assignedto" name="assignedto">
                                <option value="">* Not Assigned </option>
                                <?php foreach ($employee as $row) {
                                        // if($this->session->userdata('id')== $row['id'])
                                        // {
                                        //     continue;
                                        // }
                                            echo '<option value="' . $row['id'] . '">' . $row['name'] .'</option>';
                                    } ?>
                            </select>
                        </div>                                    
                    </div>
                    <div class="col-12"></div>
                    <!-- <div class="col-md-6 mb-1">
                            <div class="col-sm-12"><label for="comments" class="col-form-label"><?php echo 'Comments'; ?></label> -->
                               <!-- <textarea class="form-control"  placeholder="Comments" name="comments" id="comments"></textarea> -->
                        <!-- </div>                                    
                    </div> -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                            <div class="frmclasss">
                                <div class="row">
                                    <div class="col-lg-4 colmd-4 col-sm-12">
                                        <label for="email_contents" class="col-form-label"><?php echo $this->lang->line('Email Contents'); ?></label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 text-right">
                                        <label class="col-form-label"><a href="<?php echo base_url() ?>Invoices/convert_to_deals" class="btn btn-secondary btn-sm btn-crud"   type="button"><i class="fa fa-share"></i> <?php echo $this->lang->line("Convert to Deals"); ?></a></label>
                                    </div>
                                </div>
                                
                               <textarea rows="6" class="summernote1 form-textarea"  placeholder="Email Contents" id="email_contents" name="email_contents"></textarea>
                        </div>                                    
                    </div>
                    <div class="col-xl-6 col-lg-6 col-sm-12">
                            <label for="note" class="col-form-label"><?php echo $this->lang->line('Note'); ?></label>
                            <textarea rows="6" class="summernote1 form-textarea"  placeholder="Note" id="comments" name="note"></textarea>
                        </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1">
                        <label for="upfile-0" class="col-form-label"><?php echo $this->lang->line('Uploads'); ?></label>
                        <div class="row">                            
                            <!-- <div class="col-6">
                                <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png">
                                <div id="uploadsection"></div>
                            </div>            -->
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

                                                     
                    </div>


                    <!-- ==================================================================== -->
                     <div id="saman-row">
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>


                            <tr class="item_header bg-gradient-directional-blue white">
                                <th width="10%" class="text-center1 pl-1"><?php echo $this->lang->line('Item No') ?></th>
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
                            <tr>
                                <td><input type="text" class="form-control" name="code[]" id='code-0' placeholder="<?php echo $this->lang->line('Search by Item No') ?>"></td>
                                <td><input type="text" class="form-control" name="product_name[]"
                                           placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                           id='productname-0'>
                                </td>
                               
                                <td><input type="number" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                           onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                           autocomplete="off" value="1" maxlength="6"></td>
                                <td class="text-center"><strong id="onhandQty-0"></strong></td>
                                <td class="text-right">
                                    <strong id="pricelabel-0"></strong>
                                    <input type="hidden" class="form-control req prc" name="product_price[]" id="price-0" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off">
                                </td>
                                <td class="text-right">
                                    <input type="hidden" class="form-control" name="lowest_price[]" id="lowestprice-0" onkeypress="return isNumber(event)" autocomplete="off">
                                    <strong id="lowestpricelabel-0"></strong>
                                </td>
                                <?php //Verify that tax is enabled
                                if($configurations['config_tax']!='0'){ ?>           
                                        <td class="text-center">
                                            <div class="text-center">                                                
                                                <input type="hidden" class="form-control" name="product_tax[]" id="vat-0"
                                                    onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                                    autocomplete="off">
                                                    <strong id="taxlabel-0"></strong>&nbsp;<strong  id="texttaxa-0"></strong>
                                            </div>
                                        </td>
                                <?php } ?>

                                <td class="text-center"><strong id='maxdiscountratelabel-0'></strong></td>
                                <td class="text-center">
                                    <div class="input-group text-center">
                                        <select name="discount_type[]" id="discounttype-0" class="form-control" onchange="discounttypeChange(0)">
                                            <option value="Perctype">%</option>
                                            <option value="Amttype">Amt</option>
                                        </select>&nbsp;
                                        <input type="number"  min="0" class="form-control discount" name="product_discount[]" onkeypress="return isNumber(event)" id="discount-0"  autocomplete="off" onkeyup="discounttypeChange(0)">
                                        <input type="number" min="0" class="form-control discount d-none" name="product_amt[]" onkeypress="return isNumber(event)" id="discountamt-0" autocomplete="off" onkeyup="discounttypeChange(0)">
                                    </div>                                    
                                    <input type="hidden" name="disca[]" id="disca-0" value="0">
                                    <strong id="discount-amtlabel-0" class="discount-amtlabel"></strong>
                                    <div><strong id="discount-error-0"></strong></div>                                    
                                </td>

                                <td class="text-right">
                                    <strong><span class='ttlText' id="result-0">0</span></strong></td>

                                <td class="text-center">
                                <button onclick='producthistory("0")' type="button" class="btn btn-crud btn-sm btn-secondary producthis"><i class="fa fa-history"></i></button>&nbsp;
                                <button onclick='single_product_details("0")' type="button" class="btn btn-crud btn-sm btn-secondary"><i class="fa fa-info"></i></button>&nbsp;<button type="button" data-rowid="' . $i . '" class="btn btn-crud btn-sm btn-secondary removeProd" title="Remove"> <i class="fa fa-trash"></i> </button>
                                </td>
                                <input type="hidden" name="taxa[]" id="taxa-0" value="0">                                
                                <input type="hidden" class="ttInput" name="product_subtotal[]" id="total-0" value="0">
                                <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">
                                <input type="hidden" name="unit[]" id="unit-0" value="">
                                <input type="hidden" name="hsn[]" id="hsn-0" value="">
                                <input type="hidden" name="maxdiscountrate[]" id="maxdiscountrate-0">
                                <!-- <textarea id="dpid-0" class="form-control" name="product_description[]" placeholder="<?php echo $this->lang->line('Enter Product description'); ?>"  autocomplete="off"></textarea> -->
                            </tr>

                            <tr class="last-item-row sub_c tr-border">
                                <td class="add-row no-border">
                                    <button type="button" class="btn btn-crud btn-secondary" aria-label="Left Align"
                                            data-placement="top" id="lead_create_btn">
                                        <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Add Row') ?>
                                    </button>
                                </td>
                                <td colspan="7" class="no-border"></td>
                            </tr>
                            <?php 
                            if($configurations['config_tax']!='0'){ ?>
                                <tr class="sub_c noproduct-section d-none">
                                    <td colspan="7" align="right" class="no-border">
                                        <input type="hidden" value="0" id="subttlform"                                                                     name="subtotal"><strong><?php echo $this->lang->line('Total Tax') ?></strong>
                                    </td>
                                    <td align="right" colspan="2" class="no-border"><span
                                                class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>
                                        <span id="taxr" class="lightMode">0</span></td>
                                </tr>
                            <?php } ?>
                            <tr class="sub_c noproduct-section d-none1" >
                                <td colspan="9" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Total Discount').'('.$this->config->item('currency').')' ?></strong></td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="discs" class="lightMode">0.00</span></td>
                            </tr>

                            <!-- <tr class="sub_c" >
                                <td colspan="6" align="right" class="no-border">
                                    <strong><?php echo $this->lang->line('Shipping') ?></strong></td>
                                <td align="left" colspan="2" class="no-border"><input type="text" class="form-control shipVal"
                                                                    onkeypress="return isNumber(event)"
                                                                    placeholder="Value"
                                                                    name="shipping" autocomplete="off"
                                                                    onkeyup="billUpyog()">
                                    ( <?php echo $this->lang->line('Tax') ?> <?= $this->config->item('currency'); ?>
                                    <span id="ship_final">0</span> )
                                </td>
                            </tr> -->

                            <tr class="sub_c noproduct-section d-none1" >
                                <td colspan="5" class="no-border">
                                    
                                </td>
                                <td colspan="4" align="right" class="no-border"><strong><?php echo $this->lang->line('Grand Total') ?>
                                        (<span  class="currenty lightMode"><?php echo $this->config->item('currency'); ?></span>)</strong>
                                </td>
                                <td align="right" colspan="2" class="no-border">
                                    <span id="grandtotaltext">0.00</span>
                                    <input type="hidden" name="total" class="form-control"  id="invoiceyoghtml" readonly="">

                                </td>
                            </tr>

                            </tbody>
                        </table>
                            <input type="hidden" value="search" id="billtype">
                            <input type="hidden" value="0" name="counter" id="ganak">
                            <input type="hidden" value="<?=$configurations['config_tax']?>" name="configured_tax" id="configured_tax">
                            <input type="hidden" value="search" id="billtype">
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
                    </div>
                    <!-- ==================================================================== -->
                    
                    <div class="row mb-2 border-top">
                        
                        <div class="col-12 ">
                            <div class="text-right mt-3">
                            <button class="btn btn-crud btn-crud btn-secondary btn-lg" type="submit" id="save_as_draft_btn"><?php echo $this->lang->line('Save As Draft'); ?></button>&nbsp;
                            <button class="btn btn-crud btn-crud btn-primary btn-lg" id="generatelead"><?php echo $this->lang->line('Completed'); ?></button>
                            </div>  
                        </div>  
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        

        $('.summernote').summernote({
            height: 100,
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
        function toggleAssignedSection() {
            // if ($('#enquiry_status').val() !== 'Assigned') {
            //     $('.assignedsection').addClass('d-none');
            //     $('#assignedto').removeAttr('required');
            // } else {
            //     $('.assignedsection').removeClass('d-none');
            //     $('#assignedto').attr('required', 'required');
            // }
        }

        $('#enquiry_status').change(toggleAssignedSection);

        // Call the function on page load in case the initial value of enquiry_status is "Assigned"
        toggleAssignedSection();
            $("#data_form").validate({
                ignore: [], // Important: Do not ignore hidden fields (used by summernote)
                rules: {
                    customer_name: { required: true },
                    customer_phone: { 
                        required: true, 
                        phoneRegex :true
                    },
                    source_of_enquiry: { required: true },
                    due_date: { required: true },
                    due_date: { required: true },
                    customer_reference_number : { required: true },
                    customer_email: {
                        required: true,
                        email: true
                    },
                    customer_contact_number: {
                        phoneRegex :true
                    }
                },
                messages: {
                    customer_name: "Enter Name",
                    customer_phone: "Enter Phone Number",
                    phoneRegex: "Enter Valid Number",
                    customer_email: "Enter Email",
                    source_of_enquiry: "Select a source",
                    due_date: "Enter a valid date",
                    customer_reference_number: "Enter Customer Reference",
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

            // Enable/disable Add More button based on file input value
            $('#upfile-0').on('change', function() {
                if ($(this).val()) {
                    $('#addmore_img').prop('disabled', false);
                } else {
                    $('#addmore_img').prop('disabled', true);
                }
            });

            // Handle form submission
            $('#generatelead').prop('disabled',false);


            $('#generatelead').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission
                $('#generatelead').prop('disabled',true);
                // Validate the form
                if ($("#data_form").valid()) {
                    var emailContents = $('#email_contents').val();
                    var fileInput = $('#upfile-0').val(); // Get the file input value
                   
                    // Check if any product_name[] input has a value
                    var productNameFilled = false;
                    var imageFilled = false;
                    $("input[name='product_name[]']").each(function() {
                        if ($(this).val()) {
                            productNameFilled = true;
                            return false;
                        }
                    });
                    $("input[name='upfile[]']").each(function() {
                        if ($(this).val()) {
                            imageFilled = true;
                            return false; // Exit loop early if we find a filled product name
                        }
                    });
                    if (productNameFilled) {
                        var form = $('#data_form')[0]; // Get the form element
                        var formData = new FormData(form); // Create FormData object
                        Swal.fire({
                                title: "Are you sure?",
                                // text: "Are you sure you want to update inventory? Do you want to proceed?",
                                "text":"Do you want to create a lead?",
                                icon: "question",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, proceed!',
                                cancelButtonText: "No - Cancel",
                                reverseButtons: true,
                                focusCancel: true,
                                allowOutsideClick: false,
                                showCancelButton: true, 
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: baseurl + 'Invoices/customerenquiryaction', // Replace with your server endpoint
                                        type: 'POST',
                                        data: formData,
                                        contentType: false, 
                                        processData: false,
                                        success: function(response) {
                                            window.location.href = baseurl + 'invoices/leads';
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                            console.log(error); // Log any errors
                                        }
                                    });
                                }
                                else if (result.dismiss === Swal.DismissReason.cancel) {
                                    $('#generatelead').prop('disabled', false);
                                }
                                
                            });
                    } else {
                        Swal.fire({
                            title: 'Input Required',
                            text: 'To generate a lead, please enter at least one value in Product.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        $('#generatelead').prop('disabled',false);
                    }
                }
                else{
                    $('#generatelead').prop('disabled',false);
                }
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
                $('#customer_id').val("");
                $('#customer_address').val("");
                if (this.value === 'new') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    $('.customer_name').attr('placeholder', '<?php echo $this->lang->line("customer_name"); ?>');                    
                }
                else if (this.value === 'guest') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    $('.customer_name').attr('placeholder', '<?php echo $this->lang->line("customer_name"); ?>');
                    
                } else {
                    customerLabel.textContent = "<?php echo $this->lang->line('Search Customer'); ?>";
                    $(".customer_name").attr("id","customer-search");
                    $(".customer-search-result").attr("id","customer-search-result");
                    $('.customer_name').attr('placeholder', '<?php echo $this->lang->line("Enter Customer Name or Mobile Number to search"); ?>');
                }
            });
        });
    });

    $("#customer-search").keyup(function () {
        $.ajax({
            type: "GET",
            url: baseurl + 'search_products/customersearch',
            data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
            beforeSend: function () {
                $("#customer-search").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
            },
            success: function (data) {
                console.log(data);
                $("#customer-search-result").show();
                $("#customer-search-result").html(data);
                $("#customer-search").css("background", "none");

            }
        });
    });

function selectedCustomer(cid, cname, cadd2, ph, email) {
    $('#customer-search').val(cname);
    $('#customer_phone').val(ph);
    $('#customer_email').val(email);
    // $('#customer_address').val(cadd1);
    $('#customer_id').val(cid);
    $("#customer-search-result").hide();
    $.ajax({
        type: 'POST',
        url: baseurl + 'customers/customer_details_byid',
        data: {'cid': cid},
        dataType: 'json',
        success: function(response) {
            if (response.status === 'Success') {
                $('#customer_address').val(response.data);
            } else {
                console.error('Failed to get customer details');
            }
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
}

$("#assignedto").on('change', function() {
    if ($("#assignedto").val() !== "") {
        // Set the "Assigned" radio button as checked
        $("#enquiry_status2").prop('checked', true);
    } else {
        // Set the "Open" radio button as checked
        $("#enquiry_status1").prop('checked', true);
    }
});



$('#save_as_draft_btn').on('click', function(e) {
    e.preventDefault(); 
    var form = $('#data_form')[0];
    var formData = new FormData(form); 
    $.ajax({
        url: baseurl + 'Invoices/customerenquiry_draft_action', // Replace with your server endpoint
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {
        if (typeof response === "string") {
            response = JSON.parse(response.trim());
        }
        var enqid = response.data;
        window.location.href = baseurl + 'invoices/customer_leads?id='+enqid;
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
            console.log(error); // Log any errors
        }
    });
});
</script>