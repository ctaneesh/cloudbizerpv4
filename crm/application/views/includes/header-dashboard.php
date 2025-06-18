<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>CRM</title>
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo assets_url(); ?>crm-assets/images/ico/apple-icon-60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo assets_url(); ?>crm-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/png" href="<?php echo assets_url(); ?>crm-assets/images/ico/favicon-32.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/css<?= LTR ?>/bootstrap.css">
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/fonts/icomoon.css">
    <link rel="stylesheet" type="text/css"
          href="<?php echo assets_url(); ?>crm-assets/fonts/flag-icon-css/css<?= LTR ?>/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/vendors/css/extensions/pace.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/css<?= LTR ?>/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/css<?= LTR ?>/app.css">
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/css<?= LTR ?>/colors.css">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
          href="<?php echo assets_url(); ?>crm-assets/css<?= LTR ?>/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css"
          href="<?php echo assets_url(); ?>crm-assets/css<?= LTR ?>/core/menu/menu-types/vertical-overlay-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo assets_url(); ?>crm-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" href="<?php echo assets_url('crm-assets/custom/datepicker.min.css') ?>">
    <link rel="stylesheet" href="<?php echo assets_url('crm-assets/custom/jquery.dataTables.css') ?>">
    <link rel="stylesheet" href="<?php echo assets_url('crm-assets/custom/summernote-bs4.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets_url('crm-assets/custom/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets_url('crm-assets/css/custom.css'); ?>">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <?php  $urlname = assets_url();
     $main_url = str_replace('/crm', '', $urlname); ?>
     <link rel="stylesheet" type="text/css" href="<?php echo $main_url; ?>assets/css/style.css">
    <!-- END Custom CSS-->
    <script src="<?php echo assets_url(); ?>crm-assets/js/core/libraries/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url(); ?>crm-assets/vendors/js/ui/tether.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url(); ?>crm-assets/js/core/libraries/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url(); ?>crm-assets/portjs/raphael.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url(); ?>crm-assets/portjs/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url(); ?>crm-assets/js/summernote-bs4.min.js" type="text/javascript"></script>
    <script type="text/javascript">var baseurl = '<?php echo assets_url() ?>';</script>
    <script src="<?php echo assets_url('crm-assets/js/icheck.min.js'); ?>"></script>
    <script src="<?php echo assets_url('crm-assets/js/jquery.form-validator.min.js'); ?>"></script>
    <script src="<?php echo assets_url('crm-assets/js/custom.js'); ?>"></script>
</head>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns"
      class="vertical-layout vertical-menu 2-columns  fixed-navbar  menu-expanded">

<!-- navbar-fixed-top-->
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                
                <li class="nav-item" style="margin-top:-9px;"><a href="<?php echo base_url() ?>" class="navbar-brand nav-link"><img
                                alt="branding logo"
                                src="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/theme/logo-header.png"
                                data-expand="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/theme/logo-header.png"
                                data-collapse="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/theme/logo-header.png"
                                class="brand-logo height-50"></a></li>
                <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile"
                                                                    class="nav-link open-navbar-container"><i
                                class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
                <ul class="nav navbar-nav ">
                    <!-- <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i
                                    class="icon-menu5"> </i></a></li> -->
                    <!-- <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i
                                    class="icon icon-expand2"></i></a></li> -->

                </ul>
                <?php 
                
                $profilePics = !empty($profile_pic = $_SESSION['user_details'][0]->picture) ? $_SESSION['user_details'][0]->picture : 'user.png'; 
                // $profileimg = "../userfiles/customers/".$profilePics; 
                $profileimg = config_item('main_base_url')."/userfiles/customers/".$profilePics;
                ?>
                <ul class="nav navbar-nav float-xs-right">
                    <li class="dropdown dropdown-user nav-item"><a href="#" data-toggle="dropdown"  class="dropdown-toggle nav-link dropdown-user-link"><span
                                    class="avatar avatar-online"><img
                                        src="<?=$profileimg?>"
                                        alt="avatar"><i></i></span></a>
                        <div class="dropdown-menu dropdown-menu-right"><a href="<?php echo base_url(); ?>user/profile"
                                                                          class="dropdown-item"><i
                                        class="icon-head"></i><?php echo $this->lang->line('Profile') ?></a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo base_url('user/logout'); ?>" class="dropdown-item"><i
                                        class="icon-power3"></i> <?php echo $this->lang->line('Logout') ?></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- ////////////////////////////////////////////////////////////////////////////-->

