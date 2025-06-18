<div class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Account Statement') ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Account Statement') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <p><?php echo $this->lang->line('Account') ?> : <?php echo $filter[5] ?></p>

            <div class="table-container table-scroll">
                <table id="entries" class="table table-striped table-bordered zero-configuration dataTable" width="100%" style="width:100% !important;">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                        <th><?php echo $this->lang->line('Descriptions') ?></th>

                        <th class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Credit') ?></th>

                        <th class="text-right"><?php echo $this->lang->line('Balance') ?></th>


                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                        <th><?php echo $this->lang->line('Descriptions') ?></th>

                        <th class="text-right"><?php echo $this->lang->line('Debit') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Credit') ?></th>

                        <th class="text-right"><?php echo $this->lang->line('Balance') ?></th>


                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">


    $(document).ready(function () {
        var columnlist = [
        { 'width': '7%' }, 
        { 'width': '15%' },
        { 'width': '10%' }, 
        { 'width': '10%' },
        { 'width': '' }
        ];

        $('#entries').DataTable({
            "fixedHeader": true,
             responsive: true,
            "processing": true, // Show processing indicator
            "serverSide": true, // Enable server-side processing
            "ajax": {
                "url": baseurl + 'reports/statements',
                "type": "POST",
                "data": function(d) {  // Use function to add dynamic data
                    d.ac = '<?php echo $filter[0]; ?>';
                    d.sd = '<?php echo $filter[2]; ?>';
                    d.ed = '<?php echo $filter[3]; ?>';
                    d.ty = '<?php echo $filter[1]; ?>';
                    d['<?php echo $this->security->get_csrf_token_name(); ?>'] = crsf_hash;
                }
            },
            columnDefs: [
                { targets: [4], orderable: false },
                { searchable: true, targets: [0, 1, 2, 3] } // Example for first three columns
            ],
            dom: '<"results-and-buttons-wrapper"lfB>rtip',
            buttons: [
                
                {
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0,1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4], // Add a comma here
                        format: {
                            body: function (data, row, column, node) {
                                return data; // Return data as is
                            }
                        }
                    }
                }

            ],
             'columns': columnlist,
            // "columns": [
            //     { "data": "date", "width": "20%", "className": "text-center" },
            //     { "data": "note", "width": "25%" },
            //     { "data": "debit", "className": "text-right" },
            //     { "data": "credit", "className": "text-right" },
            //     { "data": "balance", "className": "text-right" }
            // ],
            "error": function(xhr, status, error) {
                $('#response').html('Error: ' + error);
            }
        });

       



    });

</script>
