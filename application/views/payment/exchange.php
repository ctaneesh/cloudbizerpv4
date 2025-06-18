<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
         <div class="card-header">
            <h4 class="card-title"> <?php echo $this->lang->line('Currency Exchange') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">                    
                        <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                    </ul>
                </div>
            <hr>
        </div>

        <div class="card-body">

            <p>Application has integrated currencylayer.com API. It offers a real-time currency conversion for your
                invoices. Accurate Exchange Rates for 168 World Currencies with data updates ranging from every 60
                minutes down to stunning 60 seconds. Please visit <a
                        href="https://currencylayer.com/">currencylayer.com</a>
                to get API key.
            <p>
            <p> Please do not forget set the CRON job for automatic base rate updates in background.</p>
            <p> API Integration and Cron Job are optionals, you can manually set exchange rates here <a
                        href="<?php echo base_url() ?>paymentgateways/currencies"><?php echo base_url() ?>
                    paymentgateways/currencies</a></p>


            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="currency"><?php echo $this->lang->line('Base Currency Code') ?><span class="compulsoryfld">*</span>
                    <small>(i.e. USD,AUD)</small>
                </label>

                    <input type="text"
                           class="form-control margin-bottom  required" name="currency"
                           value="<?php echo $exchange['url'] ?>" maxlength="3">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="key1"><?php echo $this->lang->line('API Key') ?><span class="compulsoryfld">*</span></label>

                    <input type="text"
                           class="form-control margin-bottom  required" name="key1"
                           value="<?php echo $exchange['key1'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="key2">API Endpoint<span class="compulsoryfld">*</span></label>

                    <input type="text"
                           class="form-control margin-bottom  required" name="key2"
                           value="<?php echo $exchange['key2'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('Enable Exchange') ?></label>

                    <?php if ($exchange['active'] == 1) {
                        $env = 'Yes';
                    } else {
                        $env = 'No';
                    } ?>
                    <select class="form-control" name="enable">
                        <option value="<?php echo $exchange['active'] ?>">
                            --<?php echo $this->lang->line($env) ?>--
                        </option>
                        <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="0"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label">Secondary Currency as
                    Currency </label>
                    <div class="">
                        <label class="display-inline-block custom-control custom-radio ml-1">
                            <input type="radio" name="reverse"
                                   value="1" <?php if ($exchange['other']) echo 'checked=""' ?>>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description ml-0"><?php echo $this->lang->line('Yes') ?></span>
                        </label>
                        <label class="display-inline-block custom-control custom-radio">
                            <input type="radio" name="reverse"
                                   value="0" <?php if (!$exchange['other']) echo 'checked=""' ?>>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description ml-0"><?php echo $this->lang->line('No') ?></span>
                        </label>
                    </div>
                    <small> Recommended : No | With this option input during bill creation will be considered as per
                        selected currency.
                    </small>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-12 submit-section responsive-text-right">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="paymentgateways/exchange" id="action-url">
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
        },
        messages: {
            key1: "Enter API Key",
            key2: "Enter API End Point",
            currency: "Enter Currency Code",
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
               text: "Do you want to update currency exchange?",
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
                        url: baseurl + 'paymentgateways/exchange',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'paymentgateways/exchange';
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