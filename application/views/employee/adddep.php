
<article class="content">
    <?php       
    if (($msg = check_permission($permissions)) !== true) {
        echo $msg;
        return;
    }
    ?>
    <div class="card card-block">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('employee/departments') ?>"><?php echo $this->lang->line('Departments') ?></a></li>
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Department') ?></li>
                  
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Add') . ' ' . $this->lang->line('Department') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card card-block">          
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">

                  

                    <div class="form-group row">
                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="note"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Department Name" class="form-control margin-bottom b_input required" name="name">
                        </div>
                  
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 submit-section">
                            <input type="submit" id="submit-btn" class="btn btn-crud btn-primary btn-lg margin-bottom mt-32px"
                                value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                            <input type="hidden" value="employee/adddep" id="action-url">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</article>

<script>
    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            name: { required: true },
        },
        messages: {
            adate: "Enter Department Name",
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
       
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to add a new department?",
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
                        url: baseurl + 'employee/adddep',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'employee/departments';
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