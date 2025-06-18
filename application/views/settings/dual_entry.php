<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->lang->line('Dual Entry') . ' & ' . $this->lang->line('Accounting') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <hr>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">
                    <div class="card card-block">


                        <!-- <p class="alert alert-danger">Please do not enable this feature without proper understanding of
                            dual entry accounting system.</p> -->

                        <div class="form-group row">
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="dual"><?php echo $this->lang->line('Dual Entry') ?></label>
                                <select name="dual" class="form-control">
                                    <option value="<?= $discship['key1'] ?>">
                                        *<?php if ($discship['key1']) echo $this->lang->line('Yes'); else  echo $this->lang->line('No') ?>
                                        *
                                    </option>
                                    <option value="1"><?php echo $this->lang->line('Yes') ?> </option>
                                    <option value="0"><?php echo $this->lang->line('No') ?></option>


                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="dual_inv"><?php echo $this->lang->line('Default') . ' ' . $this->lang->line('Invoice') . ' ' . $this->lang->line('Account') ?></label>
                                <select name="dual_inv" class="form-control">
                                    <option value="<?= $discship['key2'] ?>">*--Do not change--*</option>
                                    <?php foreach ($acclist as $row) {
                                        echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="dual_pur"><?php echo $this->lang->line('Default') . ' ' . $this->lang->line('Purchase Order') . ' ' . $this->lang->line('Account') ?></label>
                                <select name="dual_pur" class="form-control">
                                    <option value="<?= $discship['url'] ?>">*--Do not change--*</option>
                                    <?php foreach ($acclist as $row) {
                                        echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-12 submit-section responsive-text-right">
                                <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom" value="<?php echo $this->lang->line('Update') ?>"
                                       data-loading-text="Updating...">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#data_form").validate({
        ignore: [], 
        rules: {               
            dual: { required: true },
        },
        messages: {
            dual: "Enter Name",
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
               text: "Do you want to update Dual entry?",
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
                        url: baseurl + 'settings/dual_entry',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/dual_entry';
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

