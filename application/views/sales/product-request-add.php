<!-- erp2024 newly added page 07-06-2024 starts -->
<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
             <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('Productrequest') ?>"><?php echo $this->lang->line('Buy Requests'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Create a Purchase Request') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Create a Purchase Request') ?></button>
            </h4>
            
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                    
                </ul>
            </div>

        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <div class="message"></div>
            </div>            

        </div>
        <div  class="card-body card-block">
           <form  method="post" id="data_form">
             <input type="hidden" id="action-url" value="Productrequest/addaction">
                <div class="container-fluid">
                    <div class="row">
                        <!-- <div class="col-md-3">
                            <label class="col-form-label">To Warehouse*</label>
                            <select name="warehouse_to" id="warehouse_to" class="form-control required" required>
                                <option value="">Select Warehouse</option>
                                <?php
                                    // foreach($warehouses as $warehouse){
                                    //     echo '<option value="'.$warehouse['id'].'">'.$warehouse['title'].'</option>';
                                    // }
                                ?>
                            </select>
                        </div> -->
                        <div class="col-md-2">
                            <label class="col-form-label">Priority *</label>
                            <select name="priority" id="priority" class="form-control required" required>
                                <option value="">Select Priority</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="col-form-label">Expected By</label>
                            <input type="date" name="expectedby" id="expectedby" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label class="col-form-label">Note</label>
                            <textarea name="note" id="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <hr>

                    <!-- ====================================================================  -->
                    <div id="saman-row">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="30%" class="pl-14"><?php echo $this->lang->line('Product') ?></th>
                                    <th width="7%" class="pl-14"><?php echo $this->lang->line('On Hand Qty') ?></th>
                                    <th width="7%" class="text-center1"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $i=0;?>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control ui-autocomplete-input" name="product_name[]" placeholder="Enter Product name or Code" id="productnames-0" autocomplete="off" > 
                                            <input type="hidden" class="form-control required" name="productid[]" id="productid-0" autocomplete="off" > 
                                        </td>
                                        <td><input type="text" class="form-control" name="onhand[]" id="onhand-0" autocomplete="off" readonly> </td>
                                        <td><input type="text" class="form-control req prc required" name="transferqty[]" id="transferqty-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td>
                                        <td class="text-center">--</td>
                                    </tr>
                                <?php //$i++;  ?>

                                <tr class="last-item-row sub_c tr-no-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-crud btn-success" aria-label="Left Align"
                                                data-toggle="tooltip"
                                                data-placement="top" id="productrequest-add">
                                            <i class="icon-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                    </td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2" class="no-border"></td>
                                    <td align="right" colspan="7" class="no-border">
                                        <input type="hidden" name="ganak" id="ganak" value="<?=$i?>">
                                        <input type="hidden" name="billtype" id="billtype" value="productsearch">
                                        <input type="hidden" name="selectedProducts" id="selectedProducts" value="<?=$selectedProducts?>">
                                        <input type="submit" class="btn btn-crud btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Request Now') ?>" id="submit-btn" data-loading-text="Creating...">

                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    <!-- ====================================================================  -->
                </div>
           </form>
        </div>
    </div>
</div>
<!-- <script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script> -->
<script>
$("#warehouse_to").on('change', function(){
    $(".warehousefrom").empty();
    $(".warehousefrom").append('<option value="">Select Warehouse</option>');
});
function warehouseList(id){
    var productVal = $("#productnames-" + id).attr("data-id");
    $("#productid-" + id).val(productVal);
    var mainwarehouse = $("#warehouse_to").val();
    $.ajax({
        url: baseurl + 'Products/warehouse_by_productid',
        dataType: 'json',
        method: 'POST',
        data: {
            'prdid': productVal,
            'mainwarehouse' : mainwarehouse
        },
        success: function(data) {
            var selectElement = $("#warehousefrom-" + id); // Ensure this is the correct selector for your select element
            selectElement.empty(); // Clear existing options
            selectElement.append($('<option>', {
                value: '',
                text: 'Select Warehouse'
            }));
            if (data.length > 0) {
                $.each(data, function(index, item) {
                    selectElement.append($('<option>', {
                        value: item.id,
                        text: item.title+" - Stock("+item.stock_qty+")"
                    }));
                });
            } else {
                selectElement.append($('<option>', {
                    value: '',
                    text: 'No warehouses found'
                }));
            }
        }
    });
}
function selectedProductList(){
    var selectedProducts = $("#selectedProducts").val();
    id = $("#ganak").val();
    $.ajax({
        url: baseurl + 'Products/products_by_id',
        dataType: 'json',
        method: 'POST',
        data: {
            'selectedProducts': selectedProducts
        },
        success: function(data) {
            var selectElement = $("#product-name-" + id);
            selectElement.empty(); 
            selectElement.append($('<option>', {
                value: '',
                text: 'Select Product'
            }));
            if (data.length > 0) {
                $.each(data, function(index, item) {
                    selectElement.append($('<option>', {
                        value: item.id,
                        text: item.title+" - "+item.code
                    }));
                });
            } else {
                selectElement.append($('<option>', {
                    value: '',
                    text: 'No Products found'
                }));
            }
        }
    });
}

    $("#data_form").validate($.extend(true, {}, globalValidationOptions,{
        ignore: [],
        rules: {      
            priority: { required: true },
        },
        messages: {
            priority: "Select Priority",
        }
    }));

    $("#submit-btn").on("click", function (e) {
        e.preventDefault();
        $('#submit-btn').prop('disabled', true);
            let hasProductId = false;
        $('input[name="product_name[]"]').each(function () {
            if ($(this).val()) {
                hasProductId = true;
                return false;
            }
        });

        if (!hasProductId) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Product',
                text: 'Please select at least one product before submitting.',
            });
            $('#submit-btn').prop('disabled', false);
            return; // Stop further execution
        }

        if ($("#data_form").valid()) {
            var formData = new FormData($("#data_form")[0]);
            Swal.fire({
               title: "Are you sure?",
               text: "Do you want to add a new purchase request?",
               icon: "question",
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, proceed!',
               cancelButtonText: "No - Cancel",
               reverseButtons: true, 
               focusCancel: true,
               allowOutsideClick: false,
            }).then((result) => {
               if (result.isConfirmed) {  
                    $.ajax({
                        type: 'POST',
                        url: baseurl + 'Productrequest/addaction',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            window.location.href = baseurl + 'Productrequest';
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
               }
               else{
                    $('#submit-btn').prop('disabled', false);
               }
            });
        }
        else {
            $('#submit-btn').prop('disabled', false);
        }
    });
</script>
<!-- erp2024 newly added page 07-06-2024 ends -->