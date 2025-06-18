<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="card-body">
            <div class="card card-block">

                <h4 class="card-title"><?php echo $this->lang->line('Edit Tax Details') ?></h4>
                <hr>


                <input type="hidden" name="id" value="<?php echo $company['id'] ?>">


                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="taxstatus"><?php echo $this->lang->line('TAX Status') ?></label>
                        <select name="taxstatus" class="form-control">

                            <?php echo $taxlist; ?>

                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="taxstatus">GST Type</label>
                        <select name="gst_type" class="form-control">

                            <?php if (GST_INCL == 'inclusive') {
                                echo '<option value="inclusive">*Inclusive*</option>';


                            } else {
                                echo '<option value="yes">*Exclusive*</option>';


                            } ?>
                            <option value="inclusive">Inclusive</option>
                            <option value="yes">Exclusive</option>

                        </select>
                        <small>Applicable only if TAX Status is GST</small>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('TAX ID') ?></label>
                        <input type="text" placeholder="tax_id"
                               class="form-control margin-bottom" name="tax_id"
                               value="<?php echo $company['tax_id'] ?>">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-xs-12 responsive-text-right">
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
            taxstatus: { required: true },
        },
        messages: {
            taxstatus: "Select Tax Status",
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
               text: "Do you want to update Tax?",
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
                        url: baseurl + 'settings/tax',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/tax';
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

