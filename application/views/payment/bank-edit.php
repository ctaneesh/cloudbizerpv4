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
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Edit Account Details') ?> (<?php echo $bank_account['name'] ?>)</li>
                </ol>
            </nav>
            
         <h4 class="card-title"><?php echo $this->lang->line('Edit Account Details') ?> (<?php echo $bank_account['name'] ?>) </h4>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
         </div>
      </div>
    <form method="post" id="data_form" class="form-horizontal">
        <div class="card-body">



            <input type="hidden" name="gid" value="<?php echo $bank_account['id'] ?>">
            <div class="alert alert-danger d-none" role="alert" id="account-error">Account number already in use</div>
            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="name"
                           value="<?php echo $bank_account['name'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="acn"><?php echo $this->lang->line('Account Number') ?><span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="acn"
                           value="<?php echo $bank_account['acn'] ?>">
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="key2"><?php echo $this->lang->line('Codes') ?><span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom" name="code"
                           value="<?php echo $bank_account['code'] ?>" readonly>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('Enable Account') ?><span class="compulsoryfld">*</span></label>
                    <select class="form-control" name="enable">
                        <option value="<?php echo $bank_account['enable'] ?>">
                            --<?php echo $bank_account['enable'] ?>--
                        </option>
                        <option value="Yes"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="No"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="enable"><?php echo $this->lang->line('Default Account') ?></label>
                    <select class="form-control" name="defaultaccount">
                        <option value="<?php echo $bank_account['defaultaccount'] ?>">
                            --<?php echo $bank_account['defaultaccount'] ?>--
                        </option>
                        <option value="No"><?php echo $this->lang->line('No') ?></option>
                        <option value="Yes"><?php echo $this->lang->line('Yes') ?></option>
                    </select>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="enable"><?php echo $this->lang->line('Opening Balance / Deposit') ?></label>
                    <input type="number" name="opening_balance" id="opening_balance" class="form-control margin-bottom" value="<?php echo $bank_account['opening_balance'] ?>">
                    <input type="hidden" name="old_opening_balance" id="old_opening_balance" class="form-control margin-bottom" value="<?php echo $bank_account['opening_balance'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="note">Bank<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="bank"
                           value="<?php echo $bank_account['note'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="note">Branch<span class="compulsoryfld">*</span></label>
                    <input type="text" class="form-control margin-bottom  required" name="branch"
                           value="<?php echo $bank_account['branch'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="bankphone">Bank Phone</label>
                    <input type="number"  class="form-control margin-bottom" name="bankphone" value="<?php echo $bank_account['bankphone'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="note">Address<span class="compulsoryfld">*</span></label>
                    <textarea name="address" id="address"  class="form-textarea margin-bottom"><?php echo $bank_account['address'] ?></textarea>
                </div>
            </div>


            <div class="form-group row">                
                <div class="col-12">
                    <input type="submit" id="bank-add-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="paymentgateways/edit_bank_ac" id="action-url">
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
                branch: { required: true }
            },
            messages: {
                name: "Enter account holder name",
                acn: "Enter account number",
                bank: "Enter bank name",
                branch: "Enter branch name",
            }
        }));

    });
    $('#bank-add-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        // $('#bank-add-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update bank account?",
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
                        url: baseurl + 'paymentgateways/edit_bank_ac', // Replace with your server endpoint
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
</script>