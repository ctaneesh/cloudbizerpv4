<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header">
        <h4 class="card-title">Application Activity Log</h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">                    
                <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
            </ul>
        </div>
    </div>
    <hr>
    <div class="card-body table-scroll">
        <p class="text-danger">Application Activity Log cleanup can be managed in Cron Job Settings.</p>
        <table id="logtable" class="table table-striped table-bordered zero-configuration dataTable" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?php echo $this->lang->line('Description') ?></th>
                <th><?php echo $this->lang->line('Date') ?></th>
                <th><?php echo $this->lang->line('Name') ?></th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($acts as $row) {

                echo "<tr><td>" . $row['note'] . "</td><td>" . $row['created'] . "</td><td>" . $row['user'] . "</td></tr>";
            }
            ?>
            </tbody>

        </table>
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
                <p><?php echo $this->lang->line('Delete') ?> ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="settings/taxslabs_delete">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
     $('#logtable').DataTable();
    });
</script>