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

class Dashboard extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
            exit;
        }
        $this->load->model('dashboard_model');
        $this->load->model('tools_model');

    }


    public function index()
    {

        $today = date("Y-m-d");
        $month = date("m");
        $year = date("Y");
        // if ($this->aauth->get_user()->roleid > 3) {
            // $data['todayin'] = $this->dashboard_model->todayInvoice($today);
            // $data['todayprofit'] = $this->dashboard_model->todayProfit($today);
            // $data['incomechart'] = $this->dashboard_model->incomeChart($today, $month, $year);
            // $data['expensechart'] = $this->dashboard_model->expenseChart($today, $month, $year);
            // $data['countmonthlychart'] = $this->dashboard_model->countmonthlyChart();
            // $data['monthin'] = $this->dashboard_model->monthlyInvoice($month, $year);
            // $data['todaysales'] = $this->dashboard_model->todaySales($today);
            // $data['monthsales'] = $this->dashboard_model->monthlySales($month, $year);
            // $data['todayinexp'] = $this->dashboard_model->todayInexp($today);
            // $data['recent_payments'] = $this->dashboard_model->recent_payments();
            // $data['tasks'] = $this->dashboard_model->tasks($this->aauth->get_user()->id);
            // $data['recent'] = $this->dashboard_model->recentInvoices();
            // $data['recent_buy'] = $this->dashboard_model->recentBuyers();
            // $data['goals'] = $this->tools_model->goals(1);
            // $data['stock'] = $this->dashboard_model->stock();
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Dashboard';
            $this->load->view('fixed/header');
            $this->load->view('home', $data);
            $this->load->view('fixed/footer');
        // } 
    }
    public function dashboard()
    {
        $data['permissions'] = [];
        $today = date("Y-m-d");
        $month = date("m");
        $year = date("Y");
          ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // if ($this->aauth->get_user()->roleid > 3) {
            $data['todayin'] = $this->dashboard_model->todayInvoice($today);
            // $data['todayprofit'] = $this->dashboard_model->todayProfit($today);
            $data['todayprofit']="";
            $data['incomechart'] = $this->dashboard_model->incomeChart($today, $month, $year);
            $data['expensechart'] = $this->dashboard_model->expenseChart($today, $month, $year);
            $data['countmonthlychart'] = $this->dashboard_model->countmonthlyChart();
            $data['monthin'] = $this->dashboard_model->monthlyInvoice($month, $year);
            $data['todaysales'] = $this->dashboard_model->todaySales($today);
            $data['monthsales'] = $this->dashboard_model->monthlySales($month, $year);
            $data['todayinexp'] = $this->dashboard_model->todayInexp($today);
            $data['recent_payments'] = $this->dashboard_model->recent_payments();
            $data['tasks'] = $this->dashboard_model->tasks($this->aauth->get_user()->id);
            $data['recent'] = $this->dashboard_model->recentInvoices();
            $data['recent_buy'] = $this->dashboard_model->recentBuyers();
            // print_r( $data['recent_buy']); die();
            $data['goals'] = $this->tools_model->goals(1);
            $data['stock'] = $this->dashboard_model->stock();
            $head['usernm'] = $this->aauth->get_user()->username;
            $head['title'] = 'Dashboard';
            $this->load->view('fixed/header', $head);
            $this->load->view('dashboard', $data);
            $this->load->view('fixed/footer');
        
    }

    public function clock_in()
    {

        $id = $this->aauth->get_user()->id;
        if ($this->aauth->auto_attend()) {
            $this->dashboard_model->clockin($id);
        }

        redirect('dashboard');
    }

    public function clock_out()
    {
        $id = $this->aauth->get_user()->id;

        if ($this->aauth->auto_attend()) {
            $this->dashboard_model->clockout($id);
        }


        redirect('dashboard');
    }
}
