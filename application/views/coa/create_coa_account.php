<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <h5 class="title"> <?php echo $this->lang->line('New Chart of Account') ?> </h5>
        <hr>
        <div class="formborder">
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">                
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Name') ?>" class="form-control margin-bottom  required" name="coa_type_id" value="<?php echo $selling_price_perc ?>">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Account Code') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Account Code') ?>" class="form-control margin-bottom  required" name="typename" value="<?php echo $whole_price_perc ?>">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Account Type') ?><span class="compulsoryfld">*</span></label>
                         
                        <?php
                            echo '<select name="coa_type" id="coa_type" class="form-control">';
                            echo '<option value="">Select Type</option>';
                            foreach ($accountheaders as $parentItem) {
                                $coaHeaderId = $parentItem['coa_header_id'];
                                $coaHeader = $parentItem['coa_header'];
                                if (isset($child[$coaHeaderId])) {
                                    echo '<optgroup label="' . htmlspecialchars($coaHeader) . '">';
                                    foreach ($child[$coaHeaderId] as $childItem) {
                                        $childId = $childItem['id'];
                                        $typename = $childItem['typename'];
                                        echo '<option value="' . htmlspecialchars($childId) . '">' . htmlspecialchars($typename) . '</option>';
                                    }
                    
                                    echo '</optgroup>';
                                }
                            }
                            echo '</select>';
                            ?>


                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Parent Account') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Account Code') ?>" class="form-control margin-bottom  required" name="typename" value="<?php echo $whole_price_perc ?>">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Note') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Account Code') ?>" class="form-control margin-bottom  required" name="typename" value="<?php echo $whole_price_perc ?>">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-2">
                        <input type="submit" id="account-type-btn" class="btn btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        
                            <!-- <input type="hidden" value="productpricing/edit" id="action-url"> -->
                        <!-- <input type="hidden" value="<?php echo $id ?>" name="id"> -->
                    </div>
                </div>
            </form>
        </div>

       
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#data_form").validate({
            ignore: [],
            rules: {               
                coa_type_id: { required: true },
                typename: { required: true },
                coa_header_id: { required: true }
            },
            messages: {
                coa_type_id: "Enter Chart of Account Type ID",
                typename: "Enter Chart of Account Type Name",
                coa_header_id: "Select Chart of Account Header"
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
   

    });

    $('#account-type-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#account-type-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create a new account type?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: "No - Cancel",
                reverseButtons: true,  
                focusCancel: true,      
                allowOutsideClick: false,  // Disable outside click
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: baseurl + 'coaaccounttypes/addeditaction', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            location.reload();
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#account-type-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#account-type-btn').prop('disabled', false);
        }
    });
</script>