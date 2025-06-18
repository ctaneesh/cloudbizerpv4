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

class Payments_model extends CI_Model
{
    var $table = 'cberp_transactions';
    var $column_order = array(null, 'cberp_invoices.invoice_number', 'cberp_invoices.invoicedate', 'cberp_invoices.total','cberp_transactions.date','cberp_invoices.pamnt',null, null);
    var $column_search = array('cberp_invoices.invoice_number', 'cberp_invoices.invoicedate', 'cberp_invoices.total','cberp_transactions.date','cberp_invoices.pamnt');
    var $order = array('cberp_transactions.id' => 'desc');

    //cberp_invoices.id AS invoiceid,cberp_invoices.invoice_number,cberp_invoices.total, cberp_invoices.invoicedate, cberp_invoices.pamnt, cberp_transactions.debit as singleamount,cberp_transactions.date as paiddate
    public function __construct()
    {
        parent::__construct();
    }


    public function invoice_details($id)
    {

        $this->db->select('cberp_invoices.*,cberp_customers.*,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('cberp_invoices.tid', $id);
        $this->db->join('cberp_customers', 'cberp_invoices.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_invoices.term', 'left');
        $query = $this->db->get();
        return $query->row_array();

    }
        public function gateway_list($enable = '')
    {

        $this->db->from('cberp_gateways');
        if ($enable == 'Yes') {
            $this->db->where('enable', 'Yes');
        }
        $this->db->where('id<=', 6);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function invoice_products($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_invoice_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function invoice_transactions($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_transactions');
        $this->db->where('tid', $id);
        $this->db->where('ext', 0);
        $query = $this->db->get();
        return $query->result_array();

    }


    private function _get_datatables_query()
    {

       
        $this->db->select('cberp_invoices.id AS invoiceid,cberp_invoices.invoice_number,cberp_invoices.total, cberp_invoices.invoicedate, cberp_invoices.pamnt, cberp_transactions.debit as singleamount,cberp_transactions.date as paiddate');
        $this->db->from('cberp_transactions');
        $this->db->join('cberp_payment_transaction_link', 'cberp_payment_transaction_link.transaction_number = cberp_transactions.transaction_number');
        $this->db->join('cberp_invoices', 'cberp_invoices.invoice_number = cberp_payment_transaction_link.trans_type_number');
        $this->db->where('cberp_transactions.payerid', $this->session->userdata('user_details')[0]->cid);
      
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

    function get_datatables()
    {
        $this->_get_datatables_query();
        // $this->db->where('cberp_transactions.payerid', $this->session->userdata('user_details')[0]->cid);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();

        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        // $this->db->where('cberp_transactions.payerid', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        // $this->db->where('cberp_transactions.payerid', $this->session->userdata('user_details')[0]->cid);
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('cberp_terms');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee($id)
    {
        $this->db->select('cberp_employees.name,cberp_employees.sign,cberp_users.roleid');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function balance($id)
    {

        $this->db->select('balance');
        $this->db->from('cberp_customers');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result= $query->row_array();
        return $result['balance'];

    }

    public function activity($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_metadata');
        $this->db->where('type', 21);
        $this->db->where('rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


}
