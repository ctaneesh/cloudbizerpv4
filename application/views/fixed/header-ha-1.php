<link rel="stylesheet" type="text/css"
   href="<?= assets_url() ?>app-assets/<?= LTR ?>/core/menu/menu-types/horizontal-menu.css">
</head>
<body class="horizontal-layout horizontal-menu 2-columns menu-expanded" data-open="click" data-menu="horizontal-menu"
   data-col="2-columns">
   <span id="hdata" data-df="<?php echo $this->config->item('dformat2'); ?>"
      data-curr="<?php echo currency($this->aauth->get_user()->loc); ?>"></span>
   <!-- fixed-top-->
   <nav
      class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-static-top navbar-dark bg-gradient-x-grey-blue navbar-border navbar-brand-center" style="width: 100%;position: fixed;top: 0px;z-index: 99999;">
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
         <div class="navbar-container content" >
            <div class="collapse navbar-collapse" id="navbar-mobile">
               <ul class="nav navbar-nav mr-auto float-left">
                  <!-- erp2024 removed 27-06-2024 -->
                  <!-- <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                     href="#"><i class="ft-menu"></i></a></li> -->
                  <li class="nav-item"><a class="navbar-brand" href="<?= base_url() ?>Dashboard/dashboard"><img
                     class="brand-logo" alt="logo"
                     src="<?php echo base_url(); ?>userfiles/theme/logo-header.png">
                     </a>
                  </li>
                  <li class="dropdown  nav-item">
                     <a class="nav-link nav-link-label" href="#"
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
                  if ($this->aauth->premission(12)) { ?>
                        <li class="nav-item1  nav-link">
                           <a href="<?= base_url() ?>pos_invoices/create"
                              class="t_tooltip text-white nav-link nav-link-label" title="Access POS"><i
                              class="icon-handbag" class="text-white"></i></a>
                        </li>
                  <?php } ?>
                  <!-- erp2024 added 27-06-2024 starts -->
                  <li class="dropdown nav-item" data-menu="dropdown">
                     <a class="dropdown-toggle nav-link" href="#"
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
                  <li class="dropdown nav-item mega-dropdown">
                     <a class="dropdown-toggle nav-link " href="#"
                        data-toggle="dropdown">
                     <i class="fa fa-gear fa-spin1" style="font-size:21px;"></i>
                     <?php //echo $this->lang->line('admin_settings') ?> 
                     </a>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>locations"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('Business Locations') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/language"><i
                                             class="ft-arrow-right"></i>Languages</a></li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/dtformat"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Date & Time Format') ?>
                                             </a>
                                          </li>
                                          <!-- <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/theme"><i
                                                 class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Theme') ?>
                                             </a></li> -->
                                       </ul>
                                    </div>
                                 </div>


                                 <div class="card-header p-0 pb-1 border-0 mt-1" id="heading10" role="tab">
                                    <a class=" text-uppercase black" data-toggle="collapse"
                                       data-parent="#accordionWrap" href="#accordion10"
                                       aria-controls="accordion10"> <i
                                       class="fa fa-calendar"></i><?php echo $this->lang->line('Default Accounts') ?>
                                    </a>
                                 </div>
                                 <div class="card-collapse collapse mb-1 " id="accordion10" role="tabpanel"
                                    aria-labelledby="heading10" aria-expanded="true">
                                    <div class="card-content">
                                       <ul>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>defaultaccounts"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Double Entry') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>transactions/categories"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('Transaction Categories') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/misc_automail"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('EmailAlert') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>cronjob"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('Automatic Corn Job') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/custom_fields"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('CustomFields') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/dual_entry"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('DualEntryAccounting') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/discship"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('DiscountShipping') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/prefix"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('Prefix') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/billing_terms"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Billing Terms') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/automail"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Auto Email SMS') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/warehouse"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('DefaultWarehouse') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/pos_style"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('POSStyle') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/taxslabs"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('OtherTaxSettings') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>units/variations"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('ProductsVariations') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>units/variables"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('VariationsVariables') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>productpricing"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Pricing Percntage') ?>
                                             </a>
                                          </li>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>costingmethod"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Costing Method') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>paymentgateways"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Payment Gateways') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>paymentgateways/currencies"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Payment Currencies') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>paymentgateways/exchange"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Currency Exchange') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>paymentgateways/bank_accounts"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Bank Accounts') ?>
                                             </a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/registration"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('CRMSettings') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>plugins/recaptcha"><i
                                             class="ft-arrow-right"></i><?php echo $this->lang->line('Security') ?>
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/tickets"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Support Tickets') ?>
                                             </a>
                                          </li>
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
                                             API</a>
                                          </li>
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
                                             </a>
                                          </li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>templates/sms"><i
                                             class="ft-arrow-right"></i> SMS</a></li>
                                          <li><a class="dropdown-item"
                                             href="<?php echo base_url(); ?>settings/print_invoice"><i
                                             class="ft-arrow-right"></i>
                                             <?php echo $this->lang->line('Print Invoice') ?>
                                             </a>
                                          </li>
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
                                       aria-controls="accordion12"><i class="fa fa-print"></i>POS Printers
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
                  </li>
                  <?php } ?>
                  <!-- erp2024 added 27-06-2024 ends -->
                  <li class="nav-item nav-search">
                     <a class="nav-link nav-link-search" href="#"
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
                  <li class="dropdown dropdown-notification nav-item">
                     <a class="nav-link nav-link-label" href="#"
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
                  <li class="dropdown dropdown-notification nav-item">
                     <a class="nav-link nav-link-label" href="#"
                        data-toggle="dropdown"><i class="ficon ft-mail"></i><span
                        class="badge badge-pill badge-default badge-info badge-default badge-up"
                        id="<?php echo "unread".$this->aauth->get_user()->id; ?>"><?php echo $this->aauth->count_unread_pms() ?></span></a>
                     <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                           <h6 class="dropdown-header m-0"><span
                              class="grey darken-2"><?php echo $this->lang->line('Messages') ?></span><span
                              class="notification-tag badge badge-default badge-warning float-right m-0"
                              id="<?php echo "unreadcount".$this->aauth->get_user()->id; ?>"><?php echo $this->aauth->count_unread_pms() ?><?php echo $this->lang->line('new') ?></span>
                           </h6>
                        </li>
                        <li class="scrollable-container media-list"
                           id="<?php echo "medialist".$this->aauth->get_user()->id; ?>">
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
                              } ?> 
                        </li>
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
                        <span class="user-name"><?php echo ucfirst($this->session->userdata('orgname')); ?></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item"
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
   <div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-light navbar-without-dd-arrow navbar-shadow menu-border"  style="width: 100%;position: fixed;top: 60px !important;z-index: 99999;"
      role="navigation" data-menu="menu-wrapper">
      <!-- Horizontal menu content-->
      <div class="navbar-container main-menu-content" data-menu="menu-container">
         <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
         <li class="nav-item"><a class="nav-link" href="<?= base_url(); ?>dashboard/"><i
            class="icon-home"></i><span><?= $this->lang->line('Apps') ?></span></a></li>
         <li class="nav-item"><a class="nav-link" href="<?= base_url(); ?>Dashboard/dashboard"><i
            class="icon-speedometer"></i><span><?= $this->lang->line('Dashboard') ?></span></a></li>
         <?php
            // if ($this->aauth->premission(3)) {
                ?>
         <li class="dropdown nav-item menu_assign_class" data-menu="dropdown" data-access="CRM-95">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="fa fa-users"></i><span><?php echo $this->lang->line('CRM') ?></span></a>
            <ul class="dropdown-menu">
               <li class="dropdown dropdown-submenu menu_assign_class" data-menu="dropdown-submenu" data-access="Customers-98">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="ft-users"></i><?php echo $this->lang->line('Customers') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu="" class="menu_assign_class" data-access="New_Customer-110" id="New_Customer-110"><a class="dropdown-item"
                        href="<?php echo base_url(); ?>customers/create"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Customer') ?></a>
                     </li>

                     <li data-menu="" class="menu_assign_class" data-access="Manage_Customers-99"><a class="dropdown-item" href="<?php echo base_url(); ?>customers"
                        data-toggle="dropdown"><?= $this->lang->line('Manage Customers'); ?></a>
                     </li>

                     <li data-menu="" class="menu_assign_class"  data-access="Customer_Groups-136"><a class="dropdown-item" href="<?php echo base_url(); ?>clientgroup"
                        data-toggle="dropdown"><?= $this->lang->line('Customer Groups'); ?></a>
                     </li>
                     
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu menu_assign_class" data-menu="dropdown-submenu" data-access="Support_Ticket-114">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="fa fa-ticket"></i><?php echo $this->lang->line('Support Tickets') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu="" class="menu_assign_class" data-access="Unsolved-115"><a class="dropdown-item"
                        href="<?php echo base_url(); ?>tickets/?filter=unsolved"
                        data-toggle="dropdown"><?php echo $this->lang->line('UnSolved') ?></a>
                     </li>
                     <li data-menu="" data-access="Manage_Tickets-330" class="menu_assign_class"><a class="dropdown-item" href="<?php echo base_url(); ?>tickets"
                        data-toggle="dropdown"><?= $this->lang->line('Manage Tickets'); ?></a>
                     </li>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu menu_assign_class" data-menu="dropdown-submenu" data-access="Leads-97">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="fa fa-usd"></i><?php echo $this->lang->line('Leads') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu="" class="menu_assign_class" data-access="New_Lead-96"><a class="dropdown-item"
                        href="<?php echo base_url(); ?>invoices/newcustomerenquiry"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Lead') ?></a>
                     </li>
                     <li data-menu="" class="menu_assign_class" data-access="Manage_Lead-126"><a class="dropdown-item"
                        href="<?php echo base_url(); ?>invoices/leads"
                        data-toggle="dropdown"><?= $this->lang->line('Manage Leads'); ?></a>
                     </li>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu menu_assign_class" data-menu="dropdown-submenu" data-access="Suppliers-112"> 
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="ft-target"></i><?php echo $this->lang->line('Suppliers') ?></a>
                  <ul class="dropdown-menu">
                     <li class="menu_assign_class" data-menu="" data-access="New_Supplier-701"><a class="dropdown-item" href="<?= base_url(); ?>supplier/create"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Supplier'); ?></a>
                     </li>
                     <li class="menu_assign_class" data-menu="" data-access="Manage_Suppliers-127"><a class="dropdown-item" href="<?php echo base_url(); ?>supplier"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Suppliers'); ?></a>
                  </ul>
               </li>
            </ul>
         </li>
         <?php 
         // }
            if ($this->aauth->premission(1)) { ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="icon-basket-loaded"></i><span><?php echo $this->lang->line('sales') ?></span></a>
            <ul class="dropdown-menu">
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-call-out"></i><?php echo $this->lang->line('Quotes') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>quote/create"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Quote'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>quote"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Quotes'); ?></a>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-basket"></i><?php echo $this->lang->line('sales') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>SalesOrders"
                        data-toggle="dropdown"><?php echo $this->lang->line('Sales Orders'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>DeliveryNotes"
                        data-toggle="dropdown"><?php echo $this->lang->line('Delivery Notes'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>Deliveryreturn"
                        data-toggle="dropdown"><?php echo $this->lang->line('Delivery Returns'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>invoices/create"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Invoice'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>invoices"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Invoices'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>invoicecreditnotes"
                        data-toggle="dropdown"><?php echo $this->lang->line('Invoice Credit Notes'); ?></a>
                     </li>
                     <li data-menu="">
                        <a class="dropdown-item"
                           href="<?php echo base_url(); ?>stockreturn/creditnotes"><?php echo $this->lang->line('Credit Notes'); ?>
                        </a>
                     </li>
                     <?php    if ($this->aauth->premission(12)) { ?>
                     <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                        <a
                           class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                           class="icon-paper-plane"></i><?php echo $this->lang->line('pos sales') ?></a>
                        <ul class="dropdown-menu">
                           <li data-menu=""><a class="dropdown-item"
                              href="<?= base_url(); ?>pos_invoices/create"
                              data-toggle="dropdown"><?php echo $this->lang->line('New Invoice'); ?></a>
                           </li>
                           <li data-menu=""><a class="dropdown-item"
                              href="<?php echo base_url(); ?>pos_invoices/create?v2=true"
                              data-toggle="dropdown"><?= $this->lang->line('New Invoice'); ?>
                              V2 - Mobile</a>
                           </li>
                           <li data-menu=""><a class="dropdown-item"
                              href="<?php echo base_url(); ?>pos_invoices"
                              data-toggle="dropdown"><?php echo $this->lang->line('Manage Invoices'); ?></a>
                           </li>
                        </ul>
                     </li>
                     <?php } ?>
                  </ul>
               </li>
               <!-- <li data-menu="">
                  <a class="dropdown-item" href="<?php echo base_url(); ?>enquiry/"><i
                          class="icon-screen-tablet"></i><?php echo $this->lang->line('Customer Enquiry'); ?>
                  </a>
                  </li> -->
               </li>
           
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('Authorization Requests') ?></a>
                  <ul class="dropdown-menu">                  
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>authorization_approval"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Authorization Requests') ?></a>
                     </li>                     
                  </ul>
               </li>

               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="ft-sliders"></i> <?php echo $this->lang->line('Buy Requests') ?></a>
                  <ul class="dropdown-menu">                  
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>Productrequest/add"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Buy Request') ?></a>
                     </li>                     
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>Productrequest"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Buy Requests') ?></a>
                     </li>                     
                  </ul>
               </li>

               <!-- <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a
                  class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                      class="ft-radio"></i><?php echo $this->lang->line('Subscriptions') ?></a>
                  <ul class="dropdown-menu">
                  <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>subscriptions/create"
                                  data-toggle="dropdown"><?php echo $this->lang->line('New Subscription'); ?></a>
                  </li>
                  
                  <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>subscriptions"
                                  data-toggle="dropdown"><?php echo $this->lang->line('Subscriptions'); ?></a>
                  </ul>
                  </li> -->
                  
               
                  <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                     <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-wallet"></i><?php echo $this->lang->line('Reports') ?></a>
                           <ul class="dropdown-menu">
                              <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>reports/sales_orders_report"  data-toggle="dropdown"><?php echo $this->lang->line('Sales Orders Report'); ?></a></li> 
                              <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>Sales/saleviewstatement"  data-toggle="dropdown"><?php echo $this->lang->line('Sales - Purchase'); ?></a></li> 
                              <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>Sales/CustomerSalesReport"  data-toggle="dropdown"><?php echo $this->lang->line('Customer Sales'); ?></a></li> 
                              <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>reports/purchase_orders_report"  data-toggle="dropdown"><?php echo $this->lang->line('Open Purchase Orders'); ?></a></li> 
                              <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>reports_quotes"  data-toggle="dropdown"><?php echo $this->lang->line('Quote Report'); ?></a></li> 
                               <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>reports/sale_purchase_report"  data-toggle="dropdown"><?php echo $this->lang->line('Sale Purchase Report'); ?></a></li>
                                <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>reports/inventory_aging_report"  data-toggle="dropdown"><?php echo $this->lang->line('Inventory Aging Report'); ?></a></li>
                           </ul>
                  </li>
            </ul>
         </li>
         <?php }
            if ($this->aauth->premission(2)) { ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="ft-layers"></i><span><?php echo $this->lang->line('Stock') ?></span></a>
            <ul class="dropdown-menu">
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="ft-list"></i>
                  <?php echo $this->lang->line('Products') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu="">
                        <a class="dropdown-item" href="<?= base_url(); ?>products/add" data-toggle="dropdown">
                        <?php echo $this->lang->line('New Product'); ?></a>
                     </li>
                     <li data-menu="">
                        <a class="dropdown-item" href="<?php echo base_url(); ?>products"
                           data-toggle="dropdown"><?= $this->lang->line('Manage Products'); ?></a>
                     </li>
                     <!-- erp2024 commented section 27-06-2024 -->
                     <!-- <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>productpricing"
                        data-toggle="dropdown"> <?php echo $this->lang->line('Pricing Percntage'); ?></a>
                        </li> -->
                     <!-- <li data-menu=""><a class="dropdown-item"
                        href="<?= base_url(); ?>Products/productsbylocation" data-toggle="dropdown">
                        <?php echo $this->lang->line('Product By Location'); ?></a>
                        </li>
                        
                        <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>productpricelist"
                        data-toggle="dropdown"> <?php echo $this->lang->line('Product With Stock'); ?></a>
                        </li> -->
                     <!-- erp2024 commented section 27-06-2024 -->
                     <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                        <a
                           class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                           class="fa fa-barcode"></i><?php echo $this->lang->line('ProductsLabel'); ?></a>
                        <ul class="dropdown-menu">
                           <li data-menu="">
                              <a class="dropdown-item" href="<?php echo base_url(); ?>products/custom_label"
                                 data-toggle="dropdown"><?php echo $this->lang->line('custom_label'); ?></a>
                           </li>
                           <li data-menu="">
                              <a class="dropdown-item" href="<?php echo base_url(); ?>products/standard_label"
                                 data-toggle="dropdown"><?php echo $this->lang->line('standard_label'); ?></a>
                           </li>
                        </ul>
                     </li>
                     <li data-menu="">
                        <a class="dropdown-item" href="<?php echo base_url(); ?>productcategory"
                           data-toggle="dropdown"><?php echo $this->lang->line('Product Categories'); ?>
                        </a>
                     </li>
                     <li data-menu="">
                        <a class="dropdown-item" href="<?php echo base_url(); ?>brand"
                           data-toggle="dropdown"><?php echo $this->lang->line('Brands'); ?>
                        </a>
                     </li>
                  </ul>
               </li>
               <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>productcategory/warehouse"
                  data-toggle="dropdown"><i
                  class="ft-sliders"></i><?php echo $this->lang->line('Warehouses'); ?></a>
               </li>
               <!-- <li data-menu=""><a class="dropdown-item"
                  href="<?php echo base_url(); ?>products/stock_transfer"
                  data-toggle="dropdown"><i
                  class="ft-wind"></i><?php echo $this->lang->line('Stock Transfer'); ?></a>
                  </li> -->
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="fa fa-exchange"></i> <?php echo $this->lang->line('Stock Transfer') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu="">
                        <a class="dropdown-item" href="<?= base_url(); ?>materialrequest/"
                           data-toggle="dropdown"> <?php echo $this->lang->line('Material Request'); ?></a>
                     </li>
                     <li data-menu="">
                        <a class="dropdown-item" href="<?= base_url(); ?>products/stock_transfer"
                           data-toggle="dropdown"> <?php echo $this->lang->line('New Stock Transfer'); ?></a>
                     </li>
                     <li data-menu="">
                        <a class="dropdown-item" href="<?php echo base_url(); ?>stocktransfer/"
                           data-toggle="dropdown"><?= $this->lang->line('Stock Transfer List'); ?></a>
                     </li>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-handbag"></i> <?php echo $this->lang->line('Purchase Order') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>purchase/create"
                        data-toggle="dropdown"> <?php echo $this->lang->line('New Order'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>purchase"
                        data-toggle="dropdown"><?= $this->lang->line('Manage Orders'); ?></a>
                     </li>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-puzzle"></i> <?php echo $this->lang->line('Stock Return') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?= base_url(); ?>stockreturn"
                        data-toggle="dropdown"> <?php echo $this->lang->line('SuppliersRecords'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>stockreturn/customer"
                        data-toggle="dropdown"><?php echo $this->lang->line('CustomersRecords'); ?></a>
                     </li>
                  </ul>
               </li>
               <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>stockreturn"
                  data-toggle="dropdown"><i
                  class="ft-book"></i><?php echo $this->lang->line('Purchase Returns'); ?></a>
               </li>
               <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>Invoices/stockreciepts"
                  data-toggle="dropdown"><i
                  class="ft-book"></i><?php echo $this->lang->line('Purchase Reciepts'); ?></a>
               </li>
              
               
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-wallet"></i><?php echo $this->lang->line('Reports') ?></a>
                        <ul class="dropdown-menu">
                           <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>reports/stock_report"  data-toggle="dropdown"><?= "Stock Report"; ?></a></li>
                        </ul>
               </li>
            </ul>
         </li>
         <?php }
            if (!$this->aauth->premission(4) && $this->aauth->premission(7)) {
                ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="icon-briefcase"></i><span><?php echo $this->lang->line('Project') ?></span></a>
            <ul class="dropdown-menu">
               <li data-menu="">
                  <a class="dropdown-item" href="<?php echo base_url(); ?>manager/projects"><i
                     class="icon-calendar"></i><?php echo $this->lang->line('Manage Projects'); ?>
                  </a>
               </li>
               <!-- <li data-menu="">
                  <a class="dropdown-item" href="<?php echo base_url(); ?>manager/todo"><i
                     class="icon-list"></i><?php echo $this->lang->line('To Do List'); ?></a>
               </li> -->
               
            </ul>
         </li>
         
         
         <?php }
            if ($this->aauth->premission(5)) {
                ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="icon-calculator"></i><span><?= $this->lang->line('Accounts') ?></span></a>
            <ul class="dropdown-menu">
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-book-open"></i><?php echo $this->lang->line('Accounts') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>accounts/add"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Accounts') ?></a>
                     </li>
                     <!-- <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>accounts"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Accounts') ?></a>
                     </li> -->
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>accounts/balancesheet"
                        data-toggle="dropdown"><?= $this->lang->line('BalanceSheet'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/accountstatement"
                        data-toggle="dropdown"><?= $this->lang->line('Account Statements'); ?></a>
                     </li>
                  </ul>
               </li>

               
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-wallet"></i><?php echo $this->lang->line('Chart of Accounts') ?></a>
                        <ul class="dropdown-menu">
                           <li data-menu="">
                              <a class="dropdown-item" href="<?php echo base_url(); ?>coaaccounttypes" data-toggle="dropdown"><?php echo $this->lang->line('Account Types') ?></a>
                              
                           </li>
                           <!-- <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>coaaccounttypes/coa_account_lists"  data-toggle="dropdown"><?php echo $this->lang->line('Manage Accounts') ?></a>
                           </li>  -->
                           <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>accounts/add"  data-toggle="dropdown"><?php echo $this->lang->line('Accounts') ?></a>
                           </li> 
                           <!-- <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>coaaccounttypes/create_coa_account"  data-toggle="dropdown"><?php echo $this->lang->line('Create Account') ?></a>
                           </li>  -->
                           
                        </ul>
                     </li>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-book"></i> <?php echo $this->lang->line('Manual Journals') ?></a>
                  <ul class="dropdown-menu">
                  
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>manualjournals/create"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Manual Journal') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>manualjournals"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Manual Journals') ?></a>
                     </li>
                     
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-bank"></i><?php echo $this->lang->line('Banking') ?></a>
                        <ul class="dropdown-menu">
                           <li data-menu="">
                              <a class="dropdown-item" href="<?php echo base_url(); ?>paymentgateways/bank_accounts" data-toggle="dropdown"><?php echo $this->lang->line('Bank Accounts') ?></a>                              
                           </li>
                           <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>bankingcategory"  data-toggle="dropdown"><?php echo $this->lang->line('Banking Category') ?></a>
                           </li> 
                           <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>bankingtransactions"  data-toggle="dropdown"><?php echo $this->lang->line('Transactions') ?></a>
                           </li> 
                           <li data-menu=""><a  class="dropdown-item" href="<?php echo base_url(); ?>reconciliations"  data-toggle="dropdown"><?php echo $this->lang->line('Reconciliations') ?></a>
                           </li> 
                           
                        </ul>
                     </li>
               </li>


               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-wallet"></i><?php echo $this->lang->line('Transactions') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>transactions"
                        data-toggle="dropdown"><?php echo $this->lang->line('View Transactions') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>transactions/add"
                        data-toggle="dropdown"><?= $this->lang->line('New Transaction'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>transactions/transfer"
                        data-toggle="dropdown"><?= $this->lang->line('New Transfer'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>transactions/income"
                        data-toggle="dropdown"><?= $this->lang->line('Income'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>transactions/expense"
                        data-toggle="dropdown"><?= $this->lang->line('Expense'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>customers"
                        data-toggle="dropdown"><?= $this->lang->line('Clients Transactions'); ?></a>
                     </li>
                    
                  </ul>
               </li>

               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-money"></i> <?php echo $this->lang->line('Income') ?></a>
                  <ul class="dropdown-menu">
                  
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>transactions/add"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Income') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>transactions/income"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Income') ?></a>
                     </li>
                     
                  </ul>
               </li>

               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i class="ft-external-link"></i> <?php echo $this->lang->line('Expense') ?></a>
                  <ul class="dropdown-menu">
                  
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>transactions/add"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Expense') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>transactions/expense"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage Expense') ?></a>
                     </li>
                     
                  </ul>
               </li>


               <!-- <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a
                  class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                      class="icon-wallet"></i><?php echo $this->lang->line('Promo Codes') ?></a>
                          <ul class="dropdown-menu">
                              <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>promo/create"
                                                  data-toggle="dropdown"><?php echo $this->lang->line('New Promo') ?></a>
                              </li>
                              <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>promo"
                                                  data-toggle="dropdown"><?= $this->lang->line('Manage Promo'); ?></a>
                              </li>
                          </ul>
                      </li>
                  </li> -->
                  <!-- <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                      class="icon-wallet"></i><?php echo $this->lang->line('Reports') ?></a>
                          <ul class="dropdown-menu">
                              <li data-menu="">
                                 <a class="dropdown-item" target="_blank" href="<?php echo base_url(); ?>reports/ar_aging_report" data-toggle="dropdown"><?php echo $this->lang->line('Aged Receivables') ?></a>
                              </li>
                             <li data-menu=""><a target="_blank" class="dropdown-item" href="<?php echo base_url(); ?>reports/pay_to_supplier_aged_report"  data-toggle="dropdown"><?= $this->lang->line('Aged Payables'); ?></a>
                              </li> 
                             
                          </ul>
                      </li>
                  </li> -->
                  
            </ul>
         </li>

         
         <?php }
            if ($this->aauth->premission(6)) {
                ?>
            <!-- erp2024 commented 27-06-2024 starts -->
            <!-- <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
            data-toggle="dropdown"><i
                class="icon-note"></i><span><?php echo $this->lang->line('Tools') ?></span></a>
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
            </li> -->
         <!-- erp2024 commented 27-06-2024 ends -->
         <?php }
            if ($this->aauth->premission(4)) {
                ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="icon-briefcase"></i><span><?= $this->lang->line('Project') ?></span></a>
            <ul class="dropdown-menu">
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-calendar"></i><?php echo $this->lang->line('Project Management') ?>
                  </a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>projects/addproject"
                        data-toggle="dropdown"><?php echo $this->lang->line('New Project') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>projects"
                        data-toggle="dropdown"><?= $this->lang->line('Manage Projects'); ?></a>
                     </li>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-list"></i><?php echo $this->lang->line('To Do Lists') ?></a>
                  <ul class="dropdown-menu">
                  
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>tools/addtask"
                        data-toggle="dropdown"><?php echo $this->lang->line('New To Do List') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>tools/todo"
                        data-toggle="dropdown"><?php echo $this->lang->line('Manage To Do Lists') ?></a>
                     </li>
                     
                  </ul>
               </li>
            </ul>
         </li>
         <?php } 
            ?>
         <li class="nav-item">
            <a class="nav-link" href="https://online.cloudbizerp.com/" target="_blank"><i
               class="icon-basket"></i><span><?php echo $this->lang->line('Online Store') ?></span></a>
         </li>
         <?php
            if ($this->aauth->premission(9)) {
                ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="fa fa-user-plus"></i><span><?php echo $this->lang->line('HRM') ?></span></a>
               <ul class="dropdown-menu">
                  <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                     <a
                        class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                        class="ft-users"></i><?php echo $this->lang->line('Employees') ?></a>
                     <ul class="dropdown-menu">
                     
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee"
                           data-toggle="dropdown"><?php echo $this->lang->line('Employee') ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>expenseclaims"
                           data-toggle="dropdown"><?php echo $this->lang->line('Expense Claims') ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item"
                           href="<?php echo base_url(); ?>employee/permissions"
                           data-toggle="dropdown"><?= $this->lang->line('Permissions'); ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee/salaries"
                           data-toggle="dropdown"><?= $this->lang->line('Salaries'); ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item"
                           href="<?php echo base_url(); ?>employee/attendances"
                           data-toggle="dropdown"><?= $this->lang->line('Attendance'); ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee/holidays"
                           data-toggle="dropdown"><?= $this->lang->line('Holidays'); ?></a>
                        </li>
                     </ul>
                  </li>


                  <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                     <a
                        class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                        class="ft-users"></i><?php echo $this->lang->line('Departments') ?></a>
                     <ul class="dropdown-menu">
                     
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee/adddep"
                           data-toggle="dropdown"><?php echo $this->lang->line('New Department') ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee/departments"
                           data-toggle="dropdown"><?php echo $this->lang->line('Manage Departments') ?></a>
                        </li>
                        
                     </ul>
                  </li>

                  <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                     <a
                        class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                        class="ft-users"></i><?php echo $this->lang->line('Payrolls') ?></a>
                     <ul class="dropdown-menu">
                     
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee/payroll_create"
                           data-toggle="dropdown"><?php echo $this->lang->line('New Payroll') ?></a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>employee/payroll"
                           data-toggle="dropdown"><?php echo $this->lang->line('Manage Payrolls') ?></a>
                        </li>
                        
                     </ul>
                  </li>

               <!-- <li data-menu="">
                  <a class="dropdown-item" href="<?php echo base_url(); ?>tools/documents"><i
                              class="icon-doc"></i><?php echo $this->lang->line('Documents'); ?></a>
                  </li> -->
            </ul>
         </li>
         <?php }
            if ($this->aauth->premission(10)) {
               ?>
         <li class="dropdown nav-item" data-menu="dropdown">
            <a class="dropdown-toggle nav-link" href="#"
               data-toggle="dropdown"><i
               class="icon-pie-chart"></i><span><?php echo $this->lang->line('Data & Reports') ?></span></a>
            <ul class="dropdown-menu">
               <li data-menu="">
                  <a class="dropdown-item" href="<?php echo base_url(); ?>register"><i
                     class="icon-eyeglasses"></i><?php echo $this->lang->line('Business Registers'); ?>
                  </a>
               </li>

               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-doc"></i><?php echo $this->lang->line('Accounting') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/ar_aging_report"
                        data-toggle="dropdown"><?= $this->lang->line('Aged Receivables'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/pay_to_supplier_aged_report"
                        data-toggle="dropdown"><?= $this->lang->line('Aged Payables'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/balance_sheet_report"
                        data-toggle="dropdown"><?= $this->lang->line('Balance Sheet'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/general_ledger"
                        data-toggle="dropdown"><?php echo $this->lang->line('General Ledger')  ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/journal_entries"
                        data-toggle="dropdown"><?php echo $this->lang->line('Journal Entries')  ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/profit_and_loss"
                        data-toggle="dropdown"><?php echo $this->lang->line('Profit & Loss')  ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/trial_balance"
                        data-toggle="dropdown"><?php echo $this->lang->line('Trial Balance')  ?></a>
                     </li>
                     <!-- <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/supplierstatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('Supplier_Account_Statements') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/taxstatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('TAX_Statements'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>pos_invoices/extended"
                        data-toggle="dropdown"><?php echo $this->lang->line('ProductSales'); ?></a></li> -->
                  </ul>
               </li>

               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-doc"></i><?php echo $this->lang->line('Statements') ?></a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/accountstatement"
                        data-toggle="dropdown"><?= $this->lang->line('Account Statements'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/customerstatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('Customer_Account_Statements')  ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/supplierstatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('Supplier_Account_Statements') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/taxstatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('TAX_Statements'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>pos_invoices/extended"
                        data-toggle="dropdown"><?php echo $this->lang->line('ProductSales'); ?></a></li>
                  </ul>
               </li>


               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-bar-chart"></i><?php echo $this->lang->line('Graphical Reports') ?>
                  </a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>chart/product_cat"
                        data-toggle="dropdown"><?= $this->lang->line('Product Categories'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>chart/trending_products"
                        data-toggle="dropdown"><?= $this->lang->line('Trending Products'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>chart/profit"
                        data-toggle="dropdown"><?= $this->lang->line('Profit'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>chart/topcustomers"
                        data-toggle="dropdown"><?php echo $this->lang->line('Top_Customers') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>chart/incvsexp"
                        data-toggle="dropdown"><?php echo $this->lang->line('income_vs_expenses') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>chart/income"
                        data-toggle="dropdown"><?= $this->lang->line('Income'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>chart/expenses"
                        data-toggle="dropdown"><?= $this->lang->line('Expenses'); ?></a>
                  </ul>
               </li>
               <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                  <a
                     class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                     class="icon-bulb"></i><?php echo $this->lang->line('Summary_Report') ?>
                  </a>
                  <ul class="dropdown-menu">
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>reports/statistics"
                        data-toggle="dropdown"><?php echo $this->lang->line('Statistics') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/profitstatement"
                        data-toggle="dropdown"><?= $this->lang->line('Profit'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/incomestatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('Calculate Income'); ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item"
                        href="<?php echo base_url(); ?>reports/expensestatement"
                        data-toggle="dropdown"><?php echo $this->lang->line('Calculate Expenses') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>reports/sales"
                        data-toggle="dropdown"><?php echo $this->lang->line('Sales') ?></a>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>reports/products"
                        data-toggle="dropdown"><?php echo $this->lang->line('Products') ?></a>
                     </li>
                     </li>
                     <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>reports/commission"
                        data-toggle="dropdown"><?= $this->lang->line('Employee_Commission'); ?></a>
                     </li>
                  </ul>
         </li>

       
               <?php  if ($this->aauth->get_user()->roleid > 4) { ?>
               <!-- <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a
                  class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                      class="icon-bulb"></i><?php echo $this->lang->line('Export_Import') ?>
                  </a>
                  <ul class="dropdown-menu">
                  <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>export/crm"><?php echo $this->lang->line('Export People Data'); ?>
                          </a>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>export/transactions"><?php echo $this->lang->line('Export Transactions'); ?></a>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>export/products"><?php echo $this->lang->line('Export Products'); ?></a>
                  </li>
                  <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>export/account"><?php echo $this->lang->line('Account Statements'); ?></a>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>export/taxstatement"><?php echo $this->lang->line('Tax_Export'); ?></a>
                  </li>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>export/dbexport"><?php echo $this->lang->line('Database Backup'); ?></a>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>import/products"><?php echo $this->lang->line('Import Products'); ?></a>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>import/customers"><?php echo $this->lang->line('Import Customers'); ?></a>
                  </li>
                  <li data-menu=""><a class="dropdown-item"
                                  href="<?php echo base_url(); ?>export/people_products"> <?php echo $this->lang->line('ProductsAccount Statements'); ?></a>
                  </li>
                  
                  </ul>
                  </li> -->
               <!-- </ul>
                  </li> -->
                 
               <?php } ?>
               </ul>
                  <?php  }
                  ?>


                  <li class="dropdown nav-item" data-menu="dropdown">
                     <a class="dropdown-toggle nav-link" href="#"
                        data-toggle="dropdown"><i
                        class="fa fa-user-plus"></i><span><?php echo $this->lang->line('User Permissions') ?></span></a>
                     <ul class="dropdown-menu">
                        <!-- <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                           <a
                              class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown"><i
                              class="ft-users"></i><?php echo $this->lang->line('Menus') ?></a>
                           <ul class="dropdown-menu">
                           
                              <li data-menu=""><a class="dropdown-item" href="<?php echo base_url(); ?>menus"
                                 data-toggle="dropdown"><?php echo $this->lang->line('Manage Menus') ?></a>
                              </li>
                           </ul>
                        </li>
                        
                        <li data-menu="">
                           <a class="dropdown-item" href="<?php echo base_url(); ?>menus/menu_link_for_user_roles"><i class="icon-doc"></i><?php echo $this->lang->line('User Role - Menu Link'); ?></a>
                        </li> -->
                        <li data-menu="">
                           <a class="dropdown-item" href="<?php echo base_url(); ?>menus"><i class="icon-doc"></i><?php echo $this->lang->line('Manage Menus'); ?></a>
                        </li>
                        <li data-menu="">
                           <a class="dropdown-item" href="<?php echo base_url(); ?>roles"><i class="icon-doc"></i><?php echo $this->lang->line('Roles'); ?></a>
                        </li>
                        <li data-menu="">
                           <a class="dropdown-item" href="<?php echo base_url(); ?>roles/set_permissions_for_the_user"><i class="icon-doc"></i><?php echo $this->lang->line('User Menu Mapping'); ?></a>
                        </li>
                     </ul>
                  </li>  


            </ul>
      </div>
      <!-- /horizontal menu content-->
   </div>
   <!-- Horizontal navigation-->
   <div id="c_body"></div>
   <div class="app-content content">
   <div class="content-wrapper">
   <div class="content-header row">
   </div>
   <div class="content-body">
   <a class="about-us" href="https://treecodeit.com/" target="_blank"><?= $this->lang->line('About us') ?></a>
   <!-- <a class="about-us" href="<?= base_url(); ?>quote/about"><?= $this->lang->line('About us') ?></a> -->