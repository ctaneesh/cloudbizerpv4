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

class Paymentgateways extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('billing_model', 'billing');
        $this->load->model('invoices_model', 'invoices');
        $this->load->model('accounts_model', 'accounts');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if ($this->aauth->get_user()->roleid < 5) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }
    }

    public function index()
    {

        $data['gateway'] = $this->billing->gateway_list();
        $this->load->view('fixed/header');
        $this->load->view('payment/list', $data);
        $this->load->view('fixed/footer');
    }


    public function edit()
    {
        if ($this->input->post()) {

            $gid = $this->input->post('gid');
            $currency = $this->input->post('currency');
            $key1 = $this->input->post('key1');
            $key2 = $this->input->post('key2');
            $enable = $this->input->post('enable');
            $devmode = $this->input->post('devmode');
            $p_fee = $this->input->post('p_fee');

            if ($key2 == '') {
                $key2 = 'none';
            }

            $this->billing->gateway_update($gid, $currency, $key1, $key2, $enable, $devmode, $p_fee);

        } else {

            $id = intval($this->input->get('id'));
            $data['gateway'] = $this->billing->gateway($id);
            $this->load->view('fixed/header');
            $this->load->view('payment/gateway-edit', $data);
            $this->load->view('fixed/footer');

        }

    }


    public function settings()
    {
        $this->load->model('plugins_model', 'plugins');
        if ($this->input->post()) {

            $id = $this->input->post('account');
            $enable = $this->input->post('enable');
            $bank_enable = $this->input->post('bank');
            $pos_list = $this->input->post('pos_list');
            $auto_debit = $this->input->post('auto_debit');

            $this->billing->payment_settings($id, $enable, $bank_enable);

              $this->plugins->m_update_api(69, null, $auto_debit,null,null,null,null, false);

            if ($pos_list != PAC) {
                $config_file_path = APPPATH . "config/constants.php";
                $config_file = file_get_contents($config_file_path);
                $config_file = str_replace("('PAC', '" . PAC . "')", "('PAC', '$pos_list')", $config_file);
                file_put_contents($config_file_path, $config_file);
            }

        } else {

            $data['current'] = $this->plugins->universal_api(69);
            $this->load->model('accounts_model');
            $data['acclist'] = $this->accounts_model->accountslist();
            $data['online_pay'] = $this->billing->online_pay_settings();
            $this->load->view('fixed/header');
            $this->load->view('payment/settings', $data);
            $this->load->view('fixed/footer');

        }

    }

    function bank_accounts()
    {
        $data['permissions'] = load_permissions('Accounts','Banking','Bank Accounts');
        $data['bank_accounts'] = $this->billing->bank_accounts();
        $this->load->view('fixed/header');
        $this->load->view('payment/bank_list', $data);
        $this->load->view('fixed/footer');
    }


    public function add_bank_ac()
    {  
        
        // $data['permissions'] = load_permissions('Accounts','Banking','Bank Accounts');
        if ($this->input->post()) {

            $name = $this->input->post('name', true);
            $acn = $this->input->post('acn', true);
            $code = $this->input->post('code', true);
            $enable = $this->input->post('enable');
            $branch = $this->input->post('branch', true);
            $address = $this->input->post('address', true);
            $bank = $this->input->post('bank', true);
            $defaultaccount = $this->input->post('defaultaccount', true);
            $bankphone = $this->input->post('bankphone', true);
            $opening_balance = $this->input->post('opening_balance', true);
            if($defaultaccount=='Yes')
            {
                $this->db->update('cberp_bank_ac', ['defaultaccount'=>'No']);
            }
            $data = array(
                'name' => $name,
                'acn' => $acn,
                'code' => $code,
                'enable' => $enable,
                'note' => $bank,
                'bank' => $bank,
                'branch' => $branch,
                'address' => $address,
                'bankphone' => $bankphone,
                'defaultaccount' => $defaultaccount,
                'opening_balance' => $opening_balance
            );
           
           
          
            if ($this->db->insert('cberp_bank_ac', $data)) {
                $typedata = $this->billing->load_account_type_details();
                $accountdata =  array(
                    'acn' => $code,
                    'holder' => $name,
                    'adate' => date('Y-m-d H:i:s'),
                    'code' => $code,
                    'loc' => 1,
                    'account_type'=>$typedata['typename'],
                    'account_type_id'=>$typedata['id']
                );
                $this->db->insert('cberp_accounts', $accountdata);
               
                if($opening_balance)
                {
                    $transaction_number = get_latest_trans_number();
                    $bank_transaction_number = get_transnumber();
                    $bank_data = [
                        'acid' => $code,
                        'type' => 'Opening',
                        'cat' => 'Opening Balance',
                        'debit' => $opening_balance,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$bank_data);
                    $this->db->set('lastbal', 'lastbal + ' .$opening_balance, FALSE);
                    $this->db->where('acn', $code);
                    $this->db->update('cberp_accounts');

                    $equitycode = '300';
                    $equity_data = [
                        'acid' => $equitycode,
                        'type' => 'Opening',
                        'cat' => 'Opening Balance',
                        'credit' => $opening_balance,
                        'eid' => $this->session->userdata('id'),
                        'date' => date('Y-m-d'),
                        'transaction_number'=>$transaction_number
                    ];
                    $this->db->insert('cberp_transactions',$equity_data);
                    $this->db->set('lastbal', 'lastbal - ' .$opening_balance, FALSE);
                    $this->db->where('acn', $equitycode);
                    $this->db->update('cberp_accounts');

                    $banktranslink_data = [                            
                        'trans_type' => 'Opening',
                        'transaction_number'=> $transaction_number,
                        'bank_transaction_number'=>$bank_transaction_number,
                        'created_dt' => date('Y-m-d H:i:s'),
                        'created_by'=> $this->session->userdata('id')
                       
                    ];
                    $this->db->insert('cberp_payment_transaction_link', $banktranslink_data);
                    $banktrans_data = [
                        'trans_type' => 'Opening',
                        'trans_amount' => $opening_balance,
                        'trans_date' => DATE('Y-m-d'),
                        'trans_number'=>$bank_transaction_number,
                        'trans_payment_method'=> 'Cash',
                        'trans_account_id'=>$code,
                        'trans_chart_of_account_id'=>$equitycode,
                        'from_trans_number'=>$transaction_number,
                        'trans_ref_number' => get_banktrans_reference_number()
                    ];
                    $this->db->insert('cberp_bank_transactions',$banktrans_data);
                    
                }
               
                echo json_encode(array('status' => 'Success', 'message' =>"Account Created Successfully"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>"Account Number already used"));
            }
        } else {
            $data['permissions'] = load_permissions('Accounts','Banking','Bank Accounts','','Add New');
            $head['title'] = "Add Bank Account";
            $this->load->view('fixed/header', $head);
            $this->load->view('payment/bank-add',$data);
            $this->load->view('fixed/footer');

        }

    }


    public function edit_bank_ac()
    {

        $data['permissions'] = load_permissions('Accounts','Banking','Bank Accounts');
        if ($this->input->post()) {

            $gid = $this->input->post('gid');
            $name = $this->input->post('name', true);
            $acn = $this->input->post('acn', true);
            $code = $this->input->post('code', true);
            $enable = $this->input->post('enable', true);
            $branch = $this->input->post('branch', true);
            $address = $this->input->post('address', true);
            $bank = $this->input->post('bank', true);
            $defaultaccount = $this->input->post('defaultaccount', true);
            $bankphone = $this->input->post('bankphone', true);
            $opening_balance = $this->input->post('opening_balance', true);
            $old_opening_balance = ($this->input->post('old_opening_balance', true))? $this->input->post('old_opening_balance', true):0;
            if($defaultaccount=='Yes')
            {
                $this->db->update('cberp_bank_ac', ['defaultaccount'=>'No']);
            }
            $data = array(
                'name' => $name,
                'acn' => $acn,
                'code' => $code,
                'enable' => $enable,
                'note' => $bank,
                'bank' => $bank,
                'branch' => $branch,
                'address' => $address,
                'bankphone' => $bankphone,
                'defaultaccount' => $defaultaccount,
                'opening_balance' => $opening_balance
            );
            $this->db->set($data);
            $this->db->where('id', $gid);
            if ($this->db->update('cberp_bank_ac')) 
            {

                if($opening_balance)
                {
                    $equitycode = '300';
                    $this->accounts->update_account_balance($code,$old_opening_balance,$opening_balance,$equitycode);
                    $this->accounts->update_balance_transactions($code,$opening_balance,$equitycode);

                }
                echo json_encode(array('status' => 'Success', 'message' =>"Account Update Successfully"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>"Account Number already used"));
            }
        } else {

            $id = intval($this->input->get('id'));
            $head['title'] = "Edit Bank Account";
            $data['bank_account'] = $this->billing->bank_account_info($id);
            $this->load->view('fixed/header', $head);
            $this->load->view('payment/bank-edit', $data);
            $this->load->view('fixed/footer');

        }

    }


    public function delete_bank_ac()
    {
        $id = $this->input->post('deleteid');
        if ($id) {
            $this->db->delete('cberp_bank_ac', array('id' => $id));
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }


    function currencies()
    {

        $data['currency_list'] = $this->invoices->currencies();
        $this->load->view('fixed/header');
        $this->load->view('payment/currency_list', $data);
        $this->load->view('fixed/footer');
    }

    public function add_currency()
    {
        if ($this->input->post()) {


            $code = $this->input->post('code', true);
            $symbol = $this->input->post('symbol', true);
            $spos = $this->input->post('spos');
            $rate = $this->input->post('rate');
            $decimal = $this->input->post('decimal');
            $thous_sep = $this->input->post('thous_sep');
            $deci_sep = $this->input->post('deci_sep');

            $this->billing->add_currency($code, $symbol, $spos, $rate, $decimal, $thous_sep, $deci_sep);

        } else {

            $head['title'] = "Add Currency";
            $this->load->view('fixed/header', $head);
            $this->load->view('payment/add_currency');
            $this->load->view('fixed/footer');

        }

    }


    public function edit_currency()
    {
        if ($this->input->post()) {

            $gid = $this->input->post('gid');
            $code = $this->input->post('code', true);
            $symbol = $this->input->post('symbol', true);
            $spos = $this->input->post('spos');
            $rate = $this->input->post('rate');
            $decimal = $this->input->post('decimal');
            $thous_sep = $this->input->post('thous_sep');
            $deci_sep = $this->input->post('deci_sep');

            $this->billing->edit_currency($gid, $code, $symbol, $spos, $rate, $decimal, $thous_sep, $deci_sep);

        } else {

            $id = intval($this->input->get('id'));
            $head['title'] = "Edit Currency";
            $data['currency_d'] = $this->invoices->currency_d($id);
            $this->load->view('fixed/header', $head);
            $this->load->view('payment/currency-edit', $data);
            $this->load->view('fixed/footer');

        }

    }

    public function delete_currency()
    {
        $id = $this->input->post('deleteid');
        if ($id) {
            $this->db->delete('cberp_currencies', array('id' => $id));
            echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('DELETED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
        }
    }


    function exchange()
    {
        if ($this->input->post()) {

            $currency = $this->input->post('currency', true);
            $key1 = $this->input->post('key1', true);
            $key2 = $this->input->post('key2', true);
            $enable = $this->input->post('enable');
            $reverse = $this->input->post('reverse');


            $this->billing->exchange($currency, $key1, $key2, $enable, $reverse);

        } else {

            $this->load->model('plugins_model', 'plugins');
            $data['exchange'] = $this->plugins->universal_api(5);
            $this->load->view('fixed/header');
            $this->load->view('payment/exchange', $data);
            $this->load->view('fixed/footer');
        }
    }
    public function check_code_used_or_not()
    {
        $code = $this->input->post('code');
        $exists = $this->accounts->is_code_taken($code);
        echo $exists ? 'true' : 'false';
    }

    

}
