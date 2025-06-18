<!-- erp2024 newly added page 07-06-2024 starts -->
<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="card-title"><?php echo $this->lang->line('Material Request') ?></button>
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
        <div  class="card-body">
           <form  method="post" id="data_form">
             <input type="hidden" id="action-url" value="SalesOrders/materialrequestaction">
                <div class="container-fluid">
                    <div class="col-md-4">
                        <label class="col-form-label">To Warehouse*</label>
                        <select name="warehouse_to" id="warehouse_to" class="form-control required" required>
                            <option value="">Select Warehouse</option>
                            <?php
                                foreach($warehouses as $warehouse){
                                    echo '<option value="'.$warehouse['id'].'">'.$warehouse['title'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <hr>

                    <!-- ====================================================================  -->
                        <div id="saman-row">
                            <table class="table table-striped table-bordered zero-configuration dataTable">
                                <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="30%" class="pl-14"><?php echo $this->lang->line('Product') ?></th>
                                    <th width="30%" class="pl-14"><?php echo $this->lang->line('Transfer From') ?></th>
                                    <th width="7%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th width="5%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $i=0;
                                foreach($products as $product){ $prdid = $product['pid']; ?>
                                    <tr>
                                        <td>
                                            <select name="product-name[]" id="product-name-<?=$i?>" class="form-control" onchange="warehouseList('<?=$i?>')">
                                                <option value=""><?php echo $this->lang->line('Select Product') ?></option>
                                                <?php
                                                    foreach($products as $row){
                                                        echo '<option value="'.$row['pid'].'">'.$row['product_name'].' - '.$row['product_code'].'</option>';
                                                    }
                                                ?>
                                            </select>   
                                        </td>
                                        <td>
                                            <select name="warehousefrom[]" id="warehousefrom-<?=$i?>" class="form-control warehousefrom">
                                                <option value=""><?php echo $this->lang->line('Select Warehouse') ?></option>
                                                
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control req prc" name="transferqty[]" id="transferqty-<?=$i?>" onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()" autocomplete="off"></td>
                                        <td class="text-center">--</td>
                                    </tr>
                                <?php $i++; } ?>

                                <tr class="last-item-row sub_c tr-border">
                                    <td class="add-row no-border">
                                        <button type="button" class="btn btn-secondary" aria-label="Left Align"
                                                data-toggle="tooltip"
                                                data-placement="top" id="materialrequest-create">
                                            <i class="icon-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                    </td>
                                    <td colspan="7" class="no-border"></td>
                                </tr>
                                <tr class="sub_c" style="display: table-row;">
                                    <td colspan="2" class="no-border"></td>
                                    <td align="right" colspan="7" class="no-border">
                                        <input type="hidden" name="ganak" id="ganak" value="<?=$i?>">
                                        <input type="hidden" name="selectedProducts" id="selectedProducts" value="<?=$selectedProducts?>">
                                        <input type="submit" class="btn btn-lg btn-primary sub-btn" value="<?php echo $this->lang->line('Request Now') ?>" id="submit-data" data-loading-text="Creating...">

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
    var productVal = $("#product-name-" + id).val();
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
$('#materialrequest-create').on('click', function () {        
    selectedProductList();
    var cvalue = parseInt($('#ganak').val()) + 1;
    var nxt = parseInt(cvalue);
    $('#ganak').val(nxt);
    var functionNum = "'" + cvalue + "'";
    count = $('#saman-row div').length;
    var data = '<tr><td><select name="product-name[]" id="product-name-'+cvalue+'" class="form-control" onchange="warehouseList('+ cvalue +')"></select></td><td><select name="warehousefrom[]" id="warehousefrom-'+cvalue+'" class="form-control"><option value="">Select Warehouse</option></select></td><td><input type="text" class="form-control req prc" name="transferqty[]" id="transferqty-' + cvalue + '" onkeypress="return isNumber(event)" onkeyup="rowTotal(' + functionNum + '), billUpyog()" autocomplete="off" inputmode="numeric"></td><td class="text-center"><button type="button" data-rowid="' + cvalue + '" class="btn btn-sm btn-default removeProd" title="Remove" > <i class="fa fa-trash"></i> </button> </td></tr>';
    
    $('tr.last-item-row').before(data);
    row = cvalue;
});

</script>
<!-- erp2024 newly added page 07-06-2024 ends -->