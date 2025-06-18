
<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('accounts/add') ?>"><?php echo $this->lang->line('Chart of Accounts'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo "#".$transaction_link_data['trans_ref_number']; ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Transaction Reference')." #".$transaction_link_data['trans_ref_number']; ?>&nbsp;<a href="<?= base_url('paymentgateways/edit_bank_ac?id=' . $transaction_link_data['accountid']); ?>" class="btn btn-crud btn-sm btn-primary"><?php echo $this->lang->line('Edit'); ?></a></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
    <div class="card-body">        
        <div class="card card-block ">
            <div class="col-12 row"> 
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="account-left-section">
                    <h3><?php echo $this->lang->line('Create'); ?></h3>
                        <h2><?php echo '<span>'.$transaction_link_data['employee'].'</span> Created this transaction on <span>'.date('d-m-Y', strtotime($transaction_link_data['trans_date'])).'</span>'; ?> </h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!-- ============================================================= -->
                     <div class="transaction-box">
                        <table>
                            <tr>
                                <th class="fixedwidth fixedpadding"><?php echo $this->lang->line('Transaction Number'); ?></th>
                                <td class="fixedpadding">:&nbsp;&nbsp;<?=$transaction_link_data['bank_transaction_number']?></td>
                            </tr>
                            <tr>
                                <th class="fixedwidth fixedpadding"><?php echo $this->lang->line('Date'); ?></th>
                                <td class="fixedpadding">:&nbsp;&nbsp;<?=date('d-m-Y', strtotime($transaction_link_data['trans_date']))?></td>
                            </tr>
                            <tr>
                                <th class="fixedwidth fixedpadding"><?php echo $this->lang->line('Transaction Reference'); ?></th>
                                <td class="fixedpadding">:&nbsp;&nbsp;	opening-balance #<?=$transaction_link_data['trans_ref_number']?></td>
                            </tr>
                        </table>
                        <div class="table-scroll">
                            <table class="table table-striped table-bordered zero-configuration dataTable" style="width:100%"> 
                                <thead>
                                    <tr>
                                        <th style="width:70%"><?php echo $this->lang->line('Account'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('Debit'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('Credit'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?=$transaction_link_data['bankcode']."  - ".$transaction_link_data['bankname']?></td>
                                        <td class="text-right"><?=number_format($transaction_link_data['trans_amount'],2)?></td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td><?=$transaction_link_data['coacode']."  - ".$transaction_link_data['coaname']?></td>
                                        <td class="text-right">0.00</td>
                                        <td class="text-right"><?=number_format($transaction_link_data['trans_amount'],2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-12"></div>
                            <div class="col-lg-4 col-md-4 col-sm-12 text-right">
                                <h2><?php echo $this->lang->line('Amount').' &nbsp;&nbsp;<span>'.number_format($transaction_link_data['trans_amount'],2).'</span>'; ?> </h2>
                            </div>
                        </div>
                     </div>
                    <!-- ============================================================= -->
                </div>
            </div>
            
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
        $('#catgtable').DataTable({responsive: true});

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