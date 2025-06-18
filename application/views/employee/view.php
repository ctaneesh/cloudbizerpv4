<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">          
         
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('employee') ?>"><?php echo $this->lang->line('Employees') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $employee['name'] ?></li>
                </ol>
            </nav>
            <h4><?php echo $this->lang->line('Employee Details') ?> : <?php echo $employee['name'] ?> <a href="<?php echo base_url('employee/update?id=' . $eid) ?>"class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> <?php echo $this->lang->line('Edit') ?> <?php echo $this->lang->line('Account') ?></a></h4>
           
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
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12 border-right">
                        <?php if(!empty($employee['picture'])){ ?>   

                            <div class="ibox-content mt-2" style="position:relative">
                                <img alt="image" id="dpic" class="card-img-top img-fluid"
                                    src="<?php echo base_url('userfiles/employee/' . $employee['picture']) ?>">
                                    <button type="button" class="btn btn-crud btn-secondary btn-sm  profile-update-icon" id="changeprofilebtn"><i class="fa fa-camera" aria-hidden="true"></i> </button>
                            </div>
                        <?php } ?> 

                        <hr>

                        <a href="<?php echo base_url('employee/updatepassword?id=' . $eid) ?>"  class="btn btn-secondary btn-sm mb-1 btn-lighten-1">
                            <i class="fa fa-key"></i> <?php echo $this->lang->line('Change Password') ?>
                        </a>

                        <!-- <a href="<?php echo base_url('employee/invoices?id=' . $eid) ?>" class="btn btn-crud btn-secondary btn-sm mr-1 mb-1 btn-lighten-1">
                            <i class="fa fa-file-text"></i> <?php echo $this->lang->line('Invoices') ?> <?php echo $this->lang->line('View') ?>
                        </a>

                        <a href="<?php echo base_url('quote?eid=' . $eid) ?>"  class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1">
                            <i  class="fa fa-quote-left"></i> <?php echo $this->lang->line('Quotes') ?> <?php echo $this->lang->line('View') ?>
                        </a> -->

                        <a href="<?php echo base_url('projects?eid=' . $eid) ?>" class="btn btn-secondary btn-sm  mb-1 btn-lighten-1">
                            <i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('Projects') ?> <?php echo $this->lang->line('View') ?>
                        </a>
                        <a href="#pop_model" data-toggle="modal" data-remote="false"
                            class="btn btn-secondary btn-sm mb-1 btn-lighten-1"><i class="fa fa-calculator"></i> <?php echo $this->lang->line('Sales') ?>
                        </a>
                        <a href="#pop_model2" data-toggle="modal" data-remote="false" class="btn btn-secondary btn-sm  mb-1 btn-lighten-1">
                            <i  class="fa fa-money"></i> <?php echo $this->lang->line('Total Income') ?> 
                        </a>
                        
                        
                        
                        <div class="row m-t-lg">
                           <div class="col-md-3">
                              <strong><?php echo $this->lang->line('Email') ?></strong>
                           </div>
                           <div class="col-md-9">
                              <?php echo $employee['email'] ?>
                           </div>
                        </div>
                        <hr>
                        <div class="row">
                           <div class="col-md-3">
                              <strong><?php echo $this->lang->line('Phone') ?></strong>
                           </div>
                           <div class="col-md-9">
                              <?php echo $employee['phone'] ?>
                           </div>
                        </div>
                        <hr>
                        <div class="row m-t-lg">
                           <div class="col-md-3">
                              <strong><?php echo $this->lang->line('Phone') ?>(Alt)</strong>
                           </div>
                           <div class="col-md-9">
                              <?php if($employee['phonealt']) { echo $employee['phonealt']; } ?>
                           </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                        

                        <!--     erp2025 add 06-01-2025   Detailed hisory starts-->
                        <button class="btn history-expand-button">
                            <span>History</span>
                        </button>

                        <div class="history-container">
                        <button class="history-close-button">
                        <span>Close</span>
                            </button>
                            <h2>History <button class="btn btn-sm logclose-btn">
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
                                    foreach ($groupedProducts as $seqence_number => $products){
                                    $flag=0;
                                    ?>              
                                        <tr>
                                        <td>        
                                            <?php    foreach ($products as $product) {
                                            if($flag==0)
                                            {?>
                                            <div class="userdata">
                                            <img class="rounded-circle1" src='<?php echo base_url('userfiles/employee/thumbnail/'.$product['picture'])?>' style="width:50px; height:50px;" ?>
                                            <?php  echo $product['name'];
                                                    $flag=1;
                                            } ?>
                                            </div>           
                                                <ul><li>  <?php echo $product['old_value'];?> > <b><span style="color:#239fd0;"><?php echo $product['new_value']?></span></b> (<?php echo $product['field_label']?>)
                                                </li></ul>
                                                <?php } ?>
                                            </td>               
                                            <td><?php echo date('d-m-Y H:i:s', strtotime($product['changed_date'])); ?></td>
                                            <td><?php echo $product['ip_address']?></td> 
                                            
                                        </tr>  
                                        <?php 
                                        $i++; 
                                    
                                    }?>
                                    </tbody>
                                </table>

                            </form>
                        </div>   
                    <!--     erp2025 add 06-01-2025   Detailed hisory ends-->
                        <!-- ============================================================================ -->
                      
                        <ul class="nav nav-tabs top-master" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption active show" id="base-tab1" data-toggle="tab"
                                       aria-controls="tab1" href="#tab1" role="tab"
                                       aria-selected="true"><?php echo $this->lang->line('Address') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                       href="#tab2" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('Reporting Details') ?></a>
                                </li>
                                  <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption topsection-caption1" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                                       href="#tab3" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('Other Informations') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption topsection-caption1" id="base-tab14" data-toggle="tab" aria-controls="tab14" href="#tab14" role="tab" aria-selected="true"><?php echo $this->lang->line('Quotes') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption topsection-caption1" id="base-tab11" data-toggle="tab"
                                        aria-controls="tab11" href="#tab11" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Invoices') ?></a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption " id="base-tab15" data-toggle="tab"
                                        aria-controls="tab15" href="#tab15" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Permissions') ?></a>
                                </li> -->
                               
                            </ul>
                           
                            <div class="tab-content px-1 pt-1">
                              <!-- -------------- Address -------------- -->
                                <div class="top-section  tab-pane  active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                    <!-- ======================================================== -->
                                    <div class="card-block">
                                       <div class="row">
                                          <div class="col-md-2">
                                             <strong><?php echo $this->lang->line('Address') ?></strong>
                                          </div>
                                          <div class="col-md-10">
                                             <?php echo $employee['address'] ?>
                                          </div>
                                       </div>
                                       <hr>
                                       <div class="row m-t-lg">
                                          <div class="col-md-2">
                                             <strong><?php echo $this->lang->line('City') ?></strong>
                                          </div>
                                          <div class="col-md-10">
                                             <?php echo $employee['city'] ?>
                                          </div>
                                       </div>
                                       <hr>
                                       <div class="row m-t-lg">
                                          <div class="col-md-2">
                                             <strong><?php echo $this->lang->line('Region') ?></strong>
                                          </div>
                                          <div class="col-md-10">
                                             <?php echo $employee['region'] ?>
                                          </div>
                                       </div>
                                       <hr>
                                       <div class="row m-t-lg">
                                          <div class="col-md-2">
                                             <strong><?php echo $this->lang->line('Country') ?></strong>
                                          </div>
                                          <div class="col-md-10">
                                          <?php echo $employee['countryname'].'('.$employee['countrycode'].')'; ?>
                                          </div>
                                       </div>
                                       <hr>
                                       <div class="row m-t-lg">
                                          <div class="col-md-2">
                                             <strong><?php echo $this->lang->line('PostBox') ?></strong>
                                          </div>
                                          <div class="col-md-10">
                                             <?php echo $employee['postbox'] ?>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- Shipping Address -------------- -->   
                              <div id="tab2" role="tabpane2"  aria-labelledby="base-tab2" class="top-section card-collapse collapse" aria-expanded="false">
                                    <div class="card-block">
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong><?php echo $this->lang->line('Reporting To') ?></strong>
                                            </div>
                                            <div class="col-md-9">
                                                : <?php echo $reportingemp['name'] ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong><?php echo $this->lang->line('Amount Limit') ?></strong>
                                            </div>
                                            <div class="col-md-9">
                                                : <?php echo $reportingemp['amount_limit'] ?>
                                            </div>
                                        </div>  
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-3">
                                                <strong><?php echo $this->lang->line('Expense Claims Approver') ?></strong>
                                            </div>
                                            <div class="col-md-9">
                                                : <?php echo $expenseclaim['name'] ?>
                                            </div>
                                        </div>  
                                    </div>              
                                </div>                        
                              <!-- -------------- Customer Details -------------- -->
                              <div id="tab3" role="tabpane3" aria-labelledby="base-tab3" class="top-section card-collapse collapse" aria-expanded="false">
                                 <div class="card-block">
                                    <div class="row m-t-lg">
                                       <div class="col-md-4">
                                          <strong><?php echo $this->lang->line('Residence Permit') ?></strong>
                                       </div>
                                       <div class="col-md-6">
                                          <?php echo $employee['residence_permit']; ?>
                                       </div>
                                    </div>
                                    <hr>
                                    <div class="row m-t-lg">
                                       <div class="col-md-4">
                                          <strong><?php echo $this->lang->line('Residence Permit Expiry') ?></strong>
                                       </div>
                                       <div class="col-md-6">
                                          <?php echo dateformat($employee['expiry_date']) ?>
                                       </div>
                                    </div>
                                    <hr>
                                    <div class="row m-t-lg">
                                       <div class="col-md-4">
                                          <strong><?php echo $this->lang->line('Passport Number') ?></strong>
                                       </div>
                                       <div class="col-md-6">
                                          <?php echo $employee['passport_number']; ?>
                                       </div>
                                    </div>
                                    <hr>
                                    <div class="row m-t-lg">
                                       <div class="col-md-4">
                                          <strong><?php echo $this->lang->line('Passport Expiry') ?></strong>
                                       </div>
                                       <div class="col-md-6">
                                          <?php echo $employee['passport_expiry'] ?>
                                       </div>
                                    </div>
                                    <hr>
                                    <div class="row m-t-lg">
                                       <div class="col-md-4">
                                          <strong><?php echo $this->lang->line('Passport Status') ?></strong>
                                       </div>
                                       <div class="col-md-6">
                                          <?php echo $employee['passport_status'] ?>
                                       </div>
                                    </div>
                                    <hr>
                                    <div class="row m-t-lg">
                                       <div class="col-md-4">
                                          <strong><?php echo $this->lang->line('Join Date') ?></strong>
                                       </div>
                                       <div class="col-md-6">
                                          <?php echo $employee['join_ate'] ?>
                                       </div>
                                    </div>
                                    
                                 </div>
                              </div>
                              <!-- -------------- Contact Details -------------- -->

                              <!-- -------------- Quote details -------------- -->
                                <div class="tab-pane top-section" id="tab14" role="tabpanel" aria-labelledby="base-tab14">
                                    <!-- ======================================================== -->
                                    <div class="table-table-scroll2">
                                        <table id="quotes" class="table table-striped table-bordered zero-configuration dataTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><?php echo $this->lang->line('No') ?></th>
                                                    <th class="text-center"><?php echo $this->lang->line('Quote') ?>#</th>
                                                    <th><?php echo $this->lang->line('Customer') ?></th>
                                                    <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                                    <th class="text-right"><?php echo $this->lang->line('Total') ?></th>
                                                    <th class="no-sort text-center" ><?php echo $this->lang->line('Approval Status') ?></th>
                                                    <th class="no-sort text-center"><?php echo $this->lang->line('Status') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center"><?php echo $this->lang->line('No') ?></th>
                                                    <th class="text-center"><?php echo $this->lang->line('Quote') ?>#</th>
                                                    <th><?php echo $this->lang->line('Customer') ?></th>
                                                    <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                                    <th class="text-right"><?php echo $this->lang->line('Total') ?></th>
                                                    <th class="no-sort text-center" ><?php echo $this->lang->line('Approval Status') ?></th>
                                                    <th class="no-sort text-center"><?php echo $this->lang->line('Status') ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- Quote details ends-------------- -->

                              <!-- -------------- Invoice details -------------- -->
                                <div class="tab-pane top-section" id="tab11" role="tabpanel" aria-labelledby="base-tab11">
                                    <!-- ======================================================== -->
                                    <table id="invoices" class="table table-striped table-bordered zero-configuration dataTable" width="100%">
                                        <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center"><?php echo $this->lang->line('Invoice') ?>#</th>
                                            <th><?php echo $this->lang->line('Customer') ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Total') ?></th>
                                            <th class="no-sort text-right"><?php echo $this->lang->line('Status') ?></th>
                                            <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center"><?php echo $this->lang->line('Invoice') ?>#</th>
                                            <th><?php echo $this->lang->line('Customer') ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('Total') ?></th>
                                            <th class="no-sort text-right"><?php echo $this->lang->line('Status') ?></th>
                                            <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>

                                        </tr>
                                        </tfoot>
                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- Invoice details ends-------------- -->
                              
                              <!-- -------------- transaction details -------------- -->
                                 <div class="tab-pane top-section" id="tab12" role="tabpanel" aria-labelledby="base-tab12">
                                    <!-- ======================================================== -->
                                    <table id="crtstable" class="table table-striped table-bordered zero-configuration dataTable" cellspacing="0" width="100%">
                                       <thead>
                                          <tr>
                                             <th><?php echo $this->lang->line('Date') ?></th>
                                             <th><?php echo $this->lang->line('Debit') ?></th>
                                             <th><?php echo $this->lang->line('Credit') ?></th>
                                             <th><?php echo $this->lang->line('Account') ?></th>
                                             <th><?php echo $this->lang->line('Method') ?></th>
                                             <th><?php echo $this->lang->line('Action') ?></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th><?php echo $this->lang->line('Date') ?></th>
                                             <th><?php echo $this->lang->line('Debit') ?></th>
                                             <th><?php echo $this->lang->line('Credit') ?></th>
                                             <th><?php echo $this->lang->line('Account') ?></th>
                                             <th><?php echo $this->lang->line('Method') ?></th>
                                             <th><?php echo $this->lang->line('Action') ?></th>
                                          </tr>
                                       </tfoot>
                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- transaction details ends-------------- -->

                              <!-- -------------- account statements details -------------- -->
                                 <div class="tab-pane top-section" id="tab13" role="tabpanel" aria-labelledby="base-tab13">
                                    <!-- ======================================================== -->
                                    <form action="<?php echo base_url() ?>customers/statement" method="post" role="form" target="_blank">
                                       <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                          value="<?php echo $this->security->get_csrf_hash(); ?>">
                                       <input type="hidden" name="customer" value="<?= $id ?>">
                                       <div class="form-group row">
                                          <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                             <label class="col-form-label" for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                             <select name="trans_type" class="form-control">
                                                <option value='All'><?php echo $this->lang->line('All Transactions') ?></option>
                                                <option value='Expense'><?php echo $this->lang->line('Debit') ?></option>
                                                <option value='Income'><?php echo $this->lang->line('Credit') ?></option>
                                             </select>
                                          </div>
                                          <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                             <label class="col-form-label" for="sdate"><?php echo $this->lang->line('From Date') ?><span class="compulsoryfld">*</span></label>
                                             <input type="text" class="form-control required" placeholder="Start Date" name="sdate" id="sdate" data-toggle="datepicker" autocomplete="false" required>
                                          </div>
                                          <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                             <label class="col-form-label"  for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                             <input type="date" class="form-control required"placeholder="End Date" name="edate"  ata-toggle="datepicker" autocomplete="false">
                                          </div>
                                       </div>
                                       <div class="form-group row">
                                          <div class="col-12 submit-section">
                                             <input type="submit" class="btn btn-crud btn-primary btn-lg"  value="<?php echo $this->lang->line('View') ?>">
                                          </div>
                                       </div>
                                    </form>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- acount statements details ends-------------- -->


                              
                     <!-- ============================================================================ -->
                       <!-- ============================================================================ -->
                       
                        <input type="hidden" class="form-control" id="customer_id" name="tid" value="<?php echo $eid; ?>">
                        
                        
                       
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div id="pop_model" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"><?php echo $this->lang->line('Calculate Total Sales') ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="form_model">
                  

                        <div class="row">
                            <div class="col mb-1"><label
                                        for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
                                <?php echo $this->lang->line('Do you want mark') ?>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" class="form-control required"
                                   name="eid" id="invoiceid" value="<?php echo $eid ?>">
                            <button type="button" class="btn btn-crud btn-default"
                                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                            <input type="hidden" id="action-url" value="employee/calc_sales">
                            <button type="button" class="btn btn-crud btn-primary"
                                    id="submit_model"><?php echo $this->lang->line('Yes') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="pop_model2" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"><?php echo $this->lang->line('Calculate Income') ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="form_model2">


                        <div class="row">
                            <div class="col mb-1"><label for="pmethod">Mark As</label>
                                Do you want to calculate total income expenses of this employee ?
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" class="form-control required"
                                   name="eid" id="invoiceid" value="<?php echo $eid ?>">
                            <button type="button" class="btn btn-crud btn-default" data-dismiss="modal">Close</button>
                            <input type="hidden" id="action-url" value="employee/calc_income">
                            <button type="button" class="btn btn-crud btn-primary" id="submit_model2">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
    <!-- The basic File Upload plugin -->
    <script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
    <script>
        $(document).ready(function() {  
            // permission section
        $('#Modules').on('change', function () {
            $('.moduleclass').prop('checked', this.checked);
        });

        // When any .moduleclass checkbox changes
        $('.moduleclass').on('change', function () {
            // If all .moduleclass checkboxes are checked, set #Modules to checked
            if ($('.moduleclass:checked').length === $('.moduleclass').length) {
                $('#Modules').prop('checked', true);
            } else {
                // Otherwise, uncheck #Modules
                $('#Modules').prop('checked', false);
            }
        });
        // permission section ends 


        $(".history-expand-button").on("click", function () {
            $(".history-container").toggleClass("active");
        });
        $(".history-close-button").on("click", function () {
            $(".history-container").removeClass("active");
        });
        $(".logclose-btn").on("click", function () {
            $(".history-container").removeClass("active");
        });
        var columnlist = [
            { 'width': '4%' }, 
            { 'width': '5%' },
            { 'width': '25%' }, 
            { 'width': '10%' },
            { 'width': '10%' },
            { 'width': '9%' },
            { 'width': '' }
        ]; 
        var columnlist_invoice = [
        { 'width': '5%' }, 
        { 'width': '10%' },
        { 'width': '25%' }, 
        { 'width': '15%' },
        { 'width': '15%' },
        { 'width': '10%' },
        { 'width': '' },
        ];
      // Hide '.one' tabs when any '.two' tab is clicked
      $('.bottom-master').on('click', function () {
         $('.top-section').removeClass('show active');
         $('.topsection-caption').removeClass('active show');
      });
      $('.top-master').on('click', function () {
         $('.botom-section').removeClass('show active');
         $('.bottomsection-caption').removeClass('active show');
      });
      // Trigger file input when button is clicked
      $('#changeprofilebtn').click(function() {
         $('#fileupload').click();
      });
      $('#log').DataTable({
            paging: true,      // Enable pagination
            searching: true,   // Enable search bar
            ordering: true,    // Enable column sorting
            info: true,        // Show table information
            lengthChange: true, // Enable changing number of rows displayed
            order: [[1, 'desc']],
        });

        var table = $('#quotes').DataTable({
                "processing": true,
                "serverSide": true,
                // responsive: true,
                <?php datatable_lang();?>
                "order": [],
                "ajax": {
                    "url": "<?php echo site_url('employee/quote_list')?>",
                    "type": "POST",
                    data: {'eid': '<?php echo $eid ?>', '<?=$this->security->get_csrf_token_name()?>': crsf_hash}
                },
            'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,  // Only disable ordering for column 0
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [1,3,5,6],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [4],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-right']);
                        }
                    }
                ],
                'columns': columnlist,

            });
            // ///////////Invoice details///////////
            $('#invoices').DataTable({
                "processing": true,
                "serverSide": true,
                // responsive: true,
                "order": [],
                "ajax": {
                    "url": "<?php echo site_url('employee/invoices_list')?>",
                    "type": "POST",
                    data: {'eid': '<?php echo $eid ?>', '<?=$this->security->get_csrf_token_name()?>': crsf_hash}
                },
                
            'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [1,3,5],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-center']);
                        }
                    },
                    {
                        'targets': [4],
                        'createdCell': function(td, cellData, rowData, row, col) {
                            addClassToColumns(td, col, ['text-right']);
                        }
                    }
                ],
                'columns': columnlist_invoice,

            });
            // ///////////Invoice details///////////
            

        });
        $(function () {
            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = '<?php echo base_url() ?>employee/displaypic?id=<?php echo $eid ?>';
            $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
                done: function (e, data) {

                    //$('<p/>').text(file.name).appendTo('#files');


                    $("#dpic").attr('src', '<?php echo base_url() ?>userfiles/employee/' + data.result + '?' + new Date().getTime());
                    location.reload();
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');


            // Sign
            var sign_url = '<?php echo base_url() ?>employee/user_sign?id=<?php echo $eid ?>';
            $('#sign_fileupload').fileupload({
                url: sign_url,
                dataType: 'json',
                formData: {'<?=$this->security->get_csrf_token_name()?>': crsf_hash},
                done: function (e, data) {

                    //$('<p/>').text(file.name).appendTo('#files');
                    $("#sign_pic").attr('src', '<?php echo base_url() ?>userfiles/employee_sign/' + data.result + '?' + new Date().getTime());
                    location.reload();

                },
                progressall: function (e, data) {
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