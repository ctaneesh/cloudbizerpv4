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
        <h4 class="card-title"> <?php echo $this->lang->line('Banking Category') ?> </h4>
        <hr>
        <div class="card card-block formborder">
            <div class="alert alert-danger d-none" role="alert" id="account-error">Banking Category ID already in use</div>
            <form method="post" id="data_form" class="form-horizontal" autocomplete="off">
                <h5 id="headerlabel">Add Banking Category</h5><hr>
                <div class="form-group row">         
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Banking Type Header') ?><span class="compulsoryfld">*</span></label>
                         <select name="transtype_id" id="transtype_id" class="form-control">
                            <option value="">Select</option>
                            <?php
                                if(!empty($accountheaders))
                                {
                                    foreach($accountheaders as $row){
                                        echo '<option value="'.$row['transtype_id'].'">'.$row['transtype_name'].'</option>';
                                    }
                                }
                            ?>
                        </select> 
                    </div>       
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Banking Category ID') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Banking Category ID') ?>" class="form-control margin-bottom  required" name="transcat_id" id="transcat_id" >
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Banking Category Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Banking Category Name') ?>" class="form-control margin-bottom  required" name="transcat_name" id="transcat_name">
                        <input type="hidden"  name="category_id" id="category_id" value="0">
                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Status') ?></label>
                         <select name="status" id="status" class="form-control">
                           <option value="Active">Active</option>
                           <option value="Inactive">Inactive</option>
                        </select> 
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-2">
                        <input type="submit" id="banking-cat-btn" class="btn btn-crud1 btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Add') ?>" data-loading-text="Adding...">
                        
                            <!-- <input type="hidden" value="productpricing/edit" id="action-url"> -->
                        <!-- <input type="hidden" value="<?php echo $id ?>" name="id"> -->
                    </div>
                </div>
            </form>
        </div>

        <div class="table-scroll">
            <table id="catgtable" class="table table-striped table-bordered zero-configuration dataTable" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo $this->lang->line('Category ID') ?></th>
                    <th><?php echo $this->lang->line('Name') ?></th>
                    <th><?php echo $this->lang->line('Header ID') ?></th>
                    <th><?php echo $this->lang->line('Header Name') ?></th>
                    <th><?php echo $this->lang->line('Status') ?></th>
                    <th><?php echo $this->lang->line('Action') ?></th>


                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($details as $row) {
                    $cid = $row->id;


                    echo "<tr>
                        <td>$i</td>
                        <td>$row->transcat_id</td>
                        <td>$row->transcat_name</td>
                        <td>$row->transtype_id</td>
                        <td>$row->transtype_name</td>                 
                        <td>$row->status</td>                 
                        <td><button onclick='update_category($cid)' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></button>&nbsp;</td></tr>";
                    $i++;
                }
                ?>
                </tbody>
            
            </table>
        </div>
                                
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {               
                transcat_id: { required: true },
                typename: { required: true },
                transtype_id: { required: true }
            },
            messages: {
                transcat_id: "Enter Chart of Account Type ID",
                typename: "Enter Chart of Account Type Name",
                transtype_id: "Select Banking Type Header"
            }
        }));
        //datatables
        $('#catgtable').DataTable({responsive: false});

    });

    $('#banking-cat-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#banking-cat-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create/update category?",
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
                        url: baseurl + 'bankingcategory/addeditaction', // Replace with your server endpoint
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                            if (typeof response === "string") {
                                response = JSON.parse(response);
                            }
                            if(response.status=='Error')
                            {
                                $('#account-error').removeClass('d-none');  
                                $('#banking-cat-btn').prop('disabled', false);
                            }
                            else{
                                $('#account-error').addClass('d-none');  
                                location.reload();
                            }                    
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#banking-cat-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#banking-cat-btn').prop('disabled', false);
        }
    });

    function update_category(id)
    {
        $.ajax({
            type: 'POST',
            url: baseurl +'bankingcategory/load_category_by_id',
            data: {
                "category_id" : id
            },
            success: function(response) {
                var responseData = JSON.parse(response);
                var resultdata = responseData.data[0];
                $("#holder").focus();
                $("#banking-cat-btn").val("Update");           
                $("#headerlabel").text("Update Banking Category");           
                $("#transcat_id").val(resultdata.transcat_id);
                $("#category_id").val(resultdata.id);
                $("#transcat_name").val(resultdata.transcat_name);
                $("#transtype_id").val(resultdata.transtype_id).trigger('change');
                $("#status").val(resultdata.status).trigger('change');
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }
</script>