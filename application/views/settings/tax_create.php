<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card card-block">


            <form method="post" id="data_form" class="card-body">

                <h4 class="cad-title"><?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Tax')." Slab" ?></h4>
                <hr>

                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="tname"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Tax Name"
                               class="form-control margin-bottom  required" name="tname">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="trate"><?php echo $this->lang->line('Rate') ?> (%)<span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Tax Rate"
                               class="form-control margin-bottom  required" name="trate">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="ttype"><?php echo $this->lang->line('Type') ?></label>
                        <select class="form-control" name="ttype">
                            <option value="yes" data-tformat="yes">Exclusive</option>
                            <option value="inclusive"
                                    data-tformat="incl"><?php echo $this->lang->line('Inclusive') ?></option>
                            <option value="cgst" data-tformat="cgst"><?php echo $this->lang->line('GST1') ?></option>
                            <option value="igst" data-tformat="igst"><?php echo $this->lang->line('IGST') ?></option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           name="ttype2"><?php echo $this->lang->line('Type') ?> 2</label>
                        <select class="form-control" name="ttype2">
                            <option value="yes" data-tformat="yes">Exclusive</option>
                            <option value="inclusive"
                                    data-tformat="incl"><?php echo $this->lang->line('Inclusive') ?></option>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-12 submit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="settings/taxslabs_new" id="action-url">
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
            tname: { required: true },
            trate: { required: true },
        },
        messages: {
            tname: "Enter Tax Name",
            trate: "Enter Tax Rate",
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
               text: "Do you want to Add new tax slab?",
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
                        url: baseurl + 'settings/taxslabs_new',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/taxslabs';
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