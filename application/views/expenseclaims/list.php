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
    <div class="card-header border-bottom">
      <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Expense Claims') ?></li>
            </ol>
      </nav>
      <h5 class="title"> <?php echo $this->lang->line('Expense Claims') ?> <a  href="<?php echo base_url('expenseclaims/create') ?>"  class="btn btn-primary btn-sm rounded">  <?php echo $this->lang->line('Add new') ?></a></h5>
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="table-scroll">
            <table id="catgtable" class="table table-striped table-bordered zero-configuration" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo $this->lang->line('Claim Number') ?></th>
                    <th class='text-center'><?php echo $this->lang->line('Created Date') ?></th>
                    <th class='text-center'><?php echo $this->lang->line('Time') ?></th>
                    <th class='text-center'><?php echo $this->lang->line('Due Date') ?></th>
                    <!-- <th><?php echo $this->lang->line('Supplier') ?></th> -->
                    <th class='text-center'><?php echo $this->lang->line('Payment Status') ?></th>
                    <th class='text-center'><?php echo $this->lang->line('Status') ?></th>
                    <th class='text-center'><?php echo $this->lang->line('Amount') ?></th>
                    <th class='text-right'><?php echo $this->lang->line('Action') ?></th>


                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($details as $row) {
                    $cid = $row->id;
                    $editlink="";
                    
                    if($row->payment_status == 'Not Approved' || $row->payment_status=='Refused' || $row->refused_by)
                    {
                        $editlink = "<a href='" . base_url() . "expenseclaims/edit?id=" . $row->claim_number . "' title='Edit' class='btn btn-crud btn-secondary btn-sm'><i class='icon-pencil'></i></a>";
                    } 
                    
                    $status = ($row->refused_by) ? "<span class='st-inactive'>Refused</span>" :  "<span class='st-" . strtolower($row->approval_status) . "'>" . $row->approval_status . "</span>";

                    echo "<tr>
                    <td>$i</td>
                    <td>
                        <a href='" . base_url() . "expenseclaims/view?id=" . $row->claim_number . "' title='View'>
                            " . $row->claim_number . "
                        </a>
                    </td>
                    <td class='text-center'>" . date('d-m-Y', strtotime($row->claim_dt)) . "</td>
                    <td class='text-center'>" . date('H:i:s', strtotime($row->claim_dt)) . "</td>
                    <td class='text-center'>" . date('d-m-Y', strtotime($row->claim_due_date)) . "</td>
                    <td class='text-center'>
                        <span class='st-" . strtolower($row->payment_status) . "'>" . $row->payment_status . "</span>
                    </td>
                    <td class='text-center'>
                        ".$status."
                    </td>
                    <td class='text-right'>" . $row->claim_total . "</td>
                    <td>".$editlink."                  
                        
                    </td>
                </tr>";
                
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
        $("#data_form").validate({
            ignore: [],
            rules: {               
                transcat_id: { required: true },
                typename: { required: true },
                coa_header_id: { required: true }
            },
            messages: {
                transcat_id: "Enter Chart of Account Type ID",
                typename: "Enter Chart of Account Type Name",
                coa_header_id: "Select Chart of Account Header"
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
                                $('#account-btn').prop('disabled', false);
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