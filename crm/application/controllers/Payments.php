<?php
/**
 * Cloud Biz Erp -  Accounting,  Invoicing  and CRM Software
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

defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payments_model', 'payments');
        if (!is_login()) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    //invoices list
    public function index()
    {
        $head['title'] = "Payments";
        $this->load->view('includes/header');
        $this->load->view('payments/payments');
        $this->load->view('includes/footer');
    }

    public function recharge()
    {
        $head['title'] = "Payments";
        $data['balance']=$this->payments->balance($this->session->userdata('user_details')[0]->cid);
        $data['activity']=$this->payments->activity($this->session->userdata('user_details')[0]->cid);
        $data['gateway'] = $this->payments->gateway_list('Yes');

        $this->load->view('includes/header');
        $this->load->view('payments/recharge',$data);
        $this->load->view('includes/footer');
    }


    public function ajax_list()
    {
        $query = $this->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
        $row = $query->row_array();

        $this->config->set_item('currency', $row["currency"]);


        $list = $this->payments->get_datatables();
        $data = array();

        $no = $this->input->post('start');
        $curr = $this->config->item('currency');
        foreach ($list as $invoices) {
            $dueamount = $invoices->total - $invoices->pamnt;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $invoices->invoice_number;
            $row[] =  dateformat($invoices->invoicedate);
            $row[] =   number_format($invoices->total,2);
            $row[] =  dateformat($invoices->paiddate);
            $row[] =   number_format($invoices->singleamount,2);
            $row[] =   number_format($invoices->pamnt,2);
            $row[] = number_format($dueamount,2);;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->payments->count_all(),
            "recordsFiltered" => $this->payments->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }




}
