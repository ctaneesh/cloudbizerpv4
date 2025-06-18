<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="card-header">
          <h4 class="card-title"> <?php echo $this->lang->line('Product Pricing') ?> </h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">                    
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>                    
                </ul>
            </div>
    </div>
    
        <hr>
    <div class="card-body">
        <!-- <h5 class="title"> <?php echo $this->lang->line('Product Pricing') ?> </h5> -->
        <!-- <h5 class="title"> <?php echo $this->lang->line('Product Pricing') ?> <a
                    href="<?php echo base_url('productpricing/create') ?>"
                    class="btn btn-primary btn-sm rounded">
                <?php //echo $this->lang->line('Add new') ?>
            </a>
        </h5> -->


        <table id="catgtable" class="table table-striped table-bordered zero-configuration dataTable" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('Selling Price') ?>(%)</th>
                <th><?php echo $this->lang->line('Wholesale Price') ?>(%)</th>
                <th><?php echo $this->lang->line('Web Price') ?>(%)</th>
                <th><?php echo $this->lang->line('Minimum Price') ?>(%)</th>
                <th><?php echo $this->lang->line('Action') ?></th>


            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($units as $row) {
                $cid = $row->id;
                $minprice = $row->price_perc;
                $sellingprice = $row->selling_price_perc;
                $wholesale = $row->whole_price_perc;
                $webprice = $row->web_price_perc;


                echo "<tr>
                    <td>$i</td>
                    <td>$sellingprice</td>
                    <td>$wholesale</td>
                    <td>$webprice</td>
                    <td>$minprice</td>                 
                    <td><a href='" . base_url("productpricing/edit?id=$cid") . "' class='btn btn-secondary btn-sm' title='Edit'><i class='icon-pencil'></i></a>&nbsp;</td></tr>";
                $i++;
            }
            ?>
            </tbody>
           
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        //datatables
        $('#catgtable').DataTable({responsive: false});

    });
</script>