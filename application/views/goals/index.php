<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title"><?php echo $this->lang->line('Set Goals') ?>
                <small>(in <?php echo $this->config->item('currency') ?>)</small>
            </h4>
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


                    <div class="form-group row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="income"><?php echo $this->lang->line('Income') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Income"
                                   class="form-control margin-bottom  required" name="income"
                                   value="<?php echo $goals['income'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="expense"><?php echo $this->lang->line('Expenses') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Expenses"
                                   class="form-control margin-bottom  required" name="expense"
                                   value="<?php echo $goals['expense'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="sales"><?php echo $this->lang->line('Sales') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Sales"
                                   class="form-control margin-bottom  required" name="sales"
                                   value="<?php echo $goals['sales'] ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="netincome"><?php echo $this->lang->line('Net Income') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Net Income"
                                   class="form-control margin-bottom  required" name="netincome"
                                   value="<?php echo $goals['netincome'] ?>">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-12 submit-section text-right">
                            <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                            <input type="hidden" value="tools/setgoals" id="action-url">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

<script>
    
 $("#data_form").validate({
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            income: { required: true },
            expense: { required: true },
            sales: { required: true },
            netincome: { required: true },
        },
        messages: {
            income: "Enter Income",
            expense: "Enter Expense",
            sales: "Enter Sales",
            netincome: "Enter Net Income"
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
               text: "Do you want to update set goals?",
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
                        url: baseurl + 'tools/setgoals',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'tools/setgoals';
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