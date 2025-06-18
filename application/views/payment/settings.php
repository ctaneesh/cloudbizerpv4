<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <form method="post" id="data_form" class="form-horizontal">
         <div class="card-header">
            <h4 class="card-title"> <?php echo $this->lang->line('Online Payment Settings') ?> 
            </h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">                    
                        <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                    </ul>
                </div>
            <hr>
        </div>

        <div class="card-body">

           
            <div class="form-group row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('Enable Online Payment for Invoices') ?></label>
                    <select class="form-control" name="enable">
                        <option value="<?php echo $online_pay['enable'] ?>">
                            --<?php if ($online_pay['enable'] == 1) {
                                echo $this->lang->line('Yes');
                            } else {
                                echo $this->lang->line('No');
                            } ?>--
                        </option>
                        <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="0"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('Enable Bank Payment Button') ?></label>
                    <select class="form-control" name="bank">
                        <option value="<?php echo $online_pay['bank'] ?>">
                            --<?php if ($online_pay['bank'] == 1) {
                                echo $this->lang->line('Yes');
                            } else {
                                echo $this->lang->line('No');
                            } ?>--
                        </option>
                        <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="0"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="account"><?php echo $this->lang->line('credit-online-payment') ?></label>
                    <select name="account" class="form-control">

                        <?php
                        echo '<option value="' . $online_pay['default_acid'] . '">--' . $online_pay['holder'] . ' / ' . $online_pay['acn'] . '--</option>';

                        foreach ($acclist as $row) {
                            echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable">POS : <?php echo $this->lang->line('Payment Account List') ?></label>
                    <select class="form-control" name="pos_list">
                        <option value="<?php echo $online_pay['bank'] ?>">
                            --<?php if (PAC) {
                                echo $this->lang->line('Yes');
                            } else {
                                echo $this->lang->line('No');
                            } ?>--
                        </option>
                        <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="0"><?php echo $this->lang->line('No') ?></option>
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="enable"><?php echo $this->lang->line('AutoDebitTransaction') ?></label>
                    <select class="form-control" name="auto_debit">
                        <option value="<?php echo $online_pay['bank'] ?>">
                            --<?php if ($current['key2']==1) {
                                echo $this->lang->line('Yes');
                            } else {
                                echo $this->lang->line('No');
                            } ?>--
                        </option>
                        <option value="1"><?php echo $this->lang->line('Yes') ?></option>
                        <option value="0"><?php echo $this->lang->line('No') ?></option>
                    </select>
                    <small>Auto Debit Transaction useful to generate due statements in some regions.</small>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-12 submit-section responsive-text-right"> 
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="paymentgateways/settings" id="action-url">
                </div>
            </div>

        </div>
    </form>

</div>

<script>
    $("#data_form").validate({
        ignore: [],
        rules: {  
            enable: { required: true },
        },
        messages: {
            enable: "Select Payment",
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
               text: "Do you want to update payment settings?",
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
                        url: baseurl + 'paymentgateways/settings',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'paymentgateways/settings';
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