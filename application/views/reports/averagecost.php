<article class="content">

    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <div class="card-header border-bottom">            
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                
                </ol>
            </nav>
           
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
           
        <div class="row">
                    <div class="col-12">
                        <div class="card sameheight-item">
                            <?php $oneMonthBefore = date('Y-m-d', strtotime('-1 month')); ?>
                            <form action="<?php echo base_url() ?>sales/average_calc" method="post"
                                  role="form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="form-group row">
                      
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12 submit-section mt-2">
                                        <input type="submit" class="btn btn-crud btn-primary btn-lg" value="Calculate">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <hr>
          
                <table id="pox" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo $this->lang->line('No') ?></th>                        
                        <th><?php echo $this->lang->line('Date') ?></th>
                        <th><?php echo $this->lang->line('Product') ?></th>

                        <th class="text-center"><?php echo $this->lang->line('Type') ?></th>
                        <th class="text-right"><?php echo $this->lang->line('Quantity') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Onhand Quantity') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Cost') ?></th>
                        <th class="no-sort text-center"><?php echo $this->lang->line('Average Price') ?></th>
                        <th class="no-sort text-center"><?php echo $this->lang->line('Inventory Value') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i =1;
                        $onhand = 0;
                        $shand = 0;
                        $pavg = 0;
                        foreach($list as $data)
                        { ?>
                        <tr>
                        <td><?php echo $i; ?> </td>
                        <td><?= $data->date; ?> </td>
                        <td><?= $data->product_id; ?> </td>
                        <td><?= $data->type; ?> </td>
                        <td><?= $data->quantity;?> </td>
                        <?php if(($data->type=="Purchase") || ($data->type=="Sales Return")){
                            $onhand = $onhand + $data->quantity; 
                            if($data->type=="Purchase") 
                            {
                             $avg =  (($data->quantity * $data->cost)+($shand * $pavg))/ $onhand; 
                             $pavg =round($avg);
                             $inv = $pavg * $onhand; 
                            } 

                            ?>
                             <td><?php echo $onhand;?></td>
                             
                             <?php if($data->type=="Purchase") {?>
                            <td><?= $data->cost; ?> </td>
                             <td><?php echo $pavg;?> </td>
                             <td><?php echo $inv;?> </td>
                    
                      <?php  } }
                      else{  $onhand = $onhand - $data->quantity;
                         if($data->type=="Sale") {
                        $shand = $onhand;  } ?>
                      <td><?php echo $onhand;?> </td>
                      <?php $cost= $data->quantity * $pavg;?>
                      <td><?php echo $cost; ?> </td>
                      <td><?php ?> </td>
                      <?php } ?>      
                    
                        </tr>

                     <?php $i++;   } ?>
                    </tbody>

                   
        </table>
        </div>
    </div>
</article>

