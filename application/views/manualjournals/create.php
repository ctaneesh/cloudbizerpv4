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
               <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url('manualjournals') ?>"><?php echo $this->lang->line('Manual Journals') ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Manual Journals') ?></li>
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
                        <input type="date" class="form-control margin-bottom  required" name="journal_date" id="journal_date" value="<?=date('Y-m-d')?>">
                    </div>
                   
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Basis') ?><span class="compulsoryfld">*</span></label>
                         <select name="journal_basis" id="journal_basis" class="form-control disable-class" readonly>
                            <?php
                                if(!empty($basislist))
                                {
                                    foreach($basislist as $row){
                                        echo '<option value="'.$row['basis_number'].'">'.$row['basis_name'].'</option>';
                                    }
                                }
                            ?>
                        </select> 
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Reference') ?></label>
                        <input type="text" placeholder="<?php echo $this->lang->line('Reference') ?>" class="form-control margin-bottom" name="journal_reference" >
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                        <label class="col-form-label" for="address"><?php echo $this->lang->line('Descriptions') ?><span class="compulsoryfld">*</span></label>
                        <textarea name="journal_note" id="journal_note" class="form-textarea"></textarea>
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
                               <tr>
                                    <td>
                                    <select name="account[]" id="account-0" class="form-control form-select required" required></select>
                                    </td>
                                    <td>
                                    <textarea name="note[]" id="note-0" class="form-textarea responsive-width-elements" ></textarea>
                                    </td>
                                    <td>
                                        <input type="number" name="debit[]" id="debit-0" class="form-control text-right debit-field responsive-width-elements">
                                    </td>
                                    <td>
                                        <input type="number" name="credit[]" id="credit-0" class="form-control text-right credit-field responsive-width-elements">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-crud btn-sm btn-secondary remove-row"><i class="fa fa-trash"></i></button>
                                    </td>
                               </tr>
                            </tbody>
                        </table>
                        <!-- ================================================= -->
                       <div class="text-center">
                            <button type="button" class="btn btn-crud btn-secondary add-row-btn <?=$accpetthenhide?>" aria-label="Left Align" id="addjournalbtn"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                            </button>
                        <hr>
                       </div>
                       <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12"></div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 responsive-text-right">
                                   <div class="row">
                                        <div class="col-lg-8 col-md-4 col-sm-12 col-12">Subtotal</div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6"><div class="debitsubtotal"></div></div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6"><div class="creditsubtotal"></div></div>
                                   </div>
                                   <div class="row mt-2">
                                        <div class="col-lg-8 col-md-4 col-sm-12 col-12 totalsection">Total</div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6 debitsection total-bg-success"><div class="debittotal"></div></div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6 creaditsection total-bg-danger"><div class="credittotal"></div></div>
                                   </div>
                                </div>
                       </div>

                    </div>
                    
                    <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                            <label for="toAddInfo" class="col-form-label"></label>
                            <button type="button" class="btn  btn-crud btn-secondary mt-3" id="attachment-btn" ><i class="fa fa-paperclip" aria-hidden="true"></i> Add Attachment</button>
                            <input type="hidden" name="journal_amount" id="journal_amount">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2 text-right">
                        <input type="submit" id="journal-btn" class="btn btn-crud btn-primary btn-lg margin-bottom"
                            value="<?php echo $this->lang->line('Save') ?>" data-loading-text="Adding...">
                        
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
        $("#account-0").select2();
        $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
            ignore: [],
            rules: {               
                journal_number : { required: true },
                journal_date: { required: true },
                journal_basis: { required: true },
                journal_note: { required: true }
            },
            messages: {
                journal_number: "Enter journal number",
                journal_date: "Enter journal date",
                journal_basis: "Select journal basis",
                journal_note: "Enter journal description",
            }
        }));
        //datatables
        $('#catgtable').DataTable({responsive: true});
        load_coa_accounts(0);

        
    });

    $('#journal-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#journal-btn').prop('disabled', true);
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
        // Validate the form
        if ($("#data_form").valid()) {                
            var form = $('#data_form')[0];
            var formData = new FormData(form); 
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to create Manual Journal - Voucher Entry?",
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
                        url: baseurl + 'manualjournals/action',
                        type: 'POST',
                        data: formData,
                        contentType: false, 
                        processData: false,
                        success: function(response) {
                           
                            window.location.href = baseurl + 'manualjournals';
                            
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                            console.log(error); // Log any errors
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Enable the button again if user cancels
                    $('#journal-btn').prop('disabled', false);
                }
            });
        } else {
            // If form validation fails, re-enable the button
            $('#journal-btn').prop('disabled', false);
        }
    });

function load_coa_accounts(id) {
    $.ajax({
        type: 'POST',
        url: baseurl + 'manualjournals/coa_accounts',
        success: function (response) {
            $("#account-"+id).html(response);
        },
        error: function (xhr, status, error) {
            console.error("Error:", xhr.responseText);
        }
    });
}

$(document).ready(function () { 
    // $('#journal-btn').prop('readonly', true); 
    
    let rowCount = 1;

    // Add new journal row
    $('#addjournalbtn').on('click', function () {
        const newRow = `
            <tr>
                <td>
                    <select name="account[]" id="account-${rowCount}" class="form-control form-select required responsive-width-elements" required>
                    </select>
                </td>
                <td>
                    <textarea name="note[]" id="note-${rowCount}" class="form-textarea form-control responsive-width-elements"></textarea>
                </td>
                <td>
                    <input type="number" name="debit[]" id="debit-${rowCount}" class="form-control text-right debit-field responsive-width-elements">
                </td>
                <td>
                    <input type="number" name="credit[]" id="credit-${rowCount}" class="form-control text-right credit-field responsive-width-elements">
                </td>
                <td>
                    <button type="button" class="btn btn-crud btn-sm btn-secondary remove-row"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        // Append the new row to the table body
        $('#journal-rows').append(newRow);
        $("#account-"+rowCount).select2();
        load_coa_accounts(rowCount);
        rowCount++;
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