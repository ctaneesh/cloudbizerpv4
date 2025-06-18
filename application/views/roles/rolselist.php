<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <h4 class="card-title"> <?php echo $this->lang->line('Roles') ?> </h4>
        <hr>
        <div class="card card-block formborder">
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">                
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Role Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Role Name') ?>" class="form-control margin-bottom  required" name="role_name" id="role_name">
                        <input type="hidden" name="role_id" id="role_id" value="0">
                    </div>
                   
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Status') ?></label>
                        <select name="status" id="status" class="form-control form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-2">
                        <input type="submit" id="role-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        
                            <!-- <input type="hidden" value="productpricing/edit" id="action-url"> -->
                        <!-- <input type="hidden" value="<?php echo $id ?>" name="id"> -->
                    </div>
                </div>
            </form>
        </div>

        <br>
        <table id="catgtable" class="table table-striped table-bordered zero-configuration" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th width="4%">#</th>
                <th width="15%"><?php echo $this->lang->line('Role Name') ?></th>
                <th width="8%"><?php echo $this->lang->line('Status') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
           
            foreach ($details as $row) {
               
                echo "<tr>
                    <td>$i</td>
                    <td>".$row->role_name."</td>   
                    <td>".$row->status."</td>   
                    <td><button onclick='update_role($row->role_id)' class='btn btn-secondary btn-sm btn-crud' title='Edit'><i class='fa fa-pencil'></i></button>&nbsp;<a href='" . base_url("roles/set_user_role_permissions?role=$row->role_id") . "' class='btn btn-crud btn-secondary btn-sm btn-crud' title='Edit'><i class='fa fa-book'></i> Premissions</a></td></tr>";
                $i++; 
                // <td><a href='" . base_url("productpricing/edit?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;</td></tr>";
            }
            ?>
            </tbody>
           
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
       
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {               
                role_name: { required: true },
                status: { required: true },
            },
            messages: {
                role_name: "Enter Role Name",
                status: "Select Status",
                // module_number: "Select Module"
            }
        }));
        //datatables
        $('#catgtable').DataTable({responsive: false});

    });

    $('#role-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#role-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create/update  role?",
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
                        url: baseurl + 'roles/addeditaction', 
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            location.reload();
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#role-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#role-btn').prop('disabled', false);
        }
    });


    function update_role(id)
    {
        $.ajax({
            type: 'POST',
            url: baseurl +'roles/load_roles_by_id',
            data: {
                "role_id" : id
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                var resultdata = responseData.data[0];
                
                $("#holder").focus();
                $("#role-btn").val("Update Role");           
                $("#role_name").val(resultdata.role_name);
                $("#status").val(resultdata.status);
                $("#role_id").val(resultdata.role_id);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }
</script>