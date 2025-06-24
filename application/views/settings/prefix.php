<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Prefix') ?></h5>
            <hr>
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
                <form method="post" id="prefix_form" class="form-horizontal">


                    <input type="hidden" name="id" value="<?php echo $company['id'] ?>">
                    <div class="form-group row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="invoiceprefix"><?php echo $this->lang->line('Invoice Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="invoiceprefix" class="form-control margin-bottom  required" name="invoiceprefix"  value="<?php echo $company['prefix'] ?>" maxlength="15">
                        </div>
             
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="invoiceprefix"><?php echo $this->lang->line('Invoice Receipt Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="invoice_receipt_prefix" class="form-control margin-bottom  required" name="invoice_receipt_prefix"  value="<?php echo $prefix['receipts']['key1'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoicereturnprefix"><?php echo $this->lang->line('Credit Note Prefix'); ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Invoice Return Prefix"
                                   class="form-control margin-bottom  required" name="invoicereturnprefix"
                                   value="<?php echo $prefix['returns']['url'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="invoiceprefix"><?php echo $this->lang->line('Invoice Return Receipt Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="invoice_return_receipt_prefix" class="form-control margin-bottom  required" name="invoice_return_receipt_prefix"  value="<?php echo $prefix['receipts']['key2'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"  for="invoiceprefix">POS <?php echo $this->lang->line('Invoice Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="pos_prefix"
                                   class="form-control margin-bottom  required" name="pos_prefix"
                                   value="<?php echo $prefix['pos'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="leadprefix"><?php echo $this->lang->line('Lead') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="leadprefix"
                                   value="<?php echo $prefix['returns']['method'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Quote') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="q_prefix"
                                   value="<?php echo $prefix['name'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="salesorderprefix"><?php echo $this->lang->line('Sales Order') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="salesorderprefix"
                                   value="<?php echo $prefix['returns']['other'] ?>" maxlength="15">
                            <!--<small>-</small>-->
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Delivery Note') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="dnote_prefix"
                                   value="<?php echo $prefix['returns']['name'] ?>" maxlength="15">
                            <!--<small>-</small>-->
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Delivery Note Return') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="dnote_return_prefix"
                                   value="<?php echo $prefix['returns']['key1'] ?>" maxlength="15">
                            <!--<small>-</small>-->
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Purchase Order') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="p_prefix"
                                   value="<?php echo $prefix['key1'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Purchase Receipt') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                                <input type="text" class="form-control margin-bottom  required" name="recieptprefix" value="<?php echo $prefix['suffix'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Purchase Payment Receipt Prefix')  ?><span class="compulsoryfld">*</span></label>
                                <input type="text" class="form-control margin-bottom  required" name="purchase_payment_prefix" value="<?php echo $prefix['receipts']['url'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Subscription') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="r_prefix"
                                   value="<?php echo $prefix['key2'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Stock Return') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="s_prefix"
                                   value="<?php echo $prefix['url'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Transactions') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="t_prefix"
                                   value="<?php echo $prefix['method'] ?>" maxlength="15">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Others') . ' ' . $this->lang->line('Prefix') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="o_prefix"
                                   value="<?php echo $prefix['other'] ?>" maxlength="15">
                            <!--<small>-</small>-->
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Landing Suffix(SO,DN,INV)') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="t_suffix"
                                   value="<?php echo $prefix['returns']['suffix'] ?>" maxlength="15">
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="invoiceprefix"><?php echo $this->lang->line('Transactions') ?><span class="compulsoryfld">*</span></label>
                            <input type="text"
                                   class="form-control margin-bottom  required" name="t_prefix"
                                   value="<?php echo $prefix['method'] ?>" maxlength="15">
                        </div>

                        <div class="col-12 mt-1">
                        <div class="submit-section text-right">
                            <input type="submit" id="billing_update" class="btn btn-crud btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    // erp2024 newly added 14-06-2024 for detailed history log ends 
    $("#prefix_form").validate($.extend(true, {}, globalValidationExpandLevel,{
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            invoiceprefix: { required: true },
            // bill_number: { required: true },
            // srv: { required: true },
            // bill_amount: { required: true },
            // srvdate: { required: true },
            // bill_date: { required: true },
            
        },
        messages: {
            invoiceprefix: "Invoice Prefix",
            // bill_number: "Enter Bill No.",
            // srv: "Purchase Receipt Voucher No. required",
            // bill_amount: "Enter Bill Amount equal to the Purchase Order Amount",
            // srvdate: "Enter Purchase Receipt Date",
            // bill_date: "Enter Bill Date",
        }
    }));    

});
    // $("#billing_update").click(function (e) {
    //     e.preventDefault();
    //     var actionurl = baseurl + 'settings/prefix';
    //     actionProduct(actionurl);
    // });

    $("#billing_update").on("click", function(e) {

        e.preventDefault();
        $('#billing_update').prop('disabled', true);
        if ($("#prefix_form").valid()) {
            
            Swal.fire({
                    title: "Are you sure?",
                    "text":"Do you want to Update Prefix?",
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
                    var formData = $("#prefix_form").serialize(); 
                    $.ajax({
                        type: 'POST',
                        url: baseurl +'settings/prefix',
                        data: formData,
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                                // Handle error
                                console.error(xhr.responseText);
                        }
                    });
                    }
                    else{
                    $('#billing_update').prop('disabled', false);
                    }
            });
        }
        else{
                $('.page-header-data-section').css('display','block');
                $('#billing_update').prop('disabled', false);
            }
    });
</script>

