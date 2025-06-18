<link rel="stylesheet" type="text/css"
      href="<?= assets_url() ?>app-assets/vendors/css/calendars/fullcalendar.min.css?v=<?= APPVER ?>">
<link href="<?php echo assets_url(); ?>assets/c_portcss/bootstrapValidator.min.css?v=<?= APPVER ?>" rel="stylesheet"/>
<link href="<?php echo assets_url(); ?>assets/c_portcss/bootstrap-colorpicker.min.css?v=<?= APPVER ?>"
      rel="stylesheet"/>
<!-- Custom css  -->
<link href="<?php echo assets_url(); ?>assets/c_portcss/custom.css?v=<?= APPVER ?>" rel="stylesheet"/>

<script src='<?php echo assets_url(); ?>assets/c_portjs/bootstrap-colorpicker.min.js?v=<?= APPVER ?>'></script>
<script src="<?= assets_url() ?>app-assets/vendors/js/extensions/moment.min.js?v=<?= APPVER ?>"></script>
<script src="<?= assets_url() ?>app-assets/vendors/js/extensions/fullcalendar.min.js?v=<?= APPVER ?>"></script>
<script src='<?php echo assets_url(); ?>assets/c_portjs/main.js?v=<?= APPVER ?>'></script>
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
            <li class="breadcrumb-item"><a href="<?= base_url('projects') ?>"><?php echo $this->lang->line('Projects') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Edit Project') ?></li>
            </ol>
        </nav>
        <h4 class="card-title"><?php echo $this->lang->line('Edit Project') ?></h4>
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
    <div class="card-body">


        <form method="post" id="data_form" class="form-horizontal">

          

            <div class="form-group row">
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">                
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Title') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" placeholder="Project Title"
                           class="form-control margin-bottom  required" name="name"
                           value="<?php echo $project['name'] ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="name"><?php echo $this->lang->line('Status') ?></label>
                    <select name="status" class="form-control">
                        <?php echo '<option value="' . $project['status'] . '">** ' . $this->lang->line($project['status']) . ' **</option>';
                        echo " <option value='Waiting'>" . $this->lang->line('Waiting') . "</option>
                            <option value='Pending'>" . $this->lang->line('Pending') . "</option>
                            <option value='Terminated'>" . $this->lang->line('Terminated') . "</option>
                            <option value='Finished'>" . $this->lang->line('Finished') . "</option>
                            <option value='Progress'>" . $this->lang->line('Progress') . "</option>"; ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="progress"><?php echo $this->lang->line('Progress') ?>
                    (in %)</label>
                    <input type="range" min="0" max="100" value="<?php echo $project['progress'] ?>" class="slider"
                           id="progress" name="progress">
                    <p><span id="prog"></span></p>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="pay_cat"><?php echo $this->lang->line('Priority') ?></label>
                    <select name="priority" class="form-control">
                        <?php echo '<option value="' . $project['priority'] . '">** ' . $project['priority'] . ' **</option>'; ?>
                        <option value='Low'>Low</option>
                        <option value='Medium'>Medium</option>
                        <option value='High'>High</option>
                        <option value='Urgent'>Urgent</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="pay_cat"><?php echo $this->lang->line('Customer') ?></label>
                    <select name="customer" class="form-control" id="customer_statement">
                        <?php echo '<option value="' . $project['cid'] . '">** ' . $project['customer'] . ' **</option>'; ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="name"><?php echo $this->lang->line('Customer Can View') ?></label>
                    <select name="customerview" class="form-control">
                        <?php echo '<option value="' . $project['meta_data'] . '">** ' . $project['meta_data'] . ' **</option>'; ?>
                        <option value='true'>True</option>
                        <option value='false'>False</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="name"><?php echo $this->lang->line('Customer Can Comment') ?></label>
                    <select name="customercomment" class="form-control">
                        <?php echo '<option value="' . $project['value'] . '">** ' . $project['value'] . ' **</option>'; ?>
                        <option value='true'>True</option>
                        <option value='false'>False</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="worth"><?php echo $this->lang->line('Budget') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" placeholder="Budget" onkeypress="return isNumber(event)"
                           class="form-control margin-bottom  required" name="worth"
                           value="<?php echo edit_amountExchange_s($project['worth'], 0, $this->aauth->get_user()->loc) ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="phase"><?php echo $this->lang->line('Phase') ?><span class="compulsoryfld">*</span></label>
                    <input type="text" placeholder="Phase A,B,C"
                           class="form-control margin-bottom required" name="phase"
                           value="<?php echo $project['phase'] ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="edate"><?php echo $this->lang->line('Start Date') ?></label>
                    <input type="text" class="form-control edate"
                           placeholder="Start Date" name="sdate"
                           autocomplete="false" value="<?php echo dateformat($project['sdate']) ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="edate"><?php echo $this->lang->line('Due Date') ?></label>
                    <input type="text" class="form-control edate"
                           placeholder="End Date" name="edate"
                           autocomplete="false" value="<?php echo dateformat($project['edate']) ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="name">Link to calendar</label>
                    <select name="link_to_cal" class="form-control" id="link_to_cal">
                        <option value='0'>No</option>
                        <option value='1'>Mark Deadline(End Date)</option>
                        <option value='2'>Mark Start to End Date</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="tags"><?php echo $this->lang->line('Tags') ?></label>
                    <input type="text" placeholder="Tags"
                           class="form-control margin-bottom  required" name="tags"
                           value="<?php echo $project['tag'] ?>">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                    for="name">Task Communication</label>
                    <select name="ptype" class="form-control">
                        <option value='<?php echo $project['ptype'] ?>' selected>Don't Change</option>
                        <option value='0'>No</option>
                        <option value='1'>Emails to team</option>
                        <option value='2'>Emails to team & customer</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Assign to') ?></label>
                        <select name="employee[]" class="form-control select-box" multiple="multiple">
                            <?php
                            foreach ($emp2 as $row) {
                                $cid = $row['id'];
                                $title = $row['name'];
                                echo "<option value='$cid' selected>- $title -</option>";
                            }
                            foreach ($emp as $row) {
                                $cid = $row['id'];
                                $title = $row['name'];
                                echo "<option value='$cid'>$title</option>";
                            }
                            ?>
                        </select>
                </div>
                <div id="hidden_div" class="col-lg-3 col-md-4 col-sm-12 col-xs-12" style="display: none">
                    <label class="col-form-label" for="color">Color</label>
                        <input id="color" name="color" type="text" class="form-control input-md"
                            readonly="readonly"/>
                        <small class="help-block">Click to pick a color</small>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label class="col-form-label"
                       for="content"><?php echo $this->lang->line('Note') ?></label>
                        <textarea class="summernote"  placeholder=" Note" autocomplete="false" rows="10" name="content"><?php echo $project['note'] ?></textarea>
                </div>
            
                <div class="col-12 responsive-text-right">
                    <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"
                           value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <input type="hidden" value="projects/edit" id="action-url">
                    <input type="hidden" value="<?php echo $project['prj'] ?>" name="p_id">
                </div>
            </div>

        </form>
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

    $("#customer_statement").select2({
        minimumInputLength: 4,
        tags: [],
        ajax: {
            url: baseurl + 'search/customer_select',
            dataType: 'json',
            type: 'POST',
            quietMillis: 50,
            data: function (customer) {
                return {
                    customer: customer,
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
        }
    });

    $('.edate').datepicker({autoHide: true, format: '<?php echo $this->config->item('dformat2'); ?>'});

    var slider = $('#progress');
    var textn = $('#prog');
    textn.text(slider.val() + '%');
    $(document).on('change', slider, function (e) {
        e.preventDefault();
        textn.text($('#progress').val() + '%');

    });

    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            name: { required: true },
            phase: { required: true },
            tags: { required: true },
        },
        messages: {
            name: "Enter Project Name",  
            phase: "Enter Phase",  
            phase: "Enter Tag",  
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to add a new project?",
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
                        url: baseurl + 'projects/edit',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'projects';
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