<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="">


            <form method="post" id="data_form" class="card-body">
                <div class="card-header border-bottom">
                   <div class="row">
                    <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('printer') ?>"><?php echo $this->lang->line('List Printers'); ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Edit Printer') ?></li>
                            </ol>
                        </nav>
                        <h4 class="card-title w-100"><?php echo $this->lang->line('Edit Printer') ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                   </div>
                </div>
                <div class="form-group row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="p_name">Printer Name<span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Printer Name"
                               class="form-control margin-bottom  required" name="p_name"
                               value="<?php echo $printer['val1'] ?>">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="p_type">Printer Type</label>
                        <select class="form-control" name="p_type">
                            <option value="<?php echo $printer['val2'] ?>">--Keep
                                Current- <?php echo $printer['val2'] ?></option>
                            <option value="file">File Printer</option>
                            <option value="network">Network Printer</option>
                            <option value="windows">Windows Printer (USB)</option>
                            <option value="test">Test Dummy Printer</option>
                            <option value="server">CloudBiz REST Print Server</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="p_connect">Printer Connector<span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="Printer Connector"
                               class="form-control margin-bottom required" name="p_connect"
                               value="<?php echo $printer['val3'] ?>">
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="lid"><?php echo $this->lang->line('Business Locations') ?></label>                        
                           <select name="lid" class="form-control">
                            <option value="<?php echo $printer['val4'] ?>">- <?php $loc = location($printer['val4']);
                                echo $loc['cname']; ?>-
                            </option>
                            <?php
                            if (!$this->aauth->get_user()->loc) echo "<option value='0'>" . $this->lang->line('Default') . "</option>";
                            foreach ($locations as $row) {
                                $cid = $row['id'];
                                $acn = $row['cname'];
                                $holder = $row['address'];
                                echo "<option value='$cid'>$acn - $holder</option>";
                            }
                            ?>
                        </select>


                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label class="col-form-label"
                           for="lid">Printing Mode</label>
                        <select name="pmode" class="form-control">
                            <?php if ($printer['other']) echo "<option value='1' selected>--Advanced--</option>"; ?>
                            <option value='0'>Basic</option>
                            <option value='1'>Advanced</option>
                        </select>


                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12 submit-section responsive-text-right">
                        <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                               value="Update" data-loading-text="Adding...">
                        <input type="hidden" value="printer/edit" id="action-url">
                        <input type="hidden" value="<?php echo $printer['id'] ?>" name="p_id">
                    </div>
                </div>


            </form>
        </div>
    </div>
</article>

<script>
    $("#data_form").validate({
        ignore: [],
        rules: {      
            p_name: { required: true },
            p_connect: { required: true },
        },
        messages: {
            p_name: "Printer Name",  
            p_connect: "Printer Connector",  
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
               text: "Do you want to update this printer details?",
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
                        url: baseurl + 'printer/edit',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'printer';
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