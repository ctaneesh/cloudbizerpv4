<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
    <div class="card">
        
        <div class="card-header">
        <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('promo') ?>"><?php echo $this->lang->line('Promo Codes') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Add Promo') ?></li>
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Add Promo') ?></h5>
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


                <form method="post" id="data_form" class="form-horizontal">

                    <div class="form-row">
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="code"><?php echo $this->lang->line('Code') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="code" class="form-control margin-bottom  required" name="code" value="<?php echo $this->coupon->generate(8) ?>" required>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="amount"><?php echo $this->lang->line('Amount') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="amount" class="form-control margin-bottom  required" name="amount" value="0" onkeypress="return isNumber(event)" required>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="qty"><?php echo $this->lang->line('Qty') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="qty" class="form-control margin-bottom  required" name="qty" value="1">
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="valid"><?php echo $this->lang->line('Valid') ?><span class="compulsoryfld">*</span></label>
                            <input type="date" class="form-control required" placeholder="Start Date" name="valid" autocomplete="false" min="<?=date('Y-m-d')?>" required>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="link_ac"><?php echo $this->lang->line('Link to account') ?><span class="compulsoryfld">*</span></label>
                            <fieldset>
                                <div class="d-flex">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="link_ac" id="customRadio1" value="yes" checked>
                                        <label class="custom-control-label" for="customRadio1">
                                            <?php echo $this->lang->line('Yes'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="link_ac" id="customRadio2" value="no">
                                        <label class="custom-control-label" for="customRadio2">
                                            <?php echo $this->lang->line('No'); ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>



                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="pay_acc"><?php echo $this->lang->line('Account') ?></label>
                            <select name="pay_acc" class="form-control">
                                <option value="">Select Account</option>
                                <?php
                                if($accounts)
                                {
                                    foreach ($accounts as $row) {
                                        $cid = $row['id'];
                                        $acn = $row['acn'];
                                        $holder = $row['holder'];
                                        echo "<option value='$cid'>$acn - $holder</option>";
                                    }
                                }
                                
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-form-label" for="note"><?php echo $this->lang->line('Note') ?><span class="compulsoryfld">*</span></label>
                            <textarea name="note" class="form-textarea"></textarea>
                        </div>
                    </div>
                        <div class="col-12 text-right mt-2">
                            <hr>
                            <input type="submit" id="add-btn" class="btn btn-lg btn-primary btn-crud margin-bottom"
                                   value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                            <input type="hidden" value="promo/create" id="action-url">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [], // Important: Do not ignore hidden fields (used by summernote)
            rules: {
                // cst: { required: true },
               code: { required: true },
               amount: { required: true },
               qty: { required: true },
               valid: { required: true },
               note: { required: true },
              
              
            },
            messages: {
                amount: "Enter Amount",
                qty: "Enter Quantity",
                valid: "Enter Valid Date",
                note: "Enter Note",
                
            }
            
        }));

    });
    $("#add-btn").on("click", function(e) {
        e.preventDefault();
        var validationFailed = false;
        if ($("#data_form").valid()) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to Create Coupon?",
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
                    var form = $('#data_form')[0];
                    var formData = new FormData(form);
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'promo/create',
                        data: formData,
                        processData: false, // Prevent jQuery from processing the data
                        contentType: false, // Prevent jQuery from setting content type header
                        success: function(response) {
                            window.location.href = baseurl + 'promo';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log the error
                            $('#add-btn').prop('disabled', false); // Re-enable button on error
                        }
                    });
                } else {
                    $('#add-btn').prop('disabled', false); // Re-enable button on cancel
                }
            });
        } else {
            $('#add-btn').prop('disabled', false); // Re-enable button if form is invalid
        }
    });
</script>