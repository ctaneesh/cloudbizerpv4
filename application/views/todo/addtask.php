
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content-body">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('tools/todo') ?>"><?php echo $this->lang->line('Tasks') ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Add Task') ?></li>
                  
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Add Task') ?> </h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">
                    <div class="form-row">
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Task Title"
                                   class="form-control margin-bottom  required" name="name">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="name"><?php echo $this->lang->line('Status') ?></label>
                            <select name="status" class="form-control">
                                <?php echo "<option value='Due'>" . $this->lang->line('Due') . "</option>
                            <option value='Done'>" . $this->lang->line('Done') . "</option>
                            <option value='Progress'>" . $this->lang->line('Progress') . "</option>";
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">                            
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('Priority') ?></label>
                            <select name="priority" class="form-control">
                                <option value='Low'>Low</option>
                                <option value='Medium'>Medium</option>
                                <option value='High'>High</option>
                                <option value='Urgent'>Urgent</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="edate"><?php echo $this->lang->line('Start Date') ?><span class="compulsoryfld">*</span></label>
                            <input type="date" class="form-control required" placeholder="Start Date" name="staskdate" autocomplete="false">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="edate"><?php echo $this->lang->line('Due Date') ?><span class="compulsoryfld">*</span></label>
                            <input type="date" class="form-control required"
                                   placeholder="End Date" name="taskdate"
                                   autocomplete="false">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">                            
                        <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('Assign to') ?></label>
                            <select name="employee" class="form-control select-box">
                                <?php
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
                                  autocomplete="false" rows="10" name="content"></textarea>
                        </div>
                        <div class="col-12 submit-section text-right">                            
                            <input type="submit" id="submit-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                                   value="<?php echo $this->lang->line('Add Task') ?>" data-loading-text="Adding...">
                            <input type="hidden" value="tools/save_addtask" id="action-url">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
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

    $("#data_form").validate($.extend(true, {}, globalValidationOptions, {
        ignore: [],
        rules: {      
            name: { required: true },
            staskdate: { required: true },
            taskdate: { required: true },
        },
        messages: {
            name: "Enter Task Name",
            staskdate: "Enter Start Date",
            taskdate: "Enter Due Date",
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to add a new task?",
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
                        url: baseurl + 'tools/save_addtask',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'tools/todo';
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