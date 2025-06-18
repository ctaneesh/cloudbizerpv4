<script src="<?php echo assets_url(); ?>assets/portjs/bootstrap-timepicker.min.js" type="text/javascript"></script>
<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('employee/attendances') ?>"><?php echo $this->lang->line('Attendances') ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Attendance') ?></li>
                  
                </ol>
            </nav>
            <h5 class="card-title"><?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Attendance') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="card card-block">


            <form method="post" id="data_form" class="card-body">

                <div class="form-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Employees') ?><span class="compulsoryfld">*</span></label>
                        <select name="employee[]" class="form-control required select-box" multiple="multiple" required>
                            <?php
                            foreach ($emp as $row) {
                                $cid = $row['id'];
                                $title = $row['name'];
                                echo "<option value='$cid'>$title</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="from"><?php echo $this->lang->line('Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control b_input required" placeholder="Start Date" name="adate" data-toggle="datepicker" autocomplete="false">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="todate"><?php echo $this->lang->line('From') ?></label>
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" class="form-control input-small timepicker1" name="from">
                            <span class="input-group-addon"><i class="icon-clock"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="todate"><?php echo $this->lang->line('To') ?></label>
                        <div class="input-group bootstrap-timepicker timepicker">
                            <input type="text" class="form-control input-small timepicker2" name="to">
                            <span class="input-group-addon"><i class="icon-clock"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="note"><?php echo $this->lang->line('Note') ?></label>
                        <input type="text" placeholder="Note" class="form-control margin-bottom b_input" name="note">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submit-section mt-1 responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"  value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        <input type="hidden" value="employee/attendance" id="action-url">
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.timepicker1').timepicker('setTime', '09:00 AM');
    $('.timepicker2').timepicker('setTime', '05:00 PM');
    $('.select-box').select2();

    
    $("#data_form").validate({
        ignore: [],
        rules: {      
            adate: { required: true },
            staskdate: { required: true },
            taskdate: { required: true },
        },
        messages: {
            adate: "Enter Date",
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
        let selectedEmployees = $('select[name="employee[]"]').val();
        if (!selectedEmployees || selectedEmployees.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Employee',
                text: 'Please select at least one employee before submitting.',
            });
            $('#submit-btn').prop('disabled', false);
            return;
        }

        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to add attendence?",
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
                        url: baseurl + 'employee/attendance',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'employee/attendances';
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