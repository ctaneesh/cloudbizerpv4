<div class="app-content content container-fluid">
    <div class="card card-block">
        <div class="content-header row">
        </div>
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
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Payments') ?></li>
                        </ol>
                    </nav> 
                    <h4 class="card-title"><?php echo $this->lang->line('Payments') ?></h4><hr>
                    <div class="table-scroll">
                         <table id="invoices" class="table table-striped1 zero-configuration dataTable">
                            <thead>

                            <tr>
                                <th><?php echo $this->lang->line('Sl No') ?></th>
                                <th><?php echo $this->lang->line('Invoice Number') ?></th>
                                <th><?php echo $this->lang->line('Invoice Date') ?></th>
                                <th class="text-right"><?php echo $this->lang->line('Invoice Amount') ?></th>
                                <th><?php echo $this->lang->line('Paid Date') ?></th>
                                <th  class="text-right"><?php echo $this->lang->line('Paid Amount') ?></th>
                                <th  class="text-right"><?php echo $this->lang->line('Total')." ".$this->lang->line('Paid Amount') ?></th>
                                <th  class="text-right"><?php echo $this->lang->line('Balance Due') ?></th>


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
                "url": "<?php echo site_url('payments/ajax_list')?>",
                "type": "POST",
                 'data': {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash(); ?>'}
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
                'targets': [3,5,6,7],
                'createdCell': function(td, cellData, rowData, row, col) {
                    addClassToColumns(td, col, ['text-right']);
                }
            }
        ],

        });

    });
</script>
