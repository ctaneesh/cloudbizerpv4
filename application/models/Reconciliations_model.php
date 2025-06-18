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

class Reconciliations_model extends CI_Model
{
    var $table = 'cberp_reconciliations';
    var $column_order = array(null,'cberp_reconciliations.reconciliations_id','cberp_reconciliations.created_dt','cberp_bank_ac.name','cberp_reconciliations.date_from','cberp_reconciliations.date_to','cberp_reconciliations.opening_balance','cberp_reconciliations.closing_balance', null);
    var $column_search = array('reconciliations_id.reconciliations_id','cberp_reconciliations.created_dt','cberp_bank_ac.name','cberp_reconciliations.date_from','cberp_reconciliations.date_to','cberp_reconciliations.opening_balance','cberp_reconciliations.closing_balance');
    var $order = array('cberp_reconciliations.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }


    private function _get_datatables_query()
    {

        $this->db->select('cberp_reconciliations.*,cberp_bank_ac.name');
        $this->db->from('cberp_reconciliations');
        $this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_reconciliations.account_id');
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

    public function transaction_list($datefrom, $dateto, $account_id)
    {
        $this->db->select([
            'cberp_bank_transactions.trans_ref_number',
            'cberp_bank_transactions.trans_date',
            'cberp_bank_transactions.trans_type',
            'cberp_bank_transactions.trans_account_id',
            'cberp_bank_transactions.trans_amount',
            'cberp_bank_ac.opening_balance',
            'cberp_transactions.credit',
            'cberp_transactions.debit',
            'cberp_suppliers.name as supplier',
            'cberp_customers.name as customer',
            "(CASE WHEN cberp_reconciliations_items.trans_ref_number IS NOT NULL THEN 1 ELSE 0 END) AS flag"
        ]);
        $this->db->from('cberp_bank_transactions');
        $this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_bank_transactions.trans_account_id');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id', 'left');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_supplier_id', 'left');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number');
        $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_payment_transaction_link.transaction_number');

        $this->db->join('cberp_reconciliations_items', 'cberp_reconciliations_items.trans_ref_number = cberp_bank_transactions.trans_ref_number', 'left');

        // Date filter
        $this->db->where('cberp_bank_transactions.trans_date >=', $datefrom . ' 00:00:00');
        $this->db->where('cberp_bank_transactions.trans_date <=', $dateto . ' 23:59:59');

        // Account ID filter
        $this->db->where('cberp_bank_transactions.trans_account_id', $account_id);
        $this->db->where('cberp_transactions.acid', $account_id);

        $query = $this->db->get();

        // Uncomment to debug the generated query:
        // die($this->db->last_query());

        return $query->result_array();
    }
    public function transaction_list_edit($datefrom, $dateto, $account_id, $reconciliations_id)
{
    $this->db->select([
        'cberp_bank_transactions.trans_ref_number',
        'cberp_bank_transactions.trans_date',
        'cberp_bank_transactions.trans_type',
        'cberp_bank_transactions.trans_account_id',
        'cberp_bank_transactions.trans_amount',
        'cberp_bank_ac.opening_balance',
        'cberp_transactions.credit',
        'cberp_transactions.debit',
        'cberp_suppliers.name as supplier',
        'cberp_customers.name as customer',
        "(CASE WHEN cberp_reconciliations_items.trans_ref_number IS NOT NULL AND cberp_reconciliations_items.reconciliations_id = '$reconciliations_id' THEN 1 ELSE 0 END) AS flag"
    ]);
    
    $this->db->from('cberp_bank_transactions');
    $this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_bank_transactions.trans_account_id');
    $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id', 'left');
    $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_supplier_id', 'left');
    $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number');
    $this->db->join('cberp_transactions', 'cberp_transactions.transaction_number = cberp_payment_transaction_link.transaction_number');


    $this->db->join('cberp_reconciliations_items', 'cberp_reconciliations_items.trans_ref_number = cberp_bank_transactions.trans_ref_number', 'left');

    $this->db->join('cberp_reconciliations', 'cberp_reconciliations.reconciliations_id = cberp_reconciliations_items.reconciliations_id', 'left');

    // Date filter
    $this->db->where('cberp_bank_transactions.trans_date >=', $datefrom . ' 00:00:00');
    $this->db->where('cberp_bank_transactions.trans_date <=', $dateto . ' 23:59:59');

    // Account ID filter
    $this->db->where('cberp_bank_transactions.trans_account_id', $account_id);
    $this->db->where('cberp_transactions.acid', $account_id);

    $query = $this->db->get();

    // Uncomment to debug the generated query:
    // die($this->db->last_query());

    return $query->result_array();
}


public function details_by_id($reconciliations_id)
{
    $this->db->select('cberp_reconciliations.*');
    $this->db->from('cberp_reconciliations');
    $this->db->where('reconciliations_id', $reconciliations_id);
    $query = $this->db->get();
    return $query->row_array();
}

public function get_total_closing_balance()
{
    $this->db->select_sum('closing_balance');
    $query = $this->db->get('cberp_reconciliations');

    // Fetch the result
    $result = $query->row_array();
    
    return $result['closing_balance'] ?? 0; // Return the sum or 0 if null
}

}