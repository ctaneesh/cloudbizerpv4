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
<div class="col-lg-12 common-heading"><h1>APPS</h1></div>
    <div class="col-lg-12  dashboard-cards">
        <a href="<?php echo base_url('Dashboard/dashboard'); ?>" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Dashboard-958">
            <i class="fa fa-tachometer"></i>
            <h1>DASHBOARD</h1>
        </a>
        <a href="<?= base_url() ?>dashboard_crm/" target="_blank"  class="btn btncard position-relative menu_assign_class"  data-access="CRM-95"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            2
            </span> -->
            <i class="fa fa-users"></i>
            <h1>CRM</h1>
        </a>
        <a href="<?= base_url() ?>employee" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="HRM-152">
            <i class="fa fa-user-plus"></i>
            <h1>HRM</h1>
        </a>
        <a href="<?= base_url() ?>dashboard_sales/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Sales-111"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span> -->
            <i class="fa fa-line-chart"></i>
            <h1>SALES</h1>
        </a>
        <a href="<?= base_url() ?>dashboard_stock/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Stock-121">
            <i class="fa fa-exchange"></i>
            <h1>STOCK</h1>
        </a>
        <a href="<?= base_url() ?>dashboard_accounts" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Accounts-142">
            <i class="fa fa-money"></i>
            <h1>ACCOUNTS</h1>
        </a>
        <!-- <a href="<?= base_url() ?>tools/notes" target="_blank"  class="btn btncard position-relative" >
            <i class="fa fa-cog"></i>
            <h1>TOOLS</h1>
        </a> -->
        <a href="<?= base_url() ?>projects" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Projects-204">
            <i class="fa fa-object-group"></i>
            <h1>PROJECTS</h1>
        </a>
        <a href="<?= base_url() ?>pos_invoices/extended/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Data_and_Reports-529"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">2</span> -->
            <i class="fa fa-list-alt"></i>
            <h1>REPORTS</h1>
        </a>
        <a href="https://online.cloudbizerp.com/" target="_blank" class="btn btncard position-relative menu_assign_class" data-access="Online_Store-917">             
            <i class="fa fa-globe"></i>
            <h1>ONLINE STORE</h1>
        </a>  
        <!-- <a href="<?= base_url() ?>Sales/saleviewstatement/" target="_blank" class="btn btncard position-relative" > 
            <i class="fa fa-line-chart"></i>
            <h1>PURCHASE - SALE</h1>
        </a>  -->
        
        
    </div>
</div>
<div class="col-lg-12 common-heading"><h1>QUICK NAVIGATIONS</h1></div>
    <div class="col-lg-12  dashboard-cards dashboard-cards-2">
        <a href="<?= base_url() ?>customers/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Customers-99"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            2
            </span> -->
            <i class="fa fa-address-book-o"></i>
            <h1>CUSTOMERS</h1>
        </a>
        <a href="<?= base_url() ?>invoices/leads" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Lead-126"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            5
            </span> -->
            <i class="fa fa-usd"></i>
            <h1>LEADS</h1>
        </a>
        <a href="<?= base_url() ?>pos_invoices/create" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Access_POS-954"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            5
            </span> -->
            <i class="fa fa-paper-plane-o"></i>
            <h1>POS</h1>
        </a>
        
        <a href="<?= base_url() ?>quote/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Quotes-389"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span> -->
            <i class="fa fa-pencil-square-o"></i>
            <h1>QUOTES</h1>
        </a>

        <a href="<?= base_url() ?>SalesOrders/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Sales_Orders-117"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            10
            </span> -->
            <i class="fa fa-line-chart"></i>
            <h1>SALES ORDERS</h1>
        </a>
        <a href="<?= base_url() ?>products/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Products-134"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            100+
            </span> -->
            <i class="fa fa-th "></i>
            <h1>PRODUCTS</h1>
        </a>
        <!-- <a href="<?= base_url() ?>products" target="_blank"  class="btn btncard position-relative menu_assign_class" >
            <i class="fa fa-exchange"></i>
            <h1>STOCK TRANSFER</h1>
        </a> -->
        <a href="<?= base_url() ?>purchase/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Orders-758"> 
            <!-- <span class=" translate-middle badge rounded-pill bg-danger">
            15
            </span> -->
            <i class="fa fa-shopping-bag "></i>
            <h1>PURCHASE ORDERS</h1>
        </a>
        <!-- <a href="<?= base_url() ?>transactions/" target="_blank"  class="btn btncard position-relative menu_assign_class1" >
            <i class="fa fa-external-link"></i>
            <h1>TRANSACTIONS</h1>
        </a> -->
        <a href="<?= base_url() ?>supplier/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Suppliers-127">
            <i class="fa fa-bullseye"></i>
            <h1><?=strtoupper($this->lang->line("Suppliers"))?></h1>
        </a>

        <a href="<?= base_url() ?>DeliveryNotes/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Delivery_Notes-458">
            <i class="fa fa-file-archive-o"></i>
            <h1><?=strtoupper($this->lang->line("Delivery Notes"))?></h1>
        </a>

        <a href="<?= base_url() ?>Deliveryreturn/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Delivery_Returns-118">
            <i class="fa fa-file-code-o"></i>
            <h1><?=strtoupper($this->lang->line("Delivery Returns"))?></h1>
        </a>

        <a href="<?= base_url() ?>invoices/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Invoices1-482">
            <i class="fa fa-file-text"></i>
            <h1><?=strtoupper($this->lang->line("Invoices"))?></h1>
        </a>

        <a href="<?= base_url() ?>invoices/" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Invoice_Credit_Notes-499">
            <i class="fa fa-file-word-o"></i>
            <h1><?=strtoupper($this->lang->line("Credit Notes"))?></h1>
        </a>
        
        <a href="<?= base_url() ?>productcategory/warehouse" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_Warehouses-731" >
            <i class="fa fa-sliders"></i>
            <h1><?=strtoupper($this->lang->line("Warehouses"))?></h1>
        </a>

        <a href="<?= base_url() ?>tickets" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Support_Ticket-114">
            <i class="fa fa-ticket"></i>
            <h1><?=strtoupper($this->lang->line("Tickets"))?></h1>
        </a>
        <!-- <a href="<?= base_url() ?>events" target="_blank"  class="btn btncard position-relative menu_assign_class" >
            <i class="fa fa-calendar"></i>
            <h1>CALENDAR</h1>
        </a> -->
        <a href="<?= base_url() ?>tools/todo" target="_blank"  class="btn btncard position-relative menu_assign_class" data-access="Manage_To_Do_List-213">
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


