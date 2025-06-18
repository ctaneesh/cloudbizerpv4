<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" name="data_form" id="data_form" class="form-horizontal">
            <div class="card-body">

                <h5><?php echo $this->lang->line('Default Validity & Terms') ?></h5>
                <hr>


                <div class="form-group row">
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="lead_validity"><?php echo $this->lang->line('Lead Validity in Days') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom" name="lead_validity" id="lead_validity"
                            value="<?php echo $validity['lead_validity'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="quote_validity"><?php echo $this->lang->line('Quote Validity in Days') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom" name="quote_validity" id="quote_validity"
                            value="<?php echo $validity['quote_validity'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="salesorder_validity"><?php echo $this->lang->line('Sales Order Validity in Days') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom" name="salesorder_validity" id="salesorder_validity"
                            value="<?php echo $validity['salesorder_validity'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="deliverynote_validity"><?php echo $this->lang->line('Delivery Note Validity in Days') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom" name="deliverynote_validity" id="deliverynote_validity"
                            value="<?php echo $validity['deliverynote_validity'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="invoice_validity"><?php echo $this->lang->line('Invoice Validity in Days') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom" name="invoice_validity" id="invoice_validity"
                            value="<?php echo $validity['invoice_validity'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="purchase_order_validity"><?php echo $this->lang->line('Purchase Order Validity in Days') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" class="form-control margin-bottom" name="purchase_order_validity" id="purchase_order_validity"
                            value="<?php echo $validity['purchase_order_validity'] ?>">
                    </div>
                   
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="thous_sep"><?php echo $this->lang->line('Payment Terms') ?><span class="compulsoryfld">*</span></label>
                        <select name="payment_terms" id="payment_terms" class="form-control">
                            <?php
                                if($paymentterms)
                                {
                                    echo '<option value="">Select Payment Term</option>';
                                    foreach($paymentterms as $paymentterm)
                                    {
                                        $sel ="";
                                        if($validity['payment_terms']==$paymentterm['id'])
                                        {
                                            $sel="selected";
                                        }
                                        echo '<option value="'.$paymentterm['id'].'" '.$sel.'>'.$paymentterm['title'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                   <div class="col-12 mt-3">
                      <h5><?php echo $this->lang->line('Set the due date in days and pick a color for display') ?></h5>
                      <hr>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label for="due_date_1" class="col-form-label"> <?php echo "First Section ".$this->lang->line('Due Date (in days)'); ?><span class="compulsoryfld">*</span></label>
                        <div class="d-flex align-items-center gap-2">                            
                            <input type="number" class="form-control margin-bottom" name="due_date_1" id="due_date_1" value="<?php echo $validity['due_date_1'] ?>">&nbsp;     
                            <input type="color" id="colorPicker" name="due_date_color_1" class="form-control2 form-control-color" value="<?php echo $validity['due_date_color_1'] ?>">                       
                            
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label for="due_date_2" class="col-form-label"> <?php echo "Second Section ".$this->lang->line('Due Date (in days)'); ?><span class="compulsoryfld">*</span></label>
                        <div class="d-flex align-items-center gap-2">                            
                            <input type="number" class="form-control margin-bottom" name="due_date_2" id="due_date_2" value="<?php echo $validity['due_date_2'] ?>">&nbsp;     
                            <input type="color" id="colorPicker" name="due_date_color_2" class="form-control2 form-control-color" value="<?php echo $validity['due_date_color_2'] ?>">                       
                            
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label for="due_date_3" class="col-form-label"> <?php echo "Third Section ".$this->lang->line('Due Date (in days)'); ?><span class="compulsoryfld">*</span></label>
                        <div class="d-flex align-items-center gap-2">                            
                            <input type="number" class="form-control margin-bottom" name="due_date_3" id="due_date_3" value="<?php echo $validity['due_date_3'] ?>">&nbsp;     
                            <input type="color" id="colorPicker" name="due_date_color_3" class="form-control2 form-control-color" value="<?php echo $validity['due_date_color_3'] ?>">                       
                            
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label for="due_date_expired_color" class="col-form-label"> <?php echo "Expired Secion"; ?><span class="compulsoryfld">*</span></label>
                        <div class="d-flex align-items-center gap-2">     
                            <input type="color" id="colorPicker" name="due_date_expired_color" class="form-control2 form-control-color" value="<?php echo $validity['due_date_expired_color'] ?>">                       
                            
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label for="due_date_default_color" class="col-form-label"> <?php echo "Default Color Code"; ?><span class="compulsoryfld">*</span></label>
                        <div class="d-flex align-items-center gap-2">     
                            <input type="color" id="colorPicker" name="due_date_default_color" class="form-control2 form-control-color" value="<?php echo $validity['due_date_default_color'] ?>">                       
                            
                        </div>
                    </div>
                    
                
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section mt-14 text-right">
                        <input type="submit" id="modify_btn" class="btn btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Modify') ?>" data-loading-text="Updating...">
                    </div>
                </div>

            </div>
        </form>
    </div>
</article>

<script type="text/javascript">
    $(document).ready(function () {
        

        $("#data_form").validate({
            ignore: [],
            rules: {               
                lead_validity: { required: true },
                quote_validity: { required: true },
                salesorder_validity: { required: true },
                deliverynote_validity: { required: true },
                payment_terms: { required: true },
                due_date_1: { required: true },
                due_date_2: { required: true },
                due_date_3: { required: true },
            },
            messages: {
                lead_validity: "Enter Lead Validitiy in Days",
                quote_validity: "Enter Quote Validitiy in Days",
                salesorder_validity: "Enter Sales Order Validitiy in Days",
                deliverynote_validity: "Enter Delivery Note Validitiy in Days",
                payment_terms: "Select Payment Terms",
                due_date_1: "Enter No. of Days",
                due_date_2: "Enter No. of Days",
                due_date_3: "Enter No. of Days",
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


    });

    $('#modify_btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#modify_btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to modify data?",
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
                        url: baseurl + 'settings/validity_modify_action', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            location.reload();
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#modify_btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#modify_btn').prop('disabled', false);
        }
    });
</script>