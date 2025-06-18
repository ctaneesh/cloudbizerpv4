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

class Enquiry_model extends CI_Model
{
    var $table = 'customer_enquiry';
    var $column_order = array(null, 'cberp_customer_leads.customer_lead_number','cberp_customer_leads.lead_number', 'cberp_customer_leads.email_contents', 'cberp_customer_leads.created_date','cberp_customer_leads.status', null);
    var $column_search = array('cberp_customer_leads.customer_lead_number', 'cberp_customer_leads.lead_number', 'cberp_customer_leads.email_contents', 'cberp_customer_leads.created_date');
    var $order = array('cberp_customer_leads.lead_id' => 'desc');

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

    public function enquiry_details($lead_id)
    {

        $this->db->select('cberp_customer_leads.*');
        $this->db->from($this->table);
        $this->db->where('cberp_customer_leads.id', $lead_id);
		$this->db->where('customer_enquiry.customer_id', $this->session->userdata('user_details')[0]->cid);
        $query = $this->db->get();
        return $query->row_array();

    }

    public function enquiry_products($lead_id)
    {

        $this->db->select('cberp_customer_lead_items.*,cberp_product_description.product_name,cberp_products.product_code');
        $this->db->from('cberp_customer_lead_items');
        $this->db->where('lead_id', $lead_id);        
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_customer_lead_items.product_code', 'left');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_customer_lead_items.product_code', 'left');
        $query = $this->db->get();
        return $query->result_array();

    }





    private function _get_datatables_query()
    {
        $this->db->select('cberp_customer_leads.*,cberp_customer_leads.customer_lead_number,cberp_customer_leads.lead_number,cberp_customer_leads.enquiry_status');
        $this->db->from('cberp_customer_leads');
        
        $this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid); 
       
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
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();       
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('cberp_customer_leads');
		$this->db->where('cberp_customer_leads.customer_id', $this->session->userdata('user_details')[0]->cid);
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

    //auto search
    public function autoSearch($name)
    {
         $query = $this->db->query("SELECT pid,product_name,product_price FROM cberp_products WHERE UPPER(product_name) LIKE '" . strtoupper($name) . "%'");

        $result = $query->result_array();

        return $result;
    }
    public function lead_number()
    {
        $this->db->select("MAX(CAST(SUBSTRING_INDEX(lead_number, '/', -1) AS UNSIGNED)) + 1 AS next_id");
        $this->db->from('cberp_customer_leads');
        $query = $this->db->get();
        $next_id = $query->row()->next_id;
    
        if (is_null($next_id) || $next_id == 0) {
            $next_id = 1;
        }
        return $next_id;
    }
    
    public function loadcustomer_byid($id)
    {
        $this->db->select('name,phone,address,email');
        $this->db->from('cberp_customers');
        $this->db->where('customer_id', $id);   
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }
    public function product_data($product_code,$quantity,$lead_id)
    {
        $general_productlist = [];
        $this->db->select('cberp_products.product_price,cberp_products.tax_rate,cberp_products.discount_rate,cberp_products.maximum_discount_rate,cberp_products.unit,cberp_product_pricing.minimum_price');
        $this->db->from('cberp_products');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code','left');
        $this->db->where('cberp_products.product_code', $product_code);   
        $query = $this->db->get();
        // die($this->db->last_query());
        $productdata =  $query->row_array();
        $price = $productdata['product_price'];
        $discountperc = $productdata['discount_rate'];
        $taxperc = $productdata['tax_rate'];
        if($discountperc>0){
            $discount = ((($price*$discountperc)/100))*$quantity;
        }
        else{
            $discount =0;
        }   
        if($taxperc>0){
            $tax = (((($price*$taxperc)/100))*$quantity);
        }
        else{
            $tax =0;
        }   
        $subtotal = ((($price*$quantity)+$tax)-$discount);
        $general_productlist['lead_id'] = $lead_id;
        $general_productlist['product_code'] = $product_code;
        $general_productlist['price'] = $price;
        $general_productlist['tax'] = $productdata['tax_rate'];
        $general_productlist['unit'] = $productdata['unit'];
        $general_productlist['discount'] = $productdata['discount_rate'];
        $general_productlist['maximum_discount_rate'] = $productdata['maximum_discount_rate'];
        $general_productlist['total_discount'] = $discount;
        $general_productlist['total_tax'] = $tax;
        $general_productlist['subtotal'] = $subtotal;
        $general_productlist['quantity'] = $quantity;
        $general_productlist['lowest_price'] = $productdata['minimum_price'];
        $this->db->insert('cberp_customer_lead_items', $general_productlist);
        return $subtotal;
    }

}
