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

class Dashboard_sales extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
		$this->load->model('dashboardsales_model');
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

		$head['title'] = 'Dashboard Accounts';
		$data['tt'] ='';

		$ranges = getCommonDateRanges();

		 $data['sales'] = $this->dashboardsales_model->sales_list_count();
		 $data['quotes'] = $this->dashboardsales_model->quotes_list_count();
		 $data['purchase_request'] = $this->dashboardsales_model->purchase_request_list_count();
		 $data['delivery_notes'] = $this->dashboardsales_model->delivery_notes_list_count();

		 $data['sales_graph'] = $this->dashboardsales_model->get_sales_overview_count($ranges);
		 $data['quote_graph'] = $this->dashboardsales_model->get_quote_overview_count($ranges);
		 $data['delivry_note_graph'] = $this->dashboardsales_model->get_delivry_note_graph_overview_count($ranges);
		 //$data['purchase_rqst_graph'] = $this->dashboardsales_model->get_purchase_rqst_overview_count($ranges);

		$this->load->model('invoices_model');       
        $condition = "";
        $data['purchase_rqst_graph'] = $this->invoices_model->get_dynamic_count('product_request','requested_dt','requested_qty',$condition);

		$data['subscriptions'] = $this->dashboardsales_model->subscriptions_list_count();

		 
		 
		//  echo "<pre>";
		//  print_r($data['delivry_note_graph']);
		//  echo "</pre>";
		//  exit();
		 

		$this->load->view('fixed/header');
		$this->load->view('dashboard/dashboard_sales', $data);
		$this->load->view('fixed/footer');

	}
        

}

