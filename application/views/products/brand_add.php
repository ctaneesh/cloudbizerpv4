<article class="content-body">
    <?php       
    // if (($msg = check_permission($permissions)) !== true) {
    //     echo $msg;
    //     return;
    // }
    ?>
    <div class="card card-block">
            
           
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-body">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('brand') ?>"><?php echo $this->lang->line('Brands') ?></a></li>
                </ol>
            </nav>
            <form method="post" id="data_form" class="form-horizontal">

                <h5><?php echo $this->lang->line('Add New'); ?></h5>
                <hr>

                <div class="form-group row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                            for="product_catname"><?php echo $this->lang->line('Brand Name') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Brand Name" class="form-control margin-bottom" name="brand_name" autocomplete="off">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_catname"><?php echo $this->lang->line('Status') ?></label>
                        <select name="status" id="status" class="form-control">
                            <option value="Enable">Enable</option>
                            <option value="Disable">Disable</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-2">
                        <input type="submit" id="add_brand_btn" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Add')." ".$this->lang->line('Brand') ?>" data-loading-text="Adding...">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>

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
$("#add_brand_btn").on("click", function(e) {
    e.preventDefault();
    $('#add_brand_btn').prop('disabled', true);

    if ($("#data_form").valid()) {
      Swal.fire({
            title: "Are you sure?",
            // text: "Are you sure you want to update inventory? Do you want to proceed?",
            "text":"Do you want to create new brand?",
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
                  url: baseurl +'brand/addbrand_action',
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
               $('#add_brand_btn').prop('disabled', false);
            }
      });
    }
    else{
      $('#add_brand_btn').prop('disabled', false);
    }
});
</script>