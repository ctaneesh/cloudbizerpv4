<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
     <div class="card-header">
          <h4 class="card-title"> <?php echo $this->lang->line('Payment Gateways') ?>
        </h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                </ul>
            </div>
        <hr>
    </div>

    <div class="card-body">
        <table id="catgtable" class="table table-striped table-bordered zero-configuration" style="width:100% !important;">
            <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:15%;"><?php echo $this->lang->line('Name') ?></th>
                <th style="width:5%;"><?php echo $this->lang->line('Status') ?> On</th>
                <th style="width:5%;"><?php echo $this->lang->line('Test Mode') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($gateway as $row) {
                $cid = $row['id'];
                $title = $row['name'];
                $enable = ($row['enable']=="Yes")?"<span class='st-active'>".$row['enable']."</span>":"<span class='st-inactive'>".$row['enable']."</span>";
                $dev_mode = ($row['dev_mode']=="true")?"<span class='st-active'>".$row['dev_mode']."</span>":"<span class='st-inactive'>".$row['dev_mode']."</span>";

                echo "<tr>
                    <td>$i</td>
                    <td>$title</td>
                    <td>$enable</td>
                    <td>$dev_mode</td>
                  
                    <td><a href='" . base_url("paymentgateways/edit?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a></td></tr>";
                $i++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Name') ?></th>
                <th><?php echo $this->lang->line('Status') ?> On</th>
                <th><?php echo $this->lang->line('Test Mode') ?></th>
                <th><?php echo $this->lang->line('Action') ?></th>

            </tr>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        //datatables
        $('#catgtable').DataTable({responsive: false});

    });
</script>
