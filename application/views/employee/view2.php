<div class="content-body">
    <div class="card">
        <div class="card-header border-bottom">          
         
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('employee') ?>"><?php echo $this->lang->line('Employee') ?></a></li>
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
                    <div class="col-md-4 border-right">
                        <?php if(!empty($employee['picture'])){ ?>   

                            <div class="ibox-content mt-2" style="position:relative">
                                <img alt="image" id="dpic" class="card-img-top img-fluid"
                                    src="<?php echo base_url('userfiles/employee/' . $employee['picture']) ?>">
                                    <button type="button" class="btn btn-secondary btn-sm  profile-update-icon" id="changeprofilebtn"><i class="fa fa-camera" aria-hidden="true"></i> </button>
                            </div>
                        <?php } ?> 

                        <hr>

                        <a href="<?php echo base_url('employee/updatepassword?id=' . $eid) ?>"  class="btn btn-secondary btn-sm mb-1 btn-lighten-1">
                            <i class="fa fa-key"></i> <?php echo $this->lang->line('Change Password') ?>
                        </a>

                        <!-- <a href="<?php echo base_url('employee/invoices?id=' . $eid) ?>" class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1">
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
                              <?php echo $employee['phonealt'] ?>
                           </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                         <!-- =================================History section=========================== -->
                     <button class="history-expand-button">
                           <span>History</span>
                           </button>

                           <div class="history-container">
                            <button class="history-close-button">
                                <span>Close</span>
                            </button>
                           <h2>History</h2>
                           <form>
                           <table id="logtable" class="table table-striped table-bordered zero-configuration" style="width:100%;">
                        <thead>
                        <tr>       
                            <th><?php echo "#" ?></th>
                            <th><?php echo $this->lang->line('Action_performed') ?></th>  
                            <th><?php echo $this->lang->line('IP address')?></th>
                            <th><?php echo $this->lang->line('Performed By') ?></th>
                            <th><?php echo $this->lang->line('Performed At')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1;
                           foreach ($log as $row) { ?>
                          <tr>    
                            <td><?php echo $i?></td>
                            <td><?php echo $row['action_performed']?></td>
                            <td><?php echo $row['ip_address']?></td>
                            <td><?php echo $row['name']?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row['performed_dt'])); ?></td>
                          </tr>
                  
                  <?php $i++; } ?>
                        </tbody>
                           </table>
                              
                           </form>
                           </div>
                             <!-- =========================History End=================== -->
                        
                        <!-- ============================================================================ -->
                      
                        <ul class="nav nav-tabs top-master" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab1" data-toggle="tab"
                                       aria-controls="tab1" href="#tab1" role="tab"
                                       aria-selected="true"><?php echo $this->lang->line('Address') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab2" data-toggle="tab" aria-controls="tab2"
                                       href="#tab2" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('Reporting Details') ?></a>
                                </li>
                                  <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab3" data-toggle="tab" aria-controls="tab3"
                                       href="#tab3" role="tab"
                                       aria-selected="false"><?php echo $this->lang->line('Other Informations') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab14" data-toggle="tab" aria-controls="tab14" href="#tab14" role="tab" aria-selected="true"><?php echo $this->lang->line('Quotes') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab11" data-toggle="tab"
                                        aria-controls="tab11" href="#tab11" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Invoices') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption active show" id="base-tab15" data-toggle="tab"
                                        aria-controls="tab15" href="#tab15" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Permissions') ?></a>
                                </li>
                               
                            </ul>
                           
                            <div class="tab-content px-1 pt-1">
                              <!-- -------------- Address -------------- -->
                                <div class="top-section tab-pane " id="tab1" role="tabpanel" aria-labelledby="base-tab1">
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
                                             <input type="submit" class="btn btn-primary btn-lg"  value="<?php echo $this->lang->line('View') ?>">
                                          </div>
                                       </div>
                                    </form>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- acount statements details ends-------------- -->

                               <!-- -------------- Permissions details -------------- -->
                                 <div class="tab-pane top-section active show" id="tab15" role="tabpanel" aria-labelledby="base-tab15">
                                    <!-- ======================================================== -->
                                    <form  method="post" role="form" >
                                       <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2">
                                                <label class="col-form-label" for="role_id"><?php echo $this->lang->line('Role') ?>*</label>
                                                <select name="role_id" id="role_id" class="form-control form_select">
                                                    <option value="">Employee</option>
                                                    <option value="">Admin</option>
                                                    <option value="">Sales</option>
                                                </select>
                                        </div>
                                       </div>
                                       <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item mr-1" role="presentation">
                                                <button class="btn nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo $this->lang->line('Modules') ?></button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="btn nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo $this->lang->line('Customization') ?></button>
                                            </li>
                                        
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <!-- ======================================================== -->
                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                <div class="col-lg-4 row">
                                                    <div class="module-dashboard">
                                                        <h4>
                                                            <input type="checkbox" id="Modules" name="Modules" >
                                                            <label class="col-form-label1" for="Modules">Modules</label>
                                                        </h4>
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" id="Apps" name="Apps" class="moduleclass">
                                                                <label for="Apps"><?php echo $this->lang->line('Apps') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Dashboard" name="Dashboard" class="moduleclass">
                                                                <label for="Dashboard"><?php echo $this->lang->line('Dashboard') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="CRM" name="CRM" class="moduleclass">
                                                                <label for="CRM"><?php echo $this->lang->line('CRM') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Sales" name="Sales" class="moduleclass">
                                                                <label for="Sales"><?php echo $this->lang->line('Sales') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Stock" name="Stock" class="moduleclass">
                                                                <label for="Stock"><?php echo $this->lang->line('Stock') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Accounts" name="Accounts" class="moduleclass">
                                                                <label for="Accounts"><?php echo $this->lang->line('Accounts') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Project" name="Project" class="moduleclass">
                                                                <label for="Project"><?php echo $this->lang->line('Project') ?></label>
                                                            </li>
                                                            
                                                            <li>
                                                                <input type="checkbox" id="Online_Store" name="Online_Store" class="moduleclass">
                                                                <label for="Online_Store"><?php echo $this->lang->line('Online Store') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="HRM" name="HRM" class="moduleclass">
                                                                <label for="HRM"><?php echo $this->lang->line('HRM') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Data_And_Reports" name="Data_And_Reports" class="moduleclass">
                                                                <label for="Data_And_Reports"><?php echo $this->lang->line('Data & Reports') ?></label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ======================================================== -->

                                            <!-- ======================================================== -->
                                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                <!-- ======================= CRM Section Starts ================ -->
                                                 <table class="w-100">
                                                    <tr>
                                                        <td width="30%"><hr></td>
                                                        <td width="10%" class="text-center"><b><?php echo $this->lang->line('CRM') ?></b></td>
                                                        <td width="30%"><hr></td>
                                                    </tr>
                                                 </table>
                                                 <div class="col-lg-4 row mt-2">
                                                    <div class="module-dashboard">
                                                        <h4>
                                                            <input type="checkbox" id="Customers" name="Customers" >
                                                            <label class="col-form-label1" for="Customers"><?php echo $this->lang->line('Customers') ?></label>
                                                        </h4>
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" id="New_Customer" name="New_Customer" class="moduleclass">
                                                                <label for="New_Customer"><?php echo $this->lang->line('New Customer') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Manage_Customers" name="Manage_Customers" class="moduleclass">
                                                                <label for="Manage_Customers"><?php echo $this->lang->line('Manage Customers') ?></label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Customer_Groups" name="Customer_Groups" class="moduleclass">
                                                                <label for="Customer_Groups"><?php echo $this->lang->line('Customer Groups') ?></label>
                                                            </li>
                                                           
                                                        </ul>
                                                    </div>
                                                </div>

                                                <!-- ======================= CRM Section Ends ================ -->
                                            </div>
                                            <!-- ======================================================== -->

                                        </div>
                                    </form>
                                    <!-- ======================================================== -->
                                </div>
                              <!-- -------------- Permission details ends-------------- -->

                              
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
                    <input id="fileupload" type="file" name="files[]">

                        <div class="row">
                            <div class="col mb-1"><label
                                        for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
                                <?php echo $this->lang->line('Do you want mark') ?>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" class="form-control required"
                                   name="eid" id="invoiceid" value="<?php echo $eid ?>">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                            <input type="hidden" id="action-url" value="employee/calc_sales">
                            <button type="button" class="btn btn-primary"
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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="hidden" id="action-url" value="employee/calc_income">
                            <button type="button" class="btn btn-primary" id="submit_model2">Yes</button>
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