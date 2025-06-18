<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
        <div class="card-body">

            <h4 class="card-title">Google reCaptcha <?php echo $this->lang->line('Settings') ?></h4>
            <hr>


            <p>reCAPTCHA is a free service from Google that protects your application login page from spam and
                abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated
                software from engaging in abusive activities on your site. It does this while letting your valid
                users pass through with ease. You can signup here for keys.<a
                        href="https://www.google.com/recaptcha">https://www.google.com/recaptcha</a></p>
            <p>reCAPTCHA will appear when a user try multiple login attempts.</p>

            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="terms">Public Key<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="publickey"
                           value="<?php echo $captcha['recaptcha_p'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="terms">Private Key<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="privatekey"
                           value="<?php echo $captcha['recaptcha_s'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="terms"><?php echo $this->lang->line('Enable') ?></label>
                    <select name="captcha" class="form-control">

                        <?php switch ($captcha['captcha']) {
                            case 1 :
                                echo '<option value="1">--Yes--</option>';
                                break;
                            case 0 :
                                echo '<option value="0">--No--</option>';
                                break;

                        } ?>
                        <option value="1">Yes</option>
                        <option value="0">No</option>


                    </select>
                </div>
            </div>


            <div class="form-group row">

                <label class="col-form-label"></label>

                <div class="col-sm-4">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="plugins/recaptcha" id="action-url">
                </div>
            </div>

        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 250,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']]

            ]
        });
    });

    $("#data_form").validate({
        ignore: [],
        rules: {      
            publickey: { required: true },
            privatekey: { required: true },
        },
        messages: {
            publickey: "Enter public Key",
            privatekey: "Enter private Key",
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
               text: "Do you want to update google reCaptcha?",
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
                        url: baseurl + 'plugins/recaptcha',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'plugins/recaptcha';
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


