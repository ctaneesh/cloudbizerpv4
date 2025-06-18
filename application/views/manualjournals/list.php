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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Manual Journals') ?></li>
            </ol>
        </nav>
        <h5 class="title"> <?php echo $this->lang->line('Manual Journals') ?> <a  href="<?php echo base_url('manualjournals/create') ?>"  class="btn btn-crud1 btn-primary btn-sm rounded">  <?php echo $this->lang->line('Add new') ?></a></h5>
        <hr>
        
        <table id="catgtable" class="table table-striped table-bordered zero-configuration w-100" cellspacing="0">
            <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Journal Number') ?></th>
                <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                <th class="text-center"><?php echo $this->lang->line('Time') ?></th>
                <th><?php echo $this->lang->line('Created By') ?></th>
                <th  class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
          
            foreach ($details as $row) {
                $cid = $row->id;
                $link = base_url('manualjournals/view?id=' . $row->transaction_number);
                $relation = '<a href="' . $link . '">' . $row->journal_number . '</a>';

                echo "<tr>
                    <td>$i</td>
                    <td>$relation</td>
                    <td class='text-center'>" . date('d-m-Y', strtotime($row->journal_date)) . "</td>
                    <td class='text-center'>" . date('H:i:s', strtotime($row->created_dt)) . "</td>
                    <td>$row->name</td>                 
                    <td class='text-right'>" . number_format($row->journal_amount, 2) . "</td>                 
                    <td>
                        <a href='" . base_url('manualjournals/edit?id=' . $row->journal_number) . "' class='btn btn-crud btn-sm btn-secondary'>
                            <i class='icon-pencil'></i>
                        </a>
                    </td>
                </tr>";

                $i++;
            }
            ?>
            </tbody>
           
        </table>
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