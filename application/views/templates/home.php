<script type="text/javascript">
    var dataVisits = [
        <?php $tt_inc = 0;foreach ($incomechart as $row) {
        $tt_inc += $row['total'];
        echo "{ x: '" . $row['date'] . "', y: " . intval(amountExchange_s($row['total'], 0, $this->aauth->get_user()->loc)) . "},";
    }
        ?>
    ];
    var dataVisits2 = [
        <?php $tt_exp = 0; foreach ($expensechart as $row) {
        $tt_exp += $row['total'];
        echo "{ x: '" . $row['date'] . "', y: " . intval(amountExchange_s($row['total'], 0, $this->aauth->get_user()->loc)) . "},";
    }
        ?>];

</script>
<?php if(ENVIRONMENT == 'development') { ?>
<div class="alert alert-primary alert-danger" style="">
    <a href="#" class="close" data-dismiss="alert">×</a>
    <div class="message"><strong>Alert</strong>: Application is running in Development/Debug mode! Set it production mode <a href="<?=base_url('settings/debug') ?>">here</a></div>
</div>
<?php } ?>
<style>


    a.btncard {
        width: 160px !important;
        height: 130px  !important;
        -webkit-box-shadow: 3px 3px 10px 3px #dddddd;
        -moz-box-shadow: 3px 3px 10px 3px #dddddd;
        box-shadow: 3px 3px 10px 3px #ededed;
        background: #ffffff;
        padding-top: 12px !important;
        text-align: center;
        margin: 25px 20px 20px 20px;
        border-radius: 13px;
    }

    a.btncard {
        width:150px !important; 
        height:130px  !important;
        -webkit-box-shadow: 3px 3px 10px 3px #dddddd;
        -moz-box-shadow: 3px 3px 10px 3px #dddddd;
        box-shadow: 3px 3px 10px 3px #dddddd;
        background:#ffffff;
        padding-top:12px !important;
        text-align:center;
        margin-top:15px;
    }
    .btncard i {
        font-size: 43px;
        color:#5f7682;
        margin-top: 20px;
        line-height: 46px;
        margin-bottom: 5px;
    }
    .btncard span{
        position: absolute !important;
        top: -10px !important;
        right: -11px !important;
        border-radius:50%;
        line-height: 10px;
        width: 29px;
        height: 29px;
        padding:10px 1px 1px 2px;
        font-size: 11px;
        text-align: center;

    }
    a.btncard h1{
        font-size: 11px;
        font-weight: normal;
        padding-top: 5px;
        color: #313131 !important;

    }
    .btncard:hover{
        -webkit-box-shadow: 3px 3px 10px 3px #828282;
        -moz-box-shadow: 3px 3px 10px 3px #828282;
        box-shadow: 3px 3px 10px 3px #828282;
    }
    .btncard:hover span{
        -webkit-box-shadow: 3px 3px 10px 3px #828282;
        -moz-box-shadow: 3px 3px 10px 3px #828282;
        box-shadow: 3px 3px 10px 3px #828282;
        color:#000000;
    }
    .btncard:hover i{
        color:#1f8c66;
    }
    .bg-danger {
        background-color: #e92d47 !important;
    }
    .header-navbar.navbar-border.bg-gradient-x-grey-blue {
    background-image: linear-gradient(to right, #00686a 0%, #6F85AD 100%) !important;
    }
    .header-navbar .main-menu-content.navbar-container ul.nav li > a.nav-link {
        padding: 0.9rem 0.6rem;
        font-size: 13px;
        margin-right: 13px;
    }
    .header-navbar .main-menu-content.navbar-container ul.nav li > a.nav-link:hover {
    color: #d32f2f;
    }
    .col-form-label {
        font-size: 11px;
        color: #8c8c8c;
    padding-bottom: calc(0.5rem + 1px);
    }
.common-heading h1{
    font-size:25px !important;
    margin-left:25px !important;
    margin-top:15px;
    margin-top:10px;
}
</style>
<div class="col-lg-12 common-heading"><h1>APPS</h1></div>
<div class="col-lg-12  dashboard-cards">
        <a href="<?php echo base_url('Dashboard/dashboard'); ?>" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-tachometer"></i>
            <h1>DASHBOARD</h1>
        </a>
        <a href="<?= base_url() ?>customers/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            2
            </span>
            <i class="fa fa-users"></i>
            <h1>CRM</h1>
        </a>
        <a href="<?= base_url() ?>employee" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-user-plus"></i>
            <h1>HRM</h1>
        </a>
        <a href="<?= base_url() ?>SalesOrders/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span>
            <i class="fa fa-line-chart"></i>
            <h1>SALES</h1>
        </a>
        <a href="<?= base_url() ?>products" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-exchange"></i>
            <h1>STOCK</h1>
        </a>
        <a href="<?= base_url() ?>accounts/" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-money"></i>
            <h1>ACCOUNTS</h1>
        </a>
        <a href="<?= base_url() ?>tools/notes" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-cog"></i>
            <h1>TOOLS</h1>
        </a>
        <a href="<?= base_url() ?>projects" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-object-group"></i>
            <h1>PROJECTS</h1>
        </a>
        <a href="<?= base_url() ?>pos_invoices/extended/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">2</span>
            <i class="fa fa-list-alt"></i>
            <h1>REPORTS</h1>
        </a>
        <a href="https://online.cloudbizerp.com/" target="_blank" class="btn btncard position-relative" > 
            
            <i class="fa fa-globe"></i>
            <h1>ONLINE STORE</h1>
        </a>  
        
        
        
    </div>
</div>
<div class="col-lg-12 common-heading mt-2"><h1>QUICK NAVIGATIONS</h1></div>
<div class="col-lg-12  dashboard-cards">
        <a href="<?= base_url() ?>customers/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            2
            </span>
            <i class="fa fa-users"></i>
            <h1>CUSTOMERS</h1>
        </a>
        <a href="<?= base_url() ?>invoices/leads" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            5
            </span>
            <i class="fa fa-link"></i>
            <h1>LEADS</h1>
        </a>
        <a href="<?= base_url() ?>pos_invoices/create" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            5
            </span>
            <i class="fa fa-paper-plane-o"></i>
            <h1>POS</h1>
        </a>
        
        <a href="<?= base_url() ?>quote/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span>
            <i class="fa fa-pencil-square-o"></i>
            <h1>QUOTES</h1>
        </a>
        <a href="<?= base_url() ?>products/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            100+
            </span>
            <i class="fa fa-th "></i>
            <h1>PRODUCTS</h1>
        </a>
        <a href="<?= base_url() ?>products" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-exchange"></i>
            <h1>STOCK TRANSFER</h1>
        </a>
        <a href="<?= base_url() ?>purchase/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            15
            </span>
            <i class="fa fa-shopping-bag "></i>
            <h1>PURCHASE ORDERS</h1>
        </a>
        <a href="<?= base_url() ?>transactions/" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-external-link"></i>
            <h1>TRANSACTIONS</h1>
        </a>
        <a href="<?= base_url() ?>events" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-calendar"></i>
            <h1>CALENDAR</h1>
        </a>
        <a href="<?= base_url() ?>tools/todo" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-list-alt"></i>
            <h1>TODO LIST</h1>
        </a>

        
    </div>
</div>
<!-- <div class="col-lg-12  dashboard-cards">
        <a href="<?php echo base_url('Dashboard/dashboard'); ?>" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-tachometer"></i>
            <h1>DASHBOARD</h1>
        </a>
        <a href="<?= base_url() ?>customers/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            2
            </span>
            <i class="fa fa-users"></i>
            <h1>CRM</h1>
        </a>
        <a href="<?= base_url() ?>employee" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-user-plus"></i>
            <h1>HRM</h1>
        </a>
        <a href="<?= base_url() ?>SalesOrders/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span>
            <i class="fa fa-line-chart"></i>
            <h1>SALES</h1>
        </a>
        <a href="<?= base_url() ?>products" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-exchange"></i>
            <h1>STOCK</h1>
        </a>
        <a href="<?= base_url() ?>accounts/" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-money"></i>
            <h1>ACCOUNTS</h1>
        </a>
        <a href="<?= base_url() ?>tools/notes" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-cog"></i>
            <h1>TOOLS</h1>
        </a>
        <a href="<?= base_url() ?>projects" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-object-group"></i>
            <h1>PROJECTS</h1>
        </a>
        <a href="<?= base_url() ?>pos_invoices/extended/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">2</span>
            <i class="fa fa-list-alt"></i>
            <h1>REPORTS</h1>
        </a>

        
        <a href="<?= base_url() ?>pos_invoices/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            99+
            </span>
            <i class="fa fa-file-text"></i>
            <h1>INVOICES</h1>
        </a>
        <a href="<?= base_url() ?>quote/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span>
            <i class="fa fa-pencil-square-o"></i>
            <h1>QUOTES</h1>
        </a>
        <a href="<?= base_url() ?>DeliveryNotes/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span>
            <i class="fa fa-file-code-o"></i>
            <h1>DELIVERY NOTES</h1>
        </a>
        <a href="<?= base_url() ?>enquiry/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            3
            </span>
            <i class="fa fa-address-card"></i>
            <h1>CUSTOMER ENQUIRY</h1>
        </a>
        <a href="<?= base_url() ?>productcategory/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span>
            <i class="fa fa-cubes "></i>
            <h1>PRODUCTS CATRGORY</h1>
        </a>
        <a href="<?= base_url() ?>products/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            100+
            </span>
            <i class="fa fa-th "></i>
            <h1>PRODUCTS</h1>
        </a>
        <a href="<?= base_url() ?>products/custom_label" target="_blank"  class="btn btncard position-relative" > 
            <i class="fa fa-tags"></i>
            <h1>PRODUCT LABELS</h1>
        </a>
        <a href="<?= base_url() ?>purchase/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            15
            </span>
            <i class="fa fa-shopping-bag "></i>
            <h1>PURCHASE ORDERS</h1>
        </a>
        <a href="<?= base_url() ?>supplier/" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            2
            </span>
            <i class="fa fa-bus"></i>
            <h1>SUPPLIERS</h1>
        </a>
        <a href="<?= base_url() ?>productcategory/warehouse" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">
            1
            </span>
            <i class="fa fa-barcode"></i>
            <h1>WAREHOUSES</h1>
        </a>
        <a href="<?= base_url() ?>transactions/" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-external-link"></i>
            <h1>TRANSACTIONS</h1>
        </a>
       
        <a href="<?= base_url() ?>subscriptions/" target="_blank"  class="btn btncard position-relative" > 
            
            <i class="fa fa-hand-pointer-o"></i>
            <h1>SUBSCRIPTIONS</h1>
        </a>
        <a href="<?= base_url() ?>stockreturn/creditnotes" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">2</span>
            <i class="fa fa-sticky-note-o"></i>
            <h1>CREDIT NOTES</h1>
        </a>
        <a href="<?= base_url() ?>tickets" target="_blank"  class="btn btncard position-relative" > 
            <span class=" translate-middle badge rounded-pill bg-danger">2</span>
            <i class="fa fa-ticket"></i>
            <h1>SUPPORT TICKETS</h1>
        </a>
        <a href="<?= base_url() ?>transactions/income" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-money"></i>
            <h1>INCOME</h1>
        </a>
        <a href="<?= base_url() ?>transactions/expense" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-arrows-alt"></i>
            <h1>EXPENSES</h1>
        </a>
        <a href="<?= base_url() ?>employee" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-newspaper-o"></i>
            <h1>PAYROLL</h1>
        </a>
        <a href="https://online.cloudbizerp.com/" target="_blank" class="btn btncard position-relative" > 
            
            <i class="fa fa-globe"></i>
            <h1>WEBSITE</h1>
        </a>   
        <a href="https://online.cloudbizerp.com/" target="_blank" class="btn btncard position-relative" > 
            
            <i class="fa fa-chrome"></i>
            <h1>PORTAL</h1>
        </a>   
        <a href="https://play.google.com/store/games?device=windows" target="_blank" class="btn btncard position-relative" >             
            <i class="fa fa-android"></i>
            <h1>APP</h1>
        </a>
        <a href="https://chat.openai.com/" target="_blank" class="btn btncard position-relative" > 
            
            <i class="fa fa-forumbee"></i>
            <h1>AI</h1>
        </a>     
        
        
    </div>
</div> -->


