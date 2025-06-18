<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('locations') ?>">locations</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Business Location') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Business Location') ?></h4>
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


                    <div class="form-group row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="name"><?php echo $this->lang->line('Name') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Name" class="form-control margin-bottom  required"
                                name="name" value="<?php echo $cname ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                for="address"><?php echo $this->lang->line('Address') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="Address" class="form-control margin-bottom  required"
                                name="address" value="<?php echo $address ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="city"><?php echo $this->lang->line('City') ?><span class="compulsoryfld">*</span></label>
                            <input type="text" placeholder="City" class="form-control margin-bottom  required"
                                name="city" value="<?php echo $city ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="region"><?php echo $this->lang->line('Region') ?></label>
                            <input type="text" placeholder="Region" class="form-control margin-bottom" name="region"
                                value="<?php echo $region ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                for="country"><?php echo $this->lang->line('Country') ?></label>
                            <input type="text" placeholder="Country" class="form-control margin-bottom" name="country"
                                value="<?php echo $country ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                for="postbox"><?php echo $this->lang->line('Postbox') ?></label>
                            <input type="text" placeholder="postbox" class="form-control margin-bottom" name="postbox"
                                value="<?php echo $postbox ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="phone"><?php echo $this->lang->line('Phone') ?></label>
                            <input type="text" placeholder="Phone" class="form-control margin-bottom" name="phone"
                                value="<?php echo $phone ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="email"><?php echo $this->lang->line('Email') ?></label>
                            <input type="text" placeholder="Email" class="form-control margin-bottom" name="email"
                                value="<?php echo $email ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label" for="tax_id"><?php echo $this->lang->line('TAX ID') ?></label>
                            <input type="text" placeholder="tax_id" class="form-control margin-bottom" name="tax_id"
                                value="<?php echo $tax_id ?>">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                for="tax_id"><?php echo $this->lang->line('Default') ?><?php echo $this->lang->line('Warehouse') ?></label>
                            <select name="wid" class="selectpicker form-control">
                                <?php echo '<option value="' . $ware . '" selected>' . $this->lang->line('Do not change') . '</option>';
                                echo $this->common->default_warehouse();
                                echo '<option value="0">' . $this->lang->line('All') ?></option><?php foreach ($warehouse as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
                                } ?>

                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                for="cur_id"><?php echo $this->lang->line('Payment Currency client') ?></label>
                            <select name="cur_id" class="selectpicker form-control">
                                <option value="0">Default</option>
                                <?php foreach ($currency as $row) {
                                    if ($cur == $row['id']) echo '<option value="' . $row['id'] . '" selected>--' . $row['symbol'] . ' (' . $row['code'] . ')--</option>';
                                    echo '<option value="' . $row['id'] . '">' . $row['symbol'] . ' (' . $row['code'] . ')</option>';
                                } ?>

                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                                for="account"><?php echo $this->lang->line('credit-online-payment') ?></label>
                            <select name="account_v" class="form-control">

                                <?php
                                echo '<option value="' . $online_pay['default_acid'] . '" selected>--' . $online_pay['holder'] . ' / ' . $online_pay['acn'] . '--</option>';
                                foreach ($accounts as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <label class="col-form-label"><?php echo $this->lang->line('Company Logo') ?></label>
                            <!-- The container for the uploaded files -->
                            <table id="files" class="files">
                                <tr>
                                    <td>
                                        <a data-url="<?php echo base_url() ?>locations/file_handling?op=delete&name=<?php echo $logo ?>"
                                            class="aj_delete"><i class="btn-danger btn-sm icon-trash-a"></i>
                                            <?php echo $logo ?> </a><img style="max-height:150px;"
                                            src="<?php echo base_url() ?>userfiles/company/<?php echo $logo ?>">
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Select files...</span>
                                <!-- The file input field used as target for the file upload widget -->
                                <input id="fileupload" type="file" name="files[]">
                            </span>
                            <br>
                            <pre>Allowed: gif, jpeg, png</pre>
                            <br>
                            <!-- The global progress bar -->
                            <div id="progress" class="progress d-none">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-1  text-right submit-section">
                                <input type="submit" id="location-edit-btn" class="btn btn-primary btn-lg margin-bottom"
                                    value="<?php echo $this->lang->line('Edit') ?>" data-loading-text="Adding...">
                                <input type="hidden" value="locations/edit" id="action-url">
                                <input type="hidden" value="<?php echo $id ?>" name="id">
                            </div>
                        </div>
                        <input type="hidden" name="image" id="image" value="<?php echo $logo ?>">
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js'); ?>"></script>
    <script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
    <script>
    /*jslint unparam: true */
    /*global window, $ */
    $(function() {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '<?php echo base_url() ?>locations/file_handling';
        $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                formData: {
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash
                },
                done: function(e, data) {
                    var img = 'default.png';
                    $.each(data.result.files, function(index, file) {
                        $('#files').html(
                            '<tr><td><a data-url="<?php echo base_url() ?>locations/file_handling?op=delete&name=' +
                            file.name +
                            '" class="aj_delete"><i class="btn-danger btn-sm icon-trash-a"></i> ' +
                            file.name +
                            ' </a><img style="max-height:200px;" src="<?php echo base_url() ?>userfiles/company/' +
                            file.name + '"></td></tr>');
                        img = file.name;
                    });

                    $('#image').val(img);
                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                }
            }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });

    $(document).on('click', ".aj_delete", function(e) {
        e.preventDefault();

        var aurl = $(this).attr('data-url');
        var obj = $(this);

        jQuery.ajax({

            url: aurl,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                obj.closest('tr').remove();
                obj.remove();
            }
        });

    });

     $("#data_form").validate({
        ignore: [], // Important: Do not ignore hidden fields (used by summernote)
        rules: {               
            name: { required: true },
            address: { required: true },
            city: { required: true },
        },
        messages: {
            name: "Enter Location Name",
            address: "Enter Address",
            city: "Enter City"
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

    $("#location-edit-btn").on("click", function (e) {
        e.preventDefault();
        $('#location-edit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to update location?",
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
                        url: baseurl + 'locations/edit',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'locations';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#location-edit-btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#location-edit-btn').prop('disabled', false);
        }
    });
    </script>