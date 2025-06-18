<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="grid_3 grid_4">
            <div class="header-block">
                <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Support Tickets') ?></li>
                        </ol>
                    </nav> 
                    <h5 class="box-title"><?php echo $this->lang->line('Support Tickets') ?> <a href="<?php echo base_url('tickets/addticket') ?>" class="btn btn-primary btn-sm rounded">Add new</a></h5><hr>
                </div>

                <div class="table-scroll">
                     <table id="doctable" class="table table-striped1 zero-configuration dataTable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th><?php echo $this->lang->line('Ticket Number') ?></th>
                            <th><?php echo $this->lang->line('Subject') ?></th>
                            <th><?php echo $this->lang->line('Date') ?></th>
                            <th><?php echo $this->lang->line('Status') ?></th>
                        </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>
        </div>
    </div>
</article>

<script type="text/javascript">
    var columnlist = [
        { 'width': '4%' }, 
        { 'width': '10%' }, 
        { 'width': '25%' },
        { 'width': '12%' },
        { 'width': '' }
    ];
    $(document).ready(function () {

        $('#doctable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('tickets/tickets_load_list')?>",
                "type": "POST",
                 'data': {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash(); ?>'}
            },
           "columns": columnlist,
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },

                // {
                //     'targets': [2],
                //     'createdCell': function(td, cellData, rowData, row, col) {
                //         addClassToColumns(td, col, ['text-center']);
                //     }
                // },
                // {
                //     'targets': [5],
                //     'createdCell': function(td, cellData, rowData, row, col) {
                //         addClassToColumns(td, col, ['text-right']);
                //     }
                // }
            ],
        });

    });
</script>
