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

class Manualjournal_model extends CI_Model
{
    var $table = 'cberp_manual_journals';
    var $column_order = array(null,'cberp_manual_journals.journal_number','cberp_manual_journals.journal_date','cberp_manual_journals.created_dt','cberp_employees.name','cberp_manual_journals.journal_amount', null);
    var $column_search = array('cberp_manual_journals.journal_number','cberp_manual_journals.journal_date','cberp_manual_journals.created_dt','cberp_employees.name','cberp_manual_journals.journal_amount');
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    public function load_banking_headers()
    {
        $this->db->from('cberp_bank_transtype');
        $this->db->where('status', 'Active');
        $this->db->order_by('transtype_name', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    private function _get_datatables_query()
    {

        $this->db->select('cberp_manual_journals.*,cberp_employees.name');
        $this->db->from('cberp_manual_journals');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_manual_journals.created_by', 'inner');
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($dategap="")
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();       
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
		// $this->db->where('cberp_coa_types.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function load_category_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_manual_journals');
        $this->db->where('id', $id);   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function load_basis_list()
    {
        $this->db->select('*');
        $this->db->from('cberp_basis');
        $this->db->where('status', 'Active');   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function journal_master_by_number($transaction_number){
        $this->db->select('cberp_manual_journals.*, cberp_employees.name');
        $this->db->from('cberp_manual_journals');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_manual_journals.created_by', 'inner');
        $this->db->where('cberp_manual_journals.transaction_number', $transaction_number);
        $query = $this->db->get();

        return $query->row_array();

    } 

    public function journal_master_by_journal($journal_number){
        $this->db->select('cberp_manual_journals.*, cberp_employees.name');
        $this->db->from('cberp_manual_journals');
        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_manual_journals.created_by', 'inner');
        $this->db->where('cberp_manual_journals.journal_number', $journal_number);
        $query = $this->db->get();

        return $query->row_array();

    } 

    public function journal_items_by_number($journal_number)
    {
        $this->db->select('cberp_manual_journal_items.*, cberp_accounts.acn, cberp_accounts.holder');
        $this->db->from('cberp_manual_journal_items');
        $this->db->join('cberp_accounts', 'cberp_accounts.acn = cberp_manual_journal_items.account_id', 'inner');
        $this->db->where('cberp_manual_journal_items.journal_number', $journal_number);
        $query = $this->db->get();
        return $query->result_array();

    }


}
