<div class="content-body">
    <div class="card">
        <div class="card-header">
           
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">


                <form method="post" id="data_form" class="form-horizontal">
                    <div class="grid_3 grid_4">

                        <h4 class="card-title"><?php echo $this->lang->line('Edit Term') ?></h4>
                        <hr>


                        <input type="hidden" name="id" value="<?php echo $term['id'] ?>">


                        <div class="form-group row">
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="terms"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                                <input type="text"
                                       class="form-control margin-bottom  required" name="title"
                                       value="<?php echo $term['title'] ?>">
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label" for="terms"><?php echo $this->lang->line('Type') ?></label>
                                <select class="form-control margin-bottom" name="type">
                                    <option value="<?php echo $term['type'] ?>">Do No Change</option>
                                    <option value="0"><?= $this->lang->line('All') ?></option>
                                    <option value="1"><?= $this->lang->line('Invoice') ?></option>
                                    <option value="2"><?= $this->lang->line('Quote') ?></option>
                                    <option value="4"><?= $this->lang->line('Purchase Order') ?></option>
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"><?php echo $this->lang->line('Description') ?></label>
                                <textarea name="terms" class="summernote"><?php echo $term['terms'] ?></textarea>
                            </div>
                            <div class="col-12 responsive-text-right">
                                <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                                       value="<?php echo $this->lang->line('Update') ?>"
                                       data-loading-text="Updating...">
                                <input type="hidden" value="settings/edit_term" id="action-url">
                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
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
                ['fontsize', ['fontsize']]

            ]
        });
    });

    $("#data_form").validate({
        ignore: [], 
        rules: {               
            title: { required: true },
        },
        messages: {
            title: "Enter Term Name",
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
               text: "Do you want to create billing term?",
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
                        url: baseurl + 'settings/edit_term',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/billing_terms';
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


