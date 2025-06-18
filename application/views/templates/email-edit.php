<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
        <div class="card-body">

            <h4 class="card-title"><?php echo $this->lang->line('Edit') . ' ( ' . $email['name'] . ') ' . $this->lang->line('Template') ?></h4>
            <hr>


            <input type="hidden" name="id" value="<?php echo $email['id'] ?>">

            <div class="form-group row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="subject"><?php echo $this->lang->line('Subject') ?><span class="compulsoryfld">*</span>
                </label>
                    <input type="text"
                           class="form-control margin-bottom  required" name="subject"
                           value="<?php echo $email['key1'] ?>">
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="body"><?php echo $this->lang->line('Body') ?></label>
                        <textarea class="form-control margin-bottom summernote" name="body" rows="15"><?php echo $email['other'] ?></textarea>
                </div>
                <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12 submit-section responsive-text-right">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="templates/email_update" id="action-url">
                </div>
            </div>

        </div>
    </form>
    <div class="box mb-2">
        <div class="col-sm-2">Variables are</div>
        <div class="col-sm-8">{Company}, {BillNumber}, {URL}, {CompanyDetails}, {DueDate}, {Amount}</div>
    </div>

</div>

<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 100,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });


    });
$("#data_form").validate({
        ignore: [],
        rules: {      
            subject: { required: true },


        },
        messages: {
            subject: "Enter Email Subject",  
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
               text: "Do you want to update Email template?",
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
                        url: baseurl + 'templates/email_update',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'templates/email';
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

