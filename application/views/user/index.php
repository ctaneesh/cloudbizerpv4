<body class="horizontal-layout horizontal-menu 1-column  bg-full-screen-image menu-expanded blank-page blank-page"
   data-open="hover" data-menu="horizontal-menu" data-col="1-column">
   <!-- ////////////////////////////////////////////////////////////////////////////-->
   <!-- <div class="app-content content">
      <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body">
              <section class="flexbox-container">
                  <div class="col-12 d-flex align-items-center justify-content-center">
                      <div class="col-md-4 col-sm-10 box-shadow-2 p-1">
                          <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                              <div class="card-header border-0">
                                  <div class="card-title text-center">
                                      <img class=" mt-1" src="<?php echo base_url('userfiles/company/') . $this->config->item('logo'); ?>"
                                           alt="logo" style="max-height: 10rem;  max-width: 10rem;">
                                  </div>
                                      <span><?php echo $this->lang->line('employee_login_panel') ?></span></h6>
                              </div>
                              <div class="card-content">
      
      
                                  <div class="card-body">
                                      <?php
                                       $attributes = array('class' => 'form-horizontal form-simple', 'id' => 'login_form');
                                       echo form_open('user/checklogin', $attributes);
                                       ?>
                                      <fieldset class="form-group position-relative has-icon-left">
                                          <input type="text" class="form-control" id="user-name" name="username"
                                                 placeholder="<?php echo $this->lang->line('Your Email') ?>" required>
                                          <div class="form-control-position">
                                              <i class="ft-user"></i>
                                          </div>
                                      </fieldset>
                                      <fieldset class="form-group position-relative has-icon-left">
                                          <input type="password" class="form-control" id="user-password" name="password"
                                                 placeholder="<?php echo $this->lang->line('Your Password') ?>" required>
                                          <div class="form-control-position">
                                              <i class="fa fa-key"></i>
                                          </div>
                                      </fieldset>
                                      <?php if ($response) {
                                       echo '<div id="notify" class="alert alert-danger" >
                                             <a href="#" class="close" data-dismiss="alert">&times;</a> <div class="message">' . $response . '</div>
                                          </div>';
                                       } ?>
                                    
                                       <?php //if ($this->aauth->get_login_attempts() > 1 && $captcha_on) {
                                       echo '<script src="https://www.google.com/recaptcha/api.js"></script>
                                       <fieldset class="form-group position-relative has-icon-left">
                                       <div class="g-recaptcha" data-sitekey="' . $captcha . '"></div>
                                       </fieldset>';
                                      // } ?>
                                      <div class="form-group row">
                                          <div class="col-md-6 col-12 text-center text-sm-left">
                                              <fieldset>
                                                  <input type="checkbox" id="remember-me" class="chk-remember"
                                                         name="remember_me">
                                                  <label for="remember-me">  <?php echo $this->lang->line('remember_me') ?></label>
                                              </fieldset>
                                          </div>
                                          <div class="col-md-6 col-12 float-sm-left text-center text-sm-right"><a
                                                      href="<?php echo base_url('user/forgot'); ?>"
                                                      class="card-link"><?php echo $this->lang->line('forgot_password') ?>
                                                  ?</a></div>
                                      </div>
                                      <div class=" text-center">
                                      <button type="submit" class="btn btn-outline-primary"><i
                                                  class="ft-unlock"></i> <?php echo $this->lang->line('login') ?></button>
                                      </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>
      
          </div>
      </div>
      </div> -->
   <!-- ////////////////////////////////////////////////////////////////////////////-->
   <style>
      .bg-login {
      background-color: #eee;
      }
      .left-section {
      padding: 50px;
      }
      .card.rounded-3.text-black {
         padding-top: 0px;
      }
      .gradient-custom-2 {
         background: #fccb90;
         background: -webkit-linear-gradient(to right, #00686a, #6F85AD);
         background: linear-gradient(to right, #00686a, #6F85AD);
      }
      @media (max-width: 767px) {
         .gradient-custom-2 {
            margin-left:15px;
            margin-right:15px;
         }   
      }
      .left-ul-section {
      margin-left: 30px !important;
      padding-left: 0px;
      }
      .left-section li {
      list-style-type: circle !important;
      }
      .btn-width {
      width: 150px !important;
      }
      .footer-section {
            /* position: fixed; */
         bottom: 5px;
         /* width: 100%; */
         text-align: center;
         /* color: #000; */
         padding: 20px 60px; 

      }
      .footer-section p {
         font-size: 16px;
         text-align: center;
         color: #ffffff;
      }
      .footer-section p a {
      color: #ffffff !important;
      }
      .text-yellow {
      color: #f6e90e !important;
      }
      @media (min-width: 768px) {
      .gradient-form {
      height: 100vh !important;
      }
      }
      @media (min-width: 769px) {
      .gradient-custom-2 {
      border-top-left-radius: .8rem;
      border-bottom-left-radius: .8rem;
      }
      }
   </style>
   <section class="h-100 gradient-form bg-login1">
      <div class="container py-5 h-100">
      <h1 class="heading-title">Hello <span>Welcome to Our ERP <?=date('Y')?></span></h1>
         <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">               
               <div class="card rounded-3 text-black">
                  <div class="row g-0">
                     <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                        
                        <div class="text-white left-section deskonly">
                           <!-- <div class="text-center mobonly">
                              <img src="<?php echo base_url('userfiles/company/cloud-mobile-logo.png') ?>" alt="cloud erp" class="img-fluid">
                           </div> -->
                           <h4 class="mb-1">We are More Than Just an ERP; <br><b>Cloud Biz ERP</b></h4>
                           <p class="mb-1">Cloud-based ERP Software Enables Remote Management of Your Company's
                              Multiple Business Functions into One System, Facilitating Streamlined Operations
                              and Efficient Management From Anywhere with Internet Access.
                           </p>
                           <b>Benefits of Our Cloud ERP:</b><br>
                           <ul class="left-ul-section">
                              <li>100 Users with All Integration</li>
                              <li>Anywhere Operations</li>
                              <li>Integrated Point-of-Sale (POS)</li>
                              <li>FREE Data Migration</li>
                              <li>Customer Login</li>
                              <li>Integrated eCommerce </li>
                              <li>EDI</li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="card-body">
                           <div class="col-md-12">
                              <div class="card">
                                 <div class="card-header border-0">
                                    <!-- erp2024 change logo 04-06-2024 -->
                                    <div class="card-title text-center">
                                       <img class="mt-1 mb-1 img-fluid img-responsive" src="<?php echo base_url('userfiles/company/cloud-logo.png') ?>" alt="cloud erp" >
                                    </div>
                                    <!-- erp2024 change logo 04-06-2024 -->
                                    <!-- <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                       <span><?php echo $this->lang->line('employee_login_panel') ?></span></h6> -->
                                 </div>
                                 <div class="card-content">
                                    <div class="card-body">
                                       <?php
                                          $attributes = array('class' => 'form-horizontal form-simple', 'id' => 'login_form');
                                          echo form_open('user/checklogin', $attributes);
                                          ?>
                                       <fieldset class="form-group position-relative has-icon-left">
                                          <input type="text" class="form-control" id="user-name"
                                             name="username"
                                             placeholder="<?php echo $this->lang->line('Your Email') ?>"
                                             required>
                                          <div class="form-control-position">
                                             <i class="ft-user"></i>
                                          </div>
                                       </fieldset>
                                       <fieldset class="form-group position-relative has-icon-left">
                                          <input type="password" class="form-control" id="user-password"
                                             name="password"
                                             placeholder="<?php echo $this->lang->line('Your Password') ?>"
                                             required>
                                          <div class="form-control-position">
                                             <i class="fa fa-key"></i>
                                          </div>
                                       </fieldset>
                                       <?php if ($response) {
                                          echo '<div id="notify" class="alert alert-danger" >
                                                  <a href="#" class="close" data-dismiss="alert">&times;</a> <div class="message">' . $response . '</div>
                                              </div>';
                                          } ?>
                                       <?php //if ($this->aauth->get_login_attempts() > 1 && $captcha_on) {
                                          echo '<script src="https://www.google.com/recaptcha/api.js"></script>
                                          <fieldset class="form-group position-relative has-icon-left">
                                          <div class="g-recaptcha" data-sitekey="' . $captcha . '"></div>
                                          </fieldset>';
                                          //} ?>
                                       <div class="form-group row">
                                          <div class="col-md-6 col-12 text-center text-sm-left">
                                             <fieldset>
                                                <input type="checkbox" id="remember-me"
                                                   class="chk-remember" name="remember_me">
                                                <label for="remember-me">
                                                <?php echo $this->lang->line('remember_me') ?></label>
                                             </fieldset>
                                          </div>
                                          <div
                                             class="col-md-6 col-12 float-sm-left text-center text-sm-right">
                                             <a href="<?php echo base_url('user/forgot'); ?>"
                                                class="card-link"><?php echo $this->lang->line('forgot_password') ?>
                                             ?</a>
                                          </div>
                                       </div>
                                       <div class="login-btn-section">
                                          <button type="submit"
                                             class="btn btn btn-outline-secondary btn-width"><i
                                             class="ft-unlock"></i>
                                          <?php echo $this->lang->line('login') ?></button>
                                       </div>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-12 text-center dashboard-cards">
               <div class="col-lg-12">
                  <h1 style="color: #fff;">Connect with Our <span style="color: #ffb100;font-size: 31px;">Brilliant Apps</span>...</h1>

               </div>
                    <a href="javascript:void(0)"
                        class="btn btncard-login position-relative">
                        <i class="fa fa-line-chart"></i>
                        <h1>SALES</h1>
                    </a>
                  <a href="javascript:void(0)" class="btn btncard-login position-relative">
                     <i class="fa fa-exchange"></i>
                     <h1>STOCK</h1>
                  </a>
                  <a href="javascript:void(0)"  class="btn btncard-login position-relative" >
                        <i class="fa fa-usd"></i>
                        <h1>LEADS</h1>
                    </a>
                    <a href="javascript:void(0)"  class="btn btncard-login position-relative" >
                        <i class="fa fa-paper-plane-o"></i>
                        <h1>POS</h1>
                    </a>
                    <a href="javascript:void(0)"  class="btn btncard-login position-relative" >
                        <i class="fa fa-pencil-square-o"></i>
                        <h1>QUOTES</h1>
                    </a>
                    <a href="javascript:void(0)" class="btn btncard-login position-relative">
                        <i class="fa fa-user-plus"></i>
                        <h1>HRM</h1>
                    </a>
                  
                  <a href="javascript:void(0)"
                     class="btn btncard-login position-relative">
                     <i class="fa fa-cog"></i>
                     <h1>TOOLS</h1>
                  </a>
                  <a href="javascript:void(0)" class="btn btncard-login position-relative">
                     <i class="fa fa-object-group"></i>
                     <h1>PROJECTS</h1>
                  </a>
                  <a href="javascript:void(0)"
                     class="btn btncard-login position-relative">
                     <i class="fa fa-list-alt"></i>
                     <h1>REPORTS</h1>
                  </a>
                  <a href="javascript:void(0)"
                     class="btn btncard-login position-relative">
                     <i class="fa fa-money"></i>
                     <h1>ACCOUNTS</h1>
                  </a>
                    
                  <a href="https://online.cloudbizerp.com/" target="_blank"
                     class="btn btncard-login position-relative">
                     <i class="fa fa-globe"></i>
                     <h1>ONLINE STORE</h1>
                  </a>
                  <a href="https://chat.openai.com/" target="_blank" class="btn btncard-login position-relative" >             
                    <i class="fa fa-forumbee"></i>
                    <h1>AI</h1>
                </a> 
               </div>
            </div>
            <div class="footer-section">
               <p>Developed by <b>TreeCode IT Hub Pvt. Ltd. </b> <i class="ft-phone-call"></i> : <a
                  href="tel:+919288888846" style="color: #f6e90e !important;"><b>+91 92888 88846</b></a></p>
            </div>
         </div>
      </div>
      </div>
   </section>
   <script src="<?= assets_url(); ?>app-assets/vendors/js/vendors.min.js"></script>
   <script type="text/javascript" src="<?= assets_url(); ?>app-assets/vendors/js/ui/jquery.sticky.js"></script>
   <script type="text/javascript" src="<?= assets_url(); ?>app-assets/vendors/js/charts/jquery.sparkline.min.js"></script>
   <script src="<?= assets_url(); ?>app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
   <script src="<?= assets_url(); ?>app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
   <script src="<?= assets_url(); ?>app-assets/js/core/app-menu.js"></script>
   <script src="<?= assets_url(); ?>app-assets/js/core/app.js"></script>
   <script type="text/javascript" src="<?= assets_url(); ?>app-assets/js/scripts/ui/breadcrumbs-with-stats.js"></script>
   <script src="<?= assets_url(); ?>app-assets/js/scripts/forms/form-login-register.js"></script>