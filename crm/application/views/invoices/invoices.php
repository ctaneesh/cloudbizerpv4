<div class="app-content content container-fluid">
    <div class="card card-block">
        
        <div class="content-body">
            <?php if ($this->session->flashdata("messagePr")) { ?>
                <div class="alert alert-info">
                    <?php echo $this->session->flashdata("messagePr") ?>
                </div>
            <?php } ?>

                <div class="box-header with-border">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Invoices') ?></li>
                        </ol>
                    </nav> 
                    <h5 class="box-title"><?php echo $this->lang->line('Invoices') ?></h5><hr>
                   
                    <div class="table-scroll">
                        <table id="invoices" class="table table-striped1 zero-configuration dataTable">
                            <thead>
                            <tr>

                                <th><?php echo $this->lang->line('No') ?></th>
                                <th><?php echo $this->lang->line('Invoice No.') ?></th>
                                <th><?php echo $this->lang->line('Customer') ?></th>
                                <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                <th class="text-right"><?php echo $this->lang->line('Amount') ?></th>
                                <th  class="no-sort text-center"><?php echo $this->lang->line('Status') ?></th>
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
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var table = $('#invoices').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('invoices/ajax_list')?>",
                "type": "POST",
                'data': {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash(); ?>'}
            },
           'columnDefs': [
                {
                    'targets': [0],
                    'orderable': true,  // Only disable ordering for column 0
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
                {
                    'targets': [3,5],
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

        });

    });
</script>
