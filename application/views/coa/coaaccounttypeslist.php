<div class="card card-block">
    <?php       
        if (($msg = check_permission($permissions)) !== true) {
            echo $msg;
            return;
        }
        ?>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
      
        <h5 class="title"> <?php echo $this->lang->line('Chart of Account Types') ?> </h5>
        <hr>
        <div class="card card-block formborder">
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">     
                     <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Chart of Account Header') ?><span class="compulsoryfld">*</span></label>
                         <select name="coa_header_id" id="coa_header_id" class="form-control">
                            <option value="">Select</option>
                            <?php
                                if(!empty($accountheaders))
                                {
                                    foreach($accountheaders as $row){
                                        echo '<option value="'.$row['coa_header_id'].'">'.$row['coa_header'].'</option>';
                                    }
                                }
                            ?>
                        </select> 


                    </div>           
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Chart of Account Type ID') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Chart of Account Type ID') ?>" class="form-control margin-bottom  required" name="coa_type_id" >
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Chart of Account Type Name') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Chart of Account Type Name') ?>" class="form-control margin-bottom  required" name="typename" >
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 mt-2">
                        <input type="submit" id="account-type-btn" class="btn btn-crud1 btn-primary btn-lg margin-bottom"
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
                <th>#</th>
                <th><?php echo $this->lang->line('COA Type ID') ?></th>
                <th><?php echo $this->lang->line('Type Name') ?></th>
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
                    <td>$row->coa_type_id</td>
                    <td>$row->typename</td>
                    <td>$row->coa_header_id</td>
                    <td>$row->coa_header</td>                 
                    <td>$row->status</td>                 
                    <td><a href='' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;</td></tr>";
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
                coa_type_id: { required: true },
                typename: { required: true },
                coa_header_id: { required: true }
            },
            messages: {
                coa_type_id: "Enter Chart of Account Type ID",
                typename: "Enter Chart of Account Type Name",
                coa_header_id: "Select Chart of Account Header"
            }
        }));
        //datatables
        $('#catgtable').DataTable({responsive: false});

    });

    $('#account-type-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#account-type-btn').prop('disabled', true);
        
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create a new account type?",
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
                        url: baseurl + 'coaaccounttypes/addeditaction', // Replace with your server endpoint
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
                    $('#account-type-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#account-type-btn').prop('disabled', false);
        }
    });
</script>