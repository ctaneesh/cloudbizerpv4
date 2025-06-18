<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header border-bottom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('reports/taxstatement') ?>"><?php echo $this->lang->line('TAX Statement') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $filter[2]." ".$this->lang->line('TAX Statement'); ?></li>
                </ol>
            </nav>
            <h5><?php echo $filter[2]." ".$this->lang->line('TAX Statement') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-body ">
            
            <p><?php echo $filter[2] ?> Report</p>


            <table id="entries" class="table table-striped table-bordered zero-configuration dataTable" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <?php if ($filter[2] == 'Sales') { ?>
                        <th><?php echo $this->lang->line('Invoice') ?></th>
                        <th><?php echo $this->lang->line('Customer') ?></th>
                        <th><?php echo $this->lang->line('Company') ?></th>
                     <?php } else { ?>

                        <th>Receipt</th>
                        <th><?php echo $this->lang->line('Supplier') ?></th>
                        <th><?php echo $this->lang->line('Company') ?></th>
                    <?php } ?>


                    <th><?php echo $this->lang->line('Amount') ?></th>

                    <th>TAX Amount</th>

                    <th><?php echo $this->lang->line('Balance') ?></th>

                </tr>
                </thead>
                <tbody>
                </tbody>


            </table>
        </div>
    </div>
</article>
<script type="text/javascript">


    $(document).ready(function () {
        $('#entries').DataTable({
            "processing": true, // Show processing indicator
            "serverSide": true, // Enable server-side processing
            "ajax": {
                "url": baseurl + 'reports/taxviewstatements_load',
                "type": "POST",
                "data": function(d) {  // Use function to add dynamic data
                    d.sd = '<?php echo $filter[0]; ?>';
                    d.ed = '<?php echo $filter[1]; ?>';
                    d.ty = '<?php echo $filter[2]; ?>';
                    d.loc = '<?php echo $filter[3]; ?>';
                    d['<?php echo $this->security->get_csrf_token_name(); ?>'] = crsf_hash;
                }
            },
            columnDefs: [
                { targets: [5], orderable: false },
                { searchable: true, targets: [0, 1, 2,3] } // Example for first three columns
            ],
            dom: '<"results-and-buttons-wrapper"lfB>rtip',
            buttons: [
                
                {
                    extend: 'excelHtml5',
                    footer: true,
                    exportOptions: {
                        columns: [0,1, 2, 3, 4,5]
                    }
                },
                // {
                //     extend: 'pdfHtml5',
                //     footer: true,
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4], // Add a comma here
                //         format: {
                //             body: function (data, row, column, node) {
                //                 return data; // Return data as is
                //             }
                //         }
                //     }
                // }

            ], 
            "columns": [
                { "data": "invoice_number", "width": "20%", "className": "text-center" },
                { "data": "customer_name", "width": "25%" },
                { "data": "Company_Name", "className": "text-left" },
                { "data": "amount", "className": "text-right" },
                { "data": "tax", "className": "text-right" },
                { "data": "balance", "className": "text-right" }
            ],
            "error": function(xhr, status, error) {
                $('#response').html('Error: ' + error);
            }
        });
    });
</script>
