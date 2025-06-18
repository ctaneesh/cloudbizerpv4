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

class SalesOrder_model extends CI_Model
{
    var $table = 'cberp_sales_orders';
    var $column_order = array(null, 'cberp_sales_orders.salesorder_number', 'cberp_sales_orders.total', 'cberp_sales_orders.items', 'cberp_sales_orders.invoicedate', 'cberp_sales_orders.status','cberp_customers.customer_id','cberp_customers.name', null);
    var $column_search = array('cberp_sales_orders.salesorder_number', 'cberp_sales_orders.total', 'cberp_sales_orders.items', 'cberp_sales_orders.invoicedate','cberp_sales_orders.status','cberp_customers.customer_id','cberp_customers.name');
    var $order = array('cberp_sales_orders.created_date' => 'desc');
    
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

    public function lastsalesorder()
    {
        $prefix = get_prefix_72();
        $this->db->select('salesorder_number');
        $this->db->from($this->table);
        $this->db->where("salesorder_number IS NOT NULL");
        $this->db->order_by('created_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $last_salesorder_number_number = $query->row()->salesorder_number;
            $parts = explode('/', $last_salesorder_number_number);
            $last_number = (int)end($parts); 
            $next_number = $last_number + 1;
            return $prefix['salesorder_prefix'].$next_number;
        } else {
            return $prefix['salesorder_prefix'].'1001';
        }
    }
    

    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');
        // if ($this->aauth->get_user()->loc) {
        //     $this->db->where('loc', $this->aauth->get_user()->loc);
        //   if(BDATA)  $this->db->or_where('loc', 0);
        // }  elseif(!BDATA) { $this->db->where('loc', 0); }


        $query = $this->db->get();
        return $query->result_array();

    }

    public function quote_details($id)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.ship_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('cberp_sales_orders.id', $id);
        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_sales_orders.loc', $this->aauth->get_user()->loc);
        // } elseif (!BDATA) {
        //     $this->db->where('cberp_sales_orders.loc', 0);
        // }
        $this->db->join('cberp_customers', 'cberp_sales_orders.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.term', 'left');
        $query = $this->db->get();
        return $query->row_array();

    }

    public function quote_products($id)
    {
        $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_sales_orders_items.pid', 'left');
        $this->db->where('tid', $id);
        $query = $this->db->get();

        return $query->result_array();

    }

    public function quote_product_by_id($id)
    {
        $this->db->select('cberp_sales_orders.*');
        $this->db->from('cberp_sales_orders');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }



    public function quote_delete($id)
    {
        $this->db->trans_start();
          if ($this->aauth->get_user()->loc) {
                $res = $this->db->delete('cberp_sales_orders', array('id' => $id, 'loc' => $this->aauth->get_user()->loc));
        }
        else {
            if (BDATA) {
                    $res = $this->db->delete('cberp_sales_orders', array('id' => $id));

            } else {
                    $res = $this->db->delete('cberp_sales_orders', array('id' => $id,'loc' => 0));
            }
        }
        if ($this->db->affected_rows()) $this->db->delete('cberp_sales_orders_items', array('tid' => $id));
        if ($this->db->trans_complete()) {
            return true;
        } else {
            return false;
        }
    }

    // f(($invoices->converted_status=='0') && ($prdstatus==1)){
    //     $status = '<span class="st-due">' . $this->lang->line('Completed') . '</span>';
    // }
    // else if(($invoices->converted_status=='2') && ($prdstatus==1)){
    //     $status = '<span class="st-due">' . $this->lang->line('Completed') . '</span>';
    // }
    // else if(($invoices->converted_status=='2') && ($prdstatus!=1)){
    //     $status = '<span class="st-partial">' . $this->lang->line('Partially Converted') . '</span>';
    // }
    // else if(($invoices->converted_status=='0') && ($prdstatus!=1)){
    //     $status = '<span class="st-rejected">' . $this->lang->line('Not Converted') . '</span>';
    // }
    // else if(($invoices->converted_status=='3')){
    //     $status = '<span class="st-Closed">' . $this->lang->line('Assign for Delivery') . '</span>';
    // }
    // else{
    //     $status = '<span class="st-paid">' . $this->lang->line('Converted') . '</span>';
    private function _get_datatables_query($eid)
    {
        $filter_status = !empty($this->input->post('filter_status')) ?$this->input->post('filter_status') : "";

        $filter_expiry_date_from = !empty($this->input->post('filter_expiry_date_from')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_from'))) : ""; 

        $filter_expiry_date_to = !empty($this->input->post('filter_expiry_date_to')) ? date('Y-m-d',strtotime($this->input->post('filter_expiry_date_to'))) : "";
       
        $filter_price_from = !empty($this->input->post('filter_price_from')) ? $this->input->post('filter_price_from') : 0;
        $filter_price_to = !empty($this->input->post('filter_price_to')) ? $this->input->post('filter_price_to'): 0;

        $filter_customer = !empty($this->input->post('filter_customer')) ?$this->input->post('filter_customer') : "";

        $this->db->select('cberp_sales_orders.*,cberp_customers.customer_id as customerid,cberp_customers.name as customername');
        $this->db->from("cberp_sales_orders");
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_sales_orders.customer_id', 'left');
        // if ($eid) $this->db->where('cberp_sales_orders.id', $eid);
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(cberp_sales_orders.salesorder_date) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(cberp_sales_orders.salesorder_date) <=', datefordatabase($this->input->post('end_date')));
        }
        
       //erp2024 filter search 15-10-2024 starts
        if (!empty($filter_status)) {
            $this->db->where_in('cberp_sales_orders.converted_status', $filter_status);
        }
        if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
            $this->db->where("cberp_sales_orders.due_date BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }

        if(!empty($filter_customer)){
            $this->db->where_in("cberp_sales_orders.customer_id",$filter_customer);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_sales_orders.total BETWEEN $filter_price_from AND $filter_price_to");
        }
       //erp2024 filter search 15-10-2024 ends
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

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($eid)
    {
        $this->_get_datatables_query($eid);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result();
    }

    function count_filtered($eid)
    {
        $this->_get_datatables_query($eid);    
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($eid)
    {
        $this->_get_datatables_query($eid);
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
        $this->db->select('cberp_employees.name,cberp_employees.sign,cberp_users.roleid');
        $this->db->from('cberp_employees');
        $this->db->where('cberp_employees.id', $id);
        $this->db->join('cberp_users', 'cberp_employees.id = cberp_users.id', 'left');
        $query = $this->db->get();
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
                $this->db->update('cberp_sales_orders');
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
                $this->db->update('cberp_sales_orders');
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
    public function insert_to_sales_order_items($id) {
        $this->db->select('*');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        $data = $query->result_array();

        if (!empty($data)) {
            foreach ($data as $row) {
                $this->db->insert('cberp_sales_orders_items', $row);
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

    public function salesorder_details($salesorder_number)
    {

        $this->db->select('cberp_sales_orders.*,cberp_sales_orders.customer_id AS iid,SUM(cberp_sales_orders.shipping + cberp_sales_orders.shipping_tax) AS shipping,cberp_customers.*,cberp_sales_orders.loc as loc,cberp_sales_orders.status AS salesorders_status,cberp_customers.customer_id AS cid,cberp_terms.id AS termid,cberp_terms.title AS termtit,cberp_terms.terms AS terms,cberp_store.store_name as warehouse');
        $this->db->from("cberp_sales_orders");
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
        $this->db->join('cberp_customers', 'cberp_sales_orders.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_terms', 'cberp_terms.id = cberp_sales_orders.payment_term', 'left');        
        $this->db->join('cberp_store', 'cberp_store.store_id = cberp_sales_orders.store_id', 'left');
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();

    }

    public function salesorder_products($salesorder_number)
    {
        $this->db->select('cberp_sales_orders_items.*, cberp_products.onhand_quantity AS totalQty, cberp_products.alert_quantity,cberp_product_pricing.minimum_price as minimum_price,cberp_products.maximum_discount_rate as maximum_discouunt,cberp_products.product_code as code,cberp_product_description.product_name as product');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_sales_orders_items.product_code');
        $this->db->join('cberp_product_pricing', 'cberp_product_pricing.product_code = cberp_products.product_code');
        $this->db->join('cberp_product_description', 'cberp_product_description.product_code = cberp_products.product_code');
        $this->db->where('cberp_sales_orders_items.salesorder_number', $salesorder_number);
        $query = $this->db->get(); 
        // die($this->db->last_query());
        return $query->result_array();

    }

    public function salesorder_item_details_by_id($salesorderid,$salesorder_number)
    {
        $this->db->select('cberp_sales_orders_items.*, 
                    cberp_products.onhand_quantity AS totalQty, 
                    cberp_products.alert_quantity, 
                    cberp_products.product_code, 
                    cberp_products.unit AS productunit,
                    cberp_sales_orders_items.qty AS enteredqty, 
                    cberp_sales_orders_items.delivered_qty AS deliveredqty, 
                    cberp_sales_orders_items.remaining_qty AS remainingqty, 
                    cberp_sales_orders_items.transfered_qty AS trasferedqty, 
                    cberp_sales_orders_items.ordered_qty AS orderedqty,
                    cberp_customer_lead_items.qty AS leadqty');
        $this->db->from('cberp_sales_orders');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.salesorder_number = cberp_sales_orders.salesorder_number');
        $this->db->join('cberp_products', 'cberp_products.pid = cberp_sales_orders_items.pid');
        $this->db->join('cberp_quotes', 'cberp_quotes.id = cberp_sales_orders.quote_id');
        $this->db->join('cberp_customer_lead_items', 'cberp_customer_lead_items.tid = cberp_quotes.lead_id AND cberp_customer_lead_items.pid = cberp_sales_orders_items.pid', 'left');
        $this->db->where('cberp_sales_orders_items.tid', $salesorderid);
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
        // $this->db->where('cberp_sales_orders_items.salesorder_number', $salesorder_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    
    public function insert_delivery_note_from_sales_order($salesorder_number, $deliverynote_number, $module_number)
    {     
        $sql = "INSERT INTO cberp_delivery_notes (
            delivery_note_number, salesorder_number, total_amount, created_date, subtotal, shipping, shipping_tax, discount, tax, customer_id, status, store_id, reference, reference_date, payment_term, order_discount, customer_po_reference, customer_contact_person, customer_contact_number, customer_contact_email,delivery_note_date
        )
        SELECT
            ?, salesorder_number, total, CURDATE(), subtotal, shipping, shipping_tax, discount, tax, customer_id, 'Created', store_id, reference, NOW(), payment_term, order_discount, customer_reference_number, customer_contact_person, customer_contact_number, customer_contact_email,NOW()
        FROM
            cberp_sales_orders
        WHERE
            cberp_sales_orders.salesorder_number = ?";
            
        $this->db->query($sql, array($deliverynote_number, $salesorder_number));
        $last_insert_id = $this->db->insert_id();
        // history_table_log('cberp_sales_orders_log','sales_order_id',$salesorder_id,'Convert to delivery note');
        // history_table_log('delivery_note_log','deliverynote_id',$last_insert_id,'Create');
        //erp2024 06-01-2025 detailed history log starts
        detailed_log_history($module_number,$salesorder_number,'Assigned for delivery', $changedFields);
        detailed_log_history('Deliverynote',$deliverynote_number,'Created', $changedFields);
        //erp2024 06-01-2025 detailed history log ends 
        // $this->db->update('cberp_delivery_notes',['tid'=>$last_insert_id+1000],['delevery_note_id'=>$last_insert_id]);

        // return $last_insert_id; created
        $this->session->unset_userdata('latest_delnote_id');
        $this->session->unset_userdata('repeatsubmit');  
        $this->session->set_userdata("latest_delnote_id", $deliverynote_number);
        $this->session->set_userdata("repeatsubmit", 1);

        
        // Step 1: Fetch data
        $this->db->select('cberp_sales_orders_items.product_code, cberp_sales_orders_items.quantity, cberp_sales_orders_items.price as product_price, cberp_sales_orders_items.tax as product_tax, cberp_sales_orders_items.discount as product_discount, cberp_sales_orders_items.total_amount as subtotal, cberp_sales_orders_items.total_tax, cberp_sales_orders_items.total_discount, cberp_sales_orders_items.discount_type as discount_type,cberp_products.product_cost as product_cost,cberp_sales_orders_items.lowest_price');
        $this->db->from('cberp_sales_orders_items');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_sales_orders_items.product_code');
        $this->db->where('salesorder_number', $salesorder_number);
        $this->db->where('product_status', "0");
        $query = $this->db->get();
        $data = $query->result_array();
        // $this->db->update('cberp_sales_orders', ['converted_status'=>3 ], ['salesorder_number'=>$salesorder_number]);


        // $this->db->select('delnote_tid, delnote_seq_number,delnote_number');
        // $this->db->from('cberp_sales_orders');
        // $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_id);
        // $salesquery = $this->db->get();
        // $salesresult = $salesquery->row_array();
        // if(!empty($salesresult['delnote_tid']) && (!empty($salesresult['delnote_number'])))
        // {
        //     $delnote_seq_number = $salesresult['delnote_seq_number']+1;
        //     $delnote_number = $salesresult['delnote_tid']."-".$delnote_seq_number;
        //     $this->db->update('cberp_sales_orders', ['converted_status'=>3, 'delnote_seq_number'=>$delnote_seq_number,'delnote_number' => $delnote_number ], ['id'=>$salesorder_id]);
        // }
        // else{
        //     $delnote_number = $last_insert_id+1000;
        //     $delnote_number = $delnote_number."-1";
        //     $delnote_seq_number = 1;
        //     $delnote_tid = $last_insert_id+1000;
        //     $this->db->update('cberp_sales_orders', ['converted_status'=>3,'delnote_tid'=>$delnote_tid, 'delnote_seq_number'=>$delnote_seq_number,'delnote_number' => $delnote_number ], ['id'=>$salesorder_id]);
        // }

        

        // Step 2: Add additional fields and insert each row
        $remqty = [];
        foreach ($data as $row) {
            $row['delivery_note_number'] = $deliverynote_number; // Ensure $last_insert_id is correctly set
            $row['salesorder_number'] = $salesorder_number;
            // Insert single row
            if(!empty($row['write_off_quantity'])){
                $row['quantity'] = $row['quantity'] - $row['quantity'];
            }
            unset($row['write_off_quantity']);
            $this->db->insert('cberp_delivery_note_items', $row);
           
        }

        $validity = default_validity();
        $deliveryduedate = date('Y-m-d', strtotime(date('Y-m-d') . " +" . (int)$validity['deliverynote_validity'] . " days"));
        $this->db->update('cberp_delivery_notes',['created_by'=>$this->session->userdata('id'),'created_date'=>date('Y-m-d H:i:s'),'due_date'=>$deliveryduedate],['delivery_note_number'=>$deliverynote_number]);         
        insertion_to_tracking_table('deliverynote_number', $deliverynote_number, 'salesorder_number', $salesorder_number);
        return true;

    }

    public function salesorder_details_by_salesorder_number($salesorder_number)
    {
        $this->db->select('cberp_sales_orders.*');
        $this->db->from('cberp_sales_orders');        
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
        // $this->db->select('cberp_sales_orders.salesorder_number, cberp_sales_orders_items.product, cberp_sales_orders_items.pid, cberp_sales_orders_items.qty AS orderedqty');
        // $this->db->from('cberp_sales_orders');
        // $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row_array(); 
            return $result;
        } 
    }

    
    public function get_customer_by_salesorder_number($salesorder_number)
    {
        $this->db->select('cberp_customers.name, cberp_customers.phone, cberp_customers.email, cberp_customers.city, cberp_customers.customer_id, cberp_customers.address,cberp_customers.shipping_country, cberp_customers.credit_limit, cberp_customers.credit_period, cberp_customers.avalable_credit_limit');
        $this->db->from('cberp_sales_orders');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_sales_orders.customer_id');
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_number);    
        $query = $this->db->get();
        // echo $this->db->last_query(); die();
        return $query->row_array();
    }

        
    public function get_customer_by_salesorder_id($salesorder_id)
    {
        $this->db->select('cberp_customers.name, cberp_customers.phone, cberp_customers.email, cberp_customers.city, cberp_customers.customer_id, cberp_customers.address,cberp_customers.country, cberp_customers.credit_limit, cberp_customers.credit_period, cberp_customers.avalable_credit_limit');
        $this->db->from('cberp_sales_orders');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id = cberp_sales_orders.customer_id');
        $this->db->where('cberp_sales_orders.salesorder_number', $salesorder_id);    
        $query = $this->db->get();
        return $query->row_array();
    }
   

    public function get_delivery_note_data($salesorder_number)
    {
        $this->db->select('cberp_delivery_notes.tid,cberp_delivery_notes.delivery_note_number, cberp_delivery_notes.delevery_note_id, cberp_delivery_notes.salesorder_number, cberp_delivery_notes.total_amount, cberp_delivery_notes.delnote_number,
        cberp_delivery_notes.created_date, cberp_delivery_notes.created_time, cberp_delivery_notes.delevery_note_id, 
        cberp_delivery_notes.customer_id, cberp_customers.name, cberp_custom_data.data, cberp_delivery_notes.status');
        $this->db->from('cberp_delivery_notes');
        $this->db->join('cberp_customers', 'cberp_delivery_notes.customer_id = cberp_customers.customer_id', 'left');
        $this->db->join('cberp_custom_data', 'cberp_custom_data.rid = cberp_customers.customer_id', 'left');
        $this->db->where('cberp_delivery_notes.salesorder_number', $salesorder_number);
        $this->db->where('cberp_delivery_notes.delnote_number IS NOT NULL');
        $this->db->order_by('cberp_delivery_notes.delevery_note_id', 'DESC');
        $query = $this->db->get();
        // die($this->db->last_query());
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false; // or handle no results case as needed
        }
    }


    public function get_write_off_quantity($pid,$salesorder_id)
    {
        $this->db->select('cberp_sales_orders_items.write_off_quantity');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('cberp_sales_orders_items.pid', $pid); // Assuming $pid holds the product ID
        $this->db->where('cberp_sales_orders_items.tid', $salesorder_id); // Assuming $salesorder_id holds the sales order ID
        $query = $this->db->get();
        $result = $query->row_array(); // Fetch a single row (as array)
        if (!empty($result) && $result['write_off_quantity'] > 0) {
            return $result['write_off_quantity'];  // Return write_off_by if greater than 0
        } else {
            return 0;  // Return 0 if write_off_by is not greater than 0 or no result is found
        }


    }
    public function existing_salesorder_products($pid,$salesorder_id)
    {
        $this->db->select('cberp_sales_orders_items.subtotal,cberp_sales_orders_items.totaldiscount,cberp_sales_orders_items.del_remaining_qty');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('cberp_sales_orders_items.pid', $pid); 
        $this->db->where('cberp_sales_orders_items.tid', $salesorder_id); 
        $query = $this->db->get();
        return $query->row_array();   

    }
    public function get_prdstatus_salesorderid($salesorder_number)
    {
        $this->db->select("CASE WHEN COUNT(CASE WHEN product_status = 1 THEN 1 END) = COUNT(*) THEN 1 ELSE 0 END AS all_prdstatus_one");
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('salesorder_number', $salesorder_number); 
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['all_prdstatus_one'];

    }

    //erp2024 15-10-2024 starts
    public function get_salesorder_count_filter($datefield,$amountfield,$filter_status,$filter_expiry_date_from,$filter_expiry_date_to,$filter_price_from,$filter_price_to,$filter_customer)
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
        $this->db->from('cberp_sales_orders');
        $this->db->where('cberp_sales_orders.salesorder_number IS NOT NULL');
            // Apply the filter conditions
        if (!empty($filter_status)) {
            $this->db->where_in('cberp_sales_orders.converted_status', $filter_status);
        }
        if (!empty($filter_expiry_date_from) && !empty($filter_expiry_date_to)) {
            $this->db->where("cberp_sales_orders.invoiceduedate BETWEEN '$filter_expiry_date_from' AND '$filter_expiry_date_to'");
        }

        if(!empty($filter_customer)){
            $this->db->where_in("cberp_sales_orders.customer_id",$filter_customer);
        }

        if($filter_price_to > 0){
            $this->db->where("cberp_sales_orders.total BETWEEN $filter_price_from AND $filter_price_to");
        }
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->row_array();
    }
    //erp2024 15-10-2024 ends

     //erp2024 09-01-2025 detailed history log starts

     public function get_detailed_log($id,$page)
     {
         $this->db->select('cberp_master_log.*,cberp_employees.name,cberp_employees.picture');
         $this->db->from('cberp_master_log');  
         $this->db->join('cberp_employees','cberp_master_log.changed_by=cberp_employees.id');
         $this->db->where('cberp_master_log.item_no',$id);
         $this->db->where('cberp_master_log.log_from',$page);
         $this->db->order_by('cberp_master_log.seqence_number', 'ASC');
         $query = $this->db->get();
         return $query->result_array();
     }
     //erp2024 09-01-2025 detailed history log ends

    public function get_filter_count($ranges)
    {
        $today = date('Y-m-d H:i:s');
        $today_date = date('Y-m-d');
        $startMonth    = $ranges['month']." 00:00:00";
        $startWeek     = $ranges['week']." 00:00:00";
        $startQuarter  = $ranges['quarter']." 00:00:00";
        $startYear     = $ranges['year']." 00:00:00";

        $query = $this->db->query("
            SELECT 
                SUM(CASE WHEN salesorder_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_count,
                SUM(CASE WHEN salesorder_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_count,
                SUM(CASE WHEN salesorder_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_count,
                SUM(CASE WHEN salesorder_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_count,
                SUM(CASE WHEN DATE(salesorder_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_count,


                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND salesorder_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND salesorder_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND salesorder_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND salesorder_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_created_count,
                SUM(CASE WHEN (status = 'pending' || status = 'invoiced') AND DATE(salesorder_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_created_count,


                SUM(CASE WHEN status = 'draft' AND salesorder_date BETWEEN '$startYear' AND '$today' THEN 1 ELSE 0 END) AS yearly_draft_count,
                SUM(CASE WHEN status = 'draft' AND salesorder_date BETWEEN '$startQuarter' AND '$today' THEN 1 ELSE 0 END) AS quarterly_draft_count,
                SUM(CASE WHEN status = 'draft' AND salesorder_date BETWEEN '$startMonth' AND '$today' THEN 1 ELSE 0 END) AS monthly_draft_count,
                SUM(CASE WHEN status = 'draft' AND salesorder_date BETWEEN '$startWeek' AND '$today' THEN 1 ELSE 0 END) AS weekly_draft_count,
                SUM(CASE WHEN status = 'draft' AND DATE(salesorder_date) = '$today_date' THEN 1 ELSE 0 END) AS daily_draft_count,

        
                SUM(CASE WHEN salesorder_date BETWEEN '$startYear' AND '$today' THEN total ELSE 0 END) AS yearly_total,
                SUM(CASE WHEN salesorder_date BETWEEN '$startQuarter' AND '$today' THEN total ELSE 0 END) AS quarterly_total,
                SUM(CASE WHEN salesorder_date BETWEEN '$startMonth' AND '$today' THEN total ELSE 0 END) AS monthly_total,
                SUM(CASE WHEN salesorder_date BETWEEN '$startWeek' AND '$today' THEN total ELSE 0 END) AS weekly_total,
                SUM(CASE WHEN DATE(salesorder_date) = '$today_date' THEN total ELSE 0 END) AS daily_total

            FROM cberp_sales_orders
        ");

        return $query->row();
    }

    public function check_product_existornot($salesorder_number,$product_code)
    {
        $this->db->select('product_code');
        $this->db->from('cberp_sales_orders_items');
        $this->db->where('salesorder_number', $salesorder_number);
        $this->db->where('product_code', $product_code);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }

    }
    public function quote_related_salesorders($quote_number,$salesorder_number)
    {
        $this->db->select('salesorder_number');
        $this->db->from('cberp_sales_orders');
        $this->db->where('quote_number', $quote_number);
        $this->db->where('salesorder_number !=', $salesorder_number);
        $query = $this->db->get();
        // die($this->db->last_query());
        return $query->result_array();
        
    }

}
