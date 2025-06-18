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

class Dashboard_accounts extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
		$this->load->model('dashboardaccounts_model');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
            exit;
        }
	}

	public function index(){
		$today = date("Y-m-d");

		$head['title'] = 'Dashboard Accounts';
		$data['tt'] ='';

		$ranges = getCommonDateRanges();

		 $data['accounts'] = $this->dashboardaccounts_model->accounts_list_count();
		 $data['invoices'] = $this->dashboardaccounts_model->invoices_list_count();
		 $data['transactions'] = $this->dashboardaccounts_model->transactions_list_count();
		 $data['manualjournals'] = $this->dashboardaccounts_model->get_manualjournl_count();

		 $data['invoice_graph'] = $this->dashboardaccounts_model->get_invoice_overview_count($ranges);
		 $data['credit_graph'] = $this->dashboardaccounts_model->get_credit_overview_count($ranges);

		 $data['bank_accounts'] = $this->dashboardaccounts_model->bank_accounts_list_count();
		 $data['bank_category'] = $this->dashboardaccounts_model->bank_category_list_count();
		 $data['recociliations'] = $this->dashboardaccounts_model->recociliations_list_count();
		 
		//  echo "<pre>";
		//  print_r($data['credit_graph']);
		//  echo "</pre>";
		//  exit();
		 

		$this->load->view('fixed/header');
		$this->load->view('dashboard/dashboard_accounts', $data);
		$this->load->view('fixed/footer');

	}
        

}

