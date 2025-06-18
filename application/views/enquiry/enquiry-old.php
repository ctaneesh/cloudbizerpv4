<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->lang->line('Enquiry') ?>
            <a href="<?php echo base_url(); ?>invoices/newcustomerenquiry" class="btn btn-primary btn-sm rounded">
                    Create New</a></h4>
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
                
                <hr>
                <table id="invoices" class="table table-striped table-bordered zero-configuration ">
                    <thead>
                    <tr>
                    <th style="width:25px !important;"><?php echo $this->lang->line('No') ?></th>
                    <th style="width:25px !important;"><?php echo $this->lang->line('Enquiry #') ?></th>
                    <th style="width:125px !important;"><?php echo $this->lang->line('Customer Name') ?></th>
                    <th><?php echo $this->lang->line('Phone') ?></th>
                    <th><?php echo $this->lang->line('Email') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                    <th class="no-sort"><?php echo "Actions" ?></th>


                    </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                    <tr>
                    <th><?php echo $this->lang->line('No') ?></th>
                    <th><?php echo $this->lang->line('Enquiry #') ?></th>
                    <th><?php echo $this->lang->line('Customer Name') ?></th>
                    <th><?php echo $this->lang->line('Phone') ?></th>
                    <th><?php echo $this->lang->line('Email') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Actions') ?></th>

                    </tr>
                    </tfoot>
                </table>
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
                "url": "<?php echo site_url('enquiry/ajax_list')?>",
                "type": "POST",
                 'data': {'<?=$this->security->get_csrf_token_name()?>': crsf_hash}
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                },
            ],

        });

    });
</script>