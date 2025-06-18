<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
               <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('manualjournals') ?>"><?php echo $this->lang->line('Manual Journals') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Manual Journal')." ".$journal_number;  ?></li>
            </ol>
        </nav>
        <h4 class="card-title"> <?php echo $this->lang->line('Manual Journal - Voucher Entry')." ".$journal_number; ?> </h4>
        <hr>
        <div class="card card-block formborder">
            <form method="post" id="data_form" class="form-horizontal">
                <div class="form-group row">                
                    <div class="col-12">
                        <h4><?php echo $this->lang->line('General') ?></h4>
                        <p><?php echo $this->lang->line('Here you can') ?></p>
                        <hr>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Journal Number') ?><span class="compulsoryfld">*</span></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Journal Number') ?>" class="form-control margin-bottom  required" readonly name="journal_number" value="<?=$journal_number?>">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="name"><?php echo $this->lang->line('Journal Date') ?><span class="compulsoryfld">*</span></label>
                        <input type="date" class="form-control margin-bottom  required" name="journal_date" id="journal_date" value="<?=$journal_master['journal_date']?>">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Basis') ?><span class="compulsoryfld">*</span></label>
                         <select name="journal_basis" id="journal_basis" class="form-control disable-class "  readonly>
                            <?php
                                if(!empty($basislist))
                                {
                                    foreach($basislist as $row){
                                        $sele="";
                                        if($journal_master['basis_number']==$row['basis_number'])
                                        {
                                            $sele = "selected";
                                        }
                                        echo '<option value="'.$row['basis_number'].'" '.$sele.'>'.$row['basis_name'].'</option>';
                                    }
                                }
                            ?>
                        </select> 
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Reference') ?></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Reference') ?>" class="form-control margin-bottom" name="journal_reference" value="<?=$journal_master['journal_reference']?>">
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Note') ?><span class="compulsoryfld">*</span></label>
                        <textarea name="journal_note" id="journal_note" class="form-textarea"><?=$journal_master['journal_note']?></textarea>
                        <input type='hidden' name='transaction_number' id='transaction_number' value="<?=$journal_master['transaction_number']?>">
                    </div>
                   

                    <div class="col-12 mt-2 table-scroll">
                        <h4><?php echo $this->lang->line('Lines') ?></h4>
                        <p><?php echo $this->lang->line('Journal Lines') ?></p>
                        <hr>
                        
                        <!-- ================================================= -->
                        <table class="table table-striped table-bordered zero-configuration dataTable">
                            <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                <th width="20%" class="text-center1 pl-1"><?php echo $this->lang->line('Account') ?></th>
                                <th width="15%" class="text-center1 pl-1"><?php echo $this->lang->line('Note') ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                                <th width="10%" class="text-right"><?php echo $this->lang->line('Credit') ?></th>
                                <th><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                            </thead>
                            
                            <tbody id="journal-rows">
                                <?php
                                $table = "";
                                $i = 0;
                                if ($journal_items) {                                   
                                    echo "<input type='hidden' name='counter' id='counter' value='".count($journal_items)."'>";
                                    foreach ($journal_items as $key => $value) {
                                        $debit_readonly = ($value["debit"]>0) ? "readonly" : "";
                                        $credit_readonly = ($value["credit"]>0) ? "readonly" : "";
                                        $table .= '<tr>';
                                        $table .= '<td>';
                                        $table .= '<select name="account[]" id="account-' . $i . '" class="form-control form-select required responsive-width-elements" required></select>';
                                       

                                        $table .= '</td>';
                                        $table .= '<td>';
                                        $table .= '<textarea name="note[]" id="note-' . $i . '" class="form-textarea responsive-width-elements">' . htmlspecialchars($value["description"]) . '</textarea>';
                                        $table .= '</td>';
                                        $table .= '<td>';
                                        $table .= '<input type="number" name="debit[]" id="debit-' . $i . '" class="form-control text-right debit-field responsive-width-elements" value="' . htmlspecialchars($value["debit"]) . '" '.$credit_readonly.'>';
                                        $table .= '</td>';
                                        $table .= '<td>';
                                        $table .= '<input type="number" name="credit[]" id="credit-' . $i . '" class="form-control text-right credit-field responsive-width-elements" value="' . htmlspecialchars($value["credit"]) . '" '.$debit_readonly.'>';
                                        $table .= '</td>';
                                        $table .= '<td>';
                                        $table .= '<button type="button" class="btn btn-sm btn-secondary remove-row"><i class="fa fa-trash"></i></button>';
                                        $table .= '</td>';
                                        $table .= '</tr>';
                                        $table .= '<input type="hidden" name="account_id_old[]" id="account_id-' . $i . '" class="form-control text-right" value="' . ($value["account_id"]) . '">';

                                        $table .= '<input type="hidden" name="credit_old[]" id="credit-' . $i . '" class="form-control text-right" value="' . ($value["credit"]) . '">';

                                        $table .= '<input type="hidden" name="debit_old[]" id="debit-' . $i . '" class="form-control text-right" value="' . ($value["debit"]) . '">';
                                        echo "<script>
                                            $(document).ready(function() {
                                                load_coa_accounts($i);
                                            });
                                        </script>";
                                        $i++;
                                    }

                                    echo $table;
                                }

                                
                                ?>
                              
                            </tbody>
                        </table>
                        <!-- ================================================= -->
                       <div class="text-left">
                            <button type="button" class="btn btn-secondary <?=$accpetthenhide?> add-row-btn" aria-label="Left Align" id="addjournalbtn"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                            </button>
                        <hr>
                       </div>
                       <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12"></div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 responsive-text-right">
                                   <div class="row">
                                        <div class="col-lg-8 col-md-4 col-sm-12 col-12">Subtotal</div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6"><div class="debitsubtotal"><?=$journal_master['journal_amount']?></div></div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6"><div class="creditsubtotal"><?=$journal_master['journal_amount']?></div></div>
                                   </div>
                                   <div class="row mt-2">
                                        <div class="col-lg-8 col-md-4 col-sm-12 col-12 totalsection">Total</div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6 debitsection total-bg-success"><div class="debittotal"><?=$journal_master['journal_amount']?></div></div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6 creaditsection total-bg-success"><div class="credittotal"><?=$journal_master['journal_amount']?></div></div>
                                   </div>
                                </div>
                       </div>

                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="toAddInfo" class="col-form-label"></label>
                            <button type="button" class="btn  btn-secondary mt-3 btn-crud" id="attachment-btn" ><i class="fa fa-paperclip" aria-hidden="true"></i> Add Attachment</button>
                            <input type="hidden" name="journal_amount" id="journal_amount" value="<?=$journal_master['journal_amount']?>">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2 text-right">
                        <input type="submit" id="journal-btn" class="btn btn-primary btn-lg margin-bottom btn-crud"
                            value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Adding...">
                        
                            <!-- <input type="hidden" value="productpricing/edit" id="action-url"> -->
                        <!-- <input type="hidden" value="<?php echo $id ?>" name="id"> -->
                    </div>
                </div>
            </form>
        </div>

        
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // $("#account-0").select2();
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {
                journal_number: { required: true },
                journal_date: { required: true },
                journal_basis: { required: true },
                journal_note: { required: true },
                // Dynamically add validation for account[] fields
                "account[]": {
                    required: true
                }
            },
            messages: {
                journal_number: "Enter journal number",
                journal_date: "Enter journal date",
                journal_basis: "Select journal basis",
                journal_note: "Enter journal description",
                "account[]": "This field is required"
            }
        }));


        //datatables
        $('#catgtable').DataTable({responsive: true});
    });

    function load_coa_accounts(id) {
        $.ajax({
            type: 'POST',
            url: baseurl + 'manualjournals/coa_accounts',
            data : {
                'account_id' : $("#account_id-"+id).val()
            },
            success: function (response) {
                $("#account-"+id).html(response);
                $("#account-"+id).select2();
            },
            error: function (xhr, status, error) {
                console.error("Error:", xhr.responseText);
            }
        });
    }
    // Button click event
    $('#journal-btn').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission

        let isValid = true; // Flag to track validation status

        // Reset the invalid state for all select fields
        $("select[name='account[]']").removeClass("is-invalid");

        // Validate each 'account[]' field manually
        $("select[name='account[]']").each(function () {
            if ($(this).val() === "" || $(this).val() === null) {
                $(this).addClass("is-invalid");
                isValid = false;
            }
        });

        // If any account[] field is invalid, show validation error popup and stop form submission
        if (!isValid) {
            Swal.fire('Validation Error', 'Please ensure all account fields are selected.', 'error');
             $('#journal-btn').prop('disabled', false);
            return; // Exit function if validation fails
        }

        // Proceed if all validation passes (including custom 'account[]' validation)
        if ($("#data_form").valid()) {
            var form = $('#data_form')[0];
            var formData = new FormData(form);

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update Manual Journal - Voucher Entry?",
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
                        url: baseurl + 'manualjournals/editaction',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            window.location.href = baseurl + 'manualjournals';
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while processing the request.', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    $('#journal-btn').prop('disabled', false); // Enable the button again if user cancels
                }
            });
        } else {
            $('#journal-btn').prop('disabled', false); // Enable the button if validation fails
        }
    });


$(document).ready(function () { 
    // $('#journal-btn').prop('readonly', true); 
    
    let rowCount = $("#counter").val();

    // Add new journal row
    $('#addjournalbtn').on('click', function () {
        const newRow = `
            <tr>
                <td>
                    <select name="account[]" id="account-${rowCount}" class="form-control form-select required" required>
                    </select>
                </td>
                <td>
                    <textarea name="note[]" id="note-${rowCount}" class="form-textarea form-control"></textarea>
                </td>
                <td>
                    <input type="number" name="debit[]" id="debit-${rowCount}" class="form-control text-right debit-field">
                </td>
                <td>
                    <input type="number" name="credit[]" id="credit-${rowCount}" class="form-control text-right credit-field">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-secondary remove-row"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

       
        $('#journal-rows').append(newRow);
        $("#account-"+rowCount).select2();
        load_coa_accounts(rowCount);
        rowCount++;
        $("#counter").val(rowCount);
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        calculateTotals(); // Recalculate totals after row removal
    });

    // Disable credit field if debit has a value and vice versa
    $(document).on('input', '.debit-field', function () {
        const creditField = $(this).closest('tr').find('.credit-field');
        if ($(this).val()) {
            creditField.prop('readonly', true);
        } else {
            creditField.prop('readonly', false);
        }
        calculateTotals();
    });

    $(document).on('input', '.credit-field', function () {
        const debitField = $(this).closest('tr').find('.debit-field');
        if ($(this).val()) {
            debitField.prop('readonly', true);
        } else {
            debitField.prop('readonly', false);
        }
        calculateTotals();
    });

    // Calculate totals for debit and credit
    function calculateTotals() {
        let debitSubtotal = 0;
        let creditSubtotal = 0;
        $("#journal_amount").val(0);
        // Sum all debit and credit values
        $('.debit-field').each(function () {
            const value = parseFloat($(this).val()) || 0;
            debitSubtotal += value;
        });

        $('.credit-field').each(function () {
            const value = parseFloat($(this).val()) || 0;
            creditSubtotal += value;
        });

        // Update subtotal fields
        $('.debitsubtotal').text(debitSubtotal.toFixed(2));
        $('.creditsubtotal').text(creditSubtotal.toFixed(2));

        // Default classes to success
        $('.debitsection, .creaditsection').removeClass('bg-danger').addClass('bg-success');

        if (debitSubtotal > creditSubtotal) {
            // Debit is greater
            $('.debittotal').text(debitSubtotal.toFixed(2)).parent().removeClass('bg-danger').addClass('bg-success');
            $('.credittotal').text((creditSubtotal - debitSubtotal).toFixed(2)).parent().removeClass('bg-success').addClass('bg-danger');
        } else if (debitSubtotal < creditSubtotal) {
            // Credit is greater
            $('.debittotal').text((debitSubtotal - creditSubtotal).toFixed(2)).parent().removeClass('bg-success').addClass('bg-danger');
            $('.credittotal').text(creditSubtotal.toFixed(2)).parent().removeClass('bg-danger').addClass('bg-success');
        } else {
            // Totals are equal
            $('.debittotal').text(debitSubtotal.toFixed(2)).parent().removeClass('bg-danger').addClass('bg-success');
            $('.credittotal').text(creditSubtotal.toFixed(2)).parent().removeClass('bg-danger').addClass('bg-success');
            $("#journal_amount").val(debitSubtotal.toFixed(2));
        }

        // Enable or disable the button
        if (debitSubtotal === creditSubtotal) {
            $('#journal-btn').removeClass('disable-class'); 
        } else {
            $('#journal-btn').addClass('disable-class');
        }
    }


    // Add attachment functionality
    $('#attachment-btn').on('click', function () {
        // alert('Add Attachment functionality to be implemented.');
        
    });
});


</script>