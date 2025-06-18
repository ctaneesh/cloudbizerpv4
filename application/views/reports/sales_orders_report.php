<div class="content-body">
    <?php       
        // if (($msg = check_permission($permissions)) !== true) {
        //     echo $msg;
        //     return;
        // }       
    ?>
    <div class="card">
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><?php echo "Sales Orders Report"; ?></li>
                </ol>
                <!-- invoices/customer_leads?id=1 -->

                
            </nav>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                    <h4 class="card-title"><?php echo  "Sales Orders Report"; ?></h4>
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
                     <!-- =================== Right Section Starts============================= -->
                     <div class="col-12">
                        <div class="table-scroll">
                            <table id="salesordertable" class="table table-striped table-bordered zero-configuration" >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('#'); ?></th>
                                        <th><?php echo $this->lang->line('Order Date'); ?></th>
                                        <th><?php echo $this->lang->line('Sales Order Number'); ?></th>
                                        <th><?php echo $this->lang->line('Customer'); ?></th>
                                        <th><?php echo $this->lang->line('Item No'); ?></th>
                                        <th><?php echo $this->lang->line('Item Name'); ?></th>
                                        <th><?php echo $this->lang->line('Sales Person'); ?></th>
                                        <th><?php echo $this->lang->line('Total'); ?></th>
                                        <th><?php echo $this->lang->line('Status'); ?></th>
                                        <th><?php echo $this->lang->line('Invoice Status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>

                    <!-- =================== Right Section Ends=============================== -->
                </div>
            </div>

        </div>
    </div>
</div>




<script>
      var columnlist = [
        { 'width': '3%' }, 
        { 'width': '5%' }, 
        { 'width': '5%' },
        { 'width': '15%' }, 
        { 'width': '10%' },
        { 'width': '20%' },
        { 'width': '10%' },
        { 'width': '7%' },
        { 'width': '10%' },
        { 'width': '8%' }
        ];
$(document).ready(function() {
   
    draw_data();

 
});
var selectedCategories = [];



function draw_data(warehouse = '', category = '') {
    var table = $('#salesordertable').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        'ajax': {
            'url': "<?php echo site_url('Reports/salesorder_ajax_list')?>",
            'type': 'POST'
        },
        'columnDefs': [
            {
                'targets': [0],
                'orderable': false,  
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [],
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-center']);
                }
            },
            {
                'targets': [],
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
                    columns: [1, 2, 3, 4, 5, 6, 7, 8],
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
                    columns: [1, 2, 3, 4, 5, 6, 7, 8],
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ]
    });

  
}


</script>