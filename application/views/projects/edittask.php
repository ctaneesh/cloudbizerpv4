<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
         <?php $prid = $task['rid']; ?>
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('projects/explore?id='.$prid) ?>"><?php echo $this->lang->line('Projects') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Edit') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Edit') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">                            
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                            
                </ul>
            </div>
        </div>
        <hr>
        <div class="card-body">
           

            <form method="post" id="data_form" class="form-horizontal">


                <div class="form-group row">
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Task Title"
                               class="form-control margin-bottom  required" name="name"
                               value="<?php echo $task['name'] ?>">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Status') ?></label>
                        <select name="status" class="form-control">
                            <?php echo '<option value="' . $task['status'] . '">--' . $task['status'] . '--</option>'; ?>
                            <option value='Due'>Due</option>
                            <option value='Done'>Done</option>
                            <option value='Progress'>Progress</option>

                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="pay_cat"><?php echo $this->lang->line('Priority') ?></label>
                        <select name="priority" class="form-control">
                            <?php echo '<option value="' . $task['priority'] . '">--' . $task['priority'] . '--</option>'; ?>
                            <option value='Low'>Low</option>
                            <option value='Medium'>Medium</option>
                            <option value='High'>High</option>
                            <option value='Urgent'>Urgent</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="edate"><?php echo $this->lang->line('Start Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required"
                               placeholder="Start Date" name="staskdate"
                               data-toggle="datepicker" autocomplete="false" value="<?php echo $task['start'] ?>">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="edate"><?php echo $this->lang->line('Due Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required"
                               placeholder="End Date" name="taskdate"
                               data-toggle="datepicker" autocomplete="false" value="<?php echo $task['duedate'] ?>">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="pay_cat"><?php echo $this->lang->line('Assign to') ?></label>
                        <select name="employee" class="form-control select-box">
                            <?php
                            echo '<option value="' . $task['eid'] . '">--' . $task['emp'] . '--</option>';
                            foreach ($emp as $row) {
                                $cid = $row['id'];
                                $title = $row['name'];
                                echo "<option value='$cid'>$title</option>";
                            }
                            ?>

                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="content"><?php echo $this->lang->line('Description') ?></label>
                        <textarea class="summernote"
                                  placeholder=" Note"
                                  autocomplete="false" rows="10"
                                  name="content"><?php echo $task['description'] ?></textarea>
                    </div>
                    <div class="col-12 submit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                               value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="projects/edittask" id="action-url">
                        <input type="hidden" value="<?php echo $task['id'] ?>" name="id">
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
               text: "Do you want to update this task?",
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
                        url: baseurl + 'projects/edittask',
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