<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('paymentgateways/bank_accounts') ?>"><?php echo $this->lang->line('Bank Accounts') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Add Bank Account') ?></li>
                </ol>
            </nav>
            
         <h4 class="card-title"><?php echo $this->lang->line('Add Bank Account') ?> </h4>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
         </div>
      </div>
    <form method="post" id="data_form" class="form-horizontal" autocomplete="off">
        <div class="card-body">

            <div class="alert alert-danger d-none" role="alert" id="account-error">Account number already in use</div>
            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" class="form-control margin-bottom" name="name">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="acn"><?php echo $this->lang->line('Account Number') ?><span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom" name="acn">
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="key2"><?php echo $this->lang->line('Codes') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" id="code" class="form-control margin-bottom" name="code" placeholder="Eg : 500, 900 etc">
                    <i id="code-error" class="error"></i>
                    <input type="hidden" name="codeflg" id="codeflg" value="0">
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('Enable Account') ?></label>
                    <select class="form-control" name="enable">
                        <option value="Yes"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="No"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="enable"><?php echo $this->lang->line('Default Account') ?></label>
                    <select class="form-control" name="defaultaccount">
                        <option value="No"><?php echo $this->lang->line('No') ?></option>
                        <option value="Yes"><?php echo $this->lang->line('Yes') ?></option>
                    </select>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="enable"><?php echo $this->lang->line('Opening Balance / Deposit') ?></label>
                    <input type="number" name="opening_balance" id="opening_balance" class="form-control margin-bottom">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="note">Bank<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="bank">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address">Branch<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="branch">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="bankphone">Bank Phone</label>
                    <input type="number"  class="form-control margin-bottom" name="bankphone">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="address"><?php echo $this->lang->line('Address') ?></label>
                    
                    <textarea name="address" id="address"  class="form-textarea margin-bottom"></textarea>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-12">
                    <input type="submit" id="bank-add-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="paymentgateways/add_bank_ac" id="action-url">
                </div>
            </div>

        </div>
    </form>

</div>

<script>
      $(document).ready(function() {
       
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {   
                name: { required: true },
                acn: { required: true },
                bank: { required: true },
                branch: { required: true },
                code: { required: true }
            },
            messages: {
                name: "Enter account holder name",
                acn: "Enter account number",
                bank: "Enter bank name",
                branch: "Enter branch name",
                code: "Enter account code",
            }
        }));

    });
    $('#bank-add-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#bank-add-btn').prop('disabled', true);
        if($("#codeflg").val() == 1)
        {
            $("#code").val("");
        }
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create bank account?",
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
                        url: baseurl + 'paymentgateways/add_bank_ac', // Replace with your server endpoint
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
                                $('#bank-add-btn').prop('disabled', false);
                            }
                            else{
                                $('#account-error').addClass('d-none');  
                                 window.location.href = baseurl + 'paymentgateways/bank_accounts';
                            }
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#bank-add-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#bank-add-btn').prop('disabled', false);
        }
    });

    $('#code').on('keyup', function() {
        var code = $(this).val(); 
        if (code.length > 0) {
            $.ajax({
                url: baseurl + 'paymentgateways/check_code_used_or_not',
                type: 'POST',
                data: { code: code },
                success: function(response) {
                    if (response === 'true') {
                        // Code is already taken, display message
                        $('#code').css('border-color', 'red'); // Optional visual cue
                        $('#code-error').text('Code '+code + ' is already taken').show();
                        $("#codeflg").val(1);
                    } else {
                        // Code is available
                        $('#code').css('border-color', ''); // Reset if available
                        $('#code-error').hide();
                        $("#codeflg").val(0);
                    }
                }
            });
        } else {
            $('#code').css('border-color', ''); // Reset if input is cleared
            $('#code-error').hide();
        }
    });
</script>