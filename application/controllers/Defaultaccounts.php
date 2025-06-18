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

class Defaultaccounts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('defaultaccounts_model', 'defaultaccounts');
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

        $head['title'] = "Default Accounts";
        $head['usernm'] = $this->aauth->get_user()->username;        
        $this->load->model('accounts_model');
        $data['defaultaccounts'] = $this->defaultaccounts->load_default_accounts();
        $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
        $data['accountlist'] = $this->accounts_model->load_account_list();

        $accountchild=[];       
        $accountparent=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
            if($single['parent_account_id'])
            {
                
                $single['parent_account'] = $this->accounts_model->load_parent_by_id($single['parent_account_id']);
                $accountparent[$single['coa_header_id']][$single['parent_account_id']][] = $single;
            }
            
        } 
        $data['accountlists'] = $accountchild;
        $data['accountparent'] = $accountparent;
        
        $data['last_log'] = $this->defaultaccounts->last_record();
        $this->load->view('fixed/header', $head);
        $this->load->view('default_accounts/defaultaccount_list', $data);
        $this->load->view('fixed/footer');
    }
    // public function index()
    // {
    //     $head['title'] = "Default Accounts";
    //     $head['usernm'] = $this->aauth->get_user()->username;        
    //     $this->load->model('accounts_model');
    //     $data['defaultaccounts'] = $this->defaultaccounts->load_default_accounts();
    //     $data['accountheaders'] = $this->accounts_model->load_coa_account_headers();
    //     $data['accounttypes'] = $this->accounts_model->load_coa_account_types();
    //     $data['accountlist'] = $this->accounts_model->load_account_list();
       
    //     $accountchild=[];
    //     foreach($data['accountlist'] as $single){
    //         $accountchild[$single['coa_header_id']][] = $single;
    //     } 
    //     $data['accountlists'] = $accountchild;
        
    //     $data['last_log'] = $this->defaultaccounts->last_record();
    //     $this->load->view('fixed/header', $head);
    //     $this->load->view('default_accounts/defaultaccount_list', $data);
    //     $this->load->view('fixed/footer');
    // }

    public function addeditaction(){
         $masterdata = [
             'accounts_receivable' => $this->input->post('accounts_receivable', true),
             'accounts_payable' => $this->input->post('accounts_payable', true),
             'sales' => $this->input->post('sales', true),
             'general_expenses' => $this->input->post('general_expenses', true),
             'sales_discount' => $this->input->post('sales_discount', true),
             'order_discount' => $this->input->post('order_discount', true),
             'shipping' => $this->input->post('shipping', true),
             'purchase_discount' => $this->input->post('purchase_discount', true),
             'purchase_account' => $this->input->post('purchase_account', true),
             'owners_contribution' => $this->input->post('owners_contribution', true),
             'inventory' => $this->input->post('inventory', true),
             'cost_of_goods_solid' => $this->input->post('cost_of_goods_solid', true),
             'sales_returns' => $this->input->post('sales_returns', true),
             'sales_returns' => $this->input->post('sales_returns', true),
             'product_income' => $this->input->post('product_income', true),
             'product_expense' => $this->input->post('product_expense', true),
             'costing_account' => $this->input->post('costing_account', true),
             'damage_account' => $this->input->post('damage_account', true),
             'updated_by' => $this->session->userdata('id'),
             'updated_dt' => date('Y-m-d H:i:s'),
         ];
        $table_records = $this->db->count_all('cberp_default_double_entry_accounts');
        $this->defaultaccounts->insert_to_log();
        if ($table_records == 0) {
            $masterdata['id'] = 1;
            $masterdata['created_by'] = $this->session->userdata('id');
            $masterdata['created_dt'] = date('Y-m-d H:i:s');
            $this->db->insert('cberp_default_double_entry_accounts',$masterdata);
        } else {
            $this->db->update('cberp_default_double_entry_accounts',$masterdata,['id'=>1]);
        } 
       
        echo json_encode(array('status' => 'Success'));
    }
   


}
