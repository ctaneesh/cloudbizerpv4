<article class="content">    
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
               <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Average Costing'); ?></li>
            </ol>
      </nav>
      
      <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
               <h4 class="card-title"><?php echo $this->lang->line('Average Costing'); ?></h4>
            </div>
           
      </div>
      <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
      <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
      </div>
   </div>
    <div class="card card-block">
       
    
        <div class="card-body">
           
            <div class="col-12">                    
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label for="employee" class="col-form-label"><?php echo $this->lang->line('Product') ?></label>
                        <select name="product_id" id="product_id" class=" col form-control">
                            <?php echo '<option value="">Select a Product</option>'; ?>
                            <?php foreach ($products as $row) {
                                echo '<option value="' . $row['pid'] . '">' . $row['product_name'].'(' . $row['product_code'].')</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-32px">
                        <button type="button" name="search_btn" id="search_btn" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
            <hr>
            
            <div class="table-container table-scroll">
                <table id="avgcosting" class="table table-striped table-bordered zero-configuration w-100 dataTable">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo $this->lang->line('No') ?></th>                        
                        <th><?php echo $this->lang->line('Date') ?></th>
                        <th><?php echo $this->lang->line('Product') ?></th>
                        <th><?php echo $this->lang->line('Type') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Onhand Quantity') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Cost') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Average Cost') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Inventory Value') ?></th>
                        <th><?php echo $this->lang->line('Added By') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</article>

<script>
    $(document).ready(function() {
        draw_data();
   
    });
    // $("#product_id").on('change',function()
    // {
    //     $('#avgcosting').DataTable().destroy();
    //     draw_data($('#product_id').val()); 
    // });
    $("#search_btn").on('click',function()
    {
        $('#avgcosting').DataTable().destroy();
        draw_data($('#product_id').val());    
    });
    function draw_data(product_id) {
        table= $('#avgcosting').DataTable({
            'processing': true,
            'serverSide': true,
            'stateSave': true,
            // responsive: true,
            <?php datatable_lang();?>
            'order': [],
            'ajax': {
                'url': "<?php echo site_url('Reports/ajax_averagecost_list')?>",
                'type': 'POST',
                'data': {
                    '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                    // start_date: start_date,
                    // end_date: end_date,            
                    product_id: product_id,
                }
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
                    'targets': [4,5],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [6,7,8],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-right']);
                    }
                }
            ],
            // 'columns': columnlist,
            // dom: '<"results-and-buttons-wrapper"lfB>rtip',
            // buttons: [
            //     {
            //         text: 'Results from...',
            //         className: 'results-from',
            //         action: function(e, dt, node, config) {
            //             // Optional: Add custom action here if needed
            //         }
            //     },
            //     {
            //         extend: 'excelHtml5',
            //         footer: true,
            //         exportOptions: {
            //             columns: [1, 2, 3, 4, 5]
            //         }
            //     }
            // ]
        });
        startsection = "";
        endsection = "";
        // if(start_date ==''){       
        //     $('#DeliveryNotes_length label').css('margin-top', '5px');
        // }
        // if(start_date !=''){
        //     start_date = date_convert(start_date);
        //     startsection = "<label>Results from : </label><strong> "+start_date+"</strong>";        
        //     $('#DeliveryNotes_length label').css('margin-top', '10px');
        // }
        // if(end_date!=''){
        //     end_date = date_convert(end_date);
        //     endsection = " - <strong> "+end_date+"</strong>";
        // }
        // if(start_date==end_date){
        //     endsection= "";
        // }
        // $('.results-from').html(startsection + endsection);
    }
</script>