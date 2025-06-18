<div class="content-body">
   <div class="card">

   
      <div class="card-header border-bottom">
         <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('supplier') ?>"><?php echo $this->lang->line('Suppliers') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"> <?php echo $details['name'] ?> </li>
                </ol>
            </nav>
         <h4 class="card-title"><?php echo $this->lang->line('Supplier Details') ?>
            : <?php echo $details['name'] ?>  
            <a href="<?php echo base_url('supplier/create?id=' . $details['supplier_id']) ?>"  class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i>
                  <?php echo $this->lang->line('Edit Profile') ?>
            </a>
         </h4>
         <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
         <div class="heading-elements">
            <ul class="list-inline mb-0">
               <li><a class="breaklink" data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
         </div>
      </div>
      <div class="card-content">
         <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-4 border-right border-right-grey">
                  <div class="ibox-content mt-2">
                     <img alt="image" id="dpic" class="card-img-top img-fluid"
                        src="<?php echo base_url('userfiles/customers/') . $details['picture'] ?>">
                  </div>
                  <hr>
                  <!-- <h6><?php echo $this->lang->line('Client Group') ?>
                     <small><?php echo $customergroup['title'] ?></small>
                  </h6> -->
                  <div class="row mt-3">
                     <div class="col-md-12">
                        <a href="<?php echo base_url('supplier/view?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i class="fa fa-user"></i>
                        <?php echo $this->lang->line('View') ?></a>
                        <a href="<?php echo base_url('supplier/invoices?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-file-text"></i> <?php echo $this->lang->line('View Invoices') ?>
                        </a>
                        <a href="<?php echo base_url('supplier/transactions?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                           class="fa fa-money"></i> <?php echo $this->lang->line('View Transactions') ?>
                        </a>
                        <a href="<?php echo base_url('supplier/statement?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-briefcase"></i>
                        <?php echo $this->lang->line('Account Statements') ?>
                        </a>
                        
                      
                        <a href="<?php echo base_url('supplier/invoices?id=' . $details['supplier_id']) ?>&t=sub"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-calendar-check-o"></i>
                        <?php echo $this->lang->line('Subscriptions') ?>
                        </a>
                        <a href="<?php echo base_url('supplier/notes?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i class="fa fa-book"></i>
                        <?php echo $this->lang->line('Notes') ?>
                        </a>
                        <a href="<?php echo base_url('supplier/documents?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i class="icon-folder"></i>
                        <?php echo $this->lang->line('Documents') ?>
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-md-8">
                  <div id="mybutton">
                     <div class="">
                        <a href="<?php echo base_url('supplier/balance?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm"><i class="fa fa-briefcase"></i>
                        <?php echo $this->lang->line('Wallet') ?>
                        </a>
                        <a href="<?php echo base_url('supplier/bulkpayment?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm"><i class="fa fa-money"></i>
                        <?php echo $this->lang->line('Bulk Payment') ?>
                        </a>
                        <a href="#sendMail" data-toggle="modal" data-remote="false"
                           class="btn btn-secondary btn-sm " data-type="reminder"><i class="fa fa-envelope"></i>
                        <?php echo $this->lang->line('Send Message') ?>
                        </a>                       
                        <a href="<?php echo base_url('supplier/changepassword?id=' . $details['supplier_id']) ?>"
                           class="btn btn-secondary btn-sm d-none"><i class="fa fa-key"></i>
                        <?php echo $this->lang->line('Change Password') ?>
                        </a>
                     </div>
                  </div>
                  <div class="">
                     <h4></h4>
                     <hr>
                     <?php if ($details['company']) { ?>
                     <div class="row m-t-lg">
                        <div class="col-md-2">
                           <strong><?php echo $this->lang->line('Company') ?></strong>
                        </div>
                        <div class="col-md-10">
                           <?php echo $details['company'] ?>
                        </div>
                     </div>
                     <hr>
                     <?php } ?>
                     <div class="row m-t-lg">
                        <div class="col-md-2">
                           <strong>Email</strong>
                        </div>
                        <div class="col-md-10">
                           <?php echo $details['email'] ?>
                        </div>
                     </div>
                     <hr>
                     <div class="row m-t-lg">
                        <div class="col-md-2">
                           <strong><?php echo $this->lang->line('Phone') ?></strong>
                        </div>
                        <div class="col-md-10">
                           <?php echo $details['phone'] ?>
                        </div>
                     </div>
                     <hr>
                     <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                        <div id="heading2" class="card-header">
                           <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion2"
                              aria-expanded="false" aria-controls="accordion2"
                              class="card-title1 details-title lead collapsed">
                           <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('Address') ?>
                           </a>
                        </div>
                        <div id="accordion2" role="tabpanel" aria-labelledby="heading2"
                           class="mt-1 card-collapse collapse" aria-expanded="false">
                           <div class="card-body">
                              <div class="card-block">
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('Address') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['address'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('City') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['city'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('Region') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['region'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('Country') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['country'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('PostBox') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['postbox'] ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="heading3" class="card-header">
                           <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion3"
                              aria-expanded="false" aria-controls="accordion3"
                              class="card-title1 details-title lead collapsed">
                           <i class="fa  fa-plus-circle"></i>
                           <?php echo $this->lang->line('Shipping Address') ?>
                           </a>
                        </div>
                        <div id="accordion3" role="tabpanel" aria-labelledby="heading3"
                           class="mt-1 card-collapse collapse" aria-expanded="false">
                           <div class="card-body">
                              <div class="card-block">
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('Address') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['shipping_address_1'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('City') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['shipping_city'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('Region') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['shipping_region'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('Country') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['shipping_country'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-2">
                                       <strong><?php echo $this->lang->line('PostBox') ?></strong>
                                    </div>
                                    <div class="col-md-10">
                                       <?php echo $details['shipping_postbox'] ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- erp2024 new section 06-06-2024  Srarts -->
                        <div id="heading9" class="card-header">
                           <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion11" aria-expanded="false" aria-controls="accordion9"  class="card-title1 details-title lead collapsed"><i class="fa  fa-plus-circle"></i> <?php echo $this->lang->line('Supplier Details') ?></a>
                        </div>
                        <div id="accordion11" role="tabpanel" aria-labelledby="heading3" class="mt-1 card-collapse collapse" aria-expanded="false">
                           <div class="card-body">
                              <div class="card-block">
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Registration Number') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['registration_number']; ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Expiry Date') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo dateformat($details['expiry_date']) ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Computer Card') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['computer_card_number']; ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Sponser ID') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['sponser_id'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Credit Limit') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['credit_limit'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Credit Period') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['credit_period'] ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="heading9" class="card-header">
                           <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion10" aria-expanded="false" aria-controls="accordion9"  class="card-title1 details-title lead collapsed"><i class="fa  fa-plus-circle"></i> <?php echo $this->lang->line('Contact Details') ?></a>
                        </div>
                        <div id="accordion10" role="tabpanel" aria-labelledby="heading3" class="mt-1 card-collapse collapse" aria-expanded="false">
                           <div class="card-body">
                              <div class="card-block">
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Contact Person') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['contact_person']; ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Land Line') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['land_line'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Contact Phone1') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['contact_phone1'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Contact Phone2') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['contact_phone2'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Contact Email1') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['contact_email1'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Contact Email2') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['contact_email1'] ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- erp2024 new section 06-06-2024  Ends  -->
                        <div id="heading9" class="card-header">
                           <a data-toggle="collapse" data-parent="#accordionWrapa1" href="#accordion9" aria-expanded="false" aria-controls="accordion9"  class="card-title1 details-title lead collapsed"><i class="fa  fa-plus-circle"></i> <?php echo $this->lang->line('Extra') ?></a>
                        </div>
                        <div id="accordion9" role="tabpanel" aria-labelledby="heading3"
                           class="mt-1 card-collapse collapse" aria-expanded="false">
                           <div class="card-body">
                              <div class="card-block">
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Register') ?><?php echo $this->lang->line('Date') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php if ($details['registration_date']) echo dateformat($details['registration_date']) ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Document') ?> ID</strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['document_id'] ?>
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $this->lang->line('Other') ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $details['custom1'] ?>
                                    </div>
                                 </div>
                                 <?php foreach ($custom_fields as $row) {
                                    ?>
                                 <hr>
                                 <div class="row m-t-lg">
                                    <div class="col-md-4">
                                       <strong><?php echo $row['name'] ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                       <?php echo $row['data'] ?>
                                    </div>
                                 </div>
                                 <?php
                                    }
                                    ?>
                              </div>
                           </div>
                        </div>
                       <!-- =================================History section=========================== -->
                     <!-- <button class="history-expand-button">
                           <span>History</span>
                           </button>

                           <div class="history-container">
                           <h2>History</h2>
                           <form>
                           <table id="logtable" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                        <thead>
                        <tr>         
                            <th><?php //echo "#" ?></th>
                            <th><?php //echo $this->lang->line('Action_performed') ?></th>  
                            <th><?php //echo $this->lang->line('IP address')?></th>
                            <th><?php // echo $this->lang->line('Performed By') ?></th>
                            <th><?php //echo $this->lang->line('Performed At')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php// $i = 1;
                          // foreach ($log as $row) { ?>
                          <tr>    
                            <td><?php //echo $i?></td>
                            <td><?php //echo $row['action_performed']?></td>
                            <td><?php //echo $row['ip_address']?></td>
                            <td><?php// echo $row['name']?></td>
                            <td><?php //echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
                          </tr>
                  
                          <?php// $i++; } ?>
                        </tbody>
                           </table>
                              
                           </form>
                           </div> -->
                             <!-- =========================History End=================== -->
      <!--     erp2025 add 06-01-2025   Detailed hisory starts-->
                <button class="history-expand-button">
                    <span>History</span>
                </button>

                <div class="history-container">
                     <button class="history-close-button">
                        <span>Close</span>
                    </button>
                    <h2>History  <button class="logclose-btn btn btn-sm">
                        <span>X</span>
                    </button></h2>
                   
                    <form>
                    <table id="log" class="table table-striped table-bordered zero-configuration dataTable">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('Action Performed') ?></th>
                                <!-- <th><?php// echo $this->lang->line('Action Performed') ?></th> -->
                                <th><?php echo $this->lang->line('Performed At')?></th>
                                <!-- <th><?php //echo $this->lang->line('Performed By') ?></th> -->
                                <th><?php echo $this->lang->line('IP Address')?></th>
                                        
                            </tr>
                        </thead>
                        <tbody>
                       <?php $i = 1;
                        foreach ($groupedSupplier as $seqence_number => $suppliers){
                        $flag=0;
                        ?>              
                            <tr>
                               <td>        
                                <?php    foreach ($suppliers as $supplier) {
                                if($flag==0)
                                {?>
                                <div class="userdata">
                                <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$supplier['picture'])?>' style="width:50px; height:50px;" ?>
                                <?php  echo $supplier['name'];
                                        $flag=1;
                                } ?>
                                </div>           
                                    <ul><li>  <?php echo $supplier['old_value'];?> > <b><span class="newdata"><?php echo $supplier['new_value']?></span></b> (<?php if($supplier['field_label']==""){echo $supplier['field_name'];}else{echo $supplier['field_label'];}?>)
                                    </li></ul>
                                    <?php } ?>
                                </td>               
                                <td><?php echo date('d-m-Y H:i:s', strtotime($supplier['changed_date'])); ?></td>
                                <td><?php echo $supplier['ip_address']?></td> 
                                
                            </tr>  
                            <?php 
                            $i++; 
                          
                        }?>
                        </tbody>
                    </table>

                    </form>
                </div>   
               <!--     erp2025 add 06-01-2025   Detailed hisory ends-->

                        <h5 class="bg-blue bg-lighten-4  p-1 mt-2">
                           <?php echo $this->lang->line('Wallet') . ' ' . $this->lang->line('Balance') . ': ' . amountExchange($details['balance'], 0, $this->aauth->get_user()->loc) ?>
                        </h5>
                        <hr>
                        <h5><?php echo $this->lang->line('Summary') ?></h5>
                        <ul class="list-group list-group-flush">
                           <li class="list-group-item">
                              <span
                                 class="badge tag-default tag-pill bg-success float-xs-right"><?php echo amountExchange($money['credit'], 0, $this->aauth->get_user()->loc) ?></span>
                              <?php echo $this->lang->line('Income') ?>
                           </li>
                           <li class="list-group-item">
                              <span
                                 class="badge tag-default tag-pill bg-danger float-xs-right"><?php echo amountExchange($money['debit'], 0, $this->aauth->get_user()->loc) ?></span>
                              <?php echo $this->lang->line('Expenses') ?>
                           </li>
                           <li class="list-group-item">
                              <span
                                 class="badge tag-default tag-pill bg-pink float-xs-right"><?php echo amountExchange($due['total'] - $due['pamnt']) ?></span>
                              <?php echo $this->lang->line('Total Due') ?>
                           </li>
                           <li class="list-group-item">
                              <span
                                 class="badge tag-default tag-pill bg-blue float-xs-right"><?php echo amountExchange($due['discount'], 0, $this->aauth->get_user()->loc) ?></span>
                              <?php echo $this->lang->line('Total Discount') ?>
                           </li>
                        </ul>
                        <div id="heading6" class="card-header bg-grey bg-lighten-4 p-1 mt-2">
                           <a data-toggle="collapse" data-parent="#accordionWrapa6" href="#accordion6"
                              aria-expanded="false" aria-controls="accordion6"
                              class="card-title1 lead collapsed">
                           <i class="icon-circle-plus"></i> Wallet
                           Recharge/<?php echo $this->lang->line('Payment History') ?></a>
                        </div>
                        <div id="accordion6" role="tabpanel" aria-labelledby="heading6"
                           class="card-collapse collapse" aria-expanded="false">
                           <div class="card-body">
                              <div class="card-block">
                                 <table class="table table-striped table-bordered zero-configuration dataTable">
                                    <thead>
                                       <tr>
                                          <th><?php echo $this->lang->line('Amount') ?></th>
                                          <th><?php echo $this->lang->line('Note') ?></th>
                                       </tr>
                                    </thead>
                                    <tbody id="activity">
                                       <?php foreach ($activity as $row) {
                                          echo '<tr><td>' . amountExchange($row['col1'], 0, $this->aauth->get_user()->loc) . '</td><td>' . $row['col2'] . '</td></tr>';
                                          } ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="progress" class="progress1">
                        <div class="progress-bar progress-bar-success"></div>
                     </div>
                     <div class="col-md-12">
                        <br>
                        <h5><?php echo $this->lang->line('Change Supplier Picture') ?></h5>
                        <input
                           id="fileupload" type="file" name="files[]">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="sendMail" class="modal fade">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Send Message</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
         <div class="modal-body">
            <form id="sendmail_form">
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               <div class="row">
                  <div class="col">
                        <label for="customername" class="col-form-label"><?php echo $this->lang->line('Email') ?><span class="compulsoryfld">*</span></label>
                        <input type="email" class="form-control required" placeholder="Email" name="mailtoc" value="<?php echo $details['email'] ?>" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                    <label for="customername"  class="col-form-label"><?php echo $this->lang->line('Supplier Name') ?><span class="compulsoryfld">*</span></label>
                     <input type="text" class="form-control required"  name="customername" value="<?php echo $details['name'] ?>" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <label for="subject"  class="col-form-label"><?php echo $this->lang->line('Subject') ?><span class="compulsoryfld">*</span></label>
                     <input type="text" class="form-control required" name="subject" id="subject" required>
                  </div>
               </div>
               <div class="row">
                  <div class="col">
                     <label for="contents"  class="col-form-label"><?php echo $this->lang->line('Message') ?><span class="compulsoryfld">*</span></label>
                     <textarea name="text" class="summernote form-control required" id="contents" title="Contents" required></textarea>
                  </div>
               </div>
               <input type="hidden" class="form-control" id="cid" name="tid" value="<?php echo $details['supplier_id'] ?>">
               <input type="hidden" id="action-url" value="communication/send_general">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
               data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
            <button type="button" class="btn btn-primary" id="sendNow"><?php echo $this->lang->line('Send') ?></button>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
  $(document).ready(function () {
   $(".history-expand-button").on("click", function() {
        $(".history-container").toggleClass("active");
    });
    $(".history-close-button").on("click", function () {
      $(".history-container").removeClass("active");
    });
    $(".logclose-btn").on("click", function () {
      $(".history-container").removeClass("active");
    });   
    $('#log').DataTable({
            paging: true,      // Enable pagination
            searching: true,   // Enable search bar
            ordering: true,    // Enable column sorting
            info: true,        // Show table information
            lengthChange: true, // Enable changing number of rows displayed
            order: [[1, 'desc']],
        });
   });
</script>
<script>
   /*jslint unparam: true */
   /*global window, $ */
   $(function() {
       'use strict';
       // Change this to the location of your server-side upload handler:
       var url ='<?php echo base_url() ?>supplier/displaypic?id=<?php echo $details['supplier_id'] ?>&<?=$this->security->get_csrf_token_name()?>=' + crsf_hash;
       $('#fileupload').fileupload({
               url: url,
               dataType: 'json',
               formData: {
                   '<?=$this->security->get_csrf_token_name()?>': crsf_hash
               },
               done: function(e, data) {
                   //$('<p/>').text(file.name).appendTo('#files');
                   $("#dpic").attr('src', '<?php echo base_url() ?>userfiles/customers/' + data.result +
                       '?8978');
                       location.reload();
               },
               progressall: function(e, data) {
                   var progress = parseInt(data.loaded / data.total * 100, 10);
                   $('#progress .progress-bar').css(
                       'width',
                       progress + '%'
                   );
               }
           }).prop('disabled', !$.support.fileInput)
           .parent().addClass($.support.fileInput ? undefined : 'disabled');
   });
</script>
<script type="text/javascript">
   $(function() {
       $('.summernote').summernote({
           height: 100,
           toolbar: [
               // [groupName, [list of button]]
               ['style', ['bold', 'italic', 'underline', 'clear']],
               ['font', ['strikethrough', 'superscript', 'subscript']],
               ['fontsize', ['fontsize']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['height', ['height']],
               ['fullscreen', ['fullscreen']],
               ['codeview', ['codeview']]
           ]
       });
   });
</script>