<div class="app-content container-fluid1 plr" style="margin-top:5px;">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            
            <div class="card card-block " style="padding-bottom:100px; padding-top:100px;">
                <div class="box-header" >
                    <!-- <h3 class="box-title">DASHBOARD</h3> -->
                        <div class="col-lg-12 common-headings"><h1>APPS</h1></div>
                        <div class="col-lg-12 dashboard-cards">
                            
                            <!-- <a href="<?= base_url() ?>subscriptions/"   class="btn btncard position-relative" > 
                                
                                <i class="icon-android-calendar display-inline-block"></i>
                                <h1>SUBSCRIPTIONS</h1>
                            </a> -->
                            
                            
                            <a href="<?= base_url() ?>enquiry/"   class="btn btncard position-relative" > 
                                <!-- <span class=" translate-middle badge rounded-pill bg-danger">4</span> -->
                                
                                <i class="icon-file display-inline-block"></i>
                                <h1><?php echo strtoupper($this->lang->line('Request For Quotes')); ?></h1>
                            </a>
                            <a href="<?= base_url() ?>quote/"   class="btn btncard position-relative" > 
                                <!-- <span class=" translate-middle badge rounded-pill bg-danger">1</span> -->
                                <i class="icon-pencil-square display-inline-block"></i>
                                <h1><?php echo strtoupper($this->lang->line('Received Quotes')); ?></h1>
                            </a>
                            <a href="<?= base_url() ?>invoices/invoices"   class="btn btncard position-relative" > 
                                <!-- <span class=" translate-middle badge rounded-pill bg-danger">
                                99+
                                </span> -->
                                <i class="icon-file-text display-inline-block"></i>
                                <h1>INVOICES</h1>
                            </a>
                            <!-- <a href="<?= base_url() ?>payments/recharge"   class="btn btncard position-relative" > 
                                <i class="icon-credit-card2 display-inline-block"></i>
                                <h1>RECHARGE ACCOUNT</h1>
                            </a> -->
                            <a href="<?= base_url() ?>payments/"   class="btn btncard position-relative" > 
                                <!-- <span class=" translate-middle badge rounded-pill bg-danger">5</span> -->
                                <i class="icon-cash display-inline-block"></i>
                                <h1>PAYMENT HISTORY</h1>
                            </a>
                            <a href="<?= base_url() ?>tickets/"   class="btn btncard position-relative" > 
                                <!-- <span class=" translate-middle badge rounded-pill bg-danger">4</span> -->
                                <i class="icon-ticket display-inline-block"></i>
                                <h1>TICKETS</h1>
                            </a>
                            <a href="<?= base_url() ?>projects/"   class="btn btncard position-relative d-none" > 
                                <!-- <span class=" translate-middle badge rounded-pill bg-danger">5</span> -->
                                <i class="icon-stack display-inline-block"></i>
                                <h1>PROJECTS</h1>
                            </a>
                            <a href="<?= base_url() ?>user/profile"   class="btn btncard position-relative" > 
                                <i class="icon-user1 display-inline-block"></i>
                                <h1>PROFILE</h1>
                            </a>
                            <a href="https://online.cloudbizerp.com/"  class="btn btncard position-relative" > 
                            
                                <i class="icon-globe display-inline-block"></i>
                                <h1>ONLINE STORE</h1>
                            </a>
                                
                            <a href="https://cloudaierp.com/erpportal/"  class="btn btncard position-relative d-none" > 
                                
                                <i class="icon-chrome display-inline-block"></i>
                                <h1>PORTAL</h1>
                            </a>  
                            <a href="https://play.google.com/store/games?device=windows"  class="btn btncard position-relative" >             
                                <i class="icon-android display-inline-block"></i>
                                <h1>APP</h1>
                            </a> 
                            <a href="https://chat.openai.com/"  class="btn btncard position-relative d-none" > 
                            
                                <i class="icon-forumbee display-inline-block"></i>
                                <h1>AI</h1>
                            </a>
                        
                        </div>
                       
                        <!-- ------------------------------------------------------------------ -->
                        <div class="col-lg-12 common-heading"><h1>QUICK NAVIGATIONS</h1></div>
                        <div class="col-lg-12  dashboard-cards dashboard-cards-2">
                            <a href="<?= base_url() ?>enquiry/create"   class="btn btncard position-relative menu_assign_class" data-access="Manage_Customers-99"> 
                               
                                <i class="icon-book"></i>
                                <h1>New Request</h1>
                            </a>
                            <a href="<?= base_url() ?>tickets/addticket"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Lead-126"> 
                          
                                <i class="icon-ticket2"></i>
                                <h1>New Ticket</h1>
                            </a>
                           
                            <a href="<?= base_url() ?>user/profile"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Lead-126"> 
                          
                                <i class="icon-user-check"></i>
                                <h1>Change Password</h1>
                            </a>
                           

                            
                        </div>
                        <!-- ------------------------------------------------------------------ -->
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

