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

class Invoices extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model', 'invocies');
        if (!is_login()) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    //invoices list
    public function index()
    {
        $head['title'] = "Dashboard";
        $this->load->view('includes/header-dashboard');
        $this->load->view('invoices/dashboard');
        $this->load->view('includes/footer');
    }
    public function invoices()
    {
        $head['title'] = "Manage Invoices";
        $this->load->view('includes/header');
        $this->load->view('invoices/invoices');
        $this->load->view('includes/footer');
    }


    public function ajax_list()
    {
        $query = $this->db->query("SELECT currency FROM cberp_system WHERE id=1 LIMIT 1");
        $row = $query->row_array();

        $this->config->set_item('currency', $row["currency"]);


        $list = $this->invocies->get_datatables();
        $data = array();

        $no = $this->input->post('start');
        $curr = $this->config->item('currency');
        $main_url = config_item('main_base_url');
        foreach ($list as $invoices) {
            $validtoken = hash_hmac('ripemd160', $invoices->id, $this->config->item('encryption_key'));
            $targeturl = base_url("invoices/view?id=$invoices->id");
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . $targeturl . '">'.$invoices->invoice_number.'</a>';
            $row[] = $invoices->name;
            $row[] = $invoices->invoicedate;
            $row[] = number_format($invoices->total,2);
            $row[] = '<span class="st-' . strtolower($invoices->status) . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . $targeturl . '" class="btn btn-secondary btn-sm"><i class="icon-eye"></i> </a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->invocies->count_all(),
            "recordsFiltered" => $this->invocies->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        $data['acclist'] = '';
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;

        $data['invoice'] = $this->invocies->invoice_details($tid);
        // echo "<pre>"; print_r($data['invoice']); die();
        // if($data['invoice']['csd']==$this->session->userdata('user_details')[0]->cid){
        $data['products'] = $this->invocies->invoice_products($tid);
        $data['activity'] = $this->invocies->invoice_transactions($tid);
        $data['employee'] = $this->invocies->employee($data['invoice']['eid']);
        $this->load->view('includes/header');
        $this->load->view('invoices/view', $data);
        $this->load->view('includes/footer');
        // }

    }


}
