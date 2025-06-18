<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
            <div class="card-body">

                <h4 class="card-title"><?php echo $this->lang->line('Currency Format') ?></h4>
                <hr>


                <div class="form-group row">
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="invoiceprefix"><?php echo $this->lang->line('Currency') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control margin-bottom" name="currency"
                            value="<?php echo $currency['currency'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="currency"><?php echo $this->lang->line('Decimal Saparator') ?></label>
                        <select name="deci_sep" class="form-control">
                            <?php
                            echo '<option value="' . $currency['key1'] . '">' . $currency['key1'] . '</option>';

                            ?>
                            <option value=",">, (Comma)</option>
                            <option value=".">. (Dot)</option>
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="thous_sep"><?php echo $this->lang->line('Thousand Saparator') ?></label>
                        <select name="thous_sep" class="form-control">
                            <?php
                            echo '<option value="' . $currency['key2'] . '">' . $currency['key2'] . '</option>'; ?>
                            <option value=",">, (Comma)</option>
                            <option value=".">. (Dot)</option>
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="currency"><?php echo $this->lang->line('Decimal Place') ?></label>
                        <select name="decimal" class="form-control">
                            <?php
                            echo '<option value="' . $currency['url'] . '">' . $currency['url'] . '</option>'; ?>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="spost"><?php echo $this->lang->line('Symbol Position') ?></label>
                        <select name="spos" class="form-control">
                            <?php
                            if ($currency['method'] == 'l') $method = '**Left**'; else $method = '**Right**';
                            echo '<option value="' . $currency['method'] . '">' . $method . '</option>'; ?>
                            <option value="l">Left</option>
                            <option value="r">Right</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="spost"><?php echo $this->lang->line('Invoice') ?><?php echo $this->lang->line('Round Off') ?></label>
                        <select name="roundoff" class="form-control">
                            <?php
                            if ($currency['other'] == 'PHP_ROUND_HALF_UP') {
                                $method = '**ROUND_HALF_UP**';
                            } elseif ($currency['other'] == 'PHP_ROUND_HALF_DOWN') {
                                $method = '**ROUND_HALF_DOWN**';
                            } else {
                                $method = '**Off**';
                            }
                            echo '<option value="' . $currency['other'] . '">' . $method . '</option>'; ?>
                            <option value="">Off</option>
                            <option value="PHP_ROUND_HALF_UP">ROUND_HALF_UP</option>
                            <option value="PHP_ROUND_HALF_DOWN">ROUND_HALF_DOWN</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="spost"><?php echo $this->lang->line('Round Off') ?>
                            Precision</label>
                        <select name="r_precision" class="form-control">
                            <?php
                            echo '<option value="' . $currency['active'] . '">' . $currency['active'] . '</option>'; ?>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12 submit-section text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    </div>
                </div>

            </div>
        </form>
    </div>
</article>
<script type="text/javascript">

 $("#data_form").validate({
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            currency: { required: true },
        },
        messages: {
            income: "Enter Currency",
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
               text: "Do you want to update Currency?",
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
                        url: baseurl + 'settings/currency',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/currency';
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