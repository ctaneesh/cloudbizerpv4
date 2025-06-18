<div class="app-content content container-fluid">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <?php

        if($this->session->flashdata('quoteupdate')) {
        $message = $this->session->flashdata('quoteupdate');
        ?>
                <div class="alert alert-info"><?php echo $message['message']; ?>

                </div>
                <?php
        }
        $due_date = $lead_validity;
        $enquiry_requested_date = date("Y-m-d");
        $lead_number = ($enquirymain && $enquirymain['lead_number']) ? $enquirymain['lead_number'] : $prefix.$customer_lead_number;
       
        ?>
        
        <div class="content-body">
            
           <?php
            if ($lead_id && empty($enquirymain)) 
            {
                $msg = check_permission();
                echo $msg;
                return;
            }
           ?>
            <form method="post" id="data_form" enctype="multipart/form-data">
            
                <!-- <form method="post" id="data_form" action="saveQuoteChanges"> -->
                <section class="card1">
                    <div class="card-header border-bottom1">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('enquiry') ?>"><?php echo $this->lang->line('Request For Quotes'); ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo 
                                $lead_number; ?></li>
                            </ol>
                        </nav> 
                       
                       <div class="row"> 
                       
                            <div class="col-lg-8"><h4 style="text-align:left;"> <?php echo $lead_number; ?></h4></div>
                            <div class="col-lg-4 text-right">
                                <?php 
                                 $statustext="";
                                if (($enquirymain['customer_lead_status'] == "Draft")) {
                                    $statustext = "Draft";
                                    $class = "alert-draft"; 
                                }
                                else if (($enquirymain['customer_lead_status'] == "Created")) {
                                    $statustext = "Created";
                                    $class = "alert-created"; 
                                }
                                else if (($enquirymain['customer_lead_status'] == "Sent")) {
                                    $statustext = "Sent";
                                    $class = "alert-success"; 
                                }
                                if($statustext)
                                {
                                    echo '<div class="btn-group alert '.$class.' text-center" role="alert">';
                                    echo "<span>".$statustext."</span>";
                                    echo '</div>';
                                }
                                
                                   
                                    ?>  
                            </div>
                       </div>
                    </div>
                    <div id="invoice-template" class="card-block">
                        <div class="row wrapper white-bg page-heading">

                            <div class="col-lg-12">
                           
                                <div id="invoice-items-details" class="pt-1">
                                    <div class="row">
                                        <div class="table-responsive1 col-sm-12">
                                            <div class="form-row mb-30">
                                                <input type="hidden" name="lead_number" id="lead_number" class="form-control"  Value="<?=$lead_number?>">
                                                <input type="hidden" name="customer_lead_number" id="customer_lead_number" class="form-control"  Value="<?=$customer_lead_number?>">
                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <label class="col-form-label" for="enquiry_requested_date"><?php echo $this->lang->line('Requested Date') ?></label><br>
                                                    <input type="date" name="enquiry_requested_date" id="enquiry_requested_date" class="form-control" min="<?=date("Y-m-d")?>" Value="<?=$enquiry_requested_date?>">
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss">
                                                        <label for="due_date" class="col-form-label"><?php echo $this->lang->line('Customer Enquiry Deadline'); ?></label>
                                                        <input type="date" class="form-control" name="due_date" id="due_date" required
                                                            placeholder="Due Date" autocomplete="off" value="<?php echo $due_date; ?>" min="<?php echo $due_date; ?>" data-original-value="<?php echo $due_date; ?>"/>
                                                    </div>
                                                </div>

                                                <!--erp2024 newly added 28-09-2024  -->
                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_reference_number" class="col-form-label"><?php echo $this->lang->line('Customer Reference Number'); ?></label>
                                                    <input type="text" name="customer_reference_number" id="customer_reference_number" class="form-control" placeholder="Reference#" value="<?=$enquirymain['customer_reference_number']?>" data-original-value="<?php echo $enquirymain['customer_reference_number']; ?>">
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_contact_person" class="col-form-label"><?php echo $this->lang->line('Customer Contact Person'); ?></label>
                                                    <input type="text" name="customer_contact_person" id="customer_contact_person" class="form-control" placeholder="Customer Contact Person"  value="<?=$enquirymain['customer_contact_person']?>" data-original-value="<?php echo $enquirymain['customer_contact_person']; ?>">
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="frmclasss"><label for="customer_contact_number" class="col-form-label"><?php echo $this->lang->line('Customer Contact Number'); ?></label>
                                                    <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Contact Number"  value="<?=$enquirymain['customer_contact_number']?>"  data-original-value="<?php echo $enquirymain['customer_contact_number']; ?>">
                                                    </div>                                    
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mt-2">
                                                    <div class="frmclasss"><label for="customer_contact_email" class="col-form-label"><?php echo $this->lang->line('Customer Contact Email'); ?></label>
                                                    <input type="email" name="customer_contact_email" id="customer_contact_email" class="form-control" placeholder="Customer Contact Email" value="<?php echo $enquirymain['customer_contact_email'] ?>" data-original-value="<?php echo $enquirymain['customer_contact_email']; ?>">
                                                    </div>                                    
                                                </div>
                                                <!--erp2024 newly added 28-09-2024 ends -->
                                                <div class="col-lg-8 mt-2">
                                                    <label class="col-form-label" for="enquiry_requested_date"><?php echo $this->lang->line('Enquiry Note') ?></label><br>
                                                    <textarea name="enquiry_message" id="enquiry_message"
                                                        class="form-control"><?php echo $enquirymain['email_contents'] ?></textarea>
                                                </div>
                                                <!-- Image upload sections starts-->
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12  mt-2 mb-1">
                                                    <label for="upfile-0" class="col-form-label"><?php echo $this->lang->line('Add Attachments'); ?></label>
                                                    <div class="row">                            
                                                        <div class="col-md-8">
                                                            <div class="d-flex">
                                                                <input type="file" name="upfile[]" id="upfile-0" class="form-control1 input-file" accept=".pdf, .jpg, .jpeg, .png" onchange="readURL(this);">
                                                                <img class="blah" src="" alt="your image" style="margin-left:10px; width:50px; height:50px;">
                                                                <button type="button" class="btn btn-crud btn-secondary btn-sm delete-btn" style="height:30px; height:30px; margin:3px;"  title="Remove"><i class="icon-trash" ></i></button>
                                                            </div>
                                                            <div id="uploadsection"></div>                                                
                                                        </div>                        
                                                        <div class="col-md-4">
                                                                <button class="btn btn-crud btn-secondary btn-sm mt-1" id="addmore_img"  title="Add More Files" type="button" ><i class="fa fa-plus-circle"></i> Add More</button>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Image upload sections ends -->
                                                   
                                                <!-- ===== Image sections starts ============== -->
                                                <div class="container-fluid table-scroll">
                                                    <div class="mt-2">
                                                        
                                                        <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12"> -->
                                                            <?php 
                                                        $imgcontains = 0;
                                                        if (!empty($images)) {
                                                            echo '<table class="table table-striped table-bordered">';
                                                            $imgcontains = 1;
                                                        
                                                            foreach ($images as $image) {
                                                                $file_extension = strtolower(pathinfo($image['file_name'], PATHINFO_EXTENSION));
                                                                $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png']);
                                                                $main_url = config_item('main_base_url');
                                                                $file_url = $main_url."uploads/{$image['file_name']}";
                                                                $img_tag = $is_image ? "<img src='{$file_url}' class='img-thumbnail' alt='{$image['actual_name']}' style='width:70px; height:70px;'>" : '<i class="fa fa-file-code-o fsize-70"></i>';
                                                                $download_attr = $is_image ? 'download' : '';
                                                                $icon = "Click to download <i class='fa fa-download'></i><br>";
                                                                $imgname = $image['actual_name'];
                                                        
                                                                if ($imgcontains % 5 == 1) {
                                                                    echo '<tr>';
                                                                }
                                                        
                                                                echo "<td class='text-center file-td-section'>";
                                                                echo "<div class='file-section'>";
                                                                echo $img_tag ? "{$img_tag}" : '';
                                                                echo '<p>'.$imgname.'</p>';
                                                                echo "<a href='{$file_url}' target='_blank' {$download_attr} class='btn btn-crud btn-sm btn-secondary'>{$icon}</a>&nbsp;";
                                                                echo "<button class='btn btn-crud btn-sm btn-secondary' onclick=\"delete_attachment('{$image['lead_attachment_id']}','{$image['file_name']}')\" type='button'><i class='icon-trash'></i></button>";
                                                                echo "</div>";
                                                                echo "";
                                                                echo "</td>";
                                                        
                                                                if ($imgcontains % 5 == 0) {
                                                                    echo '</tr>';
                                                                }
                                                        
                                                                $imgcontains++;
                                                            }
                                                        
                                                            // Close the last row if it wasn't closed
                                                            if (($imgcontains - 1) % 5 != 0) {
                                                                echo '</tr>';
                                                            }
                                                        
                                                            echo '</table>';
                                                        }
                                                        ?>

                                                    </div>
                                                </div>
                                                <!-- ===== Image sections ends ============== -->
                                                <!-- <div class="col-lg-6">
                                            <label for="enquiry_requested_date"><?php echo $this->lang->line('Enquiry Note') ?></label><br>
                                            <textarea name="enquiry_note" id="enquiry_note" class="form-control"></textarea></div> -->
                                              
                                            </div>
                                            <div id="saman-row" class="table-scroll">            
                                                <table  class="mt-1 table dataTable" >
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5% !important;">SL No.</th>
                                                            <th style="width:30% !important;"><?php echo $this->lang->line('Item No') ?></th>
                                                            <th style="width:40% !important;"><?php echo $this->lang->line('Item Name') ?></th>
                                                            <th style="width:10% !important;" class="text-xs-left">
                                                                <?php echo $this->lang->line('Quantity') ?></th>
                                                            <th style="width:15% !important;" class="text-xs-left"> <?php echo $this->lang->line('Actions') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    

                                                        <?php
                                                          $i = 0;
                                                        if($enquirymain)
                                                        { 
                                                            $label = ($enquirymain['customer_lead_status'] == 'Draft') ? "Create" :"Update"; 
                                                            ?>
                                                            <input type="hidden" name="lead_id" id="lead_id" value="<?=$enquirymain['lead_id']?>">
                                                            <input type="hidden" name="general_enqid" id="general_enqid" value="<?=$enquirymain['general_enqid']?>">
                                                            <input type="hidden" name="prid" id="prid" value="<?=$enquirymain['id']?>">
                                                            <?php
                                                              
                                                                $j=1;
                                                                if(!empty($products)){
                                                                    foreach ($products as $row) {
                                                                        $productname = $row['product_name'];
                                                                        $productcode = $row['product_code'];
                                                                        $product_qty = $row['quantity'];
                                                                        $product_id = $row['product_code'];
                                                                        echo '<tr><td class="text-center serial-number">'.$j++.'</td><td><input type="text" class="form-control wid95per" name="product_code[]" placeholder="Enter Product name or Code" id="code-'.$i.'" value="'.$productcode.'" onkeypress="autocompletePrdts('.$i.')" ></td><td><input type="text" class="form-control wid95per" name="product_name[]" placeholder="Enter Product name or Code" id="productname-'.$i.'" value="'.$productname.'" onkeypress="autocompletePrdts('.$i.')" ></td><td><input type="text" class="form-control req amnt wid65per" name="product_qty[]" id="amount-'.$i.'" onkeypress="return isNumber(event)" autocomplete="off"  value='.$product_qty.'><input type="hidden" class="pdIn" name="pid[]" id="pid-'.$i.'" value='.$product_id.'> </td>';
                                                                        
                                                                        // echo '<td><button type="button" data-rowid="'.$i.'" class="btn btn-sm btn-default removeProd1" title="Remove" onclick="removeTr('.$i.')"> <i class="icon-trash"></i> </button> </td>';
                                                                        echo '<td><button type="button" data-rowid="'.$i.'" class="btn btn-sm btn-default removeProd1" title="Remove" "> <i class="icon-trash"></i> </button> </td>';
                                                                        echo  '</tr>';
                                                                        $i++;
                                                                    }
                                                                } 
                                                        }                                              
                                                        else{
                                                            $label = "Create";
                                                        ?>
                                                            <tr>
                                                            <input type="hidden" name="lead_id" id="lead_id" value="0">
                                                                <td class="text-center serial-number">1</td>
                                                                <td >
                                                                    <input type="text" class="form-control wid95per" name="product_code[]"   placeholder="<?php echo $this->lang->line('Enter Product Code') ?>"  id='code-0' required>
                                                                </td>
                                                                <td >
                                                                    <input type="text" class="form-control wid95per" name="product_name[]"   placeholder="<?php echo $this->lang->line('Enter Product name') ?>"  id='productname-0' required>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control req amnt wid65per" name="product_qty[]" id="amount-0" value="0">
                                                                </td>
                                                                <td><button type="button" data-rowid="0" class="btn btn-sm btn-default removeProd1" title="Remove" onclick="removeTr('0')"> <i class="icon-trash"></i> </button></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr class="last-item-row sub_c tr-no-border">
                                                            <td class="add-row no-border" colspan="4">
                                                                <button type="button" class="btn btn-secondary"
                                                                    aria-label="Left Align" id="addproduct1">
                                                                    <i class="icon-plus-square"></i>
                                                                    <?php echo $this->lang->line('Add Row') ?>
                                                                </button>
                                                            </td>
                                                            <td colspan="7" class="no-border"></td>
                                                            <input type="hidden" value="enquiry/action" id="action-url">
                                                            <input type="hidden" value="<?=$i?>" name="counter" id="ganak">
                                                            <input type="hidden" class="pdIn" name="pid[]" id="pid-0" value="0">

                                                        </tr>
                                                        <!-- <tr class="tr-no-border">
                                                            <td align="left"  class="no-border" colspan="2"><input type="submit" class="btn btn-lg btn-secondary sub-btn" value="<?php echo "Save As Draft" ?>" id="draft_btn" data-loading-text="Creating..."></td>
                                                            <td align="right" colspan="4" class="no-border">
                                                                <input type="submit" class="btn btn-lg btn-primary sub-btn" value="<?php echo $label ?>" id="create_btn" data-loading-text="Creating...">
                                                                <input type="submit" class="btn btn-lg btn-secondary sub-btn" value="<?php echo "Send" ?>" id="send_btn" data-loading-text="Sending...">

                                                            </td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>

                                                <div class="row pt-3 pb-2" style="border-top:1px #d0d0d0 solid;">
                                                    <div class="col-xl-3 col-lg-3 col-sm-12">
                                                        <button class="btn btn-crud1 btn-secondary btn-lg unsavedisable-btns sub-btn" type="submit"
                                                        id="draft_btn" title="Save As Draft" style="">Save As Draft</button>           
                                                    </div>
                                                    <div class="col-xl-9 col-lg-9 col-sm-12 text-right">
                                                        <button class="btn btn-crud1 btn-secondary btn-lg unsavedisable-btns sub-btn" 
                                                        id="create_btn" style=""><?php echo $label ?></button>
                                                        <button class="btn btn-crud1 sub-btn btn-primary btn-lg" id="send_btn">Send</button>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>


                                </div>
                </section>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#data_form").validate({
            rules: {
                due_date: { required: true },
                // customer_reference_number : { required: true },
                customer_contact_number: {
                            phoneRegex :true
                }
            },
            messages: {
                customer_phone: "Enter Phone Number",
                due_date: "Enter a valid date",
                // customer_reference_number: "Enter Customer Reference",
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
    });

    $('#create_btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $('#create_btn').prop('disabled',true);
        // Validate the form
        if ($("#data_form").valid()) {
            var emailContents = $('#email_contents').val();
            var fileInput = $('#upfile-0').val(); // Get the file input value
            
            // Check if any product_name[] input has a value
            var productNameFilled = false;
            var imageFilled = false;
            $("input[name='product_name[]']").each(function() {
                if ($(this).val()) {
                    productNameFilled = true;
                    return false;
                }
            });
            $("input[name='upfile[]']").each(function() {
                if ($(this).val()) {
                    imageFilled = true;
                    return false; // Exit loop early if we find a filled product name
                }
            });
            if (productNameFilled) {

                if($("#lead_id").val() > 0 )
                {
                    targeturl = 'enquiry/editaction';
                }
                else{
                    targeturl = 'enquiry/action';
                }
              
                var form = $('#data_form')[0]; // Get the form element
                var formData = new FormData(form); // Create FormData object
                Swal.fire({
                        title: "Are you sure?",
                        "text":"Do you Want to Create a Request for a Quote?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: "No - Cancel",
                        reverseButtons: true,
                        focusCancel: true,
                        allowOutsideClick: false,
                        showCancelButton: true, 
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: baseurl + targeturl,
                                // url: baseurl + 'Invoices/customerenquiryaction',
                                type: 'POST',
                                data: formData,
                                contentType: false, 
                                processData: false,
                                success: function(response) {
                                    // window.location.href = baseurl + 'enquiry';
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                    console.log(error); // Log any errors
                                }
                            });
                        }
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            $('#create_btn').prop('disabled', false);
                        }
                        
                    });
            } else {
                Swal.fire({
                    title: 'Input Required',
                    text: 'To generate a lead, please enter at least one value in Product.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                $('#create_btn').prop('disabled',false);
            }
        }
        else{
            $('#create_btn').prop('disabled',false);
        }
    });

$('#draft_btn').on('click', function(e) {
    e.preventDefault();
    $('#draft_btn').prop('disabled', true);

    var imageFilled = false;

    $("input[name='upfile[]']").each(function() {
        if ($(this).val()) {
            imageFilled = true;
            return false; // Exit loop early
        }
    });

    var form = $('#data_form')[0];
    var formData = new FormData(form);

    $.ajax({
        url: baseurl + 'enquiry/draft_action',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status === 'Success') {
                var id = res.data;
                window.location.href = baseurl + 'enquiry/create?id=' + id;
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'An error occurred while saving the draft', 'error');
            console.log(error);
            $('#draft_btn').prop('disabled', false); // Re-enable on failure
        }
    });
});

$('#send_btn').on('click', function(e) {
    e.preventDefault(); // Prevent the default form submission
    $('#send_btn').prop('disabled',true);
    // Validate the form
    if ($("#data_form").valid()) {
        var emailContents = $('#email_contents').val();
        var fileInput = $('#upfile-0').val(); // Get the file input value
        
        // Check if any product_name[] input has a value
        var productNameFilled = false;
        var imageFilled = false;
        $("input[name='product_name[]']").each(function() {
            if ($(this).val()) {
                productNameFilled = true;
                return false;
            }
        });
        $("input[name='upfile[]']").each(function() {
            if ($(this).val()) {
                imageFilled = true;
                return false; // Exit loop early if we find a filled product name
            }
        });
        if (productNameFilled) {

            
            var form = $('#data_form')[0]; // Get the form element
            var formData = new FormData(form); // Create FormData object
            Swal.fire({
                    title: "Are you sure?",
                    html: "Do you want to send this Request for a Quote?<br>Once you send it, you can't modify this request.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: "No - Cancel",
                    reverseButtons: true,
                    focusCancel: true,
                    allowOutsideClick: false,
                    showCancelButton: true, 
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: baseurl + 'enquiry/send_action',
                            type: 'POST',
                            data: formData,
                            contentType: false, 
                            processData: false,
                            success: function(response) {
                                window.location.href = baseurl + 'enquiry';
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'An error occurred while generating the lead', 'error');
                                console.log(error); // Log any errors
                            }
                        });
                    }
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        $('#send_btn').prop('disabled', false);
                    }
                    
                });
        } else {
            Swal.fire({
                title: 'Input Required',
                text: 'To generate a lead, please enter at least one value in Product.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#send_btn').prop('disabled',false);
        }
    }
    else{
        $('#send_btn').prop('disabled',false);
    }
});

function delete_attachment(id,img_name) {

    swal.fire({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this item!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: "No - Cancel",
        reverseButtons: true,
        focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + 'enquiry/deletesubItem',
                    data: { selectedProducts: id, image: img_name },
                    dataType: 'json',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {}
                });
            }
    });
}
</script>