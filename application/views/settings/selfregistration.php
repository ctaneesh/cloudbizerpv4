<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
            <div class="card-body">

                <h4 class="card-title"><?php echo $this->lang->line('CRM') ?><?php echo $this->lang->line('Settings') ?></h4>
                <hr>


                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_name"><?php echo $this->lang->line('SelfCustomerRegistration') ?> </label><select name="register" class="form-control">

                            <?php switch ($current['key1']) {
                                case '1' :
                                    echo '<option value="1">** ' . $this->lang->line('Yes') . ' **</option>';
                                    break;
                                case '0' :
                                    echo '<option value="0">**' . $this->lang->line('No') . '**</option>';
                                    break;

                            } ?>
                            <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                            <option value="0"><?php echo $this->lang->line('No') ?></option>


                        </select>

                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_name"><?php echo $this->lang->line('Customer') ?> Registration With Email
                        Verification</label><select name="email_conf" class="form-control">

                            <?php switch ($current['url']) {
                                case '1' :
                                    echo '<option value="1">** ' . $this->lang->line('Yes') . ' **</option>';
                                    break;
                                case '0' :
                                    echo '<option value="0">**' . $this->lang->line('No') . '**</option>';
                                    break;

                            } ?>
                            <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                            <option value="0"><?php echo $this->lang->line('No') ?></option>


                        </select>

                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <p class="m-1">Send automatic Email during customer registration by
                        employee. Please do not enable this feature unnecessarily, it may slow the customer registration
                        process as the application will connect to email server if your email is slow.
                    </p>
                    
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_name">Auto Email Customer Details (Registration by Employee) </label><select name="automail" class="form-control">

                            <?php switch ($current['other']) {
                                case '1' :
                                    echo '<option value="1">** ' . $this->lang->line('Yes') . ' **</option>';
                                    break;
                                case '0' :
                                    echo '<option value="0">**' . $this->lang->line('No') . '**</option>';
                                    break;

                            } ?>
                            <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                            <option value="0"><?php echo $this->lang->line('No') ?></option>


                        </select>

                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-12 submit-section">
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
        ignore: [],
        rules: {      
            register: { required: true },
        },
        messages: {
            register: "Select Customer Self Registration",
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
               text: "Do you want to update CRM settings?",
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
                        url: baseurl + 'settings/registration',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/registration';
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
