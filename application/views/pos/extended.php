<div class="content-body">
    <?php       
    // if (($msg = check_permission($permissions)) !== true) {
    //     echo $msg;
    //     return;
    // }
    ?>
    <div class="card">
        <div class="card-header">
            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('ProductSales') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"> <?php echo $this->lang->line('ProductSales') ?> </h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
            <hr>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                    <div class="col-lg-1 col-md-2 responsive-mb-1"><?php echo $this->lang->line('Invoice Date') ?></div>
                    <div class="col-lg-2 col-md-2 responsive-mb-1">
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="<?=$oneMonthBefore?>" autocomplete="off"/>
                        
                    </div>
                    <div class="col-lg-2 col-md-2 responsive-mb-1">
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="<?=date('Y-m-d')?>" autocomplete="off"/>
                    </div>

                    <div class="col-lg-2 col-md-2 responsive-mb-1">
                        <input type="button" name="search" id="search" value="Search" class="btn btn-crud btn-info btn-sm"/>
                    </div>

                    <div class="col-md-4 text-right responsive-mb-1">
                    <input type="button" name="exportpdf" id="export_pdf" value="Export" class="btn btn-crud btn-info btn-sm"/>
                    </div>

                </div>
                <hr>
                <div class="table-container">
                    <table id="invoices_rp" class="table table-striped table-bordered  dataex-res-constructor" style="width:100% !important;">
                        <thead>
                        <tr>
                            <th class="text-center"><?php echo $this->lang->line('No') ?></th>
                            <th class="text-center"> <?php echo $this->lang->line('Invoice') ?>#</th>
                            <th><?php echo $this->lang->line('Customer') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                            <th><?php echo $this->lang->line('Item Name') ?></th>
                            <th><?php echo $this->lang->line('Code') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Qty') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                            <!-- <th><?php echo $this->lang->line('Tax') ?></th> -->

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>

                        <tfoot>
                        <tr>
                            <th class="text-center"><?php echo $this->lang->line('No') ?></th>
                            <th class="text-center"> <?php echo $this->lang->line('Invoice') ?>#</th>
                            <th><?php echo $this->lang->line('Customer') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                            <th><?php echo $this->lang->line('Item Name') ?></th> 
                            <th><?php echo $this->lang->line('Code') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Qty') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                            <th class="text-right"><?php echo $this->lang->line('Discount') ?></th>
                            <!-- <th><?php echo $this->lang->line('Tax') ?></th> -->
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Delete Invoice') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this invoice') ?> ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="pos_invoices/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        draw_data();

        function draw_data(start_date = '', end_date = '') {
            $('#invoices_rp').DataTable({
                "fixedHeader": true,
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                // responsive: true,
                <?php datatable_lang();?>
                'order': [],
                'ajax': {
                    'url': "<?php echo site_url('pos_invoices/extended_ajax_list')?>",
                    'type': 'POST',
                    'data': {
                        '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                        start_date: start_date,
                        end_date: end_date
                    }
                },
                'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,  // Only disable ordering for column 0
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [1, 3,6],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [7,8],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-right']);
                        }
                    }
                ],
                dom: 'Blfrtip',
                 pageLength: 100,
                lengthMenu: [10, 20, 50, 100, 200, 500],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [0,1, 2, 3, 4, 5,6,7]
                        }
                    }
                ],
            });
        }

        $('#search').click(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('#invoices_rp').DataTable().destroy();
                draw_data(start_date, end_date);
            } else {
                alert("Date range is Required");
            }
        });
        $('#export_pdf').click(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            
            if (start_date != '' && end_date != '') {
                // Create the form dynamically
                var form = $('<form action="<?php echo site_url('pos_invoices/exportpdf')?>" method="POST" target="_blank"></form>');

                // Add hidden input fields for start_date and end_date
                form.append('<input type="hidden" name="start_date" value="' + start_date + '">');
                form.append('<input type="hidden" name="end_date" value="' + end_date + '">');

                // Append form to container
                $('body').append(form); // Append to body or another suitable element in the DOM

                // Programmatically submit the form
                form.submit();
            } else {
                alert("Date range is required");
            }   
        });

    });
</script>
