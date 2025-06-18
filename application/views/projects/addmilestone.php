<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('projects/explore?id='.$prid) ?>"><?php echo $this->lang->line('Projects') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Add Milestone'); ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Add Milestone'); ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">                            
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                            
                </ul>
            </div>
        </div>
        <hr>
        <div class="card card-body">


            <form method="post" id="data_form" class="form-horizontal">


                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Milestone Title"
                               class="form-control margin-bottom  required" name="name">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="edate"><?php echo $this->lang->line('Color') ?></label>
                        <input id="color" name="color" type="color" class="form-control input-md"
                               value="#0b97f4">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="edate"><?php echo $this->lang->line('Start Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required"
                               placeholder="Start Date" name="staskdate"
                               data-toggle="datepicker" autocomplete="false">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="edate"><?php echo $this->lang->line('Due Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required"
                               placeholder="End Date" name="taskdate"
                               data-toggle="datepicker" autocomplete="false">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="content"><?php echo $this->lang->line('Description') ?></label>
                        <textarea class="summernote"
                                  placeholder=" Note"
                                  autocomplete="false" rows="10" name="content"></textarea>
                    </div>
                    <div class="col-12 submit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="projects/addmilestone" id="action-url">
                        <input type="hidden" value="<?php echo $prid ?>" name="project">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>
<script type="text/javascript">

    $(function () {
        $('.select-box').select2();

        $('.summernote').summernote({
            height: 100,
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
    $("#data_form").validate({
        ignore: [],
        rules: {      
            name: { required: true },
        },
        messages: {
            name: "Enter Task Name",
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
               text: "Do you want to create new a milestone?",
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
                        url: baseurl + 'projects/addmilestone',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'projects/explore?id=<?= $prid ?>';
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


</script>