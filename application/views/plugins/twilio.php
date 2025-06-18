<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
        <div class="card-body">

            <h4 class="card-title">Twilio SMS Service</h4>
            <hr>


            <p>You can send bills as SMS to your customers using Twilio SMS Service. You can also setup urls
                shorter plugin to convert long invoice urls to small and more user friendly in SMS.</p>
            <p>You can signup here for keys. <a href="https://www.twilio.com/">https://www.twilio.com/</a></p>

            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="terms">Account SID <span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="key1"
                           value="<?php echo $universal['key1'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="terms">Auth Token<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="key2"
                           value="<?php echo $universal['key2'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="terms">Send Id<span class="compulsoryfld">*</span></label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="sender"
                           value="<?php echo $universal['url'] ?>">
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                                             for="terms"><?php echo $this->lang->line('Enable') ?><span class="compulsoryfld">*</span></label>
                    <select name="enable" class="form-control">

                        <?php switch ($universal['active']) {
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


                <div class="col-12 submit-section responsive-text-right">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="plugins/twilio" id="action-url">
                </div>
            </div>

            <h5 class="mt-2">Other Universal SMS Service</h5>
            <hr>


            <p class="purple">You can send your bills as SMS to your customers using any SMS Service that is
                available in your country. You can also setup urls
                shorter plugin to convert long invoice urls to small and more user friendly in SMS. If your sms
                provider has support for REST Based api like TextLocal,Clockwork you can enable in with some lines
                of code editing.</p>
        </div>
    </form>

</div>

<script>
    $("#data_form").validate({
        ignore: [],
        rules: {      
            key1: { required: true },
            key2: { required: true },
            sender: { required: true },


        },
        messages: {
            key1: "Enter Account SID",  
            key2: "Enter Auth Token",  
            sender: "Enter Send Id",  
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
               text: "Do you want to update SMS service?",
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
                        url: baseurl + 'plugins/twilio',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'plugins/twilio';
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