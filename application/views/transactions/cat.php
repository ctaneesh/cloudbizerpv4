<article class="content-body">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-body">
            
            <div class="card-header">
                    <h4 class="card-title">
                        <?php echo $this->lang->line('Transactions Categories') ?><a href="<?php echo base_url('transactions/createcat') ?>" class="btn btn-primary btn-sm rounded"><?php echo $this->lang->line('Add new') ?>
                        </a>
                    </h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">                    
                            <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                        </ul>
                    </div>
                </div>
                <hr>
            <p>&nbsp;</p>
            <div class="table-container">
                <table class="table table-striped table-bordered zero-configuration dataTable" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="width:35%"><?php echo $this->lang->line('Name') ?></th>
                        <th><?php echo $this->lang->line('Action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($catlist as $row) {
                        $cid = $row['id'];
                        echo "<tr><td>" . $row['name'] . "</td><td><a href='" . base_url("transactions/editcat?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;<a href='#' data-object-id='" . $cid . "' class='btn btn-secondary btn-sm delete-object' title='Delete'><i class='fa fa-trash'></i></a></td></tr>";
                    }
                    ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
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
                <p><?php echo $this->lang->line('delete this Transaction Category') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="transactions/delete_cat">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>