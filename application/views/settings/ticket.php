<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
            <div class="card-body">

                <h4 class="card-title"><?php echo $this->lang->line('Support Tickets') ?></h4>
                <hr>


                <div class="form-group row">
                    <div class="col-xl-2 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_name"><?php echo $this->lang->line('Support Tickets') . ' ' . $this->lang->line('Module') ?> </label><select name="service" class="form-control">

                            <?php if ($support['key1'] == 1) {
                                echo '<option value="1">*' . $this->lang->line('On') . '*</option>';


                            } ?>
                            <option value="0"><?php echo $this->lang->line('Off') ?></option>
                            <option value='1'><?php echo $this->lang->line('On') ?></option>


                        </select>
                        <small>(In Customer Login)</small>

                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-3 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="taxstatus"><?php echo $this->lang->line('Activity Email') ?></label>
                        <select name="email" class="form-control">

                            <?php if ($support['key2'] == 1) {
                                echo '<option value="1">*' . $this->lang->line('On') . '*</option>';


                            } ?>
                            <option value="0"><?php echo $this->lang->line('Off') ?></option>
                            <option value='1'><?php echo $this->lang->line('On') ?></option>

                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="taxstatus"><?php echo $this->lang->line('Email') ?></label>
                        <input type="email" name="support" class="form-control" value="<?php echo $support['url'] ?>">


                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"><?php echo $this->lang->line('Support Signature') ?></label>
                        <textarea name="signature" class="summernote"
                        ><?php echo $support['other'] ?></textarea>
                    </div>
                    <div class="col-12 submit-section responsive-text-right">
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
            service: { required: true },
        },
        messages: {
            service: "Select Support Tickets Module",  
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
               text: "Do you want to update support ticket?",
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
                        url: baseurl + 'settings/tickets',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/tickets';
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
<script type="text/javascript">
    $(function () {
        $('.summernote').summernote({
            height: 100,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']]

            ]
        });
    });
</script>

