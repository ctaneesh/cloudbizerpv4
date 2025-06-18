<article class="content-body">
    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-body">


            <form method="post" id="data_form" class="form-horizontal">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('productcategory') ?>"><?php echo $this->lang->line('Product Category') ?></a></li>
                        <li class="breadcrumb-item"><?php echo $this->lang->line('Add new') . '   ' . $this->lang->line('Sub') . ' ' . $this->lang->line('Category') ?></li>
                    </ol>
                </nav>
                <h4 class="card-title"><?php echo $this->lang->line('Add new') . '   ' . $this->lang->line('Sub') . ' ' . $this->lang->line('Category') ?></h4>
                
                <hr>

                <div class="form-group row">
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_cat"><?php echo $this->lang->line('Category') ?><span class="compulsoryfld">*</span></label>
                        <select name="cat_rel" class="form-control">
                            <?php
                            foreach ($cat as $row) {
                                $cid = $row['id'];
                                $title = $row['title'];
                                echo "<option value='$cid'>$title</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_catname"><?php echo $this->lang->line('Sub') . ' ' . $this->lang->line('Category Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Product Category Name"
                               class="form-control margin-bottom  required" name="product_catname">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="product_catname"><?php echo $this->lang->line('Descriptions') ?></label>
                        <input type="text" placeholder="Product Category Short Description"
                               class="form-control margin-bottom required" name="product_catdesc">
                    </div>
                   
             
                    <input type="hidden" value="1" name="cat_type">
                    <div class="col-sm-3 ">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom btn-crud mt-2"
                               value="<?php echo $this->lang->line('Create') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="productcategory/addcat" id="action-url">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>

<script>
     $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            product_catname: { required: true },
        },
        messages: {
            product_catname: "Enter Sub Category Name",   
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a new product subcategory?",
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
                        url: baseurl + 'productcategory/addcat',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'productcategory';
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