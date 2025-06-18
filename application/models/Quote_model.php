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
    var $column_order = array(null, 'cberp_quotes.quote_number', 'cberp_customers.name','cberp_customers.customer_id', 'cberp_quotes.quote_date','cberp_quotes.due_date', 'cberp_quotes.total','cberp_quotes.reference','cberp_quotes.customer_reference_number','cberp_employees.name', 'cberp_quotes.status', null);
    var $column_search = array('cberp_quotes.quote_number', 'cberp_customers.name', 'cberp_quotes.quote_date', 'cberp_quotes.total','cberp_employees.name','cberp_quotes.status');
    var $order = array('cberp_quotes.created_date' => 'desc');

    // $this->db->select('cberp_quotes.id,cberp_quotes.tid,cberp_quotes.quote_date,cberp_quotes.invoiceduedate,cberp_quotes.invoiceduedate,cberp_quotes.total,cberp_quotes.status,cberp_quotes.convertflg,cberp_quotes.approvalflg,cberp_quotes.refer,cberp_quotes.customer_reference_number,cberp_customers.name,cberp_customers.customer_id as customerid,cberp_quotes.prepared_flg,cberp_quotes.approved_dt,cberp_employees.name as employeename');

    public function __construct()
    {
        parent::__construct();
    }



    public function lastquote()
    {
        $this->db->select('quote_number');
        $this->db->from($this->table);
        $this->db->where("quote_number IS NOT NULL");
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_quote_number = $query->row()->quote_number;
            $parts = explode('/', $last_quote_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $next_number;
        } else {
            return '1001';
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

        $this->db->select('cberp_quotes.*,cberp_quotes.status as quotestatus,SUM(cberp_quotes.shipping + cberp_quotes.shipping_tax) AS shipping,cberp_customers.*,cberp_quotes.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('cberp_quotes.quote_number', $quote_number);
        $this->db->join('cberp_customers', 'cberp_quotes.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_quotes.payment_term', 'left');
        $query = $this->db->get();
        return $query->row_array();

    }

    public function quote_products($quote_number)
    {
        $this->db->select('cberp_quotes_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity,cberp_product_description.product_name,cberp_products.maximum_discount_rate,cberp_product_pricing.minimum_price as lowest_price');
        $this->db->from('cberp_quotes_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_quotes_items.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code');
        $this->db->where('cberp_quotes_items.quote_number', $quote_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();

    }

    public function quote_product_by_id($quote_number)
    {
        $this->db->select('cberp_quotes.*');
        $this->db->from('cberp_quotes');
        $this->db->where('quote_number', $quote_number);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function quote_delete($id)
    {
        $this->db->trans_start();
          if ($this->aauth->get_user()->loc) {
                $res = $this->db->delete('cberp_quotes', array('id' => $id, 'loc' => $this->aauth->get_user()->loc));
        }
        else {
            if (BDATA) {
                    $res = $this->db->delete('cberp_quotes', array('id' => $id));

            } else {
                    $res = $this->db->delete('cberp_quotes', array('id' => $id,'loc' => 0));
            }
        }
        if ($this->db->affected_rows()) $this->db->delete('cberp_quotes_items', array('tid' => $id));
        if ($this->db->trans_complete()) {
            return true;
        } else {
            return false;
        }
    }


    private function _get_datatables_query($eid)
    {
        $filter_status = !empty($this->input->post('filter_status')) ?$this->input->post('filter_status') : "";

        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";
       
        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";


        $this->db->select('cberp_quotes.quote_number,cberp_quotes.quote_date,cberp_quotes.due_date,cberp_quotes.total,cberp_quotes.status,cberp_quotes.reference,cberp_quotes.customer_reference_number,cberp_customers.name,cberp_customers.customer_id as customerid,cberp_quotes.prepared_flag,cberp_employees.name as employeename');
        $this->db->from($this->table);
        // if ($eid) $this->db->where('cberp_quotes.eid', $eid);
        //         if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }
        // elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }
       
        if ($this->input->post('start_date') && $this->input->post('end_date'))
        {
            $start_date = datefordatabase($this->input->post('start_date'));
            $end_date = datefordatabase($this->input->post('end_date'));
            $this->db->where("DATE(cberp_quotes.quote_date) BETWEEN '$start_date' AND '$end_date'");
        }

        $this->db->join('cberp_customers', 'cberp_quotes.customer_id=cberp_customers.customer_id', 'left');

        $this->db->join('cberp_employees', 'cberp_employees.id = cberp_quotes.created_by', 'left');
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        //filter search section starts
        if (!empty($filter_status)) {
            $this->db->where_in('cberp_quotes.status', $filter_status);
        }
        if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
            $this->db->where("cberp_quotes.due_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }

        if(!empty($filter_customer)){
            $this->db->where_in("cberp_quotes.customer_id",$filter_customer);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_quotes.total BETWEEN $filter_price_from AND $filter_price_to");
        }
        //filter search section ends

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        else{
            $order = array('cberp_quotes.created_date' => 'desc');
        }
    }

    function get_datatables($eid)
    {
        $this->_get_datatables_query($eid);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }           

        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($eid)
    {
        $this->_get_datatables_query($eid);
    // if ($this->aauth->get_user()->loc) {
    //         $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
    //     }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($eid)
    {
        $this->db->select('cberp_quotes.id');
        $this->db->from($this->table);

        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        if ($eid) $this->db->where('cberp_quotes.eid', $eid);
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('cberp_terms');
        $this->db->where('type', 2);
        $this->db->or_where('type', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee($id)
    {
        $this->db->select('cberp_employees.name,cberp_employees.sign,cberp_users.roleid,cberp_users.email,cberp_employees.phone');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function convert($id)
    {

        $invoice = $this->quote_details($id);
        $products = $this->quote_products($id);
        $this->db->trans_start();
        $this->db->select('tid');
        $this->db->from('cberp_invoices');
        $this->db->where('i_class', 0);
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
        if($invoice['loc']==$this->aauth->get_user()->loc) {
            $data = array('tid' => $iid, 'invoicedate' => $invoice['invoicedate'], 'invoiceduedate' => $invoice['invoicedate'], 'subtotal' => $invoice['invoicedate'], 'shipping' => $invoice['shipping'], 'discount' => $invoice['discount'], 'tax' => $invoice['tax'], 'total' => $invoice['total'], 'notes' => $invoice['notes'], 'csd' => $invoice['csd'], 'eid' => $invoice['eid'], 'items' => $invoice['items'], 'taxstatus' => $invoice['taxstatus'], 'discstatus' => $invoice['discstatus'], 'format_discount' => $invoice['format_discount'], 'refer' => $invoice['refer'], 'term' => $invoice['term'],'multi' => $invoice['multi'], 'loc' => $invoice['loc']);
            $this->db->insert('cberp_invoices', $data);
            $iid = $this->db->insert_id();
            foreach ($products as $row) {
                $amt = $row['qty'];
                $data = array(
                    'tid' => $iid,
                    'pid' => $row['pid'],
                    'product' => $row['product'],
                    'code' => $row['code'],
                    'qty' => $amt,
                    'price' => $row['price'],
                    'tax' => $row['tax'],
                    'discount' => $row['discount'],
                    'subtotal' => $row['subtotal'],
                    'totaltax' => $row['totaltax'],
                    'totaldiscount' => $row['totaldiscount'],
                    'product_des' => $row['product_des'],
                    'unit' => $row['unit']
                );
                $productlist[$prodindex] = $data;
                $prodindex++;
                $this->db->set('qty', "qty-$amt", FALSE);
                $this->db->where('pid', $row['pid']);
                $this->db->update('cberp_products');
            }


            $this->db->insert_batch('cberp_invoice_items', $productlist);


            //profit calculation
            $t_profit = 0;
            $this->db->select('cberp_invoice_items.pid, cberp_invoice_items.price, cberp_invoice_items.qty, cberp_products.product_cost');
            $this->db->from('cberp_invoice_items');
            $this->db->join('cberp_products', 'cberp_products.pid = cberp_invoice_items.pid', 'left');
            $this->db->where('cberp_invoice_items.tid', $iid);
            $query = $this->db->get();
            $pids = $query->result_array();
            foreach ($pids as $profit) {
                $t_cost = $profit['product_cost'] * $profit['qty'];
                $s_cost = $profit['price'] * $profit['qty'];
                $t_profit += $s_cost - $t_cost;
            }
            $data = array('type' => 9, 'rid' => $iid, 'col1' => rev_amountExchange_s($t_profit, $invoice['multi'], $this->aauth->get_user()->loc), 'd_date' => $invoice['invoicedate']);

            $this->db->insert('cberp_metadata', $data);

            if ($this->db->trans_complete()) {
                $this->db->set('status', 'accepted');
                $this->db->where('id', $id);
                $this->db->update('cberp_quotes');
                return true;
            } else {
                return false;
            }
        }else{

                return false;

        }

    }

     public function convert_po($id,$person)
    {

        $invoice = $this->quote_details($id);
        $products = $this->quote_products($id);
        $this->db->trans_start();
        $this->db->select('tid');
        $this->db->from('cberp_purchase_orders');
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
        if($invoice['loc']==$this->aauth->get_user()->loc) {
            $data = array('tid' => $iid, 'invoicedate' => $invoice['invoicedate'], 'invoiceduedate' => $invoice['invoicedate'], 'subtotal' => $invoice['invoicedate'], 'shipping' => $invoice['shipping'], 'discount' => $invoice['discount'], 'tax' => $invoice['tax'], 'total' => $invoice['total'], 'notes' => $invoice['notes'], 'csd' => $person, 'eid' => $invoice['eid'], 'items' => $invoice['items'], 'taxstatus' => $invoice['taxstatus'], 'discstatus' => $invoice['discstatus'], 'format_discount' => $invoice['format_discount'], 'refer' => $invoice['refer'], 'term' => $invoice['term'],'multi' => $invoice['multi'], 'loc' => $invoice['loc']);
            $this->db->insert('cberp_purchase_orders', $data);
            $iid = $this->db->insert_id();
            foreach ($products as $row) {
                $amt = $row['qty'];
                $data = array(
                    'tid' => $iid,
                    'pid' => $row['pid'],
                    'product' => $row['product'],
                    'code' => $row['code'],
                    'qty' => $amt,
                    'price' => $row['price'],
                    'tax' => $row['tax'],
                    'discount' => $row['discount'],
                    'subtotal' => $row['subtotal'],
                    'totaltax' => $row['totaltax'],
                    'totaldiscount' => $row['totaldiscount'],
                    'product_des' => $row['product_des'],
                    'unit' => $row['unit']
                );
                $productlist[$prodindex] = $data;
                $prodindex++;
                $this->db->set('qty', "qty+$amt", FALSE);
                $this->db->where('pid', $row['pid']);
                $this->db->update('cberp_products');
            }


            $this->db->insert_batch('cberp_purchase_order_items', $productlist);




            if ($this->db->trans_complete()) {
                $this->db->set('status', 'accepted');
                $this->db->where('id', $id);
                $this->db->update('cberp_quotes');
                return true;
            } else {
                return false;
            }
        }else{

                return false;

        }

    }

    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('cberp_currencies');

        $query = $this->db->get();
        return $query->result_array();

    }

    public function currency_d($id)
    {
        $this->db->select('*');
        $this->db->from('cberp_currencies');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function meta_insert($id, $type, $meta_data)
    {

        $data = array('type' => $type, 'rid' => $id, 'col1' => $meta_data);
        if ($id) {
            return $this->db->insert('cberp_metadata', $data);
        } else {
            return 0;
        }
    }

    public function attach($id)
    {
        $this->db->select('cberp_metadata.*');
        $this->db->from('cberp_metadata');
        $this->db->where('cberp_metadata.type', 2);
        $this->db->where('cberp_metadata.rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function meta_delete($id, $type, $name)
    {
        if (@unlink(FCPATH . 'userfiles/attach/' . $name)) {
            return $this->db->delete('cberp_metadata', array('rid' => $id, 'type' => $type, 'col1' => $name));
        }
    }
    //sales order 
    public function insert_to_sales_order_items($quote_number,$salesorder_number) {
        $this->db->select('*');
        $this->db->from('cberp_quotes_items');
        $this->db->where('quote_number', $quote_number);
        $query = $this->db->get();
        $data = $query->result_array();        
        if (!empty($data)) {
            foreach ($data as $row) {
                unset($row['quote_number']);
                $row['salesorder_number'] = $salesorder_number;
                $this->db->insert('cberp_sales_orders_items', $row);
                // die($this->db->last_query());
            }
          
        }
    }

    public function quote_product($id)
    {

        $this->db->select('*');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            
            $this->db->where('id', $tid);
            $query = $this->db->get('your_table_name');


            $data['accepted'] = 'accepted';
            $this->db->insert('cberp_sales_orders', $data);
        } else {
            // Handle the case where no data is found
            echo "No data found for the given ID.";
        }

        // $this->db->where('id', $tid);
        // $query = $this->db->get('your_table_name');
        // if ($query->num_rows() > 0) {
        //     echo 'Duplicate found';
        // } else {
        //     echo 'No duplicate found';
        // }

    }
    
    public function salesorder_details($id)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.ship_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        // if($this->session->userdata('repeatsubmit') > 1) {
        //     $this->db->select('cberp_delivery_notes.delevery_note_id as current_delevery_note_id, cberp_delivery_notes.delnote_number as current_delnote_number,cberp_delivery_notes.total_amount as current_total_amount,cberp_delivery_notes.discount as current_discount');
        // }
        $this->db->from("cberp_sales_orders");
        $this->db->join('cberp_customers', 'cberp_sales_orders.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.term', 'left');

        // if($this->session->userdata('repeatsubmit')>1){
        //     $delnote_id = $this->session->userdata("latest_delnote_id");
        //     $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.salesorder_id = cberp_sales_orders.id');            
        //     $this->db->where('cberp_delivery_notes.delevery_note_id', $delnote_id);
        // }
        $this->db->where('cberp_sales_orders.id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }
    public function salesorder_details_draft($id)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.ship_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        if($this->session->userdata('repeatsubmit') > 1) {
            $this->db->select('cberp_delivery_notes.delevery_note_id as current_delevery_note_id, cberp_delivery_notes.delnote_number as current_delnote_number,cberp_delivery_notes.total_amount as current_total_amount,cberp_delivery_notes.discount as current_discount');
        }
        $this->db->from("cberp_sales_orders");
        $this->db->join('cberp_customers', 'cberp_sales_orders.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.term', 'left');

        if($this->session->userdata('repeatsubmit')>1){
            $delnote_id = $this->session->userdata("latest_delnote_id");
            $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.salesorder_id = cberp_sales_orders.id');            
            $this->db->where('cberp_delivery_notes.delevery_note_id', $delnote_id);
        }
        $this->db->where('cberp_sales_orders.id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }
    public function salesorder_products($salesorder_number)
    {

        $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_product_description.product_name as product, cberp_products.alert_quantity, cberp_products.product_code as code, cberp_products.unit, cberp_products.product_price, cberp_product_pricing.minimum_price AS product_lowest_price, cberp_products.maximum_discount_rate AS product_max_discount, cberp_sales_orders_items.delivered_quantity AS deliveredqty, cberp_sales_orders_items.remaining_quantity AS remainingqty, cberp_sales_orders_items.transfered_quantity AS trasferedqty, cberp_sales_orders_items.ordered_quantity AS orderedqty, cberp_customer_lead_items.quantity AS leadqty, cberp_quotes.lead_number AS lead_id, cberp_customer_leads.created_date AS leaddate,cberp_customer_leads.lead_number, cberp_quotes.quote_number AS quote_id,cberp_quotes.quote_number, cberp_quotes.quote_date AS quotedate, cberp_quotes_items.quantity AS quoteqty, (cberp_product_to_store.stock_quantity + cberp_product_to_store.intransit_quantity) AS onhandqty');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_sales_orders_items.product_code');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.salesorder_number = cberp_sales_orders_items.salesorder_number');
        $this->db->join('cberp_quotes', 'cberp_quotes.quote_number = cberp_sales_orders.quote_number');
        $this->db->join('cberp_quotes_items', 'cberp_quotes_items.quote_number = cberp_sales_orders.quote_number AND cberp_sales_orders_items.product_code = cberp_quotes_items.product_code');
        $this->db->join('cberp_customer_leads', 'cberp_customer_leads.lead_number = cberp_quotes.lead_number', 'left');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.lead_number = cberp_quotes.lead_number AND cberp_customer_lead_items.product_code = cberp_quotes_items.product_code', 'left');
        $this->db->join('cberp_product_to_store', 'cberp_product_to_store.store_id = cberp_sales_orders.store_id AND cberp_product_to_store.product_code = cberp_sales_orders_items.product_code', 'left');
        $this->db->where('cberp_sales_orders_items.salesorder_number', $salesorder_number);
        // $this->db->group_by('cberp_sales_orders_items.salesorder_number');
        // Add the conditional logic for `prdstatus` lead_id
        // $this->db->group_start()
        // ->where('cberp_sales_orders.converted_status !=', 1)
        // ->where('cberp_sales_orders_items.prdstatus', '0')
        // ->group_end();
        // $this->db->or_where('cberp_sales_orders.converted_status', 1);

        $query = $this->db->get();
        // die($this->db->last_query());
        // echo $this->session->userdata('repeatsubmit');
        return $query->result_array();

    }
    public function salesorder_products_main_list($id)
    {
        $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.product_name, cberp_products.alert_quantity, cberp_products.product_code, cberp_products.unit, cberp_products.product_price, cberp_product_ai.min_price AS product_lowest_price, cberp_product_ai.max_disrate AS product_max_discount, cberp_sales_orders_items.delivered_qty AS deliveredqty, cberp_sales_orders_items.remaining_qty AS remainingqty, cberp_sales_orders_items.transfered_qty AS trasferedqty, cberp_sales_orders_items.ordered_qty AS orderedqty, cberp_customer_lead_items.qty AS leadqty, cberp_customer_lead_items.id AS lead_id, cberp_customer_leads.created_date AS leaddate, cberp_quotes.id AS quote_id, cberp_quotes.quote_date AS quotedate, cberp_quotes_items.qty AS quoteqty, (cberp_product_to_store.stock_qty + cberp_product_to_store.intransit_qty) AS onhandqty');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_sales_orders_items.pid');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_sales_orders_items.pid');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.id = cberp_sales_orders_items.tid');
        $this->db->join('cberp_quotes', 'cberp_quotes.id = cberp_sales_orders.quote_id', 'left');
        $this->db->join('cberp_quotes_items', 'cberp_quotes_items.tid = cberp_sales_orders.quote_id AND cberp_sales_orders_items.pid = cberp_quotes_items.pid', 'left');
        $this->db->join('cberp_customer_leads', 'cberp_customer_leads.lead_id = cberp_quotes.lead_id', 'left');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.tid = cberp_quotes.lead_id AND cberp_customer_lead_items.pid = cberp_quotes_items.pid', 'left');
        $this->db->join('cberp_product_to_store', 'cberp_product_to_store.store_id = cberp_sales_orders.store_id AND cberp_product_to_store.product_id = cberp_sales_orders_items.pid', 'left');
        $this->db->where('cberp_sales_orders_items.tid', $id);

        // Add the conditional logic for `prdstatus`
        // $this->db->group_start()
        //->where('cberp_sales_orders.converted_status !=', 1)
        // ->where('cberp_sales_orders_items.prdstatus', '0')
        // ->group_end();
        // $this->db->or_where('cberp_sales_orders.converted_status', 1);

        $query = $this->db->get();
        // die($this->db->last_query());
        // echo $this->session->userdata('repeatsubmit');
        return $query->result_array();

    }
    public function salesorder_products_deliverynotes($id)
    {
        $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.product_name, cberp_products.alert_quantity, cberp_products.product_code, cberp_products.unit, cberp_products.product_price, cberp_product_ai.min_price as product_lowest_price, cberp_product_ai.max_disrate as product_max_discount, cberp_sales_orders_items.delivered_qty AS deliveredqty, cberp_sales_orders_items.remaining_qty AS remainingqty, cberp_sales_orders_items.transfered_qty AS trasferedqty, cberp_sales_orders_items.ordered_qty AS orderedqty,cberp_customer_lead_items.qty as leadqty,cberp_customer_lead_items.id as lead_id,cberp_customer_leads.created_date as leaddate, cberp_quotes.id as quote_id, cberp_quotes.quote_date as quotedate, cberp_quotes_items.qty as quoteqty,(cberp_product_to_store.stock_qty + cberp_product_to_store.intransit_qty) AS onhandqty');

        if($this->session->userdata('repeatsubmit')>1){
            $this->db->select('cberp_delivery_note_items.product_qty AS current_product_qty,cberp_delivery_note_items.subtotal AS current_subtotal,cberp_delivery_note_items.totaltax AS current_totaltax,cberp_delivery_note_items.totaldiscount AS current_totaldiscount');
        }
        $this->db->from('cberp_sales_orders_items');        

        $this->db->join('cberp_products', 'cberp_products.pid = cberp_sales_orders_items.pid');

        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_sales_orders_items.pid');

        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.id = cberp_sales_orders_items.tid');

        $this->db->join('cberp_quotes', 'cberp_quotes.id = cberp_sales_orders.quote_id');

        $this->db->join('cberp_quotes_items', 'cberp_quotes_items.tid = cberp_sales_orders.quote_id AND cberp_sales_orders_items.pid = cberp_quotes_items.pid');

        $this->db->join('cberp_customer_leads', 'cberp_customer_leads.lead_id = cberp_quotes.lead_id', 'left');

      

        if($this->session->userdata('repeatsubmit')>1){
            $delnote_id = $this->session->userdata("latest_delnote_id");
            // $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.salesorder_id = cberp_sales_orders.id');            
            $this->db->join('cberp_delivery_note_items', 'cberp_sales_orders_items.pid = cberp_delivery_note_items.product_id ');            
            $this->db->where('cberp_delivery_note_items.delevery_note_id', $delnote_id);
            // $this->db->where('cberp_delivery_notes.delevery_note_id', $delnote_id);
        }

        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.tid = cberp_quotes.lead_id AND cberp_customer_lead_items.pid = cberp_quotes_items.pid', 'left');

        $this->db->join('cberp_product_to_store', 'cberp_product_to_store.store_id = cberp_sales_orders.store_id AND cberp_product_to_store.product_id = cberp_sales_orders_items.pid','left');
        $this->db->where('cberp_sales_orders_items.tid', $id);
        $this->db->where('cberp_sales_orders_items.prdstatus', '0');

        if($this->session->userdata('repeatsubmit') > 1) {
            // $this->db->group_by('cberp_delivery_note_items.product_id');
            // $this->db->group_by('cberp_sales_orders_items.pid');
        }

        $query = $this->db->get();
        // echo $this->session->userdata('repeatsubmit');
        // die($this->db->last_query());
        return $query->result_array();

    }



    public function get_enquiry_items($id)
    {
        $this->db->select('cberp_customer_lead_items.*, cberp_products.product_code AS productcode, cberp_products.product_name AS productname, cberp_products.product_name AS productdes, cberp_products.onhand_quantity AS onhand');
        $this->db->from('cberp_customer_lead_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_customer_lead_items.pid');
        $this->db->where('cberp_customer_lead_items.tid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    // public function update_quote_status($invocieno){
    //     // $this->db->where('id', $invocieno);
    //     // $quotedata =[];
    //     // $quotedata['status'] = 'converted';
    //     // $quotedata['convertflg'] = 1;
    //     // $this->db->update('cberp_quotes', $quotedata);
       
    // }

    public function update_quote_status($quote_id, $salestid, $salesorder_id) {
        // First, check if all prdstatus values are 1
        $prifix72 =  get_prefix_72();
        $prefix = $prifix72['salesorder_prefix'];
        $sql_check = "
            SELECT tq.id
            FROM cberp_quotes tq
            INNER JOIN cberp_quotes_items tqi ON tqi.tid = tq.id
            WHERE tq.id = ?
            GROUP BY tq.id
            HAVING COUNT(CASE WHEN tqi.prdstatus != 1 THEN 1 ELSE NULL END) = 0
        ";
    
        // Execute the check query
        $query = $this->db->query($sql_check, array($quote_id));
        $sql_fetch = "
        SELECT seq_number 
        FROM cberp_quotes 
        WHERE id = ?
        ";
        $query_fetch = $this->db->query($sql_fetch, array($quote_id));
        $row = $query_fetch->row();

        if ($query->num_rows() > 0) {
            $seq_number = ($row->seq_number>0)?$row->seq_number + 1 : 0;
            // If all prdstatus values are 1, update the quote
            $sql_update = "
                UPDATE cberp_quotes
                SET convertflg = 1, sales_tid = ?, salesorder_number = ?, seq_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update, array($salestid, $prefix.$salestid, $seq_number, $quote_id));
    
            $sql_update1 = "
                UPDATE cberp_sales_orders
                SET  salesorder_number = ?, seq_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update1, array($salestid, $seq_number, $salesorder_id));

            $sql_update2 = "
                UPDATE cberp_sales_orders
                SET  salesorder_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update2, array($prefix.$salestid, $salesorder_id));
        } else {
            // If not all prdstatus values are 1
            // Fetch the existing seq_number from cberp_quotes
            
            if ($row) {
                $new_seq_number = $row->seq_number + 1;
                $sql_update = "
                    UPDATE cberp_quotes
                    SET convertflg = 2, sales_tid = ?, seq_number = ?, 
                    salesorder_number = CONCAT(?, '-', ?)
                    WHERE id = ?
                ";
                $this->db->query($sql_update, array($salestid, $new_seq_number, $prefix.$salestid, $new_seq_number, $quote_id));
                $sql_update1 = "
                    UPDATE cberp_sales_orders
                    SET seq_number = ?, 
                    salesorder_number = CONCAT(?, '-', ?)
                    WHERE id = ?
                ";
                $this->db->query($sql_update1, array($new_seq_number, $prefix.$salestid, $new_seq_number, $salesorder_id));

                $sql_update2 = "
                    UPDATE cberp_sales_orders_items
                    SET
                    salesorder_number = CONCAT(?, '-', ?)
                    WHERE tid = ?
                ";
                $this->db->query($sql_update2, array($prefix.$salestid, $new_seq_number, $salesorder_id));
               
            }
        }

        $this->db->select('id,salesorder_number');
        $this->db->from('cberp_sales_orders');
        $this->db->where('quote_id', $quote_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query12 = $this->db->get();
        $resquery = $query12->row_array();
        if(!empty($resquery))
        {

            $sales_id = $resquery['id'];
            $sales_number = $resquery['salesorder_number'];
            // $this->db->select('sales_id,sales_number');
            // $this->db->from('cberp_transaction_tracking');
            // $this->db->where('quote_id', $quote_id);
            // $existing = $this->db->get()->row_array();
            // if(!empty($existing['sales_id']) && !empty($existing['sales_number'])) {
            //     $existing_sales_id = $existing['sales_id'];
            //     $existing_sales_number = $existing['sales_number'];
            //     $sales_id = $existing_sales_id . ',' . $sales_id;
            //     $sales_number = $existing_sales_number . ',' . $sales_number;
            //     // $this->db->where('quote_id', $quote_id);
            //     // $this->db->update('cberp_transaction_tracking',['sales_id'=>$sales_id,'sales_number'=>$sales_number]);
            //     insertion_to_tracking_table('sales_id', $resquery['id'], 'sales_number', $sales_number, 'quote_id', $tid);
            // }
            // else{
            //     $this->db->where('quote_id', $quote_id);
            //     $this->db->update('cberp_transaction_tracking',['sales_id'=>$sales_id,'sales_number'=>$sales_number]);
            //     insertion_to_tracking_table('sales_id', $sales_id, 'sales_number', $sales_number, 'quote_id', $tid);
            // }
            insertion_to_tracking_table('sales_id', $sales_id, 'sales_number', $sales_number, 'quote_id', $quote_id);
            
           
        }


        
    }
    

    public function update_quote_status_for_subitems($quote_id, $salestid, $salesorder_id) {
        $prifix72 =  get_prefix_72();
        $prefix = $prifix72['salesorder_prefix'];
        $sql_check = "
            SELECT tq.id
            FROM cberp_quotes tq
            INNER JOIN cberp_quotes_items tqi ON tqi.tid = tq.id
            WHERE tq.id = ?
            GROUP BY tq.id
            HAVING COUNT(CASE WHEN tqi.prdstatus != 1 THEN 1 ELSE NULL END) = 0
        ";
    
        // Execute the check query
        $query = $this->db->query($sql_check, array($quote_id));
        
        $sql_fetch = "
        SELECT seq_number,sales_tid
        FROM cberp_quotes 
        WHERE id = ?
        ";
        $query_fetch = $this->db->query($sql_fetch, array($quote_id));
        $row = $query_fetch->row();

        if ($query->num_rows() > 0) {

           
            $seq_number = ($row->seq_number>0)?$row->seq_number + 1 : 0;
            $salestid = $row->sales_tid;
            // If all prdstatus values are 1, update the quote
            $sql_update = "
                UPDATE cberp_quotes
                SET convertflg = 1, sales_tid = ?, salesorder_number = ?, seq_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update, array($salestid, $prefix.$salestid, $seq_number, $quote_id));
    
            $sql_update1 = "
                UPDATE cberp_sales_orders
                SET  salesorder_number = ?, seq_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update1, array($prefix.$salestid, $seq_number, $salesorder_id));

            $sql_update2 = "
                UPDATE cberp_sales_orders
                SET  salesorder_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update2, array($prefix.$salestid, $salesorder_id));
        } 
        else {
            // If not all prdstatus values are 1
            // Fetch the existing seq_number from cberp_quotes
           
            if ($row) {
                $new_seq_number = $row->seq_number + 1;
                $salestid = $row->sales_tid;
                $sql_update = "
                    UPDATE cberp_quotes
                    SET convertflg = 2, sales_tid = ?, seq_number = ?, 
                    salesorder_number = CONCAT(?, '-', ?)
                    WHERE id = ?
                ";
                $this->db->query($sql_update, array($salestid, $new_seq_number, $prefix.$salestid, $new_seq_number, $quote_id));
                $sql_update1 = "
                    UPDATE cberp_sales_orders
                    SET seq_number = ?, 
                    salesorder_number = CONCAT(?, '-', ?)
                    WHERE id = ?
                ";
                $this->db->query($sql_update1, array($new_seq_number, $prefix.$salestid, $new_seq_number, $salesorder_id));

                $sql_update2 = "
                    UPDATE cberp_sales_orders_items
                    SET
                    salesorder_number = CONCAT(?, '-', ?)
                    WHERE tid = ?
                ";
                $this->db->query($sql_update2, array($prefix.$salestid, $new_seq_number, $salesorder_id));
               
            }
        }

        $this->db->select('id,salesorder_number');
        $this->db->from('cberp_sales_orders');
        $this->db->where('quote_id', $quote_id);
        $this->db->where('id', $salesorder_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query12 = $this->db->get();
        $resquery = $query12->row_array();
        if(!empty($resquery))
        {

            $sales_id = $resquery['id'];
            $sales_number = $resquery['salesorder_number'];
            insertion_to_tracking_table('sales_id', $sales_id, 'sales_number', $sales_number, 'quote_id', $quote_id);
            // $this->db->select('sales_id,sales_number');
            // $this->db->from('cberp_transaction_tracking');
            // $this->db->where('quote_id', $quote_id);
            // $existing = $this->db->get()->row_array();
            // if(!empty($existing['sales_id']) && !empty($existing['sales_number'])) {
            //     $existing_sales_id = $existing['sales_id'];
            //     $existing_sales_number = $existing['sales_number'];
            //     $sales_id = $existing_sales_id . ',' . $sales_id;
            //     $sales_number = $existing_sales_number . ',' . $sales_number;
            //     $this->db->where('quote_id', $quote_id);
            //     $this->db->update('cberp_transaction_tracking',['sales_id'=>$sales_id,'sales_number'=>$sales_number]);
            // }
            // else{
            //     $this->db->where('quote_id', $quote_id);
            //     $this->db->update('cberp_transaction_tracking',['sales_id'=>$sales_id,'sales_number'=>$sales_number]);
            // }
            
           
        }


        
    }
    
    


    public function get_customer_by_quoteid($quote_number)
    {
        $this->db->select('cberp_customers.name, cberp_customers.phone, cberp_customers.email, cberp_customers.city, cberp_customers.customer_id, cberp_customers.address,cberp_customers.country, cberp_customers.credit_limit, cberp_customers.credit_period, cberp_customers.avalable_credit_limit');
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_quotes.customer_id');
        $this->db->where('cberp_quotes.quote_number', $quote_number);    
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();
    }

    public function salesorder_number()
    {
        $this->db->select('MAX(id) + 1 as next_id');
        $this->db->from('cberp_sales_orders');
        $query = $this->db->get();
        $next_id = $query->row()->next_id;
        if (is_null($next_id) || $next_id == 0) {
            $next_id = 1;
        }
        return $next_id;
    }
    public function deliverynote_number()
    {
        $this->db->select('MAX(delevery_note_id) + 1 as next_id');
        $this->db->from('cberp_delivery_notes');
        $query = $this->db->get();
        $next_id = $query->row()->next_id;
        if (is_null($next_id) || $next_id == 0) {
            $next_id = 1;
        }
        return $next_id;
    }
    public function quote_details_byid($salesorder_number)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.salesorder_number AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.shipping_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_sales_orders.status as salesorders_status');
        $this->db->from("cberp_sales_orders");
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        $this->db->join('cberp_customers', 'cberp_sales_orders.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.payment_term', 'left');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();

    }
    public function quote_details_byquoteid($id)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.ship_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from("cberp_sales_orders");
        $this->db->where('cberp_sales_orders.quote_id', $id);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        $this->db->join('cberp_customers', 'cberp_sales_orders.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.term', 'left');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();

    }
    public function quote_details_by_saleid_quoteid($id,$salesorderid)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.ship_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from("cberp_sales_orders");
        $this->db->where('cberp_sales_orders.quote_id', $id);
        $this->db->where('cberp_sales_orders.id', $salesorderid);
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        $this->db->join('cberp_customers', 'cberp_sales_orders.csd = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.term', 'left');
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();

    }
    public function tracking_details($field, $id)
    {
        $this->db->select('cberp_transaction_tracking.*');
        $this->db->from('cberp_transaction_tracking');
        $this->db->where('cberp_transaction_tracking.' . $field, $id);
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();
    }
    public function tracking_details_using_like($field, $id)
    {
        $this->db->select('cberp_transaction_tracking.*');
        $this->db->from('cberp_transaction_tracking');
        // $this->db->where('cberp_transaction_tracking.' . $field, $id);
        $this->db->like('cberp_transaction_tracking.' . $field, $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function amount_limit($id)
    {
        $this->db->select('cberp_employees.amount_limit');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        
        // print_r($result); die();
        if(!empty($result) && $result['amount_limit']>0){
            return $result['amount_limit'];
        }
        else{
            return 0;
        }
    }

    public function approved_person($id,$type){
        $sql = "
            SELECT `cberp_employees`.`name`,`cberp_employees`.`id`
            FROM `cberp_employees`
            WHERE `cberp_employees`.`id` IN (
                SELECT `cberp_employees`.`reportingto`
                FROM `cberp_employees`
                JOIN `authorization_history`
                ON `cberp_employees`.`id` = `authorization_history`.`requested_by`
                WHERE `authorization_history`.`function_id` = '$id'
                AND `authorization_history`.`function_type` = '$type'
            )";

        $query = $this->db->query($sql);

        // Fetch the result
        return $query->row_array();
    }
    
    public function product_history($productid,$customerid){
        $this->db->select("DATE_FORMAT(`cberp_quotes`.`invoicedate`, '%d-%m-%Y') AS formatted_invoicedate, `cberp_quotes`.`csd`, `cberp_quotes_items`.`price`,`cberp_products`.`product_name`,`cberp_products`.`product_code`,ROUND(`cberp_quotes_items`.`subtotal` / `cberp_quotes_items`.`qty`, 2) AS selled_price");
        
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_quotes_items', 'cberp_quotes_items.tid = cberp_quotes.id');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_quotes_items.pid');

        $this->db->where('cberp_quotes.csd', $customerid);
        $this->db->where('cberp_quotes_items.pid', $productid);
        $this->db->order_by('cberp_quotes.quote_date', 'DESC');        
        // Execute the query
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function get_quote_number_from_salesorder($quote_number)
    {
        $this->db->select('cberp_sales_orders.quote_number');
        $this->db->from('cberp_sales_orders');
        $this->db->where('salesorder_number', $quote_number);
        $query = $this->db->get();
        $result =  $query->row_array();
        if(!empty($result)){
            return $result['quote_number'];
        }
    }

    //erp2024 06-08-2024
 
    public function quote_details_for_multiplesalesorders($quote_id,$product_id)
    {
        $this->db->select('remaining_qty,delivered_qty,transfered_qty,ordered_qty,qty,price,tax,discount,subtotal,totaltax,totaldiscount');
        $this->db->from('cberp_quotes_items');
        $this->db->where('cberp_quotes_items.tid', $quote_id);
        $this->db->where('cberp_quotes_items.pid', $product_id);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    public function quote_items($quote_id,$product_id,$quotedata){        
        $this->db->where('cberp_quotes_items.tid', $quote_id);
        $this->db->where('cberp_quotes_items.pid', $product_id);
        $this->db->update('cberp_quotes_items', $quotedata);
    }

    public function get_quote_details($quote_id) {
        $this->db->select('cberp_customer_lead_items.qty AS leadqty,
                           cberp_customer_leads.created_date as leaddate,
                           cberp_customer_leads.lead_number as leadnumber,
                           cberp_quotes.lead_id, 
                           cberp_quotes.quote_number,
                           cberp_quotes_items.remaining_qty, 
                           cberp_quotes.quote_date as quotedate, 
                           cberp_quotes.id as quote_id,
                           cberp_quotes_items.pid, 
                           cberp_quotes_items.product, 
                           cberp_quotes_items.code, 
                           cberp_quotes_items.qty AS quoteqty, 
                           cberp_quotes_items.price AS quoterate,                            
                           cberp_quotes_items.subtotal AS quotedamount, 
                           cberp_products.product_price as currentrate,
                           cberp_products.product_price as product_price,
                           cberp_product_ai.min_price as product_lowest_price,
                           cberp_product_ai.max_disrate as product_max_discount');
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_quotes_items', 'cberp_quotes_items.tid = cberp_quotes.id', 'inner');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_quotes_items.pid');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_products.pid');
        $this->db->join('cberp_customer_leads', 'cberp_customer_leads.lead_id = cberp_quotes.lead_id', 'left');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.tid = cberp_quotes.lead_id', 'left');
        $this->db->where('cberp_quotes.id', $quote_id);
        $this->db->group_by('cberp_quotes_items.pid');

        $query = $this->db->get();
        // die($this->db->last_query());
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_salesorder_against_quote($quote_id, $product_id){
        $this->db->select('cberp_sales_orders_items.qty AS salesorderqty, cberp_sales_orders.id AS salesorderid,cberp_sales_orders.salesorder_number AS salesordernumber, cberp_sales_orders.completed_status AS completedstatus, cberp_sales_orders.converted_status AS convertedstatus, cberp_sales_orders_items.pid as salesprdid,cberp_sales_orders.invoicedate AS salesorderdate, cberp_sales_orders_items.subtotal as subtotal,
        cberp_sales_orders_items.totaldiscount as salestotaldiscount,
        cberp_sales_orders_items.discount as salesdiscount,
        cberp_sales_orders_items.discount_type as salesdiscounttype');
        $this->db->from('cberp_sales_orders');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.tid = cberp_sales_orders.id', 'inner');
        $this->db->where('cberp_sales_orders.quote_id', $quote_id);
        // $this->db->where('cberp_sales_orders_items.pid', $product_id);
        $query = $this->db->get();
        // die($this->db->last_query());
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_quote_items_for_new_salesorder($quote_id) {
        $this->db->select('cberp_quotes_items.*, 
                           cberp_products.onhand_quantity AS totalQty, 
                           cberp_products.alert_quantity, 
                           cberp_products.product_code, 
                           cberp_products.unit AS productunit, 
                           cberp_quotes_items.delivered_qty AS deliveredqty, 
                           cberp_quotes_items.remaining_qty AS remainingqty, 
                           cberp_quotes_items.transfered_qty AS trasferedqty, 
                           cberp_quotes_items.ordered_qty AS orderedqty, 
                           cberp_customer_lead_items.qty AS leadqty,
                           cberp_product_ai.min_price,
                           cberp_product_ai.max_disrate');
        $this->db->from('cberp_quotes');
        $this->db->join('cberp_quotes_items', 'cberp_quotes_items.tid = cberp_quotes.id');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_quotes_items.pid');
        $this->db->join('cberp_product_ai', 'cberp_product_ai.product_id = cberp_quotes_items.pid','left');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.tid = cberp_quotes.lead_id AND cberp_customer_lead_items.pid = cberp_quotes_items.pid', 'left');
        $this->db->where('cberp_quotes.id', $quote_id);
        $this->db->where('cberp_quotes_items.prdstatus', '0');

        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
    }

    public function get_sales_seqnumber_tid($quote_id) {
        $this->db->select('(cberp_quotes.seq_number + 1) as salesseqnumber, CONCAT(cberp_quotes.sales_tid, "-", cberp_quotes.seq_number + 1) as newsalesordernumber') ;
        $this->db->from('cberp_quotes');
        $this->db->where('cberp_quotes.id', $quote_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    public function check_quote_converted_stage($quote_id)
    {
        $this->db->select('convertflg');
        $this->db->from('cberp_quotes');
        $this->db->where('cberp_quotes.id', $quote_id);    
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['convertflg']);
        } else {
            return false;
        }
    }

    public function salesorder_details_for_multipledeliverynotes($salesorder_id,$product_id)
    {
        $this->db->select('remaining_qty,delivered_qty,transfered_qty,ordered_qty,qty,price,tax,discount,subtotal,totaltax,totaldiscount,del_remaining_qty,del_delivered_qty,del_transfered_qty');

        $this->db->from('cberp_sales_orders_items');
        $this->db->where('cberp_sales_orders_items.tid', $salesorder_id);
        $this->db->where('cberp_sales_orders_items.pid', $product_id);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }

    // #erp2024 20-08-2024
    
    public function update_salesorder_status($quote_id, $salestid, $salesorder_id,$completedstatus) {
        // First, check if all prdstatus values are 1
        $sql_check = "
        SELECT cberp_sales_orders.id
        FROM cberp_sales_orders 
        INNER JOIN cberp_sales_orders_items cberp_sales_orders_items ON cberp_sales_orders_items.tid = cberp_sales_orders.id
        WHERE cberp_sales_orders.id = ?
        GROUP BY cberp_sales_orders.id
        HAVING COUNT(CASE WHEN cberp_sales_orders_items.prdstatus != 1 THEN 1 ELSE NULL END) = 0
        ";
        // Execute the check query
        $query = $this->db->query($sql_check, array($quote_id));
        $sql_fetch = "
        SELECT delnote_seq_number as seq_number
        FROM cberp_sales_orders 
        WHERE id = ?
        ";
        $query_fetch = $this->db->query($sql_fetch, array($quote_id));
        $row = $query_fetch->row();
        if ($query->num_rows() > 0) {
            // if($completedstatus==1)
            // {
                $seq_number = ($row->seq_number>0)?$row->seq_number + 1 : 0;
            // }
            // else{
            //     $seq_number = ($row->seq_number>0)?$row->seq_number : 0;
            // }
            
            // If all prdstatus values are 1, update the quote
            $sql_update = "
                UPDATE cberp_sales_orders
                SET converted_status = 1, delnote_tid = ?, delnote_number = ?, delnote_seq_number = ?
                WHERE id = ?
            ";
            $this->db->query($sql_update, array($salestid, $salestid, $seq_number, $quote_id));
            $sql_update1 = "
                UPDATE cberp_delivery_notes
                SET  delnote_number = ?, delnote_seq_number = ?
                WHERE delevery_note_id = ?
            ";
            $this->db->query($sql_update1, array($salestid, $seq_number, $salesorder_id));
           
        } else {
            // If not all prdstatus values are 1
            // Fetch the existing seq_number from cberp_quotes
            
            if ($row) {
                // if($completedstatus==1)
                // {
                    $new_seq_number = $row->seq_number + 1;
                // }
                // else{
                //     $new_seq_number = $row->seq_number;
                // }
               
                $sql_update = "
                    UPDATE cberp_sales_orders
                    SET converted_status = 2, delnote_tid = ?, delnote_seq_number = ?, 
                    delnote_number = CONCAT(?, '-', ?)
                    WHERE id = ?
                ";
                $this->db->query($sql_update, array($salestid, $new_seq_number, $salestid, $new_seq_number, $quote_id));
                $sql_update1 = "
                    UPDATE cberp_delivery_notes
                    SET delnote_seq_number = ?, 
                    delnote_number = CONCAT(?, '-', ?)
                    WHERE delevery_note_id = ?
                ";
                $this->db->query($sql_update1, array($new_seq_number, $salestid, $new_seq_number, $salesorder_id));
                
                // die($this->db->last_query());
                $sql_update2 = "
                    UPDATE cberp_delivery_note_items
                    SET
                    delnote_number = CONCAT(?, '-', ?)
                    WHERE delevery_note_id = ?
                ";
                $this->db->query($sql_update2, array($salestid, $new_seq_number, $salesorder_id));
                // die($this->db->last_query());
            }
        }
    }


    public function warehouse_by_id($store_id)
    {
        $this->db->select('store_name');
        $this->db->from('cberp_store');
        $this->db->where('cberp_store.store_id', $store_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['store_name']);
        } else {
            return false;
        }
    }
 



    public function deliverynoteid_by_salesorder_number($salesorder_number,$delivery_note_number)
    {
        $this->db->select('delivery_note_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->where('cberp_delivery_notes.salesorder_number', $salesorder_number);
        $this->db->where('cberp_delivery_notes.delivery_note_number', $delivery_note_number);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['delivery_note_number']);
        } else {
            return false;
        }
    }
    public function check_deliverynote_creation_once_completed($salesorder_number){
        $this->db->select('delivery_note_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->where('salesorder_number', $salesorder_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result; 
        } else {
            return false;
        }
    }

    public function current_delnote_number($delevery_note_id)
    {
        $this->db->select('delnote_number');
        $this->db->from('cberp_delivery_notes');
        $this->db->where('delevery_note_id', $delevery_note_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['delnote_number']);
        } else {
            return false;
        }
    }

    public function assigned_warehouse($delnote_number)
    {
        $this->db->select('cberp_store.title');
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_store', 'cberp_store.id = cberp_delivery_notes.store_id');
        $this->db->where('cberp_delivery_notes.delnote_number', $delnote_number);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['title']);
        } else {
            return false;
        }
    }

    public function compare_delivery_product_price_with_avail_credit_limit($id)
    {

        $this->db->select('cberp_sales_orders.customer_id, cberp_sales_orders_items.quantity,cberp_sales_orders_items.del_remaining_qty, cberp_sales_orders_items.price, cberp_sales_orders_items.discount, cberp_sales_orders_items.discount_type');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_sales_orders', 'cberp_sales_orders.id = cberp_sales_orders_items.tid');
        $this->db->where('cberp_sales_orders_items.tid', $id);
        $query = $this->db->get();
        // echo $this->session->userdata('repeatsubmit');
        // die($this->db->last_query());
        $result =  $query->result_array();
        // echo "<pre>"; print_r($result);
        // die();
        $producttotalAmt=0;
        $subtotal = 0;
        $customerid ="";
        if(!empty($result)){
            foreach($result as $row){
                $qty = (intval($row['del_remaining_qty'])>0) ? intval($row['del_remaining_qty']): intval($row['qty']);
                $customerid =$row['csd'];
                $price = $row['price'];
                $productprice = $price * $qty;
                // echo "\n<br>Without discount : ".$productprice;
                // echo "\n<br>".$row['discount_type']."\n<br>";
                if($row['discount_type']=='Amttype')
                {
                    $discountAmopunt = $qty*$row['discount'];
                    
                }
                else{
                    $discountAmopunt = ($productprice/100)*$row['discount'];
                }
                // echo "Discount : ".$discountAmopunt."\n<br>";

                // echo "Subtotal : ".$productprice - $discountAmopunt;
                $subtotal = $productprice - $discountAmopunt;
                $producttotalAmt = $producttotalAmt+ $subtotal;
            }
            $producttotalAmt = round($producttotalAmt,2);
            $this->db->select('avalable_credit_limit');
            $this->db->from('cberp_customers');
            $this->db->where('id', $customerid);
            $query = $this->db->get();
            $customer_res = $query->row_array();

          
            if(!empty($customer_res))
            {
               
                $avalable_credit_limit = $customer_res['avalable_credit_limit'];               
                if($producttotalAmt > $avalable_credit_limit)
                {
                    return 1;
                }
                else{
                    return 2;
                }
            }

            
        }

    }

    //erp2024 14-09-2024
    public function alreadyconverted_or_not($leadid)
    {
        $this->db->select('enquiry_status');
        $this->db->from('cberp_customer_leads');
        $this->db->where('cberp_customer_leads.lead_id', $leadid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['enquiry_status']);
        } else {
            return false;
        }
    }
    public function already_converted_to_salesorder($quote_number)
    {
        $this->db->select('salesorder_number');
        $this->db->from('cberp_sales_orders');
        $this->db->where('quote_number', $quote_number);
        $this->db->where('salesorder_date IS NULL');
        $this->db->limit(1);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }
    public function salesorder_draft($salesorder_number)
    {
        $this->db->select('id');
        $this->db->from('cberp_sales_orders');
        $this->db->where('salesorder_number', $salesorder_number);
        $this->db->where('completed_status', '0');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }
    public function seqnumber_byquote($quote_id)
    {
        $this->db->select('seq_number,salesorder_number');
        $this->db->from('cberp_quotes');
        $this->db->where('cberp_quotes.id', $quote_id);
        $query = $this->db->get();
        return $query->row_array();

    }
    public function check_product_existornot($quote_number,$product_code)
    {
        $this->db->select('quote_number');
        $this->db->from('cberp_quotes_items');
        $this->db->where('quote_number', $quote_number);
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }
    public function check_quote_existornot($quote_number)
    {
        $this->db->select('quote_number');
        $this->db->from('cberp_quotes');
        $this->db->where('quote_number', $quote_number);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['quote_number']);
        } else {
            return 0;
        }

    }
    public function check_quote_existornot_by_id($quote_number)
    {
        $this->db->select('quote_number');
        $this->db->from('cberp_quotes');
        $this->db->where('quote_number', $quote_number);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['quote_number']);
        } else {
            return 0;
        }

    }
    public function check_approval_existornot($quote_number)
    {
        $this->db->select('id');
        $this->db->from('authorization_history');
        $this->db->where('function_id', $quote_number);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $res =  $query->row_array();
            return($res['id']);
        } else {
            return 0;
        }

    }


    //erp2024 15-10-2024 starts
    public function get_quote_count_filter($datefield,$amountfield,$filter_status,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$filter_customer)
    {
         

        $this->db->select(" 
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN $amountfield ELSE 0 END) AS yearly_total,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN $amountfield ELSE 0 END) AS quarterly_total,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN $amountfield ELSE 0 END) AS monthly_total,
            SUM(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN $amountfield ELSE 0 END) AS weekly_total,
            SUM(CASE WHEN DATE($datefield) = CURDATE() THEN $amountfield ELSE 0 END) AS daily_total,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE() THEN 1 ELSE NULL END) AS yearly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 3 MONTH AND CURDATE() THEN 1 ELSE NULL END) AS quarterly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() THEN 1 ELSE NULL END) AS monthly_count,
            COUNT(CASE WHEN $datefield BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() THEN 1 ELSE NULL END) AS weekly_count,
            COUNT(CASE WHEN DATE($datefield) = CURDATE() THEN 1 ELSE NULL END) AS daily_count");
        $this->db->from('cberp_quotes');
            // Apply the filter conditions
        if (!empty($filter_status)) {
            $this->db->where_in('cberp_quotes.status', $filter_status);
        }
        if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
            $this->db->where("cberp_quotes.due_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }

        if(!empty($filter_customer)){
            $this->db->where_in("cberp_quotes.csd",$filter_customer);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_quotes.total BETWEEN $filter_price_from AND $filter_price_to");
        }
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }
    //erp2024 15-10-2024 ends
    public function gethistory($quoteid)
    {
        $this->db->select('cberp_quotes_log.*,cberp_employees.name');
        $this->db->from('cberp_quotes_log');  
        $this->db->join('cberp_employees',' cberp_quotes_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_quotes_log.quote_id',$quoteid);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getsalehistory($tid)
    {
        $this->db->select('cberp_sales_orders_log.*,cberp_employees.name');
        $this->db->from('cberp_sales_orders_log');  
        $this->db->join('cberp_employees',' cberp_sales_orders_log.performed_by=cberp_employees.id');
        $this->db->where('cberp_sales_orders_log.sales_order_id',$tid);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getnotehistory($tid)
    {
        $this->db->select('delivery_note_log.*,cberp_employees.name');
        $this->db->from('delivery_note_log');  
        $this->db->join('cberp_employees',' delivery_note_log.performed_by=cberp_employees.id');
        $this->db->where('delivery_note_log.deliverynote_id',$tid);
        $query = $this->db->get();
        return $query->result_array();
    }
    //erp2024 09-01-2025 detailed history log starts

    public function get_detailed_log($id,$page)
    {
        $this->db->select('cberp_master_log.*,cberp_employees.name,cberp_employees.picture');
        $this->db->from('cberp_master_log');  
        $this->db->join('cberp_employees','cberp_master_log.changed_by=cberp_employees.id');
        $this->db->where('cberp_master_log.item_no',$id);
        $this->db->where('cberp_master_log.log_from',$page);
        $this->db->order_by('cberp_master_log.seqence_number', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    //erp2024 09-01-2025 detailed history log ends

   public function get_filter_count($ranges)
    {
        $today = date('Y-m-d');
        $startMonth    = $ranges['month'];
        $startWeek     = $ranges['week'];
        $startQuarter  = $ranges['quarter'];
        $startYear     = $ranges['year'];
        $query = $this->db->query("
            SELECT 
                -- Total counts
                SUM(CASE WHEN quote_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN quote_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN quote_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN quote_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(quote_date) = '$today' THEN 1 ELSE 0 END) AS daily_count,

                -- 'Assigned' status
                SUM(CASE WHEN status = 'Assigned' AND quote_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND quote_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND quote_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND quote_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_assigned_count,
                SUM(CASE WHEN status = 'Assigned' AND DATE(quote_date) = '$today' THEN 1 ELSE 0 END) AS daily_assigned_count,

                -- 'pending' status
                SUM(CASE WHEN status = 'pending' AND quote_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN status = 'pending' AND quote_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN status = 'pending' AND quote_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN status = 'pending' AND quote_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN status = 'pending' AND DATE(quote_date) = '$today' THEN 1 ELSE 0 END) AS daily_created_count,

                -- 'Sent' status
                SUM(CASE WHEN status = 'Sent' AND quote_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND quote_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND quote_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND quote_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_sent_count,
                SUM(CASE WHEN status = 'Sent' AND DATE(quote_date) = '$today' THEN 1 ELSE 0 END) AS daily_sent_count,

                -- 'draft' status
                SUM(CASE WHEN status = 'draft' AND quote_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'draft' AND quote_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'draft' AND quote_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'draft' AND quote_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(quote_date) = '$today' THEN 1 ELSE 0 END) AS daily_draft_count,

                -- Totals
                SUM(CASE WHEN quote_date BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN quote_date BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN quote_date BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN quote_date BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(quote_date) = '$today' THEN total ELSE 0 END) AS daily_total
            FROM cberp_quotes
        ");

        return $query->row();
    }

    public function quote_related_salesorders($quote_number)
    {
        $this->db->select('salesorder_number');
        $this->db->from('cberp_sales_orders');
        $this->db->where('quote_number', $quote_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
        
    }


}
