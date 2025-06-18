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
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Edit Task') ?></li>
                  
                </ol>
            </nav>
            <h5 class="card-title"><?php echo $this->lang->line('Edit') ?><?php echo $this->lang->line('Task') ?> </h5>
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
                            <input type="text" placeholder="Task Title" class="form-control margin-bottom  required" name="name" value="<?php echo $task['name'] ?>" data-original-value="<?=$task['name']?>">
                        </div>                        
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="name"><?php echo $this->lang->line('Status') ?></label>
                            <select name="status" class="form-control" data-original-value="<?=$task['status']?>">
                                <?php echo '<option value="' . $task['status'] . '">--' . $this->lang->line($task['status']) . '--</option>';
                                echo "<option value='Due'>" . $this->lang->line('Due') . "</option>
                            <option value='Done'>" . $this->lang->line('Done') . "</option>
                            <option value='Progress'>" . $this->lang->line('Progress') . "</option>";
                                ?>

                            </select>
                        </div>                        
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('Priority') ?></label>
                            <select name="priority" class="form-control" data-original-value="<?=$task['priority']?>">
                                <?php echo '<option value="' . $task['priority'] . '">--' . $task['priority'] . '--</option>'; ?>
                                <option value='Low'>Low</option>
                                <option value='Medium'>Medium</option>
                                <option value='High'>High</option>
                                <option value='Urgent'>Urgent</option>
                            </select>
                        </div>                        
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="edate"><?php echo $this->lang->line('Start Date') ?><span class="compulsoryfld">*</span></label>
                            <input type="date" class="form-control required"
                                   placeholder="Start Date" name="staskdate"
                                    autocomplete="false" value="<?php echo $task['start'] ?>" data-original-value="<?=$task['start']?>">
                        </div>                        
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="edate"><?php echo $this->lang->line('Due Date') ?><span class="compulsoryfld">*</span></label>
                            <input type="date" class="form-control required"
                                   placeholder="End Date" name="taskdate" autocomplete="false" value="<?php echo date('Y-m-d',strtotime($task['duedate'])); ?>" data-original-value="<?=$task['duedate']?>">
                        </div>                        
                        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label"
                               for="pay_cat"><?php echo $this->lang->line('Assign to') ?></label>
                            <select name="employee" class="form-control select-box" data-original-value="<?=$task['emp']?>">
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
                                  name="content" data-original-value="<?=$task['description']?>"><?php echo $task['description'] ?></textarea>
                        </div>                  
                        <div class="col-12 submit-section text-right">                            
                            <!-- <input type="submit" id="task_btn" class="btn btn-primary btn-lg margin-bottom"  value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding..."> -->
                            <input type="submit" id="submit-btn" class="btn btn-primary btn-lg margin-bottom"  value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                            <input type="hidden" value="tools/edittask" id="action-url">
                            <input type="hidden" value="<?php echo $task['id'] ?>" name="id">
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>

    <!--     erp2025 add 06-01-2025   Detailed hisory starts-->
   <button class="history-expand-button">
        <span>History</span>
    </button>

    <div class="history-container">
    <button class="history-close-button">
    <span>Close</span>
        </button>
        <button class="logclose-btn">
            <span>X</span>
        </button>
        <h2>History</h2>
        <form>
        <table id="log" class="table table-striped table-bordered zero-configuration dataTable">
            <thead>
                <tr>
                    <th><?php echo $this->lang->line('Action Performed') ?></th>
                    <!-- <th><?php// echo $this->lang->line('Action Performed') ?></th> -->
                    <th><?php echo $this->lang->line('Performed At')?></th>
                    <!-- <th><?php //echo $this->lang->line('Performed By') ?></th> -->
                    <th><?php echo $this->lang->line('IP Address')?></th>
                            
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;
                foreach ($groupedOrder as $seqence_number => $orders){
                $flag=0;
            ?>              
                <tr>
                    <td>        
                    <?php    foreach ($orders as $order) {
                    if($flag==0) 
                    {?>
                    <div class="userdata">
                    <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$order['picture'])?>' style="width:50px; height:50px;" ?>
                    <?php  echo $order['name'];
                            $flag=1;
                    } ?>
                    </div>           
                        <ul><li>  <?php echo $order['old_value'];?> > <b><span class="newdata"><?php echo $order['new_value']?></span></b> (<?php if($order['field_label']==""){echo $order['field_name'];}else{echo $order['field_label'];}?>)
                        </li></ul>
                        <?php } ?>
                    </td>               
                    <td><?php echo date('d-m-Y H:i:s', strtotime($order['changed_date'])); ?></td>
                    <td><?php echo $order['ip_address']?></td> 
                    
                </tr>   
                <?php 
                    $i++; 
                
                }?>
            
            </tbody>
        </table>

        </form>
    </div>   
<!--     erp2025 add 06-01-2025   Detailed hisory ends-->
<!-- =========================History End=================== -->
<script type="text/javascript">
const changedFields = {};
$(document).ready(function() {

    $(".history-expand-button").on("click", function() {
      $(".history-container").toggleClass("active");
    });
      $(".history-close-button").on("click", function () {
      $(".history-container").removeClass("active");
    });
      $(".logclose-btn").on("click", function () {
      $(".history-container").removeClass("active");
    }); 

    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [], // Important: Do not ignore hidden fields
        rules: {
            name: {required:true},
            staskdate: {required:true},
            taskdate: {required:true},
        },
        messages: {
            name  : "Enter Task Title",
            staskdate  : "Enter Task Starting Date",
            taskdate  : "Enter Task Starting Date",
        }
    }));

     // Add event listeners to all input fields
     document.querySelectorAll('input, textarea, select').forEach((input) => {
        input.addEventListener('change', function () {
            
            const fieldId = this.id || this.name; // Use `name` for grouped radio buttons
            const originalValue = this.getAttribute('data-original-value');
            var label = $('label[for="' + fieldId + '"]');
            var field_label = label.text();
            if (!field_label.trim()) {
                field_label = this.getAttribute('title') || 'Unknown Field';
            }
            if (this.type === 'checkbox') {
                // For checkboxes, use the "checked" state
                const newValue = this.checked ? this.value : null;
                const originalChecked = originalValue === this.value;

                if (originalChecked !== this.checked) {
                    changedFields[fieldId] = {
                        oldValue: originalChecked ? this.value : null,
                        newValue: newValue,
                        fieldlabel : field_label
                    }; // Track changes
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            } else if (this.type === 'radio') {
                // For radio buttons, track the selected option
                if (this.checked) {
                    const newValue = this.value;
                    if (originalValue !== newValue) {
                        changedFields[fieldId] = {
                            oldValue: originalValue,
                            newValue: newValue,
                            fieldlabel : field_label
                        };
                    } else {
                        delete changedFields[fieldId]; // Remove if no change
                    }
                }
            } else if (this.type === 'number') {
                // For numeric fields
                const newValue = parseFloat(this.value);
                const originalNumber = parseFloat(originalValue);

                if (!isNaN(originalNumber) && !isNaN(newValue) && originalNumber !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalNumber,
                        newValue: newValue,
                        fieldlabel : field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            } else if (this.tagName === 'SELECT') {
            // For select fields, use the option's label
            const selectedOption = this.options[this.selectedIndex];
            const newValue = selectedOption ? selectedOption.label : '';
            const originalLabel = this.getAttribute('data-original-label');

            if (originalLabel !== newValue) {
                changedFields[fieldId] = {
                    oldValue: originalLabel,
                    newValue: newValue,
                    fieldlabel: field_label
                };
            } else {
                delete changedFields[fieldId];
            }
        } else {
                // For text, textarea, and select fields
                const newValue = this.value;
                if (originalValue !== newValue) {
                    changedFields[fieldId] = {
                        oldValue: originalValue,
                        newValue: newValue,
                        fieldlabel : field_label
                    };
                } else {
                    delete changedFields[fieldId]; // Remove if no change
                }
            }
        });
    });
    $('select').each(function () {
            if (!$(this).attr('multiple')) {
                 const selectedLabel = $(this).find(':selected').text();
                $(this).attr('data-original-label',selectedLabel);
            } else {
            // For multi-select, get all selected options text and join them with a comma
            const selectedLabels = $(this)
                .find(':selected')
                .map(function () {
                return $(this).text();
                })
                .get()
                .join(', ');
            $(this).attr('data-original-label', selectedLabels);
            }
        });
    

});   
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

$('#task_btn').on('click', function(e) {
    e.preventDefault();
    $('#task_btn').prop('disabled',true);
    // Validate the form    
    hasUnsavedChanges = false;
    if ($("#data_form").valid()) {
     
        var form = $('#data_form')[0];
        var formData = new FormData(form);
        formData.append('changedFields', JSON.stringify(changedFields));
        Swal.fire({
                  title: "Are you sure?",
                  text: "Do you want to update task?",
                  icon: "question",
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, proceed!',
                  cancelButtonText: "No - Cancel",
                  reverseButtons: true,  
                  focusCancel: true,      
                  allowOutsideClick: false,  // Disable outside click
                  }).then((result) => {
                  if (result.isConfirmed) {
                     $.ajax({
                           url: baseurl + "tools/edittask",
                           type: 'POST',
                           data: formData,
                           contentType: false,
                           processData: false,
                           success: function(response) {
                              
                              var data = JSON.parse(response);
                              
                              window.location.href = baseurl + 'tools/todo';
                           },
                           error: function(xhr, status, error) {
                              Swal.fire('Error', 'An error occurred while generating the material request', 'error');
                              console.log(error); // Log any errors
                           }
                     });
                  } else if (result.dismiss === Swal.DismissReason.cancel) {
                     // Enable the button again if user cancels
                     $('#task_btn').prop('disabled', false);
                  }
               });
    
    }
    else{
        $('#task_btn').prop('disabled',false);
    }
});

    $("#data_form").validate({
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
                        url: baseurl + 'tools/edittask',
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