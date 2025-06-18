<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('bankingtransactions') ?>"><?php echo $this->lang->line('Transactions') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line($type) ?></li>
                </ol>
            </nav>
            
        <h5 class="title"> <?php echo $this->lang->line($type) ?></h5>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
         </div>
      </div>
    <div class="card-body">
    
        <!-- ======================================================================== -->
        <div class="card card-block formborder">
        <form method="post" id="data_form" class="form-horizontal">

            <h5 id="headerlabel1"><?php echo $this->lang->line('General'); ?></h5>
            <p class="font-13"><?php echo $this->lang->line('General description'); ?></p>
            <hr>
            <div class="alert alert-danger d-none" role="alert" id="account-error">Account number already in use</div>
            <div class="form-group row">                
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="trans_number"><?php echo $this->lang->line('Transaction Number') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" placeholder="Enter Transaction Number" value="<?=$transaction_details['trans_number']?>" class="form-control margin-bottom required" name="trans_number" id="trans_number" readonly>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="trans_date"><?php echo $this->lang->line('Date');?><span class="compulsoryfld">*</span></label>
                    <input type="date" placeholder="Enter date" value="<?=date('Y-m-d',strtotime($transaction_details['trans_date']))?>" class="form-control margin-bottom required" name="trans_date" id="trans_date">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="holder"><?php echo $this->lang->line('Payment Method') ?><span class="compulsoryfld">*</span></label>
                    <select name="trans_payment_method" id="trans_payment_method" class="form-control" required>
                        <option value="">Select Payment Method</option>
                        <option value="Bank Transfer" <?php if($transaction_details['trans_payment_method']=='Bank Transfer') { echo 'selected'; } ?>>Bank Transfer</option>
                        <option value="Cash" <?php if($transaction_details['trans_payment_method']=='Cash') { echo 'selected'; } ?>>Cash</option>
                    </select>
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                    for="accno"><?php echo $this->lang->line('Account') ?><span class="compulsoryfld">*</span></label>
                    <select name="trans_account_id" id="trans_account_id" class="form-control" required>
                        <?php

                            if ($bankaccounts)
                            {
                                echo "<option value=''>" . $this->lang->line('Select Bank Account') . "</option>";
                                foreach ($bankaccounts as $row) {
                                    $cid = $row['id'];
                                    $acn = $row['code'];
                                    $holder = $row['name'];
                                    $sel="";
                                    if($transaction_details['trans_account_id']==$acn)
                                    {
                                        $sel = "selected";
                                    }
                                    echo "<option value='$acn' $sel>$acn - $holder</option>";
                                }
                            }
                               
                        ?>
                    </select>
                    <input type="hidden" name="trans_account_id_old" id="trans_account_id_old" value="<?=$transaction_details['trans_account_id']?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="holder"><?php echo $this->lang->line('Amount') ?><span class="compulsoryfld">*</span></label>
                    <input type="number" placeholder="Enter Amount" class="form-control margin-bottom required" name="trans_amount" id="trans_amount" value="<?=$transaction_details['trans_amount']?>">
                    <input type="hidden" placeholder="Enter Amount" class="form-control margin-bottom required" name="trans_amount_old" id="trans_amount_old" value="<?=$transaction_details['trans_amount']?>">
                </div>

                <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="holder"><?php echo $this->lang->line('Descriptions') ?></label>
                    <textarea name="trans_description" id="trans_description" class="form-textarea"><?=$transaction_details['trans_description']?></textarea>
                </div>

               

                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none">
                    <label class="col-form-label"
                    for="lid"><?php echo $this->lang->line('Business Locations') ?></label>
                    <select name="lid" class="form-control">
                        <?php
                        if (!$this->aauth->get_user()->loc) echo "<option value='0'>" . $this->lang->line('All') . "</option>";
                        foreach ($locations as $row) {
                            $cid = $row['id'];
                            $acn = $row['acn'];
                            $holder = $row['address'];     
                                                  
                            echo "<option value='$acn'>$acn - $holder</option>";
                        }
                        ?>
                    </select>
                    
                </div>
                <!-- =========================================================================== -->
                <div class="col-12 mt-3">
                    <h5 id="headerlabel1"><?php echo $this->lang->line('Assign'); ?></h5>
                    <p class="font-13"><?php echo $this->lang->line('Assign-'.$type); ?></p>
                    <hr>
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                    for="accno"><?php echo $this->lang->line('Chart of Account') ?><span class="compulsoryfld">*</span></label>
                    <select name="trans_chart_of_account_id" id="trans_chart_of_account_id" class="form-control" required>
                        <?php
                             echo '<option value="">'.$this->lang->line('Chart of Account').'</option>';
                             foreach ($accounttypes as $parentItem) {
                                 $typename = $parentItem['typename'];
                                 if (isset($accountslist[$typename])) {
                                     echo '<optgroup label="' . htmlspecialchars($typename) . '">';
                                     foreach ($accountslist[$typename] as $childItem) {
                                         $childId = $childItem['id'];
                                         $typename = $childItem['acn']."-".$childItem['holder'];
                                         $acn1 = $childItem['acn'];
                                         $selected="";
                                        if($transaction_details['trans_chart_of_account_id'] == $acn1)
                                        {
                                            $selected = 'selected';
                                        }
                                         echo '<option  value="' . htmlspecialchars($acn1) . '" data-id="' . htmlspecialchars($typename) . '" ' . $selected . '>' . htmlspecialchars($typename) . '</option>';
                                     }
                     
                                     echo '</optgroup>';
                                 }
                             }
                        ?>
                    </select>
                    <input type="hidden" name="trans_chart_of_account_id_old" id="trans_chart_of_account_id_old" value="<?=$transaction_details['trans_chart_of_account_id']?>">
                </div>
               
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="trans_category_id"><?php echo $this->lang->line('Category') ?>
                    <span class="compulsoryfld">*</span></label>
                    <select name="trans_category_id" id="trans_category_id" class="form-control" required>
                    <?php
                        if ($category)
                        {
                            echo "<option value=''>" . $this->lang->line('Select Customer') . "</option>";
                            foreach ($category as $row2) {
                                $transcat_id = $row2['transcat_id'];
                                $selected1="";
                                if($transaction_details['trans_category_id'] == $transcat_id)
                                {
                                    $selected1 = 'selected';
                                }
                                echo "<option value='$transcat_id' $selected1>".$row2['transcat_name']."</option>";
                            }
                        }                            
                    ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="trans_customer_id"><?php 
                    
                     $selecttype = ($type=='income') ?  $this->lang->line('Customer') :  $this->lang->line('Supplier'); 
                     echo $selecttype;  ?></label>

                     <?php
                     if($type=='income')
                     { ?>
                        <select name="trans_customer_id" id="trans_customer_id" class="form-control">
                            <?php
                                if ($customers)
                                {
                                    echo "<option value=''>" . $this->lang->line('Select Customer') . "</option>";
                                    foreach ($customers as $row1) {
                                        $cid = $row1['id'];
                                        $selected2="";
                                        if($transaction_details['trans_customer_id'] == $cid)
                                        {
                                            $selected2 = 'selected';
                                        }
                                        echo "<option value='$cid' $selected2>".$row1['name']."</option>";
                                    }
                                }                            
                            ?>
                        </select>
                    <?php
                     }
                     else{
                        ?>
                            <select name="trans_supplier_id" id="trans_supplier_id" class="form-control">
                            <?php
                                if ($suppliers)
                                {
                                    echo "<option value=''>" . $this->lang->line('Select Supplier') . "</option>";
                                    foreach ($suppliers as $row1) {
                                        $cid = $row1['id'];
                                        $selected2="";
                                        if($transaction_details['trans_supplier_id'] == $cid)
                                        {
                                            $selected2 = 'selected';
                                        }
                                        echo "<option value='$cid' $selected2>".$row1['name']."</option>";
                                    }
                                }                            
                            ?>
                        </select>

                        <?php

                     }

                    ?>
                    
                </div>
                <!-- =========================================================================== -->

                <div class="col-12 mt-3">
                    <h5 id="headerlabel1"><?php echo $this->lang->line('Other'); ?></h5>
                    <hr>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="holder"><?php echo $this->lang->line('Reference') ?></label>
                    <input type="text"  class="form-control margin-bottom" name="trans_reference" id="trans_reference" value="<?=$transaction_details['trans_reference']?>">
                    <input type="hidden" name="trans_ref_number" id="trans_ref_number" value="<?=$transaction_details['trans_ref_number']?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="holder"><?php echo $this->lang->line('Attachment') ?></label>
                    <input type="file" class="form-control margin-bottom" name="trans_file" id="trans_file">
                </div>

                <!-- =================================================== -->

                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-2 submit-section">
                    <input type="hidden" name="trans_type" id="trans_type" value="<?=ucfirst($type)?>">
                    <input type="submit" id="transaction-btn" class="btn btn-crud btn-lg btn-primary margin-bottom"
                        value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                </div>
            </div>
        </form>
    </div>
    <!-- ======================================================================== -->
        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {     
        $("#trans_account_id").select2({
            placeholder: "Type Account", 
            allowClear: true,
            width: '100%'
        });   
        $("#trans_payment_method").select2({
            placeholder: "Select Payment Method", 
            allowClear: true,
            width: '100%'
        });   
        $("#trans_chart_of_account_id").select2({
            placeholder: "type Chart of account", 
            allowClear: true,
            width: '100%'
        });   
        $("#trans_category_id").select2({
            placeholder: "Type Category", 
            allowClear: true,
            width: '100%'
        });   
        $("#trans_customer_id").select2({
            placeholder: "Type Customer", 
            allowClear: true,
            width: '100%'
        });   
        $("#trans_supplier_id").select2({
            placeholder: "Type Supplier", 
            allowClear: true,
            width: '100%'
        });   

        $("#data_form").validate({
            ignore: [],
            rules: {               
                trans_date: { required: true },
                trans_payment_method: { required: true },
                trans_account_id: { required: true },
                trans_amount: { required: true },
                trans_chart_of_account_id: { required: true },
                trans_category_id: { required: true },
                
            },
            messages: {
                trans_date: "Enter date",
                trans_account_id: "Select account",
                trans_payment_method: "Select payment method",
                trans_amount: "Enter Amount",
                trans_chart_of_account_id: "Select Chart of Account",
                trans_category_id: "Select Category",
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

    $('#transaction-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#transaction-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update transaction?",
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
                        url: baseurl + 'bankingtransactions/banktransaction_update_action', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            if(response.status=='Error')
                            {
                                $('#account-error').removeClass('d-none');  
                                $('#transaction-btn').prop('disabled', false);
                            }
                            else{
                                $('#account-error').addClass('d-none');  
                                window.location.href = baseurl + 'bankingtransactions';
                            }
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#transaction-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#transaction-btn').prop('disabled', false);
        }
    });
</script>