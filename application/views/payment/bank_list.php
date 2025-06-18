<?php       
  if (($msg = check_permission($permissions)) !== true) {
     echo $msg;
     return;
  }
 ?>
 <div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-body">
        <h4 class="card-title"><?php echo $this->lang->line('Bank Accounts') ?> <a
                    href="<?php echo base_url('paymentgateways/add_bank_ac') ?>"
                    class="btn btn-primary btn-sm rounded">
                <?php echo $this->lang->line('Add new') ?>
            </a>
        </h4>
        <hr>
        <p><?php echo $this->lang->line('pay with bank') ?>.</p>
        <hr>

        <table id="catgtable" class="table table-striped table-bordered zero-configuration" cellspacing="0" style="width:100% !important;">
            <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:15%;"><?php echo $this->lang->line('Name') ?></th>
                <th style="width:10%;"><?php echo $this->lang->line('Account No') ?></th>
                <th style="width:10%;"><?php echo $this->lang->line('Account Code') ?></th>
                <th style="width:10%;"><?php echo $this->lang->line('Opening Balance / Deposit') ?></th>
                <th style="width:5%;"><?php echo $this->lang->line('Enable') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($bank_accounts as $row) {
                $cid = $row['id'];
                $title = $row['name'];
                $acn = $row['acn'];
                $defaultaccount = ($row['defaultaccount']=='Yes') ? '(Default)' : '' ;
                $dev_mode = (!empty($row['enable']=="Yes"))?"<span class='st-active'>".$row['enable']."</span>":"<span class='st-inactive'>".$row['enable']."</span>";

                echo "<tr>
                    <td>$i</td>
                    <td class='responsive-width'><a href='" . base_url("bankingtransactions/bank_account_view?id=$cid") . "' title='View'>".$title." ".$defaultaccount."</a></td>
                    <td>$acn</td>
                    <td>".$row['code']."</td>
                    <td class='text-right'>".$row['opening_balance']."</td>                  
                    <td>$dev_mode</td>
                    <td><a href='" . base_url("paymentgateways/edit_bank_ac?id=$cid") . "' class='btn btn-crud btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></a></td></tr>";
                    // <td><a href='" . base_url("paymentgateways/edit_bank_ac?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='fa fa-pencil'></i></a> <a href='#' data-object-id='" . $cid . "' class='btn btn-secondary btn-sm delete-object' title='Delete'><i class='fa fa-trash'></i></a></td></tr>";
                $i++;
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

                <h4 class="modal-title"><?php echo $this->lang->line('Delete') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this account') ?></p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="paymentgateways/delete_bank_ac">
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

        //datatables
        $('#catgtable').DataTable({responsive: false});

    });
</script>
