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

defined('BASEPATH') or exit('No direct script access allowed');

class Coaaccounttypes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('coaaccounttypes_model', 'coaaccounttypes');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if ($this->aauth->get_user()->roleid < 4) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }


    }

    public function index()
    {

        $data['permissions'] = load_permissions('Accounts','Chart of Accounts','Account Types');
        $head['title'] = "Chart of Account Types";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['accountheaders'] = $this->coaaccounttypes->load_coa_account_headers();
        $data['details'] = $this->coaaccounttypes->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('coa/coaaccounttypeslist', $data);
        $this->load->view('fixed/footer');
    }

    public function addeditaction(){

         $coa_type_id = $this->input->post('coa_type_id', true);
         $typename = $this->input->post('typename', true);
         $coa_header_id = $this->input->post('coa_header_id', true);

         $masterdata = [
             'coa_type_id' => $coa_type_id,
             'coa_header_id' => $coa_header_id,
             'typename' => $typename,
             'created_by' => $this->session->userdata('id'),
             'created_dt' => date('Y-m-d H:i:s'),
             'status' => 'Active'
         ];
         $this->db->insert('cberp_coa_types',$masterdata);
         echo json_encode(array('status' => 'Success'));
        //  echo json_encode(array('status' => 'Success', 'message' =>$this->lang->line('Delivery Return') . "&nbsp;".$link."&nbsp;".$returns));
    }
    public function create_coa_account(){
        $head['title'] = "New Chart of Account";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $data['accountheaders'] = $this->coaaccounttypes->load_coa_account_headers();
        $data['accounttypes'] = $this->coaaccounttypes->load_coa_account_types();
        $child = [];
        foreach($data['accounttypes'] as $row){
            $child[$row['coa_header_id']][] = $row;
        }      
        $data['child'] = $child;
    
        $data['details'] = $this->coaaccounttypes->get_datatables();
        $this->load->view('fixed/header', $head);
        $this->load->view('coa/create_coa_account', $data);
        $this->load->view('fixed/footer');
        
    }


}
