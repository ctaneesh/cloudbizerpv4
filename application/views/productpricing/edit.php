<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">


        <form method="post" id="data_form" class="form-horizontal">

            <h4 class="card-title"><?php echo $this->lang->line('Edit') ?></h4>
            <hr>

            <div class="form-group row">                
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Selling Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Selling Price" class="form-control margin-bottom  required" name="selling_price_perc" value="<?php echo $selling_price_perc ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Wholesale Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Wholesale Price" class="form-control margin-bottom  required" name="whole_price_perc" value="<?php echo $whole_price_perc ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Web Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Web Price" class="form-control margin-bottom  required" name="web_price_perc" value="<?php echo $web_price_perc ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="address"><?php echo $this->lang->line('Minimum Price') ?><span class="compulsoryfld">*</span> (%)</label>
                    <input type="number" placeholder="Minimum Price" class="form-control margin-bottom  required" name="price_perc" value="<?php echo $price_perc ?>">
                </div> 
            </div>

            <div class="form-group row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section responsive-text-right">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                    <input type="hidden" value="productpricing/edit" id="action-url">
                    <input type="hidden" value="<?php echo $id ?>" name="id">
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $("#data_form").validate({
        ignore: [],
        rules: {  
            selling_price_perc: { required: true },
            whole_price_perc: { required: true },
            web_price_perc: { required: true },
            price_perc: { required: true },
        },
        messages: {
            selling_price_perc: "Enter Selling Price",
            whole_price_perc: "Enter Wholesale Price",
            web_price_perc: "Enter Web Price",
            price_perc: "Enter Minimum Price",
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
               text: "Do you want to update pricing percentage?",
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
                        url: baseurl + 'productpricing/edit',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'productpricing';
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