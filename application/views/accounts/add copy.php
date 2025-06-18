<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card card-block">
        
        <div class="card-body">  
            <form method="post" id="data_form" class="form-horizontal">

                <h5><?php echo $this->lang->line('Add New Account') ?></h5>
                <hr>
                <div class="alert alert-danger d-none" role="alert" id="account-error">Account number already in use</div>
                <div class="form-group row">
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                        for="accno"><?php echo $this->lang->line('Account No') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Account Number"
                            class="form-control margin-bottom required" name="accno">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="holder"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Name"
                            class="form-control margin-bottom required" name="holder">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                        for="intbal"><?php echo $this->lang->line('Intial Balance') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Intial Balance" onkeypress="return isNumber(event)"
                            class="form-control margin-bottom required" name="intbal">
                    </div>


                    <!-- ======================================== -->
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Account Type') ?><span class="compulsoryfld">*</span></label>
                            
                        <?php
                            echo '<select name="account_type_id" id="account_type_id" class="form-control">';
                            echo '<option value="">Select Type</option>';
                            foreach ($accountheaders as $parentItem) {
                                $coaHeaderId = $parentItem['coa_header_id'];
                                $coaHeader = $parentItem['coa_header'];
                                if (isset($child[$coaHeaderId])) {
                                    echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';
                                    foreach ($child[$coaHeaderId] as $childItem) {
                                        $childId = $childItem['id'];
                                        $typename = $childItem['typename'];
                                        echo '<option value="' . htmlspecialchars($childId) . '" data-id="' . htmlspecialchars($typename) . '">' . htmlspecialchars($typename) . '</option>';
                                    }
                    
                                    echo '</optgroup>';
                                }
                            }
                            echo '</select>';
                            ?>


                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Parent Account') ?></label>
                        <select name="parent_account_id" id="parent_account_id" class="form-control margin-bottom">

                        </select>
                        <input type="hidden" name="account_type" id="account_type">
                    </div>
                    <!-- ======================================== -->

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 d-none">
                        <label class="col-form-label"
                        for="lid"><?php echo $this->lang->line('Business Locations') ?></label>
                        <select name="lid" class="form-control">
                            <?php
                            if (!$this->aauth->get_user()->loc) echo "<option value='0'>" . $this->lang->line('All') . "</option>";
                            foreach ($locations as $row) {
                                $cid = $row['id'];
                                $acn = $row['cname'];
                                $holder = $row['address'];
                                echo "<option value='$cid'>$acn - $holder</option>";
                            }
                            ?>
                        </select>
                        
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="acode"><?php echo $this->lang->line('Descriptions') ?></label>
                        <textarea name="acode" id="acode" class="form-textarea margin-bottom"></textarea>
                    </div>
                
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-3 submit-section">
                        <input type="submit" id="account-btn" class="btn btn-lg btn-primary margin-bottom"
                            value="<?php echo $this->lang->line('Add Account') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="accounts/addacc" id="action-url">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#account_type_id").select2({
        placeholder: "Type Customer", 
        allowClear: true,
        width: '100%'
    });   
    $("#parent_account_id").select2({
        placeholder: "Select Parent Account", 
        allowClear: true,
        width: '100%'
    });   

    $("#data_form").validate({
        ignore: [],
        rules: {               
            coa_type_id: { required: true },
            typename: { required: true },
            coa_header_id: { required: true }
        },
        messages: {
            coa_type_id: "Enter Chart of Account Type ID",
            typename: "Enter Chart of Account Type Name",
            coa_header_id: "Select Chart of Account Header"
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
$('#account-btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#account-btn').prop('disabled', true);
    
    // Validate the form
    if ($("#data_form").valid()) {                
        var form = $('#data_form')[0];
        var formData = new FormData(form); 
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to create a new account?",
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
                    url: baseurl + 'accounts/addacc', // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        // location.reload();
                        if(response.status=='Error')
                        {
                            $('#account-error').removeClass('d-none');  
                            $('#account-btn').prop('disabled', false);
                        }
                        else{
                            $('#account-error').addClass('d-none');  
                        }
                        console.log(response.status);
                        
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                        console.log(error); // Log any errors
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Enable the button again if user cancels
                $('#account-btn').prop('disabled', false);
            }
        });
    } else {
        // If form validation fails, re-enable the button
        $('#account-btn').prop('disabled', false);
    }
});
$("#account_type_id").on('change',function(){
    $("#account_type").val($("#account_type_id option:selected").data("id"));
    var delivery = $("#delevery_note_id").val();
    $.ajax({
        type: 'POST',
        url: baseurl +'accounts/load_accounts_by_typeid',
        data: {
            "account_type_id" : $("#account_type_id").val()
        },
        success: function(response) {
            var responseData = JSON.parse(response);
           console.log(responseData.data);
           $("#parent_account_id").html(responseData.data);
            // $('#submit-deliverynote').prop('disabled',false);
            // var responseData = JSON.parse(response);
            // var deliveryNoteData = responseData.data;

            // window.open(baseurl + 'DeliveryNotes/reprintnote?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg, '_blank');
            // window.location.href = baseurl + 'SalesOrders/delivery_notes?id=' + deliveryNoteData;paymentgateways/bank_accounts
            
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
});
</script>