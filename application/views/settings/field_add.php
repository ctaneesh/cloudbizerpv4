<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card card-block">


            <form method="post" id="data_form" class="card-body">

                <h4 class="card-title"><?php echo $this->lang->line('Add') ?> <?php echo $this->lang->line('Custom') ?> <?php echo $this->lang->line('Field') ?> </h4>
                <hr>

                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Custom Field Name"
                               class="form-control margin-bottom  required" name="f_name">
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_type"><?php echo $this->lang->line('Type') ?></label>
                        <select class="form-control" name="f_type">
                            <option value="text">Text</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_module"><?php echo $this->lang->line('Module') ?></label>
                        <select class="form-control" name="f_module">
                            <option value="1">Customer</option>
                             <option value="2">Invoices</option>
                            <option value="4">Product</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_view"><?php echo $this->lang->line('Public View') ?></label>
                        <select class="form-control" name="f_view">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        <small>Anyone can view out side the application.</small>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_required">Required<span class="compulsoryfld">*</span></label>
                        <select class="form-control" name="f_required">
                            <option value="">No</option>
                            <option value="required">Yes</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_placeholder">PlaceHolder<span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Field PlaceHolder "
                               class="form-control margin-bottom required" name="f_placeholder">
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="f_placeholder"><?php echo $this->lang->line('Description') ?></label>
                        <input type="text" placeholder="Field Description "
                               class="form-control margin-bottom" name="f_description">
                    </div>
                    <div class="col-12 mt-1 submit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="settings/add_custom_field" id="action-url">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>
<script>
    



 $("#data_form").validate({
        ignore: [], 
        rules: {               
            f_name: { required: true },
            f_placeholder: { required: true },
        },
        messages: {
            f_name: "Enter Name",
            f_placeholder: "Enter Placeholder",
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
               text: "Do you want to create Custom Field?",
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
                        url: baseurl + 'settings/add_custom_field',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/custom_fields';
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
