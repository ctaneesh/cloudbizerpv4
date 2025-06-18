<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
             <div class="card-header">
                    <h4 class="card-title">Edit Email Configuration</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">                    
                            <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                        </ul>
                    </div>
                </div>
                <hr>

            <div class="card-body">  
                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="host">Host<span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="host"
                               class="form-control margin-bottom  required" name="host"
                               value="<?php echo $email['host'] ?>">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="port">Port</label>

                        <input type="text" placeholder="port"
                               class="form-control margin-bottom  required" name="port"
                               value="<?php echo $email['port'] ?>">
                        <small>Port 587 recommended with TLS</small>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="auth">Auth<span class="compulsoryfld">*</span></label>

                        <select name="auth" class="form-control">
                            <?php if ($email['auth']) {
                                echo ' <option value="true">--True--
                                
                            </option>';
                            } else {
                                echo ' <option value="false">--False--
                                
                            </option>';
                            }
                            ?>
                            <option value="true">True

                            </option>
                            <option value="false">False

                            </option>
                        </select>

                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="auth_type">Auth Type</label>

                        <select name="auth_type" class="form-control">
                            <?php
                            echo ' <option value="' . $email['auth_type'] . '">--' . $email['auth_type'] . '--
                                
                            </option>';

                            ?>
                            <option value="none">None

                            </option>
                            <option value="tls">TLS

                            </option>
                            <option value="ssl">SSL

                            </option>
                        </select>

                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="username">Username<span class="compulsoryfld">*</span></label>

                        <input type="text" placeholder="username"
                               class="form-control margin-bottom  required" name="username"
                               value="<?php echo $email['username'] ?>">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="password">Password<span class="compulsoryfld">*</span></label>

                        <input type="password" placeholder="password"
                               class="form-control required" name="password"
                               value="<?php echo $email['password'] ?>">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="sender">Sender Email<span class="compulsoryfld">*</span></label>

                        <input type="text" placeholder="email"
                               class="form-control required" name="sender"
                               value="<?php echo $email['sender'] ?>">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-12 submit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom" value="Update" data-loading-text="Updating...">
                    </div>

                    </div>
                    <div class="col-sm-12"><span id="email_update_m"></span></div>
                </div>
                <pre class="mt-1 pt-1">
                    Note: #Refer to documentation to configure email templates.
                </pre>
            </div>
        </form>

        
    </div>
</article>

<script type="text/javascript">

 $("#data_form").validate({
        ignore: [], 
        rules: {               
            host: { required: true },
            auth: { required: true },
            username: { required: true },
            password: { required: true },
            sender: { required: true },
        },
        messages: {
            host: "Select Time Zone",
            auth: "Select Auth",
            username: "Enter User Name",
            password: "Enter Password",
            sender: "Enter Sender Email",
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
               text: "Do you want to update Email Configuration?",
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
                        url: baseurl + 'settings/email',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/email';
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