<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="card-body">
        <div class="">

            <h4 class="card-title"><?php echo $this->lang->line('Print Invoice') ?> Style</h4>
            <hr>


            <div class="form-row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="product_name"><?php echo $this->lang->line('Print Invoice') ?></label>
                       <select name="pstyle" class="form-control">

                        <?php switch (INVV) {
                            case '1' :
                                echo '<option value="1">** Standard Version **</option>';
                                break;
                            case '2' :
                                echo '<option value="2">** Compact Version**</option>';
                                break;

                        } ?>
                        <option value="1">Standard Version</option>
                        <option value="2">Compact Version</option>


                    </select>

                </div>
            </div>
            <div class="form-group row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-1 submit-section">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                </div>
            </div>
        <!-- <div class="form-group row">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"> 1. Standard Version <br><img alt="image" id="dpic"
                                                                class="img-responsive img-md img-md"
                                                                src="<?php echo assets_url() ?>assets/images/v1.png">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12"> 2. Compact Version<br><img alt="image" id="dpic" class="img-md"
                                                              src="<?php echo assets_url() ?>assets/images/v2.png">
            </div>
        </div> -->
    </form>
</div>
</div>


<script type="text/javascript">
$("#data_form").validate({
        ignore: [],
        rules: {      
            pstyle: { required: true },


        },
        messages: {
            pstyle: "Select Print Invoice Style",  
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
               text: "Do you want to update Invoice print setting?",
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
                        url: baseurl + 'settings/print_invoice',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/print_invoice';
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
