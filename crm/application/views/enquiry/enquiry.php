<div class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="grid_3 grid_4 animated fadeInRight">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Request For Quotes') ?></li>
                        </ol>
                    </nav> 
                    <h5><?php echo $this->lang->line('Request For Quotes') ?>
                    <a  href="<?=base_url()."enquiry/create"?>" class="btn btn-primary btn-sm"><?php echo $this->lang->line('Create New Request'); ?></a>
                    </h5>
                </div>
            </div>
            <hr>
            <div class="table-scroll">
                    <table id="invoices" class="table table-striped1 zero-configuration dataTable" cellspacing="0" >
                        <thead>
                        <tr>
                            <th class="text-center"><?php echo $this->lang->line('No') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Customer Sequence') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Enquiry #') ?></th>
                            <!-- <th><?php //echo $this->lang->line('Enq Note') ?></th> -->
                            <th><?php echo $this->lang->line('Message') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Request Date') ?></th>
                            <th class="text-center"><?php echo $this->lang->line('Due Date') ?></th>
                            <th class="no-sort text-center"><?php echo $this->lang->line('Status') ?></th>
                            <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>


                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>


</div>
<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this quote') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="quote/delete_i">
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
        var columnlist = [
        { 'width': '4%' }, 
        { 'width': '12%' },
        { 'width': '10%' }, 
        { 'width': '30%' },
        { 'width': '10%' },
        { 'width': '8%' },
        { 'width': '12%' },
        { 'width': '' }
        ];
        var table = $('#invoices').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('enquiry/ajax_list')?>",
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

                {
                    'targets': [1,2,4,5,6],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
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