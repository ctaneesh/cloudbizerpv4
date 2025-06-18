<div class="content-body">
<?php       
//   if (($msg = check_permission($permissions)) !== true) {
//      echo $msg;
//      return;
//   }
 ?>
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><?php echo "Stock"; ?></li>
                </ol>
                <!-- invoices/customer_leads?id=1 -->

                
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                    <h4 class="card-title"><?php echo  "Stock"; ?></h4>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                <!-- <button class="btn btn-secondary btn-sm mt-1">
                    <i class="fa fa-calendar"></i> As of <strong><?=date('d-m-Y')?></strong>
                </button> -->
                <!-- <button class="btn btn-secondary btn-sm mt-1" onclick="convert_to_prf()">
                    <i class="fa fa-pdf"></i> PDF</strong>
                </button>
                    <a href="<?php echo base_url(); ?>reports/stock_to_pdf" class="btn btn-secondary btn-sm mt-1" target="_blank">PDF</a>
                    <a href="<?php echo base_url(); ?>reports/export_to_excel" class="btn btn-secondary btn-sm mt-1" target="_blank">Excel</a>-->
                </div> 
            </div>
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
            <div class="card-body">
                <div class="row">
                    <!-- =================== Left Section Starts============================= -->
                     <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12" style="border-right:1px solid #ccc; height:100vh;">

                        <!-- Warehouses Section -->
                                
                        <div class="expand-btn" data-target="#warehouseList">
                            Warehouses <i class="fa fa-angle-down"></i>
                        </div>
                        <div class="sidebarlist" id="warehouseList">
                            <ul>
                                <?php
                                if(!empty($warehouses))
                                {
                                    echo '<li onclick="selected_warehouse(0)" class="warehousecls active" id="0">All Warehouses</li>';
                                    foreach($warehouses as $warehouse){
                                        echo '<li onclick="selected_warehouse('.$warehouse['id'].')" class="warehousecls" id="'.$warehouse['id'].'">'.$warehouse['title'].'</li>';
                                    }
                                }
                                ?>
                                
                            </ul>
                            <input type="hidden" name="picked_warehouse" id="picked_warehouse" value="0">
                        </div>

                    
                        <div class="expand-btn-category" data-target="#categoryList">
                            Categories <i class="fa fa-angle-down"></i>
                        </div>
                        <div class="sidebarlist" id="categoryList" style="max-height:400px;overflow-y:auto;">
                        <ul>
                            <?php
                                if(!empty($categories))
                                {
                                    foreach($categories as $category){
                                        echo '<li><input type="checkbox" class="category-checkbox" value="'.$category['id'].'"> '.$category['title'].'</li>';
                                    }
                                }
                            ?>
                        </ul>

                        </div>
                     </div>
                    <!-- =================== Left Section Ends=============================== -->
                     <!-- =================== Right Section Starts============================= -->
                     <div class="col-xl-10 col-lg-9 col-md-9 col-sm-12">
                        <div class="table-scroll" style="max-width: 100%; overflow-x: auto;">
                            <table id="stockreporttable1" class="table table-striped table-bordered zero-configuration" style="width:120%;">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('#') ?></th>
                                        <th><?php echo $this->lang->line('Product Code') ?></th>
                                        <th><?php echo $this->lang->line('Product Name') ?></th>
                                        <th><?php echo $this->lang->line('Category') ?></th>
                                        <th><?php echo $this->lang->line('Unit Cost') ?></th>
                                        <th><?php echo $this->lang->line('Selling Price') ?></th>
                                        <th><?php echo $this->lang->line('On Hand') ?></th>
                                        <th><?php echo $this->lang->line('Total Value') ?></th>
                                        <th><?php echo "Purchase<br>Orders"; ?></th>
                                        <th><?php echo "Customer<br>Sales Orders"; ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- =================== Right Section Ends=============================== -->
                </div>
            </div>

        </div>
    </div>
</div>




<script>
      var columnlist = [
        { 'width': '5%' }, 
        { 'width': '5%' }, 
        { 'width': '35%' },
        { 'width': '12%' },
        { 'width': '4%' }, 
        { 'width': '4%' },
        { 'width': '2%' },
        { 'width': '4%' },
        { 'width': '4%' },
        { 'width': '2%' },
        { 'width': '45%' }
        ];
$(document).ready(function() {
   
    draw_data();

 
});
var selectedCategories = [];

// Listen for checkbox changes
$('.category-checkbox').change(function() {
    var value = $(this).val();
    if ($(this).is(':checked')) {
        selectedCategories.push(value);
    } else {
        var index = selectedCategories.indexOf(value);
        if (index > -1) {
            selectedCategories.splice(index, 1);
        }
    }
    var picked_warehouse = $("#picked_warehouse").val();
    if(picked_warehouse>0)
    {
        picked_warehouse1 = picked_warehouse;
    }
    else{
        picked_warehouse1 = "";
    }
    $('#stockreporttable1').DataTable().destroy();
    draw_data(picked_warehouse1,selectedCategories);
});
$(document).on('click', '.expand-btn', function(e) {
    e.preventDefault();
    var $this = $(this);
    var target = $this.data('target');
    
    $(target).slideToggle(); // Toggle visibility of the corresponding list
    
    // Toggle between angle-down and angle-up icons
    if ($this.find('i').hasClass('fa-angle-down')) {
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
   
});
$(document).on('click', '.expand-btn-category', function(e) {
    e.preventDefault();
    var $this = $(this);
    var target = $this.data('target');
    
    $(target).slideToggle(); // Toggle visibility of the corresponding list
    
    // Toggle between angle-down and angle-up icons
    if ($this.find('i').hasClass('fa-angle-down')) {
        $this.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
   
});

function draw_data_working(warehouse = '', category = '') {
    $('#stockreporttable1').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        // responsive: false,
        'ajax': {
            'url': "<?php echo site_url('Reports/stock_ajax_list')?>",
            'type': 'POST',
            'data': {
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                warehouse: warehouse,
                category: category
            }
        },
        'columnDefs': [
                {
                    'targets': [0,9],
                    'orderable': false,  // Only disable ordering for column 0
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [5, 6, 7],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [4],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-right']);
                    }
                }
            ],
        'columns': columnlist,
       'dom': 'Blfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            footer: true,
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                modifier: {
                    page: 'all' // Export all pages
                }
            }
        },
        {
            extend: 'pdfHtml5',
            footer: true,
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8],
                modifier: {
            page: 'all' // Export all pages
        }
            }
        }
    ]

    });
}
function draw_data(warehouse = '', category = '') {
    var table = $('#stockreporttable1').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        'ajax': {
            'url': "<?php echo site_url('Reports/stock_ajax_list')?>",
            'type': 'POST',
            'data': function(d) {
                d['<?=$this->security->get_csrf_token_name()?>'] = crsf_hash;
                d.warehouse = warehouse;
                d.category = category;
                d.is_export = false; 
            },
            'dataSrc': function(json) {
                if (!json || !json.data) {
                    console.error('Invalid data format from server:', json);
                    return [];
                }
                return json.data; // Ensure it returns an array
            }
        },
        'columnDefs': [
            {
                'targets': [0, 9],
                'orderable': false,  
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [6],
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [4,5,7],
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-right']);
                }
            }
        ],
        'columns': columnlist || [], // Ensure columnlist is defined
        'dom': 'Blfrtip',
        'buttons': [
            {
                extend: 'excelHtml5',
                footer: true,
                action: function (e, dt, button, config) {
                    dt.ajax.params().is_export = true;
                    dt.one('preXhr', function (e, s, data) {
                        data.is_export = true;
                    });
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                    dt.ajax.params().is_export = false;
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                    modifier: {
                        page: 'all'
                    }
                }
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                action: function (e, dt, button, config) {
                    dt.ajax.params().is_export = true;
                    dt.one('preXhr', function (e, s, data) {
                        data.is_export = true;
                    });
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    dt.ajax.params().is_export = false;
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ]
    });

}

function selected_warehouse(id){
    $('#stockreporttable1').DataTable().destroy();
    $(".warehousecls").removeClass("active");
    $("#"+id).addClass("active");
    $("#picked_warehouse").val(id);
    selectedProducts = [];
    $('.category-checkbox:checked').each(function() {
        selectedProducts.push($(this).val());
    });
    if(selectedProducts.length >0){
        category = selectedProducts;
    }
    else{
        category = "";
    }
    if(id>0)
    {
        draw_data(id,category);
    }
    else{
        draw_data("",category);
    }
}

function convert_to_prf(){
    var picked_warehouse = $("#picked_warehouse").val();
    if(picked_warehouse>0)
    {
        warehouse = picked_warehouse;
    }
    else{
        warehouse = "";
    }
    selectedProducts = [];
    $('.category-checkbox:checked').each(function() {
        selectedProducts.push($(this).val());
    });
    if(selectedProducts.length >0){
        category = selectedProducts;
    }
    else{
        category = "";
    }
    $.ajax({
            type: 'POST',
            url: baseurl +'Reports/stock_to_pdf',
            
            data: {
                "category":category,
                "warehouse":warehouse
            },
            success: function(response) {
                // deliveryReport();     
                // $('#submit-deliverynote').prop('disabled',false);
                // var responseData = JSON.parse(response);
                // var deliveryNoteData = responseData.data;

                // window.open(baseurl + 'DeliveryNotes/reprintnote?delivery=' + delivery + '&sales=' + sales + '&cust=' + cust + '&priceFlg=' + priceFlg, '_blank');
                // window.location.href = baseurl + 'SalesOrders/delivery_notes?id=' + deliveryNoteData;
                
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
}
</script>