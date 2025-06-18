<article class="content">
    <div class="card card-block">
        <div class="card-header border-bottom">
        
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('productcategory/warehouse') ?>"><?php echo $this->lang->line('Warehouses') ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Edit Product warehouse') ?></li>
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Edit Product warehouse') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
            <div class="card card-body">


                <input type="hidden" name="catid" id="store_id" value="<?php echo $warehouse['id'] ?>">


                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_cat_name">Warehouse Name<span class="compulsoryfld">*</span></label>
                        <input type="text"
                               class="form-control margin-bottom  required" name="product_cat_name"
                               value="<?php echo $warehouse['title'] ?>">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label">Description<span class="compulsoryfld">*</span></label>
                        <input type="text" name="product_cat_desc" class="form-control required"
                               aria-describedby="sizing-addon1" value="<?php echo $warehouse['extra'] ?>">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="lid"><?php echo $this->lang->line('Business Locations') ?><span class="compulsoryfld">*</span></label>
                        <select name="lid" class="form-control">
                            <option value='<?php echo $warehouse['loc'] ?>'><?php echo $this->lang->line('Do not change') ?></option>
                            <option value='0'><?php echo $this->lang->line('All') ?></option>
                            <?php
                            foreach ($locations as $row) {
                                $cid = $row['id'];
                                $acn = $row['cname'];
                                $holder = $row['address'];
                                echo "<option value='$cid'>$acn - $holder</option>";
                            }
                            ?>
                        </select>

                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="pay_cat"><?php echo $this->lang->line('Warehouse Type') ?><span class="compulsoryfld">*</span></label>                           
                            <select name="warehouse_type" id="warehouse_type" class="form-control">
                                <option value='Normal' <?php if($warehouse['warehouse_type']=='Normal') { echo "selected"; } ?>><?php echo $this->lang->line('Normal') ?></option>
                                <option value='Main' <?php if($warehouse['warehouse_type']=='Main') {  echo "selected";} ?>><?php echo $this->lang->line('Default') ?></option>                            
                            </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 mt-2 submit-section">
                        <input type="submit" id="warehouse_edit_btn" class="btn btn-primary btn-lg margin-bottom"
                               value="Update" data-loading-text="Updating...">
                        <input type="hidden" value="productcategory/editwarehouse" id="action-url">
                    </div>
                </div>

            </div>
        </form>
    </div>

</article>


<script>
    
 $( document ).ready(function() {
    $("#data_form").validate({
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {
            product_catname: { required: true },
            product_catdesc: { required: true },
            lid: { required: true },
            warehouse_type: { required: true },
        },
        messages: {
            product_name: "Enter Warehouse Name",
            product_code: "Enter Item Code",
            arabic_name: "Select Business Locations",
            warehouse_type:"Select warehouse type",
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
$("#warehouse_edit_btn").on("click", function(e) {
    e.preventDefault();
    $('#warehouse_edit_btn').prop('disabled', true);
    if ($("#data_form").valid()) {
        if( $("#warehouse_type").val()=='Main')
        {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseurl +'productcategory/check_deafult_warehouse_found_without_me',
                    data :{
                        store_id : $('#store_id').val(),
                        warehouse_type : $("#warehouse_type").val()
                    },
                    success: function(response) {
                        if(response.data==1)
                        {
                            Swal.fire({
                                    title: "Are you sure?",
                                    "text":"Do you want to overwrite the existing default warehouse?",
                                    icon: "question",
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, proceed!',
                                    cancelButtonText: "No - Cancel",
                                    reverseButtons: true,
                                    focusCancel: true
                            }).then((result) => {
                                    if (result.isConfirmed) {
                                    var form = $('#data_form')[0]; // Get the form element
                                    var formData = new FormData(form); // Create FormData object
                                    $.ajax({
                                        type: 'POST',
                                        url: baseurl +'productcategory/editwarehouse',
                                        data: formData,
                                            contentType: false, 
                                            processData: false,
                                        success: function(response) {
                                            window.location.href = baseurl + 'productcategory/warehouse'; 
                                        },
                                        error: function(xhr, status, error) {
                                                // Handle error
                                                console.error(xhr.responseText);
                                        }
                                    });
                                }
                                else{
                                    $('#warehouse_edit_btn').prop('disabled', false);
                                }
                            });
                        }
                        else{
                                Swal.fire({
                                    title: "Are you sure?",
                                    "text":"Do you want to update the warehouse?",
                                    icon: "question",
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, proceed!',
                                    cancelButtonText: "No - Cancel",
                                    reverseButtons: true,
                                    focusCancel: true
                            }).then((result) => {
                                    if (result.isConfirmed) {
                                    var form = $('#data_form')[0]; // Get the form element
                                    var formData = new FormData(form); // Create FormData object
                                    $.ajax({
                                        type: 'POST',
                                        url: baseurl +'productcategory/editwarehouse',
                                        data: formData,
                                            contentType: false, 
                                            processData: false,
                                        success: function(response) {
                                            window.location.href = baseurl + 'productcategory/warehouse'; 
                                        },
                                        error: function(xhr, status, error) {
                                                // Handle error
                                                console.error(xhr.responseText);
                                        }
                                    });
                                    }
                                    else{
                                    $('#warehouse_edit_btn').prop('disabled', false);
                                    }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
        }
        else{
            Swal.fire({
                        title: "Are you sure?",
                        "text":"Do you want to update the warehouse?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: "No - Cancel",
                        reverseButtons: true,
                        focusCancel: true
                }).then((result) => {
                        if (result.isConfirmed) {
                        var form = $('#data_form')[0]; // Get the form element
                        var formData = new FormData(form); // Create FormData object
                        $.ajax({
                            type: 'POST',
                            url: baseurl +'productcategory/editwarehouse',
                            data: formData,
                                contentType: false, 
                                processData: false,
                            success: function(response) {
                                window.location.href = baseurl + 'productcategory/warehouse'; 
                            },
                            error: function(xhr, status, error) {
                                    // Handle error
                                    console.error(xhr.responseText);
                            }
                        });
                        }
                        else{
                        $('#warehouse_edit_btn').prop('disabled', false);
                        }
                });
        }
     
    }
    else{
        $('.alert-dismissible').removeClass('d-none');
        $('#warehouse_edit_btn').prop('disabled', false);
    }
});
</script>