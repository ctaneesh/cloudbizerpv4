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

class Dashboard_crm extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
		$this->load->model('dashboardcrm_model');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
            exit;
        }
        // $this->load->model('dashboard_model');
        // $this->load->model('tools_model');

    }


    public function index()
	{
		$today = date("Y-m-d");

		// Define date ranges for graph
		$ranges = [
			'month'    => date('Y-m-01'),
			'week'     => date('Y-m-d', strtotime('monday this week')),
			'quarter'  => date('Y-m-d', strtotime(date('Y') . '-' . (ceil(date('n') / 3) * 3 - 2) . '-01')),
			'year'     => date('Y-01-01')
		];

		$head['title'] = 'Dashboard CRM';

		
		$data['customer_group'] = $this->dashboardcrm_model->group_list_count();
		$data['leads'] = $this->dashboardcrm_model->get_enquiries(); 
		$data['cutomers'] = $this->dashboardcrm_model->get_customers();
		$data['suppliers'] = $this->dashboardcrm_model->get_suppliers();
		
        // Get graph data
		$ranges = getCommonDateRanges();		
		$data['lead_graph'] = $this->dashboardcrm_model->get_enquiry_count($ranges);

		$data['ticket_data'] = $this->dashboardcrm_model->get_support_ticket_count();
		// Load views
		$this->load->view('fixed/header');
		$this->load->view('dashboard/dashboard_crm', $data);
		$this->load->view('fixed/footer');
	}

    

    
}
