<article class="content">
    <div class="card card-block">
        
        <div class="card-header border-bottom">
            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('productcategory/warehouse') ?>"><?php echo $this->lang->line('Warehouses') ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Add New Product Warehouse') ?></li>
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Add New Product Warehouse') ?></h5>
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
            <form method="post" id="data_form" class="card-body">
                <div class="form-group row">
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Store/Warehouse Name" class="form-control margin-bottom  required" name="store_name" id="store_name">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Store Owner') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Name of the store owner" class="form-control margin-bottom  required" name="store_owner" id="store_owner">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Email') ?><span class="compulsoryfld">*</span></label>
                        <input type="email" placeholder="Email" class="form-control margin-bottom  required" name="store_email" id="store_email">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Phone') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Contact Number" class="form-control margin-bottom  required" name="store_phone" id="store_phone">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Phone2') ?></label>
                        <input type="text" placeholder="Contact Number" class="form-control margin-bottom" name="store_phone2" id="store_phone2">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Fax') ?></label>
                        <input type="text" placeholder="Fax" class="form-control margin-bottom" name="store_fax" id="store_fax">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('City') ?></label>
                        <input type="text" placeholder="City" class="form-control margin-bottom" name="city" id="city">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('State') ?></label>
                        <input type="text" placeholder="State" class="form-control margin-bottom" name="state" id="state">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Country') ?></label>
                        <select name="country_id" id="country_id" class="form-control">
                            <option value=''>Select Country</option>
                            <?php
                            if ($countries) {
                                foreach ($countries as $country) {
                                    echo '<option value="' . $country['id'] . '">' . $country['name'] ." - ".$country['code']. '</option>';
                                }
                            }

                            ?>                          
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_owner"><?php echo $this->lang->line('Currency') ?></label>
                          <select name="currency_id" id="currency_id" class="form-control">
                               <option value=''>Select Currency</option>
                                <?php
                                if ($countries) {
                                    foreach ($currencies as $currency) {
                                        echo '<option value="' . $currency['id'] . '">' . $currency['code'] ."-".$currency['symbol']. '</option>';
                                    }
                                }

                                ?>                   
                        </select>
                    </div>

                    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_address"><?php echo $this->lang->line('Store Address') ?><span class="compulsoryfld">*</span></label>
                            <textarea name="store_address" id="store_address" class="form-textarea margin-bottom required"  placeholder="Store Address"></textarea>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="store_address"><?php echo $this->lang->line('Store Address 2') ?></label>
                            <textarea name="store_address2" id="store_address2" class="form-textarea margin-bottom"  placeholder="Store Address"></textarea>
                    </div>
                    
                    
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="pay_cat"><?php echo $this->lang->line('Warehouse Type') ?><span class="compulsoryfld">*</span></label>                           
                            <select name="warehouse_type" id="warehouse_type" class="form-control">
                                <option value='Normal'><?php echo $this->lang->line('Normal') ?></option>
                                <option value='Main'><?php echo $this->lang->line('Default') ?></option>                            
                            </select>
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section mt-2 responsive-text-right"><hr>
                        <input type="submit" id="warehouse-add-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="productcategory/addwarehouse" id="action-url">
                    </div>
                </div>


            </form>
    </div>
</article>

<script>
    
 $( document ).ready(function() {
     $("#country_id").select2({
      placeholder: "Select Country",
    });
    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {
            store_name: { required: true },
            store_owner: { required: true },
            store_address: { required: true },
            store_email: { required: true },
            store_phone: { required: true },
            warehouse_type: { required: true },
        },
        messages: {
            product_name: "Enter Warehouse Name",
            product_code: "Enter Item Code",
            arabic_name: "Select Business Locations",
            warehouse_type:"Select warehouse type",
        }
    }));

});  
$("#warehouse-add-btn").on("click", function(e) {
    e.preventDefault();
    $('#warehouse-add-btn').prop('disabled', true);

    if ($("#data_form").valid()) {
        if( $("#warehouse_type").val()=='Main')
        {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: baseurl +'productcategory/check_deafult_warehouse_found',
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
                                    url: baseurl +'productcategory/addwarehouse',
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
                                $('#warehouse-add-btn').prop('disabled', false);
                            }
                        });
                    }
                    else{
                        Swal.fire({
                                title: "Are you sure?",
                                "text":"Do you want to create new warehouse?",
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
                                    url: baseurl +'productcategory/addwarehouse',
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
                                $('#warehouse-add-btn').prop('disabled', false);
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
                    "text":"Do you want to create new warehouse?",
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
                        url: baseurl +'productcategory/addwarehouse',
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
                    $('#warehouse-add-btn').prop('disabled', false);
                    }
            });
        }
           
     
    }
    else{
        $('.alert-dismissible').removeClass('d-none');
        $('#warehouse-add-btn').prop('disabled', false);
    }
});
</script>