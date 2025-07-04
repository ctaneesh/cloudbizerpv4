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

class Quote_model extends CI_Model
{
    var $table = 'cberp_quotes';
    var $column_order = array(null, 'quote_number', 'name', 'quote_date', 'total', 'status', null);
    var $column_search = array('quote_number', 'name', 'quote_date', 'total');
    var $order = array('created_date' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastquote()
    {
        $this->db->select('tid');
        $this->db->from($this->table);
        $this->db->order_by('tid', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->tid;
        } else {
            return 1000;
        }
    }

    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function quote_details($quote_number)
    {

        $this->db->select('cberp_quotes.*,cberp_quotes.quote_number,cberp_customers.*,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_country.name as country');
        $this->db->from($this->table);
        $this->db->where('cberp_quotes.quote_number', $quote_number);
		$this->db->where('cberp_quotes.customer_id', $this->session->userdata('user_details')[0]->cid);
        $this->db->join('cberp_customers', 'cberp_quotes.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_country', 'cberp_country.id = cberp_customers.country', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_quotes.payment_term', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }

   
    public function quote_products($quote_number)
    {
        $this->db->select('cberp_quotes_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity, cberp_products.product_code,cberp_product_description.product_name');
        $this->db->from('cberp_quotes_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_quotes_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_quotes_items.product_code');
        $this->db->where('cberp_quotes_items.quote_number', $quote_number);
        $query = $this->db->get();

        return $query->result_array();

    }




    private function _get_datatables_query()
    {
        $this->db->select('cberp_quotes.*,cberp_customers.name');
        $this->db->from($this->table);
        $this->db->where('cberp_quotes.customer_id', $this->session->userdata('user_details')[0]->cid);
        // $this->db->where('cberp_quotes.status', 'accepted');
        // $this->db->where('cberp_quotes.approvalflg', 1);
        $this->db->join('cberp_customers', 'cberp_quotes.customer_id=cberp_customers.customer_id', 'left');

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
		// $this->db->where('cberp_quotes.customer_id', $this->session->userdata('user_details')[0]->cid);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
		$this->db->where('cberp_quotes.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
		$this->db->where('cberp_quotes.customer_id', $this->session->userdata('user_details')[0]->cid);
        return $this->db->count_all_results();
    }

     public function update_status($id)
    {
        $this->db->set('status', 'customer_approved');
                $this->db->where('id', $id);
               return $this->db->update('cberp_quotes');
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
    public function update_qoute($data,$condition,$tablename){
        $this->db->where($condition);
        $this->db->update($tablename, $data);
    }
    public function insert_quoteai($data,$tablename){
        $this->db->insert($tablename, $data);
    }
    public function convert($id)
    {

        $invoice = $this->quote_details($id);
        $products = $this->quote_products($id);
        $this->db->trans_start();

        $this->db->select('tid');
        $this->db->from('cberp_invoices');
        $this->db->order_by('tid', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $iid = $query->row()->tid + 1;
        } else {
            $iid = 1000;
        }
        $productlist = array();
        $prodindex = 0;

        foreach ($products as $row) {

            $amt = $row['qty'];

            $data = array(
                'tid' => $iid,
                'pid' => $row['pid'],
                'product' => $row['product'],
                'qty' => $amt,
                'price' => $row['price'],
                'tax' => $row['tax'],
                'discount' => $row['discount'],
                'subtotal' => $row['subtotal'],
                'totaltax' => $row['totaltax'],
                'totaldiscount' => $row['totaldiscount']
            );

            $productlist[$prodindex] = $data;
            $prodindex++;

            $this->db->set('qty', "qty-$amt", FALSE);
            $this->db->where('pid', $row['pid']);
            $this->db->update('cberp_products');
        }


        $this->db->insert_batch('cberp_invoice_items', $productlist);


        $data = array('tid' => $iid, 'invoicedate' => $invoice['invoicedate'], 'invoiceduedate' => $invoice['invoicedate'], 'subtotal' => $invoice['invoicedate'], 'shipping' => $invoice['shipping'], 'discount' => $invoice['discount'], 'tax' => $invoice['tax'], 'total' => $invoice['total'], 'notes' => $invoice['notes'], 'csd' => $invoice['csd'], 'eid' => $invoice['eid'], 'items' => $invoice['items'], 'taxstatus' => $invoice['taxstatus'], 'discstatus' => $invoice['discstatus'], 'format_discount' => $invoice['format_discount'], 'refer' => $invoice['refer'], 'term' => $invoice['term']);

        $this->db->insert('cberp_invoices', $data);

        if ($this->db->trans_complete()) {
            return true;
        } else {
            return false;
        }


    }


}
