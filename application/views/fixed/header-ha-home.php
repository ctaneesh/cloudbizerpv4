<link rel="stylesheet" type="text/css"
      href="<?= assets_url() ?>app-assets/<?= LTR ?>/core/menu/menu-types/horizontal-menu.css">
</head>
<body class="horizontal-layout horizontal-menu 2-columns menu-expanded" data-open="click" data-menu="horizontal-menu"
      data-col="2-columns">
<span id="hdata"
      data-df="<?php echo $this->config->item('dformat2'); ?>"
      data-curr="<?php echo currency($this->aauth->get_user()->loc); ?>"></span>
<!-- fixed-top-->
<nav
        class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-static-top navbar-dark bg-gradient-x-grey-blue navbar-border navbar-brand-center">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a
                            class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                class="ft-menu font-large-1"></i></a></li>
                    <!-- <li class="nav-item"><a class="navbar-brand" href="<?= base_url() ?>Dashboard/dashboard"><img  class="brand-logo" alt="logo" src="<?php echo base_url(); ?>userfiles/theme/logo-header.png">
                    </a></li> -->
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse"
                            data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <!-- <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                                href="#"><i class="ft-menu"></i></a></li> -->

                       <li class="nav-item"><a class="navbar-brand" href="<?= base_url() ?>Dashboard/dashboard"><img  class="brand-logo" alt="logo" src="<?php echo base_url(); ?>userfiles/theme/logo-header.png">
                                </a></li>
                        <li class="dropdown  nav-item"><a class="nav-link nav-link-label" href="#"
                                data-toggle="dropdown"><i class="ficon ft-map-pin white"></i></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-left">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2"><i
                                                class="ficon ft-map-pin white"></i><?php echo $this->lang->line('business_location') ?></span>
                                    </h6>
                                </li>

                                <li class="dropdown-menu-footer"><span
                                        class="dropdown-item text-muted text-center blue"> <?php $loc = location($this->aauth->get_user()->loc);
                                    echo $loc['cname']; ?></span>
                                </li>
                            </ul>
                        </li>
                        <?php    
                       // if ($this->aauth->premission(12)) { ?> 
                            <li class="dropdown nav-item">
                                <a href="<?= base_url() ?>pos_invoices/create" class="t_tooltip text-white nav-link nav-link-label" title="Access POS" ><i  class="icon-handbag" class="text-white"></i></a>
                            </li> 
                            
                        <?php // } ?>
                         <!-- erp2024 added 27-06-2024 starts -->
                         <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                    data-toggle="dropdown" title="Tools">
                                <i class="icon-note"></i></a>
                            <ul class="dropdown-menu">
                                <li data-menu="">
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>tools/notes"><i
                                            class="icon-note"></i><?php echo $this->lang->line('Notes'); ?></a>
                                </li>
                                <li data-menu="">
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>events"><i
                                            class="icon-calendar"></i><?php echo $this->lang->line('Calendar'); ?></a>
                                </li>

                            </ul>
                        </li>
                        <?php if ($this->aauth->get_user()->roleid == 5) { ?>
                        <li class="dropdown nav-item mega-dropdown"><a class="dropdown-toggle nav-link " href="#"
                                data-toggle="dropdown">
                                <i class="fa fa-gear fa-spin1" style="font-size:21px;"></i>
                                <?php //echo $this->lang->line('admin_settings') ?> </a>
                            <ul class="mega-dropdown-menu dropdown-menu row">
                                <li class="col-12">
                                    <div id="accordionWrap" role="tablist" aria-multiselectable="true">
                                        <div class="card border-0 box-shadow-0 collapse-icon accordion-icon-rotate">
                                            <div class="card-header p-0 pb-1 border-0 mt-1" id="heading1" role="tab">
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap" href="#accordion1"
                                                    aria-controls="accordion1"><i class="fa fa-leaf"></i>
                                                    <?php echo $this->lang->line('business_settings')  ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion1" role="tabpanel"
                                                aria-labelledby="heading1" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/company"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('company_settings') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>locations"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Business Locations') ?>
                                                            </a></li>
                                                        <li><select class="dropdown-item"
                                                                onchange="javascript:location.href = baseurl+'settings/switch_location?id='+this.value;"><?php
                                                        $loc = location($this->aauth->get_user()->loc);
                                                        echo ' <option value="' . $loc['id'] . '"> *' . $loc['cname'] . '*</option>';

                                                        $loc = locations();
                                                        foreach ($loc as $row) {
                                                            echo ' <option value="' . $row['id'] . '"> ' . $row['cname'] . '</option>';
                                                        }
                                                        echo ' <option value="0">Master/Default</option>';
                                                        ?></select></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>tools/setgoals"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Set Goals') ?>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-header p-0 pb-1 border-0 mt-1" id="heading2" role="tab">
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap" href="#accordion2"
                                                    aria-controls="accordion2"> <i
                                                        class="fa fa-calendar"></i><?php echo $this->lang->line('Localisation') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion2" role="tabpanel"
                                                aria-labelledby="heading2" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/currency"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Currency') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/language"><i
                                                                    class="ft-arrow-right"></i>Languages</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/dtformat"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Date & Time Format') ?>
                                                            </a></li>
                                                        <!-- <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/theme"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Theme') ?>
                                                            </a></li> -->
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="card-header p-0 border-0 mt-1" id="heading3" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading3" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap" href="#accordion3"
                                                    aria-controls="accordion3"> <i
                                                        class="fa fa-lightbulb-o"></i><?php echo $this->lang->line('miscellaneous_settings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion3" role="tabpanel"
                                                aria-labelledby="heading3" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <!-- <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>webupdate"><i
                                                                    class="ft-arrow-right"></i> Software
                                                                Update</a></li> -->
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/email"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Email Config') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>transactions/categories"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Transaction Categories') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/misc_automail"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('EmailAlert') ?>
                                                            </a></li>
                                                        <!-- <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/about"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('About') ?>
                                                            </a></li> -->
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </li>
                                <li class="col-12">

                                    <div id="accordionWrap1" role="tablist" aria-multiselectable="true">
                                        <div class="card border-0 box-shadow-0 collapse-icon accordion-icon-rotate">
                                            <div class="card-header p-0 pb-1 border-0" id="heading4" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading4" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap1" href="#accordion4"
                                                    aria-controls="accordion4"><i
                                                        class="fa fa-fire"></i><?php echo $this->lang->line('AdvancedSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion4" role="tabpanel"
                                                aria-labelledby="heading4" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>restapi"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('REST API') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>cronjob"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Automatic Corn Job') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/custom_fields"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('CustomFields') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/dual_entry"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('DualEntryAccounting') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/logdata"><i
                                                                    class="ft-arrow-right"></i> Application
                                                                Activity Log</a>
                                                        </li>
                                                        <!-- <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/debug"><i
                                                                    class="ft-arrow-right"></i> Debug Mode </a>
                                                        </li> -->
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-header p-0 pb-1 border-0 mt-1" id="heading2" role="tab">
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap1" href="#accordion5"
                                                    aria-controls="accordion5"> <i
                                                        class="fa fa-shopping-cart"></i><?php echo $this->lang->line('BillingSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion5" role="tabpanel"
                                                aria-labelledby="heading5" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/billing_settings"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('billing_settings') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/discship"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('DiscountShipping') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/prefix"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Prefix') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/billing_terms"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Billing Terms') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/automail"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Auto Email SMS') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/warehouse"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('DefaultWarehouse') ?>
                                                            </a></li>

                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/pos_style"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('POSStyle') ?>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="card-header p-0 border-0 mt-1" id="heading6" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading6" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap1" href="#accordion6"
                                                    aria-controls="accordion6"><i
                                                        class="fa fa-scissors"></i><?php echo $this->lang->line('TaxSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion6" role="tabpanel"
                                                aria-labelledby="heading6" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/tax"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Tax') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/taxslabs"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('OtherTaxSettings') ?>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </li>
                                <li class="col-12">

                                    <div id="accordionWrap2" role="tablist" aria-multiselectable="true">
                                        <div class="card border-0 box-shadow-0 collapse-icon accordion-icon-rotate">
                                            <div class="card-header p-0 pb-1 border-0" id="heading7" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading7" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap2" href="#accordion7"
                                                    aria-controls="accordion7"><i
                                                        class="fa fa-flask"></i><?php echo $this->lang->line('ProductsSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion7" role="tabpanel"
                                                aria-labelledby="heading7" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>units"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Measurement Unit') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>units/variations"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('ProductsVariations') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>units/variables"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('VariationsVariables') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                            href="<?php echo base_url(); ?>productpricing"><i
                                                                class="ft-arrow-right"></i>
                                                            <?php echo $this->lang->line('Pricing Percntage') ?>
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-header p-0 pb-1 border-0 mt-1" id="heading8" role="tab">
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap2" href="#accordion8"
                                                    aria-controls="accordion8"> <i
                                                        class="fa fa-money"></i><?php echo $this->lang->line('Payment Settings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion8" role="tabpanel"
                                                aria-labelledby="heading8" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>paymentgateways/settings"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Payment Settings') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>paymentgateways"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Payment Gateways') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>paymentgateways/currencies"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Payment Currencies') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>paymentgateways/exchange"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Currency Exchange') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>paymentgateways/bank_accounts"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Bank Accounts') ?>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="card-header p-0 border-0 mt-1" id="heading9" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading9" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap2" href="#accordion9"
                                                    aria-controls="accordion9"><i
                                                        class="fa fa-umbrella"></i><?php echo $this->lang->line('CRMHRMSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion9" role="tabpanel"
                                                aria-labelledby="heading9" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>employee/auto_attendance"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('SelfAttendance')  ?>
                                                            </a></li>

                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/registration"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('CRMSettings') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>plugins/recaptcha"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Security') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/tickets"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Support Tickets') ?>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </li>
                                <li class="col-12">

                                    <div id="accordionWrap3" role="tablist" aria-multiselectable="true">
                                        <div class="card border-0 box-shadow-0 collapse-icon accordion-icon-rotate">
                                            <div class="card-header p-0 pb-1 border-0" id="heading10" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading10" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap3" href="#accordion10"
                                                    aria-controls="accordion10"><i
                                                        class="fa fa-magic"></i><?php echo $this->lang->line('PluginsSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion10" role="tabpanel"
                                                aria-labelledby="heading10" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>plugins/recaptcha"><i
                                                                    class="ft-arrow-right"></i>reCaptcha Security</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>plugins/shortner"><i
                                                                    class="ft-arrow-right"></i> URL Shortener</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>plugins/twilio"><i
                                                                    class="ft-arrow-right"></i> SMS Configuration</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>paymentgateways/exchange"><i
                                                                    class="ft-arrow-right"></i>Currency Exchange
                                                                API</a></li>
                                                        <?php plugins_checker(); ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-header p-0 pb-1 border-0 mt-1" id="heading11" role="tab">
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap3" href="#accordion11"
                                                    aria-controls="accordion11"> <i
                                                        class="fa fa-eye"></i><?php echo $this->lang->line('TemplatesSettings') ?>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion11" role="tabpanel"
                                                aria-labelledby="heading8" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>templates/email"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Email') ?>
                                                            </a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>templates/sms"><i
                                                                    class="ft-arrow-right"></i> SMS</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/print_invoice"><i
                                                                    class="ft-arrow-right"></i>
                                                                <?php echo $this->lang->line('Print Invoice') ?>
                                                            </a></li>
                                                        <!-- <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>settings/theme"><i
                                                                    class="ft-arrow-right"></i><?php echo $this->lang->line('Theme') ?>
                                                            </a></li> -->
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="card-header p-0 border-0 mt-1" id="heading12" role="tab">
                                            <!-- <div class="card-header p-0 pb-1 border-0 mt-1" id="heading12" role="tab"> -->
                                                <a class=" text-uppercase black" data-toggle="collapse"
                                                    data-parent="#accordionWrap3" href="#accordion12"
                                                    aria-controls="accordion12"><i class="fa fa-print"></i>POS
                                                    Printers</a>
                                                </a>
                                            </div>
                                            <div class="card-collapse collapse mb-1 " id="accordion12" role="tabpanel"
                                                aria-labelledby="heading12" aria-expanded="true">
                                                <div class="card-content">
                                                    <ul>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>printer/add"><i
                                                                    class="ft-arrow-right"></i>Add Printer</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="<?php echo base_url(); ?>printer"><i
                                                                    class="ft-arrow-right"></i> List Printers</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </li>


                            </ul>
                        </li> <?php } ?>
                        <!-- erp2024 added 27-06-2024 ends -->

                        <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"
                                aria-haspopup="true" aria-expanded="false" id="search-input"><i
                                    class="ficon ft-search"></i></a>
                            <div class="search-input">
                                <input class="input" type="text"
                                    placeholder="<?php echo $this->lang->line('Search Customer') ?>"
                                    id="head-customerbox">
                            </div>
                            <div id="head-customerbox-result" class="dropdown-menu ml-5" aria-labelledby="search-input">
                            </div>
                        </li>
                       
                    </ul>

                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#"
                                data-toggle="dropdown"><i class="ficon ft-bell"></i><span
                                    class="badge badge-pill badge-default badge-danger badge-default badge-up"
                                    id="taskcount">0</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span
                                            class="grey darken-2"><?php echo $this->lang->line('Pending Tasks') ?></span><span
                                            class="notification-tag badge badge-default badge-danger float-right m-0"><?=$this->lang->line('New') ?></span>
                                    </h6>
                                </li>
                                <li class="scrollable-container media-list" id="tasklist"></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center"
                                        href="<?php echo base_url('manager/todo') ?>"><?php echo $this->lang->line('Manage tasks') ?></a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#"
                                data-toggle="dropdown"><i class="ficon ft-mail"></i><span
                                    class="badge badge-pill badge-default badge-info badge-default badge-up"><?php echo $this->aauth->count_unread_pms() ?></span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span
                                            class="grey darken-2"><?php echo $this->lang->line('Messages') ?></span><span
                                            class="notification-tag badge badge-default badge-warning float-right m-0"><?php echo $this->aauth->count_unread_pms() ?><?php echo $this->lang->line('new') ?></span>
                                    </h6>
                                </li>
                                <li class="scrollable-container media-list">
                                    <?php $list_pm = $this->aauth->list_pms(6, 0, $this->aauth->get_user()->id, false);

                                foreach ($list_pm as $row) {

                                    echo '<a href="' . base_url('messages/view?id=' . $row->pid) . '">
                      <div class="media">
                        <div class="media-left"><span class="avatar avatar-sm  rounded-circle"><img src="' . base_url('userfiles/employee/' . $row->picture) . '" alt="avatar"><i></i></span></div>
                        <div class="media-body">
                          <h6 class="media-heading">' . $row->name . '</h6>
                          <p class="notification-text font-small-3 text-muted">' . $row->{'title'} . '</p><small>
                            <time class="media-meta text-muted" datetime="' . $row->{'date_sent'} . '">' . $row->{'date_sent'} . '</time></small>
                        </div>
                      </div></a>';
                                } ?> </li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center"
                                        href="<?php echo base_url('messages') ?>"><?php echo $this->lang->line('Read all messages') ?></a>
                                </li>
                            </ul>
                        </li>
                        <?php if ($this->aauth->auto_attend()) { ?>
                        <li class="dropdown dropdown-d nav-item">


                            <?php if ($this->aauth->clock()) {

                                echo ' <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon spinner icon-clock"></i><span class="badge badge-pill badge-default badge-success badge-default badge-up">' . $this->lang->line('On') . '</span></a>';

                            } else {
                                echo ' <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon icon-clock"></i><span class="badge badge-pill badge-default badge-warning badge-default badge-up">' . $this->lang->line('Off') . '</span></a>';
                            }
                            ?>

                            <ul
                                class="dropdown-menu dropdown-menu-right border-primary border-lighten-3 text-xs-center">
                                <br><br>
                                <?php echo '<span class="p-1 text-bold-300">' . $this->lang->line('Attendance') . ':</span>';
                                if (!$this->aauth->clock()) {
                                    echo '<a href="' . base_url() . '/dashboard/clock_in" class="btn btn-outline-success  btn-outline-white btn-md ml-1 mr-1" ><span class="icon-toggle-on" aria-hidden="true"></span> ' . $this->lang->line('ClockIn') . ' <i
                                    class="ficon icon-clock spinner"></i></a>';
                                } else {
                                    echo '<a href="' . base_url() . '/dashboard/clock_out" class="btn btn-outline-danger  btn-outline-white btn-md ml-1 mr-1" ><span class="icon-toggle-off" aria-hidden="true"></span> ' . $this->lang->line('ClockOut'). ' </a>';
                                }
                                ?>

                                <br><br>
                            </ul>
                        </li>
                        <?php } ?>
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <span class="avatar avatar-online">
                                    <img src="<?php echo base_url('userfiles/employee/thumbnail/' . $this->aauth->get_user()->picture) ?>"
                                        alt="avatar"><i></i></span>
                                <!-- <span class="user-name"><?php //echo $this->lang->line('Account') ?></span> -->
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item"
                                    href="<?php echo base_url(); ?>user/profile"><i class="ft-user"></i>
                                    <?php echo $this->lang->line('Profile') ?></a>
                                <a href="<?php echo base_url(); ?>user/attendance" class="dropdown-item"><i
                                        class="fa fa-list-ol"></i><?php echo $this->lang->line('Attendance') ?></a>
                                <a href="<?php echo base_url(); ?>user/holidays" class="dropdown-item"><i
                                        class="fa fa-hotel"></i><?php echo $this->lang->line('Holidays') ?></a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo base_url('user/logout'); ?>"><i
                                        class="ft-power"></i> <?php echo $this->lang->line('Logout') ?></a>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </nav>

<!-- ////////////////////////////////////////////////////////////////////////////-->

<!-- Horizontal navigation-->
<div id="c_body"></div>
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">