<div class="content-body">
    <?php       
    // if (($msg = check_permission($permissions)) !== true) {
    //     echo $msg;
    //     return;
    // }
    ?>
    <div class="card">
        <div class="card-header border-bottom">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="<?= base_url('dashboard') ?>"><?php echo $this->lang->line('Dashboard') ?></a></li>
                    <li class="breadcrumb-item"><a
                            href="<?= base_url('customers') ?>"><?php echo $this->lang->line('Customers') ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $details['name'] ?></li>
                </ol>
            </nav>
            <h4 class="card-title"><?php echo $this->lang->line('Customer Details') ?>
                : <?php echo $details['name'] ?>
                <a href="<?php echo base_url('customers/create?id=' . $details['customerid']) ?>"
                    class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i>
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
                        <div class="ibox-content mt-2" style="position:relative">
                            <img alt="image" id="dpic" class="card-img-top img-fluid"
                                src="<?php echo base_url('userfiles/customers/') . $details['picture'] ?>">
                            <button type="button" class="btn btn-crud btn-secondary btn-sm  profile-update-icon"
                                id="changeprofilebtn"><i class="fa fa-camera" aria-hidden="true"></i> </button>
                        </div>
                        <hr>
                        <h6><?php echo $this->lang->line('Client Group') ?>
                            <small><?php echo $customergroup['title'] ?></small>
                        </h6>

                        <a href="<?php echo base_url('customers/view?id=' . $details['customer_id']) ?>"
                            class="btn btn-secondary btn-sm mb-1 btn-lighten-1 d-none"><i class="fa fa-user"></i>
                            <?php echo $this->lang->line('View') ?></a>

                        <a href="<?php echo base_url('customers/balance?id=' . $details['customer_id']) ?>"
                            class="btn btn-secondary btn-sm  mb-1 btn-lighten-1"><i class="fa fa-briefcase"></i>
                            <?php echo $this->lang->line('Wallet') ?>
                        </a>

                        <a href="<?php echo base_url('customers/changepassword?id=' . $details['customer_id']) ?>"
                            class="btn btn-secondary btn-sm  mb-1 btn-lighten-1"><i class="fa fa-key"></i>
                            <?php echo $this->lang->line('Change Password') ?>
                        </a>

                        <a href="#sendMail" data-toggle="modal" data-remote="false" style="width:180px;"
                            class="btn btn-secondary btn-sm mb-1" data-type="reminder"><i class="fa fa-envelope"></i>
                            <?php echo $this->lang->line('Send Message') ?>
                        </a>
                        <!-- <button type="button" class="btn btn-secondary btn-sm  mb-1 btn-lighten-1" id="changeprofilebtn">
                           <i class="fa fa-camera" aria-hidden="true"></i> <?php echo $this->lang->line('Change Profile Picture') ?>
                        </button> -->

                        <div class="row mt-1">
                            <div class="col-md-12">

                                <?php if ($details['company']) { ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><?php echo $this->lang->line('Company') ?></strong>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo $details['company'] ?>
                                    </div>
                                </div>
                                <hr>
                                <?php } ?>
                                <div class="row m-t-lg">
                                    <div class="col-md-3">
                                        <strong>Email</strong>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo $details['email'] ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row m-t-lg">
                                    <div class="col-md-3">
                                        <strong><?php echo $this->lang->line('Phone') ?></strong>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo $details['phone'] ?>
                                    </div>
                                </div>
                                <hr>

                            </div>
                        </div>

                        <h5 class="bg-blue bg-lighten-4 p-1">
                            <?php echo $this->lang->line('Wallet') . ' ' . $this->lang->line('Balance') . ': ' . amountExchange($details['balance'], 0, $this->aauth->get_user()->loc) ?>
                        </h5>
                    </div>
                    <div class="col-md-8">
                        <div id="mybutton">
                            <div class="d-none">
                                <a href="<?php echo base_url('customers/bulkpayment?id=' . $details['customer_id']) ?>"
                                    class="btn btn-secondary btn-sm"><i class="fa fa-money"></i>
                                    <?php echo $this->lang->line('Bulk Payment') ?>
                                </a>
                                <!-- <a href="#sendMail" data-toggle="modal" data-remote="false"
                           class="btn btn-secondary btn-sm " data-type="reminder"><i class="fa fa-envelope"></i>
                        <?php echo $this->lang->line('Send Message') ?>
                        </a> -->
                            </div>
                        </div>
                        <div class="mt-2">


                            <!-- ============================================================================ -->

                            <ul class="nav nav-tabs top-master" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption active show" id="base-tab1"
                                        data-toggle="tab" aria-controls="tab1" href="#tab1" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Address') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab2" data-toggle="tab"
                                        aria-controls="tab2" href="#tab2" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Shipping Address') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab3" data-toggle="tab"
                                        aria-controls="tab3" href="#tab3" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Customer Details') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption" id="base-tab4" data-toggle="tab"
                                        aria-controls="tab4" href="#tab4" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Contact Details'); ?></a>
                                </li>
                            </ul>



                            <div class="tab-content px-1 pt-1">
                                <!-- -------------- Address -------------- -->
                                <div class="top-section tab-pane active show" id="tab1" role="tabpanel"
                                    aria-labelledby="base-tab1">
                                    <!-- ======================================================== -->
                                    <div class="card-block">
                                        <div class="row">
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
                                                <?php echo $details['countryname'] ?>
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
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- Shipping Address -------------- -->
                                <div id="tab2" role="tabpane2" aria-labelledby="base-tab2"
                                    class="top-section card-collapse collapse" aria-expanded="false">
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
                                <!-- -------------- Customer Details -------------- -->
                                <div id="tab3" role="tabpane3" aria-labelledby="base-tab3"
                                    class="top-section card-collapse collapse" aria-expanded="false">
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
                                        <hr>
                                        <div class="row m-t-lg">
                                            <div class="col-md-4">
                                                <strong><?php echo $this->lang->line('Register') ?>
                                                    <?php echo $this->lang->line('Date') ?></strong>
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
                                <!-- -------------- Contact Details -------------- -->
                                <div id="tab4" role="tabpane4" aria-labelledby="base-tab4"
                                    class="top-section card-collapse collapse" aria-expanded="false">
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

                            <input class="btn btn-sm btn-primary d-none" id="fileupload" type="file" name="files[]">
                            <hr>
                            <ul class="nav nav-tabs top-master1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption1" id="base-tab5" data-toggle="tab"
                                        aria-controls="tab5" href="#tab5" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Wallet Summary'); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink topsection-caption1" id="base-tab5" data-toggle="tab"
                                        aria-controls="tab6" href="#tab6" role="tab"
                                        aria-selected="false"><?php echo $this->lang->line('Wallet Recharge'); ?> /
                                        <?php echo $this->lang->line('Payment History'); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <!-- -------------- Address -------------- -->
                                <!-- <div class="top-section1 tab-pane active show" id="tab5" role="tabpanel" aria-labelledby="base-tab5"> -->

                                <!-- -------------- Wallet Summary -------------- -->
                                <div id="tab5" role="tabpane5" aria-labelledby="base-tab5"
                                    class="top-section1 card-collapse collapse" aria-expanded="false">
                                    <div class="row">
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
                                    </div>
                                </div>
                                <!-- </div>  -->
                                <!-- -------------- Wallet Recharge -------------- -->
                                <div id="tab6" role="tabpane6" aria-labelledby="base-tab6"
                                    class="top-section1 card-collapse collapse" aria-expanded="false">
                                    <div class="row">
                                        <table class="table table-striped table-bordered zero-configuration dataTable">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('Amount') ?></th>
                                                    <th><?php echo $this->lang->line('Note') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="activity">
                                                <?php foreach ($activity as $row) {
                                             echo '<tr><td>' . $row['col1'] . '</td><td>' . $row['col2'] . '</td></tr>';
                                             } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================================ -->
                            <hr>
                            <input type="hidden" class="form-control" id="customer_id" name="tid"  value="<?php echo $details['customerid'] ?>">
                            <ul class="nav nav-tabs bottom-master" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption" id="base-tab11"
                                        data-toggle="tab" aria-controls="tab11" href="#tab11" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Invoices') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  bottomsection-caption" id="base-tab12" data-toggle="tab"
                                        aria-controls="tab12" href="#tab12" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Transactions') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption" id="base-tab13"
                                        data-toggle="tab" aria-controls="tab13" href="#tab13" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Account Statements') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption" id="base-tab14"
                                        data-toggle="tab" aria-controls="tab14" href="#tab14" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Quotes') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption" id="base-tab16"
                                        data-toggle="tab" aria-controls="tab16" href="#tab16" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Subscriptions') ?></a>
                                </li>
                                </li>
                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <!-- -------------- Invoice details -------------- -->
                                <div class="tab-pane botom-section" id="tab11" role="tabpanel"
                                    aria-labelledby="base-tab11">
                                    <!-- ======================================================== -->
                                    <table id="invoices"
                                        class="table table-striped table-bordered zero-configuration dataTable"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('Invoice') ?>#</th>
                                                <th><?php echo $this->lang->line('Date') ?></th>
                                                <th><?php echo $this->lang->line('Total') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?php echo $this->lang->line('Invoice') ?>#</th>
                                                <th><?php echo $this->lang->line('Date') ?></th>
                                                <th><?php echo $this->lang->line('Total') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- Invoice details ends-------------- -->

                                <!-- -------------- transaction details -------------- -->
                                <div class="tab-pane botom-section" id="tab12" role="tabpanel"
                                    aria-labelledby="base-tab12">
                                    <!-- ======================================================== -->
                                    <table id="crtstable"
                                        class="table table-striped table-bordered zero-configuration dataTable"
                                        cellspacing="0" width="100%">
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
                                <div class="tab-pane botom-section" id="tab13" role="tabpanel"
                                    aria-labelledby="base-tab13">
                                    <!-- ======================================================== -->
                                    <form action="<?php echo base_url() ?>customers/statement" method="post" role="form"
                                        target="_blank">
                                        <input type="hidden"
                                            name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                            value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <input type="hidden" name="customer" value="<?= $id ?>">
                                        <div class="form-group row">
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                    for="pay_cat"><?php echo $this->lang->line('Type') ?></label>
                                                <select name="trans_type" class="form-control">
                                                    <option value='All'>
                                                        <?php echo $this->lang->line('All Transactions') ?></option>
                                                    <option value='Expense'><?php echo $this->lang->line('Debit') ?>
                                                    </option>
                                                    <option value='Income'><?php echo $this->lang->line('Credit') ?>
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                    for="sdate"><?php echo $this->lang->line('From Date') ?><span
                                                        class="compulsoryfld">*</span></label>
                                                <input type="text" class="form-control required"
                                                    placeholder="Start Date" name="sdate" id="sdate"
                                                    data-toggle="datepicker" autocomplete="false" required>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <label class="col-form-label"
                                                    for="edate"><?php echo $this->lang->line('To Date') ?></label>
                                                <input type="date" class="form-control required" placeholder="End Date"
                                                    name="edate" ata-toggle="datepicker" autocomplete="false">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12 submit-section">
                                                <input type="submit" class="btn btn-primary btn-lg"
                                                    value="<?php echo $this->lang->line('View') ?>">
                                            </div>
                                        </div>
                                    </form>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- acount statements details ends-------------- -->
                                <!-- -------------- quote details -------------- -->
                                <div class="tab-pane botom-section" id="tab14" role="tabpanel"
                                    aria-labelledby="base-tab14">
                                    <!-- ======================================================== -->
                                    <table id="quotes"
                                        class="table table-striped table-bordered zero-configuration dataTable"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>

                                                <th><?php echo $this->lang->line('Quote') ?>#</th>

                                                <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                                <th class="text-right"><?php echo $this->lang->line('Total') ?></th>
                                                <th class="no-sort text-center">
                                                    <?php echo $this->lang->line('Status') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot>
                                            <tr>

                                                <th><?php echo $this->lang->line('quote_date') ?>#</th>

                                                <th class="text-center"><?php echo $this->lang->line('Date') ?></th>
                                                <th class="text-right"> <?php echo $this->lang->line('Total') ?></th>
                                                <th class="no-sort text-center">
                                                    <?php echo $this->lang->line('Status') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>

                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- quote details ends-------------- -->

                                <!-- -------------- subscription details -------------- -->
                                <div class="tab-pane botom-section" id="tab16" role="tabpanel"
                                    aria-labelledby="base-tab16">
                                    <!-- ======================================================== -->
                                    <table id="subscription"
                                        class="table table-striped table-bordered zero-configuration" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('Invoice') ?>#</th>
                                                <th><?php echo $this->lang->line('Date') ?></th>
                                                <th><?php echo $this->lang->line('Total') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?php echo $this->lang->line('Invoice') ?>#</th>
                                                <th><?php echo $this->lang->line('Date') ?></th>
                                                <th><?php echo $this->lang->line('Total') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Status') ?></th>
                                                <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- subscription details ends-------------- -->


                            </div>
                            <!-- ============================================================================ -->
                            <hr>
                            <ul class="nav nav-tabs bottom-master1" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption1" id="base-tab15"
                                        data-toggle="tab" aria-controls="tab15" href="#tab15" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Projects') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption1" id="base-tab17"
                                        data-toggle="tab" aria-controls="tab17" href="#tab17" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Notes') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link breaklink bottomsection-caption1" id="base-tab18"
                                        data-toggle="tab" aria-controls="tab18" href="#tab18" role="tab"
                                        aria-selected="true"><?php echo $this->lang->line('Documents') ?></a>
                                </li>
                            </ul>
                            <div class="tab-content px-1 pt-1">
                                <!-- -------------- Project details -------------- -->
                                <div class="tab-pane botom-section1" id="tab15" role="tabpanel"
                                    aria-labelledby="base-tab15">
                                    <!-- ======================================================== -->
                                    <table id="ptable" class="table table-striped table-bordered zero-configuration"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $this->lang->line('Project') ?></th>
                                                <th><?php echo $this->lang->line('Due Date') ?></th>
                                                <th><?php echo $this->lang->line('Customer') ?></th>
                                                <th><?php echo $this->lang->line('Status') ?></th>
                                                <th><?php echo $this->lang->line('Actions') ?></th>


                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- Project details ends-------------- -->


                                <!-- -------------- note  details -------------- -->
                                <div class="tab-pane botom-section1" id="tab17" role="tabpanel"
                                    aria-labelledby="base-tab17">
                                    <!-- ======================================================== -->
                                    <table id="notestable" class="table table-striped table-bordered zero-configuration"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $this->lang->line('Title') ?></th>
                                                <th><?php echo $this->lang->line('Added') ?></th>
                                                <th><?php echo $this->lang->line('Action') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- note  details ends-------------- -->
                                <!-- -------------- documents  details -------------- -->
                                <div class="tab-pane botom-section1" id="tab18" role="tabpanel"
                                    aria-labelledby="base-tab18">
                                    <!-- ======================================================== -->
                                    <table id="doctable" class="table table-striped table-bordered zero-configuration"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $this->lang->line('Title') ?></th>
                                                <th><?php echo $this->lang->line('Added') ?></th>
                                                <th><?php echo $this->lang->line('Action') ?></th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>
                                    <!-- ======================================================== -->
                                </div>
                                <!-- -------------- documents  details ends-------------- -->
                            </div>

                            <!-- <a href="<?php echo base_url('customers/invoices?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-file-text"></i> <?php echo $this->lang->line('View Invoices') ?>
                        </a>
                        <a href="<?php echo base_url('customers/transactions?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1  btn-lighten-1"><i
                           class="fa fa-money"></i> <?php echo $this->lang->line('View Transactions') ?>
                        </a>
                        <a href="<?php echo base_url('customers/statement?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-briefcase"></i>
                        <?php echo $this->lang->line('Account Statements') ?>
                        </a>
                        <a href="<?php echo base_url('customers/quotes?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-quote-left"></i> <?php echo $this->lang->line('Quotes') ?>
                        </a> <a href="<?php echo base_url('customers/projects?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-2"><i class="fa fa-bullhorn"></i>
                        <?php echo $this->lang->line('Projects') ?>
                        </a>
                        <a href="<?php echo base_url('customers/invoices?id=' . $details['customer_id']) ?>&t=sub"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i
                           class="fa fa-calendar-check-o"></i>
                        <?php echo $this->lang->line('Subscriptions') ?>
                        </a>
                        <a href="<?php echo base_url('customers/notes?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i class="fa fa-book"></i>
                        <?php echo $this->lang->line('Notes') ?>
                        </a>
                        <a href="<?php echo base_url('customers/documents?id=' . $details['customer_id']) ?>"
                           class="btn btn-secondary btn-sm mr-1 mb-1 btn-lighten-1"><i class="icon-folder"></i>
                        <?php echo $this->lang->line('Documents') ?>
                        </a> -->

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
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <div class="row">
                        <div class="col">
                            <label for="customername"
                                class="col-form-label"><?php echo $this->lang->line('Email') ?><span
                                    class="compulsoryfld">*</span></label>
                            <input type="email" class="form-control required" placeholder="Email" name="mailtoc"
                                value="<?php echo $details['email'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="customername"
                                class="col-form-label"><?php echo $this->lang->line('Customer Name') ?><span
                                    class="compulsoryfld">*</span></label>
                            <input type="text" class="form-control required" name="customername"
                                value="<?php echo $details['name'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="subject" class="col-form-label"><?php echo $this->lang->line('Subject') ?><span
                                    class="compulsoryfld">*</span></label>
                            <input type="text" class="form-control required" name="subject" id="subject" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="contents" class="col-form-label"><?php echo $this->lang->line('Message') ?><span
                                    class="compulsoryfld">*</span></label>
                            <textarea name="text" class="summernote form-control required" id="contents"
                                title="Contents" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="cid" name="tid" value="<?php echo $details['customer_id'] ?>">
                    <input type="hidden" id="action-url" value="communication/send_general">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                <button type="button" class="btn btn-primary"
                    id="sendNow"><?php echo $this->lang->line('Send') ?></button>
            </div>
        </div>
    </div>
</div>



<!--     erp2025 add 06-01-2025   Detailed hisory ends-->
<!-- =========================History End=================== -->
<script src="<?php echo assets_url('assets/myjs/jquery.ui.widget.js') ?>"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo assets_url('assets/myjs/jquery.fileupload.js') ?>"></script>
<script>
$(document).ready(function() {


    // Hide '.one' tabs when any '.two' tab is clicked
    $('.bottom-master').on('click', function() {
        $('.top-section').removeClass('show active');
        $('.topsection-caption').removeClass('active show');
        $('.top-section1').removeClass('show active');
        $('.topsection-caption1').removeClass('active show');
        $('.botom-section1').removeClass('show active');
        $('.bottomsection-caption1').removeClass('active show');
    });
    $('.bottom-master1').on('click', function() {
        $('.top-section').removeClass('show active');
        $('.topsection-caption').removeClass('active show');
        $('.top-section1').removeClass('show active');
        $('.topsection-caption1').removeClass('active show');
        $('.botom-section').removeClass('show active');
        $('.bottomsection-caption').removeClass('active show');
    });
    $('.top-master').on('click', function() {
        $('.botom-section').removeClass('show active');
        $('.bottomsection-caption').removeClass('active show');
        $('.botom-section1').removeClass('show active');
        $('.bottomsection-caption1').removeClass('active show');
        $('.top-section1').removeClass('show active');
        $('.topsection-caption1').removeClass('active show');
    });
    $('.top-master1').on('click', function() {
        $('.botom-section').removeClass('show active');
        $('.bottomsection-caption').removeClass('active show');
        $('.botom-section1').removeClass('show active');
        $('.bottomsection-caption1').removeClass('active show');
        $('.top-section').removeClass('show active');
        $('.topsection-caption').removeClass('active show');
    });

    // Trigger file input when button is clicked
    $('#changeprofilebtn').click(function() {
        $('#fileupload').click();
    });


    // erp2024 invoice datatable ajax 13-10-2024 starts
    $('#invoices').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        responsive: true,
        <?php datatable_lang();?> 'order': [],
        'ajax': {
            'url': "<?php echo site_url('customers/inv_list')?>",
            'type': 'POST',
            'data': {
                'cid': $("#customer_id").val(),
                'tyd': '<?php echo @$_GET['t'] ?>',
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },
        'columnDefs': [{
            'targets': [0],
            'orderable': false,
            'className': 'text-center'
        }, ],
    });
    // erp2024 invoice ajax 13-10-2024 ends

    // erp2024 transactions datatable ajax 13-10-2024 starts
    table = $('#crtstable').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('customers/translist')?>",
            "type": "POST",
            "data": {
                'cid': <?php echo $_GET['id'] ?>,
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [0], //first column / numbering column
            "orderable": true, //set not orderable
        }, ],
    });
    // erp2024 transactions datatable ajax 13-10-2024 ends

    //erp2024 quotes list start
    $('#quotes').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        responsive: true,
        'order': [],
        'ajax': {
            'url': "<?php echo site_url('customers/qto_list')?>",
            'type': 'POST',
            'data': {
                'cid': <?php echo $_GET['id'] ?>,
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },
        'columnDefs': [{
            'targets': [0],
            'orderable': false,
            'className': 'text-center'
        }, ],
    });
    //erp2024 quotes list ends

    //erp2024 project details starts
    $('#ptable').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        responsive: true,
        'order': [],
        'ajax': {
            'url': "<?php echo site_url('customers/prj_list')?>",
            'type': 'POST',
            'data': {
                'cid': <?php echo $_GET['id'] ?>,
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },
        'columnDefs': [{
            'targets': [0],
            'orderable': false,
        }, ],
    });
    //erp2024 project details ends

    //erp2024 subscriptions starts 
    $('#subscription').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        responsive: true,
        'order': [],
        'ajax': {
            'url': "<?php echo site_url('customers/inv_list')?>",
            'type': 'POST',
            'data': {
                'cid': <?php echo $_GET['id'] ?>,
                'tyd': 'sub',
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },
        'columnDefs': [{
            'targets': [0],
            'orderable': false,
            'className': 'text-center'
        }, ],
    });
    //erp2024 subscriptions ends

    //erp2024 note details starts
    $('#notestable').DataTable({
        'processing': true,
        'serverSide': true,
        'stateSave': true,
        responsive: true,
        'order': [],
        'ajax': {
            'url': "<?php echo site_url('customers/notes_load_list')?>",
            'type': 'POST',
            'data': {
                'cid': <?php echo $_GET['id'] ?>,
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },
        'columnDefs': [{
            'targets': [0],
            'orderable': false,
            'className': 'text-center'
        }, ],
    });
    //erp2024 note details ends

    //erp2024 documents
    $('#doctable').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax": {
            "url": "<?php echo site_url('customers/document_load_list')?>",
            "type": "POST",
            'data': {
                'cid': <?=$id ?>,
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            }
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
            'className': 'text-center'
        }, ],

    });
    //documents ends



});
/*jslint unparam: true */
/*global window, $ */
$(function() {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url =
        '<?php echo base_url() ?>customers/displaypic?id=<?php echo $details['customer_id'] ?>&<?=$this->security->get_csrf_token_name()?>=' +
        crsf_hash;
    $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            formData: {
                '<?=$this->security->get_csrf_token_name()?>': crsf_hash
            },
            done: function(e, data) {
                //  $('<p>').text(file.name).appendTo('#files');
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