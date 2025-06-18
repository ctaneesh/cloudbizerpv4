<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('clientgroup') ?>"><?php echo $this->lang->line('Client Group') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Edit Customer Group') ?></li>
                </ol>
            </nav>
            <h5><?php echo $this->lang->line('Edit Customer Group') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">


                    <input type="hidden" name="gid" value="<?php echo $group['id'] ?>">


                    <div class="form-group row">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="group_name"><?php echo $this->lang->line('Group Name') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder=" Name"  class="form-control margin-bottom  required" name="group_name"  value="<?php echo $group['title'] ?>">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"><?php echo $this->lang->line('Description') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" name="group_desc" class="form-control required" placeholder="0.00" aria-describedby="sizing-addon1" value="<?php echo $group['summary'] ?>">
                        </div>

                    </div>


                    <div class="form-group row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 submit-section responsive-text-right">
                            <input type="submit" id="submit-btn" class="btn btn-lg btn-primary margin-bottom"
                                   value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                            <input type="hidden" value="clientgroup/editgroupupdate" id="action-url">
                        </div>
                    </div>

            </div>
            </form>
        </div>
    </div>
</div>
<script>
     $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            group_name: { required: true },
            group_desc: { required: true },
        },
        messages: {
            group_name: "Group Name",  
            group_desc: "Group Description",  
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update customer group?",
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
                        url: baseurl + 'clientgroup/editgroupupdate',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'clientgroup';
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