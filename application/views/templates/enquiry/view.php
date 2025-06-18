<div class="app-content content container-fluid">
    <div class="content-wrapper">
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

?>
        <div class="content-body">
            <section class="card">
                <div id="invoice-template" class="card-block">
                    <div class="row wrapper white-bg page-heading">

                        <div class="col-lg-12">
                            <div class="title-action">
                                <div class="btn-group ">
                                <a class="btn btn-success btn-min-width"  href="<?php echo base_url(); ?>enquiry/"><?php echo $this->lang->line('Back to Enquiry') ?></a>
                                    <!-- <button type="button" class="btn btn-success btn-min-width dropdown-toggle mb-1"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="icon-print"></i> <?php echo $this->lang->line('Back to Enquiry') ?>
                                    </button> -->
                                    <div class="dropdown-menu">
                                        <!-- <a class="dropdown-item"
                                           href="<?php echo $link ?>"><?php echo $this->lang->line('Print') ?></a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item"
                                           href="<?php echo $link ?>&d=1"><?php echo $this->lang->line('PDF Download') ?></a> -->

                                    </div>  
                                    
                                </div>



                            </div>
                        </div>
                    </div>
                   
                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row mt-2">
                        <div class="col-md-6 col-sm-12 text-xs-center text-md-left">
                            <ul class="px-0 list-unstyled">
                                <?php echo '<li class="text-bold-800">' . $this->lang->line('Enquiry') . ' - #'.$enqurymain['lead_id'].'</li><li>' . $this->lang->line('Enquiry Note') . ' : '.$enqurymain['enquiry_note'].'</li><li>' . $this->lang->line('Enquiry Message'). ' : '.$enqurymain['enquiry_message'].'</li><li>' . $this->lang->line('Requested Date'). ' : '.$enqurymain['enquiry_requested_date'].'</li><li>' . $this->lang->line('Enquiry Date'). ' : '.$enqurymain['enquiry_date'].'</li><li>' . $this->lang->line('Status'). ' : '.$enqurymain['status'].'</li>';
                                ?>
                            </ul>
                        </div>
                    </div>
                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-2">
                        <div class="row">
                            <div class="table-responsive col-sm-12">
                                <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('Product Name') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Product Code') ?></th>
                                        <th class="text-xs-left"><?php echo $this->lang->line('Quantity') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $c = 1;
                                    $sub_t=0;
                                    foreach ($products as $row) {
                                        echo '
                                        <th scope="row">' . $c . '</th>
                                        <td>' . $row['product_name'] . '</td>
                                        <td>' . $row['product_code'] . '</td>
                                        <td>' . $row['product_qty'] . '</td></tr>';
                                        $c++;
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

