
<div class="content-body">
    <div class="card">
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <div class="message"></div>
            </div>
            <div class="card-body">
                <form method="post" id="data_form" enctype="multipart/form-data" action="<?php echo base_url() ?>Invoices/customerenquiryeditaction">
                    <div class="row" >
                    
                    <div class="col-12 mb-2" style="border-bottom:1px #d0d0d0 solid; padding:5px;">
                        <div class="row">
                            <div class="col-9">
                                <div class="fcol-sm-12">
                                    <h3 class="title">
                                        <?php echo "Edit Lead" ?> 
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-right">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                  <input type="hidden" name="lead_id" value="<?php echo $enquirymain['lead_id']; ?>">
                    <div class="col-md-3 mb-1">
                        <div class="row">
                            <div class="col-sm-6"><label ><?php echo 'Enquiry Number'; ?></label>
                                <input type="text" class="form-control" name="lead_number" id="lead_number" placeholder="Enquiry Number" autocomplete="off" value="<?php echo $enquirymain['lead_number']; ?>" readonly/>
                            </div> 
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-12"><?php echo 'Enquiry Number'; ?><br><br></div>
                                    <?php if($enquirymain['customer_type']=='new'){ $new = "checked";   $existing = "";}
                                        else{
                                            $new = "";
                                            $existing = "checked";
                                        } ?>
                                        <div class="form-check col-6">
                                            <input class="form-check-input" type="radio" name="customerType" id="customerType2" value="existing"  <?php echo $existing; ?>>
                                            <label class="form-check-label" for="customerType2">
                                                Existing
                                            </label>
                                        </div>
                                        <div class="form-check col-6">                                        
                                            <input class="form-check-input" type="radio" name="customerType" id="customerType1" value="new" <?php echo $new; ?>>
                                            <label class="form-check-label" for="customerType1">
                                                New
                                            </label>
                                        </div>
                                </div>
                            </div> 
                        </div>                                   
                    </div>
                    <div class="col-md-3 mb-1">
                            <div class="frmSearch col-sm-12"><label for="customer_name" class="caption" id="customerLabel"><?php echo $this->lang->line('Search Customer'); ?></label>
                                <!-- <input type="text" class="form-control" name="cst" id="" -->
                                <input type="text" class="form-control customer_name" name="customer_name" id="customer-search"
                                        placeholder="Enter Customer Name or Mobile Number to search"
                                        autocomplete="off" required value="<?php echo $enquirymain['customer_name']; ?>"/>
                                <div id="customer-search-result" class="customer-search-result"></div>
                        </div>                                    
                    </div>

                    <div class="col-md-3 mb-1">
                            <div class="col-sm-12"><label for="customer_phone" class="caption"><?php echo 'Phone'; ?></label>
                                <input type="number" class="form-control" name="customer_phone" id="customer_phone" placeholder="Contact Number" autocomplete="off" value="<?php echo $enquirymain['customer_phone']; ?>"/>
                        </div>                                    
                    </div>

                    <div class="col-md-3 mb-1">
                            <div class="col-sm-12"><label for="customer_email" class="caption"><?php echo 'Email'; ?></label>
                                <input type="text" class="form-control" name="customer_email" id="customer_email"placeholder="Contact Email" autocomplete="off" value="<?php echo $enquirymain['customer_email']; ?>"/>
                        </div>                                    
                    </div>
                    <div class="col-md-3 mb-1">
                            <div class="col-sm-12"><label for="customer_address"  class="caption"><?php echo 'Address'; ?></label>
                                <input type="text" class="form-control" name="customer_address"  id="customer_address" placeholder="Contact Address" autocomplete="off" value="<?php echo $enquirymain['customer_address']; ?>"/>
                        </div>                                    
                    </div>                    
                    
                    <div class="col-md-3 mb-1">
                            <div class="col-sm-12"><label for="date_received" class="caption"><?php echo 'Date Received'; ?></label>
                                <input type="date" class="form-control" name="date_received" id="date_received" placeholder="Date Received" autocomplete="off" value="<?php echo $enquirymain['date_received']; ?>"/>
                        </div>                                    
                    </div>
                    
                    <div class="col-md-3 mb-1">
                            <div class="col-sm-12"><label for="due_date" class="caption"><?php echo 'Due Date'; ?></label>
                                <input type="date" class="form-control" name="due_date" id="due_date" placeholder="Due Date" autocomplete="off" value="<?php echo $enquirymain['due_date']; ?>"/>
                        </div>                                    
                    </div>
                    
                    <div class="col-md-3 mb-1">
                            <div class="col-sm-12"><label for="source_of_enquiry" class="caption"><?php echo 'Source of Enquiry'; ?></label>
                               <select class="form-control form-select" id="source_of_enquiry" name="source_of_enquiry">
                                    <option value="">Select Source</option>
                                    <option value="Email" <?php if($enquirymain['source_of_enquiry']=="Email"){ echo "selected"; } ?>>Email</option>
                                    <option value="Direct" <?php if($enquirymain['source_of_enquiry']=="Direct"){ echo "selected"; } ?>>Direct</option>
                               </select>
                        </div>                                    
                    </div>

                    <div class="col-md-3 mb-1">
                        <div class="col-sm-12"><label for="assignedto" class="caption"><?php echo 'Assigned to'; ?></label>
                            <select class="form-control form-select" id="assignedto" name="assignedto">
                                <option value="">Select </option>
                                <option value="Rafeeq" <?php if($enquirymain['assigned_to']=="Rafeeq"){ echo "selected"; } ?>>Rafeeq</option>
                                <option value="Shafeeq" <?php if($enquirymain['assigned_to']=="Shafeeq"){ echo "selected"; } ?>>Shafeeq</option>
                            </select>
                        </div>                                    
                    </div>
                    <div class="col-md-3 mb-1">
                        <div class="col-sm-12"><label for="enquiry_status" class="caption"><?php echo 'Status'; ?></label>
                            <select class="form-control form-select" id="enquiry_status" name="enquiry_status">
                                <option value="">Select Status</option>
                                <option value="Open" <?php if($enquirymain['enquiry_status']=="Open"){ echo "selected"; } ?>>Open</option>
                                <option value="Assigned" <?php if($enquirymain['enquiry_status']=="Assigned"){ echo "selected"; } ?>>Assigned</option>
                                <option value="Closed" <?php if($enquirymain['enquiry_status']=="Closed"){ echo "selected"; } ?>>Closed</option>
                            </select>
                        </div>                                    
                    </div>

                    <div class="col-md-6 mb-1">
                            <div class="col-sm-12"><label for="comments" class="caption"><?php echo 'Comments'; ?></label>
                               <textarea class="form-control"  placeholder="Comments" name="comments" id="comments"><?php echo  $enquirymain['comments']; ?></textarea>
                        </div>                                    
                    </div>
                    <div class="col-md-12 mb-1">
                            <div class="col-sm-12"><label for="email_contents" class="caption"><?php echo 'Email Contents'; ?></label>
                               <textarea class="form-control"  placeholder="email_contents" id="email_contents" name="email_contents"><?php echo  $enquirymain['email_contents']; ?></textarea>
                        </div>                                    
                    </div>
                    <div class="col-md-3 mb-1"></div>
                    <div class="col-md-12 mb-1">
                        <div class="col-sm-12"><label for="cst" class="caption"><?php echo '<h5><u>Uploads(Allows pdf,jpg,.csv only)</u></h5>'; ?></label>
                        </div>  
                    </div>

                    <div class="col-md-5 mb-1">
                            <div class="row">
                                
                            <?php 
                            
                                if(!empty($images)){
                                    foreach($images as $image){
                                        $file_extension = pathinfo($image['file_name'], PATHINFO_EXTENSION);
                                        $download = "";
                                        if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'png') {
                                            $download = "download";
                                        }
                                        echo '<div class="col-10 mb-2">';
                                        echo "<a target='_blank' href='".base_url()."uploads/".$image['file_name']."' $download  class='btn btn-secondary'>Click to download <i class='fa fa-download'></i></a>&nbsp; <button class='btn btn-default' onclick=\"deleteitem('".$image['lead_attachment_id']."')\" type='button'><i class='fa fa-trash'></i></button>";
                                        echo '</div>';

                                
                                    }
                                } 
                            ?>

                                <!-- <div class="col-10">
                                    <input type="file" name="upfile[]" id="upfile-0" class="form-control">
                                </div> -->
                                <div class="col-10" id="uploadsection"></div>
                                
                            </div>
                            <div class="col-10 mt-3 text-right">
                                    <button class="btn btn-primary" id="addmore_img" type="button">Add More</button>
                            </div>
                        </div>                                    
                    </div>
                    
                    <hr>
                    
                    <div class="col-md-12 mb-3" style="border-top:1px #d0d0d0 solid;">
                        <div class="text-right mt-2">
                        <button class="btn btn-primary">Save</button>
                        </div>  
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#addmore_img').click(function() {
            var fileId = $('.form-control').length; // Get the number of existing file inputs
            var newInput = '<div class="col-10 mt-1"><input type="file" name="upfile[]" id="upfile-' + fileId + '" class="form-control"></div>';
            newInput += '<div class="col-2 mt-1"><button type="button" class="btn  btn-danger delete-btn"><i class="fa fa-trash"></i></button></div>';
            $('#uploadsection').append('<div class="row">' + newInput + '</div>');
        });

        // Event delegation to handle delete button clicks on dynamically added elements
        $('#uploadsection').on('click', '.delete-btn', function() {
            $(this).closest('.row').remove(); // Remove the parent row containing the file input and delete button
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var customerTypeRadios = document.querySelectorAll('input[name="customerType"]');
        var customerLabel = document.getElementById('customerLabel');

        customerTypeRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                $(".customer_name").removeAttr("id");
                $(".customer-search-result").removeAttr("id");
                $('.customer_name').val("");
                $('#customer_phone').val("");
                $('#customer_email').val("");
                $('#customer_address').val("");
                if (this.value === 'new') {
                    customerLabel.textContent = "<?php echo $this->lang->line('customer_name'); ?>";
                    
                } else {
                    customerLabel.textContent = "<?php echo $this->lang->line('Search Customer'); ?>";
                    $(".customer_name").attr("id","customer-search");
                    $(".customer-search-result").attr("id","customer-search-result");
                }
            });
        });
    });

    $("#customer-search").keyup(function () {
        $.ajax({
            type: "GET",
            url: baseurl + 'search_products/customersearch',
            data: 'keyword=' + $(this).val() + '&' + crsf_token + '=' + crsf_hash,
            beforeSend: function () {
                $("#customer-search").css("background", "#FFF url(" + baseurl + "assets/custom/load-ring.gif) no-repeat 165px");
            },
            success: function (data) {
                console.log(data);
                $("#customer-search-result").show();
                $("#customer-search-result").html(data);
                $("#customer-search").css("background", "none");

            }
        });
    });

function selectedCustomer(cid, cname, cadd1, cadd2, ph, email) {
    $('#customer-search').val(cname);
    $('#customer_phone').val(ph);
    $('#customer_email').val(email);
    $('#customer_address').val(cadd1);
    $("#customer-search-result").hide();
}
function deleteitem(id){

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
                url: baseurl + 'Invoices/deletesubItem',
                data: { selectedProducts: id },
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