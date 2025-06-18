<div class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
            <div class="card-body">

                <h4 class="card-title"><?php echo $this->lang->line('Email') . ' ' . $this->lang->line('Alert') ?></h4>
                <p><?php echo $this->lang->line('Automated Email') ?></p>
                <hr>
                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="tzone"><?php echo $this->lang->line('Transactions') . ' ' . $this->lang->line('Email') ?></label>
                        <select name="email" class="form-control">

                            <?php
                            if ($auto['key1'] == 0) {
                                echo '<option value="' . $auto['key1'] . '">*' . $this->lang->line('No') . '</option>';
                            } else {
                                echo '<option value="' . $auto['key1'] . '">*' . $this->lang->line('Yes') . '</option>';
                            }
                            echo '<option value="1">' . $this->lang->line('Yes') . '</option>
                            <option value="0">' . $this->lang->line('No') . '</option>'; ?>

                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="tzone"><?php echo $this->lang->line('Transactions') . ' ' . $this->lang->line('Delete') . ' ' . $this->lang->line('Email') ?></label>
                        <select name="td_email" class="form-control">

                            <?php
                            if ($auto['key2'] == 0) {
                                echo '<option value="' . $auto['key2'] . '">*' . $this->lang->line('No') . '</option>';
                            } else {
                                echo '<option value="' . $auto['key2'] . '">*' . $this->lang->line('Yes') . '</option>';
                            }
                            echo '<option value="1">' . $this->lang->line('Yes') . '</option>
                            <option value="0">' . $this->lang->line('No') . '</option>'; ?>

                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="tzone"><?php echo $this->lang->line('Invoice') . ' ' . $this->lang->line('Delete') . ' ' . $this->lang->line('Email') ?></label>
                        <select name="id_email" class="form-control">

                            <?php
                            if ($auto['method'] == 0) {
                                echo '<option value="' . $auto['method'] . '">*' . $this->lang->line('No') . '</option>';
                            } else {
                                echo '<option value="' . $auto['method'] . '">*' . $this->lang->line('Yes') . '</option>';
                            }
                            echo '<option value="1">' . $this->lang->line('Yes') . '</option>
                            <option value="0">' . $this->lang->line('No') . '</option>'; ?>

                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_cat_name"><?php echo $this->lang->line('Email') ?></label>
                        <input type="email"
                               class="form-control margin-bottom  required" name="send" value="<?= $auto['url'] ?>"
                        >
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-12 sunbmit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$("#data_form").validate({
        ignore: [], 
        rules: {               
            email: { required: true },
        },
        messages: {
            email: "Select Transactions Email",
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
               text: "Do you want to update Email alert?",
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
                        url: baseurl + 'settings/misc_automail',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/misc_automail';
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

