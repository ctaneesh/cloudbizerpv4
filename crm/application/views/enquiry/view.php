<div class="app-content content container-fluid">
    <div class="card card-block">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <?php

        if($this->session->flashdata('item')) {
        $message = $this->session->flashdata('item');
        ?>
        <div class="alert alert-info"><?php echo $message['message']; ?>

        </div>
        <?php
        }
        
        if ($enqurymain['customer_id'] != $this->session->userdata('user_details')[0]->cid) 
        {
            $msg = check_permission();
            echo $msg;
            return;
        }
        ?>
        <div class="content-body">
            <section class="card1">
            <div class="card-header border-bottom1">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('enquiry') ?>"><?php echo $this->lang->line('Request For Quotes'); ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $enqurymain['lead_number']; ?></li>
                            </ol>
                        </nav> 
                       
                       <div class="row"> 
                       
                            <div class="col-lg-8"><h4 style="text-align:left;"> <?php echo $enqurymain['lead_number']; ?></h4></div>
                            <div class="col-lg-4 text-right">
                                <?php 
                                 $statustext="Sent";
                                if($statustext)
                                {
                                    echo '<div class="btn-group alert alert-success text-center" role="alert">';
                                    echo "<span>".$statustext."</span>";
                                    echo '</div>';
                                }
                                
                                   
                                    ?>  
                            </div>
                       </div>
                    </div>
                <div id="invoice-template" class="card-block">
                    
                  
                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row">
                    <div class="col-md-2 col-sm-12 text-xs-center text-md-left">
                            <img src="../../userfiles/company/<?php echo $this->config->item('logo') ?>"
                                class="img-responsive p-1 m-b-2" style="max-height: 120px;">                          
                        </div>
                        <div class="col-md-4 col-sm-12 text-xs-center text-md-left">
                            <p class="pb-0 mb-0 text-muted"><?php echo $this->lang->line('From') ?></p> 
                             <p class="pb-0 mb-0 text-bold-800"> <?php echo $this->config->item('ctitle'); ?></p>
                             <p class="pb-0 mb-0"> 
                              <?php echo $this->config->item('address');
                              if($this->config->item('city')) { echo ", ".$this->config->item('city'); }
                              ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->config->item('phone'); ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->config->item('email'); ?></p>                           
                        </div>
                        <div class="col-md-3 col-sm-12 text-xs-center text-md-left">
                            <p class="pb-0 mb-0 text-muted"><?php echo $this->lang->line('Bill To') ?></p> 
                             <p class="pb-0 mb-0 text-bold-800"> <?php echo $enqurymain['customer_name']; ?></p>
                             <p class="pb-0 mb-0"> 
                              <?php echo $enqurymain['customer_address'];?></p>                             
                             <p class="pb-0 mb-0"> <?php echo $this->lang->line('Phone')." : " .$enqurymain['customer_phone']; ?></p>
                             <p class="pb-0 mb-0"> <?php echo $this->lang->line('Email')." : " .$enqurymain['customer_email']; ?></p>                           
                        </div>

                        <div class="col-md-3 col-sm-12 text-xs-center text-md-right">
                            <p class="pb-0 mb-0 text-bold-800"> <?php echo $enqurymain['lead_number'] . '</p>'; ?>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Requested Date'); ?> : <span>  <?php echo dateformat($enqurymain['created_date']); ?></span></p>
                            <p class="pb-0 mb-0"> <?php echo $this->lang->line('Due Date'); ?> : <span>  <?php echo dateformat($enqurymain['due_date']); ?></span></p>
                                          
                        </div>
                        
                    </div>
                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <div class="row">
                            <div class="table-responsive1 col-sm-12 table-scroll">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('Item No') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Item Name') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Quantity') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $c = 1;
                                    $sub_t=0;
                                    foreach ($products as $row) {
                                        echo '
                                        <th scope="row">' . $c . '</th>
                                        <td>' . $row['product_code'] . '</td>
                                        <td style="display: block;">' . $row['product_name'] . '</td>
                                        <td>' . number_format($row['quantity']) . '</td></tr>';
                                        $c++;

                                        // <td style="display: block;width:max-content;">' . $row['product_name'] . '</td>
                                    } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>
</div>

