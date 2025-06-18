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
                    <li class="breadcrumb-item"><?php echo "Inventory Aging Report"; ?></li>
                </ol>   
            </nav>
            <div class="row">
                <div class="col-12">
                    <h4 class="card-title"><?php echo  "Inventory Aging Report"; ?></h4><hr>
                </div>
                
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
              
                    <form action="<?php echo base_url() ?>reports/inventory_aging_report" method="post"
                        role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-row">                                  
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-form-label"
                                           for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                          <input type="date" name="filter_expiry_date_to" value="<?=date('Y-m-d')?>" class="form-control required">
                                        </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 mt-16px">
                                    <button class="btn btn-crud btn-primary mt-1" type="submit" name="filter_search_btn" id="filter_search_btn">Get</button>
                                    </div>
                                </div>

                        </form>
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
                        <div class="overflow-auto">
                            <table id="salesordertable1" class="table table-striped table-bordered zero-configuration" >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('Sl No'); ?></th>
                                        <th><?php echo $this->lang->line('Item Code'); ?></th>
                                        <th><?php echo $this->lang->line('Item Description'); ?></th>
                                        <th><?php echo $this->lang->line('Onhand'); ?></th>
                                        <?php foreach ($months as $month): ?>
                                        <th><?= $month; ?></th>
                                        <?php endforeach; ?>
                                        
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
        { 'width': '5%' }, 
        { 'width': '5%' },
        { 'width': '5%' },
        { 'width': '5%' }, 
        { 'width': '5%' },
        { 'width': '5%' }, 
        { 'width': '5%' },
        { 'width': '5%' },
        { 'width': '5%' }, 
        { 'width': '5%' },
        { 'width': '5%' }, 
        { 'width': '5%' },
        { 'width': '5%' }
     
        ];
$(document).ready(function() {
   
    draw_data();
 
});
var selectedCategories = [];

function draw_data(warehouse = '', category = '') {
    var table = $('#salesordertable1').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        'ajax': {
            'url': "<?php echo site_url('Reports/inventoryaging_ajax_list')?>",
            'type': 'POST'
        },
        'columnDefs': [
            {
                'targets': [1, 2, 3, 4],
                'orderable': true,  
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
            },
            {   'targets': [1, 2, 3, 4],
                'searchable': true 
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
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
                    modifier: {
                        page: 'all'
                    }
                }
            },
            {
                extend: 'pdfHtml5',
                footer: true,
                orientation: 'landscape',
                action: function (e, dt, button, config) {
                    dt.ajax.params().is_export = true;
                    dt.one('preXhr', function (e, s, data) {
                        data.is_export = true;
                    });
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    dt.ajax.params().is_export = false;
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ]
    });

  
}

</script>