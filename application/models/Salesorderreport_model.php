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

class Salesorderreport_model extends CI_Model
{
    var $table = 'cberp_sales_orders';
    var $column_order = array(null, 'cberp_sales_orders.invoicedate','cberp_sales_orders.id', 'cberp_sales_orders.csd', 'cberp_products.product_code', 'cberp_products.product_name',null,'cberp_sales_orders.subtotal','cberp_sales_orders.completed_status','cberp_sales_orders.converted_status', null);

    var $column_search = array('cberp_sales_orders.id', 'cberp_sales_orders.salesorder_number', 'cberp_sales_orders.invoicedate', 'cberp_sales_orders.csd', 'cberp_sales_orders.completed_status','cberp_sales_orders.converted_status','cberp_sales_orders.delnote_number','cberp_customers.company','cberp_customers.name','cberp_sales_orders_items.product','cberp_sales_orders_items.subtotal','cberp_sales_orders_items.prdstatus','cberp_delivery_notes.status');

    var $order = array('cberp_sales_orders.id' => 'desc');

    var $column_search_purchase = array('cberp_purchase_orders.sent_dt','cberp_purchase_order_items.pid','cberp_purchase_order_items.product','cberp_purchase_order_items.qty','cberp_purchase_order_items.price','cberp_suppliers.name','cberp_suppliers.phone','cberp_suppliers.address','created_employee.name','approve_employee.name','sentby_employee.name');
    var $column_order_purchase = array(null, 'cberp_purchase_orders.sent_dt','cberp_purchase_order_items.pid', 'cberp_purchase_order_items.product', 'cberp_purchase_order_items.qty', 'cberp_purchase_order_items.price','cberp_suppliers.name','cberp_suppliers.phone','cberp_suppliers.address','created_employee.name','approve_employee.name','sentby_employee.name');


    public function __construct()
    {
        parent::__construct();
    }


    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('cberp_store');  
        $this->db->order_by('title', 'ASC');     
        $query = $this->db->get();
        return $query->result_array();

    }
    public function categories()
    {
        $this->db->select('id,title');
        $this->db->from('cberp_product_category');  
        $this->db->order_by('title', 'ASC');     
        $query = $this->db->get();
        return $query->result_array();

    }



    private function _get_datatables_query($eid)
    {

       

        $this->db->select('cberp_sales_orders.id, cberp_sales_orders.salesorder_number, cberp_sales_orders.invoicedate, cberp_sales_orders.csd, cberp_sales_orders.completed_status,cberp_sales_orders.converted_status,cberp_sales_orders.delnote_number,cberp_customers.name,cberp_customers.company,cberp_sales_orders_items.product,cberp_sales_orders_items.subtotal,cberp_sales_orders_items.prdstatus,cberp_delivery_notes.status,cberp_products.product_code,cberp_products.product_name');
        $this->db->from('cberp_sales_orders');
        $this->db->join('cberp_sales_orders_items', 'cberp_sales_orders_items.tid=cberp_sales_orders.id');
        $this->db->join('cberp_products', 'cberp_products.pid=cberp_sales_orders_items.pid');
        $this->db->join('cberp_customers', 'cberp_customers.customer_id=cberp_sales_orders.csd');
        $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delnote_number=cberp_sales_orders.delnote_number', 'left');
        $this->db->where('cberp_sales_orders.salesorder_number IS NOT NULL');

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
        // else{
        //     $order = array('cberp_sales_orders.id' => 'desc');
        // }
        // $order = $this->order;
        // $this->db->order_by(key($order), $order[key($order)]);
    }

    function get_datatables($eid)
    {

        $this->_get_datatables_query($eid);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);        }  
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
        // $this->db->select('cberp_products.pid');
        // $this->db->from($this->table);
        $this->_get_datatables_query($eid);

        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        return $this->db->count_all_results();
    }

    private function _get_datatables_query_purchase($eid)
    {

       
        //cberp_purchase_order_items.product,
    //     $this->db->select('cberp_purchase_orders.sent_date,cberp_purchase_order_items.product_code,cberp_purchase_order_items.quantity AS qty,cberp_purchase_order_items.price,cberp_suppliers.name as suppliername,cberp_suppliers.phone as supplierphone,cberp_suppliers.address as supplieraddress,created_employee.name as addedname,approve_employee.name as approvename,sentby_employee.name as sentbyname');
    //     $this->db->from('cberp_purchase_orders');
    //     $this->db->join('cberp_purchase_order_items', 'cberp_purchase_order_items.tid=cberp_purchase_orders.id');
    //     $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_id=cberp_purchase_orders.id', 'left');
    //     $this->db->join('cberp_suppliers', 'cberp_purchase_orders.csd=cberp_suppliers.supplier_id', 'left');
    //     $this->db->join('cberp_employees as created_employee', 'created_employee.id = cberp_purchase_orders.created_by', 'left');
    //     // Join cberp_employees for the `approved_by` field with an alias `approve_employee`
    //     $this->db->join('cberp_employees as approve_employee', 'approve_employee.id = cberp_purchase_orders.approved_by', 'left');
    //     $this->db->join('cberp_employees as sentby_employee', 'sentby_employee.id = cberp_purchase_orders.sent_by', 'left');
    //   //  $this->db->join('cberp_customers', 'cberp_customers.customer_id=cberp_sales_orders.csd');
    //    // $this->db->join('cberp_delivery_notes', 'cberp_delivery_notes.delnote_number=cberp_sales_orders.delnote_number', 'left');
    //     $this->db->where('cberp_purchase_receipts.purchase_id IS NULL AND cberp_purchase_orders.sent_by IS NOT NULL');


        $this->db->select('cberp_purchase_orders.sent_date, cberp_purchase_order_items.product_code, cberp_purchase_order_items.quantity AS qty, cberp_purchase_order_items.price, cberp_suppliers.name AS suppliername, cberp_suppliers.phone AS supplierphone, cberp_suppliers.address AS supplieraddress, created_employee.name AS addedname, approve_employee.name AS approvename, sentby_employee.name AS sentbyname,cberp_products.product_name as product');
        
        $this->db->from('cberp_purchase_orders');
        $this->db->join('cberp_purchase_order_items', 'cberp_purchase_order_items.purchase_number = cberp_purchase_orders.purchase_number');
        $this->db->join('cberp_purchase_receipts', 'cberp_purchase_receipts.purchase_number = cberp_purchase_orders.purchase_number', 'left');
        $this->db->join('cberp_suppliers', 'cberp_purchase_orders.customer_id = cberp_suppliers.supplier_id', 'left');
        $this->db->join('cberp_employees as created_employee', 'created_employee.id = cberp_purchase_orders.created_by', 'left');
        $this->db->join('cberp_employees as approve_employee', 'approve_employee.id = cberp_purchase_orders.approved_by', 'left');
        $this->db->join('cberp_employees as sentby_employee', 'sentby_employee.id = cberp_purchase_orders.sent_by', 'left');
        $this->db->join('cberp_products', 'cberp_products.product_code = cberp_purchase_order_items.product_code', 'left');
        
        // $this->db->where('cberp_purchase_receipts.purchase_number IS NULL', null, false); // raw condition
        // $this->db->where('cberp_purchase_orders.sent_by IS NOT NULL', null, false);       // raw condition
        
        $i = 0;

        foreach ($this->column_search_purchase as $item) // loop column
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

                if (count($this->column_search_purchase) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order_purchase[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        // else{
        //     $order = array('cberp_sales_orders.id' => 'desc');
        // }
        // $order = $this->order;
        // $this->db->order_by(key($order), $order[key($order)]);
    }


    function get_datatables_purchase($eid)
    {

        $this->_get_datatables_query_purchase($eid);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);        }  
        $query = $this->db->get();
    //    die($this->db->last_query());
      
        return $query->result();
    }

    function count_filtered_purchase($eid)
    {
        $this->_get_datatables_query_purchase($eid);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_purchase($eid)
    {
        // $this->db->select('cberp_products.pid');
        // $this->db->from($this->table);
        $this->_get_datatables_query_purchase($eid);

        //  if ($this->aauth->get_user()->loc) {
        //     $this->db->where('cberp_quotes.loc', $this->aauth->get_user()->loc);
        // }  elseif(!BDATA) { $this->db->where('cberp_quotes.loc', 0); }

        return $this->db->count_all_results();
    }



}
