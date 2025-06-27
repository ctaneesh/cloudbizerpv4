<article class="content-body">
    <?php       
        if (($msg = check_permission($permissions)) !== true) {
            echo $msg;
            return;
        }       
    ?>
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-start">
                                <h3 class="" id="dash_0"></h3>
                                <span><?php echo $this->lang->line('Waiting') ?></span>
                            </div>
                            <div class="media-end media-middle">
                                <i class="fa fa-clock-o font-large-2 float-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-start">
                                <h3 class="" id="dash_1"></h3>
                                <span><?php echo $this->lang->line('Processing') ?></span>
                            </div>
                            <div class="media-end media-middle">
                                <i class="fa fa-refresh font-large-2 float-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-start">
                                <h3 class="" id="dash_2"></h3>
                                <span><?php echo $this->lang->line('Solved') ?></span>
                            </div>
                            <div class="media-end media-middle">
                                <i class="fa fa-check-circle font-large-2 float-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-start">
                                <h3 class=""><?php echo $totalt ?></h3>
                                <span><?php echo $this->lang->line('Total') ?></span>
                            </div>
                            <div class="media-end media-middle">
                                <i class="fa fa-pie-chart font-large-2 float-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-body">
            <div class="header-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('Support Tickets') ?></li>
                        </ol>
                    </nav> 
                    <h4 class="card-title"><?php echo $this->lang->line('Support Tickets') ?></h4><hr>
            </div>


            <table id="doctable" class="table table-striped table-bordered zero-configuration" style="width:100% !important;">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th><?php echo $this->lang->line('Ticket Number') ?></th>
                    <th><?php echo $this->lang->line('Subject') ?></th>
                    <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                    <th class="text-center"><?php echo $this->lang->line('Status') ?></th>
                    <th><?php echo $this->lang->line('Action') ?></th>


                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>
    </div>
    <input type="hidden" id="dashurl" value="tickets/ticket_stats">
</article>
<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this ticket') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="tickets/delete_ticket">
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
        { 'width': '5%' }, 
        { 'width': '12%' },
        { 'width': '25%' },
        { 'width': '12%' }, 
        { 'width': '8%' },
        { 'width': '' }
        ];
        $('#doctable').DataTable({

            "processing": true,
            "serverSide": true,
            responsive: false,
            <?php datatable_lang();?>
            "ajax": {
                "url": "<?php if (isset($_GET['filter'])) {
                    $filter = $_GET['filter'];
                } else {
                    $filter = '';
                }    echo site_url('tickets/tickets_load_list?stat=' . $filter)?>",
                "type": "POST",
                'data': {'<?=$this->security->get_csrf_token_name()?>': crsf_hash}
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                },
             {
                    'targets': [3,4],
                    'createdCell': function(td, cellData, rowData, row, col) {
                        addClassToColumns(td, col, ['text-center']);
                    }
                },
            ],
            'columns': columnlist,
            "order": [[2, "desc"]]

        });
        miniDash();
    });
</script>
