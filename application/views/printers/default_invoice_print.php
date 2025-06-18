<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card card-block">


            <form method="post"  class="card-body" name="preprint_form" id="preprint_form">

                <div class="card-header border-bottom">
                   <div class="row">
                    <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Default Invoice Print') ?></li>
                            </ol>
                        </nav>
                        <h4 class="card-title w-100"><?php echo $this->lang->line('Default Invoice Print') ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                   </div>
                </div>

                <div class="form-row">
                    <!-- <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-xs-12"> -->
                        <label class="col-12 col-form-label" for="measurement_unit"><?php echo $this->lang->line('Default Invoice Print') ?><span class="compulsoryfld">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input mt-13" type="radio" name="printer_type" id="regularprint" value="Regular Print" <?php if($default_print=="Regular Print"){ echo "checked"; } ?>>
                            <label class="col-form-label" for="regularprint">Regular (Laser)  Print</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input mt-13" type="radio" name="printer_type" id="dotmatrix" value="Dot Matrix Print"  <?php if($default_print=="Dot Matrix Print"){ echo "checked"; } ?>>
                            <label class="col-form-label" for="dotmatrix">Dot Matrix Print</label>
                        </div>
               
                    <div class="col-12 submit-section mt-2">
                        <input type="submit" id="update-print-btn" class="btn btn-primary btn-lg margin-bottom" value="Deafult Print" data-loading-text="Updating...">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>

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
               text: "Do you want to Update Default Print Type?",
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
                        url: baseurl + 'printer/default_invoice_print',
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