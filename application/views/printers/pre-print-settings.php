<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-block">


            <form method="post"  class="card-body" name="preprint_form" id="preprint_form">

                <div class="card-header border-bottom">
                   <div class="row">
                    <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Pre Print Settings') ?></li>
                            </ol>
                        </nav>
                        <h4 class="card-title w-100"><?php echo $this->lang->line('Pre Print Settings') ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                   </div>
                </div>

                <div class="form-row">
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="measurement_unit"><?php echo $this->lang->line('Measurement Unit') ?><span class="compulsoryfld">*</span></label>
                        <select class="form-control" name="measurement_unit" id="measurement_unit">
                            <option value="in" <?php if($printer['measurement_unit']=="in"){ echo "selected"; } ?>>Inch</option>
                            <option value="px" <?php if($printer['measurement_unit']=="px"){ echo "selected"; } ?>>Pixel</option>
                            <option value="mm" <?php if($printer['measurement_unit']=="mm"){ echo "selected"; } ?>>Millimetre</option>
                            <option value="cm" <?php if($printer['measurement_unit']=="cm"){ echo "selected"; } ?>>Centimetre</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="page_width"><?php echo $this->lang->line('Page Width') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Page Width"  class="form-control margin-bottom required" name="page_width"  value="<?php echo $printer['page_width'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="page_height"><?php echo $this->lang->line('Page Height') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Page Height"  class="form-control margin-bottom required" name="page_height"  value="<?php echo $printer['page_height'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="header_height"><?php echo $this->lang->line('Header Height') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Header Height"  class="form-control margin-bottom required" name="header_height"  value="<?php echo $printer['header_height'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label"  for="footer_height"><?php echo $this->lang->line('Footer Height') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Footer Height"  class="form-control margin-bottom required" name="footer_height"  value="<?php echo $printer['footer_height'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="margin_left"><?php echo $this->lang->line('Margin Left') ?><span class="compulsoryfld">*</span></label>                        
                        <input type="number" placeholder="Margin Left"  class="form-control margin-bottom required" name="margin_left"  value="<?php echo $printer['margin_left'] ?>">
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="margin_right"><?php echo $this->lang->line('Margin Right') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Margin Right"  class="form-control margin-bottom required" name="margin_right"  value="<?php echo $printer['margin_right'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12 d-none">
                        <label class="col-form-label" for="items_per_page"><?php echo $this->lang->line('Items Displayed per Page') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Items Displayed per Page"  class="form-control margin-bottom required" name="items_per_page"  value="<?php echo $printer['items_per_page'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="row_height"><?php echo $this->lang->line('Item Row Height') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Item Row Height"  class="form-control margin-bottom required" name="row_height"  value="<?php echo $printer['row_height'] ?>">
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="bill_details_height"><?php echo $this->lang->line('Bill Details Section Height') ?><span class="compulsoryfld">*</span></label>
                        <input type="number" placeholder="Bill Details Section Height"  class="form-control margin-bottom required" name="bill_details_height"  value="<?php echo $printer['bill_details_height'] ?>">
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                    <label class=" col-form-label" for="measurement_unit"><?php echo $this->lang->line('Bill Details Displayed On') ?><span class="compulsoryfld">*</span></label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input mt-13" type="radio" name="bill_details" id="firstpage" value="First Page" <?php if($printer['bill_details']=="First Page"){ echo "checked"; } ?>>
                            <label class="col-form-label" for="firstpage">First Page Only</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input mt-13" type="radio" name="bill_details" id="everypage" value="All Pages"  <?php if($printer['bill_details']=="All Pages"){ echo "checked"; } ?>>
                            <label class="col-form-label" for="everypage">All Pages</label>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-2 col-md-4 col-sm-12 col-xs-12">
                    <label class=" col-form-label" for="measurement_unit"><?php echo $this->lang->line('Show Item Caption on All Pages') ?><span class="compulsoryfld">*</span></label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input mt-13" type="radio" name="display_item_labels" id="firstpage1" value="Yes" <?php if($printer['display_item_labels']=="Yes"){ echo "checked"; } ?>>
                            <label class="col-form-label" for="firstpage1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input mt-13" type="radio" name="display_item_labels" id="everypage1" value="No"  <?php if($printer['display_item_labels']=="No"){ echo "checked"; } ?>>
                            <label class="col-form-label" for="everypage1">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12 submit-section text-right mt-2">
                        <input type="submit" id="update-print-btn" class="btn btn-crud btn-primary btn-lg margin-bottom" value="Update" data-loading-text="Updating...">
                        <input type="hidden" value="<?php echo $printer['print_setting_number'] ?>" name="print_setting_number">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>
    </div>
<script>
    $(document).ready(function () {
        $("#preprint_form").validate({
            ignore: [],
            rules: {               
                measurement_unit: { required: true },
                header_height: { required: true },
                footer_height: { required: true },
                margin_left: { required: true },
                margin_right: { required: true },
            },
            messages: {
                measurement_unit: "Select Measurement Unit",
                header_height: "Enter Header Height",
                footer_height: "Enter Footer HEight",
                margin_left: "Enter Margin Left",
                margin_right: "Enter Margin Right",
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
    $("#update-print-btn").on("click", function (e) {
        e.preventDefault();
        $('#update-print-btn').prop('disabled', true);
        if ($("#preprint_form").valid()) {

            var formData = new FormData($("#preprint_form")[0]);
        
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to Update Pre-Print Settings?",
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
                        url: baseurl + 'printer/pre_print_settings',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) { 
                            if (typeof response === "string") {
                                response = JSON.parse(response.trim());
                            }
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#update-print-btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#update-print-btn').prop('disabled', false);
            $("#preprint_form").focus();            
            $('.alert-dismissible').removeClass('d-none');
        }
    });
</script>