<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('brand') ?>"><?php echo $this->lang->line('Brands') ?></a></li>
                    </ol>
                </nav>
            <h5><?php echo $this->lang->line('Edit')." ".$this->lang->line('Brand') ?></h5>
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


                    <input type="hidden" name="catid" value="<?php echo $brand['id'] ?>">


                    <div class="form-group row">

                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="product_cat_name"><?php echo $this->lang->line('Brand Name') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" class="form-control margin-bottom  required" name="brand_name"
                                   value="<?php echo $brand['brand_name'] ?>">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                            for="product_catname"><?php echo $this->lang->line('Status') ?></label>
                            <select name="status" id="status" class="form-control">
                                <option value="Enable" <?php if($brand['status']=='Enable'){ echo 'selected'; } ?>>Enable</option>
                                <option value="Disable" <?php if($brand['status']=='Disable'){ echo 'selected'; } ?>>Disable</option>
                            </select>
                        </div>
                        <input type="hidden" class="form-control margin-bottom  required" name="brand_id"
                        value="<?php echo $brand['id'] ?>">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-2">
                            <input type="submit" id="update_brand_btn" class="btn btn-primary btn-lg margin-bottom"
                                value="<?php echo $this->lang->line('Update')." ".$this->lang->line('Brand') ?>" data-loading-text="Adding...">
                        </div>
                    </div>


                </form>
            </div>

        </div>
        </div>


        <script>
    $(document).ready(function() {
   $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        rules: {
            brand_name: {required:true}
        },
        messages: {
            brand_name    : "Enter Brand Name",
        }
    }));
});
$("#update_brand_btn").on("click", function(e) {
    e.preventDefault();
    $('#update_brand_btn').prop('disabled', true);

    if ($("#data_form").valid()) {
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to Update brand?",
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
               var formData = $("#data_form").serialize(); 
               $.ajax({
                  type: 'POST',
                  url: baseurl +'brand/editbrand_action',
                  data: formData,
                  success: function(response) {
                     window.location.href = baseurl + 'brand'; 
                  },
                  error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                  }
               });
            }
            else{
               $('#update_brand_btn').prop('disabled', false);
            }
      });
    }
    else{
      $('#update_brand_btn').prop('disabled', false);
    }
});
</script>