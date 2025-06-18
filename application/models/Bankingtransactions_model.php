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

class Bankingtransactions_model extends CI_Model
{
    var $table = 'cberp_bank_transactions';
    var $column_order = array(null,'cberp_bank_transactions.trans_ref_number','cberp_bank_transactions.trans_number','cberp_bank_transactions.trans_date','cberp_bank_transactions.trans_type','cberp_bank_transactions.trans_category_id','cberp_bank_transactions.trans_chart_of_account_id','cberp_bank_transactions.trans_amount', null);
    var $column_search = array('cberp_bank_transactions.trans_ref_number','cberp_bank_transactions.trans_number','cberp_bank_transactions.trans_date','cberp_bank_transactions.trans_type','cberp_bank_transactions.trans_category_id','cberp_bank_transactions.trans_chart_of_account_id','cberp_bank_transactions.trans_amount');
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


        
        $this->db->select('cberp_bank_transactions.*, cberp_bank_transcategory.transcat_name, cberp_bank_ac.name,cberp_bank_ac.acn as accountnumber,cberp_customers.name as customer,cberp_suppliers.name as supplier');
        $this->db->from('cberp_bank_transactions');
        $this->db->join('cberp_bank_transcategory', 'cberp_bank_transcategory.transcat_id = cberp_bank_transactions.trans_category_id', 'left');
        // $this->db->join('cberp_bank_transtype', 'cberp_bank_transtype.transtype_id = cberp_bank_transcategory.transtype_id', 'inner');
        $this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_bank_transactions.trans_account_id');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id', 'left');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_account_id', 'trans_supplier_id','left');
        if($this->input->post('trans_type')!='All')
        {
            $trans_type = $this->input->post('trans_type');
            $this->db->where('cberp_bank_transactions.trans_type', $trans_type);
        }
        
        // $this->db->where('cberp_bank_transtype.status', 'Active');
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
        $this->db->from('cberp_bank_transcategory');
        $this->db->where('id', $id);   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function load_category_by_type($type)
    {
        $this->db->select('cberp_bank_transcategory.transcat_id,cberp_bank_transcategory.transcat_name');
        $this->db->from('cberp_bank_transcategory');
        $this->db->join('cberp_bank_transtype', 'cberp_bank_transtype.transtype_id = cberp_bank_transcategory.transtype_id', 'inner');
        $this->db->where('cberp_bank_transtype.transtype_name', $type);   
        $this->db->where('cberp_bank_transtype.status', 'Active');   
        $this->db->where('cberp_bank_transcategory.status', 'Active');   
        $this->db->order_by('cberp_bank_transcategory.transcat_name', 'ASC');   
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_new_transnumber()
    {
        $this->db->select('id');
        $this->db->from('cberp_bank_transactions');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->id+1;
        } else {
            return 1;
        }
    }

    public function bank_transaction_list($code)
    {
        $this->db->select('
            cberp_bank_transactions.*,
            cberp_invoices.id AS invoiceid,
            cberp_invoices.invoice_number AS invoicenumber,
            cberp_customers.name AS customername,
            cberp_purchase_receipts.srv AS purchasereceipt,
            cberp_purchase_receipts.id AS purchase_id,
        ');
        $this->db->from('cberp_bank_transactions');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.bank_transaction_number = cberp_bank_transactions.trans_number', 'left');
        $this->db->join('cberp_invoices', 'cberp_invoices.invoice_number = cberp_payment_transaction_link.trans_type_number', 'left');

        $this->db->join('cberp_purchase_receipts', 'cberp_payment_transaction_link.trans_type_number = cberp_purchase_receipts.srv', 'left');


        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id', 'left');
        $this->db->where('cberp_bank_transactions.trans_account_id', $code);
        $this->db->where('cberp_bank_transactions.trans_type !=', 'Deposit');
        // Execute the query
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    public function bank_transaction_details_by_reference($trans_ref_number)
    {
        $this->db->select('
            cberp_bank_transactions.*
        ');
        $this->db->from('cberp_bank_transactions');
        $this->db->where('cberp_bank_transactions.trans_ref_number', $trans_ref_number);
        // Execute the query
        $query = $this->db->get();

        $result = $query->row_array();
        return $result;
    }
    public function get_trans_number_by_bank_trans_number($bank_trans_ref_number)
    {
        $this->db->select('
            transaction_number
        ');
        $this->db->from('cberp_payment_transaction_link');
        $this->db->where('bank_transaction_number', $bank_trans_ref_number);
        // Execute the query
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['transaction_number'];
    }
    public function bank_transaction_summary_by_code($trans_account_id)
    {
        $this->db->select("
        SUM(CASE WHEN trans_type = 'Income' THEN trans_amount ELSE 0 END) AS total_income,
        SUM(CASE WHEN trans_type = 'Expense' THEN trans_amount ELSE 0 END) AS total_expense,
        SUM(CASE WHEN trans_type = 'Income' THEN trans_amount ELSE 0 END) - 
        SUM(CASE WHEN trans_type = 'Expense' THEN trans_amount ELSE 0 END) AS current_balance
        ");
        $this->db->from('cberp_bank_transactions');
        $this->db->where('trans_account_id', $trans_account_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function bank_transaction_summary($trans_account_id)
    {
        $this->db->select("
        SUM(CASE WHEN trans_type = 'Income' THEN trans_amount ELSE 0 END) AS total_income,
        SUM(CASE WHEN trans_type = 'Expense' THEN trans_amount ELSE 0 END) AS total_expense,
        SUM(CASE WHEN trans_type = 'Income' THEN trans_amount ELSE 0 END) - 
        SUM(CASE WHEN trans_type = 'Expense' THEN trans_amount ELSE 0 END) AS current_balance
        ");
        $this->db->from('cberp_bank_transactions');
        $query = $this->db->get();
        return $query->row_array();
    }


    public function export_data($trans_type)
    {


        $this->db->select('cberp_bank_transactions.trans_ref_number, cberp_bank_transactions.trans_number, cberp_bank_transactions.trans_date,cberp_bank_transactions.trans_type,cberp_bank_transcategory.transcat_name,cberp_bank_ac.acn as accountnumber,cberp_customers.name as customer,cberp_suppliers.name as supplier,cberp_bank_transactions.trans_amount');
        $this->db->from('cberp_bank_transactions');
        $this->db->join('cberp_bank_transcategory', 'cberp_bank_transcategory.transcat_id = cberp_bank_transactions.trans_category_id', 'left');
        $this->db->join('cberp_bank_ac', 'cberp_bank_ac.code = cberp_bank_transactions.trans_account_id');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_bank_transactions.trans_customer_id AND cberp_bank_transactions.trans_customer_id IS NOT NULL', 'left');
        $this->db->join('cberp_suppliers', 'cberp_suppliers.supplier_id = cberp_bank_transactions.trans_account_id AND cberp_bank_transactions.trans_supplier_id IS NOT NULL', 'left');
        if($trans_type!='All')
        {
            $this->db->where('cberp_bank_transactions.trans_type', $trans_type);
        }        
        $query = $this->db->get();
        return $query->result_array();
    }
}

