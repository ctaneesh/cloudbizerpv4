<?php
/**
 * Cloud Biz Erp  Accounting,  Invoicing  and CRM Software
 * Copyright (c) Cloud Biz Erp. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@cloudbizerp.com
 *  Website: https://www.cloudbizerp.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *   * Tree Code Hub IT (P) Ltd
 * ***********************************************************************
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_stock extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
		$this->load->model('dashboardstock_model');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
            exit;
        }
	}

	public function index(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$today = date("Y-m-d");

		$head['title'] = 'Dashboard Stock';
		$data['tt'] ='';

		 $data['products'] = $this->dashboardstock_model->group_products_list_count();
		 $data['product_stock_status'] = $this->dashboardstock_model->group_product_stock_status();		 
		 $data['purchase_order'] = $this->dashboardstock_model->get_purchase_order_list_count();
		 $data['purchase_return'] = $this->dashboardstock_model->get_purchase_return_list_count();

		 $data['stocks'] = $this->dashboardstock_model->get_stocks_count();		 
		 $data['categories'] = $this->dashboardstock_model->get_product_catgry_count();
		 $data['brands'] = $this->dashboardstock_model->get_product_brand_count();
		 $data['manufactrs'] = $this->dashboardstock_model->get_product_manufctr_count();
		 $data['purchs_recipts'] = $this->dashboardstock_model->get_purchs_recipts_count();

		$this->load->view('fixed/header');
		$this->load->view('dashboard/dashboard_stock', $data);
		$this->load->view('fixed/footer');

	}
        

}

