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
                    <li class="breadcrumb-item"><?php echo $this->lang->line('Purchase Orders Report'); ?></li>
                </ol>
                <!-- invoices/customer_leads?id=1 -->

                
            </nav>
            <div class="row">
                <div class="col-12"> 
                    <h4 class="card-title"><?php echo $this->lang->line('Purchase Orders Report'); ?></h4>
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
                                        <th><?php echo $this->lang->line('Date'); ?></th>
                                        <th><?php echo $this->lang->line('Code'); ?></th>
                                        <th><?php echo $this->lang->line('Product'); ?></th>
                                        <th><?php echo $this->lang->line('Qty'); ?></th>
                                        <th><?php echo $this->lang->line('Price'); ?></th>
                                        <th><?php echo $this->lang->line('Supplier Name'); ?></th>                                        
                                        <th><?php echo $this->lang->line('Phone Number'); ?></th>
                                        <th><?php echo $this->lang->line('Address'); ?></th>
                                        <th><?php echo $this->lang->line('Added By'); ?></th>
                                        <th><?php echo $this->lang->line('Approved By'); ?></th>
                                        <th><?php echo $this->lang->line('Sent By'); ?></th>
                                       
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
        { 'width': '10%' }, 
        { 'width': '5%' },
        { 'width': '15%' }, 
        { 'width': '6%' },
        { 'width': '8%' },
        { 'width': '10%' },
        { 'width': '7%' },
        { 'width': '10%' },
       { 'width': '8%' },
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
            'url': "<?php echo site_url('Reports/purchaseorder_ajax_list')?>",
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
                    columns: [1, 2, 3, 4, 5, 6, 9, 10, 11],
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
                    columns: [1, 2, 3, 4, 5, 6, 9, 10, 11],
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ]
    });

  
}


</script>