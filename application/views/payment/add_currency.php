<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="card-body">
        <div class="grid_3 grid_4">

            <h5><?php echo $this->lang->line('Currency') ?></h5>
            <hr>


            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="name">ISO <?php echo $this->lang->line('Code') ?><span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="code"
                           maxlength="3">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="acn">Symbol<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="symbol" maxlength="3">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="spost"><?php echo $this->lang->line('Symbol Position') ?></label>
                    <select name="spos" class="form-control">

                        <option value="0">Left</option>
                        <option value="1">Right</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="rate">Exchange Rate<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="rate"
                    >
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="currency"><?php echo $this->lang->line('Decimal Place') ?></label>
                    <select name="decimal" class="form-control">

                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="thous_sep"><?php echo $this->lang->line('Thousand Saparator') ?></label>
                    <select name="thous_sep" class="form-control">

                        <option value=",">, (Comma)</option>
                        <option value=".">. (Dot)</option>
                        <option value="">None</option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="currency"><?php echo $this->lang->line('Decimal Saparator') ?></label>
                    <select name="deci_sep" class="form-control">
                        <option value=".">. (Dot)</option>
                        <option value=",">, (Comma)</option>
                        <option value="">None</option>
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-12 submit-section responsive-text-right">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="paymentgateways/add_currency" id="action-url">
                </div>
            </div>

        </div>
    </form>

</div>


<script>
    $("#data_form").validate({
        ignore: [],
        rules: {              
            symbol: { required: true },
            code: { required: true },
        },
        messages: {
            symbol: "Enter Currency Symbol",
            code: "Enter Currency Code",
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
               text: "Do you want to create currency?",
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
                        url: baseurl + 'paymentgateways/add_currency',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'paymentgateways/currencies';
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