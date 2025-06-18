<body data-open="click" data-menu="vertical-menu" data-col="1-column"
      class="vertical-layout vertical-menu 1-column ">
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
         .justify-content-center {
            -webkit-box-pack: center !important;
            -webkit-justify-content: center !important;
            -moz-box-pack: center !important;
            -ms-flex-pack: center !important;
            justify-content: center !important;
         }
         .align-items-center {
            -webkit-box-align: center !important;
            -webkit-align-items: center !important;
            -moz-box-align: center !important;
            -ms-flex-align: center !important;
            align-items: center !important; }
            .h-100 {
               height: 100% !important;
            }
            
      </style>
<div class="app-content content1 container-fluid bg-login">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
        <h1 class="heading-title">Hello there,<span>Welcome to Customer Login</span></h1>
            <section class="h-100 gradient-form bg-login1">
               <div class="container py-5 h-100">
                  <div class="row d-flex justify-content-center align-items-center h-100">
                     <div class="col-xl-1"></div>  
                        <div class="col-xl-10">                
                        <div class="card rounded-3 text-black">
                           <div class="row g-0">
                              <div class="col-lg-6 d-flex align-items-center gradient-custom-2 deskonly">
                                 
                                    <div class="text-white left-section">
                                       <!-- <div class="text-center mobonly">
                                          <img src="<?php echo base_url('userfiles/company/cloud-mobile-logo.png') ?>" alt="cloud erp" class="img-fluid">
                                       </div> -->
                                       <h4 class="mb-1">We are more than just a ERP; <br><b>Cloud Biz ERP</b></h4>
                                       <p class="mb-1">Cloud-based ERP software enables remote management of your company's
                                          multiple business functions into one system, facilitating streamlined operations
                                          and efficient management from anywhere with internet access.
                                       </p>
                                       <b>Benefits of our Cloud ERP:</b><br>
                                       <ul class="left-ul-section">
                                          <li>100 users with all integration</li>
                                          <li>Anywhere operations</li>
                                          <li>Integrated Point-of-Sale (POS)</li>
                                          <li>FREE data migration</li>
                                          <li>Customer login</li>
                                          <li>Integrated eCommerce </li>
                                          <li>EDI</li>
                                       </ul>
                                    </div>
                                 </div>  
                                 <div class="col-lg-6">
                                    <div class="card1 m-0">
                                          <div class="card-header no-border">
                                             <div class="card-title text-xs-center" >
                                                <div class="">
                                                      <!-- <img width="100%"  src="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/company/<?php echo $this->config->item('logo'); ?>" alt="Logo"> -->
                                                      <img class="mt-1 mb-1 img-fluid img-responsive"  src="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/company/cloud-logo.png" alt="Logo">
                                                </div>
                                             </div>
                                          
                                          </div>
                                          <div class="card-body collapse in">
                                             <div class="card-block">
                                                <?php if ($this->session->flashdata("messagePr")) { ?>
                                                      <div class="alert alert-info">
                                                         <?php echo $this->session->flashdata("messagePr") ?>
                                                      </div>
                                                <?php } ?>
                                                <form class="form-horizontal form-simple"
                                                      action="<?php echo base_url() . 'user/auth_user'; ?>" method="post">
                                                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                                      <fieldset class="form-group position-relative has-icon-left mb-2">
                                                         <input type="text" name="email" class="form-control" placeholder="Email" required>
                                                         <!-- <div class="form-control-position">
                                                            <i class="icon-head"></i>
                                                         </div> -->
                                                      </fieldset>
                                                      <fieldset class="form-group position-relative has-icon-left mb-2">
                                                         <input type="password" name="password" class="form-control" id="pwd"
                                                               placeholder="Password" required>
                                                         <!-- <div class="form-control-position">
                                                            <i class="icon-key3"></i>
                                                         </div> -->
                                                      </fieldset>

                                          <?php if ($captcha_on) {
                                                   echo '<script src="https://www.google.com/recaptcha/api.js"></script>
                                                   <fieldset class="form-group position-relative has-icon-left">
                                                      <div class="g-recaptcha" data-sitekey="'.$captcha.'"></div>
                                                      </fieldset>';
                                                      } ?>


                                                   
                                                   <div class="col-12" style="text-align:center">
                                                      <button type="submit" class="btn btn-blue"><i
                                                                     class="icon-unlock2"></i> Login
                                                         </button>
                                                   </div>
                                                </form> 
                                                <?php    if ($this->common->front_end()->register) {?> <br> <div class="row"><span class="col-xs-7"><a
                                                                     href="<?php echo base_url('user/registration'); ?>" class="card-link">
                                                               <?php echo $this->lang->line('Register')  ?></a></span><span class="col-xs-5"><a
                                                                     href="<?php echo base_url('user/forgot'); ?>" class="card-link">
                                                            <?php echo $this->lang->line('forgot_password')  ?>?</a></span></div>
                                                <?php } ?>
                                             </div>
                                          </div>
                                          
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-12 text-center dashboard-cards">
                                 <div class="col-lg-12">
                                    <h1 style="color: #fff;">Connect with our <span
                                             style="color: #ffb100;font-size: 31px;">Brilliant Apps</span>...</h1>

                                 </div>
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-file-text display-inline-block"></i>
                                    <h1>INVOICES</h1>
                                 </a>
                                 <!-- <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-android-calendar display-inline-block"></i>
                                    <h1>SUBSCRIPTIONS</h1>
                                 </a> -->
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-pencil-square display-inline-block"></i>
                                    <h1>QUOTES</h1>
                                 </a>
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-file display-inline-block"></i>
                                    <h1>ENQUIRIES</h1>
                                 </a>
                                 <!-- <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-credit-card2 display-inline-block"></i>
                                    <h1>RECHARGE ACCOUNT</h1>
                                 </a> -->
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-cash display-inline-block"></i>
                                    <h1>PAYMENT HISTORY</h1>
                                 </a>

                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-ticket display-inline-block"></i>
                                    <h1>TICKETS</h1>
                                 </a>
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-stack display-inline-block"></i>
                                    <h1>PROJECTS</h1>
                                 </a>
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-globe display-inline-block"></i>
                                    <h1>WEBSITE</h1>
                                 </a>
                                 <a href="javascript:void(0)" class="btn btncard-login position-relative">
                                    <i class="fa icon-android display-inline-block"></i>
                                    <h1>APP</h1>
                                 </a>
                                 <a href="https://chat.openai.com/" target="_blank" class="btn btncard-login position-relative">
                                    <i class="fa icon-forumbee display-inline-block"></i>
                                    <h1>AI</h1>
                                 </a>
                           </div>

                        </div>
                        

                     </div>
                     <div class="footer-section">
                           <p>Developed by <b>TreeCode IT Hub Pvt. Ltd. </b> <i class="ft-phone-call"></i> : <a
                                    href="tel:+919288888846" style="color: #f6e90e !important;"><b>+91 92888 88846</b></a></p>
                        </div>
                  </div>
               </div>
            </section>

        </div>
    </div>
</div>