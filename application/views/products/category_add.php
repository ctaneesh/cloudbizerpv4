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
                        <li class="breadcrumb-item"><?php echo $this->lang->line('Add new') . ' ' . $this->lang->line('Category') ?></li>
                    </ol>
                </nav>
                <h4 class="card-title"><?php echo $this->lang->line('Add New Product Category') ?></h4>
                <hr>
                    <div class="row">
                        <!-- ----------------------------- Left Section ------------- -->
                         <div class="col-lg-4">
                             <!-- <div id="category_tree">
                                <h5><b>Categories</b></h5><hr>
                                <div id="tree"></div>
                            </div> -->
                            <div id="tree-container">
                                <h5><b><?php echo $this->lang->line('Categories') ?></b></h5><hr>
                                <div id="category_tree"></div>
                            </div>
                         </div>
                        <!-- ----------------------------- Left Section ------------- -->
                        <!-- ------------------------Right section ------------------ -->
                         <div class="col-lg-8">
                             <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink active show" id="base-generaltab" data-toggle="tab" aria-controls="generaltab" href="#generaltab" role="tab" aria-selected="true" title="General"><?php echo $this->lang->line('General') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink" id="base-datatab" data-toggle="tab" aria-controls="datatab" href="#datatab" role="tab" aria-selected="false" title="Product Pricing"><?php echo $this->lang->line('Data') ?></a>
                                </li>              
                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <div class="tab-pane active show" id="generaltab" role="tabpanel" aria-labelledby="base-generaltab">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link breaklink active show" id="base-englishtab" data-toggle="tab" aria-controls="englishtab" href="#englishtab" role="tab" aria-selected="true" title="General"><?php echo $this->lang->line('English') ?></a>
                                        </li>
                                        <li class="nav-item arabictabs">
                                            <a class="nav-link breaklink" id="base-arabictab" data-toggle="tab" aria-controls="arabictab" href="#arabictab" role="tab" aria-selected="false" title="Product Pricing"><?php echo $this->lang->line('Arabic') ?></a>
                                        </li>                
                                    </ul>
                                    <div class="tab-content px-1 pt-1">
                                        <div class="tab-pane active show" id="englishtab" role="tabpanel" aria-labelledby="base-englishtab">
                                            <div class="form-row">
                                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                        <input type="hidden" name="language_id" value="1">
                                                        <label class="col-form-label" for="product_catname"><?php echo $this->lang->line('Category Name') ?><span class="compulsoryfld">*</span></label>
                                                        <input type="text" placeholder="Product Category Name" class="form-control margin-bottom  required" name="product_catname">
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                        <label class="col-form-label"
                                                        for="description"><?php echo $this->lang->line('Descriptions') ?><span class="compulsoryfld">*</span></label>
                                                        <textarea  class="form-textarea margin-bottom required summernote1" name="description" id="description"></textarea>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                        <div class="tab-pane arabictabs" id="arabictab" role="tabpanel" aria-labelledby="base-arabictab">
                                            <div class="form-row">
                                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                        <input type="hidden" name="language_id1" value="2">
                                                        <label class="col-form-label" for="product_catname1"><?php echo $this->lang->line('Category Name') ?></label>
                                                        <input type="text" placeholder="Product Category Name" class="form-control margin-bottom" name="product_catname1">
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                        <label class="col-form-label"
                                                        for="description"><?php echo $this->lang->line('Descriptions') ?></label>
                                                        <textarea  class="form-textarea margin-bottom summernote1" name="description1" id="description1"></textarea>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="datatab" role="tabpanel" aria-labelledby="base-datatab">
                                        <div class="form-row">
                                                <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                                     <input type="text" name="parent_id" id="parent_id">
                                                    <label class="col-form-label row col-12" for="product_catname1"><?php echo $this->lang->line('Select Warehouse') ?></label>
                                                        <select name="store_id[]" id="store_id" class="form-control" multiple="multiple">
                                                            <?php
                                                                if ($stores) {
                                                                    $first = true;
                                                                    foreach ($stores as $store) {
                                                                        $selected = $first ? 'selected' : '';
                                                                        echo '<option value="' . $store['store_id'] . '" ' . $selected . '>' . $store['store_name'] . '</option>';
                                                                        $first = false;
                                                                    }
                                                                }
                                                            ?>                   
                                                        </select>
                                                </div>
                                                
                                            
                                            
                                        </div>
                                </div>
                            </div>
                             <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 submit-section responsive-text-right">
                                <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom btn-crud mt-2"
                                    value="<?php echo $this->lang->line('Add Category') ?>" data-loading-text="Adding...">
                                <input type="hidden" value="productcategory/addcat" id="action-url">
                                <input type="hidden" value="0" name="cat_type">
                            </div>
                         </div>
                        <!-- ------------------------Right section ------------------ -->
                    </div>
            </form>
        </div>
    </div>
</article>
<script>

    $(function() {
      $('#category_tree').jstree({
            'core': {
                'data': {
                    'url': '<?= base_url("Productcategory/get_category_tree") ?>',
                    'dataType': 'json'
                },
                'themes': {
                    'dots': false, // Remove dotted connecting lines
                    'stripes': false // Optional: remove alternating stripes if present
                }
            }
        });

        // Remove any remaining focus outlines
        $('#category_tree').on('ready.jstree', function() {
            $('#category_tree').find('.jstree-anchor').css('outline', 'none');
        });

        // Your node selection handler
        $('#category_tree').on('select_node.jstree', function(e, data) {
            $("#parent_id").val(data.node.id);
        });
    });
   $("#store_id").select2({
        placeholder: "Tyep Store/Warehouse", 
            allowClear: true,
            width: '100%'
    });
     $(function () {
        $('.summernote').summernote({
            height: 100,
            tooltip:false,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });
    });
     $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            product_catname: { required: true },
            product_catdesc: { required: true },
        },
        messages: {
            product_catname: "Enter Category Name",  
            group_desc: "Enter Category Description",  
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to create a new product category?",
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
