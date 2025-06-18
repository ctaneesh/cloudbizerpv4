<article class="content">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="grid_3 grid_4 animated fadeInRight table-responsive">
            <div class="row">
                <div class="col-md-8">
                    <h5><?php echo $this->lang->line('Enquiry') ?></h5>
                </div>
                <div class="col-md-4" style="text-align:right;">
                    <a  href="create" class="btn btn-primary"><?php echo $this->lang->line('Create New Enquiry'); ?></a>
                </div>
            </div>
            <hr>
            <table id="invoices" class="table-striped" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?php echo $this->lang->line('No') ?></th>
                    <th><?php echo $this->lang->line('Enquiry #') ?></th>
                    <!-- <th><?php //echo $this->lang->line('Enq Note') ?></th> -->
                    <th><?php echo $this->lang->line('Message') ?></th>
                    <th><?php echo $this->lang->line('Request Date') ?></th>
                    <th><?php echo $this->lang->line('Enq Date') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Actions') ?></th>


                </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th><?php echo $this->lang->line('No') ?></th>
                    <th><?php echo $this->lang->line('Enquiry #') ?></th>
                    <!-- <th><?php //echo $this->lang->line('Enq Note') ?></th> -->
                    <th><?php echo $this->lang->line('Message') ?></th>
                    <th><?php echo $this->lang->line('Request Date') ?></th>
                    <th><?php echo $this->lang->line('Enq Date') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                    <th class="no-sort"><?php echo $this->lang->line('Actions') ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


</article>
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

        var table = $('#invoices').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('Invoices/ajax_enquirylist')?>",
                "type": "POST",
                 'data': {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash(); ?>'}
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