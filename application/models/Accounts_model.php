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


class Accounts_model extends CI_Model
{
    var $table = 'cberp_accounts';

    public function __construct()
    {
        parent::__construct();
    }

    public function accountslist($l=true,$lid=0)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        // if ($l) {
        //     if ($this->aauth->get_user()->loc) {
        //         $this->db->where('loc', $this->aauth->get_user()->loc);
        //         if (BDATA) $this->db->or_where('loc', 0);
        //     } else {
        //         if (!BDATA) $this->db->where('loc', 0);
        //     }
        // } else {
        //     $this->db->where('loc', $lid);
        // }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function details($acid)
    {

        $this->db->select('*');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $acid);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->group_start();
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //     if(BDATA)  $this->db->or_where('loc', 0);
        //     $this->db->group_end();
        // }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function addnew($accno, $holder, $intbal, $acode, $lid,$account_type,$account_type_id,$parent_account_id)
    {
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
      
        if ($this->db->insert('cberp_accounts', $data)) {
            $this->aauth->applog("[Account Created] $accno - $intbal ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED'). "  <a href='".base_url('accounts')."' class='btn btn-secondary btn-sm'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a> <a href='add' class='btn btn-secondary btn-sm'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a>"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function edit($acid, $accno, $holder, $acode, $lid,$account_equity='')
    {
        if($account_equity){
            $data = array(
                'acn' => $accno,
                'holder' => $holder,
                'code' => $acode,
                'loc' => $lid,
                'lastbal'=>$account_equity
            );
        }
        else{
               $data = array(
            'acn' => $accno,
            'holder' => $holder,
            'code' => $acode,
            'loc' => $lid
        );
        }

        $this->db->set($data);
        $this->db->where('id', $acid);
        //  if ($this->aauth->get_user()->loc) {
        //    $this->db->where('loc', $this->aauth->get_user()->loc);
        //  }

        if ($this->db->update('cberp_accounts')) {
            $this->aauth->applog("[Account Edited] $accno - ID " . $acid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
        }

    }

    public function account_stats()
    {
        $whr = ' ';
        // if ($this->aauth->get_user()->loc) {
        //     $whr = ' WHERE loc=' . $this->aauth->get_user()->loc;
        //      if(BDATA) $whr .= 'OR loc=0 ';
        // }

        $query = $this->db->query("SELECT SUM(lastbal) AS balance,COUNT(id) AS count_a FROM cberp_accounts $whr");

        $result = $query->row_array();
        echo json_encode(array(0 => array('balance' => amountExchange($result['balance'], 0, $this->aauth->get_user()->loc), 'count_a' => $result['count_a'])));

    }
    public function load_coa_account_headers()
    {
        $this->db->from('cberp_coa_headers');
        $this->db->where('status', 'Active');
        $this->db->order_by('accounting_sort_order', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function load_coa_account_types()
    {
        $this->db->select('id,typename,coa_header_id,coa_type_id');
        $this->db->from('cberp_coa_types');
        $this->db->where('status', 'Active');
        $this->db->order_by('typename', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function load_accounts_by_typeid($account_type_id)
    {
        $this->db->select('cberp_accounts.id,cberp_accounts.acn,cberp_accounts.holder,cberp_coa_types.typename');
        $this->db->from('cberp_accounts');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id');
        $this->db->where('cberp_accounts.account_type_id', $account_type_id);        
        $this->db->order_by('cberp_accounts.holder', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function load_accounts_by_id($account_id)
    {
        $this->db->select('*');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $account_id);   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function accountslis1t($l=true,$lid=0)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        // if ($l) {
        //     if ($this->aauth->get_user()->loc) {
        //         $this->db->where('loc', $this->aauth->get_user()->loc);
        //         if (BDATA) $this->db->or_where('loc', 0);
        //     } else {
        //         if (!BDATA) $this->db->where('loc', 0);
        //     }
        // } else {
        //     $this->db->where('loc', $lid);
        // }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function load_account_list()
    {
        $this->db->select('cberp_accounts.*,cberp_coa_types.id as typeid,cberp_coa_types.coa_type_id as coa_type_id,cberp_coa_types.typename,cberp_coa_types.coa_header_id,cberp_coa_headers.coa_header,cberp_accounts.lastbal');
        $this->db->from('cberp_accounts');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id','left');
        $this->db->join('cberp_coa_headers', 'cberp_coa_types.coa_header_id = cberp_coa_headers.coa_header_id','left');
        // $this->db->where('status', 'Active');
        $this->db->order_by('acn', 'ASC'); 
      
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }

    public function load_parent_by_id($id)
    {
        $this->db->select('cberp_accounts.acn');
        $this->db->from('cberp_accounts');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result->acn;
    }

    public function load_account_list_by_type($type)
    {
        $this->db->select('cberp_accounts.*,cberp_coa_types.id as typeid,cberp_coa_types.coa_type_id as coa_type_id,cberp_coa_types.typename,cberp_coa_types.coa_header_id,cberp_coa_headers.coa_header');
        $this->db->from('cberp_accounts');
        $this->db->join('cberp_coa_types', 'cberp_coa_types.coa_type_id = cberp_accounts.account_type_id','left');
        $this->db->join('cberp_coa_headers', 'cberp_coa_types.coa_header_id = cberp_coa_headers.coa_header_id','left');
        // $this->db->where('status', 'Active');
        // $this->db->order_by('typename', 'ASC'); 
        if($type=='income')
        {
            $this->db->where_in('cberp_coa_headers.coa_header', ['Income', 'Assets','Liabilities']); 
        }
        else{
            $this->db->where_in('cberp_coa_headers.coa_header', ['Expenses', 'Assets','Liabilities']); 
        }

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function is_code_taken($code)
    {
        $this->db->select('acn');
        $this->db->from('cberp_accounts');
        $this->db->like('acn', $code);
        $query = $this->db->get();        
        return $query->num_rows() > 0;
    }
    public function update_account_balance($code,$old_opening_balance,$opening_balance,$equitycode)
    {

        $this->db->set('lastbal', 'lastbal - ' .$old_opening_balance, FALSE);
        $this->db->where('acn', $code);
        $this->db->update('cberp_accounts');
        $this->db->set('lastbal', 'lastbal + ' .$opening_balance, FALSE);
        $this->db->where('acn', $code);
        $this->db->update('cberp_accounts');

       
        $this->db->set('lastbal', 'lastbal + ' .$old_opening_balance, FALSE);
        $this->db->where('acn', $equitycode);
        $this->db->update('cberp_accounts');

        $this->db->set('lastbal', 'lastbal - ' .$opening_balance, FALSE);
        $this->db->where('acn', $equitycode);
        $this->db->update('cberp_accounts');
    }

    public function update_balance_transactions($code,$opening_balance,$equitycode)
    {

       $this->db->select('cberp_transactions.transaction_number,cberp_payment_transaction_link.bank_transaction_number');
       $this->db->from('cberp_transactions');
       $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.transaction_number = cberp_transactions.transaction_number');
       $this->db->where('cberp_transactions.acid',$code);
       $this->db->where('cberp_transactions.type','Deposit');
       $this->db->where('cberp_transactions.cat','Opening Balance');
       $query =  $this->db->get();
       if ($query->num_rows() > 0) 
       {
            $transaction_number = $query->row()->transaction_number;
            $bank_transaction_number = $query->row()->bank_transaction_number;
            $bank_data = [
                'acid' => $code,
                'type' => 'Opening',
                'cat' => 'Opening Balance',
                'debit' => $opening_balance,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d')
            ];
            $this->db->where('type', 'Opening');
            $this->db->where('cat', 'Opening Balance');
            $this->db->where('acid', $code);
            $this->db->update('cberp_transactions',$bank_data);
            $equity_data = [
                'acid' => $equitycode,
                'type' => 'Opening',
                'cat' => 'Opening Balance',
                'credit' => $opening_balance,
                'eid' => $this->session->userdata('id'),
                'date' => date('Y-m-d')
            ];
            $this->db->where('type', 'Opening');
            $this->db->where('cat', 'Opening Balance');
            $this->db->where('acid', $equitycode);
            $this->db->update('cberp_transactions',$equity_data);

            $banktrans_data = [
                'trans_type' => 'Opening',
                'trans_amount' => $opening_balance,
                'trans_date' => DATE('Y-m-d')
            ];
            $this->db->where('trans_number', $bank_transaction_number);
            $this->db->update('cberp_bank_transactions',$banktrans_data);
       }
       else{
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

    }
    
}
