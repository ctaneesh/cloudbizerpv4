<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <form method="post" id="data_form" class="form-horizontal">
            <div class="card-header border-bottom">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('customers') ?>"><?php echo $this->lang->line('Customers') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Change Customer Password') ?></li>
                        </ol>
                    </nav>
                    <h4 class="card-title"><?php echo $this->lang->line('Change Customer Password');  ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
             <div class="mt-2 ml-2 pb-2">
                
                
                <input type="hidden" name="id" value="<?php echo $customer['customer_id'] ?>">


                <div class="form-row">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="email">Email<span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="email" class="form-control margin-bottom  required" name="email" value="<?php echo $customer['email'] ?>" readonly>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="password"><?php echo $this->lang->line('Password') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" class="form-control required" name="password" placeholder="Password">
                    </div>
                
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 submit-section responsive-text-right mt-2">
                        <input type="submit" id="submit-btn" class="btn btn-lg btn-primary margin-bottom" value="Change Password" data-loading-text="Updating...">
                        <input type="hidden" value="customers/changepassword" id="action-url">
                    </div>
                </div>

            </div>
        </form>
    </div>
</article>
<script>
$("#data_form").validate($.extend(true, {}, globalValidationOptions, {
    ignore: [],
    rules: {
        password: { required: true }
    },
    messages: {
        name: "Enter Password"
    }
}));

 $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update password?",
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
                        url: baseurl + 'customers/changepassword',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            location.reload();
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