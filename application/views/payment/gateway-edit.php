<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
        <div class="card-body">

            <h4 class="card-title"><?php echo $this->lang->line('Edit Gateway Details') . ' ( ' . $gateway['name'] ?>)</h4>
            <hr>


            <input type="hidden" name="gid" value="<?php echo $gateway['id'] ?>">

            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="currency"><?php echo $this->lang->line('Currency Code') ?><span class="compulsoryfld">*</span>
                    <small>(i.e. USD,AUD)</small>
                </label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="currency"
                           value="<?php echo $gateway['currency'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="key1"><?php echo $this->lang->line('API Key') ?><span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="key1"
                           value="<?php echo $gateway['key1'] ?>">
                </div>
            <?php if ($gateway['key2'] != 'none') { ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                           for="key2"><?php echo $this->lang->line('Key 2') ?><span class="compulsoryfld">*</span></label>
                        <input type="text"
                               class="form-control margin-bottom  required" name="key2"
                               value="<?php echo $gateway['key2'] ?>">
                    </div>
            <?php } ?>

            <?php if ($gateway['extra'] != 'none') { ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                           for="key2"><?php echo $this->lang->line('Other') ?><span class="compulsoryfld">*</span></label>
                        <input type="text"
                               class="form-control margin-bottom  required" name="key2"
                               value="<?php echo $gateway['extra'] ?>">
                    </div>
            <?php } ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('Enable Gateway') ?><span class="compulsoryfld">*</span></label>
                    <select class="form-control" name="enable">
                        <option value="<?php echo $gateway['enable'] ?>">
                            --<?php echo $this->lang->line($gateway['enable']) ?>--
                        </option>
                        <option value="Yes"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="No"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="devmode"><?php echo $this->lang->line('Test Mode') ?></label>
                    <select class="form-control" name="devmode">
                        <option value="<?php echo $gateway['dev_mode'] ?>">--<?php echo $gateway['dev_mode'] ?>--
                        </option>
                        <option value="true">true</option>
                        <option value="false">false</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="key2"><?php echo $this->lang->line('Processing Fee') ?> (in %) <span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="p_fee"
                           value="<?php echo $gateway['surcharge'] ?>">
                </div>
            </div>


            <div class="form-group row">
                <div class="col-12 submit-section">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="paymentgateways/edit" id="action-url">
                </div>
            </div>

        </div>
    </form>

</div>

<script>
    $("#data_form").validate({
        ignore: [],
        rules: {  
            currency: { required: true },
            key1: { required: true },
            key2: { required: true },
            p_fee: { required: true },
        },
        messages: {
            currency: "Enter Currency",
            key1: "Enter API Key",
            key2: "Enter Public key",
            p_fee: "Enter Processing Fee",
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

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update payment gateway?",
               icon: "question",
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, proceed!',
               cancelButtonText: "No - Cancel",
               reverseButtons: true, 
               focusCancel: true,
               allowOutsideClick: false,
            }).then((result) => {
               if (result.isConfirmed) {  
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'paymentgateways/edit',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'paymentgateways';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#submit-btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#submit-btn').prop('disabled', false);
        }
    });
</script>