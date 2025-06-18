<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->lang->line('Discount') . ' & ' . $this->lang->line('Shipping') ?></h4>
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


                        <div class="form-group row">
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="discstatus"><?php echo $this->lang->line('Discount') ?></label>
                                <select name="discstatus" class="form-control">
                                    <option value="<?= $discship['key1'] ?>">*<?= $discship['other'] ?>*</option>
                                    <option value="%"><?php echo $this->lang->line('% Discount') . ' ' . $this->lang->line('After TAX') ?> </option>
                                    <option value="flat"><?php echo $this->lang->line('Flat Discount') . ' ' . $this->lang->line('After TAX') ?></option>
                                    <option value="b_p"><?php echo $this->lang->line('% Discount') . ' ' . $this->lang->line('Before TAX') ?></option>
                                    <option value="bflat"><?php echo $this->lang->line('Flat Discount') . ' ' . $this->lang->line('Before TAX') ?></option>

                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="shiptax_type"><?php echo $this->lang->line('Shipping') ?><?php echo $this->lang->line('Tax') ?></label>
                                <select name="shiptax_type" class="form-control">
                                    <option value="<?= $discship['url'] ?>">
                                        *<?php echo $this->lang->line('Do not change') ?>*
                                    </option>
                                    <option value="incl"><?php echo $this->lang->line('Inclusive') ?></option>
                                    <option value="excl">Exclusive</option>

                                    <option value="off"><?php echo $this->lang->line('Off') ?></option>

                                </select>

                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="shiptax_rate"><?php echo $this->lang->line('Shipping') ?> <?php echo $this->lang->line('Tax') ?>
                                % <?php echo $this->lang->line('Rate') ?></label>
                                <input type="text" placeholder="Shipping Tax Rate"
                                       class="form-control margin-bottom" name="shiptax_rate"
                                       value="<?= $discship['key2'] ?>">
                                       <small>Tax Rate will overridden if you create a Tax Slab.</small>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <label class="col-form-label"
                                   for="shipping_return"><?php echo $this->lang->line('Shipping Refundable') ?></label>
                                   <select name="shipping_return" id="shipping_return" class="form-control">
                                   <!-- <option value=""></option> -->
                                    <option value="No"><?php echo $this->lang->line('No') ?></option>

                                </select>
                            </div>
                            
                        </div>


                        <div class="form-group row">
                            <div class="col-12 submit-section responsive-text-right">
                                <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                                       value="<?php echo $this->lang->line('Update') ?>"
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
            discstatus: { required: true },
        },
        messages: {
            discstatus: "Select Discount",
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
               text: "Do you want to update discount & shipping?",
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
                        url: baseurl + 'settings/discship',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'settings/discship';
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

