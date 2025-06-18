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

class Accounts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }

        // if (!$this->aauth->premission(5)) {

        //     exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        // }
        $this->load->model('accounts_model', 'accounts');
        $this->li_a = 'accounts';
    }

    public function index()
    {
        $data['accounts'] = $this->accounts->accountslist();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Accounts';
        $this->load->view('fixed/header', $head);
        $this->load->view('accounts/list', $data);
        $this->load->view('fixed/footer');
    }

    public function view()
    {
        $acid = $this->input->get('id');
        $data['account'] = $this->accounts->details($acid);
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'View Account';
        $this->load->view('fixed/header', $head);
        $this->load->view('accounts/view', $data);
        $this->load->view('fixed/footer');
    }

    public function add()
    {

        $data['accounts'] = $this->accounts->accountslist();
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->model('locations_model');
        $data['locations'] = $this->locations_model->locations_list2();
        $data['accountheaders'] = $this->accounts->load_coa_account_headers();
        $data['accounttypes'] = $this->accounts->load_coa_account_types();
        $data['accountlist'] = $this->accounts->load_account_list();
        $child = [];
        foreach($data['accounttypes'] as $row){
            $child[$row['coa_header_id']][] = $row;
        }      
        $data['child'] = $child;

        
        $accountchild=[];
        $accountparent=[];
        foreach($data['accountlist'] as $single){
            $accountchild[$single['coa_header_id']][] = $single;
            if($single['parent_account_id'])
            {
                
                $single['parent_account'] = $this->accounts->load_parent_by_id($single['parent_account_id']);
                $accountparent[$single['coa_header_id']][$single['parent_account_id']][] = $single;
            }
            
        } 
        $data['accountlists'] = $accountchild;
        $data['accountparent'] = $accountparent;
        $data['permissions'] = load_permissions('Accounts','Accounts','Manage Accounts','List');
        // echo "<pre>"; print_r($data['permissions']); die();
        $head['title'] = 'Add Account';
        $this->load->view('fixed/header', $head);
        $this->load->view('accounts/add', $data);
        $this->load->view('fixed/footer');
    }

    public function addacc()
    {
        $accno = $this->input->post('accno');
        $holder = $this->input->post('holder');
        $intbal = numberClean($this->input->post('intbal'));
        $acode = $this->input->post('acode');
        $lid = $this->input->post('lid');
        $account_type = $this->input->post('account_type');
        $account_type_id = $this->input->post('account_type_id');
        $parent_account_id = $this->input->post('parent_account_id');

        $account_id = $this->input->post('account_id');

        if ($this->aauth->get_user()->loc) {
            $lid = $this->aauth->get_user()->loc;
        }

        if ($accno) {
            $data = array(
                'acn' => $accno,
                'holder' => $holder,
                'adate' => date('Y-m-d H:i:s'),
                'lastbal' => $intbal,
                'code' => $acode,
                'loc' => $lid,
                'account_type'=>$account_type,
                'account_type_id'=>$account_type_id,
                'parent_account_id'=>$parent_account_id
            );
            if($account_id > 0)
            {
                $this->db->update('cberp_accounts', $data,['id'=>$account_id]);
                echo json_encode(array('status' => 'Success', 'message' =>"Account Updated Successfully"));
            }
            else{
                if ($this->db->insert('cberp_accounts', $data)) {
                    echo json_encode(array('status' => 'Success', 'message' =>"Account Created Successfully"));
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>"Account Number already used"));
                }
            }
            
            // $this->accounts->addnew($accno, $holder, $intbal, $acode, $lid, $account_type,$account_type_id,$parent_account_id);
        }
    }

    public function delete_i()
    {
        $id = $this->input->post('deleteid');
        if ($id) {
            $whr = array('id' => $id);
            if ($this->aauth->get_user()->loc) {
                $whr = array('id' => $id, 'loc' => $this->aauth->get_user()->loc);
            }
            $this->db->delete('cberp_accounts', $whr);
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('ACC_DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }

    //view for edit
    public function edit()
    {
        $catid = $this->input->get('id');
        $this->db->select('*');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $catid);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        // }
        $query = $this->db->get();
        $data['account'] = $query->row_array();
        $this->load->model('locations_model');
        $data['locations'] = $this->locations_model->locations_list();
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Edit Account';

        $this->load->view('fixed/header', $head);
        $this->load->view('accounts/edit', $data);
        $this->load->view('fixed/footer');

    }

    public function editacc()
    {
        $acid = $this->input->post('acid');
        $accno = $this->input->post('accno');
        $holder = $this->input->post('holder');
        $acode = $this->input->post('acode');
        $lid = $this->input->post('lid');
        $equity = numberClean($this->input->post('balance'));

        if ($this->aauth->get_user()->loc) {
            $lid = $this->aauth->get_user()->loc;
        }
        if ($acid) {
            $this->accounts->edit($acid, $accno, $holder, $acode, $lid, $equity);
        }
    }

    public function balancesheet()
    {

        $data['permissions'] = load_permissions('Accounts','Accounts','BalanceSheet');
        $head['title'] = "Balance Summary";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['accounts'] = $this->accounts->accountslist();

        $this->load->view('fixed/header', $head);
        $this->load->view('transactions/balance', $data);
        $this->load->view('fixed/footer');

    }

    public function account_stats()
    {
        $this->accounts->account_stats();

    }

    public function load_accounts_by_typeid()
    {
     
        $parent_accounts = $this->accounts->load_accounts_by_typeid($this->input->post('account_type_id'));
        $optionvals = "";
        if($parent_accounts)
        {
            $optionvals .= '<option value="">Select Parent Account</option>';
            $optionvals .= '<optgroup label="' . htmlspecialchars($parent_accounts[0]['typename']) . '">';
            foreach ($parent_accounts as $option) {
                $value = htmlspecialchars($option['id']);
                $label = htmlspecialchars($option['acn'] . ' - ' . $option['holder']);
                $accountnumber = $option['acn'];
                // $optionvals .= '<option value="' . $accountnumber . '">' . $label . '</option>';
                $optionvals .= '<option value="' . $value . '">' . $label . '</option>';
            }
            $optionvals .= '</optgroup>';
        }
        echo json_encode(array('status' => 'Success', 'data' => $optionvals));
    }
    
    public function load_accounts_by_id()
    {     
        $accountdetails = $this->accounts->load_accounts_by_id($this->input->post('account_id')); 
        echo json_encode(array('status' => 'Success', 'data' => $accountdetails));
    }

}
